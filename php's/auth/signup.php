<?php
include "../../db_conn.php";

if (isset($_POST['submit'])) {
    $FirstName = $_POST['first_name'];
    $LastName = $_POST['last_name'];
    $EmailAddress = $_POST['email_address'];
    $PassWord = $_POST['password'];
    $Confirm = $_POST['confirm_password'];
    $UserRole = "patient";

    // Check if email already exists in the database
    $check_sql = "SELECT * FROM users WHERE email_address = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $EmailAddress);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: signup.php?error=Email address already exists");
        exit();
    }

    if (empty($FirstName) || empty($LastName) || empty($EmailAddress) || empty($PassWord) || empty($Confirm) || empty($UserRole)) {
        header("Location: signup.php?error=Kindly fill all fields");
        exit();
    } elseif ($PassWord != $Confirm) {
        header("Location: signup.php?error=Password does not match");
        exit();
    } elseif (!preg_match("/^(?=.*[A-Z])(?=.*\d).{8,}$/", $PassWord)) {
        header("Location: signup.php?error=Your password must be at least 8 characters long and contain at least one capital letter and one number");
        exit();
    } else {
        date_default_timezone_set('Asia/Manila');
        $currentDateTime = date('Y-m-d H:i:s');

        // Hash the password
        $hashedPassword = password_hash($PassWord, PASSWORD_DEFAULT);

        // Insert user data into the database
        $insert_sql = "INSERT INTO users (first_name, last_name, email_address, password, user_role, created_at) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ssssss", $FirstName, $LastName, $EmailAddress, $hashedPassword, $UserRole, $currentDateTime);
        $stmt->execute();

        header("Location: login.php?success=New record created successfully");
        exit();
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aquino Samontanes Dental Clinic</title>
    <link rel="stylesheet" type="text/css" href="../../css's/auth/signup.css">
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

    <?php if (isset($_GET['success'])) { ?>
        <p class="success" id="successMessage"><?php echo $_GET['success']; ?></p>
        <script>
            setTimeout(function() {
                document.getElementById('successMessage').classList.add('hide');
            }, 1000);
        </script>
    <?php } ?>

    <?php if (isset($_GET['error'])) { ?>
        <p class="error" id="errorMessage"><?php echo $_GET['error']; ?></p>
        <script>
            setTimeout(function() {
                document.getElementById('errorMessage').classList.add('hide');
            }, 5000);
        </script>
    <?php } ?>

    <form action="" method="post">
        <div class="container">
            <div class="login form">
                <header>Create your Account</header>
                
                <input type="text" id="first_name" name="first_name" placeholder="First Name">
                <input type="text" id="last_name" name="last_name" placeholder="Last Name">
                <input type="text" id="email_address" name="email_address" placeholder="Email Address">
                <p style="font-size: 15px; color:gray;"><i>Your password must be at least 8 characters long and contain at least one capital letter and one number</i></p>
                <input type="password" id="password" name="password" placeholder="Password">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password">

                
                <button type="submit" name="submit" class="button">Create</button>
                
                <div class="login">
                    <span class="login">Already have an account?
                        <a href="login.php">Login</a>
                    </span>
                </div>
            </div>
        </div>
    </form>
</body>
</html>
