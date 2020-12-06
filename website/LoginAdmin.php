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

<form action="CheckAdmin.php" method="post">
   Admin ID: <input type="text" name="AdminID"><br>
   Password: <input type="text" name="Password"><br>
   <button class="button" type="submit"> Login </button>
</form>
</body>
</html>