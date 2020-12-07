<?php
/*
finds the IDs of people that were in proximity of individual
*/
$row2=$_POST["row"];
//$row2=array("Route_no_1" => 1, "Date_1" => "2020/12/08 ","Start_time_1" =>" 08:00:00 ","Row_1" => 1 ,"Column_1" => 1, "Route_no_2" => 1, "Date_2" => "2020/12/08", "Start_time_2" => "08:00:00" ,"Row_2" => 1, "Column_2"=> 2);

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
$sql3="SELECT Employee_id FROM passenger_seat
						WHERE
						Employee_id <> ?
				AND
				(Route_no=?
				AND Date=?
				AND Start_time=?
				AND Seat_row=?
				AND Seat_col=?) OR 
				(Route_no=?
				AND Date=?
				AND Start_time=?
				AND Seat_row=?
				AND Seat_col=?) ";
        $stmt = $con->prepare($sql3);
        
        $stmt->bind_param('iissiiissii',$ID, $row2["Route_no_1"],$row2['Date_1'], $row2['Start_time_1'],$row2['Row_1'], $row2['Coumnl_1'], $row2['Route_no_2'], $row2['Date_2'], $row2['Start_time_2'],$row2['Row_2'], $row2['Column_2']);
        
        $stmt->execute();
        $result = $stmt->get_result();

$json=array();
$json['Tuples']=array();

$i=0;
//add all found information to an array to be used by caller
while ($row = $result->fetch_assoc() ) {
    global  $json;
    $tuple = array();
    $tuple['Employee_id']=$row['Employee_id'];
    $json['Tuples'][$i]=$tuple;
    $i=$i+1;
}
mysqli_close($con); // close the connection to the database
global $status;   
$status = -1;

if($i != 0) { //if proximity passengers exists
    global $status, $json;
    $status = "true";
    $json["TupleCount"]=$i;
    
}
else{
    global $status,$json;
    $status = "No ids found.";
     $json["TupleCount"]=$i;
}

$json["status"] = $status;
header('Content-type: application/json; charset=utf-8');
echo json_encode($json);


?>