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
    <title>Contracts</title>
    <script>
        function showBilling(signDate) {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "billing.php?sign_date=" + encodeURIComponent(signDate), true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById("billing-data").innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

function showCustomers(signDate) {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "customer.php?sign_date=" + encodeURIComponent(signDate), true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById("customer-data").innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

function deleteContract(contractID) {
        if (confirm("Are you sure you want to delete this contract?")) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_contract.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    alert(xhr.responseText);
                    
                    location.reload();
                }
            };
            xhr.send("contract_id=" + contractID);
        }
    }

    </script>
</head>
<body>
    <div class="header">
        <h1>Welcome to the Contracts Dashboard</h1>
    </div>
    <div class="content">
        
        <h2>Contracts</h2>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Sign Date</th>
                    
                    
                </tr>
            </thead>
            <tbody>
                <?php
                $branch_sql = "
                SELECT 
                    c.Contract_ID,
                    c.Sign_Date,
                    c.Dealership_ID,
                    c.Employee_ID,
                    c.Customer_ID,
                    c.Vehicle_ID,
                    p.Insurance_ID
                FROM Contract c
                LEFT JOIN Insurance_Policy p ON c.Vehicle_ID = p.Vehicle_ID
            ";
            $branch_stmt = sqlsrv_query($conn, $branch_sql);

                if ($branch_stmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }

                while ($branch_row = sqlsrv_fetch_array($branch_stmt, SQLSRV_FETCH_ASSOC)) {
                    echo "<tr>
                            <td><a href='javascript:void(0)' onclick='showBilling(\"" . htmlspecialchars($branch_row['Sign_Date']->format('Y-m-d')) . "\"); showCustomers(\"" . htmlspecialchars($branch_row['Sign_Date']->format('Y-m-d')) . "\")'>" . htmlspecialchars($branch_row['Sign_Date']->format('Y-m-d')) . "</a></td>
                            
                            <td>
                                <button onclick='deleteContract(" . htmlspecialchars($branch_row['Contract_ID']) . ")'>Delete</button>
                            </td>
                        </tr>";
                }
                

                sqlsrv_free_stmt($branch_stmt);
                ?>
            </tbody>
        </table>

        
        <h2>Billing & Insurance</h2>
        <div id="billing-data">
            <p>Click on a Sign Date to view its billing & insurance details.</p>
        </div>

        <h2>Customer & Employee Details</h2>
        <div id="customer-data">
            <p>Click on a Sign Date to view the contract's promisee & promisor details.</p>
        </div>
    </div>
</body>
</html>
