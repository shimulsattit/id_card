<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\School;

$schools = School::all();
foreach ($schools as $s) {
    echo "- ID: {$s->id}, Unique ID: {$s->unique_id}, Name: {$s->name}\n";
    if ($s->id == 1002)
        echo "  *** MATCH FOUND FOR ID 1002 ***\n";
}
