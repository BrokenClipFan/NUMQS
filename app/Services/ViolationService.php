<?php

namespace App\Services;

use App\Models\Violation;

class ViolationService 
{
    // Declare type constants here so you don’t have typos across your code base
    const TYPE_UNAUTHORIZED_TERMINAL = 'unauthorized_terminal_entry';
    const TYPE_OVERSPEEDING          = 'overspeeding';
    const TYPE_ROUTE_DEVIATION       = 'route_deviation';

    /**
     * Create a flexible violation log entry.
     *
     * @param int $driverProfileId
     * @param string $type        Slug identifier (e.g., self::TYPE_UNAUTHORIZED_TERMINAL)
     * @param string $name        Human-friendly title
     * @param mixed $location     String description or an array/object of coordinates
     * @param array|null $properties Custom situational metadata 
     * @param string $severity    low, medium, high
     * @return Violation
     */
    public function createViolation(
        int $driverProfileId, 
        string $type, 
        string $name, 
        mixed $location, 
        ?array $properties = [], 
        string $severity = 'medium'
    ): Violation {
        
        return Violation::create([
            'driver_profile_id' => $driverProfileId,
            'type'              => $type,
            'name'              => $name,
            // If location is passed as an array (like coordinates), encode it to JSON string
            'location'          => is_array($location) ? json_encode($location) : $location,
            // Laravel cast configuration will automatically turn this array into JSON if set up in Model
            'properties'        => $properties, 
            'severity'          => $severity,
        ]);
    }

    public function resolve($id) {
        $violation = Violation::findOrDie($id);
        $violation->update([
            resolved_at => now()
        ]);

        return redirect()->route('fleet.management')->with('success', 'Driver violation has been Resolve');
    }
}