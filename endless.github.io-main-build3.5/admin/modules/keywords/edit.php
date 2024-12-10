<?php
require_once '../../includes/header.php';
require_once '../../config.php';
global $conn;


$editing = isset($_GET['id']);
$keyword = null;

if($editing) {
    $id = (int)$_GET['id'];
    $query = "SELECT * FROM keywords WHERE keyword_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $keyword = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}
?>
    <link rel="stylesheet" href="../../admin.css">
    <div class="module-header">
        <div class="module-title">
            <h1><?php echo $editing ? 'Edit Keyword' : 'Add New Keyword'; ?></h1>
        </div>
        <a href="list.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <form action="<?php echo $editing ? 'edit_process.php' : 'create_process.php'; ?>"
          method="POST"
          class="admin-form">

        <?php if($editing): ?>
            <input type="hidden" name="keyword_id" value="<?php echo $keyword['keyword_id']; ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="keyword_name">Keyword Name</label>
            <input type="text"
                   id="keyword_name"
                   name="keyword_name"
                   value="<?php echo $editing ? htmlspecialchars($keyword['keyword_name']) : ''; ?>"
                   required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> <?php echo $editing ? 'Update Keyword' : 'Create Keyword'; ?>
            </button>
            <a href="list.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>

<?php
mysqli_close($conn);
require_once '../../includes/footer.php';
?>