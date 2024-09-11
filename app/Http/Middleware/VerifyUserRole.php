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
        // Cek jika user belum login
        if (! Auth::check()) {
            return response()->json([
                'error' => 'Unauthenticated',
                'message' => 'You must be logged in to access this resource.',
            ], 401);
        }

        // Ambil URL rute saat ini
        $currentUrl = $request->path();

        $baseUrl = Str::before($currentUrl, '/'); // Mengambil segmen awal sebelum parameter kedua

        // Cari menu berdasarkan URL
        $menu = Menu::with('permissionMenu')
            ->where('url', 'LIKE', $baseUrl.'%') // Mencocokkan URL yang dimulai dengan $baseUrl
            ->first();

        // Jika menu tidak ditemukan, berikan validasi
        if (! $menu) {
            return response()->json([
                'error' => 'Not Found',
                'message' => 'The requested route is not available in the menu.',
            ], 404);
        }

        // Ambil role_id dari user yang sedang login
        $roleId = Auth::user()->roles->first()->id ?? null;

        // Cek jika role_id tidak ditemukan
        if (! $roleId) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'User does not have a valid role.',
            ], 403);
        }

        // Cek apakah menu terkait dengan role melalui tabel role_menus
        $userMenu = RoleMenu::where('role_id', $roleId)
            ->where('menu_id', $menu->id)
            ->first();

        // Jika user tidak memiliki akses ke menu ini, berikan validasi
        if (! $userMenu) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'You do not have access to this menu.',
            ], 403);
        }

        // Cek role pengguna dengan roles yang ada di menu
        $roles = $menu->roles; // Langsung ambil roles tanpa decode
        if ($roles && ! Auth::user()->hasAnyRole($roles)) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'You do not have the required role to access this resource.',
            ], 403);
        }

        // Jika semua validasi berhasil, teruskan request
        return $next($request);
    }
}
