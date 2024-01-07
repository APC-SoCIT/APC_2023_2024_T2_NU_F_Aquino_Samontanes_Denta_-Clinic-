<?php
session_start();
include "../db_conn.php";

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
            header("Location: login.php?error=User Name is required");
            exit();
        } else if (empty($pass)) {
            header("Location: login.php?error=Password is required");
            exit();
        } else {
            $sql = "SELECT * FROM tbl_users WHERE email_address='$email' AND password='$pass'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);

                $_SESSION['first_name'] = $row['first_name'];
                $_SESSION['last_name'] = $row['last_name'];
                $_SESSION['email_address'] = $row['email_address'];
                $_SESSION['user_role'] = $row['user_role'];
                $_SESSION['id'] = $row['id'];

                if ($_SESSION['user_role'] == 'patient') {
                    header("Location: patient.php");
                    exit();
                } else {
                    header("Location: admin.php");
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
    <link rel="stylesheet" type="text/css" href="../css's/login.css">

</head>
<body>
    <form action="" method="post">
        <div class="container">
            <div class="login form">
                <header>Login</header>
                
                <?php if (isset($_GET['error'])) { ?>
                    <p class="error"><?php echo $_GET['error']; ?></p>
                <?php } ?>
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
