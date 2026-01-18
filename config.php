<?php
function loadEnv(string $filePath): array {
    $env = [];
    if (!file_exists($filePath)) return $env;

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);
        // 跳過註解
        if ($line === '' || str_starts_with($line, '#')) continue;

        // 解析 KEY=VALUE
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        // 去掉引號
        $value = trim($value, "\"'");

        // 存到 $_ENV
        $_ENV[$key] = $value;
        $env[$key] = $value;
    }

    return $env;
}
loadEnv(__DIR__ . '/.env');

//SQL
$HOST      = $_ENV['DB_HOST'] ?? 'localhost';
$DBNAME    = $_ENV['DB_NAME'] ?? 'sa';
$WRITER    = $_ENV['DB_USER'] ?? 'sa';
$WRITER_PW = $_ENV['DB_PASS'] ?? '123';
$VPNFN = '/etc/ppp/chap-secrets';
$VPNPSK = 'JcVZZ3pyKLLyT7er';   ///etc/ipsec.secrets
$URL = 'http://192.168.42.1/qasw.php';
$URLS = 'http://192.168.42.1/qasw.php';
$JURL = 'http://sw.saga.net.tw/qa.php';
$JURLS = 'https://sw.saga.net.tw/qa.php';
$GPIO = 4;
$ON_DELAY = 300;
$OFF_DELAY = 600;
?>