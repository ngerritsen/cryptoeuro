'use strict';

const crypto = require('crypto');
const fs = require('fs');
const path = require('path');

const js = fs.readFileSync(path.join(__dirname + '/../main.js'));
const css = fs.readFileSync(path.join(__dirname + '/../style.css'));

Promise.all([hash(js), hash(css)])
  .then(generatePhp)
  .then(writePhp);

function generatePhp([jsHash, cssHash]) {
  return `<?php
declare(strict_types = 1);

return [
  'js' => '${jsHash}',
  'css' => '${cssHash}'
];
`
}

function writePhp(php) {
  fs.writeFileSync(path.join(__dirname + '/../etc/hashes.php'), php);
}

function hash(source) {
  return new Promise(resolve => {
    const hash = crypto.createHash('sha1');

    hash.on('readable', () => {
      const data = hash.read();

      if (data) {
        resolve(data.toString('hex'));
      }
    });

    hash.write(source);
    hash.end();
  });
}
