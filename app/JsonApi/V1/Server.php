<?php

declare(strict_types=1);

namespace App\JsonApi\V1;

use App\JsonApi\V1\Configurations\Device\DeviceSchema;
use App\JsonApi\V1\System\Permission\PermissionSchema;
use App\JsonApi\V1\System\Role\RoleSchema;
use App\JsonApi\V1\Users\UserSchema;
use LaravelJsonApi\Core\Server\Server as BaseServer;

class Server extends BaseServer
{
    /**
     * The base URI namespace for this server.
     */
    protected string $baseUri = '/api/v1';

    /**
     * Bootstrap the server when it is handling an HTTP request.
     */
    public function serving(): void
    {
        // no-op
    }

    /**
     * Get the server's list of schemas.
     */
    protected function allSchemas(): array
    {
        return [
            UserSchema::class,
            DeviceSchema::class,
            PermissionSchema::class,
            RoleSchema::class,
        ];
    }
}
