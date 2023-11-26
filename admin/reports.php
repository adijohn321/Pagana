<?php
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['role'] === "Administrator") {
    include_once "../function/reports.php";
  // Redirect the user to the dashboard or desired page
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title> Pagana Kutawato Hotel </title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <i class='bx bx-grid-alt' ></i>
            <span class="links_name">Dashboard</span>
          </a>
        </li>
        <li>
          <a href="user.php">
            <i class='bx bx-user' ></i>
            <span class="links_name">Guests</span>
          </a>
        </li>
        
        <li>
          <a href="guests.php" >
            <i class='bx bx-user' ></i>
            <span class="links_name">Users</span>
          </a>
        </li>
        <li>
          <a href="room.php">
            <i class='bx bx-hotel' ></i>
            <span class="links_name">Room</span>
          </a>
        </li>
        <li>
          <a href="roomavail.php">
            <i class='bx bx-hotel' ></i>
            <span class="links_name">Room Booking</span>
          </a>
        </li>
        <li>
          <a href="amenities.php">
            <i class='bx bx-list-ul' ></i>
            <span class="links_name">Amenities</span>
          </a>
        </li>
        <li>
          <a href="reservations.php">
            <i class='bx bx-list-ul' ></i>
            <span class="links_name">Reservation</span>
          </a>
        </li>
        <li>
          <a href="reports.php" class="active">
            <i class='bx bx-list-ul' ></i>
            <span class="links_name">Reports</span>
          </a>
        </li>
        <li>
          <a href="help_desk.php">
            <i class='bx bx-message' ></i>
            <span class="links_name">Help Desk</span>
          </a>
        </li>
        <li>
          <a href="settings.php">
            <i class='bx bx-cog' ></i>
            <span class="links_name">Setting</span>
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
    </nav>

    <div class="home-content">
        <div class="overview-boxes">
            <div class="box">
                <div class="right-side">
                    <div class="box-topic" style="text-align: left;">Daily</div>
                    <div class="number">₱<?php echo number_format(getDailyIncome(), 2); ?></div>
                </div>
            </div>
            <div class="box">
                <div class="right-side">
                    <div class="box-topic">Monthly</div>
                    <div class="number">₱<?php echo number_format(getMonthlyIncome(), 2); ?></div>
                </div>
            </div>
            <div class="box">
                <div class="right-side">
                    <div class="box-topic">Yearly</div>
                    <div class="number">₱<?php echo number_format(getYearlyIncome(), 2); ?></div>
                </div>
            </div>
            <div class="box">
                <div class="right-side">
                    <div class="box-topic">Total</div>
                    <div class="number">₱<?php echo number_format(getTotalIncome(), 2); ?></div>
                </div>
            </div>
        </div>
    </div>

  </section>

  <script>
   let sidebar = document.querySelector(".sidebar");
let sidebarBtn = document.querySelector(".sidebarBtn");
sidebarBtn.onclick = function() {
  sidebar.classList.toggle("active");
  if(sidebar.classList.contains("active")){
  sidebarBtn.classList.replace("bx-menu" ,"bx-menu-alt-right");
}else
  sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
}
 </script>

</body>
</html>
<?php
exit();
}elseif (isset($_SESSION['user_id']) && $_SESSION['role'] === "Guest") {
  header("Location: ../index.php");
}
?>