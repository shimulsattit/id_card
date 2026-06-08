<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\IdCardTemplate;

$template = IdCardTemplate::find(6);
if ($template) {
    $template->name_color = '#292560';
    $template->text_color = '#292560';
    $template->data_color = '#000000'; // Keep data fields default/black or as requested
    $template->save();
    echo "Template ID 6 updated successfully.\n";
} else {
    echo "Template ID 6 not found.\n";
}
