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
        return view('driver.map', compact('driver', 'driverProfiles', 'routePath'));
    }

    public function getDrivers(\App\Services\QueueService $queueService) {
        // Lazily clean up drivers who haven't updated in 5 minutes
        $queueService->pruneDisconnectedDrivers();

        $drivers = User::whereHas('status', function ($query) {
            $query->where('is_online', true);
        })
        ->with(['profile', 'status'])
        ->get();

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

        $user->update([
            'latitude' => $latitude,
            'longitude' => $longitude,
            'wifi_bssid' => $newBssid,
            'last_updated' => now()
        ]);

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
        if ($newBssid && $user->state !== 'queued') {
            $newTerminal = $terminalService->getTerminalByBssid($newBssid);
            if ($newTerminal) {
                // VIOLATION CHECK: Did they enter a terminal they weren't supposed to?
                if ($user->dispatched_to && $user->dispatched_to !== $newTerminal->name) {
                    $violationService->createViolation(
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



