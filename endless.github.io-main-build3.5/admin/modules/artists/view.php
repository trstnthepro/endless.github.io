<?php
require_once '../../includes/header.php';
require_once '../../config.php';
global $conn;


if(!isset($_GET['id'])) {
    header('Location: list.php');
    exit;
}

$id = (int)$_GET['id'];

// Get person details
$query = "SELECT * FROM artists WHERE PID = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$person = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

// Get artworks by this person
$artworks_query = "SELECT * FROM artworks WHERE person_id = ?";
$stmt = mysqli_prepare($conn, $artworks_query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$artworks = mysqli_stmt_get_result($stmt);
?>
    <link rel="stylesheet" href="../../admin.css">
    <div class="module-header">
        <h1>View Person Details</h1>
        <a href="list.php" class="btn btn-secondary">Back to List</a>
    </div>

    <div class="person-details">
        <div class="info-group">
            <label>Name:</label>
            <span><?php echo htmlspecialchars($person['fname'] . ' ' . $person['lname']); ?></span>
        </div>

        <div class="info-group">
            <label>Username:</label>
            <span><?php echo htmlspecialchars($person['username']); ?></span>
        </div>

        <div class="info-group">
            <label>Author Status:</label>
            <span><?php echo $person['author'] == 'y' ? 'Author' : 'Regular User'; ?></span>
        </div>

        <?php if($person['author'] == 'y'): ?>
            <div class="associated-artworks">
                <h2>Artworks by this Artist</h2>
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
        <?php endif; ?>
    </div>

<?php require_once '../../includes/footer.php'; ?>