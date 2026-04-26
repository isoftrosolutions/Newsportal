<?php
define('ROUTED', true);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';

// Derive path by stripping the base (e.g. /news) from REQUEST_URI
$base  = rtrim(parse_url(SITE_URL, PHP_URL_PATH), '/');
$uri   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path  = trim(substr($uri, strlen($base)), '/');
$parts = $path !== '' ? array_map('rawurldecode', explode('/', $path)) : [];

$route = $parts[0] ?? '';

switch ($route) {
    case '':
        include __DIR__ . '/home.php';
        break;

    case 'article':
        $_GET['slug'] = $parts[1] ?? '';
        include __DIR__ . '/article.php';
        break;

    case 'category':
        $_GET['slug'] = $parts[1] ?? '';
        if (isset($parts[2]) && ctype_digit($parts[2])) {
            $_GET['page'] = (int)$parts[2];
        }
        include __DIR__ . '/category.php';
        break;

    case 'search':
        include __DIR__ . '/search.php';
        break;

    default:
        http_response_code(404);
        include __DIR__ . '/404.php';
}
