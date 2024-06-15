var data;

//Ajax Call for the Updateusername
$("#updateusernameform").submit(function(event){
    //hide Messahe
    $("#updateusernamemessage").hide()

    //show the spinner
    $("#spinner").css("display","block")

    //prevent deafault PHP processing
    event.preventDefault();

    //collect inputs from user
    var datatopost=$(this).serializeArray()
    console.log(datatopost)

    //send data to php file using AJAX
    $.ajax({
        url:"../lib/updateusername.php",
        type: "POST",
        data: datatopost,
        success: function(data){
            if(data)
            {
                $("#updateusernamemessage").html(data)
                //hide spinner
                $("#spinner").css("display","none")
                //show message
                $("#updateusernamemessage").slideDown()
            }
        },
        error:function()
        {
            $("#updateusernamemessage").html("<div class='alert alert-danger'>There was error calling AJAX. Please try again!.</div>")

            //hide spinner
            $("#spinner").css("display","none")

            //show message
            $("#updateusernamemessage").slideDown()
        }
    });
});


//Ajax Call for the Updateemail
$("#updateemailform").submit(function(event){
    //hide Messahe
    $("#updateemailmessage").hide()

    //show the spinner
    $("#spinner").css("display","block")

    //prevent deafault PHP processing
    event.preventDefault();

    //collect inputs from user
    var datatopost=$(this).serializeArray()
    console.log(datatopost)

    //send data to php file using AJAX
    $.ajax({
        url:"../lib/updateemail.php",
        type: "POST",
        data: datatopost,
        success: function(data){
            if(data)
            {
                $("#updateemailmessage").html(data)
                //hide spinner
                $("#spinner").css("display","none")
                //show message
                $("#updateemailmessage").slideDown()
            }
        },
        error:function()
        {
            $("#updateemailmessage").html("<div class='alert alert-danger'>There was error calling AJAX. Please try again!.</div>")

            //hide spinner
            $("#spinner").css("display","none")

            //show message
            $("#updateemailmessage").slideDown()
        }
    });
});


//Ajax Call for the Updatepassword
$("#updatepasswordform").submit(function(event){
    //hide Messahe
    $("#updatepasswordmessage").hide()

    //show the spinner
    $("#spinner").css("display","block")

    //prevent deafault PHP processing
    event.preventDefault();

    //collect inputs from user
    var datatopost=$(this).serializeArray()
    console.log(datatopost)

    //send data to php file using AJAX
    $.ajax({
        url:"../lib/updatepassword.php",
        type: "POST",
        data: datatopost,
        success: function(data){
            if(data)
            {
                $("#updatepasswordmessage").html(data)
                //hide spinner
                $("#spinner").css("display","none")
                //show message
                $("#updatepasswordmessage").slideDown()
            }
        },
        error:function()
        {
            $("#updatepasswordmessage").html("<div class='alert alert-danger'>There was error calling AJAX. Please try again!.</div>")

            //hide spinner
            $("#spinner").css("display","none")

            //show message
            $("#updatepasswordmessage").slideDown()
        }
    });
});


//Ajax Call for the Updatepassword
$("#updatepictureform").submit(function(event){
    //hide Messahe
    $("#updatepicturemessage").hide()

    //show the spinner
    $("#spinner").css("display","block")

    //prevent deafault PHP processing
    event.preventDefault();

    //collect inputs from user
    var datatopost=$(this).serializeArray()
    console.log(datatopost)

    //send data to php file using AJAX
    $.ajax({
        url:"../lib/updatepicture.php",
        type: "POST",
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success: function(data){
            if(data)
            {
                $("#updatepicturemessage").html(data)
                //hide spinner
                $("#spinner").css("display","none")
                //show message
                $("#updatepicturemessage").slideDown()
            }
        },
        error:function()
        {
            $("#updatepicturemessage").html("<div class='alert alert-danger'>There was error calling AJAX. Please try again!.</div>")

            //hide spinner
            $("#spinner").css("display","none")

            //show message
            $("#updatepicturemessage").slideDown()
        }
    });
});
