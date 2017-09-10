<?php
declare(strict_types=1);

namespace Cryptoeuro\Bitcoin;

use Cryptoeuro\HttpService;

class BitcoinMarket
{
    const COINBASE_API = 'https://api.coinbase.com/v2';

    private $httpService;

    public function __construct(HttpService $httpService)
    {
        $this->httpService = $httpService;
    }

    /**
     * @return float[]
     */
    public function getPrices(): array
    {
        $euroPriceUrl = static::COINBASE_API . '/prices/BTC-EUR';

        $sellData = $this->httpService->jsonRequest($euroPriceUrl . '/sell');
        $buyData = $this->httpService->jsonRequest($euroPriceUrl . '/buy');

        return [
            'sell' => (float)$sellData['data']['amount'],
            'buy' => (float)$buyData['data']['amount']
        ];
    }
}
