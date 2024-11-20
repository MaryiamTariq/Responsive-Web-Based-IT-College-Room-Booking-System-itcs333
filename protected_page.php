<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must log in to access this page.";
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Protected Page</title>
</head>
<body>
    <h1>Welcome to the Protected Page!</h1>
    <p>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>. You have successfully logged in.</p>
    <a href="logout.php">Logout</a>
</body>
</html>
