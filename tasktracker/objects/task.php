<?php

class task{
    public $task_id;
    public $task_name;
    public $task_priority;
    public $task_details;
    public $task_date;
    public $task_time;
    public $task_complete;

    function display_square_listing(){
        $priority_text = 'Low';
        $tag = 'primary';
        switch ($this->task_priority){
            case 0:
                $priority_text = 'Low';  
                $tag = 'primary';              
                break;

            case 1:
                $priority_text = 'Medium';
                $tag = 'warning';
                break;

            case 2:
                $priority_text = 'High';
                $tag = 'danger';
                break;

            default:
                $priority_text = 'Low';
                $tag = 'primary';
        }

        if ($this->task_complete){
            $priority_text = 'Done';
            $tag = 'success';
        }

        echo 
        '<div id='.$this->task_id.' data-bs-toggle="modal" data-bs-target="#myModal">
        <span class="badge rounded-pill bg-' . $tag . '">' . $priority_text . '</span>'
        . $this->task_name
        . '</div>';
    }
}

?>