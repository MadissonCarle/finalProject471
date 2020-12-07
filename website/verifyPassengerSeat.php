<?php
    /* Verifies that the passenger_seat exists for an employee on this route instanceand returns it
   Outputs status of the request to 'status'. 'true' means that it exists, otherwise it will give you the appropriate error message.
   Inputs:
    $_POST["Route_no"] is the route number
    $_POST["Date"] is the date
    $_POST["Start_time"] is the start time
    $_POST['Employee_id'] is the employee id
    Outputs: the listed variables above, as well as status, seat row to 'Seat_row' and seat column to 'Seat_col'
   */
    $ROUTENO = $_POST["Route_no"];
    $DATE = $_POST["Date"];
    $STARTTIME = $_POST["Start_time"];
    $EMPID = $_POST['Employee_id'];

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

$query = "SELECT * FROM passenger_seat where Route_no=? AND Date=? AND Start_time=? AND Employee_id=?";
$stmt = $con->prepare($query);
$stmt->bind_param('issi',$ROUTENO,$DATE,$STARTTIME,$EMPID);
$stmt->execute();
$result2 = $stmt->get_result();
//$row = $result2->fetch_assoc();

mysqli_close($con); // close the connection to the database
$status = -1;
$json=array();
if($row = $result2->fetch_assoc()) { //if the passenger seat exists
    global $status, $json;
    $status = "true";
    $json['Employee_id'] = $EMPID;
    $json['Route_no'] = $ROUTENO;
    $json['Date'] = $DATE;
    $json['Start_time'] = $STARTTIME;
    $json['Seat_row'] = $row['Seat_row'];
    $json['Seat_col'] = $row['Seat_col'];
}
else{
    global $status;
    $status = "Passenger_seat not found.";
}

$json["status"] = $status;
header('Content-type: application/json; charset=utf-8');
echo json_encode($json);
?>