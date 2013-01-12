<?
include("header.php");
print_header("Create New User");

// TEMP
echo "User: ".$_POST['email']."<br />";
echo "Password: ".$_POST['password']."<br />";
echo "Gender: ".$_POST['sex']."<br />";
//

if(!isset($_POST['sex'])
    || !isset($_POST['email'])
    || !isset($_POST['password'])
    || !filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)) {
    echo 'User data was submitted incorrectly. Please try again.';
    echo '<br /><br /><a href="create-user.php">Go Back</a>';
    die();
}
$birthdate = $_POST['birth-year'].'-'.$_POST['birth-month'].'-'.$_POST['birth-day'];

$displayname = mysql_escape_string($_POST['firstname'].' '.$_POST['lastname']);

$con = connectToHost();
$db_selected = selectDB($con);
  
  $sql="INSERT INTO Users (Email,Password,DisplayName,Gender,BirthDate) VALUES('$_POST[email]','$_POST[password]','$displayname','$_POST[sex]','$birthdate')";

if (!mysql_query($sql,$con)) {
  die('Error: ' . mysql_error(). '<br /><p>Unable to create user.</p>');
}

mysql_close($con);

$to = $_POST['email'];
$body = "Hi $displayname,\n\nSomeone has registered an account at <a href=Critical Mass with this email address.\n\nYour password is: $_POST[password]\n\nThanks,\nThe SWRM Team";

$subject = "Welcome to Critical Mass";
$body = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional = //EN\">
<html>
<body>
Hi $displayname,
<br /><br />
Someone has registered an account at <a href=\"http://harkath.com/swrm/events.php\">Critical Mass</a> with this email address.
<br /><br />Your password is: $_POST[password]
<br /><br />Thanks,<br />The SWRM Team
</body>
</html>";
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= "From: support@harkath.com";
if (mail($to, $subject, $body, $headers)) {
	echo "<p>Message successfully sent to $to.</p>";
} else {
	echo "<p>Message delivery to $to failed.</p>";
}

echo 'Created new user successfully!<br /><a href="create-user.php">Home</a>';
echo '<script>location.href="login.php"</script>';

print_footer();
?>