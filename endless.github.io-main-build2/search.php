<?php
include 'config.php';

if (isset($_GET['query'])) {
    $searchQuery = $_GET['query'];

    try {
        $sql = "SELECT piece_id, piece_name, piece_year, piece_description, medium_type, filename 
                FROM artworks 
                WHERE piece_name LIKE ? 
                   OR piece_description LIKE ? 
                   OR piece_year LIKE ? 
                   OR medium_type LIKE ?
                ORDER BY piece_id ASC";

        $searchTerm = "%" . $searchQuery . "%";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        $artworks = array();
        while ($row = $result->fetch_assoc()) {
            $artworks[] = array(
                'id' => $row['piece_id'],
                'title' => $row['piece_name'],
                'year' => $row['piece_year'],
                'description' => $row['piece_description'],
                'medium' => $row['medium_type'],
                'image' => 'Images/Van Gogh/' . $row['filename']
            );
        }

        header('Content-Type: application/json');
        echo json_encode($artworks);
    } catch (Exception $e) {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => $e->getMessage()]);
    }

    $conn->close();
    exit;
}
?>