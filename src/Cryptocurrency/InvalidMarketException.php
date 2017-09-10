<?php
declare(strict_types = 1);

namespace Cryptoeuro\Cryptocurrency;

use Exception;

class InvalidMarketException extends Exception
{
    public function __construct($currency) {
        $message = 'Invalid currency: "' . strtoupper($currency) . '".';

        parent::__construct($message);
    }
}
