<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\School;
use App\Models\IdCardTemplate;

$school = School::where('unique_id', 1004)->first();
if (!$school) {
    die("School 1004 not found\n");
}
echo "School Found: {$school->name} (Original ID: {$school->id}, Unique ID: {$school->unique_id})\n";
echo "Address: {$school->address}\n";
echo "Logo: {$school->logo}\n";
echo "Classes: " . json_encode($school->classes) . "\n";
echo "Sections: " . json_encode($school->sections) . "\n";
echo "Sessions: " . json_encode($school->sessions) . "\n";

$templates = IdCardTemplate::where('school_id', $school->id)->get();
echo "\nCustom Templates for this School:\n";
foreach ($templates as $t) {
    echo "- ID: {$t->id}, Name: {$t->name}\n";
}

$globalTemplates = IdCardTemplate::whereNull('school_id')->get();
echo "\nGlobal Templates:\n";
foreach ($globalTemplates as $t) {
    echo "- ID: {$t->id}, Name: {$t->name}\n";
}
