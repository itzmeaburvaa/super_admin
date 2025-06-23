@extends('layout.app')
<style>
    .dropdown-item.text-danger {
        background-color: transparent !important;
    }

    .dropdown-item.text-danger:hover,
    .dropdown-item.text-danger:focus {
        background-color: transparent !important;
    }
</style>

@section('content')
    <h2>View Clubs</h2>
    <table class="table table-bordered bg-white mt-4">
        <thead>
            <tr>
                <th>Club Name</th>
                <th>Staff Coordinator</th>
                <th>Year of Start</th>
                <th>Options</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clubs as $club)
            <tr>
                <td>{{ $club->club_name }}</td>
                <td>{{ $club->staff_coordinator_name }}</td>
                <td>{{ $club->year_started }}</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            Options
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('clubs.edit', $club->id) }}">Edit</a></li>
                            <li><a href="{{ route('clubs.profile', $club->id) }}" class="dropdown-item">View Profile</a></li>

                            <li>
                                <form method="POST" action="{{ route('clubs.destroy', $club->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="dropdown-item text-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </li>

                        </ul>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
