<?php 
session_start();
include '../../db_conn.php';

if (isset($_GET['id'])) {

    $PID = $_SESSION['id']; 
    $sql = "SELECT * FROM `patients` WHERE `patient_id`='$PID'";
    $result = $conn->query($sql); 

    if ($result->num_rows > 0) {        
        while ($row = $result->fetch_assoc()) {
            $PID = $row['patient_id'];
            $FirstName = $row['first_name'];
            $MiddleName = $row['middle_name'];
            $LastName = $row['last_name'];
            $BirthDate = $row['birthdate'];
            $Age = $row['age'];
            $Gender = $row['gender'];
            $ContactNumber = $row['contact_number'];
            $EmailAddress = $row['email_address'];
            $Weight = $row['weight'];
        }
    }

    $Usql = "SELECT * FROM `users` WHERE `id`='$PID'";
    $Uresult = $conn->query($Usql); 

    if ($Uresult->num_rows > 0) {        
        while ($row = $Uresult->fetch_assoc()) {
            $FirstName2 = $row['first_name'];
            $LastName2 = $row['last_name'];
            $EmailAddress2 = $row['email_address'];
        }
    }
    
    if (isset($_POST['submit'])) {
        $PatientID = $_SESSION['id'];
        $FirstName = $_POST['first_name'];
        $MiddleName = $_POST['middle_name'];
        $LastName = $_POST['last_name'];
        $Age = $_POST['age'];

        // Retrieve the selected values from the dropdowns
        $birthdate_day = $_POST['birthdate_day'];
        $birthdate_month = $_POST['birthdate_month'];
        $birthdate_year = $_POST['birthdate_year'];
    
        // Combine the values into a single date string in 'YYYY-MM-DD' format
        $birthdate = $birthdate_year . '-' . $birthdate_month . '-' . $birthdate_day;
        $ContactNumber = $_POST['Contact_Number'];
        $EmailAddress = $_POST['email_address'];
        $Weight = $_POST['weight'];
        $Gender = $_POST['gender'];

        if (empty($FirstName)) {
            header("Location: edit_profile.php?id=$PID&error=First%20Name%20is%20required");
            exit();

        }else if(empty($LastName)){
            header("Location: edit_profile.php?id=$PID&error=Last%20Name%20is%20required");
            exit();

        }else if(empty($Age)){
            header("Location: edit_profile.php?id=$PID&error=Age%20is%20required");
            exit();
            
        }else if(empty($ContactNumber)){
            header("Location: edit_profile.php?id=$PID&error=Contact%20Number%20is%20required");
            exit();

        }else if(empty($EmailAddress)){
            header("Location: edit_profile.php?id=$PID&error=Email%20Address%20is%20required");
            exit();

        }else if(empty($Gender)){
            header("Location: edit_profile.php?id=$PID&error=Gender%20is%20required");
            exit();

        }else{

        $Usql = "UPDATE users SET
                first_name = \"$FirstName\",
                last_name = \"$LastName\",
                email_address = \"$EmailAddress\"
            WHERE id = '$PatientID'";

        $Uresult = mysqli_query($conn, $Usql);

        // Check if patient_id already exists
        $checkQuery = "SELECT COUNT(*) as count FROM users WHERE id = '$PatientID'";
        $checkResult = $conn->query($checkQuery);

        if ($checkResult->num_rows > 0) {
            $row = $checkResult->fetch_assoc();
            $patientExists = $row['count'] > 0;
        } else {
            $patientExists = false;
        }

        // Construct SQL query
        if ($patientExists) {
            // Perform an update
            $Psql = "UPDATE patients SET
                        first_name = \"$FirstName\",
                        middle_name = \"$MiddleName\",
                        last_name = \"$LastName\",
                        last_visit = '$LastVisit',
                        age = '$Age',
                        birthdate = '$birthdate',
                        gender = '$Gender',
                        weight = '$Weight',
                        email_address = \"$EmailAddress\",
                        contact_number = '$ContactNumber',
                        created_at = '$currentDateTime'
                    WHERE patient_id = '$PatientID'";
        } else {
            // Perform an insert
            $Psql = "INSERT INTO `patients`(`patient_id`, `first_name`, `middle_name`, `last_name`, `last_visit`, `birthdate`, `age`, `gender`, `weight`, `email_address`, `contact_number`, `created_at`) 
                    VALUES ('$PatientID', \"$FirstName\", \"$MiddleName\",\"$LastName\", '$LastVisit', '$birthdate', '$Age', '$Gender', '$Weight', \"$EmailAddress\", '$ContactNumber', '$currentDateTime')";
        }

        $result = mysqli_query($conn, $Psql);

        if ($result && $Uresult == TRUE) {
            header("Location: view_profile.php?success=Profile Updated!");
                exit();
                if (isset($_GET['error']) || isset($_GET['error'])) {
                    unset($_GET['error']);
                    unset($_GET['success']);
                }
        } else {
            echo "Error:". $Psql . "<br>". $conn->error;
        }
            $conn->close();  
        }
    }

    ?>
<!DOCTYPE html>
<html>
<head>
    <title>Aquino Samontanes Dental Clinic</title>
    <link rel="stylesheet" type="text/css" href="../../css's/patient/request.css">
    <link rel="icon" href="../../pics/Logo.png" type="image/png">
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

            if (isset($_SESSION['id']) && isset($_SESSION['email_address'])) {

                $sql = "SELECT *
                FROM users";

                $result = mysqli_query($conn, $sql);
                if (!$result) {
                    die("Error: " . mysqli_error($conn));
                }
                ?>
                <li class="welcomeName"><a href="view_profile.php">Welcome, <?php echo $_SESSION['first_name']; ?> <?php echo $_SESSION['last_name']; ?></a></li>
                <?php
                } else {
                    
                }
                ?>
                <li><a href="Location.php">Location</a></li>
                <li><a href="staff.php">Staffs</a></li>
                <li><a href="service.php">Services</a></li>
                <li><a href="Calendar.php">Your Appointments</a></li>
                <li><a href="../auth/logout.php">Logout</a></li>
                <li><a href="Request.php" class="btn-nav">Request an Appointment</a></li>
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
            }, 2000);
        </script>
    <?php } ?>
        <a href="view_profile.php"><button class="back-button">Back</button></a>

    
    <form action="" method="post">

        <label>
            <span>First Name <b class="star">*</b></span>
            <input type="text" name="first_name" value="<?php echo isset($FirstName2) ? $FirstName2 : '' ?>">
        </label><br>


        <label>
            <span>Middle Name</span>
            <input type="text" name="middle_name" value="<?php echo isset($MiddleName) ? $MiddleName : '' ?>">
        </label><br>

        <label>
            <span>Last Name <b class="star">*</b></span>
            <input type="text" name="last_name" value="<?php echo isset($LastName2) ? $LastName2 : '' ?>">
        </label><br>

        <label>
            <span>Birthdate <b class="star">*</b></span>
            <!-- Day -->
            <select name="birthdate_day" onchange="calculateAge()">
                <?php for ($day = 1; $day <= 31; $day++) : ?>
                    <option value="<?php echo $day; ?>" <?php if ($day == date('d', strtotime($BirthDate))) echo 'selected'; ?>><?php echo $day; ?></option>
                <?php endfor; ?>
            </select>
            <!-- Month -->
            <select name="birthdate_month" onchange="calculateAge()">
                <?php
                $months = [
                    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June',
                    7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                ];
                foreach ($months as $monthNum => $monthName) : ?>
                    <option value="<?php echo $monthNum; ?>" <?php if ($monthNum == date('m', strtotime($BirthDate))) echo 'selected'; ?>><?php echo $monthName; ?></option>
                <?php endforeach; ?>
            </select>
            <!-- Year -->
            <select name="birthdate_year" onchange="calculateAge()">
                <?php
                $currentYear = date('Y');
                for ($year = $currentYear; $year >= $currentYear - 100; $year--) : ?>
                    <option value="<?php echo $year; ?>" <?php if ($year == date('Y', strtotime($BirthDate))) echo 'selected'; ?>><?php echo $year; ?></option>
                <?php endfor; ?>
            </select>
        </label><br>

        <label>
            <span>Age</span>
            <input type="number" id="age" name="age" value="<?php echo isset($Age) ? $Age : '' ?>" readonly>
        </label><br>

        <script>
            function calculateAge() {
                var birthdate_day = document.getElementsByName('birthdate_day')[0].value;
                var birthdate_month = document.getElementsByName('birthdate_month')[0].value;
                var birthdate_year = document.getElementsByName('birthdate_year')[0].value;

                // Create a new Date object using the birthdate components
                var birthdate = new Date(birthdate_year, birthdate_month - 1, birthdate_day);

                // Get the current date
                var today = new Date();

                // Calculate the age
                var age = today.getFullYear() - birthdate.getFullYear();

                // Adjust age if the birthday hasn't occurred yet this year
                if (today.getMonth() < birthdate.getMonth() || (today.getMonth() == birthdate.getMonth() && today.getDate() < birthdate.getDate())) {
                    age--;
                }

                // Update the age input field
                document.getElementById('age').value = age;
            }
        </script>

        <label>
            <span>Contact Number <b class="star">*</b></span>
            <input type="number" name="Contact_Number" value="<?php echo isset($ContactNumber) ? $ContactNumber : '' ?>">
        </label><br>

        <label>
            <span>Email <b class="star">*</b></span>
            <input type="text" name="email_address" value="<?php echo isset($EmailAddress2) ? $EmailAddress2 : '' ?>">
        </label><br>

        <label>
            <span>Weight(kg)</span>
            <input type="number" name="weight" step="0.01" value="<?php echo isset($Weight) ? $Weight : '' ?>">
        </label><br>

        <label>
            <span>Gender <b class="star">*</b></span>
            <input type="radio" name="gender" value="M" id="gender_male" <?php if (isset($Gender) && $Gender === "M") echo "checked"; ?>>
            <label for="gender_male">Male</label><br>
            
            <input type="radio" name="gender" value="F" id="gender_female" <?php if (isset($Gender) && $Gender === "F") echo "checked"; ?>>
            <label for="gender_female">Female</label>
        </label><br>

        <button type="submit" name="submit" class="submit_button">Submit</button>
    </form>


    <script src="../../js's/scriptindex.js"></script>
    <script src="https://kit.fontawesome.com/595a890311.js" crossorigin="anonymous"></script>
</body>
</html>

<?php
}else{
    header("Location: ../auth/login.php");
    exit();
}
?>

