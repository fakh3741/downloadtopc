<?php
include_once('/var/www/html/capstone/lib.php');
include_once('/var/www/html/capstone/postlib.php'); 
connect($db);

#post.php?mac=aaa&long=111&att=222&rate=86&emer=0
isset ($_REQUEST['mac'])?$mac=strip_tags($_REQUEST['mac']):$mac="";
isset ($_REQUEST['long'])?$long=strip_tags($_REQUEST['long']):$long="";
isset ($_REQUEST['att'])?$att=strip_tags($_REQUEST['att']):$att="";
isset ($_REQUEST['rate'])?$rate=strip_tags($_REQUEST['rate']):$rate="";
isset ($_REQUEST['emer'])?$emer=strip_tags($_REQUEST['emer']):$emer="";

$mac=htmlspecialchars($mac);

if($stmt=mysqli_prepare($db, "SELECT ownerid, name FROM owners WHERE device_mac =?")) {
    mysqli_stmt_bind_param($stmt, "s" , $mac);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $ownerid, $ownername);

    while(mysqli_stmt_fetch($stmt)) {
        $ownerid=htmlspecialchars($ownerid);
	$ownername = htmlspecialchars($ownername);
	}
}
else {
    echo "Error with database";}

//get the physical address
$address= getaddress($att,$long);
if($address)
{
    //echo $address;
}
else
{
    $address= "Not found";
}

$long=mysqli_real_escape_string($db, $long);
$att=mysqli_real_escape_string($db, $att);
$address=mysqli_real_escape_string($db, $address);
if($stmt=mysqli_prepare($db,"INSERT INTO location set locationid='', ownerid=?, longitude=?, attitude=?, time=now(), address=?")) {
		mysqli_stmt_bind_param($stmt,"ssss", $ownerid, $long, $att, $address);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);}

//collect info about the owner_profile
if($stmt=mysqli_prepare($db, "SELECT max_heart_rate, min_heart_rate, emergency_alert, heart_alert from owner_profile WHERE ownerid = ?")) {
                mysqli_stmt_bind_param($stmt, "s" , $ownerid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $max, $min, $emerg, $heart);
    
        while(mysqli_stmt_fetch($stmt)) {
        $max=htmlspecialchars($max);
        $min=htmlspecialchars($min);
        $emerg=htmlspecialchars($emerg);//name of the user
        $heart=htmlspecialchars($heart);//name of the user
        }
    }
	else { 
        echo"Can not connect to DB";}
//Check if the heart rate outside the threashold
if ($rate<$min || $rate>$max){
        //Collect the user information
       if($stmt=mysqli_prepare($db, "SELECT name, email, phone, heart from users WHERE name= ?")) {
                mysqli_stmt_bind_param($stmt, "s" , $heart);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $name, $useremail, $userphone, $heartNoti);
        while(mysqli_stmt_fetch($stmt)) {
        $name=htmlspecialchars($name);
        $useremail=htmlspecialchars($useremail);
        $userphone=htmlspecialchars($userphone);
        $heartNoti=htmlspecialchars($heartNoti);
        }
        }
        if($heartNoti == 'email'){
            $message = "The person you monitored Heart PROBLEM";
            send_email($useremail, $message,$ownername);
            //Save the event in owners event log
            if($stmt=mysqli_prepare($db,"INSERT INTO alerts set alertid=Null , ownerid=?, time=now(), type='heart',action='Email Sent'")) {
                mysqli_stmt_bind_param($stmt,"s", $ownerid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);}
        }
        elseif($heartNoti == 'phone'){
            $message = "The person you monitor has a heart problem. His current location is: $address";
            make_call($userphone, $message, $ownername);
            //Save the event in owners event log
            if($stmt=mysqli_prepare($db,"INSERT INTO alerts set alertid=Null , ownerid=?, time=now(), type='heart',action='Phone call initiated'")) {
                mysqli_stmt_bind_param($stmt,"s", $ownerid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);}
        }
}

//Add the rate to DB
$rate=mysqli_real_escape_string($db, $rate);

if($stmt=mysqli_prepare($db,"INSERT INTO heart set rateid='', ownerid=?, time=now(), rate=?")) {
		mysqli_stmt_bind_param($stmt,"ss", $ownerid, $rate);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);}

if($emer == '1'){
    echo"Emergency";
   //Collect the user information
       if($stmt=mysqli_prepare($db, "SELECT name, email, phone, emergency from users WHERE name= ?")) {
                mysqli_stmt_bind_param($stmt, "s" , $emerg);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $name, $useremail, $userphone, $emergNoti);
        while(mysqli_stmt_fetch($stmt)) {
        $name=htmlspecialchars($name);
        $useremail=htmlspecialchars($useremail);
        $userphone=htmlspecialchars($userphone);
        $emergNoti=htmlspecialchars($emergNoti);
        }
        }
	
        if($emergNoti == 'email'){
            $message = "The person you monitor need help. His address is $address";
            send_email($useremail, $message,$ownername);
            //Save the event in owners event log
            if($stmt=mysqli_prepare($db,"INSERT INTO alerts set alertid='' , ownerid=?, time=now(), type='emergency',action='Email Sent'")) {
                mysqli_stmt_bind_param($stmt,"s", $ownerid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);}
        }
        elseif($emergNoti == 'phone'){
		echo "Making call";
            $message = "The person you monitor has a hear problem. His address is $address";
            make_call($userphone, $message, $ownername);
            //Save the event in owners event log
            if($stmt=mysqli_prepare($db,"INSERT INTO alerts set alertid='' , ownerid=?, time=now(), type='emergency',action='Phone call initiated'")) {
                mysqli_stmt_bind_param($stmt,"s", $ownerid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);}
        }
}



?>
