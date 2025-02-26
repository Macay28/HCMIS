<?php
include '../config/db.php';

// Start session management
session_start();

// Retrieve username and password from POST data
$username = $_POST['USERNAME'];
$password = $_POST['PASSWORD'];

// Sanitize input to prevent SQL injection
$username = mysqli_real_escape_string($conn, $username);
$password = mysqli_real_escape_string($conn, $password);

// Query to check if the username and password match
$sql = "SELECT * FROM users WHERE USERNAME = '$username' AND PASSWORD = '$password'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$count = mysqli_num_rows($result);

if ($count == 1) {
    // Authentication successful, set session variables
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $username; // Optionally store username in session

    // Redirect to the dashboard or any authenticated page
    header("Location: ../admin/index.php");
} else {
    // Authentication failed, redirect back to login page
    header("Location: ./login.php");
}

// Close database connection
mysqli_close($conn);
