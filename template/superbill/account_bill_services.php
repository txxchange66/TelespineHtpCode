<script type="text/javascript">  
var GB_ROOT_DIR = "js/greybox/";
</script>
<script type="text/javascript" src="js/greybox/AJS.js"></script>
<script type="text/javascript" src="js/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="js/greybox/gb_scripts.js"></script>
<link href="js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
<script>
</script>
<input type="hidden"  name="showsoap" id="showsoap" value="<!shopshow>">
<!menuhead>
<script language="JavaScript" src="js/mm_menu.js"></script>
<script language="JavaScript" src="js/validateform2.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>

<script language="JavaScript">
    var jsServicesListCount=<!servicesListCount>;
    function validateServices(){
             var allnull='yes';
             var totalCountSer=parseInt(jsServicesListCount);
             var objDiv=document.getElementById('errorDiv');
             for(i=0;i<totalCountSer;i++){
                 
                // var elemenNameCode=eval('bill_services_code_id'+i);
                // var elemenNameDesc=eval('bill_services_descr_id'+i);
                // var elemenNamePrice=eval('bill_services_price_id'+i);
                 //alert(i);
                 elemenNameCode=document.getElementById('bill_services_code_id'+i);
                 elemenNameDesc=document.getElementById('bill_services_descr_id'+i);
                 elemenNamePrice=document.getElementById('bill_services_price_id'+i);
                 
                 
                 
                 elemenNameCode.style.borderColor  ='';
                 elemenNameDesc.style.borderColor  ='';
                 elemenNamePrice.style.borderColor  ='';
                 objDiv.innerHTML='';
                 var elemenNameCodeValue=trim(elemenNameCode.value);
                 var elemenNameDescValue=trim(elemenNameDesc.value);
                 var elemenNamePriceValue=trim(elemenNamePrice.value);
                 //alert(elemenNameDescValue);
                 
                 //alert(elemenNameCode.value);
                 if(elemenNameCodeValue!=null && elemenNameDescValue!=null && elemenNamePriceValue!=null ){
                	 allnull='no';
                	 //alert(elemenNameDescValue);
                    if(!V2validateData("alnumhyphen",elemenNameCode,"Please enter valid ICD9/Code with alphanumeric characters, dashes, and periods")){return false;}
                    //if(!V2validateData("alnumhyphenspace",elemenNameDesc,"Please enter valid Description. Single and Double quotes are not allowed ")){elemenNameDesc.focus();elemenNameDesc.style.borderColor  ='#FF0000';return false;}
                    if(!V2validateData("numericDecimal",elemenNamePrice,"Please enter valid Price")){ objDiv.innerHTML='';return false;}
                    if(!V2validateData("lessthan=10000",elemenNamePrice,"Price should be less than 10000")) {elemenNamePrice.focus();elemenNamePrice.style.borderColor  ='#FF0000';return false;}
                 }else if(elemenNameCodeValue==null && elemenNameDescValue==null && elemenNamePriceValue==null ){
                     
                 }else{
                   //alert('please fill the complete row [ICD9/Code,Description,Price]');  
                    if(elemenNameCodeValue==null){elemenNameCode.focus();elemenNameCode.style.borderColor  ='#FF0000'; objDiv.innerHTML='Please enter ICD9/Code'; return false;}
                    if(elemenNameDescValue==null){elemenNameDesc.focus();elemenNameDesc.style.borderColor  ='#FF0000';objDiv.innerHTML='Please enter Description';return false;}
                    if(elemenNamePriceValue==null){elemenNamePrice.focus();elemenNamePrice.style.borderColor  ='#FF0000';objDiv.innerHTML='Please enter Price';return false;}
                   
                 }
                 
             }
             if(allnull=='yes'){
            	 objDiv.innerHTML='Please enter ICD9/Code'; return false;
              }
             
             return true;
             
             
    }
    
    function AddRowService(){  
    rowId="submitButtonRow";

    var target = document.getElementById(rowId);
    var tbody = document.getElementById("serviceTableRow");
    var row = document.createElement("TR");
    var td1 = document.createElement("TD");
        var element1 = document.createElement("input");
        element1.type = "text";
        element1.name= "row"+jsServicesListCount+"[]";
        element1.id= "bill_services_code_id"+jsServicesListCount;
        element1.size= "12";
        element1.maxLength="9";
        td1.appendChild(element1);

    var td2 = document.createElement("TD");
        var element2 = document.createElement("input");
        element2.type = "text";
        element2.name= "row"+jsServicesListCount+"[]";
        element2.id= "bill_services_descr_id"+jsServicesListCount;
        element2.maxLength= "50";
        element2.size= "50";
        //maxlength="50" size="50px"        
        td2.appendChild(element2);
    
    var td3 = document.createElement("TD");
    var data3 = document.createElement("span");
        data3.innerHTML="\$&nbsp;";
        td3.appendChild(data3);
        var element3 = document.createElement("input");
        element3.type = "text";
        element3.name= "row"+jsServicesListCount+"[]";
        element3.id= "bill_services_price_id"+jsServicesListCount;
        element3.maxLength= "7";
        element3.size= "7";
        //maxlength="7" size="7" 
        td3.appendChild(element3);
    row.appendChild(td1);
    row.appendChild(td2);
    row.appendChild(td3);
    className=(jsServicesListCount%2)+1;
    row.setAttribute("class","line"+className);     
    target.parentNode.insertBefore(row, target);
    jsServicesListCount++;
    /*
    var newElement = document.createElement('tr');
    target.parentNode.insertBefore(newElement, target);
    return newElement;
    */
}

function show_trash(obj,display){
    if(obj != null ){
        var id = obj.id;
        id = id.substr(9);
        if( id !='' ){
        if( display == 1 ){
            $('#trash_' + id).css('visibility', 'visible');
            $('#edit_' + id).css('visibility', 'visible');
            
        }
        else{
            $('#trash_' + id).css('visibility', 'hidden');
            $('#edit_' + id).css('visibility', 'hidden');
        }
    }
    }
}

function del_goal(obj){
    if(!confirm("Are you sure you want to delete this service?")){
        return false;
    }
    var id = obj.id;
    id = id.substr(6);
    var jsaccount_bill_services_id = id;
    var content = $('#span_' + id).html();
    $('#span_' + id).html("<img src='/images/horizontal-preloader.gif' />");
    $.post('index.php?action=delete_account_bill_services',{account_bill_services_id:jsaccount_bill_services_id,status:3}, function(data,status){
                        
                        if( status == "success" ){
                            $('#table_id_' + id).remove();
                            
                            document.getElementById('errorDiv').innerHTML='<span style="color:green"><b>Your service has been deleted</b></span>';
                        }        
                    }
            );
}

  function edit_service(obj){
    if(!confirm("Are you sure you want to update this service?")){
        return false;
    }
     var id = obj.id;
    id = id.substr(13);
   /// alert(id);
   $('#bill_services_code_id'+id).removeAttr("readonly"); 
   $('#bill_services_descr_id'+id).removeAttr("readonly"); 
   $('#bill_services_price_id'+id).removeAttr("readonly"); 
   $('#bill_services_code_id'+id).css("background","white");
   $('#bill_services_descr_id'+id).css("background","white");
   $('#bill_services_price_id'+id).css("background","white");
    }
</script>

<div class="center-align" >
  <div id="container">
    <div id="header">
      <!header>
    </div>
    <div id="sidebar">
          <!sidebar>
        </div>
    <!-- body part starts-->
    <div id="mainContent-sec">
      <table border="0" cellpadding="0" cellspacing="0" style="vertical-align:middle; width:100%; height:81px;">
        <tr>
          <td colspan="3" style=" width:400px; height:9px;"></td>
        </tr>
        <tr>
          <td width="151" style=" width:400px;"><div id="breadcrumbNav" style="padding-left:6px;margin-top:12px;"> <a href="index.php">HOME</a> / <SOAP>CUSTOMIZE</SOAP> / <span class="highlight">BILLING</span></div></td>
          <td width="138"></td>
          <td width="173" align="right" style="padding-right:75px; margin-top:11px;height:70px;">
          &nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" valign="top" style=" width:400px; font-size: large;font-weight: bold;padding-bottom: 7px; margin:0px; vertical-align:top;">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" valign="top"  ><div>
            <!tabNavigation>
          </div></td>
        </tr>
      </table>
      
      
 <!-- best practices table starts from here-->  
 <form name="services" id="services" action="index.php" method="post" onsubmit = "return validateServices();" >
<table cellspacing="1" cellpadding="2" border="0" width="100%" class="list" id="serviceTableRow">
<!servicesListHead>
<tbody>
<!servicesListBody>
<tr  class='' id="submitButtonRow"><td colspan="2" align="right" class=''><div id="errorDiv" style="color:#FF0000;"><!error></div></td><td ><div style="align:right;"><input type="button" name="addmore" value="Add Service" onclick="AddRowService();"> <input type="submit" name="submit" name="submit" value="Save"> </div></td></tr>
</tbody></table>

<input type="hidden" name="action" value="account_bill_services">
</form>
 <!-- best practices table ends here--> 
      <div class="paging" align="right"></div>
      <!-- body part ends-->
      <!-- [/items] -->
      <div align="center" style="padding:5px;"> </div>
      <!-- [/list] -->
    </div>
  </div>
  
</div>
<script type="text/javascript">
    mmLoadMenus("<!showmenu>");
</script>