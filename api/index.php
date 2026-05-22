<?php

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($path, '/');

if ($path === '') {
    require __DIR__ . '/../Public/index.php';
    exit;
}

$file = __DIR__ . '/../' . $path;

if (is_file($file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
    require $file;
    exit;
}

require __DIR__ . '/../Public/index.php';

