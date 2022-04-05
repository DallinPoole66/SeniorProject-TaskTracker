<?php

/*
*   Accounts Model
*/


// Register a new user
function regUser($user_login, $user_password, $user_email ){
    // Create a connection
    $db = tasktrackerConnect();

    $user_hashed_password = password_hash($user_password, PASSWORD_DEFAULT);


    //SQL statement
    $sql = 'INSERT INTO users (user_login, user_hashed_password, user_email)
            VALUES (:user_login, :user_hashed_password , :user_email)';
    // Create a prepared statement
    $stmt = $db->prepare($sql);
    // Replace the placeholders in the SQL and tell the DB what kind of data it is.
    $stmt->bindValue(':user_login', $user_login, PDO::PARAM_STR);
    $stmt->bindValue(':user_hashed_password', $user_hashed_password, PDO::PARAM_STR);
    $stmt->bindValue(':user_email', $user_email, PDO::PARAM_STR);
    // Insert data
    try{
        $stmt->execute();

    } catch (PDOException $e) {
        // echo 'Connection failed: ' . $e->getMessage();
        return 0;
    }

    // Ask for how many rows changed
    $rowsChanged = $stmt->rowCount();
    // Close connection
    $stmt->closeCursor();

    // Shows if we were successful.
    return $rowsChanged;
}

function get_user_id(){
    if ( !isset($_SESSION['user'])){
        return 0;
    }
    // Create a connection
    $db = tasktrackerConnect();

    //SQL statement
    $sql = 'SELECT user_id FROM users WHERE user_login = :user_login';

    // Create a prepared statement
    $stmt = $db->prepare($sql);
    // Replace the placeholders in the SQL and tell the DB what kind of data it is.
    $stmt->bindValue(':user_login',$_SESSION['user'], PDO::PARAM_STR);
    
    // Retrieve data
    try{
        $stmt->execute();

    } catch (PDOException $e) {
        // echo 'Connection failed: ' . $e->getMessage();
        return 0;
    }

    $user_id = $stmt->fetch()['user_id'];
    $stmt->closeCursor();

    return $user_id;


}

// Login as user
function loginUser($user_login, $user_password){
    // Create a connection
    $db = tasktrackerConnect();
    $user_hashed_password = password_hash($user_password, PASSWORD_DEFAULT);
    //SQL statement
    $sql = 'SELECT user_hashed_password FROM users WHERE user_login = :user_login';

    // Create a prepared statement
    $stmt = $db->prepare($sql);
    // Replace the placeholders in the SQL and tell the DB what kind of data it is.
    $stmt->bindValue(':user_login', $user_login, PDO::PARAM_STR);
    
    // Insert data
    try{
        $stmt->execute();

    } catch (PDOException $e) {
        // echo 'Connection failed: ' . $e->getMessage();
        return 0;
    }

    // Ask for how many rows changed
    $rows = $stmt->rowCount();
    if ($rows == 0){
        // Close connection
        $stmt->closeCursor();
        return false;
    }
    $user_hashed_password = $stmt->fetch()['user_hashed_password'];
    
    // Close connection
    $stmt->closeCursor();

    // Verify that the entered password matches what we have stored
    if ( password_verify($user_password, $user_hashed_password) )
    {        
        $_SESSION['user'] = $user_login;
        $_SESSION['user_password'] = $user_password;

        return true; 
    }

    return false;
}

?>