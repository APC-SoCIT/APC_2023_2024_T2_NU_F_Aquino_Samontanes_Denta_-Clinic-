<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aquino Samontanes Dental Clinic</title>
    <link rel="stylesheet" href="../../css's/patient/staff.css">

    <style>
        /*Map*/
        #map-container{
            width: 100%;
            display: flex;
            align-items: center;
            flex-direction: column;
            padding: 0;
        }
        h1#map-title{
            margin-top: 1em;
            color: white;
            margin-bottom: .4em;
            font-weight: bold;
            font-size: 5em;
            text-align: center;
        }
        iframe{
            margin-bottom: 5em;
            width: 80%;
            height: 500px;
        }
    </style>
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
                <li><a href="staff.php" class="sel_page">Staffs</a></li>
                <li><a href="service.php">Services</a></li>
                <li><a href="Calendar.php">Your Appointments</a></li>
                <li><a href="../auth/logout.php">Logout</a></li>
                <li><a href="Request.php" class="btn-nav">Request an Appointment</a></li>
                <?php
                } else {
                ?>
                <li><a href="Location.php">Location</a></li>
                <li><a href="staff.php" class="sel_page">Staffs</a></li>
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

    <div class="dentists">
        <div class="staff-container">
            <img class="staff-circle" src="../../pics/dentist.jpg"></img>
            <div class="staff-text">
                <div class="staff-name">Catherine Aquino Samontanes (Dentist) </div>
                <div class="staff-details"><b>Age:</b> 55 years old&emsp;&emsp;<b>Birthdate:</b> Novermber 13, 1968<br>
                <b>Occupation:</b> Doctor/Owner of Aquino-Samontanes Clinic<br>
                <b>Contact Number:</b> 09271520198</div>
            </div>
        </div>
        <div class="staff-container2">
            <div class="staff-text2">
                <div class="staff-name">(Receptionist) Lazaro G. Samontanes  </div>
                <div class="staff-details"><b>Birthdate:</b> March 15, 1959 &emsp;&emsp;<b>Age:</b> 64 years old<br>
                <b>Occupation:</b> Retired OFW, 26 years in Saudi Arabia and has a small business</div>
            </div>
            <img class="staff-circle2" src="../../pics/receptionist.jpg"></img>
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

    <script src="../../js's/scriptindex.js"></script>
    <script src="https://kit.fontawesome.com/595a890311.js" crossorigin="anonymous"></script>
    
</body>
</html>