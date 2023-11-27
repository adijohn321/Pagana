<?php
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['role'] === "Administrator") {
  include_once "../function/dashboard.php";
  // Redirect the user to the dashboard or desired page


  function registerUser($fname, $mname, $lname, $email, $phone, $password, $confirm_password, $role)
  {
    $errors = array();

    // Validate first name
    if (!preg_match("/^[A-Za-z][A-Za-z\s]*$/", $fname)) {
      $errors['fname'] = "Special Characters, Numbers, and Spaces as the First Letter are not allowed for First Name.";
    }

    if (!empty($mname)) {
      if (!preg_match("/^[A-Za-z][A-Za-z\s]*$/", $mname)) {
        $errors['mname'] = "Special Characters, Numbers, and Spaces as the First Letter are not allowed for Middle Name.";
      }
    }
    if (!preg_match("/^[A-Za-z][A-Za-z\s]*$/", $lname)) {
      $errors['lname'] = "Special Characters, Numbers, and Spaces as the First Letter are not allowed for Last Name.";
    }

    if (preg_match('/[^A-Za-z0-9]/', $password) || strpos($password, ' ') !== false) {
      $errors['password'] = "Special Characters and Spaces are not allowed for Password.";
    }
    // if($password > 5){
    //   $errors['password'] = "Password should be higher than 8 letters";
    //}

    // Validate email: Check if it contains special characters or spaces
    if (preg_match('/[^A-Za-z0-9@._-]/', $email) || strpos($email, ' ') !== false) {
      $errors['email'] = "Special Characters and Spaces are not allowed for Email.";
    }

    if ($password !== $confirm_password) {
      $errors['match'] = "Password and Confirm Password do not match.";
    }


    $conn = dbConnect();
    $status = '0';
    if ($role != "Guest") {
      $status = '1';
    } else {

    }
    $emailCheckQuery = $conn->prepare("SELECT COUNT(*) AS count FROM users WHERE email = ?");
    $emailCheckQuery->bind_param("s", $email);
    $emailCheckQuery->execute();
    $result = $emailCheckQuery->get_result();
    $row = $result->fetch_assoc();
    $emailCount = $row['count'];

    if ($emailCount > 0) {
      $errors['email'] = "Email is already registered.";
    }

    if (!empty($errors)) {
      $_SESSION['errors'] = $errors;
      return false;
    }
    $verificationToken = bin2hex(random_bytes(32)); // Generates a random 32-byte hex string

    $stmt = $conn->prepare("INSERT INTO users (fname, mname, lname, email, phone, password, status, verification_token,  user_level) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password
    $stmt->bind_param("ssssisiss", $fname, $mname, $lname, $email, $phone, $hashedPassword, $status, $verificationToken, $role);

    if ($stmt->execute()) {
      $subject = "Account Registration - Email Verification";
      $message = "Dear $fname,\n\nThank you for registering an account with Pagana Hotel. Please click the following link to verify your email address:\n\n";
      $verificationLink = "https://localhost/pagana/verify.php?token=$verificationToken"; // Update with your verification URL
      $message .= "$verificationLink\n\n";
      $message .= "If you did not register an account, please ignore this email.\n\n";
      $sender = "From: Pagana Hotel";
      //mail($email, $subject, $message, $sender);
      $stmt->close();
      $conn->close();
      return true; // Registration successful
    } else {
      $stmt->close();
      $conn->close();
      return false; // Registration failed
    }



  }
  
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fname = $_POST["fname"];
    $mname = $_POST["mname"];
    $lname = $_POST["lname"];
    $email = $_POST["email"];
    $phone = $_POST["number"];
    $password = $_POST["password1"];
    $confirm_password = $_POST["password2"];
    $role = $_POST["type"];



    // For better security, consider using password_hash() to hash the password before storing it in the database.
    // $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if (registerUser($fname, $mname, $lname, $email, $phone, $password, $confirm_password, $role)) {
      // Registration successful, you can redirect to a success page or perform any other actions.
      $_SESSION['success'] = "Registration Successful.";
    } else {
      // Registration failed, you can display an error message or redirect to an error page.
      $_SESSION['error'] = "Registration Failed.";
    }
  }




  ?>
  <!DOCTYPE html>
  <html lang="en" dir="ltr">

  <head>
    <meta charset="UTF-8">
    <title>Pagana Kutawato Hotel</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css""
      integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
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
          <a href="user.php">
            <i class='bx bx-grid-alt'></i>
            <span class="links_name">Guests</span>
          </a>
        </li>
        <li>
          <a href="guests.php" class="active">
            <i class='bx bx-user'></i>
            <span class="links_name">Users</span>
          </a>
        </li>
        <li>
          <a href="room.php">
            <i class='bx bx-hotel'></i>
            <span class="links_name">Room</span>
          </a>
        </li>
        <li>
          <a href="roomavail.php">
            <i class='bx bx-hotel'></i>
            <span class="links_name">Room Booking</span>
          </a>
        </li>
        <li>
          <a href="amenities.php">
            <i class='bx bx-list-ul'></i>
            <span class="links_name">Amenities</span>
          </a>
        </li>
        <li>
          <a href="reservations.php">
            <i class='bx bx-list-ul'></i>
            <span class="links_name">Reservation</span>
          </a>
        </li>
        <li>
          <a href="reports.php">
            <i class='bx bx-list-ul'></i>
            <span class="links_name">Reports</span>
          </a>
        </li>
        <li>
          <a href="help_desk.php">
            <i class='bx bx-message'></i>
            <span class="links_name">Help Desk</span>
          </a>
        </li>
        <li>
          <a href="settings.php">
            <i class='bx bx-cog'></i>
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
    <section class="home-section" style="background:white">
      <nav>
        <div class="sidebar-button">
          <i class='bx bx-menu sidebarBtn'></i>
          <span class="dashboard">Users</span>
        </div>
      </nav>

      <div class="home-content">
        <div class="overview-boxes" style=" position: absolute;display: block;top: 100px;">

          <!-- Button to trigger the login modal -->
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#loginModal">
            New User
          </button>
          <?php if (isset($_SESSION['success'])) { ?>
            <div class="alert alert-success">
              <?php echo $_SESSION['success']; ?>
            </div>
            <?php
            unset($_SESSION['success']);
          }
          ?>
          <?php if (isset($_SESSION['error'])) { ?>
            <div class="alert alert-danger">
              <?php echo $_SESSION['error']; ?>
            </div>
            <?php
            unset($_SESSION['error']);
          }
          ?>
          <div class="home-content" style="overflow: hidden; padding-top: 15px;">
            <div class="tables table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th style="text-align: center; width: 5%;">Image</th>
                    <th style="text-align: center;" colspan="3">Name</th>
                    <th style="text-align: center;">Email</th>
                    <th style="text-align: center;">Phone</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $stmt = $conn->prepare("SELECT * FROM users  ORDER BY id DESC");
                  $stmt->execute();
                  $result = $stmt->get_result();

                  $sn = 0;
                  while ($row = $result->fetch_assoc()) {
                    $sn++;

                    $userId = $row['id'];
                    $userImage = $row['image'];
                    $userFName = $row['fname'];
                    $userMName = $row['mname'];
                    $userLName = $row['lname'];
                    $userEmail = $row['email'];
                    $userPhone = $row['phone'];
                    $userStatus = $row['status'];
                    $userLevel = $row['user_level'];

                    if ($userLevel !== 'Guest') {
                      ?>
                      <tr>
                        <td style="text-align: center; text-transform: capitalize ">
                          <?php echo $userImage; ?>
                        </td>
                        <td style="text-align: center; text-transform: capitalize ">
                          <?php echo $userFName; ?>
                        </td>
                        <td style="text-align: center; text-transform: capitalize ">
                          <?php echo $userMName; ?>
                        </td>
                        <td style="text-align: center; text-transform: capitalize ">
                          <?php echo $userLName; ?>
                        </td>
                        <td style="text-align: center; text-transform: capitalize ">
                          <?php echo $userEmail; ?>
                        </td>
                        <td style="text-align: center; text-transform: capitalize ">
                          <?php echo $userPhone; ?>
                        </td>
                        <?php if ($userStatus == false) {
                          ?>
                          <td style="text-align: center; text-transform: capitalize ">Unverified</td>
                          <?php
                        } elseif ($userStatus == true) {
                          ?>
                          <td style="text-align: center; text-transform: capitalize ">Verified</td>
                          <?php
                        }
                        ?>
                        <td style="text-align: center; text-transform: capitalize ">
                          <a href="user.php?id=<?php echo $userId; ?>" class="btn btn-danger">Remove</a>
                        </td>
                      </tr>
                      <?php
                    } else {
                    }
                  }
                  $stmt->close();
                  $conn->close();
                  ?>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Login Modal -->
          <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document" style="max-width:900px;width:900px">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="loginModalLabel">Create New In-Charge</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">


                  <main class="main-content" style="    width: -webkit-fill-available;">
                    <div class="container mt-4">
                      <form action="guests.php" method="post" enctype="multipart/form-data"
                        style="display:block;margin-bottom:10px">
                        <div class="row">
                          <div class="col-md-3">
                            <div class="form-group">
                              <label for="fname">First Name</label>
                              <input type="text" class="form-control" id="fname" name="fname" max_length="50" required>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="form-group">
                              <label for="mname">Middle Name</label>
                              <input type="text" class="form-control" id="mname" name="mname" max_length="50">
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="form-group">
                              <label for="lname">Last Name</label>
                              <input type="text" class="form-control" id="lname" name="lname" max_length="50" required>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="form-group">
                              <label for="ename">Extension</label>
                              <input type="text" class="form-control" id="ename" name="ename" max_length="50">
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-md-6">

                            <div class="form-group">
                              <label for="number">Mobile Number</label>

                              <input type="number" class="form-control" id="number" name="number" max_length="13"
                                required>
                            </div>
                          </div>
                          <div class="col-md-6">

                            <div class="form-group">
                              <label for="email">Email</label>

                              <input type="email" class="form-control" id="email" max_length="50" required name="email">
                            </div>
                          </div>


                        </div>

                        <div class="row">
                          <div class="col-md-6">

                            <div class="form-group">
                              <label for="password">Password</label>

                              <input type="password" class="form-control" id="password" max_length="50" required
                                name="password1">
                            </div>
                          </div>
                          <div class="col-md-6">

                            <div class="form-group">
                              <label for="repass">Retype Password</label>

                              <input type="password" class="form-control" id="repass" max_length="50" required
                                name="password2">
                            </div>
                          </div>


                        </div>

                        <div class="form-group">
                          <label for="type">User Type</label>
                          <select id="type" name="type" class="form-control" name="type">
                            <option value="Administrator">Administrator</option>
                            <option value="InCharge">InCharge</option>
                          </select>
                        </div>
                        <div style="text-align:center">
                          <button type="submit" class="btn"
                            style="color: #fff;background-color: #0d6efd;border-color: #0d6efd;border-radius:5px;width:300px">Submit</button>
                        </div>

                      </form>

                  </main>
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
      sidebarBtn.onclick = function () {
        sidebar.classList.toggle("active");
        if (sidebar.classList.contains("active")) {
          sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
        } else
          sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
      }
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
      integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
      crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
      integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
      crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
      integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
      crossorigin="anonymous"></script>
  </body>

  </html>
  <?php
  exit();
} elseif (isset($_SESSION['user_id']) && $_SESSION['role'] === "Guest") {
  header("Location: ../index.php");
}
?>