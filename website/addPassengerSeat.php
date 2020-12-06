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
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

mysqli_close($con);
?>