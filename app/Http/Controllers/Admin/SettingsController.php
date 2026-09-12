<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        $queueTimerMinutes = Setting::getValue('queue_timer_minutes', 15);
        $terminals = \App\Models\Terminal::all();
        return view('admin.settings', compact('queueTimerMinutes', 'terminals'));
    }

    public function updateTerminal(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bssid' => 'required|string|max:255',
        ]);

        $terminal = \App\Models\Terminal::findOrFail($id);
        $terminal->update([
            'name' => $request->name,
            'bssid' => $request->bssid,
        ]);

        return back()->with('success', "Terminal '{$terminal->name}' updated successfully.");
    }

    public function update(Request $request)
    {
        $request->validate([
            'queue_timer_minutes' => 'required|integer|min:1|max:1440',
        ]);

        Setting::setValue('queue_timer_minutes', $request->queue_timer_minutes);

        return back()->with('success', 'Settings updated successfully.');
    }
}
