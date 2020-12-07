<?php
$ID = $_POST["EmployeeID"];

// Create connection
$con=mysqli_connect("localhost","root","MyNewPass","471project");

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

if($i != 0) { //if the driver exists
    global $status, $json;
    $status = "true";
    $json["TupleCount"]=$i;
    
}
else{
    global $status,$json;
    $status = "Passenger not found.";
     $json["TupleCount"]=$i;
}

$json["status"] = $status;
header('Content-type: application/json; charset=utf-8');
echo json_encode($json);


?>