<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html>
<head>
<title>SWRM - Create New User</title>
<?php include("header.php"); ?>
<script type="text/javascript">
W3CDOM = true;
function validate() {
	validForm = true;
	firstError = null;
	errorstring = '';
        // Check gender
        var genderChecked = 0;
        //alert(document.forms[0]["sex"]);
        for(var i=0;i<document.forms[0]["sex"].length;i++) {
            genderChecked += document.forms[0]["sex"][i].checked;
        }
        if(!genderChecked) {
            writeError(document.forms[0]["sex"][0],'   Required field');
            validForm = false;
        }
	var x = document.forms[0].elements;
	for (var i=0;i<x.length;i++) {
		if (!x[i].value)
			writeError(x[i],' Required field');
	}
    if(document.forms[0]["password"].value != document.forms[0]["confpass"].value) {
        writeError(document.forms[0]["confpass"],' Passwords do not match!');
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

<div id="container">
<!--<?php include("nav.php"); ?>-->

<!---->

<form name="input" action="create-user-process.php" onsubmit="return validate()" method="post">
<p>Email (this will be your username): <input type="text" name="email" /></p>
<p>Password: <input type="password" name="password" /></p>
<p>Confirm password: <input type="password" name="confpass" /></p>
<p>First Name: <input type="text" name="firstname" /></p>
<p>Last Name: <input type="text" name="lastname" /></p>
<p><input type="radio" name="sex" value="male" /> Male</p>
<p><input type="radio" name="sex" value="female" /> Female</p>
<p>Birthday
<select name="birth-month">
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
<select name="birth-day">
<?
for($i=1;$i<=31;$i++) {
	echo "<option value=\"$i\">$i</option>";
}
?>
</select>
<select name="birth-year">
<?
for($i=2012;$i>1920;$i--) {
	echo "<option value=\"$i\">$i</option>";
}
?>
</select></p>
<!--<p><select name="element">
  <option value="none">Select</option>
  <option value="fire">Fire</option>
  <option value="water">Water</option>
  <option value="air">Air</option>
  <option value="earth">Earth</option>
  <option value="light">Light</option>
  <option value="dark">Darkness</option>
</select></p>
<p><select name="faction">
  <option value="none">Select</option>
  <option value="Fostron">Fostron</option>
  <option value="Silfar">Silfar</option>
  <option value="Narnia">Narnia</option>
  <option value="Norn">Norn</option>
</select></p>-->
<input type="submit" value="Create Account" />
</form> 

</body>

</html>