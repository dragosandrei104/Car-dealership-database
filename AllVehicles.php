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
    <title>Vehicles</title>
    <script>
        function showSpecs(vehicleID) {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "specs.php?vehicle_id=" + vehicleID, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById("specs-data").innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        function filterVehicles(isNew) {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "filter_vehicles.php?isNew=" + isNew, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById("vehicles-table-body").innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        function deleteVehicle(vehicleID) {
        if (confirm("Are you sure you want to delete this vehicle?")) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_vehicle.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    alert(xhr.responseText);
                    
                    location.reload();
                }
            };
            xhr.send("vehicle_id=" + vehicleID);
        }
    }

        function showVehiclesWheelchair() {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "vehicleswheelchair.php", true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById("vehicleswheelchair-data").innerHTML = xhr.responseText;
                }
        };
            xhr.send();
        }


    

    </script>
</head>
<body>
    <div class="header">
        <h1>Welcome to the Vehicles Dashboard</h1>
    </div>
    <div class="content">
        
        <h2>Vehicles</h2>
        <button onclick="showVehiclesWheelchair()">Show Vehicles without Wheelchair Access</button>
        <div id="vehicleswheelchair-data">
            <p>Click the button to see the Vehicles without Wheelchair Access.</p>
        </div>
        <button onclick="filterVehicles(0)">Show First-Hand Vehicles</button>
        <button onclick="filterVehicles(1)">Show Second-Hand Vehicles</button>
        

        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Show specs.</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>New</th>
                    <th>Available</th>
                    
                </tr>
            </thead>
            <tbody id="vehicles-table-body">
                <?php
                $branch_sql = "SELECT * FROM Vehicle";
                $branch_stmt = sqlsrv_query($conn, $branch_sql);

                if ($branch_stmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }

                while ($branch_row = sqlsrv_fetch_array($branch_stmt, SQLSRV_FETCH_ASSOC)) {
                    echo "<tr>
                        <td>
                            <input 
                                type='radio' 
                                name='vehicle_id' 
                                value='" . htmlspecialchars($branch_row['Vehicle_ID']) . "' 
                                onclick='showSpecs(\"" . htmlspecialchars($branch_row['Vehicle_ID']) . "\")'>
                        </td>
                        <td>" . htmlspecialchars($branch_row['Make']) . "</td>
                        <td>" . htmlspecialchars($branch_row['Model']) . "</td>
                        <td>" . htmlspecialchars($branch_row['isNew'] ? 'Yes' : 'No') . "</td>
                        <td>" . htmlspecialchars($branch_row['isAvailable'] ? 'Yes' : 'No') . "</td>
            
                        <td>
                            <button onclick='deleteVehicle(" . htmlspecialchars($branch_row['Vehicle_ID']) . ")'>Delete</button>
                        </td>
                    </tr>";
                }

                sqlsrv_free_stmt($branch_stmt);
                ?>
            </tbody>
        </table>

        <h2>Specifications</h2>
        <div id="specs-data">
            <p>Click on a Vehicle to view its specs.</p>
        </div>

    </div>
</body>
</html>
