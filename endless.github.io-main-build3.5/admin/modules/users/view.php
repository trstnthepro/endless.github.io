<?php
require_once '../../includes/header.php';
require_once '../../config.php';
global $conn;


if(!isset($_GET['id'])) {
    header('Location: list.php');
    exit;
}

$id = (int)$_GET['id'];

// Get keyword details
$query = "SELECT k.*, COUNT(axk.piece_id) as usage_count 
          FROM keywords k 
          LEFT JOIN artworks_x_keywords axk ON k.keyword_id = axk.keyword_id 
          WHERE k.keyword_id = ?
          GROUP BY k.keyword_id";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$keyword = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

// Get artworks using this keyword
$artworks_query = "SELECT a.* 
                  FROM artworks a 
                  JOIN artworks_x_keywords axk ON a.piece_id = axk.piece_id 
                  WHERE axk.keyword_id = ?";
$stmt = mysqli_prepare($conn, $artworks_query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$artworks = mysqli_stmt_get_result($stmt);
?>

    <div class="module-header">
        <h1>View Keyword Details</h1>
        <a href="list.php" class="btn btn-secondary">Back to List</a>
    </div>

    <div class="keyword-details">
        <div class="info-group">
            <label>Keyword:</label>
            <span><?php echo htmlspecialchars($keyword['keyword_name']); ?></span>
        </div>

        <div class="info-group">
            <label>Usage Count:</label>
            <span><?php echo $keyword['usage_count']; ?> artworks</span>
        </div>

        <div class="associated-artworks">
            <h2>Artworks Using This Keyword</h2>
            <?php while($artwork = mysqli_fetch_assoc($artworks)): ?>
                <div class="artwork-item">
                    <img src="/<?php echo htmlspecialchars($artwork['web_filename']); ?>"
                         alt="<?php echo htmlspecialchars($artwork['piece_name']); ?>">
                    <a href="../artworks/view.php?id=<?php echo $artwork['piece_id']; ?>">
                        <?php echo htmlspecialchars($artwork['piece_name']); ?>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

<?php require_once '../../includes/footer.php'; ?>