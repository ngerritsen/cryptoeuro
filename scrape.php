<?php
declare(strict_types = 1);

require('src/CryptocurrencyMarket.php');
require('src/BitcoinMarket.php');
require('src/HttpService.php');

$httpService = new HttpService();
$cryptocurrencyMarket = new CryptocurrencyMarket($httpService);
$bitcoinMarket = new BitcoinMarket($httpService);

echo '<pre>';
echo(json_encode($cryptocurrencyMarket->getAll()));
echo(json_encode($bitcoinMarket->getPrices()));
