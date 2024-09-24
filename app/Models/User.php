<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'people_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($user) {
            $user->uid = $user->exists ? $user->uid : Str::uuid()->toString();
        });
    }

    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }

    public function people()
    {
        return $this->belongsTo(People::class, 'people_id', 'id')->select('id', 'full_name', 'account_id');
    }

    public function pits()
    {
        return $this->hasManyThrough(Pit::class, Account::class, 'id', 'account_id', 'account_id', 'id');
    }

    public function getMenusForRole(): Collection
    {
        // Get the IDs of the roles the user has
        $roleIds = $this->roles->pluck('id');

        // Get the menus associated with these roles and filter for active status
        $menus = Menu::select('id', 'name', 'key', 'icon', 'url', 'parent_id', 'position')
            ->whereIn('id', function ($query) use ($roleIds) {
                $query->select('menu_id')
                    ->from('role_menus')
                    ->whereIn('role_id', $roleIds);
            })
            ->where('status', 'active') // Filter for active menus
            ->get();

        // Convert menus to a tree structure
        return $this->buildMenuTree($menus);
    }

    /**
     * Build a tree structure from the flat list of menus.
     */
    protected function buildMenuTree(Collection $menus): Collection
    {
        // Create a map of menu items by their ID
        $menuMap = $menus->keyBy('id');

        // Initialize an empty collection to hold the tree structure
        $tree = collect();

        foreach ($menus as $menu) {
            if ($menu->parent_id === null) {
                // If the menu has no parent, add it to the root of the tree
                $tree->push($this->buildMenuNode($menu, $menuMap));
            }
        }

        return $tree;
    }

    /**
     * Build a menu node with its children.
     */
    protected function buildMenuNode(Menu $menu, Collection $menuMap): array
    {
        $node = $menu->toArray();
        $node['children'] = $menuMap->filter(function ($item) use ($menu) {
            return $item->parent_id === $menu->id;
        })->map(function ($item) use ($menuMap) {
            return $this->buildMenuNode($item, $menuMap);
        })->values(); // Ensure children is a simple array

        return $node;
    }
}
