<html>
<body>

<?php
    // Grab session data
    session_start();
    $ROUTENO = $_SESSION["Route_no"];
    $DATE = $_SESSION["Date"];
    $STARTTIME = $_SESSION["Start_time"];
    
    //Get the seats and passenger seats and display
    //Create route data as array
    $data = array ( 
        'Route_no' => $ROUTENO,
        'Date' => $DATE,
        'Start_time' => $STARTTIME
    );
    $url = 'getSeatOnRouteInstance.php';
    $returnval = sendReceiveJSONPOST($url,$data);
    
    $array = array(); //according to the internet these are automatically resized to 2d if needed
    
    $count = 0;
    
    //for each seat, check if it is occupied
    while($count < $returnval["TupleCount"]) {
        $seatrow = $returnval['Tuples'][$count]['Row'];
        $seatcol = $returnval['Tuples'][$count]['Column'];
        $data = array ( 
            'Route_no' => $ROUTENO,
            'Date' => $DATE,
            'Start_time' => $STARTTIME,
            'Seat_row' => $seatrow,
            'Seat_col' => $seatcol
        );
        $url = 'getPassengerSeat.php';
        $row = sendReceiveJSONPOST($url,$data);

        if($row["status"] == 'true') { // there's a passenger here
            if (isset($_SESSION["Row"]) && isset($_SESSION["Col"]) && $_SESSION["Row"] == $seatrow && $_SESSION["Col"] == $seatcol) { // this is the last employee added
                $array[$seatrow -1 ][$seatcol -1] = 3; // taken by last employee
            }
            else {
                $array[$seatrow -1 ][$seatcol -1] = 1; // taken
            }
        }
        else { // the seat is free
            $array[$seatrow -1 ][$seatcol -1] = 2; // free
        }
        $count += 1;
    }
    echo "<h3>Seat Map for Route ".$ROUTENO." (Date: ".$DATE.", Start Time: ".$STARTTIME.")</h3>";
    echo "<h4>Key: Grey = aisle, Red = occupied, Green = unoccupied</h4>";
    if (isset($_SESSION["Row"]) && isset($_SESSION["Col"])) { // if we've returned from trying to assign a seat to an employee
		if($_SESSION["Employee"] == -1) { // error, that employee does not exist
			echo "<h4>Error: That employee does not exist. Please re-enter id.</h4>";
		}
        else if($_SESSION["Row"] == -1 || $_SESSION["Col"] == -1) { // last employee we inserted was unsuccesful
            echo "<h4>Error: bus is full! Cannot add another passenger!</h4>";
        }
        else { // last employee we inserted was successful
            echo "<h4>Employee ".$_SESSION["Employee"].", your seat is highlighted in yellow.</h4>";
        }
    }
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
                else { // the most recently added passenger
                    echo "<td style=\"background-color:#f5f242\" width=\"50\" height\"50\">&nbsp</td>";
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
                else { // the most recently added passenger
                    echo "<td style=\"background-color:#f5f242\" width=\"50\" height\"50\">&nbsp</td>";
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
    
<form action="endRoute.php" method="post">
   <input type="submit" value="END ROUTE">
</form>

</body>
</html>

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