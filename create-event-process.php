<?
include("header.php");
print_header("Creating Event...");

// TEMP
//echo "User: ".$_POST['email']."<br />";
//echo "Password: ".$_POST['password']."<br />";
//echo "Gender: ".$_POST['sex']."<br />";
//

if(!isset($_POST['title'])
    || !isset($_POST['ifstatement'])
	|| !isset($_POST['thenstatement'])
    || !isset($_POST['description'])
	|| !isset($_POST['visibility'])
	|| !isset($_POST['due-year'])
	|| !isset($_POST['due-month'])
	|| !isset($_POST['due-day'])
	|| !isset($_POST['event-year'])
	|| !isset($_POST['event-month'])
	|| !isset($_POST['event-day'])
//	|| !isset($_POST['location'])
//	|| !isset($_SESSION['user'])
    || !filter_input(INPUT_POST, 'triggernumber', FILTER_VALIDATE_INT)
	|| (!isset($_SESSION['user']) && !isset($_POST['email']))){
//	|| (isset($_POST['email']) && !filter_input(INPUT_POST,'email', FILTER_VALIDATE_EMAIL))) {
    echo 'User data was submitted incorrectly. Please try again.';
    echo '<br /><br /><a href="create-event.php">Go Back</a>';
    die();
}

$con = connectToHost();
$db_selected = selectDB($con);

$duedate = $_POST['due-year'].'-'.$_POST['due-month'].'-'.$_POST['due-day'];
$eventdate = $_POST['event-year'].'-'.$_POST['event-month'].'-'.$_POST['event-day'];
$location = ($_POST['location'] != '' ? "'".mysql_escape_string($_POST['location'])."'" : "NULL");

if(isset($_SESSION['user'])) {
	$creator_field = "Creator";
	$creator = $_SESSION['user'];
} else {
	$creator_field = "ContactInfo";
	$creator = "'$_POST[email]'";
}
$sql = sprintf("INSERT INTO Events (Name,IfStatement,ThenStatement,EventDate,DueDate,Description,TriggerNumber,Location,Visibility,%s) VALUES('%s','%s','%s','%s','%s','%s','%s',%s,'%s',%s)",
		$creator_field,
		mysql_escape_string($_POST['title']),
		mysql_escape_string($_POST['ifstatement']),
		mysql_escape_string($_POST['thenstatement']),
		mysql_escape_string($eventdate),
		mysql_escape_string($duedate),
		mysql_escape_string($_POST['description']),
		mysql_escape_string($_POST['triggernumber']),
		$location,
		mysql_escape_string($_POST['visibility']),
		$creator);

if (!mysql_query($sql,$con)) {
  die('Error: ' . mysql_error(). '<br /><p>Unable to create event.</p>');
}

$sql = sprintf("SELECT EID From Events WHERE Name='%s' AND IfStatement='%s' AND ThenStatement='%s' AND EventDate='%s' AND DueDate='%s' AND Visibility='%s' AND %s=%s",
		mysql_escape_string($_POST['title']),
		mysql_escape_string($_POST['ifstatement']),
		mysql_escape_string($_POST['thenstatement']),
		mysql_escape_string($eventdate),
		mysql_escape_string($duedate),
		mysql_escape_string($_POST['visibility']),
		$creator_field,
		$creator);
		
$result = mysql_query($sql);
$row = mysql_fetch_array($result);

mysql_close($con);
echo 'Event created successfully!<br /><a href="create-event.php">Home</a>';
echo '<script>location.href="event-page.php?id='.$row['EID'].'"</script>';

print_footer();
?>