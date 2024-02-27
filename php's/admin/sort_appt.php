<?php
include '../../db_conn.php';

$column = $_GET['column'];
$orderBy = htmlspecialchars($column);

// Determine the sorting order
$order = isset($_GET['order']) && ($_GET['order'] === 'desc') ? 'DESC' : 'ASC';

$sql = "SELECT DISTINCT appointments.*, patients.first_name, patients.middle_name, patients.last_name, patients.gender
    FROM appointments 
    INNER JOIN patients ON appointments.patient_id = patients.patient_id
    WHERE appointments.appointment_condition='approved'
        ORDER BY $orderBy $order";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>{$row['patient_id']}</td>";
        echo "<td>{$row['first_name']}</td>";
        echo "<td>{$row['middle_name']}</td>";
        echo "<td>{$row['last_name']}</td>";
        echo "<td>{$row['date_of_appointment']}</td>";
        echo "<td>{$row['gender']}</td>";
        echo "<td>{$row['appointment_condition']}</td>";
        echo "<td>";
        echo "<div style=\"text-align: center; display: flex; flex-align: horizontal;\">";
        echo "<form method='post'>";
        echo "<button class=\"btn-nav\" type='submit' name='Done' value='{$row['id']}'>Done</button>";
        echo "</form>";
        echo "<button class=\"btn-nav\" style=\"\" onclick=\"openModal('{$row['id']}')\">Cancel</button>";
        echo "</div>";
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7'>No data found</td></tr>";
}

mysqli_close($conn);
?>
