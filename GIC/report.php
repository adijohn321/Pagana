<?php
session_start();

if (true) {
  include_once "../function/dashboard.php";
  // Redirect the user to the dashboard or desired page
  ?>
  <!DOCTYPE html>
  <html lang="en" dir="ltr">

  <head>
    <meta charset="UTF-8">
    <title>Pagana Kutawato Hotel</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
      .box-link {
        text-align: center;
        margin-top: 10px;
        /* Adjust this value as needed */
      }
    </style>
  </head>

  <body>
    <div class="sidebar">
      <div class="logo-details">
        <img src="../img/Pagana_logo.png" width="40" height="40" style="margin-left: 10px;" alt="">
        <span class="logo_name">Pagana Kutawato</span>
      </div>
      <ul class="nav-links">
        <li>
          <a href="dashboard.php">
            <i class='bx bx-grid-alt'></i>
            <span class="links_name">Dashboard</span>
          </a>
        </li>

        <li>
          <a href="roomavail.php">
            <i class='bx bx-hotel'></i>
            <span class="links_name">Room Booking</span>
          </a>
        </li>
        <li>
          <a href="reservations.php">
            <i class='bx bx-list-ul'></i>
            <span class="links_name">Reservation</span>
          </a>
        </li>

        <li>
          <a href="booking.php">
            <i class='bx bx-list-ul'></i>
            <span class="links_name">Booking</span>
          </a>
        </li>
        <li>
          <a href="report.php" class="active">
            <i class='bx bx-grid-alt'></i>
            <span class="links_name">Reports</span>
          </a>
        </li>
        <li class="log_out">
          <a href="logout.php">
            <i class='bx bx-log-out'></i>
            <span class="links_name">Log out</span>
          </a>
        </li>
      </ul>
    </div>
    <section class="home-section">
      <nav>
        <div class="sidebar-button">
          <i class='bx bx-menu sidebarBtn'></i>
          <span class="dashboard">Reports</span>
        </div>
        <div style="text-align: end;">
          <h3 style="margin: 0px;font-size: 1.17rem;">
            <?php
            echo $fullname;
            ?>
          </h3>
          <small>Guest In-Charge</small>
        </div>

      </nav>


      <div class="home-content">
        <div class="container mt-5">
          <h1 class="mb-4">Report Generation</h1>

          <!-- Report Selection Form -->
          <form>
            <div class="form-group">
              <label for="reportType" class="mb-2">Select Report Type:</label>
              <select class="form-select" id="reportType" name="reportType">
                <option value="sales">Sales Report</option>
                <option value="inventory">Reservations Report</option>
                <option value="expenses">Schedule Report</option>
                <!-- Add more report types as needed -->
              </select>
            </div>
            <div class="form-group">
              <label for="startDate" class="mb-2">Start Date:</label>
              <input type="date" class="form-control" id="startDate" name="startDate">
            </div>
            <div class="form-group">
              <label for="endDate" class="mb-2">End Date:</label>
              <input type="date" class="form-control" id="endDate" name="endDate">
            </div>
            <button type="submit" class="btn btn-primary">Download Report</button>
          </form>

          <!-- Replace this with your report content -->
        </div>

      </div>
    </section>


    <script>
      let sidebar = document.querySelector(".sidebar");
      let sidebarBtn = document.querySelector(".sidebarBtn");
      sidebarBtn.onclick = function () {
        sidebar.classList.toggle("active");
        if (sidebar.classList.contains("active")) {
          sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
        } else
          sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
      }
    </script>

  </body>

  </html>
  <?php
  exit();
} elseif (isset($_SESSION['user_id']) && $_SESSION['role'] === "Guest") {
  header("Location: ../index.php");
}
?>