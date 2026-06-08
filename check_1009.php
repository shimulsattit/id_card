<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\School;
use App\Models\IdCardTemplate;
use App\Models\Student;

echo "--- All Schools ---\n";
$schools = School::all();
foreach ($schools as $s) {
    echo "ID: {$s->id} | Unique ID: {$s->unique_id} | Name: {$s->name}\n";
}

echo "\n--- Students for School 11 ---\n";
$students = Student::where('school_id', 11)->take(5)->get();
if ($students->isEmpty()) {
    echo "No students found for school 11.\n";
} else {
    foreach ($students as $stu) {
        echo "- ID: {$stu->id} | SID: {$stu->student_id} | Name: {$stu->name} | Class: {$stu->class} | Roll: {$stu->roll}\n";
    }
}

echo "\n--- All Templates ---\n";
$templates = IdCardTemplate::all();
foreach ($templates as $t) {
    echo "ID: {$t->id} | Name: {$t->name} | School ID: " . ($t->school_id ?? 'Global') . " | Settings: " . ($t->settings ? 'YES' : 'NO') . " | BG: {$t->background_image}\n";
    if ($t->id == 10) {
        echo "   Template 10 Details:\n";
        echo "   - Background: {$t->background_image}\n";
        echo "   - Name Color: {$t->name_color}\n";
        echo "   - Data Color: {$t->data_color}\n";
        echo "   - Text Color: {$t->text_color}\n";
        echo "   - Settings: " . ($t->settings ? json_encode($t->settings, JSON_PRETTY_PRINT) : 'null') . "\n";
    }
}
