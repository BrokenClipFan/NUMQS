<?php

namespace App\Services;
use App\Models\DriverQueue;
use App\Models\DriverStatus;

class QueueService {

  public function addToQueue($driver, $terminal) {
    // Prevent duplicate entries for the same driver!
    if (DriverQueue::where('driver_profile_id', $driver->user_id)->exists()) {
        return; 
    }

    $position = (DriverQueue::where('terminal_id', $terminal->id)->max('position') ?? 0) + 1;

    DriverQueue::create([
      'driver_profile_id' => $driver->user_id,
      'terminal_id' => $terminal->id,
      'queued_at' => now(),
      'position' => $position,
      'filling_at' => $position == 1 ? now() : null // Auto-start if first
    ]);

    DriverStatus::where('user_id', $driver->user_id)->update([
      'queued_in' => $terminal->name,
    ]);
  }

  public function getFillingAt($driver) {
    return DriverQueue::where('driver_profile_id', $driver->user_id)->value('filling_at');
  }

  public function getPosition($driver) {
    return DriverQueue::where('driver_profile_id', $driver->user_id)->value('position');
  }

  public function getQueuedAt($driver) {
    $queued_at = DriverQueue::where([
      "driver_profile_id" => $driver->user_id,
    ])->value('queued_at');

    return $queued_at;
  }

  public function getAllQueues() {
    return DriverQueue::all();
  }

  public function getTopPosition($wifi) {
    return DriverQueue::where('terminal_id', $wifi->id)->orderBy('position', 'asc')->first();
  }

  public function getFillingAtMinutes($driver, $wifi) {
    $fillingAt = $this->getFillingAt($driver);
    
    $minutes = now()->diffInMinutes($fillingAt ,false);
    
    return abs($minutes);
  }

  public function pruneDisconnectedDrivers() {
    $threshold = now()->subMinutes(5);
    $disconnectedDrivers = DriverStatus::where('is_online', true)
                            ->where('last_updated', '<', $threshold)
                            ->get();

    foreach ($disconnectedDrivers as $driver) {
        $driver->update([
            'is_online' => false,
            'state' => 'idle'
        ]);
        $this->removeFromQueue($driver);
    }
  }

  public function getQueueWithProfiles() {
    $this->pruneDisconnectedDrivers();

    $queues = DriverQueue::with('profile')
    ->orderBy('position', 'asc')
    ->get();
    
    return $queues;
  }

  public function allWithDetails() {
    $queues = DriverQueue::with('profile')->with('status')
    ->orderBy('position', 'asc')
    ->get();
    
    return $queues;
  }
  
  public function removeFromQueue($driver) {
    // Get their terminal before deleting
    $queuedDriver = DriverQueue::where('driver_profile_id', $driver->user_id)->first();
    $terminalId = $queuedDriver ? $queuedDriver->terminal_id : null;

    DriverStatus::where('user_id', $driver->user_id)->update([
      'queued_in' => null,
    ]);
    $deleted = DriverQueue::where('driver_profile_id', $driver->user_id)->delete();

    // Promote the next driver in the queue to start filling up
    if ($terminalId) {
        $nextTop = DriverQueue::where('terminal_id', $terminalId)->orderBy('position', 'asc')->first();
        if ($nextTop && is_null($nextTop->filling_at)) {
            $nextTop->update(['filling_at' => now()]);
        }
    }

    return $deleted;
  }

  public function setFillingUp($driver) {
    $queuedDriver = DriverQueue::where('driver_profile_id', $driver->user_id)->first();
    $queuedDriver->update([
      'filling_at' => now()
    ]);
  }
}