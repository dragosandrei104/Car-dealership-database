<?php

include('Connect.php');


if (isset($_GET['isNew'])) {
    $isNew = intval($_GET['isNew']);

    
    $query = "SELECT * FROM Vehicle WHERE isNew = ?";
    $params = array($isNew);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        echo "<tr>
                <td>
                    <input 
                        type='radio' 
                        name='vehicle_id' 
                        value='" . htmlspecialchars($row['Vehicle_ID']) . "' 
                        onclick='showSpecs(\"" . htmlspecialchars($row['Vehicle_ID']) . "\")'>
                </td>
                <td>" . htmlspecialchars($row['Make']) . "</td>
                <td>" . htmlspecialchars($row['Model']) . "</td>
                <td>" . htmlspecialchars($row['isNew'] ? 'Yes' : 'No') . "</td>
                <td>" . htmlspecialchars($row['isAvailable'] ? 'Yes' : 'No') . "</td>
                <td>" . htmlspecialchars($row['Office_ID']) . "</td>
              </tr>";
    }

    sqlsrv_free_stmt($stmt);
} else {
    echo "<tr><td colspan='6'>Invalid request.</td></tr>";
}
?>
