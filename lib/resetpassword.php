<?php ob_start(); ?>
<?php
    session_start();
    require 'connect.php';
?>
<!doctype html>
<html lang="en">

<head>
  <title>CarPool Website</title>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.2.1 -->

    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <!-- Google Popins Fonts -->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <!-- jQuery Library CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
    
</head>

<body>
  <header>
    <!-- place navbar here -->
    <nav class="navbar navbar-expand-sm navbar-light bg-light fixed-top">
          <div class="container">
            <a class="navbar-brand" href="/"><img src="../img/logocar.png" alt="CarPool" class="rounded-pill" style="height: 30px;width:80px"></a>
            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavId">
                <ul class="navbar-nav me-auto mt-2 mt-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php" aria-current="page">Search <span class="visually-hidden">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Help</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact Us</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li>
                            <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
                        </li>
                    </ul>
                </div>
            </div>
      </div>
    </nav>  
  </header>
  <main>
    <div class="container">
        <div class="row justify-content-center align-items-center title">
            <div class="col-lg-10 col-sm-5 mt-5">
                <div class="mt-5 text-center">
                    <h1 class="text-white">Reset Password!</h1>
                    <div id="resultmessage"></div>
                    <?php

                        //If user_id or key is missing
                        if(!isset($_GET['user_id']) || !isset($_GET['key'])){
                            echo '<div class="alert alert-danger">There was an error. Please click on the link you received by email.</div>'; exit;
                        }
                      
                        $user_id = $_GET['user_id'];
                        $key = $_GET['key'];
                        $time = time() - 86400;

                        //Prepare variables for the query
                        $user_id = mysqli_real_escape_string($con, $user_id);
                        $key = mysqli_real_escape_string($con, $key);

                        //Run Query: Check combination of user_id & key exists and less than 24h old
                        $sql = "SELECT user_id FROM forgotpassword WHERE rkey='$key' AND user_id='$user_id' AND time > '$time' AND status='pending'";
                        $result = mysqli_query($con, $sql);
                        if(!$result){
                            echo '<div class="alert alert-danger">Error running the query!</div>'; exit;
                        }

                        //If combination does not exist
                        //show an error message
                        $count = mysqli_num_rows($result);
                        if($count !== 1){
                            echo '<div class="alert alert-danger">Please try again.</div>';
                            exit;
                        }

                        //print reset password form with hidden user_id and key fields
                        echo "
                        <form method=post id='passwordreset' >
                        <input type='hidden' name='key' value=$key>
                        <input type='hidden' name='user_id' value=$user_id>
                        <div class='mb-2'>
                            
                            <input type='password' name='password' id='password' placeholder='Enter Password' class='form-control'>
                        </div>
                        <div class='mb-2'>
                            
                            <input type='password' name='password2' id='password2' placeholder='Re-enter Password' class='form-control'>
                        </div>
                        <button type='submit' name='resetpassword' class='btn btn-success btn-lg mb-5'>Reset Password</button>


                        </form>
                        ";
                ?>
                    
                </div>
            </div>
        </div>
        
        
  </main>
 
  <!-- place footer here -->
    <footer class="bg-light text-center text-lg-start mt-auto" id="footer">
        <!-- copyrights -->
        <div class="text-center p-3 footer" >
            <p>Hansa Innovations Copyright &copy; 2020-<?php $today=date("Y"); echo $today;  ?>.
            </p>
        </div>
    </footer>

    <!-- Spinner -->
    <div id="spinner">
        <img src="../img/ajax-loader.gif " width="64" height="64" alt="ajax">
        <br>Loading....
    </div>

  <!-- Bootstrap JavaScript Libraries -->
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/script.js"></script>
 
</body>

</html>
<?php ob_flush(); ?>