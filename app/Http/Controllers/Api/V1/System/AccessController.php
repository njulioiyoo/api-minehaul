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

        $role->syncPermissions($permissions);

        return new DataResponse($role);
    }
}
