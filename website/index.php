
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
</head>
<body>
    <form action="/index.php" method="get">
<!--       page that user can use to navigate to proper home page-->
        <button class="button" type="submit"formaction="/finalProject471/website/LoginAdmin.php"> Login as Admin</button>
        <button class="button" type="submit"formaction="/finalProject471/website/busDriverLogin.php"> Login as Bus Driver</button>
        </form>
    </body>
</html>

<?php
// Initialize the session
session_start();
 
// Unset all of the session variables
$_SESSION = array();
 
// Destroy the session.
session_destroy();
?>