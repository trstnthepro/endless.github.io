<?php
require_once '../../includes/header.php';
require_once '../../config.php';
global $conn;


$editing = isset($_GET['id']);
$artist = null;

if($editing) {
    $id = (int)$_GET['id'];
    $query = "SELECT * FROM people WHERE PID = ? AND author = 'y'";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $artist = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

session_start();
// Check if user is logged in and has admin rights
if(!isset($_SESSION['user']) || $_SESSION['user']['security_level'] <= 0) {
    header('Location: login.php');
    exit;
}

?>
    <link rel="stylesheet" href="../../admin.css">
    <div class="module-header">
        <div class="module-title">
            <h1><?php echo $editing ? 'Edit Artist' : 'Add New Artist'; ?></h1>
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
            <input type="hidden" name="PID" value="<?php echo $artist['PID']; ?>">
        <?php endif; ?>

        <input type="hidden" name="author" value="y">

        <div class="form-group">
            <label for="fname">First Name</label>
            <input type="text"
                   id="fname"
                   name="fname"
                   value="<?php echo $editing ? htmlspecialchars($artist['fname']) : ''; ?>"
                   required>
        </div>

        <div class="form-group">
            <label for="lname">Last Name</label>
            <input type="text"
                   id="lname"
                   name="lname"
                   value="<?php echo $editing ? htmlspecialchars($artist['lname']) : ''; ?>"
                   required>
        </div>

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text"
                   id="username"
                   name="username"
                   value="<?php echo $editing ? htmlspecialchars($artist['username']) : ''; ?>"
                   required>
        </div>

        <?php if(!$editing): ?>
            <div class="form-group">
                <label for="pw">Password</label>
                <input type="password" id="pw" name="pw">
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email"
                   id="email"
                   name="email"
                   value="<?php echo $editing ? htmlspecialchars($artist['email']) : ''; ?>">
        </div>

        <?php if($editing): ?>
            <div class="form-group">
                <label for="new_password">New Password (leave blank to keep current)</label>
                <input type="password" id="new_password" name="new_password">
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="file_path_name">Profile Image</label>
            <input type="file" id="file_path_name" name="file_path_name" accept="image/*">
            <?php if($editing && $artist['file_path_name']): ?>
                <div class="current-image">
                    <img src="/<?php echo htmlspecialchars($artist['file_path_name']); ?>"
                         alt="Current profile image">
                    <p>Current profile image</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> <?php echo $editing ? 'Update Artist' : 'Create Artist'; ?>
            </button>
            <a href="list.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>

<?php
mysqli_close($conn);
require_once '../../includes/footer.php';
?>