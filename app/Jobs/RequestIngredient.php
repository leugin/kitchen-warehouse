<?php

namespace App\Jobs;

use App\Foundation\FarmerMarket\Contract\FarmerMarketService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Leugin\KitchenCore\Models\Ingredient\Ingredient;
use Leugin\KitchenCore\Models\Warehouse\Warehouse;

class RequestIngredient implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly Ingredient $ingredient
    )
    {
    }

    public function __invoke(FarmerMarketService $service): int
    {
        $newStock = $service->buy($this->ingredient->name);
        if ($newStock > 0) {
            $warehouse = Warehouse::query()->where('ingredient_id', $this->ingredient->id)->first();
            $warehouse->update([
                'stock' => $warehouse->stock + $newStock,
            ]);
        }
        return $newStock;
    }
}
