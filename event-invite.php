<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html>
<head>
<title>Event Invitations</title>
<?php include("header.php"); ?>
</head>

<body>
<?
include("nav.php");

$eid = $_POST['eid'];
$tok = strtok($_POST['invitees'],", ");

echo "Input: $_POST[invitees]<br />";

while($tok !== false) {
	if(!filter_var($tok, FILTER_VALIDATE_EMAIL)) {
		echo "<p>Address $tok was invalid.</p>";
	} else {
		$subject = "Invitation to SWRM Event";
		$body = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional = //EN\">
		<html>
		<body>
		Hello there!<br /><br />You have been invited to the event $eid. Click <a href=\"harkath.com/swrm/event-page.php?id=$eid\">here</a> to view the event details.<br /><br />
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

?>

</body>
</html>