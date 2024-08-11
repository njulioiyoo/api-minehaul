<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Device;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DevicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any devices.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the device.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Device $device)
    {
        return true;
    }

    /**
     * Determine whether the user can create devices.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the device.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Device $device)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the device.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Device $device)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the device.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Device $device)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the device.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Device $device)
    {
        //
    }
}
