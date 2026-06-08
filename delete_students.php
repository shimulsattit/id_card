<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$schoolId = 5;
$classesToDelete = ['Play', 'Six'];

$deletedCount = \App\Models\Student::where('school_id', $schoolId)
    ->whereIn('class', $classesToDelete)
    ->delete();

echo "Successfully deleted $deletedCount student records for School ID $schoolId (Play, Six).\n";
