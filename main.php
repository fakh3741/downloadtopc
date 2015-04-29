<?php

session_start();
session_regenerate_id();
include_once('lib.php'); 
include_once('userheader.php');
connect($db);


switch($s){
    //main page for users
case 0;
default:
    $name= $_SESSION['name'];
    echo"<article class=\"box post\">
        <header>
            <h2>Users Main Page</h2>
            <p>Welecome: &nbsp  $name </p>
        </header>
        <p> 
        <table style=\"width:100%\">
            <tr>
		<td> <center><img src=\"img/profile.png\" alt=\"profile\" height=\"120\" width=\"120\"> <br> <a href=\"main.php?s=1\">Update profile</a></center></td>
                <td> <center><img src=\"img/location.png\" alt=\"location\" height=\"120\" width=\"120\"> <br><a href=\"location.php\">Location tracking</a></center></td>
                 <td> <center><img src=\"img/heart.png\" alt=\"heart\" height=\"120\" width=\"120\"> <br> <a href=\"heart.php\">Heart pulse monitaring</a></center></td>
                
            </tr>
        </table>  
        </p>						
        </article>";
    
	break;

case 1:
	  echo"<article class=\"box post\">
        <header>
            <h2>Editing the owner profile</h2>
            <p></p>
        </header>
        <p> <table style=\"width:100%\">
            <form method=post action=main.php?s=2>
            <tr><td> Your new password:</td><td>  <input type=\"password\" id=\"pwd\" size=\"50\" name=\"pwd\"> </td></tr>
            <tr><td> Email address:</td><td><input type=\"text\" id=\"useremail\" size=\"50\" name=\"useremail\"> </td></tr>
            <tr><td> Phone number: </td><td><input type=\"text\" id=\"phone\" size=\"50\" name=\"phone\"> </td></tr>
	    <tr><td></td><td><a href=\"main.php\" class=\"button\">Back</a><input type=\"submit\" value=\"Update\"></td></tr>
        </form>
        </table>                                        
        </p>                                            
        </article>";	

	break;    
case 2:
	if ($_SESSION['user']=="user") {
        $userid= $_SESSION['userid'];
        $useremail=mysqli_real_escape_string($db, $useremail);
        $pwd=mysqli_real_escape_string($db, $pwd);
        $phone=mysqli_real_escape_string($db, $phone);
	$salt=substr(str_shuffle("uhghgjried58686jfu5"), 0 , 10);
        $salthash = hash('sha256', $salt);
        $hashpwd=hash('sha256', $pwd.$salthash);
           if($stmt=mysqli_prepare($db,"UPDATE users set email=?, password=?, phone=?,salt=? WHERE userid=?")) {
                mysqli_stmt_bind_param($stmt,"sssss", $useremail, $hashpwd, $phone,$salthash, $userid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
       }
	echo" 
    <article class=\"box post\">
        <header>
            <h2></h2>
            <p>Users profile</p>
        </header>
        <p> 
        <center>
        Your profile has been updated.
        <br><br>
        <a href=\"main.php\" class=\"button\">Main page</a>
        </center>        
        </p>
        </article> ";
	}

	break;
}

include_once('footer.php');
?>
