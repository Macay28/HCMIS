<?php
include "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patientId = $_POST['PATIENT_ID'];
    $disease = $_POST['DISEASE'];

    if (empty($patientId) || empty($disease)) {
        header("Location: ../cases/index.php?id=" . urlencode($patientId));
        exit;
    }

    $sql = "INSERT INTO cases (PATIENT_ID, DISEASE) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $patientId, $disease);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: ../cases/index.php?id=" . urlencode($patientId));
        exit;
    } else {
        echo "Error " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
