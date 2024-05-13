<?php

namespace App\Foundation\FarmerMarket\Infrastructure;

use App\Foundation\FarmerMarket\Contract\FarmerMarketService;
use Illuminate\Support\Facades\Http;

class FakerFarmerMarket implements FarmerMarketService
{

    public function buy(string $ingredient): int
    {
         return rand(0, 100);
    }



}
