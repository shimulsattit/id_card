<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\School;
use App\Models\IdCardTemplate;

$schoolId = 4;
$school = School::find($schoolId);
echo "School ID 4: " . ($school ? $school->name : "NOT FOUND") . "\n";

$templates = IdCardTemplate::where('school_id', $schoolId)->get();
echo "Custom Templates for School 4:\n";
foreach ($templates as $t) {
    echo "- ID: {$t->id}, Name: {$t->name}\n";
}

$allTemplates = IdCardTemplate::whereNull('school_id')->get();
echo "\nGlobal Templates:\n";
foreach ($allTemplates as $t) {
    echo "- ID: {$t->id}, Name: {$t->name}\n";
}
