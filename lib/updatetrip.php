<?php

    session_start();
    require 'connect.php';
    $errors='';

    //    <!--Define error messages-->
    $missingdeparture = '<p><strong>Please enter a departure!</strong></p>';
    $invaliddeparture = '<p><strong>Please enter a valid departure!</strong></p>';
    $missingdestination = '<p><strong>Please enter your destination!</strong></p>';
    $invaliddestination = '<p><strong>Please enter a valid destination!</strong></p>';
    $missingPrice = '<p><strong>Please enter a Price!</strong></p>';
    $invalidPrice = '<p><strong>Please enter the value only in number!</strong></p>';
    $missingseatavialable = '<p><strong>Please enter the seat available</strong></p>';
    $invalidseatavialble = '<p><strong>Please enter the value only in number!</strong></p>';
    $missingFrequency = '<p><strong>Please Select the Frequency(Regular or OneOff)!</strong></p>';
    $missingday = '<p><strong>Please select atleast one day in Weekdays!</strong></p>';
    $missingDate = '<p><strong>Please select the date of Departure!</strong></p>';
    $missingtime = '<p><strong>Please select the time of Departure!!</strong></p>';

    //Get inputs
    $trip_id = $_POST['trip_id'];
    $departure=$_POST['departure2'];
    $destination=$_POST['destination2'];
    $price=$_POST['price2'];
    $seatavailable=$_POST['seatsavailable2'];
    if(isset($_POST['regular2']))
    {
        $regular=$_POST['regular2'];
    }
    else
    {
        $regular='';
    }
    
    $date=$_POST['date2'];
    $time=$_POST['time2'];
    $monday='';
    $tuesday='';
    $wednesday='';
    $thursday='';
    $friday='';
    $saturday='';
    $sunday='';

    //check Coordinates
    if(!isset($_POST['departureLatitude']) or !isset($_POST['departureLongitude']) )
    {
        $errors.=$invaliddeparture;
    }
    else
    {
        $departureLatitude=$_POST['departureLatitude'];
        $departureLongitude=$_POST['departureLongitude'];
    }
    if(!isset($_POST['destinationLatitude']) or !isset($_POST['destinationLongitude']) )
    {
        $errors.=$invaliddestination;
    }
    else
    {
        $destinationLatitude=$_POST['destinationLatitude'];
        $destinationLongitude=$_POST['destinationLongitude'];
    }

    //get departure
    if(empty($_POST["departure2"])){
        $errors .= $missingdeparture;
    }else{
        $departure = htmlspecialchars($_POST["departure2"]);   
    }

    //Get destination
    if(empty($_POST["destination2"])){
        $errors .= $missingdestination;
    }else{
        $destination= htmlspecialchars($_POST["destination2"]);
    }

    //Get price
    if(empty($_POST["price2"]))
    {
        $errors .= $missingPrice;
    }elseif(preg_match('/\D/',$price))
    {
        $errors.=$invalidPrice;
    }
    else
    {
        $price = htmlspecialchars($_POST["price2"]);
    }

    //Get Seat Available
    if(empty($_POST["seatsavailable2"])){
        $errors .= $missingseatavialable;   
    }elseif(preg_match('/\D/',$seatavailable))
    {
        $errors.=$invalidseatavialble;
    }
    else
    {
        $seatavailable = htmlspecialchars($_POST["seatsavailable2"]);
    }

    //check for regular
    if(!$regular)
    {
        $errors.=$missingFrequency;
    }
    elseif($regular=="Y")
    {
        if(isset($_POST['monday2']))
        {
            $monday=$_POST['monday2'];
        }
        else
        {
            $monday='';
        }
        if(isset($_POST['tuesday2']))
        {
            $tuesday=$_POST['tuesday2'];
        }
        else
        {
            $tuesday='';
        }
        if(isset($_POST['wednesday2']))
        {
            $wednesday=$_POST['wednesday2'];
        }
        else
        {
            $wednesday='';
        }
        if(isset($_POST['thursday2']))
        {
            $thursday=$_POST['thursday2'];
        }
        else
        {
            $thursday='';
        }
        if(isset($_POST['friday2']))
        {
            $friday=$_POST['friday2'];
        }
        else
        {
            $friday='';
        }
        if(isset($_POST['saturday2']))
        {
            $saturday=$_POST['saturday2'];
        }
        else
        {
            $saturday='';
        }
        if(isset($_POST['sunday2']))
        {
            $sunday=$_POST['sunday2'];
        }
        else
        {
            $sunday='';
        }

        if(!$monday && !$tuesday && !$wednesday && !$thursday && !$friday && !$saturday && !$sunday)
        {
            $errors.=$missingday;
        }
        if(!$time)
        {
            $errors.=$missingtime;
        }
    }
    elseif($regular=="N")
    {
        if(!$monday && !$tuesday && !$wednesday && !$thursday && !$friday && !$saturday && !$sunday)
        {
            $monday='';
            $tuesday='';
            $wednesday='';
            $thursday='';
            $friday='';
            $saturday='';
            $sunday='';
        }
        if(!$date)
        {
            $errors.=$missingDate;
        }  
    }

    //Print the error message
    if($errors)
    {
        $resultMessage='<div class="alert alert-danger">' . $errors .'</div>';
        echo $resultMessage;
    }
    else
    {
        //prepare variables for query
        $tableName='carsharetrips';
        $departure=mysqli_real_escape_string($con,$departure);
        $destination=mysqli_real_escape_string($con,$destination);
        if($regular == "Y")
        {
            //query for a regular trip
            $sql = "UPDATE $tableName SET `departure` = '$departure', `departureLongitude` = '$departureLongitude', `departureLatitude` = '$departureLatitude', `destination` = '$destination', `destinationLongitude`= '$destinationLongitude', `destinationLatitude` = '$destinationLatitude', `price` = '$price', `seatsavailable` = '$seatavailable', 
            `regular` = '$regular', 
            `monday` = '$monday', 
            `tuesday` = '$tuesday', `wednesday` = '$wednesday', `thursday` = '$thursday', 
            `friday` = '$friday', 
            `saturday` = '$saturday', 
            `sunday` = '$sunday', 
            `time` = '$time'
            WHERE `trip_id` = '$trip_id' LIMIT 1";
        }
        else
        { 
            //query for a one off trip
            $sql = "UPDATE $tableName SET `departure` = '$departure', `departureLongitude` = '$departureLongitude', `departureLatitude` = '$departureLatitude', `destination` = '$destination', `destinationLongitude`= '$destinationLongitude', `destinationLatitude` = '$destinationLatitude', `price` = '$price', `seatsavailable` = '$seatavailable', `regular` = '$regular', `date`='$date', `time` = '$time' WHERE `trip_id` = '$trip_id'";
        }
        $results=mysqli_query($con,$sql);
        //check for errors
        if(!$results)
        {
            echo '<div class="alert alert-danger">There was error inserting Trip in database!</div>';
        }
    }
?>