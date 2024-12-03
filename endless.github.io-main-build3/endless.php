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
           a.medium_type, a.web_filename, a.full_filename,
           p.fname, p.lname
    FROM artworks a
    LEFT JOIN people p ON a.person_id = p.PID
    ORDER BY a.piece_id ASC";

            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                echo '<!-- Debug: Starting gallery output -->';
                while ($row = $result->fetch_assoc()) {
                    // Clean up the image path by removing the unnecessary prefix
                    $imagePath = str_replace('acad276/Endless/endless.github.io-main-build3/', '', $row['web_filename']);

                    echo "<!-- Debug - Original path: " . $row['web_filename'] . " -->";
                    echo "<!-- Debug - Cleaned path: " . $imagePath . " -->";

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
                if($result === false) {
                    echo "<!-- Debug - SQL Error: " . $conn->error . " -->";
                }
                echo '<p class="no-results">No art pieces found in the gallery.</p>';
            }
        } catch (Exception $e) {
            error_log("Database error: " . $e->getMessage());
            echo "<!-- Debug - Exception: " . $e->getMessage() . " -->";
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

        // Add click handler for art pieces
        gallery.addEventListener('click', function(e) {
            const artPiece = e.target.closest('.art-piece');
            if (artPiece) {
                const container = artPiece.closest('.art-piece-container');
                const pieceId = container.dataset.id;

                // Fetch artwork details
                fetch(`get_artwork_details.php?id=${pieceId}`)
                    .then(response => response.json())
                    .then(data => {
                        horizontalPanel.innerHTML = `
                            <div class="fade-overlay-left"></div>
                            <div class="fade-overlay-right"></div>
                            <div class="art-info">
                                <div class="art-info-content">
                                    <h2>${data.piece_name}</h2>
                                    <p>${data.piece_description || 'No description available'}</p>
                                    <p>Year: ${data.piece_year || 'Unknown'}</p>
                                    <p>Medium: ${data.medium_type || 'Unknown'}</p>
                                </div>
                                <div class="art-info-image">
                                    <img src="${data.full_filename}" alt="${data.piece_name}">
                                </div>
                            </div>
                        `;
                        horizontalPanel.classList.add('active');
                        closeButton.style.display = 'block';
                    })
                    .catch(error => console.error('Error:', error));
            }
        });

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

        // Close button handler for horizontal panel
        closeButton.addEventListener('click', function() {
            horizontalPanel.classList.remove('active');
            closeButton.style.display = 'none';
        });

        // Close horizontal panel when clicking outside
        horizontalPanel.addEventListener('click', function(e) {
            if (e.target === horizontalPanel) {
                horizontalPanel.classList.remove('active');
                closeButton.style.display = 'none';
            }
        });

        // Handle keyboard events (ESC to close panel)
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && horizontalPanel.classList.contains('active')) {
                horizontalPanel.classList.remove('active');
                closeButton.style.display = 'none';
            }
        });

        // Handle image action buttons (Favorite, Download, Bookmark)
        gallery.addEventListener('click', function(e) {
            const actionIcon = e.target.closest('.action-icon');
            if (actionIcon) {
                e.stopPropagation(); // Prevent opening the horizontal panel
                const container = actionIcon.closest('.art-piece-container');
                const pieceId = container.dataset.id;

                if (actionIcon.classList.contains('favorite-icon')) {
                    // Handle favorite action
                    console.log('Favorite clicked for piece:', pieceId);
                } else if (actionIcon.classList.contains('share-icon')) {
                    // Handle download action
                    console.log('Download clicked for piece:', pieceId);
                } else if (actionIcon.classList.contains('info-icon')) {
                    // Handle bookmark action
                    console.log('Bookmark clicked for piece:', pieceId);
                }
            }
        });
    });
</script>
</body>
</html>
