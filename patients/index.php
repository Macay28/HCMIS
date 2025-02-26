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

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Patients</title>
  <link href="../admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
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
          <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
              <h6 class="m-0 font-weight-bold text-success">PATIENT INFORMATION</h6>
              <a href="../patients/create.php" class="btn btn-success btn-sm text-uppercase" style="width: 100px;"> <i class="fas fa-plus"></i> Add</a>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th hidden>ID</th>
                      <th>Name</th>
                      <th>Gender</th>
                      <th>Phone Number</th>
                      <th>Address</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php

                    $sql = "SELECT * FROM patients";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                      echo '<tr>';
                      echo '<th scope="row" hidden>' . htmlspecialchars($row['ID']) . '</th>';
                      echo '<td>' . htmlspecialchars($row['NAME']) . '</td>';
                      echo '<td>' . htmlspecialchars($row['GENDER']) . '</td>';
                      echo '<td>' . htmlspecialchars($row['PHONE_NUMBER']) . '</td>';
                      echo '<td>' . htmlspecialchars($row['ADDRESS']) . '</td>';
                      echo '<td>';
                      echo '<div class="dropdown">';
                      echo '<button class="btn btn-success btn-sm dropdown-toggle" type="button" id="actionsDropdown' . $row['ID'] . '" data-bs-toggle="dropdown" aria-expanded="false">';
                      echo 'Actions';
                      echo '</button>';
                      echo '<ul class="dropdown-menu" aria-labelledby="actionsDropdown' . $row['ID'] . '">';
                      echo '<li><a class="dropdown-item text-success" href="../cases/index.php?id=' . htmlspecialchars($row['ID']) . '">View</a></li>';
                      echo '<li><a class="dropdown-item text-primary" href="../patients/update.php?id=' . htmlspecialchars($row['ID']) . '">Edit</a></li>';
                      echo '<li><a class="dropdown-item text-danger" href="../patients/delete.php?id=' . htmlspecialchars($row['ID']) . '" onclick="return confirm(\'Are you sure you want to delete this record?\')">Remove</a></li>';
                      echo '</ul>';
                      echo '</div>';
                      echo '</td>';
                      echo '</tr>';
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>

          </div>
        </div>
      </div>
      <footer class="sticky-footer bg-gray">
        <div class="container my-auto">
          <div class="Sitio Mansumbil text-center my-auto">
            <span id="currentDate"></span>
          </div>
        </div>
      </footer>
    </div>
  </div>

  <a class="scroll-to-top rounded btn-success" href="#page-top">
    <i class="fas fa-angle-up text-success"></i>
  </a>

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
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var dropdowns = document.querySelectorAll('.dropdown-toggle');
      dropdowns.forEach(function(dropdown) {
        new bootstrap.Dropdown(dropdown);
      });
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>