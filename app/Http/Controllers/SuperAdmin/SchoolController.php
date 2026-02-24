<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SchoolController extends Controller
{
    public function index()
    {
        $schools = School::withCount(['students', 'teachers'])->latest()->paginate(10);
        return view('admin.schools.index', compact('schools'));
    }

    public function create()
    {
        return view('admin.schools.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'classes' => 'nullable|array', // Validate classes array
            // Admin User Fields
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:6',
        ]);

        DB::transaction(function () use ($request) {
            // Upload Logo
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('schools/logos', 'public');
            }

            // Create School
            $school = School::create([
                'name' => $request->name,
                'address' => $request->address,
                'logo' => $logoPath,
                'classes' => $request->classes,
            ]);

            // Create School Admin
            $user = User::create([
                'name' => $request->admin_name,
                'email' => $request->admin_email,
                'password' => Hash::make($request->admin_password),
                'school_id' => $school->id,
                'role' => 'school_admin'
            ]);
            $user->assignRole('school_admin');
        });

        return redirect()->route('admin.schools.index')->with('success', 'School and Admin created successfully.');
    }

    public function edit(School $school)
    {
        $admin = User::where('school_id', $school->id)->where('role', 'school_admin')->first();
        return view('admin.schools.edit', compact('school', 'admin'));
    }

    public function update(Request $request, School $school)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'classes' => 'nullable|array',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email,' . ($school->users()->where('role', 'school_admin')->first()->id ?? 'NULL'),
            'admin_password' => 'nullable|string|min:6',
        ]);

        DB::transaction(function () use ($request, $school) {
            $data = $request->only(['name', 'address', 'classes']);

            if ($request->hasFile('logo')) {
                $data['logo'] = $request->file('logo')->store('schools/logos', 'public');
            }

            $school->update($data);

            // Update School Admin
            $admin = User::where('school_id', $school->id)->where('role', 'school_admin')->first();
            if ($admin) {
                $admin->name = $request->admin_name;
                $admin->email = $request->admin_email;
                if ($request->filled('admin_password')) {
                    $admin->password = Hash::make($request->admin_password);
                }
                $admin->save();
            }
        });

        return redirect()->route('admin.schools.index')->with('success', 'School and Admin updated successfully.');
    }

    public function destroy(School $school)
    {
        $school->delete();
        return redirect()->route('admin.schools.index')->with('success', 'School deleted successfully.');
    }

    public function loginAsSchoolAdmin(School $school)
    {
        // Find the admin user for this school
        $admin = User::where('school_id', $school->id)->where('role', 'school_admin')->first();

        if ($admin) {
            \Illuminate\Support\Facades\Log::info('Switching to user ID: ' . $admin->id);

            // Simple login
            \Illuminate\Support\Facades\Auth::loginUsingId($admin->id);

            return redirect('/dashboard');
        }

        return redirect()->back()->with('error', 'No admin user found for this school.');
    }
}
