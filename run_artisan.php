<?php
/**
 * ⚠️ IMPORTANT: Delete this file immediately after use!
 * Upload to project root, open in browser, then DELETE.
 */

// Security: simple token check
$token = $_GET['token'] ?? '';
if ($token !== 'run1234') {
    die('<h2 style="color:red">❌ Access Denied. Add ?token=run1234 to URL</h2>');
}

echo '<html><body style="font-family:monospace; background:#111; color:#0f0; padding:20px;">';
echo '<h2 style="color:#fff;">🚀 Laravel Artisan Runner</h2>';

$base = dirname(dirname(__FILE__)); // public/ থেকে project root এ

$commands = [
    'migrate --force' => 'php artisan migrate --force',
    'config:clear' => 'php artisan config:clear',
    'view:clear' => 'php artisan view:clear',
    'cache:clear' => 'php artisan cache:clear',
    'route:clear' => 'php artisan route:clear',
];

foreach ($commands as $label => $cmd) {
    echo '<h3 style="color:#ff0;">▶ ' . htmlspecialchars($label) . '</h3>';
    $output = shell_exec('cd ' . escapeshellarg($base) . ' && ' . $cmd . ' 2>&1');
    echo '<pre style="background:#222; padding:10px; border-radius:5px;">' . htmlspecialchars($output) . '</pre>';
}

echo '<h2 style="color:#f00;">⚠️ এই file টি এখনই DELETE করুন!</h2>';
echo '</body></html>';
