
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
    $("#in_emp_id, #in_work_add_id,#in_occupation_id,#in_hpweek_id").attr("disabled","disabled");
    }
    if($(this).attr("checked")==false)
    {
  
    $("#in_emp_id, #in_work_add_id,#in_occupation_id,#in_hpweek_id").removeAttr("disabled");    
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
                //alert(statevalue);
               $(ObjElement).val(URLDecode(statevalue));
            }else if($(ObjElement).attr('type')=='radio'){ 
                // Need to check
                 $("input").each(function(){
                  var elementname = $(this);
                    if($(elementname).attr("name")==prop && $(elementname).attr("value")==URLDecode(statevalue))
                        $(elementname).attr("checked", "checked");
                } );
            } else if($(ObjElement).attr('type')=='checkbox' ){
                if($(ObjElement).attr('name') == prop && $(ObjElement).attr('value')== URLDecode(statevalue)){
                    //alert($(ObjElement).attr('name')+'  '+$(ObjElement).attr('type')+'  '+statevalue+' '+prop+' '+$(ObjElement).attr('value'));
                    $(ObjElement).attr("checked", "checked");
                }
                    
            } else {
               $(ObjElement).val(URLDecode(statevalue));
            }
            // For Textarea
            var ObjElement=$("textarea[name="+prop+"]");
                $(ObjElement).val(URLDecode(statevalue));
            // For Select Option
            var ObjElement=$("select[name="+prop+"]");
                $(ObjElement).val(URLDecode(statevalue));
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

function previous_page(formName, nextPage)
{
    document.getElementById("nextPage").value=nextPage;
    document.getElementById("closeChild").value=0;
    $("#"+formName).submit();
}
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
$(elemTot).css("border","solid 2px red"); 
$(elemTot).focus();

return false; 

}

if(document.intakeform.elements[i].selectedIndex==0 && elem=="SELECT" && !$(elemTot).attr("disabled") && elemTot.className!="year" && elemTot.className!="country")
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
$(elemTot).css("border","solid 2px red");
$(elemTot).focus();
return false; 
}
if(document.intakeform.elements[i].value=="" && elem=="INPUT" && elemTot.type=="text" && $('.nonmandatorytextboxother').val()=='')
{
$("#msg").show();
$("#msg").css({marginTop:$(elemTot).offset().top+"px", marginLeft:$(elemTot).offset().left-15+"px"});  
$(elemTot).css("border","solid 2px red"); 
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
var str='';
var slash=/\\\\/gi;

function URLDecode(psEncodeString)
{
    
	// Create a regular expression to search all +s in the string
//alert(psEncodeString);
	var slash=/\\/gi;
	   str=psEncodeString;
	   str = str.replace(/\+/g, " ");	
	   str= str.replace(/&#039;/gi,"'");
	   str= str.replace(/\%5C/gi,"'");
	   str= str.replace(/\%27/gi,"");
	   str= str.replace(/\%22/gi,'"');
	   str= str.replace(/&quot;/gi,'"');
	   str= str.replace(/&lt;/gi,"<");
	   str= str.replace(/&gt;/gi,">");
       str= str.replace(/&amp;/gi,"&");
       str= str.replace(slash,"");
       str= str.replace(/%2F/,"/");
       str= str.replace(/%20/gi," ");
    	str= str.replace(/%21/gi,"!");
    	str= str.replace(/%22/gi,'"');
    	str= str.replace(/%23/gi,"#");
    	str= str.replace(/%24/gi,"$");
    	str= str.replace(/%25/gi,"%");
    	str= str.replace(/%26/gi,"&");
    	str= str.replace(/%27/gi,"'");
    	str= str.replace(/%28/gi,"(");
    	str= str.replace(/%29/gi,")");
    	str= str.replace(/%2A/gi,"*");
    	str= str.replace(/%2B/gi,"+");
    	str= str.replace(/%2C/gi,",");
    	str= str.replace(/%2D/gi,"-");
    	str= str.replace(/%2E/gi,".");
    	
    	str= str.replace(/%30/gi,"0");
    	str= str.replace(/%31/gi,"1");
    	str= str.replace(/%32/gi,"2");
    	str= str.replace(/%33/gi,"3");
    	str= str.replace(/%34/gi,"4");
    	str= str.replace(/%35/gi,"5");
    	str= str.replace(/%36/gi,"6");
    	str= str.replace(/%37/gi,"7");
    	str= str.replace(/%38/gi,"8");
    	str= str.replace(/%39/gi,"9");
    	str= str.replace(/%3A/gi,":");
    	str= str.replace(/%3B/gi,";");
    	str= str.replace(/%3C/gi,"<");
    	str= str.replace(/%3D/gi,"=");
   	str= str.replace(/%3E/gi,">");
    	str= str.replace(/%3F/gi,"?");
   	str= str.replace(/%40/gi,"@");
    	str= str.replace(/%41/gi,"A");
   	str= str.replace(/%42/gi,"B");
    	str= str.replace(/%43/gi,"C");
    	str= str.replace(/%44/gi,"D");
    	str= str.replace(/%45/gi,"E");
    	str= str.replace(/%46/gi,"F");
    	str= str.replace(/%47/gi,"G");
    	str= str.replace(/%48/gi,"H");
    	str= str.replace(/%49/gi,"I");
    	str= str.replace(/%4A/gi,"J");
    	str= str.replace(/%4B/gi,"K");
    	str= str.replace(/%4C/gi,"L");
    	str= str.replace(/%4D/gi,"M");
    	str= str.replace(/%4E/gi,"N");
    	str= str.replace(/%4F/gi,"O");
    	str= str.replace(/%50/gi,"P");
    	str= str.replace(/%51/gi,"Q");
    	str= str.replace(/%52/gi,"R");
    	str= str.replace(/%53/gi,"S");
    	str= str.replace(/%54/gi,"T");
    	str= str.replace(/%55/gi,"U");
    	str= str.replace(/%56/gi,"V");
    	str= str.replace(/%57/gi,"W");
    	str= str.replace(/%58/gi,"X");
    	str= str.replace(/%59/gi,"Y");
    	str= str.replace(/%5A/gi,"Z");
    	str= str.replace(/%5B/gi,"[");
    	
    	str= str.replace(/%5D/gi,"]");
    	str= str.replace(/%5E/gi,"^");
   	str= str.replace(/%5F/gi,"_");
   	str= str.replace(/%60/gi,"`");
    	str= str.replace(/%61/gi,"a");
    	str= str.replace(/%62/gi,"b");
    	str= str.replace(/%63/gi,"c");
    	str= str.replace(/%64/gi,"d");
    	str= str.replace(/%65/gi,"e");
    	str= str.replace(/%66/gi,"f");
    	str= str.replace(/%67/gi,"g");
    	str= str.replace(/%68/gi,"h");
    	str= str.replace(/%69/gi,"i");
    	str= str.replace(/%6A/gi,"j");
    	str= str.replace(/%6B/gi,"k");
    	str= str.replace(/%6C/gi,"l");
    	str= str.replace(/%6D/gi,"m");
    	str= str.replace(/%6E/gi,"n");
    	str= str.replace(/%6F/gi,"o");
    	str= str.replace(/%70/gi,"p");
    	str= str.replace(/%71/gi,"q");
    	str= str.replace(/%72/gi,"r");
    	str= str.replace(/%73/gi,"s");
    	str= str.replace(/%74/gi,"t");
    	str= str.replace(/%75/gi,"u");
    	str= str.replace(/%76/gi,"v");
    	str= str.replace(/%77/gi,"w");
    	str= str.replace(/%78/gi,"x");
    	str= str.replace(/%79/gi,"y");
    	str= str.replace(/%7A/gi,"z");
    	str= str.replace(/%7B/gi,"{");
    	str= str.replace(/%7C/gi,"|");
    	str= str.replace(/%7D/gi,"|");
    	str= str.replace(/%7E/gi,"~");
     	str= str.replace(/%7F/gi,"");
    	str= str.replace(/%80/gi,"€");
     	str= str.replace(/%81/gi,"");
    	str= str.replace(/%82/gi,"‚");
    	str= str.replace(/%83/gi,"ƒ");
    	str= str.replace(/%84/gi,"„");
    	str= str.replace(/%85/gi,"…");
    	str= str.replace(/%86/gi,"†");
    	str= str.replace(/%87/gi,"‡");
    	str= str.replace(/%88/gi,"ˆ");
    	str= str.replace(/%89/gi,"‰");
    	str= str.replace(/%8A/gi,"Š");
    	str= str.replace(/%8B/gi,"‹");
    	str= str.replace(/%8C/gi,"Œ");
     	str= str.replace(/%8D/gi,"");
    	str= str.replace(/%8E/gi,"Ž");
     	str= str.replace(/%8F/gi,"");
     	str= str.replace(/%90/gi,"");
    	str= str.replace(/%91/gi,"‘");
    	str= str.replace(/%92/gi,"’");
    	str= str.replace(/%93/gi,'“');
    	str= str.replace(/%94/gi,'”');
    	str= str.replace(/%95/gi,"•");
    	str= str.replace(/%96/gi,"–");
    	str= str.replace(/%97/gi,"—");
    	str= str.replace(/%98/gi,"˜");
    	str= str.replace(/%99/gi,"™");
    	str= str.replace(/%9A/gi,"š");
    	str= str.replace(/%9B/gi,"›");
    	str= str.replace(/%9C/gi,"œ");
     	str= str.replace(/%9D/gi,"");
    	str= str.replace(/%9E/gi,"ž");
    	str= str.replace(/%9F/gi,"Ÿ");
     	str= str.replace(/%A0/gi," ");
    	str= str.replace(/%A1/gi,"¡");
    	str= str.replace(/%A2/gi,"¢");
    	str= str.replace(/%A3/gi,"£");
     	str= str.replace(/%A4/gi,"");
    	str= str.replace(/%A5/gi,"¥");
    	str= str.replace(/%A6/gi,"|");
    	str= str.replace(/%A7/gi,"§");
    	str= str.replace(/%A8/gi,"¨");
    	str= str.replace(/%A9/gi,"©");
    	str= str.replace(/%AA/gi,"ª");
    	str= str.replace(/%AB/gi,"«");
    	str= str.replace(/%AC/gi,"¬");
    	str= str.replace(/%AD/gi,"¯");
    	str= str.replace(/%AE/gi,"®");
    	str= str.replace(/%AF/gi,"¯");
    	str= str.replace(/%B0/gi,"°");
    	str= str.replace(/%B1/gi,"±");
    	str= str.replace(/%B2/gi,"²");
    	str= str.replace(/%B3/gi,"³");
    	str= str.replace(/%B4/gi,"´");
    	str= str.replace(/%B5/gi,"µ");
    	str= str.replace(/%B6/gi,"¶");
    	str= str.replace(/%B7/gi,"·");
    	str= str.replace(/%B8/gi,"¸");
    	str= str.replace(/%B9/gi,"¹");
    	str= str.replace(/%BA/gi,"º");
    	str= str.replace(/%BB/gi,"»");
    	str= str.replace(/%BC/gi,"¼");
    	str= str.replace(/%BD/gi,"½");
    	str= str.replace(/%BE/gi,"¾");
    	str= str.replace(/%BF/gi,"¿");
    	str= str.replace(/%C0/gi,"À");
    	str= str.replace(/%C1/gi,"Á");
    	str= str.replace(/%C2/gi,"Â");
    	str= str.replace(/%C3/gi,"Ã");
    	str= str.replace(/%C4/gi,"Ä");
    	str= str.replace(/%C5/gi,"Å");
    	str= str.replace(/%C6/gi,"Æ");
    	str= str.replace(/%C7/gi,"Ç");
    	str= str.replace(/%C8/gi,"È");
    	str= str.replace(/%C9/gi,"É");
    	str= str.replace(/%CA/gi,"Ê");
    	str= str.replace(/%CB/gi,"Ë");
    	str= str.replace(/%CC/gi,"Ì");
    	str= str.replace(/%CD/gi,"Í");
    	str= str.replace(/%CE/gi,"Î");
    	str= str.replace(/%CF/gi,"Ï");
    	str= str.replace(/%D0/gi,"Ð");
    	str= str.replace(/%D1/gi,"Ñ");
    	str= str.replace(/%D2/gi,"Ò");
    	str= str.replace(/%D3/gi,"Ó");
    	str= str.replace(/%D4/gi,"Ô");
    	str= str.replace(/%D5/gi,"Õ");
    	str= str.replace(/%D6/gi,"Ö");
     	str= str.replace(/%D7/gi," ");
    	str= str.replace(/%D8/gi,"Ø");
    	str= str.replace(/%D9/gi,"Ù");
    	str= str.replace(/%DA/gi,"Ú");
    	str= str.replace(/%DB/gi,"Û");
    	str= str.replace(/%DC/gi,"Ü");
    	str= str.replace(/%DD/gi,"Ý");
    	str= str.replace(/%DE/gi,"Þ");
    	str= str.replace(/%DF/gi,"ß");
    	str= str.replace(/%E0/gi,"à");
    	str= str.replace(/%E1/gi,"á");
    	str= str.replace(/%E2/gi,"â");
    	str= str.replace(/%E3/gi,"ã");
    	str= str.replace(/%E4/gi,"ä");
    	str= str.replace(/%E5/gi,"å");
    	str= str.replace(/%E6/gi,"æ");
    	str= str.replace(/%E7/gi,"ç");
    	str= str.replace(/%E8/gi,"è");
    	str= str.replace(/%E9/gi,"é");
    	str= str.replace(/%EA/gi,"ê");
    	str= str.replace(/%EB/gi,"ë");
    	str= str.replace(/%EC/gi,"ì");
    	str= str.replace(/%ED/gi,"í");
    	str= str.replace(/%EE/gi,"î");
    	str= str.replace(/%EF/gi,"ï");
    	str= str.replace(/%F0/gi,"ð");
    	str= str.replace(/%F1/gi,"ñ");
    	str= str.replace(/%F2/gi,"ò");
    	str= str.replace(/%F3/gi,"ó");
    	str= str.replace(/%F4/gi,"ô");
    	str= str.replace(/%F5/gi,"õ");
    	str= str.replace(/%F6/gi,"ö");
    	str= str.replace(/%F7/gi,"÷");
    	str= str.replace(/%F8/gi,"ø");
    	str= str.replace(/%F9/gi,"ù");
    	str= str.replace(/%FA/gi,"ú");
    	str= str.replace(/%FB/gi,"û");
    	str= str.replace(/%FC/gi,"ü");
    	str= str.replace(/%FD/gi,"ý");
    	str= str.replace(/%FE/gi,"þ");
	   
	  if (psEncodeString.match(/\+/gi))
		     {	 
		  	   var lsRegExp = /\+/gi;
		 	   var str=unescape(String(psEncodeString).replace(lsRegExp, " "));
		       str= str.replace(/&#039;/gi,"'");
	 		   str= str.replace(/&quot;/gi,'"');
	 		   str= str.replace(/&lt;/gi,"<");
	 		   str= str.replace(/&gt;/gi,">");
	 		   str= str.replace(/&amp;/gi,"&");
	 		   str= str.replace(slash,"");
		 		  str= str.replace(/%2F/,"/");
		 		 str= str.replace(/\%5C/gi,"'");
		 		 str= str.replace(/\%27/gi,"");
		    	   str= str.replace(/\%22/gi,'"');
		    	   str= str.replace(/%20/gi," ");
		    	 	str= str.replace(/%21/gi,"!");
		    	 	str= str.replace(/%22/gi,'"');
		    	 	str= str.replace(/%23/gi,"#");
		    	 	str= str.replace(/%24/gi,"$");
		    	 	str= str.replace(/%25/gi,"%");
		    	 	str= str.replace(/%26/gi,"&");
		    	 	str= str.replace(/%27/gi,"'");
		    	 	str= str.replace(/%28/gi,"(");
		    	 	str= str.replace(/%29/gi,")");
		    	 	str= str.replace(/%2A/gi,"*");
		    	 	str= str.replace(/%2B/gi,"+");
		    	 	str= str.replace(/%2C/gi,",");
		    	 	str= str.replace(/%2D/gi,"-");
		    	 	str= str.replace(/%2E/gi,".");
		    	 	str= str.replace(/%2F/gi,"/");
		    	 	str= str.replace(/%30/gi,"0");
		    	 	str= str.replace(/%31/gi,"1");
		    	 	str= str.replace(/%32/gi,"2");
		    	 	str= str.replace(/%33/gi,"3");
		    	 	str= str.replace(/%34/gi,"4");
		    	 	str= str.replace(/%35/gi,"5");
		    	 	str= str.replace(/%36/gi,"6");
		    	 	str= str.replace(/%37/gi,"7");
		    	 	str= str.replace(/%38/gi,"8");
		    	 	str= str.replace(/%39/gi,"9");
		    	 	str= str.replace(/%3A/gi,":");
		    	 	str= str.replace(/%3B/gi,";");
		    	 	str= str.replace(/%3C/gi,"<");
		    	 	str= str.replace(/%3D/gi,"=");
		    		str= str.replace(/%3E/gi,">");
		    	 	str= str.replace(/%3F/gi,"?");
		    		str= str.replace(/%40/gi,"@");
		    	 	str= str.replace(/%41/gi,"A");
		    		str= str.replace(/%42/gi,"B");
		    	 	str= str.replace(/%43/gi,"C");
		    	 	str= str.replace(/%44/gi,"D");
		    	 	str= str.replace(/%45/gi,"E");
		    	 	str= str.replace(/%46/gi,"F");
		    	 	str= str.replace(/%47/gi,"G");
		    	 	str= str.replace(/%48/gi,"H");
		    	 	str= str.replace(/%49/gi,"I");
		    	 	str= str.replace(/%4A/gi,"J");
		    	 	str= str.replace(/%4B/gi,"K");
		    	 	str= str.replace(/%4C/gi,"L");
		    	 	str= str.replace(/%4D/gi,"M");
		    	 	str= str.replace(/%4E/gi,"N");
		    	 	str= str.replace(/%4F/gi,"O");
		    	 	str= str.replace(/%50/gi,"P");
		    	 	str= str.replace(/%51/gi,"Q");
		    	 	str= str.replace(/%52/gi,"R");
		    	 	str= str.replace(/%53/gi,"S");
		    	 	str= str.replace(/%54/gi,"T");
		    	 	str= str.replace(/%55/gi,"U");
		    	 	str= str.replace(/%56/gi,"V");
		    	 	str= str.replace(/%57/gi,"W");
		    	 	str= str.replace(/%58/gi,"X");
		    	 	str= str.replace(/%59/gi,"Y");
		    	 	str= str.replace(/%5A/gi,"Z");
		    	 	str= str.replace(/%5B/gi,"[");
		    	 	str= str.replace(/%5D/gi,"]");
		    	 	str= str.replace(/%5E/gi,"^");
		    		str= str.replace(/%5F/gi,"_");
		    		str= str.replace(/%60/gi,"`");
		    	 	str= str.replace(/%61/gi,"a");
		    	 	str= str.replace(/%62/gi,"b");
		    	 	str= str.replace(/%63/gi,"c");
		    	 	str= str.replace(/%64/gi,"d");
		    	 	str= str.replace(/%65/gi,"e");
		    	 	str= str.replace(/%66/gi,"f");
		    	 	str= str.replace(/%67/gi,"g");
		    	 	str= str.replace(/%68/gi,"h");
		    	 	str= str.replace(/%69/gi,"i");
		    	 	str= str.replace(/%6A/gi,"j");
		    	 	str= str.replace(/%6B/gi,"k");
		    	 	str= str.replace(/%6C/gi,"l");
		    	 	str= str.replace(/%6D/gi,"m");
		    	 	str= str.replace(/%6E/gi,"n");
		    	 	str= str.replace(/%6F/gi,"o");
		    	 	str= str.replace(/%70/gi,"p");
		    	 	str= str.replace(/%71/gi,"q");
		    	 	str= str.replace(/%72/gi,"r");
		    	 	str= str.replace(/%73/gi,"s");
		    	 	str= str.replace(/%74/gi,"t");
		    	 	str= str.replace(/%75/gi,"u");
		    	 	str= str.replace(/%76/gi,"v");
		    	 	str= str.replace(/%77/gi,"w");
		    	 	str= str.replace(/%78/gi,"x");
		    	 	str= str.replace(/%79/gi,"y");
		    	 	str= str.replace(/%7A/gi,"z");
		    	 	str= str.replace(/%7B/gi,"{");
		    	 	str= str.replace(/%7C/gi,"|");
		    	 	str= str.replace(/%7D/gi,"|");
		    	 	str= str.replace(/%7E/gi,"~");
		    	  	str= str.replace(/%7F/gi,"");
		    	 	str= str.replace(/%80/gi,"€");
		    	  	str= str.replace(/%81/gi,"");
		    	 	str= str.replace(/%82/gi,"‚");
		    	 	str= str.replace(/%83/gi,"ƒ");
		    	 	str= str.replace(/%84/gi,"„");
		    	 	str= str.replace(/%85/gi,"…");
		    	 	str= str.replace(/%86/gi,"†");
		    	 	str= str.replace(/%87/gi,"‡");
		    	 	str= str.replace(/%88/gi,"ˆ");
		    	 	str= str.replace(/%89/gi,"‰");
		    	 	str= str.replace(/%8A/gi,"Š");
		    	 	str= str.replace(/%8B/gi,"‹");
		    	 	str= str.replace(/%8C/gi,"Œ");
		    	  	str= str.replace(/%8D/gi,"");
		    	 	str= str.replace(/%8E/gi,"Ž");
		    	  	str= str.replace(/%8F/gi,"");
		    	  	str= str.replace(/%90/gi,"");
		    	 	str= str.replace(/%91/gi,"‘");
		    	 	str= str.replace(/%92/gi,"’");
		    	 	str= str.replace(/%93/gi,'“');
		    	 	str= str.replace(/%94/gi,'”');
		    	 	str= str.replace(/%95/gi,"•");
		    	 	str= str.replace(/%96/gi,"–");
		    	 	str= str.replace(/%97/gi,"—");
		    	 	str= str.replace(/%98/gi,"˜");
		    	 	str= str.replace(/%99/gi,"™");
		    	 	str= str.replace(/%9A/gi,"š");
		    	 	str= str.replace(/%9B/gi,"›");
		    	 	str= str.replace(/%9C/gi,"œ");
		    	  	str= str.replace(/%9D/gi,"");
		    	 	str= str.replace(/%9E/gi,"ž");
		    	 	str= str.replace(/%9F/gi,"Ÿ");
		    	  	str= str.replace(/%A0/gi," ");
		    	 	str= str.replace(/%A1/gi,"¡");
		    	 	str= str.replace(/%A2/gi,"¢");
		    	 	str= str.replace(/%A3/gi,"£");
		    	  	str= str.replace(/%A4/gi,"");
		    	 	str= str.replace(/%A5/gi,"¥");
		    	 	str= str.replace(/%A6/gi,"|");
		    	 	str= str.replace(/%A7/gi,"§");
		    	 	str= str.replace(/%A8/gi,"¨");
		    	 	str= str.replace(/%A9/gi,"©");
		    	 	str= str.replace(/%AA/gi,"ª");
		    	 	str= str.replace(/%AB/gi,"«");
		    	 	str= str.replace(/%AC/gi,"¬");
		    	 	str= str.replace(/%AD/gi,"¯");
		    	 	str= str.replace(/%AE/gi,"®");
		    	 	str= str.replace(/%AF/gi,"¯");
		    	 	str= str.replace(/%B0/gi,"°");
		    	 	str= str.replace(/%B1/gi,"±");
		    	 	str= str.replace(/%B2/gi,"²");
		    	 	str= str.replace(/%B3/gi,"³");
		    	 	str= str.replace(/%B4/gi,"´");
		    	 	str= str.replace(/%B5/gi,"µ");
		    	 	str= str.replace(/%B6/gi,"¶");
		    	 	str= str.replace(/%B7/gi,"·");
		    	 	str= str.replace(/%B8/gi,"¸");
		    	 	str= str.replace(/%B9/gi,"¹");
		    	 	str= str.replace(/%BA/gi,"º");
		    	 	str= str.replace(/%BB/gi,"»");
		    	 	str= str.replace(/%BC/gi,"¼");
		    	 	str= str.replace(/%BD/gi,"½");
		    	 	str= str.replace(/%BE/gi,"¾");
		    	 	str= str.replace(/%BF/gi,"¿");
		    	 	str= str.replace(/%C0/gi,"À");
		    	 	str= str.replace(/%C1/gi,"Á");
		    	 	str= str.replace(/%C2/gi,"Â");
		    	 	str= str.replace(/%C3/gi,"Ã");
		    	 	str= str.replace(/%C4/gi,"Ä");
		    	 	str= str.replace(/%C5/gi,"Å");
		    	 	str= str.replace(/%C6/gi,"Æ");
		    	 	str= str.replace(/%C7/gi,"Ç");
		    	 	str= str.replace(/%C8/gi,"È");
		    	 	str= str.replace(/%C9/gi,"É");
		    	 	str= str.replace(/%CA/gi,"Ê");
		    	 	str= str.replace(/%CB/gi,"Ë");
		    	 	str= str.replace(/%CC/gi,"Ì");
		    	 	str= str.replace(/%CD/gi,"Í");
		    	 	str= str.replace(/%CE/gi,"Î");
		    	 	str= str.replace(/%CF/gi,"Ï");
		    	 	str= str.replace(/%D0/gi,"Ð");
		    	 	str= str.replace(/%D1/gi,"Ñ");
		    	 	str= str.replace(/%D2/gi,"Ò");
		    	 	str= str.replace(/%D3/gi,"Ó");
		    	 	str= str.replace(/%D4/gi,"Ô");
		    	 	str= str.replace(/%D5/gi,"Õ");
		    	 	str= str.replace(/%D6/gi,"Ö");
		    	  	str= str.replace(/%D7/gi," ");
		    	 	str= str.replace(/%D8/gi,"Ø");
		    	 	str= str.replace(/%D9/gi,"Ù");
		    	 	str= str.replace(/%DA/gi,"Ú");
		    	 	str= str.replace(/%DB/gi,"Û");
		    	 	str= str.replace(/%DC/gi,"Ü");
		    	 	str= str.replace(/%DD/gi,"Ý");
		    	 	str= str.replace(/%DE/gi,"Þ");
		    	 	str= str.replace(/%DF/gi,"ß");
		    	 	str= str.replace(/%E0/gi,"à");
		    	 	str= str.replace(/%E1/gi,"á");
		    	 	str= str.replace(/%E2/gi,"â");
		    	 	str= str.replace(/%E3/gi,"ã");
		    	 	str= str.replace(/%E4/gi,"ä");
		    	 	str= str.replace(/%E5/gi,"å");
		    	 	str= str.replace(/%E6/gi,"æ");
		    	 	str= str.replace(/%E7/gi,"ç");
		    	 	str= str.replace(/%E8/gi,"è");
		    	 	str= str.replace(/%E9/gi,"é");
		    	 	str= str.replace(/%EA/gi,"ê");
		    	 	str= str.replace(/%EB/gi,"ë");
		    	 	str= str.replace(/%EC/gi,"ì");
		    	 	str= str.replace(/%ED/gi,"í");
		    	 	str= str.replace(/%EE/gi,"î");
		    	 	str= str.replace(/%EF/gi,"ï");
		    	 	str= str.replace(/%F0/gi,"ð");
		    	 	str= str.replace(/%F1/gi,"ñ");
		    	 	str= str.replace(/%F2/gi,"ò");
		    	 	str= str.replace(/%F3/gi,"ó");
		    	 	str= str.replace(/%F4/gi,"ô");
		    	 	str= str.replace(/%F5/gi,"õ");
		    	 	str= str.replace(/%F6/gi,"ö");
		    	 	str= str.replace(/%F7/gi,"÷");
		    	 	str= str.replace(/%F8/gi,"ø");
		    	 	str= str.replace(/%F9/gi,"ù");
		    	 	str= str.replace(/%FA/gi,"ú");
		    	 	str= str.replace(/%FB/gi,"û");
		    	 	str= str.replace(/%FC/gi,"ü");
		    	 	str= str.replace(/%FD/gi,"ý");
		    	 	str= str.replace(/%FE/gi,"þ");
			 }
		 if (psEncodeString.match(/\%5Cn/gi))  
		   {
				
			   var lsRegExp = /\%5Cn/gi;
			   var str=unescape(String(psEncodeString).replace(lsRegExp, "\n"));
			   str = str.replace(/\+/g, " ");	
			   str= str.replace(/&#039;/gi,"'");
			   str= str.replace(/&quot;/gi,'"');
			   str= str.replace(/&lt;/gi,"<");
			   str= str.replace(/&gt;/gi,">");
			   str= str.replace(/&amp;/gi,"&");
			   str= str.replace(slash,"");
			   str= str.replace(/%2F/,"/");	
			   str= str.replace(/\%5C/gi,"'");
			   str= str.replace(/\%27/gi,"");
	    	   str= str.replace(/\%22/gi,'"');
	    	   str= str.replace(/%20/gi," ");
	    	 	str= str.replace(/%21/gi,"!");
	    	 	str= str.replace(/%22/gi,'"');
	    	 	str= str.replace(/%23/gi,"#");
	    	 	str= str.replace(/%24/gi,"$");
	    	 	str= str.replace(/%25/gi,"%");
	    	 	str= str.replace(/%26/gi,"&");
	    	 	str= str.replace(/%27/gi,"'");
	    	 	str= str.replace(/%28/gi,"(");
	    	 	str= str.replace(/%29/gi,")");
	    	 	str= str.replace(/%2A/gi,"*");
	    	 	str= str.replace(/%2B/gi,"+");
	    	 	str= str.replace(/%2C/gi,",");
	    	 	str= str.replace(/%2D/gi,"-");
	    	 	str= str.replace(/%2E/gi,".");
	    	 	str= str.replace(/%2F/gi,"/");
	    	 	str= str.replace(/%30/gi,"0");
	    	 	str= str.replace(/%31/gi,"1");
	    	 	str= str.replace(/%32/gi,"2");
	    	 	str= str.replace(/%33/gi,"3");
	    	 	str= str.replace(/%34/gi,"4");
	    	 	str= str.replace(/%35/gi,"5");
	    	 	str= str.replace(/%36/gi,"6");
	    	 	str= str.replace(/%37/gi,"7");
	    	 	str= str.replace(/%38/gi,"8");
	    	 	str= str.replace(/%39/gi,"9");
	    	 	str= str.replace(/%3A/gi,":");
	    	 	str= str.replace(/%3B/gi,";");
	    	 	str= str.replace(/%3C/gi,"<");
	    	 	str= str.replace(/%3D/gi,"=");
	    		str= str.replace(/%3E/gi,">");
	    	 	str= str.replace(/%3F/gi,"?");
	    		str= str.replace(/%40/gi,"@");
	    	 	str= str.replace(/%41/gi,"A");
	    		str= str.replace(/%42/gi,"B");
	    	 	str= str.replace(/%43/gi,"C");
	    	 	str= str.replace(/%44/gi,"D");
	    	 	str= str.replace(/%45/gi,"E");
	    	 	str= str.replace(/%46/gi,"F");
	    	 	str= str.replace(/%47/gi,"G");
	    	 	str= str.replace(/%48/gi,"H");
	    	 	str= str.replace(/%49/gi,"I");
	    	 	str= str.replace(/%4A/gi,"J");
	    	 	str= str.replace(/%4B/gi,"K");
	    	 	str= str.replace(/%4C/gi,"L");
	    	 	str= str.replace(/%4D/gi,"M");
	    	 	str= str.replace(/%4E/gi,"N");
	    	 	str= str.replace(/%4F/gi,"O");
	    	 	str= str.replace(/%50/gi,"P");
	    	 	str= str.replace(/%51/gi,"Q");
	    	 	str= str.replace(/%52/gi,"R");
	    	 	str= str.replace(/%53/gi,"S");
	    	 	str= str.replace(/%54/gi,"T");
	    	 	str= str.replace(/%55/gi,"U");
	    	 	str= str.replace(/%56/gi,"V");
	    	 	str= str.replace(/%57/gi,"W");
	    	 	str= str.replace(/%58/gi,"X");
	    	 	str= str.replace(/%59/gi,"Y");
	    	 	str= str.replace(/%5A/gi,"Z");
	    	 	str= str.replace(/%5B/gi,"[");
	    	 	str= str.replace(/%5D/gi,"]");
	    	 	str= str.replace(/%5E/gi,"^");
	    		str= str.replace(/%5F/gi,"_");
	    		str= str.replace(/%60/gi,"`");
	    	 	str= str.replace(/%61/gi,"a");
	    	 	str= str.replace(/%62/gi,"b");
	    	 	str= str.replace(/%63/gi,"c");
	    	 	str= str.replace(/%64/gi,"d");
	    	 	str= str.replace(/%65/gi,"e");
	    	 	str= str.replace(/%66/gi,"f");
	    	 	str= str.replace(/%67/gi,"g");
	    	 	str= str.replace(/%68/gi,"h");
	    	 	str= str.replace(/%69/gi,"i");
	    	 	str= str.replace(/%6A/gi,"j");
	    	 	str= str.replace(/%6B/gi,"k");
	    	 	str= str.replace(/%6C/gi,"l");
	    	 	str= str.replace(/%6D/gi,"m");
	    	 	str= str.replace(/%6E/gi,"n");
	    	 	str= str.replace(/%6F/gi,"o");
	    	 	str= str.replace(/%70/gi,"p");
	    	 	str= str.replace(/%71/gi,"q");
	    	 	str= str.replace(/%72/gi,"r");
	    	 	str= str.replace(/%73/gi,"s");
	    	 	str= str.replace(/%74/gi,"t");
	    	 	str= str.replace(/%75/gi,"u");
	    	 	str= str.replace(/%76/gi,"v");
	    	 	str= str.replace(/%77/gi,"w");
	    	 	str= str.replace(/%78/gi,"x");
	    	 	str= str.replace(/%79/gi,"y");
	    	 	str= str.replace(/%7A/gi,"z");
	    	 	str= str.replace(/%7B/gi,"{");
	    	 	str= str.replace(/%7C/gi,"|");
	    	 	str= str.replace(/%7D/gi,"|");
	    	 	str= str.replace(/%7E/gi,"~");
	    	  	str= str.replace(/%7F/gi,"");
	    	 	str= str.replace(/%80/gi,"€");
	    	  	str= str.replace(/%81/gi,"");
	    	 	str= str.replace(/%82/gi,"‚");
	    	 	str= str.replace(/%83/gi,"ƒ");
	    	 	str= str.replace(/%84/gi,"„");
	    	 	str= str.replace(/%85/gi,"…");
	    	 	str= str.replace(/%86/gi,"†");
	    	 	str= str.replace(/%87/gi,"‡");
	    	 	str= str.replace(/%88/gi,"ˆ");
	    	 	str= str.replace(/%89/gi,"‰");
	    	 	str= str.replace(/%8A/gi,"Š");
	    	 	str= str.replace(/%8B/gi,"‹");
	    	 	str= str.replace(/%8C/gi,"Œ");
	    	  	str= str.replace(/%8D/gi,"");
	    	 	str= str.replace(/%8E/gi,"Ž");
	    	  	str= str.replace(/%8F/gi,"");
	    	  	str= str.replace(/%90/gi,"");
	    	 	str= str.replace(/%91/gi,"‘");
	    	 	str= str.replace(/%92/gi,"’");
	    	 	str= str.replace(/%93/gi,'“');
	    	 	str= str.replace(/%94/gi,'”');
	    	 	str= str.replace(/%95/gi,"•");
	    	 	str= str.replace(/%96/gi,"–");
	    	 	str= str.replace(/%97/gi,"—");
	    	 	str= str.replace(/%98/gi,"˜");
	    	 	str= str.replace(/%99/gi,"™");
	    	 	str= str.replace(/%9A/gi,"š");
	    	 	str= str.replace(/%9B/gi,"›");
	    	 	str= str.replace(/%9C/gi,"œ");
	    	  	str= str.replace(/%9D/gi,"");
	    	 	str= str.replace(/%9E/gi,"ž");
	    	 	str= str.replace(/%9F/gi,"Ÿ");
	    	  	str= str.replace(/%A0/gi," ");
	    	 	str= str.replace(/%A1/gi,"¡");
	    	 	str= str.replace(/%A2/gi,"¢");
	    	 	str= str.replace(/%A3/gi,"£");
	    	  	str= str.replace(/%A4/gi,"");
	    	 	str= str.replace(/%A5/gi,"¥");
	    	 	str= str.replace(/%A6/gi,"|");
	    	 	str= str.replace(/%A7/gi,"§");
	    	 	str= str.replace(/%A8/gi,"¨");
	    	 	str= str.replace(/%A9/gi,"©");
	    	 	str= str.replace(/%AA/gi,"ª");
	    	 	str= str.replace(/%AB/gi,"«");
	    	 	str= str.replace(/%AC/gi,"¬");
	    	 	str= str.replace(/%AD/gi,"¯");
	    	 	str= str.replace(/%AE/gi,"®");
	    	 	str= str.replace(/%AF/gi,"¯");
	    	 	str= str.replace(/%B0/gi,"°");
	    	 	str= str.replace(/%B1/gi,"±");
	    	 	str= str.replace(/%B2/gi,"²");
	    	 	str= str.replace(/%B3/gi,"³");
	    	 	str= str.replace(/%B4/gi,"´");
	    	 	str= str.replace(/%B5/gi,"µ");
	    	 	str= str.replace(/%B6/gi,"¶");
	    	 	str= str.replace(/%B7/gi,"·");
	    	 	str= str.replace(/%B8/gi,"¸");
	    	 	str= str.replace(/%B9/gi,"¹");
	    	 	str= str.replace(/%BA/gi,"º");
	    	 	str= str.replace(/%BB/gi,"»");
	    	 	str= str.replace(/%BC/gi,"¼");
	    	 	str= str.replace(/%BD/gi,"½");
	    	 	str= str.replace(/%BE/gi,"¾");
	    	 	str= str.replace(/%BF/gi,"¿");
	    	 	str= str.replace(/%C0/gi,"À");
	    	 	str= str.replace(/%C1/gi,"Á");
	    	 	str= str.replace(/%C2/gi,"Â");
	    	 	str= str.replace(/%C3/gi,"Ã");
	    	 	str= str.replace(/%C4/gi,"Ä");
	    	 	str= str.replace(/%C5/gi,"Å");
	    	 	str= str.replace(/%C6/gi,"Æ");
	    	 	str= str.replace(/%C7/gi,"Ç");
	    	 	str= str.replace(/%C8/gi,"È");
	    	 	str= str.replace(/%C9/gi,"É");
	    	 	str= str.replace(/%CA/gi,"Ê");
	    	 	str= str.replace(/%CB/gi,"Ë");
	    	 	str= str.replace(/%CC/gi,"Ì");
	    	 	str= str.replace(/%CD/gi,"Í");
	    	 	str= str.replace(/%CE/gi,"Î");
	    	 	str= str.replace(/%CF/gi,"Ï");
	    	 	str= str.replace(/%D0/gi,"Ð");
	    	 	str= str.replace(/%D1/gi,"Ñ");
	    	 	str= str.replace(/%D2/gi,"Ò");
	    	 	str= str.replace(/%D3/gi,"Ó");
	    	 	str= str.replace(/%D4/gi,"Ô");
	    	 	str= str.replace(/%D5/gi,"Õ");
	    	 	str= str.replace(/%D6/gi,"Ö");
	    	  	str= str.replace(/%D7/gi," ");
	    	 	str= str.replace(/%D8/gi,"Ø");
	    	 	str= str.replace(/%D9/gi,"Ù");
	    	 	str= str.replace(/%DA/gi,"Ú");
	    	 	str= str.replace(/%DB/gi,"Û");
	    	 	str= str.replace(/%DC/gi,"Ü");
	    	 	str= str.replace(/%DD/gi,"Ý");
	    	 	str= str.replace(/%DE/gi,"Þ");
	    	 	str= str.replace(/%DF/gi,"ß");
	    	 	str= str.replace(/%E0/gi,"à");
	    	 	str= str.replace(/%E1/gi,"á");
	    	 	str= str.replace(/%E2/gi,"â");
	    	 	str= str.replace(/%E3/gi,"ã");
	    	 	str= str.replace(/%E4/gi,"ä");
	    	 	str= str.replace(/%E5/gi,"å");
	    	 	str= str.replace(/%E6/gi,"æ");
	    	 	str= str.replace(/%E7/gi,"ç");
	    	 	str= str.replace(/%E8/gi,"è");
	    	 	str= str.replace(/%E9/gi,"é");
	    	 	str= str.replace(/%EA/gi,"ê");
	    	 	str= str.replace(/%EB/gi,"ë");
	    	 	str= str.replace(/%EC/gi,"ì");
	    	 	str= str.replace(/%ED/gi,"í");
	    	 	str= str.replace(/%EE/gi,"î");
	    	 	str= str.replace(/%EF/gi,"ï");
	    	 	str= str.replace(/%F0/gi,"ð");
	    	 	str= str.replace(/%F1/gi,"ñ");
	    	 	str= str.replace(/%F2/gi,"ò");
	    	 	str= str.replace(/%F3/gi,"ó");
	    	 	str= str.replace(/%F4/gi,"ô");
	    	 	str= str.replace(/%F5/gi,"õ");
	    	 	str= str.replace(/%F6/gi,"ö");
	    	 	str= str.replace(/%F7/gi,"÷");
	    	 	str= str.replace(/%F8/gi,"ø");
	    	 	str= str.replace(/%F9/gi,"ù");
	    	 	str= str.replace(/%FA/gi,"ú");
	    	 	str= str.replace(/%FB/gi,"û");
	    	 	str= str.replace(/%FC/gi,"ü");
	    	 	str= str.replace(/%FD/gi,"ý");
	    	 	str= str.replace(/%FE/gi,"þ");
		 }
		   
		  
		  // Return the decoded string
     return str;
}
  

