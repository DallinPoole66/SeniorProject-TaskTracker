<div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"> 
            Copy Task
        </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form method="post" action="/tasktracker/index.php">
            <div class="mb-3 mt-3">
                <label for="task_name" class="form-label">Task Name:</label>
                <input type="task_name" class="form-control" id="task_name" placeholder="Task Name" name="task_name"
                <?php 
                    if(isset($task)){
                        echo "value='".$task['task_name']."'";
                    }
                ?>
                >
            </div>
            
            <label for="task_priority" class="form-label">Priority:</label>
            <select class="form-select" id="task_priority" name="task_priority"  
                >
                <option value = '1' <?php 
                    if(isset($task)){
                        if($task['task_priority'] == 'low')
                        echo "selected";
                    }
                ?>>Low</option>
                <option value = '2'<?php 
                    if(isset($task)){
                        if($task['task_priority'] == 'med')
                        echo "selected";
                    }
                ?>>Medium</option>
                <option value = '3' <?php 
                    if(isset($task)){
                        if($task['task_priority'] == 'high')
                        echo "selected";
                    }
                ?>>High</option>
            </select> 
            
            <div class="mb-3 mt-3">
                <label for="task_date" class="form-label">Date:</label>
                <input class="form-control"  type="date" id="task_date" name="task_date"<?php 
                    if(isset($task)){
                        echo "value =".$task['task_date'];
                    }
                ?>>
            </div>
            
            <div class="mb-3 mt-3">
                <label for="task_time" class="form-label">Estimated Time:</label> 
                <select class="form-select" id="task_time" name="task_time">
                    <option value = '0'<?php 
                    if(isset($task)){
                        if($task['task_time'] == 0)
                        echo "selected";
                    }
                ?>>5 minutes</option>
                    <option value = '1'<?php 
                    if(isset($task)){
                        if($task['task_time'] == 1)
                        echo "selected";
                    }
                ?>>15 minutes</option>
                    <option value = '2'<?php 
                    if(isset($task)){
                        if($task['task_time'] == 2)
                        echo "selected";
                    }
                ?>>30 minutes</option>
                    <option value = '3'<?php 
                    if(isset($task)){
                        if($task['task_time'] == 3)
                        echo "selected";
                    }
                ?>>45 minutes</option>
                    <option value = '4'<?php 
                    if(isset($task)){
                        if($task['task_time'] == 4)
                        echo "selected";
                    }
                ?>>1 hour</option>
                    <option value = '5'<?php 
                    if(isset($task)){
                        if($task['task_time'] == 5)
                        echo "selected";
                    }
                ?>>1 hour 30 min.</option>
                    <option value = '6'<?php 
                    if(isset($task)){
                        if($task['task_time'] == 6)
                        echo "selected";
                    }
                ?>>2 hours</option>
                </select> 
            </div>
                
            <label for="task_details">Details:</label>
            <textarea class="form-control" rows="5" id="task_details" name="task_details"><?php 
                    if(isset($task)){
                        echo $task['task_details'];
                    }
                ?></textarea> 
            
            
            <div class="d-grid mb-3 mt-3">
                <input type="submit" name="add_task" value="Copy Task" class="btn btn-primary">
                <input type="hidden" name="action" value="submitcopy">
                <input type="hidden" name="task_id"<?php 
                    if(isset($task)){
                        echo "value= ".$task['task_id'];
                    }
                ?> >
            </div>
         </form> 
      </div>


    </div>
  </div>