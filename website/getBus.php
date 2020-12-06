<?php
    /* Verifies that the bus with this bus_no exists and returns it.
   Outputs status of the request to 'status'. 'true' means that it exists, otherwise it will give you the appropriate error message.
   Other outputs:
   'Vehicle_id' is the bus_no, and 'Model_no' is the bus type primary key
   */
    $BUSNO = $_POST["bus_no"];

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
    //Get the bus data
    $query = "SELECT * FROM bus where Vehicle_id=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i',$BUSNO);
    $stmt->execute();
    $result = $stmt->get_result();
    $thebus = $result->fetch_assoc();

mysqli_close($con); // close the connection to the database
$status = -1;
$json=array();
if($thebus) { //if the bus exists
    global $status, $json;
    $status = "true";
    $json['Vehicle_id'] = $thebus['Vehicle_id'];
    $json['Model_no'] = $thebus['Model_no'];
}
else{
    global $status;
    $status = "Bus not found.";
}

$json["status"] = $status;
header('Content-type: application/json; charset=utf-8');
echo json_encode($json);
?>