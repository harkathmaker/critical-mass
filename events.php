<?php
include("header.php");
print_header("Events");

include("nav.php"); ?>

<br /><br />
<table border="1">
<tr><td>Name</td><td>If...</td><td>Then...</td><td>Trigger number</td></tr>
<?php
$MAX_PLAYERS = 50;

require_once("error.php");
require_once("connection.php");

$con = connectToHost();
$db_selected = selectDB($con);

$result = mysql_query("SELECT * FROM Events");
mysql_close($con);

$i = 0;
while($row = mysql_fetch_array($result)) {
  if($i >= $MAX_PLAYERS) {
	break;
  }
  echo "<tr><td><a href=\"event-page.php?id=$row[EID]\">$row[Name]</a></td><td>$row[IfStatement]</td><td>$row[ThenStatement]</td><td>$row[TriggerNumber]</td></tr>";
  $i += 1;
}

echo '</table>';
print_footer();
?>