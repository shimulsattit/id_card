<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\School;

// Get all schools ordered by ID (creation date)
$schools = School::orderBy('id')->get();
echo "Found " . $schools->count() . " schools to re-ID." . PHP_EOL;

$currentId = 1001;

foreach ($schools as $school) {
    $school->update(['unique_id' => $currentId]);
    echo "Updated " . $school->name . " -> " . $currentId . PHP_EOL;
    $currentId++;
}

echo "Done." . PHP_EOL;
