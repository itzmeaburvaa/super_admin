<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'event_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['club_id', 'event_name', 'description', 'date', 'time']);

        // ✅ Store image in shared_storage/events
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('event_images', 'public'); // goes to shared_storage/event_images
            $data['image_path'] = $path;
        }

        Event::create($data);

        return redirect()->back()->with('success', 'Event added successfully!');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'event_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $event = Event::findOrFail($id);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($event->image_path && Storage::disk('public')->exists($event->image_path)) {
                Storage::disk('public')->delete($event->image_path);
            }

            $path = $request->file('image')->store('event_images', 'public');
            $event->image_path = $path;
        }

        $event->event_name = $request->event_name;
        $event->description = $request->description;
        $event->date = $request->date;
        $event->time = $request->time;
        $event->save();

        return redirect()->route('clubs.profile', ['id' => $event->club_id])
                         ->with('success', 'Event updated successfully!');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        // Delete image
        if ($event->image_path && Storage::disk('public')->exists($event->image_path)) {
            Storage::disk('public')->delete($event->image_path);
        }

        $event->delete();

        return redirect()->back()->with('success', 'Event deleted successfully!');
    }
}
