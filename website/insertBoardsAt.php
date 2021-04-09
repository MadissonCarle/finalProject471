<?php
    /* Creates a new instance of boards_at, using the specified location in 'Address', the passenger given by 'Employee_id', and the boarding time given by "Boarding_time"
   Outputs status of the request to 'status'. 'true' means that the insert was successful, otherwise it will give you the appropriate error message.
   */
    $loc= $_POST["Address"];
    $EMPID = $_POST["Employee_id"];
    $time = $_POST["Boarding_time"];

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

$query = "INSERT INTO boards_at (Employee_id,Address,Boarding_time) VALUES (?,?,?)";
$stmt = $con->prepare($query);
$stmt->bind_param('iss',$EMPID,$loc,$time);
$success = $stmt->execute();

mysqli_close($con); // close the connection to the database
$status = -1;


if($success) { //if the insert was successful
    global $status;
    $status = "true";
}
else{
    global $status;
    $status = "Insert unsuccessful. (this board location already exists, or invalid inputs)";
}
$json=array();
$json["status"] = $status;
header('Content-type: application/json; charset=utf-8');
echo json_encode($json);
?>