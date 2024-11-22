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

    <a href="profile.php" class="profile-icon"></a>
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
                echo '<!-- Debug: Starting gallery output -->';

                while ($row = $result->fetch_assoc()) {
                    // Construct image path based on your actual structure
                    $imagePath = 'Images/claude_joseph_vernet/' .
                        str_pad($row['piece_id'], 4, '0', STR_PAD_LEFT) .
                        '/WEB ' . str_pad($row['piece_id'], 4, '0', STR_PAD_LEFT) . '.png';

                    echo "<!-- Debug - Image Path: $imagePath -->";

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
        const searchForm = document.getElementById('searchForm');
        const searchInput = document.getElementById('searchInput');
        const gallery = document.getElementById('gallery');
        const menuButton = document.querySelector('.menu-button');
        const menuOverlay = document.querySelector('.menu-overlay');
        const menuClose = document.querySelector('.menu-close');
        const closeButton = document.getElementById('closeButton');
        const horizontalPanel = document.getElementById('horizontalPanel');

        // Search form submission
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const query = searchInput.value.trim();
            if (!query) return;

            fetch(`search.php?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Search error:', data.error);
                        return;
                    }
                    updateGallery(data);
                })
                .catch(error => console.error('Search error:', error));
        });

        function updateGallery(artworks) {
            if (!artworks.length) {
                gallery.innerHTML = '<p style="color: white; text-align: center; margin-top: 20px;">No results found</p>';
                return;
            }

            gallery.innerHTML = '';
            artworks.forEach(artwork => {
                const artworkHTML = `
            <div class="art-piece-container" data-id="${artwork.id}">
                <img src="${artwork.image}"
                     data-full-image="${artwork.fullImage}"
                     alt="${artwork.title}"
                     class="art-piece">
                <div class="image-actions">
                    <img src="ui_images/Favorite.png" alt="Favorite" class="action-icon favorite-icon">
                    <img src="ui_images/Download.png" alt="Download" class="action-icon share-icon">
                    <img src="ui_images/Bookmark.png" alt="Bookmark" class="action-icon info-icon">
                </div>
            </div>
        `;
                gallery.insertAdjacentHTML('beforeend', artworkHTML);
            });
        }

            // Scroll to first result
            const firstArtwork = gallery.querySelector('.art-piece-container');
            if (firstArtwork) {
                firstArtwork.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        // Clear search and reload page when input is cleared
        searchInput.addEventListener('input', function() {
            if (this.value.trim() === '') {
                location.reload();
            }
        });

        // Menu functionality
        menuButton.addEventListener('click', function() {
            menuOverlay.classList.add('active');
        });

        menuClose.addEventListener('click', function() {
            menuOverlay.classList.remove('active');
        });

        // Close button handler
        closeButton.addEventListener('click', function() {
            horizontalPanel.classList.remove('active');
            closeButton.style.display = 'none';
        });
    });
</script>
</body>
</html>
