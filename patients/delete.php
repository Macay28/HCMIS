<?php
// Include database connection
include "../config/db.php";

// Check if ID is provided in the query string
if (isset($_GET['id'])) {
    // Sanitize the ID to prevent SQL injection
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Delete data from database
    $sql = "DELETE FROM patients WHERE ID = $id";

    if (mysqli_query($conn, $sql)) {
        echo "Record deleted successfully";
        // Redirect to a success page or back to the list page
        header("Location: ./index.php");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    echo "ID not provided";
}
