<nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href=<?php echo $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/index.php'; ?>>Task Tracker</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    </form>
    <div class="collapse navbar-collapse" id="mynavbar">
      <ul class="navbar-nav me-auto">
        <?php 
        
        if(isset($_SESSION['user'])){
          echo '
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="modal" data-bs-target="#addTask">Add Task</a>
          </li>';
        }
        ?>
      </ul>
      <div class="d-flex">
        <?php 
                
        if(isset($_SESSION['user'])){
          echo '<a class="btn btn-primary" role="button" type="button" href="/tasktracker/accounts/index.php?action=logout" >Logout</a>';
        }
        else{
          echo '<a class="btn btn-primary" role="button" type="button" href="/tasktracker/accounts/index.php?action=login" >Login</a>';
        }
        ?>
      </div>
    </div>
  </div>
</nav>