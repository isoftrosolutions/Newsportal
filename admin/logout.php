<?php
session_start();
require_once dirname(__DIR__) . '/includes/functions.php';
logActivity('logout', 'admin_user', $_SESSION['admin_id'] ?? 0, 'लगआउट गरे');
session_destroy();
header('Location: login.php');
exit;
