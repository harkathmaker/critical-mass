<?

function sign($arg) {
	return ($arg == 0 ? $arg : min(1, max(-1, $arg)));
}

?>