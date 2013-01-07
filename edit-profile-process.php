<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html>
<head>
<title>Edit User Profile</title>
<? include("header.php"); ?>
</head>

<body>

<div id="container">

<?
include("nav.php");
require_once("error.php");
require_once("connection.php");

// TEMP
//echo "User: ".$_POST['email']."<br />";
//echo "Password: ".$_POST['password']."<br />";
//echo "Gender: ".$_POST['sex']."<br />";
//

// if(!isset($_POST['title'])
    // || !isset($_POST['ifstatement'])
	// || !isset($_POST['thenstatement'])
    // || !isset($_POST['description'])
	// || !isset($_POST['visibility'])
	// || !isset($_POST['duedate'])
	// || !isset($_POST['eventdate'])
	// || !isset($_POST['location'])
	// || !isset($_SESSION['user'])
    // || !filter_input(INPUT_POST, 'triggernumber', FILTER_VALIDATE_INT)) {
    // echo 'User data was submitted incorrectly. Please try again.';
    // echo '<br /><br /><a href="create-event.php">Go Back</a>';
    // die();
// }

$displayname = mysql_escape_string($_POST['displayname']);
$aboutme = mysql_escape_string($_POST['aboutme']);
$location = mysql_escape_string($_POST['location']);
$birthdate = $_POST['birth-year'].'-'.$_POST['birth-month'].'-'.$_POST['birth-day'];

$con = connectToHost();
$db_selected = selectDB($con);
  
$sql=sprintf("UPDATE Users SET DisplayName='%s', Gender='%s', AboutMe='%s', Location='%s', BirthDate='%s' WHERE UID=%s",$displayname,$_POST['sex'],$aboutme,$location,$birthdate,$_SESSION['user']);

if (!mysql_query($sql,$con)) {
  die('Error: ' . mysql_error(). '<br /><p>Unable to update profile.</p>');
} else {
	mysql_close($con);
	echo '<p>Updated successfully.</p>';
	echo '<script>location.href="profile.php?user='.$_SESSION['user'].'"</script>';
}
?>

</body>

</html>