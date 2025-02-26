<?php
require_once "../config/db.php";

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header('Location: ../Login/login.php');
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "patient_record_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get selected month (default to current month)
$selectedMonth = isset($_GET['month']) ? $_GET['month'] : date('Y-m');

// Query to get the top 3 diseases in the selected month
$query = "
    SELECT disease, COUNT(*) AS case_count 
    FROM cases 
    WHERE DATE_FORMAT(date_created, '%Y-%m') = ? 
    GROUP BY disease 
    ORDER BY case_count DESC 
    LIMIT 3";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $selectedMonth);
$stmt->execute();
$result = $stmt->get_result();

// Store top 3 diseases and their case counts
$topDiseases = [];
$labels = [];
$data = [];
$colors = ['#FF0000', '#008000', '#0000FF'];

while ($row = $result->fetch_assoc()) {
    $topDiseases[] = $row;
    $labels[] = $row['disease'];
    $data[] = $row['case_count'];
}

// Ensure we always have 3 entries
while (count($labels) < 3) {
    $labels[] = "N/A";
    $data[] = 0;
}

$query = "
    SELECT disease, COUNT(*) AS case_count 
    FROM cases 
    WHERE DATE_FORMAT(date_created, '%Y-%m') = ? 
    GROUP BY disease 
    ORDER BY case_count DESC 
    LIMIT 3";

$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param("s", $selectedMonth);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    die("Query Preparation Failed: " . $conn->error);
}

// Fetch results and check if empty
$topDiseases = [];
while ($row = $result->fetch_assoc()) {
    $topDiseases[] = $row;
}

// if (empty($topDiseases)) {
//     echo "<p style='color:red;'>No data found for this month ($selectedMonth).</p>";
// }

// Initialize disease count
$count_disease = [
    'FEVER' => 0,
    'COLD' => 0,
    'COUGH' => 0,
    'DENGUE' => 0,
    'ASTHMA' => 0,
    'TUBERCULOSIS' => 0,
    'PNEUMONIA' => 0,
    'INFLUENZA' => 0,
    'RABIES' => 0,
    'LEPTOSPIROSIS' => 0,
    'CHOLERA' => 0,
    'DIABETES' => 0,
    'STROKE' => 0,
    'MALARIA' => 0,
    'CANCER' => 0,
    'DIARRHEA' => 0,
    'CHIKUNGUNYA' => 0,
    'HEPATITIS' => 0,
    'LEPROSY' => 0,
    'SCHISTOSOMIASIS' => 0,
    'YELLOW FEVER' => 0,
    'IRISH POTATO BLIGHT' => 0,
];

$maxDisease = "";
$minDisease = "";
$maxCases = 0;
$minCases = PHP_INT_MAX;

// Fetch disease data for the selected month
$query = "SELECT DISEASE, COUNT(*) AS TOTAL_DISEASE 
          FROM cases 
          WHERE DATE_FORMAT(DATE_CREATED, '%Y-%m') = ? 
          GROUP BY DISEASE";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $selectedMonth);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $disease = strtoupper($row['DISEASE']);
        $count = $row['TOTAL_DISEASE'];

        $count_disease[$disease] = $count;

        // Identify rampant (most cases) and low (least cases)
        if ($count > $maxCases) {
            $maxCases = $count;
            $maxDisease = $disease;
        }
        if ($count < $minCases) {
            $minCases = $count;
            $minDisease = $disease;
        }
    }
} else {
    echo "Error fetching data: " . mysqli_error($conn);
}

// Handle cases where there are no records for the selected month
if ($maxCases == 0) {
    $maxDisease = "None";
}
if ($minCases == PHP_INT_MAX) {
    $minDisease = "None";
}

// Fetch total cases per month
$monthlyCases = array_fill(0, 12, 0); // Default 0 for all months

$query = "SELECT MONTH(DATE_CREATED) AS month, COUNT(*) AS total_cases 
          FROM cases 
          GROUP BY MONTH(DATE_CREATED) 
          ORDER BY MONTH(DATE_CREATED)";

$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $monthIndex = (int)$row['month'] - 1; // Adjust index (0 for January, 11 for December)
        $monthlyCases[$monthIndex] = (int)$row['total_cases'];
    }
}

// Define colors for top 3 rankings
$colors = ['red', 'green', 'blue'];
$stmt->close();
$conn->close();

?>
<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="../admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../admin/css/style.css" rel="stylesheet">
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        .container_bar {
            max-width: auto;
            margin: 0 auto;
            padding: 20px;
            height: 60.6vh;
            /* Full viewport height */
            display: flex;
            flex-direction: column;
        }

        #myBarChart {
            flex-grow: 1;
            max-height: 72%;
            /* Limit chart height */
            margin-bottom: 20px;
        }

        .input-group {
            display: flex;
            justify-content: center;
            gap: 10px;

            border-radius: 5px;
            flex-shrink: 0;
        }

        .input-group select,
        .input-group input,
        .input-group button {
            padding: 8px;
            font-size: 16px;
        }

        .input-group button {
            background-color: green;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .input-group button:hover {
            background-color: white;
            color: green;
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include "../admin/includes/sidebar.php"; ?>
        <?php include "../admin/includes/scripts.php"; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include "../admin/includes/header.php"; ?>

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-12 col-lg-7">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-success">TOP THREE RAMPANT DISEASES</h6>
                                </div>
                                <div class="card-body">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <?php foreach ($topDiseases as $index => $disease) : ?>
                                                <div class="col-xl-4 col-md-6 mb-4">
                                                    <div class="card shadow h-100 py-2" style="border-left: 5px solid <?php echo $colors[$index]; ?>;">
                                                        <div class="card-body">
                                                            <div class="row no-gutters align-items-center">
                                                                <div class="col mr-2">
                                                                    <div class="text-s font-weight-bold text-uppercase mb-1" style="color: <?php echo $colors[$index]; ?>;">
                                                                        <?php echo strtoupper($disease['disease']); ?>
                                                                    </div>
                                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                                        <?php echo $disease['case_count']; ?>
                                                                    </div>
                                                                </div>
                                                                <div class="col-auto">
                                                                    <i class="fas fa-exclamation-circle fa-2x" style="color: <?php echo $colors[$index]; ?>;"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>

                                        <!-- <div class="row">
                                            <div class="col-xl-12 col-lg-7">
                                                <form method="GET">
                                                    <label for="month">Select Month:</label>
                                                    <input type="month" id="month" name="month" value="<?php echo $selectedMonth; ?>" class="form-control">
                                                    <br>
                                                    <button type="submit" class="btn btn-success">Check Cases</button>
                                                </form>
                                            </div>
                                        </div> -->
                                        <!-- <div class="row">
                                            <div class="col-xl-4 col-md-6 mb-4">
                                                <div class="card border-left-danger shadow h-100 py-2">
                                                    <div class="card-body">
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col mr-2">
                                                                <div class="text-s font-weight-bold text-danger text-uppercase mb-1">FEVER
                                                                </div>
                                                                <div class="row no-gutters align-items-center">
                                                                    <div class="col-auto">
                                                                        <div class="h5 mb-0 mr-3 font-weight-bold text-800">
                                                                            <?php echo $count_disease['FEVER']; ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <i class="fas fa-procedures fa-2x text-300"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-md-6 mb-4">
                                                <div class="card border-left-success shadow h-100 py-2">
                                                    <div class="card-body">
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col mr-2">
                                                                <div class="text-s font-weight-bold text-success text-uppercase mb-1">COLD
                                                                </div>
                                                                <div class="row no-gutters align-items-center">
                                                                    <div class="col-auto">
                                                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                                            <?php echo $count_disease['COLD']; ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <i class="fas fa-viruses fa-2x text-300"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-md-6 mb-4">
                                                <div class="card border-left-primary shadow h-100 py-2">
                                                    <div class="card-body">
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col mr-2">
                                                                <div class="text-s font-weight-bold text-primary text-uppercase mb-1">COUGH
                                                                </div>
                                                                <div class="row no-gutters align-items-center">
                                                                    <div class="col-auto">
                                                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                                            <?php echo $count_disease['COUGH']; ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <i class="fas fa-head-side-cough fa-2x text-300"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-8 col-lg-7">
                                    <div class="card shadow mb-4">
                                        <div class="card-header py-3">
                                            <h6 class="m-0 font-weight-bold text-success">CASES IN EVERY MONTH</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="container_bar">
                                                <canvas id="myBarChart"></canvas>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-7">
                                    <div class="card shadow mb-4">
                                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                            <h6 class="m-0 font-weight-bold text-success">PIE CASES</h6>
                                            <div class="dropdown no-arrow">

                                            </div>
                                        </div>
                                        <!-- Pie Cases -->
                                        <div class="card-body">

                                            <div class="chart-pie pt-4 pb-2">
                                                <canvas id="myPieChart"></canvas>
                                            </div>
                                            <br><br><br><br><br>

                                        </div>


                                    </div>
                                </div>
                                <!-- RAMPANT & FREQUENT -->

                                <div class="col-xl-12 col-lg-7">
                                    <div class="card shadow mb-4">
                                        <div class="card-header py-3">
                                            <h6 class="m-0 font-weight-bold text-success">RAMPANT & FREQUENT DISEASES</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="container_bar">
                                                <div class="row mt-4">
                                                    <!-- Most Rampant Disease (Gold) -->
                                                    <div class="col-xl-6 col-md-6 mb-4">
                                                        <div class="card border-left-warning shadow h-100 py-2" style="border-left-color: #FFFF00 !important;">
                                                            <div class="card-body">
                                                                <div class="row no-gutters align-items-center">
                                                                    <div class="col mr-2">
                                                                        <div class="text-s font-weight-bold text-uppercase mb-1" style="color: #FFFF00;">
                                                                            Most Rampant Disease (<?php echo $selectedMonth; ?>)
                                                                        </div>
                                                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                                            <?php echo $maxDisease . " (" . $maxCases . ")"; ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <i class="fas fa-exclamation-triangle fa-2x" style="color: #FFFF00;"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Least Frequent Disease (Bronze) -->
                                                    <div class="col-xl-6 col-md-6 mb-4">
                                                        <div class="card border-left-info shadow h-100 py-2" style="border-left-color: #B5651D !important;">
                                                            <div class="card-body">
                                                                <div class="row no-gutters align-items-center">
                                                                    <div class="col mr-2">
                                                                        <div class="text-s font-weight-bold text-uppercase mb-1" style="color: #B5651D;">
                                                                            Least Frequent Disease (<?php echo $selectedMonth; ?>)
                                                                        </div>
                                                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                                            <?php echo $minDisease . " (" . $minCases . ")"; ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <i class="fas fa-info-circle fa-2x" style="color: #B5651D;"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Month Selection Form -->
                                                    <div class="row">
                                                        <div class="col-xl-12 col-lg-7">
                                                            <form method="GET">
                                                                <label for="month">Select Month:</label>
                                                                <input type="month" id="month" name="month" value="<?php echo $selectedMonth; ?>" class="form-control">
                                                                <br>
                                                                <button type="submit" class="btn btn-success">Check Cases</button>
                                                            </form>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                    <footer class="sticky-footer bg-gray">
                        <div class="container my-auto">
                            <div class="copyright text-center my-auto">
                                <span id="currentDate"></span>
                            </div>
                        </div>
                    </footer>
                </div>
            </div>

            <a class=" scroll-to-top rounded btn-success" href="#page-top">
                <i class="fas fa-angle-up text-success"></i>
            </a>
            <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to Logout?</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <!-- <div class="modal-body">Are you sure you want to Logout?</div> -->
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                            <a class="btn btn-success" href="../Login/logout.php">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                const now = new Date();
                const currentYear = now.getFullYear();
                document.querySelector('#currentDate').innerHTML = `Sitio Mansumbil ${currentYear}`
            </script>
            <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
            <script src="vendor/jquery/jquery.min.js"></script>
            <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
            <script src="js/sb-admin-2.min.js"></script>
            <script src="vendor/chart.js/Chart.min.js"></script>
            <script src="./js/demo/chart-bar-demo.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var dropdowns = document.querySelectorAll('.dropdown-toggle');
                    dropdowns.forEach(function(dropdown) {
                        new bootstrap.Dropdown(dropdown);
                    });
                });
            </script>

            <!-- Pie Cases -->
            <script>
                var ctx = document.getElementById("myPieChart").getContext('2d');
                var myPieChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: <?php echo json_encode($labels); ?>,
                        datasets: [{
                            data: <?php echo json_encode($data); ?>,
                            backgroundColor: ['#FF0000', '#008000', '#0000FF'],
                            hoverBackgroundColor: ['#FFFF00', '#00FF00', '#00FFFF'],
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        cutoutPercentage: 80, // Adjust this for size control
                        plugins: {
                            tooltip: {
                                backgroundColor: "white",
                                bodyFontColor: "black",
                                borderColor: 'black',
                                borderWidth: 1,
                                displayColors: false,
                                caretPadding: 10,
                            },
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        },
                    }
                });
            </script>

            <!-- Bar Graph -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctx = document.getElementById("myBarChart").getContext('2d');

                    // Load data from PHP
                    const monthlyCases = <?php echo json_encode($monthlyCases); ?>;

                    // Bar chart data
                    const data = {
                        labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                        datasets: [{
                            label: "Total Cases",
                            backgroundColor: "#008000",
                            hoverBackgroundColor: "#00FF00",
                            borderColor: "#000000",
                            data: monthlyCases,
                        }],
                    };

                    // Bar chart options
                    const options = {
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                left: 10,
                                right: 25,
                                top: 25,
                                bottom: 10
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false
                                },
                                ticks: {
                                    maxTicksLimit: 6
                                },
                            },
                            y: {
                                ticks: {
                                    beginAtZero: true,
                                    maxTicksLimit: 5,
                                    padding: 10,
                                    callback: function(value) {
                                        return value.toLocaleString();
                                    }
                                },
                                grid: {
                                    color: "#C0C0C0",
                                    zeroLineColor: "rgb(8, 255, 61)",
                                    borderDash: [2],
                                    zeroLineBorderDash: [2]
                                }
                            },
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: "black",
                                bodyFontColor: "black",
                                borderColor: 'black',
                                borderWidth: 1,
                                displayColors: false,
                                caretPadding: 10,
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return tooltipItem.dataset.label + ': ' + tooltipItem.raw.toLocaleString();
                                    }
                                }
                            }
                        }
                    };

                    // Initialize Chart.js Bar Chart
                    new Chart(ctx, {
                        type: 'bar',
                        data: data,
                        options: options
                    });

                });
            </script>



            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>