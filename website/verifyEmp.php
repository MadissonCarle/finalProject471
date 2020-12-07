<?php
$First_name = $_POST["FirstName"];
 $Last_name = $_POST["LastName"];

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

   
    $query="SELECT * FROM Passenger 
    WHERE First_name =?
    AND Last_name=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('ss',$First_name,$Last_name);
    $stmt->execute();
    $theEmp = $stmt->get_result();
    

    
$json=array();
$json['Tuples']=array();
global $i;
$i=0;

while ($row = $theEmp->fetch_assoc() ) {
    global $i, $json;
    $tuple = array();
    $tuple['Employee_id']=$row['Employee_id'];
    $tuple['First_name']=$row['First_name'];
    $tuple['Last_name']=$row['Last_name'];
    $tuple['Department']=$row['Department'];
    $tuple['Admin_id']=$row['Admin_id'];
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