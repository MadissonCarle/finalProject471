<?php
    /* Creates a new route_instance for the current date and time, using the specified route in 'Route_no', bus driver in 'DriverID', and bus in 'Vehicle_id' (all primary keys)
   Outputs status of the request to 'status'. 'true' means that the insert was successful, otherwise it will give you the appropriate error message.
   Also outputs the primary key of the created route instance as follows
   'Route_no' is the route number, 'Date' is the date, 'Start_time' is the start time.
   */
    $b = $_POST["Vehicle_id"];
    $d = $_POST["DriverID"];
    $r = $_POST["Route_no"];

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

    $stmt =$con->prepare("INSERT INTO route_instance (Route_no, Date, Start_time, Driver_id, Vehicle_id) VALUES (?, ?, ?, ?,?)");
    date_default_timezone_set("America/Edmonton");
    $date = date("Y/m/d"); // get the date
    $time = date("H:i:s"); // get the current time
    $stmt->bind_param('issii',$r,$date,$time,$d,$b); // add the values
    $success = $stmt->execute(); // creates the route instance

mysqli_close($con); // close the connection to the database
$status = -1;

$json=array();
if($success) { //if successful
    global $status, $json;
    $status = "true";
    $json['Route_no'] = $r;
    $json['Date'] = $date;
    $json['Start_time'] = $time;
}
else{
    global $status;
    $status = "Insert unsuccessful. (route instance already exists)";
}

$json["status"] = $status;
header('Content-type: application/json; charset=utf-8');
echo json_encode($json);
?>