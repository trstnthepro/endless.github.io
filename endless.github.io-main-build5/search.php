<?php
include 'config.php';

if (isset($_GET['query'])) {
    // Step 1: Log the search term to the database
    $searchQuery = trim($_GET['query']);
    $stmt = $conn->prepare("INSERT INTO searches (search_query) VALUES (?)");
    $stmt->bind_param("s", $searchQuery);
    $stmt->execute();

    // Step 2: Fetch the actual search results (e.g., artworks) based on the search term
    $searchTerm = "%" . $conn->real_escape_string($searchQuery) . "%";
    $sql = "SELECT DISTINCT
                a.piece_id,
                a.piece_name,
                a.piece_year,
                a.piece_description,
                a.medium_type,
                a.web_filename,
                a.full_filename,
                CONCAT(p.fname, ' ', p.lname) as artist_name
            FROM artworks a
            LEFT JOIN artists p ON a.artist_id = p.PID
            WHERE a.piece_name LIKE ?
               OR a.piece_description LIKE ?
               OR a.piece_year LIKE ?
               OR a.medium_type LIKE ?
               OR CONCAT(p.fname, ' ', p.lname) LIKE ?
            ORDER BY a.piece_id ASC
            LIMIT 50";

    try {
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception($conn->error);
        }

        $stmt->bind_param("sssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);

        if (!$stmt->execute()) {
            throw new Exception($stmt->error);
        }

        $result = $stmt->get_result();
        $artworks = [];

        while ($row = $result->fetch_assoc()) {
            // Strip the prefix from image paths
            $webImage = str_replace('acad276/Endless/endless.github.io-main-build3/', '', $row['web_filename']);
            $fullImage = str_replace('acad276/Endless/endless.github.io-main-build3/', '', $row['full_filename']);

            $artworks[] = [
                'id' => $row['piece_id'],
                'title' => $row['piece_name'],
                'year' => $row['piece_year'],
                'description' => $row['piece_description'],
                'medium' => $row['medium_type'],
                'image' => $webImage,
                'fullImage' => $fullImage,
                'artist' => $row['artist_name']
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($artworks);

    } catch (Exception $e) {
        error_log("Search error: " . $e->getMessage());
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Search failed']);
    }

    $stmt->close();
} else {
    // Step 3: If no search query, return the top 10 most popular search terms for visualizations
    $sql = "SELECT search_query, COUNT(*) AS search_count
            FROM searches
            GROUP BY search_query
            ORDER BY search_count DESC
            LIMIT 10";

    try {
        $result = $conn->query($sql);
        $popularSearches = [];

        while ($row = $result->fetch_assoc()) {
            $popularSearches[] = [
                'search_query' => $row['search_query'],
                'search_count' => $row['search_count']
            ];
        }

        header('Content-Type: application/json');
        echo json_encode(['search_data' => $popularSearches]);

    } catch (Exception $e) {
        error_log("Search error: " . $e->getMessage());
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Search failed']);
    }
}
?>
