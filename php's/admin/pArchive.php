<?php 
session_start();
include '../../db_conn.php';

    if (isset($_GET['patient_id'])) {
        $PID = $_GET['patient_id'];
        $archived = 'yes';

    $sql = "UPDATE `patients` SET `is_archived`='$archived' WHERE `patient_id`='$PID'";
    
    $result = $conn->query($sql);

    if ($result == TRUE) {
        header("Location: pTable.php?error=Patient Record Archived");
        exit();

    }else{
        echo "Error:" . $sql . "<br>" . $conn->error;
    }
}
?>