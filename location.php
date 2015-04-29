<?php

session_start();
session_regenerate_id();
include_once('lib.php'); 
connect($db);
isdigit($s);
if($_SESSION['user']=="owner"){
	include_once('ownerheader.php');
}
if($_SESSION['user']=="user"){
	include_once('userheader.php');
}
switch($s){
case 0;
default:
    echo"<script type=\"text/javascript\" 
           src=\"http://maps.google.com/maps/api/js?sensor=false\"></script>";
    
    $ownerid = $_SESSION['ownerid'];
    $ownerid=htmlspecialchars($ownerid);
    if($stmt=mysqli_prepare($db, "SELECT time, longitude, attitude from location WHERE ownerid = ? ORDER BY locationid desc LIMIT 1")) {
                mysqli_stmt_bind_param($stmt, "s" , $ownerid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $time, $longitude, $attitude);
         while(mysqli_stmt_fetch($stmt)) {
        $time=htmlspecialchars($time);
        $longitude=htmlspecialchars($longitude);
        $attitude=htmlspecialchars($attitude);
	$long = floatval($longitude);
	$latt = floatval($attitude);
         }
    }

    echo"<article class=\"box post\">
        <header>
            <h2>Location Tracking</h2>
            <p>Last location at: $time </p>
        </header>
        <p> 
        <center>
        <div id=\"map\" style=\"width: 600px; height: 400px\"></div> </center>

   <script type=\"text/javascript\"> 
      var myOptions = {
         zoom: 15,
         center: new google.maps.LatLng($latt,$long),
         mapTypeId: google.maps.MapTypeId.ROADMAP
      }

      var map = new google.maps.Map(document.getElementById(\"map\"), myOptions);
   </script>
        
        </p>
        <header>
        <h2></h2>
            <p>Last 30 location coordinates:</p>
            </header>
            <p>
            <table style=\"width:100%\">
            <tr> <td> Time </td> <td> Longitude </td> <td> Latitude </td><td>Address</td> <td> Show it on map </td> </tr>";
    
    if($stmt=mysqli_prepare($db, "SELECT time, longitude, attitude, address from location WHERE ownerid = ? ORDER BY locationid desc LIMIT 30")) {
                mysqli_stmt_bind_param($stmt, "s" , $ownerid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $time, $longitude, $attitude, $address);
    
        while(mysqli_stmt_fetch($stmt)) {
        $time=htmlspecialchars($time);
        $longitude=htmlspecialchars($longitude);
        $attitude=htmlspecialchars($attitude);
	$address=htmlspecialchars($address);
        echo" <tr>
                <td> $time </td>
                <td> $longitude </td>
                <td> $attitude </td>
		<td> $address </td>
                <td> <a href=location.php?s=1&long=$lonitude&att=$attitude> Show the location on map </a> </td> </tr>";
            }
    }
	else { 
        echo"Can not connect to DB";}
          echo"  </table> </p>
        </article>";
    
    
    
    
	break;


case "1":
	isset ($_REQUEST['att'])?$att=strip_tags($_REQUEST['att']):$att="";
	isset ($_REQUEST['long'])?$long=strip_tags($_REQUEST['long']):$long="";
	$latt=floatval($att);
	$longt=floatval($long);
    echo"<article class=\"box post\">
        <header>
            <h2>Location Tracking</h2>
            <p>View a location on a map</p>
        </header>
        <p> 
        <center>
        <div id=\"map\" style=\"width: 800px; height: 600px\"></div> </center>

   <script type=\"text/javascript\"> 
      var myOptions = {
         zoom: 19,
         center: new google.maps.LatLng($latt, $longt),
         mapTypeId: google.maps.MapTypeId.ROADMAP
      }

      var map = new google.maps.Map(document.getElementById(\"map\"), myOptions);
   </script>
 		<a href=\"location.php\" class=\"button\">Back</a>       
        </p> 
	</article>";  
	break;


}

include_once('footer.php');
?>
