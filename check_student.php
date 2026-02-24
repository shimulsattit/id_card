<?php
use App\Models\Student;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$schoolId = 1; // Assuming school ID 1 for now, or fetch first school
$students = Student::all();

echo "Total Students: " . $students->count() . "\n";
if ($students->count() > 0) {
    echo "First Student: " . json_encode($students->first()->toArray()) . "\n";

    // Check for specific student
    $check = Student::where('class', 'Seven')->where('section', 'A')->get();
    echo "Students in Class Seven, Section A: " . $check->count() . "\n";

    // Check distinct classes and sections
    echo "Distinct Classes: " . json_encode(Student::distinct()->pluck('class')->toArray()) . "\n";
    echo "Distinct Sections: " . json_encode(Student::distinct()->pluck('section')->toArray()) . "\n";
}

use App\Models\User;
echo "\nUsers:\n";
foreach (User::all() as $user) {
    echo "ID: {$user->id}, Name: {$user->name}, Role: {$user->role}, SchoolID: {$user->school_id}\n";
}
