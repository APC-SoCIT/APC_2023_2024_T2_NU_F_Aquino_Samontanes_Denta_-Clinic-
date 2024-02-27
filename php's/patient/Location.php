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
                <a href="#" aria-label="Homepage">
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
                <?php
                } else {
                    
                }
                ?>
                <li><a href="Location.php">Our Location</a></li>
                <li><a href="">Dentist & Reviews</a></li>
                <li><a href="">Our Services</a></li>
                <li><a href="Calendar.php">Your Appointments</a></li>
                <li><a href="">Contact Us</a></li>
                <li><a href="../auth/logout.php">Logout</a></li>
                <li><a href="Request.php" class="btn-nav">Request an Appointment</a></li>
            </ul>
            <div class="hamburger">
                <i class="fa-solid fa-bars"></i>
            </div>
        </nav>
    </header>

    <div id="map-container">
            <h1 id="map-title"> Location Map </h1>   
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3009.579471735484!2d121.00662006864943!3d14.729947842478422!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b10aaf8fba31%3A0x5ee32fe428c13876!2sLot%2014%2C%20Lot%2014%20Titan%20St%2C%20Sunriser%20Village%2C%20Caloocan%2C%20Metro%20Manila!5e1!3m2!1sen!2sph!4v1709003505271!5m2!1sen!2sph" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe> 
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

    <script src="../../js's/scriptindex.js"></script>
    <script src="https://kit.fontawesome.com/595a890311.js" crossorigin="anonymous"></script>
    
</body>
</html>