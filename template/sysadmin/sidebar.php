<h2>Welcome<br><!name_first> <!name_last></h2><!--<a href="mailto:support@txxchange.com"><img src="images/feedbackIcon.gif" /></a>-->
<div id="navigationalLink" >
<ul class="sideNav">

	<li class="loginBtn"><a href="index.php?action=logout">Logout</a></li>
	<li class="seperator"><img src="images/spacer.gif" width="148" height="1"></li>
	<li class="HomeBtn"><a href="index.php?action=sysAdmin"  class="selected">Home</a></li>
	<li class="Treatment ManagerBtn"><a href="index.php?action=treatmentManager" >Treatment Manager</a></li>
	<li class="Plan TemplatesBtn"><a href="index.php?action=systemPlan" >Plan Templates</a></li>
	<li class="Article LibraryBtn"><a href="index.php?action=articleList" >Article Library</a></li>
    <li class="Subscriber ManagerBtn"><a href="index.php?action=patientList" >Patient Manager</a></li>
    <li ><a href="index.php?action=system_notifications" >Notifications</a></li>
	<li class="Subscriber ManagerBtn"><a href="index.php?action=subscriberListing" >Subscriber Manager</a></li>
	<li class="Subscriber ManagerBtn"><a href="index.php?action=listClinic" >Account Manager</a></li>
    <li id="switch" style="background:url(/images/list_expand.gif) no-repeat 0px;  6px;padding-left:15px;" onclick="switchMenu('eMaintenance',this);" >
        <a href="index.php?action=system_report" onclick="assign();" >System Report</a>
        <div id="eMaintenance" style="padding-left:4px;display:none;margin-right:-10px;" >
            <a href="index.php?action=global_report" onclick="assign();" >All User Report</a>
            <br>
            <a href="index.php?action=new_member" onclick="assign();" >New Members</a>
			<br>
            <a href="index.php?action=invite_list" onclick="assign();" >All Invitee Report</a>
            <br>
            <a href="index.php?action=referralReports" onclick="assign();" >Referral Reports</a>
        </div>
            
        
    </li>
	<li class="seperator"><img src="images/spacer.gif" width="148" height="1"></li>
</ul>
</div>
<ul class="sideNav">
<li class="Subscriber ManagerBtn"><a href="index.php?action=switch_user" onMouseOver="help_text(this, 'Switch to some other Provider or account admin home page')">Switch to User</a></li>
	<!--
	<li><h2 style="text-align:left;">TIP</h2></li>
	<li class="rolloverhelp"><div id="rolloverhelp" style="font-size: 85%; text-align:left;padding: 5px 1em;">Rollover buttons or elements on the page to get help and tips.</div><li>
	-->

</ul>
