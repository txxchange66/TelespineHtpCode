<!-- Grey box -->
<script type="text/javascript">  
var GB_ROOT_DIR = "js/greybox/";
</script>
<script type="text/javascript" src="js/greybox/AJS.js"></script>
<script type="text/javascript" src="js/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="js/greybox/gb_scripts.js"></script>
<link href="js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
<script>
</script>
<style type="text/css">
.error
{
float:left;
background:url('images/img1_callout_bg_left.png') no-repeat left top;
padding:0px 0 45px 30px;
position: absolute;
margin-left: 169px;
top: 226px;
}
.error p
{
font-family:Geneva,Verdana,san-serif;
font-size:11px;
color:red;
margin:0;
padding:10px 0 0 0;
font-weight: bold;
}
.error1
{
background:url('images/img1_callout_bg_right.png') no-repeat right  top;
padding:0px 15px 45px 0px;
}
</style>
<!-- End --><div id="container">
	<div id="header">
		<div id='line'><span id='wxlogo' style="float:right;"></span>
                                <div id='txlogo' style='float:left; padding-left:55px;' ></div>
                                  <div style='clear:both;'></div>
                             <div id='subheader' style='float:right; padding-right:55px;' >
                             
                                <div id='cliniclogo'  ></div>
                             </div>
                             <div style='clear:both;'></div>
                  </div>
	</div>
	<div id="sidebar">
		<ul>&nbsp;</ul>
		<!--<ul class="sideNav">
			<li class="helpBtn">Tx Xchange<br> <span style="font-size: 70%; text-align:left;" >(Release <!release_version>)</span></li>
		</ul>-->
    <div style="padding-top:300px;" >
     <table width="10" border="0" cellspacing="0" >
         <tr>
            <td>
                <!-- <script src="https://siteseal.thawte.com/cgi/server/thawte_seal_generator.exe"></script> -->
            </td>
         </tr>
         </table>
    </div>    
	</div>
	<div id="mainContent" style="padding-top:14px;">
        <h1 class="largeH1"  >LOGIN</h1><br/>
        <form name="login" method="POST" action="index.php">
        <table border="0" cellpadding="2" cellspacing="0">
            <tr>
                <td style="width:120px;"><label for="username" >*&nbsp;Email Address:&nbsp;</label></td>
                <td><input type="text" style="width:170px" name="username" size="25"  value='<!username>'/></td>
                <td style="margin-left: 5px;" align="left" rowspan="2" valign="top"><div><!error></div></td>
            </tr>
            <tr>
            	<td><label for="password" >*&nbsp;Password:&nbsp;</label></td>
                <td><input type="password" style="width:170px" name="password" size="25"  value="<!password>"/><!callout></td>
            </tr>
            <tr>
            	<td>&nbsp;</td>
            	<td style="padding-top: 5px;"><input type="submit" name="submitted" value="Login"/></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
            	<td style="width:120px;" height="35px;">&nbsp;</td>
            	<td style="padding-top:10px;font-size:12px;" ><a href="index.php?action=loginRecovery" style="font-weight: bold;">Forgot your password?</a></td>
            	<td>&nbsp;</td>
            </tr>
        </table>
        <input type="hidden" name="action"  value="validate_login_frm"/>
        </form>
	</div>
</div>
<div id="footer">
	<!footer>
</div>
</div>
 <script type="text/javascript">
        document.login.username.focus();
 </script>