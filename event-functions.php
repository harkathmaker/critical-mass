<?

function getEventInfo($EID) {
	$query = sprintf("SELECT * FROM Events WHERE EID=%s LIMIT 1",$EID);
	$result = mysql_query($query);
	$info = mysql_fetch_array($result);
	return $info;
}
?>