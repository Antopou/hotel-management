// public/js/dashboard.js

document.addEventListener('DOMContentLoaded', function () {
    // Check for Chart.js
    if (typeof Chart === 'undefined') {
        console.error('Chart.js library not loaded!');
        return;
    }

    // Check for data
    if (!window.dashboardData) {
        console.error('Dashboard data not found!');
        return;
    }

    // Check for the canvas
    const chartCanvas = document.getElementById('revenueChart');
    if (!chartCanvas) {
        console.error('Chart canvas #revenueChart not found!');
        return;
    }

    // Prepare data
    const months = window.dashboardData.months || [];
    const revenues = window.dashboardData.revenues || [];

    if (!months.length || !revenues.length) {
        console.warn('Chart data is empty. No chart will be rendered.');
        return;
    }

    // Create the chart
    new Chart(chartCanvas.getContext('2d'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Revenue',
                data: revenues,
                borderWidth: 3,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40,167,69,0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#28a745'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value;
                        }
                    }
                }
            }
        }
    });
});
