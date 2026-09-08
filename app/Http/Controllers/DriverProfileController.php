<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DriverProfile;
use Illuminate\Support\Facades\Auth;

class DriverProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();
        $driver = DriverProfile::where('user_id', $userId)->first();
        // Fetch unresolved violations for this driver's profile
        $violations = collect();
        if ($driver) {
            $violations = \App\Models\Violation::where('driver_profile_id', $driver->id)
                            ->whereNull('resolved_at')
                            ->orderBy('created_at', 'desc')
                            ->get();
        }
        return view('driver.profile', compact('driver', 'violations'));
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
    public function update(Request $request)
    {
        $id = Auth::id();
        $profile = DriverProfile::where('user_id', $id)->first();

        $request->merge([
            'emergency_name' => $request->emergency_name ? strtoupper($request->emergency_name) : null,
        ]);

        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:100',
            'emergency_name' => 'nullable|string|max:100',
            'emergency_phone' => 'nullable|string|max:100',
        ]);

        $data = array_filter($validated, function($value){ return !is_null($value) && $value !== ''; });

        $profile->update($data);
        return redirect()->back()->with('success', 'profile has updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
