<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\School;
use App\Models\User;

$user = User::where('email', 'nobojibon2026@gmail.com')->first();
if ($user) {
    echo "User ID: " . $user->id . "\n";
    echo "School ID: " . $user->school_id . "\n";
    $school = $user->school;
    echo "School Name: " . $school->name . "\n";
    echo "Classes in DB: " . json_encode($school->classes) . "\n";
} else {
    echo "User not found\n";
}
