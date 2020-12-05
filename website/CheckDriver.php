<?php

$DRIVERID = $_POST["DriverID"];
$PASSWORD = $_POST["Password"];
//$PASSWORD = 'jsmith';
$ROUTENO = $_POST["Route_no"];
$BUSNO = $_POST["bus_no"];

// Create connection
$con=mysqli_connect("localhost","root","root","471project");

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

// Get the bus driver
$query = "SELECT * FROM bus_driver where Driver_id=? AND Password =?";
$stmt = $con->prepare($query);
$stmt->bind_param('is',$DRIVERID,$PASSWORD);
$stmt->execute();
$result = $stmt->get_result();

//Check if driver exists
$thedriver = $result->fetch_assoc();
if ($thedriver){ // check that route and bus exist
    //Get the route data
    $query = "SELECT * FROM route where Route_no=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i',$ROUTENO);
    $stmt->execute();
    $result = $stmt->get_result();
    $theroute = $result->fetch_assoc();

    if($theroute){ // check that the bus exists
        $query = "SELECT * FROM bus where Vehicle_id=?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i',$BUSNO);
        $stmt->execute();
        $result = $stmt->get_result();
        $thebus = $result->fetch_assoc();
        if($thebus) { //everything exists, create a vaild route instance
            $routeinstance = createRouteInstance($con,$thedriver,$theroute,$thebus);
            // give this to another page and return
            echo "<p>We did it?</p>";
        }
        else {
            echo "<p>Bus not found.</p>";
        }
    }
    else{
        echo "<p>Route not found.</p>";
    }   
}
else{
    echo "<p>Driver not found.</p>";
}
//include some option to return

?>
 
<?php
// Creates a route instance with the correct seat layout for the bus and the current date and time. Also sets the seats that are in proximity correctly.
    function createRouteInstance($con,$d,$r,$b) {
        $stmt =$con->prepare("INSERT INTO route_instance (Route_no, Date, Start_time, Driver_id, Vehicle_id) VALUES (?, ?, ?, ?,?)");
        $date = date("Y/m/d"); // get the date
        $time = date("H:i:s"); // get the current time
        $stmt->bind_param("issii",$r['Route_no'],$date,$time,$d['Driver_id'],$b['Vehicle_id']); // add the values
        $stmt->execute(); // creates the route instance
        
        //Grab the new route instance
        $query = "SELECT * FROM route_instance where Route_no=? AND Date=? AND Start_time=?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('iss',$r['Route_no'],$date,$time);
        $stmt->execute();
        $result = $stmt->get_result();
        $newinstance = $result->fetch_assoc();
        
        //Grab the bus type to set up the the seats:
        $query = "SELECT * FROM bus_type where Model_no=?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i',$b['Model_no']);
        $stmt->execute();
        $result2 = $stmt->get_result();
        $bustype = $result2->fetch_assoc();
        
        //Check error
        if(!$con->prepare("INSERT INTO seat VALUES (?, ?, ?, ?, ?)")) {
             echo "<p>Error</p>";
        }
        
        //echo "<p>" . $newinstance['Route_no'] . "</p>";
        //Set up the seats
        setUpSeats($con,$newinstance,$bustype);
        
        return $newinstance;
    }

//Creates a 2D array of seats with the number of rows and columns specified in bus type. Also sets which seats are in proximity
    function setUpSeats($con,$instance,$btype) {
        
        echo "<p>" . $instance['Route_no'] . "</p>";
         echo "<p>" . $btype['No_of_rows'] . "</p>";
        echo "<p>" . $btype['No_of_cols'] . "</p>";
        
        //Create the seats
        $numrows = $btype['No_of_rows'];
        
        $numcols = $btype['No_of_cols'];
        $routeno = $instance['Route_no'];
        $date = $instance['Date'];
        $time = $instance['Start_time'];
        echo "<p>" . $date . "</p>";
        echo "<p>" . $time . "</p>";
        
        for($i=1; $i <= $numrows; $i+=1) {
            /* if (is_int($i)) {
                echo "<p>Is int</p>";
                echo "<p>" . strlen($date) . "</p>";
            } */
            for($j=1; $j <= $numcols; $j+=1) {
                $stmt =$con->prepare("INSERT INTO seat VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param('issii',$routeno,$date,$time,$i,$j); // add the values
                $stmt->execute(); // creates the seat
            }
        }
        
        //Set the seats that are in proximity
		for($i=1; $i <= $numrows; $i+=1) {
			for($j=1; $j <= $numcols; $j+=1) {
				if($j!=$numcols) {// insert proximity to seat on the right if it exists
					$stmt =$con->prepare("INSERT INTO in_proximity (Route_no_1, Date_1, Start_time_1, Row_1, Column_1, Route_no_2, Date_2, Start_time_2, Row_2, Column_2) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $col = $j +1;
                    $stmt->bind_param('issiiissii',$routeno,$date,$time,$i,$j,$routeno,$date,$time,$i,$col); // add the values
                    $stmt->execute();
                }
				if($i!=$numrows) { // insert proximity to seat directly below if it exists
					$stmt =$con->prepare("INSERT INTO in_proximity (Route_no_1, Date_1, Start_time_1, Row_1, Column_1, Route_no_2, Date_2, Start_time_2, Row_2, Column_2) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $row = $i + 1;
                    $stmt->bind_param('issiiissii',$routeno,$date,$time,$i,$j,$routeno,$date,$time,$row,$j); // add the values
                    $stmt->execute();
                }
				if($i!=$numrows && $j!=$numcols) { // insert proximity to seat diagonally right down if it exists
					$stmt =$con->prepare("INSERT INTO in_proximity (Route_no_1, Date_1, Start_time_1, Row_1, Column_1, Route_no_2, Date_2, Start_time_2, Row_2, Column_2) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $row = $i + 1;
                    $col = $j +1;
                    $stmt->bind_param('issiiissii',$routeno,$date,$time,$i,$j,$routeno,$date,$time,$row,$col); // add the values
                    $stmt->execute();
                }
				if($j!=$numcols && ($i-1) > 0) { // insert proximity to seat diagonal right up if it exists
					$stmt =$con->prepare("INSERT INTO in_proximity (Route_no_1, Date_1, Start_time_1, Row_1, Column_1, Route_no_2, Date_2, Start_time_2, Row_2, Column_2) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $row = $i - 1;
                    $col = $j +1;
                    $stmt->bind_param('issiiissii',$routeno,$date,$time,$i,$j,$routeno,$date,$time,$row,$col); // add the values
                    $stmt->execute();
                }
			}
		}
    }
        


?>

  
<?php

/* echo "<table border='1'>
<tr>
<th>DriverID</th>
<th>Password</th>
</tr>";

 while($row = $result->fetch_assoc())
  {
    echo "<tr>";
  echo "<td>" . $row['Driver_id'] . "</td>";
  echo "<td>" . $row['Password'] . "</td>";
  echo "</tr>";
  }
echo "</table>";
 ?> */

mysqli_close($con);
?>

