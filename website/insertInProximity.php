<?php
    /* Sets two seats on the same route instance to be in proximity, using the specified route_instance in 'Route_no','Date','Start_time', and the rows and columns specified by 'Row1', 'Column1", 'Row2' and 'Column2'
   Outputs status of the request to 'status'. 'true' means that the insert was successful, otherwise it will give you the appropriate error message.
   */
    $routeno= $_POST["Route_no"];
    $date = $_POST["Date"];
    $time = $_POST["Start_time"];
    $i = $_POST["Row1"];
    $j = $_POST["Column1"];
    $k = $_POST["Row2"];
    $l = $_POST["Column2"];

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
    
$stmt =$con->prepare("INSERT INTO in_proximity (Route_no_1, Date_1, Start_time_1, Row_1, Column_1, Route_no_2, Date_2, Start_time_2, Row_2, Column_2) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param('issiiissii',$routeno,$date,$time,$i,$j,$routeno,$date,$time,$k,$l); // add the values
$success = $stmt->execute();

mysqli_close($con); // close the connection to the database
$status = -1;


if($success) { //if the insert was successful
    global $status;
    $status = "true";
}
else{
    global $status;
    $status = "Insert unsuccessful. (proximity already exists, or invalid inputs)";
}
$json=array();
$json["status"] = $status;
header('Content-type: application/json; charset=utf-8');
echo json_encode($json);
?>