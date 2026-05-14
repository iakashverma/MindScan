<?php
$pageTitle = 'Contact / Team';
require_once __DIR__ . '/partials/main_header.php';
?>

<section class="section">
    <div class="container">
        <div class="glass-panel" data-aos="fade-up">
            <h2 class="section-title">Team and Contact</h2>
            <p>Connect with the research team behind MindScan and explore project details.</p>
        </div>

        <div class="row g-4 mt-4">
            <div class="col-lg-7" data-aos="fade-right">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="glass-card">
                            <h5>Akash Verma</h5>
                            <p>12503475</p>
                            <div class="d-flex gap-2">
                                <a href="#" aria-label="LinkedIn"><i class="fa-brands fa-linkedin"></i></a>
                                <a href="#" aria-label="GitHub"><i class="fa-brands fa-github"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="glass-card">
                            <h5>Om Jaiswal</h5>
                            <p>12525580</p>
                            <div class="d-flex gap-2">
                                <a href="#" aria-label="LinkedIn"><i class="fa-brands fa-linkedin"></i></a>
                                <a href="#" aria-label="GitHub"><i class="fa-brands fa-github"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="glass-card">
                            <h5>Kunal Mishra</h5>
                            <p>12506997</p>
                            <div class="d-flex gap-2">
                                <a href="#" aria-label="LinkedIn"><i class="fa-brands fa-linkedin"></i></a>
                                <a href="#" aria-label="GitHub"><i class="fa-brands fa-github"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="glass-card mt-4">
                    <h6>Guide</h6>
                    <p>Dr. Rajni Bhalla</p>
                    <h6>University</h6>
                    <p>Lovely Professional University</p>
                </div>
            </div>
            <div class="col-lg-5" data-aos="fade-left">
                <div class="glass-panel">
                    <h5>Contact Form</h5>
                    <form method="post" action="contact_submit.php">
                        <div class="mb-3">
                            <label class="form-label">Your Name</label>
                            <input type="text" name="contact_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="contact_email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea name="contact_message" class="form-control" rows="4" required></textarea>
                        </div>
                        <button class="btn btn-gradient" type="submit">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
if (isset($_GET['status'])):
    $status = $_GET['status'] === 'sent' ? 'sent' : 'error';
?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        <?php if ($status === 'sent'): ?>
        Swal.fire('Message sent', 'Your message has been delivered to the research team.', 'success');
        <?php else: ?>
        Swal.fire('Unable to send', 'Please verify your email settings and try again.', 'error');
        <?php endif; ?>
    });
</script>
<?php endif; ?>

<?php
require_once __DIR__ . '/partials/main_footer.php';
?>
