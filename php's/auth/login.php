<?php
session_start();
include "../../db_conn.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        function validate($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $email = validate($_POST['email']);
        $pass = validate($_POST['password']);
        $UserType = $_POST['user_type'];

        if (empty($email)) {
            header("Location: login.php?error=Email Address is required");
            exit();
        } else if (empty($pass)) {
            header("Location: login.php?error=Password is required");
            exit();
        } else {
            // Fetch user from the database
            $stmt = $conn->prepare("SELECT * FROM users WHERE email_address = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                $hashedPassword = $row['password'];

                // Verify password
                if (password_verify($pass, $hashedPassword)) {
                    // Password is correct, start session
                    session_start();

                    $_SESSION['first_name'] = $row['first_name'];
                    $_SESSION['last_name'] = $row['last_name'];
                    $_SESSION['email_address'] = $row['email_address'];
                    $_SESSION['user_role'] = $row['user_role'];
                    $_SESSION['id'] = $row['id'];

                    if ($_SESSION['user_role'] == 'patient') {
                        header("Location: ../patient/Home.php");
                        exit();
                    } else {
                        header("Location: ../admin/CheckAppointments.php");
                        exit();
                    }
                } else {
                    // Password is incorrect
                    header("Location: login.php?error=Incorrect password");
                    exit();
                }
            } else {
                // User not found
                header("Location: login.php?error=User not found");
                exit();
            }
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aquino Samontanes Dental Clinic</title>
    <link rel="stylesheet" type="text/css" href="../../css's/auth/login.css">

</head>
<body>
<header>
        <nav>
            <div class="logo">
                <a href="../../index.php" aria-label="Homepage">
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
                <li><a href="../patient/Location.php">Location</a></li>
                <li><a href="../patient/staff.php">Staffs</a></li>
                <li><a href="">Services</a></li>
                <li><a href="../patient/Calendar.php">Your Appointments</a></li>
                <li><a href="../auth/logout.php" class="sel_page">Logout</a></li>
                <li><a href="../patient/Request.php" class="btn-nav">Request an Appointment</a></li>
                <?php
                } else {
                ?>
                <li><a href="../patient/Location.php">Location</a></li>
                <li><a href="../patient/staff.php">Staffs</a></li>
                <li><a href="">Services</a></li>
                <li><a href="../auth/login.php" class="sel_page">Login</a></li>
                <li><a href="../patient/Request.php" class="btn-nav">Request an Appointment</a></li>
                <?php
                }
                ?>
            </ul>
            <div class="hamburger">
                <i class="fa-solid fa-bars"></i>
            </div>
        </nav>
    </header>
                
    <?php if (isset($_GET['error'])) { ?>
        <p class="error" id="errorMessage"><?php echo $_GET['error']; ?></p>
        <script>
            setTimeout(function() {
                document.getElementById('errorMessage').classList.add('hide');
            }, 1000);
        </script>
    <?php } ?>
    
    <form action="" method="post">
        <div class="container">
            <div class="login form">
                <header>Login</header>
                    <input type="text" id="email" name="email" placeholder="Email Address">
                    <input type="password" id="password" name="password" placeholder="Password">
            
                    <button type="submit" class="button">Login</button>
                    <div class="signup">
                        <span class="signup">Don't have an account?
                            <a href="signup.php">Signup</a>
                        </span>
                    </div>
            </div>
        </div>
    </form>
</body>
</html>
