<?php
session_start();
require_once 'config.php';


// If already logged in and has admin rights, redirect to dashboard
if(isset($_SESSION['user']) && $_SESSION['user']['security_level'] > 0) {
    header('Location: admin_dashboard.php');
    exit;
}

// Handle login form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = ? AND security_level > 0";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        header('Location: admin_dashboard.php');
        exit;
    } else {
        $error = "Invalid credentials or insufficient permissions";
    }
}
?>

    <!DOCTYPE html>
    <html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body class="login-page">
<div class="login-container">
    <h1>Admin Login</h1>
    <?php if(isset($error)): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" class="login-form">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>
</body>
    </html>
<?php
