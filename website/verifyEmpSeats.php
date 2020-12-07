<?php
/*Finds seats an employee was in for each route instance they took based on theri ID
*/
$ID = $_POST["EmployeeID"];

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

//query
 $sql="SELECT * FROM Passenger_seat 
WHERE Employee_id =?";
$stmt = $con->prepare($sql);
$stmt->bind_param('i',$ID);
$stmt->execute();
$theSeats = $stmt->get_result();

$json=array();
$json['Tuples']=array();
global $i;
$i=0;

//add all inforamtion to an array to be used by caller
while ($row = $theSeats->fetch_assoc() ) {
    global $i, $json;
    $tuple = array();
    $tuple['Route_no']=$row['Route_no'];
    $tuple['Date']=$row['Date'];
    $tuple['Start_time']=$row['Start_time'];
    $tuple['Seat_row']=$row['Seat_row'];
    $tuple['Seat_col']=$row['Seat_col'];
    $tuple['Employee_id']=$row['Employee_id'];
    $json['Tuples'][$i]=$tuple;
    $i=$i+1;
}

mysqli_close($con); // close the connection to the database
global $status;   
$status = -1;

if($i != 0) { //if seats were found
    global $status, $json;
    $status = "true";
    $json["TupleCount"]=$i;
    
}
else{
    global $status,$json;
    $status = "Passenger not found on any buses";
     $json["TupleCount"]=$i;
}

$json["status"] = $status;
header('Content-type: application/json; charset=utf-8');
echo json_encode($json);


?>