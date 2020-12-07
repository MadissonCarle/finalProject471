<?php
/*
Finds employee information through a provided name
*/
 $First_name = $_POST["FirstName"];
 $Last_name = $_POST["LastName"];

//Create admin data as array
$data = array ( 
    "FirstName" => $First_name,
    "LastName" => $Last_name
);
//query
$url = 'verifyEmp.php';
$returnval = sendReceiveJSONPOST($url,$data);

session_start();

if ($returnval["status"] == "true"){ // check that emp exist
//output table of their information
   echo "<table border='1'>
<tr>
<th>ID</th>
<th>First Name</th>
<th>Last Name</th>
<th>Department</th>
<th>Admin ID</th>
</tr>";
         $i=0;
         while($i<$returnval["TupleCount"]){
             $row=$returnval["Tuples"][$i];
    echo "<tr>";
  echo "<td>" . $row['Employee_id'] . "</td>";
  echo "<td>" . $row['First_name'] . "</td>";
  echo "<td>" . $row['Last_name'] . "</td>";
  echo "<td>" . $row['Department'] . "</td>";
  echo "<td>" . $row['Admin_id'] . "</td>";
    echo"</tr>";
             $i=$i+1;
        }
echo "</table>";

     } else {
       echo "<p>".$returnval["status"]."</p>";
     }
echo '<form> <button class="button" type="submit"formaction="/finalProject471/website/adminMainView.php">Return to previous page</button></form>';


?>
<?php

// Sends data as POST to the form at $url, receives and decodes the JSON response as an array.
    function sendReceiveJSONPOST($url,$data) {
        $data = http_build_query($data);
        $options = array(
          'http' => array(
            'method'  => 'POST',
              'header' =>  "Content-type: application/x-www-form-urlencoded\r\n"."Content-Length: " . strlen($data) . "\r\n",
                'content' => $data
            
            )
        );

        $context  = stream_context_create( $options );
        $result = file_get_contents('http://localhost/finalProject471/website/'.$url, false, $context );
        $response = json_decode( $result, true );
        return $response;
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
</html>