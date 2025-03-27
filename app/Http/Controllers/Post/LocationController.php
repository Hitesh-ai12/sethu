<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function getLocations()
    {
        $locations = Location::orderBy('id', 'desc')->get();
        return response()->json(['success' => true, 'locations' => $locations]);
    }

    public function ApigetLocations()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $locations = Location::orderBy('created_at', 'desc')->get();

        if ($locations->isEmpty()) {
            return response()->json(['success' => true, 'message' => 'No locations found', 'locations' => []], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Locations fetched successfully',
            'locations' => $locations
        ], 200);
    }

    public function addLocation(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $location = Location::create(['name' => $request->name]);
        return response()->json(['success' => true, 'location' => $location]);
    }

    public function deleteLocation($id)
    {
        $location = Location::find($id);
        if (!$location) {
            return response()->json(['success' => false, 'message' => 'Location not found']);
        }
        $location->delete();
        return response()->json(['success' => true, 'message' => 'Location deleted successfully']);
    }

    public function updateLocation(Request $request, $id)
    {
        $location = Location::find($id);
        if (!$location) {
            return response()->json(['success' => false, 'message' => 'Location not found']);
        }
        $location->update(['name' => $request->name]);
        return response()->json(['success' => true, 'message' => 'Location updated successfully']);
    }
}
