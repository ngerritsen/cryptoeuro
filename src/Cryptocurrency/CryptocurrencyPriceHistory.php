<?php
declare(strict_types=1);

namespace Cryptoeuro\Cryptocurrency;

use Cryptoeuro\PdoFactory;

class CryptocurrencyPriceHistory
{
    private $pdoFactory;

    public function __construct(PdoFactory $pdoFactory)
    {
        $this->pdoFactory = $pdoFactory;
    }

    public function update(array $currencies)
    {
        $connection = $this->pdoFactory->createConnection();
        $values = $this->createValues($currencies);

        $statement = $connection->prepare("
            INSERT INTO cryptocurrency_price (currency, time, sell, buy)
            VALUES {$values}
            ON DUPLICATE KEY UPDATE sell=sell, buy=buy
        ");

        $statement->execute();
    }

    public function getLastDay(string $currency)
    {
        $connection = $this->pdoFactory->createConnection();

        $statement = $connection->prepare("
            SELECT * FROM cryptocurrency_price
            WHERE currency='{$currency}'
            ORDER BY ABS(TIMESTAMPDIFF(
                SECOND,
                time,
                TIMESTAMPADD(
                    HOUR,
                    -24,
                    NOW()
                )
            )) LIMIT 1;
        ");

        $statement->execute();

        return $statement->fetchAll()[0];
    }

    private function createValues(array $currencies): string
    {
        $valueStrings = array_map(function (array $currency): string {
            return "(
                '{$currency['MarketCurrency']}',
                NOW(),
                '{$currency['Bid']}',
                '{$currency['Ask']}'
            )";
        }, $currencies);

        return implode(',', $valueStrings);
    }
}
