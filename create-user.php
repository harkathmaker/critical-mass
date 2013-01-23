<?
include("header.php");

require_once("ui/date.php");
print_header("Create New User",true);
?>

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

<?
print_footer();

include("nav.php");
?>

<form name="input" action="create-user-process.php" onsubmit="return validate()" method="post">
<p><label for="email">Email (this will be your username): </label><input type="email" name="email" id="email" /></p>
<p><label for="password">Password: </label><input type="password" name="password" id="password" /></p>
<p><label for="confpass">Confirm password: </label><input type="password" name="confpass" id="confpass" /></p>
<p><label for="firstname">First Name: </label><input type="text" name="firstname" id="firstname" /></p>
<p><label for="lastname">Last Name: </label><input type="text" name="lastname" id="lastname" /></p>
<p><input type="radio" name="sex" value="male" id="male" /><label for="male"> Male</label></p>
<p><input type="radio" name="sex" value="female" id="female" /><label for="female"> Female</label></p>
<p>Birthday
<select name="birth-month">
<?
	print_month_options();
?>
</select>
<select name="birth-day">
<?
print_day_options();
?>
</select>
<select name="birth-year">
<?
print_year_options(intval(date('Y'))-8,1920);
?>
</select></p>
<input type="submit" value="Create Account" />
</form> 

<? print_footer(); ?>