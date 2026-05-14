<?php
$pageTitle = 'Mental Health Assessment';
$pageScripts = ['assets/js/assessment.js'];
require_once __DIR__ . '/partials/main_header.php';
?>

<section class="section">
    <div class="container">
        <div class="glass-panel" data-aos="fade-up">
            <h2 class="section-title">AI Mental Health Assessment</h2>
            <p>Answer 10 questions to receive a real-time mental health risk report powered by our AI scoring logic.</p>

            <div class="assessment-progress">
                <span></span>
            </div>

            <form id="assessment-form" method="post" action="assessment_submit.php">
                <div class="assessment-step active" data-step="1">
                    <h5>Step 1 - User Details</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Age</label>
                            <input type="number" name="age" class="form-control" min="12" max="80" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select" required>
                                <option value="">Select</option>
                                <option>Male</option>
                                <option>Female</option>
                                <option>Non-binary</option>
                                <option>Prefer not to say</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Occupation</label>
                            <input type="text" name="occupation" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="assessment-step" data-step="2">
                    <h5>Step 2 - Assessment Questions</h5>
                    <div class="mt-3">
                        <label class="form-label">1. How many hours do you spend daily on social media?</label>
                        <input type="range" name="question_1" class="form-range" min="1" max="5" value="3" data-range data-range-output="q1output" required>
                        <div>Hours scale: <span id="q1output">3</span></div>
                    </div>

                    <div class="mt-4">
                        <label class="form-label">2. Do you compare yourself with others online?</label>
                        <div class="likert">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <label>
                                    <input type="radio" name="question_2" value="<?php echo $i; ?>" required>
                                    <span><?php echo $i; ?></span>
                                </label>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="form-label">3. Does social media affect your concentration?</label>
                        <div class="likert">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <label>
                                    <input type="radio" name="question_3" value="<?php echo $i; ?>" required>
                                    <span><?php echo $i; ?></span>
                                </label>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="form-label">4. Do you feel anxious without checking social media?</label>
                        <div class="emoji-group">
                            <?php
                            $emotionIcons = ['fa-face-smile', 'fa-face-meh', 'fa-face-frown', 'fa-face-frown-open', 'fa-face-angry'];
                            for ($i = 1; $i <= 5; $i++):
                                $icon = $emotionIcons[$i - 1];
                            ?>
                                <label>
                                    <input type="radio" name="question_4" value="<?php echo $i; ?>" required>
                                    <span><i class="fa-solid <?php echo $icon; ?>"></i></span>
                                </label>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="form-label">5. Has social media affected your sleep quality?</label>
                        <input type="range" name="question_5" class="form-range" min="1" max="5" value="3" data-range data-range-output="q5output" required>
                        <div>Impact scale: <span id="q5output">3</span></div>
                    </div>

                    <div class="mt-4">
                        <label class="form-label">6. Do you use social media aimlessly?</label>
                        <div class="likert">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <label>
                                    <input type="radio" name="question_6" value="<?php echo $i; ?>" required>
                                    <span><?php echo $i; ?></span>
                                </label>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="form-label">7. Does social media reduce your productivity?</label>
                        <div class="likert">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <label>
                                    <input type="radio" name="question_7" value="<?php echo $i; ?>" required>
                                    <span><?php echo $i; ?></span>
                                </label>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="form-label">8. Do you feel emotionally stressed after using social media?</label>
                        <div class="emoji-group">
                            <?php
                            $stressIcons = ['fa-face-smile', 'fa-face-meh', 'fa-face-frown', 'fa-face-frown-open', 'fa-face-angry'];
                            for ($i = 1; $i <= 5; $i++):
                                $icon = $stressIcons[$i - 1];
                            ?>
                                <label>
                                    <input type="radio" name="question_8" value="<?php echo $i; ?>" required>
                                    <span><i class="fa-solid <?php echo $icon; ?>"></i></span>
                                </label>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="form-label">9. Have you tried reducing social media usage?</label>
                        <div class="likert">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <label>
                                    <input type="radio" name="question_9" value="<?php echo $i; ?>" required>
                                    <span><?php echo $i; ?></span>
                                </label>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="form-label">10. Do you feel happier after limiting social media?</label>
                        <div class="likert">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <label>
                                    <input type="radio" name="question_10" value="<?php echo $i; ?>" required>
                                    <span><?php echo $i; ?></span>
                                </label>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-light" id="step-back" disabled>Back</button>
                    <button type="button" class="btn btn-gradient" id="step-next">Next</button>
                    <button type="submit" class="btn btn-gradient d-none" id="step-submit">Submit Assessment</button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/partials/main_footer.php';
?>
