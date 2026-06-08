<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\IdCardTemplate;

$t = IdCardTemplate::find(10);
if ($t) {
    $t->settings = null;  // Clear settings so hardcoded positions are used
    $t->name_color = '#a70063';  // Deep pink/magenta as requested
    $t->data_color = '#000000';  // Black
    $t->photo_border_color = '#00a89e';  // Teal border
    $t->save();
    echo "Template 10 updated successfully.\n";
    echo "Settings: " . ($t->settings ?? 'NULL') . "\n";
    echo "Name Color: {$t->name_color}\n";
    echo "Data Color: {$t->data_color}\n";
} else {
    echo "Template 10 not found.\n";
}
