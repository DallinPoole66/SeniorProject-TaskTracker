<?php
if (session_status() == PHP_SESSION_NONE){
    session_start();
}

// Get the current date to solve timezone issues.
if (isset($_REQUEST['date']))
{
    $_SESSION['date'] = date( "n-j", strtotime($_REQUEST['date']) );
}


?>