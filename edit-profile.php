<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html>
<head>
<title>Edit User Profile</title>
<?php include("header.php"); ?>
</head>

<body>
<?
include("nav.php");


if(!isset($_SESSION['user'])) {
	include("error-page.php");
	exit();
}

$con = connectToHost();
$db = selectDB($con);

$user_info = getUserInfo($_SESSION['user']);
mysql_close($con);

$pic_url = ($user_info['ProfilePic'] != NULL ? $user_info['ProfilePic'] : "profile_default.jpg");
echo '<img src="images/profile/'.$pic_url.'" width="160" height="160" />';

echo '<form action="upload-profile-pic.php" method="post" enctype="multipart/form-data">
<label for="file">Upload a new profile photo:</label>
<input type="file" name="profilepic" id="file"><br />
<input type="submit" name="submit" value="Upload">
</form>';

echo '<form name="input" action="edit-profile-process.php" method="post" >';
echo '<p>Name: <input type="text" name="displayname" value="'.$user_info['DisplayName'].'" />';

$checkMale = "";
$checkFemale = "";
if($user_info['Gender'] == "MALE") {
	$checkMale = "checked";
} else {
	$checkFemale = "checked";
}
echo '<p><input type="radio" name="sex" id="male" value="MALE" '.$checkMale.' /><label for="male">Male</label></p>';
echo '<p><input type="radio" name="sex" id="female" value="FEMALE" '.$checkFemale.' /><label for="female">Female</label></p>';
echo '<p>Birthday
<select name="birth-month">
  <option value="1">January</option>
  <option value="2">February</option>
  <option value="3">March</option>
  <option value="4">April</option>
  <option value="5">May</option>
  <option value="6">June</option>
  <option value="7">July</option>
  <option value="8">August</option>
  <option value="9">September</option>
  <option value="10">October</option>
  <option value="11">November</option>
  <option value="12">December</option>
</select>
<select name="birth-day">';
for($i=1;$i<=31;$i++) {
	echo "<option value=\"$i\">$i</option>";
}
echo '</select>';
echo '<select name="birth-year">';
for($i=2012;$i>1920;$i--) {
	echo "<option value=\"$i\">$i</option>";
}
echo '</select></p>';
echo '<p>Location: <input type="text" name="location" value="'.$user_info['Location'].'" /></p>';
echo '<p>About Me:</p>';
echo '<textarea name="aboutme" rows="6" cols="50">';
echo $user_info['AboutMe'];
echo '</textarea>';

echo '<input type="submit" value="Save Changes" />';
echo '</form>';

?>

</body>

</html>