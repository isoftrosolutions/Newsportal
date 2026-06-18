<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';

header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$ad_id = (int)($_POST['ad_id'] ?? 0);

if (!$ad_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid advertisement ID']);
    exit;
}

// Verify advertisement exists and is active
$ad = getAdvertisementById($ad_id);
if (!$ad || !$ad['is_active']) {
    http_response_code(404);
    echo json_encode(['error' => 'Advertisement not found']);
    exit;
}

// Increment click count
if (incrementAdClick($ad_id)) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to track click']);
}
?></content>
<parameter name="filePath">track-ad-click.php