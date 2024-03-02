<?php
include '../../../db_conn.php';

$column = $_GET['column'];
$orderBy = htmlspecialchars($column);

// Determine the sorting order
$order = isset($_GET['order']) && ($_GET['order'] === 'desc') ? 'DESC' : 'ASC';

$sql = "SELECT DISTINCT patient_id, first_name, middle_name, last_name, gender, contact_number
        FROM patients 
        WHERE is_archived != 'yes'
        ORDER BY $orderBy $order";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Output rows as needed
        echo "<tr>";
        echo "<td>{$row['patient_id']}</td>";
        echo "<td>{$row['first_name']}</td>";
        echo "<td>{$row['middle_name']}</td>";
        echo "<td>{$row['last_name']}</td>";
        echo "<td>{$row['gender']}</td>";
        echo "<td>{$row['contact_number']}</td>";
        echo "<td style='margin: auto;'><a href='pView.php?patient_id=" . $row['patient_id'] . "'><button class=\"btn-nav\">View</button></a>
        <a href='pUpdate.php?patient_id=" . $row['patient_id'] . "'><button class=\"btn-nav\">Update</button></a>
        <a href='#' class='custom-link' onclick='openModal();'><button class=\"btn-nav cancel\">Archive</button></a>
        </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7'>No data found</td></tr>";
}

mysqli_close($conn);
?>
