<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;

// Test the permissions
echo "Testing shipment permissions...\n";

// Get a user and assign manager role
$user = User::first();
if (!$user) {
    echo "No user found. Please seed the database first.\n";
    exit(1);
}

// Assign manager role
$user->assignRole('manager');

// Check if user has shipment permissions
$permissions = [
    'view shipments',
    'create shipments',
    'edit shipments',
    'delete shipments',
    'update shipment status',
    'export shipments'
];

echo "User roles: " . implode(', ', $user->getRoleNames()->toArray()) . "\n";
echo "Checking permissions:\n";

foreach ($permissions as $permission) {
    $hasPermission = $user->hasPermissionTo($permission);
    echo "  $permission: " . ($hasPermission ? 'YES' : 'NO') . "\n";
}

echo "Test completed.\n";