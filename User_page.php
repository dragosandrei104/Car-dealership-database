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
    <title>Customer Dashboard</title>
</head>
<body>
    <div class="header">
        <h1>Welcome to the Customer Dashboard</h1>
    </div>
    <div class="content">
        <h2>Customer Details</h2>
        <?php
        
        $customer_sql = "SELECT 
                            Customer_ID, 
                            First_Name, 
                            Last_Name, 
                            Gender, 
                            SSN, 
                            Location_Address, 
                            Phone, 
                            Email 
                         FROM Customer 
                         WHERE Customer_Username = ?";
        
        $params = array($current_username);
        $stmt = sqlsrv_query($conn, $customer_sql, $params);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        
        if (sqlsrv_has_rows($stmt)) {
            echo "<table border='1' cellpadding='5' cellspacing='0'>
                    <thead>
                        <tr>
                            <th>Customer ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Gender</th>
                            <th>SSN</th>
                            <th>Location Address</th>
                            <th>Phone</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>";
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['Customer_ID']) . "</td>
                        <td>" . htmlspecialchars($row['First_Name']) . "</td>
                        <td>" . htmlspecialchars($row['Last_Name']) . "</td>
                        <td>" . htmlspecialchars($row['Gender']) . "</td>
                        <td>" . htmlspecialchars($row['SSN']) . "</td>
                        <td>" . htmlspecialchars($row['Location_Address']) . "</td>
                        <td>" . htmlspecialchars($row['Phone']) . "</td>
                        <td>" . htmlspecialchars($row['Email']) . "</td>
                      </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No customer details found for the current user.</p>";
        }

        sqlsrv_free_stmt($stmt);
        ?>

        <h2>Vehicle, Ticket, and Insurance Details</h2>
        <?php
        
        $vehicle_ticket_insurance_sql = "SELECT 
                                            V.Vehicle_ID, 
                                            V.Make, 
                                            V.Model, 
                                            V.isNew, 
                                            V.isAvailable, 
                                            V.Office_ID, 
                                            ST.Ticket_ID, 
                                            ST.Location_Address AS Ticket_Location, 
                                            ST.Phone AS Ticket_Phone, 
                                            ST.Email AS Ticket_Email, 
                                            ST.Checkup_Date,
                                            IP.Insurance_ID,
                                            COALESCE(IP.Effective_Date, 'N/A') AS Effective_Date,
                                            COALESCE(IP.Expire_Date, 'N/A') AS Expire_Date,
                                            COALESCE(IP.Total_Amount, 'N/A') AS Total_Amount,
                                            COALESCE(IP.isActive, 'N/A') AS isActive
                                       FROM Vehicle V
                                       INNER JOIN Service_Ticket ST ON V.Vehicle_ID = ST.Vehicle_ID
                                       LEFT JOIN Insurance_Policy IP ON V.Vehicle_ID = IP.Vehicle_ID
                                       WHERE V.Vehicle_ID IN (SELECT Vehicle_ID FROM Customer WHERE Customer_Username = ?)";
        
        $stmt = sqlsrv_query($conn, $vehicle_ticket_insurance_sql, $params);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        
        if (sqlsrv_has_rows($stmt)) {
            echo "<table border='1' cellpadding='5' cellspacing='0'>
                    <thead>
                        <tr>
                            <th>Vehicle ID</th>
                            <th>Make</th>
                            <th>Model</th>
                            <th>New</th>
                            <th>Available</th>
                            <th>Office ID</th>
                            <th>Ticket ID</th>
                            <th>Ticket Location</th>
                            <th>Ticket Phone</th>
                            <th>Ticket Email</th>
                            <th>Checkup Date</th>
                            <th>Insurance ID</th>
                            <th>Effective Date</th>
                            <th>Expire Date</th>
                            <th>Total Amount</th>
                            <th>Is Active</th>
                        </tr>
                    </thead>
                    <tbody>";
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['Vehicle_ID']) . "</td>
                        <td>" . htmlspecialchars($row['Make']) . "</td>
                        <td>" . htmlspecialchars($row['Model']) . "</td>
                        <td>" . ($row['isNew'] ? 'Yes' : 'No') . "</td>
                        <td>" . ($row['isAvailable'] ? 'Yes' : 'No') . "</td>
                        <td>" . htmlspecialchars($row['Office_ID']) . "</td>
                        <td>" . htmlspecialchars($row['Ticket_ID']) . "</td>
                        <td>" . htmlspecialchars($row['Ticket_Location']) . "</td>
                        <td>" . htmlspecialchars($row['Ticket_Phone']) . "</td>
                        <td>" . htmlspecialchars($row['Ticket_Email']) . "</td>
                        <td>" . htmlspecialchars($row['Checkup_Date']->format('Y-m-d')) . "</td>
                        <td>" . htmlspecialchars($row['Insurance_ID']) . "</td>
                        <td>" . htmlspecialchars($row['Effective_Date']->format('Y-m-d')) . "</td>
                        <td>" . htmlspecialchars($row['Expire_Date']->format('Y-m-d')) . "</td>
                        <td>" . htmlspecialchars($row['Total_Amount']) . "</td>
                        <td>" . htmlspecialchars($row['isActive'] ? 'Yes' : 'No') . "</td>
                      </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No vehicle, ticket, or insurance details found for the current user.</p>";
        }

        sqlsrv_free_stmt($stmt);
        ?>
    </div>
</body>
</html>
