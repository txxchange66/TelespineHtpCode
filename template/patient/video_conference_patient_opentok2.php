<!DOCTYPE HTML>
<html>
    <head>
        <title>Tele consult</title>
        <script src="http://static.opentok.com/webrtc/v2.0/js/TB.min.js" type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />



        <script type="text/javascript" charset="utf-8">

            //var subscriber_width = [120, 160, 220];
            //var subscriber_height = [90, 120, 165];
            var subscriber_width = [320, 360, 420];
            var subscriber_height = [290, 320, 365];
            var publisher;
            var loc_events;
            var streams1;
            TB.addEventListener("exception", exceptionHandler);
            var session = TB.initSession("<!session_id>");

            session.addEventListener("sessionConnected", sessionConnectedHandler);
            session.addEventListener("streamCreated", streamCreatedHandler);
            session.connect("<!apiKey>", "<!token>"); // Replace with your API key and token. 
            function sessionConnectedHandler(event) {



                subscribeToStreams(event.streams);
                $("#publisher").append("<div id='publisher_div'></div>");
                publisher = TB.initPublisher("<!apiKey>", "publisher_div");
                //publisher.addEventListener('accessAllowed', accessAllowedHandler);
                $("#publisher_div").css("width", "100%").css("height", "100%");
                session.publish(publisher);

                loc_events = event;


            }

            function streamCreatedHandler(event) {
                subscribeToStreams(event.streams);

            }

            function subscribeToStreams(streams) {



                for (var i = 0; i < streams.length; i++) {
                    var stream = streams[i];
                    if (stream.connection.connectionId != session.connection.connectionId) {
                        //session.subscribe(stream);

                        var div = document.createElement('div');
                        div.setAttribute('id', 'subscriber_div');
                        var subscriberProps = {width: 500,
                            height: 500};

                        var streamsContainer = document.getElementById('subscriber');
                        streamsContainer.appendChild(div);

                        //subscriber = session.subscribe(stream, 'stream' + stream.streamId,subscriberProps);
                        subscriber = session.subscribe(stream, 'subscriber_div');
                        $("#subscriber_div").css("width", "100%").css("height", "100%");
                    }
                }
                streams1 = streams;
            }



            function unsubscribeToStreams(streams) {


                alert(streams.length);
                for (var i = 0; i < streams.length; i++) {
                    var stream = streams[i];
                    if (stream.connection.connectionId != session.connection.connectionId) {
                        //session.subscribe(stream);
                        //subscriber_div

                        var streamsContainer = document.getElementById('subscriber');
                        streamsContainer.appendChild(div);

                        session.unsubscribe(stream);
                    }
                }

            }




            function unpublish() {
                session.unpublish(publisher);
//unsubscribeToStreams(loc_events.streams);

                $('#subscriber').hide();

                $("#publishbtn").css("display", "block");
                $("#unpublishbtn").css("display", "none");
                $("#publishbtn").css("left", $("#wrapper").width() / 2 - 62);
                $("#unpublishbtn").css("left", $("#wrapper").width() / 2 - 62);
                $("#message").html("");
                var provider_id = $('#provider_id').val();
                var patainent_id = $('#patainent_id').val();
                var dataString = 'provider_id=' + provider_id + '&patainent_id=' + patainent_id + '&call_staus=0';
//alert (dataString);return false;
                $.ajax({
                    type: "POST",
                    url: "index.php?action=chage_call_status",
                    data: dataString,
                    success: function() {
// return "close";
                    }
                });


            }
            function publish() {
//subscribeToStreams(event.streams);

                // subscribeToStreams(event.streams);
                $("#publisher").append("<div id='publisher_div'></div>");
                publisher = TB.initPublisher("<!apiKey>", "publisher_div");
                //publisher.addEventListener('accessAllowed', accessAllowedHandler);
                $("#publisher_div").css("width", "100%").css("height", "100%");
                session.publish(publisher);





                $("#publishbtn").css("display", "none");
                $("#unpublishbtn").css("display", "block");

                $("#publishbtn").css("left", $("#wrapper").width() / 2 - 62);
                $("#unpublishbtn").css("left", $("#wrapper").width() / 2 - 62);
                $("#message").html("Please wait a moment for the other party to answer.");

                var provider_id = $('#provider_id').val();
                var patainent_id = $('#patainent_id').val();
                var dataString = 'provider_id=' + provider_id + '&patainent_id=' + patainent_id + '&call_staus=1';
                //alert (dataString);return false;
                $.ajax({
                    type: "POST",
                    url: "index.php?action=chage_call_status",
                    data: dataString,
                    success: function() {
                        // return "close";
                    }
                });

                $('#subscriber').show();




            }

            function exceptionHandler(event) {
                alert("Exception: " + event.code + "::" + event.message);
            }

            $(function() {
                $("#publisher").draggable({containment: "parent"});
            });


            $(function() {
                $("#wrapper").resizable({maxHeight: 550, maxWidth: 910, minHeight: 320, minWidth: 550, stop: function(event, ui) {

                        var w = $("#wrapper").width() + 70;
                        var h = $("#wrapper").height() + 120;
                        window.resizeTo(w, h);

                        $("#subscriber").css("width", "100%").css("height", "100%");
                        $("#subscriber_div").css("width", "100%").css("height", "100%");



                        $("#publisher").offset({top: $("#wrapper").height() - 140, left: $("#wrapper").width() - 185});
                        $("#publisher").css('bottom', '10px');
                        $("#publisher").css('right', '10px');
                        $("#publishbtn").css("left", $("#wrapper").width() / 2 - 62);
                        $("#unpublishbtn").css("left", $("#wrapper").width() / 2 - 62);
                    }});
            });




            $(window).resize(function() {


                if ($("#wrapper").width() > 1150) {

                    $("#wrapper").css("width", 1150);

                }
                if ($("#wrapper").height() > 650) {


                    $("#wrapper").css("height", 650);

                }
                if ($("#wrapper").width() > 730) {


                    var w = $("#wrapper").width() + 70;
                    var h = $("#wrapper").height() + 120;
                    window.resizeTo(w, h);
                    $("#publishbtn").css("left", $("#wrapper").width() / 2 - 62);
                    $("#unpublishbtn").css("left", $("#wrapper").width() / 2 - 62);

//$("#publisher").offset({ top:270, left: 520 });

//$("#publisher").offset({ right:0, bottom: 0 });

                    $("#subscriber").css("width", "100%").css("height", "100%");
                    $("#subscriber_div").css("width", "100%").css("height", "100%");

                    $("#publisher").offset({top: $("#wrapper").height() - 150, left: $("#wrapper").width() - 200});
                    $("#publisher").css('bottom', '10px');
                    $("#publisher").css('right', '10px');
                }

                if ($("#wrapper").height() > 430) {
                    var w = $("#wrapper").width() + 70;
                    var h = $("#wrapper").height() + 120;
                    window.resizeTo(w, h);
                    $("#subscriber").css("width", "100%").css("height", "100%");
                    $("#subscriber_div").css("width", "100%").css("height", "100%");
                    //$("#publisher").offset({ right:0, bottom: 0 });

                    $("#publisher").offset({top: $("#wrapper").height() - 150, left: $("#wrapper").width() - 200});

                    $("#publishbtn").css("left", $("#wrapper").width() / 2 - 62);
                    $("#unpublishbtn").css("left", $("#wrapper").width() / 2 - 62);

                }

                if (window.screen.width<620){
                                      
                     var w = $("#wrapper").width() + 70;
                    var h = $("#wrapper").height() + 120;
                    window.resizeTo(w, h);
                }
                
               if (window.screen.height<440){
                    var w = $("#wrapper").width() + 70;
                    var h = $("#wrapper").height() + 120;
                    window.resizeTo(w, h);
                }
                         
                if($(this).width()>980 || $(this).height()>650 ){
	var w = $("#wrapper").width()+70;
	var h = $("#wrapper").height()+120;
		window.resizeTo(w,h);
	}
	if($(this).width()<$("#wrapper").width()){
		var w = $("#wrapper").width()+70;
	var h = $("#wrapper").height()+120;
		window.resizeTo(w,h);
	
	}
	if($(this).height()<$("#wrapper").height()){
		var w = $("#wrapper").width()+70;
	var h = $("#wrapper").height()+120;
		window.resizeTo(w,h);
	
	}

                if ($("#wrapper").width() < 730) {

                    $("#subscriber").css("width", "100%").css("height", "100%");
                    $("#subscriber_div").css("width", "100%").css("height", "100%");
                    //$("#publisher").offset({ right:0, bottom: 0 });

                    $("#publisher").offset({top: $("#wrapper").height() - 150, left: $("#wrapper").width() - 200});

                    $("#publishbtn").css("left", $("#wrapper").width() / 2 - 62);
                    $("#unpublishbtn").css("left", $("#wrapper").width() / 2 - 62);

                }


                if ($("#wrapper").height() < 430) {

                    $("#subscriber").css("width", "100%").css("height", "100%");
                    $("#subscriber_div").css("width", "100%").css("height", "100%");
                    //$("#publisher").offset({ right:0, bottom: 0 });

                    $("#publisher").offset({top: $("#wrapper").height() - 150, left: $("#wrapper").width() - 200});
                    $("#publishbtn").css("left", $("#wrapper").width() / 2 - 62);
                    $("#unpublishbtn").css("left", $("#wrapper").width() / 2 - 62);

                }

            });


            window.onbeforeunload = confirmExit;
            function confirmExit() {
                var provider_id = $('#provider_id').val();
                var patainent_id = $('#patainent_id').val();


// return provider_id;
//return false;


                var dataString = 'provider_id=' + provider_id + '&patainent_id=' + patainent_id + '&call_staus=0';
//alert (dataString);return false;

                $.ajax({
                    type: "POST",
                    url: "index.php?action=chage_call_status",
                    data: dataString,
                    success: function() {
// return "Closeing the Call Wait.";
                        return false;
                    }
                });
                return false;
                $.browser.chrome = /chrom(e|ium)/.test(navigator.userAgent.toLowerCase());
                if ($.browser.chrome) {
                    return "Call has been closed now.";
                } else {

                    return false;
                }

            }

            $(document).ready(function() {
// Handler for .ready() called.
                var provider_id = $('#provider_id').val();
                var patainent_id = $('#patainent_id').val();
                var dataString = 'provider_id=' + provider_id + '&patainent_id=' + patainent_id + '&call_staus=1';
//alert (dataString);return false;
                $.ajax({
                    type: "POST",
                    url: "index.php?action=chage_call_status",
                    data: dataString,
                    success: function() {
// return "close";
                    }
                });
//return false;

            });



        </script>
    </head>
    <body>
        <style>
            #resizable { width: 150px; height: 150px; padding: 0.5em; }
            #resizable h3 { text-align: center; margin: 0; }
        </style>




        <style type="text/css">
            body {
                text-align: center;
            }

            #wrapper {
                margin: 0 auto;
                position: relative;
                width: 730px;
                height: 430px;
                border:1px solid #000;
                margin:20px 0px 20px 20px;
                padding: 0px 3px 3px 0px;
            }

            #subscriber {
                position: relative;
                width: 100%;
                height: 100%;
                z-index: 1;

            }

            #publisher {
                position: absolute;
                width: 200px;
                height: 150px;
                z-index: 10;
                bottom: 10px;
                right: 10px;
            }

            #publisher_div {
                /* border: 1px solid white;*/
            }

            #subscriber_div, #publisher_div {
                width: 100%;
                height: 100%;
            }

            #publishbtn {
                position: absolute;
                z-index: 11;
                bottom: 1px;
                left: 303px;
            }

            #unpublishbtn {
                position: absolute;
                z-index: 11;
                bottom: 1px;
                left: 303px;
            }


            #message {
                position: absolute;

                top: 100px;
                left: 200px;
            }

        </style>
    </head>
<body>

    <div id='wrapper'>
        <div id="message">Please wait a moment for the other party to answer. </div>
        <div id='subscriber'></div>
        <div id='publisher'></div>
        <div id="publishbtn" style="display:none;">
            <a href="#" onclick="publish()">   <img src="images/start-call.png" align="left" /></a>
        </div>
        <div id="unpublishbtn">
            <a href="#" onclick="unpublish()"><img src="images/end-call.png" align="left" /></a>
        </div>

    </div>


    <input type="hidden" id="patainent_id" value="<!patainent_id>">
    <input type="hidden" id="provider_id" value="<!provider_id>">


</body>
</html>

