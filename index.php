<?php
session_start();
session_regenerate_id();
include_once('/var/www/html/capstone/lib.php'); 
include_once('header.php');
isdigit($s);

switch($s){
case 0;
default:
if($_SESSION['user']=="owner"){
        header("Location: profile.php");
}
if($_SESSION['user']=="user"){
        header("Location: main.php");
}
    echo"
    <section id=\"intro\" class=\"container\">
							<div class=\"row\">
								<div class=\"4u\">
									<section class=\"first\">
										<i class=\"icon featured fa-cog\"></i>
										<header>
											<h2>Register a new Device</h2>
										</header>
										<p>
                                        If you have a new device and this is the first time to use                                          it. Click below to register your device.<br><br>
                                        <a href=\"index.php?s=1\" class=\"button\">Register</a>
                                        </p>
									</section>
								</div>
								<div class=\"4u\">
									<section class=\"middle\">
										<i class=\"icon featured alt fa-flash\"></i>
										<header>
											<h2>Owner login</h2>
										</header>
										<p>
                                        <form method=post action=login.php?s=1>
    Email address: <input type=\"text\" id=\"owneremail\" name=\"owneremail\"> <br>
    Password: <input type=\"password\" id=\"pwd\" name=\"pwd\"> <br>
    <input type=\"submit\" value=\"Login\">
    </form>
                                        </p>
									</section>
								</div>
								<div class=\"4u\">
									<section class=\"last\">
										<i class=\"icon featured alt2 fa-star\"></i>
										<header>
											<h2>User login</h2>
										</header>
										<p>
                                        <form method=post action=login.php?s=2>
    Email address: <br> <input type=\"text\" id=\"useremail\" name=\"useremail\"> <br>
    Password: <br> <input type=\"password\" id=\"pwd\" name=\"pwd\"> <br>
    <input type=\"submit\" value=\"Login\">
    </form>
                                        </p>
									</section>
								</div>
							</div>";
    
	break;

//Add new owner
case "1":
    echo"<article class=\"box post\">
        <header>
            <h2>Registering New Device</h2>
            <p>Fill the below fields to add your navigation to the system</p>
        </header>
        <p> <table style=\"width:100%\">
			<form method=post action=add.php?s=1>
            <tr><td> Your name:</td><td>  <input type=\"text\" id=\"name\" size=\"50\" name=\"name\"> </td></tr>
            <tr><td> Email address: </td><td><input type=\"text\" id=\"owneremail\" size=\"50\" name=\"owneremail\"> </td></tr>
            <tr><td> Password: </td><td><input type=\"password\" id=\"password\" size=\"50\" name=\"password\"> </td></tr>
            <tr><td> Phone number:</td><td> <input type=\"text\" id=\"phone\" size=\"50\" name=\"phone\"> </td></tr>
            <tr><td> Device MAC:</td><td> <input type=\"text\" id=\"mac\" size=\"50\" name=\"mac\"> </td></tr>
            <tr><td></td><td><a href=\"index.php\" class=\"button\">Back</a><input type=\"submit\" value=\"Register\"></td></tr>
        </form>
        </table>					
        </p>						
        </article>";
    break;

// Add new users    
case "2":
    if ($_SESSION['user']=="owner") {
    echo " 
    <article class=\"box post\">
        <header>
            <h2>Adding new user</h2>
            <p>Fill the below fields to add a new user to moniter you.</p>
        </header>
        <p> 
            <table style=\"width:100%\">
	<form method=post action=add.php?s=2 id=\"newuser\">
    <tr><td> User name: </td> <td> <input type=\"text\" id=\"name\" size=\"50\" name=\"name\"> </td></tr>
    <tr><td> Email address: </td> <td> <input type=\"text\" id=\"useremail\" size=\"50\" name=\"useremail\"> </td></tr>
    <tr><td> Password </td> <td> <input type=\"password\" id=\"password\" size=\"50\" name=\"password\"> </td></tr>
    <tr><td> Phone number: </td> <td> <input type=\"text\" id=\"phone\" size=\"50\" name=\"phone\"> </td></tr>
    <tr><td> Location tracking: </td> <td> <select name=tracking form=\"newuser\"> 
                                            <option value=\"yes\">Yes</option>
                                            <option value=\"no\">No</option>
                                            </select></td></tr>
    <tr><td> Recive heart alerts: </td> <td> <select name=heart form=\"newuser\"> 
                                            <option value=\"none\">None</option>
                                            <option value=\"email\">Email</option>
                                            <option value=\"phone\">Phone</option>
                                            <option value=\"both\">Email&Phone</option>
                                            </select></td></tr>
    <tr><td> Recive emergency alerts: </td> <td> <select name=emergency form=\"newuser\"> 
                                            <option value=\"none\">None</option>
                                            <option value=\"email\">Email</option>
                                            <option value=\"phone\">Phone</option>
                                            <option value=\"both\">Email&Phone</option>
                                            </select></td></tr>
    <tr><td></td><td><a href=\"profile.php\" class=\"button\">Back</a>  <input type=\"submit\" value=\"Register\"></td></tr>
    </form>
    </table>
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
	
case "100":
	session_unset();
        session_destroy();
        header("Location: index.php");
        break;

}

include_once('footer.php');
?>
