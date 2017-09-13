<?php
declare(strict_types=1);

namespace Cryptoeuro\Cryptocurrency;

use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class CryptocurrencyController
{
    /** @var Cryptocurrencies */
    private $cryptocurrencies;

    public function __construct(Cryptocurrencies $cryptocurrencies)
    {
        $this->cryptocurrencies = $cryptocurrencies;
    }

    public function getAll(Request $request, Response $response): ResponseInterface
    {
        $currencies = $this->cryptocurrencies->getAll();

        return $response->withJson($currencies);
    }
}
