<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MenuPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any menu.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the menu.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Menu $menu)
    {
        return true;
    }

    /**
     * Determine whether the user can create menu.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the menu.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Menu $menu)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the menu.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Menu $menu)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the menu.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Menu $menu)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the menu.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Menu $menu)
    {
        //
    }
}
