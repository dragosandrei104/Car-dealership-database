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
    <title>Employees</title>
    <script>

function showGreatestAvgProfit() {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "greatest_avg_profit.php", true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById("greatest-avg-result").innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }

        function showCustomerList(employeeID) {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "customerlist.php?employee_id=" + employeeID, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById("customerlist-data").innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        function showSoldVehicles(employeeID) {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "soldvehicles.php?employee_id=" + employeeID, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById("soldvehicles-data").innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }
    </script>
</head>
<body>
    <div class="header">
        <h1>Welcome to the Employees Dashboard</h1>
    </div>
    <div class="content">
        <h2>Employees</h2>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Select</th>
                    
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
            <tbody id="employee-table-body">
                <?php
                $branch_sql = "SELECT * FROM Employee";
                $branch_stmt = sqlsrv_query($conn, $branch_sql);

                if ($branch_stmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }

                while ($branch_row = sqlsrv_fetch_array($branch_stmt, SQLSRV_FETCH_ASSOC)) {
                    echo "<tr>
                            <td>
                                <input 
                                    type='radio' 
                                    name='employee' 
                                    onclick='showCustomerList(" . htmlspecialchars($branch_row['Employee_ID']) . "); showSoldVehicles(" . htmlspecialchars($branch_row['Employee_ID']) . ")' 
                                    id='employee_" . htmlspecialchars($branch_row['Employee_ID']) . "' />
                            </td>
                            
                            <td>" . htmlspecialchars($branch_row['First_Name']) . "</td>
                            <td>" . htmlspecialchars($branch_row['Last_Name']) . "</td>
                            <td>" . htmlspecialchars($branch_row['Position']) . "</td>
                            <td>" . htmlspecialchars($branch_row['Gender']) . "</td>
                            <td>" . htmlspecialchars($branch_row['SSN']) . "</td>
                            <td>" . htmlspecialchars($branch_row['Location_Address']) . "</td>
                            <td>" . htmlspecialchars($branch_row['Phone']) . "</td>
                            <td>" . htmlspecialchars($branch_row['Email']) . "</td>
                          </tr>";
                }
                sqlsrv_free_stmt($branch_stmt);
                ?>
            </tbody>
        </table>

        <h2>Customer list details</h2>
        <div id="customerlist-data">
            <p>Click on an Employee ID to view its customers.</p>
        </div>

        <h2>Sold vehicles details</h2>
        <div id="soldvehicles-data">
            <p>Click on an Employee ID to view its sold vehicles.</p>
        </div>

        <h2>Employee with the Greatest Average Profit</h2>
        <button onclick="showGreatestAvgProfit()">Show Employee with Greatest Average Profit</button>
        <div id="greatest-avg-result">
            <p>Click the button above to display the employee with the greatest average profit.</p>
        </div>
    </div>
</body>
</html>
