<?php

namespace App\Services;

// use App\Models\DriverStatus;

class DriverAssignmentService {

  public function setDriving($driver, $status) {
    $driver->update(['is_online' => $status]);
    
    if($status) 
      $this->checkLastUpdated($driver);

    return "Success";
  }

  public function checkLastUpdated($driver) {
    if($driver->last_updated->isToday()) {
      return;
    }

    $driver->update([
      'state' => 'in_route',
      'dispatched_to' => 'none',
      'last_updated' => now()
    ]);
  }
}
