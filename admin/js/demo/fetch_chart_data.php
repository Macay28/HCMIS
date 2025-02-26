<?php
include "../config/db.php";

// Query to fetch only 3 rows
$sql = "SELECT condition_name, count FROM conditions_stats LIMIT 3"; // Fetch 3 records
$result = mysqli_query($conn, $sql);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = [
        'label' => $row['condition_name'],
        'value' => (int)$row['count']
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
