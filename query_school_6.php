<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\School;
use App\Models\IdCardTemplate;

$school = School::find(6);
if ($school) {
    echo "School ID 6: " . $school->name . "\n";
} else {
    echo "School ID 6 not found.\n";
    $allSchools = School::all(['id', 'name']);
    foreach ($allSchools as $s) {
        echo "ID: {$s->id}, Name: {$s->name}\n";
    }
}

$templates = IdCardTemplate::where('school_id', 6)->orWhereNull('school_id')->get();
echo "\nTemplates for School 6 (or global):\n";
foreach ($templates as $t) {
    echo "ID: {$t->id}, Name: {$t->name}, School ID: " . ($t->school_id ?? 'Global') . "\n";
}
