<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Student;
use App\Models\IdCardTemplate;

$schoolId = 4; // Perfect Residential School
$students = Student::where('school_id', $schoolId)->get();

echo "Total Students in School 4: " . $students->count() . "\n";
echo "Listing some students (ID, Name, Roll, Registration):\n";
foreach ($students->take(50) as $s) {
    echo "- ID: {$s->student_id}, Name: {$s->name}, Roll: {$s->roll}, Reg: {$s->registration_no}\n";
    if ($s->student_id == '1002' || $s->roll == '1002' || $s->registration_no == '1002') {
        echo "  *** MATCH FOUND FOR 1002 ***\n";
    }
}

$templates = IdCardTemplate::where('school_id', $schoolId)->orWhereNull('school_id')->get();
echo "\nTemplates available:\n";
foreach ($templates as $t) {
    echo "- ID: {$t->id}, Name: {$t->name}\n";
}
