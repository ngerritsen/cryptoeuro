<?php
declare(strict_types=1);

namespace Cryptoeuro\Cryptocurrency;

use Cryptoeuro\HttpService;

class CryptocurrencyMarkets
{
    const BITTREX_API = 'https://bittrex.com/api/v1.1/public';

    private $httpService;

    public function __construct(HttpService $httpService)
    {
        $this->httpService = $httpService;
    }

    public function getAll(): array
    {
        $btcBasedMarkets = $this->getBtcBasedMarkets();
        $marketSummaries = $this->getMarketSummaries();

        return $this->mergeMarketsWithSummaries($btcBasedMarkets, $marketSummaries);
    }

    /**
     * @param string $currency
     * @return array[]
     * @throws InvalidMarketException
     */
    public function get(string $currency): array
    {
        $url = static::BITTREX_API . '/getmarketsummary?market=btc-' . $currency;

        $marketData = $this->httpService->jsonRequest($url);

        if (!$marketData['success'] && $marketData['message'] === 'INVALID_MARKET') {
            throw new InvalidMarketException($currency);
        }

        return $marketData['result'][0];
    }

    private function mergeMarketsWithSummaries(array $btcBasedMarkets, array $marketSummaries): array
    {
        return array_map(function (array $market) use ($marketSummaries): array {
            $summary = $this->findMarketSummary($marketSummaries, $market['MarketCurrency']);

            if (empty($summary)) {
                return null;
            }

            return array_merge($summary, $market);
        }, $btcBasedMarkets);
    }

    private function findMarketSummary(array $marketSummaries, string $currency): array
    {
        foreach ($marketSummaries as $summary) {
            if ('BTC-' . $currency === $summary['MarketName']) {
                return $summary;
            }
        }

        return null;
    }

    private function getBtcBasedMarkets(): array
    {
        $marketData = $this->httpService->jsonRequest(static::BITTREX_API . '/getmarkets');

        return array_filter($marketData['result'], function (array $market): bool {
            return $market['BaseCurrency'] === 'BTC';
        });
    }

    private function getMarketSummaries(): array
    {
        $marketSummaryData = $this->httpService->jsonRequest(static::BITTREX_API . '/getmarketsummaries');

        return $marketSummaryData['result'];
    }
}
