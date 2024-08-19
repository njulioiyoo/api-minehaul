<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'url', 'parent_id', 'position'];

    public $timestamps = false;

    // Properti untuk menyimpan roles sementara
    protected $rolesToSync = [];

    protected static function boot()
    {
        parent::boot();

        // Event sebelum model disimpan (baik create atau update)
        static::saving(function ($menu) {
            if (request()->has('data.attributes.roles')) {
                $roles = request()->input('data.attributes.roles');
                $menu->rolesToSync = $roles;
            }
        });

        // Event setelah model disimpan (baik create atau update)
        static::saved(function ($menu) {
            // Sinkronisasi roles di tabel pivot
            if (! empty($menu->rolesToSync) && is_array($menu->rolesToSync)) {
                Log::info('Syncing roles: ', $menu->rolesToSync);
                $menu->roles()->sync($menu->rolesToSync);
            }
        });
    }

    // Relasi menu ke role_menus
    public function roleMenu()
    {
        return $this->hasMany(RoleMenu::class, 'menu_id');
    }

    // Relasi belongsToMany ke Spatie\Permission\Models\Role
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_menus', 'menu_id', 'role_id');
    }

    // Relasi menu ke parent
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    // Relasi menu ke children
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')
            ->select('id', 'name', 'icon', 'url', 'parent_id', 'position')
            ->orderBy('position');
    }

    public function getTree()
    {
        $user = auth()->user();
        $roles = $user->roles->pluck('id')->toArray();

        // Ambil menu berdasarkan role pengguna yang sedang login
        $menus = $this->whereNull('parent_id')
            ->whereHas('roleMenu', function ($query) use ($roles) {
                $query->whereIn('role_id', $roles);
            })
            ->with(['children' => function ($query) use ($roles) {
                $query->whereHas('roleMenu', function ($query) use ($roles) {
                    $query->whereIn('role_id', $roles);
                })
                    ->select('id', 'name', 'icon', 'url', 'parent_id', 'position');
            }])
            ->orderBy('position')
            ->get();

        return $menus;
    }
}
