<?php
declare(strict_types=1);

namespace Cryptoeuro\PriceWatch;


use Cryptoeuro\Bitcoin\BitcoinMarket;
use Cryptoeuro\Bitcoin\BitcoinPriceHistory;
use Cryptoeuro\Cryptocurrency\CryptocurrencyMarkets;
use Cryptoeuro\Cryptocurrency\CryptocurrencyPriceHistory;
use Cryptoeuro\Cryptocurrency\InvalidMarketException;

class PriceWatchService
{
    const BITCOIN = 'BTC';

    /** @var @var CryptocurrencyMarkets */
    private $cryptocurrencyMarkets;

    /** @var CryptocurrencyPriceHistory */
    private $cryptocurrencyPriceHistory;

    /** @var @var BitcoinMarket */
    private $bitcoinMarket;

    /** @var BitcoinPriceHistory */
    private $bitcointPriceHistory;

    /**
     * @param CryptocurrencyMarkets $cryptocurrencyMarkets
     * @param CryptocurrencyPriceHistory $cryptocurrencyPriceHistory
     * @param BitcoinMarket $bitcoinMarket
     * @param BitcoinPriceHistory $bitcoinPriceHistory
     */
    public function __construct(
        CryptocurrencyMarkets $cryptocurrencyMarkets,
        CryptocurrencyPriceHistory $cryptocurrencyPriceHistory,
        BitcoinMarket $bitcoinMarket,
        BitcoinPriceHistory $bitcoinPriceHistory
    )
    {
        $this->cryptocurrencyMarkets = $cryptocurrencyMarkets;
        $this->cryptocurrencyPriceHistory = $cryptocurrencyPriceHistory;
        $this->bitcoinMarket = $bitcoinMarket;
        $this->bitcoinPriceHistory = $bitcoinPriceHistory;
    }

    /**
     * @param   array $requestedCurrencies
     * @return  array[]
     */
    public function getPrices($requestedCurrencies): array
    {
        $bitcoinSellPrice = $this->bitcoinMarket->getPrices()['sell'];
        $bitcoinLastDaySellPrice = (float)$this->bitcoinPriceHistory->getLastDay()['sell'];
        $results = [];

        foreach ($requestedCurrencies as $requestedCurrency) {
            $currency = $requestedCurrency['currency'];
            $amount = $requestedCurrency['amount'];

            try {
                list($currentValue, $valueChange) = $this->getCurrencyValues(
                    $currency,
                    $amount,
                    $bitcoinSellPrice,
                    $bitcoinLastDaySellPrice
                );

                $results[] = [
                    'error' => null,
                    'currency' => $currency,
                    'amount' => $amount,
                    'current_value' => $currentValue,
                    'value_change' => $valueChange
                ];
            } catch (InvalidMarketException $exception) {
                $results[] = [
                    'error' => $exception->getMessage()
                ];
            }
        }

        return $results;
    }

    private function calculateEuroValue(
        float $marketSellPrice,
        float $amount,
        float $bitcoinSellPrice
    ): float
    {
        return $bitcoinSellPrice * $marketSellPrice * $amount;
    }

    private function calculateValueChange(float $currentValue, float $lastDayValue)
    {
        return ($currentValue - $lastDayValue) / $lastDayValue * 100;
    }

    private function getCurrencyValues(
        string $currency,
        float $amount,
        float $bitcoinSellPrice,
        float $bitcoinLastDaySellPrice
    ): array
    {
        list($currentSellPrice, $lastDaySellPrice) = $this->getCurrencySellPrices($currency);

        $currentValue = $this->calculateEuroValue($currentSellPrice, $amount, $bitcoinSellPrice);
        $lastDayValue = $this->calculateEuroValue($lastDaySellPrice, $amount, $bitcoinLastDaySellPrice);
        $valueChange = $this->calculateValueChange($currentValue, $lastDayValue);

        return [$currentValue, $valueChange];
    }

    private function getCurrencySellPrices(string $currency): array
    {
        if (strtoupper($currency) === self::BITCOIN) {
            $currentSellPrice = 1;
            $lastDaySellPrice = 1;
        } else {
            $currentSellPrice = $this->cryptocurrencyMarkets->get($currency)['Bid'];
            $lastDaySellPrice = (float)$this->cryptocurrencyPriceHistory->getLastDay($currency)['sell'];
        }

        return [$currentSellPrice, $lastDaySellPrice];
    }
}
