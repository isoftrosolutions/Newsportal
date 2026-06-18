<?php
session_start();
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/includes/db.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: index.php'); exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM admin_users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_id']   = $user['id'];
            $_SESSION['admin_name'] = $user['full_name'];
            header('Location: index.php'); exit;
        } else {
            $error = 'गलत प्रयोगकर्ता नाम वा पासवर्ड।';
        }
    } else {
        $error = 'सबै फिल्ड भर्नुहोस्।';
    }
}
?>
<!DOCTYPE html>
<html lang="ne">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>लगइन — <?= SITE_NAME ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@400;500&family=Noto+Sans+Devanagari:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin.css">
</head>
<body class="login-page">

<div class="login-wrap">

  <div class="login-brand">
    <div class="login-brand-icon"><i class="fa fa-newspaper"></i></div>
    <h1><?= BRAND_NAME ?></h1>
    <p><?= SITE_NAME ?> — सम्पादक प्यानल</p>
  </div>

  <div class="login-card">

    <?php if ($error): ?>
    <div class="alert alert-error">
      <i class="fa fa-circle-exclamation"></i>
      <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
      <div class="form-group">
        <label>प्रयोगकर्ता नाम</label>
        <input type="text" name="username" required
               placeholder="admin"
               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
               autofocus>
      </div>
      <div class="form-group">
        <label>पासवर्ड</label>
        <input type="password" name="password" required placeholder="••••••••">
      </div>
      <button type="submit" class="btn-login">
        लगइन गर्नुहोस् &nbsp;<i class="fa fa-arrow-right"></i>
      </button>
    </form>

  </div>

  <p class="login-footer">
    <a href="<?= SITE_URL ?>/"><i class="fa fa-arrow-left"></i> वेबसाइटमा फर्कनुहोस्</a>
  </p>

</div>

</body>
</html>
