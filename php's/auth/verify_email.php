<!--
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Email Verification</h2>
        <?php
        // Check if email and verification code are provided in the URL
        if(isset($_GET['email']) && isset($_GET['code'])) {
            $EmailAddress = $_GET['email'];
            $VerificationCode = $_GET['code'];
            
            // Check if email and verification code exist in the database
            $check_sql = "SELECT * FROM users WHERE email_address = '$EmailAddress' AND verification_code = '$VerificationCode'";
            $check_result = $conn->query($check_sql);

            if ($check_result->num_rows > 0) {
                // Update user's status to verified
                $update_sql = "UPDATE users SET verified = 1 WHERE email_address = '$EmailAddress'";
                $update_result = $conn->query($update_sql);

                if ($update_result) {
                    echo "<p>Email verified successfully!</p>";
                } else {
                    echo "<p>Error verifying email.</p>";
                }
            } else {
                echo "<p>Invalid email or verification code.</p>";
            }
        } else {
            echo "<p>Email or verification code not provided.</p>";
        }
        ?>
    </div>
</body>
</html>
    -->