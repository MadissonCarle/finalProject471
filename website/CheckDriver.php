<?php

$DRIVERID = $_POST["DriverID"];
$PASSWORD = $_POST["Password"];
$ROUTENO = $_POST["Route_no"];
$BUSNO = $_POST["bus_no"];

//Create bus driver data as array
$data = array ( 
    'DriverID' => $DRIVERID,
    'Password' => $PASSWORD
);
$url = 'verifyBusDriver.php';
$returnval = sendReceiveJSONPOST($url,$data);

if ($returnval["status"] == "true"){ // check that route and bus exist
    
    //Create route data as array
    $data = array ( 
        'Route_no' => $ROUTENO
    );
    $url = 'verifyRoute.php';
    $returnval = sendReceiveJSONPOST($url,$data);

    if($returnval["status"] == "true"){ // check that the bus exists
        
        //Create bus data as array
        $data = array ( 
            'bus_no' => $BUSNO
        );
        $url = 'getBus.php';
        $thebus = sendReceiveJSONPOST($url,$data);
  
        if($thebus["status"] == "true") { //everything exists, create a vaild route instance
            $routeinstance = createRouteInstance($DRIVERID,$ROUTENO,$thebus);
            
            // give this to another page and return
            session_start();
            $_SESSION["Route_no"] = $routeinstance['Route_no'];
            $_SESSION["Date"] = $routeinstance['Date'];
            $_SESSION["Start_time"] = $routeinstance['Start_time'];
            $redirect =  "Location: showBusLayout.php";
            header($redirect);
            exit;
        }
        else {
            echo "<p>".$thebus["status"]."</p>";
            echo "<form action=\"index.php\" method=\"post\">
                    <input type=\"submit\" value=\"Return to main page\">
                    </form>";
        }
    }
    else{
        echo "<p>".$returnval["status"]."</p>";
        echo "<form action=\"index.php\" method=\"post\">
                    <input type=\"submit\" value=\"Return to main page\">
                    </form>";
    }   
}
else{
    echo "<p>".$returnval["status"]."</p>";
    echo "<form action=\"index.php\" method=\"post\">
                    <input type=\"submit\" value=\"Return to main page\">
                    </form>";
}

?>
 
<?php
// Creates a route instance with the correct seat layout for the bus and the current date and time. Also sets the seats that are in proximity correctly.
    function createRouteInstance($d,$r,$b) {
        //Create data for route instance as array
        $data = array ( 
            'Route_no' => $r,
            'Vehicle_id' => $b["Vehicle_id"],
            'DriverID' => $d
        );
        $url = 'insertRouteInstance.php';
        $returnval = sendReceiveJSONPOST($url,$data); // this is the route instance
        
        if($returnval["status"] != 'true') {
            echo "<p>".$returnval["status"]."</p>";
            echo "<form action=\"index.php\" method=\"post\">
                    <input type=\"submit\" value=\"Return to main page\">
                    </form>";
            exit;
        }
        
        //Grab the bus_type
        $data = array ( 
            'Model_no' => $b["Model_no"],
        );
        $url = 'getBusType.php';
        $bustype = sendReceiveJSONPOST($url,$data);
         if($bustype["status"] != 'true') {
            echo "<p>".$bustype["status"]."</p>";
            echo "<form action=\"index.php\" method=\"post\">
                    <input type=\"submit\" value=\"Return to main page\">
                    </form>";
            exit;
        }
        
        //Set up the seats
        setUpSeats($returnval,$bustype);
        return $returnval;
    }

//Creates a 2D array of seats with the number of rows and columns specified in bus type. Also sets which seats are in proximity
    function setUpSeats($instance,$btype) {
        //Create the seats
        $numrows = $btype['No_of_rows'];
        $numcols = $btype['No_of_cols'];
        $routeno = $instance['Route_no'];
        $date = $instance['Date'];
        $time = $instance['Start_time'];
        
        
        // Creat an array of seats
        for($i=1; $i <= $numrows; $i+=1) {
            for($j=1; $j <= $numcols; $j+=1) {
                $data = array ( 
                    'Route_no' => $routeno,
                    'Date' => $date,
                    'Start_time' => $time,
                    'Row' => $i,
                    'Column' => $j
                );
                $url = 'insertSeat.php';
                sendReceiveJSONPOST($url,$data);
            }
        }
        
        //Set the seats that are in proximity
		for($i=1; $i <= $numrows; $i+=1) {
			for($j=1; $j <= $numcols; $j+=1) {
				if($j!=$numcols) {// insert proximity to seat on the right if it exists
                    $col = $j +1;
                    $data = array ( 
                        'Route_no' => $routeno,
                        'Date' => $date,
                        'Start_time' => $time,
                        'Row1' => $i,
                        'Column1' => $j,
                        'Row2' => $i,
                        'Column2' => $col
                    );
                    $url = 'insertInProximity.php';
                    sendReceiveJSONPOST($url,$data);
                }
				if($i!=$numrows) { // insert proximity to seat directly below if it exists
                    $row = $i + 1;
                    $data = array ( 
                        'Route_no' => $routeno,
                        'Date' => $date,
                        'Start_time' => $time,
                        'Row1' => $i,
                        'Column1' => $j,
                        'Row2' => $row,
                        'Column2' => $j
                    );
                    $url = 'insertInProximity.php';
                    sendReceiveJSONPOST($url,$data);
                }
				if($i!=$numrows && $j!=$numcols) { // insert proximity to seat diagonally right down if it exists
                    $row = $i + 1;
                    $col = $j +1;
                    $data = array ( 
                        'Route_no' => $routeno,
                        'Date' => $date,
                        'Start_time' => $time,
                        'Row1' => $i,
                        'Column1' => $j,
                        'Row2' => $row,
                        'Column2' => $col
                    );
                    $url = 'insertInProximity.php';
                    sendReceiveJSONPOST($url,$data);
                }
				if($j!=$numcols && ($i-1) > 0) { // insert proximity to seat diagonal right up if it exists
                    $row = $i - 1;
                    $col = $j +1;
                    $data = array ( 
                        'Route_no' => $routeno,
                        'Date' => $date,
                        'Start_time' => $time,
                        'Row1' => $i,
                        'Column1' => $j,
                        'Row2' => $row,
                        'Column2' => $col
                    );
                    $url = 'insertInProximity.php';
                    sendReceiveJSONPOST($url,$data);
                }
			}
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

