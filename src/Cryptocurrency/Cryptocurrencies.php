<?php
declare(strict_types=1);

namespace Cryptoeuro\Cryptocurrency;

use Cryptoeuro\PdoFactory;
use PDO;

class Cryptocurrencies
{
    private $pdoFactory;

    public function __construct(PdoFactory $pdoFactory)
    {
        $this->pdoFactory = $pdoFactory;
    }

    public function store(array $currencies)
    {
        $connection = $this->pdoFactory->createConnection();
        $values = $this->createValues($currencies);

        $statement = $connection->prepare("
            INSERT INTO cryptocurrency (currency, name, logo_url)
            VALUES {$values}
            ON DUPLICATE KEY UPDATE logo_url=logo_url, name=name
        ");

        $statement->execute();
    }

    public function getAll(): array
    {
        $connection = $this->pdoFactory->createConnection();

        $statement = $connection->prepare("
          SELECT currency, name FROM cryptocurrency
          ORDER BY currency
        ");

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    private function createValues(array $currencies): string
    {
        $valueStrings = array_map(function (array $currency): string {
            return "(
                '{$currency['MarketCurrency']}',
                '{$currency['MarketCurrencyLong']}',
                '{$currency['LogoUrl']}'
            )";
        }, $currencies);

        return implode(',', $valueStrings);
    }
}
