<?php
/* Includes the typically used stylesheets, etc. */	

echo '<link rel="stylesheet" type="text/css" href="style.css" />';
//echo '<link rel="stylesheet" type="text/css" href="harkathstyle.css" />';
echo '<!-- include javascript functions -->';
//include("roll.php");

// Starting the session
session_start();

require_once("connection.php");
require_once("user-functions.php");
require_once("error.php");
?>