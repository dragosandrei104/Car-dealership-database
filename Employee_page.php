<?php

session_start();


if (!isset($_SESSION['Username'])) {
    header("Location: BD.html");
    exit;
}


include('Connect.php');


$current_username = $_SESSION['Username'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Dashboard</title>
</head>
<body>
    <div class="header">
        <h1>Welcome to the Employee Dashboard</h1>
    </div>
    <div class="content">
        <h2>Employee Details</h2>
        <?php
        
        $employee_sql = "SELECT 
                            Employee_ID, 
                            First_Name, 
                            Last_Name,
                            Position, 
                            Gender, 
                            SSN, 
                            Location_Address, 
                            Phone, 
                            Email 
                         FROM Employee 
                         WHERE Employee_Username = ?";
        
        $params = array($current_username);
        $stmt = sqlsrv_query($conn, $employee_sql, $params);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        $employee_id = null;
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
                $employee_id = $row['Employee_ID']; // Save Employee_ID for filtering sold vehicles
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
            echo "<p>No employee details found for the current user.</p>";
        }

        sqlsrv_free_stmt($stmt);
        ?>

        <h2>Sold Vehicles for Current Employee</h2>
        <?php
        if ($employee_id !== null) {
            
            $sold_vehicles_sql = "SELECT SV.Sale_ID, SV.Sale_Date, SV.Vehicle_ID, SV.Aggreed_Price, V.Make, V.Model
                                  FROM Sold_Vehicles SV
                                  INNER JOIN Vehicle V ON V.Vehicle_ID = SV.Vehicle_ID
                                  WHERE SV.Employee_ID = ?";
            
            $params = array($employee_id);
            $stmt = sqlsrv_query($conn, $sold_vehicles_sql, $params);

            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }

            if (sqlsrv_has_rows($stmt)) {
                echo "<table border='1' cellpadding='5' cellspacing='0'>
                        <thead>
                            <tr>
                                <th>Sale ID</th>
                                <th>Sale Date</th>
                                <th>Vehicle ID</th>
                                <th>Make</th>
                                <th>Model</th>
                                <th>Aggreed Price</th>
                            </tr>
                        </thead>
                        <tbody>";
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['Sale_ID']) . "</td>
                            <td>" . htmlspecialchars($row['Sale_Date']->format('Y-m-d')) . "</td>
                            <td>" . htmlspecialchars($row['Vehicle_ID']) . "</td>
                            <td>" . htmlspecialchars($row['Make']) . "</td>
                            <td>" . htmlspecialchars($row['Model']) . "</td>
                            <td>" . htmlspecialchars($row['Aggreed_Price']) . "</td>
                          </tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No sold vehicles found for the current employee.</p>";
            }

            sqlsrv_free_stmt($stmt);
        } else {
            echo "<p>Employee details not found. Cannot fetch sold vehicles.</p>";
        }
        ?>

<h2>Customer List</h2>
<?php
if ($employee_id !== null) {
    
    $customer_list_sql = "SELECT 
                              CL.Customer_ID, 
                              CL.Employee_ID, 
                              CASE WHEN CL.Customer_Status = 1 THEN 'Yes' ELSE 'No' END AS Customer_Status,
                              C.First_Name, 
                              C.Last_Name
                          FROM Customer_List CL
                          INNER JOIN Customer C ON C.Customer_ID = CL.Customer_ID
                          WHERE CL.Employee_ID = ?";

    $params = array($employee_id);
    $stmt = sqlsrv_query($conn, $customer_list_sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    if (sqlsrv_has_rows($stmt)) {
        echo "<table border='1' cellpadding='5' cellspacing='0'>
                <thead>
                    <tr>
                        
                        <th>First Name</th>
                        <th>Last Name</th>
                        
                        <th>Customer Status</th>
                    </tr>
                </thead>
                <tbody>";
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>
                    
                    <td>" . htmlspecialchars($row['First_Name']) . "</td>
                    <td>" . htmlspecialchars($row['Last_Name']) . "</td>
                    
                    <td>" . htmlspecialchars($row['Customer_Status']) . "</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No customer list found for the current employee.</p>";
    }

    sqlsrv_free_stmt($stmt);
} else {
    echo "<p>Employee details not found. Cannot fetch customer list.</p>";
}
?>



        <h2>Sold Vehicle with the Highest Agreed Price</h2>
        <?php
        if ($employee_id !== null) {
            
            $highest_price_sql = "SELECT SV.Sale_ID, SV.Sale_Date, SV.Vehicle_ID, SV.Aggreed_Price, V.Make, V.Model
                                  FROM Sold_Vehicles SV
                                  RIGHT JOIN Vehicle V ON V.Vehicle_ID = SV.Vehicle_ID
                                  WHERE SV.Employee_ID = ? AND SV.Aggreed_Price >= ANY (SELECT M.Aggreed_Price FROM Sold_Vehicles M WHERE M.Sale_ID = SV.Sale_ID)
                                  ORDER BY SV.Aggreed_Price DESC";
            
            $params = array($employee_id);
            $stmt = sqlsrv_query($conn, $highest_price_sql, $params);

            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }

            if (sqlsrv_has_rows($stmt)) {
                echo "<table border='1' cellpadding='5' cellspacing='0'>
                        <thead>
                            <tr>
                                
                                <th>Sale Date</th>
                                
                                <th>Make</th>
                                <th>Model</th>
                                <th>Aggreed Price</th>
                            </tr>
                        </thead>
                        <tbody>";
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    echo "<tr>
                            
                            <td>" . htmlspecialchars($row['Sale_Date']->format('Y-m-d')) . "</td>
                            
                            <td>" . htmlspecialchars($row['Make']) . "</td>
                            <td>" . htmlspecialchars($row['Model']) . "</td>
                            <td>" . htmlspecialchars($row['Aggreed_Price']) . "</td>
                          </tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No sold vehicle found with the highest agreed price for the current employee.</p>";
            }

            sqlsrv_free_stmt($stmt);
        } else {
            echo "<p>Employee details not found. Cannot fetch highest-priced sold vehicle.</p>";
        }
        ?>
    </div>
</body>
</html>
