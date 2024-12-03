<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        $sql = "SELECT a.piece_id, a.piece_name, a.piece_year, a.piece_description, 
               a.medium_type, a.web_filename, a.full_filename,
               p.fname, p.lname
        FROM artworks a
        LEFT JOIN people p ON a.person_id = p.PID
        WHERE a.piece_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Return the artwork details as JSON
            header('Content-Type: application/json');
            echo json_encode($row);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Artwork not found']);
        }

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(400);
    echo json_encode(['error' => 'No ID provided']);
}
?>