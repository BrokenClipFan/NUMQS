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
    public function index() {
        $driver = User::where('is_verified', false)->orderBy('created_at', 'asc')->paginate(1);
        $currentDriver = $driver->first();
        return view('admin.verify-driver', compact('driver', 'currentDriver'));
    }

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
        $validated['user_id'] = $id;

        try{
            DB::transaction(function() use ($validated, $id, $user) {
                DriverProfile::create($validated);
                DriverStatus::create([
                    'user_id' => $id
                ]);

                $user->update([
                    'is_verified' => true
                ]);
                }
            );
                    
            return redirect()->route('verify.driver', [
                'page' => request('page', 1)
            ])->with('success', 'Driver approved');

        } catch(\Exception $e) {
            return redirect()->route('verify.driver', [
                'page' => request('page', 1)
            ])->with('error', $e->getMessage());
        }
    }
}
