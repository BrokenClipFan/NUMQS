<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApprovalController;
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

Route::get('/fake/auth/callback', function () {
    // --- START OF MOCK DATA ---
    $facebookUser = (object) [
        'id'           => '1234567890987654', 
        'name'         => 'John Doe',
        'email'        => 'johndoe@example.com',
        'token'        => 'fake-easy-access-token-123456',
        'refreshToken' => 'fake-refresh-token-123456',
        'verified' => false
    ];
    // --- END OF MOCK DATA ---

    // Fixed: Using object property syntax (->) instead of array syntax
    $user = User::where('facebook_id', $facebookUser->id)->first();
 
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
            'password' => Hash::make(Str::random(24)),
            'verified' => $facebookUser->verified
        ]);
    }
 
    Auth::login($user);
 
    return redirect('/dashboard');
})->name('auth.callback');

require __DIR__.'/auth.php';


// Route::get('/auth/redirect', function () {
//     return Socialite::driver('facebook')->setScopes(['public_profile'])->redirect();
// })->name('facebook.redirect');
 
// Route::get('/auth/callback', function () {
//     $facebookUser = Socialite::driver('facebook')->user();
 
//     // $user->token
//     $user = User::where('facebook_id', $facebookUser->id)->first();
//     dd($user);
//     if ($user) {
//         $user->update([
//             'facebook_token' => $facebookUser->token,
//             'facebook_refresh_token' => $facebookUser->refreshToken,
//         ]);
//     } else {
//         $user = User::create([
//             'name' => $facebookUser->name,
//             'email' => $facebookUser->email,
//             'facebook_id' => $facebookUser->id,
//             'facebook_token' => $facebookUser->token,
//             'facebook_refresh_token' => $facebookUser->refreshToken,
//         ]);
//     }
 
//     Auth::login($user);
 
//     return redirect('/dashboard');
// });
