<?php
    session_start();
    require 'connect.php';
    $errors='';

    //If user_id or key is missing
    if(!isset($_POST['user_id']) || !isset($_POST['key'])){
        echo '<div class="alert alert-danger">There was an error. Please click on the link you received by email.</div>'; exit;
    }

    $user_id = $_POST['user_id'];
    $key = $_POST['key'];
    $time = time() - 86400;

    //Prepare variables for the query
    $user_id = mysqli_real_escape_string($con, $user_id);
    $key = mysqli_real_escape_string($con, $key);

    //Run Query: Check combination of user_id & key exists and less than 24h old
    $sql = "SELECT user_id FROM forgotpassword  WHERE rkey='$key' AND user_id='$user_id' AND time > '$time' AND status='pending'";
    $result = mysqli_query($con, $sql);
    if(!$result){
        echo '<div class="alert alert-danger">Error running the query!</div>'; exit;
    }

    //If combination does not exist
    //show an error message
    $count = mysqli_num_rows($result);
    if($count !== 1){
        echo '<div class="alert alert-danger">Please try again.</div>';
        exit;
    }

    //Define error messages
    $missingPassword = '<p><strong>Please enter a Password!</strong></p>';
    $invalidPassword = '<p><strong>Your password should be at least 6 characters long and inlcude one capital letter and one number!</strong></p>';
    $differentPassword = '<p><strong>Passwords don\'t match!</strong></p>';
    $missingPassword2 = '<p><strong>Please confirm your password</strong></p>';

    //Get passwords
    if(empty($_POST["password"])){
        $errors .= $missingPassword; 
    }elseif(!(strlen($_POST["password"])>6
            and preg_match('/[A-Z]/',$_POST["password"])
            and preg_match('/[0-9]/',$_POST["password"])
            )
        ){
        $errors .= $invalidPassword; 
    }else{
        $password = htmlspecialchars($_POST["password"]); 
        if(empty($_POST["password2"])){
            $errors .= $missingPassword2;
        }else{
            $password2 = htmlspecialchars($_POST["password2"]);
            if($password !== $password2){
                $errors .= $differentPassword;
            }
        }
    }

    //If there are any errors print error
    if($errors){
        $resultMessage = '<div class="alert alert-danger">' . $errors .'</div>';
        echo $resultMessage;
        exit;
    }

    //prepare variables for the query
    $password = mysqli_real_escape_string($con, $password);
    $password = hash('sha256', $password);
    $user_id = mysqli_real_escape_string($con, $user_id);

    //Run Query: Update users password in the users table
    $sql = "UPDATE users SET password='$password' WHERE user_id='$user_id'";
    $result = mysqli_query($con, $sql);
    if(!$result){
        echo '<div class="alert alert-danger">There was a problem storing the new password in the database!</div>';
        exit;
    }

    //set the key status to "used" in the forgotpassword table to prevent the key from being used twice
    $sql = "UPDATE forgotpassword SET status='used' WHERE rkey='$key' AND user_id='$user_id'";
    $result = mysqli_query($con, $sql);
    if(!$result){
        echo '<div class="alert alert-danger">Error running the query</div>';
    }else{
        echo '<div class="alert alert-success">Your password has been update successfully!<br><hr><a href="/index.php" class="btn btn-success btn-lg">Login</a></div>';  
    }
?>