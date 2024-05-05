<?php

namespace App\Domain\Warehouse\UseCase;

use App\Events\OrderFinished;
use Leugin\AlegraLaravel\App\Domain\Data\OrderStatus;
use Leugin\AlegraLaravel\Framework\Model\Order;

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
