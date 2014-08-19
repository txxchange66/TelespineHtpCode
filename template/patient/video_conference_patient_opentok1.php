<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

    <link href="css/outcome.css" rel="stylesheet" type="text/css">
     <title>Teleconsultation</title>
         <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
      <script>
function validate(form) {


    if (!$("input[name='providerId']").is(':checked')) {
        alert('Please Select Provider');
                    return false;    

    }else{
        return true;
    }

    
       
       
		 
    }



      
    $( document ).ready(function() {
  // Handler for .ready() called.

   
   if(/chrom(e|ium)/.test(navigator.userAgent.toLowerCase())){
 
}else{
    
   
             $("#maindiv" ).hide();
            $("#maindiv1").show();
            
            
        }
  
  
  
});  
      
      </script>
    <link rel="STYLESHEET" type="text/css" href="css/styles.css"/> 
    <style>
    .form_container tr td{padding:5px 0 5px 0;}
    .provlist{}
    .provlist ul{margin:0;padding: 0; list-style: none;}
    .provlist ul li{ padding:5px 0 5px 20px; float:left;width:46%;}
     .provlist ul li input {float: left;margin-top: 0;}
    
    </style>
    </head>
    <body>
        
  
        
        
      <form action="index.php?action=patient_video_conference" method="post" onSubmit="return validate(this);">
          <div style="border:2px solid #c2c2c2; border-top:0; margin:20px;" id="maindiv">
              <div  class="largeH6" style="padding-left: 5px; padding-top: 0px; font-size: 13px; letter-spacing: 1px;">Please Select Provider</div>
              <div style="height:10px;">&nbsp;</div>
              <div class="provlist"><ul><!providerlist></ul></div>
              <div style="height:20px; clear:both;">&nbsp;</div>
             
              <div style="text-align: center; padding: 0px 0 20px 0;">  <input name="step1" value="1" type="hidden">         
    <input name="Submit" value="Start Call" type="submit"> </div>
    
                      
</div>
      </form>
        


        <div style="border:2px solid #c2c2c2; border-top:0; margin:20px;display:none" id="maindiv1">
          <div  class="largeH6" style="padding-left: 5px; padding-top: 0px; font-size: 13px; letter-spacing: 1px;"></div>  
            <p>&nbsp;</p>
            It appears as if you are not using the Chrome browser from Google. This is a very popular, fast, and secure browser that supports the video teleconferencing standard we use (called WebRTC). Please <a href="http://www.google.com/chrome">click HERE</a>  to download Chrome.
            <p>&nbsp;</p>
            
        </div>  
        

    </body>

</html>