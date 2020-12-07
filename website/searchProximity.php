<?php
$IDs=array();
//$found=0;
function getEmpSeats($ID,$con){
    $url = 'verifyEmpSeats.php';
    global $data;
$returnval = sendReceiveJSONPOST($url,$data);



if ($returnval["status"] == "true") {
	echo "<table border='1'>
	<tr>
	<th>Route_no</th>
	<th>Date</th>
	<th>Start_time</th>
	<th>Seat_row</th>
	<th>Seat_col</th>
	<th>Employee_id</th>
	</tr>";
    
    $i=0;
	$allInstances= array();
    while($i<$returnval["TupleCount"]){
        $row=$returnval["Tuples"][$i];
		array_push($allInstances,$row);
		echo "<tr>";
		echo "<td>" . $row['Route_no'] . "</td>";
		echo "<td>" . $row['Date'] . "</td>";
		echo "<td>" . $row['Start_time'] . "</td>";
		echo "<td>" . $row['Seat_row'] . "</td>";
		echo "<td>" . $row['Seat_col'] . "</td>";
		echo "<td>" . $row['Employee_id'] . "</td>";
		echo"</tr>";
        $i=$i+1;
	}
	echo "</table>";
    getAllProx($allInstances,$returnval,$con,$ID);
    
    echo "<h1>All passengers that were in close proximity to contageous individual</h1>";
    getPassengers($con);
} else {
	echo "<p>".$returnval["status"]."</p>";
    mysqli_close($con);
    echo "<form action=\"index.php\" method=\"post\">
                    <input type=\"submit\" value=\"Return to main page\">
                    </form>";
}
   
}
function getAllProx($allInstances, $returnval ,$con,$ID){
    $i=0;
    
	while($i<$returnval["TupleCount"]){
        $data=array("allInstances"=>$allInstances,"count"=>$i);
        $url='getProx.php';
        $returnval = sendReceiveJSONPOST($url,$data);
		$i=$i+1;

		if ($returnval["status"] == "true") {
            $found=1;
             
			getIDs($returnval,$con,$ID);
           
           
            
		} else {
            
			if($found===0){
			echo "No employees were in proximity";
            }
		}
    }

}

function getIDs($results2,$con,$ID){
    $j=0;
    while($j<$results2["TupleCount"]){
       
        $row2=$results2["Tuples"][$j];
       
        $data =array("row"=>$row2);
        $url='verifyIDs.php';
        $returnval=sendReceiveJSONPOST($url,$data);
         
        
				if ($returnval["status"] == "true") {
                    $i=0;
					while( $i<$returnval["TupleCount"]){
                        global $IDs;
                        $row=$returnval["Tuples"][$i];
                        array_push($IDs,$row['Employee_id']);
                        
                        $i=$i+1;
                    }
				} else {
					
                        echo "<p>".$returnval["status"]."</p>";
                        //mysqli_close($con);
                        echo "<form action=\"index.php\" method=\"post\">
                    <input type=\"submit\" value=\"Return to main page\">
                    </form>";
					
						
					}
					$j=$j+1;
				}
}

function getPassengers($con){
    global $IDs;
    $i=0;
   

    $passengers=array();
    while($i<sizeof($IDs)){
        $data = array("IDs"=>$IDs,"count"=> $i);

        $url='getProxPass.php';
        $returnval = sendReceiveJSONPOST($url,$data);

        $j=0;
        while($j<$returnval["TupleCount"]){
            $row=$returnval["Tuples"][$j];
             array_push($passengers,$row);
            $j=$j+1;
        }
        
      $i=$i+1;  
    }
     echo "<table border='1'>
<tr>
<th>ID</th>
<th>First Name</th>
<th>Last Name</th>
<th>Department</th>
<th>Admin ID</th>
</tr>";
    $k=0;
        while($k<sizeof($passengers)){
    echo "<tr>";
  echo "<td>" . $passengers[$k]['Employee_id'] . "</td>";
  echo "<td>" . $passengers[$k]['First_name'] . "</td>";
  echo "<td>" . $passengers[$k]['Last_name'] . "</td>";
  echo "<td>" . $passengers[$k]['Department'] . "</td>";
  echo "<td>" . $passengers[$k]['Admin_id'] . "</td>";
    echo"</tr>";
            $k =$k+1;
        }
echo "</table>";       
}
?>


<?php

$ID = $_POST["EmployeeID"];
global $data;
$data= array(
    "EmployeeID"=>$ID
);

$url = 'verifyEmpSeats.php';
$returnval = sendReceiveJSONPOST($url,$data);

session_start();

// Create connection
$con=mysqli_connect("localhost","root","MyNewPass","471project");

// Check connection
if (mysqli_connect_errno())
{
	echo "<html><body><p>Failed to connect to MySQL: " . mysqli_connect_error()."</p></body></html>";
    exit;
}


//step 1  
echo "<h1>All trips taken by contageous passenger</h1>";
getEmpSeats($ID,$con);

echo '<form> <button class="button" type="submit"formaction="/finalProject471/website/adminMainView.php"> Return to previous page</button></form>';


mysqli_close($con);
?>

<?php

// Sends data as POST to the form at $url, receives and decodes the JSON response as an array.
    function sendReceiveJSONPOST($url,$data) {
        $data = http_build_query($data);
        $options = array(
          'http' => array(
            'method'  => 'POST',
              'header' =>  "Content-type: application/x-www-form-urlencoded\r\n"."Content-Length: " . strlen($data) . "\r\n",
                'content' => $data
            
            )
        );

        $context  = stream_context_create( $options );
        $result = file_get_contents('http://localhost/finalProject471/website/'.$url, false, $context );
        $response = json_decode( $result, true );
        return $response;
    }



?>

<!DOCTYPE html>
<html>
<head>
<style>
.button {
  background-color: #58bee0;
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
}
</style>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 500px; padding: 20px; }
    </style>
</head>
</html>