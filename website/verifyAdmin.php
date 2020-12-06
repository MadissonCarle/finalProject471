<?php
/* Verifies that a bus driver exists with this driver id and password. Used to login.
   Outputs status of the request to 'status'. 'true' means that it exists, otherwise it will give you the appropriate error message.
*/
$ADMINID = $_POST["AdminID"];
$PASSWORD = $_POST["Password"];

// Create connection
$con=mysqli_connect("localhost","root","MyNewPass","471project");

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
$query = "SELECT Admin_id, Password FROM administrator where Admin_id=? AND Password =?";
$stmt = $con->prepare($query);
$stmt->bind_param('is',$ADMINID,$PASSWORD);
$stmt->execute();
$result = $stmt->get_result();

//Check if driver exists
$theAdmin = $result->fetch_assoc();

mysqli_close($con); // close the connection to the database
$status = -1;

if($theAdmin) { //if the driver exists
    global $status;
    $status = "true";
}
else{
    global $status;
    $status = "Admin not found.";
}
$json=array();
$json["status"] = $status;
header('Content-type: application/json; charset=utf-8');
echo json_encode($json);

?>