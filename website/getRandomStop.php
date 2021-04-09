<?php
    /* Gets a random location that the route 'Route_no' stops at and returns it.
   Outputs status of the request to 'status'. 'true' means that it exists, otherwise it will give you the appropriate error message.
   Other outputs:
   'Address' is the location returned.
   */
    $ROUTENO = $_POST["Route_no"];

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
    $query = "SELECT * from stops_at WHERE Route_no =? ORDER BY rand() LIMIT 1";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i',$ROUTENO);
    $stmt->execute();
    $result = $stmt->get_result();
    

mysqli_close($con); // close the connection to the database
$status = -1;
$json=array();
if($row = $result->fetch_assoc()) { //if the route stops somewhere
    global $status, $json;
    $status = "true";
    $json['Address'] = $row['Address'];
}
else{
    global $status;
    $status = "No bus stops found.";
}

$json["status"] = $status;
header('Content-type: application/json; charset=utf-8');
echo json_encode($json);
?>