<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Menu;
use App\Models\RoleMenu;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class VerifyUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is not logged in
        if (! Auth::check()) {
            return response()->json([
                'error' => 'Unauthenticated',
                'message' => 'You must be logged in to access this resource.',
            ], 401);
        }

        // Get the current route URL
        $currentUrl = $request->path();

        // Extract the base URL segment before the second parameter
        $baseUrl = Str::before($currentUrl, '/');

        // Find the menu based on the URL
        $menu = Menu::with('permissionMenu')
            ->where('url', 'LIKE', $baseUrl.'%') // Match URLs starting with $baseUrl
            ->first();

        // If the menu is not found, return a validation error
        if (! $menu) {
            return response()->json([
                'error' => 'Not Found',
                'message' => 'The requested route is not available in the menu.',
            ], 404);
        }

        // Get the role_id of the currently logged-in user
        $roleId = Auth::user()->roles->first()->id ?? null;

        // Check if role_id is not found
        if (! $roleId) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'User does not have a valid role.',
            ], 403);
        }

        // Check if the menu is associated with the role via the role_menus table
        $userMenu = RoleMenu::where('role_id', $roleId)
            ->where('menu_id', $menu->id)
            ->first();

        // If the user does not have access to this menu, return a validation error
        if (! $userMenu) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'You do not have access to this menu.',
            ], 403);
        }

        // Check the user's roles against the roles available in the menu
        $roles = $menu->roles; // Directly retrieve roles without decoding
        if ($roles && ! Auth::user()->hasAnyRole($roles)) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'You do not have the required role to access this resource.',
            ], 403);
        }

        // If all validations pass, proceed with the request
        return $next($request);
    }
}
