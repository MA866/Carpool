<?php
    $con=mysqli_connect('localhost','root','','cars');
    if(mysqli_connect_error())
    {
        die('ERROR:Unable to Connect:' .mysqli_connect_error());
        echo "<script>alert('Hi!')</script>";
    }
?>