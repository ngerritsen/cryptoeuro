<?php
declare(strict_types=1);

use Cryptoeuro\Cryptocurrency\CryptocurrencyController;
use Cryptoeuro\PriceWatch\PriceWatchController;
use Slim\App;

/** @var App $app */
$app = $container->get(App::class);

$app->get('/', PriceWatchController::class . ':get');
$app->get('/api/currencies', CryptocurrencyController::class . ':getAll');

$app->run();
