<?php
session_start();
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
if (isset($_SESSION['user_id']) && $_SESSION['role'] === "Administrator") {
    $id = $_GET['id'];
    $status = $_GET['status'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $conn = dbConnect();
        
    $stmt = $conn->prepare("UPDATE `room` SET status = '$status' WHERE id = '$id'");
    
    if($stmt->execute()){
        
        $_SESSION['success'] = 'Room has been updated.';
        header("Location: room.php");
    }else{
        $_SESSION['error'] = 'Room has not been updated.';

    }

    //cancel all pending transaction here.
    //send notification to all cancelled transaction
    }
    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Out of order</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap4.min.css" rel="stylesheet"/>
</head>
<body>
    <Div>
        
    </Div>
    <div class="row justify-content-center" style="margin-top:100px">
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
                            <div class="card-body">
                                <form action="" method="POST" enctype="multipart/form-data">
                                <h2>Warning</h2>
                                <H1>Are you sure to put this room in <?php echo ($status==1? 'OUT OF ORDER':'BACK IN BUSINESS') ?> state?</H1>
                                <p>This action will cancell all pending and incoming transaction.</p>
                                <div style="text-align:center">
                          <button type="submit" class="btn"
                            style="color: #fff;background-color: #0d6efd;border-color: #0d6efd;border-radius:5px;width:300px">Proceed</button>
                            
                          <a type="button" href="room.php" class="btn"
                            style="color: #fff;background-color: #0d6efd;border-color: #0d6efd;border-radius:5px;width:300px">Cancel</a>
                        </div>
                                </form>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</body>
</html>
<?php
exit();
}elseif (isset($_SESSION['user_id']) && $_SESSION['role'] === "Guest") {
  header("Location: ../index.php");
}
?>