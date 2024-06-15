<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';
    require 'cred.php';
   
    //<!--Start session-->
    session_start();
    require 'connect.php';
    $errors='';

    //<!--Check user inputs-->
    //    <!--Define error messages-->
    $missingEmail = '<p><strong>Please enter your email address!</strong></p>';
    $invalidEmail = '<p><strong>Please enter a valid email address!</strong></p>';

    //    <!--Get , email-->

    //Get email
    if(empty($_POST["forgetemail"])){
        $errors .= $missingEmail;   
    }else{
        $email = filter_var($_POST["forgetemail"], FILTER_SANITIZE_EMAIL);
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors .= $invalidEmail;   
        }
    }

    //If there are any errors print error
    if($errors){
        $resultMessage = '<div class="alert alert-danger">' . $errors .'</div>';
        echo $resultMessage;
        exit;
    }

    //no errors

    //Prepare variables for the queries
    $email = mysqli_real_escape_string($con, $email);

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($con, $sql);
    if(!$result){
        echo '<div class="alert alert-danger">Error running the query!</div>'; exit;
    }
    $results = mysqli_num_rows($result);
    if($results != 1){
        echo '<div class="alert alert-danger">That email is does not exists in our database?</div>';  exit;
    }
    $row=mysqli_fetch_array($result,MYSQLI_ASSOC);

    $user_id=$row['user_id'];

    //Create a unique  activation code
    $key = bin2hex(openssl_random_pseudo_bytes(16));
    $time=time();
    $status='pending';

    $sql = "INSERT INTO forgotpassword (`user_id`, `rkey`, `time`, `status`) VALUES ('$user_id', '$key', '$time', '$status')";
    $result = mysqli_query($con, $sql);
    if(!$result){
        echo '<div class="alert alert-danger">There was an error inserting the users details in the database!</div>'; 
        exit;
    }

    //Send the user an email with a link to activate.php with their email and activation code
    $message = "Please click on this link or copy  to activate your account:\n\n";
    $message .= "http://localhost:3000/lib/resetpassword.php?user_id=$user_id&key=$key";

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                    //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = MYEMAIL;                                //SMTP username
        $mail->Password   = MYPASS;                                 //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom(MYEMAIL, 'Mahi singh');
        $mail->addAddress($email);     //Add a recipient
        //$mail->addAddress('ellen@example.com');               //Name is optional
        $mail->addReplyTo(MYEMAIL, 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Reset your Password';
        $mail->Body    = $message;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        echo "<div class='alert alert-success'> An email has been sent to $email. Please click on the activation link or copy to reset your password.</div>";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }    
 ?>