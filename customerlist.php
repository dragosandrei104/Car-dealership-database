<?php

include('Connect.php');


if (isset($_GET['employee_id'])) {
    $employee_id = intval($_GET['employee_id']);

    
    $employee_sql = "SELECT
            CL.Customer_ID,
            C.First_Name,
            C.Last_Name,
            C.Gender,
            C.SSN,
            C.Location_Address,
            C.Phone,
            C.Email,
            CL.Customer_Status
        FROM Customer_List CL
        LEFT JOIN Customer C ON CL.Customer_ID = C.Customer_ID
        WHERE CL.Employee_ID = ?";
    $params = array($employee_id);
    $stmt = sqlsrv_query($conn, $employee_sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    
    if (sqlsrv_has_rows($stmt)) {
        echo "<table border='1' cellpadding='5' cellspacing='0'>
                <thead>
                    <tr>
                        
                        <th>First_Name</th>
                        <th>Last_Name</th>
                        <th>Gender</th>
                        <th>SSN</th>
                        <th>Location_Address</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Customer_Status</th>
                    </tr>
                </thead>
                <tbody>";
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>
                    
                    <td>" . htmlspecialchars($row['First_Name']) . "</td>
                    <td>" . htmlspecialchars($row['Last_Name']) . "</td>
                    <td>" . htmlspecialchars($row['Gender']) . "</td>
                    <td>" . htmlspecialchars($row['SSN']) . "</td>
                    <td>" . htmlspecialchars($row['Location_Address']) . "</td>
                    <td>" . htmlspecialchars($row['Phone']) . "</td>
                    <td>" . htmlspecialchars($row['Email']) . "</td>
                    <td>" . htmlspecialchars($row['Customer_Status'] ? 'Yes.' : 'No.') . "</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No customers found for Employee ID: " . htmlspecialchars($employee_id) . ".</p>";
    }

    sqlsrv_free_stmt($stmt);
} else {
    echo "<p>Invalid request.</p>";
}
?>
