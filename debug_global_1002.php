<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Student;

$searchId = '1002';
$students = Student::where('student_id', $searchId)
    ->orWhere('roll', $searchId)
    ->orWhere('registration_no', $searchId)
    ->with('school')
    ->get();

if ($students->isEmpty()) {
    echo "No student found with ID/Roll/Reg matching '$searchId'\n";

    // Check for partial matches
    $partial = Student::where('student_id', 'like', "%$searchId%")
        ->orWhere('roll', 'like', "%$searchId%")
        ->with('school')
        ->take(10)
        ->get();

    if (!$partial->isEmpty()) {
        echo "Partial matches found:\n";
        foreach ($partial as $s) {
            echo "- ID: {$s->student_id}, Roll: {$s->roll}, Name: {$s->name}, School: {$s->school->name}\n";
        }
    }
} else {
    foreach ($students as $s) {
        echo "Match Found: Name: {$s->name}, School: {$s->school->name} (School ID: {$s->school_id}), ID: {$s->student_id}, Roll: {$s->roll}\n";
    }
}
