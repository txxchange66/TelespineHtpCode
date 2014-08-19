<div id="container">
	<div id="header">
		<!-- <img src="images/logo2.gif" width="191" height="66" alt="Tx Xchange" class="headLogo" /> -->
	<!header>
</div>
<div id="sidebar">
		<ul id="ulCircle">&nbsp;</ul>

		

		<ul class="sideNav">
			<li class="loginBtn"><a href="index.php?action=logout">Logout</a></li>
			<!-- <li class="helpBtn"><a href="?action=comingSoon">About Tx Xchange</a></li> -->
			<!--<li><h2 style="text-align:left;">TIP</h2></li>
			<li class="rolloverhelp"><div id="rolloverhelp" style="font-size: 85%; text-align:left;padding: 5px 1em;">Rollover buttons or elements on the page to get help and tips.</div><li> -->
		</ul>

		

	</div>

	

	

	

	<div id="mainContent">
      <div style="margin-top:15px;">
      <h1 class="largeH1">PATIENT LOGIN</h1>
      </div>
<div style="padding:10px;color:red"><!error></div>
<br/>

<form id="agreementForm" name="agreementForm" method="post" action="index.php">

<table border="0" cellpadding="2" cellspacing="0">
  <tr>
    <td valign="top">
	
	<table width="90%" border="0" cellspacing="0" cellpadding="0" align="left" class="form_container">
		
      <tr>
        <td> <h4><strong>Account Setup</strong></h4>
        	<p>
			Please take a moment to:
			<br/>
			<br/>
			1. Confirm your Account Information
			<br/>
			2. Enter a 6 character (minimum) personalized password
			<br/>
			3. Read our Terms of Service and Privacy Policy
			<br/>
			<br/>
			Once done, you will be able to access . Thank you.
			</p>          
</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><h3><strong>Account Information</strong></h3> </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td valign="top">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="24%" align="left"><label>First Name : </label></td>
            <td width="21%" align="left">
				<!firstName>            </td>
            <td width="4%">&nbsp;</td>
            <td width="15%" align="left"><label>Address : </label></td>
            <td width="36%" rowspan="5" align="left" valign="top" ><!address></td>
          </tr>
          <tr  class="inputRow">
            <td align="left"><label>Last Name : </label></td>
            <td align="left">
             <!lastName>            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr  class="inputRow">
            <td><label>Email : </label></td>
            <td>
                <!email>          </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          <tr  class="inputRow">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr  class="inputRow">
        <td valign="top" align="center">&nbsp;</td>
      </tr>
      <tr  class="inputRow">
        <td valign="top" align="left"><h3><strong>Security Information</strong></h3></td>
      </tr>
      <tr  class="inputRow">
        <td valign="top" align="center">&nbsp;</td>
      </tr>
      <tr>
        <td valign="top" align="center">

		<table width="100%" border="0" cellspacing="2" cellpadding="1">
  <tr>
    <td width="23%" align="left"><label>Password : </label></td>
    <td width="77%" align="left"><input type="password" name="new_password" style="width:125px;" AUTOCOMPLETE="OFF"/></td>
  </tr>
  <tr>
    <td align="left"><label>Confirm Password :</label> </td>
    <td align="left"><input type="password" name="new_password2" style="width:125px;" AUTOCOMPLETE="OFF"/></td>
  </tr>
  <tr>
    <td align="left"><label>Challenge Questions :</label> </td>
    <td align="left">
	 <select name="question_id" style="width:240px;" >
	 <!questionOptions>
     </select>    
	</td>
  </tr>
  <tr>
    <td align="left"><label>Answer : </label></td>
    <td align="left"><input type="text" name="answer" value="<!answer>" style="width:235px;" /></td>
  </tr>
</table>				</td>
      </tr>
      <tr>
        <td valign="top" align="center">&nbsp;</td>
      </tr>
      <tr>
        <td valign="top" align="center">

		<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left"><h3><strong>Terms of Services</strong></h3></td>
  </tr>
  <tr>
    <td style="padding-left:3px" align="left">
    <div style="width: 700px; height: 300px; text-align:justify; overflow:auto; border-style:solid; border-width:thin; padding-left:20px; padding-top:20px;padding-right:20px;">
        <!terms_services>
    </div>
    </td>
  <tr>
  <td>&nbsp;</td>
  </tr>
  <!privacy_policy>
  <tr>
    <td align="left">
      <table width="100%">
      	<tr><td valign="top" ><input type="checkbox" name="agree_terms" <!agree_terms> /></td>
      		<td valign="top" ><label>I agree to the Terms of Services & Privacy Policy.</label></td>
       	</tr>
       	<tr><td valign="top" ><input type="checkbox" name="email_promotions" <!email_promotions> /></td>
      		<td valign="top" ><label>I agree to receive email from <!channel> regarding updates to the software.</label></td>
      	</tr>
       </table>			
     </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>				</td>
      </tr>
      <tr>
        <td valign="top" align="center">&nbsp;</td>
      </tr>
      <tr>
        <td valign="top" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="center">
			<input type="hidden" name="id" value="<!id>" />
			<input type="hidden" name="action" value="submitPatientAgreement"/>
			<input type="submit" name="Submit" value="Submit" /></td>
          </tr>
        </table></td>
      </tr>
    </table>
	</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<h3>&nbsp;</h3>
</form>



  </div>

	</div>

	

	<div id="footer">



		<!footer>
	</div>

</div>
