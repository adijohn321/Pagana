<?php
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['role'] === "Administrator") {
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
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap4.min.css" rel="stylesheet"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
      .home-content .tables {
  background: #fff;
  padding: 20px 30px;
  border-radius: 12px;
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
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

    <div class="home-content" style="overflow: hidden;">
        <div class="tables table-responsive">
          <div style="margin-bottom: 20px;">            
            <a class="btn btn-primary" href="add_room.php">Add Room</a>
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
          </div>
          
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th style="text-align: center; width: 5%;">#</th>
                    <th style="text-align: center;">image</th>
                    <th style="text-align: center;">Name</th>
                    <th style="text-align: center;">Type</th>
                    <th style="text-align: center;">Description</th>
                    <th style="text-align: center;">Rate</th>
                    <th style="text-align: center;">Action</th>
                </tr>
                </thead>
                <tbody>
                  <?php
                    // Fetch rooms data from the database
                  include_once("../function/dbconnect.php");
                  $conn = dbConnect();
                  $stmt = $conn->prepare("SELECT * FROM room");
                  $stmt->execute();
                  $result = $stmt->get_result();
                  
                  $sn = 0;
                  while ($row = $result->fetch_assoc()) {
                      $sn++;
                      
                      $roomId = $row['id'];
                      $roomImage = explode(', ',$row['image']);
                      $roomName = $row['name'];
                      $roomType = $row['type'];
                      $roomDescription = $row['description'];
                      $roomRate = $row['rate'];
                      $status = $row['status'];
                  ?>
                  <tr>
                      <td style="text-align: center;"><?php echo $sn; ?></td>
                      <td style="text-align: center; text-transform: capitalize ">
                        <?php foreach($roomImage as $image) { ?>
                            <img src="../uploads/<?php echo $image; ?>" width="50" height="50">
                        <?php } ?>
                      </td>
                      <td style="text-align: center; text-transform: capitalize "><a href="room_info.php?id=<?php echo $roomId; ?>"><?php echo $roomName; ?></a></td>
                      <td style="text-align: center; text-transform: capitalize "><?php echo $roomType; ?></td>
                      <td style="text-align: center; text-transform: capitalize "><?php echo substr($roomDescription, 0 , 100); ?>...</td>
                      <td style="text-align: center; text-transform: capitalize ">₱<?php echo number_format((float)$roomRate, 2, '.', ''); ?></td>
                      <td style="text-align: center; text-transform: capitalize ">
                          <a href="edit_room.php?id=<?php echo $roomId; ?>" class="btn btn-primary">Edit</a>
                          <?php if($status===0){?>
                          <a href="outoforder.php?id=<?php echo $roomId; ?>&status=1" class="btn btn-danger">Out of order</a>
                          <?php }else{?>
                          <a href="outoforder.php?id=<?php echo $roomId; ?>&status=0" class="btn btn-danger">Back in Business</a>

                            <?php }?>
                      </td>
                  </tr>
                  <?php
                  }
                  $stmt->close();
                  $conn->close();
                  ?>
                </tbody>
            </table>
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
 <script type="text/javascript">
    $(document).ready(function() {
        $('table').DataTable();
    });
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