<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Student;

$studentId = '1002';
$student = Student::where('student_id', $studentId)->first();

if ($student) {
    echo "Student Found: " . $student->name . " (School: " . $student->school->name . ")\n";
    echo "Name length: " . mb_strlen($student->name) . "\n";
} else {
    echo "Student with ID $studentId not found\n";
    // Check for ID-1002 or similar
    $student = Student::where('student_id', 'like', "%$studentId%")->first();
    if ($student) {
        echo "Partial Match Found: " . $student->name . " (ID: " . $student->student_id . ")\n";
    }
}
