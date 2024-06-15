<?php
    session_start();
    require 'connect.php';
    $user_id = $_SESSION['user_id'];

    function uploadProfilePicture($id, $file, $ext, $con)
    {
        $targetdir = '../profilepicture/' . md5(time()) . ".$ext";
        if(move_uploaded_file($file, $targetdir))
        {
            $sql = "UPDATE users SET profilepicture = '$targetdir' WHERE user_id = '$id'";
            $result = mysqli_query($con, $sql);
            if(!$result)
            {
                $resultMessage = '<div class="alert alert-danger">Unable to update databse. please try again!</div>';
                echo $resultMessage;
            }
        }
        else
        {
            $resultMessage = '<div class="alert alert-danger">Unable to upload file. please try again!</div>';
            echo $resultMessage;
        }
    }

    $errors = '';
    // error message
    $noFileUpload = '<p><b>Please select a file to upload</b></p>';
    $wrongFormat = '<p><b>Please Select correct file Format (jpeg, jpg, png)</b></p>';
    $fileTooLarge = '<p><b>Select the file upto 3MB only</b></p>';

    // file details
    $name = $_FILES['picture']['name'];
    $type = $_FILES['picture']['type'];
    $size = $_FILES['picture']['size'];
    $fileerror = $_FILES['picture']['error'];
    $tmp_name = $_FILES['picture']['tmp_name'];
    $extension = pathinfo($name, PATHINFO_EXTENSION);

    $allowedFormat = array("jpeg"=>"image/jpeg", "jpg"=>"image/jpg", "png"=>"image/png");
    
    if($fileerror==4)
    {
        $errors .= $noFileUpload;
    }
    else
    {
        if(!in_array($type, $allowedFormat))
        {
            $errors .= $wrongFormat;
        }
        elseif($size > 3*1024*1024)
        {
            $errors .= $fileTooLarge;
        }
    }

    // display errors
    if($errors)
    {
        $resultMessage = '<div class="alert alert-danger">' . $errors . '</div>';
        echo $resultMessage;
    }
    else
    {
        uploadProfilePicture($user_id, $tmp_name, $extension, $con);
    }
?>