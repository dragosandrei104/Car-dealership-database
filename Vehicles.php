<?php

include('Connect.php');

if (isset($_GET['location_address'])) {
    $location_address = $_GET['location_address'];

    
    $vehicle_sql = "
        SELECT
            v.Vehicle_ID,
            v.Make,
            v.Model,
            v.isNew,
            v.isAvailable,
            v.Office_ID,
            s.Specs_ID,
            s.Int_Color,
            s.Ext_Color,
            s.Fuel_Type,
            s.Fuel_Consumption,
            s.Emission_Class,
            s.Wheeldrive_Type,
            s.N_Seats,
            s.N_Doors,
            s.Transmission_Type,
            s.Hatchback_Vol,
            s.hasWheelchariAccess,
            s.Misc
        FROM Vehicle v
        LEFT JOIN Specifications s ON v.Vehicle_ID = s.Vehicle_ID
        JOIN Branch_Office b ON v.Office_ID = b.Office_ID
        WHERE b.Location_Address = ?
    ";
    $params = array($location_address);
    $vehicle_stmt = sqlsrv_query($conn, $vehicle_sql, $params);

    if ($vehicle_stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    echo "<table border='1' cellpadding='5' cellspacing='0'>
            <thead>
                <tr>
                    
                    <th>Make</th>
                    <th>Model</th>
                    <th>New</th>
                    <th>Available</th>
                    
                    
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

    
    if (sqlsrv_has_rows($vehicle_stmt)) {
        while ($row = sqlsrv_fetch_array($vehicle_stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>
                    
                    <td>" . htmlspecialchars($row['Make']) . "</td>
                    <td>" . htmlspecialchars($row['Model']) . "</td>
                    <td>" . htmlspecialchars($row['isNew'] ? 'Yes.' : 'No.') . "</td>
                    <td>" . htmlspecialchars($row['isAvailable'] ? 'Yes.' : 'No.') . "</td>
                    
                    
                    <td>" . htmlspecialchars($row['Int_Color']) . "</td>
                    <td>" . htmlspecialchars($row['Ext_Color']) . "</td>
                    <td>" . htmlspecialchars($row['Fuel_Type']) . "</td>
                    <td>" . htmlspecialchars($row['Fuel_Consumption']) . "</td>
                    <td>" . htmlspecialchars($row['Emission_Class']) . "</td>
                    <td>" . htmlspecialchars($row['Wheeldrive_Type']) . "</td>
                    <td>" . htmlspecialchars($row['N_Seats']) . "</td>
                    <td>" . htmlspecialchars($row['Transmission_Type']) . "</td>
                    <td>" . htmlspecialchars($row['Hatchback_Vol']) . "</td>
                    <td>" . htmlspecialchars($row['hasWheelchariAccess'] ? 'Yes.' : 'No.') . "</td>
                    <td>" . htmlspecialchars($row['Misc']) . "</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No vehicles found for Location Address: " . htmlspecialchars($location_address) . ".</td></tr>";
    }

    echo "</tbody></table>";

    
    sqlsrv_free_stmt($vehicle_stmt);
} else {
    echo "<p>Invalid request.</p>";
}
?>
