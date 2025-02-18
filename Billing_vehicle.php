<?php

include('Connect.php');


if (isset($_GET['contract_id'])) {
    $contract_id = intval($_GET['contract_id']);

    
    $contract_sql = "SELECT * FROM Vehicle WHERE Contract_ID = ?";
    $params = array($contract_id);
    $stmt = sqlsrv_query($conn, $contract_sql, $params);

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
                </tr>
                </thead>
                <tbody>";
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['Vehicle_ID']) . "</td>
                    <td>" . htmlspecialchars($row['Make']) . "</td>
                    <td>" . htmlspecialchars($row['Model']) . "</td>
                    <td>" . htmlspecialchars($row['isNew'] ? 'Yes.' : 'No.') . "</td>
                    <td>" . htmlspecialchars($row['isAvailable'] ? 'Yes.' : 'No.') . "</td>
                    <td>" . htmlspecialchars($row['Office_ID']) . "</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No billing found for Contract ID: " . htmlspecialchars($contract_id) . ".</p>";
    }

    sqlsrv_free_stmt($stmt);
} else {
    echo "<p>Invalid request.</p>";
}
?>
