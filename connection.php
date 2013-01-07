<?php


function connectToHost() {
	$con = mysql_connect("localhost", "harkathc_SWTest", "FortuneDays5");
	if (!$con) {
	  die('Could not connect: ' . mysql_error());
	}
	return $con;
}
function selectDB($con) {
	$db_selected = mysql_select_db("harkathc_SWRM", $con);
	if (!$db_selected) {
	  die ("Can't use test_db : " . mysql_error());
	}
	return $db_selected;
}

?>