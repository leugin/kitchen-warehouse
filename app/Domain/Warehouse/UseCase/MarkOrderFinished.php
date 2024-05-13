<?php

namespace App\Domain\Warehouse\UseCase;

use App\Events\OrderFinished;
use Leugin\KitchenCore\Data\Values\OrderStatus;
use Leugin\KitchenCore\Models\Order\Order;

class MarkOrderFinished
{

    public function __invoke(Order $order){

        $order->update([
            'status'=>OrderStatus::FINISHED->value
        ]);

        OrderFinished::dispatch($order);

        return $order;
    }
}
