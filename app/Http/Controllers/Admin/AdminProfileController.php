<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DriverProfile;
use App\Models\User;

class AdminProfileController extends Controller
{
    public function index($id) {
        $profile = DriverProfile::findOrFail($id);
        $user = User::findOrFail($profile->user_id);
        
        return view('admin.driver-info', compact('profile', 'user'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'profile_image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $profile = DriverProfile::findOrFail($id);

        if($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $profile->profile = "//" . $path;
        }

        $profile->save();
    }
}
