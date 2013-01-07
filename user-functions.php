<?

function getUserInfo($UID) {
	$query = sprintf("SELECT * FROM Users WHERE UID=%s LIMIT 1",$UID);
	$result = mysql_query($query);
	$info = mysql_fetch_array($result);
	return $info;
}
?>