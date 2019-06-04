<html lang="en">
 <head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">   
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>TomTom Mysportcloud data access</title>

    <link href="css/styles.css" rel="stylesheet">
  <STYLE>
   .value {
    font-size: 200%; font-weight: bold;
    }
   .machead {
    padding-top: 1px;
    text-align: center;
   } 

  </STYLE>
 </head>
<body>
<div class="background">
<img src="img/running.jpg">

</div>


<div class="outer">	
	<div class="content-outer">
            
<H2> TomTom Mysportcloud report </H2>
            
        <div class="content-main">


<?php 
error_reporting(E_ALL) ; 
ini_set('display_errors', 'On');

 include 'functions.php' ;
 $config = get_config_file ("./config.ini");
 //  echo '<pre>'; print_r($config); echo '</pre>';
 

// Create connection

// echo "<H1>" . $config["servername"] . "</H1>" ; 

   $conn = new mysqli($config["servername"] . ":" . $config["port"] , $config["username"], $config["password"], $config["dbname"]); 

 
   // Check connection 
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
       } 

//$sql = "select date , steps from tracking";
// SELECT week(date) as weeknr, sum(steps) as steps FROM `tracking` group by weeknr
//

// This query gives 2 rows, the last dates on which target was reached and not reached unless the last day is not yet 10.000 is not taken in account

$sql="SELECT (steps > 10000) as yes, MAX(date) as date
FROM `tracking` 
where date < (select max(date) from tracking) + ( select (steps>10000) from tracking order by date DESC limit 0,1 )
GROUP BY  yes";


$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
                       $date_target[] = new DateTime($row ["date"]) ;
    }
//    echo ("<PRE>");
//    echo ("</PRE>");

    $interval =   $date_target[0]->diff($date_target[1]); 
    if ($date_target[0] > $date_target[1]) 
     { 
        echo ('<P class="machead"> Number of days you missed your goal of 10.000 steps: </P>' . PHP_EOL );
         $numdays=0 ;}
     else {
        echo ('<P class="machead"> Consecutive days with more then 10.000 steps: </P>' . PHP_EOL );
      }  
        echo ('<DIV align="CENTER"><B class=value>' . $interval->days . "</B> </DIV>" . PHP_EOL );
}else {
    echo "0 results";
}

// this query will select the largest number of consecutive days that have steps > 10000 

/* 
$sql="select date, steps, (steps>10000) as stepstarget, @prev_stepstarget:=@cur_stepstarget as prev, @cur_stepstarget:=(steps>10000) as cur ,
case when @prev_stepstarget=@cur_stepstarget then @cnt
     else @cnt:=@cnt+1 end as teller
from tracking t,
(select @prev_stepstarget:=0, @cur_stepstarget:=0, @cnt:=0) r ";

$sql"select max(date) as date , sum(stepstarget), teller 
from
(select date, steps, (steps>10000) as stepstarget, @prev_stepstarget:=@cur_stepstarget as prev, @cur_stepstarget:=(steps>10000) as cur ,
case when @prev_stepstarget=@cur_stepstarget then @cnt
     else @cnt:=@cnt+1 end as teller
from tracking t,
(select @prev_stepstarget:=0, @cur_stepstarget:=0, @cnt:=0) r 
 ) p
 group by teller
 order by date";
*/

$sql="SELECT date(max(date)) as date , sum(stepstarget) as sumsteps, a_group 
from (select date, steps, (steps>10000) as stepstarget, @prev_stepstarget:=@cur_stepstarget as prev, @cur_stepstarget:=(steps>10000) as cur ,
        case when @prev_stepstarget=@cur_stepstarget then @cnt
             else @cnt:=@cnt+1 end as a_group
        from tracking t,
            (select @prev_stepstarget:=0, @cur_stepstarget:=0, @cnt:=0) r
     ) p
group by a_group
order by sumsteps desc
limit 0,1";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
                 //      $date_max = new DateTime($row ["date"]) ;
                       $date_max = $row ["date"] ;
                 
                       $num_days = $row["sumsteps"] ;
    }
        echo ('<P class="machead"> The higest number of consecutive days with more then 10.000 steps ever: </P>' . PHP_EOL );
        echo ('<DIV align="CENTER"><B class=value>' . $num_days. "</B><BR>" . PHP_EOL );
        echo ('reached on ' . $date_max . "</DIV>" .PHP_EOL );
}else {
    echo "0 results";
}







// moving average 
// using these charts: http://www.chartjs.org/ 

    

    $sql="SELECT 1000*UNIX_TIMESTAMP(t1.date) as date, t1.steps, 
    ( SELECT round(SUM(t2.steps) / COUNT(t2.steps))
      FROM tracking AS t2
      WHERE DATEDIFF(t1.date, t2.date) BETWEEN 0 AND 6
    ) AS 'stepsavg7'
      FROM tracking AS t1
      ORDER BY t1.date
      desc limit 60";



$result = $conn->query($sql);
  
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        //                 $toto[] = '{x:' . $row["date"] . ', y: ' . $row ["steps"] . "}"; 
                       $labelsr[] = $row ["date"] ;
                       $datar[]   =$row["steps"] ;
                       $stepsavg7[]=$row["stepsavg7"];
                       
      //                     $toto[] = '{x:' . $row["id"] . ', y: ' . $row ["steps"] . "}"; 
                      // echo "{x:" . $row["date"] . ', y: ' . $row ["steps"] . "},";
                     }
                     $labels= implode (' , ',$labelsr);
                     $data = implode(' , ', $datar);
                     $tempavg=$stepsavg7[0] . str_repeat(" , null",59) ;
                     $stepsavg7[0] = null;
                     $stepsavg=implode(' , ', $stepsavg7);
                      
                   //  echo "BLEB <P>" . $tempavg ;
                     

    echo '<P class="machead"> Moving average</P>' . PHP_EOL ;
    echo '<canvas id="myChart"></canvas>' . PHP_EOL ;
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>' . PHP_EOL ;
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>' . PHP_EOL ;
    

echo '<SCRIPT>
    var ctx = document.getElementById("myChart").getContext("2d");
    var chart = new Chart(ctx, {
        // The type of chart we want to create
 //       type: "line",
        type: "scatter",
        
        // The data for our dataset
        data: {
            labels:[' . $labels . '],' . PHP_EOL ;
        echo    'datasets: [{
                label: "7 days steps moving average",
                fill: false,
                borderColor: "rgb(99, 128, 255)",
                backgroundColor:  "rgb(99, 128, 255)",
                data: [ ' ; 
                echo $stepsavg; 
            echo  ' ]
            },{
                label: "Daily steps",
                fill: false,
                borderColor: "rgb(255, 99, 132)",
                backgroundColor:  "rgb(255, 99, 132)",
                data: [ ' ; 
                echo $data; 
            echo  ' ]

            },{
                label: "Temp average",
                fill: false,
                borderColor: "rgb(22, 255, 132)",
                backgroundColor:  "rgb(22, 255, 132)",
                data: [ ' ; 
                echo $tempavg; 
            echo  ' ]

            }
            ]
        },
    
        // Configuration options go here
        options: {
            scales: {
                xAxes: [{
                  type: "time",
                  time: {
                    unit: "week",
                    tooltipFormat: "ddd D MMM Y",
                  }
                }]
                ,yAxes: [{
                    ticks: {
                        min: 0
                         }
                }]
              }
           
        }
    });
 </SCRIPT>';

  } else {
      echo "0 results";
  }


   $conn->close();

   ?>
   
  </DIV> 
  </DIV> 
  </DIV> 
</BODY>
</HTML>
