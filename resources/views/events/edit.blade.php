@extends('layout.app')

@section('content')
<div class="container mt-5">
    <h3 class="mb-4">Edit Event</h3>

    <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="p-4 shadow rounded bg-white">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="event_name" class="form-label">Event Name</label>
            <input type="text" name="event_name" value="{{ $event->event_name }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4">{{ $event->description }}</textarea>
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" value="{{ $event->date }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="time" class="form-label">Time</label>
            <input type="time" name="time" value="{{ $event->time }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Event Image</label>
            <input type="file" name="image" class="form-control">
            @if ($event->image_path)
                <div class="mt-2">
                    <img src="{{ asset('storage/' .$event->image_path) }}" width="180" class="rounded shadow border" alt="Event Image">
                </div>
            @endif
        </div>

        <button type="submit" class="btn btn-success">Update Event</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
@endsection
