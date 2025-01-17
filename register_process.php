<?php
session_start();

// Database configuration
$dsn = "mysql:host=localhost;dbname=cs333-project";
$dbUsername = "root"; // Replace with your DB username
$dbPassword = "";     // Replace with your DB password

try {
    // Create a PDO connection
    $pdo = new PDO($dsn, $dbUsername, $dbPassword);

    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if form data is submitted
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Retrieve and sanitize form data
        $fName = htmlspecialchars(trim($_POST['fName']));
        $lName = htmlspecialchars(trim($_POST['lName']));
        $username = htmlspecialchars(trim($_POST['username']));
        $email = htmlspecialchars(trim($_POST['email']));
        $password = $_POST['password'];

        // Validate that none of the fields are empty
        if (empty($fName) || empty($lName) || empty($username) || empty($email) || empty($password)) {
            die("All fields are required.");
        }

        // Email validation
        if (!preg_match("/^[a-zA-Z]+@uob\.edu\.bh$/", $email)) {
            $_SESSION['error'] = "Please use a valid UOB email address (example: wahmed@uob.edu.bh)";
            header("Location: register.php");
            exit();
        }

        // Check if email exists
        $stmt = $pdo->prepare("SELECT email FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "Email already registered.";
            header("Location: register.php");
            exit();
        }
        
        // Hash the password for secure storage
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert data into the users table
        $sql = "INSERT INTO users (fName, lName, username, email, password) VALUES (:fName, :lName, :username, :email, :password)";
        $stmt = $pdo->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':fName', $fName);
        $stmt->bindParam(':lName', $lName);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute the statement
        if ($stmt->execute()) {
            // echo "Registration successful!";
            $_SESSION['success'] = "Registration successful! You can now login.";
            header("Location: login.php"); // Redirect to login page
            exit();
        } else {
            //echo "Error: Registration failed.";
            $_SESSION['error'] = "Registration failed. Please try again.";
            header("Location: register.php");
            exit();
        }
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>