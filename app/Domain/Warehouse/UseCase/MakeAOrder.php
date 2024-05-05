<?php

namespace App\Domain\Warehouse\UseCase;

use App\Exceptions\NotEnoughIngredientException;
use Leugin\AlegraLaravel\Framework\Model\Order;
use Leugin\AlegraLaravel\Framework\Model\Warehouse;

class MakeAOrder
{
    public function __construct()
    {
    }


    public function __invoke(Order $order):bool
    {
        $ingredientsNeed =  $order->ingredients()
            ->withPivot(['required'])
            ->whereRaw('required > available')
            ->get();
        $ingredientsStock = Warehouse::query()
            ->whereIn('ingredient_id', $ingredientsNeed->pluck('id')->toArray())
            ->get();

        foreach ($ingredientsNeed as $ingredientNeed)
        {
            $ingredientStock = $ingredientsStock->where('ingredient_id', $ingredientNeed->id)->first();
            if ($ingredientStock->stock < $ingredientNeed->pivot->required)
            {
                throw  new NotEnoughIngredientException($ingredientNeed, $order->id);
            }
            $ingredientStock->update(['stock' => $ingredientStock->stock - $ingredientNeed->pivot->required]);
        }
        return true;
    }
}
