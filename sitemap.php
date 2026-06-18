<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';

// Set content type to XML
header('Content-Type: application/xml; charset=utf-8');

// Get all published articles
$articles = getLatestArticles(1000); // Get up to 1000 articles
$categories = getCategories();

// Start XML output
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// Homepage
echo '  <url>' . "\n";
echo '    <loc>' . SITE_URL . '/</loc>' . "\n";
echo '    <lastmod>' . date('Y-m-d') . '</lastmod>' . "\n";
echo '    <changefreq>daily</changefreq>' . "\n";
echo '    <priority>1.0</priority>' . "\n";
echo '  </url>' . "\n";

// Categories
foreach ($categories as $category) {
    echo '  <url>' . "\n";
    echo '    <loc>' . url('category', $category['slug']) . '</loc>' . "\n";
    echo '    <lastmod>' . date('Y-m-d') . '</lastmod>' . "\n";
    echo '    <changefreq>weekly</changefreq>' . "\n";
    echo '    <priority>0.8</priority>' . "\n";
    echo '  </url>' . "\n";
}

// Articles
foreach ($articles as $article) {
    echo '  <url>' . "\n";
    echo '    <loc>' . url('article', $article['slug']) . '</loc>' . "\n";
    echo '    <lastmod>' . date('Y-m-d', strtotime($article['updated_at'] ?? $article['published_at'])) . '</lastmod>' . "\n";
    echo '    <changefreq>monthly</changefreq>' . "\n";
    echo '    <priority>0.6</priority>' . "\n";
    echo '  </url>' . "\n";
}

// Static pages
$static_pages = ['search'];
foreach ($static_pages as $page) {
    echo '  <url>' . "\n";
    echo '    <loc>' . url($page) . '</loc>' . "\n";
    echo '    <lastmod>' . date('Y-m-d') . '</lastmod>' . "\n";
    echo '    <changefreq>monthly</changefreq>' . "\n";
    echo '    <priority>0.3</priority>' . "\n";
    echo '  </url>' . "\n";
}

echo '</urlset>' . "\n";
?>