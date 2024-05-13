<?php

namespace App\Foundation\FarmerMarket\Contract;

interface FarmerMarketService
{
    public function buy(string $ingredient):int;
}
