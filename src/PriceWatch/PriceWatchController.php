<?php
declare(strict_types=1);

namespace Cryptoeuro\PriceWatch;

use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Twig_Environment;

class PriceWatchController
{
    /** @var Twig_Environment */
    private $twig;

    /** @var PriceWatchService */
    private $priceWatchService;

    /** @var string[] $assetHashes */
    private $assetHashes;

    public function __construct(
        Twig_Environment $twig,
        PriceWatchService $priceWatchService,
        array $assetHashes
    ) {
        $this->twig = $twig;
        $this->priceWatchService = $priceWatchService;
        $this->assetHashes = $assetHashes;
    }

    public function get(Request $request, ResponseInterface $response): ResponseInterface
    {
        $requestedCurrencies = $this->parseRequestedCurrencies($request->getQueryParams());

        $currencies = $this->priceWatchService->getPrices($requestedCurrencies);

        $sendCurrenciesOnly = !empty($request->getHeader('X-CurrenciesOnly'));

        if ($sendCurrenciesOnly) {
            $html = $this->twig->render('Currencies.twig', ['currencies' => $currencies]);
        } else {
            $html = $this->twig->render('PriceWatch.twig', [
                'currencies' => $currencies,
                'asset_hashes' => $this->assetHashes
            ]);
        }

        $response->getBody()->write($html);

        return $response;
    }

    /**
     * @param   array $queryParams
     * @return  array[]
     */
    public function parseRequestedCurrencies(array $queryParams): array
    {
        $requestedCurrencies = [];

        foreach ($queryParams as $param => $value) {
            $requestedCurrencies[] = [
                'amount' => is_numeric($value) && $value !== '0' ? (float)$value : 1,
                'currency' => (string)$param
            ];
        }

        return $requestedCurrencies;
    }
}
