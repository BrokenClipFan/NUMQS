<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DriverProfileService;
use App\Models\User;

class DriverFleet extends Controller
{
    protected $profileService;

    public function __construct(DriverProfileService $profileService) {
        $this->profileService = $profileService;
    }

    public function index() {
        $unverifiedCount = $this->profileService->getUnverifiedCount();
        $verifiedCount = $this->profileService->getVerifiedCount();
        $unVerifiedUsers = $this->profileService->getUnverifiedUsers();
        $verifiedUsers = $this->profileService->getVerifiedUsersWithProfile();

        return view('admin.fleet-management', compact(
                                                    'unverifiedCount', 
                                                    'verifiedCount', 
                                                    'unVerifiedUsers', 
                                                    'verifiedUsers',
                                                ));
    }

}
