<link rel="STYLESHEET" type="text/css" href="css/styles.css">
    <meta charset="utf-8">
    <link rel="stylesheet" href="js/autocomplete/demos.css">
    <link rel="stylesheet" href="js/autocomplete/themes/base/jquery.ui.autocomplete.css">
    <link rel="stylesheet" href="js/autocomplete/themes/base/jquery.ui.button.css">
    <link rel="stylesheet" href="js/autocomplete/themes/base/jquery.ui.selectable.css">
    <link rel="stylesheet" href="js/autocomplete/themes/base/jquery.ui.core.css">
    <link rel="stylesheet" href="js/autocomplete/themes/base/jquery.ui.all.css">

    <script src="js/autocomplete/jquery-1.5.1.js"></script>
    <script src="js/autocomplete/ui/jquery.ui.core.js"></script>
    <script src="js/autocomplete/ui/jquery.ui.widget.js"></script>

    <script src="js/autocomplete/ui/jquery.ui.position.js"></script>
    <script src="js/autocomplete/ui/jquery.ui.autocomplete.js"></script>
    
    <style>
    .ui-autocomplete-loading { background: white url('images/ui-anim_basic_16x16.gif') right center no-repeat; }
    </style>
<style>
#greyBox table,th,tr,td {
    font-size: 12px;
}
</style>
<script language="JavaScript" src="js/validateform2.js"></script>
    <script>
        


    </script>
<script language="JavaScript"><!--
    var jsServicesListCount=<!servicesListCount>;
    function reloadServices(){
        
        
    }
    function validateServices(){
             
             var totalCountSer=parseInt(jsServicesListCount);
             var objDiv=document.getElementById('errorDiv');
             for(i=0;i<totalCountSer;i++){
            	 document.getElementById('bill_services_id'+i).style.borderColor=''; 
                 }

              for(i=0;i<totalCountSer;i++){
                 
                // var elemenNameCode=eval('bill_services_code_id'+i);
                // var elemenNameDesc=eval('bill_services_descr_id'+i);
                // var elemenNamePrice=eval('bill_services_price_id'+i);
                 //alert(i);
                 elemenNameCode=document.getElementById('bill_services_id'+i);
                 elemenNameDesc=document.getElementById('bill_services_id'+i+"_descr");
                 elemenNamePrice=document.getElementById('bill_services_id'+i+"_price");
                 elemenNameId=document.getElementById('bill_services_id'+i+"_id");
                 elemenNamedesval=document.getElementById('bill_services_id'+i+"_desval");
                 
                 
                 elemenNameCode.style.borderColor  ='';
                 elemenNameDesc.style.borderColor  ='';
                 elemenNamePrice.style.borderColor  ='';

                 var elemenNameCodeValue=trim(elemenNameCode.value);
                 var elemenNameDescValue=trim(elemenNameDesc.value);
                 var elemenNamePriceValue=trim(elemenNamePrice.value);
                 var elemenIdValue=trim(elemenNameId.value);
                 var elemendesvalValue=trim(elemenNamedesval.value);
                 if(elemenIdValue!=null){
                	 if(elemenIdValue!=null){
                    	 if(elemendesvalValue!=elemenNameCodeValue){
                    		 elemenNameCode.focus();elemenNameCode.style.borderColor  ='#FF0000';objDiv.innerHTML='Please select valid ICD9/Code';return false;
                        	 }
                    	
                      }
                  }
                 if(elemenNameCodeValue!=null && elemenNameDescValue!=null && elemenNamePriceValue!=null ){
                    if(!V2validateData("alnumhyphen",elemenNameCode,"Please select valid ICD9/Code")){return false;}
                    if(!V2validateData("numericDecimal",elemenNamePrice,"Please enter Price")) return false;
                 }else if(elemenNameCodeValue==null && elemenNameDescValue==null && elemenNamePriceValue==null ){
                     
                 }else{
                   //alert('please fill the complete row [ICD9/Code,Description,Price]');  
                    if(elemenNameCodeValue==null){elemenNameCode.focus();elemenNameCode.style.borderColor  ='#FF0000'; objDiv.innerHTML='Please select ICD9/Code'; return false;}
                    if(elemenNameDescValue==null){elemenNameCode.focus();elemenNameCode.style.borderColor  ='#FF0000';objDiv.innerHTML='Please select valid ICD9/Code';return false;}
                    if(elemenNamePriceValue==null){elemenNameCode.focus();elemenNameCode.style.borderColor  ='#FF0000';objDiv.innerHTML='Please select valid ICD9/Code';return false;}
                   
                 }
                 
             }
             return true;
             
             
    } 

    function URLDecode(psEncodeString)
    {
      // Create a regular expression to search all +s in the string

    		  	   var lsRegExp = /\+/gi;
    		 	   var str=unescape(String(psEncodeString).replace(lsRegExp, " "));
    		       str= str.replace(/&#039;/gi,"'");
    	 		   str= str.replace(/&quot;/gi,'"');
     	 		   str= str.replace(/&lt;/gi,"<");
    	 		   str= str.replace(/&gt;/gi,">");
    	 		   str= str.replace(/&amp;/gi,"&");
     	 		   return str;
    }
    function fillData( objItem,objID, curID) {
                 //alert(objItem+" "+objID); 
                 $("#"+$(curID).attr("id")+"_descr").val(URLDecode(objItem.descr));
                 $("#"+$(curID).attr("id")+"_price").val(objItem.price);
                 $("#"+$(curID).attr("id")+"_id").val(objItem.id);
                 $("#"+$(curID).attr("id")+"_desval").val(objItem.label);
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
        element1.id= "bill_services_id"+jsServicesListCount;
        element1.size= "12";
        element1.value="";
        element1.maxLength= "9";
        td1.appendChild(element1);
        


    var td2 = document.createElement("TD");
        var element2 = document.createElement("input");
        element2.type = "text";
        element2.name= "row"+jsServicesListCount+"[]";
        element2.id= "bill_services_id"+jsServicesListCount+"_descr";
        element2.maxLength= "50";
        element2.style.width= "300px";
        element2.readOnly="true";
        //maxlength="50" size="50px"        
        td2.appendChild(element2);
    
    var td3 = document.createElement("TD");
    var data3 = document.createElement("span");
        data3.innerHTML="\$ ";
        td3.appendChild(data3);
        var element3 = document.createElement("input");
        element3.type = "text";
        element3.name= "row"+jsServicesListCount+"[]";
        element3.id= "bill_services_id"+jsServicesListCount+"_price";
        element3.maxLength= "7";
        element3.size= "7";
        element3.readOnly="true";
        //maxlength="7" size="7" 
        td3.appendChild(element3);
        var element4=document.createElement("input");
        element4.type = "hidden";
        element4.name= "row"+jsServicesListCount+"[]";
        element4.id= "bill_services_id"+jsServicesListCount+"_id";
        td3.appendChild(element4);
        var element5=document.createElement("input");
        element5.type = "hidden";
        element5.name= "row"+jsServicesListCount+"[]";
        element5.id= "bill_services_id"+jsServicesListCount+"_desval";
        td3.appendChild(element5);
        
        
    row.appendChild(td1);
    row.appendChild(td2);
    row.appendChild(td3);
    className=(jsServicesListCount%2)+1;
    row.setAttribute("class","line"+className);     
    target.parentNode.insertBefore(row, target);
    $( "#bill_services_id"+jsServicesListCount ).autocomplete({
        source: "index.php?action=getBillingServicesData",
        minLength: 1,
        select: function( event, ui ) {  
        	//alert($(this).attr("id"));
            fillData( ui.item, $("#bill_services_id"+(jsServicesListCount-1)), $(this));
        }
    });
    jsServicesListCount++;
    
    /*
    var newElement = document.createElement('tr');
    target.parentNode.insertBefore(newElement, target);
    return newElement;
    */
}


--></script>
 <div style="position: absolute; top: 15px; left: 30px; width: 570px;line-height:1.5" id="greyBox">       
 <!-- best practices table starts from here-->  
 <form name="services" id="services" action="index.php" method="post" onsubmit = "return validateServices();" >
<table cellspacing="1" cellpadding="2" border="0" width="100%" class="list" id="serviceTableRow">
<!providerListHead>
<tbody>
<!providerListBody>
<tr  class='' id="submitButtonRow"><td colspan="3">
<table cellspacing="1" cellpadding="2" border="0" width="100%"><tr><td><div id="errorDiv" style="color:#FF0000;"></div></td><td  colspan="2"><div style="float:right;"><input type="button" name="addmore" value="Add Service" onclick="AddRowService();"> <input type="submit" name="submit" value="Save"> </div></td></tr></table></td>
</tr>
</tbody></table>

<input type="hidden" name="action" value="add_provider_account_bill">
<input type="hidden" name="id" value="<!id>">
</form>
</div>