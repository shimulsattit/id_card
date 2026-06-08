<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\School;
use App\Models\Student;

$school = School::where('unique_id', 1004)->first();
if ($school) {
    $students = Student::where('school_id', $school->id)->get();
    echo "Found " . $students->count() . " students for School 1004:\n";
    foreach ($students as $s) {
        echo "- ID: {$s->id}, Name: {$s->name}, Class: {$s->class}, Roll: {$s->roll}, Session: {$s->session}\n";
    }
} else {
    echo "School 1004 not found.\n";
}
