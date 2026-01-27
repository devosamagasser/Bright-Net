<?php

namespace App\Modules\Shared\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Lightweight resource for easy access data (id and name only).
 */
class EasyAccessResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        // Handle both objects and arrays
        if (is_array($this->resource)) {
            return [
                'id' => $this->resource['id'] ?? null,
                'name' => $this->resource['name'] ?? null,
            ];
        }

        return [
            'id' => $this->id ?? null,
            'name' => $this->name ?? null,
        ];
    }
}

