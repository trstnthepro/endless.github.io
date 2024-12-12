<?php
require_once 'config.php';
require_once '../search.php';

header('Content-Type: application/json'); // Set JSON response type

// Initialize an empty response array
$response = [];

// Fetch Most Popular Searches
$popularSearchesQuery = "SELECT search_query, COUNT(*) AS search_count
                         FROM searches
                         GROUP BY search_query
                         ORDER BY search_count DESC
                         LIMIT 10";

$popularSearchesResult = $conn->query($popularSearchesQuery);
$labels = [];
$counts = [];

if ($popularSearchesResult && $popularSearchesResult->num_rows > 0) {
    while ($row = $popularSearchesResult->fetch_assoc()) {
        $labels[] = $row['search_query']; // Add search query to labels
        $counts[] = $row['search_count']; // Add search count to counts
    }
    $response['search_data'] = [
        'labels' => $labels,
        'counts' => $counts
    ];
} else {
    // If no data is available, provide an empty structure
    $response['search_data'] = [
        'labels' => [],
        'counts' => []
    ];
}

// Fetch User Activity Statistics
$userActivityQuery = "SELECT activity_type, COUNT(*) AS activity_count
                      FROM user_activity
                      GROUP BY activity_type
                      ORDER BY activity_count DESC";

$userActivityResult = $conn->query($userActivityQuery);
$activityTypes = [];
$activityCounts = [];

if ($userActivityResult && $userActivityResult->num_rows > 0) {
    while ($row = $userActivityResult->fetch_assoc()) {
        $activityTypes[] = $row['activity_type']; // Add activity type to types
        $activityCounts[] = $row['activity_count']; // Add activity count to counts
    }
    $response['user_activity'] = [
        'types' => $activityTypes,
        'counts' => $activityCounts
    ];
} else {
    // If no data is available, provide an empty structure
    $response['user_activity'] = [
        'types' => [],
        'counts' => []
    ];
}

// Send the JSON response
echo json_encode($response);
?>
