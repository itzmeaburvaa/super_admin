<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\StudentCoordinator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClubController extends Controller
{
    private $sharedDisk = 'public'; // default Laravel disk, already symlinked to shared folder

    public function index()
    {
        $clubs = Club::with('studentCoordinators')->get();
        return view('clubs.index', compact('clubs'));
    }

    public function create()
    {
        return view('clubs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'club_name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'introduction' => 'nullable|string',
            'mission' => 'nullable|string',
            'staff_coordinator_name' => 'nullable|string|max:255',
            'staff_coordinator_email' => 'nullable|email|max:255',
            'staff_coordinator_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'year_started' => 'nullable|integer'
        ]);

        // Store club logo
        $logoPath = $request->file('logo')->store('club_logos', $this->sharedDisk);

        // Store staff photo (optional)
        $staffPhotoPath = null;
        if ($request->hasFile('staff_coordinator_photo')) {
            $staffPhotoPath = $request->file('staff_coordinator_photo')->store('staff_photos', $this->sharedDisk);
        }

        Club::create([
            'club_name' => $request->club_name,
            'logo' => $logoPath,
            'introduction' => $request->introduction,
            'mission' => $request->mission,
            'staff_coordinator_name' => $request->staff_coordinator_name,
            'staff_coordinator_email' => $request->staff_coordinator_email,
            'staff_coordinator_photo' => $staffPhotoPath,
            'year_started' => $request->year_started,
        ]);

        return redirect()->back()->with('success', 'Club added successfully!');
    }

    public function edit($id)
    {
        $club = Club::with('studentCoordinators')->findOrFail($id);
        return view('clubs.edit', compact('club'));
    }

    public function update(Request $request, $id)
    {
        $club = Club::with('studentCoordinators')->findOrFail($id);

        $request->validate([
            'club_name' => 'required',
            'staff_coordinator_email' => 'required|email',
            'year_started' => 'required|integer',
            'staff_coordinator_photo' => 'nullable|image|max:5120',
            'logo' => 'nullable|image|max:5120',
            'student_names.*' => 'nullable|string',
            'student_photos.*' => 'nullable|image|max:5120',
            'student_ids.*' => 'nullable|integer',
        ]);

        $fields = ['club_name', 'introduction', 'mission', 'staff_coordinator_name', 'staff_coordinator_email', 'year_started'];
        foreach ($fields as $field) {
            $club->$field = $request->$field;
        }

        // Staff Photo update
        if ($request->hasFile('staff_coordinator_photo')) {
            if ($club->staff_coordinator_photo) {
                Storage::disk($this->sharedDisk)->delete($club->staff_coordinator_photo);
            }
            $staffPhotoPath = $request->file('staff_coordinator_photo')->store('staff_photos', $this->sharedDisk);
            $club->staff_coordinator_photo = $staffPhotoPath;
        }

        // Logo update
        if ($request->hasFile('logo')) {
            if ($club->logo) {
                Storage::disk($this->sharedDisk)->delete($club->logo);
            }
            $logoPath = $request->file('logo')->store('club_logos', $this->sharedDisk);
            $club->logo = $logoPath;
        }

        $club->save();

        // Handle Student Coordinators
        $ids = $request->student_ids ?? [];
        $names = $request->student_names ?? [];
        $photos = $request->file('student_photos') ?? [];

        foreach ($names as $i => $name) {
            $id = $ids[$i] ?? null;
            $photoFile = $photos[$i] ?? null;

            if ($id) {
                $student = StudentCoordinator::find($id);
                if ($student) {
                    $student->name = $name;
                    if ($photoFile) {
                        if ($student->photo) {
                            Storage::disk($this->sharedDisk)->delete($student->photo);
                        }
                        $student->photo = $photoFile->store('student_photos', $this->sharedDisk);
                    }
                    $student->save();
                }
            } else {
                if ($name) {
                    $photoPath = $photoFile ? $photoFile->store('student_photos', $this->sharedDisk) : null;
                    StudentCoordinator::create([
                        'club_id' => $club->id,
                        'name' => $name,
                        'photo' => $photoPath,
                    ]);
                }
            }
        }

        return redirect()->route('clubs.index')->with('success', 'Club updated successfully!');
    }

    public function destroy($id)
    {
        $club = Club::findOrFail($id);

        if ($club->staff_coordinator_photo) {
            Storage::disk($this->sharedDisk)->delete($club->staff_coordinator_photo);
        }

        if ($club->logo) {
            Storage::disk($this->sharedDisk)->delete($club->logo);
        }

        // Delete student photos
        foreach ($club->studentCoordinators as $student) {
            if ($student->photo) {
                Storage::disk($this->sharedDisk)->delete($student->photo);
            }
        }

        $club->studentCoordinators()->delete();
        $club->delete();

        return redirect()->route('clubs.index')->with('success', 'Club deleted successfully!');
    }

    public function profile($id)
    {
        $club = Club::with('studentCoordinators')->findOrFail($id);
        return view('clubs.profile', compact('club'));
    }
}
