<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\System;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use LaravelJsonApi\Core\Responses\DataResponse;
use Spatie\Permission\Models\Role;

class AccessController extends Controller
{
    /**
     * Update roles for a user.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUserRoles(Request $request, $userId)
    {
        $input = $request->json()->all();
        $roleId = $input['data']['attributes']['roles'] ?? [];

        $user = User::findOrFail($userId);

        $user->syncRoles($roleId);

        // Optionally sync permissions for the assigned roles
        $permissions = Role::whereIn('id', $roleId)
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('id')
            ->unique()
            ->toArray();

        $user->syncPermissions($permissions);

        return new DataResponse($user);
    }

    /**
     * Update permissions for a role.
     *
     * @param  int  $roleId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRolePermissions(Request $request, $roleId)
    {
        $input = $request->json()->all();
        $permissions = $input['data']['attributes']['permissions'] ?? [];

        $role = Role::findOrFail($roleId);

        // Sinkronisasi permissions untuk role
        $role->syncPermissions($permissions);

        return new DataResponse($role);
    }
}
