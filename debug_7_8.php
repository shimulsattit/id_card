<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\School;

foreach ([7, 8] as $id) {
    $s = School::find($id);
    if ($s) {
        echo "ID: " . $s->id . ", Name: " . $s->name . ", Classes: " . json_encode($s->classes) . "\n";
    } else {
        echo "ID: $id not found\n";
    }
}
