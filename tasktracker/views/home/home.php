<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/objects/calendar.php';

  $calendar = make_calendar();
  $_SESSION["calendar"] = serialize($calendar);
?><html lang="en">
  <head>
    <title>Task Tracker</title>
    <meta charset="utf-8">
    <!-- Bootstrap 5 -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script  src="javascript/tasks_list.js"></script>
    <script src="javascript/home.js"></script>
    
    <link rel="stylesheet" href="views/css/main.css">

  </head>
  <body>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/views/generic/navigation.php'; ?>
    
    <main style="margin-top:80px">


<div class="container-fluid">         
    <div class="row">
      <div class="container col-xxl-3" id="sidebar">
        
      <div class="border border-bottom-0" id="overduetasks" >
            
            </div> 
        <div class="border border-top-0" id="todaystasks" >
        
        </div> 
        <div class="border border-top-0" id="canvas">
          <h2>This Month's Stats:</h2>
          <canvas  id="statsCanvas" width="400" height="400">              
          </canvas> 
        </div>
      </div>

    <div class="col-xl pb-3" id="this_month">
    </div>
    </div>


</div> 

<div class="modal" id="taskDetails">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"> 
            <span class="badge rounded-pill bg-primary">Low</span>
            Email Client
        </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      <p class="text-black-50" style="margin-bottom: 0px;">30 min. February 05 at 17:15</p>
        Email John about the changes to the color palette and to get approval for the wireframes.
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Complete</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<div class="modal" id="addTask">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"> 
            Add Task
        </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form method="post" action="/tasktracker/index.php">
            <div class="mb-3 mt-3">
                <label for="task_name" class="form-label">Task Name:</label>
                <input type="task_name" class="form-control" id="task_name" placeholder="Task Name" name="task_name">
            </div>
            
            <label for="task_priority" class="form-label">Priority:</label>
            <select class="form-select" id="task_priority" name="task_priority"  >
                <option value = '1'>Low</option>
                <option value = '2'>Medium</option>
                <option value = '3'>High</option>
            </select> 
            
            <div class="mb-3 mt-3">
                <label for="task_date" class="form-label">Date:</label>
                <input class="form-control"  type="date" id="task_date" name="task_date" >
            </div>
            
            <div class="mb-3 mt-3">
                <label for="task_time" class="form-label">Estimated Time:</label> 
                <select class="form-select" id="task_time" name="task_time">
                    <option value = '0'>5 minutes</option>
                    <option value = '1'>15 minutes</option>
                    <option value = '2'>30 minutes</option>
                    <option value = '3'>45 minutes</option>
                    <option value = '4'>1 hour</option>
                    <option value = '5'>1 hour 30 min.</option>
                    <option value = '6'>2 hours</option>
                </select> 
            </div>
                
            <label for="task_details">Details:</label>
            <textarea class="form-control" rows="5" id="task_details" name="task_details"></textarea> 
            
            
            <div class="d-grid mb-3 mt-3">
                <input type="submit" name="add_task" value="Add Task" class="btn btn-primary">
                <input type="hidden" name="action" value="add">
            </div>
         </form> 
      </div>


    </div>
  </div>
</div>

<div class="modal" id="taskEdit" >
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"> 
            Edit Task
        </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form method="post" action="/tasktracker/index.php">
            <div class="mb-3 mt-3">
                <label for="task_name" class="form-label">Task Name:</label>
                <input type="task_name" class="form-control" id="task_name" placeholder="Task Name" name="task_name">
            </div>
            
            <label for="task_priority" class="form-label">Priority:</label>
            <select class="form-select" id="task_priority" name="task_priority"  >
                <option value = '1'>Low</option>
                <option value = '2'>Medium</option>
                <option value = '3'>High</option>
            </select> 
            
            <div class="mb-3 mt-3">
                <label for="task_date" class="form-label">Date:</label>
                <input class="form-control"  type="date" id="task_date" name="task_date" >
            </div>
            
            <div class="mb-3 mt-3">
                <label for="task_time" class="form-label">Estimated Time:</label> 
                <select class="form-select" id="task_time" name="task_time">
                    <option value = '0'>5 minutes</option>
                    <option value = '1'>15 minutes</option>
                    <option value = '2'>30 minutes</option>
                    <option value = '3'>45 minutes</option>
                    <option value = '4'>1 hour</option>
                    <option value = '5'>1 hour 30 min.</option>
                    <option value = '6'>2 hours</option>
                </select> 
            </div>
                
            <label for="task_details">Details:</label>
            <textarea class="form-control" rows="5" id="task_details" name="task_details"></textarea> 
            
            
            <div class="d-grid mb-3 mt-3">
                <input type="submit" name="add_task" value="Add Task" class="btn btn-primary">
                <input type="hidden" name="action" value="edit">
            </div>
         </form> 
      </div>


    </div>
  </div>

</div>
<div class="modal" id="taskCopy">
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

      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Copy</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

</main>    


  <!-- Insert Footer -->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/views/generic/footer.php'; ?>

</body>


</html>