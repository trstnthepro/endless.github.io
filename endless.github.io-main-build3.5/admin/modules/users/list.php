<?php
require_once '../../includes/header.php';
require_once '../../config.php';
global $conn;


$query = "SELECT id, username, email, created_at, first_name, last_name, security_level FROM users ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

    <div class="module-header">
        <div class="module-title">
            <h1>Manage Users</h1>
            <p>View and manage user accounts</p>
        </div>
        <a href="create.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New User
        </a>
    </div>

    <div class="table-container">
        <table class="admin-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Name</th>
                <th>Email</th>
                <th>Joined</th>
                <th>Security Level</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td>
                        <?php
                        if($row['first_name'] && $row['last_name']) {
                            echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
                        } else {
                            echo "Not provided";
                        }
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo date('M j, Y', strtotime($row['created_at'])); ?></td>
                    <td>
                        <?php
                        switch($row['security_level']) {
                            case 0:
                                echo "User";
                                break;
                            case 1:
                                echo "Moderator";
                                break;
                            case 2:
                                echo "Admin";
                                break;
                            default:
                                echo "Unknown";
                        }
                        ?>
                    </td>
                    <td class="actions">
                        <a href="edit.php?id=<?php echo $row['id']; ?>"
                           class="btn btn-icon" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="confirmDelete(<?php echo $row['id']; ?>)"
                                class="btn btn-icon btn-danger" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        function confirmDelete(id) {
            if(confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                window.location.href = 'delete.php?id=' + id;
            }
        }
    </script>

<?php
mysqli_close($conn);
require_once '../../includes/footer.php';
?>