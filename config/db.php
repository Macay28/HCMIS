<?php
session_start();
$localhost = "localhost";
$username = "root";
$password = "";
$db = "patient_record_db";

$conn = mysqli_connect($localhost, $username, $password, $db);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
