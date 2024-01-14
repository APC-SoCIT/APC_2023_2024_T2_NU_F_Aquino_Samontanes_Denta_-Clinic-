<?php 
session_start();
include '../../db_conn.php';

    if (isset($_GET['patient_id'])) {
        $PID = $_GET['patient_id'];
        $archived = 'no';

    $sql = "UPDATE `patients` SET `is_archived`='$archived' WHERE `patient_id`='$PID'";
    
    $result = $conn->query($sql);

    if ($result == TRUE) {
        header("Location: ArchivedRecords.php?success=Patient Record Retrieved");
        exit();

    }else{
        echo "Error:" . $sql . "<br>" . $conn->error;
    }
}
?>