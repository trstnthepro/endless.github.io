<?php
require_once 'config.php';
require_once 'classes/ArtworkFileManager.php';

try {
    // Convert MySQL connection to PDO
    $dsn = "mysql:host=webdev.iyaserver.com;dbname=twilcher_endless;charset=utf8mb4";
    $pdo = new PDO($dsn, "twilcher", "AcadDev_Wilcher_6801994716");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $fileManager = new ArtworkSystem\ArtworkFileManager(
        $pdo,
        __DIR__ . '/Images'  // Adjust this path to your images directory
    );

    // Process the upload
    $artworkId = $fileManager->processArtworkUpload(
        [
            'web_version' => $_FILES['web_version'],
            'full_version' => $_FILES['full_version']
        ],
        [
            'author_id' => $_POST['author_id'],
            'piece_name' => $_POST['piece_name'],
            'piece_number' => $_POST['piece_number'],
            'year' => $_POST['year'],
            'description' => $_POST['description'],
            'medium_type' => $_POST['medium_type'],
            'keywords' => $_POST['keywords']
        ]
    );

    // Return success response
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'artwork_id' => $artworkId,
        'message' => 'Artwork uploaded successfully'
    ]);

} catch (Exception $e) {
    // Return error response
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}