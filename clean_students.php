<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\School;
use App\Models\Student;

// Based on listing, ID 4 is Perfect Residential School
$schoolId = 4;
$school = School::find($schoolId);

if (!$school) {
    echo "School with ID $schoolId not found.\n";
    exit;
}

$count = Student::where('school_id', $school->id)->count();
Student::where('school_id', $school->id)->delete();

echo "Successfully deleted $count student records for school: {$school->name} (ID: {$school->id}).\n";
echo "Images remain in storage.\n";
