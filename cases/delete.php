<?php
include "../config/db.php";  // Include your database connection

// Check if ID is provided in the URL
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Delete the case from the database
    $sql = "DELETE FROM cases WHERE ID = $id";

    if (mysqli_query($conn, $sql)) {
        // Success message, stay on the same page
        header("Location: ./index.php?id=$id");
    } else {
        // Error message, stay on the same page
        header("Location: ./index.php?error=" . urlencode("Error deleting record: " . mysqli_error($conn)));
    }
} else {
    // No ID provided
    header("Location: ./index.php?error=" . urlencode("No ID provided to delete."));
}

mysqli_close($conn);
