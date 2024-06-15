$(function(){
    //define variables
    var departureLat=0,departureLng=0,destinationLat=0,destinationLng=0;
    var data;
    var trip;
    var sloc,endloc;
    var $invoker

    // get Trips
    getTrips();
    
    //Add trip form:hide all date and time and checkboxes
    $('.regular').hide();
    $('.oneoff').hide();
    $('.time').hide();
    //hide or show inputs on the choice of trip is regular or one off
    var myradio=$('input[name="regular"]');
    myradio.click(function()
    {
        if($(this).is(':checked'))
        {
            if($(this).val()=="Y")
            {
                $('.oneoff').hide();
                $('.regular').show();
                $('.time').show(); 
            }
            else
            {
                $('.oneoff').show();
                $('.regular').hide(); 
                $('.time').show(); 
            }
        }
    });

    //click on Create Trip Button
    $('#addtripform').submit(function(event){
        //hide Messahe
        $("#results").hide()
        //show the spinner
        $("#spinner").css("display","block")
        //prevent deafault PHP processing
        event.preventDefault();
        //collect inputs from user
        data=$('#addtripform').serializeArray()
        getAddTripDepartureCoordinates();
    })

    //function to get departure coordinates
    function getAddTripDepartureCoordinates()
    {
        departureLat = olat;
        departureLng = olng;
        data.push({name:'departureLongitude', value:departureLng})
        data.push({name:'departureLatitude', value:departureLat})
        getAddTripDestinationCoordinates();
    }

    //function to get destination coordinates
    function getAddTripDestinationCoordinates()
    {
        destinationLat = dlat;
        destinationLng = dlng;
        data.push({name:'destinationLongitude',value:destinationLng})
        data.push({name:'destinationLatitude',value:destinationLat})
        submitAddTripRequest();
    }

    //function to add Trip request
    function submitAddTripRequest(){
        console.log(data);
        $.ajax({
            url: "lib/addtrips.php",
            data: data,
            type: "POST",
            success: function(data2)
            {
                console.log(data);
                if(data2)
                {
                    $('#results').html(data2);
                    $("#spinner").css("display", "none");
                    $("#results").slideDown();
                }
                else
                {
                    getTrips();
                    $("#results").hide();
                    $('#addtripModal').modal('hide');
                    $("#spinner").css("display", "none");
                    //empty form
                    $('#addtripform')[0].reset();
                }
            },
            error: function()
            {
                $("#results").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
                $("#spinner").css("display", "none");
                $("#results").fadeIn();

            }
        }); 
    }

    // create function
    function getTrips()
    {
        $("#spinner").css("display", "block");
        $.ajax({
            url: "lib/gettrips.php",
            success: function(data2)
            {
                $("#spinner").css("display", "none");
                $('#mytrips').html(data2);
                $("#mytrips").hide();
                $("#mytrips").fadeIn();
            },
            error: function()
            {
                $("#spinner").css("display", "none");
                $("#mytrips").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
                $("#mytrips").hide();
                $("#mytrips").fadeIn();

            }
        })
    }

    // Starts of Edit Trip detailes
    // Edit trip form:hide all date and time checkbox
    $('.regular2').hide();
    $('.oneoff2').hide();
    $('.time2').hide();
    //hide or show inputs on the choice of trip is regular or one off
    var myradio2=$('input[name="regular2"]');
    myradio2.click(function()
    {
        if($(this).is(':checked'))
        {
            if($(this).val()=="Y")
            {
                $('.oneoff2').hide();
                $('.regular2').show();
                $('.time2').show(); 
            }
            else
            {
                $('.oneoff2').show();
                $('.regular2').hide(); 
                $('.time2').show(); 
            }
        }
    });

    // Calendar Input
    $('#date, #date2').datepicker({
        showAnim: "fadeIn",
        numberOfMonth:1,
        dateFormat:"D d M,yy",
        minDate:+1,
        maxDate:"+12M",
        showWeek:false
    });

    // clcik on edit trip button
    $('#edittripModal').on('show.bs.modal', function(e){
        $('#result2').html("");
        $invoker = $(e.relatedTarget);
        $.ajax({
            url:"lib/gettripdetail.php",
            method: "POST",
            data: {trip_id:$invoker.data('trip_id')},
            success:function(data2)
            {
                trip = JSON.parse(data2);

                // Fill edit trip from input using AJAX return by JSON data
                formatModal();
            },
            error: function()
            {
                $("#result2").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
                $("#spinner2").css("display", "none");
                $("#result2").fadeIn();
            }
        })
    });

    // function dataModal
    function formatModal()
    {
        $('#departure2').val(trip['departure']);
        $('#destination2').val(trip['destination']);   
        $('#price2').val(trip['price']);
        $('#seatsavailable2').val(trip['seatsavailable']);
        if(trip["regular"] == "Y")
        {
            $('#yes2').prop('checked', true);
            $('#monday2').prop('checked', trip["monday"] == "1"? true:false);
            $('#tuesday2').prop('checked', trip["tuesday"] == "1"? true:false);
            $('#wednesday2').prop('checked', trip["wednesday"] == "1"? true:false);
            $('#thursday2').prop('checked', trip["thursday"] == "1"? true:false);
            $('#friday2').prop('checked', trip["friday"] == "1"? true:false);
            $('#saturday2').prop('checked', trip["saturday"] == "1"? true:false);
            $('#sunday2').prop('checked', trip["sunday"] == "1"? true:false);
            $('input[name="time2"]').val(trip["time"]);
            $('.oneoff2').hide();
            $('.regular2').show();
            $('.time2').show();
        }
        else
        {
            $('#no2').prop('checked', true);
            $('input[name = "date2"]').val(trip['date']);
            $('input[name = "time2"]').val(trip['time']);
            $('.oneoff2').show();
            $('.regular2').hide();
            $('.time2').show();
        }
    }

    // click on edit trip button
    $('#edittripform').submit(function(event){
        //hide Messahe
        $("#result2").hide();

        //show the spinner
        $("#spinner").css("display","block");

        //prevent deafault PHP processing
        event.preventDefault();
        //collect inputs from user

        data=$('#edittripform').serializeArray();
        data.push({name:'trip_id', value:$invoker.data('trip_id')});
        getEditTripDepartureCoordinates();
    })

    //function to get departure coordinates
    function getEditTripDepartureCoordinates()
    {
        departureLat = olat;
        departureLng = olng;
        data.push({name:'departureLongitude', value:departureLng})
        data.push({name:'departureLatitude', value:departureLat})
        getEditTripDestinationCoordinates();
    }

    //function to get destination coordinates
    function getEditTripDestinationCoordinates()
    {
        destinationLat = dlat;
        destinationLng = dlng;
        data.push({name:'destinationLongitude',value:destinationLng})
        data.push({name:'destinationLatitude',value:destinationLat})
        submitEditTripRequest();
    }

    //function to add Trip request
    function submitEditTripRequest(){
        console.log(data);
        $.ajax({
            url: "lib/updatetrip.php",
            data: data,
            type: "POST",
            success: function(data2)
            {
                console.log(data);
                if(data2)
                {
                    $('#result2').html(data2);
                    $("#spinner").css("display", "none");
                    $("#result2").slideDown();
                }
                else
                {
                    getTrips();
                    $("#result2").hide();
                    $('#edittripModal').modal('hide');
                    $("#spinner").css("display", "none");
                    //empty form
                    $('#edittripform')[0].reset();
                }
            },
            error: function()
            {
                $("#result2").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
                $("#spinner").css("display", "none");
                $("#result2").fadeIn();

            }
        }); 
    }

    // delete button click and AJAX Call
    $('#deletetrip').click(function()
    {
        $.ajax({
            url:"lib/deletetrips.php",
            method: "POST",
            data: {trip_id:$invoker.data('trip_id')},
            success:function()
            {
                $('#edittripModal').modal('hide')
                getTrips()
            },
            error: function()
            {
                $("#result2").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
                $("#result2").hide();
                $("#result2").fadeIn();
            }
        })
    });

    // Ajax call search form Index.php
    $('#searchform').submit(function(event){
        //hide Messahe
        $("#results").fadeOut();

        //show the spinner
        $("#spinner").css("display","block");

        //prevent deafault PHP processing
        event.preventDefault();
        //collect inputs from user

        data=$(this).serializeArray();
        console.log(data);
        getSearchTripDepartureCoordinates();
    })

    //function to get departure coordinates
    function getSearchTripDepartureCoordinates()
    {
        departureLat = olat;
        departureLng = olng;
        data.push({name:'departureLongitude', value:departureLng})
        data.push({name:'departureLatitude', value:departureLat})
        getSearchTripDestinationCoordinates();
    }

    //function to get destination coordinates
    function getSearchTripDestinationCoordinates()
    {
        destinationLat = dlat;
        destinationLng = dlng;
        data.push({name:'destinationLongitude',value:destinationLng})
        data.push({name:'destinationLatitude',value:destinationLat})
        submitSearchTripRequest();
    }

    //function to add Trip request
    function submitSearchTripRequest(){
        console.log(data);
        $.ajax({
            url: "lib/search.php",
            data: data,
            type: "POST",
            success: function(data2)
            {
                console.log(data);
                if(data2)
                {
                    $('#results').html(data2);

                    // accordion
                    $('#results').accordion({
                        icons:false,
                        active:false,
                        collapsible:true,
                        hightStyle:"content"
                    })
                    $("#spinner").css("display", "none");
                    $("#results").slideDown();
                }
            },
            error: function()
            {
                $("#results").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
                $("#spinner").css("display", "none");
                $("#results").fadeIn();

            }
        }); 
    }
})