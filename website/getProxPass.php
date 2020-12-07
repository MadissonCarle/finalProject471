<?php
/*gets all passengers in proximity
*/
$IDs =$_POST["IDs"];
$i=$_POST["count"];


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

//query
 $sql="SELECT * FROM passenger WHERE Employee_id=?";
         $stmt = $con->prepare($sql);
        $stmt->bind_param('i',$IDs[$i]);
        $stmt->execute();
        $result = $stmt->get_result();

$json=array();
$json['Tuples']=array();
global $j;
$j=0;

//adding all passengers to array to be used by caller
while ($row = $result->fetch_assoc() ) {
    global $j, $json;
    $tuple = array();
    $tuple['Employee_id']=$row['Employee_id'];
    $tuple['First_name']=$row['First_name'];
    $tuple['Last_name']=$row['Last_name'];
    $tuple['Department']=$row['Department'];
    $tuple['Admin_id']=$row['Admin_id'];
    $json['Tuples'][$j]=$tuple;
    $j=$j+1;
}
mysqli_close($con); // close the connection to the database
global $status;   
$status = -1;

if($j != 0) { //if the passengers exist
    global $status, $json;
    $status = "true";
    $json["TupleCount"]=$j;
    
}
else{
    global $status,$json;
    $status = "Passenger not found.";
     $json["TupleCount"]=$j;
}

$json["status"] = $status;
header('Content-type: application/json; charset=utf-8');
echo json_encode($json);

?>