<?ph
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: adminLogin.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<style>
.button {
  background-color: #58bee0;
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
}
</style>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 500px; padding: 20px; }
    </style>
</head>
<body>
<div class="wrapper">
<h1>Get information on an employee in the database by entering their first and last name</h1>
<form action="searchEmp.php" method="post">
   First Name: <input type="text" name="FirstName" class="form-control"><br>
   Last Name: <input type="text" name="LastName"class="form-control"><br>
   <button class="button" type="submit">Find Employee
    </button> 
</form>
<h1>Find employees that were in close proximity to a contageous individual by entering their ID</h1>
<form action="searchProximity.php" method="post">
   Employee ID: <input type="text" name="EmployeeID" class="form-control"><br>
   <button class="button" type="submit">Find Employees
    </button> 
    
</form>
    <form><button class="button" type="Admin"formaction="logoutAdmin.php"> Logout</button></form>
</div> 
</body>
</html>