<?php

session_start();
// Rest of your existing PHP code...
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = "twilcher.webdev.iyaserver.com";
    $userid = "twilcher_ally_endless";
    $userpw = "VanGogh12!";
    $db = "twilcher_endless";

    $mysql = new mysqli($host, $userid, $userpw, $db);

    if ($mysql->connect_errno) {
        echo json_encode(['error' => "Database connection error: " . $mysql->connect_error, 'success' => false]);
        echo "<script>console.log($mysql->connect_error)</script>";
        exit();
    }

    $email = $mysql->real_escape_string($_POST["email"]);

    // Check if the email already exists
    $sql_check = "SELECT * FROM emails WHERE email = '$email'";
    $result = $mysql->query($sql_check);

    if ($result->num_rows > 0) {
        echo json_encode(['error' => "That email has already been used. Please close and reopen the popup to retry.", 'success' => false]);
        exit();
    }

    // Insert the email into the database
    $sql_insert = "INSERT INTO emails (email) VALUES ('$email')";
    if (!$mysql->query($sql_insert)) {
        echo json_encode(['error' => "Database error: " . $mysql->error, 'success' => false]);
        exit();
    }

    echo json_encode(['success' => true]);
//                echo "<p style='color: azure'> success</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-0MKW3NC13P"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-0MKW3NC13P');
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Endless Art Gallery</title>
    <link rel="stylesheet" href="endless.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            text-align: center;
            background-color: black;
            font-family: 'Futura', sans-serif;
            color: white;
        }

        form {
            width: 500px;
            margin: auto;
        }

        .email-catch {
            color: rgb(219, 219, 219);
        }

        .icon-container {
            width: 51px;
            height: 51px;
            background-color: rgb(18, 18, 18, 0.98);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 0 4px 3px rgb(219, 219, 219, 0.25);
            position: fixed;
            bottom: 75px;
            right: 75px;
            z-index: 5;
            cursor: pointer;
        }

        .icon-symbol {
            opacity: 0.75;
            transition: opacity 0.3s ease;
        }

        .icon-symbol:hover {
            opacity: 1;
        }

        .join-newsletter {
            width: 379px;
            height: 51px;
            background-color: rgb(18, 18, 18, 1);
            border-radius: 23px;
            z-index: 1;
            position: fixed;
            bottom: 75px;
            right: 75px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .join-newsletter p {
            margin: 11px;
        }

        .icon-container:hover + .join-newsletter {
            opacity: 1;
            pointer-events: auto;
        }

        .pop-up {
            width: 752px;
            height: 337px;
            background-color: rgba(18, 18, 18, 0.9);
            border-radius: 23px;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0;
            pointer-events: none;
            z-index: 5;
            transition: opacity 0.5s ease;
        }

        .pop-up.visible {
            opacity: 1;
            pointer-events: auto;
        }

        .pop-up .icon-container {
            position: relative;
            top: 0;
            left: 0;
            margin: 20px auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .pop-up-text {
            margin-top: 20px;
        }

        #email-input-box {
            width: 515px;
            height: 51px;
            background-color: rgba(18, 18, 18, 0.98);
            color: rgba(219, 219, 219, 0.75);
            border-radius: 23px;
            padding-left: 20px;
            font-family: 'Futura', sans-serif;
            z-index: 1;
        }

        #join-now {
            font-family: 'Futura', sans-serif;
            width: 116px;
            height: 36px;
            border-radius: 23px;
            background-color: rgba(18, 18, 18, 0.98);
            box-shadow: 0 0 4px 3px rgba(219, 219, 219, 0.25);
            color: rgba(219, 219, 219, 1);
            z-index: 5;
            position: relative;
            bottom: 47px;
            left: 225px;
        }

        #join-now:hover {
            background-color: rgba(219, 219, 219, 0.98);
            color: rgba(18, 18, 18, 0.98);
        }

        .exit {
            top: 20px;
            right: 20px;
            position: absolute;
            cursor: pointer;
            font-size: 18px;
            color: white;
        }
    </style>
</head>
<body>
<header class="header">
    <button class="menu-button">
        <span class="hamburger-icon"></span>
    </button>

    <a href="endless.php" class="logo"></a>

    <a href="dashboard.php" class="profile-icon"></a>
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
            <a href="about.html" class="menu-link">About</a>
        </div>
        <div class="menu-item">
            <span class="menu-number">03</span>
            <a href="#" class="menu-link">Favorites</a>
        </div>
        <div class="menu-item">
            <span class="menu-number">04</span>
            <a href="#" class="menu-link">Moodboards</a>
        </div>
    </nav>
</div>

<div class="search-container">
    <form id="searchForm" class="search-bar" action="search.php" method="GET">
        <input type="text" id="searchInput" name="query"
               placeholder="ex. The Starry Night, Mona Lisa, Guernica, etc..."
               autocomplete="off">
        <button type="submit" id="searchButton" class="search-icon" style="background: url('ui_images/Search.png') no-repeat; right: 0.50rem"></button>
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
            $servername = "webdev.iyaserver.com";
            $username = "twilcher";
            $password = "AcadDev_Wilcher_6801994716";
            $dbname = "twilcher_endless";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $conn->set_charset("utf8mb4");
            $sql = "SELECT a.piece_id, a.piece_name, a.piece_year, a.piece_description, 
           a.medium_type, a.web_filename, a.full_filename,
           p.fname, p.lname
    FROM artworks a
    LEFT JOIN artists p ON a.artist_id = p.PID
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
                    echo '    <div class="image-actions">';
                    if (isset($_SESSION['user_id'])) {
                        echo '<img src="ui_images/Favorite.png" alt="Favorite" class="action-icon favorite-icon">';
                    } else {
                        echo '<a href="login.php"><img src="ui_images/Favorite.png" alt="Favorite" class="action-icon"></a>';
                    }
                    echo '    </div>';
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

<div class="email-catch">
    <div class="floating-icons">
        <div class="icon-container" id="icon">
            <div class="icon-symbol">
                <img src="ui_images/newsletter_symbol.png" alt="Newsletter Icon">
            </div>
        </div>
        <div class="join-newsletter">
            <p>Join our newsletter...</p>
        </div>
    </div>

    <div class="pop-up" id="pop-up">
        <div class="icon-container">
            <div class="icon-symbol">
                <img src="ui_images/newsletter_symbol.png" alt="Pop-up Icon">
            </div>
        </div>
        <div class="pop-up-text">
            <h1>Join the ENDLESS.AI newsletter</h1>
            <p>for updates & daily inspiration</p>
        </div>
        <div class="email-input">
            <form id="newsletter-form">
                <input type="email" name="email" placeholder="yourname@email.com" id="email-input-box" required>
                <input type="submit" value="Join now!" id="join-now">
            </form>
        </div>
        <div class="exit" id="xButton">X</div>
    </div>
</div>


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

        async function toggleFavorite(button, pieceId) {
            const isFavorited = button.classList.contains('favorited');
            const action = isFavorited ? 'remove' : 'add';

            try {
                const response = await fetch('update_favorites.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `piece_id=${pieceId}&action=${action}`
                });

                const data = await response.json();
                if (data.success) {
                    if (action === 'add') {
                        button.classList.add('favorited');
                        button.src = 'ui_images/Favorite_filled.png';
                    } else {
                        button.classList.remove('favorited');
                        button.src = 'ui_images/Favorite.png';
                    }
                } else {
                    if (data.error === 'Not logged in') {
                        window.location.href = 'login.php';
                    } else {
                        alert('Error updating favorite status');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error updating favorite status');
            }
        }

        // Add click handler for art pieces
        gallery.addEventListener('click', function(e) {

            const actionIcon = e.target.closest('.action-icon');
            if (actionIcon) {
                e.stopPropagation(); // Prevent opening the horizontal panel
                const container = actionIcon.closest('.art-piece-container');
                const pieceId = container.dataset.id;

                if (actionIcon.classList.contains('favorite-icon')) {
                    toggleFavorite(actionIcon, pieceId);
                }
            }

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

    $(document).ready(function() {
        const popup = $("#pop-up");
        const originalPopupContent = popup.html();

        $("#icon").on("click", function() {
            popup.html(originalPopupContent);
            popup.addClass("visible");

            $("#xButton").on("click", closePopup);

            // Handle form submission
            $("#newsletter-form").on("submit", function(e) {
                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: window.location.href,
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        console.log("in success")
                        console.log(response)
                        if (response.success) {
                            popup.html(`
                                <div class='icon-container'>
                                    <div class='icon-symbol'>
                                        <img src="newsletter_symbol.png" alt="Pop-up Icon">
                                    </div>
                                </div>
                                <div class='pop-up-text'>
                                    <h1>Thanks for joining!</h1>
                                    <p>You'll hear from us soon.</p>
                                </div>
                                <div class='exit' id='xButton'>X</div>
                            `);

                            $("#xButton").on("click", closePopup);
                        } else {
                            popup.html(`
                                <div class='icon-container'>
                                    <div class='icon-symbol'>
                                        <img src="newsletter_symbol.png" alt="Pop-up Icon">
                                    </div>
                                </div>
                                <div class='pop-up-text'>
                                    <h1>An error occurred.</h1>
                                    <p>${response.error}</p>
                                </div>
                                <div class='exit' id='xButton'>X</div>
                            `);
                            // alert("Error: " + response.error);
                            $("#xButton").on("click", closePopup);
                        }
                    },
                    error: function(error) {
                        popup.html(`
                                <div class='icon-container'>
                                    <div class='icon-symbol'>
                                        <img src="newsletter_symbol.png" alt="Pop-up Icon">
                                    </div>
                                </div>
                                <div class='pop-up-text'>
                                    <h1>An error occurred.</h1>
                                    <p>This email has already been used to sign up for our newsletter. Please exit and use a different email to sign up.</p>
                                </div>
                                <div class='exit' id='xButton'>X</div>
                            `);
                        $("#xButton").on("click", closePopup);
                    }
                });
            });
        });

        function closePopup() {
            popup.removeClass("visible");
        }

        $("#xButton").on("click", closePopup);
    });
</script>




<div class="artwork-modal">
    <button class="modal-close">
        <img src="ui_images/menuOpen.png" alt="Close modal">
    </button>
    <div class="fade-overlay-left"></div>
    <div class="fade-overlay-right"></div>

    <div class="horizontal-scroll-container">
        <!-- Original sections -->
        <div class="artwork-content" style="padding-left: 12.56rem;">
            <div class="artwork-section">
                <img src="/api/placeholder/400/400" alt="Artwork" class="artwork-image">
                <div class="artwork-info">
                    <h1 class="artwork-title">name of art piece...</h1>
                    <p class="artwork-description">
                        Lorem ipsum dolor amet, consectetuer adipiscing elit. Facilisis ipsum eu pellentesque proin dis nisi turpis. Porttitor posuere iaculis diam phasellus primis pellentesque.
                        <br><br>
                        Ut egestas ad a velit ridiculus nisi torquent sociosqu finibus. Ut sed blandit dictumst vel eros sed metus amet. Euismod elit fames in a elit rhoncus dolor. Erat sollicitudin porta habitant gravida donec vestibulum.
                        <br><br>
                        Nec mi faucibus porttitor nulla ridiculus malesuada dolor felis. Phasellus sapien mauris proin maximus blandit vulputate etiam massa.
                    </p>
                </div>
            </div>
            <div class="artist-section">
                <img src="/api/placeholder/400/400" alt="Artist" class="artist-image">
                <div class="artist-info">
                    <h2 class="artist-name">name of artist...</h2>
                    <p class="artist-bio">
                        Lorem ipsum dolor amet, consectetuer adipiscing elit. Facilisis ipsum eu pellentesque proin dis nisi turpis. Porttitor posuere iaculis diam phasellus primis pellentesque.
                        <br><br>
                        Ut egestas ad a velit ridiculus nisi torquent sociosqu finibus. Ut sed blandit dictumst vel eros sed metus amet. Euismod elit fames in a elit rhoncus dolor. Erat sollicitudin porta habitant gravida donec vestibulum.
                        <br><br>
                        Nec mi faucibus porttitor nulla ridiculus malesuada dolor felis. Phasellus sapien mauris proin maximus blandit vulputate etiam massa.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.querySelector('.artwork-modal');
        const closeButton = document.querySelector('.modal-close');
        const scrollContainer = document.querySelector('.horizontal-scroll-container');
        const originalContent = document.querySelector('.artwork-content');

        // Add this after document.ready
        function loadExistingFavorites() {
            fetch('get_favorites.php')
                .then(response => response.json())
                .then(data => {
                    if (data.favorites) {
                        data.favorites.forEach(pieceId => {
                            const container = document.querySelector(`.art-piece-container[data-id="${pieceId}"]`);
                            if (container) {
                                const favoriteIcon = container.querySelector('.favorite-icon');
                                favoriteIcon.classList.add('favorited');
                                favoriteIcon.src = 'ui_images/Favorite_filled.png';
                            }
                        });
                    }
                })
                .catch(error => console.error('Error loading favorites:', error));
        }

        loadExistingFavorites();


// Call this function when the page loads
        document.addEventListener('DOMContentLoaded', loadExistingFavorites);

        // Gallery click handler - single unified version
        const gallery = document.getElementById('gallery');
        gallery.addEventListener('click', function(e) {
            const artPiece = e.target.closest('.art-piece');
            if (artPiece) {
                const container = artPiece.closest('.art-piece-container');
                const pieceId = container.dataset.id;

                fetch(`get_artwork_details.php?id=${pieceId}`)
                    .then(response => response.text())
                    .then(data => {
                        const [title, description, artistName, artistBio, artistPortrait] = data.split('|||');

                        scrollContainer.innerHTML = '';

                        const newContent = `
                        <div class="artwork-content" style="padding-left: 12.56rem;">
                            <div class="artwork-section">
                                <img src="${artPiece.src}" alt="${title}" class="artwork-image">
                                <div class="artwork-info">
                                    <h1 class="artwork-title">${title}</h1>
                                    <p class="artwork-description">${description || 'No description available'}</p>
                                </div>
                            </div>
                            <div class="artist-section">
                                <img src="${artistPortrait || '/api/placeholder/400/400'}" alt="Artist Portrait" class="artist-image">
                                <div class="artist-info">
                                    <h2 class="artist-name">${artistName || 'Unknown Artist'}</h2>
                                    <p class="artist-bio">${artistBio || 'No artist biography available.'}</p>
                                </div>
                            </div>
                        </div>`;

                        scrollContainer.style.scrollBehavior = 'auto';
                        scrollContainer.innerHTML = newContent;
                        modal.classList.add('active');
                        scrollContainer.scrollLeft = 0;
                        scrollContainer.style.scrollBehavior = 'smooth'; // Reset for future scrolling
                    })
                    .catch(error => console.error('Error:', error));
            }
        });

        function appendNewContent() {
            const originalContent = document.querySelector('.artwork-content');
            const clone = originalContent.cloneNode(true);
            clone.style.paddingLeft = '';
            scrollContainer.appendChild(clone);
        }

        scrollContainer.addEventListener('scroll', () => {
            const scrollLeft = scrollContainer.scrollLeft;
            const scrollWidth = scrollContainer.scrollWidth;
            const containerWidth = scrollContainer.clientWidth;

            if (scrollLeft + containerWidth > scrollWidth * 0.8) {
                appendNewContent();
            }
        });

        closeButton.addEventListener('click', () => {
            modal.classList.remove('active');
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                modal.classList.remove('active');
            }
        });
    });
</script>

</body>
</html>