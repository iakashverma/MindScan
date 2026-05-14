document.addEventListener('DOMContentLoaded', () => {
    initScreening();
    initMoodTracker();
});

function initScreening() {
    const progressEl = document.getElementById('screening-progress');
    const countEl = document.getElementById('screening-count');
    const questionEl = document.getElementById('screening-question');
    const optionsEl = document.getElementById('screening-options');
    const alertEl = document.getElementById('screening-alert');
    const backBtn = document.getElementById('screening-back');
    const nextBtn = document.getElementById('screening-next');
    const navEl = document.getElementById('screening-nav');
    const cardEl = document.getElementById('screening-card');
    const resultEl = document.getElementById('screening-result');
    const scoreEl = document.getElementById('screening-score');
    const riskEl = document.getElementById('screening-risk');
    const descEl = document.getElementById('screening-desc');
    const restartBtn = document.getElementById('screening-restart');

    if (!progressEl || !countEl || !questionEl || !optionsEl || !backBtn || !nextBtn || !cardEl || !resultEl) {
        return;
    }

    const questions = [
        'How often do you feel anxious after using social media?',
        'Do you compare your lifestyle or appearance to others online?',
        'How often does screen time disrupt your sleep routine?',
        'Do you feel a strong urge to check notifications frequently?',
        'How often do social posts make you feel inadequate?',
        'Do you notice a negative mood shift after scrolling?',
        'How often do you use social media to avoid problems?',
        'Do social apps distract you from important tasks?',
        'How often does social media create conflict in relationships?',
        'Do you feel happier after reducing your social media use?'
    ];

    const optionLabels = ['Never', 'Rarely', 'Sometimes', 'Often', 'Always'];
    const state = {
        current: 0,
        answers: new Array(questions.length).fill(null),
    };

    const showAlert = (message) => {
        if (!alertEl) {
            return;
        }
        alertEl.textContent = message;
        alertEl.classList.toggle('active', Boolean(message));
    };

    const renderOptions = () => {
        optionsEl.innerHTML = '';
        optionLabels.forEach((label, index) => {
            const value = index + 1;
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'screening-option';
            button.textContent = label;
            button.setAttribute('data-value', String(value));
            button.setAttribute('aria-pressed', 'false');
            button.addEventListener('click', () => {
                state.answers[state.current] = value;
                updateSelection();
                showAlert('');
            });
            optionsEl.appendChild(button);
        });
    };

    const updateSelection = () => {
        const currentValue = state.answers[state.current];
        optionsEl.querySelectorAll('.screening-option').forEach((button) => {
            const value = parseInt(button.getAttribute('data-value') || '0', 10);
            const isActive = value === currentValue;
            button.classList.toggle('active', isActive);
            button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        });
    };

    const renderQuestion = () => {
        countEl.textContent = `Question ${state.current + 1} of ${questions.length}`;
        questionEl.textContent = questions[state.current];
        progressEl.style.width = `${((state.current + 1) / questions.length) * 100}%`;
        backBtn.disabled = state.current === 0;
        nextBtn.textContent = state.current === questions.length - 1 ? 'View Result' : 'Next';
        renderOptions();
        updateSelection();
    };

    const showResult = () => {
        const adjustedAnswers = state.answers.map((value, index) => {
            if (index === questions.length - 1 && value !== null) {
                return 6 - value;
            }
            return value;
        });
        const total = adjustedAnswers.reduce((sum, value) => sum + (value || 0), 0);
        const minScore = questions.length;
        const maxScore = questions.length * 5;
        const percent = Math.round(((total - minScore) / (maxScore - minScore)) * 100);

        let riskLabel = 'Low Risk';
        let riskClass = 'risk-low';
        let description = 'Your responses suggest low impact from social media. Keep prioritizing balance and boundaries.';

        if (percent >= 70) {
            riskLabel = 'High Risk';
            riskClass = 'risk-high';
            description = 'Your responses suggest a high impact. Consider limiting exposure and seeking support if stress persists.';
        } else if (percent >= 35) {
            riskLabel = 'Moderate Risk';
            riskClass = 'risk-moderate';
            description = 'Your responses indicate moderate impact. Small habit changes can improve mood and focus.';
        }

        if (scoreEl) {
            scoreEl.textContent = `Score: ${total} / ${maxScore}`;
        }
        if (riskEl) {
            riskEl.className = `risk-pill ${riskClass}`;
            riskEl.textContent = `${riskLabel} (${percent}%)`;
        }
        if (descEl) {
            descEl.textContent = description;
        }

        cardEl.classList.add('d-none');
        nextBtn.classList.add('d-none');
        backBtn.classList.add('d-none');
        if (navEl) {
            navEl.classList.add('d-none');
        }
        resultEl.classList.remove('d-none');
    };

    const restart = () => {
        state.current = 0;
        state.answers = new Array(questions.length).fill(null);
        resultEl.classList.add('d-none');
        cardEl.classList.remove('d-none');
        nextBtn.classList.remove('d-none');
        backBtn.classList.remove('d-none');
        if (navEl) {
            navEl.classList.remove('d-none');
        }
        showAlert('');
        renderQuestion();
    };

    nextBtn.addEventListener('click', () => {
        if (state.answers[state.current] === null) {
            showAlert('Please select an option to continue.');
            return;
        }
        if (state.current === questions.length - 1) {
            showResult();
            return;
        }
        state.current += 1;
        renderQuestion();
    });

    backBtn.addEventListener('click', () => {
        if (state.current > 0) {
            state.current -= 1;
            renderQuestion();
        }
    });

    if (restartBtn) {
        restartBtn.addEventListener('click', restart);
    }

    renderQuestion();
}

function initMoodTracker() {
    const optionsEl = document.getElementById('mood-options');
    const messageEl = document.getElementById('mood-message');
    const historyEl = document.getElementById('mood-history');
    const clearBtn = document.getElementById('mood-clear');
    const chartEl = document.getElementById('mood-chart');

    if (!optionsEl || !historyEl || !chartEl) {
        return;
    }

    const moodOptions = [
        { id: 'uplifted', label: 'Uplifted', score: 5, color: '#25d5ff' },
        { id: 'calm', label: 'Calm', score: 4, color: '#7f5bff' },
        { id: 'steady', label: 'Steady', score: 3, color: '#9fb2ff' },
        { id: 'low', label: 'Low', score: 2, color: '#ffb347' },
        { id: 'stressed', label: 'Stressed', score: 1, color: '#ff7f7f' },
    ];

    const storageKey = 'mindscan-mood-log';
    let moodChart = null;

    const loadMoodData = () => {
        try {
            return JSON.parse(localStorage.getItem(storageKey) || '[]');
        } catch (error) {
            return [];
        }
    };

    const saveMoodData = (data) => {
        localStorage.setItem(storageKey, JSON.stringify(data));
    };

    const getTodayKey = () => new Date().toISOString().split('T')[0];

    const formatShortDate = (date) => {
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        return `${months[date.getMonth()]} ${date.getDate()}`;
    };

    const getLastDates = (count) => {
        const dates = [];
        const today = new Date();
        for (let i = count - 1; i >= 0; i -= 1) {
            const date = new Date(today);
            date.setDate(today.getDate() - i);
            dates.push(date);
        }
        return dates;
    };

    const renderMoodOptions = () => {
        optionsEl.innerHTML = '';
        moodOptions.forEach((option) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'mood-option';
            button.textContent = option.label;
            button.setAttribute('data-mood', option.id);
            button.setAttribute('aria-pressed', 'false');
            button.addEventListener('click', () => logMood(option.id));
            optionsEl.appendChild(button);
        });
    };

    const updateActiveMood = (moodId) => {
        optionsEl.querySelectorAll('.mood-option').forEach((button) => {
            const isActive = button.getAttribute('data-mood') === moodId;
            button.classList.toggle('active', isActive);
            button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        });
    };

    const renderHistory = (data) => {
        historyEl.innerHTML = '';
        if (data.length === 0) {
            const empty = document.createElement('div');
            empty.className = 'mood-row muted';
            empty.textContent = 'No mood entries yet.';
            historyEl.appendChild(empty);
            return;
        }
        data.slice(-7).reverse().forEach((entry) => {
            const option = moodOptions.find((item) => item.id === entry.mood);
            const row = document.createElement('div');
            row.className = 'mood-row';

            const dateSpan = document.createElement('span');
            dateSpan.textContent = entry.date;
            const moodStrong = document.createElement('strong');
            moodStrong.textContent = option ? option.label : entry.mood;

            row.appendChild(dateSpan);
            row.appendChild(moodStrong);
            historyEl.appendChild(row);
        });
    };

    const renderChart = (data) => {
        if (typeof Chart === 'undefined') {
            return;
        }

        const dateMap = new Map(data.map((entry) => [entry.date, entry.score]));
        const lastDates = getLastDates(7);
        const labels = lastDates.map(formatShortDate);
        const values = lastDates.map((date) => {
            const key = date.toISOString().split('T')[0];
            return dateMap.has(key) ? dateMap.get(key) : null;
        });

        if (moodChart) {
            moodChart.data.labels = labels;
            moodChart.data.datasets[0].data = values;
            moodChart.update();
            return;
        }

        moodChart = new Chart(chartEl, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Mood',
                        data: values,
                        borderColor: '#25d5ff',
                        backgroundColor: 'rgba(37, 213, 255, 0.15)',
                        borderWidth: 2,
                        pointRadius: 4,
                        pointBackgroundColor: '#25d5ff',
                        tension: 0.4,
                        fill: true,
                        spanGaps: false,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: '#b1bddc' }, grid: { color: 'rgba(255,255,255,0.05)' } },
                    y: {
                        min: 1,
                        max: 5,
                        ticks: { color: '#b1bddc', stepSize: 1 },
                        grid: { color: 'rgba(255,255,255,0.05)' },
                    },
                },
            },
        });
    };

    const refreshUI = () => {
        const data = loadMoodData();
        const today = getTodayKey();
        const todayEntry = data.find((entry) => entry.date === today);
        updateActiveMood(todayEntry ? todayEntry.mood : '');
        renderHistory(data);
        renderChart(data);
    };

    const logMood = (moodId) => {
        const option = moodOptions.find((item) => item.id === moodId);
        if (!option) {
            return;
        }

        const data = loadMoodData();
        const today = getTodayKey();
        const existing = data.find((entry) => entry.date === today);

        if (existing) {
            existing.mood = moodId;
            existing.score = option.score;
        } else {
            data.push({ date: today, mood: moodId, score: option.score });
        }

        const trimmed = data.slice(-30);
        saveMoodData(trimmed);
        updateActiveMood(moodId);
        renderHistory(trimmed);
        renderChart(trimmed);

        if (messageEl) {
            messageEl.textContent = 'Mood saved for today.';
            setTimeout(() => {
                messageEl.textContent = '';
            }, 3000);
        }
    };

    if (clearBtn) {
        clearBtn.addEventListener('click', () => {
            if (confirm('Clear all mood history?')) {
                localStorage.removeItem(storageKey);
                refreshUI();
            }
        });
    }

    renderMoodOptions();
    refreshUI();
}
