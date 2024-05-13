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
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Leugin\KitchenCore\Models\Order\Order;

class RetryOrder implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly Order $order,
        private  readonly int $intent = 1
    )
    {
        $this->connection = 'rabbitmq';


    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array<int, object>
     */
    public function middleware(): array
    {
        return [new WithoutOverlapping($this->order->id)];
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
                new RetryOrder($this->order, $this->intent + 1)
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
    public function failed(Exception $exception): void
    {
        Log::error("erroe en queue", [
            'message' => $exception->getMessage(),
        ]);
    }
}
