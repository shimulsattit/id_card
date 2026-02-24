<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Student;

$ids = ['260366', '260367'];

foreach ($ids as $id) {
    $records = Student::where('student_id', $id)->get();
    echo "ID: $id (" . $records->count() . " records):\n";
    foreach ($records as $r) {
        $schoolName = $r->school ? $r->school->name : 'No School';
        echo " - DB_ID: {$r->id} | School: $schoolName (ID: {$r->school_id}) | Name: {$r->name} | Class: {$r->class} | Roll: {$r->roll} | Created: {$r->created_at}\n";
    }
    echo "\n";
}
