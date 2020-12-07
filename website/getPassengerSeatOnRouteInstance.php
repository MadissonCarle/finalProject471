<?php
    /* Gets all the passenger seats for a specified route instance.
        Inputs:
        $_POST["Route_no"] is the route number
        $_POST["Date"] is the date
        $_POST["Start_time"] is the start time
        Outputs status of the request to 'status'. 'true' means that there are tuples returned, otherwise it will give you the appropriate error message.
        Json object example:
        {
    "Route_no": "2",
    "Date": "2020/12/06",
    "Start_time": "19:48:15",
    "Tuples": [
        {
            "Seat_row": 1,
            "Seat_col": 1,
            "Employee_id": 10
        },
        {
            "Seat_row": 4,
            "Seat_col": 1,
            "Employee_id": 12
        }
    ],
    "TupleCount": 2,
    "status": "true"
}
   */
    $ROUTENO = $_POST["Route_no"];
    $DATE = $_POST["Date"];
    $STARTTIME = $_POST["Start_time"];

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

//Get the seats
$query = "SELECT * FROM passenger_seat where Route_no=? AND Date=? AND Start_time=?";
$stmt = $con->prepare($query);
$stmt->bind_param('iss',$ROUTENO,$DATE,$STARTTIME);
$stmt->execute();
$result = $stmt->get_result();


$json=array();
$json['Route_no'] = $ROUTENO;
$json['Date'] = $DATE;
$json['Start_time'] = $STARTTIME;
$json['Tuples'] = array();
$i = 0;
    
while ($row = $result->fetch_assoc() ) {
    global $i, $json;
    $tuple = array();
    $tuple['Seat_row'] = $row['Seat_row'];
    $tuple['Seat_col'] = $row['Seat_col'];
    $tuple['Employee_id'] = $row['Employee_id'];
    $json['Tuples'][$i] = $tuple;
    $i+=1;
}
mysqli_close($con); // close the connection to the database
$status = -1;

if($i != 0) { // there are rows in the table
    global $status, $json;
    $status = "true";
    $json["TupleCount"] = $i;
}
else{
    global $status, $json;
    $status = "No passenger seats found on this route instance.";
    $json["TupleCount"] = $i;
}

$json["status"] = $status;
header('Content-type: application/json; charset=utf-8');
echo json_encode($json);
?>