<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aquino Samontanes Dental Clinic</title>
    <link rel="stylesheet" href="../../css's/patient/index.css">

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
                <li class="welcomeName">Welcome, <?php echo $_SESSION['first_name']; ?> <?php echo $_SESSION['last_name']; ?></li>
                <li><a href="Location.php" class="sel_page">Location</a></li>
                <li><a href="staff.php">Staffs</a></li>
                <li><a href="service.php">Services</a></li>
                <li><a href="Calendar.php">Your Appointments</a></li>
                <li><a href="../auth/logout.php">Logout</a></li>
                <li><a href="Request.php" class="btn-nav">Request an Appointment</a></li>
                <?php
                } else {
                ?>
                <li><a href="Location.php" class="sel_page">Location</a></li>
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

    <div id="map-container">
            <h1 id="map-title"> Location Map </h1>  
             <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d236.03861305349926!2d121.01035561892066!3d14.729989790308293!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b1531cfebfff%3A0xb0e56a359e5b0cb9!2sBing-Bing&#39;s%20Condiments!5e1!3m2!1sen!2sph!4v1709168426601!5m2!1sen!2sph" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <div class="location_container">
        <div class="directions">
            <div class="text_container">
                <p>When you're heading to the Aquino-Samontanes Dental Clinic, it might be a bit tricky to spot at first. Get the directions from the maps above and once you've identified the building (from the picture) you'll need to enter the building, but don't worry if you don't immediately see a dental clinic. It's tucked away inside of a (sari-sari) store.</p>
            </div>
            <div class="img_container">
                <img src="../../pics/location.jpg" alt="Placeholder Image">
            </div>
        </div>

        <div class="directions">
            <div class="text_container">
                <p>Once you're inside that store, you will be immediately greeted by the receptionist. And from there on you are inside the clinic. Take care and keep those pearly whites shining!</p>
            </div>
            <div class="img_container">
                <img src="../../pics/specific_location.jpg" alt="Placeholder Image" style="max-width: 50%;">
            </div>
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

    <script src="../../js's/scriptindex.js"></script>
    <script src="https://kit.fontawesome.com/595a890311.js" crossorigin="anonymous"></script>
    
</body>
</html>