<?php
/*
    Our Primary Entry Point and Controller.
*/

require_once $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/objects/calendar.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/models/main_model.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/models/accounts_model.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/models/tasks_model.php';

// Check for a session
if (session_status() == PHP_SESSION_NONE){
    session_start();
}


// Get the action, if any
$action = filter_input(INPUT_POST, 'action' );
if ($action == NULL ) {
    $action = filter_input(INPUT_GET, 'action' );
}


// Control flow from action
switch ($action){
    case 'register':
        // Filter Data and store
        $user_login = trim(filter_input(INPUT_POST, 'user_login'));
        $user_password = trim(filter_input(INPUT_POST, 'user_password'));
        $user_email = trim(filter_input(INPUT_POST, 'user_email'));

        $user_email = checkEmail($user_email);
        $checkPassword = checkPassword($user_password);

        // Check for missing data
        if(empty($user_login) || empty($user_email) || empty($checkPassword) ){
            $message = '<p class="text-danger">Please check all fields!</p>';
            include $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/views/accounts/register.php';  
            exit;
        }
        // Send Data to the model
        $regOutcome = regUser($user_login, $user_password, $user_email);

        // Check for success
        if($regOutcome == 1){
            $message = '<p class="text-success">Thanks for registering! Please use your email and password to login.</p>';
            include $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/views/accounts/login.php';  
        }else{
            $message = '</p class="text-warning">Sorry, but the registration failed. Please try again.</p>';
            include $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/views/accounts/register.php';  
        }
        break;

    // Go to login Page
    case 'login':
        include $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/views/accounts/login.php';  
        break;

    // Try to sign in with given input.
    case 'signin':
        
        // Filter Data and store
        $user_login = trim(filter_input(INPUT_POST, 'user_login'));
        $user_password = trim(filter_input(INPUT_POST, 'user_password'));


        // Check for missing data
        if(empty($user_login) ||  empty($user_password) ){
            $message = '<p class="text-danger">Please provide information for all empty form fields.</p>';
            include $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/views/accounts/login.php';  
            exit;
        }
        else{           
            // Check if we logged in 
            $loginResult = loginUser($user_login, $user_password);
            if ($loginResult){      
                header("Location: ../index.php");
                break;
            }else{
                // Handle incorrect login info gracefully.
                $message = '<p>Invalid login information!</p>';
                include $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/views/accounts/login.php';  
                exit;
            }
        }
        break;

    // Logout and destroy session.
    case 'logout':
        
        if(isset($_SESSION['user'])){
            session_destroy();
        }
        header('Location: /tasktracker/index.php');
        break;

    // Show our sign up page.
    case 'signup':
        include $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/views/accounts/register.php';  
        break;
    
        // Default to the sign up page.
    default:
        include $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/views/accounts/register.php';  
    
    break;
}


// Validate that the given email is in fact an email address.
function checkEmail($email){
    $valEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    return $valEmail;
}

// Validate that the given password meets our minimum specs.
function checkPassword($password){
    $pattern = '/^(?=.*[[:digit:]])(?=.*[[:punct:]\s])(?=.*[A-Z])(?=.*[a-z])(?:.{8,})$/';
    return preg_match($pattern, $password);
}


?>