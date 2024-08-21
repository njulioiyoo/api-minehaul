<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasMenuAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role;

class Menu extends Model
{
    use HasFactory;
    use HasMenuAttributes;
    use SoftDeletes;

    protected $fillable = ['name', 'key', 'url', 'parent_id', 'position', 'deleted_at'];

    public $timestamps = false;

    protected $rolesToSync = [];

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
