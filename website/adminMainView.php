<?ph
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: adminLogin.php");
    exit;
}
?>

<html>
<body>

<h1>Get information on an employee in the database by entering their first and last name</h1>
<form action="searchEmp.php" method="post">
   First Name: <input type="text" name="FirstName"><br>
   Last Name: <input type="text" name="LastName"><br>
   <input type="submit" value="find">
</form>
<h1>Find employees that were in close proximity to a contageous individual by entering their ID</h1>
<form action="searchProximity.php" method="post">
   Employee ID: <input type="text" name="EmployeeID"><br>
   <input type="submit" value="Find employees in proximity">
    
</form>
    <form><button type="Admin"formaction="logoutAdmin.php"> Logout</button></form>

</body>
</html>