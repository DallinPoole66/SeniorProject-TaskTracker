<?php

# Load Main Model
require_once $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/models/main_model.php';
# Load Accounts Model
require_once $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/models/accounts_model.php';
/*
*   Tasks Model
*/


// Register a new user
function addTask($task_name, $task_details, $task_priority, $task_date, $task_time ){
    if (!isset($_SESSION['user']))
    {
        return false;
    }
    
    // Create a connection
    $db = tasktrackerConnect();
    $user_id = get_user_id();

    //SQL statement
    $sql = 'INSERT INTO tasks (user_id, task_name, task_details, task_priority, task_date, task_time, task_completed)
            VALUES (:user_id, :task_name, :task_details, :task_priority, :task_date, :task_time, :task_completed)';

    // Create a prepared statement
    $stmt = $db->prepare($sql);
    // Replace the placeholders in the SQL and tell the DB what kind of data it is.
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':task_name', $task_name, PDO::PARAM_STR);
    $stmt->bindValue(':task_details', $task_details, PDO::PARAM_STR);
    $stmt->bindValue(':task_priority', $task_priority);
    $stmt->bindValue(':task_date', $task_date);
    $stmt->bindValue(':task_time', $task_time, PDO::PARAM_INT);
    $stmt->bindValue(':task_completed', false, PDO::PARAM_BOOL);

    // Insert data
    try{
        $stmt->execute();

    } catch (PDOException $e) {
        return 0;
    }

    // Ask for how many rows changed
    $rowsChanged = $stmt->rowCount();
    // Close connection
    $stmt->closeCursor();
    
    echo $rowsChanged;
    // Shows if we were successful.
    return $rowsChanged;
}

// Gets a specific task
function get_task($task_id){
    if ( !isset($_SESSION['user'])){
        return NULL;
    }
    // Get the logged in user to prevent someone accessing someone else's tasks.
    $user_id = get_user_id();
    // Create a connection
    $db = tasktrackerConnect();


    //SQL statement
    $sql = 'SELECT * FROM `tasks` WHERE user_id = :user_id and task_id = :task_id';

    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $user_id);
    $stmt->bindValue(':task_id', $task_id);
 
    // Retrieve data
    try{
        $stmt->execute();

    } catch (PDOException $e) {
        // echo 'Connection failed: ' . $e->getMessage();
        return 0;
    }

    $task = $stmt->fetch();
    $stmt->closeCursor();

    return $task;
}

// Gets the tasks for the given user and month.
function get_tasks_for_month($month, $year){
    if ( !isset($_SESSION['user'])){
        return NULL;
    }
    // Get user
    $user_id = get_user_id();
    // Create a connection
    $db = tasktrackerConnect();

    //SQL statement
    $sql = 'SELECT * FROM `tasks` WHERE user_id = :user_id and YEAR(task_date) = :year and MONTH(task_date) = :month';

    // Create a prepared statement
    $stmt = $db->prepare($sql);
    // Replace the placeholders in the SQL and tell the DB what kind of data it is.
    $stmt->bindValue(':user_id',$user_id);
    $stmt->bindValue(':year',$year);
    $stmt->bindValue(':month',$month);
    
    // Retrieve data
    try{
        $stmt->execute();

    } catch (PDOException $e) {
        // echo 'Connection failed: ' . $e->getMessage();
        return 0;
    }

    $tasks = $stmt->fetchAll();
    $stmt->closeCursor();

    return $tasks;
}

// Get tasks before the passed in date that are still incomplete
function get_overdue_tasks($day, $month, $year){
    if ( !isset($_SESSION['user'])){
        return NULL;
    }
    // Get user
    $user_id = get_user_id();
    // Create a connection
    $db = tasktrackerConnect();

    
    $date = date('Y-m-d', strtotime($year."-".$month."-".$day));

    //SQL statement
    $sql = 'SELECT * FROM `tasks` WHERE user_id = :user_id and task_date < :date and task_completed = false';

    // Create a prepared statement
    $stmt = $db->prepare($sql);
    // Replace the placeholders in the SQL and tell the DB what kind of data it is.
    $stmt->bindValue(':user_id',$user_id);
    $stmt->bindValue(':date',$date);
    
    // Retrieve data
    try{
        $stmt->execute();

    } catch (PDOException $e) {
        // echo 'Connection failed: ' . $e->getMessage();
        return 0;
    }

    $tasks = $stmt->fetchAll();
    $stmt->closeCursor();

    return $tasks;
    
}

// Gets the tasks for a specific day and the logged in user.
function get_tasks_for_day($day, $month, $year){
    if ( !isset($_SESSION['user'])){
        return NULL;
    }
    // Get user
    $user_id = get_user_id();
    // Create a connection
    $db = tasktrackerConnect();

    //SQL statement
    $sql = 'SELECT * FROM `tasks` WHERE user_id = :user_id and YEAR(task_date) = :year and MONTH(task_date) = :month and DAY(task_date) = :day';

    // Create a prepared statement
    $stmt = $db->prepare($sql);
    // Replace the placeholders in the SQL and tell the DB what kind of data it is.
    $stmt->bindValue(':user_id',$user_id);
    $stmt->bindValue(':year',$year);
    $stmt->bindValue(':month',$month);
    $stmt->bindValue(':day',$day);
    
    // Retrieve data
    try{
        $stmt->execute();

    } catch (PDOException $e) {
        // echo 'Connection failed: ' . $e->getMessage();
        return 0;
    }

    $tasks = $stmt->fetchAll();
    $stmt->closeCursor();

    return $tasks;
}

// Get the tasks for the logged in user and the next 7 days.
function get_tasks_for_week($day, $month, $year){
    if ( !isset($_SESSION['user'])){
        return NULL;
    }
    // Get user
    $user_id = get_user_id();
    // Create a connection
    $db = tasktrackerConnect();

    $start_date = date('Y-m-d', strtotime($year."-".$month."-".$day));
    $end_date = date('Y-m-d', strtotime($year."-".$month."-".$day." + 7 days"));

    //SQL statement
    $sql = "SELECT * FROM `tasks` 
            WHERE user_id = :user_id 
            and task_date between :start_date and :end_date";

    // Create a prepared statement
    $stmt = $db->prepare($sql);
    // Replace the placeholders in the SQL and tell the DB what kind of data it is.
    $stmt->bindValue(':user_id',$user_id);
    $stmt->bindValue(':start_date',$start_date);
    $stmt->bindValue(':end_date',$end_date);
    
    // Retrieve data
    try{
        $stmt->execute();

    } catch (PDOException $e) {
        // echo 'Connection failed: ' . $e->getMessage();
        return 0;
    }

    $tasks = $stmt->fetchAll();
    $stmt->closeCursor();

    return $tasks;
}


// Complete the task given
function complete_task($task_id){
    if ( !isset($_SESSION['user'])){
        return NULL;
    }
    $user_id = get_user_id();
    // Create a connection
    $db = tasktrackerConnect();

    //SQL statement
    $sql = 'UPDATE `tasks` 
            SET task_completed = 1 
            WHERE user_id = :user_id and task_id = :task_id';

        
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $user_id);
    $stmt->bindValue(':task_id', $task_id);
 
    // Retrieve data
    try{
        $stmt->execute();

    } catch (PDOException $e) {
        // echo 'Connection failed: ' . $e->getMessage();
        return 0;
    }

    $outcome = $stmt->rowCount();
    $stmt->closeCursor();

    return $outcome;   

}

// Delete a task from the DB
function delete_task($task_id){
    if ( !isset($_SESSION['user'])){
        return NULL;
    }
    $user_id = get_user_id();
    // Create a connection
    $db = tasktrackerConnect();

    //SQL statement
    $sql = 'DELETE FROM `tasks` 
            WHERE user_id = :user_id and task_id = :task_id';

        
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $user_id);
    $stmt->bindValue(':task_id', $task_id);
 
    // Retrieve data
    try{
        $stmt->execute();

    } catch (PDOException $e) {
        // echo 'Connection failed: ' . $e->getMessage();
        return 0;
    }

    $outcome = $stmt->rowCount();
    $stmt->closeCursor();

    return $outcome;   
}

// Updates a given task
function editTask($task_id, $task_name, $task_details, $task_priority, $task_date, $task_time ){
    if (!isset($_SESSION['user']))
    {
        return false;
    }
    
    // Create a connection
    $db = tasktrackerConnect();
    $user_id = get_user_id();

    //SQL statement
    $sql = 'UPDATE tasks
            SET task_name = :task_name, task_details = :task_details, task_priority = :task_priority, task_date = :task_date,  task_time = :task_time
            WHERE user_id = :user_id and task_id = :task_id';

    // Create a prepared statement
    $stmt = $db->prepare($sql);
    // Replace the placeholders in the SQL and tell the DB what kind of data it is.
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':task_id', $task_id, PDO::PARAM_INT);
    $stmt->bindValue(':task_name', $task_name, PDO::PARAM_STR);
    $stmt->bindValue(':task_details', $task_details, PDO::PARAM_STR);
    $stmt->bindValue(':task_priority', $task_priority);
    $stmt->bindValue(':task_date', $task_date);
    $stmt->bindValue(':task_time', $task_time, PDO::PARAM_INT);

    // Insert data
    try{
        $stmt->execute();

    } catch (PDOException $e) {
        return 0;
    }

    // Ask for how many rows changed
    $rowsChanged = $stmt->rowCount();
    // Close connection
    $stmt->closeCursor();
    
    // Shows if we were successful.
    return $rowsChanged;
}


?>