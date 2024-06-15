<?php
     //session start
     session_start();
     require 'connect.php';
     $errors='';
     //define error messages
     $missingdeparture = '<p><strong>Please enter a departure!</strong></p>';
     $invaliddeparture = '<p><strong>Please enter a valid departure!</strong></p>';
     $missingdestination = '<p><strong>Please enter your destination!</strong></p>';
     $invaliddestination = '<p><strong>Please enter a valid destination!</strong></p>';
     //get inputs
     $departure=$_POST['departure'];
     $destination=$_POST['destination'];
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
     //get inputes
     if(!$departure)
     {
        $errors.=$missingdeparture;
     }
     else
     {
        $departure=htmlspecialchars($departure);
     }
     if(!$destination)
     {
        $errors.=$missingdestination;
     }
     else
     {
        $destination=htmlspecialchars($destination);
     }
     //if there is any error then print error
     if($errors)
    {
        $resultMessage='<div class="alert alert-danger">' . $errors .'</div>';
        echo $resultMessage;
    }
    $sql="SELECT * FROM carsharetrips WHERE  (departureLongitude = '$departureLongitude' AND departureLatitude = '$departureLatitude')  AND (destinationLongitude='$destinationLongitude' AND destinationLatitude='$destinationLatitude')"; 
    $result=mysqli_query($con,$sql);
    if(!$result)
    {
        echo "ERROR:Unable to execute: $sql" . mysqli_error($con);
        exit; 
    }
    $row=mysqli_num_rows($result);
    if($row==0)
    {
        echo '<div class="alert alert-info text-center mb-5">There is no Trip matching your search</div>';
        exit;
    }
    echo '<div class="alert alert-info text-start fw-bold mb-5">From '. $departure . ' To ' . $destination.'<br>Closest Trip</div>';
    echo '<div id="message"></div>';
    //Loop through trips and closest ones
    //retrieve each row in $result
    while($row=mysqli_fetch_array($result,MYSQLI_ASSOC))
    {
        //check if the trip is in past
        $dateOk=1;
        if($row['regular']=="N")
        {
            $source=$row['date'];
            $tripDate=DateTime::createFromFormat('D d M,Y',$source);
            $today=date('D d M,Y');
            $todayDate=DateTime::createFromFormat('D d M,Y',$today);
            $dateOk=($tripDate>$todayDate);
        }
        //if date is OK
        if($dateOk)
        {
            //get the user ID
            $person_id=$row['user_id'];
            //run query to get user details
            $sql="SELECT * FROM users WHERE user_id='$person_id' LIMIT 1";
            $result1=mysqli_query($con,$sql);
            if($result1)
            {
                //get the user details
                $row1=mysqli_fetch_array($result1,MYSQLI_ASSOC);
                //get Firstname
                $firstname=$row1['first_name'];
                //get phone number
                if(isset($_SESSION['user_id']))
                {
                    $phonenumber=$row1['phonenumber'];
                }
                else
                {
                    $phonenumber="Please Sign up or Log in ! To view contact Information";
                }
                //get picture
                $picture=$row1['profilepicture'];
                //get Gender
                $gender=$row1['gender'];
                //get user details
                $userDetails=$row1['moreinformation'];
                //get trip departure
                $tripDeparture=$row['departure'];
                //get trip destination
                $tripDestination=$row['destination'];
                //get trip price
                $tripPrice=$row['price'];
                //get trip seats
                $tripSeats=$row['seatsavailable'];
                //get trip frequency and time
                if($row['regular']=='N')
                {
                    $frequency="One-off Journey";
                    $time=$row['date'] . " at " . $row['time']. ".";
                }
                else
                {
                    $frequency="Regular.";
                    $weekdays=['monday'=>'Mon','tuesday'=>'Tue','wednesday'=>'Wed','thursday'=>'Thu','friday'=>'Fri','saturday'=>'Sat','sunday'=>'Sun'];
                    $array=[];
                    foreach($weekdays as $key => $value)
                    {
                        if($row[$key]==1)
                        {
                            array_push($array,$value);
                        }
                        $time=implode("-",$array) . " at " . $row['time']. ".";
                    }
                }
                
                //print trip
                echo 
                "<h4  >
                    <div class='row'>
                        <div class='col-sm-2 text-start'>
                            <div>
                                <img class='rounded-pill' style='height:50px;width:50px' src='$picture'>
                            </div>
                            <div class='fst-italic text-capitalize text-start'>$firstname</div>
                        </div>
                        <div class='col-sm-8 text-start'>
                            <div>
                                <span class='fw-bold text-success'>Departure:</span>
                                $tripDeparture
                            </div>
                            <div>
                                <span class='fw-bold text-danger'>Destination:</span>
                                $tripDestination
                            </div>
                            <div class='mt-1 text-primary'>
                                $time
                            </div>
                            <div class=' text-warning mb-5'>
                                $frequency
                            </div>
                        </div>
                        <div class='col-sm-2 text-end'>
                            <div class='fw-bold mt-1 text-danger'>
                                ₹ $tripPrice
                            </div>
                            <div class='fw-semibold text-primary'>
                                Per Seat
                            </div>
                            <div class='fw-semibold text-primary mb-5'>
                                $tripSeats Left
                            </div>
                        </div>
                    </div> 
                    <div class='row'>
                        <div class='col'>
                            Gender:$gender
                        </div>
                        <div class='col'>
                            &#9742:$phonenumber
                        </div>
                        <div class='col'>
                            About me:$userDetails
                        </div>
                    </div>
                       
                </h4>";
            }
        }
    }
?>