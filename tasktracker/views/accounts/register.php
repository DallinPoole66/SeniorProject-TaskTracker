<html lang="en"  style=" background-image: url('/tasktracker/images/keyboard.jpg'); background-repeat: no-repeat;  background-size: cover; height: 100%;">
  <head>
    <title>Task Tracker</title>
    <meta charset="utf-8">
    <!-- Bootstrap 5 -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  </head>
  <body class="d-flex flex-column h-100" style="background-color: rgba(0,0,0,0);">
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/views/generic/navigation.php'; ?>
    <main  style="height: 100%; background-color: rgba(0, 0, 0, 0.6);">

        <div class="container" style="margin-top:160px;">
            <div class="row rounded-3 justify-content-center mx-auto border col-md-5 shadow-lg p-4 mb-4 bg-white"  > 
                <h1 class="display-6 pt-3" style="text-align: center">Start getting more done!</h1>
                
                <form method="post" action="/tasktracker/accounts/index.php" >
                    <div class="my-3">
                        <label for="user_login" class="form-label">Username:</label><br>
                        <input type="text" class="form-control" id="user_login" name="user_login" placeholder="Username" required
                    <?php
                        if(isset($user_login)){
                            echo 'value="'.$user_login.'"';
                        }
                    ?>>
                    </div>   

                    <div class="mb-3">
                        <label for="user_email" class="form-label">Email:</label><br>
                        <input type="text" class="form-control" id="user_email" name="user_email" placeholder="Email@Address.com" required <?php
                        if(isset($user_email)){
                            echo 'value="'.$user_email.'"';
                        }
                    ?>>
                    </div>                     
                    
                    <div class="mb-3">
                        <label for="user_password" class="form-label">Password:</label><br>
                        <input type="password" class="form-control" id="user_password" name="user_password" placeholder="Password" required
                        pattern="(?=^.{8,}$)(?=.*\d)(?=.*\W+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$">
                        <span id="password_hint">Passwords must be at least 8 characters long and contain at least 1 number, 1 capital letter and 1 special character.</span>
                    </div>                   
                    
                    <?php
                        if(isset($message)){
                            echo $message;
                        }
                    ?>
                    <br>
                    <div class="d-grid">
                        <input type="submit" name="submit" value="Register" class="btn btn-primary">
                        <input type="hidden" name="action" value="register">
                    </div>
                </form> 
            </div>
        </div>

    </main>
  <!-- Insert Footer -->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/tasktracker/views/generic/footer.php'; ?>
  </body>
</html>