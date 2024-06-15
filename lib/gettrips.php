<?php
    session_start();
    require 'connect.php';

    // reletive trip details
    $sql = "SELECT * FROM carsharetrips WHERE user_id = '". $_SESSION['user_id'] ."'";
    if($result = mysqli_query($con, $sql))
    {
        if(mysqli_num_rows($result) > 0)
        {
            while($row = mysqli_fetch_array($result))
            {
                // Check frequency
                if($row['regular'] == "N")
                {
                    $frequancy = "One-off journey";
                    $time = $row['date']. " at " . $row['time']. " .";
                }
                else
                {
                    $frequancy = "Regular";
                    $array = [];
                    if($row['monday'] == 1)
                    {
                        array_push($array, "Mon");
                    }
                    if($row['tuesday'] == 1)
                    {
                        array_push($array, "Tue");
                    }
                    if($row['wednesday'] == 1)
                    {
                        array_push($array, "Wed");
                    }
                    if($row['thursday'] == 1)
                    {
                        array_push($array, "Thu");
                    }
                    if($row['friday'] == 1)
                    {
                        array_push($array, "Fri");
                    }
                    if($row['saturday'] == 1)
                    {
                        array_push($array, "Sat");
                    }
                    if($row['sunday'] == 1)
                    {
                        array_push($array, "Sun");
                    }
                    $time = implode("-", $array). " at " .$row['time']. ".";
                }
                echo '
                <div class="row trip">
                    <div class="col-sm-8 text-start">
                        <div><span class="fw-bold">Departure:</span>'. $row['departure'] .'</div>
                        <div><span class="fw-bold">Destination:</span>'. $row['destination'] .'</div>
                        <div class="mt-5">' . $time . '</div>
                        <div>' .$frequancy . '</div>
                    </div>
                    <div class="col-sm-2 text-end">
                        <div class="fw-bold"> ₹' . $row['price'] .'</div>
                        <div class="fw-semibold"> per Seat </div>
                        <div class="fw-bold">' . $row['seatsavailable'] .' left</div>
                    </div>
                    <div class="col-sm-2 text-end">
                        <button type="button" class="btn btn-danger btn-md" data-bs-target="#edittripModal" data-bs-toggle="modal" data-trip_id="' .$row['trip_id']. '">Edit</button>
                    </div>
                </div>';
            }
        }
    }
?>