<?php

session_start();
include_once('/var/www/html/capstone/lib.php'); 
connect($db);
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
	 if($stmt=mysqli_prepare($db, "SELECT max_heart_rate, min_heart_rate from owner_profile WHERE ownerid = ?")) {
                mysqli_stmt_bind_param($stmt, "s" , $ownerid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $max_rate, $min_rate);
    while(mysqli_stmt_fetch($stmt)) {
        $max_rate=htmlspecialchars($max_rate);
        $min_rate=htmlspecialchars($min_rate);

    }
    }	    
    if($stmt=mysqli_prepare($db, "SELECT time, rate from heart WHERE ownerid = ? ORDER BY rateid desc LIMIT 30")) {
                mysqli_stmt_bind_param($stmt, "s" , $ownerid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $time, $rate);
	echo"<article class=\"box post\">
        <header>
            <h2>Heart Rate Tracking</h2>
            <p>Recent heart rate chart</p>
        </header>
        <p>
		<img src=\"chart.php\">
   	
        
         </p>
        <header>
        <h2></h2>
            <p>Last 30 Heart Rate Log:</p>
            </header>
            <p>
            <table style=\"width:100%\">
            <tr> <td> Time </td> <td>Rate</td><td> Status</td> </tr>";
    while(mysqli_stmt_fetch($stmt)) {
        $time=htmlspecialchars($time);
        $rate=htmlspecialchars($rate);
        echo" <tr>
                <td> $time </td>
                <td> $rate </td>";
	if($rate > $max_rate){
		echo" <td> HIGH RATE </td>";}
	elseif($rate < $min_rate){
		echo"<td> LOW RATE </td>";}
	else{
		echo"<td> NORMAL </td>";}
 	echo"</tr>";
    }
	echo"  </table> </p>
        </article>";
    }
	else { 
        echo"Can not connect to DB";}
    
	break;

}

include_once('/var/www/html/capstone/footer.php');
?>
