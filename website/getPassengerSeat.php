<?php
    /* Verifies that the passenger_seat exists and returns it
   Outputs status of the request to 'status'. 'true' means that it exists, otherwise it will give you the appropriate error message.
   Inputs:
    $_POST["Route_no"] is the route number
    $_POST["Date"] is the date
    $_POST["Start_time"] is the start time
    $_POST['Seat_row'] is the seat row
    $_POST['Seat_col'] is the seat column
    Outputs: the listed variables above, as well as status and Employee_id (the employee in that seat)
   */
    $ROUTENO = $_POST["Route_no"];
    $DATE = $_POST["Date"];
    $STARTTIME = $_POST["Start_time"];
    $seatrow = $_POST['Seat_row'];
    $seatcol = $_POST['Seat_col'];

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

$query = "SELECT * FROM passenger_seat where Route_no=? AND Date=? AND Start_time=? AND Seat_row=? AND Seat_col=?";
$stmt = $con->prepare($query);
$stmt->bind_param('issii',$ROUTENO,$DATE,$STARTTIME,$seatrow,$seatcol);
$stmt->execute();
$result2 = $stmt->get_result();
$row = $result2->fetch_assoc();

mysqli_close($con); // close the connection to the database
$status = -1;
$json=array();
if($row) { //if the passenger exists
    global $status, $json;
    $status = "true";
    $json['Employee_id'] = $row['Employee_id'];
    $json['Route_no'] = $ROUTENO;
    $json['Date'] = $DATE;
    $json['Start_time'] = $STARTTIME;
    $json['Seat_row'] = $seatrow;
    $json['Seat_col'] = $seatcol;
}
else{
    global $status;
    $status = "Passenger_seat not found.";
}

$json["status"] = $status;
header('Content-type: application/json; charset=utf-8');
echo json_encode($json);
?>