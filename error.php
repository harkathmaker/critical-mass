<?php

//error handler function
function error_handler_print($errno, $errstr, $errfile, $errLine) {
  echo "<b>Error:</b> [$errno] $errstr at line $errLine in $errfile<br />";
}

set_error_handler("error_handler_print");
?>