<?php

 $First_name = $_POST["FirstName"];
        $Last_name = $_POST["LastName"];
echo $First_name. "<br>". $Last_name. "<br>";


// Create connection
$con=mysqli_connect("localhost","root","MyNewPass","471project");

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }


   
    $sql="SELECT * FROM Passenger 
    WHERE First_name ='".$First_name."'
    AND Last_name='".$Last_name."'";
    $results = mysqli_query($con, $sql);
     if (mysqli_num_rows($results) < 1) {
        echo "invalid employee name";
         echo '<form><input type="button" value="Return to previous page" onClick="javascript:history.go(-1)"></form>';
    } else {
         echo "<table border='1'>
<tr>
<th>ID</th>
<th>First Name</th>
<th>Last Name</th>
<th>Department</th>
<th>Admin ID</th>
</tr>";
        while( $row = mysqli_fetch_array($results)){
    echo "<tr>";
  echo "<td>" . $row['Employee_id'] . "</td>";
  echo "<td>" . $row['First_name'] . "</td>";
  echo "<td>" . $row['Last_name'] . "</td>";
  echo "<td>" . $row['Department'] . "</td>";
  echo "<td>" . $row['Admin_id'] . "</td>";
    echo"</tr>";
        }
echo "</table>";
 echo '<form><input type="button" value="Return to previous page" onClick="javascript:history.go(-1)"></form>';
     }



mysqli_close($con);
?>