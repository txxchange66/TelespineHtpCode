<!-- Grey box -->
<script type="text/javascript">  
var GB_ROOT_DIR = "js/greybox/";
</script>

<!--
<script type="text/javascript" src="js/greybox/AJS.js"></script>
<script type="text/javascript" src="js/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="js/greybox/gb_scripts.js"></script>
-->
<script type="text/javascript" src="js/greybox/AJS.min.js"></script>
<script type="text/javascript" src="js/greybox/AJS_fx.min.js"></script>
<script type="text/javascript" src="js/greybox/gb_scripts.min.js"></script>
<link href="js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
<script>
<!--
function freeTrialLeft_handler(action){
    url = '/index.php?action=' + action;
   	GB_showCenter('Trial Period Expiry Notification', url, 210,500 );
	//window.location = url;
}
function showmyaccount(){
    var randomnumber=Math.floor(Math.random()*11);
   // alert(randomnumber);
    url='index.php?action=viewMyAccount&rand='+ randomnumber;
    //alert(url);
    window.location = url;
    
}
//-->
</script>
<!-- End -->

		<h2 >Welcome <br><!name_first> <!name_last></h2><!--<a href="mailto:support@txxchange.com"><img src="images/feedbackIcon.gif" /></a>-->
		<ul class="sideNav">
			<li class="loginBtn"><a href="index.php?action=logout">Logout</a></li>

			<li class="seperator"><img src="images/spacer.gif" width="148" height="1"></li>
			<!therapist_link>
			<li ><a href="index.php?action=viewMyAccount" onMouseOver="help_text(this, 'Manage your account and contact inforamtion')">My Account</a></li>
            <li ><a href="javascript:void(0);" onclick="GB_showCenter('Contact Support', '/index.php?action=provider_contact_us',130,335);">Contact Support</a></li>
			<!freetrial_link>			
			<li class="seperator"><img src="images/spacer.gif" width="148" height="1"></li>
			<!sysadmin_link>

			<li style="padding-top:14px; #padding-top:9px; ">
       <a style="font-size:12px; margin-right:-5px; display:block; line-height:15px;" href="http://www.youtube.com/user/TxXchange#p/u" target="_blank"><img src="images/icon-bedeo.jpg" align="left" style=" margin-top:-4px; margin-right:5px;">Training and Support Videos
        </a>
      </li>
      <li style="padding-top:12px; ">
       <a title="Send other health, wellness, and fitness professionals an invite to join Tx Xchange to collaborate and increase referrals" style="font-size:12px; margin-right:-5px; font-weight:bold; display:block; line-height:15px; padding-top:8px;" href="javascript:void(0);" onclick="GB_showCenter('Send Invite', '/index.php?action=sendinvite_provider',450,720);"><img src="images/icon-sendinvite.jpg" align="left" style=" margin-right:5px;"><span style="display:block; padding-top:11px;">Send Invite</span>
        </a><div style="clear:both;"></div>
      </li>
		</ul>
		
		<ul class="sideNav">
			<!--<li class="helpBtn"><a href="help.php">Help</a></li>
			<!sysadmin_link>
			
			<li class="rolloverhelp">
			<div id="rolloverhelp" style="font-size: 85%; text-align:left;padding: 5px 1em;">Rollover buttons or elements on the page to get help and tips.</div>
			<li>
			-->
		</ul>
