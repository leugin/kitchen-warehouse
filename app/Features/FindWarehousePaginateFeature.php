<?php

namespace App\Features;


use App\Data\Dto\FindWarehousePaginate;
use App\Domain\Warehouse\UseCase\GetWarehousePaginateUseCase;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\WarehouseResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Leugin\KitchenCore\Helper\Response;


class FindWarehousePaginateFeature
{

    public function __construct(
        private readonly GetWarehousePaginateUseCase $warehousePaginate,
        private readonly Request $request
    )
    {
    }
    public function __invoke(): JsonResponse
    {
        $find  = FindWarehousePaginate::from($this->request->toArray());
        $response = $this->warehousePaginate->__invoke($find);
        $resource  = new PaginateResource($response, fn($item)=> new WarehouseResource($item));
        $resource->additional($find->toArray());

        return response()->json(Response::success($resource));

    }
}
