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
    $destination = $firstDestination ?: 'none';

    if($driver->last_updated && $driver->last_updated->isToday()) {
      // Even if already updated today, if they are starting a new drive we should apply their chosen destination.
      $driver->update([
        'state' => 'in_route',
        'dispatched_to' => $destination,
        'going_to' => $destination,
        'last_updated' => now()
      ]);
      return;
    }

    $driver->update([
      'state' => 'in_route',
      'dispatched_to' => $destination,
      'going_to' => $destination,
      'last_updated' => now()
    ]);
  }
}

