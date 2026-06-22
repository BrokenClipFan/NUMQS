<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\Admin\DriverVerificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

Route::get('/driver/profile', function () {
    return view('driver.profile');
});
Route::get('/dispatcher/queue', function () {
    return view('dispatcher.queue-board');
});

// Admin Area
Route::get('/verify-driver', [DriverVerificationController::class, 'index'])->name('verify.driver');

Route::get('/fleet-management', function() {
    return view('admin.fleet-management');
})->name('fleet.management');

Route::get('/driver-info', function() {
    return view('admin.driver-info');
});

Route::get('/admin/sandbox', function () {
    return view('admin.sandbox');
});

Route::middleware('auth')->group(function () {

    Route::get('/pending-approval', function() {
        return view('auth.pending-approval');
    })->name('pending.approval');
    
    Route::middleware('verified')->group(function() {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        
        Route::get('/driver/profile', function () {
            return view('driver.profile');
        })->name('profile');

        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        Route::get('/', function () {
            return view('driver.map');
        })->name('driver.map');
    });
});

Route::get('/auth/redirect', function () {
    return Socialite::driver('facebook')->setScopes(['public_profile'])->redirect();
})->name('facebook.redirect');
 
Route::get('/auth/callback', function () {

    $facebookUser = Socialite::driver('facebook')
        ->stateless()
        ->user();

    $user = User::where('facebook_id', $facebookUser->id)->first();
    if ($user) {
        $user->update([
            'facebook_token' => $facebookUser->token,
        ]);
    } else {
        $user = User::create([
            'name' => $facebookUser->name,
            'email' => $facebookUser->email,
            'facebook_id' => $facebookUser->id,
            'facebook_token' => $facebookUser->token,
            'password' => Hash::make(Str::random(32))
        ]);
    }

    Auth::login($user);

    return redirect('/dashboard');
});
require __DIR__.'/auth.php';
