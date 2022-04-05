<?php
/*
    Calendar Day stores a specific date and the tasks associated with it.
    Provides fucntions for providing a view as needed.
*/
class CalendarDay {
    public $date;
    public $calendar_month;
    public $tasks;

    function __construct($date, $calendar_month)
    {
        $this->date = $date;
        $this->calendar_month = $calendar_month;
        $this->tasks = [];
    }

    // Get the day of month this is.
    function get_day_number(){
        return date("d",$this->date) ;
    }

    // Add a task to the task list
    function add_task($task){
        $this->tasks[] = $task;
    }

    // Builds out a view for each task.
    function build_tasks_list(){
        foreach($this->tasks as $task){

            $priority_txt = "Low";
            $priority_color = "primary";
            $txt_color = '';
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
                $txt_color = 'class = "text-black-50"';
            }

            echo '<div id="'.$task['task_id'].'_square" '.$txt_color.' onclick = "event.stopPropagation(); select_task('.$task['task_id'].')" data-bs-toggle="modal" data-bs-target="#taskDetails">
                <span  class="badge rounded-pill bg-'.$priority_color.'">'.$priority_txt.'</span> '. $task['task_name'] . '</div>';
            
        }
    }

    // Builds the square this day uses for the view.
    function build_calendar_square(){
        $border_color = '';
        if (date("n-j",$this->date) == $_SESSION['date']){
            $border_color = 'border-primary ';
        }
        // Get a JS friendly timestamp
        $stamp =date("Y-m-d", $this->date);

        // If its sunday add a css class to help with mobile visibility
        if (date("w",$this->date) == 0)
        {
            echo 
            '<div onclick =add_task_date("'.$stamp.'") class="col-md border border '.$border_color.' day pb-3 firstday">
                <p class="text-black-50" style="padding: 0px; margin: 0px;">'. date("j",$this->date) . '</p>';

        }else {
            echo 
            '<div onclick =add_task_date("'.$stamp.'") class="col-md border border '.$border_color.' day pb-3">
                <p class="text-black-50" style="padding: 0px; margin: 0px;">'. date("j",$this->date) . '</p>';

        }
        
        $this->build_tasks_list();

        echo '</div>';
    }

    // Like the build calendar square functino but fdor when this day is in a non selected month.
    function build_calendar_square_other_month(){
        $stamp =date("Y-m-d", $this->date);
        if (date("w",$this->date) == 0)
        {
            echo 
            '<div onclick =add_task_date("'.$stamp.'") class="col-md border bg-light day pb-3">
                <p class="text-black-50" style="padding: 0px; margin: 0px;">'. date("d",$this->date) . '</p>';

        }else {
            echo 
            '<div onclick =add_task_date("'.$stamp.'") class="col-md border bg-light day pb-3">
                <p class="text-black-50" style="padding: 0px; margin: 0px;">'. date("d",$this->date) . '</p>';

        }
        
        $this->build_tasks_list();

        echo '</div>';
    }

}
?>