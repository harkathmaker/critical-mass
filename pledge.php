<?
include("header.php");
print_header("Pledging to Event...");

include("nav.php");

$con = connectToHost();
$db = selectDB($con);

$event_info = getEventInfo($_POST['eid']);

$result = mysql_query("SELECT COUNT(*) AS Num FROM Attendees WHERE EventID=$event_info[EID]");
$num = mysql_fetch_array($result);
$result = mysql_query("SELECT COUNT(*) AS Num FROM AnonAttendees WHERE EventID=$event_info[EID]");
$anonNum = mysql_fetch_array($result);
$total_count = $num['Num']+$anonNum['Num'];

if($event_info['MaxNumber'] != NULL and $total_count >= $event_info['MaxNumber']) {
	echo "<h3>Max Guest Amount Reached</h3>
	<p>Sorry, this event already has the maximum allowed number of pledges!</p>";
} else if(isset($_SESSION['user'])) {
	// Subscribe the registered user to the event
	
	$query = sprintf("SELECT * FROM Attendees WHERE User=%s AND EventID=%s","$_SESSION[user]","$_POST[eid]");
	$request = mysql_query($query);

	if(mysql_num_rows($request) > 0) {
		echo "You are already pledged to this event.";
	} else {
		// Register with the current date
		$query = sprintf("INSERT INTO Attendees (EventID,User,JoinDate) VALUES (%s,%s,'%s')","$_POST[eid]","$_SESSION[user]",date("c"));
		$request = mysql_query($query);
		
		if($request) {
			echo "<p>Pledged successfully!</p>";
		} else {
			die("Error pledging: ".mysql_error());
		}
		
		// Remove anonymous duplicate
		$user_info = getUserInfo($_SESSION['user']);
		$query = sprintf("DELETE FROM AnonAttendees WHERE EventID=%s AND ContactMethod='%s' AND ContactInfo='%s'","$_POST[eid]","EMAIL",$user_info['Email']);
		mysql_query($query);
		
		$subject = "You've pledged to join $event_info[Name]";
		$body = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional = //EN\">
		<html>
		<body>
		Hello $user_info[DisplayName]!<br /><br />You have pledged to the event <a href=\"harkath.com/swrm/event-page.php?id=$_POST[eid]\">$event_info[Name]</a>. You will be notified again when the event has received enough pledges to take place.<br /><br />
		Thanks,<br />
		The SWRM Team
		</body>
		</html>";
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "From: support@harkath.com";
		if (mail($user_info['Email'], $subject, $body, $headers)) {
			echo "<p>Message successfully sent to $user_info[Email]!</p>";
		} else {
			echo "<p>Message delivery to $user_info[Email] failed.</p>";
		}
	}
} else if(isset($_POST['email'])) {
	$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

	if(!$email) {
		die('Invalid email.');
	}
	
	// Subscribe anonymously
	$can_submit = true;
	
	$query = sprintf("SELECT * FROM Attendees INNER JOIN Users ON Users.UID=Attendees.User WHERE Email='%s' AND EventID=%s",$email,$_POST['eid']);
	$request = mysql_query($query);
	if(mysql_num_rows($request) > 0) {
		echo "<p>A registered user with this email has already pledged to the event.</p>";
		$can_submit = false;
	}
	$query = sprintf("SELECT * FROM AnonAttendees WHERE EventID=%s AND ContactMethod='%s' AND ContactInfo='%s'",$_POST['eid'],"EMAIL",$email);
	$request = mysql_query($query);
	if(mysql_num_rows($request) > 0) {
		echo "<p>Someone has already pledged to the event using this email.</p>";
		$can_submit = false;
	}
	
	if($can_submit) {
		echo "Anonymous email: $email";
		
		$query = sprintf("INSERT INTO AnonAttendees (EventID,ContactMethod,ContactInfo,JoinDate) VALUES (%s,'%s','%s','%s')","$_POST[eid]","EMAIL",$email,date("c"));
		$request = mysql_query($query);
		if($request) {
			echo "<p>Pledged anonymously successfully!</p>";
		}
		
		$subject = "You've pledged to join $event_info[Name]";
		$body = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional = //EN\">
		<html>
		<body>
		Hello there!<br /><br />You have pledged to the event <a href=\"harkath.com/swrm/event-page.php?id=$_POST[eid]\">$event_info[Name]</a>. You will be notified again when the event has received enough pledges to take place.<br /><br />
		Thanks,<br />
		The SWRM Team
		</body>
		</html>";
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "From: support@harkath.com";
		if (mail($email, $subject, $body, $headers)) {
			echo "<p>Message successfully sent to $email!</p>";
		} else {
			echo "<p>Message delivery to $email failed.</p>";
		}
	}
} else {
	// Something went wrong.
	include("error-page.php");
}

echo '<a href="event-page.php?id='.$_POST['eid'].'">Return to Event Page</a>';

mysql_close($con);

print_footer();
?>