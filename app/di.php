<?php
declare(strict_types=1);

use Cryptoeuro\Bitcoin\BitcoinMarket;
use Cryptoeuro\Bitcoin\BitcoinPriceHistory;
use Cryptoeuro\Cryptocurrency\Cryptocurrencies;
use Cryptoeuro\Cryptocurrency\CryptocurrencyMarkets;
use Cryptoeuro\Cryptocurrency\CryptocurrencyPriceHistory;
use Cryptoeuro\HttpService;
use Cryptoeuro\PdoFactory;
use Cryptoeuro\PriceWatch\PriceWatchController;
use Cryptoeuro\PriceWatch\PriceWatchService;
use Cryptoeuro\Scraper\Scraper;
use League\Container\Argument\RawArgument;
use League\Container\Container;
use Slim\App;
use Slim\CallableResolver;

const ROOT = __DIR__ . '/..';

require ROOT . '/vendor/autoload.php';

$assetHashes = require ROOT . '/etc/hashes.php';
$config = require ROOT . '/etc/config.php';

$container = new Container();

$container->add(Twig_Environment::class, function (): Twig_Environment {
    $loader = new Twig_Loader_Filesystem(ROOT . '/templates');
    return new Twig_Environment($loader, [
        'cache' => ROOT . '/.template-cache',
        'auto_reload' => true
    ]);
});

$container->add(App::class, function () use ($container): App {
    return new App([
        'settings' => [
            'displayErrorDetails' => true
        ],
        'callableResolver' => new CallableResolver($container)
    ]);
});

$container->add(HttpService::class);
$container->add(PdoFactory::class)->withArgument(new RawArgument($config['mysql']));

$container->add(BitcoinMarket::class)->withArgument(HttpService::class);
$container->add(BitcoinPriceHistory::class)->withArgument(PdoFactory::class);

$container->add(CryptocurrencyMarkets::class)->withArgument(HttpService::class);
$container->add(CryptocurrencyPriceHistory::class)->withArgument(PdoFactory::class);
$container->add(Cryptocurrencies::class)->withArgument(PdoFactory::class);

$container->add(PriceWatchService::class)->withArguments([
    CryptocurrencyMarkets::class,
    CryptocurrencyPriceHistory::class,
    BitcoinMarket::class,
    BitcoinPriceHistory::class
]);

$container->add(PriceWatchController::class)->withArguments([
    Twig_Environment::class,
    PriceWatchService::class,
    new RawArgument($assetHashes)
]);

$container->add(Scraper::class)->withArguments([
    CryptocurrencyMarkets::class,
    BitcoinMarket::class,
    Cryptocurrencies::class,
    CryptocurrencyPriceHistory::class,
    BitcoinPriceHistory::class
]);
