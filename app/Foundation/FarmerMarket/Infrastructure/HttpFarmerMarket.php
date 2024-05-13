<?php

namespace App\Foundation\FarmerMarket\Infrastructure;

use App\Foundation\FarmerMarket\Contract\FarmerMarketService;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class HttpFarmerMarket implements FarmerMarketService
{
    public function __construct(
        private readonly  string $url
    )
    {
    }
    public function buy(string $ingredient): int
    {
        $request = $this->request(strtolower($ingredient));
       return $request->json('quantitySold');
    }

    public function request(string $ingredient): Response
    {
        return Http::get($this->url, [
            'ingredient' => $ingredient,
        ]);
    }


}
