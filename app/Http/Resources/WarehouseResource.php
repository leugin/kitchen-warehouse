<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Leugin\KitchenCore\Models\Warehouse\Warehouse;

/**
 * @mixin Warehouse
 */
class WarehouseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  [
            'ingredient_id' => $this->ingredient_id,
            'label' => $this->label,
            'stock' => $this->stock,
            'updated'=>$this->updated_at
        ];
    }
}
