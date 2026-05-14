<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

require_admin_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('dashboard.php');
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id > 0) {
    $pdo = get_db();
    $stmt = $pdo->prepare('DELETE FROM assessments WHERE id = :id');
    $stmt->execute(['id' => $id]);
}

redirect('dashboard.php');
