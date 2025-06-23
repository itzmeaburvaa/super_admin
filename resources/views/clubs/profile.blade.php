@extends('layout.app')

@section('content')
<div class="p-4" style="max-height: 100vh; overflow-y: auto; background-color: #f8f9fc;">
    <div class="container-fluid">
        <div class="card shadow-lg rounded-4 p-5 border-0 bg-white">

            {{-- Club Header --}}
            <div class="row align-items-center mb-5">
                @if ($club->logo)
                    <div class="col-md-3 text-center mb-3 mb-md-0">
                        <img src="{{ asset('storage/' . $club->logo) }}" alt="Club Logo"
                             class="img-fluid rounded shadow-sm border"
                             style="max-height: 150px; object-fit: contain;">
                    </div>
                @endif
                <div class="col-md-9">
                    <h2 class="fw-bold" style="color: #003366;">{{ $club->club_name }}</h2>
                    <p class="text-muted"><i class="bi bi-calendar-event"></i> <strong>Founded:</strong> {{ $club->year_started }}</p>
                    <p class="mt-3">{{ $club->introduction ?? '—' }}</p>

                    <h5 class="fw-semibold mt-4" style="color: #003366;">Mission</h5>
                    <p>{{ $club->mission ?? '—' }}</p>
                </div>
            </div>

            <hr class="my-4">

            {{-- Staff Coordinator --}}
            <div class="row align-items-center mb-5">
                <div class="col-md-3 text-center mb-3 mb-md-0">
                    @if ($club->staff_coordinator_photo)
                        <img src="{{ asset('storage/' . $club->staff_coordinator_photo) }}"
                             class="rounded-circle shadow border"
                             width="140" height="140" alt="Staff Photo" style="object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-light d-flex justify-content-center align-items-center shadow border"
                             style="width: 140px; height: 140px;">
                            <i class="bi bi-person fs-1 text-muted"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-9">
                    <h4 class="fw-semibold" style="color: #003366;">Staff Coordinator</h4>
                    <h6 class="mb-1 mt-3">{{ $club->staff_coordinator_name }}</h6>
                    <p class="mb-0 text-muted"><i class="bi bi-envelope"></i> {{ $club->staff_coordinator_email }}</p>
                </div>
            </div>

            <hr class="my-4">

            {{-- Student Coordinators --}}
            <div>
                <h4 class="fw-semibold mb-4" style="color: #003366;">Student Coordinators</h4>
                <div class="row">
                    @forelse ($club->studentCoordinators as $student)
                        <div class="col-md-3 col-sm-4 col-6 mb-4 text-center">
                            <div class="d-flex flex-column align-items-center">
                                @if ($student->photo)
                                    <img src="{{ asset('storage/' . $student->photo) }}" 
                                         class="rounded-circle shadow border mb-2"
                                         width="140" height="140"
                                         style="object-fit: cover;" 
                                         alt="Student Photo">
                                @else
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center shadow border mb-2"
                                         style="width: 120px; height: 120px;">
                                        <i class="bi bi-person-circle fs-1 text-muted"></i>
                                    </div>
                                @endif
                                <p class="fw-medium text-dark mb-0">{{ $student->name }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted">No student coordinators listed.</div>
                    @endforelse
                </div>
            </div>

            <hr class="my-4">

            {{-- Events Section --}}
            {{-- Events Section --}}
<div class="mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0" style="color: #003366;">Club Events</h4>
        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
            + Add Event
        </button>
    </div>

    @if ($club->events->count())
        <div class="table-responsive">
            <table class="table table-bordered bg-white align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 130px;">Event Logo</th>
                        <th>Event Name</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($club->events as $event)
                        <tr>
                            <td class="text-center">
                                @if($event->image_path)
                                    <img src="{{ asset('storage/' .$event->image_path) }}"
                                         alt="Event Logo"
                                         style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px;">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>{{ $event->event_name }}</td>
                            <td>{{ $event->description }}</td>
                            <td>{{ $event->date }}</td>
                            <td>{{ \Carbon\Carbon::parse($event->time)->format('h:i A') }}</td>
                            <td>
                                <a href="{{ route('events.edit', $event->id) }}" class="btn btn-sm btn-warning mb-1">Edit</a>
                                <form action="{{ route('events.destroy', $event->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-muted">No events added yet.</p>
    @endif
</div>


        </div>
    </div>
</div>

<!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
        @csrf
        <input type="hidden" name="club_id" value="{{ $club->id }}">
        <div class="modal-header">
            <h5 class="modal-title" id="addEventModalLabel">Add New Event</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

            <div class="mb-3">
                <label for="event_name" class="form-label">Event Name</label>
                <input type="text" name="event_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" name="date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="time" class="form-label">Time</label>
                <input type="time" name="time" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Event Image</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save Event</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
    </form>
  </div>
</div>
@endsection
