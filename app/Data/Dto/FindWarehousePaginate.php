<?php

namespace App\Data\Dto;



use Leugin\KitchenCore\Data\Dto\FindWarehouse;
use Leugin\KitchenCore\Traits\PaginationDto;

class FindWarehousePaginate extends FindWarehouse
{
    use PaginationDto;
}
