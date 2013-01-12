<?
require_once("utility/util.php");

function print_day_options() {
	for($i=1;$i<=31;$i++) {
		echo "<option value=\"$i\">$i</option>";
	}
}
function print_month_options() {
  echo '<option value="1">January</option>';
  echo '<option value="2">February</option>';
  echo '<option value="3">March</option>';
  echo '<option value="4">April</option>';
  echo '<option value="5">May</option>';
  echo '<option value="6">June</option>';
  echo '<option value="7">July</option>';
  echo '<option value="8">August</option>';
  echo '<option value="9">September</option>';
  echo '<option value="10">October</option>';
  echo '<option value="11">November</option>';
  echo '<option value="12">December</option>';
}
function print_year_options($range_start,$range_end) {
	$s = sign($range_end-$range_start);
	for($i=$range_start;$i!=$range_end+$s;$i+=$s) {
		echo "<option value=\"$i\">$i</option>";
	}
}

?>