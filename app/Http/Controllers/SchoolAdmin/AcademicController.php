<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcademicController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $allClasses = ['Play', 'Nursery', 'KG', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Honours'];
        $currentClasses = $school->classes ?? [];
        $currentSections = $school->sections ?? [];
        $currentSessions = $school->sessions ?? [];

        return view('school.academic.index', compact('allClasses', 'currentClasses', 'currentSections', 'currentSessions'));
    }

    public function updateClasses(Request $request)
    {
        $request->validate([
            'classes' => 'nullable|array',
        ]);

        $school = Auth::user()->school;
        $school->update(['classes' => $request->classes]);

        return redirect()->back()->with('success', 'Classes updated successfully.');
    }

    public function updateSections(Request $request)
    {
        $request->validate([
            'sections_input' => 'nullable|string',
        ]);

        // Process comma-separated string into array
        $sections = array_map('trim', explode(',', $request->sections_input));
        $sections = array_filter($sections); // Remove empty values

        $school = Auth::user()->school;
        $school->update(['sections' => $sections]);

        return redirect()->back()->with('success', 'Sections updated successfully.');
    }

    public function updateSessions(Request $request)
    {
        $request->validate([
            'sessions_input' => 'nullable|string',
        ]);

        // Process comma-separated string into array
        $sessions = array_map('trim', explode(',', $request->sessions_input));
        $sessions = array_filter($sessions); // Remove empty values

        $school = Auth::user()->school;
        $school->update(['sessions' => $sessions]);

        return redirect()->back()->with('success', 'Sessions updated successfully.');
    }
}
