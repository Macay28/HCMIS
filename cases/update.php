<?php
include "../config/db.php"; // Include your database connection

// Check if an ID is passed
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Fetch the case data to edit
    $sql = "SELECT * FROM cases WHERE ID = $id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $case = mysqli_fetch_assoc($result);
    } else {
        // Redirect to the previous page if no such case exists
        header("Location: ../cases/index.php?id=" . $case["PATIENT_ID"]);
        exit();
    }
}

// Check if the form is submitted to update the record
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get updated values
    $disease = mysqli_real_escape_string($conn, $_POST['DISEASE']);
    $id = $_POST['ID'];
    $patient_id = $_POST["PATIENT_ID"];

    // Update the case in the database
    $updateSql = "UPDATE cases SET DISEASE = '$disease' WHERE ID = $id";
    if (mysqli_query($conn, $updateSql)) {
        // Redirect to the page where the case is listed
        header("Location: ../cases/index.php?id=" . $patient_id);
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Edit Patient Case</title>
    <link href="../admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="../admin/css/style.css" rel="stylesheet">
    <link href="../admin/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">
    <div id="wrapper">
        <?php include "../admin/includes/sidebar.php"; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include "../admin/includes/header2.php"; ?>
                <div class="container-fluid">
                    <div class="mb-5 ml-1">

                    </div>
                    <div class="card shadow mb-4">
                        <div class="card border-0 shadow w-100">
                            <div class="card-header border-0 bg-success text-center text-white py-3 fs-5">
                                Edit Case
                            </div>
                            <div class="card-body">
                                <!--- try <h2>Edit Case for Patient: <?php echo htmlspecialchars($case['PATIENT_ID']); ?></h2> ---->
                                <form action="update.php" method="POST">
                                    <input type="hidden" name="ID" value="<?php echo htmlspecialchars($case['ID']); ?>">
                                    <input type="hidden" name="PATIENT_ID" value="<?php echo htmlspecialchars($case['PATIENT_ID']); ?>">

                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label for="disease" class="form-label">Disease</label>
                                            <input type="text" class="form-control" id="disease" name="DISEASE" value="<?php echo htmlspecialchars($case['DISEASE']); ?>" required>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <button type="submit" class="btn btn-success btn-sm">Save Changes</button>
                                            <a href="../cases/index.php?id=<?php echo htmlspecialchars($case['PATIENT_ID']); ?>" class="btn btn-secondary btn-sm ms-2">Cancel</a>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span id="currentDate"></span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-success" href="../Login/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div> -->



    <script>
        const now = new Date();
        const currentYear = now.getFullYear();
        document.querySelector('#currentDate').innerHTML = `Sitio Mansumbil ${currentYear}`
    </script>

    <script src="../admin/vendor/jquery/jquery.min.js"></script>
    <script src="../admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../admin/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../admin/js/sb-admin-2.js"></script>
    <script src="../admin/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../admin/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="../admin/js/demo/datatables-demo.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

    <script src="../admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>