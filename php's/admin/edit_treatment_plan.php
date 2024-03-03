<?php 
session_start();
include '../../db_conn.php';

if (isset($_GET['id'])) {
    $ID = $_GET['id']; 
    $isChecked = true;
    
    $plansql = "SELECT * FROM `treatment_plan` WHERE `id`='$ID'";
    $planresult = $conn->query($plansql); 

    if ($planresult->num_rows > 0) {        
        while ($row = $planresult->fetch_assoc()) {
            $ID = $row['id'];
            $PID = $row['patient_id'];
            $TreatmentPlan = $row['treatment_plan'];
            $Progress = $row['progress'];
            $Prophylaxis = $row['Prophylaxis'];
            $Date = $row['date'];
            $formattedDate = date('Y-m-d\TH:i', strtotime($Date));
            $Comment = $row['comments'];
            $Extraction = $row['extraction'];
            $ToothExtraction = $row['teeth_extraction'];
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

    if(isset($_POST["xray_submit"])) {
        // File upload path
        $targetDir = "../../pics/xrays/";
        
        // Loop through each file
        foreach($_FILES['photos']['name'] as $key=>$val){
            $fileName = basename($_FILES["photos"]["name"][$key]);
            $targetFilePath = $targetDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    
            // Allow certain file formats
            $allowedTypes = array('jpg', 'png', 'jpeg', 'gif');
            if(in_array($fileType, $allowedTypes)) {
                // Upload file to server
                if(move_uploaded_file($_FILES["photos"]["tmp_name"][$key], $targetFilePath)) {
                    // Insert image file name into database
                    $insert = $conn->query("INSERT into xrays (treatment_plan_id, xray_film, created_at) VALUES ('$ID', '".$fileName."', NOW())");
                    if(!$insert) {
                        echo "File upload failed, please try again.";
                    }
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            } else {
                echo 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.';
            }
        }
    }

    if(isset($_POST["submit"])) {
        $TreatmentPlan = $_POST['dropdown'];
        $Progress = $_POST['Progress'];
        $Prophylaxis = $_POST['Prophylaxis'];
        $Date = $_POST['date'];
        $timestamp = strtotime($Date);
        $DateOfAppointmentFormatted = date('m/d/y H:i', $timestamp);
        $Comment = $_POST['comments'];
        $Extraction = $_POST['extraction'];
        $selectedTeeth = serialize($_POST['teeth']);
        

        if (empty($TreatmentPlan)) {
            header("Location: edit_treatment_plan.php?id=$ID&error=Treatment Plan is required");
            exit();
        } elseif (empty($Progress)) {
            header("Location: edit_treatment_plan.php?id=$ID&error=Progress is required");
            exit();
        } elseif (empty($Prophylaxis)) {
            header("Location: edit_treatment_plan.php?id=$ID&error=Fill if patient needs prophylaxis");
            exit();
        } elseif (empty($Extraction)) {
            header("Location: edit_treatment_plan.php?id=$ID&error=Fill if patient needs tooth extraction");
            exit();
        } else {
            // Prepare SQL statement for updating data
            $updatesql = "UPDATE treatment_plan SET treatment_plan='$TreatmentPlan', progress='$Progress', Prophylaxis='$Prophylaxis', date='$DateOfAppointmentFormatted', extraction='$Extraction', teeth_extraction='$selectedTeeth', comments='$Comment' WHERE `id`='$ID'";

            if ($conn->query($updatesql) === TRUE) {
                header("Location: view_treatment_plan.php?id=$ID&success=Treatment Plan Updated");
                exit();
            } else {
                echo "Error updating record: " . $conn->error;
            }

            $conn->close();
        }
    }
?>
<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Aquino Samontanes Dental Clinic</title>
        <link rel="stylesheet" type="text/css" href="../../css's/admin/EditTP.css">
        <script>
            // JavaScript functions to open and close the modal
            function openModal() {
                document.getElementById("overlay").style.display = "block";
                document.getElementById("uploadModal").style.display = "block";
            }

            function closeModal() {
                document.getElementById("overlay").style.display = "none";
                document.getElementById("uploadModal").style.display = "none";
            }
        </script>
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
        <a href="view_treatment_plan.php?id=<?php echo $ID; ?>"><button class="back-button">Back</button></a>
        <div class="card">
            <div class="card-content">
                <div class="column1">

                    <form action="" method="post">

                    <label for="dropdown">Treatment Plan:
                    <select id="dropdown" name="dropdown">
                        <option value="Jacket Crown Preparation">Jacket Crown Preparation</option>
                        <option value="Teeth Restoration">Teeth Restoration</option>
                        <option value="Root Canal Treatment">Root Canal Treatment</option>
                        <option value="Surgery">Surgery</option>
                        <option value="Removal Denture Construction">Removal Denture Construction</option>
                    </select>
                    </label>

                    <label>
                        <span>Progress:</span>
                        <input type="radio" name="Progress" value="Ongoing" id="Progress_Ongoing" <?php if ($Progress === "Ongoing") echo "checked"; ?>> 
                        <label for="Progress_Ongoing">Ongoing</label><br>
                        
                        <input type="radio" name="Progress" value="Done" id="Progress_Done" <?php if ($Progress === "Done") echo "checked"; ?>>
                        <label for="Progress_Done">Done</label><br>

                        <input type="radio" name="Progress" value="Cancelled" id="Progress_Cancelled" <?php if ($Progress === "Cancelled") echo "checked"; ?>>
                        <label for="Progress_Cancelled">Cancelled</label>
                    </label>

                    <label>
                        <span>Oral Prophylaxis:</span>
                        <input type="radio" name="Prophylaxis" value="yes" id="Prophylaxis_yes" <?php if ($Prophylaxis === "yes") echo "checked"; ?>> 
                        <label for="Prophylaxis_yes">Yes</label><br>

                        <input type="radio" name="Prophylaxis" value="no" id="Prophylaxis_no" <?php if ($Prophylaxis === "no") echo "checked"; ?>> 
                        <label for="Prophylaxis_no">No</label>
                    </label>

                    <label>
                        <span>Date of Treatment:</span>
                        <input type="datetime-local" id="dateTimePicker" name="date" value="<?php echo $formattedDate ?>">
                    </label>
                    
                    

                    <label>
                        <span>Tooth Extraction?</span>
                        
                        <input type="radio" name="extraction" value="yes" id="extraction_yes" onclick="showCheckBox()"> 
                        <label for="extraction_yes">Yes</label><br>

                        <input type="radio" name="extraction" value="no" id="extraction_no" onclick="hideCheckBox()"> 
                        <label for="extraction_no">No</label>
                    </label>

                    <div id="tooth-container">
                        Upper Arch<hr> <br>    
                        <div class="tooth-column">    
                            <label for="tooth1">
                                <input type="checkbox" id="tooth1" name="teeth[]" value="1" >
                                Central incisor (Upper - right)
                            </label><br>

                            <label for="tooth2">
                                <input type="checkbox" id="tooth2" name="teeth[]" value="2">
                                Central incisor (Upper - left)
                            </label><br>

                            <label for="tooth3">
                                <input type="checkbox" id="tooth3" name="teeth[]" value="3">
                                Lateral incisor (Upper - right)
                            </label><br>

                            <label for="tooth4">
                                <input type="checkbox" id="tooth4" name="teeth[]" value="4">
                                Lateral incisor (Upper - left)
                            </label><br>

                            <label for="tooth5">
                                <input type="checkbox" id="tooth5" name="teeth[]" value="5">
                                Canine (Upper - right)
                            </label><br>

                            <label for="tooth6">
                                <input type="checkbox" id="tooth6" name="teeth[]" value="6">
                                Canine (Upper - left)
                            </label><br>

                            <label for="tooth7">
                                <input type="checkbox" id="tooth7" name="teeth[]" value="7">
                                First premolar (Upper - right)
                            </label><br>

                            <label for="tooth8">
                                <input type="checkbox" id="tooth8" name="teeth[]" value="8">
                                First premolar (Upper - left)
                            </label><br>
                        </div>
                        <div class="tooth-column">
                        <label for="tooth9">
                            <input type="checkbox" id="tooth9" name="teeth[]" value="9">
                                Second premolar (Upper - right)
                            </label><br>

                            <label for="tooth10">
                                <input type="checkbox" id="tooth10" name="teeth[]" value="10">
                                Second premolar (Upper - left)
                            </label><br>

                            <label for="tooth11">
                                <input type="checkbox" id="tooth11" name="teeth[]" value="11">
                                First molar (Upper - right)
                            </label><br>

                            <label for="tooth12">
                                <input type="checkbox" id="tooth12" name="teeth[]" value="12">
                                First molar (Upper - left)
                            </label><br>

                            <label for="tooth13">
                                <input type="checkbox" id="tooth13" name="teeth[]" value="13">
                                Second molar (Upper - right)
                            </label><br>

                            <label for="tooth14">
                                <input type="checkbox" id="tooth14" name="teeth[]" value="14">
                                Second molar (Upper - left)
                            </label><br>

                            <label for="tooth15">
                                <input type="checkbox" id="tooth15" name="teeth[]" value="15">
                                Third molar (Upper - right)
                            </label><br>

                            <label for="tooth16">
                                <input type="checkbox" id="tooth16" name="teeth[]" value="16">
                                Third molar (Upper - left)
                            </label><br>
                        </div>

                        Lower Arch<hr> <br>

                        <div class="tooth-column">        
                            <label for="tooth17">
                                <input type="checkbox" id="tooth17" name="teeth[]" value="17" >
                                Central incisor (Lower - right)
                            </label><br>

                            <label for="tooth18">
                                <input type="checkbox" id="tooth18" name="teeth[]" value="18">
                                Central incisor (Lower - left)
                            </label><br>

                            <label for="tooth19">
                                <input type="checkbox" id="tooth19" name="teeth[]" value="19">
                                Lateral incisor (Lower - right)
                            </label><br>

                            <label for="tooth20">
                                <input type="checkbox" id="tooth20" name="teeth[]" value="20">
                                Lateral incisor (Lower - left)
                            </label><br>

                            <label for="tooth21">
                                <input type="checkbox" id="tooth21" name="teeth[]" value="21">
                                Canine (Lower - right)
                            </label><br>

                            <label for="tooth22">
                                <input type="checkbox" id="tooth22" name="teeth[]" value="22">
                                Canine (Lower - left)
                            </label><br>

                            <label for="tooth23">
                                <input type="checkbox" id="tooth23" name="teeth[]" value="23">
                                First premolar (Lower - right)
                            </label><br>

                            <label for="tooth24">
                                <input type="checkbox" id="tooth24" name="teeth[]" value="24">
                                First premolar (Lower - left)
                            </label><br>
                        </div>
                        <div class="tooth-column">
                        <label for="tooth25">
                            <input type="checkbox" id="tooth25" name="teeth[]" value="25">
                                Second premolar (Lower - right)
                            </label><br>

                            <label for="tooth26">
                                <input type="checkbox" id="tooth26" name="teeth[]" value="26">
                                Second premolar (Lower - left)
                            </label><br>

                            <label for="tooth27">
                                <input type="checkbox" id="tooth27" name="teeth[]" value="27">
                                First molar (Lower - right)
                            </label><br>

                            <label for="tooth28">
                                <input type="checkbox" id="tooth28" name="teeth[]" value="28">
                                First molar (Lower - left)
                            </label><br>

                            <label for="tooth29">
                                <input type="checkbox" id="tooth29" name="teeth[]" value="29">
                                Second molar (Lower - right)
                            </label><br>

                            <label for="tooth30">
                                <input type="checkbox" id="tooth30" name="teeth[]" value="30">
                                Second molar (Lower - left)
                            </label><br>

                            <label for="tooth31">
                                <input type="checkbox" id="tooth31" name="teeth[]" value="31">
                                Third molar (Lower - right)
                            </label><br>

                            <label for="tooth32">
                                <input type="checkbox" id="tooth32" name="teeth[]" value="32">
                                Third molar (Lower - left)
                            </label><br>
                        </div>
                    </div>


                    <script>
                        function showCheckBox() {
                        document.getElementById('tooth-container').style.opacity = '1';
                        document.getElementById('tooth-container').style.pointerEvents = 'auto';

                        }

                        function hideCheckBox() {
                        document.getElementById('tooth-container').style.opacity = '.4';
                        document.getElementById('tooth-container').style.pointerEvents = 'none';

                        var checkboxes = document.querySelectorAll('input[type="checkbox"]');
                        checkboxes.forEach(function(checkbox) {
                            checkbox.checked = false;
                        });
                        }
                    </script>

                </div>
                <div class="column2">

                    <label>
                        <span>Comments: </span>
                        <textarea name="comments" rows="3" cols="50"style="resize: none;"><?php echo $Comment; ?> </textarea>
                    </label>

                    <button type="submit" name="submit" class="edit-button">Save</button>

                    </form>
                    <!-- Button to open the modal -->
                    <button onclick="openModal()">Upload X-ray Films</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal -->
    <div class="overlay" id="overlay" onclick="closeModal()"></div> <!-- Overlay -->
    <div class="modal" id="uploadModal">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Upload Photos</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="file" name="photos[]" id="photos" accept="image/*" class="upload_bttn" multiple>
            <input type="submit" value="Upload" name="xray_submit" class="upload_bttn">
        </form>
    </div>

</body>
</html>

    <?php
    } else{ 
        header('Location: pTable.php');
    } 
?> 