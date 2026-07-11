<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\Admin\DriverVerificationController;
use App\Http\Controllers\DriverProfileController;
use App\Http\Controllers\NagaQueueController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\DriverFleet;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

Route::get('/driver/profile', function () {
    return view('driver.profile');
});
Route::get('/dispatcher/queue', function () {
    return view('dispatcher.queue-board');
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
            return view('driver.profile');
        })->name('dashboard');

        // Route::post('/add/queue',[NagaQueueController::class, 'store'])->name('add.to.queue');
        
        Route::get('/', [DriverController::class, 'index'])->name('driver.map');
        Route::get('/drivers', [DriverController::class, 'getDrivers']);
        Route::post('/driver/location/update', [DriverController::class, 'updateLocation'])->name('location.update');
        Route::post('/driver/online/update', [DriverController::class, 'changeOnlineStatus'])->name('online.update');

        Route::get('/queue', [DriverController::class, 'getAllQueues'])->name('get.queues');

    });

    Route::middleware('is_admin')->group(function() {
        Route::get('/verify-driver', [DriverVerificationController::class, 'index'])->name('verify.driver');

        Route::get('admin/drivers', [DriverFleet::class, 'index'])->name('fleet.management');

        Route::get('/driver-info', function() {
            return view('admin.driver-info');
        });

        Route::get('/admin/sandbox', function () {
            return view('admin.sandbox');
        });

        Route::post('/admin/profile/{id}/store', [DriverVerificationController::class, 'store'])->name('driver.store');
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
            'avatar' => $facebookUser->avatar,
            'password' => Hash::make(Str::random(32))
        ]);
    }

    Auth::login($user);

    return redirect('/dashboard');
});
require __DIR__.'/auth.php';
