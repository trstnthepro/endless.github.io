<?php
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database configuration
$host = "webdev.iyaserver.com";
$userid = "twilcher_hudson_endless";
$userpw = "VanGogh12!";
$db = "twilcher_endless";

$pdo = new mysqli($host, $userid, $userpw, $db);
if ($pdo->connect_errno) {
    echo "Database connection error: " . htmlspecialchars($pdo->connect_error);
    exit();
}

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username, email, first_name, last_name, bio, profile_picture, 
    JSON_UNQUOTE(favorites) as favorites 
    FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();


// Debug steps
echo "<!-- 
DEBUG STEPS:
1. Raw favorites from DB: " . ($userData['favorites'] ?? 'null') . "
-->";

// Try different approaches to decode
$approach1 = json_decode($userData['favorites'], true);
$approach2 = json_decode(str_replace('\\', '', $userData['favorites']), true);
$approach3 = json_decode(stripslashes($userData['favorites']), true);

echo "<!--
2. Decode attempts:
Approach 1 (simple decode): " . print_r($approach1, true) . "
Approach 2 (remove backslashes): " . print_r($approach2, true) . "
Approach 3 (stripslashes): " . print_r($approach3, true) . "
JSON Last Error: " . json_last_error_msg() . "
-->";

// Use the successful approach
if ($approach1 !== null) {
    $favorites = $approach1;
} elseif ($approach2 !== null) {
    $favorites = $approach2;
} elseif ($approach3 !== null) {
    $favorites = $approach3;
} else {
    // If none work, try parsing it manually
    $rawFavorites = trim($userData['favorites'], '[]"');
    $favorites = $rawFavorites ? explode(',', $rawFavorites) : [];
}

echo "<!--
3. Final favorites array: " . print_r($favorites, true) . "
-->";

if (!$userData) {
    // Handle the case where the user is not found in the database
    echo "User not found!";
    exit();
}

$userFullName = htmlspecialchars($userData['first_name'] . ' ' . $userData['last_name']);
$userEmail = htmlspecialchars($userData['email']);
$userBio = htmlspecialchars($userData['bio']);
$profilePicture = !empty($userData['profile_picture']) ? htmlspecialchars($userData['profile_picture']) : 'ui_images/default_profile_icon.png';

?>
<!DOCTYPE html>
<html lang="en">
<style>

    #favorites {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .favorites-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr); /* Exactly 4 columns */
        gap: 20px;
        margin: 0 auto;
        padding: 15px;
    }

    .favorites-title {
        margin-bottom: 20px;
        padding: 0 15px;
    }

    .favorite-item {
        position: relative;
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.2s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        aspect-ratio: 1; /* Makes the whole item square */
    }

    .favorite-image-container {
        position: relative;
        height: 100%;
        width: 100%;
    }

    .favorite-thumbnail {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .favorite-details {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 8px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(5px);
    }

    .favorite-details h3 {
        margin: 0;
        font-size: 0.9rem;
        color: #333;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .favorite-details p {
        margin: 2px 0 0 0;
        color: #666;
        font-size: 0.8rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .remove-favorite {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.9);
        border: none;
        color: #333;
        font-size: 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .favorite-item:hover .remove-favorite {
        opacity: 1;
    }

    /* Add responsive behavior for smaller screens */
    @media (max-width: 1024px) {
        .favorites-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .favorites-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .favorites-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-0MKW3NC13P"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'G-0MKW3NC13P');
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Endless Art Gallery - Profile/Dashboard</title>
    <link rel="stylesheet" href="endless.css">
</head>
<body>

<header class="header">
    <button class="menu-button">
        <span class="hamburger-icon"></span>
    </button>

    <a href="endless.php" class="logo"></a>

    <a href="logout.php" class="logout_button">Logout</a>
</header>
<h2 class="profile-header">Profile</h2>
<div class="profile-container">
    <div id="userProfile">
        <div class="profile-column center">
            <a>
                <img id="profileImage" src="ui_images/profile_image.png" alt="User Profile Picture">
            </a>
            <form class="profile-form" id="upload">
                <input type="file" id="imageUpload" accept="image/*" required>
            </form>
        </div>

        <div class="profile-column">
            <span id="displayName"><h1 id="userFullName"><?= $userFullName ?></h1></span>
            <p>Email: <span id="userEmail"><?= $userEmail ?></span></p>
            <p>Bio: <span id="userBio"><?= $userBio ?></span></p>

            <button id="editProfileButton" class="profile-edit-button">Edit Profile</button>

            <form id="profileEditForm" class="profile-form" method="POST" action="update_profile.php"
                  style="display: none;">
                <label>First Name</label><br>
                <input name="firstname" id="firstNameInput" type="text"
                       value="<?= htmlspecialchars($userData['first_name']) ?>"><br>

                <label>Last Name</label><br>
                <input name="lastname" id="lastNameInput" type="text"
                       value="<?= htmlspecialchars($userData['last_name']) ?>"><br>

                <label>About Me</label><br>
                <textarea name="bio" id="bioInput"><?= htmlspecialchars($userData['bio']) ?></textarea><br>

                <input type="submit" value="Save Changes" class="profile-edit-button">
            </form>
        </div>
    </div>

</div>

<hr class="profile-divider">

<div id="favorites">
    <div class="favorites-title">Favorites</div>
    <?php
    if (!empty($favorites)) {
        $placeholders = str_repeat('?,', count($favorites) - 1) . '?';

        // Add debug output
        echo "<!-- Debug: Favorites array: " . print_r($favorites, true) . " -->";

        $favorites_query = "
            SELECT 
                a.piece_id,
                a.piece_name,
                a.web_filename,
                a.full_filename,
                p.fname as artist_fname,
                p.lname as artist_lname
            FROM artworks a
            LEFT JOIN artists p ON a.artist_id = p.PID
            WHERE a.piece_id IN ($placeholders)";

        if ($stmt = $pdo->prepare($favorites_query)) {
            $types = str_repeat('i', count($favorites));
            $bindParams = array($types);
            foreach ($favorites as $key => $value) {
                $bindParams[] = &$favorites[$key];
            }
            call_user_func_array(array($stmt, 'bind_param'), $bindParams);

            $stmt->execute();
            $result = $stmt->get_result();
            $favorites_data = $result->fetch_all(MYSQLI_ASSOC);

            // Add debug output for image paths
            echo "<!-- Debug: Raw favorites data: " . print_r($favorites_data, true) . " -->";

            if ($favorites_data) {
                ?>
                <div class="favorites-grid">
                    <?php foreach ($favorites_data as $favorite):
                        // Clean up the image path by removing any unnecessary prefix
                        $imagePath = str_replace('acad276/Endless/endless.github.io-main-build3/', '', $favorite['web_filename']);

                        // Debug output for each image path
                        echo "<!-- Debug: Original path: " . $favorite['web_filename'] . " -->";
                        echo "<!-- Debug: Cleaned path: " . $imagePath . " -->";
                        ?>
                        <div class="favorite-item">
                            <div class="favorite-image-container">
                                <!--                                <a href="endless.php?piece_id=-->
                                <?php //= htmlspecialchars($favorite['piece_id'])
                                ?><!--">-->
                                <img src="<?= htmlspecialchars($imagePath) ?>"
                                     alt="<?= htmlspecialchars($favorite['piece_name']) ?>"
                                     class="favorite-thumbnail"
                                     loading="lazy"
                                     onerror="this.onerror=null; this.src='ui_images/default_artwork.png';">
                                <!--                                </a>-->
                                <button class="remove-favorite"
                                        data-piece-id="<?= htmlspecialchars($favorite['piece_id']) ?>">
                                    ×
                                </button>
                            </div>
                            <div class="favorite-details">
                                <h3><?= htmlspecialchars($favorite['piece_name']) ?></h3>
                                <p><?= htmlspecialchars($favorite['artist_fname'] . ' ' . $favorite['artist_lname']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php
            }
            $stmt->close();
        }
    } else { ?>
        <p class="no-favorites">No favorites yet. Browse the gallery to add some!</p>
    <?php } ?>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
// Handle removing favorites
        document.querySelectorAll('.remove-favorite').forEach(button => {
            button.addEventListener('click', async function (e) {
                e.preventDefault();
                e.stopPropagation(); // Prevent modal from opening
                const pieceId = this.dataset.pieceId;
                const favoriteItem = this.closest('.favorite-item');

                if (confirm('Are you sure you want to remove this from your favorites?')) {
                    try {
                        const response = await fetch('update_favorites.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `piece_id=${pieceId}&action=remove`
                        });

                        const data = await response.json();
                        if (data.success) {
                            favoriteItem.remove();

// Check if there are any favorites left
                            const favoritesGrid = document.querySelector('.favorites-grid');
                            if (favoritesGrid && favoritesGrid.children.length === 0) {
                                const noFavorites = document.createElement('p');
                                noFavorites.className = 'no-favorites';
                                noFavorites.textContent = 'No favorites yet. Browse the gallery to add some!';
                                favoritesGrid.parentNode.replaceChild(noFavorites, favoritesGrid);
                            }
                        } else {
                            alert('Error removing from favorites');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Error removing from favorites');
                    }
                }
            });
        });

// Handle favorite item clicks
        document.querySelectorAll('.favorite-item').forEach(item => {
            item.addEventListener('click', function (e) {
// Don't trigger if clicking the remove button
                if (e.target.closest('.remove-favorite')) {
                    return;
                }

// Get the item's data
                const imageElement = item.querySelector('.favorite-thumbnail');
                const titleElement = item.querySelector('.favorite-details h3');
                const artistElement = item.querySelector('.favorite-details p');

// Create modal
                const modalHTML = `
<div class="favorite-modal" style="
                    display: block;
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0,0,0,0.7);
                    z-index: 1000;
                ">
    <div class="favorite-modal-content" style="
                        position: relative;
                        background-color: #fff;
                        margin: 5% auto;
                        padding: 20px;
                        width: 90%;
                        max-width: 800px;
                        max-height: 90vh;
                        border-radius: 8px;
                        overflow-y: auto;
                    ">
                        <span class="close-modal" style="
                            position: absolute;
                            top: 10px;
                            right: 15px;
                            font-size: 24px;
                            cursor: pointer;
                            z-index: 1001;
                        ">&times;</span>
        <img src="${imageElement.src}"
             alt="${imageElement.alt}"
             style="
                                display: block;
                                margin: 0 auto;
                                max-width: 100%;
                                max-height: 70vh;
                                object-fit: contain;
                             ">
        <div class="modal-details" style="
                            padding: 15px 0;
                            text-align: center;
                        ">
            <h2>${titleElement.textContent}</h2>
            <p>${artistElement.textContent}</p>
        </div>
    </div>
</div>
`;

// Add the modal to the document
                document.body.insertAdjacentHTML('beforeend', modalHTML);

// Get the newly created modal
                const modal = document.querySelector('.favorite-modal');
                const closeBtn = modal.querySelector('.close-modal');

// Handle closing the modal
                closeBtn.addEventListener('click', function () {
                    modal.remove();
                });

// Close modal when clicking outside
                modal.addEventListener('click', function (event) {
                    if (event.target === modal) {
                        modal.remove();
                    }
                });

// Close modal with Escape key
                const handleEscape = function (event) {
                    if (event.key === 'Escape') {
                        modal.remove();
                        document.removeEventListener('keydown', handleEscape);
                    }
                };
                document.addEventListener('keydown', handleEscape);
            });
        });

// Profile image upload handler
        const imageUploadInput = document.getElementById('imageUpload');
        const profileImage = document.getElementById('profileImage');

        imageUploadInput.addEventListener('change', function () {
            const file = imageUploadInput.files[0];
            if (file) {
                const confirmChange = confirm("Are you sure you want to change your profile picture?");
                if (confirmChange) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        profileImage.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    imageUploadInput.value = '';
                }
            }
        });

// Profile edit form handler
        const editProfileButton = document.getElementById('editProfileButton');
        const profileEditForm = document.getElementById('profileEditForm');

        editProfileButton.addEventListener('click', function () {
            profileEditForm.style.display = profileEditForm.style.display === 'none' ? 'block' : 'none';
        });

        profileEditForm.addEventListener('submit', function () {
            console.log("Form is being submitted to update_profile.php...");
        });
    });
</script>
</body>
</html>