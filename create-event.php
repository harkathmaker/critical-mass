<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html>
<head>
<title>SWRM - Create New Event</title>
<?php include("header.php"); ?>
<script type="text/javascript">
W3CDOM = true;
function validate() {
	validForm = true;
	firstError = null;
	errorstring = '';

	var x = document.forms[0].elements;
	for (var i=0;i<x.length;i++) {
		if (!x[i].value && x[i].name != "maxnumber")
			writeError(x[i],' Required field');
	}
    if (validForm) {
		alert('All data is valid!');
    } else {
        alert('Data invalid.');
    }
	return validForm;
}

function writeError(obj,message) {
	validForm = false;
	if (obj.hasError) return;
	if (W3CDOM) {
		obj.className += ' error';
		obj.onchange = removeError;
		var sp = document.createElement('span');
		sp.className = 'error';
		sp.appendChild(document.createTextNode(message));
		obj.parentNode.appendChild(sp);
		obj.hasError = sp;
	}
	else {
		errorstring += obj.name + ': ' + message + '\n';
		obj.hasError = true;
	}
	if (!firstError)
		firstError = obj;
}

function removeError()
{
	this.className = this.className.substring(0,this.className.lastIndexOf(' '));
	this.parentNode.removeChild(this.hasError);
	this.hasError = null;
	this.onchange = null;
}
</script>
</head>

<body>

<?php include("nav.php"); ?>

<div id="container">
<!--<?php #include("menu.php"); ?>-->

<!---->

<form name="input" action="create-event-process.php" onsubmit="return validate()" method="post">
<p>Event Title: <input type="text" name="title" /></p>
<p>If: <input type="text" name="ifstatement" /> (i.e. "If 10 people pledge to this event")</p>
<p>Then: <input type="text" name="thenstatement" /> (i.e. "Then we will have a smashing get-together at Ground Kontrol")</p>
<p>Description: <textarea name="description" rows="4" cols="50"/></textarea></p>
<p>Trigger number: <input type="text" name="triggernumber" /></p>
<p>Max number (leave blank for none): <input type="text" name="maxnumber" /></p>
<p>Due date (when do you need to know the number of attendees?): 
<select name="due-month">
  <option value="1">January</option>
  <option value="2">February</option>
  <option value="3">March</option>
  <option value="4">April</option>
  <option value="5">May</option>
  <option value="6">June</option>
  <option value="7">July</option>
  <option value="8">August</option>
  <option value="9">September</option>
  <option value="10">October</option>
  <option value="11">November</option>
  <option value="12">December</option>
</select>
<select name="due-day">
<?
for($i=1;$i<=31;$i++) {
	echo "<option value=\"$i\">$i</option>";
}
?>
</select>
<select name="due-year">
<?
for($i=2020;$i>2013;$i--) {
	echo "<option value=\"$i\">$i</option>";
}
?>
</select></p>
<p>Event date (when is the event happening?): 
<select name="event-month">
  <option value="1">January</option>
  <option value="2">February</option>
  <option value="3">March</option>
  <option value="4">April</option>
  <option value="5">May</option>
  <option value="6">June</option>
  <option value="7">July</option>
  <option value="8">August</option>
  <option value="9">September</option>
  <option value="10">October</option>
  <option value="11">November</option>
  <option value="12">December</option>
</select>
<select name="event-day">
<?
for($i=1;$i<=31;$i++) {
	echo "<option value=\"$i\">$i</option>";
}
?>
</select>
<select name="event-year">
<?
for($i=2020;$i>2013;$i--) {
	echo "<option value=\"$i\">$i</option>";
}
?>
</select></p>
<p>Location: <input type="text" name="location" /></p>
<p><input type="radio" name="visibility" value="public" /> Public Event</p>
<p><input type="radio" name="visibility" value="private" /> Private Event</p>

<?
if(!isset($_SESSION['user'])) {
	echo '<p>Email address (all notifications will be sent here): <input type="text" name="email" /></p>';
}
?>

<input type="submit" value="Create Event!" />
</form> 

</body>

</html>