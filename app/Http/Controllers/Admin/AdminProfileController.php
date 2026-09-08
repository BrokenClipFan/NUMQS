<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DriverProfile;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class AdminProfileController extends Controller
{
    public function index(User $user) {
        $user->load('profile');
        
        return view('admin.driver-info', compact('user'));
    }

    public function update(Request $request, int $id) {
        $driverProfile = DriverProfile::where('user_id', $id)->with('user')->first();
        
        // Capitalize names and license before validation (FULL UPPERCASE)
        $request->merge([
            'first_name' => $request->first_name ? strtoupper($request->first_name) : null,
            'middle_name' => $request->middle_name ? strtoupper($request->middle_name) : null,
            'last_name' => $request->last_name ? strtoupper($request->last_name) : null,
            'emergency_name' => $request->emergency_name ? strtoupper($request->emergency_name) : null,
            'license_number' => $request->license_number ? strtoupper($request->license_number) : null,
        ]);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',

            'phone' => 'required|string|max:20',
            'email' => 'nullable|string|max:50',
            'address' => 'required|string|max:255',
            'emergency_name' => 'required|string|max:255',
            'emergency_phone' => 'required|string|max:20',

            'birthdate' => 'required|date',

            'license_number' => 'required|string|max:255',
            'plate_number' => 'required|string|max:255',
            'image_front' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
            'image_side' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
            'image_plate' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 

            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $validated = array_filter($validated, fn($value) => !is_null($value));

        if($validated['license_number'] !== $driverProfile->license_number) {
            if(DriverProfile::where('license_number', $validated['license_number'])->exists()) {
                return back()->with('warning', 'License Number is taken');
            }
        }

        if($validated['plate_number'] !== $driverProfile->plate_number) {
            if(DriverProfile::where('plate_number', $validated['plate_number'])->exists()) {
                return back()->with('warning', 'Plate Number is taken');
            }
        }

        if($request->hasFile('image_front')) {
            if($driverProfile->image_front_path &&
                Storage::disk('public')->exists($driverProfile->image_front_path)){
                Storage::disk('public')->delete($driverProfile->image_front_path);
            }

            $validated['image_front_path'] = $request->file('image_front')->store('jeepneys/vehicleFront', 'public');
        }

        if($request->hasFile('image_side')) {
            if($driverProfile->image_side_path &&
                Storage::disk('public')->exists($driverProfile->image_side_path)){
                Storage::disk('public')->delete($driverProfile->image_side_path);
            }

            $validated['image_side_path'] = $request->file('image_side')->store('jeepneys/vehicleSide', 'public');
        }

        if($request->hasFile('image_plate')) {
            if($driverProfile->image_plate_path &&
                Storage::disk('public')->exists($driverProfile->image_plate_path)){
                Storage::disk('public')->delete($driverProfile->image_plate_path);
            }

            $validated['image_plate_path'] = $request->file('image_plate')->store('jeepneys/vehiclePlate', 'public');
        }
        
        if($request->hasFile('profile_image')) {
            if($driverProfile->profile &&
                Storage::disk('public')->exists($driverProfile->profile)){
                Storage::disk('public')->delete($driverProfile->profile);
            }

            $validated['image_profile_path'] = $request->file('profile_image')->store('profiles', 'public');
        }

        if(!empty($validated['email'])){
            $driverProfile->user->update([
                'email' => $validated['email']
            ]);
        }

        $driverProfile->update($validated);

        return redirect()->back()->with('success', 'Successfully Updated Driver Info');
    }
}
