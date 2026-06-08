<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\IdCardTemplate;

$template = IdCardTemplate::find(6);
if ($template) {
    echo json_encode($template->toArray(), JSON_PRETTY_PRINT);
} else {
    echo "Template ID 6 not found.";
}
