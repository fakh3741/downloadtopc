<?php
session_start();
include_once('/var/www/html/capstone/lib.php');
connect($db);
require_once('/var/www/html/capstone/jpgraph-3.5.0b1/src/jpgraph.php');
/*
Include the module for creating line graph plots.
*/
require_once('/var/www/html/capstone/jpgraph-3.5.0b1/src/jpgraph_line.php');

$xArray=array();
$yArray=array();

$ydata = array(87,95,95,95,97,99,110,110);

/*
We're not going to set the values for the X axis.
*/
$xdata = array(0, 1, 2, 3, 4, 5, 6, 7);
$ownerid = 4;
    if($stmt=mysqli_prepare($db, "SELECT time, rate from heart WHERE ownerid = ? LIMIT 100")) {
                mysqli_stmt_bind_param($stmt, "s" , $ownerid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $time, $rate);
		$i=0;
    while(mysqli_stmt_fetch($stmt)) {
        $time=htmlspecialchars($time);
        $rate=htmlspecialchars($rate);
        $yArray[]=$time;
	$xArray[]=$rate;


        }}

print_r($yArray);
print_r($xArray);
print_r($ydata);
$graph = new Graph(400, 300, 'auto', 10, true);

// Setting what axises to use
$graph->SetScale('textlin');

/*
Next, we need to create a LinePlot with some example parameters.
*/
$lineplot = new LinePlot($ydata, $xdata);

// Setting the LinePlot color
$lineplot->SetColor('forestgreen');

// Adding LinePlot to graphic 
$graph->Add($lineplot);

// Giving graphic a name
$graph->title->Set('Simple graphic');

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
