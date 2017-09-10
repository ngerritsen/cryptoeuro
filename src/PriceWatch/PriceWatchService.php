<?php
declare(strict_types=1);

namespace Cryptoeuro\PriceWatch;


use Cryptoeuro\Bitcoin\BitcoinMarket;
use Cryptoeuro\Cryptocurrency\CryptocurrencyMarkets;
use Cryptoeuro\Cryptocurrency\InvalidMarketException;

class PriceWatchService
{
    /** @var @var CryptocurrencyMarkets */
    private $cryptocurrencyMarkets;

    /** @var @var BitcoinMarket */
    private $bitcoinMarket;

    public function __construct(
        CryptocurrencyMarkets $cryptocurrencyMarkets,
        BitcoinMarket $bitcoinMarket
    )
    {
        $this->cryptocurrencyMarkets = $cryptocurrencyMarkets;
        $this->bitcoinMarket = $bitcoinMarket;
    }

    /**
     * @param   array $requestedCurrencies
     * @return  array[]
     */
    public function getPrices($requestedCurrencies): array
    {
        $bitcoinSellPrice = $this->bitcoinMarket->getPrices()['sell'];
        $results = [];

        foreach ($requestedCurrencies as $requestedCurrency) {
            $currency = $requestedCurrency['currency'];
            $amount = $requestedCurrency['amount'];

            try {
                $market = $this->cryptocurrencyMarkets->get($currency);

                $results[] = [
                    'error' => null,
                    'currency' => $currency,
                    'amount' => $amount,
                    'current_value' => $this->calculateCurrentValue($market['Bid'], $amount, $bitcoinSellPrice),
                ];
            } catch (InvalidMarketException $exception) {
                $results[] = [
                    'error' => $exception->getMessage()
                ];
            }
        }

        return $results;
    }

    private function calculateCurrentValue(
        float $marketSellPrice,
        float $amount,
        float $bitcoinSellPrice
    ): float
    {
        return $bitcoinSellPrice * $marketSellPrice * $amount;
    }
}
