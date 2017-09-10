<?php
declare(strict_types=1);

namespace Cryptoeuro\Scraper;

use Cryptoeuro\Bitcoin\BitcoinMarket;
use Cryptoeuro\Bitcoin\BitcoinPriceHistory;
use Cryptoeuro\Cryptocurrency\Cryptocurrencies;
use Cryptoeuro\Cryptocurrency\CryptocurrencyMarkets;
use Cryptoeuro\Cryptocurrency\CryptocurrencyPriceHistory;

class Scraper
{
    /** @var @var CryptocurrencyMarkets */
    private $cryptocurrencyMarkets;

    /** @var @var BitcoinMarket */
    private $bitcoinMarket;

    /** @var @var Cryptocurrencies */
    private $cryptocurrencies;

    /** @var @var CryptocurrencyPriceHistory */
    private $cryptocurrencyPriceHistory;

    /** @var @var BitcoinPriceHistory */
    private $bitcoinPriceHistory;

    public function __construct(
        CryptocurrencyMarkets $cryptocurrencyMarkets,
        BitcoinMarket $bitcoinMarket,
        Cryptocurrencies $cryptocurrencies,
        CryptocurrencyPriceHistory $cryptocurrencyPriceHistory,
        BitcoinPriceHistory $bitcoinPriceHistory
    )
    {
        $this->cryptocurrencyMarkets = $cryptocurrencyMarkets;
        $this->bitcoinMarket = $bitcoinMarket;
        $this->cryptocurrencies = $cryptocurrencies;
        $this->cryptocurrencyPriceHistory = $cryptocurrencyPriceHistory;
        $this->bitcoinPriceHistory = $bitcoinPriceHistory;
    }

    public function scrape()
    {
        $currencies = $this->cryptocurrencyMarkets->getAll();
        $bitcoinPrices = $this->bitcoinMarket->getPrices();

        $this->cryptocurrencies->store($currencies);
        $this->cryptocurrencyPriceHistory->update($currencies);
        $this->bitcoinPriceHistory->update($bitcoinPrices['sell'], $bitcoinPrices['buy']);
    }
}
