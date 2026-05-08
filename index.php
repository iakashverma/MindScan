<?php
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
        </div>
    </div>
</section>

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
            </div>
        </div>
    </div>
</section>

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