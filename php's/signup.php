<?php
include "../db_conn.php";

if (isset($_POST['submit'])) {
    $FirstName = $_POST['first_name'];
    $LastName = $_POST['last_name'];
    $EmailAddress = $_POST['email_address'];
    $PassWord = $_POST['password'];
    $Confirm = $_POST['confirm_password'];
    $UserRole = "patient";

    // Check if email already exists in the database
    $check_sql = "SELECT * FROM tbl_users WHERE email_address = '$EmailAddress'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        header("Location: signup.php?error=Email address already exists");
        exit();
    }

    if (empty($FirstName) || empty($LastName) || empty($EmailAddress) || empty($PassWord) || empty($Confirm) || empty($UserRole)) {
        header("Location: signup.php?error=Kindly fill all fields");
        exit();
    } elseif ($PassWord != $Confirm) {
        header("Location: signup.php?error=Password does not match");
        exit();
    } else {

        $sql = "INSERT INTO `tbl_users`(`first_name`, `last_name`, `email_address`, `password`, `user_role`) 
            VALUES ('$FirstName', '$LastName', '$EmailAddress','$PassWord', '$UserRole')";

        $result = $conn->query($sql);

        if ($result === TRUE) {
            header("Location: login.php?success=New record created successfully");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aquino Samontanes Dental Clinic</title>
    <link rel="stylesheet" type="text/css" href="../css's/signup.css">
</head>

<body>
    <form action="" method="post">
        <div class="container">
            <div class="login form">
                <header>Create your Account</header>
                <?php if (isset($_GET['error'])) { ?>
                    <p class="error"><?php echo $_GET['error']; ?></p>
                <?php } ?>
                <?php if (isset($_GET['success'])) { ?>
                    <p class="success"><?php echo $_GET['success']; ?></p>
                <?php } ?>
                
                <input type="text" id="first_name" name="first_name" placeholder="First Name">
                <input type="text" id="last_name" name="last_name" placeholder="Last Name">
                <input type="text" id="email_address" name="email_address" placeholder="Email Address">
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
