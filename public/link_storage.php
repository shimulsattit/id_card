<?php
$target = __DIR__ . '/../storage/app/public';
$shortcut = __DIR__ . '/storage';

if (file_exists($shortcut)) {
    echo "Starting cleanup...<br>";
    // Try to remove it if it's a file/link or directory
    if (is_link($shortcut) || is_file($shortcut)) {
        unlink($shortcut);
        echo "Removed existing storage link/file.<br>";
    } elseif (is_dir($shortcut)) {
        // Simple directory removal (may fail if not empty, but standard symlink shouldn't be a filled dir)
        rmdir($shortcut);
        echo "Removed existing storage directory.<br>";
    }
}

echo "Creating symlink...<br>";
echo "Target: $target<br>";
echo "Shortcut: $shortcut<br>";

if (symlink($target, $shortcut)) {
    echo "<strong>Success!</strong> Storage link has been created.";
} else {
    echo "<strong>Error:</strong> Could not create link. Ensure permissions are correct.";
}
