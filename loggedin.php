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

    <!-- MapmyIndia Plugin Link -->
    <script src="https://apis.mapmyindia.com/advancedmaps/v1/4899c581-15a4-4147-bb52-07e26535a445/map_load?v=1.5"></script>
    <script src="https://apis.mapmyindia.com/advancedmaps/api/4899c581-15a4-4147-bb52-07e26535a445/map_sdk_plugins"></script>

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
                <div >
                    <button type="button" class="btn btn-success btn-lg mb-5 mt-5" data-bs-toggle="modal" data-bs-target="#addTripModal">Add Trip</button>
                </div>
                <div  id="mytrips"></div>
                <!-- Ajax call for PHP File -->
            </div>
        </div>
    </div>
  </main>

  <!-- Add Trip Form Start -->
  <form method="post" id="addtripform">  
    <!-- Modal Body -->
    <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
    <div class="modal fade" id="addTripModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"  aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Trip</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Error message from PHP File -->
                    <div id="results"></div>
                    <!-- Map mapmyindia -->
                    <div class="row justify-content-center align-items-center ">
                        <div id="map" class="mapIndia"></div>
                    </div>
                    <div class="mb-2">
                      <!-- <label for="" class="form-label">Email</label> -->
                      <input type="text" class="form-control" name="departure" id="departure" placeholder="Departure">
                    </div>
                    <div class="mb-2">
                      <!-- <label for="" class="form-label">Email</label> -->
                      <input type="text" class="form-control" name="destination" id="destination" placeholder="Destination">
                    </div>
                    <div class="mb-2">
                      <!-- <label for="" class="form-label">Email</label> -->
                      <input type="number" class="form-control" name="price" id="price"  placeholder="Price">
                    </div>
                    <div class="mb-2">
                      <!-- <label for="" class="form-label">Email</label> -->
                      <input type="number" class="form-control" name="seatsavailable" id="seatsavailable"  placeholder="Seats Available">
                    </div>
                    <div class="form-check form-inline mb-2">
                        <label class="form-check-label me-5" for="regular">Frequancy</label>
                        <label class=" me-5"><input class="form-check-input" type="radio" id="yes" name="regular" value="Y">Regular</label>
                        <label><input class="form-check-input" type="radio" id="no" name="regular" value="N">One-off</label>     
                    </div>
                    <div class="checkbox checkbox-inline mb-2 regular">
                        <label><input type="checkbox" value="1" id="monday" name="monday"> Monday</label>    
                        <label><input type="checkbox" value="1" id="tuesday" name="tuesday"> Tuesday</label>    
                        <label><input type="checkbox" value="1" id="wednesday" name="wednesday"> Wednesday</label>    
                        <label><input type="checkbox" value="1" id="thursday" name="thursday"> Thursday</label>    
                        <label><input type="checkbox" value="1" id="friday" name="friday"> Friday</label>    
                        <label><input type="checkbox" value="1" id="saturday" name="saturday"> Saturday</label>    
                        <label><input type="checkbox" value="1" id="sunday" name="sunday"> Sunday</label>    
                    </div> 
                    <div class="form-inline mb-2 oneoff">
                        <label for="date" class="form-control-label">Date</label>
                        <input type="date" name="date" id="date" placeholder="Date" class="form-control">
                    </div>
                    <div class="form-inline mb-2 time ">
                        <label for="time" class="form-control-label">Time</label>
                        <input type="time" name="time" id="time" placeholder="Time" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success px-5" name="createTrip">Create Trip</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
  </form>

  <!-- Etid Trip Form -->
  <form method="post" id="edittripform">  
    <!-- Modal Body -->
    <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
    <div class="modal fade" id="edittripModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"  aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Trip</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Error message from PHP File -->
                    <div id="result2"></div>
                    <!-- Map mapmyindia -->
                    <div class="row justify-content-center align-items-center ">
                        <div id="map1" class="mapIndia"></div>
                    </div>
                    <div class="mb-2">
                      <!-- <label for="" class="form-label">Email</label> -->
                      <input type="text" class="form-control" name="departure2" id="departure2" placeholder="Departure">
                    </div>
                    <div class="mb-2">
                      <!-- <label for="" class="form-label">Email</label> -->
                      <input type="text" class="form-control" name="destination2" id="destination2" placeholder="Destination">
                    </div>
                    <div class="mb-2">
                      <!-- <label for="" class="form-label">Email</label> -->
                      <input type="number" class="form-control" name="price2" id="price2"  placeholder="Price">
                    </div>
                    <div class="mb-2">
                      <!-- <label for="" class="form-label">Email</label> -->
                      <input type="number" class="form-control" name="seatsavailable2" id="seatsavailable2"  placeholder="Seats Available">
                    </div>
                    <div class="form-check form-check-inline mb-2">
                        <label class="form-check-label me-5" for="regular">Frequancy</label>
                        <label class=" me-5"><input class="form-check-input" type="radio" id="yes2" name="regular2" value="Y">Regular</label>
                        <label><input class="form-check-input" type="radio" id="no2" name="regular2" value="N">One-off</label>     
                    </div>
                    <div class="checkbox checkbox-inline mb-2 regular2">
                        <label><input type="checkbox" value="1" id="monday2" name="monday2"> Monday</label>    
                        <label><input type="checkbox" value="1" id="tuesday2" name="tuesday2"> Tuesday</label>    
                        <label><input type="checkbox" value="1" id="wednesday2" name="wednesday2"> Wednesday</label>    
                        <label><input type="checkbox" value="1" id="thursday2" name="thursday2"> Thursday</label>    
                        <label><input type="checkbox" value="1" id="friday2" name="friday2"> Friday</label>    
                        <label><input type="checkbox" value="1" id="saturday2" name="saturday2"> Saturday</label>    
                        <label><input type="checkbox" value="1" id="sunday2" name="sunday2"> Sunday</label>    
                    </div> 
                    <div class="form-inline mb-2 oneoff2">
                        <label for="date" class="form-control-label">Date</label>
                        <input type="date" name="date2" id="date2" placeholder="Date" class="form-control">
                    </div>
                    <div class="form-inline mb-2 time2">
                        <label for="time" class="form-control-label">Time</label>
                        <input type="time" name="time2" id="time2" placeholder="Time" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning px-5" name="updateTrip">Update Trip</button>
                    <button type="button" class="btn btn-danger" name="deletetrip" id="deletetrip">Delete</button>
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
    <script src="js/script.js"></script>
    <script src="js/map.js"></script>
    <script src="js/trip.js"></script>
    
</body>

</html>