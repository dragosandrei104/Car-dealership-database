<?php

session_start();
include('Connect.php');


$avg_price_sql = "
    SELECT 
        E.Employee_ID, 
        E.First_Name, 
        E.Last_Name, 
        E.Position, 
        AVG(SV.Aggreed_Price) AS AvgProfit
    FROM Employee E
    INNER JOIN Sold_Vehicles SV 
    ON E.Employee_ID = SV.Employee_ID
    GROUP BY E.Employee_ID, E.First_Name, E.Last_Name, E.Position
    HAVING AVG(SV.Aggreed_Price) >= ALL (
            SELECT 
                AVG(A.Aggreed_Price) 
            FROM 
                Sold_Vehicles A 
            WHERE 
                A.Employee_ID = E.Employee_ID
        )";

$avg_price_stmt = sqlsrv_query($conn, $avg_price_sql);

if ($avg_price_stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

if (sqlsrv_has_rows($avg_price_stmt)) {
    echo "<table border='1' cellpadding='5' cellspacing='0'>
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Position</th>
                    <th>Average Agreed Price</th>
                </tr>
            </thead>
            <tbody>";
    while ($row = sqlsrv_fetch_array($avg_price_stmt, SQLSRV_FETCH_ASSOC)) {
        echo "<tr>
                <td>" . htmlspecialchars($row['First_Name']) . "</td>
                <td>" . htmlspecialchars($row['Last_Name']) . "</td>
                <td>" . htmlspecialchars($row['Position']) . "</td>
                <td>" . htmlspecialchars(number_format($row['AvgProfit'], 2)) . "</td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p>No employee found with the greatest average profit.</p>";
}

sqlsrv_free_stmt($avg_price_stmt);
