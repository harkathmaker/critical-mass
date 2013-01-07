<?php
include("header.php");

$allowedExts = array("jpg", "jpeg", "gif", "png");
$extension = end(explode(".", $_FILES["profilepic"]["name"]));
if ((($_FILES["profilepic"]["type"] == "image/gif")
|| ($_FILES["profilepic"]["type"] == "image/jpeg")
|| ($_FILES["profilepic"]["type"] == "image/png")
|| ($_FILES["profilepic"]["type"] == "image/pjpeg"))
&& ($_FILES["profilepic"]["size"] < 200000)
&& in_array($extension, $allowedExts))
{
  if ($_FILES["profilepic"]["error"] > 0) {
    echo "Return Code: " . $_FILES["profilepic"]["error"] . "<br>";
  } else {
    echo "Upload: " . $_FILES["profilepic"]["name"] . "<br>";
    echo "Type: " . $_FILES["profilepic"]["type"] . "<br>";
    echo "Size: " . ($_FILES["profilepic"]["size"] / 1024) . " kB<br>";
    echo "Temp profilepic: " . $_FILES["profilepic"]["tmp_name"] . "<br>";

    if (file_exists("images/profile/" . $_SESSION['user'].$extension))
    {
      echo $_SESSION['user'].$extension . " already exists; deleting. ";
	  unlink("images/profile/" . $_SESSION['user'].$extension);
    }
    else
    {
	  $new_file = "profilepic".$_SESSION['user'].".".$extension;
      move_uploaded_file($_FILES["profilepic"]["tmp_name"],
      "images/profile/".$new_file);
      echo "Stored in: " . "images/profile/" . $new_file;
	  $con = connectToHost();
	  $db = selectDB($con);
	  $sql = "UPDATE Users SET ProfilePic='$new_file' WHERE UID=$_SESSION[user]";
	  echo "Query : ".$sql."<br />";
	  if(mysql_query($sql)) {
		echo "Query successful.";
		echo '<script>location.href="profile.php?user='.$_SESSION['user'].'"</script>'; 
	  }
    }
  }
}
else
{
  echo "Invalid file";
}
?>