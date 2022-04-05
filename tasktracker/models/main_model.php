<?php

// Main Task Tracker Model.
/*
    Proxy connection to the tasktracker database.
*/

function tasktrackerConnect(){
    $server = 'localhost';
    $dbname = 'tasktracker';
    $username = 'iClient';
    $password = '/WISHajBHEZBktWf';
    $dsn = "mysql:host=$server;dbname=$dbname";
    $options = array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION );

    try {
        $link = new PDO( $dsn, $username, $password, $options );
        $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $link;

    } catch( PDOException $e) {
        header('Location: /tasktracker/index.php');
        exit;
    }

}
function getUsers(){
    // Establish connection to database.
    $db = tasktrackerConnect();

    // SQL query
    $sql = 'SELECT * FROM users';
    // Create a prepared statement
    $stmt = $db->prepare($sql);
    // Execute the prepared statement
    $stmt->execute();
    // Store results from query
    $users = $stmt->fetchAll();
    // Close interactions with database.
    $stmt->closeCursor();

    // Return the array of results.
    return $users;
}

function is_session_started()
{
    if ( php_sapi_name() !== 'cli' ) {
        if ( version_compare(phpversion(), '5.4.0', '>=') ) {
            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
        } else {
            return session_id() === '' ? FALSE : TRUE;
        }
    }
    return FALSE;
}

if ( is_session_started() === FALSE ) 
{
    session_start();
}

?>

