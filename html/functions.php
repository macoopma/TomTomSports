<?php 

function put_config_file($file, $array, $i = 0){
  foreach($array as $param => $value) {
    $paramsJoined[] = "$param=$value";
   }
  $str = implode(PHP_EOL, $paramsJoined);
  // $str = http_build_query($array, '' , PHP_EOL);
  file_put_contents($file,$str);
// May want to add some error handling here 
 }

function get_config_file($file) {
  $str = file_get_contents($file);
  $s1= str_replace(PHP_EOL, "&", $str);
  $s2= str_replace(" ", "", $s1);
  parse_str($s2, $array);
 return $array;
}

 // test the script 
 if (basename(__FILE__) == basename ($_SERVER['SCRIPT_FILENAME'])) { 
 echo "<HTML><BODY><H2> Running functions in test mode BLE</H2>" . PHP_EOL;
 
 echo "<TABLE border=1> \n";
 echo  "<TR><TD> __FILE__ <TD>" . __file__ . "</TR> \n" ; 
 echo  "<TR><TD> script_filename  <TD>" . $_SERVER['SCRIPT_FILENAME'] . "</TR> \n" ; 
 echo "</TABLE > \n";
 

 $config = get_config_file ("./config.ini");
 echo "<P><PRE>" . PHP_EOL ;

 var_dump ($config) ;
 echo "</PRE>" . PHP_EOL;
 
 $config ["mac"] ="wasHere";  
 put_config_file ("./configtest.ini", $config);

 echo "<P> <PRE>" . PHP_EOL ;
 
  var_dump ($config) ;
  echo "</PRE>" . PHP_EOL;
  
 echo '</BODY></HTML>' . PHP_EOL ;

}

?>

