<?php
    /* Creates a new seat for the route_instance, using the specified route_instance in 'Route_no','Date','Start_time', and the row and column specified by 'Row' and 'Column'
   Outputs status of the request to 'status'. 'true' means that the insert was successful, otherwise it will give you the appropriate error message.
   */
    $routeno= $_POST["Route_no"];
    $date = $_POST["Date"];
    $time = $_POST["Start_time"];
    $i = $_POST["Row"];
    $j = $_POST["Column"];

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

    $stmt =$con->prepare("INSERT INTO seat VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('issii',$routeno,$date,$time,$i,$j); // add the values
    $success = $stmt->execute(); // creates the seat

mysqli_close($con); // close the connection to the database
$status = -1;


if($success) { //if successful
    global $status;
    $status = "true";
}
else{
    global $status;
    $status = "Insert unsuccessful. (seat already exists, or invalid inputs)";
}
$json=array();
$json["status"] = $status;
header('Content-type: application/json; charset=utf-8');
echo json_encode($json);
?>