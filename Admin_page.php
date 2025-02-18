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
    <title>Admin Dashboard</title>
    <script>
        function showEmployees(locationAddress) {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "employees.php?location_address=" + encodeURIComponent(locationAddress), true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById("employee-data").innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        function showVehicles(locationAddress) {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "vehicles.php?location_address=" + encodeURIComponent(locationAddress), true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById("vehicle-data").innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        function showBranchWithMostVehicles() {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "mostvehicles.php", true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById("mostvehicles-data").innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

    </script>
</head>
<body>
    <div class="header">
        <h1>Welcome to the Admin Dashboard</h1>
    </div>
    <div class="content">
       
        <div>
            <button onclick="window.location.href='contracts.php';">Go to Contracts</button>
        </div>
        <div>
            <button onclick="window.location.href='clients.php';">Go to Clients</button>
        </div>
        <div>
            <button onclick="window.location.href='allemployees.php';">Go to Employees</button>
        </div>
        <div>
            <button onclick="window.location.href='allvehicles.php';">Go to Vehicles</button>
        </div>
        <div>
            <button onclick="window.location.href='add_vehicle.php';">Add Vehicle</button>
        </div>
        <div>
            <button onclick="window.location.href='add_contract.php';">Add Contract</button>
        </div>
        <button onclick="showBranchWithMostVehicles()">Show Branch Office with Most Vehicles</button>
        <div id="mostvehicles-data">
            <p>Click the button to see the branch office with the most vehicles.</p>
        </div>
    <div class="content">
        
        <h2>Branch Offices</h2>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    
                    <th>Location Address</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Name</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $branch_sql = "SELECT * FROM Branch_Office";
                $branch_stmt = sqlsrv_query($conn, $branch_sql);

                if ($branch_stmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }

                while ($branch_row = sqlsrv_fetch_array($branch_stmt, SQLSRV_FETCH_ASSOC)) {
                    echo "<tr>
                            
                            <td><a href='javascript:void(0)' onclick='showEmployees(\"" . htmlspecialchars($branch_row['Location_Address']) . "\"); showVehicles(\"" . htmlspecialchars($branch_row['Location_Address']) . "\")'>" . htmlspecialchars($branch_row['Location_Address']) . "</a></td>
                            <td>" . htmlspecialchars($branch_row['Phone']) . "</td>
                            <td>" . htmlspecialchars($branch_row['Email']) . "</td>
                            <td>" . htmlspecialchars($branch_row['Name']) . "</td>
                          </tr>";
                }
                
                sqlsrv_free_stmt($branch_stmt);
                ?>
            </tbody>
        </table>

        
        <h2>Employees</h2>
        <div id="employee-data">
            <p>Click on a Location Address to view its employees.</p>
        </div>

        
        <h2>Vehicles</h2>
        <div id="vehicle-data">
            <p>Click on a Location Address to view its vehicles.</p>
        </div>

        
    </div>
</body>
</html>