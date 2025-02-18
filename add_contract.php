<?php
session_start();
if (!isset($_SESSION['Username'])) {
    header("Location: BD.html");
    exit;
}

include('Connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $contractID = $_POST['Contract_ID'];
    $signDate = $_POST['Sign_Date'];
    $dealershipID = $_POST['Dealership_ID'];
    $employeeID = $_POST['Employee_ID'];
    $customerID = $_POST['Customer_ID'];
    $vehicleID = $_POST['Vehicle_ID'];
    $insuranceID = !empty($_POST['Insurance_ID']) ? $_POST['Insurance_ID'] : NULL;

    $billID = $_POST['Bill_ID'];
    $zipCode = $_POST['Zip_Code'];
    $cardNumber = $_POST['Card_Number'];
    $paymentType = $_POST['Payment_Type'];
    $installments = $_POST['Installments'];

    
    function idExists($conn, $table, $column, $value) {
        $sql = "SELECT COUNT(*) AS count FROM $table WHERE $column = ?";
        $params = array($value);
        $stmt = sqlsrv_query($conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        return $row['count'] > 0;
    }

    
    if (
        idExists($conn, 'Dealership', 'Dealership_ID', $dealershipID) &&
        idExists($conn, 'Employee', 'Employee_ID', $employeeID) &&
        idExists($conn, 'Customer', 'Customer_ID', $customerID) &&
        idExists($conn, 'Vehicle', 'Vehicle_ID', $vehicleID) &&
        (!$insuranceID || idExists($conn, 'Insurance_Policy', 'Insurance_ID', $insuranceID))
    ) {
        
        $sql_contract = "INSERT INTO Contract (Contract_ID, Sign_Date, Dealership_ID, Employee_ID, Customer_ID, Vehicle_ID, Insurance_ID)
                         VALUES (?, ?, ?, ?, ?, ?, ?)";
        $params_contract = array($contractID, $signDate, $dealershipID, $employeeID, $customerID, $vehicleID, $insuranceID);
        $stmt_contract = sqlsrv_query($conn, $sql_contract, $params_contract);
        if ($stmt_contract === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        
        $sql_billing = "INSERT INTO Billing (Bill_ID, Dealership_ID, Contract_ID, Customer_ID, Zip_Code, Card_Number, Payment_Type, Installments)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $params_billing = array($billID, $dealershipID, $contractID, $customerID, $zipCode, $cardNumber, $paymentType, $installments);
        $stmt_billing = sqlsrv_query($conn, $sql_billing, $params_billing);
        if ($stmt_billing === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        echo "<p>Contract and corresponding bill added successfully!</p>";

        sqlsrv_free_stmt($stmt_contract);
        sqlsrv_free_stmt($stmt_billing);
    } else {
        echo "<p>Error: One or more IDs provided do not exist. Please check your inputs.</p>";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Add Contract and Billing</title>
</head>
<body>
    <h1>Add a New Contract and Bill</h1>
    <form method="POST" action="add_contract.php">
        
        <fieldset>
            <legend>Contract Information</legend>
            <label for="Contract_ID">Contract ID:</label>
            <input type="text" id="Contract_ID" name="Contract_ID" required><br><br>

            <label for="Sign_Date">Sign Date:</label>
            <input type="date" id="Sign_Date" name="Sign_Date" required><br><br>

            <label for="Dealership_ID">Dealership ID:</label>
            <input type="text" id="Dealership_ID" name="Dealership_ID" required><br><br>

            <label for="Employee_ID">Employee ID:</label>
            <input type="text" id="Employee_ID" name="Employee_ID" required><br><br>

            <label for="Customer_ID">Customer ID:</label>
            <input type="text" id="Customer_ID" name="Customer_ID" required><br><br>

            <label for="Vehicle_ID">Vehicle ID:</label>
            <input type="text" id="Vehicle_ID" name="Vehicle_ID" required><br><br>

            <label for="Insurance_ID">Insurance ID:</label>
            <input type="text" id="Insurance_ID" name="Insurance_ID"><br><br>
        </fieldset>

       
        <fieldset>
            <legend>Billing Information</legend>
            <label for="Bill_ID">Bill ID:</label>
            <input type="text" id="Bill_ID" name="Bill_ID" required><br><br>

            <label for="Zip_Code">Zip Code:</label>
            <input type="text" id="Zip_Code" name="Zip_Code" required><br><br>

            <label for="Card_Number">Card Number:</label>
            <input type="text" id="Card_Number" name="Card_Number" required><br><br>

            <label for="Payment_Type">Payment Type:</label>
            <input type="text" id="Payment_Type" name="Payment_Type" required><br><br>

            <label for="Installments">Installments:</label>
            <input type="text" id="Installments" name="Installments"><br><br>
        </fieldset>

        <button type="submit">Add Contract and Bill</button>
    </form>
    <br>
    <button onclick="window.location.href='Admin_page.php';">Back to Admin Page</button>
</body>
</html>
