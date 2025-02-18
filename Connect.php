<?php
$serverName = "DESKTOP-A6VAUHD\SQLEXPRESS"; 
// echo "yay merge";

$connectionInfo = array("Database"=>"DealershipAuto","Encrypt" => "no");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {
     //echo "Connection established.<br />";
}else{
     //echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}



$server_info = sqlsrv_server_info( $conn);
if( $server_info )
{
    //foreach( $server_info as $key => $value) {
       //echo $key.": ".$value."<br />";
    //}
} else {
      die( print_r( sqlsrv_errors(), true));
}