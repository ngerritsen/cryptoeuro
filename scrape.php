<?php
declare(strict_types=1);

use Cryptoeuro\Scraper\Scraper;

require __DIR__ . '/app/di.php';

/** @var Scraper $scraper */
$scraper = $container->get(Scraper::class);

$scraper->scrape();

echo 'Done!';
