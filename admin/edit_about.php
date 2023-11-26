<?php
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['role'] === "Administrator") {
  // Redirect the user to the dashboard or desired page
// Other code and validations

$id = $_GET['id'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once "../function/edit_about.php";

    $newType = $_POST["type"];
    $newDescription = $_POST["description"];

    // Call the insertRoom function
    if (updateAbout($newDescription, $newType, $id)) {
        // Room inserted successfully
        $_SESSION['success'] = "About Us has been updated.";
    } else {
        // Failed to insert room
        $_SESSION['error'] = "Failed to update About Us.";
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
          <a href="guests.php" class="active">
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
          <a href="settings.php" class="active">
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
        <span class="dashboard">Settings</span>
      </div>
    </nav>

    <div class="home-content">
        <!-- Rejected tab content here -->
        <div class="tables">
          <div style="margin-bottom: 20px;">            
            <a class="btn btn-danger" href="settings.php">Go Back</a>
          </div>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Edit About</div>
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
                        <?php
                          // Fetch rooms data from the database
                          include_once("../function/dbconnect.php");
                          $conn = dbConnect();
                          $stmt = $conn->prepare("SELECT * FROM about_us WHERE id = '$id'");
                          $stmt->execute();
                          $result = $stmt->get_result();

                          $sn = 0;
                          while ($row = $result->fetch_assoc()) {
                              $sn++;

                              $aboutDescription = $row['description'];
                              $aboutType = $row['type'];
                              
                          ?>
                            <div class="card-body">
                                <form action="" method="POST">
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea type="text" class="form-control" id="description" name="description" required><?php echo $aboutDescription; ?></textarea>
                                        <?php if (isset($_SESSION['errors']['description'])) { ?>
                                            <div id="description-error" style="color: red; font-size: 14px;"><?php echo $_SESSION['errors']['description']; ?></div>
                                        <?php } ?>
                                    </div>
                                    <div class="mb-3">
                                        <label for="type" class="form-label">Type</label>
                                        <select name="type" id="type" class="form-control" required>
                                            <option value="<?php echo $aboutType; ?>"><?php echo $aboutType; ?></option>
                                            <option value="Mission">Mission</option>
                                            <option value="Vision">Vision</option>
                                            <option value="Company">Company</option>
                                        </select>
                                        <?php if (isset($_SESSION['errors']['type'])) { ?>
                                            <div id="type-error" style="color: red; font-size: 14px;"><?php echo $_SESSION['errors']['type']; ?></div>
                                        <?php } ?>
                                    </div>
                                    <?php unset($_SESSION['errors']); ?>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Edit About</button>
                                    </div>
                                </form>
                            </div>
                            <?php
                            }
                            $stmt->close();
                            $conn->close();
                            ?>
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
            document.getElementById('description-error').style.display = 'none';
            document.getElementById('type-error').style.display = 'none';
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