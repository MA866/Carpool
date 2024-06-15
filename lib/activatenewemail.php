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
                        <a class="nav-link active" href="/index.php" aria-current="page">Search <span class="visually-hidden">(current)</span></a>
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
                <div class="col-lg-10 col-sm-5">
                    <div class="mt-5 text-center">
                        <h1 class="text-white">Email Activation!</h1>
                        <?php
                            //If email, newemail or  activation key is missing show an error
                            if(!isset($_GET['email']) || !isset($_GET['email']) || !isset($_GET['key'])){
                                echo '<div class="alert alert-danger">There was an error. Please click on the activation link you received by email.</div>'; exit;
                            }
                            //else
                            //Store them in two variables
                            $email = $_GET['email'];
                            $newemail = $_GET['newemail'];
                            $key = $_GET['key'];

                            //Prepare variables for the query
                            $email = mysqli_real_escape_string($con, $email);
                            $newemail = mysqli_real_escape_string($con, $newemail);
                            $key = mysqli_real_escape_string($con, $key);

                            //Run query: set activation field to "activated" for the provided email
                            $sql = "UPDATE users SET email='$newemail', activation2='0' WHERE (email='$email' AND activation2='$key') LIMIT 1";
                            $result = mysqli_query($con, $sql);

                            //If query is successful, show success message and invite user to login
                            if(mysqli_affected_rows($con) == 1){
                                session_destroy();
                                setcookie("rememberme", "", time()-3600);
                                echo '<div class="alert alert-success">Your account has been activated.</div>';
                                echo '<a href="/index.php" type="button" class="btn-lg btn-sucess">Log in<a/>';
                                
                            }else{
                            //Show error message
                                echo '<div class="alert alert-danger">Your account could not be activated. Please try again later.</div>';
                                echo '<div class="alert alert-danger">' . mysqli_error($con) . '</div>'; 
                            }
                        ?>
                    </div>
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
 
</body>

</html>