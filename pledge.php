<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html>
<head>
<title>Event</title>
<?php include("header.php"); ?>
</head>

<body>
<?php include("nav.php"); ?>

<?

$con = connectToHost();
$db = selectDB($con);

if(isset($_SESSION['user'])) {
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
		$request = mysql_query($query);
		if($request) {
			echo "Deleted duplicate.";
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
	}
} else {
	// Something went wrong.
	include("error-page.php");
}

echo '<a href="event-page.php?id='.$_POST['eid'].'">Return to Event Page</a>';

mysql_close($con);
?>

</body>

</html>