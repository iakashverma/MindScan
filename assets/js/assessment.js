document.addEventListener('DOMContentLoaded', () => {
    const steps = Array.from(document.querySelectorAll('.assessment-step'));
    const progress = document.querySelector('.assessment-progress span');
    const nextBtn = document.getElementById('step-next');
    const backBtn = document.getElementById('step-back');
    const submitBtn = document.getElementById('step-submit');
    const form = document.getElementById('assessment-form');

    let current = 0;

    const showStep = (index) => {
        steps.forEach((step, i) => {
            step.classList.toggle('active', i === index);
        });
        if (progress) {
            progress.style.width = `${((index + 1) / steps.length) * 100}%`;
        }
        if (backBtn) {
            backBtn.disabled = index === 0;
        }
        if (nextBtn) {
            nextBtn.classList.toggle('d-none', index === steps.length - 1);
        }
        if (submitBtn) {
            submitBtn.classList.toggle('d-none', index !== steps.length - 1);
        }
    };

    const validateStep = () => {
        const active = steps[current];
        if (!active) return true;
        const requiredFields = active.querySelectorAll('[required]');
        for (const field of requiredFields) {
            if (field.type === 'radio') {
                const group = active.querySelectorAll(`input[name="${field.name}"]`);
                const hasSelection = Array.from(group).some((input) => input.checked);
                if (!hasSelection) {
                    return false;
                }
            } else if (!field.value) {
                field.focus();
                return false;
            }
        }
        return true;
    };

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            if (!validateStep()) {
                Swal.fire('Please complete all required fields before continuing.');
                return;
            }
            current = Math.min(current + 1, steps.length - 1);
            showStep(current);
        });
    }

    if (backBtn) {
        backBtn.addEventListener('click', () => {
            current = Math.max(current - 1, 0);
            showStep(current);
        });
    }

    if (form) {
        form.addEventListener('submit', (event) => {
            if (!validateStep()) {
                event.preventDefault();
                Swal.fire('Please complete all required fields before submitting.');
            }
        });
    }

    const ranges = document.querySelectorAll('[data-range]');
    ranges.forEach((range) => {
        const outputId = range.getAttribute('data-range-output');
        const output = outputId ? document.getElementById(outputId) : null;
        const update = () => {
            if (output) {
                output.textContent = range.value;
            }
        };
        range.addEventListener('input', update);
        update();
    });

    showStep(current);
});
