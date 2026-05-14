document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('admin-search');
    const table = document.getElementById('records-table');

    if (searchInput && table) {
        searchInput.addEventListener('input', () => {
            const value = searchInput.value.toLowerCase();
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach((row) => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(value) ? '' : 'none';
            });
        });
    }

    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach((form) => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            Swal.fire({
                title: 'Delete this record?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    if (typeof Chart !== 'undefined') {
        const chartEl = document.getElementById('adminChart');
        if (chartEl) {
            const dataset = JSON.parse(chartEl.getAttribute('data-chart') || '[]');
            new Chart(chartEl, {
                type: 'bar',
                data: {
                    labels: dataset.map((item) => item.label),
                    datasets: [{
                        label: 'Assessments',
                        data: dataset.map((item) => item.value),
                        backgroundColor: ['#25d5ff', '#7f5bff', '#ff5bd8'],
                    }],
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { ticks: { color: '#b1bddc' } },
                        y: { ticks: { color: '#b1bddc' }, beginAtZero: true },
                    },
                },
            });
        }
    }
});
