<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">         <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>TomTom Mysportcloud data access</title>

    <link href="css/styles.css" rel="stylesheet">
<STYLE>
table tr:hover td {
	background: #f2f2f2;
	background: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#f0f0f0));
	background: -moz-linear-gradient(top,  #f2f2f2,  #f0f0f0);	
}


table{border-collapse:collapse;border-spacing:0}
td,th{
		padding:0;
		vertical-align:top
		}

.pure-table {border-collapse:collapse;border-spacing:0;empty-cells:show;border:1px solid #cbcbcb}
.pure-table caption{color:#000;font:italic 85%/1 arial,sans-serif;padding:1em 0;text-align:center}
.pure-table td,.pure-table th{border-left:1px solid #cbcbcb;border-width:0 0 0 1px;font-size:inherit;margin:0;overflow:visible;padding:.3em .3em}
.pure-table td:first-child,.pure-table th:first-child{border-left-width:0}
.pure-table thead{background-color:#e0e0e0;color:#000;text-align:left;vertical-align:bottom}
.pure-table td{background-color:transparent}
.pure-table-odd td{background-color:#f2f2f2}
.pure-table-striped tr:nth-child(2n-1) td{background-color:#f2f2f2}
.pure-table-bordered td{border-bottom:1px solid #cbcbcb}
.pure-table-bordered tbody>tr:last-child>td{border-bottom-width:0}
.pure-table-horizontal td,.pure-table-horizontal th{border-width:0 0 1px;border-bottom:1px solid #cbcbcb}
.pure-table-horizontal tbody>tr:last-child>td{border-bottom-width:0}




</STYLE>

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
<H2> TomTom Mysportcloud data access </H2>

            </p>
        <div class="content-main">

This page is here to link to the TomTom mysports web site to ask for authorisation to read data from their Cloud for a specific user. 
<P>
<P>
Currently we have:
<?PHP
echo "<TABLE class='pure-table pure-table-bordered pure-table-striped'> \n" ;
echo "<TR><TD>Client Name<TD> " . $config ["client_name"] . PHP_EOL;
echo "<TR><TD>API-key <TD> " . $config ["api_key"] . PHP_EOL;
echo "<TR><TD>Client ID <TD>" . $config ["client_id"] . PHP_EOL;
echo "<TR><TD>Call back URL<TD>" . $config ["redirect_uri"] . PHP_EOL;
echo "</TABLE> \n" ;

$url = "https://mysports.tomtom.com/app/authorize-client/?client_name=" . $config["client_name"] 
       . "&scope=activities%20tracking%20heart_rate%20physiology"
       . "&redirect_uri=" . $config ["redirect_uri"] 
       . "&client_id=" . $config ["client_id"]
       . "&state=stap1" ;

/*
sportdata&scope=activities%20tracking%20heart_rate%20physiology&redirect_uri=https:%2F%2Ftt.coopmans.org%2Ftomtom%2Fcallback.php&client_id=d2d70f76-212b-44b1-9366-d8f4b59bb707&state=stap1
*/

echo "<P> If all that seems good please click <A href='" . $url . "'>here </A> and authorise from the TomTom web site with the email adress and password that belongs to the profile of your TomTom Mysports device. \n";
echo "<BR> You will be automatically transferred back to this page afterwards. \n";
echo "If you feel anything is wrong or missing from the table above you should check the config file of this application.";

?>




 </BODY>
</HTML>
  
