<?php
    session_start();
    require 'lib/connect.php';
    if(!isset($_SESSION['user_id'])){
        header("location: index.php");
    }
    ?>

    <?php
    $user_id = $_SESSION['user_id'];

    //get username and email
    $sql = "SELECT * FROM users WHERE user_id='$user_id'";
    $result = mysqli_query($con, $sql);

    $count = mysqli_num_rows($result);
    if($count == 1){
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
        $username = $row['username'];
        $email = $row['email'];
        $picture = $row['profilepicture'];
    }else{
        echo "There was an error retrieving the username and profile picture from the database";   
    }
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

</head>

<body >
    <header>
        <!-- place navbar here -->
        <nav class="navbar navbar-expand-sm navbar-light bg-light fixed-top">
                <div class="container">
                    <a class="navbar-brand" href="/"><img src="img/logocar.png" alt="CarPool" class="rounded-pill" style="height: 30px;width:80px"></a>
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
                                <a class="nav-link" href="/profile.php">My Profile</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="loggedin.php">My Trips</a>
                            </li>
                        </ul>
                        <div class="d-flex">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li>
                                    <a href="/profile.php" class="text-decoration-none text-capitalize text-muted fw-bold mx-3">
                                        <?php echo $username ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="/profile.php">
                                    <?php 
                                        if(empty($picture))
                                        {
                                            echo "<div><img class='rounded-pill'style='height: 30px;width:30px' src='../profilepicture/avatar.jpg'</div>";
                                        }
                                        else
                                        {
                                            echo "<div><img class='rounded-pill'style='height: 30px;width:30px' src='$picture'></div>";
                                        }
                                    
                                    ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="index.php?logout=1" class="btn btn-primary btn-md mx-3" >Logout</a>
                                </li>
                            </ul>
                        </div>
                    </div>
            </div>
        </nav>  
    </header>
    <main>
        <div class="container">
            <div class="row justify-content-center align-items-center mt-5">
                <div class="col-lg-10 col-sm-8 col-sm-offset-2">
                    <h2 class="mt-5 mb-5 text-center text-primary text-uppercase">Genral Account Setting</h2>
                    <div class="table-responsive title text-center">
                        <table class="table table-hover table-condensed table-bordered text-white">
                            <tr data-bs-target="#updatepicture" data-bs-toggle="modal">
                                <td>User Picture</td>
                                <td>
                                    <?php
                                        if(empty($picture))
                                        {
                                            echo "<div><img class='rounded-pill' style='height: 50px;width:50px' src='../profilepicture/avatar.jpg'</div>";
                                        }
                                        else
                                        {
                                            echo "<div><img class='rounded-pill'style='height: 30px;width:30px' src='$picture'</div>";
                                        }
                                    ?>
                                </td>
                            </tr>
                            <tr data-bs-target="#updateusername" data-bs-toggle="modal">
                                <td>User Name</td>
                                <td><?php echo $username ?></td>
                            </tr>
                            <tr data-bs-target="#updateemail" data-bs-toggle="modal">
                                <td>Email</td>
                                <td><?php echo $email ?></td>
                            </tr>
                            <tr data-bs-target="#updatepassword" data-bs-toggle="modal">
                                <td>Password</td>
                                <td>Hidden</td>
                            </tr>
                        </table>
                    </div>
                    <!-- Ajax call for PHP File -->
                </div>
            </div>
        </div>
    </main>

    <!-- Add Update user form Start -->
    <form method="post" id="updateusernameform">  

        <!-- Modal Body -->
        <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
        <div class="modal fade" id="updateusername" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"  aria-hidden="true">
            <div class="modal-dialog  modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Username</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Error message from PHP File -->
                        <div id="updateusernamemessage"></div>

                        <div class="mb-2">
                        <!-- <label for="" class="form-label">Email</label> -->
                        <input type="text" class="form-control" name="username" id="username" value="<?php echo $username; ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success px-5" name="updateusername">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- update email Form -->
    <form method="post" id="updateemailform">  

        <!-- Modal Body -->
        <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
        <div class="modal fade" id="updateemail" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"  aria-hidden="true">
            <div class="modal-dialog  modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Email</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Error message from PHP File -->
                        <div id="updateemailmessage"></div>

                        <div class="mb-2">
                        <!-- <label for="" class="form-label">Email</label> -->
                        <input type="text" class="form-control" name="email" id="email" value="<?php echo $email; ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success px-5" name="updateemail">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- update password Form -->
    <form method="post" id="updatepasswordform">  

        <!-- Modal Body -->
        <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
        <div class="modal fade" id="updatepassword" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"  aria-hidden="true">
            <div class="modal-dialog  modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Password</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Error message from PHP File -->
                        <div id="updatepasswordmessage"></div>
                        

                        <div class="mb-2">
                        <!-- <label for="" class="form-label">Email</label> -->
                        <input type="password" class="form-control" name="currentpassword" id="currentpassword" placeholder="Enter your current password">
                        </div>
                        <div class="mb-2">
                        <!-- <label for="" class="form-label">Email</label> -->
                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter new password">
                        </div>
                        <div class="mb-2">
                        <!-- <label for="" class="form-label">Email</label> -->
                        <input type="password" class="form-control" name="password2" id="password2" placeholder="Enter confirm password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success px-5" name="updatepassword">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- update picture Form -->
    <form method="post" id="updatepictureform" enctype="multipart/form-data">  

        <!-- Modal Body -->
        <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
        <div class="modal fade" id="updatepicture" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"  aria-hidden="true">
            <div class="modal-dialog  modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Upload Profile Picture</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Error message from PHP File -->
                        <div id="updatepicturemessage"></div>
                        <?php 
                                if(empty($picture))
                                {
                                    echo "<div><img class='rounded-pill'style='height: 50px;width:50px' src='../profilepicture/avatar.jpg'</div>";
                                }
                                else
                                {
                                    echo "<div><img class='rounded-pill'style='height: 50px;width:50px' src='$picture'</div>";
                                }
                            
                            ?>
                        <div class="form-inline">
                            <div class="form-group">
                                <label for="picture">Select a Picture</label>
                                <input type="file" name="picture" id="picture">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success px-5" name="updatepicture">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- place footer here -->
    <footer class="bg-light text-center text-lg-start mt-auto mb-0 " id="footer" >
        <!-- copyrights -->
        <div class="text-center p-3 footer " >
            <p>Hansa Innovations Copyright &copy; 2023-<?php $today=date("Y"); echo $today;  ?>.
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
    <script src="js/profile.js"></script>
  
</body>

</html>