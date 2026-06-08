<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\School;
use App\Models\IdCardTemplate;
use App\Models\Student;

$searchName = 'Perfect';
$schools = School::where('name', 'like', "%$searchName%")->get();

if ($schools->isEmpty()) {
    echo "No school found with name containing '$searchName'\n";
    // List some schools to see what's there
    $allSchools = School::take(10)->get();
    echo "Some schools in DB:\n";
    foreach ($allSchools as $s) {
        echo "- ID: {$s->id}, Name: {$s->name}\n";
    }
    exit;
}

foreach ($schools as $school) {
    echo "Found School: " . $school->name . " (ID: " . $school->id . ")\n";

    $templates = IdCardTemplate::where('school_id', $school->id)->orWhereNull('school_id')->get();
    echo "Templates for this school:\n";
    foreach ($templates as $t) {
        echo "- ID: {$t->id}, Name: {$t->name}, Type: {$t->type}\n";
    }

    $students = Student::where('school_id', $school->id)->take(5)->get();
    echo "Sample Students:\n";
    foreach ($students as $s) {
        echo "- Name: {$s->name} (" . mb_strlen($s->name) . " chars)\n";
    }
    echo "-------------------\n";
}
