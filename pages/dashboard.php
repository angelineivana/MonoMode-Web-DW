<?php
$servername = "localhost";
$user = "root";
$pass = "root";
$dbname = "db_ecommweb_olap";

$conn = new mysqli($servername, $user, $pass, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['year'])) {
  $selectedYear = $_GET['year'];
} else {
  // Default to the current year
  $selectedYear = date('Y');
}


$sql = 'select
YEAR(t.trans_date) AS year,
SUM(t.trans_total_price) AS revenue
FROM
transaction t
WHERE
t.status_del = "0"
GROUP BY
YEAR(trans_date)
order by 1;
    ';

$query = mysqli_query($conn, $sql);

if (!$query) {
  die('SQL Error: ' . mysqli_error($conn));
}

$data = array();
while ($row = mysqli_fetch_assoc($query)) {
  $data[] = $row;
}

// Convert the data to JSON format
$jsonData = json_encode($data);

// Pass the JSON data to JavaScript
echo "<script>var chartData = $jsonData;</script>";

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Monomode Dashboard
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css"
    href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.0.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show  bg-gray-200">
  <aside
    class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark"
    id="sidenav-main">
    <hr class="horizontal light mt-0 mb-2">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
        aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" target="_blank">
        <img src="../assets/img/logo_monomode.png" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold text-white"></span>
      </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto  max-height-vh-100" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-white active bg-gradient-primary" href="dashboard.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">dashboard</i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white " href="tables.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">table_view</i>
            </div>
            <span class="nav-link-text ms-1">TransBranch Table</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white " href="billing.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">table_view</i>
            </div>
            <span class="nav-link-text ms-1">TransBranchProd Table</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white " href="virtual-reality.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">table_view</i>
            </div>
            <span class="nav-link-text ms-1">TransProd Table</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white " href="rtl.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">table_view</i>
            </div>
            <span class="nav-link-text ms-1">TransProdCust Table</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white " href="notifications.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">table_view</i>
            </div>
            <span class="nav-link-text ms-1">Customer_Total_Purchase Table</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white " href="Table6.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">table_view</i>
            </div>
            <span class="nav-link-text ms-1">Branch_Revenue Table</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>
  <main class="main-content position-relative border-radius-lg ">
    <div class="card z-index-2 mt-3 p-3">
      <h4 class="m-2">Revenue By Year</h4>
      <!-- <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" id="yearDropdown" data-bs-toggle="dropdown"
          aria-expanded="false">
          Select Year
        </button>
        <ul class="dropdown-menu" aria-labelledby="yearDropdown">
          <li><a class="dropdown-item" href="#" onclick="changeYear(2020)">2020</a></li>
          <li><a class="dropdown-item" href="#" onclick="changeYear(2021)">2021</a></li>
          <li><a class="dropdown-item" href="#" onclick="changeYear(2022)">2022</a></li>
          <li><a class="dropdown-item" href="#" onclick="changeYear(2023)">2023</a></li>
        </ul>
      </div> -->

      <div class="card-header p-0 position-relative mx-3 z-index-2 bg-transparent">
        <div class="bg-gradient-primary p-3 shadow-primary border-radius-lg py-3 pe-1">
          <div class="chart">
            <canvas id="chart-bars" class="chart-canvas" height="400"></canvas>
          </div>
        </div>
      </div>
    </div>
  </main>
  <div class="fixed-plugin">
    <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
      <i class="material-icons py-2">settings</i>
    </a>
    <div class="card shadow-lg">
      <div class="card-header pb-0 pt-3">
        <div class="float-start">
          <p>See our dashboard options.</p>
        </div>
        <div class="float-end mt-4">
          <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
            <i class="material-icons">clear</i>
          </button>
        </div>
        <!-- End Toggle Button -->
      </div>
      <hr class="horizontal dark my-1">
      <div class="card-body pt-sm-3 pt-0">
        <!-- Sidebar Backgrounds -->
        <div>
          <h6 class="mb-0">Sidebar Colors</h6>
        </div>
        <a href="javascript:void(0)" class="switch-trigger background-color">
          <div class="badge-colors my-2 text-start">
            <span class="badge filter bg-gradient-primary active" data-color="primary"
              onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-dark" data-color="dark" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this)"></span>
          </div>
        </a>
        <!-- Sidenav Type -->
        <hr class="horizontal dark my-3">
        <div class="mt-2 d-flex">
          <h6 class="mb-0">Light / Dark</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="dark-version" onclick="darkMode(this)">
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/chartjs.min.js"></script>
  <script src="chart.js"></script>
  <script>
    function changeYear(year) {
      // Filter the chartData based on the selected year
      var filteredData = chartData.filter(item => item.year == year);

      // Prepare the data for Chart.js
      var labels = filteredData.map(item => item.branch_name + ' - ' + item.year);
      var values = filteredData.map(item => parseInt(item.revenue.replace('Rp. ', '').replace(',', '')));

      // Update the chart data and labels
      chart.data.labels = labels;
      chart.data.datasets[0].data = values;

      // Update the chart
      chart.update();
    }

    document.querySelectorAll('.dropdown-item').forEach(item => {
      item.addEventListener('click', event => {
        var selectedYear = parseInt(event.target.innerText);
        changeYear(selectedYear);
      });
    });


    var jsonData = <?php echo $jsonData; ?>;

    // Prepare the data for Chart.js
    var labels = jsonData.map(item => item.year);
    var values = jsonData.map(item => parseInt(item.revenue.replace('Rp. ', '').replace(',', '')));

    // Create the chart using Chart.js
    var ctx = document.getElementById("chart-bars").getContext("2d");
    var chart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: '',
          tension: 0.4,
          borderWidth: 0,
          borderRadius: 4,
          borderSkipped: false,
          backgroundColor: 'rgba(255, 255, 255, .8)',
          data: values
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          x: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5],
              color: 'rgba(255, 255, 255, .2)'
            },
            ticks: {
              display: true,
              color: '#ffffff',
              padding: 10,
              font: {
                color: 'rgba(255, 255, 255, .2)',
                size: 14,
                weight: 300,
                family: "Roboto",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5],
              color: 'rgba(255, 255, 255, .2)'
            },
            beginAtZero: true,
            ticks: {
              color: '#ffffff',
              callback: function (value) {
                if (value !== undefined) {
                  return 'Rp. ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }
                return '';
              }
            }
          }
        },
        tooltip: {
          callbacks: {
            label: function (context) {
              return '';
            }
          }
        },
        plugins: {
          legend: {
            display: false // Remove the legend
          }
        }
      }
    });
  </script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/material-dashboard.min.js?v=3.0.0"></script>
</body>

</html>