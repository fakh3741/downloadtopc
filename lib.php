<?php

function connect(&$db){
    $mycnf="/etc/capstone-mysql.conf";
    if (!file_exists($mycnf)) {
        echo "ERROR: DB Config file not found: $mycnf";
        exit;
    }
    	$mysql_ini_array=parse_ini_file($mycnf);
    	$db_host=$mysql_ini_array["host"];
    	$db_user=$mysql_ini_array["user"];
    	$db_pass=$mysql_ini_array["pass"];
    	$db_port=$mysql_ini_array["port"];
    	$db_name=$mysql_ini_array["dbName"];
	$db = mysqli_init();
        $db_sslkey='/etc/mysql-ssl/server-key.pem';
        $db_sslcert='/etc/mysql-ssl/server-cert.pem';
        mysqli_ssl_set($db, $db_sslkey, $db_sslcert, NULL, NULL, NULL);
        mysqli_real_connect($db, $db_host, $db_user, $db_pass, $db_name, $db_port);

    //$db = mysqli_connect($db_host, $db_user, $db_pass,$db_name);
	if (mysqli_connect_errno()) {
                print "Error connecting to DB: " . mysqli_connect_error();
                exit;
        }
    }

function isdigit($n) {
        if($n != null) {
                if(!is_numeric($n)) {
                        print "<b> ERROR: </b>
                        Invalid input.";
                        exit;
                }
        }
}

function maxattempt(){
	header("Location: index.php");
        exit;
}


function logout(){
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit;
}

function owner_authenticate($db, $postEmail, $postPass){

        if ($postEmail == Null || $postPass == Null) {
                logout();
        }
/*	
	$whitelist = array("172.20.0.1");
        $host_ip = $_SERVER['REMOTE_ADDR'];
        $failnumber = 0;
        if(!in_array($host_ip,$whitelist)){
                $query="SELECT count(*) from login WHERE ip=? AND action='fail' AND date >= DATE_SUB(now(),INTERVAL 1 HOUR) group by ip";
                if ($stmt = mysqli_prepare($db, $query)) {
                        mysqli_stmt_bind_param($stmt, "s", $host_ip);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_bind_result($stmt, $failnumber);
                        while(mysqli_stmt_fetch($stmt)) {
                                $failnumber=$failnumber;
                        }
	mysqli_stmt_close($stmt);

                }
                if($failnumber >= 5){
                        maxattempt();
                        exit;
                }
	}
*/

    #Select salt and password, see if we match
        $query="select ownerid, name, email, password, salt from owners where email=?";
        if ($stmt = mysqli_prepare($db, $query)) {
                mysqli_stmt_bind_param($stmt, "s", $postEmail);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $ownerid, $name, $email, $password, $salt);
                while(mysqli_stmt_fetch($stmt)) {
                                $ownerid=$ownerid;
                                $name=$name;
                                $email=$email;
                                $password=$password;
                                $salt=$salt;
                                
                }
                mysqli_stmt_close($stmt);
                $epass=hash('sha256', $postPass.$salt);
                if ($epass == $password) {
			session_regenerate_id();
                        $_SESSION['ownerid']=$ownerid;
                        $_SESSION['name']=$name;
                        $_SESSION['email']=$email;
                        $_SESSION['authenticated']="yes";
                        $_SESSION['ip']=$_SERVER['REMOTE_ADDR'];
			$_SESSION['user']="owner";
			$_SESSION['HTTP_USER_AGENT']=md5($_SERVER['HTTP_USER_AGENT']);
			$_SESSION['created']=time();
                        header("Location: profile.php");
                }
                else{

			echo "Failed to Login<br>";
                        header("Location: index.php");
                        exit;
                }
        }
}
function session_check(){
	if(isset($_SESSION['HTTP_USER_AGENT'])){
		if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT'])){
			logout();
		}
	}
	elseif(isset($_SESSION['ip'])) {
		if($_SESSION['ip'] != $SERVER['REMOTE_ADDR']) {
			logout();
		}
	}
	elseif($_SESSION['created']){
		if (time() - $_SESSION['created'] > 1800) {
			logout();
		}
	}
	elseif("POST" == $_SERVER["REQUEST_METHOD"]) {
		if (isset($_SESRVER["HTTP_ORIGIN"])) {
			if ($_SERVER["HTTP_ORIGIN"] != "https://172.20.74.19"){
				logout();
			}
		}
	}
	else {
		logout();
	}
}


function user_authenticate($db, $postEmail, $postPass){

        if ($postEmail == Null || $postPass == Null) {
                logout();
        }
/*
        $whitelist = array("172.20.0.1");
        $host_ip = $_SERVER['REMOTE_ADDR'];
        $failnumber = 0;
        if(!in_array($host_ip,$whitelist)){
                $query="SELECT count(*) from login WHERE ip=? AND action='fail' AND date >= DATE_SUB(now(),INTERVAL 1 HOUR) group by ip";
                if ($stmt = mysqli_prepare($db, $query)) {
                        mysqli_stmt_bind_param($stmt, "s", $host_ip);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_bind_result($stmt, $failnumber);
                        while(mysqli_stmt_fetch($stmt)) {
                                $failnumber=$failnumber;
                        }
        mysqli_stmt_close($stmt);

                }
                if($failnumber >= 5){
                        maxattempt();
                        exit;
                }
        }
*/
    #Select salt and password, see if we match
        $query="SELECT userid, ownerid, name, password, phone, tracking, heart, emergency, salt from users where email=?";
        if ($stmt = mysqli_prepare($db, $query)) {
                mysqli_stmt_bind_param($stmt, "s", $postEmail);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $userid, $owner, $name, $password, $phone, $tracking, $heart, $emergency, $salt);
                while(mysqli_stmt_fetch($stmt)) {
			$userid=$userid;
			$owner=$owner;
			$name=$name;
                	$password=$password;
			$phone=$phone;
			$tracking=$tracking;
			$heart=$heart;
			$emergency=$emergency;
                	$salt=$salt;               
                }
                mysqli_stmt_close($stmt);
                $epass=hash('sha256', $postPass.$salt);
            
                if ($epass == $password) {
			session_regenerate_id();
                        $_SESSION['ownerid']=$owner;
			$_SESSION['userid']=$userid;
                        $_SESSION['name']=$name;
                        $_SESSION['email']=$postEmail;
			$_SESSION['phone']=$phone;
			$_SESSION['tracking']=$tracking;
			$_SESSION['heart']=$heart;
			$_SESSION['emergency']=$emergency;
                        $_SESSION['authenticated']="yes";
                        $_SESSION['ip']=$_SERVER['REMOTE_ADDR'];
			$_SESSION['HTTP_USER_AGENT']=md5($_SERVER['HTTP_USER_AGENT']);
			$_SESSION['created']=time();
			$_SESSION['user']="user";
                        header("Location: main.php");
                }
                else{

                        echo "Failed to Login";
                        header("Location: index.php");
                        exit;
                }
        }
}


isset ($_REQUEST['s'])?$s=strip_tags($_REQUEST['s']):$s="";
isset ($_REQUEST['ownerid'])?$ownerid=strip_tags($_REQUEST['ownerid']):$ownerid="";
isset ($_REQUEST['userid'])?$userid=strip_tags($_REQUEST['userid']):$userid="";
isset ($_REQUEST['name'])?$name=strip_tags($_REQUEST['name']):$name="";
isset ($_REQUEST['useremail'])?$useremail=strip_tags($_REQUEST['useremail']):$useremail="";
isset ($_REQUEST['password'])?$password=strip_tags($_REQUEST['password']):$password="";
isset ($_REQUEST['mac'])?$mac=strip_tags($_REQUEST['mac']):$mac="";
isset ($_REQUEST['phone'])?$phone=strip_tags($_REQUEST['phone']):$phone="";
isset ($_REQUEST['tracking'])?$tracking=strip_tags($_REQUEST['tracking']):$tracking="";
isset ($_REQUEST['heart'])?$heart=strip_tags($_REQUEST['heart']):$heart="";
isset ($_REQUEST['emergency'])?$emergency=strip_tags($_REQUEST['emergency']):$emergency="";

isset ($_REQUEST['pwd'])?$pwd=strip_tags($_REQUEST['pwd']):$pwd="";
isset ($_REQUEST['email'])?$email=strip_tags($_REQUEST['email']):$email="";
isset ($_REQUEST['owneremail'])?$owneremail=strip_tags($_REQUEST['owneremail']):$owneremail="";
isset ($_REQUEST['usersnumber'])?$usersnumber=strip_tags($_REQUEST['usersnumber']):$usersnumber="";
isset ($_REQUEST['profilestatus'])?$profilestatus=strip_tags($_REQUEST['profilestatus']):$profilestatus="";

isset ($_REQUEST['maxrate'])?$maxrate=strip_tags($_REQUEST['maxrate']):$maxrate="";
isset ($_REQUEST['minrate'])?$minrate=strip_tags($_REQUEST['minrate']):$minrate="";
isset ($_REQUEST['emergencyalert'])?$emergencyalert=strip_tags($_REQUEST['emergencyalert']):$emergencyalert="";
isset ($_REQUEST['heartalert'])?$heartalert=strip_tags($_REQUEST['heartalert']):$heartalert="";
isset ($_REQUEST['lightalert'])?$lightalert=strip_tags($_REQUEST['lightalert']):$lightalert="";

?>
