<?php
$IDs=array();
//$found=0;
function getEmpSeats($ID,$con){
    $sql="SELECT * FROM Passenger_seat 
WHERE Employee_id ='".$ID."'";
$results = mysqli_query($con, $sql);
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
    
    echo "<h1>All passengers that were in close proximity to contagious individual</h1>";
    getPassengers($con);
}
}
function getAllProx($allInstances, $results,$con,$ID){
    
    $i=0;
	while($i<mysqli_num_rows($results)){
		$sql2 ="SELECT * FROM in_proximity
				WHERE 
				(Route_no_1='".$allInstances[$i]['Route_no']."'
				AND Date_1='".$allInstances[$i]['Date']."'
				AND Start_time_1='".$allInstances[$i]['Start_time']."'
				AND Row_1='".$allInstances[$i]['Seat_row']."'
				AND Column_1='".$allInstances[$i]['Seat_col']."') OR 
				(Route_no_2='".$allInstances[$i]['Route_no']."'
				AND Date_2='".$allInstances[$i]['Date']."'
				AND Start_time_2='".$allInstances[$i]['Start_time']."'
				AND Row_2='".$allInstances[$i]['Seat_row']."'
				AND Column_2='".$allInstances[$i]['Seat_col']."') ";
				$results2 = mysqli_query($con, $sql2);
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
						Employee_id <> '".$ID."'
				AND
				(Route_no='".$row2[0]."'
				AND Date='".$row2[1]."'
				AND Start_time='".$row2[2]."'
				AND Seat_row='".$row2[3]."'
				AND Seat_col='".$row2[4]."') OR 
				(Route_no='".$row2[5]."'
				AND Date='".$row2[6]."'
				AND Start_time='".$row2[7]."'
				AND Seat_row='".$row2[8]."'
				AND Seat_col='".$row2[9]."') ";

				$results3 = mysqli_query($con, $sql3);

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
        $sql="SELECT * FROM passenger WHERE Employee_id='".$IDs[$i]."'";
        $result=mysqli_query($con, $sql);
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
$con=mysqli_connect("localhost","root","root","471project");

// Check connection
if (mysqli_connect_errno())
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


//step 1  
echo "<h1>All trips taken by contagious passenger</h1>";
getEmpSeats($ID,$con);

echo '<form> <button class="button" type="submit"formaction="/finalProject471/website/adminMainView.php"> return to previous page</button></form>';


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