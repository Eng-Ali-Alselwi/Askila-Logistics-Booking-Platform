<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestPermissionsController extends Controller
{
    public function testShipments()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }
        
        $permissions = [
            'view shipments',
            'create shipments',
            'edit shipments',
            'delete shipments',
            'update shipment status',
            'export shipments'
        ];
        
        $results = [];
        foreach ($permissions as $permission) {
            $results[$permission] = $user->hasPermissionTo($permission);
        }
        
        return response()->json([
            'user_roles' => $user->getRoleNames()->toArray(),
            'permissions' => $results
        ]);
    }
}