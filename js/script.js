var data
//Ajax Call for sign up form
//Once the form is submitted
$("#signupform").submit(function(event){
    //hide Messahe
    $("#signupmessage").hide()

    //show the spinner
    $("#spinner").css("display","block")

    //prevent deafault PHP processing
    event.preventDefault();

    //collect inputs from user
    var datatopost=$(this).serializeArray()
    console.log(datatopost)

    //send data to php file using AJAX
    $.ajax({
        url:"lib/signup.php",
        type: "POST",
        data: datatopost,
        success: function(data){
            if(data)
            {
                $("#signupmessage").html(data)
                //hide spinner
                $("#spinner").css("display","none")
                //show message
                $("#signupmessage").slideDown()
            }
        },
        error:function()
        {
            $("#signupmessage").html("<div class='alert alert-danger'>There was error calling AJAX. Please try again!.</div>")

            //hide spinner
            $("#spinner").css("display","none")

            //show message
            $("#signupmessage").slideDown()
        }
    });
});


//Ajax Call for the login form
//Once the form is submitted
$("#loginform").submit(function(event){ 
    //hide message
    $("#loginmessage").hide();

    //show spinner
    $("#spinner").css("display", "block");

    //prevent default php processing
    event.preventDefault();

    //collect user inputs
    var datatopost = $(this).serializeArray();

    // console.log(datatopost);
    //send them to login.php using AJAX
    $.ajax({
        url: "lib/login.php",
        type: "POST",
        data: datatopost,
        success: function(data){
            if(data == "success"){
                window.location = "loggedin.php";//to change later
            }else{
                $('#loginmessage').html(data); 

                //hide spinner
                $("#spinner").css("display", "none");

                //show message
                $("#loginmessage").slideDown();
            }
        },
        error: function(){
            $("#loginmessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");

            //hide spinner
            $("#spinner").css("display", "none");

            //show message
            $("#loginmessage").slideDown();
            
        }
    
    });

});


//Ajax Call for the forgot password form
//Once the form is submitted
$("#forgotpasswordform").submit(function(event){ 
    //hide message
    $("#forgotpasswordmessage").hide();

    //show spinner
    $("#spinner").css("display", "block");

    //prevent default php processing
    event.preventDefault();

    //collect user inputs
    var datatopost = $(this).serializeArray();

    //console.log(datatopost);
    //send them to signup.php using AJAX
    $.ajax({
        url: "lib/forgotpassword.php",
        type: "POST",
        data: datatopost,
        success: function(data){
            $('#forgotpasswordmessage').html(data);

            //hide spinner
            $("#spinner").css("display", "none");

            //show message
            $("#forgotpasswordmessage").slideDown();
        },
        error: function(){
            $("#forgotpasswordmessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");

            //hide spinner
            $("#spinner").css("display", "none");

            //show message
            $("#forgotpasswordmessage").slideDown();
        }
    
    });

});


//Ajax Call for storeresetpassword
//Once the form is submitted
$("#passwordreset").submit(function(event){ 
    //prevent default php processing
    event.preventDefault();

    //collect user inputs
    var datatopost = $(this).serializeArray();
    console.log(datatopost);
    
    //send them to storeresetpassword.php using AJAX
    $.ajax({
        url: "../lib/storeresetpassword.php",
        type: "POST",
        data: datatopost,
        success: function(data){

            $('#resultmessage').html(data);
        },
        error: function(){
            $("#resultmessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");

        }

    });

});   