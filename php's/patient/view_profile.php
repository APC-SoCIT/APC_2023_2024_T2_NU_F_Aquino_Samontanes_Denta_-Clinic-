<?php
session_start();
include '../../db_conn.php';

if (isset($_SESSION['id']) && isset($_SESSION['email_address'])) {

    $PID = $_SESSION['id'];
    $sql = "SELECT * FROM users WHERE `id`='$PID'";
    $result = $conn->query($sql); 

    if ($result->num_rows > 0) {        
        while ($row = $result->fetch_assoc()) {
            $FirstName = $row['first_name'];
            $LastName = $row['last_name'];
            $EmailAddress = $row['email_address'];
        }
    }

    $psql = "SELECT * FROM patients WHERE `patient_id`='$PID'";
    $presult = $conn->query($psql); 

    if ($presult->num_rows > 0) {        
        while ($row = $presult->fetch_assoc()) {
            $ContactNumber = $row['contact_number'];
            $Age = $row['age'];
            $Gender = $row['gender'];
        }
    }
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aquino Samontanes Dental Clinic</title>
    <link rel="stylesheet" href="../../css's/patient/viewprfl.css">
</head>
<body>

    <header>
        <nav>
            <div class="logo">
                <a href="Home.php" aria-label="Homepage">
                    <img src="../../pics/Logo.png" alt="" class="src">
                </a>
            </div>
            <ul>
            <?php

            if (isset($_SESSION['id']) && isset($_SESSION['email_address'])) {

                $sql = "SELECT *
                FROM users";

                $result = mysqli_query($conn, $sql);
                if (!$result) {
                    die("Error: " . mysqli_error($conn));
                }
                ?>
                <li class="welcomeName"><a href="view_profile.php">Welcome, <?php echo $_SESSION['first_name']; ?> <?php echo $_SESSION['last_name']; ?></a></li>
                <li><a href="Location.php">Location</a></li>
                <li><a href="staff.php">Staffs</a></li>
                <li><a href="service.php">Services</a></li>
                <li><a href="Calendar.php">Your Appointments</a></li>
                <li><a href="../auth/logout.php">Logout</a></li>
                <li><a href="Request.php" class="btn-nav">Request an Appointment</a></li>
                <?php
                } else {
                ?>
                <li><a href="Location.php">Location</a></li>
                <li><a href="staff.php">Staffs</a></li>
                <li><a href="service.php">Services</a></li>
                <li><a href="../auth/login.php">Login</a></li>
                <li><a href="Request.php" class="btn-nav">Request an Appointment</a></li>
                <?php
                }
                ?>
            </ul>
            <div class="hamburger">
                <i class="fa-solid fa-bars"></i>
            </div>
        </nav>
    </header>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>


    <?php if (isset($_GET['success'])) { ?>
        <p class="success" id="successMessage"><?php echo $_GET['success']; ?></p>
        <script>
            setTimeout(function() {
                document.getElementById('successMessage').classList.add('hide');
            }, 2000);
        </script>
    <?php } ?>
    <!-- Center the container horizontally -->
    <div class="container">
        <button class="back-button" onclick="goBack()">Back</button>
        <!-- Edit button outside the card, aligned with the right side of the container -->
        <a href="edit_profile.php?id=<?php echo $PID; ?>"><button class="edit-button">Edit</button></a>
        <!-- Single card with two columns inside -->
        <div class="card">
            <div class="card-content">
                <div class="column1">
                    <div class="profile">
                        <img src="../../pics/profile.webp" alt="">
                        <h1 class="name"><?php echo $FirstName ?> <?php echo $LastName ?></h1>
                    </div>
                </div>
                <div class="column2">
                    Information <br>
                    <hr>
                    <div class="info">
                        <h1 style="font-family: 'Poppins'; font-size: larger;">Age</h1>
                        <p><?php echo $Age ?></p>
                    </div>
                    <div class="info">
                        <h1 style="font-family: 'Poppins'; font-size: larger;">Email Address</h1>
                        <p><?php echo $EmailAddress ?></p>
                    </div>
                    <div class="info">
                        <h1 style="font-family: 'Poppins'; font-size: larger;">Gender</h1>
                        <p><?php echo $Gender ?></p>
                    </div>
                    <div class="info">
                        <h1 style="font-family: 'Poppins'; font-size: larger;">Contact Number</h1>
                        <p><?php echo $ContactNumber ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../js's/scriptindex.js"></script>
    <script src="https://kit.fontawesome.com/595a890311.js" crossorigin="anonymous"></script>
    
</body>
</html>

<?php
} else {
    header("Location: ../../index.php");
    exit();
}
?>