<?php
    $user_id = $_SESSION['user_id'];

    //get username and email
    $sql = "SELECT * FROM users WHERE user_id='$user_id'";
    $result = mysqli_query($con, $sql);

    $count = mysqli_num_rows($result);

    if($count == 1){
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
        $username = $row['username'];
        //$email=$row['email'];
        $picture = $row['profilepicture'];
    }else{
        echo "There was an error retrieving the username and profile picture from the database";   
    }
?>
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
                        <a class="nav-link" href="/profile.php">My Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/loggedin.php">My Trips</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li>
                            <a href="profile.php" class="text-decoration-none text-capitalize text-muted fw-bold mx-3">
                                <?php echo $username ?>
                            </a>
                        </li>
                        <li>
                            <a href="profile.php">
                            <?php 
                                if(empty($picture))
                                {
                                    echo "<div><img class='rounded-pill'style='height: 30px;width:30px' src='../profilepicture/avatar.jpg'</div>";
                                }
                                else
                                {
                                    echo "<div><img class='rounded-pill 'style='height: 30px;width:30px' src='$picture' alt='PP'></div>";
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