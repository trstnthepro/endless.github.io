<?php
require_once 'config.php';
require_once 'includes/header.php'; // Includes your header for consistent formatting
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Visualizations</title>
    <link rel="stylesheet" href="/acad276/Endless/endless.github.io-main-build3.5/admin/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="admin-main">
    <h1>Data Visualizations</h1>

    <h2>Most Popular Searches</h2>
    <canvas id="searchChart"></canvas>

    <h2>User Activity</h2>
    <canvas id="activityChart"></canvas>
</div>

<script>
    fetch('user_behavior.php')
        .then(response => response.json())
        .then(data => {
            // Render Most Popular Searches
            if (data.search_data.labels.length > 0 && data.search_data.counts.length > 0) {
                const searchCtx = document.getElementById('searchChart').getContext('2d');
                new Chart(searchCtx, {
                    type: 'bar',
                    data: {
                        labels: data.search_data.labels,
                        datasets: [{
                            label: 'Search Counts',
                            data: data.search_data.counts,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'top' },
                            title: { display: true, text: 'Most Popular Searches' }
                        }
                    }
                });
            } else {
                console.warn('No search data available');
                document.getElementById('searchChart').parentNode.innerHTML = '<p>No search data available</p>';
            }

            // Render User Activity
            if (data.user_activity.types.length > 0 && data.user_activity.counts.length > 0) {
                const activityCtx = document.getElementById('activityChart').getContext('2d');
                new Chart(activityCtx, {
                    type: 'pie',
                    data: {
                        labels: data.user_activity.types,
                        datasets: [{
                            label: 'User Activity',
                            data: data.user_activity.counts,
                            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'top' },
                            title: { display: true, text: 'User Activity Breakdown' }
                        }
                    }
                });
            } else {
                console.warn('No user activity data available');
                document.getElementById('activityChart').parentNode.innerHTML = '<p>No user activity data available</p>';
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
</script>
</body>
</html>
