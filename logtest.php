<table class="loginDisplay">
<tr>
<?
require_once("error.php");
require_once("connection.php");
require_once("user-functions.php");

if(isset($_SESSION['user']))
    {
        // Code for Logged members

        // Identifying the user
        $user = $_SESSION['user'];
		
		$con = connectToHost();
		$db = selectDB($con);
		$user_info = getUserInfo($_SESSION['user']);
		mysql_close($con);
       
        // Information for the user.
	echo "<td>Welcome, $user_info[Email] (ID $user).</td>";
    echo '<td><a href="profile.php?user='.$user.'">View Profile</a></td><td><a href="logout.php">Sign out</a></td>';
    }
else
    {
        // Code to show Guests
   	echo "<td>Currently not signed in.</td>";
    echo '<td><a href="login.php">Sign in</a></td><td><a href="create-user.php">Create Account</a></td>';
    }

?>
</tr>
</table>
<br />