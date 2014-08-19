<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
            <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
            <title>New Category</title>
            <meta name="description" content="<!metaDesc>">        
            <link rel="stylesheet" type="text/css" href="css/styles_popup.css" />
</head>
<body>
<div>
     <div>
<script language="JavaScript">
<!--
 function change_parent_url()
 {
    //alert('hello sir');
    parent.parent.GB_CURRENT.hide();
    top.parent.document.detailform.submit();
 }    
function processDelConfirm()
{
    parent.parent.hello();
}
-->
</script>           
<table width="75%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td style="padding-left:25px; padding-right:25px;"><b>&nbsp;</b></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td style="padding-left:35px; padding-right:25px;"><strong><span id="message"></span></strong></td>
  </tr>
  <tr>
    <td style="padding-left:40px; padding-right:40px;">&nbsp;</td>
  </tr> 
  <tr>
    <td>
    <div align="right">
    <form name="therapistDelConfirmForm">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr> 
  <tr>
         <td>&nbsp;</td>
 </tr>
 <tr>
       <td style="padding-right:40px;" align="right">
<div>
      <input type="button" name="Cancel" value="Cancel" onclick="javascript:parent.parent.GB_CURRENT.hide();" />
      <input type="button" name="Confirm" value="Confirm" onclick="javascript:change_parent_url();;" />
 </div></td>
    </tr>
 <tr>
            <td>&nbsp;            </td>
 </tr>
</table>
</form>
</div>
      </td>

            <tr>

    <td style="padding-left:40px; padding-right:40px;">&nbsp;</td>

  </tr>

  </tr>

</table>

</div>

            </div>

</body>

</html>
<script language="javascript">
function init() {
 
     if( top.parent.document.detailform.parent_category_id.value == 'parent' ){
        document.getElementById('message').innerHTML = "Are you sure you want to create a new parent category.";
    }
    else if( top.parent.document.detailform.parent_category_id.value >= 1){
        document.getElementById('message').innerHTML = "Are you sure you want to create a new child category.";
    }
    
}
window.onload = init;
</script>