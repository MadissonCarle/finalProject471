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
    $empExists = employeeExists($con,$EMPID);
    if (!$empExists) { // if the employee doesn't exist
        $_SESSION["Row"] = -1;
        $_SESSION["Col"] = -1;
        $_SESSION["Employee"] = -1;
        returnToBusLayout($con);
    }

    //Check if the employee already has a seat
    $empHasSeat = hasSeat($con,$ROUTENO,$DATE,$STARTTIME,$EMPID);
    if($empHasSeat) {
        returnToBusLayout($con);
    }

    //Get the seats on this route instance
    $query = "SELECT * FROM seat where Route_no=? AND Date=? AND Start_time=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('iss',$ROUTENO,$DATE,$STARTTIME);
    $stmt->execute();
    $result = $stmt->get_result();

    //Find the first available seat
    $goodrow = -1;
    $goodcol = -1;
    while($row = $result->fetch_assoc()) {
        $query = "SELECT * FROM passenger_seat where Route_no=? AND Date=? AND Start_time=? AND Seat_row=? AND Seat_col=?";
        $stmt = $con->prepare($query);
        $seatrow = $row['Row'];
        $seatcol = $row['Column'];
        $stmt->bind_param('issii',$ROUTENO,$DATE,$STARTTIME,$seatrow,$seatcol);
        $stmt->execute();
        $result2 = $stmt->get_result();
        if(! $result2->fetch_assoc()) { // the seat is free
            global $goodrow, $goodcol;
            $goodrow = $seatrow;
            $goodcol = $seatcol;
            
            $query = "INSERT INTO passenger_seat (Route_no, Date, Start_time, Seat_row, Seat_col, Employee_id) VALUES (?,?,?,?,?,?)";
            $stmt = $con->prepare($query);
            $stmt->bind_param('issiii',$ROUTENO,$DATE,$STARTTIME,$seatrow,$seatcol,$EMPID);
            $stmt->execute();
            break;
        }
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

    function employeeExists($con,$EMPID) {
        $query = "SELECT * FROM passenger where Employee_id=?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i',$EMPID);
        $stmt->execute();
        $result = $stmt->get_result();
        if(! $result->fetch_assoc()) {
            return false;
        }
        return true;
    }

    function hasSeat($con, $ROUTENO,$DATE,$STARTTIME,$EMPID) {
        $query = "SELECT * FROM passenger_seat where Route_no=? AND Date=? AND Start_time=? AND Employee_id=?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('issi',$ROUTENO,$DATE,$STARTTIME,$EMPID);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $_SESSION["Row"] = $row['Seat_row'];
            $_SESSION["Col"] = $row['Seat_col'];
            $_SESSION["Employee"] = $EMPID;
            returnToBusLayout($con);
            return true;
        }

        return false;
    }

    function setBoardsAt($con,$ROUTENO,$EMPID) {
        //get a location that the bus stops at
        $query = "SELECT * from stops_at WHERE Route_no =? ORDER BY rand() LIMIT 1";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i',$ROUTENO);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        // set that location in boards_at for the passenger
        // Get the boarding time
        date_default_timezone_set("America/Edmonton");
        $time = date("H:i:s"); // get the current time
        
        $query = "INSERT INTO boards_at (Employee_id,Address,Boarding_time) VALUES (?,?,?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param('isi',$EMPID,$row["Address"],$time);
        $stmt->execute();
    }
?>