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
        echo "<html><body><p>Failed to connect to MySQL: " . mysqli_connect_error()."</p></body></html>";
        exit;
    }
    
    setDisembarksAt($con,$ROUTENO,$DATE,$STARTTIME);
    
    //Unset all the session variables
    $_SESSION = array();
    
    session_destroy(); // get rid of everything in the session
    mysqli_close($con);
    $redirect =  "Location: index.php"; // go back to previous page
    header($redirect);
    exit;
?>

<?php
    function setDisembarksAt($con,$ROUTENO,$DATE,$STARTTIME) {
        //get a location that the bus stops at
        $query = "SELECT * from stops_at WHERE Route_no =? ORDER BY rand() LIMIT 1";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i',$ROUTENO);
        $stmt->execute();
        $result = $stmt->get_result();
        $location = $result->fetch_assoc();
        
        // set that location in boards_at for the passenger
        // Get the disembark time
        date_default_timezone_set("America/Edmonton");
        $time = date("H:i:s"); // get the current time
        
        //Get all the passengers on this bus
        $query = "SELECT * from passenger_seat WHERE Route_no =? AND Date=? AND Start_time=?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('iss',$ROUTENO,$DATE,$STARTTIME);
        $stmt->execute();
        $result2 = $stmt->get_result();
        
        //Set the disembark location and time for all passengers
        while($row = $result2->fetch_assoc()) {
            $query = "INSERT INTO disembarks_at (Employee_id,Address,Disembark_time) VALUES (?,?,?)";
            $stmt = $con->prepare($query);
            $stmt->bind_param('iss',$row['Employee_id'],$location['Address'],$time);
            $stmt->execute();
        }
    }
?>