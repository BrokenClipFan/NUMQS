<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MapLandmark;

class MapLandmarkController extends Controller
{
    public function getLandmarks() {
        return response()->json(MapLandmark::all());
    }

    public function index() {
        $landmarks = MapLandmark::all();
        return view('admin.landmarks.index', compact('landmarks'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            
            
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('landmarks', 'public');
        }

        $icon = 'bi-geo-alt-fill';
        $color = '#f2a63c';
        switch ($request->type) {
            case 'gas_station': 
                $icon = 'bi-fuel-pump-fill'; 
                $color = '#f97316'; 
                break;
            case 'school': 
                $icon = 'bi-book-fill'; 
                $color = '#3b82f6'; 
                break;
            case 'market': 
                $icon = 'bi-shop'; 
                $color = '#22c55e'; 
                break;
            case 'mini_stop': 
                $icon = 'bi-signpost-2-fill'; 
                $color = '#a855f7'; 
                break;
            case 'other': 
                $icon = 'bi-geo-alt-fill'; 
                $color = '#f2a63c'; 
                break;
        }

        MapLandmark::create([
            'name' => $request->name,
            'type' => $request->type,
            'icon' => $icon,
            'color' => $color,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'image_path' => $imagePath
        ]);

        return back()->with('success', 'Landmark added successfully.');
    }


    public function update(Request $request, $id) {
        $landmark = MapLandmark::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            
            
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('landmarks', 'public');
            $landmark->image_path = $imagePath;
        }


        $icon = 'bi-geo-alt-fill';
        $color = '#f2a63c';
        switch ($request->type) {
            case 'gas_station': 
                $icon = 'bi-fuel-pump-fill'; 
                $color = '#f97316'; 
                break;
            case 'school': 
                $icon = 'bi-book-fill'; 
                $color = '#3b82f6'; 
                break;
            case 'market': 
                $icon = 'bi-shop'; 
                $color = '#22c55e'; 
                break;
            case 'mini_stop': 
                $icon = 'bi-signpost-2-fill'; 
                $color = '#a855f7'; 
                break;
            case 'other': 
                $icon = 'bi-geo-alt-fill'; 
                $color = '#f2a63c'; 
                break;
        }
        $landmark->update([
            'name' => $request->name,
            'type' => $request->type,
            'icon' => $icon,
            'color' => $color,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return back()->with('success', 'Landmark updated successfully.');
    }

    public function destroy($id) {
        MapLandmark::findOrFail($id)->delete();
        return back()->with('success', 'Landmark deleted successfully.');
    }
}
