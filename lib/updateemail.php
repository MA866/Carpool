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

    //    <!--Get user_id , email, through AJAx-->
    $user_id = $_SESSION['user_id'];
    $newemail = $_POST['email'];

    // check if newemail exixts
    $sql = "SELECT * FROM users WHERE email='$newemail'";
    $result = mysqli_query($con, $sql);
    $count = mysqli_num_rows($result);

    if($count > 0)
    {
        echo '<div class="alert alert-danger">This email id already registerd with us. please try another!</div>';
    }

    // get the current email
    $sql = "SELECT * FROM users WHERE user_id='$user_id'";
    $result = mysqli_query($con, $sql);
    $count = mysqli_num_rows($result);

    if($count == 1)
    {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $email = $row['email'];
    }
    else
    {
        echo '<div class="alert alert-danger">There was error retrieving email from Database</div>';
    }

    // No error
    //Create a unique  activation code
    $activationKey = bin2hex(openssl_random_pseudo_bytes(16));

    $sql = "UPDATE users SET activation2='$activationKey' WHERE user_id='$user_id'";
    $result = mysqli_query($con, $sql);
    if(!$result){
        echo '<div class="alert alert-danger">There was an error inserting the users details in the database!</div>'; 
        exit;
    }

    //Send the user an email with a link to activate.php with their email and activation code
    $message = "Please click on this link or copy  to activate your account:\n\n";
    $message .= "http://localhost:3000/lib/activatenewemail.php?email=" . urlencode($email) ."&newemail=". urlencode($newemail) . "&key=$activationKey";

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
        $mail->addAddress($newemail);     //Add a recipient
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
        $mail->Subject = 'Confirm Email Update';
        $mail->Body    = $message;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        echo "<div class='alert alert-success'> An email has been sent to $newemail. Please click on the activation link to confirm you own this email</div>";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }    
 ?>