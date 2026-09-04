<?php
namespace App\Services;
use App\Models\DriverProfile;
use App\Models\User;

class DriverProfileService {

  protected $user;

  public function __construct(User $user) {
    $this->user = $user;
  }
  
  public function getUnverifiedCount() {
    return $this->user->where('is_verified', false)->where('role', 'driver')->count();
  }
  
  public function getVerifiedCount() {
    return $this->user->where('is_verified', true)->where('role', 'driver')->count();
  }

  public function getUnverifiedUsers() {
    return $this->user->where('is_verified', false)->where('role', 'driver')->get();
  }

  public function getVerifiedUsersWithProfile() {
    return $this->user->where('is_verified', true)->where('role', 'driver')->with('profile')->get();
  }

}