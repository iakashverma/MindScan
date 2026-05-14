<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/scoring.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('assessment.php');
}

$required = ['name', 'age', 'gender', 'occupation'];
for ($i = 1; $i <= 10; $i++) {
    $required[] = 'question_' . $i;
}

$missing = require_fields($required);
if (!empty($missing)) {
    redirect('assessment.php');
}

$name = post_string('name');
$age = clamp_int(post_int('age'), 12, 80);
$gender = post_string('gender');
$occupation = post_string('occupation');

$answers = [];
for ($i = 1; $i <= 10; $i++) {
    $answers[$i] = clamp_int(post_int('question_' . $i), 1, 5);
}

$scores = compute_scores($answers);

$pdo = get_db();
$pdo->beginTransaction();

$userStmt = $pdo->prepare('INSERT INTO users (name, age, gender, occupation) VALUES (:name, :age, :gender, :occupation)');
$userStmt->execute([
    'name' => $name,
    'age' => $age,
    'gender' => $gender,
    'occupation' => $occupation,
]);
$userId = (int)$pdo->lastInsertId();

$assessmentStmt = $pdo->prepare('INSERT INTO assessments (user_id, question_1, question_2, question_3, question_4, question_5, question_6, question_7, question_8, question_9, question_10, stress_score, mental_health_score, productivity_score, sleep_score, risk_level) VALUES (:user_id, :q1, :q2, :q3, :q4, :q5, :q6, :q7, :q8, :q9, :q10, :stress, :mental, :productivity, :sleep, :risk)');
$assessmentStmt->execute([
    'user_id' => $userId,
    'q1' => $answers[1],
    'q2' => $answers[2],
    'q3' => $answers[3],
    'q4' => $answers[4],
    'q5' => $answers[5],
    'q6' => $answers[6],
    'q7' => $answers[7],
    'q8' => $answers[8],
    'q9' => $answers[9],
    'q10' => $answers[10],
    'stress' => $scores['stress_score'],
    'mental' => $scores['mental_health_score'],
    'productivity' => $scores['productivity_score'],
    'sleep' => $scores['sleep_score'],
    'risk' => $scores['risk_level'],
]);

$assessmentId = (int)$pdo->lastInsertId();
$pdo->commit();

$_SESSION['last_assessment_id'] = $assessmentId;

redirect('report.php?id=' . $assessmentId);
