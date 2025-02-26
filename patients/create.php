<?php
include "../config/db.php";

// Disable caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['NAME'];
    $gender = $_POST['GENDER'];
    $phoneNumber = $_POST['PHONE_NUMBER'];
    $address = $_POST['ADDRESS'];

    if (empty($name) || empty($gender) || empty($phoneNumber) || empty($address)) {
        header('Location: ./create.php');
        exit;
    }

    $sql = "INSERT INTO patients (NAME, GENDER, PHONE_NUMBER, ADDRESS) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $name, $gender, $phoneNumber, $address);

    if ($stmt->execute()) {
        $name = "";
        $gender = "";
        $phoneNumber = "";
        $address = "";
        header("Location: ./index.php");
    } else {
        echo "Error " . $conn->error;
    }

    $stmt->close();
    $conn->close();
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
    <title>New Patient</title>
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
                    <div class="mb-3 ml-1">
                        <a class="btn btn-success btn-sm" href="../patients/index.php"><i class="fas fa-arrow-alt-circle-left mr-2"></i>Back</a>
                    </div>
                    <div class="card shadow mb-4">
                        <main class="container mt-3 min-vh=100 d-flex justify-content-center">

                            <div class="card border-0 shadow w-75">
                                <div class="card-header border-0 bg-success text-center text-white py-3 fs-5">
                                    New Patient
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <label for="name" class="form-label">Name</label>
                                                <input type="text" class="form-control border-2" id="NAME" name="NAME">
                                            </div>
                                            <div class="col-6 mb-3">
                                                <label for="gender" class="form-label">Gender</label>
                                                <select class="form-control border-2" id="GENDER" name="GENDER">
                                                    <option selected disabled>Select Gender</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                </select>
                                            </div>
                                            <div class="col-6 mb-3">
                                                <label for="phoneNumber" class="form-label">Phone Number</label>
                                                <input type="tel" class="form-control border-2" id="PHONE_NUMBER" name="PHONE_NUMBER">
                                            </div>
                                            <div class="col-12 mb-3">
                                                <label for="address" class="form-label">Address</label>
                                                <textarea type="text" class="form-control border-2" id="ADDRESS" name="ADDRESS" rows="3"></textarea>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <button type="submit" class="btn btn-success w-100">Submit</button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>


                        </main>

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



    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">New Patient Case</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="./create.php" method="POST">
                        <input type="hidden" id="patient_id" name="PATIENT_ID" value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>">
                        <div class="mb-3">
                            <label for="disease" class="form-label">Disease</label>
                            <input type="text" class="form-control" id="disease" name="DISEASE">
                        </div>
                        <div class="d-flex justify-content-end align-items-center gap-2">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>


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

</body>

</html>