<?php
/*
    Holds functions for teh tasks controller.
*/


# Load Models
require_once $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/models/main_model.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/models/tasks_model.php';

# Load Calendar classes.
require_once $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/objects/calendar.php';



// Gets tasks on the passed in date
function get_tasks_array_day($date){

}





// Gets task for the 7 days starting with the date passed in.
function get_tasks_array_week($date){

    // Return nothing if the user isn't logged in.
    if ( !isset($$_SESSION['use'] ) )
    {
        return;
    }

    



}


?>