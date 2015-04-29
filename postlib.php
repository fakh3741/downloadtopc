<?php
include_once('twilio/Services/Twilio.php');

function getaddress($lat,$lng){
	$url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false';
	$json = @file_get_contents($url);
	$data=json_decode($json);
	$status = $data->status;
	if($status=="OK")
		return $data->results[0]->formatted_address;
	else
		return false;
}

function make_call($phone, $message, $name) {
        
    // Library version.
    $version = "2010-04-01";

    // Set your account ID and authentication token.
    $sid = "AC566cacd1ee62f160975e39094b2b0301";
    $token = "8543c3761d9eb3c488d14198b244fc58";

    // The number of the phone initiating the the call.
    $from_number = "7202077555";

    // The number of the phone receiving call.
    $to_number = "7202077555";

    // Use the Twilio-provided site for the TwiML response.
    $url = "http://twimlets.com/message";

    // Create the call client.
    $client = new Services_Twilio($sid, $token, $version);

    //Make the call.
    try
    {
        $call = $client->account->calls->create(
            $from_number, 
            $to_number,
            $url.'?Message='.urlencode($message)
        );
    }
    catch (Exception $e) 
    {
        echo 'Error: ' . $e->getMessage();
    }
    
}

function send_email($email, $message, $name) {
    $to = $email;
    $subject = "Important message from:".$name;
    $header = "From:capstone2015.team7@gmail.com \r\n";
    $retval = mail ($to,$subject,$message,$header);
    if( $retval == true )  
    {
       echo "Message sent successfully...";
    }
    else
    {
       echo "Message could not be sent...";
    }   

}

?>
