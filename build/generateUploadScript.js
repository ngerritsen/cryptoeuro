'use strict';

const glob = require('glob');
const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

const shouldUploadVendor = execSync('git diff HEAD^ HEAD', { encoding: 'utf-8' })
  .includes('composer.lock');

const paths = [
  ...glob.sync('*.{php,js,css,png,htaccess}'),
  ...glob.sync('app/**/*.php'),
  ...glob.sync('etc/**/*.php'),
  ...glob.sync('src/**/*.php'),
  ...glob.sync('templates/**/*.twig'),
];

if (shouldUploadVendor) {
  paths.push(...glob.sync('vendor/**/*.{php,json}'));
}

const uploadScript = paths
  .map((filepath) => {
    const dir = path.dirname(filepath);

    return (
      `curl -3 --ftp-create-dirs -T ${filepath} ` +
      `ftp://$FTP_USER:$FTP_PASSWORD@ftp.carehr.nl/public_html/cryptoeuro/${dir}/`
    );
  })
  .join('\n');

fs.writeFileSync('upload.sh', uploadScript);

console.log(`Generated upload script with ${paths.length} paths.`);
