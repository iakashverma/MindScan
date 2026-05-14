<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';

$pageTitle = $pageTitle ?? 'Admin Console';
$requireLogin = $requireLogin ?? true;
$vendorScripts = $vendorScripts ?? [];
$pageScripts = $pageScripts ?? [];

if ($requireLogin) {
    require_admin_login();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($pageTitle); ?> | <?php echo e(APP_NAME); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="admin-body">
<div class="admin-shell">
    <aside class="admin-sidebar glass-panel">
        <div class="admin-brand">
            <span>MindScan Admin</span>
            <small>Secure Research Console</small>
        </div>
        <nav class="admin-nav">
            <a href="dashboard.php" class="admin-link"><i class="fa-solid fa-grid"></i> Overview</a>
            <a href="research.php" class="admin-link"><i class="fa-solid fa-flask"></i> Research Content</a>
            <a href="library.php" class="admin-link"><i class="fa-solid fa-book"></i> Research Library</a>
            <a href="../dashboard.php" class="admin-link"><i class="fa-solid fa-chart-line"></i> Public Dashboard</a>
            <a href="../assessment.php" class="admin-link"><i class="fa-solid fa-clipboard-list"></i> Assessment</a>
            <a href="logout.php" class="admin-link text-danger"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </nav>
    </aside>
    <div class="admin-main">
        <header class="admin-topbar">
            <div>
                <h4><?php echo e($pageTitle); ?></h4>
                <p>Welcome, <?php echo e($_SESSION['admin_username'] ?? 'Admin'); ?></p>
            </div>
            <div class="admin-actions">
                <span class="status-pill">System Online</span>
            </div>
        </header>
        <main class="admin-content">
