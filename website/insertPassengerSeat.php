<?php
    /* Creates a new passenger_seat for the route_instance, using the specified route_instance in 'Route_no','Date','Start_time', the passenger with employee id 'Employee_id', and the row and column specified by 'Row' and 'Column'
   Outputs status of the request to 'status'. 'true' means that the insert was successful, otherwise it will give you the appropriate error message.
   */
    $routeno= $_POST["Route_no"];
    $date = $_POST["Date"];
    $time = $_POST["Start_time"];
    $i = $_POST["Row"];
    $j = $_POST["Column"];
    $EMPID = $_POST["Employee_id"];

    // Create connection
$con=mysqli_connect("localhost","root","root","471project");

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

$query = "INSERT INTO passenger_seat VALUES (?,?,?,?,?,?)";
$stmt = $con->prepare($query);
$stmt->bind_param('issiii',$routeno,$date,$time,$i,$j,$EMPID);
$result = $stmt->execute();

mysqli_close($con); // close the connection to the database
$status = -1;

$json=array();
if($result) { //if the bus exists
    global $status;
    $status = "true";
}
else{
    global $status;
    $status = "Insert unsuccessful. (passenger seat already exists, or invalid inputs)";
    $status = $routeno . $date . $time . $i . $j . $EMPID;
}

$json["status"] = $status;
header('Content-type: application/json; charset=utf-8');
echo json_encode($json);
?>