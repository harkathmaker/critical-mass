<?
include("header.php");

print_header("Event Invitations");

include("nav.php");

$eid = $_POST['eid'];
$tok = strtok($_POST['invitees'],", ");

echo "Input: $_POST[invitees]<br />";

$con = connectToHost();
$db = selectDB($con);

$event_info = getEventInfo($eid);

if(isset($_SESSION['user'])) {
	// Get the user's name
	$user_info = getUserInfo($_SESSION['user']);
	$name = $user_info['DisplayName'];
} else {
	$name = "Someone";
}

while($tok !== false) {
	if(!filter_var($tok, FILTER_VALIDATE_EMAIL)) {
		echo "<p>Address $tok was invalid.</p>";
	} else {
		$subject = "Invitation to Critical Mass Event";
		$body = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional = //EN\">
		<html>
		<body>
		Hello there!<br /><br />$name has invited you to <a href=\"harkath.com/swrm/event-page.php?id=$eid\">$event_info[Name]</a>.<br /><br />
		$_POST[note]<br /><br />
		Thanks,<br />
		The SWRM Team
		</body>
		</html>";
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "From: support@harkath.com";
		if (mail($tok, $subject, $body, $headers)) {
			echo "<p>Message successfully sent to $tok!</p>";
		} else {
			echo "<p>Message delivery to $tok failed.</p>";
		}
	}
	$tok = strtok(", ");
}

print_footer();

?>