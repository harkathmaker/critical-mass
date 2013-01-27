<?
include("header.php");

$SEARCH_LIMIT = 30;

// Internal function for printing out the info for matching events.
// Takes an SQL query row as input.
function print_event_result($row) {
	echo "<table border=\"1\"><tr><td><a href=\"event-page.php?id=$row[EID]\">$row[Name]</a></td><td>$row[Description]</td></tr></table><br />";
}
// Internal function for printing out info for a matching user.
// Takes an SQL query row as input.
function print_user_result($row) {
	echo "<table border=\"1\"><tr><td><a href=\"profile.php?user=$row[UID]\">$row[DisplayName]</a></td><td>$row[AboutMe]</td></tr></table><br />";
}



print_header("Search results for ".$_GET['search']);

include("nav.php");

$search = mysql_escape_string($_GET['search']);

$con = connectToHost();
$db = selectDB($con);

echo "<h2>Search results for $_GET[search]</h2>";

$query = sprintf("SELECT * FROM Events WHERE Name='%s' LIMIT %s",$search,$SEARCH_LIMIT);
$exact_name_event_result = mysql_query($query);
$query = sprintf("SELECT * FROM Events WHERE Location='%s' LIMIT %s",$search,$SEARCH_LIMIT);
$exact_location_event_result = mysql_query($query);
$query = sprintf("SELECT * FROM Events WHERE Name LIKE '%%%s%%' OR Description LIKE '%%%s%%' OR IfStatement LIKE '%%%s%%' OR ThenStatement LIKE '%%%s%%' OR Location LIKE '%%%s%%'  OR ContactInfo='%s' LIMIT %s",
				$search,
				$search,
				$search,
				$search,
				$search,
				$search,
				$SEARCH_LIMIT
				);
$event_result = mysql_query($query);

$i = 0;
$printed_events = array();
if(mysql_num_rows($exact_name_event_result)+mysql_num_rows($exact_location_event_result)+mysql_num_rows($event_result) > 0) {
	echo "<h3>Events</h3>";
}
while($row = mysql_fetch_array($exact_name_event_result) and $i < $SEARCH_LIMIT) {
	$printed_events[] = $row['EID'];
	print_event_result($row);
	$i++;
}
while($row = mysql_fetch_array($exact_location_event_result) and $i < $SEARCH_LIMIT) {
	if(!in_array($row['EID'],$printed_events)) {
		$printed_events[] = $row['EID'];
		print_event_result($row);
		$i++;
	}
}
while($row = mysql_fetch_array($event_result) and $i < $SEARCH_LIMIT) {
	if(!in_array($row['EID'],$printed_events)) {
		$printed_events[] = $row['EID'];
		print_event_result($row);
		$i++;
	}
}
$query = sprintf("SELECT COUNT(*) AS count FROM Events WHERE Name LIKE '%%%s%%' OR Description LIKE '%%%s%%' OR IfStatement LIKE '%%%s%%' OR ThenStatement LIKE '%%%s%%' OR Location LIKE '%%%s%%'  OR ContactInfo='%s'",
				$search,
				$search,
				$search,
				$search,
				$search,
				$search
				);
$result = mysql_query($query);
$count = mysql_fetch_array($result);
echo "<p>Found $count[count] matching events.</p>";

$query = sprintf("SELECT * FROM Users WHERE DisplayName='%s' OR Email='%s' LIMIT %s",$search,$search,$SEARCH_LIMIT);
$exact_user_result = mysql_query($query);
$query = sprintf("SELECT * FROM Users WHERE DisplayName LIKE '%%%s%%' OR Email LIKE '%%%s%%' LIMIT %s",$search,$search,$SEARCH_LIMIT);
$rough_user_result = mysql_query($query);

$i = 0;
$printed_users = array();
if(mysql_num_rows($exact_user_result)+mysql_num_rows($rough_user_result) > 0) {
	echo "<h3>Users</h3>";
}
while($row = mysql_fetch_array($exact_user_result) and $i < $SEARCH_LIMIT) {
	$printed_users[] = $row['UID'];
	print_user_result($row);
	$i++;
}
while($row = mysql_fetch_array($rough_user_result) and $i < $SEARCH_LIMIT) {
	if(!in_array($row['UID'],$printed_users)) {
		$printed_users[] = $row['UID'];
		print_user_result($row);
		$i++;
	}
}
$query = sprintf("SELECT COUNT(*) AS count FROM Users WHERE DisplayName LIKE '%%%s%%' OR Email LIKE '%%%s%%'",$search,$search);
$result = mysql_query($query);
$count = mysql_fetch_array($result);
echo "<p>Found $count[count] matching users.</p>";

mysql_close($con);

print_footer();
?>