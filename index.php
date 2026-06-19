<?php
define('ROUTED', true);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';

// Derive path by stripping the base (e.g. /news) from REQUEST_URI
$base  = rtrim(parse_url(SITE_URL, PHP_URL_PATH), '/');
$uri   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path  = trim(substr($uri, strlen($base)), '/');
$parts = $path !== '' ? array_map('rawurldecode', explode('/', $path)) : [];

// Centralized route info — used by header.php instead of duplicating this logic
$_current_route = $parts[0] ?? '';
$_current_slug  = '';

// Validate the route segment allows only safe characters
if ($_current_route !== '' && !preg_match('/^[a-zA-Z0-9_-]+$/', $_current_route)) {
    http_response_code(404);
    include __DIR__ . '/404.php';
    exit;
}

// Content routes should only respond to GET
$get_routes = ['article', 'category', 'search'];
if (in_array($_current_route, $get_routes) && $_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    header('Allow: GET');
    exit;
}

switch ($_current_route) {
    case '':
        include __DIR__ . '/home.php';
        break;

    case 'article':
        $_current_slug = $parts[1] ?? '';
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $_current_slug)) {
            http_response_code(404);
            include __DIR__ . '/404.php';
            break;
        }
        $_GET['slug'] = $_current_slug;
        include __DIR__ . '/article.php';
        break;

    case 'category':
        $_current_slug = $parts[1] ?? '';
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $_current_slug)) {
            http_response_code(404);
            include __DIR__ . '/404.php';
            break;
        }
        $_GET['slug'] = $_current_slug;
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
