<?php
require_once '../../includes/header.php';
require_once '../../config.php';
global $conn;


// Fetch artworks with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Simple query without prepared statement first
$query = "SELECT a.*, p.fname, p.lname 
          FROM artworks a 
          LEFT JOIN artists p ON a.person_id = p.PID 
          ORDER BY a.piece_id DESC 
          LIMIT $per_page OFFSET $offset";

$result = mysqli_query($conn, $query);

// Check for query error
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Get total count for pagination
$count_query = "SELECT COUNT(*) as count FROM artworks";
$count_result = mysqli_query($conn, $count_query);
$total_rows = mysqli_fetch_assoc($count_result)['count'];
$total_pages = ceil($total_rows / $per_page);
?>

    <div class="module-header">
        <div class="module-title">
            <h1>Manage Artworks</h1>
            <p>Add, edit, and manage artwork entries</p>
        </div>
        <a href="create.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Artwork
        </a>
    </div>

    <div class="table-container">
        <table class="admin-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Title</th>
                <th>Artist</th>
                <th>Year</th>
                <th>Medium</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['piece_id']; ?></td>
                    <td>
                        <?php if($row['web_filename']): ?>
                            <img src="/<?php echo htmlspecialchars($row['web_filename']); ?>"
                                 alt="<?php echo htmlspecialchars($row['piece_name']); ?>"
                                 class="thumbnail">
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['piece_name']); ?></td>
                    <td>
                        <?php
                        if($row['fname'] && $row['lname']) {
                            echo htmlspecialchars($row['fname'] . ' ' . $row['lname']);
                        } else {
                            echo "Unknown Artist";
                        }
                        ?>
                    </td>
                    <td><?php echo $row['piece_year']; ?></td>
                    <td><?php echo htmlspecialchars($row['medium_type']); ?></td>
                    <td class="actions">
                        <a href="view.php?id=<?php echo $row['piece_id']; ?>"
                           class="btn btn-icon" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="edit.php?id=<?php echo $row['piece_id']; ?>"
                           class="btn btn-icon" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="confirmDelete(<?php echo $row['piece_id']; ?>)"
                                class="btn btn-icon btn-danger" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
<?php if($total_pages > 1): ?>
    <div class="pagination">
        <?php for($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>"
               class="btn <?php echo ($i == $page) ? 'btn-primary' : 'btn-secondary'; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>
<?php endif; ?>

    <script>
        function confirmDelete(id) {
            if(confirm('Are you sure you want to delete this artwork?')) {
                window.location.href = 'delete.php?id=' + id;
            }
        }
    </script>

<?php
mysqli_close($conn);
require_once '../../includes/footer.php';
?>