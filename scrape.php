<?php
declare(strict_types = 1);

use Cryptoeuro\Cryptocurrency\CryptocurrencyMarkets;
use Cryptoeuro\Cryptocurrency\Cryptocurrencies;
use Cryptoeuro\Cryptocurrency\CryptocurrencyPriceHistory;
use Cryptoeuro\Bitcoin\BitcoinMarket;
use Cryptoeuro\Bitcoin\BitcoinPriceHistory;

require __DIR__ . '/../app/di.php';

/** @var CryptocurrencyMarkets $cryptocurrencyMarkets */
$cryptocurrencyMarkets = $container->get(CryptocurrencyMarkets::class);
/** @var BitcoinMarket $bitcoinMarket */
$bitcoinMarket = $container->get(BitcoinMarket::class);
/** @var Cryptocurrencies $cryptocurrencies */
$cryptocurrencies = $container->get(Cryptocurrencies::class);
/** @var CryptocurrencyPriceHistory $cryptocurrencyPriceHistory */
$cryptocurrencyPriceHistory = $container->get(CryptocurrencyPriceHistory::class);
/** @var BitcoinPriceHistory $bitcoinPriceHistory */
$bitcoinPriceHistory = $container->get(BitcoinPriceHistory::class);

$currencies = $cryptocurrencyMarkets->getAll();
$bitcoinPrices = $bitcoinMarket->getPrices();

$cryptocurrencies->store($currencies);
$cryptocurrencyPriceHistory->update($currencies);
$bitcoinPriceHistory->update($bitcoinPrices['sell'], $bitcoinPrices['buy']);
