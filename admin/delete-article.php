<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }

require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/includes/db.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: articles.php'); exit; }

$db = getDB();
$article = $db->query("SELECT * FROM articles WHERE id = $id")->fetch_assoc();
if ($article) {
    if ($article['image'] && file_exists(UPLOAD_PATH . $article['image'])) {
        unlink(UPLOAD_PATH . $article['image']);
    }
    $db->query("DELETE FROM articles WHERE id = $id");
}
header('Location: articles.php?deleted=1');
exit;
