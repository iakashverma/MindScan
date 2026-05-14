<?php
<<<<<<< HEAD
$pageTitle = 'Home';
$vendorScripts = ['https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js'];
$pageScripts = ['assets/js/home.js'];
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/partials/main_header.php';

$researchItems = [];
$researchError = '';
$documents = [];
$documentError = '';
$documentGroups = [
    'paper' => [],
    'synopsis' => [],
    'dataset' => [],
];

$pdo = null;
try {
    $pdo = get_db();
} catch (Throwable $e) {
    $researchError = 'Research content is not available yet.';
    $documentError = 'Research documents are not available yet.';
}

if ($pdo) {
    try {
        $stmt = $pdo->query('SELECT id, title, item_type, body, stat_label, stat_value, image_path, created_at FROM research_items ORDER BY created_at DESC');
        $researchItems = $stmt->fetchAll();
    } catch (Throwable $e) {
        $researchError = 'Research content is not available yet.';
    }

    try {
        $docStmt = $pdo->query('SELECT id, title, doc_type, description, file_path, created_at FROM research_documents ORDER BY created_at DESC');
        $documents = $docStmt->fetchAll();
        foreach ($documents as $doc) {
            $type = (string)($doc['doc_type'] ?? '');
            if (isset($documentGroups[$type])) {
                $documentGroups[$type][] = $doc;
            }
        }
    } catch (Throwable $e) {
        $documentError = 'Research documents are not available yet.';
    }
}
?>

<section class="section" id="about">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-7" data-aos="fade-right">
                <div class="section-head">
                    <h2 class="section-title">About the Project</h2>
                    <p class="section-subtitle">MindScan is a focused research platform that maps social media habits to mental wellness indicators. It is designed to support academic analysis while keeping the experience calming, minimal, and privacy-first.</p>
                </div>
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="glass-card">
                            <h6>Purpose</h6>
                            <p>Provide a structured way to observe how digital behavior shapes mood, sleep, and focus.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="glass-card">
                            <h6>Objectives</h6>
                            <p>Collect signals through screening, highlight trends, and support research reporting.</p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="glass-card">
                            <h6>Why It Matters</h6>
                            <p>Students and young adults face growing online pressure. MindScan translates that impact into measurable, actionable insights.</p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="glass-card">
                            <h6>Key Findings Preview</h6>
                            <ol class="key-findings">
                                <li>Excessive usage (4+ hours/day) significantly increases distraction and reduces focus.</li>
                                <li>Emotional instability is strongly linked with increased screen time, especially before sleep.</li>
                                <li>Sleep disturbances are 2.3x more common among heavy social media users.</li>
                                <li>Productivity decline of 40-60% reported by users who engage in aimless scrolling.</li>
                                <li>Social comparison features drive the highest rates of anxiety and low self-esteem.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-lg-5" data-aos="fade-left">
                <div class="glass-panel about-figure">
                    <div class="soft-illustration" aria-hidden="true"></div>
                    <div class="about-facts">
                        <div class="about-fact">
                            <span class="fact-label">Focus</span>
                            <strong>Social media impact</strong>
                        </div>
                        <div class="about-fact">
                            <span class="fact-label">Method</span>
                            <strong>Screening and dashboards</strong>
                        </div>
                        <div class="about-fact">
                            <span class="fact-label">Ethos</span>
                            <strong>Privacy-first design</strong>
                        </div>
                    </div>
                </div>
            </div> -->
=======
/**
 * MindScan - Home Page
 * Premium landing page with hero, stats, features, and research highlights
 */
$currentPage = 'home';
require_once __DIR__ . '/includes/config.php';

// Get a random quote for the footer
$quoteStmt = $pdo->query("SELECT quote_text, author FROM quotes ORDER BY RAND() LIMIT 1");
$dailyQuote = $quoteStmt->fetch();

// Count stats
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalAssessments = $pdo->query("SELECT COUNT(*) FROM assessments")->fetchColumn();

require_once __DIR__ . '/includes/header.php';
?>

<!-- ── Hero Section ──────────────────────────────────────── -->
<section class="hero" id="hero">
    <div class="hero-bg">
        <div class="hero-gradient"></div>
        <div class="hero-particles">
            <div class="particle" style="left:10%;top:20%"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>
    </div>
    <div class="hero-content">
        <div class="hero-badge">
            <i class="fas fa-atom"></i>
            AI-Powered Behavioral Intelligence Platform
        </div>
        <h1 class="hero-title">Understand Your Digital Behavior.<br>Transform Your Digital Wellness.</h1>
        <p class="hero-subtitle">AI-powered behavioral analytics platform focused on social media habit awareness,
            self-awareness measurement, and positive digital change prediction.</p>
        <div class="hero-actions">
            <a href="<?php echo APP_URL; ?>/pages/assessment.php" class="btn btn-primary btn-lg">
                <i class="fas fa-brain"></i> Start Assessment
            </a>
            <a href="<?php echo APP_URL; ?>/pages/dashboard.php" class="btn btn-outline btn-lg">
                <i class="fas fa-chart-line"></i> View Dashboard
            </a>
        </div>
        <div class="hero-stats">
            <div class="stat-item">
                <div class="stat-number" data-count="1500" data-suffix="+">0</div>
                <div class="stat-label">Users Assessed</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-count="92" data-suffix="%">0</div>
                <div class="stat-label">Accuracy Rate</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-count="15" data-suffix="">0</div>
                <div class="stat-label">Behavior Metrics</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-count="5" data-suffix="">0</div>
                <div class="stat-label">AI Models</div>
            </div>
        </div>
    </div>
</section>

<!-- ── Why Digital Wellness Matters ───────────────────────── -->
<section class="section" id="why-wellness">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-badge"><i class="fas fa-shield-heart"></i> Why It Matters</span>
            <h2 class="section-title">Why Digital Wellness Matters</h2>
            <p class="section-desc">The average person spends 2+ hours daily on social media. Understanding your
                patterns is the first step toward intentional digital living.</p>
        </div>
        <div class="feature-grid">
            <div class="glass-card reveal">
                <div class="card-icon blue"><i class="fas fa-brain"></i></div>
                <h3 class="card-title">Self-Awareness</h3>
                <p class="card-text">Research shows that self-aware individuals are 68% more likely to successfully
                    modify their digital habits. Know yourself, change yourself.</p>
            </div>
            <div class="glass-card reveal">
                <div class="card-icon purple"><i class="fas fa-mobile-screen"></i></div>
                <h3 class="card-title">Social Media Impact</h3>
                <p class="card-text">Excessive social media use correlates with increased anxiety, reduced sleep
                    quality, and decreased productivity across all demographics.</p>
            </div>
            <div class="glass-card reveal">
                <div class="card-icon cyan"><i class="fas fa-chart-mixed"></i></div>
                <h3 class="card-title">Behavioral Patterns</h3>
                <p class="card-text">AI-driven analysis reveals hidden behavioral patterns that users cannot detect on
                    their own, enabling data-driven change strategies.</p>
            </div>
>>>>>>> 29a15dde4f1bec4dab4fd0e1fad057bd0c04bd27
        </div>
    </div>
</section>

<<<<<<< HEAD
<section class="section objectives-section" id="objectives">
    <div class="container objectives-shell">
        <div class="objectives-header text-center">
            <h2 class="objectives-title">Research <span>Objectives</span></h2>
            <p class="objectives-subtitle">Our study is guided by six core objectives designed to comprehensively understand the social media-mental health relationship.</p>
        </div>

        <div class="objectives-grid">
            <article class="objectives-card">
                <div class="objectives-icon icon-teal"><i class="fa-solid fa-brain"></i></div>
                <span class="objectives-label">OBJECTIVE 01</span>
                <h5>Mental Health Analysis</h5>
                <p>Quantify the psychological impact of social media usage patterns on anxiety, depression, stress, and emotional well-being using validated metrics.</p>
            </article>
            <article class="objectives-card">
                <div class="objectives-icon icon-blue"><i class="fa-solid fa-chart-line"></i></div>
                <span class="objectives-label">OBJECTIVE 02</span>
                <h5>Behavioral Analytics</h5>
                <p>Analyze user behavior patterns including usage frequency, duration, platform preferences, and their correlation with behavioral changes.</p>
            </article>
            <article class="objectives-card">
                <div class="objectives-icon icon-pink"><i class="fa-solid fa-comment-dots"></i></div>
                <span class="objectives-label">OBJECTIVE 03</span>
                <h5>Sentiment Analysis</h5>
                <p>Apply NLP techniques to analyze open-ended survey responses, identifying emotional patterns and sentiment distribution across user groups.</p>
            </article>
            <article class="objectives-card">
                <div class="objectives-icon icon-green"><i class="fa-solid fa-arrow-trend-up"></i></div>
                <span class="objectives-label">OBJECTIVE 04</span>
                <h5>ML-Based Prediction</h5>
                <p>Develop machine learning models to predict mental health risk levels based on social media usage patterns and demographic factors.</p>
            </article>
            <article class="objectives-card">
                <div class="objectives-icon icon-amber"><i class="fa-solid fa-shield-heart"></i></div>
                <span class="objectives-label">OBJECTIVE 05</span>
                <h5>Digital Wellness Framework</h5>
                <p>Create evidence-based guidelines and assessment tools for promoting healthier digital habits and social media awareness.</p>
            </article>
            <article class="objectives-card">
                <div class="objectives-icon icon-warm"><i class="fa-solid fa-bolt"></i></div>
                <span class="objectives-label">OBJECTIVE 06</span>
                <h5>Awareness &amp; Prevention</h5>
                <p>Build an interactive platform that educates users about the effects of social media and provides tools for self-assessment and improvement.</p>
            </article>
        </div>
    </div>
</section>

<section class="section" id="research-dashboard">
    <div class="container">
        <!-- <div class="row g-3 align-items-end mb-4">
            <div class="col-lg-8">
                <h2 class="section-title">Research Dashboard</h2>
                <p class="section-subtitle">Admin-managed research content including charts, images, statistics, and summaries. Add or update items in the admin console and the dashboard updates automatically.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a class="btn btn-outline-light" href="admin/login.php">Admin Manage Content</a>
            </div>
        </div>

        <?php if ($researchError): ?>
            <div class="glass-panel research-empty">
                <?php echo e($researchError); ?>
            </div>
        <?php elseif (empty($researchItems)): ?>
            <div class="glass-panel research-empty">
                No research items yet. Add content from the admin console to populate this dashboard.
            </div>
        <?php else: ?>
            <div class="research-grid">
                <?php foreach ($researchItems as $item): ?>
                    <article class="glass-card research-card" data-aos="fade-up">
                        <?php
                        $createdAt = $item['created_at'] ?? '';
                        $displayDate = $createdAt ? date('M d, Y', strtotime((string)$createdAt)) : 'N/A';
                        ?>
                        <div class="research-meta">
                            <span class="research-type"><?php echo e(ucfirst((string)$item['item_type'])); ?></span>
                            <span class="research-date"><?php echo e($displayDate); ?></span>
                        </div>
                        <h5><?php echo e($item['title']); ?></h5>

                        <?php if ($item['item_type'] === 'stat'): ?>
                            <div class="research-stat">
                                <div class="stat-value"><?php echo e((string)$item['stat_value']); ?></div>
                                <div class="stat-label"><?php echo e((string)$item['stat_label']); ?></div>
                            </div>
                            <?php if (!empty($item['body'])): ?>
                                <p class="muted"><?php echo nl2br(e($item['body'])); ?></p>
                            <?php endif; ?>
                        <?php elseif ($item['item_type'] === 'image'): ?>
                            <?php if (!empty($item['image_path'])): ?>
                                <div class="research-image">
                                    <img src="<?php echo e($item['image_path']); ?>" alt="<?php echo e($item['title']); ?>" loading="lazy">
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($item['body'])): ?>
                                <p class="muted"><?php echo nl2br(e($item['body'])); ?></p>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="muted"><?php echo nl2br(e((string)$item['body'])); ?></p>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?> -->

        <div class="research-docs">
            <div class="section-head">
                <h3 class="section-title">Research Library</h3>
                <p class="section-subtitle">Browse uploaded PDFs for papers, synopsis documents, and datasets.</p>
            </div>

            <?php if ($documentError): ?>
                <div class="glass-panel research-empty">
                    <?php echo e($documentError); ?>
                </div>
            <?php else: ?>
                <div class="doc-grid">
                    <?php
                    $docTypeLabels = [
                        'paper' => 'Research Papers',
                        'synopsis' => 'Synopsis Documents',
                        'dataset' => 'Datasets',
                    ];
                    foreach ($docTypeLabels as $type => $label):
                        $docs = $documentGroups[$type] ?? [];
                    ?>
                        <div class="glass-panel doc-column">
                            <div class="doc-column-head">
                                <h6><?php echo e($label); ?></h6>
                                <span class="doc-count"><?php echo count($docs); ?> files</span>
                            </div>
                            <?php if (empty($docs)): ?>
                                <p class="muted">No <?php echo e(strtolower($label)); ?> uploaded yet.</p>
                            <?php else: ?>
                                <?php foreach ($docs as $doc): ?>
                                    <article class="doc-card">
                                        <div class="doc-title"><?php echo e($doc['title']); ?></div>
                                        <p class="doc-desc"><?php echo nl2br(e((string)$doc['description'])); ?></p>
                                        <?php if (!empty($doc['file_path'])): ?>
                                            <div class="doc-actions">
                                                <a class="btn btn-sm btn-outline-light" href="<?php echo e($doc['file_path']); ?>" target="_blank" rel="noopener">View</a>
                                                <a class="btn btn-sm btn-gradient" href="<?php echo e($doc['file_path']); ?>" download>Download</a>
                                            </div>
                                        <?php else: ?>
                                            <p class="muted">File missing.</p>
                                        <?php endif; ?>
                                    </article>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="section" id="screening">
    <div class="container">
        <div class="section-head">
            <h2 class="section-title">Mental Health Screening</h2>
            <p class="section-subtitle">Answer 10 short questions to receive a simple risk level. This is an educational screening and not a clinical diagnosis.</p>
        </div>
        <div class="glass-panel screening-shell" data-aos="fade-up">
            <div class="screening-progress">
                <span id="screening-progress"></span>
            </div>
            <div class="screening-alert" id="screening-alert" role="alert" aria-live="polite"></div>
            <div class="screening-card" id="screening-card">
                <div class="screening-count" id="screening-count">Question 1 of 10</div>
                <h5 id="screening-question"></h5>
                <div class="screening-options" id="screening-options" role="radiogroup" aria-label="Response options"></div>
                <div class="screening-scale">
                    <span>Never</span>
                    <span>Always</span>
                </div>
            </div>
            <div class="screening-result d-none" id="screening-result" aria-live="polite">
                <div class="result-score" id="screening-score"></div>
                <div class="risk-pill" id="screening-risk"></div>
                <p class="result-desc" id="screening-desc"></p>
                <div class="result-actions">
                    <button class="btn btn-outline-light" type="button" id="screening-restart">Retake Screening</button>
                    <a class="btn btn-gradient" href="assessment.php">Full Assessment</a>
                </div>
            </div>
            <div class="screening-nav" id="screening-nav">
                <button type="button" class="btn btn-outline-light" id="screening-back" disabled>Back</button>
                <button type="button" class="btn btn-gradient" id="screening-next">Next</button>
=======
<!-- ── Research Objectives ───────────────────────────────── -->
<section class="section" style="padding-top: 40px;">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-badge"><i class="fas fa-flask"></i> Research</span>
            <h2 class="section-title">Research Objectives</h2>
            <p class="section-desc">Our platform is grounded in rigorous behavioral science research focused on digital
                wellness and habit modification.</p>
        </div>
        <div class="feature-grid">
            <div class="glass-card reveal">
                <div class="card-icon green"><i class="fas fa-magnifying-glass-chart"></i></div>
                <h3 class="card-title">Analyze Usage Patterns</h3>
                <p class="card-text">Understand how users interact with social media — frequency, duration, emotional
                    triggers, and behavioral dependencies.</p>
            </div>
            <div class="glass-card reveal">
                <div class="card-icon orange"><i class="fas fa-gauge-high"></i></div>
                <h3 class="card-title">Measure Change Readiness</h3>
                <p class="card-text">Assess how prepared individuals are to make meaningful changes to their digital
                    consumption habits.</p>
            </div>
            <div class="glass-card reveal">
                <div class="card-icon pink"><i class="fas fa-robot"></i></div>
                <h3 class="card-title">Predict Success</h3>
                <p class="card-text">Use machine learning models to predict the likelihood of successful habit
                    modification based on behavioral markers.</p>
            </div>
            <div class="glass-card reveal">
                <div class="card-icon cyan"><i class="fas fa-hand-holding-heart"></i></div>
                <h3 class="card-title">Personalized Strategies</h3>
                <p class="card-text">Generate tailored wellness recommendations based on individual behavioral profiles
                    and readiness levels.</p>
            </div>
            <div class="glass-card reveal">
                <div class="card-icon blue"><i class="fas fa-eye"></i></div>
                <h3 class="card-title">Build Self-Awareness</h3>
                <p class="card-text">Help users recognize unconscious digital habits and develop mindful technology
                    usage patterns.</p>
            </div>
            <div class="glass-card reveal">
                <div class="card-icon purple"><i class="fas fa-chart-pie"></i></div>
                <h3 class="card-title">Visualize Insights</h3>
                <p class="card-text">Transform complex behavioral data into intuitive, interactive visualizations for
                    research and personal understanding.</p>
>>>>>>> 29a15dde4f1bec4dab4fd0e1fad057bd0c04bd27
            </div>
        </div>
    </div>
</section>

<<<<<<< HEAD
<section class="section" id="mood">
    <div class="container">
        <div class="section-head">
            <h2 class="section-title">Mood Tracker</h2>
            <p class="section-subtitle">Log your mood daily and visualize trends over time. All mood data is stored locally in your browser and never uploaded.</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-5" data-aos="fade-right">
                <div class="glass-panel mood-shell">
                    <h5>How are you feeling today?</h5>
                    <div class="mood-options" id="mood-options"></div>
                    <div class="mood-message" id="mood-message" role="status"></div>
                    <p class="privacy-note">Your entries stay on this device only.</p>
                    <button class="btn btn-outline-light btn-sm" type="button" id="mood-clear">Clear mood history</button>
                </div>
            </div>
            <div class="col-lg-7" data-aos="fade-left">
                <div class="glass-panel mood-panel">
                    <div class="mood-chart-header">
                        <div>
                            <h5>7-Day Trend</h5>
                            <span class="muted">Based on your latest entries</span>
                        </div>
                    </div>
                    <div class="mood-chart">
                        <canvas id="mood-chart" height="200" role="img" aria-label="Mood trend chart"></canvas>
                    </div>
                    <div class="mood-history" id="mood-history"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/partials/main_footer.php';
?>
=======
<!-- ── Daily Quote ────────────────────────────────────────── -->
<?php if ($dailyQuote): ?>
    <section class="quote-section">
        <div class="container">
            <div class="quote-card reveal">
                <p class="quote-text">
                    <?php echo htmlspecialchars($dailyQuote['quote_text']); ?>
                </p>
                <p class="quote-author">—
                    <?php echo htmlspecialchars($dailyQuote['author']); ?>
                </p>
                <button class="btn btn-outline btn-sm quote-refresh" onclick="refreshQuote()">
                    <i class="fas fa-refresh"></i> New Quote
                </button>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- ── Call to Action ────────────────────────────────────── -->
<section class="section" style
>>>>>>> 29a15dde4f1bec4dab4fd0e1fad057bd0c04bd27
