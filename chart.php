<?php
session_start();
session_regenerate_id();
include_once('/var/www/html/capstone/lib.php');
connect($db);
/*
Include JpGraph in your script. Note that jpgraph.php should reside in a directory that's present in your PHP INCLUDE_PATH, otherwise specify the full path yourself.
*/ 
require_once('/var/www/html/capstone/jpgraph-3.5.0b1/src/jpgraph.php'); 
/*
Include the module for creating line graph plots.
*/ 
require_once('/var/www/html/capstone/jpgraph-3.5.0b1/src/jpgraph_line.php'); 
// Include the module for creating line graph plots.  

$xArray=array();
$yArray=array();

	$ownerid = $_SESSION['ownerid'];
    if($stmt=mysqli_prepare($db, "SELECT time, rate from heart WHERE ownerid = ? ORDER BY rateid desc LIMIT 15")) {
                mysqli_stmt_bind_param($stmt, "s" , $ownerid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $time, $rate);
                $i=0;
    while(mysqli_stmt_fetch($stmt)) {
        $time=htmlspecialchars($time);
        $rate=htmlspecialchars($rate);
        $yArray[]=intval($rate);
	$xArray[]=$i;
	$i=$i+1;


        }}
/*
Let's create a Graph instance and set some variables (width, height, cache filename, cache timeout). If the last argument "inline" is true the image is streamed back to the browser, otherwise it's only created in the cache.
*/ 
$graph = new Graph(600, 400, 'auto', 10, true); 

// Setting what axises to use
$graph->SetScale('textlin'); 

/*
Next, we need to create a LinePlot with some example parameters.
*/ 
$lineplot = new LinePlot($yArray, $xArray); 

// Setting the LinePlot color
$lineplot->SetColor('forestgreen'); 

// Adding LinePlot to graphic 
$graph->Add($lineplot); 

// Giving graphic a name
//$graph->title->Set('Simple graphic'); 

/*
If the graph is going to have labels with international characters, make sure to use a TrueType font that includes the required characters, e.g. Arial.
*/ 
$graph->title->SetFont(FF_ARIAL, FS_NORMAL); 
$graph->xaxis->title->SetFont(FF_ARIAL, FS_NORMAL); 
$graph->yaxis->title->SetFont(FF_ARIAL, FS_NORMAL); 

// Naming axises 
$graph->xaxis->title->Set('Time'); 
$graph->yaxis->title->Set('Heart rate'); 

// Coloring axises
$graph->xaxis->SetColor('#СС0000'); 
$graph->yaxis->SetColor('#СС0000'); 

// Setting the LinePlot width 
$lineplot->SetWeight(3); 

// To define a marker type, we denote dots as asterisks 
$lineplot->mark->SetType(MARK_FILLEDCIRCLE); 

// Showing value above each dot 
$lineplot->value->Show(); 

// Filling background with a gradient
$graph->SetBackgroundGradient('ivory', 'orange'); 

// Adding a shadow
$graph->SetShadow(4); 

/* 
Showing image in browser. If, when creating an graph object, the last parameter is false, the image would be saved in cache and not showed in browser.
*/  
  
$graph->Stroke(); 

?>
