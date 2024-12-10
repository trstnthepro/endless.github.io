<?php
require_once '../../includes/header.php';
require_once '../../config.php';
global $conn;

// Fetch all artists
$query = "SELECT * FROM people WHERE author = 'y' ORDER BY fname, lname";
$result = mysqli_query($conn, $query);
?>
    <link rel="stylesheet" href="../../admin.css">
    <div class="module-header">
        <div class="module-title">
            <h1>Manage Artists</h1>
            <p>Add, edit, and manage artist profiles</p>
        </div>
        <a href="create.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Artist
        </a>
    </div>

    <div class="table-container">
        <table class="admin-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Username</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php while($artist = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $artist['PID']; ?></td>
                    <td><?php echo htmlspecialchars($artist['fname'] . ' ' . $artist['lname']); ?></td>
                    <td><?php echo htmlspecialchars($artist['username']); ?></td>
                    <td class="actions">
                        <a href="edit.php?id=<?php echo $artist['PID']; ?>"
                           class="btn btn-icon" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="confirmDelete(<?php echo $artist['PID']; ?>)"
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
            if(confirm('Are you sure you want to delete this artist? This will also remove all associated artworks.')) {
                window.location.href = 'delete.php?id=' + id;
            }
        }
    </script>

<?php
mysqli_close($conn);
require_once '../../includes/footer.php';
?>