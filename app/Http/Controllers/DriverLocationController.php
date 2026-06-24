<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DriverStatus;

class DriverLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

    public function test(Request $request) {
        // Access the data sent from JS using the -> operator
        $firstName = $request->input('first_name');
        
        // Perform logic...
        
        // Return a JSON response back to JavaScript
        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated for ' . $firstName
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateLocation(Request $request, string $id)
    {
        $request->validate([
            'latitude' => 'required|decimal',
            'longitude' => 'required|decimal',
        ]);

        $user = DriverStatus::where('user_id', $id)->firstOrFail();
        $user->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'last_updated' => now()
        ]);
        return back()->with('success', 'Location updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
