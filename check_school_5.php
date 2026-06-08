<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$school = \App\Models\School::find(5);

if ($school) {
    echo json_encode($school->toArray(), JSON_PRETTY_PRINT);
} else {
    echo "School not found.";
}
