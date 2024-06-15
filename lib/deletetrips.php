<?php
    // Start Session
    session_start();
    require 'connect.php';

    $sql = "DELETE FROM carsharetrips WHERE trip_id='".$_POST['trip_id']."'";
    $result = mysqli_query($con, $sql);
?>