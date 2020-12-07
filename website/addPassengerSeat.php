<?php 
    //grab session data
    session_start();
    $ROUTENO = $_SESSION["Route_no"];
    $DATE = $_SESSION["Date"];
    $STARTTIME = $_SESSION["Start_time"];

     // Create connection
    $con=mysqli_connect("localhost","root","root","471project");

    // Check connection
    if (mysqli_connect_errno())
    {
        echo "<html><body><p>Failed to connect to MySQL: " . mysqli_connect_error()."</p></body></html>";
        exit;
    }

    $EMPID = $_POST["EmployeeID"];

    // Find the first free seat and assign it

    //Check that employee exists
    $empExists = employeeExists($EMPID);
    if (!$empExists) { // if the employee doesn't exist
        $_SESSION["Row"] = -1;
        $_SESSION["Col"] = -1;
        $_SESSION["Employee"] = -1;
        returnToBusLayout($con);
    }
    //Check if the employee already has a seat
    $empHasSeat = hasSeat($ROUTENO,$DATE,$STARTTIME,$EMPID);
    if($empHasSeat) {
        returnToBusLayout($con);
    }
    
    //Get the seats on this route instance
    //Create route data as array
    $data = array ( 
        'Route_no' => $ROUTENO,
        'Date' => $DATE,
        'Start_time' => $STARTTIME
    );
    $url = 'getSeatOnRouteInstance.php';
    $returnval = sendReceiveJSONPOST($url,$data);

    //Find the first available seat
    $goodrow = -1;
    $goodcol = -1;
    $count = 0;
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
        
        if($row["status"] != 'true') { // the seat is free
            /* echo $seatrow;
            echo $seatcol;
            echo $EMPID;
            exit; */
            global $goodrow, $goodcol;
            $goodrow = $seatrow;
            $goodcol = $seatcol;
            
            $data = array ( 
                'Route_no' => $ROUTENO,
                'Date' => $DATE,
                'Start_time' => $STARTTIME,
                'Row' => $seatrow,
                'Column' => $seatcol,
                'Employee_id' => $EMPID
            );
            $url = 'insertPassengerSeat.php';
            $row = sendReceiveJSONPOST($url,$data);
            break;
        }
        $count += 1;
    }
// Setting session row and column to -1,-1 is checked for in showBusLayout, means there was no available seat and error is displayed accordingly
    if($goodrow == -1 || $goodcol == -1) { // bus is full
        $_SESSION["Row"] = $goodrow;
        $_SESSION["Col"] = $goodcol;
        $_SESSION["Employee"] = $EMPID;
        returnToBusLayout($con);
    }
    
    //Set a board location and time
    setBoardsAt($con,$ROUTENO,$EMPID);
   
    // Set the row and column
    $_SESSION["Row"] = $goodrow;
    $_SESSION["Col"] = $goodcol;
    $_SESSION["Employee"] = $EMPID;
    returnToBusLayout($con);
?>

<?php
    function returnToBusLayout($con) {
        mysqli_close($con);
        $redirect =  "Location: showBusLayout.php"; // go back to previous page
        header($redirect);
        exit;
    }

    function employeeExists($EMPID) {
        $data = array ( 
            'Employee_id' => $EMPID,
        );
        $url = 'getPassenger.php';
        $returnval = sendReceiveJSONPOST($url,$data);
        
        if($returnval["status"] == 'true') {
            return true;
        }
        return false;
    }

    function hasSeat($ROUTENO,$DATE,$STARTTIME,$EMPID) {
         $data = array ( 
            'Route_no' => $ROUTENO,
            'Date' => $DATE,
            'Start_time' => $STARTTIME,
            'Employee_id' => $EMPID
        );
        $url = 'verifyPassengerSeat.php';
        $return = sendReceiveJSONPOST($url,$data);
        
        if ($return["status"] == 'true') {
            $_SESSION["Row"] = $return['Seat_row'];
            $_SESSION["Col"] = $return['Seat_col'];
            $_SESSION["Employee"] = $EMPID;
            return true;
        }

        return false;
    }

    function setBoardsAt($con,$ROUTENO,$EMPID) {
        //get a location that the bus stops at
        $data = array ( 
            'Route_no' => $ROUTENO,
        );
        $url = 'getRandomStop.php';
        $return = sendReceiveJSONPOST($url,$data);
        
        // set that location in boards_at for the passenger
        // Get the boarding time
        date_default_timezone_set("America/Edmonton");
        $time = date("H:i:s"); // get the current time
        
        $data = array ( 
            'Employee_id' => $EMPID,
            'Boarding_time' => $time,
            'Address' => $return['Address']
        );
        $url = 'insertBoardsAt.php';
        $return = sendReceiveJSONPOST($url,$data);
    }

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