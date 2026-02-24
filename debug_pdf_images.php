<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Student;
use App\Models\IdCardTemplate;

$ids = ['260366', '260367'];
foreach ($ids as $id) {
    $records = Student::where('student_id', $id)->get();
    echo "ID: $id (" . $records->count() . " records):\n";
    foreach ($records as $r) {
        $schoolName = $r->school ? $r->school->name : 'No School';
        echo " - DB_ID: {$r->id} | School: $schoolName (ID: {$r->school_id}) | Name: {$r->name} | Class: {$r->class} | Photo: " . ($r->photo ?: 'NULL') . "\n";
    }
    echo "\n";
}

// Check Template used in screenshot (PALLI MANGAL)
$template = IdCardTemplate::where('school_id', 3)->first(); // Likely 3 based on name search earlier
if ($template) {
    echo "Template ID: {$template->id} | Name: {$template->name} | Background: {$template->background_image}\n";
    $bgPath = public_path('storage/' . $template->background_image);
    echo "Background exists: " . (file_exists($bgPath) ? 'YES' : 'NO') . "\n";
} else {
    echo "Template for School 3 not found.\n";
}
