<?php
namespace App\Domain\Warehouse\UseCase;



use App\Data\Dto\FindWarehousePaginate;
use Leugin\KitchenCore\Models\Warehouse\Warehouse;

final class GetWarehousePaginateUseCase
{


    public function __invoke(FindWarehousePaginate $find)
    {
        return Warehouse::filter($find)->with([
            'ingredient'
        ])->paginate($find->getPerPage());
    }
}
