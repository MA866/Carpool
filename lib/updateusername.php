<?php
    session_start();
    require 'connect.php';
    $errors = '';

    // get th user id
    $id = $_SESSION['user_id'];

    // get username through Ajax
    $username = $_POST['username'];

    // run the query
    $sql = "UPDATE users SET username='$username' WHERE user_id='$id'";
    $result = mysqli_query($con, $sql);
    if(!$result)
    {
        echo '<div class="alert alert-danger">There was error updating username</div>';
    }
?>