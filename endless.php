<?php
// Start the session to track user login status
session_start();

// Function to check if the user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Define the redirect URLs
$loginPage = 'login.php';
$dashboardPage = 'dashboard.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Endless Art Gallery</title>
    <link rel="stylesheet" href="endless.css">
</head>
<body>
<header class="header">
    <button class="menu-button">
        <span class="hamburger-icon"></span>
    </button>

    <a href="endless.php" class="logo"></a>

    <!-- Profile Button -->
    <a href="#" class="profile-icon" id="profileButton"></a>
</header>

<div class="menu-overlay">
    <button class="menu-close">
        <img src="ui_images/menuOpen.png" alt="Close menu">
    </button>
    <nav class="menu-items">
        <div class="menu-item">
            <span class="menu-number">01</span>
            <a href="#" class="menu-link active">Home</a>
        </div>
        <div class="menu-item">
            <span class="menu-number">02</span>
            <a href="#" class="menu-link">Favorites</a>
        </div>
        <div class="menu-item">
            <span class="menu-number">03</span>
            <a href="#" class="menu-link">Moodboards</a>
        </div>
    </nav>
</div>

<div class="search-container">
    <form id="searchForm" class="search-bar" action="search.php" method="GET">
        <input type="text" id="searchInput" name="query"
               placeholder="ex. The Starry Night, Mona Lisa, Guernica, etc..."
               autocomplete="off">
        <button type="submit" id="searchButton" class="search-icon"></button>
    </form>
    <div id="searchResults" class="search-results"></div>
</div>

<div class="gallery-container">
    <div class="fade-overlay-top"></div>
    <div class="fade-overlay-bottom"></div>
    <div id="gallery">
        <?php
        include 'config.php';

        try {
            $sql = "SELECT a.piece_id, a.piece_name, a.piece_year, a.piece_description, 
           a.medium_type, a.filename, a.web_filename, a.full_filename,
           p.fname, p.lname
        FROM artworks a
        LEFT JOIN people p ON a.person_id = p.id
        ORDER BY a.piece_id ASC";

            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Construct image path based on your actual structure
                    $imagePath = 'Images/claude_joseph_vernet/' .
                        str_pad($row['piece_id'], 4, '0', STR_PAD_LEFT) .
                        '/WEB ' . str_pad($row['piece_id'], 4, '0', STR_PAD_LEFT) . '.png';

                    echo '<div class="art-piece-container" data-id="' . htmlspecialchars($row['piece_id']) . '">';
                    echo '    <img src="' . htmlspecialchars($imagePath) . '" 
                         alt="' . htmlspecialchars($row['piece_name']) . '" 
                         class="art-piece">';
                    echo '    <div class="image-actions">
                        <img src="ui_images/Favorite.png" alt="Favorite" class="action-icon favorite-icon">
                        <img src="ui_images/Download.png" alt="Download" class="action-icon share-icon">
                        <img src="ui_images/Bookmark.png" alt="Bookmark" class="action-icon info-icon">
                    </div>';
                    echo '</div>';
                }
            } else {
                echo '<p class="no-results">No art pieces found in the gallery.</p>';
            }
        } catch (Exception $e) {
            error_log("Database error: " . $e->getMessage());
            echo '<p class="error">Unable to load gallery content. Please try again later.</p>';
        }

        $conn->close();
        ?>
    </div>
</div>

<div id="horizontalPanel" class="horizontal-scroll-panel">
    <div class="fade-overlay-left"></div>
    <div class="fade-overlay-right"></div>
</div>

<div id="closeButton" class="close-button">✕</div>

<canvas id="backgroundCanvas"></canvas>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profileButton = document.getElementById('profileButton');

        // Redirect to login or dashboard based on user's login status
        profileButton.addEventListener('click', function(e) {
            e.preventDefault();

            // PHP dynamically sets the appropriate URL
            const isLoggedIn = <?= json_encode(isLoggedIn()); ?>;
            const redirectTo = isLoggedIn ? '<?= $dashboardPage; ?>' : '<?= $loginPage; ?>';

            // Redirect to the appropriate page
            window.location.href = redirectTo;
        });
    });
</script>
</body>
</html>
