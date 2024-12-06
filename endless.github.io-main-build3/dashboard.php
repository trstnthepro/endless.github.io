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
$stmt = $pdo->prepare("SELECT username, email, first_name, last_name, bio, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();




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
<head>
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

<div class="profile-container">
    <h2 class="profile-header">Profile</h2>

    <div id="userProfile">
        <div class="profile-column center">

                <img id="profileImage" src="ui_images/profile_image.png" alt="User Profile Picture">

            <form class="profile-form" id="upload">
                <input type="file" class="imageUpload" accept="image/*" required>
            </form>
        </div>


        <div class="profile-column">
            <span id="displayName"><h1 id="userFullName"><?= $userFullName ?></h1></span>
            <p>Email: <span id="userEmail"><?= $userEmail ?></span></p>
            <p>Bio: <span id="userBio"><?= $userBio ?></span></p>



            <button id="editProfileButton" class="profile-edit-button">Edit Profile</button>

            <form id="profileEditForm" class="profile-form" method="POST" action="update_profile.php" style="display: none;">

                <label>First Name</label><br>
                <input name="firstname" id="firstNameInput" type="text" value="<?= htmlspecialchars($userData['first_name']) ?>"><br>


                <label>Last Name</label><br>
                <input name="lastname" id="lastNameInput" type="text" value="<?= htmlspecialchars($userData['last_name']) ?>"><br>


                <label>About Me</label><br>
                <textarea name="bio" id="bioInput"><?= htmlspecialchars($userData['bio']) ?></textarea><br>


                <input type="submit" value="Save Changes" class="profile-edit-button">
            </form>
        </div>
    </div>
</div>


<hr class="profile-divider">


<div id="favorites">
    <div id="favtitle"></div>
    <div class="favorites-title">Favorites</div>
    <br>
    <p>Van Gogh [insert mini pic of favorited image that hyperlinks to image]</p>
</div>


<script>

    document.addEventListener('DOMContentLoaded', function() {
        const menuButton = document.querySelector('.menu-button');
        const menuOverlay = document.querySelector('.menu-overlay');
        const menuClose = document.querySelector('.menu-close');

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


    // Profile image upload handler
    const imageUploadInput = document.getElementById('imageUpload');
    const profileImage = document.getElementById('profileImage');


    // imageUploadInput.addEventListener('change', function () {
    //     const file = imageUploadInput.files[0];
    //     if (file) {
    //         const confirmChange = confirm("Are you sure you want to change your profile picture?");
    //         if (confirmChange) {
    //             const reader = new FileReader();
    //             reader.onload = function (e) {
    //                 profileImage.src = e.target.result;
    //             };
    //             reader.readAsDataURL(file);
    //         } else {
    //             imageUploadInput.value = '';
    //         }
    //     }
    // });

    // Profile edit form handler
    const editProfileButton = document.getElementById('editProfileButton');
    const profileEditForm = document.getElementById('profileEditForm');
    const userFullName = document.getElementById('userFullName');
    const userBio = document.getElementById('userBio');
    const favtitle = document.getElementById('favtitle');


    editProfileButton.addEventListener('click', function () {
        profileEditForm.style.display = profileEditForm.style.display === 'none' ? 'block' : 'none';
    });


    profileEditForm.addEventListener('submit', function () {
        // Let the form submit naturally to update_profile.php
        console.log("Form is being submitted to update_profile.php...");
    });


</script>
</body>
</html>
