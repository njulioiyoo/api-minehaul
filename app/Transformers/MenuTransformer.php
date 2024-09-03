<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Menu;

class MenuTransformer
{
    public function transform(Menu $menu): array
    {
        return [
            'type' => 'menus',
            'id' => $menu->id,
            'attributes' => [
                'id' => $menu->id,
                'name' => $menu->name,
                'created_at' => $menu->created_at,
                'updated_at' => $menu->updated_at,
            ],
        ];
    }
}
