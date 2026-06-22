<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class DriverVerificationController extends Controller
{
    public function index() {
        $driver = User::where('is_verified', false)->orderBy('created_at', 'asc')->paginate(1);
        $driverCount = User::where('is_verified', false)->count();

        return view('admin.verify-driver', compact('driverCount', 'driver'));
    }
}
