<?php
/* Verifies that an employee exists with the employee id 'Employee_id', and returns their information.
   Outputs status of the request to 'status'. 'true' means that it exists, otherwise it will give you the appropriate error message.
*/
$EMPID = $_POST["Employee_id"];

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

$query = "SELECT * FROM passenger where Employee_id=?";
$stmt = $con->prepare($query);
$stmt->bind_param('i',$EMPID);
$stmt->execute();
$result = $stmt->get_result();
$emp = $result->fetch_assoc();

mysqli_close($con); // close the connection to the database
$status = -1;
$json=array();
if($emp) { //if the employee exists
    global $status, $json;
    $json["Employee_id"] = $EMPID;
    $json["First_name"] = $emp["First_name"];
    $json["Last_name"] = $emp["Last_name"];
    $json["Department"] = $emp["Department"];
    $json["Admin_id"] = $emp["Admin_id"];
    $status = "true";
}
else{
    global $status;
    $status = "Employee not found.";
}

$json["status"] = $status;
header('Content-type: application/json; charset=utf-8');
echo json_encode($json);

?>