<?php

include('Connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contract_id'])) {
    $contract_id = intval($_POST['contract_id']);

    
    $delete_billing_sql = "DELETE FROM Billing WHERE Contract_ID = ?";
    $delete_billing_stmt = sqlsrv_query($conn, $delete_billing_sql, array($contract_id));

    if ($delete_billing_stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    
    $delete_contract_sql = "DELETE FROM Contract WHERE Contract_ID = ?";
    $delete_contract_stmt = sqlsrv_query($conn, $delete_contract_sql, array($contract_id));

    if ($delete_contract_stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        echo "Contract and its related billing records deleted successfully!";
    }

    sqlsrv_free_stmt($delete_billing_stmt);
    sqlsrv_free_stmt($delete_contract_stmt);
} else {
    echo "Invalid request.";
}

?>