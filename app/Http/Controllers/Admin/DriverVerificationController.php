<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DriverStatus;
use App\Models\DriverProfile;
use Illuminate\Support\Facades\DB;

class DriverVerificationController extends Controller
{
    public function index(int $id) {
        $user = User::findOrFail($id);
        return view('admin.verify-driver', compact('user'));
    }

    public function create(int $id) {
        $driver = User::findOrFail($id);

        return view('admin.verify-driver', compact('driver'));
    }

    public function setDispatcher(int $id) {
        $user = User::findOrFail($id);
        
        $user->update([
            'role' => 'dispatcher',
            'is_verified' => true
        ]);
        
        return redirect()->route('fleet.management')->with('success', 'User has been approved as a Dispatcher.');
    }

    public function store(Request $request, int $id)
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
            'image_front' => 'required|image|mimes:jpeg,png,jpg|max:2048', 
            'image_side' => 'required|image|mimes:jpeg,png,jpg|max:2048', 
            'image_plate' => 'required|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $user = User::findOrFail($id);
        $validated['user_id'] = $id;

        try{
            DB::transaction(function() use ($request, $validated, $id, $user) {

                if ($request->hasFile('image_front')) {
                    $validated['image_front_path'] = $request->file('image_front')->store('jeepneys/vehicleFront', 'public/jeepney');
                }

                if ($request->hasFile('image_side')) {
                    $validated['image_side_path'] = $request->file('image_side')->store('jeepneys/vehicleSide', 'public/jeepney');
                }

                if ($request->hasFile('image_back')) {
                    $validated['image_plate_path'] = $request->file('image_plate')->store('jeepneys/vehiclePlate', 'public');
                }

                // 4. Create the record with the full, validated data including the image path
                DriverProfile::create($validated);
                DriverStatus::create([
                    'user_id' => $id
                ]);

                $user->update([
                    'is_verified' => true
                ]);
                }
            );
                    
            return redirect()->route('fleet.management')->with('success', 'Driver approved');

        } catch(\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}
