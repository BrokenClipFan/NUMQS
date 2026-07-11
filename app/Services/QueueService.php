<?php

namespace App\Services;
use App\Models\DriverQueue;
use App\Models\DriverStatus;

class QueueService {

  public function addToQueue($driver, $terminal) {
    $position = (DriverQueue::where('terminal_id', $terminal->id)->max('position') ?? 0) + 1;

    DriverQueue::create([
      'driver_profile_id' => $driver->user_id,
      'terminal_id' => $terminal->id,
      'queued_at' => now(),
      'position' => $position,
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
    return DriverQueue::where('terminal_id', $wifi->id)->orderBy('position')->first();
  }

  public function getFillingAtMinutes($driver, $wifi) {
    $fillingAt = $this->getFillingAt($driver);
    
    $minutes = now()->diffInMinutes($fillingAt ,false);
    
    return abs($minutes);
  }

  public function getQueueWithProfiles() {
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
    DriverStatus::where('user_id', $driver->user_id)->update([
      'queued_in' => null,
    ]);
    return DriverQueue::where('driver_profile_id', $driver->user_id)->delete();
  }

  public function setFillingUp($driver) {
    $queuedDriver = DriverQueue::where('driver_profile_id', $driver->user_id)->first();
    $queuedDriver->update([
      'filling_at' => now()
    ]);
  }
}