<?php

namespace App\Http\Controllers;
use App\Models\DriverProfile;
use Illuminate\Http\Request;
use App\Models\User;

class DriverProfileController extends Controller
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
    public function store(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',

            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'emergency_name' => 'required|string|max:255',
            'emergency_phone' => 'required|string|max:20',

            'birthdate' => 'required|date',

            'license_number' => 'required|string|max:255|unique:driver_profiles,license_number',
            'plate_number' => 'required|string|max:255|unique:driver_profiles,license_number',
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'is_verified' => true
        ]);
        $validated['user_id'] = $id;

        DriverProfile::create($validated);

        return redirect()->route('verify.driver', [
            'page' => request('page', 1)
        ])->with('success', 'Driver approved');
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
