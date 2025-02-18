<?php

session_start();
if (!isset($_SESSION['Username'])) {
    header("Location: BD.html");
    exit;
}

include('Connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $vehicleID = $_POST['Vehicle_ID'];
    $make = $_POST['Make'];
    $model = $_POST['Model'];
    $isNew = isset($_POST['isNew']) ? 1 : 0;
    $isAvailable = isset($_POST['isAvailable']) ? 1 : 0;
    $officeID = $_POST['Office_ID'];

    
    $specsID = $_POST['Specs_ID'];
    $interiorColor = $_POST['Int_Color'];
    $exteriorColor = $_POST['Ext_Color'];
    $fuelType = $_POST['Fuel_Type'];
    $fuelConsumption = $_POST['Fuel_Consumption'];
    $emissionClass = $_POST['Emission_Class'];
    $wheelDriveType = $_POST['Wheeldrive_Type'];
    $numberOfSeats = $_POST['N_Seats'];
    $numberOfDoors = $_POST['N_Doors'];
    $transmissionType = $_POST['Transmission_Type'];
    $hatchbackVolume = $_POST['Hatchback_Vol'];
    $wheelchairAccess = isset($_POST['hasWheelchariAccess']) ? 1 : 0;
    $misc = $_POST['Misc'];

    
    $verify_sql = "SELECT COUNT(*) AS count FROM Branch_Office WHERE Office_ID = ?";
    $verify_params = array($officeID);
    $verify_stmt = sqlsrv_query($conn, $verify_sql, $verify_params);

    if ($verify_stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($verify_stmt, SQLSRV_FETCH_ASSOC);

    if ($row['count'] > 0) {
        
        $sql_vehicle = "INSERT INTO Vehicle (Vehicle_ID, Make, Model, isNew, isAvailable, Office_ID) VALUES (?, ?, ?, ?, ?, ?)";
        $params_vehicle = array($vehicleID, $make, $model, $isNew, $isAvailable, $officeID);
        $stmt_vehicle = sqlsrv_query($conn, $sql_vehicle, $params_vehicle);

        if ($stmt_vehicle === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        
$sql_specs = "INSERT INTO Specifications (Specs_ID, Vehicle_ID, Int_Color, Ext_Color, Fuel_Type, Fuel_Consumption, Emission_Class, 
Wheeldrive_Type, N_Seats, N_Doors, Transmission_Type, Hatchback_Vol, hasWheelchariAccess, Misc) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$params_specs = array(
$specsID, $vehicleID, $interiorColor, $exteriorColor, $fuelType, $fuelConsumption, $emissionClass,
$wheelDriveType, $numberOfSeats, $numberOfDoors, $transmissionType, $hatchbackVolume, $wheelchairAccess, $misc
);
$stmt_specs = sqlsrv_query($conn, $sql_specs, $params_specs);

if ($stmt_specs === false) {
die(print_r(sqlsrv_errors(), true));
}


        echo "<p>Vehicle and specifications added successfully!</p>";

        sqlsrv_free_stmt($stmt_vehicle);
        sqlsrv_free_stmt($stmt_specs);
    } else {
        
        echo "<p>Error: The provided Office ID does not exist. Please provide a valid Office ID.</p>";
    }

    sqlsrv_free_stmt($verify_stmt);
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Add Vehicle</title>
</head>
<body>
    <h1>Add a New Vehicle</h1>
    <form method="POST" action="add_vehicle.php">
        
        <fieldset>
            <legend>Vehicle Information</legend>
            <label for="Vehicle_ID">Vehicle ID:</label>
            <input type="text" id="Vehicle_ID" name="Vehicle_ID" required><br><br>

            <label for="Make">Make:</label>
            <input type="text" id="Make" name="Make" required><br><br>

            <label for="Model">Model:</label>
            <input type="text" id="Model" name="Model" required><br><br>

            <label for="isNew">Is New:</label>
            <input type="checkbox" id="isNew" name="isNew"><br><br>

            <label for="isAvailable">Is Available:</label>
            <input type="checkbox" id="isAvailable" name="isAvailable"><br><br>

            <label for="Office_ID">Office ID:</label>
            <input type="text" id="Office_ID" name="Office_ID" required><br><br>
        </fieldset>

        
        <fieldset>
            <legend>Vehicle Specifications</legend>
            <label for="Specs_ID">Specs ID:</label>
            <input type="text" id="Specs_ID" name="Specs_ID" required><br><br>

            <label for="Int_Color">Interior Color:</label>
            <input type="text" id="Int_Color" name="Int_Color"><br><br>

            <label for="Ext_Color">Exterior Color:</label>
            <input type="text" id="Ext_Color" name="Ext_Color"><br><br>

            <label for="Fuel_Type">Fuel Type:</label>
            <input type="text" id="Fuel_Type" name="Fuel_Type"><br><br>

            <label for="Fuel_Consumption">Fuel Consumption:</label>
            <input type="text" id="Fuel_Consumption" name="Fuel_Consumption" placeholder="e.g., 8L/100km"><br><br>

            <label for="Emission_Class">Emission Class:</label>
            <input type="text" id="Emission_Class" name="Emission_Class"><br><br>

            <label for="Wheeldrive_Type">Wheel Drive Type:</label>
            <input type="text" id="Wheeldrive_Type" name="Wheeldrive_Type"><br><br>

            <label for="N_Seats">Number of Seats:</label>
            <input type="text" id="N_Seats" name="N_Seats"><br><br>

            <label for="N_Doors">Number of Doors:</label>
            <input type="text" id="N_Doors" name="N_Doors"><br><br>

            <label for="Transmission_Type">Transmission Type:</label>
            <input type="text" id="Transmission_Type" name="Transmission_Type"><br><br>

            <label for="Hatchback_Vol">Hatchback Volume:</label>
            <input type="text" id="Hatchback_Vol" name="Hatchback_Vol" placeholder="e.g., 400L"><br><br>

            <label for="hasWheelchariAccess">Wheelchair Access:</label>
            <input type="checkbox" id="hasWheelchariAccess" name="hasWheelchariAccess"><br><br>

            <label for="Misc">Miscellaneous:</label>
            <input type="text" id="Misc" name="Misc" placeholder="e.g., Sunroof, GPS"><br><br>
        </fieldset>

        <button type="submit">Add Vehicle</button>
    </form>
    <br>
    <button onclick="window.location.href='Admin_page.php';">Back to Admin Page</button>
</body>
</html>
