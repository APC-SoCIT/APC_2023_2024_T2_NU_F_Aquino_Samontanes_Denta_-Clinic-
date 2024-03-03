<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Aquino Samontanes Dental Clinic</title>
    <link rel="stylesheet" href="../../css's/patient/index.css">
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
                <li class="welcomeName">Welcome, <?php echo $_SESSION['first_name']; ?> <?php echo $_SESSION['last_name']; ?></li>
                <li><a href="Location.php">Location</a></li>
                <li><a href="staff.php">Staffs</a></li>
                <li><a href="services.php" class="sel_page">Services</a></li>
                <li><a href="Calendar.php">Your Appointments</a></li>
                <li><a href="../auth/logout.php">Logout</a></li>
                <li><a href="Request.php" class="btn-nav">Request an Appointment</a></li>
                <?php
                } else {
                ?>
                <li><a href="Location.php">Location</a></li>
                <li><a href="staff.php">Staffs</a></li>
                <li><a href="services.php" class="sel_page">Services</a></li>
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

  <div class="card-container">
    <div class="card">Card 1</div>
    <div class="card">Card 2</div>
    <div class="card">Card 3</div>
    <div class="card">Card 4</div>
    <div class="card">Card 5</div>
    <div class="card">Card 6</div>
  </div>

  <footer>
        <div class="container">
            <div class="sec about">
                <h2>About</h2>
                <p>Welcome to Aquino-Samontanes Dental Clinic, <br>your premier dental care provider 
                in Caloocan, Philippines. <br>At Aquino-Samontanes Dental Clinic, we are dedicated to
                <br>delivering exceptional dental services to our community.
                </p>
                <ul class="sci">
                    <li><a href="#"><i class="fa-brands fa-square-facebook" style="color: #ffffff;"></i></a></li>
                </ul>
            </div>

            <div class="sec quicklinks">
                <h2>Navigations</h2>
                <ul>
                    <li><a href="Home.php">Home</a></li>
                    <li><a href="Location.php">Location</a></li>
                    <li><a href="#">Our Services</a></li>
                    <li><a href="Request.php">Request an Appointment</a></li>
                </ul>
            </div>

            <div class="sec contact">
                <h2>Contact Us</h2>
                <ul class="info">
                    <li>
                        <span><i class="fa-solid fa-location-dot"></i></i></span><p><a href="tel:+630987654321">Block 11 Lot 14 Sunriser Avenue, Sunriser Village, Llano Brgy 167 Caloocan City</a></p>
                        <span><i class="fa-solid fa-phone"></i></span><p><a href="tel:+630987654321">+63 098 765 4321</a></p>
                        <span><i class="fa-solid fa-envelope"></i></span><p><a href="mailto:ASamontanesDentalClinic@gmail.com">ASamontanesDentalClinic@gmail.com</a></p>
                    </li>
                </ul>
            </div>

        </div>
    </footer>
    <div class="copyrightText">
        <p>Aquino-Samontanes Dental Clinic 2024</p>
    </div>
</body>
</html>
