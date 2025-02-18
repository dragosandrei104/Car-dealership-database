<?php

include('Connect.php');


if (isset($_GET['sign_date'])) {
    $sign_date = $_GET['sign_date'];

    
    $query = "
        SELECT
            CN.Contract_ID,
            CT.Customer_ID,
            CT.First_Name AS Customer_First_Name,
            CT.Last_Name AS Customer_Last_Name,
            CT.SSN AS Customer_SSN,
            E.Employee_ID,
            E.First_Name AS Employee_First_Name,
            E.Last_Name AS Employee_Last_Name,
            E.SSN AS Employee_SSN
        FROM Contract CN
        LEFT JOIN Customer CT ON CT.Customer_ID = CN.Customer_ID
        LEFT JOIN Employee E ON CN.Employee_ID = E.Employee_ID
        WHERE CONVERT(VARCHAR, CN.Sign_Date, 23) = ?
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
                        <th>Contract_ID</th>
                        <th>Customer_ID</th>
                        <th>Customer First Name</th>
                        <th>Customer Last Name</th>
                        <th>Customer SSN</th>
                        <th>Employee_ID</th>
                        <th>Employee First Name</th>
                        <th>Employee Last Name</th>
                        <th>Employee SSN</th>
                    </tr>
                </thead>
                <tbody>";
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['Contract_ID']) . "</td>
                    <td>" . htmlspecialchars($row['Customer_ID']) . "</td>
                    <td>" . htmlspecialchars($row['Customer_First_Name']) . "</td>
                    <td>" . htmlspecialchars($row['Customer_Last_Name']) . "</td>
                    <td>" . htmlspecialchars($row['Customer_SSN']) . "</td>
                    <td>" . htmlspecialchars($row['Employee_ID']) . "</td>
                    <td>" . htmlspecialchars($row['Employee_First_Name']) . "</td>
                    <td>" . htmlspecialchars($row['Employee_Last_Name']) . "</td>
                    <td>" . htmlspecialchars($row['Employee_SSN']) . "</td>
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
