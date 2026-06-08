<?php
/**
 * ⚠️ IMPORTANT: Upload this file to your LIVE SERVER's public directory.
 * Visit it in your browser (e.g. id.cyberhavenit.com/fix_super_admin.php)
 */

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<html><body style='font-family:monospace; background:#111; color:#0f0; padding:20px;'>";
echo "<h2>🛠️ Deep Fixing Super Admin on Live Server...</h2>";

try {
    // 1. Clear all caches completely
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    \Illuminate\Support\Facades\Artisan::call('permission:cache-reset');
    echo "<p>✅ All Laravel and Spatie caches cleared.</p>";

    $email = 'admin@system.com';
    $user = \App\Models\User::where('email', $email)->first();

    if (!$user) {
        echo "<p>⚠️ User admin@system.com not found. Creating...</p>";
        $user = \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => $email,
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'super_admin',
            'school_id' => null,
        ]);
    } else {
        echo "<p>✅ User admin@system.com exists (ID: {$user->id}). Updating password to 'password' just in case.</p>";
        $user->password = \Illuminate\Support\Facades\Hash::make('password');
        $user->role = 'super_admin';
        $user->school_id = null;
        $user->save();
    }

    // 2. Fix Roles in Database
    $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    echo "<p>✅ Role 'super_admin' verified in DB.</p>";

    // 3. Sync Roles (removes any other roles and gives ONLY super_admin)
    $user->syncRoles(['super_admin']);
    echo "<p>✅ Synced 'super_admin' role to user.</p>";

    // 4. Debug output
    echo "<h3>🔍 Debug Info:</h3>";
    echo "<ul>";
    echo "<li>User hasRole('super_admin'): " . ($user->hasRole('super_admin') ? 'TRUE' : 'FALSE') . "</li>";
    echo "<li>User roles in DB: " . $user->roles->pluck('name')->join(', ') . "</li>";
    echo "</ul>";

    echo "<h2 style='color:#ff0;'>🎉 FIX APPLIED! Please try logging in again.</h2>";

} catch (\Exception $e) {
    echo "<h2 style='color:red;'>❌ ERROR: " . $e->getMessage() . "</h2>";
}

echo "</body></html>";
