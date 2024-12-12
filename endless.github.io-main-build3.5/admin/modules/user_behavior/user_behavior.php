<?php
require_once '../../config.php';

header('Content-Type: application/json'); // Set JSON response type

// Initialize the response structure
$response = ['search_data' => ['labels' => [], 'counts' => []]];

try {
    // Verify the database connection
    if (!$conn) {
        throw new Exception('Database connection failed: ' . (mysqli_connect_error() ?: 'Unknown error'));
    }

    // Query to get all search queries and their counts
    $popularSearchesQuery = "SELECT search_query, COUNT(*) AS search_count
                             FROM searches
                             GROUP BY search_query
                             ORDER BY search_count DESC";

    $result = $conn->query($popularSearchesQuery);

    if ($result === false) {
        throw new Exception('Search query failed: ' . $conn->error);
    }

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $response['search_data']['labels'][] = $row['search_query'];
            $response['search_data']['counts'][] = intval($row['search_count']);
        }
    } else {
        $response['search_data']['error'] = 'No search data found';
    }

    // Return the JSON response
    echo json_encode($response);

} catch (Exception $e) {
    // Log the error
    error_log($e->getMessage());

    // Return an error response
    echo json_encode([
        'error' => $e->getMessage(),
        'search_data' => ['labels' => [], 'counts' => []]
    ]);
}
?>
