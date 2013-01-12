<?php
/* Includes the typically used stylesheets, etc. */	

// Starting the session
session_start();

require_once("connection.php");
require_once("user-functions.php");
require_once("event-functions.php");
require_once("error.php");

function print_header($title,$end_manually = false) {
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'."\n";
	echo '<html>'."\n";
	echo '<head>'."\n";
	echo "<title>$title</title>\n";
	echo '<link rel="stylesheet" type="text/css" href="style.css" />'."\n";
	//echo '<link rel="stylesheet" type="text/css" href="harkathstyle.css" />';
	echo '<!-- include javascript functions -->'."\n";
	//include("roll.php");
	if(!$end_manually) {
		end_header();
	}
}
function end_header() {
	echo '</head>'."\n";
	echo '<body>'."\n";
}
function print_footer() {
	echo '</body>'."\n";
	echo '</html>';
}
?>