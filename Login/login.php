<?php

require_once "../config/db.php";

if (isset($_SESSION["username"])) {
    // Redirect to the dashboard or homepage if logged in
    header("Location: ../admin/index.php");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Simulate authentication (replace with actual logic)
    $username = $_POST['USERNAME'];
    $password = $_POST['PASSWORD'];

    // Example: check against hardcoded values (replace with database check)
    $sql = "SELECT * FROM users WHERE USERNAME = '$username' AND PASSWORD = '$password'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $_SESSION["username"] = $username;
        header("Location: index.php");
        exit;
    } else {
        // Authentication failed
        header("Location: login.php");
        echo "Invalid username or password. Please try again.";
    }
}
?>
<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <div class="wrapper">
        <form action="./auth.php" method="POST">
            <h2>Login</h2>
            <div class="input-field">
                <input type="text" id="username" name="USERNAME" value="admin" required>
                <label>Enter Username</label>
            </div>
            <div class="input-field">
                <input type="password" id="password" name="PASSWORD" value="admin" required>
                <label>Enter Password</label>
                <span toggle="#password" class="show-hide-toggle"></span>
            </div>
            <button type="submit">Log In</button>
        </form>
    </div>
    <script src="script.js"></script>
</body>

</html>