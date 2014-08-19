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
<style>
/*   soap css */
ul.tabNavigation
{
    list-style: none;
    margin: 0;
    padding: 0;
    padding-bottom:5px;
    border-bottom: 1px solid #0a558f;

}

ul.tabNavigation li
{
    display: inline;
}

ul.tabNavigation li a {
    padding-left:20px;
    padding-right:70px;
    background-color: #fff;
    color: #333333;
    text-decoration: none;
    font-weight:normal;
    font-size:13px;
    font-family:Verdana;
}

ul.tabNavigation li a.selected
{
    background-color: #fff;
    color: #0a558f;
    font-family:Verdana;
  
    font-size:13px;
    padding-top: 3px;
    padding-bottom:4px;
}
.tabs
{
    width:740px;
}       
ul.tabNavigation li a:focus
{
    outline: 0;
    border-bottom:none;
}

div.tabs > div
{
    padding: 5px;
    margin-top: 3px;
}
    
/*Subjective Content css starts here*/
    
.subjective
{
    font-family:Verdana;
    font-size:12px;
    color:#0a558f;
    width:739px;
    margin-left:13px;
    line-height:25px;
}
.subjective a
{
    color:#0a558f;
}
.sub-head
{
    /*background:url('images/plus_sign.png') no-repeat left 50%;*/
}
.list-heading
{
    font-weight:bold;
    color:#333333;
}
.sub-list ul
{
    list-style-type:none;
    padding:0px;
    margin:0px;
}
.sub-list ul li
{
    /*background:url('images/plus_sign.png') no-repeat left 55%;*/
    padding-left:0px;
}
.expand-link
{
    padding-left:5px;
}
.hide-content
{
    display:none;
}
table
{
    border-collapse:collapse;
}
.table-data
{
    width:730px;
}
.text
{
    width:677px;
}
.row-border
{
    border-top:1px #cbcbcb solid;
    background:#f0f0f0;
}
.row-border-grey
{
    border-top:1px #cbcbcb solid;
    background:#e0e0e0;
}
.button-row
{
    text-align:right;
    padding-right:10px;
}
.slidingDiv
{
    height:300px;
    background-color: #99CCFF;
    padding:20px;
    margin-top:10px;
    border-bottom:5px solid #3399FF;
    
}
     
.show_hide
{
    
    display:none;
}

/*Subjective Content css starts here*/

/* Pop Up */

.popup
{
    width:755px;
    border:3px #0280cc solid;
}
.popup-heading
{
     background:#0280cc;
     height:26px;
     border-bottom:1px #aaaaaa solid;
}
.popup-heading-text
{
     font-family:Arial;
     font-size:12px;
     color:#fff;
     font-weight:bold;
     float:left;
     padding-top:5px;
     padding-bottom:5px;
     padding-left:10px;
}

.close
{
    float:right;
    margin-right:10px;
    margin-top:5px;
}
.popup-text
{
    font-family:Verdana;
    font-size:12px;
    color:#333333;
    margin:5px 6px 6px 6px;
    padding-bottom:10px;
    line-height:25px;
    border-bottom:1px #999999 solid;
}
.submit-button
{
    text-align:right;
    padding-right:13px;
    margin-bottom:6px;
}
</style>

<script language="JavaScript">

function showHide(vThis)
{
    vParent = vThis.parentNode;
    vSibling = vParent.nextSibling;
    while (vSibling.nodeType==3) {   // Fix for Mozilla/FireFox Empty Space becomes a TextNode or Something
        vSibling = vSibling.nextSibling;
    };

    if(vSibling.style.display == "none")
    {
        vThis.src="images/minus_sign.png";
        vThis.alt = "Hide";
        vSibling.style.display = "block";
    } else {
        vSibling.style.display = "none";
        vThis.src="images/plus_sign.png";
        vThis.alt = "Show";
    }
return;
}

$(function () {
    var tabContainers = $('div.tabs > div');
    tabContainers.hide().filter('<!submenushow>').show();
    
    $('div.tabs ul.tabNavigation a').click(function () {
        tabContainers.hide();
        tabContainers.filter(this.hash).show();
        $('div.tabs ul.tabNavigation a').removeClass('selected');
        $(this).addClass('selected');
        return false;
    }).filter('<!submenushow>').click();
     
   $('.tbl<!showhideid>').find("img").attr("src","images/minus_sign.png");
   $('#tbl<!showhideid>').parent().parent().show();
    
});


    var jsServicesListCount=<!servicesListCount>;
    function AddRowService(val){  
    rowId="submitButtonRow"+val;

    var target = document.getElementById(rowId);
    var tbody = document.getElementById("tbl"+val);
    var row = document.createElement("TR");
    var td1 = document.createElement("TD");
    var element1 = document.createElement("input");
    element1.type = "text";
    element1.name= "row"+jsServicesListCount+"[]";
    element1.id= "soap_answer"+jsServicesListCount;
    element1.style.width= "659px";
    td1.appendChild(element1);
    
    var td2 = document.createElement("TD");
    var data3 = document.createElement("span");
    var element1 = document.createElement("input");
    element1.type = "hidden";
    element1.name= "row"+jsServicesListCount+"[]";
    var element2 = document.createElement("input");
    element2.type = "hidden";
    element2.name= "row"+jsServicesListCount+"[]";
    element2.value= val;
    td2.appendChild(element1);
    td2.appendChild(element2);

    row.appendChild(td1);
    row.appendChild(td2);
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
    if(!confirm("Are you sure you want to delete this answer?")){
        return false;
    }
    var id = obj.id;
    id = id.substr(6);
    var jssoap_answer_id = id;
    var content = $('#span_' + id).html();
    $('#span_' + id).html("<img src='/images/horizontal-preloader.gif' />");
    $.post('index.php?action=delete_soap',{soap_answer_id:jssoap_answer_id,status:3}, function(data,status){
                        
                        if( status == "success" ){
                            $('#table_id_' + id).remove();
                            document.getElementById('errorDiv').innerHTML='<span style="color:green"><b>Your answer has been deleted</b></span>';
                        }        
                    }
            );
}

  function edit_service(obj){
    if(!confirm("Are you sure you want to update this answer?")){
        return false;
    }
     var id = obj.id;
    id = id.substr(13);
   // alert(id);
   $('#soap_answer'+id).removeAttr("readonly"); 
   $('#soap_answer'+id).css("background","white");
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
          <td width="151" style=" width:400px;"><div id="breadcrumbNav" style="padding-left:6px;margin-top:12px;"> <a href="index.php">HOME</a> / <span>CUSTOMIZE</span> / <span class="highlight">SOAP</span></div></td>
          <td width="138"></td>
          <td width="173" align="right" style="padding-right:75px; margin-top:11px;height:70px;">
          &nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" valign="top" style=" width:400px; font-size: large;font-weight: bold;padding-bottom: 7px; margin:0px; vertical-align:top;">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" valign="top"><div>
            <!tabNavigation>
          </div><div id="errorDiv" style=" padding-top:10px;"><!error></div></td>
        </tr>
      </table>
      
      
 <!-- best practices table starts from here-->  

<table cellspacing="1" cellpadding="2" border="0" width="100%" class="list" id="serviceTableRow">
<!servicesListHead>
<tbody>

</tbody>
</table>
<!-- menu -->
<div class="tabs"  style="margin-top: 5px;">
        <ul class="tabNavigation">
            <li><a href="#subjective" id="subjective">Subjective</a></li>
            <li><a href="#sellers"  id="sellers">Objective</a></li>
            <li><a href="#arrivals" id="arrivals">Assessment</a></li>
        </ul>
        
        <!--Subjective Tab Starts Here-->
        
        <div id="subjective" class="subjective">
             <div class="sub-head">
             <span  class='tbl<!questionid1>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Chief Complaint</span>
             </span>
             <div class="hide-content" style="display:none;">
             <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list" id='tbl<!questionid1>'>
             <!servicesListBody1>
              <tr id="submitButtonRow<!questionid1>" class="">
              <td colspan="2" style="text-align:right">
              <input type="button" onclick="AddRowService('<!questionid1>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></td>
              </tr>
             </table>
             </form>
              </div>
             </div>
             <div class="list-heading">History of Present Illness</div>
             <div class="sub-list">
             <ul>
             <li>
             <span  class='tbl<!questionid2>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Location</span>
             </span>
             <div class="hide-content" style="display:none;">
             <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid2>'>
             <!servicesListBody2>
              <tr id="submitButtonRow<!questionid2>" class="">
              <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid2>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
             </div>
             </li>
             <li><span  class='tbl<!questionid3>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Onset</span>
             </span>
             <div class="hide-content" style="display:none;">
             <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid3>'>
             <!servicesListBody3>
              <tr id="submitButtonRow<!questionid3>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid3>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
             </div>
             </li>
             <li>
             <span  class='tbl<!questionid4>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Provocation</span>
             </span>
             <div class="hide-content" style="display:none;">
              <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid4>'>
             <!servicesListBody4>
              <tr id="submitButtonRow<!questionid4>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid4>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
             </div>
             </li>
             <li>
             <span  class='tbl<!questionid5>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Palliation</span>
             </span>
             <div class="hide-content" style="display:none;">
              <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid5>'>
             <!servicesListBody5>
              <tr id="submitButtonRow<!questionid5>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid5>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
             </div>
             </li>
             <li>
             <span  class='tbl<!questionid6>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Quality</span>
             </span>
             <div class="hide-content" style="display:none;">
              <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid6>'>
             <!servicesListBody6>
              <tr id="submitButtonRow<!questionid6>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid6>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
             </div>
             </li>
             <li>
             <span  class='tbl<!questionid7>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Radiation</span>
             </span>
             <div class="hide-content" style="display:none;">
              <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid7>'>
             <!servicesListBody7>
              <tr id="submitButtonRow<!questionid7>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid7>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
             </div>
             </li>
             <li>
             <span class='tbl<!questionid8>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Severity</span>
             </span>
             <div class="hide-content" style="display:none;">
              <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid8>'>
             <!servicesListBody8>
              <tr id="submitButtonRow<!questionid8>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid8>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
             </div>
             </li>
             <li>
             <span  class='tbl<!questionid9>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Duration</span>
             </span>
             <div class="hide-content" style="display:none;">
              <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid9>'>
             <!servicesListBody9>
              <tr id="submitButtonRow<!questionid9>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid9>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
             </div>
             </li>
             <li>
             <span  class='tbl<!questionid10>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Timings</span>
             </span>
             <div class="hide-content" style="display:none;">
              <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid10>'>
             <!servicesListBody10>
              <tr id="submitButtonRow<!questionid10>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid10>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
             </div>
             </li>
             <li>
             <span  class='tbl<!questionid11>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Associated Signs / Symptoms</span>
             </span>
             <div class="hide-content" style="display:none;">
              <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid11>'>
             <!servicesListBody11>
              <tr id="submitButtonRow<!questionid11>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid11>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
             </div>
              </li>
             </ul>
             
             <div class="list-heading">Review of Systems</div>
             <ul>
             <li>
             <span  class='tbl<!questionid12>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Constitutional</span>
             </span>
             <div class="hide-content" style="display:none;">
              <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid12>'>
             <!servicesListBody12>
              <tr id="submitButtonRow<!questionid12>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid12>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
             </div>
             </li>
             <li><span  class='tbl<!questionid13>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Eyes</span>
             </span>
             <div class="hide-content" style="display:none;">
              <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid13>'>
             <!servicesListBody13>
              <tr id="submitButtonRow<!questionid13>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid13>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
             </div>
             </li>
             <li>
             <span  class='tbl<!questionid14>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Ears, Nose, Mouth, Throat</span>
             </span>
             <div class="hide-content" style="display:none;">
              <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid14>'>
             <!servicesListBody14>
              <tr id="submitButtonRow<!questionid14>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid14>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
             </div>
             </li>
             <li>
             <span  class='tbl<!questionid15>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Cardiovascular</span>
             </span>
             <div class="hide-content" style="display:none;">
              <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid15>'>
             <!servicesListBody15>
              <tr id="submitButtonRow<!questionid15>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid15>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
             </div>
             </li>
              <li>
             <span  class='tbl<!questionid16>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Respiratory</span>
             </span>
             <div class="hide-content" style="display:none;">
              <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid16>'>
             <!servicesListBody16>
              <tr id="submitButtonRow<!questionid16>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid16>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
             </div>
             </li>
             <li>
             <span  class='tbl<!questionid17>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Gastrointestinal</span>
             </span>
             <div class="hide-content" style="display:none;">
              <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid17>'>
             <!servicesListBody17>
              <tr id="submitButtonRow<!questionid17>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid17>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
             </div>
             </li>
             <li>
             <span  class='tbl<!questionid18>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Genital-urinary</span>
             </span>
             <div class="hide-content" style="display:none;">
              <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid18>'>
             <!servicesListBody18>
              <tr id="submitButtonRow<!questionid18>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid18>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
             </div>
             </li>
             <li>
             <span  class='tbl<!questionid19>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Musculo-Skeletal</span>
             </span>
             <div class="hide-content" style="display:none;">
              <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid19>'>
             <!servicesListBody19>
              <tr id="submitButtonRow<!questionid19>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid19>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
             </div>
             </li>
             <li>
             <span  class='tbl<!questionid20>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Skin</span>
             </span>
             <div class="hide-content" style="display:none;">
              <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid20>'>
             <!servicesListBody20>
              <tr id="submitButtonRow<!questionid20>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid20>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
             </div>
             </li>
             <li>
             <span  class='tbl<!questionid21>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Neurological</span>
             </span>
             <div class="hide-content" style="display:none;">
              <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid21>'>
             <!servicesListBody21>
              <tr id="submitButtonRow<!questionid21>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid21>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
             </div>
             </li>
              <li>
             <span  class='tbl<!questionid22>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Psychiatric</span>
             </span>
             <div class="hide-content" style="display:none;">
              <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid22>'>
             <!servicesListBody22>
              <tr id="submitButtonRow<!questionid22>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid22>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
             </div>
             </li>
             <li>
             <span  class='tbl<!questionid23>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Endocrine</span>
             </span>
             <div class="hide-content" style="display:none;">
              <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid23>'>
             <!servicesListBody23>
              <tr id="submitButtonRow<!questionid23>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid23>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
             </div>
             </li>
             <li>
             <span  class='tbl<!questionid24>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Hematology / Lymphatic</span>
             </span>
             <div class="hide-content" style="display:none;">
              <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid24>'>
             <!servicesListBody24>
              <tr id="submitButtonRow<!questionid24>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid24>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
             </div>
             </li>
             <li>
             <span  class='tbl<!questionid25>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Allergy / Immunology</span>
             </span>
             <div class="hide-content" style="display:none;">
              <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid25>'>
             <!servicesListBody25>
              <tr id="submitButtonRow<!questionid25>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid25>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
             </div>
             </li>
             </ul>             
             </div>
        </div>
        
        <!--Subjective Tab Ends Here-->
        
        <!--Sellers Tab Starts Here--->
        
        <div id="sellers" style="min-height:500px;">

            <div id="subjective" class="subjective">
             <div class="sub-head">
             <span  class='tbl<!questionid26>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Vitals</span>
             </span>
             <div class="hide-content" style="display:none;">
             <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid26>'>
             <!servicesListBody26>
              <tr id="submitButtonRow<!questionid26>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid26>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
              </div>
             </div>
             
              <div class="sub-head">
             <span  class='tbl<!questionid27>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Constitutional</span>
             </span>
             <div class="hide-content" style="display:none;">
             <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid27>'>
             <!servicesListBody27>
              <tr id="submitButtonRow<!questionid27>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid27>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
              </div>
             </div>
               <div class="sub-head">
             <span  class='tbl<!questionid28>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Eyes</span>
             </span>
             <div class="hide-content" style="display:none;">
             <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid28>'>
             <!servicesListBody28>
              <tr id="submitButtonRow<!questionid28>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid28>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
              </div>
             </div>
               <div class="sub-head">
             <span  class='tbl<!questionid29>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Ears, nose, mouth, throat</span>
             </span>
             <div class="hide-content" style="display:none;">
             <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid29>'>
             <!servicesListBody29>
              <tr id="submitButtonRow<!questionid29>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid29>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
              </div>
             </div>
               <div class="sub-head">
             <span  class='tbl<!questionid30>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Cardiovascular</span>
             </span>
             <div class="hide-content" style="display:none;">
             <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid30>'>
             <!servicesListBody30>
              <tr id="submitButtonRow<!questionid30>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid30>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
              </div>
             </div>
               <div class="sub-head">
             <span  class='tbl<!questionid31>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Respiratory</span>
             </span>
             <div class="hide-content" style="display:none;">
             <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid31>'>
             <!servicesListBody31>
              <tr id="submitButtonRow<!questionid31>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid31>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
              </div>
             </div>
               <div class="sub-head">
             <span  class='tbl<!questionid32>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Gastrointestinal</span>
             </span>
             <div class="hide-content" style="display:none;">
             <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid32>'>
             <!servicesListBody32>
              <tr id="submitButtonRow<!questionid32>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid32>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
              </div>
             </div>
               <div class="sub-head">
             <span  class='tbl<!questionid33>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Genital-urinary</span>
             </span>
             <div class="hide-content" style="display:none;">
             <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid33>'>
             <!servicesListBody33>
              <tr id="submitButtonRow<!questionid33>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid33>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
              </div>
             </div>
               <div class="sub-head">
             <span  class='tbl<!questionid34>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Musculo-skeletal</span>
             </span>
             <div class="hide-content" style="display:none;">
             <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid34>'>
             <!servicesListBody34>
              <tr id="submitButtonRow<!questionid34>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid34>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
              </div>
             </div>
               <div class="sub-head">
             <span  class='tbl<!questionid35>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Skin</span>
             </span>
             <div class="hide-content" style="display:none;">
             <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid35>'>
             <!servicesListBody35>
              <tr id="submitButtonRow<!questionid35>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid35>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
              </div>
             </div>
               <div class="sub-head">
             <span  class='tbl<!questionid36>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Neurological</span>
             </span>
             <div class="hide-content" style="display:none;">
             <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid36>'>
             <!servicesListBody36>
              <tr id="submitButtonRow<!questionid36>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid36>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
              </div>
             </div>
               <div class="sub-head">
             <span  class='tbl<!questionid37>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Psychiatric</span>
             </span>
             <div class="hide-content" style="display:none;">
             <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid37>'>
             <!servicesListBody37>
              <tr id="submitButtonRow<!questionid37>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid37>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
              </div>
             </div>
               <div class="sub-head">
             <span  class='tbl<!questionid38>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Endocrine</span>
             </span>
             <div class="hide-content" style="display:none;">
             <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid38>'>
             <!servicesListBody38>
              <tr id="submitButtonRow<!questionid38>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid38>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
              </div>
             </div>
               <div class="sub-head">
             <span  class='tbl<!questionid39>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Hematology/ lymphatic</span>
             </span>
             <div class="hide-content" style="display:none;">
             <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid39>'>
             <!servicesListBody39>
              <tr id="submitButtonRow<!questionid39>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid39>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
              </div>
             </div>
               <div class="sub-head">
             <span  class='tbl<!questionid40>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Allergy/ immunology</span>
             </span>
             <div class="hide-content" style="display:none;">
             <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid40>'>
             <!servicesListBody40>
              <tr id="submitButtonRow<!questionid40>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid40>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
              </div>
             </div>
               <div class="sub-head">
             <span  class='tbl<!questionid41>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">EAV</span>
             </span>
             <div class="hide-content" style="display:none;">
             <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid41>'>
             <!servicesListBody41>
              <tr id="submitButtonRow<!questionid41>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid41>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
              </div>
             </div>
               <div class="sub-head">
             <span  class='tbl<!questionid42>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">Laboratory</span>
             </span>
             <div class="hide-content" style="display:none;">
             <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid42>'>
             <!servicesListBody42>
              <tr id="submitButtonRow<!questionid42>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid42>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
              </div>
             </div>
              
             
             </div>

           </div>
        
        <!--Seller Tab Ends Here--->
        
        <!--Arrival Tab Starts Here--->
        
        <div id="arrivals" style="min-height:500px;">

            <div id="subjective" class="subjective">
             <div class="sub-head">
             <span  class='tbl<!questionid43>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">DX</span>
             </span>
             <div class="hide-content" style="display:none;">
             <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid43>'>
             <!servicesListBody43>
              <tr id="submitButtonRow<!questionid43>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid43>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
              </div>
             </div>
             
              <div class="sub-head">
             <span  class='tbl<!questionid44>'><img src="images/plus_sign.png" style="cursor:pointer" onclick="showHide(this);"/>
             <span class="expand-link">DDX</span>
             </span>
             <div class="hide-content" style="display:none;">
             <form name="soapform" id="soapform" action=index.php method="post"> 
             <table cellspacing="0" cellpadding="6"  class="list"  id='tbl<!questionid44>'>
             <!servicesListBody44>
              <tr id="submitButtonRow<!questionid44>" class="">
             <td  colspan="2" style="text-align:right"><div style="align:right;">
              <input type="button" onclick="AddRowService('<!questionid44>');" value="Add More" name="addmore">
              <input type="submit" name="submit" value="Save"> <input type="hidden" name="action" value="account_soap_services"></div></td>
              </tr>
             </table>
             </form>
              </div>
             </div>
             </div>

        </div>
        
        <!--Arrival Tab Ends Here--->
      
</div>
<!-- -------------------------------------------- -->



<input type="hidden" name="action" value="account_bill_services">

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
