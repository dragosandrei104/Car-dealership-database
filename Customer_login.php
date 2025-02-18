<?php

session_start();


include 'Connect.php';


if (isset($_POST['login'])) {
    
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    
    if (empty($username) || empty($password)) {
        echo "Please fill in all the fields.";
    } else {
        
        $sql = "SELECT * FROM Customer WHERE Customer_Username = ?";
        $params = array($username);

        
        $stmt = sqlsrv_query($conn, $sql, $params, array("Scrollable" => SQLSRV_CURSOR_KEYSET));

        
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        
        if (sqlsrv_num_rows($stmt) > 0) {
            
            $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

            
            echo $password;
            echo $row['Customer_Password'];
            if ($password===$row['Customer_Password']) {
                
                session_regenerate_id(true);

                
                $_SESSION['Username'] = $row['Customer_Username'];
                $_SESSION['Email'] = $row['Email'];

                
                header("Location: User_page.php");
                exit;
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "Invalid username.";
        }

        
        sqlsrv_free_stmt($stmt);
    }
}


sqlsrv_close($conn);
?>