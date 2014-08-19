<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>TeleSpine</title>
<style>
.jq_selected {
	border-width: 1px;
	border-style: solid;
	border-color: red;
	background-color: yellow
}
</style>
<script>function notifyScript() {     var evt = document.createEvent("Event");     evt.initEvent("notify", false, false);     if (document.getElementById("addthis-extension-script") == null) {        var d=document.createElement("div"); d.setAttribute("style", "display:none"); d.setAttribute("id", "addthis-extension-script");         if (window._ate)             d.textContent=_ate.pub();         else if(window.addthis_config && addthis_config.pubid)    d.textContent= addthis_config.pubid;        else if(window.addthis_config && addthis_config.username)    d.textContent= addthis_config.username;        else if(window.addthis_pub)    d.textContent= addthis_pub;        else             d.textContent="";         document.body.appendChild(d);     }    document.documentElement.dispatchEvent(evt); }notifyScript()</script>
</head>

<body bgcolor="#ffffff" style="background-color:#ffffff;">
<style type="text/css">
/* #Reset & Basics 
================================================== */ 
table, tbody, tfoot, thead, tr, th, td { 
border-collapse: collapse; 
border-spacing: 0; 
border: 0; 
} 
img { border:0px; 
} 
body { 
-webkit-font-smoothing: antialiased; 
-webkit-text-size-adjust: none; 
margin:0; 
padding:0; 
width: 100% !important; 
height: 100%; 
} 
body, td, p, h1, h2, h3 { 
font-family: Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; 
} 
h1 { 
padding-bottom: 10px; 
margin-bottom:0px; 
} 
p { 
line-height: 25px; 
} 
.hideunsub { 
display: none !important; 
visibility: hidden !important; 
max-height: 0px !important; 
height: 0px !important; 
width: 0px !important; 
line-height: 0px !important; 
font-size: 0px; 
mso-hide: all !important; 
overflow: hidden; 
} 
.hidedesk { 
display: none !important; 
visibility: hidden !important; 
max-height: 0px !important; 
height: 0px !important; 
width: 0px !important; 
line-height: 0px !important; 
font-size: 0px; 
mso-hide: all !important; 
overflow: hidden; 
} 
.hide30 { 
display: none !important; 
visibility: hidden !important; 
max-height: 0px !important; 
height: 0px !important; 
width: 0px !important; 
line-height: 0px !important; 
font-size: 0px; 
mso-hide: all; 
} 
.hidecta { 
display: none !important; 
visibility: hidden !important; 
max-height: 0px !important; 
height: 0px !important; 
mso-hide: all !important; 
} 
.score{
	font-size:larger;
	font-weight:bold;
	color:#fff;
	background-color:#8586FF;
	border-radius:60px;
	line-height:42px;
	padding:4px 12px;
}
.pain{
	font-size:larger;
	font-weight:normal;
	color:#fff;
	background-color:#FF0000;
	border-radius:60px;
	line-height:42px;
	padding:4px 12px;
}
/* Smartphones (portrait and landscape) ----------- */ 
@media only screen and (max-width: 600px) { 
table[class="desktop"] { 
width: 318px !important; 
margin: 0 auto !important; 
padding: 0 !important; 
border: 0px !important; 
max-width: 320px !important; 
} 
img[class="collapse"] { 
width: 0px !important; 
} 
table[class="mobileclear"] { 
clear: both !important; 
} 
td[class="collapse"] { 
width: 1px !important; 
} 
img[class="hidemobile"], table[class="hidemobile"] { 
display: none !important; 
width: 0px !important; 
height: 0px !important; 
} 
td[class="hidemobile"] { 
height: 0px !important; 
} 
td[class="hidedesk"] { 
display: block !important; 
max-height: none !important; 
height: auto !important; 
width: auto !important; 
line-height: auto !important; 
float: none !important; 
overflow: visible !important; 
visibility: visible !important; 
} 
div[class="hidedesk"] { 
display: block !important; 
max-height: none !important; 
height: auto !important; 
width: auto !important; 
margin:0px; 
padding:0px; 
line-height: auto !important; 
float: none !important; 
overflow: visible !important; 
visibility: visible !important; 
} 
table[class="hidedesk"] { 
display: block !important; 
max-height: none !important; 
height: auto !important; 
width: auto !important; 
line-height: auto !important; 
float: none !important; 
overflow: visible !important; 
visibility: visible !important; 
} 
table[class="hidecta"] { 
display: block !important; 
max-height: none !important; 
height: 33px !important; 
line-height: auto !important; 
float: none !important; 
padding:0px !important; 
overflow: visible !important; 
visibility: visible !important; 
} 
td[class="hidecta"] { 
display: inline !important; 
max-height: none !important; 
height: 33px !important; 
line-height: auto !important; 
float: none !important; 
padding:0px; 
overflow: visible !important; 
visibility: visible !important; 
} 
td[class="mobile_crunch"] { 
height:auto !important; 
padding-top:10px; 
padding-bottom:10px; 
} 
img[class="hidedesk"] { 
display: block !important; 
max-height: none !important; 
height: auto !important; 
width: auto !important; 
line-height: auto !important; 
float: none !important; 
overflow: visible !important; 
visibility: visible !important; 
} 
*[class="mobilespace"] { 
width: 15px !important; 
} 
img[class="hide30"] { 
display: block !important; 
max-height: none !important; 
height: 30px !important; 
width: 30px !important; 
line-height: auto !important; 
float: none !important; 
overflow: visible !important; 
visibility: visible !important; 
} 
img[class="pad15"] { 
height: 15px !important; 
width: 15px !important; 
} 
table[class="fullwidth"] { 
width: 100% !important; 
text-align: center !important; 
float: none !important; 
border: 0px !important; 
} 
td[class="heightreduce"] { 
height: 55px !important; 
} 
td[class="mobilecolor"] { 
background-color: #e3eaed !important; 
} 
div[class="mobilepad"] { 
padding: 0px 10px 0px 10px !important; 
} 
h1[class="mobileheader"] { 
font-size:25px !important; 
} 
} 
</style>
<table align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" class="desktop" style="background-color:#ffffff;" width="100%">
  <tbody>
    <tr>
      <td align="center" bgcolor="#e8f1f4" style="background-color:#11BDF0;" valign="middle"><table align="center" border="0" cellpadding="0" cellspacing="0" class="desktop" width="640">
          <tbody>
            <tr>
              <td height="30"><table border="0" cellpadding="0" cellspacing="0" class="desktop" width="100%">
                  <tbody>
                    <tr>
                      <td style="line-height:0px; font-size:0px;" width="12"><img border="0" height="26" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="12" /></td>
                      <td align="left" style="font-size:12px; font-family: Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif;" valign="middle"></td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
            <tr>
              <td align="center" bgcolor="#E8F1F4" class="hidemobile" height="280" valign="middle"><a href="." title="(opens in a new window)" target="_blank"><img alt="TeleSpine™" border="0" class="hidemobile" height="280" src="<!images_url>/assets/email/hero_image2.jpg" style="background-color:#11BDF0; text-align:center; vertical-align:middle; font-size:30px; color:#ffffff; font-family: Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; font-weight:bold; display:block;" title="TeleSpine™" width="642" /></a></td>
            </tr>
            <tr>
              <td align="center" bgcolor="#E8F1F4" class="hidedesk" style="line-height:0px; font-size:0px;" valign="middle"><div class="hidedesk" style="display:none; visibility: hidden; max-height:0px; height:0px; width:0px; line-height:0px; font-size:0px; mso-hide:all; overflow:hidden;"><a href="h#" title="(opens in a new window)" target="_blank"><img alt="TeleSpine™" border="0" class="hidedesk" src="<!images_url>/assets/email/hero_mobile2.jpg" style="display:none; visibility: hidden; max-height:0px; height:0px; width:0px; line-height:0px; font-size:0px; mso-hide:all;" title="TeleSpine™" /></a></div></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
    <tr>
      <td bgcolor="#ffffff"><table align="center" border="0" cellpadding="0" cellspacing="0" class="desktop" width="640">
          <tbody>
            <tr>
              <td bgcolor="#FFFFFF" height="23"><img border="0" height="23" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="1" /></td>
            </tr>
            <tr>
              <td bgcolor="#FFFFFF"><table align="center" border="0" cellpadding="0" cellspacing="0" class="desktop" width="640">
                  <tbody>
                    <tr>
                      <td align="left" height="250" valign="middle"><table align="left" border="0" cellpadding="0" cellspacing="0" class="desktop" style="float:left; border:1px solid #ffffff;" width="347">
                          <tbody>
                            <tr>
                              <td align="left" style="mso-table-lspace:0; mso-table-rspace:0; font-size:18px; mso-line-height-rule:exactly; line-height:24px; font-family: Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; color:#454546;" valign="top"><div class="mobilepad">
                                 <!-- <h1 class="mobileheader" style="font-family:Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; font-size:27px; color:#454546; font-weight:normal; white-space:nowrap;">Your Score: <span class="score">45</span> <span class="pain">3/10</span></h1>-->
<strong>Hello,</strong> We see you haven't logged in to start your Healthy Back Program. We'd like to help you get better from low back pain, please take a few minutes to login and get started.</div></td>

 

                            </tr>
                            <tr>
                              <td align="left" style="line-height:0px; font-size:0px;" valign="top"><img border="0" height="23" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="1" /></td>
                            </tr>
                            <tr>
                              <td><table align="center" bgcolor="#84b64a" border="0" cellpadding="0" cellspacing="0">
                                  <tbody>
                                    <tr>
                                      <td align="left" valign="top" width="21"><img alt="" border="0" height="35" src="<!images_url>/assets/email/left.jpg" style="display: block;" width="21" /></td>
                                      <td align="center" valign="middle"><table align="center" border="0" cellpadding="0" cellspacing="0">
                                          <tbody>
                                            <tr>
                                              <td width="30"><span style="line-height:0px; font-size:0px;"><a href="http://emails.TeleSpine.com" style="color:#ffffff; text-decoration:none; white-space:nowrap;" title="(opens in a new window)" target="_blank"><img alt="" border="0" height="31" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="30" /></a></span></td>
                                              <td align="right" style="font-size:18px; mso-line-height-rule:exactly; line-height:26px; text-align:right; font-family: Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; color:#ffffff;" valign="middle"><a href="http://emails.TeleSpine.com" style="color:#ffffff; text-decoration:none; white-space:nowrap;" title="(opens in a new window)" target="_blank">Update Now</a></td>
                                              <td align="left" style="line-height:0px; font-size:0px;" valign="top" width="44"><a href="http://emails.TeleSpine.com" title="(opens in a new window)" target="_blank"><img alt="" border="0" height="31" src="<!images_url>/assets/email/cta_arrow.gif" style="display:block;" width="44" /></a></td>
                                            </tr>
                                          </tbody>
                                        </table></td>
                                      <td align="left" valign="top" width="21"><img alt="" border="0" height="35" src="<!images_url>/assets/email/right.jpg" style="display: block;" width="21" /></td>
                                    </tr>
                                  </tbody>
                                </table></td>
                            </tr>
                            <tr>
                              <td align="left" style="line-height:0px; font-size:0px;" valign="top"><img border="0" height="23" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="1" /></td>
                            </tr>
                          </tbody>
                        </table>
                        <table align="right" border="0" cellpadding="0" cellspacing="0" class="desktop" style="float:right; border:1px solid #ffffff;" width="250">
                          <tbody>
                            <tr>
                              <td align="center" bgcolor="#FFFFFF" class="mobile_crunch" height="240" style="mso-table-lspace:0;mso-table-rspace:0;line-height:0px; font-size:0px;" valign="middle"><a href="http://emails.TeleSpine.com" style="color:#ffffff; text-decoration:none; white-space:nowrap;" title="(opens in a new window)" target="_blank"><img alt="Discover more" border="0" height="166" src="<!images_url>/assets/email/body_right2.jpg" style="display:block;" title="Discover more" width="277" /></a></td>
                            </tr>
                          </tbody>
                        </table></td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
            <tr>
              <td bgcolor="#11bdf0"><table bgcolor="#11bdf0" border="0" cellpadding="0" cellspacing="0" width="100%">
                  <tbody>
                    <tr>
                      <td bgcolor="#11BDF0" height="30" style="line-height:0px; font-size:0px;" width="30"><img border="0" class="pad15" height="30" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="30" /></td>
                      <td bgcolor="#11BDF0" height="30" style="line-height:0px; font-size:0px;"><img border="0" height="30" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="260" /></td>
                      <td bgcolor="#11BDF0" height="30" style="line-height:0px; font-size:0px;" width="30"><img border="0" class="pad15" height="30" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="30" /></td>
                    </tr>
                    <tr>
                      <td bgcolor="#11BDF0" style="line-height:0px; font-size:0px;" width="30"><img border="0" class="pad15" height="30" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="30" /></td>
                      <td bgcolor="#11BDF0" height="250" valign="top"><table align="left" border="0" cellpadding="0" cellspacing="0" class="fullwidth" style="float:left; border:1px solid #11bdf0;" width="275">
                          <tbody>
                            <tr>
                              <td align="left" bgcolor="#11BDF0" style="line-height:0px; font-size:0px;" valign="top"><a href="http://emails.TeleSpine.com" title="(opens in a new window)" target="_blank"><img alt="Make a group call" border="0" height="46" src="<!images_url>/assets/email/body2_001.gif" style="display:block;" title="Make a group call" width="51" /></a></td>
                              <td align="left" bgcolor="#11BDF0" style="mso-table-lspace:0; mso-table-rspace:0; font-size:16px; mso-line-height-rule:exactly; line-height:24px; font-family: Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; color:#ffffff;" valign="top">Contact<br />
                                Got a question? We are here to help. 303-123-1234</td>
                            </tr>
                            <tr>
                              <td align="left" bgcolor="#11BDF0" style="line-height:0px; font-size:0px;" valign="top" width="51"><img border="0" height="10" src="<!images_url>/assets/email/spacer.gif" style="display:block;" /></td>
                              <td align="left" bgcolor="#11BDF0" style="line-height:0px; font-size:0px;" valign="top"><img border="0" height="10" src="<!images_url>/assets/email/blue_spacer.gif" style="display:block;" /></td>
                            </tr>
                            <tr>
                              <td align="left" bgcolor="#11BDF0" style="line-height:0px; font-size:0px;" valign="top" width="51"><img border="0" height="10" src="<!images_url>/assets/email/spacer.gif" style="display:block;" /></td>
                              <td align="left" bgcolor="#11BDF0" height="14" style="mso-table-lspace:0; mso-table-rspace:0; font-size:16px; mso-line-height-rule:exactly; line-height:24px; font-family: Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; color:#ffffff;" valign="top"><a href="http://emails.TeleSpine.com" style="color:#ffffff; text-decoration:none;" title="(opens in a new window)" target="_blank">Update Your Profile <img border="0" height="11" src="<!images_url>/assets/email/body2_arrow.gif" width="16" /></a></td>
                            </tr>
                            <tr>
                              <td align="left" bgcolor="#11BDF0" style="line-height:0px; font-size:0px;" valign="top"><img border="0" height="40" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="30" /></td>
                              <td align="left" bgcolor="#11BDF0" style="line-height:0px; font-size:0px;" valign="top"><img border="0" height="40" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="40" /></td>
                            </tr>
                            <tr>
                              <td align="left" bgcolor="#11BDF0" style="line-height:0px; font-size:0px;" valign="top"><a href="http://emails.TeleSpine.com" title="(opens in a new window)" target="_blank"><img alt="Send a file" border="0" height="46" src="<!images_url>/assets/email/body2_002.gif" style="display:block;" title="Send a file" width="51" /></a></td>
                              <td align="left" bgcolor="#11BDF0" style="mso-table-lspace:0; mso-table-rspace:0; font-size:16px; mso-line-height-rule:exactly; line-height:24px; font-family: Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; color:#ffffff;" valign="top">Upload photos<br />
                                Customize your online experience. Change your profile, and choose your design. </td>
                            </tr>
                            <tr>
                              <td align="left" bgcolor="#11BDF0" style="line-height:0px; font-size:0px;" valign="top" width="51"><img border="0" height="10" src="<!images_url>/assets/email/spacer.gif" style="display:block;" /></td>
                              <td align="left" bgcolor="#11BDF0" style="line-height:0px; font-size:0px;" valign="top"><img border="0" height="10" src="<!images_url>/<!images_url>/assets/email/blue_spacer.gif" style="display:block;" /></td>
                            </tr>
                            <tr>
                              <td align="left" bgcolor="#11BDF0" style="line-height:0px; font-size:0px;" valign="top" width="51"><img border="0" height="10" src="<!images_url>/assets/email/spacer.gif" style="display:block;" /></td>
                              <td align="left" bgcolor="#11BDF0" height="14" style="mso-table-lspace:0; mso-table-rspace:0; font-size:16px; mso-line-height-rule:exactly; line-height:24px; font-family: Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; color:#ffffff;" valign="top"><a href="http://emails.TeleSpine.com" style="color:#ffffff; text-decoration:none;" title="(opens in a new window)" target="_blank">Update Now <img border="0" height="11" src="<!images_url>/assets/email/body2_arrow.gif" width="16" /></a></td>
                            </tr>
                            <tr>
                              <td align="left" bgcolor="#11BDF0" style="line-height:0px; font-size:0px;" valign="top"><img border="0" class="hide30" height="30" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="30" /></td>
                              <td align="left" bgcolor="#11BDF0" style="line-height:0px; font-size:0px;" valign="top"><img border="0" class="hide30" height="30" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="30" /></td>
                            </tr>
                          </tbody>
                        </table>
                        <table align="right" border="0" cellpadding="0" cellspacing="0" class="fullwidth" style="float:right; border:1px solid #11bdf0;" width="270">
                          <tbody>
                            <tr>
                              <td align="left" bgcolor="#11BDF0" style="line-height:0px; font-size:0px;" valign="top" width="51"><a href="http://emails.TeleSpine.com" title="(opens in a new window)" target="_blank"><img alt="Share your desktop" border="0" height="46" src="<!images_url>/assets/email/body2_003.gif" style="display:block;" title="Share your desktop" width="51" /></a></td>
                              <td align="left" bgcolor="#11BDF0" style="mso-table-lspace:0; mso-table-rspace:0; font-size:16px; mso-line-height-rule:exactly; line-height:24px; font-family: Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; color:#ffffff;" valign="top">Mobile vieweing<br />
                                With the coaching program you can exhange one on one with an assistant to help you now.</td>
                            </tr>
                            <tr>
                              <td align="left" bgcolor="#11BDF0" style="line-height:0px; font-size:0px;" valign="top" width="51"><img border="0" height="10" src="<!images_url>/assets/email/spacer.gif" style="display:block;" /></td>
                              <td align="left" bgcolor="#11BDF0" style="line-height:0px; font-size:0px;" valign="top"><img border="0" height="10" src="<!images_url>/assets/email/blue_spacer.gif" style="display:block;" /></td>
                            </tr>
                            <tr>
                              <td align="left" bgcolor="#11BDF0" style="line-height:0px; font-size:0px;" valign="top" width="51"><img border="0" height="10" src="<!images_url>/assets/email/spacer.gif" style="display:block;" /></td>
                              <td align="left" bgcolor="#11BDF0" height="14" style="mso-table-lspace:0; mso-table-rspace:0; font-size:16px; mso-line-height-rule:exactly; line-height:24px; font-family: Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; color:#ffffff;" valign="top"><a href="http://emails.TeleSpine.com" style="color:#ffffff; text-decoration:none;" title="(opens in a new window)" target="_blank">View on your Phone <img border="0" height="11" src="<!images_url>/assets/email/body2_arrow.gif" width="16" /></a></td>
                            </tr>
                            <tr>
                              <td align="left" bgcolor="#11BDF0" style="line-height:0px; font-size:0px;" valign="top"><img border="0" class="hide30" height="40" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="30" /></td>
                              <td align="left" bgcolor="#11BDF0" style="line-height:0px; font-size:0px;" valign="top"><img border="0" height="40" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="30" /></td>
                            </tr>
                            <tr>
                              <td align="left" bgcolor="#11BDF0" style="line-height:0px; font-size:0px;" valign="top"><a href="http://emails.TeleSpine.com" title="(opens in a new window)" target="_blank"><img alt="Get your number" border="0" height="46" src="<!images_url>/assets/email/body2_004.gif" style="display:block;" title="Get your number" width="51" /></a></td>
                              <td align="left" bgcolor="#11BDF0" style="mso-table-lspace:0; mso-table-rspace:0; font-size:16px; mso-line-height-rule:exactly; line-height:24px; font-family: Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; color:#ffffff;" valign="top">Recive Messages<br />
                                Get your daily status by email, and online. </td>
                            </tr>
                            <tr>
                              <td align="left" bgcolor="#11BDF0" style="line-height:0px; font-size:0px;" valign="top" width="51"><img border="0" height="10" src="<!images_url>/assets/email/spacer.gif" style="display:block;" /></td>
                              <td align="left" bgcolor="#11BDF0" style="line-height:0px; font-size:0px;" valign="top"><img border="0" height="10" src="<!images_url>/assets/email/blue_spacer.gif" style="display:block;" /></td>
                            </tr>
                            <tr>
                              <td align="left" bgcolor="#11BDF0" style="line-height:0px; font-size:0px;" valign="top" width="51"><img border="0" height="10" src="<!images_url>/assets/email/spacer.gif" style="display:block;" /></td>
                              <td align="left" bgcolor="#11BDF0" height="14" style="mso-table-lspace:0; mso-table-rspace:0; font-size:16px; mso-line-height-rule:exactly; line-height:24px; font-family: Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; color:#ffffff;" valign="top"><a href="http://emails.TeleSpine.com" style="color:#ffffff; text-decoration:none;" title="(opens in a new window)" target="_blank">Login <img border="0" height="11" src="<!images_url>/assets/email/body2_arrow.gif" width="16" /></a></td>
                            </tr>
                          </tbody>
                        </table></td>
                      <td bgcolor="#11BDF0" style="line-height:0px; font-size:0px;" width="30"><img border="0" class="pad15" height="30" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="30" /></td>
                    </tr>
                    <tr>
                      <td bgcolor="#11BDF0" height="30" style="line-height:0px; font-size:0px;" width="30"><img border="0" class="pad15" height="30" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="30" /></td>
                      <td bgcolor="#11BDF0" height="30" style="line-height:0px; font-size:0px;"><img border="0" height="30" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="30" /></td>
                      <td bgcolor="#11BDF0" height="30" style="line-height:0px; font-size:0px;" width="30"><img border="0" class="pad15" height="30" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="30" /></td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
            <tr>
              <td bgcolor="#ffffff" class="mobilecolor" height="15" style="line-height:0px; font-size:0px;"><img border="0" height="15" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="1" /></td>
            </tr>
            <tr>
              <td class="mobilecolor" height="135" valign="top"><table align="left" border="0" cellpadding="0" cellspacing="0" class="fullwidth" style="float:left; border:1px solid #ffffff;" width="300">
                  <tbody>
                    <tr>
                      <td align="left" style="line-height:0px; font-size:0px;" valign="top"><img border="0" height="15" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="15" /></td>
                      <td align="left" style="mso-table-lspace:0; mso-table-rspace:0; font-size:16px; mso-line-height-rule:exactly; line-height:20px; font-family: Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; color:#454546;" valign="top"><h1 style="font-family:Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; font-size:21px; color:#454546; font-weight:normal;">Less Pain, More Gain</h1>
                        A TeleSpine subscription helps you take control of your pain. By updating and folling the program daily you can affectively change your life.</td>
                    </tr>
                    <tr>
                      <td align="left" height="13" style="line-height:0px; font-size:0px;" valign="top"><img border="0" height="13" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="15" /></td>
                      <td align="left" height="13" style="line-height:0px; font-size:0px;" valign="top"><img border="0" height="13" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="15" /></td>
                    </tr>
                    <tr>
                      <td align="left" style="line-height:0px; font-size:0px;" valign="top"><img border="0" height="20" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="15" /></td>
                      <td align="left" style="mso-table-lspace:0; mso-table-rspace:0; font-size:16px; mso-line-height-rule:exactly; line-height:20px; font-family: Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; color:#454546;" valign="top"><a href="http://emails.TeleSpine.com" style="color:#11bdf0; text-decoration:none;" title="(opens in a new window)" target="_blank"><span style="color:#11bdf0; text-decoration:none;">Learn more</span>&nbsp;<img border="0" height="9" src="<!images_url>/assets/email/blue_arrow.gif" width="17" /></a></td>
                    </tr>
                    <tr>
                      <td align="left" style="line-height:0px; font-size:0px;" valign="top"><img border="0" height="20" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="15" /></td>
                      <td align="left" style="line-height:0px; font-size:0px;" valign="top"><img border="0" height="20" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="15" /></td>
                    </tr>
                  </tbody>
                </table>
                <table align="right" border="0" cellpadding="0" cellspacing="0" class="fullwidth" style="float:right; border:1px solid #ffffff;" width="300">
                  <tbody>
                    <tr>
                      <td align="left" style="line-height:0px; font-size:0px;" valign="top"><img border="0" height="15" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="15" /></td>
                      <td align="left" style="mso-table-lspace:0; mso-table-rspace:0; font-size:16px; mso-line-height-rule:exactly; line-height:20px; font-family: Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; color:#454546;" valign="top"><h1 style="font-family:Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; font-size:21px; color:#454546; font-weight:normal;">Get TeleSpine on mobile</h1>
                        Did you know you can use TeleSpine on your mobile too?</td>
                    </tr>
                    <tr>
                      <td align="left" height="13" style="line-height:0px; font-size:0px;" valign="top"><img border="0" height="13" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="15" /></td>
                      <td align="left" height="13" style="line-height:0px; font-size:0px;" valign="top"><img border="0" height="13" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="10" /></td>
                    </tr>
                    <tr>
                      <td align="left" style="line-height:0px; font-size:0px;" valign="top"><img border="0" height="20" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="15" /></td>
                      <td align="left" style="mso-table-lspace:0; mso-table-rspace:0; font-size:16px; mso-line-height-rule:exactly; line-height:20px; font-family: Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; color:#454546;" valign="top"><a href="http://emails.TeleSpine.com" style="color:#11bdf0; text-decoration:none;" title="(opens in a new window)" target="_blank"><span style="color:#11bdf0; text-decoration:none;">Visit TeleSpine on your mobile</span>&nbsp;<img border="0" height="9" src="<!images_url>/assets/email/blue_arrow.gif" width="17" /></a></td>
                    </tr>
                    <tr>
                      <td align="left" style="line-height:0px; font-size:0px;" valign="top"><img border="0" height="20" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="15" /></td>
                      <td align="left" style="line-height:0px; font-size:0px;" valign="top"><img border="0" height="20" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="15" /></td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
    <tr>
      <td bgcolor="#ffffff" height="10" style="line-height:0px; font-size:0px;"><img border="0" height="10" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="1" /></td>
    </tr>
    <tr>
      <td align="center"><table border="0" cellpadding="0" cellspacing="0" class="fullwidth" width="640">
          <tbody>
            <tr>
              <td bgcolor="#ffffff" height="15" style="line-height:0px; font-size:0px;"><img border="0" height="15" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="1" /></td>
            </tr>
            <tr>
              <td bgcolor="#FFFFFF" height="55" valign="top"><table align="left" border="0" cellpadding="0" cellspacing="0" class="fullwidth" style="float:left; border:1px solid #ffffff;" width="300">
                  <tbody>
                    <tr>
                      <td align="left" style="line-height:0px; font-size:0px; mso-table-lspace:0; mso-table-rspace:0;" valign="middle" width="25"><img border="0" height="34" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="25" /></td>
                      <td align="left" style="line-height:0px; font-size:0px;" valign="middle" width="50"><a href="http://emails.TeleSpine.com" title="(opens in a new window)" target="_blank"><img alt="My account" border="0" height="34" src="<!images_url>/assets/email/footer_001.gif" style="display:block;" title="My account" width="42" /></a></td>
                      <td align="left" style="font-size:12px; font-family:Arial, Helvetica, sans-serif; color:#999999;" valign="middle" width="79"><a href="http://emails.TeleSpine.com" style="font-size:12px; font-family:Arial, Helvetica, sans-serif; color:#999999; text-decoration:none;" title="(opens in a new window)" target="_blank"><span style="font-size:12px; font-family:Arial, Helvetica, sans-serif; color:#999999; text-decoration:none;">My<br />
                        Account</span></a></td>
                      <td align="left" style="line-height:0px; font-size:0px;" valign="middle" width="20"><img border="0" height="15" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="20" /></td>
                      <td align="left" style="line-height:0px; font-size:0px;" valign="middle" width="50"><a href="http://emails.TeleSpine.com" title="(opens in a new window)" target="_blank"><img alt="Latest download" border="0" height="34" src="<!images_url>/assets/email/footer_002.gif" style="display:block;" title="Latest download" width="42" /></a></td>
                      <td align="left" style="font-size:12px; font-family:Arial, Helvetica, sans-serif; color:#999999;" valign="middle" width="74"><a href="http://emails.TeleSpine.com" style="font-size:12px; font-family:Arial, Helvetica, sans-serif; color:#999999; text-decoration:none;" title="(opens in a new window)" target="_blank"><span style="font-size:12px; font-family:Arial, Helvetica, sans-serif; color:#999999; text-decoration:none;">Latest<br />
                        updates</span></a></td>
                    </tr>
                    <tr>
                      <td align="left" style="line-height:0px; font-size:0px; mso-table-lspace:0; mso-table-rspace:0;" valign="middle"><img border="0" height="15" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="25" /></td>
                      <td align="left" style="line-height:0px; font-size:0px;" valign="middle"><img border="0" height="15" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="50" /></td>
                      <td align="left" style="line-height:0px; font-size:0px;" valign="middle"><img border="0" height="15" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="79" /></td>
                      <td align="left" style="line-height:0px; font-size:0px;" valign="middle"><img border="0" height="15" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="20" /></td>
                      <td align="left" style="line-height:0px; font-size:0px;" valign="middle"><img border="0" height="15" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="50" /></td>
                      <td align="left" style="line-height:0px; font-size:0px;" valign="middle"><img border="0" height="15" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="74" /></td>
                    </tr>
                  </tbody>
                </table>
                <table align="right" border="0" cellpadding="0" cellspacing="0" class="fullwidth" style="float:right; border:1px solid #ffffff;" width="300">
                  <tbody>
                    <tr>
                      <td align="left" style="line-height:0px; font-size:0px; mso-table-lspace:0; mso-table-rspace:0;" valign="middle" width="25"><img border="0" height="15" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="25" /></td>
                      <td align="left" style="line-height:0px; font-size:0px;" valign="middle" width="50"><a href="http://emails.TeleSpine.com" title="(opens in a new window)" target="_blank"><img alt="More great features" border="0" height="34" src="<!images_url>/assets/email/footer_003.gif" style="display:block;" title="More great features" width="42" /></a></td>
                      <td align="left" style="font-size:12px; font-family:Arial, Helvetica, sans-serif; color:#999999;" valign="middle" width="79"><a href="http://emails.TeleSpine.com" style="font-size:12px; font-family:Arial, Helvetica, sans-serif; color:#999999; text-decoration:none;" title="(opens in a new window)" target="_blank"><span style="font-size:12px; font-family:Arial, Helvetica, sans-serif; color:#999999; text-decoration:none;">More<br />
                        <span style="white-space:nowrap;">great videos</span></span></a></td>
                      <td align="left" style="line-height:0px; font-size:0px;" valign="middle" width="20"><span style="line-height:0px; font-size:0px; mso-table-lspace:0; mso-table-rspace:0;"><img border="0" height="15" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="20" /></span></td>
                      <td align="left" style="line-height:0px; font-size:0px;" valign="middle" width="50"><a href="http://emails.TeleSpine.com" title="(opens in a new window)" target="_blank"><img alt="Need help" border="0" height="34" src="<!images_url>/assets/email/footer_004.gif" style="display:block;" title="Need help" width="42" /></a></td>
                      <td align="left" style="font-size:12px; font-family:Arial, Helvetica, sans-serif; color:#999999;" valign="middle" width="74"><a href="http://emails.TeleSpine.com" style="font-size:12px; font-family:Arial, Helvetica, sans-serif; color:#999999; text-decoration:none;" title="(opens in a new window)" target="_blank"><span style="font-size:12px; font-family:Arial, Helvetica, sans-serif; color:#999999; text-decoration:none;">Need<br />
                        help</span></a></td>
                    </tr>
                    <tr>
                      <td align="left" style="line-height:0px; font-size:0px; mso-table-lspace:0; mso-table-rspace:0;" valign="middle"><img border="0" height="15" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="25" /></td>
                      <td align="left" style="line-height:0px; font-size:0px;" valign="middle"><img border="0" height="15" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="50" /></td>
                      <td align="left" style="line-height:0px; font-size:0px;" valign="middle"><img border="0" height="15" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="79" /></td>
                      <td align="left" style="line-height:0px; font-size:0px;" valign="middle"><img border="0" height="15" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="20" /></td>
                      <td align="left" style="line-height:0px; font-size:0px;" valign="middle"><img border="0" height="15" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="50" /></td>
                      <td align="left" style="line-height:0px; font-size:0px;" valign="middle"><img border="0" height="15" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="74" /></td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
            <tr>
              <td bgcolor="#ffffff" height="15" style="line-height:0px; font-size:0px;"><img border="0" height="15" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="1" /></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
    <tr>
      <td bgcolor="#596b70" style="line-height:0px; font-size:0px;"><img border="0" height="30" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="1" /></td>
    </tr>
    <tr>
      <td align="center" bgcolor="#596b70" valign="middle"><div class="hidedesk" style="display:none; visibility: hidden; max-height:0px; height:0px; width:0px; line-height:0px; font-size:0px; mso-hide:all; overflow:hidden;">
          <table align="center" border="0" cellpadding="0" cellspacing="0" class="hidecta" style="display:none; visibility: hidden; max-height:0px; height:0px; line-height:0px; font-size:0px; mso-hide:all;" width="207">
            <tbody>
              <tr>
                <td width="27"><a href="http://emails.TeleSpine.com"><img alt="F" border="0" height="27" src="<!images_url>/assets/email/sn_fb.gif" style="background-color:#8f8f8f; color:#ffffff; font-size:24px; font-family: Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; text-align:center;" width="27" /></a></td>
                <td height="25" style="line-height:0px; font-size:0px;" width="18"><img border="0" height="26" src="<!images_url>/assets/email/spacer.gif" width="18" /></td>
                <td width="27"><a href="http://emails.TeleSpine.com"><img alt="T" border="0" height="27" src="<!images_url>/assets/email/sn_tw.gif" style="background-color:#8f8f8f; color:#ffffff; font-size:24px; font-family: Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; text-align:center;" width="27" /></a></td>
                <td height="25" style="line-height:0px; font-size:0px;" width="18"><img border="0" height="26" src="<!images_url>/assets/email/spacer.gif" width="18" /></td>
                <td width="27"><a href="http://emails.TeleSpine.com"><img alt="Y" border="0" height="27" src="<!images_url>/assets/email/sn_yt.gif" style="background-color:#8f8f8f; color:#ffffff; font-size:24px; font-family: Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; text-align:center;" width="27" /></a></td>
                <td height="25" style="line-height:0px; font-size:0px;" width="18"><img border="0" height="26" src="<!images_url>/assets/email/spacer.gif" width="18" /></td>
                <td width="27"><a href="http://emails.TeleSpine.com"><img alt="P" border="0" height="27" src="<!images_url>/assets/email/sn_pn.gif" style="background-color:#8f8f8f; color:#ffffff; font-size:24px; font-family: Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; text-align:center;" width="27" /></a></td>
                <td height="25" style="line-height:0px; font-size:0px;" width="18"><img border="0" height="26" src="<!images_url>/assets/email/spacer.gif" width="18" /></td>
                <td width="27"><a href="http://emails.TeleSpine.com"><img alt="R" border="0" height="27" src="<!images_url>/assets/email/sn_rs.gif" style="background-color:#8f8f8f; color:#ffffff; font-size:24px; font-family: Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; text-align:center;" width="27" /></a></td>
              </tr>
            </tbody>
          </table>
        </div></td>
    </tr>
    <tr>
      <td align="center" bgcolor="#596b70" style="line-height:0px; font-size:0px;" valign="middle"><img border="0" height="15" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="18" /></td>
    </tr>
    <tr>
      <td align="center" bgcolor="#596b70" valign="top"><table border="0" cellpadding="0" cellspacing="0" class="fullwidth" width="640">
          <tbody>
            <tr>
              <td bgcolor="#596b70"><table border="0" cellpadding="0" cellspacing="0" class="desktop">
                  <tbody>
                    <tr>
                      <td bgcolor="#596b70" style="line-height:0px; font-size:0px;"><img border="0" height="10" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="14" /></td>
                      <td align="left" bgcolor="#596b70" style="font-size:12px; mso-line-height-rule:exactly; line-height:18px; color:#cccccc; font-family: Arial, Helvetica, sans-serif;" valign="top"><table align="right" bgcolor="#596b70" border="0" cellpadding="0" cellspacing="0" class="fullwidth" style="float:right; border:1px solid #596b70;" width="100%">
                          <tbody>
                            <tr>
                              <td align="left" bgcolor="#596B70" class="heightreduce" height="70" style="font-size:12px; color:#cccccc; font-family: Arial, Helvetica, sans-serif; mso-line-height-rule:exactly; line-height:18px;" valign="top"><b style="color:#cccccc;">© 2013-2014 TeleSpine.</b> The TeleSpine name, associated trade marks and logos and the logo are trade marks of TeleSpine or related entities. <br /></td>
                            </tr>
                          </tbody>
                        </table>
                        <p style="clear:both; line-height:18px;"><b>TeleSpine Address...</b> <br />
                          <br />
                          Be alert to emails that request account information or urgent action. Be cautious of websites with irregular addresses or those that offer unofficial TeleSpine downloads. Security updates and product upgrades are made available at <a href="http://emails.TeleSpine.com" style="color:#cccccc;" title="(opens in a new window)" target="_blank"><span style="color:#cccccc;">www.TeleSpine.com</span></a>.</p>
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                          <tbody>
                            <tr>
                              <td style="font-size:12px; color:#cccccc; font-family: Arial, Helvetica, sans-serif; mso-line-height-rule:exactly; line-height:18px; color:#cccccc;"><a href="http://emails.TeleSpine.com" style="color:#cccccc;" title="(opens in a new window)" target="_blank"><span style="color:#cccccc;">Term of use</span></a>&nbsp;•&nbsp;<a href="http://emails.TeleSpine.com" style="color:#cccccc;" title="(opens in a new window)" target="_blank"><span style="color:#cccccc;">Privacy</span></a>&nbsp;•&nbsp;<a href="http://emails.TeleSpine.com" style="color:#cccccc;" title="(opens in a new window)" target="_blank"><span style="color:#cccccc;">Unsubscribe</span></a></td>
                              <td align="right"><table align="right" border="0" cellpadding="0" cellspacing="0" class="hidemobile">
                                  <tbody>
                                    <tr>
                                      <td width="27"><a href="http://emails.TeleSpine.com"><img alt="Facebook" border="0" height="27" src="<!images_url>/assets/email/sn_fb.gif" style="background-color:#8f8f8f; color:#ffffff; font-size:24px; font-family: Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; text-align:center;" title="Facebook" width="27" /></a></td>
                                      <td height="25" style="line-height:0px; font-size:0px;" width="18"><img border="0" height="26" src="<!images_url>/assets/email/spacer.gif" width="18" /></td>
                                      <td width="27"><a href="http://emails.TeleSpine.com"><img alt="Twitter" border="0" height="27" src="<!images_url>/assets/email/sn_tw.gif" style="background-color:#8f8f8f; color:#ffffff; font-size:24px; font-family: Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; text-align:center;" title="Twitter" width="27" /></a></td>
                                      <td height="25" style="line-height:0px; font-size:0px;" width="18"><img border="0" height="26" src="<!images_url>/assets/email/spacer.gif" width="18" /></td>
                                      <td width="27"><a href="http://emails.TeleSpine.com"><img alt="YouTube" border="0" height="27" src="<!images_url>/assets/email/sn_yt.gif" style="background-color:#8f8f8f; color:#ffffff; font-size:24px; font-family: Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; text-align:center;" title="YouTube" width="27" /></a></td>
                                      <td height="25" style="line-height:0px; font-size:0px;" width="18"><img border="0" height="26" src="<!images_url>/assets/email/spacer.gif" width="18" /></td>
                                      <td width="27"><a href="http://emails.TeleSpine.com"><img alt="Pinterest" border="0" height="27" src="<!images_url>/assets/email/sn_pn.gif" style="background-color:#8f8f8f; color:#ffffff; font-size:24px; font-family: Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; text-align:center;" title="Pinterest" width="27" /></a></td>
                                      <td height="25" style="line-height:0px; font-size:0px;" width="18"><img border="0" height="26" src="<!images_url>/assets/email/spacer.gif" width="18" /></td>
                                      <td width="27"><a href="http://emails.TeleSpine.com"><img alt="RSS" border="0" height="27" src="<!images_url>/assets/email/sn_rs.gif" style="background-color:#8f8f8f; color:#ffffff; font-size:24px; font-family: Segoe UI, Segoe WP, Segoe UI Regular, Helvetica Neue, Helvetica, Tahoma, Arial Unicode MS, Sans-serif; text-align:center;" title="RSS" width="27" /></a></td>
                                    </tr>
                                  </tbody>
                                </table></td>
                            </tr>
                          </tbody>
                        </table></td>
                      <td align="left" bgcolor="#596b70" style="line-height:0px; font-size:0px;" valign="top"><img border="0" height="10" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="14" /></td>
                    </tr>
                    <tr>
                      <td style="line-height:0px; font-size:0px;"><img border="0" height="10" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="14" /></td>
                    </tr>
                    <tr>
                      <td bgcolor="#596b70" colspan="4"><table border="0" cellpadding="0" cellspacing="0" width="100%">
                          <tbody>
                            <tr>
                              <td bgcolor="#596B70" height="20" style="line-height:0px; font-size:0px;"><img border="0" height="20" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="1" /></td>
                              <td bgcolor="#596B70" height="20" style="line-height:0px; font-size:0px;"><img border="0" height="20" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="1" /></td>
                              <td bgcolor="#596B70" style="line-height:0px; font-size:0px;"><img border="0" height="20" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="15" /></td>
                            </tr>
                            <tr>
                              <td align="center" bgcolor="#4f5f64" valign="top"><img alt="" border="0" height="86" src="<!images_url>/assets/email/emergency_call.gif" width="80" /></td>
                              <td align="left" bgcolor="#4f5f64" style="font-size:12px; color:#cccccc; font-family: Arial, Helvetica, sans-serif; mso-line-height-rule:exactly; line-height:18px;" valign="middle"><img border="0" height="10" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="200" />
                                <p style="line-height:18px; mso-line-height-rule:exactly;"><b style="color:#cccccc; font-size:14px;">No emergency calls with TeleSpine</b><br />
                                  TeleSpine is not a replacement for your family health doctor, or for emergencies. If you have an emergency please contact 911, or see your doctor.</p>
                                <img border="0" height="10" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="200" /></td>
                              <td align="left" bgcolor="#4f5f64" style="line-height:0px; font-size:0px;" valign="middle"><img border="0" height="20" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="20" /></td>
                            </tr>
                          </tbody>
                        </table></td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
            <tr>
              <td bgcolor="#596b70" height="50" style="line-height:0px; font-size:0px;"><img border="0" height="50" src="<!images_url>/assets/email/spacer.gif" style="display:block;" width="1" /></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
  </tbody>
</table>
<table>
</table>
<div class="hideunsub" style="display:none; visibility: hidden; max-height:0px; height:0px; width:0px; line-height:0px; font-size:0px; mso-hide:all; overflow:hidden;"><a href="#">Unsubscribe</a></div>
<table style="display:none; height:0px;">
  <tbody>
    <tr>
      <td style="display:none !important; width:0px; height: 0px; color:#ffffff; line-height:0px; visibility:hidden; max-height:0px;font-size:0px;"><a href="#">TeleSpine</a></td>
    </tr>
  </tbody>
</table>
<font color="#666666" face="Verdana,Helvetica" originaltag="yes" size="1"></font><br />
<img width="1" height="1" src="<!images_url>/assets/email/emailReceieved.php?123123x" alt=" " />
<div style="display:none" id="addthis-extension-script"></div>
</body>
</html>