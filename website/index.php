<html>
    <body> 
    <form action="/index.php" method="get">
        <button type="Admin"formaction="/finalProject471/website/adminLogin.php"> Login as Admin</button>
        <button type="Driver"formaction="/finalProject471/website/busDriverLogin.php"> Login as Bus Driver</button>
        </form>
    </body>
</html>

<?php
// Clear any session data if it exists
    session_start();
    unset($_SESSION["Route_no"]);
    unset($_SESSION["Date"]);
    unset($_SESSION["Start_time"]);
    unset($_SESSION["Row"]);
    unset($_SESSION["Col"]);
    unset($_SESSION["Employee"]);
    session_destroy(); 
?>