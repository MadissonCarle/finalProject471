<?php
/* Verifies that a Administrator exists with this admin id and password. Used to login.
   Outputs status of the request to 'status'. 'true' means that it exists, otherwise it will give you the appropriate error message.
*/
$ADMINID = $_POST["AdminID"];
$PASSWORD = $_POST["Password"];

include_once "config.php";
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

// Get the admin
$query = "SELECT * FROM administrator where Admin_id=? AND Password =?";
$stmt = $con->prepare($query);
$stmt->bind_param('is',$ADMINID,$PASSWORD);
$stmt->execute();
$result = $stmt->get_result();

//Check if admin exists
$theAdmin = $result->fetch_assoc();

mysqli_close($con); // close the connection to the database
$status = -1;

if($theAdmin) { //if admin exists
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