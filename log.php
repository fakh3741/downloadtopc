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
    
    $ownerid = $_SESSION['ownerid'];
    $ownerid=htmlspecialchars($ownerid);
    if($stmt=mysqli_prepare($db, "SELECT count(*) as number FROM alerts WHERE ownerid = ? AND type='heart'")) {
                mysqli_stmt_bind_param($stmt, "s" , $ownerid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $heartalert);
         while(mysqli_stmt_fetch($stmt)) {
        	$heartalert=htmlspecialchars($heartalert);
         }
    }

	if($stmt=mysqli_prepare($db, "SELECT count(*) as number FROM alerts WHERE ownerid = ? AND type='emergency'")) {
                mysqli_stmt_bind_param($stmt, "s" , $ownerid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $emergencyalert);
         while(mysqli_stmt_fetch($stmt)) {
                $emergencyalert=htmlspecialchars($emergencyalert);
         }
    }

    echo"<article class=\"box post\">
        <header>
            <h2>Event log</h2>
            <p>Events summary:</p>
        </header>
        <p> 
        <center>
		 <img src=\"img/alert.png\" alt=\"\" height=\"30\" width=\"30\"> You have: $heartalert heart rate alerts.&nbsp&nbsp &nbsp&nbsp&nbsp 
		 <img src=\"img/alert.png\" alt=\"\" height=\"30\" width=\"30\"> You have: $emergencyalert emergency alerts.
        </p>
        <header>
        <h2></h2>
            <p>Last events:</p>
            </header>
            <p>
            <table style=\"width:100%\">
            <tr> <td> Time </td> <td> Event type </td> <td> Action </td> </tr>";
    
    if($stmt=mysqli_prepare($db, "SELECT time, type, action from alerts WHERE ownerid = ? ORDER BY alertid desc LIMIT 30")) {
                mysqli_stmt_bind_param($stmt, "s" , $ownerid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $time, $eventtype, $action);
    
        while(mysqli_stmt_fetch($stmt)) {
        $time=htmlspecialchars($time);
        $eventtype=htmlspecialchars($eventtype);
        $action=htmlspecialchars($action);
        echo" <tr>
                <td> $time </td>
                <td> $eventtype </td>
                <td> $action </td>
              </tr>";
            }
    }
	else { 
        echo"Can not connect to DB";}
          echo"  </table> </p>
        </article>";
    
    
    
    
	break;

}

include_once('footer.php');
?>
