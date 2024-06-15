<?php
    session_start();
    require 'lib/connect.php';

    // logout 
    require 'lib/logout.php';

    // rememberme
    require 'lib/remember.php';
?>  
<!doctype html>
<html lang="en">

<head>
  <title>CarPool Website</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">

  <!-- Google Popins Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <!-- jQuery Library CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>

    <!-- MapmyIndia Plugin Link -->
    <script src="https://apis.mapmyindia.com/advancedmaps/v1/4899c581-15a4-4147-bb52-07e26535a445/map_load?v=1.5"></script>
    <script src="https://apis.mapmyindia.com/advancedmaps/api/4899c581-15a4-4147-bb52-07e26535a445/map_sdk_plugins"></script>
    
</head>

<body>
  <header>
    <!-- place navbar here -->
        <?php
            if(isset($_SESSION['user_id']))
            {
                require 'lib/menuconnected.php';
            }
            else
            {
                require 'lib/menunotconnected.php';
            }
        ?>
  </header>
  <main>
        <div class="container">
            <div class="row justify-content-center align-items-center title mt-5">
                <div class="col-lg-10 col-sm-5">
                    <div class="mt-5 text-center">
                        <h1 class="text-white">Plan your next trip now !</h1>
                        <p class="fw-bold text-white">Save money ! Environment too!</p>
                        <p class="fw-bolder text-white">You can save up yo ₹ in lacs a year using car sharing</p>
                    </div>
                </div>
            </div>
            <!-- Start of Search Form -->
            <div class="row justify-content-center align-items-center mt-2">
                <form  method="post" id="searchform" class="mb-3  ">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="mb-1 ms-5">
                                <input type="text" name="departure" id="departure" class="form-control" placeholder="Departure">
                            
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="mb-1 me-5">
                                <input type="text" name="destination" id="destination" class="form-control" placeholder="Destination">
                            
                            </div>
                        </div>
                        <div class="col-md-2">
                            <input type="submit" class="btn btn-success btn-md mx-5" name="search" value="Search">
                        </div>
                    </div>
                </form>
            </div>
            <!-- End of Search Form -->

            <!-- Start of Map-->
            <div class="row justify-content-center align-items-center ">
                <div class="border border-5 border-secondary shadow-lg bg-body rounded map" id="map"></div>
            </div>
            <!-- End of Map -->

            <!-- Sign Up Button -->
            <div class="row justify-content-center align-items-center ">
                <div class="d-grid mt-4 mb-5">
                    <?php
                        if(!isset($_SESSION['user_id']))
                        {
                            echo '<button type="button" class="btn btn-success btn-lg " data-bs-toggle="modal" data-bs-target="#signupModal">Sign Up It\'s Free</button>';
                        }
                    ?>
                    
                </div>
            </div>

            <!-- Results of Search -->
            <div class="row justify-content-center align-items-center ">
                <div  id="results"></div>
                
            </div>
        </div>
  </main>
  
  <!-- Login Form Start -->
  <form method="post" id="loginform">  
    <!-- Modal Body -->
    <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
    <div class="modal fade" id="loginModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"  aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Login Form</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Login message from PHP File -->
                    <div id="loginmessage"></div>
                    <div class="mb-3">
                      <!-- <label for="" class="form-label">Email</label> -->
                      <input type="email" class="form-control" name="loginemail" id="loginemail" aria-describedby="eemailId" placeholder="Email">
                      <small id="eemailId" class="form-text text-muted">We'll never share your email with anyone else</small>
                    </div>
                    <div class="mb-3">
                      <!-- <label for="" class="form-label">Email</label> -->
                      <input type="password" class="form-control" name="loginpassword" id="loginpassword"  placeholder="Password">
                    </div>
                    <div class="form-check form-check-inline mb-3">
                        <input class="form-check-input" type="checkbox" id="rememberme" name="rememberme">
                        <label class="form-check-label" for="rememberme">Remember Me</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success px-5" name="login">Login</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary float-end" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#forgetpasswordModal">Forget Password</button>
                </div>
            </div>
        </div>
    </div>
  </form>
  <!-- forget Password Form -->
  <form method="post" id="forgotpasswordform">  
    <!-- Modal Body -->
    <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
    <div class="modal fade" id="forgetpasswordModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"  aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Forgot Password ? Enter Email</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Forgot Password message from PHP File -->
                    <div id="forgotpasswordmessage"></div>
                    <div class="mb-3">
                      <!-- <label for="" class="form-label">Email</label> -->
                      <input type="email" class="form-control" name="forgetemail" id="forgetemail" aria-describedby="emailHelpId" placeholder="Email">
                      <small id="emailHelpId" class="form-text text-muted">Your Registered Email Only</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success px-5" name="forgotpassword">Submit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary float-end" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
                </div>
            </div>
        </div>
    </div>
  </form>
  <!-- Sign Up Form -->
  <form method="post" id="signupform" >  
    <!-- Modal Body -->
    <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
    <div class="modal fade" id="signupModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"  aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sign Up and Start using our website!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Forgot Password message from PHP File -->
                    <div id="signupmessage"></div>
                    <div class="mb-1">
                      <input type="text" name="username" id="username" class="form-control" placeholder="Username">
                    </div>
                    <div class="mb-1">
                      <input type="text" name="firstname" id="firstname" class="form-control" placeholder="Firstname">
                    </div>
                    <div class="mb-1">
                      <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Lastname">
                    </div>
                    <div class="mb-1">
                      <input type="email" name="email" id="email" class="form-control" placeholder="Email">
                    </div>
                    <div class="mb-1">
                      <input type="password" name="password" id="password" class="form-control" placeholder="Choose aPassword">
                    </div>
                    <div class="mb-1">
                      <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password">
                    </div>
                    <div class="mb-1">
                      <input type="t" name="phonenumber" id="phonenumber" class="form-control" placeholder="Mobile Number">
                    </div>
                    <div class="mb-1 form-inline">
                        <label class="form-lable" for="gender">Gender</label>
                        <label><input type="radio" name="gender" id="male" value="male">Male</label>
                        <label><input type="radio" name="gender" id="female" value="female">Female</label>
                    </div>
                    <div class="mb-1">
                        <label for="moreinformation">More Details:</label> 
                        <textarea name="moreinformation" class="form-control" rows="5" maxlength="300"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success px-5" name="signup">Sign Up</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
  </form>
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
    <script src="js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/map.js"></script>
    <script src="js/trip.js"></script>

    
</body>

</html>