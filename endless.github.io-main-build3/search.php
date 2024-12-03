<?php
include 'config.php';

if (isset($_GET['query'])) {
    $searchQuery = $_GET['query'];

    try {
        $sql = "SELECT a.piece_id, a.piece_name, a.piece_year, a.piece_description, 
               a.medium_type, a.web_filename, a.full_filename,
               p.fname, p.lname
        FROM artworks a
        LEFT JOIN people p ON a.person_id = p.PID
        WHERE a.piece_name LIKE ? 
           OR a.piece_description LIKE ? 
           OR a.piece_year LIKE ? 
           OR a.medium_type LIKE ?
           OR CONCAT(p.fname, ' ', p.lname) LIKE ?
        ORDER BY a.piece_id ASC";

        $searchTerm = "%" . $searchQuery . "%";
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("sssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
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
                'image' => $row['web_filename'],
                'fullImage' => $row['full_filename']
            );
        }

        header('Content-Type: application/json');
        echo json_encode($artworks);

    } catch (Exception $e) {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => $e->getMessage()]);
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>