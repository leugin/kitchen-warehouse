<?php

namespace App\Jobs;

use App\Domain\Warehouse\UseCase\MakeAOrder;
use App\Domain\Warehouse\UseCase\MarkOrderFinished;
use App\Exceptions\NotEnoughIngredientException;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Leugin\KitchenCore\Models\Order\Order;

class OrderCreated implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    /**
     * Create a new job instance.
     */
    public function __construct(private Order $order)
    {
        $this->connection = 'rabbitmq';

    }

    /**
     * @throws Exception
     */
    public function handle():bool
    {
        try {
            $makeAOrder = app()->make(MakeAOrder::class);

            DB::beginTransaction();
            if ($makeAOrder->__invoke($this->order)) {
                app()->make(MarkOrderFinished::class)->__invoke($this->order);
            }
            DB::commit();
        }catch (NotEnoughIngredientException $exception)
        {
            Bus::chain([
                new RequestIngredient($exception->ingredient),
                new RetryOrder($this->order)
            ])->dispatch();
            DB::rollBack();
            return false;
        }catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
         }

        return true;
    }

    /**
     * @return string[]
     */
    public function tags(): array
    {
        return [self::class];
    }

    /**
     * @return string
     */
    public function displayName(): string
    {
        return "kitchen OrderCreated";
    }

    /**
     * The job failed to process.
     *
     * @param Exception $exception
     */
    public function failed( $exception): void
    {
        logger(['emit Tracker Hire Segment Exception' => [
            'message' => $exception->getMessage(),
        ]]);
    }
}
