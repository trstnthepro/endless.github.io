<?php
require_once '../../includes/header.php';
require_once '../../config.php';
global $conn;


$editing = isset($_GET['id']);
$user = null;

if($editing) {
    $id = (int)$_GET['id'];
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}
?>
    <link rel="stylesheet" href="../../admin.css">
    <div class="module-header">
        <div class="module-title">
            <h1><?php echo $editing ? 'Edit User' : 'Add New User'; ?></h1>
        </div>
        <a href="list.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <form action="<?php echo $editing ? 'edit_process.php' : 'create_process.php'; ?>"
          method="POST"
          class="admin-form">

        <?php if($editing): ?>
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
        <?php endif; ?>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="username">Username</label>
                <input type="text"
                       id="username"
                       name="username"
                       value="<?php echo $editing ? htmlspecialchars($user['username']) : ''; ?>"
                       required>
            </div>

            <div class="form-group col-md-6">
                <label for="email">Email</label>
                <input type="email"
                       id="email"
                       name="email"
                       value="<?php echo $editing ? htmlspecialchars($user['email']) : ''; ?>"
                       required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="first_name">First Name</label>
                <input type="text"
                       id="first_name"
                       name="first_name"
                       value="<?php echo $editing ? htmlspecialchars($user['first_name']) : ''; ?>">
            </div>

            <div class="form-group col-md-6">
                <label for="last_name">Last Name</label>
                <input type="text"
                       id="last_name"
                       name="last_name"
                       value="<?php echo $editing ? htmlspecialchars($user['last_name']) : ''; ?>">
            </div>
        </div>

        <?php if(!$editing): ?>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group col-md-6">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="security_level">Security Level</label>
            <select id="security_level" name="security_level" required>
                <option value="0" <?php echo ($editing && $user['security_level'] == 0) ? 'selected' : ''; ?>>User</option>
                <option value="1" <?php echo ($editing && $user['security_level'] == 1) ? 'selected' : ''; ?>>Moderator</option>
                <option value="2" <?php echo ($editing && $user['security_level'] == 2) ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>

        <div class="form-group">
            <label for="bio">Bio</label>
            <textarea id="bio"
                      name="bio"
                      rows="4"><?php echo $editing ? htmlspecialchars($user['bio']) : ''; ?></textarea>
        </div>

        <?php if($editing): ?>
            <div class="form-group">
                <label for="new_password">New Password (leave blank to keep current)</label>
                <input type="password" id="new_password" name="new_password">
            </div>
        <?php endif; ?>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> <?php echo $editing ? 'Update User' : 'Create User'; ?>
            </button>
            <a href="list.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>

<?php
mysqli_close($conn);
require_once '../../includes/footer.php';
?>