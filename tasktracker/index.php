<?php
# Load Main Model
require_once $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/objects/calendar.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/models/main_model.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/models/accounts_model.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/models/tasks_model.php';

// Check for a session.
if (session_status() == PHP_SESSION_NONE){
    session_start();
}

// Get the action the user requested.
$action = filter_input(INPUT_POST, 'action' );
if ($action == NULL ) {
    $action = filter_input(INPUT_GET, 'action' );
}


// Direct the user to the correct page and handle any action passed to us.
if ( isset($_SESSION['user']) ){
   
    switch ($action){

        // Delete a task
        case 'delete';
            $task_id = filter_input(INPUT_POST, 'task_id', FILTER_SANITIZE_NUMBER_INT);
           
            $results = delete_task($task_id);
            include $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/views/home/home.php';           
            break;

        // Mark a task as complete
        case 'complete';
            $task_id = filter_input(INPUT_POST, 'task_id', FILTER_SANITIZE_NUMBER_INT);
           
            $results = complete_task($task_id);
            include $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/views/home/home.php';           
            break;

        // Add a new task
        case 'add': 
            // Filter Data and store
            $task_name = filter_input(INPUT_POST, 'task_name');
            $task_details = filter_input(INPUT_POST, 'task_details');
            $task_date = filter_input(INPUT_POST, 'task_date');
            $task_priority = filter_input(INPUT_POST, 'task_priority');
            $task_time = filter_input(INPUT_POST, 'task_time');
            
            // Check for missing data
            if(!empty($task_name) || !empty($task_details) || !empty($task_date) || !empty($task_priority) || !empty($task_time)){
                // Add task
                addTask($task_name, $task_details, $task_priority, $task_date, $task_time);
            }
            include $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/views/home/home.php';
            break;
        
        // Update a task 
        case 'submitedit':
            // Filter Data and store
            $task_id = filter_input(INPUT_POST, 'task_id');
            $task_name = filter_input(INPUT_POST, 'task_name');
            $task_details = filter_input(INPUT_POST, 'task_details');
            $task_date = filter_input(INPUT_POST, 'task_date');
            $task_priority = filter_input(INPUT_POST, 'task_priority');
            $task_time = filter_input(INPUT_POST, 'task_time');
            
            $_SESSION["results"] = editTask($task_id, $task_name, $task_details, $task_priority, $task_date, $task_time);
            
            include $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/views/home/home.php';
            break;
        
        // Duplicate the passed in task and allow for the user to edit at the same time.
        case 'submitcopy':
                // Filter Data and store
                $task_name = filter_input(INPUT_POST, 'task_name');
                $task_details = filter_input(INPUT_POST, 'task_details');
                $task_date = filter_input(INPUT_POST, 'task_date');
                $task_priority = filter_input(INPUT_POST, 'task_priority');
                $task_time = filter_input(INPUT_POST, 'task_time');
                
                $_SESSION["results"] = addTask($task_name, $task_details, $task_priority, $task_date, $task_time);
                
                include $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/views/home/home.php';
                break;
        
        default:
            include $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/views/home/home.php';
    }
}
else{
    // If the user isn't logged in we send them to the login page
    include $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/views/accounts/login.php';

}



?>