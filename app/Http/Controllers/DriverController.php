<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DriverStatus;
use App\Models\User;
use App\Services\DriverAssignmentService;
use App\Services\QueueService;
use App\Models\DriverProfile;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role === 'dispatcher') {
            return redirect()->route('dispatcher.queue');
        }

        $driver = DriverStatus::where('user_id', Auth::id())->first();
        $driverProfiles = DriverProfile::all();
        $routePath = \App\Models\Route::first();
        $terminals = \App\Models\Terminal::all();
        return view('driver.map', compact('driver', 'driverProfiles', 'routePath', 'terminals'));
    }

    public function getDrivers(\App\Services\QueueService $queueService) {
        // Lazily clean up drivers who haven't updated in 5 minutes (or 1 hour if in_route for dead zones)
        $queueService->pruneDisconnectedDrivers();

        $drivers = User::whereHas('status', function ($query) {
            $query->where('is_online', true);
        })
        ->with(['profile', 'status'])
        ->get();
        
        $route = \App\Models\Route::first();
        $coords = $route ? json_decode($route->path, true) : [];
        $now = now();

        foreach ($drivers as $driver) {
            $status = $driver->status;
            // Extrapolate and protect any in_route driver who loses connection anywhere on the route
            if ($status && $status->state === 'in_route' && $status->last_updated) {
                $secondsStale = $status->last_updated->diffInSeconds($now);
                if ($secondsStale > 15) {
                    $status->is_stale = true; // Frontend will show "Lost Connection" badge
                    
                    if (count($coords) > 0 && $status->waypoint_index !== null) {
                        // Extrapolate conceptually: 1 waypoint per 5 seconds (very slow visual crawl)
                        $pointsToMove = floor($secondsStale / 5);
                        $idx = (int)$status->waypoint_index;
                        
                        if ($status->going_to === 'Uling') {
                            $idx += $pointsToMove;
                            if ($idx >= count($coords) - 1) $idx = count($coords) - 1;
                        } else {
                            $idx -= $pointsToMove;
                            if ($idx <= 0) $idx = 0;
                        }
                        
                        $status->latitude = $coords[$idx]['lat'] ?? $status->latitude;
                        $status->longitude = $coords[$idx]['lng'] ?? $status->longitude;
                    }
                }
            }
        }

        return $drivers;
    }

    public function getQueues(QueueService $queueService) {
        $queues = $queueService->getQueueWithProfiles();
        return $queues;
    }

    public function getAllQueues(QueueService $queueService) {
        $queues = $queueService->allWithDetails();
        return $queues;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateLocation(Request $request, \App\Services\QueueService $queueService, \App\Services\TerminalService $terminalService, \App\Services\ViolationService $violationService)
    {
        $request->validate([
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'wifi_bssid' => 'nullable|string'
        ]);

        $user = DriverStatus::where('user_id', Auth::id())->firstOrFail();
        
        $oldBssid = $user->wifi_bssid;
        $newBssid = $request->input('wifi_bssid', $oldBssid);
        
        $latitude = $request->latitude;
        $longitude = $request->longitude;

        // If they connected to a terminal, snap their location to the terminal coords! (Offline GPS fallback)
        $snappedTerminal = null;
        if ($newBssid) {
            $snappedTerminal = $terminalService->getTerminalByBssid($newBssid);
            if ($snappedTerminal) {
                $route = \App\Models\Route::first();
                if ($route && $route->path) {
                    $coords = json_decode($route->path, true);
                    if (!empty($coords)) {
                        if (strtolower($snappedTerminal->name) === 'naga') {
                            $latitude = $coords[0]['lat'];
                            $longitude = $coords[0]['lng'];
                        } elseif (strtolower($snappedTerminal->name) === 'uling') {
                            $last = end($coords);
                            $latitude = $last['lat'];
                            $longitude = $last['lng'];
                        }
                    }
                }
            }
        }
        
        if (is_null($latitude) || is_null($longitude)) {
            $latitude = $user->latitude;
            $longitude = $user->longitude;
        }

        $updateData = [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'wifi_bssid' => $newBssid,
            'last_updated' => now()
        ];

        // Ensure waypoint_index stays accurate for extrapolation
        $route = \App\Models\Route::first();
        if ($route && $route->path) {
            $coords = json_decode($route->path, true);
            if (!empty($coords)) {
                if ($snappedTerminal && strtolower($snappedTerminal->name) === 'naga') {
                    $updateData['waypoint_index'] = 0;
                } else if ($snappedTerminal && strtolower($snappedTerminal->name) === 'uling') {
                    $updateData['waypoint_index'] = count($coords) - 1;
                } else if ($latitude !== null && $longitude !== null) {
                    $minDist = PHP_FLOAT_MAX;
                    $bestIdx = null;
                    foreach ($coords as $idx => $coord) {
                        $dist = pow($coord['lat'] - $latitude, 2) + pow($coord['lng'] - $longitude, 2);
                        if ($dist < $minDist) {
                            $minDist = $dist;
                            $bestIdx = $idx;
                        }
                    }
                    if ($bestIdx !== null) {
                        $updateData['waypoint_index'] = $bestIdx;
                    }
                }
            }
        }

        $user->update($updateData);

                // 1. Process LEAVING a terminal queue (Only if BSSID actually changes)
        if ($oldBssid !== $newBssid && $oldBssid && $user->state === 'queued') {
            $oldTerminal = $terminalService->getTerminalByBssid($oldBssid);
            if ($oldTerminal) {
                $nextTerminal = $oldTerminal->name == "Uling" ? "Naga" : "Uling";
                $user->update([
                    'dispatched_to' => $nextTerminal,
                    'going_to' => $nextTerminal,
                    'state' => 'in_route'
                ]);
                $queueService->removeFromQueue($user);
            }
        }

        // 2. Process ENTERING a terminal queue (Extremely Performant)
        // Only run the database query if they have a Wi-Fi connection AND are NOT in a queue yet!
        $violation = null;
        if ($newBssid && $user->state !== 'queued') {
            $newTerminal = $terminalService->getTerminalByBssid($newBssid);
            if ($newTerminal) {
                // VIOLATION CHECK: Did they enter a terminal they weren't supposed to?
                if ($user->dispatched_to && $user->dispatched_to !== $newTerminal->name) {
                    $violation = $violationService->createViolation(
                        $user->user_id, // The migration foreign key explicitly points to users(id)
                        \App\Services\ViolationService::TYPE_UNAUTHORIZED_TERMINAL,
                        'Bypassed Route / Wrong Terminal',
                        $newTerminal->name,
                        ['expected_terminal' => $user->dispatched_to, 'actual_terminal' => $newTerminal->name],
                        'high'
                    );
                }
                
                $user->update(['state' => 'queued', 'queued_in' => $newTerminal->name]);
                $queueService->addToQueue($user, $newTerminal);
            }
        }

        if ($request->expectsJson() || $request->isJson() || $request->ajax()) {
            // Prevent toast spam: only notify the frontend if this is a brand new violation
            $shouldNotify = $violation && $violation->wasRecentlyCreated;
            
            return response()->json([
                'success' => true,
                'violation' => $shouldNotify ? $violation : null
            ]);
        }

        return back()->with('success', 'Location is Updated');
    }

    public function changeOnlineStatus(Request $request, DriverAssignmentService $service) {
        $request->validate([
            'is_online' => 'required|boolean',
            'first_destination' => 'nullable|string'
        ]);

        $driver = DriverStatus::where('user_id', Auth::id())->firstOrFail();
        
        $service->setDriving($driver, $request->boolean('is_online'), $request->input('first_destination'));
        
        $message = $request->boolean('is_online') ? "You are now online" : "You are now offline";
        return redirect()->route('driver.map')->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::findOrFail($id)->delete();

        return redirect()->route('fleet.management')->with('success', 'Driver account and all historical log parameters have been permanently removed.');
    }

    public function rejected(int $id) {
        User::findOrFail($id)->delete();

        return redirect()->route('fleet.management')->with('success', 'Driver account has been Rejected');
    }

    public function resolve(int $id, \App\Services\ViolationService $violationService) {
        return $violationService->resolve($id);
    }
}



