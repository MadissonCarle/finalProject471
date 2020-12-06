<?php
$ADMINID = $_POST["AdminID"];
$PASSWORD = $_POST["Password"];

// Initialize the session
session_start();
  $_SESSION["loggedin"] = false;
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: adminMainView.php");
    exit;
}
// Include config file
require_once "config.php";
// Create connection
$con=mysqli_connect("localhost","root","MyNewPass","471project");

// Check connection
if (mysqli_connect_errno())
{
    echo "<html><body><p>Failed to connect to MySQL: " . mysqli_connect_error()."</p></body></html>";
    exit;
}

// Get the bus driver
$query = "SELECT Admin_id, Password FROM administrator where Admin_id=? AND Password =?";
$stmt = $con->prepare($query);
$stmt->bind_param('is',$ADMINID,$PASSWORD);
$stmt->execute();
$result = $stmt->get_result();

//Check if driver exists
$theAdmin = $result->fetch_assoc();
if ($theAdmin){ // check that route and bus exist
     // Password is correct, so start a new session
    session_start();
                            
    // Store data in session variables
    $_SESSION["loggedin"] = true;
                           
    $_SESSION["adminID"] = $adminID;                            
                            
    // Redirect user to welcome page
    header("location: adminMainView.php"); 
    exit;
}
else{
    echo "<p>Administrator not found.</p>";
    mysqli_close($con);
    echo "<form action=\"index.php\" method=\"post\">
                    <input type=\"submit\" value=\"Return to main page\">
                    </form>";
}

?>