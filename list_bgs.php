<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\IdCardTemplate;

$templates = IdCardTemplate::all();
foreach ($templates as $t) {
    echo "ID: {$t->id}, Name: {$t->name}, BG: {$t->background_image}\n";
}
