<?php
$ADMINID = $_POST["AdminID"];
$PASSWORD = $_POST["Password"];

//Create admin data as array
$data = array ( 
    'AdminID' => $ADMINID,
    'Password' => $PASSWORD
);
$url = 'verifyAdmin.php';
$returnval = sendReceiveJSONPOST($url,$data);

//// Initialize the session
//session_start();
//  $_SESSION["loggedin"] = false;
//// Check if the user is already logged in, if yes then redirect him to welcome page
//if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
//    header("location: adminMainView.php");
//    exit;
//}


if ($returnval["status"] == "true"){ // check that route and bus exist
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
    echo "<p>".$returnval["status"]."</p>";
    echo "<form action=\"index.php\" method=\"post\">
                    <input type=\"submit\" value=\"Return to main page\">
                    </form>";
}
?>
<?php

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