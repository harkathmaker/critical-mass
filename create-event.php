<?php
include("header.php");
require_once("ui/date.php");

print_header("SWRM - Create New Event");

include("nav.php");
?>

<form name="input" action="create-event-process.php" onsubmit="return validate()" method="post">
<p>Event Title: <input type="text" name="title" /></p>
<p>If: <input type="text" name="ifstatement" /> (i.e. "If 10 people pledge to this event")</p>
<p>Then: <input type="text" name="thenstatement" /> (i.e. "Then we will have a smashing get-together at Ground Kontrol")</p>
<p>Description: <textarea name="description" rows="4" cols="50"/></textarea></p>
<p>Trigger number: <input type="text" name="triggernumber" /></p>
<p>Max number (leave blank for none): <input type="text" name="maxnumber" /></p>
<p>Due date (when do you need to know the number of attendees?): 
<select name="due-month">
<?
print_month_options();
?>
</select>
<select name="due-day">
<?
print_day_options();
?>
</select>
<select name="due-year">
<?
print_year_options(2020,2013);
?>
</select></p>
<p>Event date (when is the event happening?): 
<select name="event-month">
<?
print_month_options();
?>
</select>
<select name="event-day">
<?
print_day_options();
?>
</select>
<select name="event-year">
<?
print_year_options(2020,2013);
?>
</select></p>
<p><label for="public">Location: </label><input type="text" name="location" id="location" /></p>
<p><input type="radio" name="visibility" id="public" value="public" /><label for="public"> Public Event</label></p>
<p><input type="radio" name="visibility" id="private" value="private" /><label for="public"> Private Event</label></p>

<?
if(!isset($_SESSION['user'])) {
	echo '<p>Email address (all notifications will be sent here): <input type="email" name="email" /></p>';
}

echo '<input type="submit" value="Create Event!" />';
echo '</form>';

print_footer();
?>