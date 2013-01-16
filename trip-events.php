<?

require_once("public_html/swrm/connection.php");
require_once("public_html/swrm/error.php");
require_once("public_html/swrm/user-functions.php");
require_once("public_html/swrm/event-functions.php");

function send_email($email,$name,$event_info,$subject,$body) {
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= "From: support@harkath.com";
	if (mail($email, $subject, $body, $headers)) {
		echo "Message successfully sent to $email!\r\n";
	} else {
		echo "Message delivery to $email failed.\r\n";
	}
}
function send_success_email($email,$name,$event_info) {
	$subject = "$event_info[Name] is happening!";
	
	$timestamp = strtotime($event_info['EventDate']);
	$evdate = date("F jS \a\\t g:i A", $timestamp);
	
	$body = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional = //EN\">
	<html>
	<body>
	Hello $name!<br /><br />The event <a href=\"http://harkath.com/swrm/event-page.php?id=$event_info[EID]\">$event_info[Name]</a> has reached critical mass and will take place on $evdate.<br /><br />
	Thanks,<br />
	The SWRM Team
	</body>
	</html>";
	send_email($email,$name,$event_info,$subject,$body);
}
function send_creator_fail_email($email,$name,$event_info) {
	$subject = "$event_info[Name] didn't reach critical mass";
	$body = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional = //EN\">
	<html>
	<body>
	Hello $name,<br /><br />Your event <a href=\"http://harkath.com/swrm/event-page.php?id=$event_info[EID]\">$event_info[Name]</a> unfortunately failed to reach critical mass and has been cancelled. Better luck next time!<br /><br />
	Thanks,<br />
	The SWRM Team
	</body>
	</html>";
	send_email($email,$name,$event_info,$subject,$body);
}
function send_creator_success_email($email,$name,$event_info) {
	$subject = "$event_info[Name] is happening!";
	
	$timestamp = strtotime($event_info['EventDate']);
	$evdate = date("F jS \a\\t g:i A", $timestamp);
	
	$body = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional = //EN\">
	<html>
	<body>
	Hello $name!<br /><br />Your event <a href=\"http://harkath.com/swrm/event-page.php?id=$event_info[EID]\">$event_info[Name]</a> has reached critical mass and will take place on $evdate. Happy hosting!<br /><br />
	Thanks,<br />
	The SWRM Team
	</body>
	</html>";
	send_email($email,$name,$event_info,$subject,$body);
}

$con = connectToHost();
$db = selectDB($con);



$query = "SELECT * FROM Events WHERE DueDate < NOW() AND Activated IS NULL";
$result = mysql_query($query);

while($row = mysql_fetch_array($result)) {
	// Check the trigger number has been hit
	$query = "SELECT COUNT(*) AS AnonAttendeeCount FROM Events INNER JOIN AnonAttendees ON Events.EID=AnonAttendees.EventID WHERE Events.EID=$row[EID]";
	$count_result = mysql_query($query);
	$cr = mysql_fetch_array($count_result);
	$count = $cr['AnonAttendeeCount'];
	$query = "SELECT COUNT(*) AS AttendeeCount FROM Events INNER JOIN Attendees ON Events.EID=Attendees.EventID WHERE Events.EID=$row[EID]";
	$count_result = mysql_query($query);
	$cr = mysql_fetch_array($count_result);
	$count += $cr['AttendeeCount'];
	
	// Figure out whether creator was anonymous or registered; used later
	if($row['Creator'] == NULL) {
		$creator = -1;
		echo "Creator was NULL.\r\n";
	} else {
		$creator = $row['Creator'];
	}
	
	if($count < $row['TriggerNumber']) {
		// Event didn't hit critical mass
		echo "Event $row[EID] needed $row[TriggerNumber] but only has $count. Notifying creator.\r\n";
		
		// Notify the creator
		if($creator != -1) {
			$creator_info = getUserInfo($row['Creator']);
			send_creator_fail_email($creator_info['Email'],$creator_info['DisplayName'],$row);
		} else {
			send_creator_fail_email($row['ContactInfo'],"there",$row);
		}
		
		mysql_query("UPDATE Events SET Activated=NOW() WHERE EID=$row[EID]");
	} else {
		// Event succeeded
		echo "Event $row[EID] needed $row[TriggerNumber] and had $count. Sending notifications.\r\n";
		
		// Regular attendees
		$query = "SELECT * FROM Attendees INNER JOIN Users ON Attendees.User=Users.UID WHERE EventID=$row[EID] AND UID<>$creator";
		$attendees = mysql_query($query);
		while($a = mysql_fetch_array($attendees)) {
			send_success_email($a['Email'],$a['DisplayName'],$row);
		}
		// Anonymous attendees
		$query = "SELECT * FROM AnonAttendees WHERE EventID=$row[EID] AND ContactInfo<>'$row[ContactInfo]'";
		$attendees = mysql_query($query);
		while($a = mysql_fetch_array($attendees)) {
			send_success_email($a['ContactInfo'],"there",$row);
		}
		// Notify the creator
		if($creator != -1) {
			$creator_info = getUserInfo($row['Creator']);
			send_creator_success_email($creator_info['Email'],$creator_info['DisplayName'],$row);
		} else {
			send_creator_success_email($row['ContactInfo'],"there",$row);
		}
		
		mysql_query("UPDATE Events SET Activated=NOW() WHERE EID=$row[EID]");
	}
}


mysql_close($con);

?>
