<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$schoolId = 5;
$classes = \App\Models\Student::where('school_id', $schoolId)
    ->select('class', \DB::raw('count(*) as count'))
    ->groupBy('class')
    ->get();

echo "School: Raipura Ideal High School (ID: $schoolId, Unique ID: 1003)\n";
foreach ($classes as $class) {
    echo "Class: " . ($class->class ?: "(empty)") . " - Count: " . $class->count . "\n";
}
