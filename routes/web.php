<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('driver.map');
});
Route::get('/driver/profile', function () {
    return view('driver.profile');
});
Route::get('/dispatcher/queue', function () {
    return view('dispatcher.queue-board');
});
Route::get('/admin/sandbox', function () {
    return view('admin.sandbox');
});

Route::get('/dashboard', function () {
    return view('driver.profile');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard/breeze', function () {
    return view('dashboard');
})->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/auth/redirect', function () {
    return Socialite::driver('facebook')->setScopes(['public_profile'])->redirect();
})->name('facebook.redirect');
 
Route::get('/auth/callback', function () {
    $facebookUser = Socialite::driver('facebook')->user();
 
    // $user->token
    $user = User::where('facebook_id', $facebookUser->id)->first();
    dd($user);
    if ($user) {
        $user->update([
            'facebook_token' => $facebookUser->token,
            'facebook_refresh_token' => $facebookUser->refreshToken,
        ]);
    } else {
        $user = User::create([
            'name' => $facebookUser->name,
            'email' => $facebookUser->email,
            'facebook_id' => $facebookUser->id,
            'facebook_token' => $facebookUser->token,
            'facebook_refresh_token' => $facebookUser->refreshToken,
        ]);
    }
 
    Auth::login($user);
 
    return redirect('/dashboard');
});

require __DIR__.'/auth.php';
