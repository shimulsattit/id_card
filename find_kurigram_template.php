<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\IdCardTemplate;

$imageName = 'id_card_templates/1IJIzmXEyip69C6Wk9Eyb8zcCy9q6HZ4v57xXIaj.jpg';
$template = IdCardTemplate::where('background_image', $imageName)->first();

if ($template) {
    echo "Found Template:\n";
    echo json_encode($template->toArray(), JSON_PRETTY_PRINT);
} else {
    echo "No template found with this background image.\n";
}
