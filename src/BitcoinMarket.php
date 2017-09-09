<?php
declare(strict_types = 1);

class BitcoinMarket {
    const COINBASE_API = 'https://api.coinbase.com/v2';

    private $httpService;

    public function __construct(HttpService $httpService) {
        $this->httpService = $httpService;
    }

    public function getPrices(): array {
        $euroPriceUrl = static::COINBASE_API . '/prices/BTC-EUR';

        $sellData = $this->httpService->jsonRequest($euroPriceUrl . '/sell');
        $buyData = $this->httpService->jsonRequest($euroPriceUrl . '/buy');

        return [
            'sell' => $sellData['data']['amount'],
            'buy' => $buyData['data']['amount']
        ];
    }
}
