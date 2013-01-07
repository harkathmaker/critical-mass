<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html>
<head>
<title>Messages</title>
<?php include("header.php"); ?>
</head>

<body>
<?
include("nav.php");

$MAX_MESSAGES = 50;

if(!isset($_SESSION['user'])) {
	include("not-signed-in.php");
	exit();
}

$con = connectToHost();
$db = selectDB($con);

echo "<br />";

if(isset($_REQUEST['user'])) {
	if(isset($_REQUEST['text'])) {
		// Send the new message
		echo "Sending message to ".$_REQUEST['user'];
		$query = sprintf("INSERT INTO Messages (Sender,Recipient,Text,DateSent) VALUES (%s,%s,'%s','%s')",$_SESSION['user'],$_REQUEST['user'],mysql_escape_string($_REQUEST['text']),date("c"));
		$result = mysql_query($query);
	}

	// Conversation with a specific user
	$query = sprintf("SELECT * FROM Messages WHERE (Sender=%s AND Recipient=%s) OR (Recipient=%s AND Sender=%s) ORDER BY DateSent DESC LIMIT %s",$_SESSION['user'],$_REQUEST['user'],$_SESSION['user'],$_REQUEST['user'],$MAX_MESSAGES);
	$result = mysql_query($query);
	
	$other_info = getUserInfo($_REQUEST['user']);
	$user_info = getUserInfo($_SESSION['user']);

	mysql_close($con);
	
	echo '<table class="messages">';
	
	$last_correspondent = NULL;
	$result_reversed = array();
	while($row = mysql_fetch_array($result)) {
		$result_reversed[] = $row;
	}
	$result_reversed = array_reverse($result_reversed);
	foreach($result_reversed as $row) {
		echo '<tr><td>';
		if($last_correspondent != $row['Sender']) {
			echo ($row['Sender'] == $_SESSION['user'] ? $user_info['DisplayName'] : $other_info['DisplayName']);
			$last_correspondent = $row['Sender'];
		}
		echo '</td><td>'.$row['Text'].'</td>';
		$timeRaw = $row['DateSent'];
		$timestamp = strtotime($timeRaw);
		$time = date("F j g:i a", $timestamp);
		echo "<td>$time</td></tr>";
	}
	echo '</table>';
	
	echo '<form name="sendmessage" action="messages.php" method="post">';
	echo '<input type="hidden" name="user" value="'.$_REQUEST['user'].'" />';
	echo '<textarea name="text" rows="6" cols="50"></textarea>';
	echo '<input type="submit" value="Send" />';
	echo '</form>';
	
} else {

	$query = sprintf("SELECT * FROM Messages WHERE Sender=%s OR Recipient=%s ORDER BY DateSent DESC",$_SESSION['user'],$_SESSION['user']);
	$result = mysql_query($query);

	$users = array();

	echo '<table class="messageList">';
	while($row = mysql_fetch_array($result)) {
		// Find the other person in the conversation
		$otherId = ($row['Sender'] != $_SESSION['user'] ? $row['Sender'] : $row['Recipient']);
		if(!in_array($otherId,$users)) {
			$users[] = $otherId;
			$correspondent_info = getUserInfo($otherId);
			echo '<tr><td><a href="messages.php?user='.$otherId.'">'.$correspondent_info['DisplayName'].'('.$otherId.')</a></td></tr>';
			echo '<tr><td>'.substr($row['Text'],0,50).' ...</td></tr>';
		}
	}
	echo '</table>';
	mysql_close($con);
}

?>

</body>

</html>