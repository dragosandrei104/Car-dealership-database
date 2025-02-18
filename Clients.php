<?php

session_start();
if (!isset($_SESSION['Username'])) {
    header("Location: BD.html");
    exit;
}


include('Connect.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Clients</title>
    <script>
        function showTickets(vehicleID) {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "ticket.php?vehicle_id=" + vehicleID, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById("ticket-data").innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

    </script>
</head>
<body>
    <div class="header">
        <h1>Welcome to the Clients Dashboard</h1>
    </div>
    <div class="content">
        
        <h2>Clients</h2>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Customer_ID</th>
                    <th>First_Name</th>
                    <th>Last_Name</th>
                    <th>Gender</th>
                    <th>SSN</th>
                    <th>Location Address</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Select</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $branch_sql = "SELECT * FROM Customer";
                $branch_stmt = sqlsrv_query($conn, $branch_sql);

                if ($branch_stmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }

                while ($branch_row = sqlsrv_fetch_array($branch_stmt, SQLSRV_FETCH_ASSOC)) {
                    echo "<tr>
                                <td>" . htmlspecialchars($branch_row['Customer_ID']) . "</td>
                                <td>" . htmlspecialchars($branch_row['First_Name']) . "</td>
                                <td>" . htmlspecialchars($branch_row['Last_Name']) . "</td>
                                <td>" . htmlspecialchars($branch_row['Gender']) . "</td>
                                <td>" . htmlspecialchars($branch_row['SSN']) . "</td>
                                <td>" . htmlspecialchars($branch_row['Location_Address']) . "</td>
                                <td>" . htmlspecialchars($branch_row['Phone']) . "</td>
                                <td>" . htmlspecialchars($branch_row['Email']) . "</td>
                                <td>
                                    <input 
                                        type='radio' 
                                        name='vehicle_id' 
                                        value='" . htmlspecialchars($branch_row['Vehicle_ID']) . "' 
                                        onclick='showTickets(\"" . htmlspecialchars($branch_row['Vehicle_ID']) . "\")'>
                                 </td>
                                </tr>";

                       
                }
                
                sqlsrv_free_stmt($branch_stmt);
                ?>
            </tbody>

            
        </table>
        
        <h2>Tickets</h2>
        <div id="ticket-data">
            <p>Click on a Vehicle ID to view its tickets.</p>
        </div>
        

</body>
</html>