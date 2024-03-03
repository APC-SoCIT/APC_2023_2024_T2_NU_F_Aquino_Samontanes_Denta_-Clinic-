<?php 
session_start();
include '../../db_conn.php';

if (isset($_GET['id'])) {
    $ID = $_GET['id']; 
    
    $plansql = "SELECT * FROM `treatment_plan` WHERE `id`='$ID'";
    $planresult = $conn->query($plansql); 

    if ($planresult->num_rows > 0) {        
        while ($row = $planresult->fetch_assoc()) {
            $PID = $row['patient_id'];
            $TreatmentPlan = $row['treatment_plan'];
            $Progress = $row['progress'];
            $Date = $row['date'];
            $Prophylaxis = $row['Prophylaxis'];
            $Comment = $row['comments'];
            $selectedTeeth = unserialize($row['teeth_extraction']);
        }
    }

    $sql = "SELECT * FROM `patients` WHERE `patient_id`='$PID'";
    $result = $conn->query($sql); 

    if ($result->num_rows > 0) {        
        while ($row = $result->fetch_assoc()) {
            $PID = $row['patient_id'];
            $FirstName = $row['first_name'];
            $MiddleName = $row['middle_name'];
            $LastName = $row['last_name'];
            $Gender = $row['gender'];
            $ContactNumber = $row['contact_number'];
            $EmailAddress = $row['email_address'];
        }
    }

    $xsql = "SELECT * FROM `xrays` WHERE `treatment_plan_id`='$ID'";
    $xresult = $conn->query($xsql); 

    

?>
<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Aquino Samontanes Dental Clinic</title>
        <link rel="stylesheet" type="text/css" href="../../css's/admin/ViewTP.css">
  
    </head>
<html>
<body>
    <header>
        <nav>
            <div class="logo">
                <a href="#" aria-label="Homepage">
                    <img src="../../pics/Logo.png" alt="" class="src">
                </a>
            </div>
            <ul>
                <li class="dropdown">
                    <a href="CheckAppointments.php" class="dropbtn">Appointments</a>
                    <div class="dropdown-content">
                        <a href="CheckAppointments.php">Check Appointments</a>
                        <a href="Calendar.php">Appointment Calendar</a>
                        <a href="FinishedAppts.php">Finished Appointments</a>
                        <a href="CancelledAppts.php">Cancelled Appointments</a>
                    </div>
                </li>
                <li><a href="pTable.php" class="sel_page">Patient Records Table</a></li>
                <li><a href="ArchivedRecords.php">Archived Records</a></li>
                <li><a href="../auth/logout.php">Logout</a></li>
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
            }, 1000);
        </script>
    <?php } ?>

    <!-- Center the container horizontally -->
    <div class="container">
        <!-- Back button outside the card, aligned with the left side of the container -->
        <a href="pView.php?patient_id=<?php echo $PID; ?>"><button class="back-button">Back</button></a>
        <!-- Edit button outside the card, aligned with the right side of the container -->
        <a href="edit_treatment_plan.php?id=<?php echo $ID; ?>"><button class="edit-button">Edit</button></a>
        <!-- Single card with two columns inside -->
        <div class="card">
            <div class="card-content">
                <div class="column1">
                    <h2><?php echo $FirstName; ?> <?php echo $MiddleName; ?> <?php echo $LastName; ?></h2>
                    <p><strong>Treatment Plan:</strong> <?php echo $TreatmentPlan; ?></p>
                    <p><strong>Progress:</strong> <?php echo $Progress; ?></p>
                    <p><strong>Date:</strong> <?php echo $Date; ?></p>
                    <p><strong>Prophylaxis:</strong> <?php echo $Prophylaxis; ?></p>
                    <p style="line-height:1.5em; margin-top: 5px;"><strong>Tooth Extraction:</strong> 
                    <?php 
                    // Map of tooth numbers to descriptions
                    $toothDescriptions = [
                        1 => "Central incisor (Upper - right)",
                        2 => "Central incisor (Upper - left)",
                        3 => "Lateral incisor (Upper - right)",
                        4 => "Lateral incisor (Upper - left)",
                        5 => "Canine (Upper - right)",
                        6 => "Canine (Upper - left)",
                        7 => "First premolar (Upper - right)",
                        8 => "First premolar (Upper - left)",
                        9 => "Second premolar (Upper - right)",
                        10 => "Second premolar (Upper - left)",
                        11 => "First molar (Upper - right)",
                        12 => "First molar (Upper - left)",
                        13 => "Second molar (Upper - right)",
                        14 => "Second molar (Upper - left)",
                        15 => "Third molar (Upper - right)",
                        16 => "Third molar (Upper - left)",
                        17 => "Central incisor (Lower - right)",
                        18 => "Central incisor (Lower - left)",
                        19 => "Lateral incisor (Lower - right)",
                        20 => "Lateral incisor (Lower - left)",
                        21 => "Canine (Lower - right)",
                        22 => "Canine (Lower - left)",
                        23 => "First premolar (Lower - right)",
                        24 => "First premolar (Lower - left)",
                        25 => "Second premolar (Lower - right)",
                        26 => "Second premolar (Lower - left)",
                        27 => "First molar (Lower - right)",
                        28 => "First molar (Lower - left)",
                        29 => "Second molar (Lower - right)",
                        30 => "Second molar (Lower - left)",
                        31 => "Third molar (Lower - right)",
                        32 => "Third molar (Lower - left)"
                    ];

                    if (!empty($selectedTeeth)) {
                        // Initialize an array to store descriptions of selected teeth
                        $selectedTeethDescriptions = [];
                        
                        // Loop through each selected tooth number and get its description
                        foreach ($selectedTeeth as $toothNumber) {
                            // Check if the tooth number exists in the mapping
                            if (isset($toothDescriptions[$toothNumber])) {
                                // Add the description to the array
                                $selectedTeethDescriptions[] = $toothDescriptions[$toothNumber];
                            }
                        }

                        // Output the descriptions separated by commas
                        echo implode(", ", $selectedTeethDescriptions);
                    } else {
                        echo "No teeth selected"; // or any other appropriate message
                    }
                    ?>
                </p>



                </div>
                <div class="column2">
                    <button class="collapse-button" onclick="toggleComments()">View X-ray Films</button>
                    <div class="collapse-section">
                        <div class="xray-films" id="xray_films" style="display: none;">
                        <?php
                        if ($xresult->num_rows > 0) {        
                            while ($row = $xresult->fetch_assoc()) {
                                $Xray = $row['xray_film'];
                                $imagePath = "../../pics/xrays/" . $Xray; // Path to the image file
                                // Display the image using an HTML img tag
                                echo '<img src="' . $imagePath . '" alt="Xray Film" class="xray_films_img">';
                            }
                        } else {
                            echo "<p style='text-align: center; font-size: 18px; color: #555; background-color: #f7f7f7; padding: 10px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); margin-bottom: 1rem;'>There are no xray films yet.</p>";
                    
                        }
                        ?>
                        </div>
                    </div>
                    <h4>Comments</h4>
                    <div class="comments-box">
                        <textarea name="comments" rows="3" cols="50" readonly style="resize: none;"><?php echo $Comment; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleComments() {
            var xray_films = document.getElementById("xray_films");
            if (xray_films.style.display === "none") {
                xray_films.style.display = "block";
            } else {
                xray_films.style.display = "none";
            }
        }
    </script>
</body>
</html>

    <?php
    } else{ 
        header('Location: pTable.php');
    } 
?> 

