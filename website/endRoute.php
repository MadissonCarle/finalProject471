<?php
    // Grab session data
    session_start();
    $ROUTENO = $_SESSION["Route_no"];
    $DATE = $_SESSION["Date"];
    $STARTTIME = $_SESSION["Start_time"];
    
    setDisembarksAt($ROUTENO,$DATE,$STARTTIME);
    
    //Unset all the session variables
    $_SESSION = array();
    
    session_destroy(); // get rid of everything in the session
    $redirect =  "Location: index.php"; // go back to previous page
    header($redirect);
    exit;
?>

<?php
    function setDisembarksAt($ROUTENO,$DATE,$STARTTIME) {
        //get a location that the bus stops at
        $data = array ( 
            'Route_no' => $ROUTENO,
        );
        $url = 'getRandomStop.php';
        $location = sendReceiveJSONPOST($url,$data);
        
        // set that location in boards_at for the passenger
        // Get the disembark time
        date_default_timezone_set("America/Edmonton");
        $time = date("H:i:s"); // get the current time
        
        //Get all the passengers on this bus (getPassengerSeatOnRouteInstance)
        $data = array ( 
            'Route_no' => $ROUTENO,
            'Date' => $DATE,
            'Start_time' => $STARTTIME
        );
        $url = 'getPassengerSeatOnRouteInstance.php';
        $returnval = sendReceiveJSONPOST($url,$data);
        
        //Set the disembark location and time for all passengers
        $count = 0;
        while($count < $returnval["TupleCount"]) {
            
            $employee = $returnval['Tuples'][$count]['Employee_id'];
            $data = array ( 
                'Employee_id' => $employee,
                'Disembark_time' => $time,
                'Address' => $location['Address']
            );
            $url = 'insertDisembarksAt.php';
            $return = sendReceiveJSONPOST($url,$data);
            $count +=1;
        }
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