<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html>
<head>
<title>Login</title>
<?php include("header.php"); ?>
</head>

<body>

<div id="container">
<?php include("menu.php"); ?>

<form name="input" action="login-process.php" method="post">
<p>Email: <input type="text" name="email" /></p>
<p>Password: <input type="password" name="password" /></p>
<p><a href="forgot-pass.php">Forgot Password!</a></p>
<input type="submit" value="Sign in" />
</form> 

</body>

</html>