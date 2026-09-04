<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DriverProfileService;
use App\Models\User;
use App\Models\Violation;

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
        $violations = Violation::where('resolved_at', null)->with('profile')->get();

        return view('admin.fleet-management', compact(
                                                    'unverifiedCount', 
                                                    'verifiedCount', 
                                                    'unVerifiedUsers', 
                                                    'verifiedUsers',
                                                    'violations'
                                                ));
    }

    public function queues() {
        $activeQueues = \App\Models\DriverQueue::with(['profile.user', 'terminal'])->orderBy('terminal_id')->orderBy('position')->get();
        return view('admin.queues', compact('activeQueues'));
    }

    public function resolved() {
        $violations = Violation::whereNotNull('resolved_at')->with('profile')->get();
        return view('admin.resolved-violations', compact('violations'));
    }

    public function reorder(\Illuminate\Http\Request $request) {
        $orders = $request->input('orders');
        if (is_array($orders)) {
            foreach ($orders as $index => $id) {
                \App\Models\DriverQueue::where('id', $id)->update(['position' => $index + 1]);
            }
        }
        return response()->json(['success' => true, 'message' => 'Queue reordered successfully']);
    }
}
