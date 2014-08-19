<script src="js/jquery.min.js"></script>  
<!-- Grey box -->
<script type="text/javascript">  
var GB_ROOT_DIR = "js/greybox/";
</script>
<script type="text/javascript" src="js/greybox/AJS.min.js"></script>
<script type="text/javascript" src="js/greybox/AJS_fx.min.js"></script>
<script type="text/javascript" src="js/greybox/gb_scripts.min.js"></script>
<link href="js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
<!-- End -->
<script type='text/javascript'>



	$(document).ready(function() { 
		
		
		$('.labresults').hide();
	   
            if(<!result_document_status>=='1'){
                
                $('.slide').hide(); 	
		$('.sliden').show(); 
		$('.labresults').show();
                
            }
            else{
                $('.slide').show(); 	
                $('.sliden').hide(); 
		$('.labresults').hide();
            }
             if(<!treatment_program_status>=='1'){
                
                $('.slideTPp').hide(); 	
                $('.slideTPn').show(); 
                $('#soapnote').show();
                
            }
            else{
                $('.slideTPp').show(); 	
                $('.slideTPn').hide(); 
                $('#soapnote').hide();
            }
             
            
            
	    
       
        
        $('#summaryservices').hide();        
        $('.slideNsum').hide();

	    
	     		 

		$('.slide').click(function() {	
			$('.slide').hide(); 	
			$('.sliden').show(); 
			$('.labresults').show();
                        
                        var patainent_id = $('#ruserid').val();
                       
                        var dataString = 'patainent_id=' + patainent_id + '&block_name=ResultDocument&staus=1';
                        //alert (dataString);return false;
                        $.ajax({
                            type: "POST",
                            url: "index.php?action=patient_section_status",
                            data: dataString,
                            success: function() {
        // return "close";
                            }
                        });
                        
                        
                        
                        
			return false;
		 });

		 $('.sliden').click(function() {		
	   		 $('.slide').show(); 	
			 $('.sliden').hide(); 
			 $('.labresults').hide();
                         
                         var patainent_id = $('#ruserid').val();
                       
                        var dataString = 'patainent_id=' + patainent_id + '&block_name=ResultDocument&staus=0';
                        //alert (dataString);return false;
                        $.ajax({
                            type: "POST",
                            url: "index.php?action=patient_section_status",
                            data: dataString,
                            success: function() {
        // return "close";
                            }
                        });
                        
                         
			 return false;
		 });

		 $('.slideTPp').click(function() {	
				$('.slideTPp').hide(); 	
				$('.slideTPn').show(); 
				$('#soapnote').show();	
                                var patainent_id = $('#ruserid').val();
                       
                                var dataString = 'patainent_id=' + patainent_id + '&block_name=TreatmentProgram&staus=1';
                                //alert (dataString);return false;
                                $.ajax({
                                    type: "POST",
                                    url: "index.php?action=patient_section_status",
                                    data: dataString,
                                    success: function() {
                // return "close";
                                    }
                                });
				return false;
			 });

			 $('.slideTPn').click(function() {		
		   		 $('.slideTPp').show(); 	
				 $('.slideTPn').hide(); 
				 $('#soapnote').hide();
                                 var patainent_id = $('#ruserid').val();
                       
                                var dataString = 'patainent_id=' + patainent_id + '&block_name=TreatmentProgram&staus=0';
                                //alert (dataString);return false;
                                $.ajax({
                                    type: "POST",
                                    url: "index.php?action=patient_section_status",
                                    data: dataString,
                                    success: function() {
                // return "close";
                                    }
                                });
                                 
				 return false;
			 });
             
         $('.slideNsum').click(function() {    
                $('.slideNsum').hide();     
                $('.slidePsum').show(); 
                $('#summaryservices').hide();        
                return false;
             });

         $('.slidePsum').click(function() {    
                $('.slidePsum').hide();     
                $('.slideNsum').show(); 
                $('#summaryservices').show();        
                return false;
             });
		 
	}); 




</script>
<script language="JavaScript" type="text/javascript"  >
function openWindow(title, url){
    GB_showCenter(title, url, 570, 970 );
    //GB_showFullScreen(title, url);
}
function add_goal(){
    GB_showCenter('Add Goal', '/index.php?action=add_goal', 125, 355 );
}
function view_goal(){
    GB_showCenter('View Goal', '/index.php?action=view_goal', 280, 570 );
}
function reminder(userId){
    var url = '/index.php?action=reminderPopup&patient_id=' + userId;
    GB_showCenter('Reminder', url , 280, 570 );
}
function stikeout(obj){
    if( obj != null ){
        var content = $('#span_' + obj.value).html();
        patient_goal_id = obj.value;
        patient='patient';
        $('#span_' + obj.value).html("<img src='/images/horizontal-preloader.gif' />");
        if( obj.checked ===  true ){
            $.post('index.php?action=update_goal',{patient_goal_id:patient_goal_id,status:2,side:patient}, function(data,status){
                //alert(data);
                        if( status == "success" ){
                            if(/success/.test(data)){
                                $('#span_' + obj.value).html(content);
                                $('#span_' + obj.value).css('text-decoration', 'line-through');
                            }
                            else{
                                $('#span_' + obj.value).html(content);
                                alert('An error occurred while processing your request. Please try again later.');
                            }
                        }        
                    }
            );
        }
        else{
            $.post('index.php?action=update_goal',{patient_goal_id:patient_goal_id,status:1}, function(data,status){
                        if( status == "success" ){
                            if(/success/.test(data)){
                                $('#span_' + obj.value).css('text-decoration', 'none');
                                $('#span_' + obj.value).html(content);
                            }
                            else{
                                alert('An error occurred while processing your request. Please try again later.');
                                $('#span_' + obj.value).html(content);
                            }
                        }        
                    }
            );
        }
        
    }
}
function show_trash(obj,display){
    if(obj != null ){
        var id = obj.id;
        id = id.substr(4);
        if( display == 1 ){
            $('#trash_' + id).css('visibility', 'visible');
        }
        else{
            $('#trash_' + id).css('visibility', 'hidden');
        }
    }
}

function del_goal(obj){
    var id = obj.id;
    id = id.substr(6);
    
    var patient_goal_id = id;
    var content = $('#span_' + id).html();
    $('#span_' + id).html("<img src='/images/horizontal-preloader.gif' />");
    $.post('index.php?action=update_goal',{patient_goal_id:patient_goal_id,status:3}, function(data,status){
                        if( status == "success" ){
                            if(/success/.test(data)){
                                //$('#div_' + id).remove();
                                 $('#div_' + id).parent().parent().remove();
                            }
                            else{
                                alert('An error occurred while processing your request. Please try again later.');
                                $('#span_' + id).html(content);
                            }
                        }        
                    }
            );
}


function reloadlab(patient_id){
	 if(patient_id != null && patient_id != "" ){
		 $.post('index.php?action=labreportlist',{patient_id:patient_id}, function(data,status){
			   
         
           if( status == "success" ){
         	  document.getElementById("labresults").innerHTML = data;
              
                 
             
           }
           else{
               alert("Ajax connection failed.");
           }    
           
              
       }
   )        
   
}
else{
   alert("Patient Id not Found.");
			
	}
}
</script>
<!--<script type="text/javascript">
$(document).ready(function(){

	//alert($(".fdtablePaginaterWrap").length);
if($(".fdtablePaginaterWrap").length!=0){
//alert('show');
	$(".max-pages-5").hide();}
else{
	$(".max-pages-5").show();	
}
	
});</script>
--><script type="text/javascript" src="js/paginate.js"></script>
<style type="text/css">
/* Demo style */
p
        {
        width:800px;
        margin:0 auto 1.6em auto;
        }
        
/* Pagination list styles */
ul.fdtablePaginater
        {
       /* display:table;*/
        list-style:none;
        padding:0;
        margin:0 auto;
        text-align:center;
        
        width:auto;
       
        }
ul.fdtablePaginater li
        {
        display:table-cell;
        padding-right:4px;
        color:#ff0000;
        list-style:none;
        
        -moz-user-select:none;
        -khtml-user-select:none;
        }
ul.fdtablePaginater li a.currentPage
        {
        border-color:#a84444 !important;
        color:#000;
        }
ul.fdtablePaginater li a:active
        {
        border-color:#222 !important;
        color:#222;
        }
ul.fdtablePaginater li a,
ul.fdtablePaginater li div
        {
        display:block;
      
        font-size:11px;
        color:#666;
        padding:0;
        margin:0;
        text-decoration:none;
        outline:none;
        font-family: Geneva,Verdana,san-serif;
        
       
        }
ul.fdtablePaginater li div
        {
        cursor:normal;
            color:#000;
       
        }
ul.fdtablePaginater li a span,
ul.fdtablePaginater li div span
        {
        display:block;
        
            color:#0069A0;
            margin-top:5px;
        
       
        }
ul.fdtablePaginater li a
        {
        cursor:pointer;
            color:#000;
            border-right:1px solid #000;
            padding:0 5px 0 2px;
        }
 ul.fdtablePaginater li a.currentPage
        {
        	border-color:#fff !important;
        	border-right:1px solid #000 !important;
}       

ul.fdtablePaginater li a:focus
        {
        color:#333;
        text-decoration:none;
        border-color:#aaa;
        }
.fdtablePaginaterWrap
        {
        text-align:center;
        clear:both;
        text-decoration:none;
        }
ul.fdtablePaginater li .next-page span,
ul.fdtablePaginater li .previous-page span,
ul.fdtablePaginater li .first-page span,
ul.fdtablePaginater li .last-page span
        {
        font-weight:normal !important;
            
        }
    ul.fdtablePaginater li div.previous-page span{color:#000 !important;} 
        ul.fdtablePaginater li div.next-page span{color:#000 !important;}     
        
/* Keep the table columns an equal size during pagination */
td.sized1
        {
        width:16em;
        text-align:left;
        }
td.sized2
        {
        width:10em;
        text-align:left;
        }
td.sized3
        {
        width:7em;
        text-align:left;
        }
tfoot td
        {
        text-align:right;
       /* font-weight:bold;*/
        text-transform:uppercase;
        letter-spacing:1px;
        }
#visibleTotal
        {
        text-align:center;
        letter-spacing:auto;
        }
* html ul.fdtablePaginater li div span,
* html ul.fdtablePaginater li div span
        {
        background:#eee;
        }
tr.invisibleRow
        {
        display:none;
        visibility:hidden;
        }
p.paginationText
        {
        font-style:oblique;
        }
 #paginate1-fdtablePaginaterWrapTop{display:none;}
 #paginate2-fdtablePaginaterWrapTop{display:none;}	

      
        
     
        
   ul.fdtablePaginater li a.currentPage span{color:#000 !important;}
    
   
 ul.fdtablePaginater li a.next-page {border-right:none; font-weight:normal !important;}
 

        
</style>
<!--[if IE]>
<style type="text/css">
ul.fdtablePaginater {display:inline-block;}
ul.fdtablePaginater {display:inline;}
ul.fdtablePaginater li {float:left;}
ul.fdtablePaginater {text-align:center;}
/*table { border-bottom:1px solid #C1DAD7 }*/
</style>
<![endif]-->
    
<!-- starting template -->

<div id="container">
  <div id="header">
    <!header>                            
  </div>
  <!sidebar>
  <div id="mainContent">
  <div style="clear:both"><br/></div>
  
<div>
<div style="float:left"><img src="images/menu_03.jpg"/></div>
<div style="float:right"><img src="images/menu_03.jpg"/></div>
<div class="menu">

<ul>
<li class="active"><a href="javascript:void(0);"><!providertype></a></li>
</ul>
</div>
</div>

	<div style="float: right; margin-top: 8px; margin-bottom: -8px; height:25px" ><!outcome_measure_link></div>
      
     <div style="clear:both;"></div>
    <div style="width:697px;">
	<div style="clear:both"></div>
	<div style="clear:both"></div>
	<div style="width: 742px;padding-top:1px;">
      <table width="100%" cellpadding="0" cellspacing="0" style="margin-right:0px;">
        <tbody>
        <!message>
        <!--<tr >
            <td colspan="2" style="border-bottom: #CCC 0px solid; color:green;padding-bottom:15px; font-size:12px; font-weight: bold;">
             You have subscribe sucessfully.
            </td>
        </tr>-->
        <tr class="list" >
            <td colspan="2" style="border-bottom: #CCC 0px solid; ">
                <!recent_message>
            </td>
        </tr>
		<tr >
            <td colspan="2" ><br></td>
          </tr>
          <tr >
            <td class="list" valign="top" width="50%" ><div style="padding-left:0px; padding-top:00px;  padding-right:10px;12px;"><!currTxPlan></div></td>
            <td  class="list" valign="top" width="50%"><!currArticleLib></td>                                    
          </tr>
          <tr>
            <td valign="top" width="50%" >&nbsp;</td>
            <td valign="top" width="50%">&nbsp;</td>                                    
          </tr>
          
          <tr  >
            <td  class="list" valign="top" width="50%" >
            <div style="padding-left:0px; padding-top:10px;  padding-right:10px;12px;"><!reminder></div>
           <div style="clear:both"><br/></div>
            <div style="padding-left:0px; padding-top:10px;  padding-right:10px;12px;" class="list">
        <!member_goal>
        <div style="clear:both"><br/></div>
    </div>
   <div style="height:10px;"></div>
    <div> <table cellpadding="0" cellspacing="0" style="100%" border=0>
            <tr 
style="background:url('../images/img-table-head-bg-rep.jpg') top left 
repeat-x; height:37px;" >
                
                
                <input type="hidden" id="ruserid" value="<!ruserid>">
             <td style="font-weight:bold; width:269px; padding:2px 0px 2px 10px;letter-spacing:1px;">
          <b class="smallH6-new" > <span name="slide" id="slide" class="slide" style="cursor: pointer;">&nbsp;+</span><span name="sliden" id="sliden" class="sliden" style="cursor: pointer;padding-left:6px;padding-right:4px;">-</span> RESULTS & DOCUMENTS</b></td>

             <td align="right" >&nbsp;&nbsp;&nbsp;<!LabResult></td>
            </tr>
            
           </table>
          <div name="labresults" id="labresults" class="labresults" style="width:357px;"><table cellpadding="5" cellspacing="0" style="width:357px;" border="0"> 
          <!labresult_display></table>
           </div></div>
           
           	<div style="clear:both"></div>
	   <div style="float:left; width:357px;">
       <br><br>
        <!--<div style="padding-left:10px;background-color:#f0f0f0;width:200px;height:230px;">
            <!consultation_section>
        </div>
        -->
        <div style="clear:both; "></div>
        <!newtreatmentplan>
        </div>
               
           
    </td>
            <td  class="list" valign="top" width="50%"><!widget>
         <!paitentomgraph> 
            </td>                                    
          </tr>
        </tbody>
      </table>
    </div>
       
	<div style="clear:both"></div>
    <div style="padding-top:25px;  ">
    <div style="float:left; display:none">
    <table  border="2" cellspacing="0" cellpadding="0">
  <tr>
    <td style="margin:0; padding:0; height:29px;" align="left" valign="bottom"><img src="images/img-rem-top.gif" align="absbottom" width="327" height="37"  /></td>
  </tr> 

  <tr>
    <td style="background:url(images/img-rem-mid.gif) repeat-y; margin:0; padding:0;">
    <div style="padding-left:10px; padding-top:10px; font-size:12px; min-height:100px;">
        <!reminder>
    </div>
    </td>
  </tr>
  <tr>
    <td style="margin:0; padding:0; height:14px;"><img src="images/img-rem-bot.gif" /></td>
  </tr>
</table>
<div style="padding-top:15px;" >
    <table  border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td  style="background:url(images/img-mygoals-top.gif) no-repeat; color:#FF0000;" align="absbottom" width="327" height="37" ><span style="padding-left:200px;"></span></td>
  </tr>
  <tr>
    <td style="background:url(images/img-rem-mid.gif) repeat-y; margin:0; padding:0;">
    <div style="padding-left:10px; padding-top:10px; font-size:12px;">
        <!member_goal>
    </div>
   
    </td>
  </tr>
  <tr>
    <td style="margin:0; padding:0; height:14px;"><img src="images/img-rem-bot.gif" /></td>
  </tr>
 
</table>
</div>
</div>

</div>

        <!--<div style="clear:both;"><span style="font-size:11px;padding-left:355px;">A Lower Score Represents Less Functional Disability</span></div>-->
  </div>
  <div style="clear:both"></div>
       <div style="float:left; width:340px;">
  <div style="clear:both"></div>
        <!ServicesSummaries>
        </div>
   </div>     
  <div id="footer"> </div>
<script language="javascript" type="text/javascript">
      $(document).ready(function() {
            <!javascript>
      });
      
</script>
<!adminNotifications>
