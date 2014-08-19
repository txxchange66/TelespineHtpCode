<!DOCTYPE html>
<html lang="en" ng-app>
    <head>
        <meta charset="utf-8">
        <title>TeleSpine</title>
        <meta name="description" content="TeleSpine Login. The TeleSpine program is a web-based telehealth service putting a major dent in healthcare costs for employees with LBP. It is a 8 week online coaching program, evidence-based and patient centric, and staffed by certified providers who are experts in LBP and telehealth. The TeleSpine healthcare service was developed to remove barriers to high quality care and achieve equal or better outcomes compared to traditional, office-based LBP care at a significantly reduced cost and risk.">
        <meta name="keyword" content="telespine,spine,back pain,therapy,oswestery">
        <meta name="copyright" CONTENT="Copyright &copy; 2010-2014 TeleSpine, and affiliates. All rights reserved.">
        <meta http-equiv="EXPIRES" CONTENT="Mon, 7 Jul 2014 11:59:59 GMT">
        <!-- start: Favicon and Touch Icons -->
        <meta name="viewport" content="initial-scale=0.94, maximum-scale=0.94, user-scalable=no">
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
        <link rel="apple-touch-icon" href="assets/img/icon-telespine-128.png"/>
        <link rel="shortcut icon" href="assets/img/icon-telespine-16.png">
        <meta name="thumbnail" content="assets/img/telespine-logo-thumb.png" />
        <PageMap>
            <DataObject type="thumbnail">
                <Attribute name="src" value="assets/img/telespine-logo-thumb.png"/>
                <Attribute name="width" value="100"/>
                <Attribute name="height" value="100"/>
            </DataObject>
        </PageMap>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/css/style.min.css" rel="stylesheet">
        <link href="assets/css/retina.min.css" rel="stylesheet">
        <!-- end: CSS -->
        <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <script src="assets/js/respond.min.js"></script>
        <script src="assets/js/modernizr.custom.47853.js"></script>
        <script src="assets/css/ie6-8.css"></script>
        <script src="assets/css/normalize.css"></script>
<![endif]-->
    <!--[if lt IE 9]>
      <link href="assets/css/ie8-styles.min.css" rel="stylesheet">
      <script src="assets/js/ie8-scripts.min.js"></script>
    <![endif]-->
        <style>
            .input-large{
                color:#555 !important;
            }
            .login-box input[type="text"], .login-box input[type="password"], .login-box input[type="email"] {
                border: none;
                background: #e4e6eb;
                -webkit-box-shadow: none;
                -moz-box-shadow: none;
                box-shadow: none;
                -webkit-border-radius: 2px;
                -moz-border-radius: 2px;
                border-radius: 2px;
                padding: 0px 10px;
                height: 40px;
                margin: 5px auto;
                color:#555 !important;
                outline:none;
            }
            .errorMessage {
                position:relative;
                display: none;
                border-radius: 15px;
                background-color: rgba(255,70,73,0.81);
                color: #fff;
                /*border: 2px solid rgba(215,34,37,0.81);*/
                padding: 14px 22px 14px 42px;
                background: rgba(255,0,4,0.81);
                margin: 8px 0px 8px 0px;
                font-size: 15px !important;
                text-shadow: 1px 1px 3px rgba(215,34,37,0.81);
            }
            .alert-icon{
                position:absolute;
                top:22%;
                left:6px;
                font-size:32px !important;
                color:rgba(245,245,245,1.00);
            }
            .fieldError {
                color: #fff !important;
                font-weight: bold;
                box-shadow: none !important;
                background-color: rgba(255,70,73,0.81) !important;
                border: 2px solid rgba(215,34,37,0.81) !important;
            }

            .fieldError::-webkit-input-placeholder {
                color:#fff !important;
                font-weight:300;
            }

        </style>
    </head>

    <body style="background-color:rgba(60,174,238,1.0);">

        <div class="container">
            <div class="row">
                <div class="row">
                    <div class="login-box" style="border-radius:8px;box-shadow:0px 0px 80px rgba(23,158,234,0.84);">
                        <h1 style="margin-top:2px;"><img src="assets/img/telespine-logo.png" alt="TeleSpine" style="max-width:300px; width:100%;padding:0px 10px;"></h1>
                        <div id="loginPanel" class="<!loginstyle>">
                            <h2 style="font-size:20px;text-align:center">Login to your account</h2>
                          <!-- START: success message from signup or other forms will appear here -->
                          <div class="<!divclass>"><p class="alert-success col-sm-12 col-md-12 col-lg-12"><!messagecontent></p></div>
                        <!-- ENDS: success message from signup or other forms will appear here -->
                            <form class="form-horizontal" id="login-form" action="index.php?action=patientlogin" method="post">
                                <fieldset>
                                    <div id="login-form-message" class="errorMessage" <!errorstyle> ><!error></div>
                                    <input class="input-large col-xs-12 required" name="username" value="" id="username" type="text" placeholder="Email address" style="border-radius:8px;color:#555 !important;" autocomplete='off' autofocus data-role="not-blank" minlength="8" maxlength="50"/>
                                    <input class="input-large col-xs-12 required" name="password" id="password" type="password" placeholder="Password" style="border-radius:8px;color:#555 !important;" value="" autocomplete='off' data-role="not-blank" minlength="6" maxlength="32"/>
                                    <div class="clearfix"></div>
                                    <label class="remember" for="remember">
                                        <input type="checkbox" id="remember" /> Remember me</label>
                                    <div class="clearfix"></div>
                                    <button type="submit" name="patientlogin" value="submitted" class="btn btn-primary col-xs-12" style="border-radius:8px;">Login</button>
                                </fieldset>
                            </form>
                            <hr>
                            <h3>Forgot Password?</h3>
                            <p><a href="javaScript:showLogin(false)">Click here to have your password resent</a></p>
                        </div>
                        <div id="forgotPasswordPanel" class="<!forgotpasswordstyle>">
                            <h2 style="font-size:20px;text-align:center">Forgot Password?</h2>
                            <p>Please provide your email address. If it's in our system, we'll send your password.</p>
                            <form class="form-horizontal" id="forgot-form" action="index.php?action=forgotpassword" method="post">
                                <fieldset>
                                    <div id="forgot-form-message" class="errorMessage" <!errorstyle1> ><!errormsg></div>
                                    <p class="alert-success"><!message></p>
                                    <input class="input-large col-xs-12 required" name="email" id="email" type="email" placeholder="Email address" style="border-radius:8px;color:#555 !important;" autocomplete='off' data-role="not-blank" minlength="8" maxlength="50"/>
                                    <div class="clearfix"></div>
                                    <button type="submit" name="forgotpassword" value="submitted" class="btn btn-primary col-xs-12" style="border-radius:8px;">Send</button>
                                </fieldset>
                            </form><br>

                            <hr>
                            <!--<h3>Back to Login</h3>-->
                            <p><h3><a href="index.php?action=patientlogin">Click here to login</a></h3></p>
                        </div>
                    </div>
                </div>
                <!--/row--> 
            </div>
            <!--/row--> 
        </div>
        <!--/container--> 

        <!-- start: JavaScript--> 
        <!--[if !IE]>--> 
        <script src="assets/js/jquery-2.0.3.min.js"></script> 
        <!--<![endif]--> 
        <!--[if IE]>
                        <script src="assets/js/jquery-1.10.2.min.js"></script>
                <![endif]--> 
        <!--[if !IE]>--> 
        <script>
            window.jQuery || document.write("<script src='assets/js/jquery-2.0.3.min.js'>" + "<" + "/script>");
        </script> 
        <!--<![endif]--> 

        <!--[if IE]>
        <script>
        window.jQuery || document.write("<script src='assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
        </script>
        <![endif]--> 
        <script src="assets/js/app.controllers.js"></script>
        <script src="assets/js/jquery-migrate-1.2.1.min.js"></script> 
        <script src="assets/js/bootstrap.min.js"></script> 
        <script src="assets/js/core.min.js"></script> 
         <script src="assets/js/inForm.js"></script>
        <!-- theme scripts --> 
        <script>
            $.fn.scrollTo = function(target, options, callback) {
                if (typeof options == 'function' && arguments.length == 2) {
                    callback = options;
                    options = target;
                }
                var settings = $.extend({
                    scrollTarget: target,
                    offsetTop: 50,
                    duration: 500,
                    easing: 'swing'
                }, options);
                return this.each(function() {
                    var scrollPane = $(this);
                    var scrollTarget = (typeof settings.scrollTarget == "number") ? settings.scrollTarget : $(settings.scrollTarget);
                    var scrollY = (typeof scrollTarget == "number") ? scrollTarget : scrollTarget.offset().top + scrollPane.scrollTop() - parseInt(settings.offsetTop);
                    scrollPane.animate({scrollTop: scrollY}, parseInt(settings.duration), settings.easing, function() {
                        if (typeof callback == 'function') {
                            callback.call(this);
                        }
                    });
                });
            }

            function showLogin(show) {
                var forgot = "#forgotPasswordPanel";
                var loginPanel = "#loginPanel";
                if (!show) {
                    $(loginPanel).slideUp(400);
                    $(forgot).slideDown(1000);
                    $("#email").focus();
                    inForm.init("forgot-form", "fieldError");
                    inForm.properties.formId = "forgot-form";
                } else {
                    $(forgot).slideUp(400);
                    $(loginPanel).slideDown(1000);
                    inForm.init("login-form", "fieldError");
                    inForm.properties.formId = "login-form";
                }
            }
            $(document).ready(function(e) {
                App.init();
                showLogin(true);
            });
        </script>
    </body>
</html>