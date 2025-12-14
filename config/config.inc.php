<?php

$config = [];

$dbUser = getenv('ROUNDCUBE_DB_USER') ?: '';
$dbPass = getenv('ROUNDCUBE_DB_PASSWORD') ?: '';
$dbHost = getenv('ROUNDCUBE_DB_HOST') ?: 'localhost';
$dbName = getenv('ROUNDCUBE_DB_NAME') ?: 'roundcubemail';

$config['db_dsnw'] = sprintf(
    'pgsql://%s:%s@%s/%s',
    rawurlencode($dbUser),
    rawurlencode($dbPass),
    $dbHost,
    $dbName
);

$imapHost = getenv('ROUNDCUBE_DEFAULT_HOST') ?: 'localhost';
$imapPort = getenv('ROUNDCUBE_DEFAULT_PORT') ?: '143';
$config['imap_host'] = $imapHost . ':' . $imapPort;

$smtpHost = getenv('ROUNDCUBE_SMTP_SERVER') ?: 'localhost';
$smtpPort = getenv('ROUNDCUBE_SMTP_PORT') ?: '587';
$config['smtp_host'] = $smtpHost . ':' . $smtpPort;

$config['smtp_user'] = getenv('ROUNDCUBE_SMTP_USER') ?: '%u';
$config['smtp_pass'] = getenv('ROUNDCUBE_SMTP_PASS') ?: '%p';

$productName = getenv('ROUNDCUBE_PRODUCT_NAME');
$config['product_name'] = $productName !== false && $productName !== '' ? $productName : 'Roundcube Webmail';

$desKey = getenv('ROUNDCUBE_DES_KEY');
$config['des_key'] = $desKey !== false && $desKey !== '' ? $desKey : 'rcmail-!24ByteDESkey*Str';

$pluginsEnv = getenv('ROUNDCUBE_PLUGINS');
if ($pluginsEnv !== false && $pluginsEnv !== '') {
    $config['plugins'] = array_filter(array_map('trim', explode(',', $pluginsEnv)));
} else {
    $config['plugins'] = ['archive', 'zipdownload'];
}

$config['skin'] = 'elastic';

