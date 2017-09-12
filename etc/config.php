<?php
declare(strict_types = 1);

$config = [
    'mysql' => [
        'db' => 'cryptoeuro',
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => 'development'
    ]
];

$secretsJsonLocation = __DIR__ . '/../../../../secrets.json';

if (file_exists($secretsJsonLocation)) {
    $secrets = json_decode(file_get_contents($secretsJsonLocation), true);

    $config['mysql']['db'] = $secrets['mysql_db'];
    $config['mysql']['username'] = $secrets['mysql_user'];
    $config['mysql']['password'] = $secrets['mysql_password'];
}

return $config;
