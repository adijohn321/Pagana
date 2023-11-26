<?php
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['role'] === "Administrator") {
  // Redirect the user to the dashboard or desired page
$id = $_GET['id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  include_once "../function/edit_room.php";

  // Update room information
  $newName = $_POST['name'];
  $newType = $_POST['type'];
  $newDescription = $_POST['description'];
  $newRate = $_POST['rate'];

    if (updateRoom($newName, $newType, $newDescription, $newRate, $id)) {
      // Room inserted successfully
      $_SESSION['success'] = "Room updated successfully.";
  } else {
      // Failed to insert room
      $_SESSION['error'] = "Failed to update room.";
  }
}
$id = $_GET['id'];
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
        .img-fluid {
            height: -300%;
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
          <a href="room.php" class="active">
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
        <span class="dashboard">Room</span>
      </div>
    </nav>

    <div class="home-content">
        <!-- Rejected tab content here -->
        <div class="tables">
          <div style="margin-bottom: 20px;">            
            <a class="btn btn-danger" href="room.php">Go Back</a>
          </div>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Edit Room</div>
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
                          $stmt = $conn->prepare("SELECT * FROM room WHERE id = '$id'");
                          $stmt->execute();
                          $result = $stmt->get_result();

                          $sn = 0;
                          while ($row = $result->fetch_assoc()) {
                              $sn++;

                              $roomId = $row['id'];
                              $roomImage = $row['image'];
                              $roomName = $row['name'];
                              $roomType = $row['type'];
                              $roomDescription = $row['description'];
                              $roomRate = $row['rate'];
                              
                          ?>
                            <div class="card-body">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <img src="../uploads/<?php echo $roomImage; ?>" class="img-fluid" style="max-height: 300px; width:1000px">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" required value="<?php echo $roomName; ?>">
                                        <?php if (isset($_SESSION['errors']['name'])) { ?>
                                            <div id="name-error" style="color: red; font-size: 14px;"><?php echo $_SESSION['errors']['name']; ?></div>
                                        <?php } ?>
                                    </div>
                                    <div class="mb-3">
                                        <label for="type" class="form-label">Type</label>
                                        <select type="text" class="form-control" id="type" name="type"  required>
                                      
                                          <option value="Single" <?php echo ($roomType=="Single"?"selected":""); ?> >Single</option>
                                          <option value="Double" <?php echo ($roomType!="Single"?"Selected":""); ?>>Double</option>
                                      </select>
                                        
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea type="text" class="form-control" id="description" name="description" required><?php echo $roomDescription; ?></textarea>
                                        <?php if (isset($_SESSION['errors']['description'])) { ?>
                                            <div id="description-error" style="color: red; font-size: 14px;"><?php echo $_SESSION['errors']['description']; ?></div>
                                        <?php } ?>
                                    </div>
                                    <div class="mb-3">
                                        <label for="rate" class="form-label">Rate</label>
                                        <input type="text" class="form-control" id="rate" name="rate" required value="<?php echo $roomRate; ?>">
                                        <?php if (isset($_SESSION['errors']['rate'])) { ?>
                                            <div id="rate-error" style="color: red; font-size: 14px;"><?php echo $_SESSION['errors']['rate']; ?></div>
                                        <?php } ?>
                                    </div>
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Main Image</label>
                                        <input type="file" class="form-control" id="image" name="image">
                                        <?php if (isset($_SESSION['errors']['image'])) { ?>
                                            <div id="image-error" style="color: red; font-size: 14px;"><?php echo $_SESSION['errors']['image']; ?></div>
                                        <?php } ?>
                                    </div>
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Image</label>
                                        <input type="file" class="form-control" id="images" name="images[]" multiple>
                                        <?php if (isset($_SESSION['errors']['images'])) { ?>
                                            <div id="images-error" style="color: red; font-size: 14px;"><?php echo $_SESSION['errors']['images']; ?></div>
                                        <?php } ?>
                                    </div>
                                    <?php unset($_SESSION['errors']); ?>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Update Room</button>
                                        <!-- <button type="button" class="btn btn-warning">Add Amenities</button> -->
                                        <!-- <a href="outoforder.php?id=<?php echo$id?>" class="btn btn-secondary">Out of order</a> -->
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
            document.getElementById('name-error').style.display = 'none';
            document.getElementById('description-error').style.display = 'none';
            document.getElementById('type-error').style.display = 'none';
            document.getElementById('rate-error').style.display = 'none';
            document.getElementById('image-error').style.display = 'none';
            document.getElementById('images-error').style.display = 'none';
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