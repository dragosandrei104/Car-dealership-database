<?php

include('Connect.php');


if (isset($_GET['location_address'])) {
    $location_address = $_GET['location_address'];

    
    $employee_sql = "
        SELECT e.* 
        FROM Employee e
        JOIN Branch_Office b ON e.Office_ID = b.Office_ID
        WHERE b.Location_Address = ?
    ";
    $params = array($location_address);
    $stmt = sqlsrv_query($conn, $employee_sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    
    if (sqlsrv_has_rows($stmt)) {
        echo "<table border='1' cellpadding='5' cellspacing='0'>
                <thead>
                    <tr>
                        
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Position</th>
                        <th>Gender</th>
                        <th>SSN</th>
                        <th>Location Address</th>
                        <th>Phone</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>";
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>
                    
                    <td>" . htmlspecialchars($row['First_Name']) . "</td>
                    <td>" . htmlspecialchars($row['Last_Name']) . "</td>
                    <td>" . htmlspecialchars($row['Position']) . "</td>
                    <td>" . htmlspecialchars($row['Gender']) . "</td>
                    <td>" . htmlspecialchars($row['SSN']) . "</td>
                    <td>" . htmlspecialchars($row['Location_Address']) . "</td>
                    <td>" . htmlspecialchars($row['Phone']) . "</td>
                    <td>" . htmlspecialchars($row['Email']) . "</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No employees found for Location Address: " . htmlspecialchars($location_address) . ".</p>";
    }

    sqlsrv_free_stmt($stmt);
} else {
    echo "<p>Invalid request.</p>";
}
?>
