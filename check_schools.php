<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$schools = \App\Models\School::where('name', 'like', '%RAIPURA IDEAL%')->get();

foreach ($schools as $school) {
    echo "ID: " . $school->id . " - Name: " . $school->name . "\n";
}
