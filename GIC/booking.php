<?php
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['role'] === "InCharge") {
  // Redirect the user to the dashboard or desired page
  include_once "../function/dashboard.php";
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
      .home-content .tables {
        background: #fff;
        padding: 20px 30px;
        border-radius: 12px;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
      }

      .action-column {
        display: none;
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
          <a href="reservations.php" class="active">
            <i class='bx bx-list-ul'></i>
            <span class="links_name">Booking</span>
          </a>
        </li>

        <li>
          <a href="report.php" class="">
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
          <span class="dashboard">Reservation</span>
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

      <div class="home-content" style="overflow: hidden;">
        <div class="tables table-responsive">
          <div class="btn-group mb-3" role="group" aria-label="Reservation Status">
            <!-- <button type="button" class="btn btn-primary" onclick="filterReservations('Pending')">Pending</button>
            <button type="button" class="btn btn-success" onclick="filterReservations('Accepted')">Accepted</button>
            <button type="button" class="btn btn-danger" onclick="filterReservations('Rejected')">Rejected</button> -->
          </div>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th style="text-align: center; width: 5%;">Transaction ID</th>
                <th style="text-align: center;">Room</th>
                <th style="text-align: center;">Amenities</th>
                <th style="text-align: center;">Name</th>
                <th style="text-align: center;">Phone</th>
                <th style="text-align: center;">Check-In</th>
                <th style="text-align: center;">Check-Out</th>
                <th style="text-align: center;">Paid</th>
                <th style="text-align: center;">Total</th>
                <th style="text-align: center;">Status</th>
                <th class="action-column" style="text-align: center;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Fetch rooms data from the database
              $conn = dbConnect();
              $stmt = $conn->prepare("SELECT * FROM reservation where status = 'Reserved'  ORDER BY id DESC");
              $stmt->execute();
              $result = $stmt->get_result();

              $sn = 0;
              while ($row = $result->fetch_assoc()) {
                $sn++;

                $reservationId = $row['id'];
                $reservationName = $row['name'];
                $reservationRoom = $row['room_id'];
                $reservationEmail = $row['email'];
                $reservationTotal = $row['total_rate'];
                $reservationPhone = $row['phone'];
                $reservationAddress = $row['address'];
                $reservationIn = $row['checkin'];
                $reservationOut = $row['checkout'];
                $reservationAmount = $row['amount_paid'];
                $reservationStatus = $row['status'];
                $reservationTransaction = $row['transaction_id'];

                ?>
                <tr class="reservation-row" data-status="<?php echo $reservationStatus; ?>">
                  <td style="text-align: center; text-transform: capitalize ">
                    <?php echo $reservationTransaction; ?>
                  </td>
                  <td style="text-align: center; text-transform: capitalize ">
                    <?php echo $reservationRoom; ?>
                  </td>
                  <td style="text-align: center; text-transform: capitalize ">
                    <?php
                    $stmt1 = $conn->prepare("SELECT * FROM amenities_reservation WHERE reservation_id = '$reservationId'");
                    $stmt1->execute();
                    $result1 = $stmt1->get_result();

                    while ($amenities = $result1->fetch_assoc()) {
                      $amenity = $amenities['name'];
                      echo $amenity;
                      echo ', ';
                    }
                    ?>
                  </td>
                  <td style="text-align: center; text-transform: capitalize ">
                    <?php echo $reservationName; ?>
                  </td>
                  <td style="text-align: center; text-transform: capitalize ">
                    <?php echo $reservationPhone; ?>
                  </td>
                  <td style="text-align: center; text-transform: capitalize ">
                    <?php echo $reservationIn; ?>
                  </td>
                  <td style="text-align: center; text-transform: capitalize ">
                    <?php echo $reservationOut; ?>
                  </td>
                  <td style="text-align: center; text-transform: capitalize ">₱
                    <?php echo $reservationAmount; ?>
                  </td>
                  <td style="text-align: center; text-transform: capitalize ">₱
                    <?php echo $reservationTotal; ?>
                  </td>
                  <td style="text-align: center; text-transform: capitalize ">
                    <?php echo $reservationStatus; ?>
                  </td>
                  <td class="action-column" style="text-align: center; text-transform: capitalize;">
                    <div class="AR" style="display:flex">
                      <button class="btn btn-primary"
                        onclick="updateReservationStatus('<?php echo $reservationTransaction; ?>')">Accept</button>
                      <button class="btn btn-danger"
                        onclick="rejectReservationStatus('<?php echo $reservationTransaction; ?>')">Reject</button>
                    </div>
                    <div class="A" style="display:flex">
                      <a href="room_booking.php?id=<?php echo $reservationRoom ?>&checkin=<?php echo $reservationIn; ?>&checkout=<?php echo $reservationOut; ?>&name=<?php echo $reservationName; ?> &email=<?php echo $reservationEmail; ?>&phone=<?php echo $reservationPhone; ?> &address=<?php echo $reservationAddress; ?>&rid=<?php echo $reservationId; ?>&total=<?php echo $reservationTotal ?>"
                        class="btn btn-primary">Book</a>
                    </div>
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
      function filterReservations(status) {
        $('.reservation-row').hide(); // Hide all reservation rows
        $('.action-column').hide(); // Hide all action columns
        $('.A').hide();
        $('.AR').hide();

        if (status === 'Pending') {
          $('.reservation-row[data-status="Pending"]').show();
          $('.action-column').show(); // Hide all action columns  // Show rows with the selected status
          $('.A').hide();
          $('.AR').show();
        } else if (status === 'Accepted') {
          $('.reservation-row[data-status="Accepted"]').show(); // Show rows with the selected status
          $('.action-column').show();
          $('.A').show();
          $('.AR').hide();
        } else if (status === 'Rejected') {
          $('.reservation-row[data-status="Rejected"]').show(); // Show rows with the selected status
        }
      }
    </script>
    <script>
      function updateReservationStatus(id) {
        $.ajax({
          url: '../function/pending_status.php',
          method: 'POST',
          data: { id: id },
          success: function (response) {
            // Refresh the reservation table or handle the response as needed
            location.reload(); // This will refresh the page
          },
          error: function (xhr, status, error) {
            console.error(error);
          }
        });
      }
    </script>
    <script>
      function rejectReservationStatus(id) {
        $.ajax({
          url: '../function/reject_status.php',
          method: 'POST',
          data: { id: id },
          success: function (response) {
            // Refresh the reservation table or handle the response as needed
            location.reload(); // This will refresh the page
          },
          error: function (xhr, status, error) {
            console.error(error);
          }
        });
      }
    </script>
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
    <script type="text/javascript">
      $(document).ready(function () {
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
} elseif (isset($_SESSION['user_id']) && $_SESSION['role'] === "Guest") {
  header("Location: ../index.php");
}
?>