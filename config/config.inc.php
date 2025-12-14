<?php

$config = [];

$databaseUrl = getenv('DATABASE_URL');
$dbDsn = null;

if ($databaseUrl !== false && $databaseUrl !== '') {
    $url = parse_url($databaseUrl);

    if ($url !== false && isset($url['scheme'], $url['host'], $url['path'])) {
        $scheme = $url['scheme'];

        if ($scheme === 'postgres' || $scheme === 'postgresql') {
            $scheme = 'pgsql';
        }
        $user = isset($url['user']) ? $url['user'] : '';
        $pass = isset($url['pass']) ? $url['pass'] : '';
        $host = $url['host'];

        if (isset($url['port'])) {
            $host .= ':' . $url['port'];
        }

        $name = ltrim($url['path'], '/');
        $query = isset($url['query']) && $url['query'] !== '' ? '?' . $url['query'] : '';

        $dbDsn = sprintf(
            '%s://%s:%s@%s/%s%s',
            $scheme,
            rawurlencode($user),
            rawurlencode($pass),
            $host,
            $name,
            $query
        );
    }
}

if ($dbDsn === null) {
    $dbUser = getenv('ROUNDCUBE_DB_USER') ?: '';
    $dbPass = getenv('ROUNDCUBE_DB_PASSWORD') ?: '';
    $dbHost = getenv('ROUNDCUBE_DB_HOST') ?: 'localhost';
    $dbPort = getenv('ROUNDCUBE_DB_PORT');

    if ($dbPort !== false && $dbPort !== '') {
        $dbHost .= ':' . $dbPort;
    }

    $dbName = getenv('ROUNDCUBE_DB_NAME') ?: 'roundcubemail';

    $dbDsn = sprintf(
        'pgsql://%s:%s@%s/%s',
        rawurlencode($dbUser),
        rawurlencode($dbPass),
        $dbHost,
        $dbName
    );
}

$config['db_dsnw'] = $dbDsn;

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

$enableInstaller = getenv('ROUNDCUBE_ENABLE_INSTALLER');
if ($enableInstaller !== false && $enableInstaller !== '') {
    $config['enable_installer'] = $enableInstaller === '1';
} else {
    $config['enable_installer'] = false;
}

$config['log_driver'] = 'stdout';
$config['log_logins'] = true;
$config['session_debug'] = true;
$config['sql_debug'] = true;
