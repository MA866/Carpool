<?php
    session_start();
    require 'connect.php';

    // retrieve all trips
    $sql = "SELECT * FROM carsharetrips WHERE trip_id ='".$_POST['trip_id']."'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result);

    $array = array("trip_id"=>$row['trip_id'], "departure"=>$row['departure'], "destination"=>$row['destination'], "price"=>$row['price'], "seatsavailable"=>$row['seatsavailable'], "regular"=>$row['regular'], "date"=>$row['date'], "time"=>$row['time'], "monday"=>$row['monday'], "tuesday"=>$row['tuesday'], "wednesday"=>$row['wednesday'], "thursday"=>$row['thursday'], "friday"=>$row['friday'], "saturday"=>$row['saturday'], "sunday"=>$row['sunday']);
    echo json_encode($array);
?>
