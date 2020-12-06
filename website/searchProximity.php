<?php
$IDs=array();
//$found=0;
function getEmpSeats($ID,$con){
    $sql="SELECT * FROM Passenger_seat 
WHERE Employee_id =?";
$stmt = $con->prepare($sql);
$stmt->bind_param('i',$ID);
$stmt->execute();
$results = $stmt->get_result();

//$results = mysqli_query($con, $sql);
if (mysqli_num_rows($results) < 1) {
	echo "Employee ID is either incorrect or this employee has not taken any buses";
	echo $ID;
} else {
	echo "<table border='1'>
	<tr>
	<th>Route_no</th>
	<th>Date</th>
	<th>Start_time</th>
	<th>Seat_row</th>
	<th>Seat_col</th>
	<th>Employee_id</th>
	</tr>";

	$allInstances= array();
	while( $row = mysqli_fetch_array($results)){
		array_push($allInstances,$row);
		echo "<tr>";
		echo "<td>" . $row['Route_no'] . "</td>";
		echo "<td>" . $row['Date'] . "</td>";
		echo "<td>" . $row['Start_time'] . "</td>";
		echo "<td>" . $row['Seat_row'] . "</td>";
		echo "<td>" . $row['Seat_col'] . "</td>";
		echo "<td>" . $row['Employee_id'] . "</td>";

		echo"</tr>";
	}
	echo "</table>";
    getAllProx($allInstances,$results,$con,$ID);
    
    echo "<h1>All passengers that were in close proximity to contageous individual</h1>";
    getPassengers($con);
}
}
function getAllProx($allInstances, $results,$con,$ID){
    
    $i=0;
	while($i<mysqli_num_rows($results)){

        $sql2 ="SELECT * FROM in_proximity
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
        $results2 = $stmt->get_result();
		$i=$i+1;

		if (mysqli_num_rows($results2) < 1) {
            if($found===0){
			echo "No employees were in proximity";
            }
		} else {
			$found=1;
			getIDs($results2,$con,$ID);
		}
    }
}

function getIDs($results2,$con,$ID){
    while( $row2 = mysqli_fetch_array($results2)){


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
        
        $stmt->bind_param('iissiiissii',$ID,$row2[0],$row2[1],$row2[2],$row2[3],$row2[4], $row2[5], $row2[6], $row2[7],$row2[8], $row2[9]);
        $stmt->execute();
        $results3 = $stmt->get_result();
				if (mysqli_num_rows($results3) < 1) {
					echo "No employees were in proximity";
				} else {
					

					while( $row3 = mysqli_fetch_array($results3)){
                        global $IDs;
                        array_push($IDs,$row3['Employee_id']);
						
					}
					
				}

			}
}

function getPassengers($con){
    global $IDs;
    $i=0;
    $passengers=array();
    while($i<sizeof($IDs)){
        $sql="SELECT * FROM passenger WHERE Employee_id=?";
         $stmt = $con->prepare($sql);
        $stmt->bind_param('i',$IDs[$i]);
        $stmt->execute();
        $result = $stmt->get_result();
        while( $row = mysqli_fetch_array($result)){
            array_push($passengers,$row);
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
    $i=0;
        while($i<sizeof($passengers)){
    echo "<tr>";
  echo "<td>" . $passengers[$i]['Employee_id'] . "</td>";
  echo "<td>" . $passengers[$i]['First_name'] . "</td>";
  echo "<td>" . $passengers[$i]['Last_name'] . "</td>";
  echo "<td>" . $passengers[$i]['Department'] . "</td>";
  echo "<td>" . $passengers[$i]['Admin_id'] . "</td>";
    echo"</tr>";
            $i =$i+1;
        }
echo "</table>";
}
?>


<?php

		$ID = $_POST["EmployeeID"];



// Create connection
$con=mysqli_connect("localhost","root","MyNewPass","471project");

// Check connection
if (mysqli_connect_errno())
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


//step 1  
echo "<h1>All trips taken by contageous passenger</h1>";
getEmpSeats($ID,$con);

echo '<form> <button class="button" type="submit"formaction="/finalProject471/website/adminMainView.php"> Return to previous page</button></form>';


mysqli_close($con);
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