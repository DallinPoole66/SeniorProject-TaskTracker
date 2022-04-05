<?php
/*
    Main Tasks Controller
*/

# Load Models
require_once $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/models/main_model.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/objects/calendar.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/models/tasks_model.php';

// Check for session
if (!isset($_SESSION['user'])){
    session_start();   
}

// Select the previous month
function select_last_month(){
    // Deserialize our calendar object and have it select the previous month.
    $calendar = unserialize($_SESSION["calendar"]);
    $calendar->select_last_month();
    // Update the View
    echo $calendar->build_grid_display();
    // Store the modified calendar object back onto the session. 
    $_SESSION["calendar"] = serialize($calendar);
}

// Select the current month from our calendar object
function get_this_month(){    
    $calendar = unserialize($_SESSION["calendar"]);
    echo $calendar->build_grid_display();
}


function select_next_month(){
    // Deserialize the calendar from our session. Then select the next month.
    $calendar = unserialize($_SESSION["calendar"]);
    $calendar->select_next_month();
    // Update the view.
    echo $calendar->build_grid_display();
    // Store the object back on the session.
    $_SESSION["calendar"] = serialize($calendar);
}

// Get this month's break down of task stats.
function get_this_months_stats(){
    $calendar = unserialize($_SESSION["calendar"]);
    echo json_encode($calendar->get_stats());
}

// Send a view for the details of the task_id given.
function get_task_details($task_id){
    $task = get_task($task_id);
    $priority_txt = "Low";
    $priority_color = "primary";

    switch($task['task_priority']){
        case 'low':
            $priority_txt = "Low";
            $priority_color = "primary";
            break;

        case 'med':
            $priority_txt = "Med";
            $priority_color = "warning";
            break;

        case 'high':
            $priority_txt = "High";
            $priority_color = "danger";
            break;

        default:
            $priority_txt = "Low";
            $priority_color = "primary";
            break;
    }
    
    if ($task['task_completed']){
        $priority_txt = "Done";
        $priority_color = "success";
    }
    $task_time = '5 minutes';
    switch($task['task_time']){
        case 0:
            $task_time = '5 minutes';
            break;
        case 1:
            $task_time = '15 minutes';
            break;
        case 2:
            $task_time = '30 minutes';
            break;
        case 3:
            $task_time = '45 minutes';
            break;
        case 4:
            $task_time = '1 hour';
            break;
        case 5:
            $task_time = '1.5 hour';
            break;
        case 5:
            $task_time = '2 hours';
            break;

        default:
            $task_time = '5 minutes';
            break;
    }

    echo '<div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"> 
            <span class="badge rounded-pill bg-'.$priority_color.'">'.$priority_txt.'</span>
            '.$task['task_name'].'
        </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      <p class="text-black-50" style="margin-bottom: 0px;">'.$task_time.'</p>
        '.$task['task_details'].'
        <br>
        <br>
        
      </div>
    
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-primary"  onclick="copy_task('.$task['task_id'].')" data-bs-toggle="modal" data-bs-target="#taskCopy" >Copy</button>
        <button type="button" class="btn btn-primary"  onclick="edit_task('.$task['task_id'].')" data-bs-toggle="modal" data-bs-target="#taskEdit" >Edit</button>
        <form method="post" action="/tasktracker/index.php">
        <input type="submit" class="btn btn-success"   data-bs-dismiss="modal" value="Complete">
        <input type="hidden" name="action" value="complete">
        <input type="hidden" name="task_id" value='.$task['task_id'].'>
        </form>
        <form method="post" action="/tasktracker/index.php">
        <input type="submit" class="btn btn-danger"   data-bs-dismiss="modal" value="Delete">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="task_id" value='.$task['task_id'].'>
        </form>
      </div>

    </div>
  </div>';
}

// Update the Editing view upon request
if (isset($_REQUEST["edit"])){
    $task_id = $_REQUEST["edit"];
    $task = get_task($task_id);
    $priority_txt = "Low";
    $priority_color = "primary";

    switch($task['task_priority']){
        case 'low':
            $priority_txt = "Low";
            $priority_color = "primary";
            break;

        case 'med':
            $priority_txt = "Med";
            $priority_color = "warning";
            break;

        case 'high':
            $priority_txt = "High";
            $priority_color = "danger";
            break;

        default:
            $priority_txt = "Low";
            $priority_color = "primary";
            break;
    }
    
    if ($task['task_completed']){
        $priority_txt = "Done";
        $priority_color = "success";
    }
    $task_time = '5 minutes';
    switch($task['task_time']){
        case 0:
            $task_time = '5 minutes';
            break;
        case 1:
            $task_time = '15 minutes';
            break;
        case 2:
            $task_time = '30 minutes';
            break;
        case 3:
            $task_time = '45 minutes';
            break;
        case 4:
            $task_time = '1 hour';
            break;
        case 5:
            $task_time = '1.5 hour';
            break;
        case 5:
            $task_time = '2 hours';
            break;

        default:
            $task_time = '5 minutes';
            break;
    }

    include("../views/edit_task_view.php");
}


// Update the copy view when requested.
if (isset($_REQUEST["copy"])){
    $task_id = $_REQUEST["copy"];
    $task = get_task($task_id);
    $priority_txt = "Low";
    $priority_color = "primary";

    switch($task['task_priority']){
        case 'low':
            $priority_txt = "Low";
            $priority_color = "primary";
            break;

        case 'med':
            $priority_txt = "Med";
            $priority_color = "warning";
            break;

        case 'high':
            $priority_txt = "High";
            $priority_color = "danger";
            break;

        default:
            $priority_txt = "Low";
            $priority_color = "primary";
            break;
    }
    
    if ($task['task_completed']){
        $priority_txt = "Done";
        $priority_color = "success";
    }
    $task_time = '5 minutes';
    switch($task['task_time']){
        case 0:
            $task_time = '5 minutes';
            break;
        case 1:
            $task_time = '15 minutes';
            break;
        case 2:
            $task_time = '30 minutes';
            break;
        case 3:
            $task_time = '45 minutes';
            break;
        case 4:
            $task_time = '1 hour';
            break;
        case 5:
            $task_time = '1.5 hour';
            break;
        case 5:
            $task_time = '2 hours';
            break;

        default:
            $task_time = '5 minutes';
            break;
    }

    include("../views/copy_task_view.php");
}

// Retrieve stats
if (isset($_REQUEST['stats'])){
    get_this_months_stats();
}

// Handle the month selection requests.
if (isset($_REQUEST['m']))
{
    $m = $_REQUEST["m"];
    switch($m){
    case 0:
        get_this_month();
        break;
    case -1:
        select_last_month();
        break;
    case 1:
        select_next_month();
        break;
    default:
        get_this_month();
        break;
    }
}

// Retrieve the task details given.
if (isset($_REQUEST['t']))
{
    $t = $_REQUEST["t"];
    get_task_details($t);
}

// Mark a task complete
if (isset($_REQUEST['complete']))
{
    $task_id = $_REQUEST["complete"];
    complete_task($task_id);
}

// Delete a given task
if (isset($_REQUEST['delete'])){
    $task_id = filter_input(INPUT_GET, 'delete', FILTER_SANITIZE_NUMBER_INT);
    delete_task($task_id);
}


// Get the overdue task list
if (isset($_REQUEST['od'])){    
    $date = explode('-', $_SESSION['date']);
    $day = $date[1];
    $month = $date[0];
    $year = date('Y');
    $tasks = get_overdue_tasks($day, $month, $year);
    echo json_encode($tasks);


}

// Get the upcoming task list
if (isset($_REQUEST['d']))
{
    $d = $_REQUEST['d'];    
    $date = explode('-', $_SESSION['date']);
    $day = $date[1];
    $month = $date[0];
    $year = date('Y');

    $tasks =  get_tasks_for_week($day, $month, $year);
    echo json_encode($tasks);
 

}





?>