<?php
declare(strict_types = 1);

function write($value) {
  return htmlspecialchars((string)$value);
}

function tagsToCurrencyData(array $tags): array {
  $currencies = [];
  $btcSellPrice = getBtcSellPrice();

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
      'euro' => coinToEuro($amount, $stats['Ask'], $btcSellPrice),
      'amount' => $amount
    ];
  }

  return $currencies;
}

function getBtcSellPrice(): float {
  return (float)getBtcStats()['amount'];
}

function getBtcStats(): array {
  $raw = file_get_contents('https://api.coinbase.com/v2/prices/BTC-EUR/sell');
  $data = json_decode($raw, true);

  return $data['data'];
}

function getCoinStats(string $tag) {
  $raw = file_get_contents('https://bittrex.com/api/v1.1/public/getmarketsummary?market=btc-' . urlencode($tag));
  $data = json_decode($raw, true);

  return $data['result'][0];
}

function coinToEuro(float $amount, float $coinAsk, float $btcSellPrice): float {
  return $amount * ($coinAsk * $btcSellPrice);
}

function parseAmount($amount): float {
  $amount = (float)$amount;

  return (int)$amount === 0 ? 1 : $amount;
}
