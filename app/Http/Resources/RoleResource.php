<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,

            // Lista simple para UI (badges)
            'permissions' => $this->whenLoaded('permissions', function () {
                return $this->permissions->map(fn($p) => [
                    'id' => $p->id,
                    'name' => $p->name,
                ]);
            }, $this->permissions->pluck('name')->values()),

            // NUEVO: conteo optimizado para tabla
            'permissions_count' => $this->whenLoaded('permissions', function () {
                return $this->permissions->count();
            }, $this->permissions()->count()),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
