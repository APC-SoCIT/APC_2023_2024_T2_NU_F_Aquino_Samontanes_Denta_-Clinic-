<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aquino Samontanes Dental Clinic</title>
    <link rel="stylesheet" href="css's/patient/index.css">
    <link rel="icon" href="pics/Logo.png" type="image/png">
    
</head>
<body>
<header>
        <nav>
            <div class="logo">
                <a href="#" aria-label="Homepage">
                    <img src="pics/Logo.png" alt="" class="src">
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
                <li class="welcomeName">Welcome, <?php echo $_SESSION['first_name']; ?> <?php echo $_SESSION['last_name']; ?></li>
                <li><a href="php's/patient/Location.php">Location</a></li>
                <li><a href="php's/patient/staff.php">Staffs</a></li>
                <li><a href="php's/patient/service.php">Services</a></li>
                <li><a href="php's/patient/Calendar.php">Your Appointments</a></li>
                <li><a href="php's/auth/logout.php">Logout</a></li>
                <li><a href="php's/patient/Request.php" class="btn-nav">Request an Appointment</a></li>
                <?php
                } else {
                ?>
                <li><a href="php's/patient/Location.php">Location</a></li>
                <li><a href="php's/patient/staff.php">Staffs</a></li>
                <li><a href="php's/patient/service.php">Services</a></li>
                <li><a href="php's/auth/login.php">Login</a></li>
                <li><a href="php's/patient/Request.php" class="btn-nav">Request an Appointment</a></li>
                <?php
                }
                ?>
            </ul>
            <div class="hamburger">
                <i class="fa-solid fa-bars"></i>
            </div>
        </nav>
    </header>

    <div class="container-main">
        <div class="container-submain">
           <div class="container-main-content">
            <h1>We Offer Every <br> Dental Specialty <br> Your Family Needs</h1>
            <h2>From family dentistry to braces and oral surgery, Aquino Samontanes Dental <br> Clinic offers your entire family comprehensive dental care that’s second to none.</h2>
           </div>
           <div class="container-main-content-button">
            <a href="php's/patient/service.php" class="btn-main">Service we Offer</a>
           </div>
        </div>
    </div>
    
    <div class="main-bottom">
        <h3>Request an Appointment</h3>
        <p>A visit to one of our starts here.</p>
        <div class="main-bottom-button">
            <a href="php's/patient/Request.php" class="btn-main-bottom">Start Here</a>  
        </div>              
    </div>

    <script src="js's/scriptindex.js"></script>
    <script src="https://kit.fontawesome.com/595a890311.js" crossorigin="anonymous"></script>
    
</body>
</html>