<?php
/*
    Calendar Month
    Provides a class to serve as a container for days. Has functions for creating the needed views.
    Provides stast for the month's tasks.
*/
require_once $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/objects/calendar_day.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/models/tasks_model.php';

// Filters for our task stats.
function filter_low($var){
    return $var['task_priority'] == 'low' and $var['task_completed'] == false;
}

function filter_med($var){
    return $var['task_priority'] == 'med' and $var['task_completed'] == false;
}

function filter_high($var){
    return $var['task_priority'] == 'high' and $var['task_completed'] == false;
}

function filter_done($var){
    return $var['task_completed'];
}

# Class to represent a month of tasks and days
class CalendarMonth {
    public $date;
    public $offset;
    public $days;
    public $tasks;

    # Constructor
    function __construct($date)
    {

        # First row offset
        $this->offset = 0;
        # Date of this month
        $this->date = $date;
        # Array of CalendarDays        
        $this->days = [];
        $num_days_in_month = date("t", $date);

        // Gets tasks for this user and this month.
        if (isset($_SESSION['user'])){
            $this->tasks = get_tasks_for_month(date("m", $this->date),  date("Y",$this->date));            
        }
        // Finds the offset for this month from Sunday.
        $this->offset = strtotime('first day of this month', $date);
        $this->offset =  (date('N', $this->offset)) % 7;

        # Add calendar days for each day in this month
        for ($i = 0; $i < $num_days_in_month; $i++){
            $date_for_day = mktime(12,0,0 , date("m", $this->date), $i + 1, date("Y",$this->date)) ;
            $this->days[] = new CalendarDay($date_for_day, $this);
        }
        foreach($this->tasks as $task){
            $day = date('d', strtotime($task["task_date"]));
            $this->days[$day - 1]->add_task($task);
        }
    }

    // Get an array of the tasks stats
    function get_stats(){
        $stats = array(
            "total" => count($this->tasks)
            ,"done" => count(array_filter($this->tasks, "filter_done"))
            ,"high" => count(array_filter($this->tasks, "filter_high"))
            ,"med"  => count(array_filter($this->tasks, "filter_med"))
            ,"low" => count(array_filter($this->tasks, "filter_low"))
        );
        return $stats;
    }

    function get_date(){
        return $this->date;
    }

    // Build a view for this month.
    function build_output()
    {
        
        $cur_day = 0;   
        foreach( $this->days as $day_object){
            if($cur_day % 7 == 0){
                if ($cur_day > 0){

                    echo '</div>';
                }
                echo '<div class="row" style="min-height: 175px; padding-right: 16px; padding-left: 16px;">';
            }
             
            $day_object->build_calendar_square();
            $cur_day++;
        }
        echo '</div>';


    }


}

?>