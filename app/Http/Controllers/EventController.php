<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        // Fetch all events from the database
        $events = Event::all();

        // Pass events to the view
        return view('events.index', ['events' => $events]);
    }

    // Store a new event
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'link' => 'required|url',
        ]);

        $imagePath = $request->file('image')->store('events', 'public');

        Event::create([
            'user_id' => Auth::id(),
            'image' => $imagePath,
            'link' => $request->link,
        ]);

        return redirect()->back()->with('success', 'Event created successfully!');
    }

    // Delete an event
    public function destroy($id)
    {
        $event = Event::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $event->delete();

        return redirect()->back()->with('success', 'Event deleted successfully!');
    }

   // ✅ Handle Event Store (API)
public function storeEventApi(Request $request)
{
    // Validate the request
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        'link' => 'required|url',
    ]);

    // Check if the user is authenticated
    $user = auth()->user();
    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Store the image
    $imagePath = $request->file('image')->store('events', 'public');

    // Create the event
    $event = Event::create([
        'image' => $imagePath,
        'link' => $request->link,
        'user_id' => $user->id, // Associate the event with the authenticated user
    ]);

    return response()->json([
        'message' => 'Event added successfully!',
        'data' => $event,
    ], 201);
}

// ✅ Delete Event (API)
public function deleteEventApi($id)
{
    // Check if the event exists
    $event = Event::find($id);
    if (!$event) {
        return response()->json(['error' => 'Event not found'], 404);
    }

    // Delete the event
    $event->delete();

    return response()->json([
        'message' => 'Event deleted successfully!',
    ], 200);
}

// ✅ Get Events (API)
public function getEvents()
{
    // Retrieve all events
    $events = Event::all();

    return response()->json([
        'data' => $events,
    ], 200);
}
// ✅ Get Event by ID (API)
public function getEventById($id)
{
    // Find the event by ID
    $event = Event::find($id);

    // Check if the event exists
    if (!$event) {
        return response()->json(['error' => 'Event not found'], 404);
    }

    // Return the event details
    return response()->json([
        'data' => $event,
    ], 200);
}

}
