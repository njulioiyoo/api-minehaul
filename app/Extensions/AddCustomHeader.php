<?php

declare(strict_types=1);

namespace App\Extensions;

use Dedoc\Scramble\Extensions\OperationExtension;
use Dedoc\Scramble\Support\Generator\Operation;
use Dedoc\Scramble\Support\Generator\Parameter;
use Dedoc\Scramble\Support\Generator\Schema;
use Dedoc\Scramble\Support\Generator\Types\StringType;
use Dedoc\Scramble\Support\RouteInfo;

class AddCustomHeader extends OperationExtension
{
    public function handle(Operation $operation, RouteInfo $routeInfo)
    {
        $operation->addParameters([
            Parameter::make('x-api-token', 'header')
                ->setSchema(
                    Schema::fromType(new StringType)
                )
                ->required(true)
                ->example('w7rL2X0izsM26xWUFYSjF02qdCDunKf2mrzZRYXvj9X0opxPKe5s4IBZT49O'),
        ]);
    }
}
