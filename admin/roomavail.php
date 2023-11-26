<?php
session_start();
$checkin = isset($_POST['checkin']) ? $_POST['checkin'] : date('Y-m-d',strtotime(date('Y-m-d'). '1 days'));
$checkout = isset($_POST['checkout']) ? $_POST['checkout'] : date('Y-m-d',strtotime(date('Y-m-d'). '+ 2 days'));
$cdate = new DateTime("3 Months");
$fdate = $cdate->format('Y-m-d');

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
          <a href="guests.php">
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
          <a href="roomavail.php" class="active">
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
            <div class="container-fluid bg-dark mb-5 wow fadeIn" data-wow-delay="0.1s" style="padding: 35px;">
                <div class="container">
                    <form action="" method="POST" onsubmit="return validateForm()">
                        <div class="row g-2">
                            <div class="col-md-10">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label style="color: white;">From:</label>
                                        <input type="date" id="checkin" class="form-control border-0" name="checkin" max="<?php echo $fdate; ?>" value="<?php echo $checkin; ?>" placeholder="Keyword" />
                                    </div>
                                    <div class="col-md-6">
                                        <label style="color: white;">To:</label>
                                        <input type="date" id="checkout" class="form-control border-0" name="checkout" max="<?php echo $fdate; ?>" value="<?php echo $checkout; ?>" placeholder="Keyword" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label></label>
                                <button class="btn btn-dark border-0 w-100">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
            <table class="table table-bordered">
                <thead>
                <tr>
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
                        function dbConnect()
                        {
                        // Modify these variables with your database credentials
                        $host = "localhost";
                        $username = "root";
                        $password = "";
                        $database = "paganadb";

                        $conn = new mysqli($host, $username, $password, $database);

                        if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                        }

                        return $conn;
                        }
                        $conn = dbConnect();
                        $select = mysqli_query($conn, "SELECT * FROM `room`");
                        $select_arr = array(); 
                           if(mysqli_num_rows($select)>0){
                              while($fetch = mysqli_fetch_assoc($select)){
                                 $select_arr[$fetch['name']]= $fetch;
                                 $book = explode(',', $select_arr[$fetch['name']]['name']);
                                 foreach($book as $booked){
                                 }
      
                              }
                           }else{
                           }
                        $fetch_booked = mysqli_query($conn, "SELECT distinct name from `room` where name not in (SELECT room_id from `reservation` where '$checkin' BETWEEN date(checkin) and date(checkout) OR '$checkout' BETWEEN date(checkin) and date(checkout) OR (checkin <= '$checkout' AND checkout >= '$checkin')
                        OR (checkin <= '$checkin' AND checkout >= '$checkout')
                        OR (checkin >= '$checkin' AND checkout <= '$checkout'))");
                        if(mysqli_num_rows($fetch_booked)>0){
                        while($fetch = mysqli_fetch_assoc($fetch_booked)){
                              
                    ?>
                  <tr>
                      <td style="text-align: center; text-transform: capitalize "><img src="../uploads/<?php echo $select_arr[$fetch['name']]['image']; ?>" width="50" height="50"></td>
                      <td style="text-align: center; text-transform: capitalize "><?php echo $select_arr[$fetch['name']]['name']; ?></td>
                      <td style="text-align: center; text-transform: capitalize "><?php echo $select_arr[$fetch['name']]['type']; ?></td>
                      <td style="text-align: center; text-transform: capitalize "><?php echo substr($select_arr[$fetch['name']]['description'], 0, 100); ?>...</td>
                      <td style="text-align: center; text-transform: capitalize ">₱<?php echo $select_arr[$fetch['name']]['rate']; ?></td>
                      <td style="text-align: center; text-transform: capitalize ">
                          <a href="room_booking.php?id=<?php echo $select_arr[$fetch['name']]['id']; ?>&checkin=<?php echo $checkin; ?>&checkout=<?php echo $checkout; ?>" class="btn btn-primary">Book</a>
                      </td>
                  </tr>
                  <?php
                           
                        }
                    }else{
                    echo '<p class="empty" style="color: black; text-align: center;">No Available Room has been found!</p>';
            
                    }
                      
                     ?>
                </tbody>
            </table>
        </div>
    </div>
  </section>
  <script>
            function validateForm() {
                var fromDate = new Date(document.getElementById("checkin").value);
                var toDate = new Date(document.getElementById("checkout").value);
                
                if (fromDate > toDate) {
                    alert("Check-In date cannot be higher than Check-Out date");
                    return false; // Prevent form submission
                }
                
                return true; // Allow form submission
            }
        </script>

        <script>
        $(function(){
            var dtToday = new Date();
            
            var month = dtToday.getMonth() + 1;
            var day = dtToday.getDate() + 1;
            var year = dtToday.getFullYear();
            if(month < 10)
                month = '0' + month.toString();
            if(day < 10)
                day = '0' + day.toString();
            
            var maxDate = year + '-' + month + '-' + day;
            $('#checkin').attr('min', maxDate);
        });

        $(function(){
            var dtToday = new Date();
            
            var month = dtToday.getMonth() + 1;
            var day = dtToday.getDate() + 2 ;
            var year = dtToday.getFullYear();
            if(month < 10)
                month = '0' + month.toString();
            if(day < 10)
                day = '0' + day.toString();

            var maxDate = year + '-' + month + '-' + day;
            $('#checkout').attr('min', maxDate);
        });
        </script>
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