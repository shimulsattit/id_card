<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\Designation;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $query = Teacher::where('school_id', $schoolId);

        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('designation', 'like', '%' . $request->search . '%');
        }

        $teachers = $query->latest()->paginate(20);
        return view('school.teachers.index', compact('teachers'));
    }

    public function create()
    {
        $designations = Designation::where('school_id', auth()->user()->school_id)
            ->orWhereNull('school_id')
            ->orderBy('name')
            ->get();
        return view('school.teachers.create', compact('designations'));
    }

    public function store(Request $request)
    {
        $schoolId = Auth::user()->school_id;

        $request->validate([
            'employee_id' => 'required|string|unique:teachers,employee_id',
            'name' => 'required|string',
            'designation' => 'required|string',
            'joining_date' => 'required|date',
            'mobile' => 'required|numeric',
            'blood_group' => 'nullable|string',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:150|dimensions:max_width=300,max_height=300',
        ]);

        $data = $request->except(['photo', 'signature', '_token']);
        $data['school_id'] = $schoolId;

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('teachers/photos', 'public');
        }

        Teacher::create($data);

        return redirect()->route('school.teachers.index')->with('success', 'Teacher added successfully.');
    }

    public function edit(Teacher $teacher)
    {
        if ($teacher->school_id !== Auth::user()->school_id)
            abort(403);

        $designations = Designation::where('school_id', auth()->user()->school_id)
            ->orWhereNull('school_id')
            ->orderBy('name')
            ->get();

        return view('school.teachers.edit', compact('teacher', 'designations'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        if ($teacher->school_id !== Auth::user()->school_id)
            abort(403);

        $request->validate([
            'employee_id' => 'required|string|unique:teachers,employee_id,' . $teacher->id,
            'name' => 'required|string',
            'designation' => 'required|string',
            'joining_date' => 'required|date',
            'mobile' => 'required|numeric',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:150|dimensions:max_width=300,max_height=300',
        ]);

        $data = $request->except(['photo', 'signature', '_token', '_method']);

        if ($request->hasFile('photo')) {
            if ($teacher->photo)
                Storage::disk('public')->delete($teacher->photo);
            $data['photo'] = $request->file('photo')->store('teachers/photos', 'public');
        }

        $teacher->update($data);

        return redirect()->route('school.teachers.index')->with('success', 'Teacher updated successfully.');
    }

    public function destroy(Teacher $teacher)
    {
        if ($teacher->school_id !== Auth::user()->school_id)
            abort(403);
        if ($teacher->photo)
            Storage::disk('public')->delete($teacher->photo);
        $teacher->delete();
        return redirect()->route('school.teachers.index')->with('success', 'Teacher deleted.');
    }
}
