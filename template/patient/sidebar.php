<!-- Grey box -->
<script type="text/javascript">  
var GB_ROOT_DIR = "js/greybox/";
</script>
<script type="text/javascript" src="js/greybox/AJS.min.js"></script>
<script type="text/javascript" src="js/greybox/AJS_fx.min.js"></script>
<script type="text/javascript" src="js/greybox/gb_scripts.min.js"></script>
<link href="js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
<!-- End -->
<script language="javascript">
function mypopup()
 {
     url = "index.php?action=patient_video_conference";
     //mywindow = window.open (url,"mywindow","location=0,status=0,scrollbars=0,width=793,height=543,resizable=0");
      $.browser.chrome = /chrom(e|ium)/.test(navigator.userAgent.toLowerCase()); 
        if($.browser.chrome){
        mywindow = window.open (url,"mywindow","location=0,status=0,scrollbars=0,width=793,height=543,resizable=0");
        mywindow.moveTo(0,0);
      }else{
         // mywindow = window.open (url,"mywindow","location=0,status=0,scrollbars=0,width=785,height=485,resizable=0");
         
                 url = '/cromedownloadmessage.html';
                GB_showCenter('Download Chrome', url, 140,500 );
      }
     
}
</script>
<div id="sidebar">
    <br><br>
    <!--<a href="mailto:support@txxchange.com"   >
        <img src="images/feedbackIcon.gif" alt="Give Us Feedback" width="117" height="27" />
    </a>-->
    <span class="welcome">Hello, </span> <span class="user" style="padding-bottom:0px;"><!userName></span><br/>
    <a href="index.php?action=logout">
        <span style="font-size:14px;">Logout</span>
    </a>
    <ul class="sideNav" >
      <li class="seperator"><img src="images/spacer.gif" width="148" height="1"></li>
      <li > <a href="index.php" ><span style="font-size:14px;">Home</span></a></li>
      <!message>
      <!teleconsultation>
      <li >
        <a href="index.php?action=changePass" > <span style="font-size:14px;">My Account</span> </a>
      </li>
      <li >
        <a href="javascript:void(0);" onclick="GB_showCenter('Contact Technical Support', '/index.php?action=patient_contact_us',200,500);" ><span style="font-size:14px;">Contact Support</span></a>
      </li>
     <!-- <li style="padding-top:10px;">
        <a href="index.php?action=patient_help" target=new >
        <span style="font-size:14px;color:red;">How Do I..?</span>
        </a>
      </li>-->
      <li style="padding-top:10px;">
       <a style="font-size:12px; margin-right:-5px; display:block; line-height:15px; padding-top:8px;" href="index.php?action=patient_help" target="_blank"><img src="images/icon-helpfiles.jpg" align="left" style=" margin-right:5px;"><span style="display:block; padding-top:11px;">Feature Help</span>
        </a><div style="clear:both;"></div>
      </li>
      <!--<li style="padding-top:5px; ">
       <a title="Send your other health, wellness, and fitness professionals an invite to join Tx Xchange and enjoy a better health, wellness, or fitness experience." style="font-size:12px; margin-right:-5px; font-weight:bold; display:block; line-height:15px; padding-top:8px;" href="javascript:void(0);" onclick="GB_showCenter('Send Invite', '/index.php?action=sendinvite',450,720);"><img src="images/icon-sendinvite.jpg" align="left" style=" margin-right:5px;"><span style="display:block; padding-top:11px;">Send Invite</span>
        </a><div style="clear:both;"></div>
      </li>-->
	  <!--<!referral_link>-->

      <!scheduling>
    </ul>
    <!markcv>
    <div style="padding-top:276px;" >
     <table width="10" border="0" cellspacing="0" >
         <tr>
            <td>
                <script src="https://siteseal.thawte.com/cgi/server/thawte_seal_generator.exe"></script>
            </td>
         </tr>
         </table>
    </div>
</div>
