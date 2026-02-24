<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$t = \App\Models\IdCardTemplate::find(3);
if ($t) {
    echo "ID: " . $t->id . "\n";
    echo "Name: " . $t->name . "\n";
    echo "School ID: " . ($t->school_id ?? 'NULL') . "\n";
    echo "Type: " . $t->type . "\n";
} else {
    echo "Template 3 not found\n";
}
