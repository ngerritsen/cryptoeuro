<?php
declare(strict_types=1);

namespace Cryptoeuro\Bitcoin;

use Cryptoeuro\PdoFactory;

class BitcoinPriceHistory
{
    private $pdoFactory;

    public function __construct(PdoFactory $pdoFactory)
    {
        $this->pdoFactory = $pdoFactory;
    }

    public function update(float $sell, float $buy)
    {
        $connection = $this->pdoFactory->createConnection();

        $statement = $connection->prepare("
            INSERT INTO bitcoin_price (time, sell, buy)
            VALUES (NOW(), :sell, :buy)
            ON DUPLICATE KEY UPDATE sell=sell, buy=buy
        ");

        $statement->execute([
            ':sell' => $sell,
            ':buy' => $buy
        ]);
    }
}
