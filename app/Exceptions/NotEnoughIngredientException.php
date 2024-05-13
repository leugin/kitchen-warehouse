<?php

namespace App\Exceptions;


use Leugin\KitchenCore\Models\Ingredient\Ingredient;

class NotEnoughIngredientException extends \DomainException
{
    public function __construct(
        public readonly Ingredient $ingredient,
        public readonly int $orderId
    ) {
        parent::__construct(__("not enough {$this->ingredient->name} to cook $this->orderId "), 400,);
    }
}
