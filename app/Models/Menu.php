<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class Menu extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'key', 'url', 'parent_id', 'position', 'deleted_at'];

    public $timestamps = false;

    // Properti untuk menyimpan roles sementara
    protected $rolesToSync = [];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($menu) {
            // Generate the key from the name field
            $menu->key = Str::slug($menu->name);

            if (request()->has('data.attributes.roles')) {
                $roles = request()->input('data.attributes.roles');
                $menu->rolesToSync = $roles;
            }
        });

        static::saved(function ($menu) {
            if (! empty($menu->rolesToSync) && is_array($menu->rolesToSync)) {
                Log::info('Syncing roles: ', $menu->rolesToSync);
                $menu->roles()->sync($menu->rolesToSync);

                // Ambil permissions yang terkait dengan roles
                $permissions = Role::whereIn('id', $menu->rolesToSync)->with('permissions')->get()
                    ->pluck('permissions')
                    ->flatten()
                    ->unique('id')
                    ->mapWithKeys(function ($permission) {
                        return [$permission->id => 'read'];
                    })
                    ->toArray();

                Log::info('Permissions to sync:', $permissions);

                // Sinkronisasi permissions di tabel pivot PermissionMenu
                foreach ($permissions as $permissionId => $status) {
                    Log::info('Updating or creating PermissionMenu with:', [
                        'menu_id' => $menu->id,
                        'permission_id' => $permissionId,
                        'status' => $status,
                    ]);

                    try {
                        PermissionMenu::updateOrCreate(
                            ['menu_id' => $menu->id, 'permission_id' => $permissionId],
                            ['status' => $status]
                        );
                    } catch (\Exception $e) {
                        Log::error('Error syncing PermissionMenu:', [
                            'menu_id' => $menu->id,
                            'permission_id' => $permissionId,
                            'status' => $status,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
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
