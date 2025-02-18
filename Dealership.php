<php?
$servername = "DESKTOP-A6VAUHD\SQLEXPRESS";
//$username = "root";
//$password = "";
$dbname = "DealershipAuto";

// Create connection
$conn = new mysqli($servername, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>