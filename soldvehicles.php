<?php

include('Connect.php');


if (isset($_GET['employee_id'])) {
    $employee_id = intval($_GET['employee_id']);

  
    $employee_sql = "SELECT
            E.Employee_ID,
            SV.Sale_ID,
            SV.Sale_Date,
            SV.Aggreed_Price,
            V.Vehicle_ID,
            V.Make,
            V.Model,
            V.isNew,
            V.isAvailable
        FROM Sold_Vehicles SV
        LEFT JOIN Employee E ON SV.Employee_ID = E.Employee_ID
        LEFT JOIN Vehicle V ON SV.Vehicle_ID = V.Vehicle_ID
        WHERE SV.Employee_ID = ?";
    $params = array($employee_id);
    $stmt = sqlsrv_query($conn, $employee_sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    
    if (sqlsrv_has_rows($stmt)) {
        echo "<table border='1' cellpadding='5' cellspacing='0'>
                <thead>
                    <tr>
                        
                        <th>Sale_Date</th>
                        <th>Aggreed_Price</th>
                        
                        <th>Make</th>
                        <th>Model</th>
                        <th>isNew</th>
                        <th>isAvailable</th>
                    </tr>
                </thead>
                <tbody>";
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>
                    
                    <td>" . htmlspecialchars($row['Sale_Date']->format('Y-m-d')) . "</td>
                    <td>" . htmlspecialchars($row['Aggreed_Price']) . "</td>
                    
                    <td>" . htmlspecialchars($row['Make']) . "</td>
                    <td>" . htmlspecialchars($row['Model']) . "</td>
                    <td>" . htmlspecialchars($row['isNew'] ? 'Yes.' : 'No.') . "</td>
                    <td>" . htmlspecialchars($row['isAvailable'] ? 'Yes.' : 'No.') . "</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No sold vehicles found for Employee ID: " . htmlspecialchars($employee_id) . ".</p>";
    }

    sqlsrv_free_stmt($stmt);
} else {
    echo "<p>Invalid request.</p>";
}
?>
