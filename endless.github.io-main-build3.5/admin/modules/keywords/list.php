<?php
require_once '../../includes/header.php';
require_once '../../config.php';
global $conn;


$query = "SELECT k.*, COUNT(axk.piece_id) as artwork_count 
          FROM keywords k 
          LEFT JOIN artworks_x_keywords axk ON k.keyword_id = axk.keyword_id 
          GROUP BY k.keyword_id 
          ORDER BY k.keyword_name";
$result = mysqli_query($conn, $query);
?>
    <link rel="stylesheet" href="../../admin.css">
    <div class="module-header">
        <div class="module-title">
            <h1>Manage Keywords</h1>
            <p>Add, edit, and manage artwork keywords</p>
        </div>
        <a href="create.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Keyword
        </a>
    </div>

    <div class="table-container">
        <table class="admin-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Keyword</th>
                <th>Artworks Using</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['keyword_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['keyword_name']); ?></td>
                    <td><?php echo $row['artwork_count']; ?></td>
                    <td class="actions">
                        <a href="edit.php?id=<?php echo $row['keyword_id']; ?>"
                           class="btn btn-icon" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <?php if($row['artwork_count'] == 0): ?>
                            <button onclick="confirmDelete(<?php echo $row['keyword_id']; ?>)"
                                    class="btn btn-icon btn-danger" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        function confirmDelete(id) {
            if(confirm('Are you sure you want to delete this keyword?')) {
                window.location.href = 'delete.php?id=' + id;
            }
        }
    </script>

<?php
mysqli_close($conn);
require_once '../../includes/footer.php';
?>