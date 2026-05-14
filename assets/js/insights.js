document.addEventListener('DOMContentLoaded', () => {
    initInsightsTabs();
    initUsageChart();
    if (document.querySelector('.insights-panel.active[data-panel="demographics"]')) {
        initDemographicsCharts();
    }
    if (document.querySelector('.insights-panel.active[data-panel="stress"]')) {
        initStressChart();
    }
    if (document.querySelector('.insights-panel.active[data-panel="mood"]')) {
        initMoodTrendsChart();
    }

    const exportBtn = document.getElementById('insights-export');
    if (exportBtn) {
        exportBtn.addEventListener('click', () => {
            alert('CSV export will be available with the next data release.');
        });
    }
});

function initInsightsTabs() {
    const tabs = Array.from(document.querySelectorAll('.insights-tab'));
    const panels = Array.from(document.querySelectorAll('.insights-panel'));

    if (tabs.length === 0 || panels.length === 0) {
        return;
    }

    const activateTab = (tab) => {
        const target = tab.getAttribute('data-tab');
        tabs.forEach((item) => {
            const isActive = item === tab;
            item.classList.toggle('active', isActive);
            item.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });

        panels.forEach((panel) => {
            const matches = panel.getAttribute('data-panel') === target;
            panel.classList.toggle('active', matches);
        });

        if (target === 'demographics') {
            initDemographicsCharts();
        }
        if (target === 'stress') {
            initStressChart();
        }
        if (target === 'mood') {
            initMoodTrendsChart();
        }
    };

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => activateTab(tab));
    });
}

function initUsageChart() {
    if (typeof Chart === 'undefined') {
        return;
    }

    const canvas = document.getElementById('platformUsageChart');
    if (!canvas) {
        return;
    }

    const labels = ['Instagram', 'YouTube', 'WhatsApp', 'Facebook', 'Twitter/X', 'Snapchat', 'LinkedIn', 'TikTok', 'Reddit'];
    const values = [78, 65, 82, 34, 28, 22, 18, 42, 11];
    const colors = ['#ff6b6b', '#ffb347', '#25d5ff', '#6f7bff', '#9a6bff', '#f39ad5', '#35d39e', '#2a72ff', '#9aa7c7'];

    new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [
                {
                    data: values,
                    backgroundColor: colors,
                    borderWidth: 0,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            animation: { duration: 900, easing: 'easeOutQuart' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (context) => `${context.label}: ${context.parsed}%`,
                    },
                },
            },
        },
    });
}

let demographicsChartsInitialized = false;
let stressChartInitialized = false;
let moodChartInitialized = false;

function initDemographicsCharts() {
    if (demographicsChartsInitialized) {
        return;
    }

    if (typeof Chart === 'undefined') {
        return;
    }

    const ageCanvas = document.getElementById('ageDistributionChart');
    const genderCanvas = document.getElementById('genderDistributionChart');
    const occupationCanvas = document.getElementById('occupationAnalysisChart');

    if (!ageCanvas || !genderCanvas || !occupationCanvas) {
        return;
    }

    const sliceBorder = '#ffffff';

    new Chart(ageCanvas, {
        type: 'pie',
        data: {
            labels: ['13-17', '18-24', '25-34', '35-44', '45+'],
            datasets: [
                {
                    data: [12, 44, 22, 14, 8],
                    backgroundColor: ['#7f9cff', '#2a72ff', '#35d39e', '#ffb347', '#ff7fbf'],
                    borderColor: sliceBorder,
                    borderWidth: 2,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 900, easing: 'easeOutQuart' },
            plugins: { legend: { display: false } },
        },
    });

    new Chart(genderCanvas, {
        type: 'pie',
        data: {
            labels: ['Male', 'Female', 'Non-binary', 'Prefer not to say'],
            datasets: [
                {
                    data: [46, 44, 6, 4],
                    backgroundColor: ['#6f7bff', '#ff7fbf', '#35d39e', '#9aa7c7'],
                    borderColor: sliceBorder,
                    borderWidth: 2,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 900, easing: 'easeOutQuart' },
            plugins: { legend: { display: false } },
        },
    });

    new Chart(occupationCanvas, {
        type: 'bar',
        data: {
            labels: ['Student', 'Working Professional', 'Freelancer', 'Homemaker', 'Unemployed', 'Other'],
            datasets: [
                {
                    label: 'Participants',
                    data: [120, 70, 28, 20, 18, 21],
                    backgroundColor: ['#2a72ff', '#7f9cff', '#35d39e', '#ffb347', '#ff7fbf', '#9aa7c7'],
                    borderRadius: 12,
                    borderSkipped: false,
                },
            ],
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 900, easing: 'easeOutQuart' },
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: { color: '#5d6a86' },
                    grid: { color: 'rgba(30, 40, 70, 0.08)' },
                },
                y: {
                    ticks: { color: '#5d6a86' },
                    grid: { display: false },
                },
            },
        },
    });

    demographicsChartsInitialized = true;
}

function initStressChart() {
    if (stressChartInitialized) {
        return;
    }

    if (typeof Chart === 'undefined') {
        return;
    }

    const canvas = document.getElementById('stressCorrelationChart');
    if (!canvas) {
        return;
    }

    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: ['<1 hr', '1-2 hrs', '2-3 hrs', '3-4 hrs', '4-5 hrs', '5+ hrs'],
            datasets: [
                {
                    label: 'Stress Level (%)',
                    data: [18, 26, 33, 41, 52, 64],
                    backgroundColor: 'rgba(255, 127, 127, 0.7)',
                    borderColor: 'rgba(215, 84, 84, 0.9)',
                    borderWidth: 1,
                    borderRadius: 10,
                    borderSkipped: false,
                },
                {
                    label: 'Participants',
                    data: [22, 48, 64, 58, 42, 31],
                    backgroundColor: 'rgba(37, 213, 255, 0.65)',
                    borderColor: 'rgba(24, 164, 198, 0.9)',
                    borderWidth: 1,
                    borderRadius: 10,
                    borderSkipped: false,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 900, easing: 'easeOutQuart' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (context) => `${context.dataset.label}: ${context.parsed.y}`,
                    },
                },
            },
            scales: {
                x: {
                    ticks: { color: '#5d6a86' },
                    grid: { display: false },
                },
                y: {
                    beginAtZero: true,
                    ticks: { color: '#5d6a86' },
                    grid: { color: 'rgba(30, 40, 70, 0.08)' },
                },
            },
        },
    });

    stressChartInitialized = true;
}

function initMoodTrendsChart() {
    if (moodChartInitialized) {
        return;
    }

    if (typeof Chart === 'undefined') {
        return;
    }

    const canvas = document.getElementById('moodTrendsChart');
    if (!canvas) {
        return;
    }

    new Chart(canvas, {
        type: 'line',
        data: {
            labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            datasets: [
                {
                    label: 'Happy',
                    data: [72, 76, 74, 78, 82, 85, 80],
                    borderColor: 'rgba(53, 211, 158, 0.9)',
                    backgroundColor: 'rgba(53, 211, 158, 0.2)',
                    tension: 0.45,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgba(53, 211, 158, 0.9)',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                },
                {
                    label: 'Neutral',
                    data: [58, 60, 59, 62, 63, 65, 61],
                    borderColor: 'rgba(42, 114, 255, 0.9)',
                    backgroundColor: 'rgba(42, 114, 255, 0.2)',
                    tension: 0.45,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgba(42, 114, 255, 0.9)',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                },
                {
                    label: 'Sad',
                    data: [34, 32, 35, 33, 31, 30, 32],
                    borderColor: 'rgba(255, 179, 71, 0.9)',
                    backgroundColor: 'rgba(255, 179, 71, 0.22)',
                    tension: 0.45,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgba(255, 179, 71, 0.9)',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                },
                {
                    label: 'Stressed',
                    data: [26, 28, 27, 29, 30, 32, 28],
                    borderColor: 'rgba(255, 127, 127, 0.9)',
                    backgroundColor: 'rgba(255, 127, 127, 0.2)',
                    tension: 0.45,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgba(255, 127, 127, 0.9)',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 900, easing: 'easeOutQuart' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (context) => `${context.dataset.label}: ${context.parsed.y}`,
                    },
                },
            },
            scales: {
                x: {
                    ticks: { color: '#5d6a86' },
                    grid: { display: false },
                },
                y: {
                    beginAtZero: true,
                    ticks: { color: '#5d6a86' },
                    grid: { color: 'rgba(30, 40, 70, 0.08)' },
                },
            },
        },
    });

    moodChartInitialized = true;
}
