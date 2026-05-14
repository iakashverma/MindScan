<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

require_admin_login();

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="mindscan-assessments.csv"');

$pdo = get_db();
$rows = $pdo->query('SELECT a.id, u.name, u.age, u.gender, u.occupation, a.mental_health_score, a.stress_score, a.sleep_score, a.productivity_score, a.risk_level, a.created_at FROM assessments a JOIN users u ON a.user_id = u.id ORDER BY a.created_at DESC')->fetchAll();

$output = fopen('php://output', 'w');

fputcsv($output, ['ID', 'Name', 'Age', 'Gender', 'Occupation', 'Mental Health Score', 'Stress Score', 'Sleep Score', 'Productivity Score', 'Risk Level', 'Created At']);
foreach ($rows as $row) {
    fputcsv($output, $row);
}

fclose($output);
exit;
