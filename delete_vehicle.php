<?php

include('Connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vehicle_id'])) {
    $vehicle_id = intval($_POST['vehicle_id']);

    $delete_sql = "DELETE FROM Vehicle WHERE Vehicle_ID = ?";
    $delete_stmt = sqlsrv_query($conn, $delete_sql, array($vehicle_id));

    if ($delete_stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        echo "Vehicle deleted successfully!";
    }

    sqlsrv_free_stmt($delete_stmt);
} else {
    echo "Invalid request.";
}
?>
