<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Student;
use Illuminate\Support\Facades\DB;

$schoolId = 3; // Palli Mangal

$duplicates = Student::where('school_id', $schoolId)
    ->select('student_id', DB::raw('COUNT(*) as count'))
    ->groupBy('student_id')
    ->having('count', '>', 1)
    ->get();

if ($duplicates->isEmpty()) {
    echo "No duplicate Student IDs found for school $schoolId.\n";
} else {
    echo "Found " . $duplicates->count() . " Student IDs with duplicate records:\n\n";
    foreach ($duplicates as $d) {
        $records = Student::where('school_id', $schoolId)
            ->where('student_id', $d->student_id)
            ->get();
        echo "Student ID: {$d->student_id} ({$d->count} records):\n";
        foreach ($records as $r) {
            echo " - DB_ID: {$r->id} | Name: {$r->name} | Class: {$r->class} | Roll: {$r->roll} | Session: {$r->session} | Created: {$r->created_at}\n";
        }
        echo "\n";
    }
}
