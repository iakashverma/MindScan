<?php
$pageTitle = 'Data Insights';
$vendorScripts = ['https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js'];
$pageScripts = ['assets/js/insights.js'];
$bodyClass = 'insights-body';
require_once __DIR__ . '/partials/main_header.php';
?>

<section class="section insights-section">
    <div class="container insights-shell">
        <div class="insights-hero text-center">
            <h1 class="insights-title">Research <span>Findings</span></h1>
            <p class="insights-subtitle">Key discoveries from our comprehensive analysis of 277 participants' social media usage patterns and mental health data.</p>
        </div>

        <div class="findings-grid">
            <article class="findings-card">
                <div class="findings-icon icon-warm"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <span class="findings-value">47.9%</span>
                <h5>Negative Life Impact</h5>
                <p>Nearly half of participants reported negative life impact from excessive social media use.</p>
            </article>
            <article class="findings-card">
                <div class="findings-icon icon-cool"><i class="fa-solid fa-arrow-trend-up"></i></div>
                <span class="findings-value">72%</span>
                <h5>Screen Time &amp; Stress</h5>
                <p>Strong positive correlation found between daily screen time and reported stress levels.</p>
            </article>
            <article class="findings-card">
                <div class="findings-icon icon-violet"><i class="fa-solid fa-moon"></i></div>
                <span class="findings-value">43.7%</span>
                <h5>Sleep Disturbances</h5>
                <p>Significant sleep disturbances reported among users with 4+ hours daily screen time.</p>
            </article>
            <article class="findings-card">
                <div class="findings-icon icon-amber"><i class="fa-solid fa-clock"></i></div>
                <span class="findings-value">62.3%</span>
                <h5>Productivity Decline</h5>
                <p>Majority experienced productivity decline due to aimless scrolling and notifications.</p>
            </article>
            <article class="findings-card">
                <div class="findings-icon icon-pink"><i class="fa-solid fa-user-group"></i></div>
                <span class="findings-value">55.2%</span>
                <h5>Social Comparison</h5>
                <p>Over half reported emotional distress from social comparison on platforms.</p>
            </article>
            <article class="findings-card">
                <div class="findings-icon icon-teal"><i class="fa-solid fa-brain"></i></div>
                <span class="findings-value">58.8%</span>
                <h5>Concentration Issues</h5>
                <p>Reduced attention span and concentration linked to frequent social media checking.</p>
            </article>
        </div>

        
        <div class="insights-tabs">
            <div class="insights-tablist" role="tablist" aria-label="Insights navigation">
                <button class="insights-tab active" type="button" role="tab" aria-selected="true" data-tab="platform">Platform Usage</button>
                <button class="insights-tab" type="button" role="tab" aria-selected="false" data-tab="stress">Stress Analysis</button>
                <button class="insights-tab" type="button" role="tab" aria-selected="false" data-tab="mood">Mood Trends</button>
                <button class="insights-tab" type="button" role="tab" aria-selected="false" data-tab="demographics">Demographics</button>
            </div>
            <!-- <button class="insights-export" type="button" id="insights-export" aria-label="Export CSV">
                <i class="fa-solid fa-file-csv"></i>
                Export CSV
            </button> -->
        </div>

        <div class="insights-panels">
            <div class="insights-panel active" data-panel="platform">
                <div class="insights-card usage-card">
                    <div class="usage-details">
                        <h3>Social Media Platform Usage</h3>
                        <p class="insights-muted">Distribution among 277 participants</p>
                        <ul class="usage-list">
                            <li><span class="usage-label"><span class="usage-dot dot-instagram"></span>Instagram</span><strong>78%</strong></li>
                            <li><span class="usage-label"><span class="usage-dot dot-youtube"></span>YouTube</span><strong>65%</strong></li>
                            <li><span class="usage-label"><span class="usage-dot dot-whatsapp"></span>WhatsApp</span><strong>82%</strong></li>
                            <li><span class="usage-label"><span class="usage-dot dot-facebook"></span>Facebook</span><strong>34%</strong></li>
                            <li><span class="usage-label"><span class="usage-dot dot-twitter"></span>Twitter/X</span><strong>28%</strong></li>
                            <li><span class="usage-label"><span class="usage-dot dot-snapchat"></span>Snapchat</span><strong>22%</strong></li>
                            <li><span class="usage-label"><span class="usage-dot dot-linkedin"></span>LinkedIn</span><strong>18%</strong></li>
                            <li><span class="usage-label"><span class="usage-dot dot-tiktok"></span>TikTok</span><strong>42%</strong></li>
                            <li><span class="usage-label"><span class="usage-dot dot-reddit"></span>Reddit</span><strong>11%</strong></li>
                        </ul>
                    </div>
                    <div class="usage-chart">
                        <div class="usage-chart-canvas">
                            <canvas id="platformUsageChart" aria-label="Platform usage chart" role="img"></canvas>
                        </div>
                        <div class="usage-legend">
                            <span class="legend-item"><span class="usage-dot dot-instagram"></span>Instagram</span>
                            <span class="legend-item"><span class="usage-dot dot-youtube"></span>YouTube</span>
                            <span class="legend-item"><span class="usage-dot dot-whatsapp"></span>WhatsApp</span>
                            <span class="legend-item"><span class="usage-dot dot-facebook"></span>Facebook</span>
                            <span class="legend-item"><span class="usage-dot dot-twitter"></span>Twitter/X</span>
                            <span class="legend-item"><span class="usage-dot dot-snapchat"></span>Snapchat</span>
                            <span class="legend-item"><span class="usage-dot dot-linkedin"></span>LinkedIn</span>
                            <span class="legend-item"><span class="usage-dot dot-tiktok"></span>TikTok</span>
                            <span class="legend-item"><span class="usage-dot dot-reddit"></span>Reddit</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="insights-panel" data-panel="stress">
                <div class="insights-card stress-card">
                    <div class="stress-header">
                        <h3 class="stress-title">Screen Time vs Stress Level</h3>
                        <p class="stress-subtitle">Correlation between daily screen time and reported stress levels.</p>
                    </div>
                    <div class="stress-chart">
                        <canvas id="stressCorrelationChart" aria-label="Screen time versus stress chart" role="img"></canvas>
                    </div>
                    <div class="stress-legend" aria-label="Chart legend">
                        <span class="stress-legend-item"><span class="stress-dot stress-dot-red"></span>Stress Level (%)</span>
                        <span class="stress-legend-item"><span class="stress-dot stress-dot-teal"></span>Participants</span>
                    </div>
                </div>
            </div>
            <div class="insights-panel" data-panel="mood">
                <div class="insights-card mood-card">
                    <div class="mood-header">
                        <h3 class="mood-title">Weekly Mood Variation Trends</h3>
                        <p class="mood-subtitle">How mood states fluctuate throughout the week.</p>
                    </div>
                    <div class="mood-chart">
                        <canvas id="moodTrendsChart" aria-label="Weekly mood trends chart" role="img"></canvas>
                    </div>
                    <div class="mood-legend" aria-label="Chart legend">
                        <span class="mood-legend-item"><span class="mood-dot mood-happy"></span>Happy</span>
                        <span class="mood-legend-item"><span class="mood-dot mood-neutral"></span>Neutral</span>
                        <span class="mood-legend-item"><span class="mood-dot mood-sad"></span>Sad</span>
                        <span class="mood-legend-item"><span class="mood-dot mood-stressed"></span>Stressed</span>
                    </div>
                </div>
            </div>
            <div class="insights-panel" data-panel="demographics">
                <div class="insights-card demographics-shell">
                    <div class="demographics-grid">
                        <div class="insights-card demo-card">
                            <h4 class="demo-title">Age Distribution</h4>
                            <div class="demo-chart">
                                <canvas id="ageDistributionChart" aria-label="Age distribution chart" role="img"></canvas>
                            </div>
                            <div class="demo-legend">
                                <span class="demo-legend-item"><span class="demo-dot dot-age-13"></span>13-17</span>
                                <span class="demo-legend-item"><span class="demo-dot dot-age-18"></span>18-24</span>
                                <span class="demo-legend-item"><span class="demo-dot dot-age-25"></span>25-34</span>
                                <span class="demo-legend-item"><span class="demo-dot dot-age-35"></span>35-44</span>
                                <span class="demo-legend-item"><span class="demo-dot dot-age-45"></span>45+</span>
                            </div>
                        </div>

                        <div class="insights-card demo-card">
                            <h4 class="demo-title">Gender Distribution</h4>
                            <div class="demo-chart">
                                <canvas id="genderDistributionChart" aria-label="Gender distribution chart" role="img"></canvas>
                            </div>
                            <div class="demo-legend">
                                <span class="demo-legend-item"><span class="demo-dot dot-gender-male"></span>Male</span>
                                <span class="demo-legend-item"><span class="demo-dot dot-gender-female"></span>Female</span>
                                <span class="demo-legend-item"><span class="demo-dot dot-gender-non"></span>Non-binary</span>
                                <span class="demo-legend-item"><span class="demo-dot dot-gender-none"></span>Prefer not to say</span>
                            </div>
                        </div>

                        <div class="insights-card demo-card">
                            <h4 class="demo-title">Occupation Analysis</h4>
                            <div class="demo-chart demo-chart-tall">
                                <canvas id="occupationAnalysisChart" aria-label="Occupation analysis chart" role="img"></canvas>
                            </div>
                            <div class="demo-legend">
                                <span class="demo-legend-item"><span class="demo-dot dot-occupation"></span>Participants</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<?php
require_once __DIR__ . '/partials/main_footer.php';
?>
