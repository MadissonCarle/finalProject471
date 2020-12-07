<html>
<body>

<form action="CheckDriver.php" method="post">
   Driver ID: <input type="text" name="DriverID"><br>
   Password: <input type="text" name="Password"><br>
    Route #: <input type="text" name="Route_no"><br>
    Bus #: <input type="text" name="bus_no"><br>
   <input type="submit" value="Login">
</form>

</body>
</html>

<?php
    session_start();
    //Unset all the session variables
    $_SESSION = array();
    session_destroy(); // get rid of everything in the session
?>