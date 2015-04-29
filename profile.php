<?php

session_start();
session_regenerate_id();
include_once('lib.php'); 
include_once('ownerheader.php');
connect($db);
session_check();


switch($s){
    //main page for the owners
case 0;
default:
	$ownername= $_SESSION['name'];
	$ownerid=$_SESSION['ownerid'];
	$query="SELECT count(*) FROM users";
        $result=mysqli_query($db, $query);
        echo mysql_result($result,0);
	//check for number of users
	if($stmt=mysqli_prepare($db, "SELECT count(*) as number FROM users WHERE ownerid = ?")) {
                mysqli_stmt_bind_param($stmt, "s" , $ownerid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $usersnumber);
         while(mysqli_stmt_fetch($stmt)) {
        	$usersnumber=htmlspecialchars($usersnumber);
         }
    	}

	//check if the profile is setup
	if($stmt=mysqli_prepare($db, "SELECT count(*) as status FROM owner_profile WHERE ownerid = ?")) {
                mysqli_stmt_bind_param($stmt, "s" , $ownerid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $profilestatus);
         while(mysqli_stmt_fetch($stmt)) {
                $profilestatus=htmlspecialchars($profilestatus);
         }
        }

    echo"
	<article class=\"box post\">
        <header>
            <h2>Owners Main Page</h2>
            <p>Welecome $ownername </p>
        </header>
        <p> ";
	if($usersnumber =="0"){
                echo"<img src=\"img/nouser.png\" alt=\"nouser\" height=\"35\" width=\"35\"> You do not have any user to monitor you. <a href=\"index.php?s=2\">Please add users.</a>";}
        else{
                echo"<img src=\"img/nouser.png\" alt=\"user\" height=\"35\" width=\"35\"> You have $usersnumber  user to monitor you.";}

        if($profilestatus =="0"){
                echo" <br> <img src=\"img/alert.png\" alt=\"\" height=\"30\" width=\"30\"> Your profile is not ready. <a href=\"profile.php?s=1\">Please setup your profile.</a>";
	}
	echo" <table style=\"width:100%\">
    	<tr>
                <td><center> <img src=\"img/profile.png\" alt=\"profile\" height=\"85\" width=\"85\"> <br><a href=\"profile.php?s=3\"> Manage your profile</a> </center></td>
                <td> <center><img src=\"img/adduser.png\" alt=\"adduser\" height=\"85\" width=\"85\"> <br> <a href=\"index.php?s=2\"> Add users</a> </center></td>
                <td> <center><img src=\"img/location.png\" alt=\"location\" height=\"85\" width=\"85\"> <br><a href=\"location.php\">Location tracking</a></center></td>
            </tr>
            <tr>
                <td> <center><img src=\"img/heart.png\" alt=\"heart\" height=\"85\" width=\"85\"> <br> <a href=\"heart.php\">Heart pulse monitaring</a></center></td>
                <td> <center><img src=\"img/log.png\" alt=\"log\" height=\"85\" width=\"85\"> <br><a href=\"log.php\"> Event log</a></center></td>
        </table>
        
        </p>						
        </article>";
    
	break;

//Edit the profile
case "1":
	$ownerid=$_SESSION['ownerid'];
	 echo"<article class=\"box post\">
        <header>
            <h2>Editing the owner profile</h2>
            <p></p>
        </header>
        <p>
	 <table style=\"width:100%\">
        	<form method=post action=profile.php?s=2 id=\"editprofile\">
            <tr><td> Max heart rate:</td><td><input type=\"text\" id=\"maxrate\" size=\"50\" name=\"maxrate\"></td></tr>
            <tr><td> Min heart rate:</td><td><input type=\"text\" id=\"minrate\" size=\"50\" name=\"minrate\"></td></tr>
            <tr><td> Emergency alert:</td><td>
                                            <select name=emergencyalert form=\"editprofile\"> 
                                            <option value=\"0\">None</option>
                                            <option value=\"911\">Emergency (911) </option>";
                                            $query = "SELECT userid, name from users where ownerid=$ownerid";
                                            $result=mysqli_query($db, $query); 
                                            while($row=mysqli_fetch_row($result)) {
                                            echo "<option value=$row[1]>$row[1]</option>";
                                            }
                                            echo"</select>
            </td></tr>
            <tr><td> Heart rate alert:</td><td>
                                            <select name=heartalert form=\"editprofile\"> 
                                            <option value=\"0\">None</option>
                                            <option value=\"911\">Emergency (911) </option>";
                                            $query = "SELECT userid, name from users where ownerid=$ownerid";
                                            $result=mysqli_query($db, $query); 
                                            while($row=mysqli_fetch_row($result)) {
                                            echo "<option value=$row[1]>$row[1]</option>";
                                            }
                                            echo"</select>
            </td></tr>
            <tr><td></td><td><a href=\"profile.php?s=3\" class=\"button\">Back</a><input type=\"submit\" value=\"Update\"></td></tr>
            </form>
	</table>                                        
        </p>                                            
        </article>";
	break;

//Save the profile
case "2":
    	$ownerid=$_SESSION['ownerid'];
	$maxrate=mysqli_real_escape_string($db , $maxrate);
	$minrate=mysqli_real_escape_string($db , $minrate);
	//$emergencyalert=mysqli_real_escape_string($db, $emergencyalert);
    	//$heartalert=mysqli_real_escape_string($db, $heartalert);
	if($stmt=mysqli_prepare($db,"INSERT INTO owner_profile set profileid='', ownerid=?, max_heart_rate=?, min_heart_rate=?, emergency_alert=?, heart_alert=?,light_alert=''")) {
		mysqli_stmt_bind_param($stmt,"sssss", $ownerid, $maxrate, $minrate, $emergencyalert, $heartalert);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
    }

	echo" <br><br> The owner profile has been modefied. <br><br>
       <a href=\"profile.php\" class=\"button\"> Continue to the owner main page </a><br><br>
	<a href=\"profile.php?s=3\" class=\"button\"> Back to the profile </a> ";
	break;
    
//Display the pofile
case "3":
	if($_SESSION['user']=="owner"){	
		$ownername= $_SESSION['name'];
		$ownerid = $_SESSION['ownerid'];
    		$ownerid=htmlspecialchars($ownerid);
    		if($stmt=mysqli_prepare($db, "SELECT profileid, max_heart_rate, min_heart_rate, emergency_alert, heart_alert from owner_profile WHERE ownerid=?")) {
                mysqli_stmt_bind_param($stmt, "s" , $ownerid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $profileid, $max_heart, $min_heart, $emergency_alert, $heart_alert);
        	 while(mysqli_stmt_fetch($stmt)) {
        		$profileid=htmlspecialchars($profileid);
        		$max_heart=htmlspecialchars($max_heart);
	        	$min_heart=htmlspecialchars($min_heart);
			$emergency_alert=htmlspecialchars($emergency_alert);
			$heart_alert=htmlspecialchars($heart_alert);
         	}
    	}
	echo"<article class=\"box post\">
        <header>
            <h2>Owner Profile</h2>
            <p>Profile of: $ownername </p>
        </header>
        <p> 
        <table style=\"width:100%\">
            <tr>
                <td></td>
		<td></td>
                <td> <center><img src=\"img/EditUser.png\" alt=\"edit\" height=\"40\" width=\"40\"> <br> <a href=\"profile.php?s=1\"> Edit the profile</a> </center></td>
            </tr>
	<tr> 
               <td> Heart rate threshold</td>
                <td></td>
		<td></td>
        </tr>
            <tr>
		<td></td>
                <td> Max heart rate:</td>
                <td> $max_heart </td>
	</tr>
	<tr>      
	        <td></td>
		<td> Min heart rate:</td>
                <td> $min_heart </td>
        </tr>
	<tr>        
	        <td> In case of the following event, notify the selected user:</td>
                <td> </td>
		<td></td>
        </tr>		

	<tr> 
		<td></td>
                <td> Press the Emergencu button:</td>
                <td> $emergency_alert</td>
        </tr>
	<tr> 
		<td></td>
               	<td> Heart rate exceed threshold:</td>
                <td> $heart_alert</td>
        </tr>
        </table>
        
        </p>                                            
        </article>";}	
	break;

}

include_once('footer.php');
?>
