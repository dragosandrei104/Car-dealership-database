<?php

include('Connect.php');


$sql = "SELECT TOP 1 
            BO.Office_ID, 
            BO.Dealership_ID, 
            BO.Location_Address, 
            BO.Phone, 
            BO.Email, 
            BO.Name, 
            (SELECT COUNT(V.Vehicle_ID) FROM Vehicle V WHERE V.Office_ID = BO.Office_ID) AS VehicleCount
        FROM Branch_Office BO
        ORDER BY VehicleCount DESC";

$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}



if (sqlsrv_has_rows($stmt)) {
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    echo "<table border='1' cellpadding='5' cellspacing='0'>
        <thead>
            <tr>
                
                <th>Location Address</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Name</th>
                <th>Total Vehicles</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                
                <td>" . htmlspecialchars($row['Location_Address']) . "</td>
                <td>" . htmlspecialchars($row['Phone']) . "</td>
                <td>" . htmlspecialchars($row['Email']) . "</td>
                <td>" . htmlspecialchars($row['Name']) . "</td>
                <td>" . htmlspecialchars($row['VehicleCount']) . "</td>
            </tr>
        </tbody>
      </table>";
} else {
    echo "<p>No branch offices with vehicles found.</p>";
}

sqlsrv_free_stmt($stmt);
?>
