<?php
/*
    Calendar Object to hold montsh with tasks and make providing the needed views easier.
*/
require_once $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/objects/calendar_month.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/models/tasks_model.php';

/*
    A Calendar holds 3 months at a time. Last, current and next month.
*/
class Calendar{
    public $last_month;
    public $this_month;
    public $next_month;

    # Constructor
    function __construct($date)
    {
        

        $this->this_month = new CalendarMonth($date);

        $this->last_month = $this->build_last_month($date);
        $this->next_month = $this->build_next_month($date);
    }

    // Get the stats for the current month.
    function get_stats(){
        return $this->this_month->get_stats();
    }

    // Build out the information for the previous month.
    function build_last_month($date){
        $cur_month = date('m', $date);
        $cur_year = date('Y', $date);
        

        $last_month_number = $cur_month - 1;
        $last_year = $cur_year;

        // Wrap around the new years
        if ($last_month_number < 1){
            $last_month_number = 12;
            $last_year -= 1;
        } 
        

        $last_date =  mktime(12,0,0 , $last_month_number, 1, $last_year);
        return new CalendarMonth( $last_date);

    }

    // Build out the next month.
    function build_next_month($date){
        $cur_month = date('m', $date);
        $cur_year = date('Y', $date);
        
        $next_month_number = $cur_month + 1;
        $next_year = $cur_year;

        // Wrap around new years
        if ($next_month_number > 12){
            $next_month_number = 1;
            $next_year += 1;
        } 
        $next_date =  mktime(12,0,0 , $next_month_number, 1, $next_year);
        return new CalendarMonth($next_date);

    }


    // Move the current month back one and update.
    function select_last_month(){
        $this->next_month = $this->this_month;
        $this->this_month = $this->last_month;

        $this->last_month = $this->build_last_month($this->this_month->get_date());
    }

    // Move the current month forward one and update.
    function select_next_month(){
        $this->last_month = $this->this_month;
        $this->this_month = $this->next_month;

        $this->next_month = $this->build_next_month($this->this_month->get_date());
    }

    // Build the square calendar view.
    function build_grid_display(){
        $days = [];
        // Figure out how many days from the last month we need to show.
        for($i = $this->this_month->offset; $i > 0; $i--){
            $days[] = $this->last_month->days[count($this->last_month->days) - $i];
        }
        // Populate with this month's days
        foreach($this->this_month->days as $day_object){
            $days[] = $day_object;
        }

        // Figure out how many days we need from next month to pad the end.
        $end_offset = 7 - count($days) % 7;

        // Correct if we have a full week of next month.
        if (count($days) % 7 == 0){
            $end_offset = 0;
        }
        
        // Get tnext months days and add them to our array.
        for($i = 0; $i < $end_offset; $i++){
            $days[] = $this->next_month->days[$i];
        }

        // Output the month header.
        echo '<h1 id="month_name" class="display-6"><button class="btn btn-lg" type="button" onclick="select_last_month()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
      </svg></button>' . date("F", $this->this_month->date) . '<button class="btn btn-lg" type="button" onclick="select_next_month()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
      </svg></button></h1>
        <hr style="margin-top: 8px;"></hr>';

        // Make a row for each week starting on Sunday.
        $day_count = 0;
        foreach($days as $day_object){
            // If it's Sunday End the old week's row and start a new one.
            if($day_count % 7 == 0){
                if ($day_count > 0){

                    echo '</div>';
                }
                echo '<div class="row week" style="min-height: 150px; padding-right: 16px; padding-left: 16px;">';
            }
            // Build a square for days not in the current month.
            if ($day_object->get_day_number() > $day_count || $day_object->get_day_number() < $day_count - $this->this_month->offset){
                $day_object->build_calendar_square_other_month();
                
            }else{
                // Build a square for this month's days
                $day_object->build_calendar_square();

            }
            $day_count++;
        }

        echo '</div>';



    }



}

// Make a calendar from today.
function make_calendar(){
    $date = mktime(12,0,0 , date("m"), date("d"), date("Y")) ;

    $calendar = new Calendar($date);
    return $calendar;
}

?>