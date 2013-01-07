<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html>
<head>
<title>Event</title>
<?php include("header.php"); ?>
</head>

<body>


<?php
include("nav.php");


echo "<br /><br />";

$con = connectToHost();
$db_selected = selectDB($con);
$eid = $_GET['id'];

$result = mysql_query("SELECT * FROM Events WHERE EID='$eid'");
$event = mysql_fetch_array($result);

if($event['Creator'] != NULL) {
	// Creator was registered user
	$creator_info = getUserInfo($event['Creator']);
	$creator = '<a href="profile.php?user='.$creator_info['UID'].'">'.$creator_info['DisplayName']."</a> (".$creator_info['Email'].")";
} else {
	// Creator was anonymous
	$creator = $event['ContactInfo'];
}

echo "<h2>$event[Name]</h2>";
echo "<h4>$event[Visibility] Event by $creator</h4>";

echo "<table border=\"1\">";
echo "<tr><td>If...</td><td>Then...</td><td>Trigger number</td><td>Pledged Attendees</td></tr>";

$result = mysql_query("SELECT COUNT(*) AS Num FROM Attendees WHERE EventID='$eid'");
$num = mysql_fetch_array($result);
$result = mysql_query("SELECT COUNT(*) AS Num FROM AnonAttendees WHERE EventID='$eid'");
$anonNum = mysql_fetch_array($result);
$totalCount = $num['Num']+$anonNum['Num'];
echo "<tr><td>$event[IfStatement]</td><td>$event[ThenStatement]</td><td>$event[TriggerNumber]</td><td>$totalCount</td></tr>";

echo "</table><br />";

echo "<p>$event[Description]</p><br />";

echo '<form name="input" action="pledge.php" method="post">';
echo '<input type="hidden" value="'.$eid.'" name="eid" />';
if(isset($_SESSION['user'])) {

	$result = mysql_query(sprintf("SELECT * FROM Attendees WHERE User=%s AND EventID=%s","$_SESSION[user]","$eid"));
	if(mysql_num_rows($result) > 0) {
		echo "You are pledged to this event.";
	} else {
		echo '<input type="submit" value="Pledge!" />';
	}
	echo '</form>';
} else {
	
//	echo "<a href=\"pledge.php?anon=1&eid=$eid\">Pledge!</a>";
	echo '<p>Notify me by email: <input type="text" name="email" /></p>';
	echo '<input type="submit" value="Pledge!" /></form>';
}

if($event['Visibility'] == "Public" or (isset($_SESSION['user']) and $event['Creator'] == $_SESSION['user'])) {
	// User is allowed to invite to this event
	echo '<p>Invite people to this event</p>';
	echo '<p>Enter emails below, separated by commas:</p>';
	echo '<form action="event-invite.php" method="post">';
	echo '<input type="hidden" name="eid" value="'.$eid.'" />';
	echo '<textarea name="invitees" rows="6" cols="50"></textarea>';
	echo '<input type="submit" value="Send Invites" />';
	echo '</form>';
}

mysql_close($con);
?>

<br /><br />

</body>

</html>