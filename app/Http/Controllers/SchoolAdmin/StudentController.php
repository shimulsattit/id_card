<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use ZipArchive;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $query = Student::where('school_id', $schoolId);

        if ($request->has('class') && $request->class) {
            $query->where('class', $request->class);
        }
        if ($request->has('section') && $request->section) {
            $query->where('section', $request->section);
        }
        if ($request->input('session')) {
            $query->where('session', $request->input('session'));
        }
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('roll', 'like', '%' . $request->search . '%');
        }

        // Fetch classes and sections for the filter dropdowns
        $school = Auth::user()->school;
        $classes = $school->classes ?? [];
        $sections = $school->sections ?? [];
        $sessions = $school->sessions ?? [];

        $students = $query->orderBy('student_id', 'asc')->paginate(20);
        return view('school.students.index', compact('students', 'classes', 'sections', 'sessions'));
    }

    public function create()
    {
        $school = Auth::user()->school;
        $classes = $school->classes ?? [];
        $sections = $school->sections ?? [];
        $sessions = $school->sessions ?? [];
        return view('school.students.create', compact('classes', 'sections', 'sessions'));
    }

    public function store(Request $request)
    {
        // ... (validation remains same) ...
        $schoolId = Auth::user()->school_id;

        $request->validate([
            'name' => 'required|string',
            'student_id' => 'required|string',
            'registration_no' => 'nullable|string',
            'class' => 'required',
            'section' => 'required',
            // ... (rest of validation) ...
            'roll' => [
                'required',
                Rule::unique('students')->where(function ($query) use ($schoolId, $request) {
                    return $query->where('school_id', $schoolId)
                        ->where('class', $request->class)
                        ->where('section', $request->section);
                })
            ],
            'father_name' => 'required|string',
            'mother_name' => 'required|string',
            'dob' => 'nullable|date',
            'blood_group' => 'nullable|string',
            'session' => 'nullable|string',
            'contact_no' => 'required|numeric',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:150|dimensions:max_width=300,max_height=300',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except(['photo', 'signature', '_token']);
        $data['school_id'] = $schoolId;

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('students/photos', 'public');
        }
        if ($request->hasFile('signature')) {
            $data['signature'] = $request->file('signature')->store('students/signatures', 'public');
        }

        Student::create($data);

        return redirect()->route('school.students.index')->with('success', 'Student added successfully.');
    }

    public function edit(Student $student)
    {
        if ($student->school_id !== Auth::user()->school_id)
            abort(403);
        $school = Auth::user()->school;
        $classes = $school->classes ?? [];
        $sections = $school->sections ?? [];
        $sessions = $school->sessions ?? [];
        return view('school.students.edit', compact('student', 'classes', 'sections', 'sessions'));
    }

    public function update(Request $request, Student $student)
    {
        if ($student->school_id !== Auth::user()->school_id)
            abort(403);

        $request->validate([
            'name' => 'required|string',
            'student_id' => 'required|string',
            'registration_no' => 'nullable|string',
            'class' => 'required',
            'section' => 'required',
            'roll' => [
                'required',
                Rule::unique('students')->ignore($student->id)->where(function ($query) use ($student, $request) {
                    return $query->where('school_id', $student->school_id)
                        ->where('class', $request->class)
                        ->where('section', $request->section);
                })
            ],
            'father_name' => 'required|string',
            'mother_name' => 'required|string',
            'dob' => 'nullable|date',
            'session' => 'nullable|string',
            'contact_no' => 'required|numeric',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:150|dimensions:max_width=300,max_height=300',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except(['photo', 'signature', '_token', '_method']);

        if ($request->hasFile('photo')) {
            if ($student->photo)
                Storage::disk('public')->delete($student->photo);
            $data['photo'] = $request->file('photo')->store('students/photos', 'public');
        }
        if ($request->hasFile('signature')) {
            if ($student->signature)
                Storage::disk('public')->delete($student->signature);
            $data['signature'] = $request->file('signature')->store('students/signatures', 'public');
        }

        $student->update($data);

        return redirect()->route('school.students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        if ($student->school_id !== Auth::user()->school_id)
            abort(403);
        if ($student->photo)
            Storage::disk('public')->delete($student->photo);
        if ($student->signature)
            Storage::disk('public')->delete($student->signature);
        $student->delete();
        return redirect()->route('school.students.index')->with('success', 'Student deleted.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), "r");

        // Read header
        $header = fgetcsv($handle);

        $schoolId = Auth::user()->school_id;
        $count = 0;

        try {
            while (($row = fgetcsv($handle)) !== false) {
                // Combine header with row data
                $data = array_combine($header, $row);

                // Allow both 'student_id' and 'registration_no' for flexibility
                $studentId = $data['student_id'] ?? $data['registration_no'] ?? null;

                if (!$studentId)
                    continue; // Skip if no ID

                Student::updateOrCreate(
                    [
                        'school_id' => $schoolId,
                        'class' => $data['class'],
                        'section' => $data['section'],
                        'roll' => $data['roll'],
                    ],
                    [
                        'name' => $data['name'],
                        'student_id' => $studentId,
                        'registration_no' => $data['registration_no'] ?? null,
                        'father_name' => $data['father_name'],
                        'mother_name' => $data['mother_name'],
                        'dob' => isset($data['dob']) && !empty($data['dob']) ? (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $data['dob']) ? Carbon::createFromFormat('d/m/Y', $data['dob'])->format('Y-m-d') : (preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['dob']) ? $data['dob'] : null)) : null,
                        'blood_group' => $data['blood_group'] ?? null,
                        'session' => $data['session'] ?? '2024-2025',
                        'contact_no' => $data['contact_no'],
                        'photo' => 'students/photos/default.png', // Default photo for new records
                        'signature' => null, // Default signature for new records
                    ]
                );
                $count++;
            }
            fclose($handle);

            return redirect()->back()->with('success', "$count students imported successfully.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing students: ' . $e->getMessage());
        }
    }

    public function downloadSample()
    {
        $headers = [
            'name',
            'student_id',
            'registration_no',
            'class',
            'section',
            'roll',
            'father_name',
            'mother_name',
            'dob',
            'blood_group',
            'session',
            'contact_no'
        ];

        $callback = function () use ($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);

            // Add a sample row
            fputcsv($file, [
                'John Doe',
                '2023001',
                '1234567890',
                'Ten',
                'A',
                '01',
                'Father Name',
                'Mother Name',
                '2010-01-01',
                'A+',
                '2024-2025',
                '01700000000'
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=students_sample.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ]);
    }


    public function show(Student $student)
    {
        return redirect()->route('school.students.edit', $student->id);
    }

    public function importPhotos(Request $request)
    {
        $request->validate([
            'zip_file' => 'required|mimes:zip|max:51200', // Max 50MB
        ]);

        $zip = new ZipArchive;
        $file = $request->file('zip_file');

        if ($zip->open($file->getRealPath()) === TRUE) {
            // Create a temporary extraction directory
            $path = storage_path('app/temp/student_photos_' . time());
            File::makeDirectory($path, 0755, true, true);

            $zip->extractTo($path);
            $zip->close();

            $files = File::allFiles($path);
            $count = 0;
            $schoolId = Auth::user()->school_id;

            foreach ($files as $file) {
                $filename = $file->getFilename();
                $extension = $file->getExtension();

                // Allowed extensions
                if (!in_array(strtolower($extension), ['jpg', 'jpeg', 'png']))
                    continue;

                // Extract Student ID from filename (remove extension)
                $studentId = pathinfo($filename, PATHINFO_FILENAME);

                // Find student by ID and School
                $student = Student::where('school_id', $schoolId)->where('student_id', $studentId)->first();

                if ($student) {
                    // Delete old photo if not default
                    if ($student->photo && $student->photo !== 'students/photos/default.png') {
                        Storage::disk('public')->delete($student->photo);
                    }

                    // Move file to public storage
                    $newPath = 'students/photos/' . $filename;

                    // We need to use Storage facade to put file to public disk correctly
                    // Reading file content and putting it to storage is safer for different filesystem drivers
                    Storage::disk('public')->put($newPath, File::get($file->getRealPath()));

                    $student->update(['photo' => $newPath]);
                    $count++;
                }
            }

            // Cleanup temp directory
            File::deleteDirectory($path);

            return redirect()->back()->with('success', "$count photos updated successfully.");
        } else {
            return redirect()->back()->with('error', 'Failed to open zip file.');
        }
    }


    public function exportReport()
    {
        $schoolId = Auth::user()->school_id;
        $students = Student::where('school_id', $schoolId)
            ->orderBy('class')
            ->orderBy('roll')
            ->get();

        $school = Auth::user()->school;

        // Process images to absolute paths for Excel
        foreach ($students as $student) {
            $path = $student->photo ? public_path('storage/' . $student->photo) : null;
            if ($path && file_exists($path)) {
                // Convert to file URL format for Windows
                $student->excel_photo = 'file:///' . str_replace('\\', '/', $path);
            } else {
                $student->excel_photo = null;
            }
        }

        return response(view('school.students.report', compact('students', 'school')))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="student_report.xls"');
    }
}
