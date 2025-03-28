<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{

    public function index(Request $request)
    {
        $events = Event::where('user_id', Auth::id())->paginate(5); // 5 events per page
        return view('events.index', compact('events'));
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

    public function destroy($id)
    {
        $event = Event::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $event->delete();

        return redirect()->back()->with('success', 'Event deleted successfully!');
    }


   // ✅ Handle Event Store (API)
    public function storeEventApi(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'link' => 'required|url',
        ]);

        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $imagePath = $request->file('image')->store('events', 'public');

        $event = Event::create([
            'image' => $imagePath,
            'link' => $request->link,
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Event added successfully!',
            'data' => $event,
        ], 201);
    }

    // ✅ Delete Event (API)
    public function deleteEventApi($id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        $event->delete();

        return response()->json([
            'message' => 'Event deleted successfully!',
        ], 200);
    }

    // ✅ Get Events (API)
    public function getEvents()
    {
        $events = Event::all();

        return response()->json([
            'data' => $events,
        ], 200);
    }

    // ✅ Get Event by ID (API)
    public function getEventById($id)
    {

        $event = Event::find($id);

        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        return response()->json([
            'data' => $event,
        ], 200);
    }

}
