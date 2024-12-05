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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Endless Art Gallery - Sign Up</title>
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

        .name-container {
            display: flex;                /* Use flexbox to align inputs horizontally */
            gap: 20px;                    /* Add space between the inputs */
        }

        .input-group {
            flex: 1;                      /* Allow each input group to take equal space */
            display: flex;
            flex-direction: column;       /* Keep the label above the input */
        }

        .input-group label {
            margin-bottom: 5px;           /* Add space between the label and input */
        }

        .input-group input {
            width: 100%;                  /* Ensure the input fills the container */
            box-sizing: border-box;       /* Include padding and border in the width */
        }

    </style>
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

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <div class="name-container">
            <div class="input-group">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>
            <div class="input-group">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>
        </div>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <div class="button-group">
            <button type="submit">Sign Up</button>
            <a href="login.php">
                <button type="button">Login</button>
            </a>
        </div>
    </form>
</div>
</body>
</html>
