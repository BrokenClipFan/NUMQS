<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DriverStatus;
use App\Models\User;
use App\Services\DriverAssignmentService;
use App\Services\QueueService;
use App\Models\DriverProfile;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $driver = DriverStatus::where('user_id', auth()->user()->id)->first();
        $driverProfiles = DriverProfile::all();
        return view('driver.map', compact('driver', 'driverProfiles'));
    }

    public function getDrivers() {
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
    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $user = DriverStatus::where('user_id', auth()->user()->id)->firstOrFail();
        $user->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'last_updated' => now()
        ]);

        return back()->with('success', 'Location is Updated');
    }

    public function changeOnlineStatus(Request $request, DriverAssignmentService $service) {
        $request->validate([
            'is_online' => 'required|boolean'
        ]);

        $driver = DriverStatus::where('user_id', auth()->user()->id)->firstOrFail();
        
        $service->setDriving($driver, $request->is_online);
        
        $message = $request->boolean('is_online') ? "You are now online" : "Your are now offline";
        return back()->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
