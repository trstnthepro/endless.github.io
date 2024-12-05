<?php
// Start the session to manage login state
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
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $errors = [];

    // Validate inputs
    if (empty($email)) {
        $errors[] = "Email is required.";
    }
    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    // If no errors, attempt to log in
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $errors[] = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Endless Art Gallery - Login</title>
    <link rel="stylesheet" href="endless.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: "Futura";
            background-color: #000000;
            color: white;
            display: flex;
            flex-direction: column;
        }

        .header {
            position: fixed; /* Keeps the header at the top */
            width: 100%; /* Ensures it spans the entire width */
            top: 0; /* Aligns it to the top of the viewport */
            background-color: #000; /* Match header background to avoid gaps */
            z-index: 1000; /* Ensures it stays above other content */
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.5); /* Optional: Adds a shadow for clarity */
        }

        .form-header {
            margin-top:0px;
        }

        .logo {
            font-size: 1.5em;
            font-weight: bold;
            text-align: center;
            align-content: center;
        }

        .form-container {
            margin-top: 100px;
            padding: 20px;
            width: 50%;
            background-color: #1a1a1a;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.5);
            margin-left: auto;
            margin-right: auto;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 10px;
            font-size: 1.2em;
        }

        input {
            margin-bottom: 10px;
            padding: 10px;
            font-size: 1em;
            border-radius: 5px;
            border: 1px solid #555;
            background-color: #333;
            color: white;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
        }

        button {
            padding: 10px 20px;
            font-size: 1.2em;
            background-color: white;
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: Futura, sans-serif;
            transition: darkgrey 350ms ease-in;
        }

        button[type="submit"] {
            font-size: 1.2em;
            background: white;
            color: black;
            border: none;
            padding: 10px 20px;
            border-radius: 0.5rem;
            cursor: pointer;
            font-family: Futura, sans-serif;
            transition: darkgrey 150ms ease-in;
        }

        button[type="submit"]:hover {
            background: darkgrey;
        }

        button:hover {
            background: darkgrey;
        }

        .error-messages {
            color: red;
        }

        a {
            text-decoration: none;
        }
    </style>
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

    <form action="login.php" method="post">
        <h1 class="form-header">Login</h1>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <div class="button-group">
            <button type="submit">Login</button>
            <a href="signup.php">
                <button type="button">Sign Up</button>
            </a>
        </div>
    </form>
</div>

<script>
    // Add any shared JavaScript functionality here, if needed
</script>
</body>
</html>
