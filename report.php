<?php
$pageTitle = 'Generated Report';
$vendorScripts = ['https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js'];
$pageScripts = ['assets/js/report.js'];
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/scoring.php';
require_once __DIR__ . '/partials/main_header.php';

$assessmentId = isset($_GET['id']) ? (int)$_GET['id'] : (int)($_SESSION['last_assessment_id'] ?? 0);
$report = null;

if ($assessmentId > 0) {
    $pdo = get_db();
    $stmt = $pdo->prepare('SELECT a.*, u.name, u.age, u.gender, u.occupation FROM assessments a JOIN users u ON a.user_id = u.id WHERE a.id = :id');
    $stmt->execute(['id' => $assessmentId]);
    $report = $stmt->fetch();
}

if (!$report) {
    $report = [
        'name' => 'Guest',
        'age' => '-',
        'gender' => '-',
        'occupation' => '-',
        'risk_level' => 'Unavailable',
        'mental_health_score' => 0,
        'stress_score' => 0,
        'sleep_score' => 0,
        'productivity_score' => 0,
    ];
}

$emotionalScore = 0;
$sentimentLabel = 'Unavailable';
$aiSummary = 'Complete the assessment to generate your personalized summary.';
if (isset($report['question_1'])) {
    $answers = [];
    for ($i = 1; $i <= 10; $i++) {
        $answers[$i] = (int)$report['question_' . $i];
    }
    $computed = compute_scores($answers);
    $emotionalScore = $computed['emotional_score'];
    $sentimentLabel = $computed['sentiment_label'];
    $aiSummary = $computed['ai_summary'];
}

$riskBadgeClass = 'bg-secondary-subtle text-secondary-emphasis';
if ($report['risk_level'] === 'Low Risk') {
    $riskBadgeClass = 'bg-success-subtle text-success-emphasis';
} elseif ($report['risk_level'] === 'Moderate Risk') {
    $riskBadgeClass = 'bg-warning-subtle text-warning-emphasis';
} elseif ($report['risk_level'] === 'High Risk') {
    $riskBadgeClass = 'bg-danger-subtle text-danger-emphasis';
}

$recommendations = [
    'Low Risk' => [
        'Maintain balanced screen time routines.',
        'Prioritize offline hobbies and social interactions.',
        'Track weekly usage to sustain healthy habits.',
    ],
    'Moderate Risk' => [
        'Schedule intentional breaks every 30 minutes.',
        'Disable non-essential notifications.',
        'Use night mode to reduce sleep disruption.',
    ],
    'High Risk' => [
        'Set strict daily screen time limits.',
        'Practice digital detox sessions each week.',
        'Seek support from peers or counselors if stress persists.',
    ],
];

$recommendationList = $recommendations[$report['risk_level']] ?? $recommendations['Moderate Risk'];

$reportData = [
    'name' => $report['name'],
    'age' => $report['age'],
    'gender' => $report['gender'],
    'occupation' => $report['occupation'],
    'risk_level' => $report['risk_level'],
    'mental_health_score' => (int)$report['mental_health_score'],
    'stress_score' => (int)$report['stress_score'],
    'sleep_score' => (int)$report['sleep_score'],
    'productivity_score' => (int)$report['productivity_score'],
    'emotional_score' => $emotionalScore,
    'sentiment_label' => $sentimentLabel,
    'recommendations' => $recommendationList,
];
?>

<section class="section">
    <div class="container">
        <div class="glass-panel" data-aos="fade-up">
            <h2 class="section-title">Mental Health Risk Report</h2>
            <p>Instant AI-powered analysis based on your assessment responses.</p>
            <div class="row g-4 mt-3">
                <div class="col-lg-4">
                    <div class="glass-card">
                        <h6>Participant</h6>
                        <p><?php echo e((string)$report['name']); ?> | <?php echo e((string)$report['age']); ?> | <?php echo e((string)$report['gender']); ?></p>
                        <p><?php echo e((string)$report['occupation']); ?></p>
                        <h5 class="mt-3">Risk Level</h5>
                        <span class="badge <?php echo e($riskBadgeClass); ?> px-3 py-2"><?php echo e((string)$report['risk_level']); ?></span>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="report-grid">
                        <div class="glass-card text-center">
                            <svg class="progress-ring" data-progress="<?php echo (int)$report['mental_health_score']; ?>" viewBox="0 0 120 120">
                                <circle class="track" cx="60" cy="60" r="52"></circle>
                                <circle class="progress" cx="60" cy="60" r="52"></circle>
                            </svg>
                            <h6 class="mt-3">Mental Health Score</h6>
                            <p><?php echo (int)$report['mental_health_score']; ?>%</p>
                        </div>
                        <div class="glass-card text-center">
                            <svg class="progress-ring" data-progress="<?php echo (int)$report['stress_score']; ?>" viewBox="0 0 120 120">
                                <circle class="track" cx="60" cy="60" r="52"></circle>
                                <circle class="progress" cx="60" cy="60" r="52"></circle>
                            </svg>
                            <h6 class="mt-3">Stress Score</h6>
                            <p><?php echo (int)$report['stress_score']; ?>%</p>
                        </div>
                        <div class="glass-card text-center">
                            <svg class="progress-ring" data-progress="<?php echo (int)$report['sleep_score']; ?>" viewBox="0 0 120 120">
                                <circle class="track" cx="60" cy="60" r="52"></circle>
                                <circle class="progress" cx="60" cy="60" r="52"></circle>
                            </svg>
                            <h6 class="mt-3">Sleep Risk</h6>
                            <p><?php echo (int)$report['sleep_score']; ?>%</p>
                        </div>
                        <div class="glass-card text-center">
                            <svg class="progress-ring" data-progress="<?php echo (int)$report['productivity_score']; ?>" viewBox="0 0 120 120">
                                <circle class="track" cx="60" cy="60" r="52"></circle>
                                <circle class="progress" cx="60" cy="60" r="52"></circle>
                            </svg>
                            <h6 class="mt-3">Productivity Impact</h6>
                            <p><?php echo (int)$report['productivity_score']; ?>%</p>
                        </div>
                        <div class="glass-card text-center">
                            <svg class="progress-ring" data-progress="<?php echo (int)$emotionalScore; ?>" viewBox="0 0 120 120">
                                <circle class="track" cx="60" cy="60" r="52"></circle>
                                <circle class="progress" cx="60" cy="60" r="52"></circle>
                            </svg>
                            <h6 class="mt-3">Emotional Stability</h6>
                            <p><?php echo (int)$emotionalScore; ?>% | <?php echo e($sentimentLabel); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass-panel mt-4">
                <h5>AI Summary</h5>
                <p><?php echo e($aiSummary); ?></p>
                <h6 class="mt-3">Personalized Recommendations</h6>
                <div class="row g-3">
                    <?php foreach ($recommendationList as $item): ?>
                        <div class="col-md-4">
                            <div class="glass-card h-100"><?php echo e($item); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 mt-4">
                <button class="btn btn-gradient" id="download-report">Download PDF Report</button>
                <button class="btn btn-outline-light" id="print-report">Print Report</button>
                <button class="btn btn-outline-light" id="share-report">Share Report</button>
                <button class="btn btn-outline-light" id="voice-report">Voice Narration</button>
            </div>
        </div>
    </div>
</section>

<script>
    window.reportData = <?php echo json_encode($reportData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;
</script>

<?php
require_once __DIR__ . '/partials/main_footer.php';
?>
