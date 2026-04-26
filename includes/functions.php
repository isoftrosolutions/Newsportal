<?php
require_once __DIR__ . '/db.php';

function getCategories() {
    $db = getDB();
    $res = $db->query("SELECT * FROM categories WHERE parent_id IS NULL ORDER BY sort_order ASC");
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function getArticlesByCategory($category_id, $limit = 6, $offset = 0) {
    $db = getDB();
    $stmt = $db->prepare("SELECT a.*, c.name as cat_name, c.slug as cat_slug, c.color as cat_color
        FROM articles a JOIN categories c ON a.category_id = c.id
        WHERE a.category_id = ? AND a.status = 'published'
        ORDER BY a.published_at DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("iii", $category_id, $limit, $offset);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getLatestArticles($limit = 12, $offset = 0) {
    $db = getDB();
    $stmt = $db->prepare("SELECT a.*, c.name as cat_name, c.slug as cat_slug, c.color as cat_color
        FROM articles a JOIN categories c ON a.category_id = c.id
        WHERE a.status = 'published'
        ORDER BY a.published_at DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getFeaturedArticles($limit = 4) {
    $db = getDB();
    $stmt = $db->prepare("SELECT a.*, c.name as cat_name, c.slug as cat_slug, c.color as cat_color
        FROM articles a JOIN categories c ON a.category_id = c.id
        WHERE a.is_featured = 1 AND a.status = 'published'
        ORDER BY a.published_at DESC LIMIT ?");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getBreakingNews() {
    $db = getDB();
    $res = $db->query("SELECT * FROM breaking_news WHERE is_active = 1 ORDER BY created_at DESC LIMIT 5");
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function getPopularArticles($limit = 5) {
    $db = getDB();
    $stmt = $db->prepare("SELECT a.*, c.name as cat_name, c.slug as cat_slug, c.color as cat_color
        FROM articles a JOIN categories c ON a.category_id = c.id
        WHERE a.status = 'published'
        ORDER BY a.views DESC LIMIT ?");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getArticleBySlug($slug) {
    $db = getDB();
    $stmt = $db->prepare("SELECT a.*, c.name as cat_name, c.slug as cat_slug, c.color as cat_color
        FROM articles a JOIN categories c ON a.category_id = c.id
        WHERE a.slug = ? AND a.status = 'published'");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    if ($result) {
        $db->query("UPDATE articles SET views = views + 1 WHERE id = " . (int)$result['id']);
    }
    return $result;
}

function getCategoryBySlug($slug) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM categories WHERE slug = ?");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function searchArticles($q, $limit = 20) {
    $db = getDB();
    $search = "%{$q}%";
    $stmt = $db->prepare("SELECT a.*, c.name as cat_name, c.slug as cat_slug, c.color as cat_color
        FROM articles a JOIN categories c ON a.category_id = c.id
        WHERE a.status = 'published' AND (a.title LIKE ? OR a.content LIKE ? OR a.excerpt LIKE ?)
        ORDER BY a.published_at DESC LIMIT ?");
    $stmt->bind_param("sssi", $search, $search, $search, $limit);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getRelatedArticles($article_id, $category_id, $limit = 4) {
    $db = getDB();
    $stmt = $db->prepare("SELECT a.*, c.name as cat_name, c.slug as cat_slug, c.color as cat_color
        FROM articles a JOIN categories c ON a.category_id = c.id
        WHERE a.category_id = ? AND a.id != ? AND a.status = 'published'
        ORDER BY a.published_at DESC LIMIT ?");
    $stmt->bind_param("iii", $category_id, $article_id, $limit);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getImageUrl($image, $seed = null) {
    if ($image && str_starts_with($image, 'http')) {
        return $image;
    }
    if ($image && file_exists(UPLOAD_PATH . $image)) {
        return UPLOAD_URL . $image;
    }
    $s = $seed ?? abs(crc32($image ?? uniqid()));
    return 'https://picsum.photos/seed/' . $s . '/800/500';
}

function timeAgo($datetime) {
    $now = new DateTime('now', new DateTimeZone('Asia/Kathmandu'));
    $ago = new DateTime($datetime, new DateTimeZone('Asia/Kathmandu'));
    $diff = $now->getTimestamp() - $ago->getTimestamp();

    if ($diff < 60) return 'अहिले';
    if ($diff < 3600) return floor($diff / 60) . ' मिनेट अघि';
    if ($diff < 86400) return floor($diff / 3600) . ' घण्टा अघि';
    if ($diff < 604800) return floor($diff / 86400) . ' दिन अघि';

    return $ago->format('Y-m-d');
}

function getNepaliDate() {
    $tz = new DateTimeZone('Asia/Kathmandu');
    $now = new DateTime('now', $tz);
    $y = (int)$now->format('Y');
    $m = (int)$now->format('n');
    $d = (int)$now->format('j');

    // BS months data for 2082 and 2083
    $bs_data = [
        2082 => [31,32,31,32,31,30,30,30,29,30,29,31],
        2083 => [31,31,32,32,31,30,30,29,30,29,30,30],
    ];
    // 2082 BS started 2025-04-14, 2083 BS started 2026-04-13
    $bs_starts = [
        2082 => ['y'=>2025,'m'=>4,'d'=>14],
        2083 => ['y'=>2026,'m'=>4,'d'=>13],
    ];

    $bs_year = null;
    $days_from_start = null;
    foreach (array_reverse(array_keys($bs_starts), true) as $bsy) {
        $s = $bs_starts[$bsy];
        $start = new DateTime("{$s['y']}-{$s['m']}-{$s['d']}", $tz);
        $diff = (int)$now->diff($start)->days;
        if ($now >= $start) {
            $bs_year = $bsy;
            $days_from_start = $diff;
            break;
        }
    }
    if (!$bs_year) { $bs_year = 2082; $days_from_start = 0; }

    $months = isset($bs_data[$bs_year]) ? $bs_data[$bs_year] : $bs_data[2083];
    $bs_month = 0;
    $rem = $days_from_start;
    foreach ($months as $i => $days) {
        if ($rem < $days) { $bs_month = $i; break; }
        $rem -= $days;
    }
    $bs_day = $rem + 1;

    $ne_months = ['','बैशाख','जेठ','असार','श्रावण','भदौ','असोज','कार्तिक','मंसिर','पुस','माघ','फाल्गुन','चैत्र'];
    $ne_days = ['आइतबार','सोमबार','मंगलबार','बुधबार','बिहीबार','शुक्रबार','शनिबार'];
    $dow = (int)$now->format('w');

    $d2 = fn($n) => implode('', array_map(fn($x) => ['०','१','२','३','४','५','६','७','८','९'][$x], str_split((string)$n)));

    return [
        'bs' => $d2($bs_day) . ' ' . ($ne_months[$bs_month + 1] ?? '') . ' ' . $d2($bs_year),
        'day' => $ne_days[$dow],
        'ad' => $now->format('Y-m-d'),
    ];
}

function url($type, $param = '', $page = 0) {
    switch ($type) {
        case 'article':  return SITE_URL . '/article/' . $param;
        case 'category':
            $u = SITE_URL . '/category/' . $param;
            return ($page > 1) ? $u . '/' . $page : $u;
        case 'search':   return SITE_URL . '/search';
        default:         return SITE_URL . '/';
    }
}

function slug($text) {
    $text = preg_replace('/[\s]+/', '-', trim(mb_strtolower($text)));
    $text = preg_replace('/[^\p{L}\p{N}\-]/u', '', $text);
    return $text ?: uniqid();
}

function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

function totalArticlesByCategory($cat_id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT COUNT(*) FROM articles WHERE category_id = ? AND status = 'published'");
    $stmt->bind_param("i", $cat_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_row()[0];
}
