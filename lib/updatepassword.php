<?php
    // Start session
    session_start();
    require 'connect.php';
    $errors='';

    //<!--Check user inputs-->
    //    <!--Define error messages-->
    $missingCurrentPassword = '<p><strong>Please enter your Current Password!</strong></p>';
    $incorrectCurrentPassword = '<p><strong>Password Enter is incorrect</strong></p>';
    $missingPassword = '<p><strong>Please enter a Password!</strong></p>';
    $invalidPassword = '<p><strong>Your password should be at least 6 characters long and inlcude one capital letter and one number!</strong></p>';
    $differentPassword = '<p><strong>Passwords don\'t match!</strong></p>';
    $missingPassword2 = '<p><strong>Please confirm your password</strong></p>';


    //    <!--Get password, password2-->

    //Get passwords
    if(empty($_POST["currentpassword"]))
    {
        $errors .= $missingCurrentPassword; 
    }else
    {
        $currentpassword = $_POST['currentpassword'];
        $currentpassword = htmlspecialchars($_POST['currentpassword']);
        $currentpassword = mysqli_escape_string($con, $currentpassword);
        $currentpassword = hash('sha256', $currentpassword);

        // check current password i e current password is correct
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT password FROM users WHERE user_id = '$user_id'";
        $result = mysqli_query($con, $sql);
        $count = mysqli_num_rows($result);
        if($count !== 1)
        {
            echo '<div class="alert alert-danger">' . $errors . '</div>';  
            exit;
        }
        else
        {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if($currentpassword != $row['password'])
            {
                $errors = $incorrectCurrentPassword;
            }
        }
    }
    
    //Get passwords
    if(empty($_POST["password"]))
    {
        $errors .= $missingPassword; 
    }
    elseif(!(strlen($_POST["password"]) > 6 
        and preg_match('/[A-Z]/',$_POST["password"])
        and preg_match('/[0-9]/',$_POST["password"])
        )
      )
    {
        $errors .= $invalidPassword; 
    }
    else
    {
        $password = htmlspecialchars($_POST["password"]); 
        if(empty($_POST["password2"])){
            $errors .= $missingPassword2;
        }else{
            $password2 = filter_var($_POST["password2"]);
            if($password !== $password2)
            {
                $errors .= $differentPassword;
            }
        }
    }

    //If there are any errors print error
    if($errors)
    {
        $resultMessage = '<div class="alert alert-danger">' . $errors .'</div>';
        echo $resultMessage;
        exit;
    }
    else
    {
        $password = mysqli_real_escape_string($con, $password);
        $password = hash('sha256', $password);

        // run the query
        $sql = "UPDATE users SET password='$password' WHERE user_id = '$user_id'";
        $result = mysqli_query($con, $sql);
        if(!$result)
        {
            echo '<div class="alert alert-danger">The password can not be reset</div>';
        }
        else
        {
            echo '<div class="alert alert-success">The password has been updated successfully</div>';
        }
    }    
 ?>