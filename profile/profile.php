<?php
require_once "../config/db.php";
// Disable caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['username'])) {
    header('Location: ../Login/login.php');
    exit();
}
?>
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile</title>
    <!---Custom Css File!--->
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <section class="main">
        <div class="profile-card">
            <div class="image">
                <img src="../profile/images/admin logo.png" alt="" class="profile-pic">
            </div>
            <div class="data">
                <h2>Health Center</h2>
                <span>Kabankalan City</span>
            </div>
            <div class="row">
                <div class="info">
                    <h3>Cellphone No.</h3>
                    <span>+6399952480461</span>
                </div>
                <div class="info">
                    <h3>Location</h3>
                    <span>Brgy.Tagukon</span>
                </div>

            </div>
            <div class="buttons">
                <a href="../admin/index.php" class="btn">Back</a>
            </div>
        </div>
    </section>
</body>

</html>