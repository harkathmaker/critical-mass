<?
include("header.php");

print_header("User Profile");

include("nav.php");

$PROFILEPIC_WIDTH = 256;
$PROFILEPIC_HEIGHT = 384;
$FOLLOWERS_TO_DISPLAY = 5;
$FOLLOWEES_TO_DISPLAY = 5;

$con = connectToHost();
$db = selectDB($con);

$user_info = getUserInfo($_GET['user']);

// Process following/unfollowing. This would be good to move to AJAX, etc., later.
if(isset($_SESSION['user']) and isset($_GET['follow'])) {
	if($_GET['follow'] == 1) {
		// Start following
		mysql_query(sprintf("DELETE FROM Followers WHERE Subject=%s AND Follower=%s",$_GET['user'],$_SESSION['user']));
		mysql_query(sprintf("INSERT INTO Followers (Subject,Follower) VALUES (%s,%s)", $_GET['user'],$_SESSION['user']));
	} else {
		// Unfollow
		mysql_query(sprintf("DELETE FROM Followers WHERE Subject=%s AND Follower=%s",$_GET['user'],$_SESSION['user']));
	}
}

if(isset($_SESSION['user']) and $_SESSION['user'] == $_GET['user']) {
	// It's their own profile
	echo '<table><tr><td><h2>'.$user_info['DisplayName'].'</h2></td><td><a href="edit-profile.php">Edit your profile</a></td></tr></table>';
} else {
	echo "<h2>$user_info[DisplayName]</h2>";
}

$pic_url = ($user_info['ProfilePic'] != NULL ? $user_info['ProfilePic'] : "profile_default.jpg");
echo '<img src="images/profile/'.$pic_url.'" width="160" height="160" />
<br />';


if(isset($_SESSION['user'])) {
	$query = sprintf("SELECT * FROM Followers WHERE Subject=%s AND Follower=%s",$_GET['user'],$_SESSION['user']);
	$result = mysql_query($query);
	if(mysql_num_rows($result) > 0) {
		// Already Following
		echo '<h5><a href="profile.php?user='.$_GET['user'].'&follow=0">Unfollow User</a></h5>';
	} else {
		echo '<h5><a href="profile.php?user='.$_GET['user'].'&follow=1">Follow User</a></h5>';
	}
	echo '<h5><a href="messages.php?user='.$_GET['user'].'">Send Message</a></h5>';
}

echo '<table class="profile">';
$birth = $user_info['BirthDate'];
$timestamp = strtotime($birth);
$birthdate = date("F j, Y", $timestamp);
echo "<tr><td>Birth Date</td><td>$birthdate</td></tr>";
$genderString = ($user_info['Gender'] == "FEMALE" ? "Female" : "Male");
echo "<tr><td>Gender</td><td>$genderString</td></tr>";
echo "<tr><td>Location</td><td>$user_info[Location]</td></tr>";
echo '</table>';

echo '<h3>About Me</h3>';
echo '<div id="aboutMe">';
echo $user_info['AboutMe'];
echo '</div>';

if(!is_null($user_info['ProfilePic'])) {
	echo '<img src="'.$user_info['ProfilePic'].'" width="'.$PROFILEPIC_WIDTH.'" height="'.$PROFILEPIC_HEIGHT.'" />';
}

// Karma?


//Following
echo '<h3>Is Following</h3>';
echo '<table>';
$query = sprintf("SELECT * FROM Followers INNER JOIN Users ON Followers.Subject=Users.UID WHERE Follower=%s LIMIT %s",$_GET['user'],$FOLLOWEES_TO_DISPLAY);
$result = mysql_query($query);
while($row = mysql_fetch_array($result)) {
	echo '<tr><td><a href="profile.php?user='.$row['Subject'].'">'.$row['DisplayName'].'</a></td></tr>';
}
echo '</table>';

//Followers
echo '<h3>Followers</h3>';
echo '<table>';
$query = sprintf("SELECT * FROM Followers INNER JOIN Users ON Followers.Follower=Users.UID WHERE Subject=%s LIMIT %s",$_GET['user'],$FOLLOWERS_TO_DISPLAY);
$result = mysql_query($query);
while($row = mysql_fetch_array($result)) {
	echo '<tr><td><a href="profile.php?user='.$row['Follower'].'">'.$row['DisplayName'].'</a></td></tr>';
}
echo '</table>';

mysql_close($con);

print_footer();

?>