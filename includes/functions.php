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
    $result = $stmt->get_result();
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function getLatestArticles($limit = 12, $offset = 0) {
    $db = getDB();
    $stmt = $db->prepare("SELECT a.*, c.name as cat_name, c.slug as cat_slug, c.color as cat_color
        FROM articles a JOIN categories c ON a.category_id = c.id
        WHERE a.status = 'published'
        ORDER BY a.published_at DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function getFeaturedArticles($limit = 4) {
    $db = getDB();
    $stmt = $db->prepare("SELECT a.*, c.name as cat_name, c.slug as cat_slug, c.color as cat_color
        FROM articles a JOIN categories c ON a.category_id = c.id
        WHERE a.is_featured = 1 AND a.status = 'published'
        ORDER BY a.published_at DESC LIMIT ?");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
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
    $result = $stmt->get_result();
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
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

function getImageUrl($image, $seed = null, $size = 'medium') {
    if ($image && str_starts_with($image, 'http')) {
        return $image;
    }
    if ($image && file_exists(UPLOAD_PATH . $image)) {
        $path = UPLOAD_URL . $image;

        // Check if WebP version exists
        $webp_path = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $path);
        $webp_file = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', UPLOAD_PATH . $image);
        if (file_exists($webp_file)) {
            return $webp_path;
        }

        return $path;
    }

    // Fallback to optimized picsum with size
    $sizes = [
        'small' => '400/300',
        'medium' => '600/400',
        'large' => '800/600'
    ];
    $dimensions = $sizes[$size] ?? $sizes['medium'];
    $s = $seed ?? abs(crc32($image ?? uniqid()));

    return 'https://picsum.photos/seed/' . $s . '/' . $dimensions . '?webp=true';
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

// Advertisement functions
function getAdvertisements($position = null, $active_only = true) {
    $db = getDB();
    $where = $active_only ? "WHERE is_active = 1" : "WHERE 1=1";
    if ($position) {
        $where .= " AND position = ?";
        $stmt = $db->prepare("SELECT * FROM advertisements $where ORDER BY sort_order ASC");
        $stmt->bind_param("s", $position);
    } else {
        $stmt = $db->prepare("SELECT * FROM advertisements $where ORDER BY sort_order ASC");
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getAdvertisementById($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM advertisements WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function createAdvertisement($data) {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO advertisements (title, image, link, position, size, is_active, sort_order, start_date, end_date) VALUES (?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssisss",
        $data['title'],
        $data['image'],
        $data['link'],
        $data['position'],
        $data['size'],
        $data['is_active'],
        $data['sort_order'],
        $data['start_date'],
        $data['end_date']
    );
    return $stmt->execute();
}

function updateAdvertisement($id, $data) {
    $db = getDB();
    $stmt = $db->prepare("UPDATE advertisements SET title=?, image=?, link=?, position=?, size=?, is_active=?, sort_order=?, start_date=?, end_date=? WHERE id=?");
    $stmt->bind_param("sssssisssi",
        $data['title'],
        $data['image'],
        $data['link'],
        $data['position'],
        $data['size'],
        $data['is_active'],
        $data['sort_order'],
        $data['start_date'],
        $data['end_date'],
        $id
    );
    return $stmt->execute();
}

function deleteAdvertisement($id) {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM advertisements WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function logActivity($action, $entity_type = null, $entity_id = null, $description = '') {
    $db = getDB();
    $db->query("CREATE TABLE IF NOT EXISTS activity_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        admin_id INT NOT NULL,
        admin_name VARCHAR(100) DEFAULT NULL,
        action VARCHAR(50) NOT NULL,
        entity_type VARCHAR(50) DEFAULT NULL,
        entity_id INT DEFAULT NULL,
        description VARCHAR(500) DEFAULT NULL,
        ip_address VARCHAR(45) DEFAULT NULL,
        user_agent TEXT DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        KEY admin_id (admin_id),
        KEY action (action),
        KEY created_at (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $stmt = $db->prepare("INSERT INTO activity_logs (admin_id, admin_name, action, entity_type, entity_id, description, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $admin_id   = $_SESSION['admin_id'] ?? 0;
    $admin_name = $_SESSION['admin_name'] ?? 'Unknown';
    $ip         = $_SERVER['REMOTE_ADDR'] ?? '';
    $ua         = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $stmt->bind_param("ississss", $admin_id, $admin_name, $action, $entity_type, $entity_id, $description, $ip, $ua);
    return $stmt->execute();
}

function incrementAdClick($id) {
    $db = getDB();
    $stmt = $db->prepare("UPDATE advertisements SET click_count = click_count + 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function displayAdvertisement($position, $size = null, $limit = 1) {
    $ads = getAdvertisements($position, true);

    // Filter by size if specified
    if ($size) {
        $ads = array_filter($ads, function($ad) use ($size) {
            return $ad['size'] === $size;
        });
    }

    // Filter by date range
    $today = date('Y-m-d');
    $ads = array_filter($ads, function($ad) use ($today) {
        $start_ok = empty($ad['start_date']) || $ad['start_date'] <= $today;
        $end_ok = empty($ad['end_date']) || $ad['end_date'] >= $today;
        return $start_ok && $end_ok;
    });

    // Limit results
    $ads = array_slice($ads, 0, $limit);

    if (empty($ads)) {
        return getAdPlaceholder($position, $size);
    }

    $output = '';
    foreach ($ads as $ad) {
        $output .= '<div class="advertisement ad-' . $ad['size'] . '" data-ad-id="' . $ad['id'] . '">';
        if ($ad['link']) {
            $output .= '<a href="' . e($ad['link']) . '" target="_blank" onclick="trackAdClick(' . $ad['id'] . ')">';
        }
        if ($ad['image']) {
            $output .= '<img src="' . getImageUrl($ad['image']) . '" alt="' . e($ad['title']) . '" loading="lazy">';
        } else {
            $output .= '<div class="ad-text">' . e($ad['title']) . '</div>';
        }
        if ($ad['link']) {
            $output .= '</a>';
        }
        $output .= '</div>';
    }

    return $output;
}

function getAdPlaceholder($position, $size = null) {
    $dimensions = [
        'small' => '200 × 100',
        'medium' => '300 × 250',
        'large' => '300 × 400',
        'banner' => '728 × 90'
    ];

    $dim = $size && isset($dimensions[$size]) ? $dimensions[$size] : '300 × 250';

    return '<div class="ad-placeholder">
        <i class="fa fa-ad"></i>
        <span>विज्ञापन</span>
        <span style="font-size:10px">' . $dim . '</span>
    </div>';
}
