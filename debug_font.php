<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\School;
use App\Models\IdCardTemplate;
use App\Models\Student;

$schoolId = 1002;
$school = School::find($schoolId);

if (!$school) {
    echo "School $schoolId not found\n";
    exit;
}

echo "School Name: " . $school->name . "\n";

$templates = IdCardTemplate::where('school_id', $schoolId)->orWhereNull('school_id')->get();
echo "Templates:\n";
foreach ($templates as $t) {
    echo "- ID: {$t->id}, Name: {$t->name}, Type: {$t->type}\n";
}

$students = Student::where('school_id', $schoolId)->take(5)->get();
echo "Sample Students:\n";
foreach ($students as $s) {
    echo "- Name: {$s->name} (" . mb_strlen($s->name) . " chars)\n";
}
