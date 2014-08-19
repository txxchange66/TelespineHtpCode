<input type="hidden"  name="showsoap" id="showsoap" value="<!shopshow>">
<script language="JavaScript" src="js/show_menu.js"></script>
<script language="JavaScript" src="js/mm_menu.js"></script>

<script language="JavaScript" src="js/treatment.js"></script>
<script language=JavaScript>
<!--
function check_length(my_form)
{
maxLen = 750; // max number of characters allowed
if (my_form.clinic_overview.value.length > maxLen) {
// Alert message if maximum limit is reached.
// If required Alert can be removed.
var msg = "You have reached your maximum limit of characters allowed";
alert(msg);
// Reached the Maximum length so trim the textarea
my_form.clinic_overview.value = my_form.clinic_overview.value.substring(0, maxLen);
t = document.getElementById('maxlimit');
t.innerHTML = (maxLen - my_form.clinic_overview.value.length)+' Characters Left';
}
else{ // Maximum length not reached so update the value of my_text counter
t = document.getElementById('maxlimit');
t.innerHTML = (maxLen - my_form.clinic_overview.value.length)+' Characters Left';}
}
function checkLength()
{
maxLen = 750; // max number of characters allowed
if (document.frm.clinic_overview.value.length > maxLen) {
var msg = "You have reached your maximum limit of characters allowed";
alert(msg);
return false;
}
}
//-->
</script>
<div class="center-align" >
  <div id="container"><!--style="padding-top:14px;"-->
    <div id="header">
      <!header>
    </div>
    
    <div id="sidebar">
          <!sidebar>
    </div>
    <!-- body part starts-->
    <div id="mainContent">
      <table border="0" cellpadding="0" cellspacing="0" style="vertical-align:middle; width:100%; height:81px;">
        <tr>
          <td colspan="3" style=" width:400px; height:9px;"></td>
        </tr>
        <tr>
          <td width="151" style=" width:400px;"><div id="breadcrumbNav" style="margin-top:26px;"> <a href="index.php">HOME</a> / <span class="highlight"  > <a href="index.php?action=userListing">ACCOUNT ADMINISTRATOR </a>/ MARKETING</span></div></td>
          <td width="138"></td>
          <td width="173" align="right" style="padding-right:75px; margin-top:11px;height:70px;">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" valign="top" style=" width:400px; font-size: large;
    font-weight: bold;
    padding-bottom: 15px; margin:0px; vertical-align:top; 
    ">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" valign="top"  ><div style=" padding-top:25px;">
            <!navigationTab>
          </div></td>
        </tr>
        <tr>      
          <td colspan="3" valign="top" class="topnavnew" >&nbsp;</td>
        </tr>
      </table>
      
      
 <!-- best practices table starts from here-->  
<form action="index.php" method="post" name="frm" enctype="multipart/form-data" onSubmit="return checkLength();" >
    <table width="100%" border="0" cellpadding="0" cellspacing="0"  style="border:1px solid #CCCCCC;"> 
        <tr>
            <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
       
        <tr>
          <td class="marketingtitle" width="185px">Company Details</td>
          <td align="left" style="padding-top: 20px;"><!error></td>
        </tr>
        <tr>
        <td >&nbsp;</td> <td >&nbsp;</td>
        </tr>
                        <tr>
                    <td class="text">Clinic URL:</td>
                    <td>
                        <input type="text" name="clinic_url" value="<!clinic_url>" style="width:145px;" /><span style="font-size:9px;font-weight:normal;">(e.g. http://www.google.com)</span>
                        </td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                    <td class="text">Clinic Logo:</td>
                    <td>  <div style="height:5px;"></div>
                        <table cellpadding="0" cellspacing="0" width=" 50%">
                            <tr>
                              <td width="33%" align="left"><input type="file" name="clinic_logo" value="" /></td>
                                <td width="4%"  align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif;font-size:11px;color:#000000;">&nbsp;<!show_link></td>
                                <td width="4%" align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif;font-size:11px;color:#000000;padding-left:5px;" ><!remove></td>
                            </tr>
                         </table>
                            
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td  class="text" style=" font-weight:normal; padding-left:0px;">
                    <div id="media_pic1" style="display: none;padding-bottom:10px;padding-top:10px;"><img src="/asset/images/clinic_logo/<!show_image>" border="0"></div> 
                    
                    Image(size:<!width> X <!height>, Type:jpeg,gif,png)
                    </td>
                </tr>
                 <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                    <td align="center" colspan="2" >
                        <input type="hidden" name="action" value="website" />
                        <input type="hidden" name="website_form_submit" value="submit" />
                        
                    </td>
                </tr>
                 <tr>
                    <td class="text">Company Overview</td>
                    <td><div style="height:5px;"></div>
                        <textarea onMouseOver="help_text(this, 'Please enter the Company Overview')" onKeyup="check_length(this.form);" name="clinic_overview" rows="3" cols="15" style="width:345px;" onKeyup="check_length(this.form);"><!clinic_overview></textarea>
                        
                        </td>
                </tr>
                 <tr>
                    <td class="text" >&nbsp;</td>
                    <td class="text" style=" font-weight:normal; padding-left:0px;">
                                     <div id="maxlimit" >750 Characters Left</div>
                                       Posts must be less than 750 characters.</td>
                      </tr>
            </table>

            </td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
      </table>
      
     <table cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="right"><input type="submit" name="save" value="  Save Company details  " /></td>
        </tr>
    </table>
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