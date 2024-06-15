<?php

    session_start();
    require 'connect.php';
    $errors='';
    //<!--Check user inputs-->
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
    $departure=$_POST['departure'];
    $destination=$_POST['destination'];
    $price=$_POST['price'];
    $seatavailable=$_POST['seatsavailable'];
    if(isset($_POST['regular']))
    {
        $regular=$_POST['regular'];
    }
    else
    {
        $regular='';
    }
    
    $date=$_POST['date'];
    $time=$_POST['time'];
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
    if(empty($_POST["departure"])){
        $errors .= $missingdeparture;
    }else{
        $departure = htmlspecialchars($_POST["departure"]);   
    }
    //Get destination
    if(empty($_POST["destination"])){
        $errors .= $missingdestination;
    }else{
        $destination= htmlspecialchars($_POST["destination"]);
    }
    //Get price
    if(empty($_POST["price"]))
    {
        $errors .= $missingPrice;
    }elseif(preg_match('/\D/',$price))
    {
        $errors.=$invalidPrice;
    }
    else
    {
        $price = htmlspecialchars($_POST["price"]);
    }
    //Get Seat Available
    if(empty($_POST["seatsavailable"])){
        $errors .= $missingseatavialable;   
    }elseif(preg_match('/\D/',$seatavailable))
    {
        $errors.=$invalidseatavialble;
    }
    else
    {
        $seatavailable = htmlspecialchars($_POST["seatsavailable"]);
    }
    //check for regular
    if(!$regular)
    {
        $errors.=$missingFrequency;
    }
    elseif($regular=="Y")
    {
        if(isset($_POST['monday']))
        {
            $monday=$_POST['monday'];
        }
        else
        {
            $monday='';
        }
        if(isset($_POST['tuesday']))
        {
            $tuesday=$_POST['tuesday'];
        }
        else
        {
            $tuesday='';
        }
        if(isset($_POST['wednesday']))
        {
            $wednesday=$_POST['wednesday'];
        }
        else
        {
            $wednesday='';
        }
        if(isset($_POST['thursday']))
        {
            $thursday=$_POST['thursday'];
        }
        else
        {
            $thursday='';
        }
        if(isset($_POST['friday']))
        {
            $friday=$_POST['friday'];
        }
        else
        {
            $friday='';
        }
        if(isset($_POST['saturday']))
        {
            $saturday=$_POST['saturday'];
        }
        else
        {
            $saturday='';
        }
        if(isset($_POST['sunday']))
        {
            $sunday=$_POST['sunday'];
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
        if($regular == "Y"){
            //query for a regular trip
            $sql = "INSERT INTO $tableName (`user_id`,`departure`, `departureLongitude`, `departureLatitude`, `destination`, `destinationLongitude`, `destinationLatitude`, `price`, `seatsavailable`, `regular`, `monday`, `tuesday`, `wednesday`, `thursday`, `friday`, `saturday`, `sunday`, `time`) VALUES ('".$_SESSION['user_id']."', '$departure','$departureLongitude','$departureLatitude','$destination','$destinationLongitude','$destinationLatitude','$price','$seatavailable','$regular','$monday','$tuesday','$wednesday','$thursday','$friday','$saturday','$sunday','$time')";
        }else{ 
            //query for a one off trip
            $sql = "INSERT INTO $tableName (`user_id`,`departure`, `departureLongitude`, `departureLatitude`, `destination`, `destinationLongitude`, `destinationLatitude`, `price`, `seatsavailable`, `regular`, `date`, `time`) VALUES ('".$_SESSION['user_id']."', '$departure','$departureLongitude','$departureLatitude','$destination','$destinationLongitude','$destinationLatitude','$price','$seatavailable','$regular','$date','$time')";   
        }
        $results=mysqli_query($con,$sql);
        //check for errors
        if(!$results)
        {
            echo '<div class="alert alert-danger">There was error inserting Trip in database!</div>';
        }
    }
?>