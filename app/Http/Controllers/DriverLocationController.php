<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DriverStatus;
use App\Models\User;

class DriverLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $driver = DriverStatus::where('user_id', auth()->user()->id)->first();
        return view('driver.map', compact('driver'));
    }

    public function getDrivers() {
        $drivers = User::whereHas('status', function ($query) {
        $query->where('is_online', true);
        })
        ->with(['profile', 'status'])
        ->get();

        return $drivers;
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

    public function changeOnlineStatus(Request $request) {
        $request->validate([
            'is_online' => 'required|boolean'
        ]);

        $driver = DriverStatus::where('user_id', auth()->user()->id);
        $driver->update([
            'is_online' => $request->is_online
        ]);

        if($request->is_online)
            return back()->with('success', "You are now online");
        else
            return back()->with('success', "Your are now offline");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
