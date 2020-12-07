<?php
/*
finds all in_proximity instances for specified case
*/
$allInstances=$_POST["allInstances"];
$i=$_POST["count"];

include_once "config.php";
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

$sql2 ="SELECT DISTINCT * FROM in_proximity
				WHERE 
				(Route_no_1=?
				AND Date_1=?
				AND Start_time_1=?
				AND Row_1=?
				AND Column_1=?) OR 
				(Route_no_2=?
				AND Date_2=?
				AND Start_time_2=?
				AND Row_2=?
				AND Column_2=?) ";
        $stmt = $con->prepare($sql2);
        
        $stmt->bind_param('issiiissii',$allInstances[$i]['Route_no'], $allInstances[$i]['Date'],$allInstances[$i]['Start_time'],$allInstances[$i]['Seat_row'],$allInstances[$i]['Seat_col'],$allInstances[$i]['Route_no'], $allInstances[$i]['Date'],$allInstances[$i]['Start_time'],$allInstances[$i]['Seat_row'],$allInstances[$i]['Seat_col']);
        
        $stmt->execute();
        $result = $stmt->get_result();

$json=array();
$json['Tuples']=array();
global $j;
$j=0;

//populating Tuple array to be returned and used by caller
while ($row = $result->fetch_assoc() ) {
    global $j, $json;
    $tuple = array();
     $tuple['Route_no_1']=$row['Route_no_1'];
    $tuple['Date_1']=$row['Date_1'];
    $tuple['Start_time_1']=$row['Start_time_1'];
    $tuple['Row_1']=$row['Row_1'];
    $tuple['Column_1']=$row['Column_1'];
     $tuple['Route_no_2']=$row['Route_no_2'];
    $tuple['Date_2']=$row['Date_2'];
    $tuple['Start_time_2']=$row['Start_time_2'];
    $tuple['Row_2']=$row['Row_2'];
    $tuple['Column_2']=$row['Column_2'];
    $json['Tuples'][$j]=$tuple;
    $j=$j+1;
}
mysqli_close($con); // close the connection to the database
global $status;   
$status = -1;

if($j != 0) { //if in_proximity cases exists
    global $status, $json;
    $status = "true";
    $json["TupleCount"]=$j;
    
}
else{
    global $status,$json;
    $status = "No passengers in close proximity";
     $json["TupleCount"]=$j;
}

$json["status"] = $status;
header('Content-type: application/json; charset=utf-8');
echo json_encode($json);


?>