<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title> TomTom Mysports callback page </title>

    <link href="css/styles.css" rel="stylesheet">


</head>
<body>
<div class="background">
<img src="img/running.jpg">

</div>

<?php 
 include 'functions.php' ;
 $config = get_config_file ("./config.ini");
 ?> 


<div class="outer">
	
	<div class="content-outer">
		
		<div class="intro">
            <p>
<H2> TomTom Mysports callback page </H2>

            </p>
        <div class="content-main">

<?php 

$auth_code=""; 
if (!empty($_GET))
 {
  
  // echo "<table border="1"><TR><TD colspan=2><CENTER> Form Get </CENTER></TD></TR>";
    foreach ($_GET as $key => $value) {
   /*     echo "<tr>";
        echo "<td>";
        echo $key;
        echo "</td>";
        echo "<td>";
        echo $value;
        echo "</td>";
        echo "</tr>"; 
        echo "</table>"; */
        $stap1 = FALSE;
        if ($value == 'stap1') 
        	{$stap1 = TRUE; 
        	//echo "stap1 is true";
        	 }
        if ($key == 'code')
            {$auth_code=$value; }

    }
 }  

?>

<?php
if ($auth_code !== '') {
   echo "Authorisation granted, code is: " . $auth_code ;
   $config ["auth_code"] = $auth_code ;

if ($stap1 == TRUE) {
	$url = 'https://api.tomtom.com/mysports/oauth2/token';
	$data = array('grant_type' => 'authorization_code' ,
              	  'code' => $auth_code ,
              	  'state' => 'stap2' ,

                    'redirect_uri' => $config["redirect_uri"]
                 );

                 $encoded = base64_encode ($config["client_id"] . ":" . $config["client_secret"] ) ;
$api_key = $config ["api_key"] ;
 
	// use key 'http' even if you send the request to https://...
	$options = array(
    	'http' => array(
				'method'  => 'POST',    		
        		'header'  => "Content-type: application/x-www-form-urlencoded\r\n" .  
          					 "Authorization: Basic " . $encoded . "\r\n" . 
                    		 "Content-Length: " . strlen(http_build_query($data)) . "\r\n" .
                			 "Api-Key: " . $api_key . "\r\n" , 
                			 
				'content' => http_build_query($data)
    		)
		);
  	$context  = stream_context_create($options);
    $result = file_get_contents($url, false , $context);
    
	if ($result === FALSE) { /* Handle error */ 
	  	var_dump($result);

    	}
echo '<P><P>';

//	var_dump($result);
	
	$result_arr= json_decode ($result, True ) ; 
	printf ("<HR>");
	$access_token= $result_arr["access_token"];	
	
    $refresh_token = $result_arr ["refresh_token"]; 	
    printf ("<P>Access is granted. <P>") ; 
    printf ('access token is ' . $access_token ) ;
    printf ('<P>refresh token is ' . $refresh_token ) ;
    
    $config ["access_token"] = $access_token ;
    $config ["refresh_token"] = $refresh_token ;
    
    put_config_file ("./config.ini", $config);
    
	 
	}

} // end if authorisation granted 
else {
    echo "<P> <CENTER> Authorisation NOT granted, please try again </CENTER><P>";
    }
?>

        </div>
		</div>
	</div>
</div>
</body>
</html>

<?php 
/*
$servername = "localhost";
    $username = "root";
    $password = "Dino2004!";
    $dbname = "sportdata";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        } 
     $sql = "INSERT INTO apps (api_key, client_id, client_secret, callback_url, access_token, refresh_token )
        VALUES ('$api_key', '$client_id', '$client_secret', '$callback_url', '$access_token', '$refresh_token')";

    if ($conn->query($sql) === TRUE) {
        echo "<P>New record created successfully";
        } else {    
        echo "<P>Error: " . $sql . "<br>" . $conn->error;
            }       
 
    $conn->close();
*/
?>

	
