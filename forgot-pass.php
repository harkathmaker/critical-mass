<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html>
<head>
<title>Login</title>
<?php include("header.php"); ?>
</head>

<body>

<div id="container">
<?php include("nav.php"); ?>

<p>You've forgotten your password? HALP!</p>
<p>Enter your email, and your new password will be sent to you.</p>
<form name="input" action="forgot-pass-process.php" method="post">
<p>Email: <input type="text" name="email" /></p>
<input type="submit" value="Reset Password" />
</form> 

</body>

</html>