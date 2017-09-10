<?php
declare(strict_types=1);

namespace Cryptoeuro;

use PDO;

class PdoFactory
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function createConnection(): PDO
    {
        $config = $this->config;
        $dsn = 'mysql:host=' . $config['host'] . ';port=3306;dbname=' . $config['db'];

        $connection = new PDO($dsn, $config['username'], $config['password']);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $connection;
    }
}
