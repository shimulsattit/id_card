<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DesignationController extends Controller
{
    public function index()
    {
        $schoolId = Auth::user()->school_id;
        // Fetch designations specific to the school OR system defaults (where school_id is null)
        $designations = Designation::where('school_id', $schoolId)
            ->orWhereNull('school_id')
            ->orderBy('name')
            ->get();

        return view('school.designations.index', compact('designations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Designation::create([
            'school_id' => Auth::user()->school_id,
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Designation added successfully.');
    }

    public function destroy(Designation $designation)
    {
        // Only allow deleting own school's designations, not defaults
        if ($designation->school_id !== Auth::user()->school_id) {
            abort(403, 'Unauthorized action.');
        }

        $designation->delete();
        return redirect()->back()->with('success', 'Designation deleted.');
    }
}
