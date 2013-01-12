<?
include("header.php");

print_header("Forgot Password");

include("nav.php");

echo "<p>You've forgotten your password? HALP!</p>
<p>Enter your email, and your new password will be sent to you.</p>
<form name=\"input\" action=\"forgot-pass-process.php\" method=\"post\">
<p>Email: <input type=\"text\" name=\"email\" /></p>
<input type=\"submit\" value=\"Reset Password\" />
</form>";

print_footer();
?>