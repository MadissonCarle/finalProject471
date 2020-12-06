<html>
<body>

<?php
    // Grab session data
    session_start();
    $ROUTENO = $_SESSION["Route_no"];
    $DATE = $_SESSION["Date"];
    $STARTTIME = $_SESSION["Start_time"];
    
    // Create connection
    $con=mysqli_connect("localhost","root","root","471project");

    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    //Get the seats and passenger seats and display
    $query = "SELECT * FROM seat where Route_no=? AND Date=? AND Start_time=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('iss',$ROUTENO,$DATE,$STARTTIME);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $array = array(); //according to the internet these are automatically resized to 2d if needed
    
    //for each seat, check if it is occupied
    while($row = $result->fetch_assoc()) {
        $query = "SELECT * FROM passenger_seat where Route_no=? AND Date=? AND Start_time=? AND Seat_row=? AND Seat_col=?";
        $stmt = $con->prepare($query);
        $seatrow = $row['Row'];
        $seatcol = $row['Column'];
        $stmt->bind_param('issii',$ROUTENO,$DATE,$STARTTIME,$seatrow,$seatcol);
        $stmt->execute();
        $result2 = $stmt->get_result();
        if($result2->fetch_assoc()) { // there's a passenger here
            $array[$seatrow -1 ][$seatcol -1] = 1; // taken
        }
        else { // the seat is free
            $array[$seatrow -1 ][$seatcol -1] = 2; // free
        }
    }
    mysqli_close($con);
    //echo "<p>Errors maybe?</p>";
    echo "<h3>Seat Map for Route ".$ROUTENO." (Date: ".$DATE.", Start Time: ".$STARTTIME.")</h3>";
    echo "<h4>Key: Grey = aisle, Red = occupied, Green = unoccupied</h4>";
    
    //Display a table
    echo "<table border='1'>";
    for ($i=0; $i < count($array); $i+=1) {
        $numcols = count($array[$i]); //get num of cols
        echo "<tr>";
        $aisle = 0;
        if($numcols % 2 == 0) { // number of columns is even
            $aisle = $numcols / 2; // index of the aisle (using zero index)
        }
        else { // number of columns is odd
            $aisle = ($numcols+1) / 2; // index of the aisle (using zero index)
        }
        for($j=0; $j<=$numcols; $j+=1) {
            if ($j < $aisle) {
                if($array[$i][$j] == 2) { // seat is free
                    echo "<td style=\"background-color:#18c446\" width=\"50\" height\"50\">&nbsp</td>"; //height doesn't change for whatever reason
                }
                else if($array[$i][$j] == 1) { //seat is occupied
                    echo "<td style=\"background-color:#de221f\" width=\"50\" height\"50\">&nbsp</td>";
                }
            }
            else if ($j == $aisle) { // this is the aisle
                echo "<td style=\"background-color:#82817e\" width=\"50\" height\"50\">&nbsp</td>";
            }
            else { // it's after the aisle
                if($array[$i][$j-1] == 2) { // seat is free
                    echo "<td style=\"background-color:#18c446\" width=\"50\" height\"50\">&nbsp</td>";
                }
                else if($array[$i][$j-1] == 1) { //seat is occupied
                    echo "<td style=\"background-color:#de221f\" width=\"50\" height\"50\">&nbsp</td>";
                }
            }
        }
        
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h4>New passenger?</h4>";
?>
<form action="addPassengerSeat.php" method="post">
   Enter Employee ID: <input type="text" name="EmployeeID"><br>
   <input type="submit" value="ENTER">
</form>

</body>
</html>