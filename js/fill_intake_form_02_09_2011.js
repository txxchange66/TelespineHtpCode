
$(document).ready(function(){
	
                          
$("#in_had_surg1y_id,#in_had_surg2y_id,#in_had_surg3y_id,#in_had_surg4y_id,#in_had_surg5y_id,#in_had_surg6y_id").attr("disabled","disabled");
$("input#in_had_surg1_id").click(function(){
	
	
$("#in_had_surg1y_id").removeAttr("disabled");    
	
});
$("input#in_had_surg2_id").click(function(){
	
	
$("#in_had_surg2y_id").removeAttr("disabled");    
	
});
$("input#in_had_surg3_id").click(function(){		
$("#in_had_surg3y_id").removeAttr("disabled");    	
});

$("input#in_had_surg4_id").click(function(){		
$("#in_had_surg4y_id").removeAttr("disabled");    	
});

$("input#in_had_surg5_id").click(function(){		
$("#in_had_surg5y_id").removeAttr("disabled");    	
});

$("input#in_had_surg6_id").click(function(){	
$("#in_had_surg6y_id").removeAttr("disabled");    	
});
		
$("input#in_retired_id").click(function(){
   
    
    if($(this).attr("checked")==true)
    {
    $("#in_emp_id, #in_work_add_id").attr("disabled","disabled");
    }
    if($(this).attr("checked")==false)
    {
  
    $("#in_emp_id, #in_work_add_id").removeAttr("disabled");    
    }
    
    
});

$("input#in_cur_hcare_id_y").click(function(){
    $("#in_cur_hcreN_id").attr("disabled","disabled");
    $("#in_cur_hcreY_id").removeAttr("disabled");      
});				
$("input#in_cur_hcare_id_n").click(function(){
	    $("#in_cur_hcreY_id").attr("disabled","disabled");
	    $("#in_cur_hcreN_id").removeAttr("disabled");  
});
$("input#in_knwn_dis_id_n").click(function(){
   	$("#in_knw_dis_w_id").attr("disabled","disabled");
        
});
$("input#in_knwn_dis_id_y").click(function(){
   	$("#in_knw_dis_w_id").removeAttr("disabled");
});

$("input#in_exercise_id_n").click(function(){
   	$("#in_exercise_y_id,#in_exercise_h_id").attr("disabled","disabled");
          
});
$("input#in_exercise_id_y").click(function(){
   	$("#in_exercise_y_id,#in_exercise_h_id").removeAttr("disabled");
});
$("input#in_tele_id_n").click(function(){
   	$("#in_tele_h_id").attr("disabled","disabled");
         
});
$("input#in_tele_id_y").click(function(){
   	$("#in_tele_h_id").removeAttr("disabled");
});
$("input#in_read_id_n").click(function(){
   	$("#in_read_h_id").attr("disabled","disabled");
          
});
$("input#in_read_id_y").click(function(){
   	$("#in_read_h_id").removeAttr("disabled");
});
$("input#in_religoius_id_n").click(function(){
   	$("#in_religoius_y_id").attr("disabled","disabled");
         
});
$("input#in_religoius_id_y").click(function(){
   	$("#in_religoius_y_id").removeAttr("disabled");
});
$("input#in_smoked_id_n").click(function(){
   	$("#in_smoked_yrs_id,#in_smoked_h_id").attr("disabled","disabled");
         
});
$("input#in_smoked_id_y").click(function(){
   	$("#in_smoked_yrs_id,#in_smoked_h_id").removeAttr("disabled");
});
$("input#in_b_cont_id_n").click(function(){
   	$("#in_sympt_a_id").attr("disabled","disabled");
    
});
$("input#in_b_cont_id_y").click(function(){
   	$("#in_sympt_a_id").removeAttr("disabled");
});

$("input#in_b_cont_id_p").click(function(){
   	$("#in_sympt_a_id").removeAttr("disabled");
});

$("input#in_pms_id_y").click(function(){
   	$("#in_sympt_b_id").removeAttr("disabled");
});

$("input#in_pms_id_n").click(function(){
 	$("#in_sympt_b_id").attr("disabled","disabled");
});

$("input#in_pms_id_p").click(function(){
   	$("#in_sympt_b_id").removeAttr("disabled");
});






});



$(document).ready(function()
{

   
         
         for(var prop in data1){
            var statevalue=data1[prop];
            
            //alert($("input[name="+prop+"]").attr("type"));
            // For Input
            var ObjElement=$("input[name="+prop+"]");

            if($(ObjElement).attr('type')=='text' ){
                
               $(ObjElement).val(statevalue);
            }else if($(ObjElement).attr('type')=='radio'){ 
                // Need to check
                 $("input").each(function(){
                  var elementname = $(this);
                    if($(elementname).attr("name")==prop && $(elementname).attr("value")==statevalue)
                        $(elementname).attr("checked", "checked");
                } );
            } else if($(ObjElement).attr('type')=='checkbox' ){
                if($(ObjElement).attr('name') == prop && $(ObjElement).attr('value')== statevalue){
                    //alert($(ObjElement).attr('name')+'  '+$(ObjElement).attr('type')+'  '+statevalue+' '+prop+' '+$(ObjElement).attr('value'));
                    $(ObjElement).attr("checked", "checked");
                }
                    
            } else {
               $(ObjElement).val(statevalue);
            }
            // For Textarea
            var ObjElement=$("textarea[name="+prop+"]");
                $(ObjElement).val(statevalue);
            // For Select Option
            var ObjElement=$("select[name="+prop+"]");
                $(ObjElement).val(statevalue);
           }
          
 
 
 

                  
    
   /* 

    $("select").each(function(){
        var elementname = $(this);
        for(var prop in data1){
            var statevalue=data1[prop];
            
            if($(elementname).attr("name")==prop){
                $(this).val(statevalue);
                //$(this).focus();
                  }
          }
    });    
    
    $("textarea").each(function(){
    
        var elementname = $(this);
        for(var prop in data1){
            var statevalue=data1[prop];
            
            if($(elementname).attr("name")==prop){
                $(this).val(statevalue);
                //$(this).focus();
                  }
          }
    });
    
    $("input").each(function(){

        var elementname = $(this);
        for(var prop in data1){
            var statevalue=data1[prop];
            
            
            if($(elementname).attr("name")==prop){
                
                if($(elementname).attr("type")=='radio' && $(elementname).attr("value")==statevalue)
                    {
                        //alert($(elementname).attr("name"));
                        $(elementname).attr("checked", "checked");
                    }
                if($(elementname).attr("type")=='checkbox' && $(elementname).attr("value")==statevalue)
                    {
                            $(elementname).attr("checked", "checked");
                    }
                
                if($(elementname).attr("type")=='text'){
                    $(this).val(statevalue);  }
                }
          }
});
    */

});


var j=0;
var b=0; 
function validate(formName, nextPage)
{
	

  
var inputText = $("intakeform input[type=text]");
var selectText = $("intakeform select");


 
for(i=0;i<document.intakeform.elements.length;i++)
{

var elemTot = document.intakeform.elements[i];
var elem = document.intakeform.elements[i].tagName;

$(elemTot).css("border",""); 
if(document.intakeform.elements[i].value=="" && elem=="INPUT" && elemTot.type=="text" && !$(elemTot).attr("disabled") && elemTot.className!="nonmandatorytextbox" && elemTot.className!="nonmandatorytextboxother")
{
$("#msg").show();
$("#msg").css({marginTop:$(elemTot).offset().top+"px", marginLeft:$(elemTot).offset().left-15+"px"});  
$(elemTot).css("border","solid 1px red"); 
$(elemTot).focus();

return false; 

}

if(document.intakeform.elements[i].selectedIndex==0 && elem=="SELECT" && !$(elemTot).attr("disabled") && elemTot.className!="year")
{
$("#msg").show();
$("#msg").css({marginTop:$(elemTot).offset().top+"px", marginLeft:$(elemTot).offset().left-15+"px"}); 

$(elemTot).focus();

 return false; 
}

if(document.intakeform.elements[i].value=="" && elemTot.type=="textarea" && !$(elemTot).attr("disabled") && elemTot.className!="nonmandatorytextarea")
{
$("#msg").show();
$("#msg").css({marginTop:$(elemTot).offset().top+"px", marginLeft:$(elemTot).offset().left-15+"px"});  
$(elemTot).css("border","solid 1px red");
$(elemTot).focus();
return false; 
}
if(document.intakeform.elements[i].value=="" && elem=="INPUT" && elemTot.type=="text" && $('.nonmandatorytextboxother').val()=='')
{
$("#msg").show();
$("#msg").css({marginTop:$(elemTot).offset().top+"px", marginLeft:$(elemTot).offset().left-15+"px"});  
$(elemTot).css("border","solid 1px red"); 
$(elemTot).focus();
 return false; 
}

//if(elemTot.type=="checkbox" && $('.channel:checked').length == 0 && elemTot.className!="check")
//{
//
//$("#msg").show();
//$("#msg").css({marginTop:$('.channel').offset().top+"px", marginLeft:$('.channel').offset().left-15+"px"});   
//
//return false;
//}
//	
//if(elemTot.type=="checkbox" && $('.chIll:checked').length == 0 && elemTot.className!="check")
//{
//
//$("#msg").show();
//$("#msg").css({marginTop:$('.chIll').offset().top+"px", marginLeft:$('.chIll').offset().left-15+"px"});   
//
//return false;
//}



var clasRad = $(".radioCustom"); 

$(clasRad[j]).find('td').css("border","solid 0px #ffffff");
if(document.intakeform.elements[i].checked==false && elem=="INPUT" && elemTot.type=="radio")
{

        
        if($(clasRad[j]).find("input[type=radio]:checked").length==0)
        {
           
            $("#msg").show();
            $("#msg").css({marginTop:$(clasRad[j]).offset().top+"px", marginLeft:$(clasRad[j]).find("input[type=radio]:first").offset().left-15+"px"});   
            $(clasRad[j]).find('input[type=radio]:first').focus();
            //$(clasRad[j]).find('td').css("border","solid 1px red"); 
           //$(clasRad[j]).find('td').css("border","solid 1px red"); 
           //$(clasRad[j]).find('td').contains('input[type=radio]').css("border","solid 1px red");
            return false; 
        
        }
        else if($(clasRad[j]).find("input[type=radio]:checked").length==1)
        {
          j++; 
        }
        
 
}




}

document.getElementById("nextPage").value=nextPage;
document.getElementById("closeChild").value=0;
$("#"+formName).submit();

}
   

