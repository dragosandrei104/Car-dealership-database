<?php

include('Connect.php');


$sql = "SELECT 
            V.Vehicle_ID, 
            V.Make, 
            V.Model, 
            V.isAvailable, 
            V.isNew, 
            V.Office_ID
        FROM Vehicle V
        WHERE V.Vehicle_ID NOT IN (
            SELECT S.Vehicle_ID 
            FROM Specifications S 
            WHERE S.hasWheelchariAccess = 1
        )";

$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}


if (sqlsrv_has_rows($stmt)) {
    echo "<table border='1' cellpadding='5' cellspacing='0'>
        <thead>
            <tr>
                <th>Show specs.</th>
                <th>Make</th>
                <th>Model</th>
                <th>isNew</th>
                <th>isAvailable</th>
                
            </tr>
        </thead>
        <tbody>";

    
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        echo "<tr>
                <td>
                    <input 
                        type='radio' 
                        name='vehicle_id' 
                        value='" . htmlspecialchars($row['Vehicle_ID']) . "' 
                        onclick='showSpecs(\"" . htmlspecialchars($row['Vehicle_ID']) . "\")'>
                </td>
                <td>" . htmlspecialchars($row['Make']) . "</td>
                <td>" . htmlspecialchars($row['Model']) . "</td>
                <td>" . htmlspecialchars($row['isNew'] ? 'Yes' : 'No') . "</td>
                <td>" . htmlspecialchars($row['isAvailable'] ? 'Yes' : 'No') . "</td>
                
              </tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<p>No vehicles found without wheelchair access.</p>";
}

sqlsrv_free_stmt($stmt);
?>
