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
       
        <button class="button" type="submit"formaction="/finalProject471/website/LoginAdmin.php"> Login as Admin</button>
        <button class="button" type="submit"formaction="/finalProject471/website/busDriverLogin.php"> Login as Bus Driver</button>
        </form>
    </body>
</html>

<?php
    session_start();
    //Unset all the session variables
    $_SESSION = array();
    session_destroy(); // get rid of everything in the session
?>