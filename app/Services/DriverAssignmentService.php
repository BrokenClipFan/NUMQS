<?php

namespace App\Services;
use App\Models\DriverQueue;

class DriverAssignmentService {

  public function setDriving($driver, $status) {
    $driver->update(['is_online' => $status]);
    
    if($status) {
      $this->checkLastUpdated($driver);
    } else {
      // If going offline, clean up queue and state
      $driver->update([
        'state' => 'idle',
      ]);
      app(\App\Services\QueueService::class)->removeFromQueue($driver);
    }

    return "Success";
  }

  public function checkLastUpdated($driver) {
    if($driver->last_updated && $driver->last_updated->isToday()) {
      return;
    }

    $driver->update([
      'state' => 'in_route',
      'dispatched_to' => 'none',
      'last_updated' => now()
    ]);
  }
}
