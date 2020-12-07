<?php
    /* Verifies that the route with this route_no exists.
   Outputs status of the request to 'status'. 'true' means that it exists, otherwise it will give you the appropriate error message.
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

    //Get the route data
    $query = "SELECT * FROM route where Route_no=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i',$ROUTENO);
    $stmt->execute();
    $result = $stmt->get_result();
    $theroute = $result->fetch_assoc();

mysqli_close($con); // close the connection to the database
$status = -1;

if($theroute) { //if the route exists
    global $status;
    $status = "true";
}
else{
    global $status;
    $status = "Route not found.";
}
$json=array();
$json["status"] = $status;
header('Content-type: application/json; charset=utf-8');
echo json_encode($json);
?>