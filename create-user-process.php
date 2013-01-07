<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html>
<head>
<title>Create New Player</title>
<?php include("header.php"); ?>
</head>

<body>

<div id="container">
<!--<?php #include("menu.php"); ?>-->

<?

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
echo 'Connection successful.<br /><a href="create-user.php">Home</a>';
?>

</body>

</html>