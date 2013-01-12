<table border="1" class="nav">
<tr>
<td><a href="create-event.php">Create Event</a></td>
<td><a href="events.php">View Events</a></td>
<td><form name="search" action="search.php" method="get">
<input type="text" name="search" />
<input type="submit" value="Search" />
</form>
</td>
<?
require_once("error.php");
require_once("connection.php");
require_once("user-functions.php");

if(isset($_SESSION['user'])) {
	// Code for Logged members

	// Identifying the user
	$user = $_SESSION['user'];
	
	$con = connectToHost();
	$db = selectDB($con);
	$user_info = getUserInfo($_SESSION['user']);
	mysql_close($con);
   
	// Information for the user.
    echo "<td>Welcome, $user_info[Email] (ID $user). <a href=\"profile.php?user=$user\">View Profile</a></td>";
	echo '<td><a href="messages.php">Messages</a></td>';
	echo '<td><a href="logout.php">Sign out</a></td>';
} else {
    // Code to show Guests
    echo '<td>Currently not signed in. <a href="login.php">Sign in</a></td><td><a href="create-user.php">Create Account</a></td>';
}
?>
</tr>
</table>