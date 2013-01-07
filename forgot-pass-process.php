<?php

require_once("error.php");
require_once("connection.php");
# Snippet from PHP Share: http://www.phpshare.org

/**
 * Generate and return a random string
 *
 * The default string returned is 8 alphanumeric characters.
 *
 * The type of string returned can be changed with the "seeds" parameter.
 * Four types are - by default - available: alpha, numeric, alphanum and hexidec. 
 *
 * If the "seeds" parameter does not match one of the above, then the string
 * supplied is used.
 *
 * @author      Aidan Lister <aidan@php.net>
 * @version     2.1.0
 * @link        http://aidanlister.com/repos/v/function.str_rand.php
 * @param       int     $length  Length of string to be generated
 * @param       string  $seeds   Seeds string should be generated from
 */
function str_rand($length = 8, $seeds = 'alphanum')
{
    // Possible seeds
    $seedings['alpha'] = 'abcdefghijklmnopqrstuvwqyz';
    $seedings['numeric'] = '0123456789';
    $seedings['alphanum'] = 'abcdefghijklmnopqrstuvwqyz0123456789';
    $seedings['hexidec'] = '0123456789abcdef';
    
    // Choose seed
    if (isset($seedings[$seeds]))
    {
        $seeds = $seedings[$seeds];
    }
    
    // Seed generator
    list($usec, $sec) = explode(' ', microtime());
    $seed = (float) $sec + ((float) $usec * 100000);
    mt_srand($seed);
    
    // Generate
    $str = '';
    $seeds_count = strlen($seeds);
    
    for ($i = 0; $length > $i; $i++)
    {
        $str .= $seeds{mt_rand(0, $seeds_count - 1)};
    }
    
    return $str;
}

 if(!isset($_POST['email'])) {
  die('No email provided.');
 }
 $email = $_POST['email'];
 echo "Email: $email <br />";

$con = connectToHost();
$db = selectDB($con);
  
$query = "SELECT * FROM Users WHERE Email='$email'";
echo $query;
$result = mysql_query($query);

$to = "";

while($row = mysql_fetch_array($result))
{
  $to = $row['Email'];
  $name = $row['DisplayName'];
  $user = $row['UID'];
}
  
  if($to == "") {
    die('No registered user appears to have the specified email.');
  }
  
  $newpass = str_rand(12,'alphanum');
  
  mysql_query("UPDATE Users SET Password='$newpass' WHERE UID='$user'");

mysql_close($con);
 
 
 $subject = "SWRM Password Reset";
 $body = "Hi $name,\n\nYou are receiving this email because someone requested that your password be reset.\n\nYour new password is: $newpass\n\nThanks,\nThe SWRM Team";
 $headers = "From: support@harkath.com";
 if (mail($to, $subject, $body, $headers)) {
   echo "<p>Message successfully sent!</p>";
  } else {
   echo "<p>Message delivery failed...</p>";
  }
 ?>