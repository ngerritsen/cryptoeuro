<?php
$currencies = [];
$btcPerEuro = (float)file_get_contents('https://blockchain.info/tobtc?currency=EUR&value=1');

foreach ($_GET as $tag => $amount) {
  $amount = empty($amount) ? 1 : $amount;
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

function getCoinStats($tag) {
  $raw = file_get_contents('https://bittrex.com/api/v1.1/public/getmarketsummary?market=btc-' . urlencode($tag));
  $data = json_decode($raw, true);

  return $data['result'][0];
}

function coinToEuro($amount, $coinAsk, $btcPerEuro) {
  return round($amount * ($coinAsk / $btcPerEuro), 2);
}
?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="keywords" content="crypto, currencies, euro, bitcoin">
<meta name="description" content="Converts cryptocurrencies to euro">

<title>Cryptoeuro</title>

<link href="https://fonts.googleapis.com/css?family=Ubuntu+Mono" rel="stylesheet">
<link href="style.css" rel="stylesheet">

<div class="container">
  <main class="js-container">
    <?php foreach($currencies as $currency): ?>
      <section class="section">
        <?php if ($currency['valid'] === false) : ?>
            <p class="error">Invalid currency "<?= $currency['tag'] ?>"</p>
        <?php else : ?>
            <h2 class="currency"><?= $currency['amount'] ?> <?= $currency['tag'] ?></h2>
            <p class="value">€ <?= $currency['euro'] ?></p>
        <?php endif; ?>
      </section>
    <?php endforeach ?>
  </main>
  <footer>
    <p class="js-time time"></p>

    <p class="explain">
      Type the currencies to convert in the query string, optionally with an amount, for example:
      <i>"?LTC=120&amp;ETH=32.5"</i>. Auto refreshes every minute.
    </p>

    <p class="info">
      Currency is converted to BTC using <a class="link" href="https://bittrex.com">Bittrex</a>,
      then to EUR using <a class="link" href="https://blockchain.info">blockchain.info</a>.
    </p>
  <footer>
</div>

<script src="main.js"></script>
