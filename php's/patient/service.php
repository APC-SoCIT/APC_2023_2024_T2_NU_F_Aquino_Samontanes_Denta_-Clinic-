<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Aquino Samontanes Dental Clinic</title>
    <link rel="stylesheet" href="../../css's/patient/index.css">
    <link rel="icon" href="../../pics/Logo.png" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
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
            session_start();
            include '../../db_conn.php';

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
    <div class="card">
        <h1 class="service-title">Jacket Crown Preparation</h1>
        <p class="service-content">Excellent for rebuilding and fortifying badly damaged teeth, improving beauty as well as function.</p>
    </div>
    <div class="card">
        <h1 class="service-title">Root Canal Treatment</h1>
        <p class="service-content">Preserve the natural structure of the tooth and relieve extreme tooth pain brought on by an infection or inflammation in the tooth pulp.</p>
    </div>
    <div class="card">
        <h1 class="service-title">Teeth Restoration</h1>
        <p class="service-content">Required to treat decay, cracks, or discolouration in order to guarantee better oral health and a self-assured grin.</p>
    </div>
    <div class="card">
        <h1 class="service-title">Teeth Extraction</h1>
        <p class="service-content">Relieves excruciating tooth pain or crowding problems, stops further problems, and returns comfort to the mouth.</p>
    </div>
    <div class="card">
        <h1 class="service-title">Surgery</h1>
        <p class="service-content">Offers long-term oral health and comfort by treating a variety of dental conditions, such as severely diseased gums or impacted wisdom teeth.</p>
    </div>
    <div class="card">
        <h1 class="service-title">Removal Denture Extraction</h1>
        <p class="service-content">This procedure gives patients who have lost a lot of teeth their entire dental functionality and beauty back, enabling them to chew more easily and smile more confidently.</p>
    </div>
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
                    <li><a href="https://www.facebook.com/catherine.samontanes.37?mibextid=LQQJ4d"><i class="fa-brands fa-square-facebook" style="color: #ffffff;"></i></a></li>
                </ul>
            </div>

            <div class="sec quicklinks">
                <h2>Navigations</h2>
                <ul>
                    <li><a href="Home.php">Home</a></li>
                    <li><a href="Location.php">Location</a></li>
                    <li><a href="staff.php">Staffs</a></li>
                    <li><a href="service.php">Services</a></li>
                </ul>
            </div>

            <div class="sec contact">
                <h2>Contact Us</h2>
                <ul class="info">
                    <li>
                        <span><i class="fa-solid fa-location-dot"></i></i></span><p><a href="tel:+630987654321">Block 11 Lot 14 Sunriser Avenue, Sunriser Village, Llano Brgy 167 Caloocan City</a></p>
                        <span><i class="fa-solid fa-phone"></i></span><p><a href="tel:+639271520198">+639271520198</a></p>
                        <span><i class="fa-solid fa-envelope"></i></span><p><a href="samontanescatherine@yahoo.com">samontanescatherine@yahoo.com</a></p>
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
