<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$templates = \App\Models\IdCardTemplate::all();
foreach ($templates as $template) {
    echo "ID: " . $template->id . " | Name: " . $template->name . "\n";
}
