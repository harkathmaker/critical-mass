<?php
require_once("error.php");
require_once("connection.php");

// Normal user section - Not logged ------
        if(isset($_REQUEST['email']) && isset($_REQUEST['password']))
            {
                // Section for logging process -----------
                $email = trim($_REQUEST['email']);
                $pass = trim($_REQUEST['password']);
                
                $validUser = FALSE;
				$user = -1;

                $con = connectToHost();
				$db_selected = selectDB($con);
                  
                $result = mysql_query("SELECT * FROM Users WHERE Email='$email'");
				
				mysql_close($con);

                while($row = mysql_fetch_array($result))
                  {
                  if($row['Email'] == $email && $row['Password'] == $pass) {
                    $validUser = TRUE;
					$user = $row['UID'];
                    break;
                  }
                }
                  
                
                
                if($validUser)
                    {
                        // Successful login ------------------
                        session_start();
                        // Setting Session
                        $_SESSION['user'] = $user;
                       
                        // Redirecting to the logged page.
                        header("Location: events.php");
                    }
                else
                    {
                        // Wrong username or Password. Show error here.
                       echo "Invalid username or password!";
                    }
               
            }
?> 