<?php
require_once '../../includes/header.php';
require_once '../../config.php';
global $conn;


$editing = isset($_GET['id']);
$artwork = null;
$keywords = [];

if($editing) {
    $id = (int)$_GET['id'];

    // Fetch artwork details
    $query = "SELECT * FROM artworks WHERE piece_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $artwork = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    // Fetch associated keywords
    $keyword_query = "SELECT k.keyword_id, k.keyword_name 
                     FROM keywords k
                     JOIN artworks_x_keywords axk ON k.keyword_id = axk.keyword_id
                     WHERE axk.piece_id = ?";
    $stmt = mysqli_prepare($conn, $keyword_query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $keyword_result = mysqli_stmt_get_result($stmt);
    while($row = mysqli_fetch_assoc($keyword_result)) {
        $keywords[] = $row;
    }
}

// Fetch all artists for dropdown
$artists_query = "SELECT PID, fname, lname FROM artists WHERE author = 'y'";
$artists_result = mysqli_query($conn, $artists_query);

// Fetch all keywords for selection
$all_keywords_query = "SELECT * FROM keywords ORDER BY keyword_name";
$all_keywords_result = mysqli_query($conn, $all_keywords_query);
?>
    <link rel="stylesheet" href="../../admin.css">
    <div class="module-header">
        <div class="module-title">
            <h1><?php echo $editing ? 'Edit Artwork' : 'Add New Artwork'; ?></h1>
        </div>
        <a href="list.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <form action="<?php echo $editing ? 'edit_process.php' : 'create_process.php'; ?>"
          method="POST"
          enctype="multipart/form-data"
          class="admin-form">

        <?php if($editing): ?>
            <input type="hidden" name="piece_id" value="<?php echo $artwork['piece_id']; ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="piece_name">Artwork Title</label>
            <input type="text"
                   id="piece_name"
                   name="piece_name"
                   value="<?php echo $editing ? htmlspecialchars($artwork['piece_name']) : ''; ?>"
                   required>
        </div>

        <div class="form-group">
            <label for="person_id">Artist</label>
            <select id="person_id" name="person_id">
                <option value="">Select Artist</option>
                <?php while($artist = mysqli_fetch_assoc($artists_result)): ?>
                    <option value="<?php echo $artist['PID']; ?>"
                        <?php echo ($editing && $artwork['person_id'] == $artist['PID']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($artist['fname'] . ' ' . $artist['lname']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="piece_year">Year</label>
            <input type="number"
                   id="piece_year"
                   name="piece_year"
                   value="<?php echo $editing ? $artwork['piece_year'] : ''; ?>">
        </div>

        <div class="form-group">
            <label for="medium_type">Medium</label>
            <input type="text"
                   id="medium_type"
                   name="medium_type"
                   value="<?php echo $editing ? htmlspecialchars($artwork['medium_type']) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="piece_description">Description</label>
            <textarea id="piece_description"
                      name="piece_description"
                      rows="4"><?php echo $editing ? htmlspecialchars($artwork['piece_description']) : ''; ?></textarea>
        </div>

        <div class="form-group">
            <label for="keywords">Keywords</label>
            <div class="checkbox-group">
                <?php while($keyword = mysqli_fetch_assoc($all_keywords_result)): ?>
                    <label class="checkbox-label">
                        <input type="checkbox"
                               name="keywords[]"
                               value="<?php echo $keyword['keyword_id']; ?>"
                            <?php echo (in_array($keyword['keyword_id'], array_column($keywords, 'keyword_id'))) ? 'checked' : ''; ?>>
                        <?php echo htmlspecialchars($keyword['keyword_name']); ?>
                    </label>
                <?php endwhile; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="web_image">Web Image</label>
            <input type="file" id="web_image" name="web_image" accept="image/*">
            <?php if($editing && $artwork['web_filename']): ?>
                <div class="current-image">
                    <img src="/<?php echo htmlspecialchars($artwork['web_filename']); ?>"
                         alt="Current web image">
                    <p>Current web image</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="full_image">Full Resolution Image</label>
            <input type="file" id="full_image" name="full_image" accept="image/*">
            <?php if($editing && $artwork['full_filename']): ?>
                <div class="current-image">
                    <img src="/<?php echo htmlspecialchars($artwork['full_filename']); ?>"
                         alt="Current full resolution image">
                    <p>Current full resolution image</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> <?php echo $editing ? 'Update Artwork' : 'Create Artwork'; ?>
            </button>
            <a href="list.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>

<?php
mysqli_close($conn);
require_once '../../includes/footer.php';
?>