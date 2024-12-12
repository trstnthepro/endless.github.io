<?php
require_once '../../config.php';
require_once '../../includes/header.php'; // Include your header for consistent formatting
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Data Visualization</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<h1>Most Popular Searches</h1>
<canvas id="searchChart"></canvas>

<script>
    fetch('user_behavior.php') // Fetch the search query data from PHP
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Received data:', data);

            if (data.search_data && data.search_data.labels.length > 0) {
                // Create the chart
                const ctx = document.getElementById('searchChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar', // Bar chart type
                    data: {
                        labels: data.search_data.labels, // Labels (search queries)
                        datasets: [{
                            label: 'Search Counts',
                            data: data.search_data.counts, // Data (search counts)
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
                console.warn('No search data available', data);
                document.body.innerHTML = `<p>No search data available</p>`;
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            document.body.innerHTML = `<p>Error fetching data: ${error.message}</p>`;
        });
</script>
</body>
</html>
