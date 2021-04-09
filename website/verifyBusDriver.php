<?php
/* Verifies that a bus driver exists with this driver id and password. Used to login.
   Outputs status of the request to 'status'. 'true' means that it exists, otherwise it will give you the appropriate error message.
*/
$DRIVERID = $_POST["DriverID"];
$PASSWORD = $_POST["Password"];

require_once "config.php";

// Create connection
$con=mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,"471project");

// Check connection
if (mysqli_connect_errno())
{
    $status = "Failed to connect to MySQL: " . mysqli_connect_error();
    $json=array();
    $json["status"] = $status;
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($json);
    exit;
}

// Get the bus driver
$query = "SELECT * FROM bus_driver where Driver_id=? AND Password =?";
$stmt = $con->prepare($query);
$stmt->bind_param('is',$DRIVERID,$PASSWORD);
$stmt->execute();
$result = $stmt->get_result();
$thedriver = $result->fetch_assoc();

mysqli_close($con); // close the connection to the database
$status = -1;

if($thedriver) { //if the driver exists
    global $status;
    $status = "true";
}
else{
    global $status;
    $status = "Driver not found.";
}
$json=array();
$json["status"] = $status;
header('Content-type: application/json; charset=utf-8');
echo json_encode($json);

?>