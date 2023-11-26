<?php
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['role'] === "Administrator") {
  // Redirect the user to the dashboard or desired page
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once "../function/add_amenities.php";

    $name = $_POST["name"];
    $rate = $_POST["rate"];

    // Call the insertRoom function
    if (insertAmenities($name, $rate)) {
        // Room inserted successfully
        $_SESSION['success'] = "Amenities added successfully.";
    } else {
        // Failed to insert room
        $_SESSION['error'] = "Failed to add amenities.";
    }

    // Redirect or show appropriate messages
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title>Pagana Kutawato Hotel</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap4.min.css" rel="stylesheet"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        .card-header {
            font-size: 20px;
            font-weight: 800;
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
          <a href="amenities.php" class="active">
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
          <a href="reports.php">
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
        <span class="dashboard">Amenities</span>
      </div>
    </nav>

    <div class="home-content">
        <!-- Rejected tab content here -->
        <div class="tables">
          <div style="margin-bottom: 20px;">            
            <a class="btn btn-danger" href="amenities.php">Go Back</a>
          </div>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Add Amenities</div>
                        <?php if (isset($_SESSION['success'])) { ?>
                            <div class="alert alert-success"><?php echo $_SESSION['success']; ?></div>
                            <?php
                            unset($_SESSION['success']);
                            }
                        ?>
                        <?php if (isset($_SESSION['error'])) { ?>
                            <div class="alert alert-danger"><?php echo $_SESSION['error']; ?></div>
                            <?php
                            unset($_SESSION['error']);
                            }
                        ?>
                            <div class="card-body">
                                <form action="add_amenities.php" method="POST" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                        <?php if (isset($_SESSION['errors']['name'])) { ?>
                                            <div id="name-error" style="color: red; font-size: 14px;"><?php echo $_SESSION['errors']['name']; ?></div>
                                        <?php } ?>
                                    </div>
                                    <div class="mb-3">
                                        <label for="rate" class="form-label">Rate</label>
                                        <input type="text" class="form-control" id="rate" name="rate" required>
                                        <?php if (isset($_SESSION['errors']['rate'])) { ?>
                                            <div id="rate-error" style="color: red; font-size: 14px;"><?php echo $_SESSION['errors']['rate']; ?></div>
                                        <?php } ?>
                                    </div>
                                    <?php unset($_SESSION['errors']); ?>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Add Amenities</button>
                                    </div>
                                </form>
                            </div>
                        </div>
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
 <script>
    // Function to remove error messages after 10 seconds
    function removeErrors() {
        setTimeout(function() {
            document.getElementById('name-error').style.display = 'none';
            document.getElementById('rate-error').style.display = 'none';
        }, 10000);
    }

    removeErrors();
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap4.min.js"></script>
</body>
</html>
<?php
exit();
}elseif (isset($_SESSION['user_id']) && $_SESSION['role'] === "Guest") {
  header("Location: ../index.php");
}
?>