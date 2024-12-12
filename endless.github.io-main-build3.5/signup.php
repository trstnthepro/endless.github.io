<?php
// Ensure no whitespace or output before session_start()
session_start();

// Database configuration
$host = "webdev.iyaserver.com";
$userid = "twilcher_hudson_endless";
$userpw = "VanGogh12!";
$db = "twilcher_endless";

$pdo = new mysqli(
    $host,
    $userid,
    $userpw,
    $db
);

if ($pdo->connect_errno) {
    echo "Database connection error: " . htmlspecialchars($pdo->connect_error);
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    $errors = [];

    // Validate inputs
    if (empty($username)) {
        $errors[] = "Username is required.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }
    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // If no errors, proceed with database insertion
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors[] = "Username or email already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                // Redirect to login page after successful signup
                header("Location: login.php");
                exit();
            } else {
                $errors[] = "An error occurred while creating your account. Please try again.";
            }
        }

        $stmt->close();
    }
}

$pdo->close();
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
    <title>Endless Art Gallery - Sign Up</title>
    <link rel="stylesheet" href="endless.css">
</head>
<body>
<div class="header">
    <div class="logo">Sign Up</div>
</div>
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

<div class="form-container">
    <?php if (!empty($errors)): ?>
        <div class="error-messages">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="signup.php" method="post">
        <h1 class="form-header">Sign Up</h1>


        <input type="text" id="username" name="username" placeholder="Username:" required>

        <div class="name-container">
            <div class="input-group">
                <input type="text" id="first_name" name="first_name" placeholder="First Name:" required>

            </div>
            <div class="input-group">
                <input type="text" id="last_name" name="last_name" placeholder="Last Name:" required>

            </div>
        </div>

        <input type="email" id="email" name="email" placeholder="Email:" required>

        <input type="password" id="password" name="password" placeholder="Password:" required>

        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password:" required>

        <div class="button-group">
            <button type="submit" class="signup-button">Sign Up</button>
        </div>
        <hr style="margin: 20px; border-color: grey;">

        <a href="login.php">
            <div class="button-group">
                <button type="button" class="login-button">Login</button>
            </div>
        </a>
    </form>
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
</script>
</body>
    </html>
<?php
