<?php

namespace App\Services;
use App\Models\DriverQueue;

class DriverAssignmentService {

  public function setDriving($driver, $status, $firstDestination = null) {
    $driver->update(['is_online' => $status]);
    
    if($status) {
      $this->checkLastUpdated($driver, $firstDestination);
    } else {
      // If going offline, clean up queue and state
      $driver->update([
        'state' => 'idle',
      ]);
      app(\App\Services\QueueService::class)->removeFromQueue($driver);
    }

    return "Success";
  }

  public function checkLastUpdated($driver, $firstDestination = null) {
    if($driver->last_updated && $driver->last_updated->isToday()) {
      // If resuming a drive on the same day, PREVENT cheating by restoring them
      // to their existing route. Ignore the new destination selection entirely.
      $driver->update([
        'state' => 'in_route',
        // Preserve their existing dispatched_to and going_to!
        'last_updated' => now()
      ]);
      return;
    }

    $destination = $firstDestination ?: 'none';

    $driver->update([
      'state' => 'in_route',
      'dispatched_to' => $destination,
      'going_to' => $destination,
      'last_updated' => now()
    ]);
  }
}

