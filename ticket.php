<?php

include('Connect.php');


if (isset($_GET['vehicle_id'])) {
    $vehicle_id = intval($_GET['vehicle_id']);

    
    $ticket_sql = "SELECT * FROM Service_Ticket WHERE Vehicle_ID = ?";
    $params = array($vehicle_id);
    $stmt = sqlsrv_query($conn, $ticket_sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    
    if (sqlsrv_has_rows($stmt)) {
        echo "<table border='1' cellpadding='5' cellspacing='0'>
                <thead>
                    <tr>
                        <th>Ticket_ID</th>
                        <th>Location_Address</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Checkup_Date</th>
                        <th>Vehicle_ID</th>
                    </tr>
                </thead>
                <tbody>";
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['Ticket_ID']) . "</td>
                    <td>" . htmlspecialchars($row['Location_Address']) . "</td>
                    <td>" . htmlspecialchars($row['Phone']) . "</td>
                    <td>" . htmlspecialchars($row['Email']) . "</td>
                    <td>" . htmlspecialchars($row['Checkup_Date']->format('Y-m-d')) . "</td>
                    <td>" . htmlspecialchars($row['Vehicle_ID']) . "</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No tickets found for Vehicle ID: " . htmlspecialchars($vehicle_id) . ".</p>";
    }

    sqlsrv_free_stmt($stmt);
} else {
    echo "<p>Invalid request.</p>";
}
?>
