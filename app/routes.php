<?php
declare(strict_types=1);

use Cryptoeuro\PriceWatch\PriceWatchController;
use Slim\App;

/** @var App $app */
$app = $container->get(App::class);

$app->get('/', PriceWatchController::class . ':get');

$app->run();
