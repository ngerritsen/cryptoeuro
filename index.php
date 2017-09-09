<?php
declare(strict_types = 1);

require_once('functions.php');

$assetHashes = require_once('hashes.php');

$currencies = tagsToCurrencyData($_GET);
?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="keywords" content="crypto, currencies, euro, bitcoin">
<meta name="description" content="Converts cryptocurrencies to euro">
<meta name="keywords" content="crypto, currencies, euro, bitcoin">

<title>Cryptoeuro</title>

<link href="https://fonts.googleapis.com/css?family=Ubuntu+Mono" rel="stylesheet">
<link href="style.css?<?= write($assetHashes['css']) ?>" rel="stylesheet">

<div class="container">
  <main class="js-container">
    <?php foreach($currencies as $currency): ?>
      <section class="section">
        <?php if ($currency['valid'] === false) : ?>
            <p class="error">Invalid currency "<?= write($currency['tag']) ?>"</p>
        <?php else : ?>
            <h2 class="currency"><?=  write($currency['amount']) ?> <?= write($currency['tag']) ?></h2>
            <p class="value">€ <?=  write($currency['euro']) ?></p>
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

<script src="main.js?<?= write($assetHashes['js']) ?>"></script>
