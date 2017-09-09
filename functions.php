<?php
declare(strict_types = 1);

function write($value) {
  return htmlspecialchars((string)$value);
}

function tagsToCurrencyData(array $tags): array {
  $currencies = [];
  $btcPerEuro = getBtcPerEuro();

  foreach ($tags as $tag => $amount) {
    $amount = parseAmount($amount);
    $tag = strtoupper(htmlspecialchars($tag));
    $stats = getCoinStats($tag);

    if (empty($stats)) {
      $currencies[] = [
        'tag' => $tag,
        'valid' => false
      ];

      continue;
    }

    $currencies[] = [
      'tag' => $tag,
      'valid' => true,
      'euro' => coinToEuro($amount, $stats['Ask'], $btcPerEuro),
      'amount' => $amount
    ];
  }

  return $currencies;
}

function getBtcPerEuro(): float {
  return (float)file_get_contents('https://blockchain.info/tobtc?currency=EUR&value=1');
}

function getCoinStats(string $tag) {
  $raw = file_get_contents('https://bittrex.com/api/v1.1/public/getmarketsummary?market=btc-' . urlencode($tag));
  $data = json_decode($raw, true);

  return $data['result'][0];
}

function coinToEuro(float $amount, float $coinAsk, float $btcPerEuro): float {
  return round($amount * ($coinAsk / $btcPerEuro), 2);
}

function parseAmount($amount): float {
  $amount = (float)$amount;

  return $amount === 0 ? 1 : $amount;
}
