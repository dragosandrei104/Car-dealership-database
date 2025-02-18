<?php
include('Connect.php');

if (isset($_GET['sign_date'])) {
    $sign_date = $_GET['sign_date'];

    $query = "
        SELECT 
            c.Contract_ID,
            c.Sign_Date,
            c.Dealership_ID,
            c.Employee_ID,
            c.Customer_ID,
            c.Vehicle_ID,
            v.Make,
            v.Model,
            v.isNew,
            v.isAvailable,
            p.Insurance_ID,
            p.Effective_Date,
            p.Expire_Date,
            p.Total_Amount,
            p.isActive
        FROM Contract c
        LEFT JOIN Vehicle v ON c.Vehicle_ID = v.Vehicle_ID
        LEFT JOIN Insurance_Policy p ON c.Vehicle_ID = p.Vehicle_ID
        WHERE CONVERT(VARCHAR, c.Sign_Date, 23) = ?
    ";
    $params = array($sign_date);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    if (sqlsrv_has_rows($stmt)) {
        echo "<table border='1' cellpadding='5' cellspacing='0'>
                <thead>
                    <tr>
                        
                        <th>Sign Date</th>
                        
                        <th>Vehicle Make</th>
                        <th>Vehicle Model</th>
                        <th>Is New</th>
                        <th>Is Available</th>
                        
                        <th>Effective Date</th>
                        <th>Expire Date</th>
                        <th>Total Amount</th>
                        <th>Is Active</th>
                    </tr>
                </thead>
                <tbody>";
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>
                    
                    <td>" . (!is_null($row['Sign_Date']) ? htmlspecialchars($row['Sign_Date']->format('Y-m-d')) : 'N/A') . "</td>
                    
                    <td>" . htmlspecialchars($row['Make']) . "</td>
                    <td>" . htmlspecialchars($row['Model']) . "</td>
                    <td>" . ($row['isNew'] ? 'Yes' : 'No') . "</td>
                    <td>" . ($row['isAvailable'] ? 'Yes' : 'No') . "</td>
                   
                    <td>" . (!is_null($row['Effective_Date']) ? htmlspecialchars($row['Effective_Date']->format('Y-m-d')) : 'N/A') . "</td>
                    <td>" . (!is_null($row['Expire_Date']) ? htmlspecialchars($row['Expire_Date']->format('Y-m-d')) : 'N/A') . "</td>
                    <td>" . htmlspecialchars($row['Total_Amount']) . "</td>
                    <td>" . ($row['isActive'] ? 'Yes' : 'No') . "</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No data found for Sign Date: " . htmlspecialchars($sign_date) . ".</p>";
    }

    sqlsrv_free_stmt($stmt);
} else {
    echo "<p>Invalid request.</p>";
}
?>
