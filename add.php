<?php
session_start();
session_regenerate_id();
include_once('lib.php'); 
include_once('header.php');
connect($db);

switch($s){
case 0;
default:
        echo" 
    <article class=\"box post\">
        <header>
            <h2>Invaled page!!</h2>
            <p></p>
        </header>
        <p> 
        <center>
        You are not authorized to access this page. <br>
        <a href=\"index.php\" class=\"button\">Home page</a>
        </center>        
        </p>
        </article> ";
	break;
    
//Adding new owner    
case "1":
	$name=mysqli_real_escape_string($db , $name);
	$owneremail=mysqli_real_escape_string($db, $owneremail);
	$password=mysqli_real_escape_string($db, $password);
    	$phone=mysqli_real_escape_string($db, $phone);
    	$mac=mysqli_real_escape_string($db, $mac);
        $salt=substr(str_shuffle("uj658986jgjfu5"), 0 , 10);
    	$salthash = hash('sha256', $salt);
    	$hashpwd=hash('sha256', $password.$salthash);
	if($stmt=mysqli_prepare($db,"INSERT INTO owners set ownerid='', name=?, email=?, password=?, device_mac=?, phone=?, salt=?")) {
		mysqli_stmt_bind_param($stmt,"ssssss", $name, $owneremail, $hashpwd, $mac, $phone, $salthash);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
    }

	else{ 
        echo "Adding new owner - Error with Query";
        break;
        }

	echo "
    
    <article class=\"box post\">
        <header>
            <h2>Welcome $name</h2>
            <p>The registeration process went fine <br>
            You can now start using the system.</p>
        </header>
        <p> 
        <center>
        You have to complete the following:<br>
        1- Setup your profile.<br>
        2- Add new users to monitar you. <br><br><br>
        <a href=\"login.php?s=1&owneremail=$owneremail&pwd=$password\" class=\"button\">Start using the application</a>
        </center>        
        </p>
      
        </article>";
	break;

//Adding new user
case "2":
    //check if the user is an owner
    if ($_SESSION['user']=="owner") {
        $ownerid= $_SESSION['ownerid'];
        $ownerid=mysqli_real_escape_string($db , $ownerid);
	$name=mysqli_real_escape_string($db , $name);
	$useremail=mysqli_real_escape_string($db, $useremail);
        $password=mysqli_real_escape_string($db, $password);
        $phone=mysqli_real_escape_string($db, $phone);
        $tracking=mysqli_real_escape_string($db, $tracking);
        $heart=mysqli_real_escape_string($db, $heart);
        $emergency=mysqli_real_escape_string($db, $emergency);
        $salt=substr(str_shuffle("uhghgjriedkdjfnghjgjj658958686jfu5"), 0 , 10);
    	$salthash = hash('sha256', $salt);
    	$hashpwd=hash('sha256', $password.$salthash);
        
	   if($stmt=mysqli_prepare($db,"INSERT INTO users set userid='', ownerid=?, name=?, email=?, password=?, salt=?, phone=?, tracking=?, heart=?, emergency=?")) {
		mysqli_stmt_bind_param($stmt,"sssssssss", $ownerid, $name, $useremail, $password, $salthash, $phone, $tracking, $heart, $emergency);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
       }

    if ($stmt = mysqli_prepare($db, "SELECT userid from users where name=? and email=? order by userid desc limit 1")) {
	mysqli_stmt_bind_param($stmt, "ss" , $name , $usremail);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt,$ownerid);
	while(mysqli_stmt_fetch($stmt)){
		$userid=$userid;
	}
	mysqli_stmt_close($stmt); }
    
    // Send an email to the user inform him that he was added to the system.
    //notify($userid);
    
	echo" 
    <article class=\"box post\">
        <header>
            <h2></h2>
            <p>Adding users</p>
        </header>
        <p> 
        <center>
        The user $name has been added to your profile. <br>
        An email was sent to the email address provided.
        <br><br>
        <a href=\"index.php?s=2\" class=\"button\">Add a nother user</a>
        <a href=\"profile.php\" class=\"button\">Main page</a>
        </center>        
        </p>
        </article> ";
    }
    else{
        echo" 
    <article class=\"box post\">
        <header>
            <h2>Invaled page!!</h2>
            <p></p>
        </header>
        <p> 
        <center>
        You are not authorized to access this page. <br>
        <a href=\"index.php\" class=\"button\">Home page</a>
        </center>        
        </p>
        </article> ";
    }
	break;

}

include_once('footer.php');
?>
