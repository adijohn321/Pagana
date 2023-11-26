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
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <style>
      .box-link {
        text-align: center;
        margin-top: 10px; /* Adjust this value as needed */
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
          <a href="dashboard.php" class="active">
            <i class='bx bx-grid-alt' ></i>
            <span class="links_name">Dashboard</span>
          </a>
        </li>
        
        <li>
          <a href="roomavail.php">
            <i class='bx bx-hotel' ></i>
            <span class="links_name">Room Booking</span>
          </a>
        </li>
        <li>
          <a href="reservations.php">
            <i class='bx bx-list-ul' ></i>
            <span class="links_name">Reservation</span>
          </a>
        </li>
        
        <li>
          <a href="booking.php">
            <i class='bx bx-list-ul' ></i>
            <span class="links_name">Booking</span>
          </a>
        </li>
        
        <li>
          <a href="report.php" class="">
            <i class='bx bx-grid-alt'></i>
            <span class="links_name">Reports</span>
          </a>
        </li>
        
        <li>
          <a href="checkout.php" class="">
            <i class='bx bx-grid-alt'></i>
            <span class="links_name">Check Out</span>
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
        <span class="dashboard">Dashboard</span>
      </div>
      <div style="text-align: end;">
        <h3>
        <?php
        echo $fullname;
        ?>
        </h3>
        <small>Current User</small>
      </div>
      
    </nav>
    

    <div class="home-content">
      <div class="overview-boxes">
        <div class="box" style = "width: calc(100% / 2 - 15px);">
          <div class="right-side">
            <div class="box-topic">Vacant Rooms</div>
            <div class="number"><?php echo $roomCount; ?></div>
            <div class="box-link">
              <a href="room.php">View Available Rooms</a>
            </div>
          </div>
        </div>
        <div class="box" style = "width: calc(100% / 2 - 15px);">
          <div class="right-side">
            <div class="box-topic">Reservation</div>
            <div class="number"><?php echo $reservationCount; ?></div>
            <div class="box-link">
              <a href="reservations.php">View Reservation</a>
            </div>
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