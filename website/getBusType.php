<?php
    /* Verifies that the bus_type with this Model_no exists and returns it.
   Outputs status of the request to 'status'. 'true' means that it exists, otherwise it will give you the appropriate error message.
   Other outputs:
   'Model_no' is the model number, 'No_of_rows' is the number of rows, and 'No_of_cols' is the number of columns
   */
    $MODELNO = $_POST["Model_no"];

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
    //Grab the bus type to set up the the seats:
    $query = "SELECT * FROM bus_type where Model_no=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i',$MODELNO);
    $stmt->execute();
    $result2 = $stmt->get_result();
    $bustype = $result2->fetch_assoc();

mysqli_close($con); // close the connection to the database
$status = -1;
$json=array();
if($bustype) { //if the bus type exists
    global $status, $json;
    $status = "true";
    $json['Model_no'] = $bustype['Model_no'];
    $json['No_of_rows'] = $bustype['No_of_rows'];
    $json['No_of_cols'] = $bustype['No_of_cols'];
}
else{
    global $status;
    $status = "Bus type not found.";
}

$json["status"] = $status;
header('Content-type: application/json; charset=utf-8');
echo json_encode($json);
?>