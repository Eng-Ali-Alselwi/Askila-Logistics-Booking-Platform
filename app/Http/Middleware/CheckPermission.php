<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Support multiple permissions separated by '|' or ',' (any passes)
        $delimiters = ['|', ','];
        $permissions = [$permission];
        foreach ($delimiters as $delim) {
            if (str_contains($permission, $delim)) {
                $permissions = array_filter(array_map('trim', explode($delim, $permission)));
                break;
            }
        }

        $allowed = count($permissions) > 1
            ? $user->hasAnyPermission($permissions)
            : $user->hasPermissionTo($permission);

        // Check if user has the required permission(s)
        if (!$allowed) {
            // Log the denied permission for debugging
            Log::warning('Permission denied for user', [
                'user_id' => $user->id,
                'required_permission' => $permission,
                'url' => $request->fullUrl(),
                'method' => $request->method()
            ]);
            
            // Provide a more detailed error message
            abort(403, 'Permission denied: You need the "' . $permission . '" permission to access this resource.');
        }

        return $next($request);
    }
}
