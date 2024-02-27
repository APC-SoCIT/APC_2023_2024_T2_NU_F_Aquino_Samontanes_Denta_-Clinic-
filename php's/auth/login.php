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
            $sql = "SELECT * FROM users WHERE email_address='$email' AND password='$pass'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);

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
                header("Location: login.php?error=Incorrect User name or password");
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
                <li><a href="../patient/Location.php">Our Location</a></li>
                <li><a href="">Dentist & Reviews</a></li>
                <li><a href="">Our Services</a></li>
                <li><a href="">Blog</a></li>
                <li><a href="">Contact Us</a></li>
                <li><a href="login.php">Log-in</a></li>
                <li><a href="login.php" class="btn-nav">Request an Appointment</a></li>
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
