<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class DriverVerificationController extends Controller
{
    public function index() {
        $driver = User::where('is_verified', false)->orderBy('created_at', 'asc')->paginate(1);
        $currentDriver = $driver->first();
        return view('admin.verify-driver', compact('driver', 'currentDriver'));
    }
}
