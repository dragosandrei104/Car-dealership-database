<?php

include('Connect.php');


if (isset($_GET['vehicle_id'])) {
    $vehicle_id = intval($_GET['vehicle_id']);

    
    $employee_sql = "SELECT * FROM Specifications WHERE Vehicle_ID = ?";
    $params = array($vehicle_id);
    $stmt = sqlsrv_query($conn, $employee_sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

   
    if (sqlsrv_has_rows($stmt)) {
        echo "<table border='1' cellpadding='5' cellspacing='0'>
                <thead>
                    <tr>
                        
                        <th>Interior color</th>
                        <th>Exterior color</th>
                        <th>Fuel Type</th>
                        <th>Fuel consumption</th>
                        <th>Emission class</th>
                        <th>Wheeldrive type</th>
                        <th>Number of seats</th>
                        <th>Number of doors</th>
                        <th>Transmission type</th>
                        <th>Hatchback volume</th>
                        <th>Wheelchair access</th>
                        <th>Misc.</th>
                    </tr>
                </thead>
                <tbody>";
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>
                    
                    <td>" . htmlspecialchars($row['Int_Color']) . "</td>
                    <td>" . htmlspecialchars($row['Ext_Color']) . "</td>
                    <td>" . htmlspecialchars($row['Fuel_Type']) . "</td>
                    <td>" . htmlspecialchars($row['Fuel_Consumption']) . "</td>
                    <td>" . htmlspecialchars($row['Emission_Class']) . "</td>
                    <td>" . htmlspecialchars($row['Wheeldrive_Type']) . "</td>
                    <td>" . htmlspecialchars($row['N_Seats']) . "</td>
                    <td>" . htmlspecialchars($row['Wheeldrive_Type']) . "</td>
                    <td>" . htmlspecialchars($row['Transmission_Type']) . "</td>
                    <td>" . htmlspecialchars($row['Hatchback_Vol']) . "</td>
                    <td>" . htmlspecialchars($row['hasWheelchariAccess'] ? 'Yes.' : 'No.') . "</td>
                    <td>" . htmlspecialchars($row['Misc']) . "</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No specs found for Vehicle ID: " . htmlspecialchars($vehicle_id) . ".</p>";
    }

    sqlsrv_free_stmt($stmt);
} else {
    echo "<p>Invalid request.</p>";
}
?>
