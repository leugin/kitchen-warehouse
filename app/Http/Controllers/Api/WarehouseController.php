<?php

namespace App\Http\Controllers\Api;

use App\Features\FindWarehousePaginateFeature;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class WarehouseController extends Controller
{
    public function paginate(FindWarehousePaginateFeature $feature): JsonResponse
    {
        return $feature->__invoke();
    }
}
