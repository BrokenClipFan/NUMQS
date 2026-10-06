<?php

use App\Http\Controllers\Admin\DriverVerificationController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApprovalController;
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


Route::middleware('auth')->group(function () {
    
    Route::get('/check-status', function() {
        $user = auth()->user();
        if (!$user->is_verified) {
            return redirect()->route('pending.approval');
        }
        
        if ($user->role === 'dispatcher') {
            return redirect()->route('dispatcher.queue');
        }
        
        return redirect()->route('driver.map');
    })->name('check.status');
    
    Route::get('/dispatcher/queue', function () {
        if(auth()->user()->role !== 'dispatcher') abort(403);
        $activeQueues = \App\Models\DriverQueue::with(['profile.user', 'terminal'])->orderBy('terminal_id')->orderBy('position')->get();
        return view('dispatcher.queue-board', compact('activeQueues'));
    })->name('dispatcher.queue');

    Route::post('/dispatcher/queues/reorder', function(\Illuminate\Http\Request $request) {
        if(auth()->user()->role !== 'dispatcher') abort(403);
        return app(\App\Http\Controllers\DriverFleet::class)->reorder($request);
    })->name('dispatcher.queues.reorder');
    
    Route::get('/pending-approval', function() {
        return view('auth.pending-approval');
    })->name('pending.approval');
    
    Route::middleware('verified')->group(function() {
        Route::get('/profile', [DriverProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile/update', [DriverProfileController::class, 'update'])->name('profile.update');

        // Route::post('/add/queue',[NagaQueueController::class, 'store'])->name('add.to.queue');
        
        Route::get('/', [DriverController::class, 'index'])->name('driver.map');
        // Route::get('/', function() {
        //     return view('debug-gps');
        // })->name('driver.map');

        Route::get('/queue-lineup', function() {
            $driver = \App\Models\DriverStatus::where('user_id', auth()->id())->first();
            return view('driver.queue', compact('driver'));
        })->name('driver.queue');

        Route::get('/drivers', [DriverController::class, 'getDrivers']);
        Route::get('/landmarks', [\App\Http\Controllers\MapLandmarkController::class, 'getLandmarks']);
        Route::post('/driver/location/update', [DriverController::class, 'updateLocation'])->name('location.update');
        Route::post('/driver/online/update', [DriverController::class, 'changeOnlineStatus'])->name('online.update');

        Route::get('/queue', [DriverController::class, 'getAllQueues'])->name('get.queues');
        
        });
        
    Route::middleware('is_admin')->group(function() {
            
        
        Route::get('/admin/landmarks', [\App\Http\Controllers\MapLandmarkController::class, 'index'])->name('admin.landmarks.index');
        Route::post('/admin/landmarks', [\App\Http\Controllers\MapLandmarkController::class, 'store'])->name('admin.landmarks.store');
        Route::put('/admin/landmarks/{id}', [\App\Http\Controllers\MapLandmarkController::class, 'update'])->name('admin.landmarks.update');
        Route::put('/admin/landmarks/{id}', [\App\Http\Controllers\MapLandmarkController::class, 'update'])->name('admin.landmarks.update');
        Route::delete('/admin/landmarks/{id}', [\App\Http\Controllers\MapLandmarkController::class, 'destroy'])->name('admin.landmarks.destroy');

        Route::delete('/admin/delete/{id}', [DriverController::class, 'destroy'])->name('admin.drivers.destroy');
        
        Route::delete('/admin/reject/{id}', [DriverController::class, 'rejected'])->name('admin.verify.reject');
        
        Route::delete('/admin/violation/resolve/{id}', [DriverController::class, 'resolve'])->name('admin.violations.resolve');
        Route::get('/admin/violations/resolved', [DriverFleet::class, 'resolved'])->name('admin.violations.resolved');
        Route::get('/admin/queues', [DriverFleet::class, 'queues'])->name('admin.queues');
        Route::post('/admin/queues/reorder', [DriverFleet::class, 'reorder'])->name('admin.queues.reorder');
        Route::get('/admin/dispatchers', [DriverFleet::class, 'dispatchers'])->name('admin.dispatchers');
        Route::delete('/admin/dispatchers/{id}', [DriverFleet::class, 'revokeDispatcher'])->name('admin.dispatchers.revoke');

        Route::get('/verify/driver/{id}', [DriverVerificationController::class, 'index'])->name('verify.driver');
        Route::post('/verify/dispatcher/{id}', [DriverVerificationController::class, 'setDispatcher'])->name('admin.verify.dispatcher');

        Route::get('admin/dashboard', [DriverFleet::class, 'index'])->name('fleet.management');
        Route::get('/admin/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('admin.settings');
        Route::post('/admin/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('admin.settings.update');
        Route::post('/admin/terminals/{id}', [\App\Http\Controllers\Admin\SettingsController::class, 'updateTerminal'])->name('admin.terminals.update');
        Route::post('/admin/terminals/{id}', [\App\Http\Controllers\Admin\SettingsController::class, 'updateTerminal'])->name('admin.terminals.update');

        Route::get('/admin/view/{user}', [AdminProfileController::class, 'index'])->name('view.driver');

        Route::get('/admin/sandbox', function () {
            return view('admin.sandbox');
        });

        Route::put('/admin/udpate/{id}', [AdminProfileController::class, 'update'])->name('admin.drivers.update');

        Route::put('/admin/profile/{id}/store', [DriverVerificationController::class, 'store'])->name('driver.store');
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

    return redirect('/profile');
});
require __DIR__.'/auth.php';


Route::get('/debug-gps', function () {
    $terminals = \App\Models\Terminal::all();
    $routePath = \App\Models\Route::first();
    return view('debug-gps', compact('terminals', 'routePath'));
});

Route::post('/debug-gps/simulate', function (\Illuminate\Http\Request $request) {
    $offlineBots = $request->input('offline_bots', []);
    
    $drivers = \App\Models\DriverStatus::where('user_id', '!=', auth()->id())
        ->whereIn('user_id', [2, 3, 4, 5, 6, 7]) // Pick a few dummy drivers
        ->get();
        
    $route = \App\Models\Route::first();
    if (!$route || !$route->path) return response()->json(['success' => false]);
    $coords = json_decode($route->path, true);
    if (empty($coords)) return response()->json(['success' => false]);

    foreach ($drivers as $driver) {
        if (!$driver->is_online) {
            $driver->update([
                'is_online' => true,
                'state' => 'in_route',
                'dispatched_to' => rand(0, 1) ? 'Naga' : 'Uling',
                'going_to' => rand(0, 1) ? 'Naga' : 'Uling',
                'waypoint_index' => rand(0, count($coords) - 1)
            ]);
        } else if ($driver->state === 'queued') {
            if (rand(1, 5) === 1) { 
                app(\App\Services\QueueService::class)->removeFromQueue($driver);
                $nextDest = $driver->queued_in === 'Naga' ? 'Uling' : 'Naga';
                $driver->update([
                    'state' => 'in_route',
                    'dispatched_to' => $nextDest,
                    'going_to' => $nextDest,
                    'wifi_bssid' => null
                ]);
            }
        } else {
            $idx = (int)$driver->waypoint_index;
            $speed = rand(2, 6);
            if ($driver->dispatched_to === 'Uling') {
                $idx += $speed;
                if ($idx >= count($coords) - 1) {
                    $idx = count($coords) - 1;
                    $terminal = \App\Models\Terminal::where('name', 'Uling')->first();
                    $driver->update(['state' => 'queued', 'queued_in' => 'Uling', 'wifi_bssid' => $terminal->bssid]);
                    if ($terminal) app(\App\Services\QueueService::class)->addToQueue($driver, $terminal);
                }
            } else {
                $idx -= $speed;
                if ($idx <= 0) {
                    $idx = 0;
                    $terminal = \App\Models\Terminal::where('name', 'Naga')->first();
                    $driver->update(['state' => 'queued', 'queued_in' => 'Naga', 'wifi_bssid' => $terminal->bssid]);
                    if ($terminal) app(\App\Services\QueueService::class)->addToQueue($driver, $terminal);
                }
            }
            
            if (isset($coords[$idx])) {
                $updateData = [
                    'waypoint_index' => $idx,
                    'latitude' => $coords[$idx]['lat'],
                    'longitude' => $coords[$idx]['lng'],
                ];
                
                $isJammed = in_array($driver->user_id, $offlineBots);
                
                // If the bot is completely jammed via UI, let their timestamp rot.
                // Otherwise, check normal dead zone logic.
                if (!$isJammed) {
                    if ($idx < 217 || $idx >= count($coords) - 1) {
                        $updateData['last_updated'] = now();
                    }
                }
                
                $driver->update($updateData);
            }
        }
    }
    return response()->json(['success' => true]);
});

Route::post('/debug-gps/bot-cheat', function (\Illuminate\Http\Request $request) {
    $driverId = $request->input('driver_id');
    $driver = \App\Models\DriverStatus::where('user_id', $driverId)->first();
    if (!$driver) return response()->json(['success' => false]);
    
    $service = app(\App\Services\DriverAssignmentService::class);
    
    // Simulate the bot attempting to cheat:
    // 1. Go offline to clear their queue state
    $service->setDriving($driver, false);
    
    // 2. Go online immediately and try to choose the OPPOSITE of what they were doing
    $cheatDest = $driver->dispatched_to === 'Naga' ? 'Uling' : 'Naga';
    $service->setDriving($driver, true, $cheatDest);
    
    return response()->json([
        'success' => true, 
        'driver' => $driver->user_id,
        'attempted_dest' => $cheatDest,
        'actual_dest' => $driver->fresh()->dispatched_to // Should remain the original due to our patch!
    ]);
});


