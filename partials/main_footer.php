</main>

<footer class="footer" style="background: var(--card); padding: 40px 0; border-top: 1px solid var(--glass-border); margin-top: 60px;">
    <div class="container">
        <div class="row gy-4">
            <div class="col-md-6">
                <h5>MindScan</h5>
                <p>AI-driven mental health analytics for academic research, awareness, and action.</p>
            </div>
            <div class="col-md-3">
                <h6>Project</h6>
                <ul class="list-unstyled">
                    <li>Effects of Social Media on Mental Health</li>
                    <li>Lovely Professional University</li>
                    <li>Guide: Dr. Rajni Bhalla</li>
                </ul>
            </div>
            <div class="col-md-3">
                <h6>Quick Links</h6>
                <ul class="list-unstyled">
                    <li><a href="assessment.php">Take Assessment</a></li>
                    <li><a href="dashboard.php">View Dashboard</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <span>Next Generation Project Showcase - 2026</span>
        </div>
    </div>
</footer>

<!-- <div class="chatbot">
    <button class="chatbot-toggle" id="chatbot-toggle" type="button">
        <i class="fa-solid fa-robot"></i>
    </button>
    <div class="chatbot-window" id="chatbot-window">
        <div class="chatbot-header">
            <span>MindScan Assistant</span>
            <button type="button" id="chatbot-close" aria-label="Close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="chatbot-body">
            <div class="chatbot-message bot">Hello! I can guide you through the assessment or the dashboard insights.</div>
            <div class="chatbot-message user">Show me the latest research highlights.</div>
            <div class="chatbot-message bot">Open the dashboard to explore charts and statistics.</div>
        </div>
        <div class="chatbot-input">
            <input type="text" placeholder="Ask MindScan..." disabled>
            <button type="button" disabled><i class="fa-solid fa-paper-plane"></i></button>
        </div>
    </div>
</div>

<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="themeToast" class="toast text-bg-dark" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">Theme updated.</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div> -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php foreach ($vendorScripts as $script): ?>
<script src="<?php echo e($script); ?>"></script>
<?php endforeach; ?>

<script src="assets/js/main.js"></script>

<?php foreach ($pageScripts as $script): ?>
<script src="<?php echo e($script); ?>"></script>
<?php endforeach; ?>
</body>
</html>
