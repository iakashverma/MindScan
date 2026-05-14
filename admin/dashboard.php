<?php
$pageTitle = 'Admin Dashboard';
$vendorScripts = ['https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js'];
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../partials/admin_header.php';

$pdo = get_db();
$totalUsers = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$avgStress = (float)$pdo->query('SELECT AVG(stress_score) FROM assessments')->fetchColumn();

$commonRiskStmt = $pdo->query('SELECT risk_level, COUNT(*) AS total FROM assessments GROUP BY risk_level ORDER BY total DESC LIMIT 1');
$commonRisk = $commonRiskStmt->fetch();
$commonRiskLabel = $commonRisk ? $commonRisk['risk_level'] : 'N/A';

$chartRows = $pdo->query('SELECT risk_level, COUNT(*) AS total FROM assessments GROUP BY risk_level')->fetchAll();
$chartData = [];
foreach ($chartRows as $row) {
    $chartData[] = [
        'label' => $row['risk_level'],
        'value' => (int)$row['total'],
    ];
}

$records = $pdo->query('SELECT a.id, a.risk_level, a.mental_health_score, a.stress_score, a.sleep_score, a.productivity_score, a.created_at, u.name, u.age, u.gender FROM assessments a JOIN users u ON a.user_id = u.id ORDER BY a.created_at DESC LIMIT 50')->fetchAll();
?>

<div class="row g-4">
    <div class="col-md-4">
        <div class="glass-card">
            <h6>Total Users</h6>
            <h3><?php echo $totalUsers; ?></h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="glass-card">
            <h6>Average Stress Score</h6>
            <h3><?php echo number_format($avgStress, 1); ?>%</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="glass-card">
            <h6>Most Common Risk</h6>
            <h3><?php echo e($commonRiskLabel); ?></h3>
        </div>
    </div>
</div>

<div class="row g-4 mt-4">
    <div class="col-lg-6">
        <div class="glass-panel">
            <h5>Risk Category Distribution</h5>
            <canvas id="adminChart" height="200" data-chart='<?php echo json_encode($chartData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>'></canvas>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="glass-panel">
            <h5>Export Data</h5>
            <p>Download assessment records as CSV for research documentation.</p>
            <a class="btn btn-gradient" href="export.php">Export CSV</a>
        </div>
    </div>
</div>

<div class="glass-panel mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>Assessment Records</h5>
        <input type="text" id="admin-search" class="form-control" style="max-width: 240px;" placeholder="Search records">
    </div>
    <div class="table-responsive">
        <table class="table table-dark table-striped" id="records-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Risk</th>
                    <th>Mental Score</th>
                    <th>Stress</th>
                    <th>Sleep</th>
                    <th>Productivity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $row): ?>
                    <tr>
                        <td><?php echo e($row['name']); ?></td>
                        <td><?php echo e((string)$row['age']); ?></td>
                        <td><?php echo e($row['gender']); ?></td>
                        <td><?php echo e($row['risk_level']); ?></td>
                        <td><?php echo e((string)$row['mental_health_score']); ?></td>
                        <td><?php echo e((string)$row['stress_score']); ?></td>
                        <td><?php echo e((string)$row['sleep_score']); ?></td>
                        <td><?php echo e((string)$row['productivity_score']); ?></td>
                        <td>
                            <a class="btn btn-sm btn-outline-light" href="../report.php?id=<?php echo (int)$row['id']; ?>">View</a>
                            <form class="d-inline delete-form" method="post" action="delete.php">
                                <input type="hidden" name="id" value="<?php echo (int)$row['id']; ?>">
                                <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once __DIR__ . '/../partials/admin_footer.php';
?>
