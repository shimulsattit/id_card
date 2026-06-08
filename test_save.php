<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\School;

$school = School::find(8);
echo "Before: " . json_encode($school->classes) . "\n";

$classes = $school->classes;
$classes[] = 'Angel';
$classes[] = 'Pioneer';

$school->update(['classes' => $classes]);

echo "After Update: " . json_encode($school->fresh()->classes) . "\n";
