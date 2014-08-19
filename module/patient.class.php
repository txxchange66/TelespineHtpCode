<?php
/**
 *
 * Copyright (c) 2008 Tx Xchange
 *
 * It includes the functionality of view reminders, view messages, plans and article library.
 *
 * // necessary classes
 * require_once("module/application.class.php");
 *
 */


// including files
require_once("module/application.class.php");
//require_once("include/postgraph.class.php");
require_once("include/graph.class.php");
require_once ("opentok/API_Config.php");
require_once ("opentok/OpenTokSDK.php");
//    require_once ("module/intakePaper.class.php");
// require_once("module/treatmentManager.class.php");
// class declaration

class patient extends application{

	// class variable declaration section


	/**
	 * Get action from header and calling function
	 * The variable defines the action request
	 *
	 * @var string
	 * @access private
	 */
	private $action;



	/**
	 * Populate html form elements as an array
	 * The variable defines all the fields present in the form
	 *
	 * @var array
	 * @access private
	 */
	private $form_array;



	/**
	 * The variable defines the error message(if any) against the action request
	 * It could be an array if more than one error messages are there else a simple variable
	 *
	 * @var string
	 * @access private
	 */
	private $error;



	/**
	 * Pass as parameter in display() function which shows output in browser.
	 *
	 * The variable is used for getting final output template or string message to be displayed to the user
	 * This function of statement(s) are to handle all the actions supported by this Login class
	 * that is it could be the case that more then one action are handled by login
	 * for example at first the action is "login" then after submit say action is submit
	 * so if login is explicitly called we have the login action set (which is also our default action)
	 * else whatever action is it is set in $str.
	 *
	 * @var string
	 * @access private
	 */
	private $output;


	/**
	 *  constructor
	 *  set action variable from url string, if not found action in url, call default action from config.php
	 *
	 */
	function __construct(){
		parent::__construct();
		if($this->value('action')){
			/*
			 This block of statement(s) are to handle all the actions supported by this Login class
			that is it could be the case that more then one action are handled by login
			for example at first the action is "login" then after submit say action is submit
			so if login is explicitly called we have the login action set (which is also our default action)
			else whatever action is it is set in $str.
			*/
			$str	=	$this->value('action');
		}else{
			$str	=	"patient"; //default if no action is specified
		}
		$this->action	=	$str;
		if($this->get_checkLogin($this->action)	==	"true" ){

			if( isset($_SESSION['username']) && isset($_SESSION['password']) ){

				if(!$this->chk_login($_SESSION['username'],$_SESSION['password'])){

					header("location:index.php");

				}

			}

			else{

				header("location:index.php");

			}

		}

		if($this->userAccess($str)){
			// Code To Call Personalized GUI
			$this->call_patient_gui();
			//$this->call_gui;
			$str = $str."()";
			eval("\$this->$str;");
		}
		else{
			$this->output = $this->config['error_message'];
		}

		$this->display();
		/*
		 $str = $str."()";
		eval("\$this->$str;");
		$this->display();
		*/
	}
	function get_patient_subscription($userid){
		$sql="select subscription_title from patient_subscription where user_id={$userid} and subs_status ='1' order by user_subs_id DESC LIMIT 0,1";
		$res=$this->execute_query($sql);
		if($this->num_rows($res) > 0)
			$row =$this->fetch_object($res);
		return $row->subscription_title;
		 
	}

         /*
        * This chage have been made on data 25-9-2013 by Rohit Mishra for Ticket TXM-33
        */
        
        function patient_section_status()
        {
            $patainent_id = $_REQUEST['patainent_id'];
            $block_name = $_REQUEST['block_name'];
            $staus = $_REQUEST['staus'];
            
            $query = "select * from patient_section_status where patainent_id='$patainent_id' AND block_name='$block_name'"; 
            $result = @mysql_query($query);
            $num = mysql_num_rows($result);
            $row = @mysql_fetch_array($result);
            if($num==0){

               $query ="INSERT INTO  patient_section_status(patainent_id,block_name,status) VALUES('$patainent_id','$block_name','$staus')";
            }else{
               $query ="UPDATE  patient_section_status SET status='$staus' where patainent_id='$patainent_id' AND block_name='$block_name'"; 
            }
    
            
            $result = @mysql_query($query);
        }
        
	/**
	 * display patient home page after login.
	 *
	 * @access public
	 */
	function patient()
	{
		$userInfo = $this->userInfo();
		if (!$userInfo)
		{
			header("location:index.php");
		}
		else
		{
			if($this->value('payment')=='sucess'){
				$ehsname=$this->get_patient_subscription($userInfo['user_id']);
				$message="<tr><td colspan='2' style='border-bottom: #CCC 0px solid; color:green;padding-bottom:30px; font-size:12px; font-weight: bold;'>You have successfully signed-up for {$ehsname}.
				</td></tr>";
				$replace['message']=$message;
			}elseif($this->value('payment')=='update'){
				$ehsname=$this->get_patient_subscription($userInfo['user_id']);
				$message="<tr><td colspan='2' style='border-bottom: #CCC 0px solid; color:green;padding-bottom:30px; font-size:12px; font-weight: bold;'>Your credit card information has been changed successfully for {$ehsname}.
				</td></tr>";
				$replace['message']=$message;
			}
			// set template variables
			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['footer'] = $this->build_template($this->get_template("footer"));

			$replace['browser_title'] = ucfirst($this->get_clinic_name($this->get_clinic_info($this->userInfo('user_id'))))." : Home";

			$userId 							=	$userInfo['user_id'];
			$replace['userName']				=	$userInfo['name_first'];
			$replace['reminder'] 				=	$this->reminderComponent($userId);
			$replace['outcome_measure_link']	=	$this->outcome_measure_link();
			//$replace['therapistInfo']			=	$this->therapistInfoComponent($userId);
			$replace['currTxPlan'] 				=	$this->currTxPlanComponent($userId);
			$replace['recent_message']			=	$this->recentMessageComponent();
			$replace['currArticleLib']			=	$this->articleLibComponent($userId);
			$replace['planHistory']				=	$this->planHistoryComponent($userId);
			//$replace['consultation_section']	=	$this->consultation($userId);
			$replace['newtreatmentplan']		=	$this->newtreatmentplan($userId);
			//$replace['ServicesSummaries']		=	$this->ServicesSummaries($userId);
			$replace['member_goal'] 			=	$this->goal($userId);
			//$replace['scheduling']			=	$this->scheduling($this->clinicInfo('clinic_id'));
			// Get Patient Id.
			if($this->userInfo('usertype_id')	==	1){
				$patient_id	=	$this->userInfo('user_id');
			}
                        
                        /*
                         * This chage have been made on data 25-9-2013 by Rohit Mishra for Ticket TXM-33
                         */
                        
                        $replace['ruserid'] = $this->userInfo('user_id');
                        $replace['result_document_status'] = 1;
                        $replace['treatment_program_status'] = 1;
                                         
                         $query = "select * from patient_section_status where patainent_id = '$patient_id' AND  block_name='ResultDocument'";
                        $result = @mysql_query($query);
                        $num = mysql_num_rows($result);
                        $row = @mysql_fetch_array($result);
                        if($num>0){
                           $replace['result_document_status'] = $row['status']; 
                        }
                        
                        $query = "select * from patient_section_status where patainent_id = '$patient_id' AND  block_name='TreatmentProgram'";
                        $result = @mysql_query($query);
                        $num = mysql_num_rows($result);
                        $row = @mysql_fetch_array($result);
                        if($num>0){
                           $replace['treatment_program_status'] = $row['status']; 
                        }
                        
                        
                        
                        //End Changes 
                        
                        
			$replace['graphMap']				=	$this->showMap($patient_id);
			$replace['sidebar']					=	$this->sidebar();
			$widget								=	$this->checkprxoservice($this->userInfo('user_id'));
			if($widget	==	'store'){
				$replace['widget']				=	$this->build_template($this->get_template("prxo"));
				$replace['clinicname']			=	$this->get_clinic_name($this->get_clinic_info($this->userInfo('user_id')));

			}elseif($widget=='wellness_store'){
				$replace['widget'] 				=	$this->build_template($this->get_template("wellness_store"));
				$replace['clinicname']			=	$this->get_clinic_name($this->get_clinic_info($this->userInfo('user_id')));
				$replace['storeURL']				=	$this->GetstoreURL();
				 
			}elseif($widget=='widget'){
				$replace['widget']				=	$this->build_template($this->get_template("widget"));
			}else{
				$replace['widget']				=	'';
			}

			/* code for get provider type */
			$query = "select therapist_id from therapist_patient
			where patient_id = '{$this->userInfo("user_id")}' and status = 1 order by therapist_patient_id ASC limit 0,1";
			$result								=	@mysql_query($query);
			$rowproveder						=	 @mysql_fetch_array($result);
			$provederid							=	$rowproveder['therapist_id'];
			$ProviderTypeId						=	$this->userInfo('practitioner_type',$provederid);
			$sqlprovide="select name from practitioner where practitioner_id={$ProviderTypeId}";
			$queryprovertype					=	@mysql_query($sqlprovide);
			$rowprovedertype					=	 @mysql_fetch_array($queryprovertype);
			//print_r($rowprovedertype );
			$providertypepai					=	$rowprovedertype['name'];
			$replace['providertype']			=	$providertypepai;
			/*end of code*/
			 
			$replace['javascript'] = "";
			//check elpto or plpto patients
			$patient_id = $this->userInfo(user_id);
			$query = "select clinic_id from clinic_user where user_id = '{$patient_id}'";
			$result = @mysql_query($query);
			$row = @mysql_fetch_array($result);
			$clinic_id = $row['clinic_id'];
			$replace['scheduling'] = $this->scheduling($clinic_id);
			$query = "select service_name from clinic_service where clinic_id = '{$clinic_id}' ";
			$result = @mysql_query($query);
			$row = @mysql_fetch_array($result);
			$elptoCheck=$row['service_name'];
			$acn_num=$om_num=0;
			if($row['service_name'] == 'elpto' ){
				// Following coode will display few popup when member will login first time.
				$company = $this->get_field($patient_id, "user", "company");
				if( $_SESSION['show_popup'] === true){
					if( !empty($company) && is_string($company) ){
						$replace['javascript'] = "openWindow('', '/index.php?action=lbhra');";
					}
					else
						$replace['javascript'] = "openWindow('', '/index.php?action=thanks_page');";
					unset($_SESSION['show_popup']);
				}
				else{
					if( !empty($company) && is_string($company) ){
						$query = "select * from lbhra where patient_id = '{$patient_id}' ";
						$result = @mysql_query($query);
						$row = @mysql_fetch_array($result);
						if( is_null($row) || !is_array($row) ){
							$replace['javascript'] = "openWindow('', '/index.php?action=lbhra');";
						}
					}
					else{

						// This code will show Patient history questionaries. And this page will execute only when patient has closed the popup without filling the form in his first login.
						//$patient_id = $this->userInfo(user_id);
						$query = "select * from acn_patient where patient_id = '{$patient_id}' ";
						$result = @mysql_query($query);
						$acn_num = mysql_num_rows($result);
						$row = @mysql_fetch_array($result);
						if( is_null($row) || !is_array($row) ){
							$replace['javascript'] = "openWindow('', '/netsuite/index.php?action=acn_patient&patient_id={$patient_id}');";
						}
						else{
							// This code will show outcome form questionaries. And this page will execute only when patient has closed the popup without filling the form in his first login.
							$patient_id = $this->userInfo(user_id);
							$query = "select * from patient_om where patient_id = '{$patient_id}' ";
							$result = @mysql_query($query);
							$om_num = mysql_num_rows($result);
							$row = @mysql_fetch_array($result);
							if( is_null($row) || !is_array($row) ){
								$replace['javascript'] = "openWindow('', '/netsuite/index.php?action=outcomeQuestion&patient_id={$patient_id}');";
							}
						}
					}
				}
				// end of intial popup
				//$replace['widget'] = $this->build_template($this->get_template("widget"));
				$replace['markcv'] = $this->build_template($this->get_template("markcv"));
			}





			//code for checking if user has any admin notification message
			$query = "select * from message_notifications_users where  user_id=".$this->userInfo('user_id');
			$result = @mysql_query($query);
			if(@mysql_num_rows($result)>0)
			{
				while($row = @mysql_fetch_object($result)){
					$messageIds.=$row->message_id.",";
				}
				$messageIds=substr($messageIds,0,-1);
				$urlStr="<script>GB_showCenter('Telehealth Network Updates', '/index.php?action=read_notification&ids=".$messageIds."',450,720);</script>";
			}
			else
				$urlStr="";

			if($elptoCheck=="elpto")
			{
				if($acn_num!=0 && $om_num!=0)
					$replace['adminNotifications'] = $urlStr;
			}
			else
			{
				$replace['adminNotifications'] = $urlStr;
			}




			/* code for intake paper work*/

			$replace['intakeform'] ='';
			//echo $_SESSION['intakepaper'];
			if($urlStr==""){
				$sqlintake="select * from patient_intake where intake_compl_status='0' And p_user_id= ".$this->userInfo('user_id');
				$resultintake=$this->execute_query($sqlintake);
				if($this->num_rows($resultintake)>0){
					$rowintake=$this->fetch_array($resultintake);
					if($rowintake['intake_compl_status']==0){
						if($rowintake['intake_last_page']=='')
							$pagenumber=0;
						else
							$pagenumber=$rowintake['intake_last_page'];
						$replace['intakeform'] ="<script>GB_showCenter('Adult Intake Paperwork', '/index.php?action=fillintakepaperwork',720,960);</script>";
						$_SESSION['intakepaper']='assign';
					}
				}else{
					//$_SESSION['intakepaper']='assign';
					$sqlintake="select * from patient_brief_intake where p_user_id= ".$this->userInfo('user_id');
					$resultintake=$this->execute_query($sqlintake);
					if($this->num_rows($resultintake)>0){
						$rowintake=$this->fetch_array($resultintake);
						if($rowintake['intake_compl_status']==0){
							$replace['intakeform'] ="<script>GB_showCenter('Brief Intake Paperwork', '/index.php?action=fillbriefintakepaperwork',720,960);</script>";
							$_SESSION['intakepaper']='assign';
						}
					}

				}



			}


			/*   end of code*/







			$sql="select lab_report_id,lab_title,DATE_FORMAT(lab_datetime, '%m/%d/%Y') as labdate,lab_path from patient_lab_reports where pat_user_id ={$patient_id} and makeprivate='0' order by lab_report_id desc";

			$query=$this->execute_query($sql);
			//$row=$this->fetch_array($query);

			$rowLab = $this->populate_array($query,0);
			if($rowLab!=='')
			{
				foreach($rowLab as $klab => $kValue)
				{
					//print_r($kValue);
					$replace['labresult_display'].='<tr >
					<td style=" border-bottom:1px solid #cbcbcb;color:#005b85; padding-left:12px; width:100%; "><a href="index.php?action=downloadfile&lab_report_id='.$kValue['lab_report_id'].'" target="_NEW" >'.$kValue['lab_title'].' '.$kValue['labdate'].'</a> </td>

					</tr>';
				}
			}
			/***end of view intake paper work***/
			/* code for show lab result*/

			$replace['LabResult']="<a href='javascript:void(0);' onclick=\"GB_showCenter('Upload Results & Documents', '/index.php?action=aa_upload_lab_result&pid={$patient_id}&role=pat',190,430);\"><img src=\"images/btn_upload.gif\" alt=\"Upload\" title=\"Upload\" align=\"left\"/></a>";

			$replace['ViewGoal']="<a href='javascript:view_goal();'  id='allLink'  ><img src=\"images/btn_view_all.gif\" alt=\"View All\" title=\"view All\" align=\"absmiddle\"/></a>";

			$replace['AddGoal']="<a href='javascript:add_goal();' ><img src=\"images/btn_add_a_goal.gif\" alt=\"Add a Goal\" title=\"Add a Goal\" align=\"absmiddle\"/></a>";
			 

			$replace['body'] = $this->build_template($this->get_template("patient"),$replace);
			// Personalized GUI
			$replace['headingMessages']=strtoupper($_SESSION['patientLabel']['Messages']);
			$replace['headingVideoPlans']=strtoupper($_SESSION['patientLabel']['Video Plans']);
			$replace['headingSuggestedReading']=strtoupper($_SESSION['patientLabel']['Suggested Reading']);
			$replace['headingReminders']=strtoupper($_SESSION['patientLabel']['Reminders']);
			$replace['headingPatientGoals']=strtoupper($_SESSION['patientLabel']['Patient Goals']);
                        
                        
			if($_SESSION['clinicfeature']['Outcomes Measures Graph']=='1')
				$replace['paitentomgraph']=$this->build_template($this->get_template('paitentomgraph'),$replaceSOAP);
			else{
				$replace['paitentomgraph']='';
			}
			$this->output = $this->build_template($this->get_template("main"),$replace);
		}

	}
	/**
	 * This function will return list of Goals.
	 */
	function goal($userId){
		$query = " select * from patient_goal where created_by = '{$userId}' and (status = 1 or status = 2) order by patient_goal_id desc";
		$result = @mysql_query($query);
		$replace['view_all'] = '';
		if( @mysql_num_rows($result) > 0 ){
			while( $row = @mysql_fetch_array($result) ){
				$strike = "";
				$checked = "";
				if($row['status'] == 2 ){
					$strike = 'text-decoration: line-through';
					$checked = 'checked';
				}

				//$style = ($c++%2)?"line1":"line2";
				$row['goal'] = $this->lengthtcorrect($row['goal'],38);
				$replace['list_goal'] .= "<tr class=".$style."><td style=\"height:25px;\"><div id='div_{$row['patient_goal_id']}' onmouseover='show_trash(this,1);' onmouseout='show_trash(this,2);' style='padding-left:0px;' >
				<span id='trash_{$row['patient_goal_id']}' style='visibility:hidden;' onclick='del_goal(this);'>
				<img src='/images/trash.gif' />
				</span><input type='checkbox' name='chk_{$row['patient_goal_id']}' value='{$row['patient_goal_id']}' $checked onclick='stikeout(this);' align='bottom' />
				&nbsp;<span id='span_{$row['patient_goal_id']}'  style='{$strike}' vertical-align:top;'  >".$row['goal']."</span></div></td></tr>";

			}
			//$replace['view_all'] = '<table width="100%"><tr><td><table align="right"><tr><td style="border-bottom: #CCC 0px solid; " ><a href="javascript:view_goal();"  id="allLink"  >View all</a>&nbsp;&nbsp;|&nbsp;&nbsp; <a href="javascript:add_goal();" >Add a Goal</a></td></tr></table></td></tr></table>';
		}else{
			//$replace['view_all'] = '<table width="100%"><tr><td><table align="right"><tr><td style="border-bottom: #CCC 0px solid; "><a href="javascript:add_goal();" >Add a Goal</a></td></tr></table></td></tr></table>';
		}
		$output = $this->build_template($this->get_template('goal'),$replace);
		return $output;
	}
	/**
	 * This function list consultation notes to member.
	 */
	function consultation($userId){
		if( is_numeric($userId)){
			$query = "select * from soap_notes where patient_id = '{$userId}' order by created_on desc ";
			$result = @mysql_query($query);
			while( $row = @mysql_fetch_array($result) ){
				$row['date'] = $this->formatDate($row['created_on']);
				$replace['note_dates'] .= $this->build_template($this->get_template("soapRecord"),$row);
			}
			$output = $this->build_template($this->get_template('note_dates'),$replace);
		}
		return $output;
		 
	}
	/**
	 * View SOAP Notes
	 */
	function viewSoapNoteMember(){
		$soap_notes_id = $this->value('soap_notes_id');
		$query = " select * from soap_notes where soap_notes_id = '{$soap_notes_id}' ";
		$result = @mysql_query($query);
		if( $row = @mysql_fetch_array($result)){
			$encrypt_field = array('subjective','objective', 'assessment', 'plan');
			$row = $this->decrypt_field($row, $encrypt_field);
			$row['soap_notes_id'] = $soap_notes_id;
			$this->output = $this->build_template($this->get_template("viewSoapNote"),$row);
		}

	}
	/**
	 * View SOAP Notes
	 */
	function printSoapNotes(){
		$soap_notes_id = $this->value('soap_notes_id');
		$query = " select * from soap_notes where soap_notes_id = '{$soap_notes_id}' ";
		$result = @mysql_query($query);
		if( $row = @mysql_fetch_array($result)){
			$encrypt_field = array('subjective','objective', 'assessment', 'plan');
			$row = $this->decrypt_field($row, $encrypt_field);
			$row['date'] = $this->formatDate($row['created_on']);
			$this->output = $this->build_template($this->get_template("printSoapNotes"),$row);
		}

	}
	/**
	 * Get Map co-ordinate
	 */
	/*function showMap($patient_id){

	$data = $this->getData($patient_id);
	$graphdate = $this->getGraphDate($patient_id);

	// creates graph object
	$graph = new PostGraph(400,300);

	// set titles
	$graph->setGraphTitles('', '# of Functional Surveys', 'Level of Disability');

	// set format of number on Y axe
	$graph->setYNumberFormat('integer');

	// set number of ticks on Y axe
	$graph->setYTicks(10);

	// set data
	$graph->setData($data);

	$graph->setBackgroundColor(array(255, 255, 255));
	$graph->setBarsColor(array(72, 107, 143));

	$graph->setTextColor(array(0, 0, 0));

	// set orientation of text on X axe
	$graph->setXTextOrientation('horizontal');

	// prepare image
	$graph->drawImage();

	// Show map
	$mapstr = '<map name="map1">';
	//print_r($graph->barValue);
	foreach( $graph->barValue as $key => $value ){
	if( empty($value['value']) || $value['value'] == '' ){
	$value['value'] = '0';
	}
	if( !empty($graphdate[$key]) ){
	if( $value['value'] == '0' ){
	$value['x1'] = $value['x1'] + 3;
	$value['y1'] = $value['y1'] + 3;
	}
	$mapstr .= "<area shape='rect' coords='{$value['x1']},{$value['y1']},{$value['x2']},{$value['y2']}' alt='{$value['value']}%:{$graphdate[$key]}' title='{$value['value']}%:{$graphdate[$key]}' href='#' />";
	}
	}
	$mapstr .= '</map>';

	return $mapstr;

	}*/
	/**
	 * Get Map co-ordinate
	 */
	function showMap($patient_id){

		$graphdate = $this->getGraphDate($patient_id);
		//Get Patient Id.
		$patient_id = $this->userInfo('user_id');

		// Get OSW Score
		$scoreOSW = $this->getLineGraphScore(1,$patient_id);
		foreach($scoreOSW as $key => $value){
			if( is_null($value['score']) ){
				$scoreOSW1[] = 0;
			}
			else{
				if( is_numeric($value['score']) ){
					$scoreOSW1[] = $value['score'];
				}
				else{
					$scoreOSW1[] = -1;
				}
			}
			$scoreOSWDate[] = $value['submited_on'];
			$TypeOSW[] = 1;
		}

		// Get NDI Score
		$scoreNDI = $this->getLineGraphScore(2,$patient_id);
		foreach($scoreNDI as $value){
			if( is_null($value['score']) ){
				$scoreNDI1[] = 0;
			}
			else{
				if( is_numeric($value['score']) ){
					$scoreNDI1[] = $value['score'];
				}
				else{
					$scoreNDI1[] = -1;
				}
			}
			$scoreNDIDate[] = $value['submited_on'];
			$TypeNDI[] = 2;
		}

		// Get FABQ1 & FABQ2 Score
		$scoreFABQ = $this->getLineGraphScore(4,$patient_id);
		foreach($scoreFABQ as $value){
			if( is_null($value['score']) ){
				$scoreFABQA[] = 0;
			}
			else{
				if( is_numeric($value['score']) ){
					$scoreFABQA[] = $value['score'];
				}
				else{
					$scoreFABQA[] = -1;
				}
			}

			if( is_null($value['score2']) ){
				$scoreFABQW[] = 0;
			}
			else{
				if( is_numeric($value['score2']) ){
					$scoreFABQW[] = $value['score2'];
				}
				else{
					$scoreFABQW[] = -1;
				}
			}
			$scoreFABQDate[] = $value['submited_on'];
			$TypeFABQ[] = 4;
		}

		// Get LEFS Score
		$scoreLEFS = $this->getLineGraphScore(5,$patient_id);
		foreach($scoreLEFS as $key => $value){
			if( is_null($value['score']) ){
				$scoreLEFS1[] = 0;
			}
			else{
				if( is_numeric($value['score']) ){
					$scoreLEFS1[] = $value['score'];
				}
				else{
					$scoreLEFS1[] = -1;
				}
			}
			$scoreLEFSDate[] = $value['submited_on'];
			$TypeLEFS[] = 1;
		}

		// Get DASH Score
		$scoreDASH = $this->getLineGraphScore(6,$patient_id);
		foreach($scoreDASH as $key => $value){
			if( is_null($value['score']) ){
				$scoreDASH1[] = 0;
			}
			else{
				if( is_numeric($value['score']) ){
					$scoreDASH1[] = $value['score'];
				}
				else{
					$scoreDASH1[] = -1;
				}
			}
			$scoreDASHDate[] = $value['submited_on'];
			$TypeDASH[] = 6;
		}

		// Get painscale Score
		$scorePS = $this->getLineGraphScore(7,$patient_id);
		foreach($scorePS as $key => $value){
			if( is_null($value['score']) ){
				$scorePS1[] = 0;
			}
			else{
				if( is_numeric($value['score']) ){
					$scorePS1[] = $value['score'];
				}
				else{
					$scorePS1[] = -1;
				}
			}
			$scorePSDate[] = $value['submited_on'];
			$TypePS[] = 7;
		}
		// creates line graph object
		$chart = new graph(400,300);

		$chart->parameter['path_to_fonts'] = 'font/';                     // path to where fonts are stored
		$chart->parameter['title'] = ' ';
		$chart->parameter['x_label'] = '# of Assessments Taken';        // X-Axis Lavel
		$chart->parameter['y_label_left'] = 'Level of Disability';    // Y-Axis Lavel
		$chart->parameter['inner_border_type'] = 'box';                    // Border X,Y-Axis
		$chart->parameter['y_ticks_colour'] = 'black';                    // Y-Axis ticks
		$chart->parameter['x_ticks_colour'] = 'black';                    // X-Axis ticks
		$chart->parameter['label_colour'] = 'gray33';                    // X and Y-Axis color
		$chart->parameter['y_grid'] = 'line';                            // Y- Axis Grid line
		$chart->parameter['axis_colour'] = 'gray33';                    // colour of axis text
		$chart->parameter['y_min_left'] = 0;                              // start at 0
		$chart->parameter['y_max_left'] = 100;                             // and go to 50
		$chart->parameter['y_decimal_left'] = 0;                         // 0 decimal places for y axis.
		$chart->parameter['point_size'] = 6;
		$chart->parameter['y_resolution_left']= 1;
		$chart->parameter['y_decimal_left']= 0;


		$chart->x_data = array(1,2,3,4,5,6,7,8,9,10);                     // X-Axis parameters

		$arrayLines=array('osw','ndi','fabqa','fabqw','lefs','dash','painscale');                    // Names of lines

		$arr = array($scoreOSW1,$scoreNDI1,$scoreFABQA,$scoreFABQW,$scoreLEFS1,$scoreDASH1,$scorePS1);

		$arrDate = array($scoreOSWDate,$scoreNDIDate,$scoreFABQDate,$scoreFABQDate,$scoreLEFSDate,$scoreDASHDate,$scorePSDate);

		$arrayformType = array($TypeOSW,$TypeNDI,$TypeFABQ,$TypeFABQ,$TypeLEFS,$TypeDASH,$TypePS);

		$counter=0;
		foreach ($arrayLines as $value) {

			$chart->y_data[$value] = array($arr[$counter][0], $arr[$counter][1], $arr[$counter][2], $arr[$counter][3], $arr[$counter][4], $arr[$counter][5], $arr[$counter][6], $arr[$counter][7], $arr[$counter][8], $arr[$counter][9], $arr[$counter][10]);

			$chart->y_scoreDate[$value] = array($arrDate[$counter][0], $arrDate[$counter][1], $arrDate[$counter][2], $arrDate[$counter][3], $arrDate[$counter][4], $arrDate[$counter][5], $arrDate[$counter][6], $arrDate[$counter][7], $arrDate[$counter][8], $arrDate[$counter][9], $arrDate[$counter][10]);

			$chart->y_formType[$value] = array($arrayformType[$counter][0], $arrayformType[$counter][1], $arrayformType[$counter][2], $arrayformType[$counter][3], $arrayformType[$counter][4], $arrayformType[$counter][5], $arrayformType[$counter][6], $arrayformType[$counter][7], $arrayformType[$counter][8], $arrayformType[$counter][9], $arrayformType[$counter][10]);

			$counter++;
		}

		$chart->y_format['osw'] =
		array('colour' => 'blue', 'line' => 'line', 'point' => 'square');

		$chart->y_format['ndi'] =
		array('colour' => 'red', 'line' => 'line', 'point' => 'square');

		$chart->y_format['fabqa'] =
		array('colour' => 'green', 'line' => 'line', 'point' => 'square');

		$chart->y_format['fabqw'] =
		array('colour' => 'black', 'line' => 'line', 'point' => 'square');

		$chart->y_format['lefs'] =
		array('colour' => '#0A558F', 'line' => 'line', 'point' => 'square');

		$chart->y_format['dash'] =
		array('colour' => '#F1BF2B', 'line' => 'line', 'point' => 'square');

		$chart->y_format['painscale'] =
		array('colour' => 'yellow', 'line' => 'line', 'point' => 'square');
		// order in which to draw data sets.
		$chart->y_order =$arrayLines;

		// Show map
		$chart->drawAreaTag();
		$chart->getxy();

		$mapstr = '<map name="map1">';
		foreach( $chart->valuesAll as $key => $value ){
			if( $value[4] == -1){
				continue;
			}
			if( $value[6] == 4 ){
				$mapstr .= "<area shape='rect' coords='{$value[0]},{$value[1]},{$value[2]},{$value[3]}' alt='Score $value[4] : {$value[5]}' title='Score $value[4] : {$value[5]}' href='javascript:void(0);' />";
			}else{
				$mapstr .= "<area shape='rect' coords='{$value[0]},{$value[1]},{$value[2]},{$value[3]}' alt='{$value[4]}% : {$value[5]}' title='{$value[4]}% : {$value[5]}' href='javascript:void(0);' />";
			}

		}
		$mapstr .= '</map>';
		return $mapstr;

	}
	/**
	 * Show Outcome Measure Link.
	 */
	function outcome_measure_link(){
		//$flag = $this->questionnaire_link();
		//if( !($flag === false) ){
		//   return $flag;
			//}
			$patient_id = $this->userInfo('user_id');
			$query = " select * from patient_om where patient_id = '{$patient_id}' and status = 1 order by patient_om_id ASC";
			$result = @mysql_query($query);
			$outcomeLink = "";
			if( @mysql_num_rows($result) > 0 ){
				$title_arr[1] = 'Oswestry';
				$title_arr[2] = 'Neck Disability Index';
				$title_arr[4] = 'Fear-Avoidance Beliefs Questionnaire (FABQ)';
				$title_arr[5] = 'Lower Extremity Functional Scale Questionnaire (LEFS)';
				$title_arr[6] = 'Disability of the Arm, Shoulder and Hand Questionnaire (DASH)';
				$row = @mysql_fetch_array($result);
				//$title = $title_arr[$row['om_form']];
				$title ='Outcome Measures';
				$url = "/index.php?action=show_outcome_form&patient_id={$patient_id}";
				$outcomeLink = '<a href="javascript:void(0)" onclick="openWindow('."'".$title."'".','."'".$url."'".');" ><img src="images/blink.gif" width="300" height="23" alt="" /></a>';
			}else{
				$query = " select * from lbhra where patient_id = '{$patient_id}' and status = 1 order by lbhra_patient_id ASC";
				$result = @mysql_query($query);
				$outcomeLink = "";
				if( @mysql_num_rows($result) > 0 ){
					$row = @mysql_fetch_array($result);
					//$title = $title_arr[$row['om_form']];
					$title ='Outcome Measures';
					$url = "/index.php?action=show_outcome_form&patient_id={$patient_id}";
					$outcomeLink = '<a href="javascript:void(0)" onclick="openWindow('."'".$title."'".','."'".$url."'".');" ><img src="images/blink.gif" width="300" height="23" alt="" /></a>';
				}else{
					$query = " select * from acn_patient where patient_id = '{$patient_id}' and status = 1 order by acn_patient_id ASC";
					$result = @mysql_query($query);
					$outcomeLink = "";
					if( @mysql_num_rows($result) > 0 ){
						$row = @mysql_fetch_array($result);
						//$title = $title_arr[$row['om_form']];
						$title ='Outcome Measures';
						$url = "/index.php?action=show_outcome_form&patient_id={$patient_id}";
						//netsuite/index.php?action=acn_patient&patient_id={$patient_id}";
						$outcomeLink = '<a href="javascript:void(0)" onclick="openWindow('."'".$title."'".','."'".$url."'".');" ><img src="images/blink.gif" width="300" height="23" alt="" /></a>';
					}
				}
			}
			 
			 
			return $outcomeLink;
	}
	/**
	 * Show Questionnaire Link.
	 */
	function questionnaire_link(){
		$patient_id = $this->userInfo('user_id');
		$query = " select * from assign_questionnaire where patient_id = '{$patient_id}' and status = 1 ";
		$result = @mysql_query($query);
		$outcomeLink = "";
		if( @mysql_num_rows($result) > 0 ){
			$row = @mysql_fetch_array($result);
			$title = 'Patient Questionnaire';
			$url = "/netsuite/index.php?action=acn_patient&patient_id={$patient_id}";
			$outcomeLink = '<a href="javascript:void(0)" onclick="openWindow('."'".$title."'".','."'".$url."'".');" ><img src="images/blink-fill-question.gif" alt="" /></a>';
			return $outcomeLink;
		}
		return false;
	}

	/**
	 * Display outcome form.
	 */
	function show_outcome_form(){
		$patient_id = $this->userInfo('user_id');
		$form_type = $this->value('form_type');
		$replace['patient_om_id'] = $this->value('patient_om_id');
		$painscale='no';
		$flag='one';
		$sqlOm="SELECT * FROM patient_om WHERE patient_id=".$patient_id." and status=1 order by patient_om_id ASC";
		$resOm=$this->execute_query($sqlOm);
		$numrow=$this->num_rows($resOm);
		if($numrow > 0){
			$row=$this->fetch_object($resOm);
			$replace['patient_om_id']=$row->patient_om_id;
			$form_type=$row->om_form;
			$flag='two';

		}else{
			$sqlOm="SELECT * FROM lbhra WHERE patient_id=".$patient_id." and status=1 order by lbhra_patient_id ASC";
			$resOm=$this->execute_query($sqlOm);
			$numrow=$this->num_rows($resOm);
			if($numrow > 0){
				$form_type=7;
				$flag='two';
			}else{
				$sqlOm="SELECT * FROM acn_patient WHERE patient_id=".$patient_id." and status=1 order by acn_patient_id ASC";
				$resOm=$this->execute_query($sqlOm);
				$numrow=$this->num_rows($resOm);
				if($numrow > 0){
					$form_type=8;
					$flag='two';
				}

			}
		}

		if($flag=='one'){
			$this->output = $this->build_template($this->get_template('closePopup'),$replace);
			return;
		}

		if( $this->value('submitted') != "" ){

			$score = $this->submit_outcome_form($replace['patient_om_id'],$form_type);

			if( is_numeric($score) ){
				$score_first = $score;
			}elseif( is_array($score) ){
				$score_first = $score[0];
				$score_second = $score[1];
				$painscale    =  $score[1];
			}

			$patient_om_arr = array(
					'score' => $score_first,
					'score2' => $score_second,
					'status' => 2,
					'submited_on' => gmdate('Y-m-d H:i:s')
			);

			// print_r($patient_om_arr);
			//exit;
			$where = " status = 1 and patient_om_id = '{$replace['patient_om_id']}' ";
			$this->update('patient_om',$patient_om_arr,$where);
			if($painscale!='no' and $form_type==1){
				$sql="select * from patient_om where patient_om_id = '{$replace['patient_om_id']}'";
				$res=$this->execute_query($sql);
				$row_n=$this->fetch_object($res);
				$arr=array(
						'om_form'=>7,
						'score'=>$painscale,
						'score2'=>'',
						'patient_id'=>$row_n->patient_id,
						'therapist_id'=>$row_n->therapist_id,
						'status'=>2,
						'created_on'=>$row_n->created_on,
						'submited_on'=>gmdate('Y-m-d H:i:s')
				);
				$this->insert('patient_om',$arr);
			}
			 
			 
			 
			 
			 
			 
			/* send internal message to therapist*/
			if($form_type == 1){
				$subject = "Oswestry Completed";
			}
			elseif($form_type == 2 ){
				$subject = "NDI Completed";
			}
			elseif($form_type == 4 ){
				$subject = "FABQ Completed";
			}
			elseif($form_type == 5 ){
				$subject = "LEFS Completed";
			}
			elseif($form_type == 6 ){
				$subject = "DASH Completed" ;
			}
			$data = array(
					'patient_id' => $this->userInfo("user_id"),
					'sender_id' => $this->userInfo("user_id"),
					'subject' => $this->encrypt_data($subject),
					'content' => '',
					'parent_id' => '0',
					'sent_date' => date('Y-m-d H:i:s',time()),
					'recent_date' => date('Y-m-d H:i:s',time()),
					'replies' => '0'
			);

			if($this->insert("message",$data)){
				$message_id = $this->insert_id();

				// Entry for Therapist


				$query = "select therapist_id from therapist_patient
				where patient_id = '{$this->userInfo("user_id")}' and status = 1 ";
				$result = @mysql_query($query);
				while( $row = @mysql_fetch_array($result)){
					// Entry for Therapist
					$data = array(
							'message_id' => $message_id,
							'user_id' => $row['therapist_id'],
							'unread_message' => '1'
					);
					$this->insert("message_user",$data);
					if( $row->therapist_id != "" && is_numeric($row['therapist_id']) ){
						$this->new_post_notification($row['therapist_id']);
					}
				}
				 
				 
				$data = array(
						'message_id' => $message_id,
						'user_id' => $this->userInfo("user_id"),
						'unread_message' => '2'
				);
				$this->insert("message_user",$data);
			}
			/* end of message*/

			$sqlOm="SELECT * FROM patient_om WHERE patient_id=".$patient_id." and status=1 order by patient_om_id ASC";
			$resOm=$this->execute_query($sqlOm);
			$numrow=$this->num_rows($resOm);
			$lbhra = " select * from lbhra where patient_id = ".$patient_id." and status = 1 order by lbhra_patient_id ASC";
			$resultLbhra = @mysql_query($lbhra);
			$numrowLbhra=$this->num_rows($resultLbhra);
			$sqlacn_patient="SELECT * FROM acn_patient WHERE patient_id=".$patient_id." and status=1 order by acn_patient_id ASC";
			$resacn_patient=$this->execute_query($sqlacn_patient);
			$numrowacn_patient=$this->num_rows($resacn_patient);
			if($numrow > 0){
				$row=$this->fetch_object($resOm);
				$replace['patient_om_id']=$row->patient_om_id;
				$form_type=$row->om_form;
			}elseif($numrowLbhra > 0){
				$form_type=7;
			} elseif($numrowacn_patient > 0){
				$form_type=8;
			}else{
				$this->output = $this->build_template($this->get_template('closePopup'),$replace);
				return;
			}
		}

		if($form_type == 1){
			$template_path = "oswestry";
		}
		elseif($form_type == 2 ){
			$template_path = "ndi";
		}
		elseif($form_type == 4 ){
			$template_path = "fabq";
		}
		elseif($form_type == 5 ){
			$template_path = "lefs";
		}
		elseif($form_type == 6 ){
			$template_path = "dash";
		}elseif($form_type == 7 ){
			$template_path = "thanks";
		}elseif($form_type == 8 ){
			$template_path = "acn_patient";
		}
		$replace['form_type'] = $row->om_form;
		$this->output = $this->build_template($this->get_template($template_path),$replace);
	}
	/**
	 * Process submitted outcome measure form.
	 */
	function submit_outcome_form($patient_om_id,$form_type){
		if( $form_type == 1){
			$question_arr = array(
					'pain_intensity',
					'personal_care',
					'lifting',
					'walking',
					'sitting',
					'standing',
					'sleeping',
					'social_life',
					'traveling',
					'employment_homemaking'
			);
		}
		elseif( $form_type == 2 ){
			$question_arr = array(
					'pain_intensity',
					'concentration',
					'personal_care',
					'work',
					'lifting',
					'driving',
					'reading',
					'sleeping',
					'headaches',
					'recreation'
			);
		}
		elseif( $form_type == 4 ){
			$question_arr = array(array(
					'q2',
					'q3',
					'q4',
					'q5'
			),
					array(
							'q6',
							'q7',
							'q9',
							'q10',
							'q11',
							'q12',
							'q15'
					)
			);
		}
		elseif( $form_type == 5 ){
			$question_arr = array(
					'usual_work',
					'usual_hobbies',
					'bath',
					'rooms',
					'shoes',
					'squatting',
					'groceries',
					'light_activities',
					'heavy_activities',
					'car',
					'blocks',
					'mile',
					'stairs',
					'standing',
					'sitting',
					'even_ground',
					'uneven_ground',
					'sharp_turns',
					'hopping',
					'bed'
			);
		}
		elseif( $form_type == 6 ){
			$question_arr = array(
					'jar',
					'write',
					'key',
					'meal',
					'door',
					'object_shelf',
					'household',
					'garden',
					'bed',
					'shopping',
					'heavy',
					'bulb',
					'hair',
					'back',
					'pullover',
					'knife',
					'recreational',
					'recreational_force',
					'recreational_free',
					'transportation',
					'sexual',
					'week_extent',
					'week_limited',
					'arm_pain',
					'arm_pain_specific',
					'tingling',
					'weakness',
					'stiffness',
					'week_sleep',
					'capable',
			);
		}
		$score = 0;
		$cnt = 0;
                $cnt1 = 0;
		$scalearr = array();
		if( $form_type == 1){
                        $arr = array();
			foreach( $question_arr as  $value ){
				if(array_key_exists($value, $_REQUEST)){
                                        $arr[] = $_REQUEST["{$value}"][0];
					foreach( $arr as $key => $value ){
						$score += ($value - 1);
						$cnt++;
					}
				}
			}
			$score = round(($score/(5*$cnt))*100);
			$painscale=($_REQUEST['painscale'][0])*10;
			array_push($scalearr,$score);
			array_push($scalearr,$painscale);

			return $scalearr;

		}elseif($form_type == 2 ){
                    $arr = array();
			foreach( $question_arr as  $value ){
				if(array_key_exists($value, $_REQUEST)){
					$arr[] = $_REQUEST["{$value}"][0];
					foreach( $arr as $key => $value ){
						$score += ($value - 1);
						$cnt++;
					}
				}
			}
			$score = round(($score/(5*$cnt))*100);
			return $score;
			 
			 
		}

		elseif( $form_type == 5){
                    $arr = array();
			foreach( $question_arr as  $value ){
				if(array_key_exists($value, $_REQUEST)){
					$arr[] = $_REQUEST["{$value}"][0];
					foreach( $arr as $key => $value ){
						$score += $value;
						$cnt++;
					}
				}
			}
			return $score;

		}
		elseif( $form_type == 6){
                    $arr = array();
			foreach( $question_arr as  $value ){
				if(array_key_exists($value, $_REQUEST)){
					$arr[] = $_REQUEST["{$value}"][0];
					foreach( $arr as $key => $value ){
						$score += $value;
						$cnt++;
					}
				}
			}
			$score = round((($score/$cnt) - 1) * 25);
			return $score;

		}
		elseif($form_type == 4){
                    $arr = array();
			// For FABQ scoring
			foreach( $question_arr as $key => $value ){
				//print_r($question_arr);
				if($key == 0 ){
					// For Scale 1 or FABQA Array Element
					foreach( $value as $value1 ){
						if(array_key_exists($value1, $_REQUEST)){
							$arr[] = $_REQUEST["{$value1}"][0];
							foreach( $arr as $key => $value1 ){
								$score += $value1;
								$cnt++;
							}
						}
					}
					//$score = round(($score/(6*$cnt))*100);
					//$score = ($score == 0 || is_null($score))?0:$score;
					array_push($scalearr,$score);

				}else if($key == 1){
					// For Scale 1 or FABQW Array Element
					foreach( $value as $value2 ){
						if(array_key_exists($value2, $_REQUEST)){
                                                        $arr1[] = $_REQUEST["{$value2}"][0];
							foreach( $arr1 as $key => $value2 ){
								$score2 += $value2;
								$cnt1++;
							}
						}
					}
					//$score2 = round(($score2/(6*$cnt1))*100);
					//$score2 = ($score2 == 0 || is_null($score))?0:$score2;
					array_push($scalearr,$score2);
				}
			}
			//exit;
			return $scalearr;
		}
	}

	/**
	 * This function replaces all URL to hyperlink.
	 *
	 */
	function replace_link_hyperlink($input){


		$regex = '|http(s)?://([\w-]+\.)+[\w-]+(/[\w- ./?%&=]*)?|i';

		$replacement = '<a href="$0" target=new >$0</a>';
		$input = preg_replace($regex, $replacement, $input);

		return $input;

	}
	/**
	 * Function returns bulletin message for current clinic.
	 */

	function bulletin_message(){

		if($this->value('action') != 'patient' ){
			return "";
		}

		// Retrive bulletin message from database.
		if( $this->clinicInfo("clinic_id") != ""  ){

			$query = "select * from bulletin_board where clinic_id = '{$this->clinicInfo("clinic_id")}' ";
			$result = @mysql_query($query);
			if( $row = @mysql_fetch_array($result)  ){
				$data = array(
						//'message' => $this->replace_link_hyperlink($row['message'])
						'message' => htmlspecialchars_decode($row['message']) // row changed by MOHIT SHARMA
				);

				$data['message'] = $this->replace_link_hyperlink($data['message']); // row changed by Manu;
				//$data['message'] = wordwrap( $data['message'] , 19 ,"\n",true);
				$bulletin_board = $this->build_template($this->get_template("bulletin_board"),$data);
				return $bulletin_board;
			}
		}

		$bulletin_board = $this->build_template($this->get_template("bulletin_board"));
		return $bulletin_board;
	}

	/**
	 * Function to display reminder block in home page of patient.
	 *
	 * @param interger $patient_id
	 * @return string
	 * @access public
	 */
	function reminderComponent_old($patient_id)
	{

		$query = "select * from patient_reminder where patient_id = '{$patient_id}' and status = 1 ORDER BY patient_reminder_id DESC";
		$result = $this->execute_query($query);
		$totRecords = $this->num_rows($result);
		$lengthShortened = false;

		$reminderContent = '';

		$count = 1;

		$reminderComponent = "";

		$reminderContentLink = "<div style='padding-left:230px;' ><a href='javascript:void(0)'  id='allLink'  onClick='reminder({$patient_id});'>View all</a></div>";

		if ($totRecords !=0)
		{

			while (($currentReminder = $this->fetch_array($result)) && $count <=4)
			{

				if(strlen($currentReminder['reminder']) > 50)
				{
					$reminderContent .= '<li>'.$this->lengthtcorrect($this->decrypt_data($currentReminder['reminder']),50).'</li>';
					$lengthShortened = true;
				}
				elseif (strlen($currentReminder['reminder']) <= 50)
				{
					$reminderContent .= '<li>' . $this->decrypt_data($currentReminder['reminder']) .'</li>';
				}

				$count++;
				 
				 
			}


			if ($lengthShortened == true || $totRecords > 4)
			{
				//top5 or top reminders that fit in box depending on their length.
				$reminderComponent = '<div id="top5" style="width:300px;  vertical-align:top; "><ul style="margin-left:20px;">'.$reminderContent.'</ul>'.$reminderContentLink.'</div>';
			}
			else
			{
				$reminderComponent = '<div id="top5" style="width:300px;  vertical-align:top; "><ul style="margin-left:20px;">'.$reminderContent.'</ul></div>';
			}
		}
		else
		{
			$reminderComponent = '<div id="top5" style="width:300px;  vertical-align:top; "><ul style="margin-left:20px;"></ul></div>';
		}

		return $reminderComponent;
	}
	/**
	 * Function to display reminder block in home page of patient.
	 *
	 * @param interger $patient_id
	 * @return string
	 * @access public
	 */
	function reminderComponent($patient_id)
	{
		$reminderContent = '';

		$count = 1;

		$reminderComponent = "";
		$reminderComponent .= "<h6 class=\"smallH6\" style=\"letter-spacing:1px;\" ><div style=\"padding-top:5px;\"><!headingReminders></div></h6>";
		$reminderComponent .= '<table  cellpadding="1" cellspacing="1" border="0" width="100%" style="font-size:12px;" >';
		$query = "select * from patient_reminder where patient_id = '{$patient_id}' and status = 1 ORDER BY patient_reminder_id DESC";
		$result = $this->execute_query($query);
		$totRecords = $this->num_rows($result);
		$lengthShortened = false;



		$reminderContentLink = "<div style='padding-left:230px;' ><a href='javascript:void(0)'  id='allLink'  onClick='reminder({$patient_id});'>View all</a></div>";

		if ($totRecords !=0)
		{
			$i=1;
			while (($currentReminder = $this->fetch_array($result)) && $count <=4)
			{
				// $style = ($c++%2)?"line1":"line2";
				if(strlen($currentReminder['reminder']) > 50)
				{
					$reminderContent .= '<tr class="'.$style.'"><td style="padding-left:12px;height:25px;"  >'.$i.'. '.$this->lengthtcorrect($this->decrypt_data($currentReminder['reminder']),50).'</td></tr>';

					$lengthShortened = true;
				}
				elseif (strlen($currentReminder['reminder']) <= 50)
				{
					$reminderContent .= '<tr class="'.$style.'"><td style="padding-left:12px;height:25px;"  >' .$i.'. '.$this->decrypt_data($currentReminder['reminder']) .'</td></tr>';

				}

				$count++;
				$i++;
				 
			}


			if ($lengthShortened == true || $totRecords > 4)
			{
				//top5 or top reminders that fit in box depending on their length.
				$reminderComponent .= '<table  cellpadding="1" cellspacing="1" border="0" width="100%" style="font-size:12px;" >'.$reminderContent.'<tr><td><table align="right"><tr><td style="border-bottom: #CCC 0px solid; " >'.$reminderContentLink.'</td></tr></table></td></tr></table>';
			}
			else
			{
				$reminderComponent .= '<table  cellpadding="1" cellspacing="1" border="0" width="100%" style="font-size:12px;" >'.$reminderContent.'</table>';
			}
		}
		else
		{
			$reminderComponent .= '<table  cellpadding="1" cellspacing="1" border="0" width="100%" style="font-size:12px;" ><ul style="margin-left:20px;"></ul></table>';
		}

		return $reminderComponent;
	}

	/**
	 * Therapist Info Component
	 *
	 * @param interger $pid
	 * @return string
	 * @access public
	 */
	function therapistInfoComponent($pid)
	{
		$therapistInfoComponent = "<script type=\"text/javascript\" language=\"JavaScript\">
		<!--
		function showPTinfo(pid)
		{
		if(!pt_info_win)
		{
		var pt_info_win = window.open('index.php?action=ptInfo&tid='+pid,'ptInfoWin','width=550,height=280,resizable=no,scrollbars=no');
	}
	pt_info_win.focus();
	}
	//-->
	</script>";

		$privateKey = $this->config['private_key'];
		$query = "select u.user_id,
		AES_DECRYPT(UNHEX(u.name_title),'{$privateKey}') as name_title,
		AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}') as name_first,
		AES_DECRYPT(UNHEX(u.name_last),'{$privateKey}') as name_last,
		u.name_suffix  from therapist_patient tp
		inner join user u on u.user_id = tp.therapist_id
		where tp.patient_id = '{$pid}' AND u.status = 1 ";
		$therapistInfoComponent .= "\n\n" . '<!-- start PT info -->'.
				'<h3 style="padding-left: 5px; width: 235px; position: absolute; margin-top: 10px;">Therapist  Information</h3>'.
				'<span style="font-size: 9px; position: relative; top: 35px; left: 5px;">';

		$res = $this->execute_query($query);

		$totRecords = $this->num_rows($res);

		if($res !== false && $totRecords)
		{
			/*
			 if theres a single therapist info then all the details of therapist are displayed on the page
			but if there are more than one therapist then we display there short info as link
			and then on click of link we have a popup displaying all the info

			But we will always displays therapist info as links no matter its one or more than one

			*/

			$count = 0;

			while(($r = $this->fetch_array($res)) && $count < 4)
			{

				// list PT names linked to details
				$therapistInfoComponent .= '<a href="javascript:void(0)" onMouseOver="help_text(this, \'Click here to view the therapist contact information\')" onClick="showPTinfo('.$r['user_id'].')"><strong>';

				if(!empty($r['name_title']))
				{
					$therapistInfoComponent .= $r['name_title'] . ' ';
				}

				$therapistInfoComponent .= $r['name_first'] . ' ' .$r['name_last'];

				if(!empty($r['name_suffix']))
				{
					$therapistInfoComponent .= ', ' . $r['name_suffix'];
				}

				$therapistInfoComponent .= '</strong> Contact Info</a><br/>';

				++$count;

			}

			if ($totRecords > 4)
			{
				$therapistInfoComponent .= '<span style="padding-left:150px; "><a href="javascript:void(0)"  id="allLink" onMouseOver="help_text(this, \'Click here to view the complete listing of therapists\')" onClick="window.open(\'index.php?action=therapistListPopup&patient_id='.$pid.'\',\'TherapistListWin\',\'width=550,height=280,resizable=no,scrollbars=yes\');">View all</a></span>';
				$therapistInfoComponent .= '</span>'."\n<!-- end PT info -->\n\n";

			}
			else
			{
				$therapistInfoComponent .= '</span>'."\n<!-- end PT info -->\n\n";
			}


		}
		else
		{
			$therapistInfoComponent .= 'No Associated Therapists found.';
			$therapistInfoComponent .= '</span>'."\n<!-- end PT info -->\n\n";
		}


		return $therapistInfoComponent;


	}


	/**
	 * populating Current TxPlans Component on patient home page.
	 *
	 * @param integer $id
	 * @return string
	 * @access public
	 */
	function currTxPlanComponent($id)
	{
		$currTxPlanComponent = "<script language=\"JavaScript\" type=\"text/javascript\">
		<!--
		function showPlan(id)
		{
		if(! g_plan_win) var g_plan_win = window.open('index.php?action=planViewer&id='+id, 'g_plan_win', 'width=969,height=688,resizable=1,scrollbars=auto');
		g_plan_win.focus();
	}
	//-->
	</script>";

		$currTxPlanComponent .= "<h6 class=\"smallH6\" style=\"letter-spacing:1px;\" ><div style=\"padding-top:5px;\">MY <!headingVideoPlans></div></h6>";

		$query = " select * from plan where patient_id = '{$id}' and status = 1 ORDER BY plan_id DESC";
		$res = $this->execute_query($query);

		if($res !== false)
		{
			if($this->num_rows($res))
			{
				$currTxPlanComponent .= '<table  cellpadding="1" cellspacing="1" border="0" width="100%" style="font-size:12px;"  id="paginate1" class="rowstyle-alt max-pages-5 paginate-5">';
				while($r = $this->fetch_array($res))
				{
					//$style = ($c++%2)?"line1":"line2";
					$currTxPlanComponent .= '<tr class="'.$style.'"><td style="padding-left:12px;height:25px;"><a href="index.php?action=view_plan&plan_id='.$r[plan_id].'" onMouseOver="help_text(this, \'Displays the current plans\')" onClick="showPlan(' . $r['plan_id'] . ')">' .$this->unread_plan($r[plan_id],$r['plan_name']).'</a></td>';
					//'subject' => "<a href='index.php?action=patient_set_unread_message&message_id={$row['message_id']}'>".$this->unread_message($row['message_id'],$this->decrypt_data($row['subject']))."</a>"
					$currTxPlanComponent .= '</tr>';
				}

				$currTxPlanComponent .= '</table> ';
			}
			else
			{
				// no assigned plan
				$currTxPlanComponent .= '<span style="padding-left:12px;font-size:13px;"></span>';
			}
		}
		else
		{
			// failed getting plans
		}

		return $currTxPlanComponent;


	}

	/**
	 * Changes the status of message to read in database.
	 * @param none
	 * @return none
	 * @access public
	 */
	function view_plan(){
		if( $this->value('plan_id') != "" ){
			$data = array(
					'unread_plan' => 2
			);

			$this->update("plan",$data, " plan_id = '{$this->value('plan_id')}' and patient_id = '{$this->userInfo('user_id')}' ");
		}
		header("location:index.php?action=patient");
		exit();
	}



	/**
	 * populating message component on patient home page.
	 *
	 * @return string
	 * @access public
	 */
	function recentMessageComponent()
	{
		//$replace['recent_message'] .= "<h1 class=\"smallH1\" >Recent Messages</h1>";
		$replace['recent_message'] .= "<h6 class=\"smallH6\" style=\"letter-spacing:1px;\" ><div style=\"float:left;width:250px;padding-top:5px;\"><!headingMessages></div><div style=\"float:right; width:200px; padding-top:3px; color:#0653a3; text-decoration:none;valign:top;\"><a href=\"index.php?action=patient_compose_message\" ><img src=\"images/compose_03.gif\" alt=\"Compose New Message\" title=\"Compose New Message\"  align=\"absmiddle\"   /></a>&nbsp; <a href=\"index.php?action=patient_message_listing&sort=sent_date&order=desc\" ><img src=\"images/view_all_03.gif\" alt=\"View All Messages\" title=\"View All Messages\" align=\"absmiddle\"/></a></div><div style=\"clear:both;\"></div></h6>";
		$privateKey = $this->config['private_key'];
		$query = "SELECT concat(AES_DECRYPT(UNHEX(name_first),'{$privateKey}'),' ',AES_DECRYPT(UNHEX(name_last),'{$privateKey}')) as from_name,m.subject,(

		SELECT max( m1.sent_date )
		FROM message m1
		WHERE (
		m1.parent_id = m.message_id
		OR m1.message_id = m.message_id
		)
		) AS sent_date,m.patient_id,m.message_id,
		(
		select count(*) from message m1
		where  m1.parent_id = m.message_id
		)
		as replies
		FROM message m
		inner join user u on u.user_id = m.sender_id
		WHERE m.patient_id = '{$this->userInfo('user_id')}' and m.parent_id = 0  and m.sent_visible='1' and m.mass_status!=1
		and  m.message_id not in (select message_id from system_message where user_id = '{$this->userInfo('user_id')}')
		order by sent_date desc limit 0,5";

		$res = $this->execute_query($query);

		if($res !== false)
		{
			if($this->num_rows($res))
			{

				$replace['recent_message'] .= '<table onMouseOver="help_text(this, \'Displays the recent message list. Click on the subject to view the full message\')" width="100%" cellpadding="3" cellspacing="4" border="0" style="font-size:12px;" >';
				while($row = $this->fetch_array($res))
				{
					$data = array(
							//'style' => ($c++%2)?"line1":"line2",
							'from_name' => "<a href='index.php?action=patient_set_unread_message&message_id={$row['message_id']}'>".$this->unread_message($row['message_id'],$row['from_name'])."</a>",
							'subject' => "<a href='index.php?action=patient_set_unread_message&message_id={$row['message_id']}'>".$this->unread_message($row['message_id'],$this->decrypt_data($row['subject']))."</a>",
							'sent_date' => "<a href='index.php?action=patient_set_unread_message&message_id={$row['message_id']}'>".$this->unread_message($row['message_id'],$this->formatDateExtra($row['sent_date'], $this->userInfo('timezone')))."</a>",
							'replies' => $row['replies']
							);
					$replace['recent_message'] .= $this->build_template($this->get_template("recent_message"),$data);
				}

				//$replace['recent_message'] .= '<tr height="0px" ></tr></table>';
				$replace['recent_message'] .= '</table>';
			}
			else
			{
				// no messages
				$replace['recent_message'] .= '<span style="padding-left:12px;font-size:13px;">No Messages</span>';
			}
		}
		else
		{
			// failed getting plans
		}

		return $replace['recent_message'];


	}

	function unread_article(){
		$aid=$this->value('aid');
		$plan_id=$this->value('plan_id');
		if($plan_id==0){
			$sql="select * from patient_article where article_id= '{$aid}' and patient_id = '{$this->userInfo('user_id')}' ";
			$res=$this->execute_query($sql);
			//echo $this->num_rows($res);
			if($this->num_rows($res)>0)
			{
				$data = array(
						'read_article' => 1
				);
				$this->update("patient_article",$data, " article_id = '{$this->value('aid')}' and patient_id = '{$this->userInfo('user_id')}' ");
				 
			}
		}else{
			$data = array(
					'read_article' => 1
			);
			$this->update("plan_article",$data, " article_id = '{$this->value('aid')}'  and plan_id 	 = '{$plan_id}'");
		}
		echo 'done';
	}

	/**
	 * populating Article Library Component on patient home poage
	 *
	 * @param integer $id
	 * @return string
	 * @access public
	 */
	function articleLibComponent($id)
	{

		$articleLibComponent = "<script language=\"JavaScript\" type=\"text/javascript\">
		<!--
		function showArticle(id,pid)
		{

		 
		if(!csw) var csw = window.open('index.php?action=articlePreview&aid='+id, 'catSelectWindow', 'width=800, height=480, status=no, toolbar=no, resizable=1,scrollbars=yes');
		csw.focus();
		$.post('index.php?action=unread_article', { aid: id,plan_id:pid },
		function(data) { a='#a'+id; $(a).css('font-weight','normal');});
			
	}
	//-->
	</script>";

		$articleLibComponent .= "<h6 class=\"smallH6\" style=\"letter-spacing:1px;\" ><div style=\"padding-top:5px;\"><!headingSuggestedReading></div></h6>";

		/*$query = "select * from plan p
		 inner join plan_article pa on pa.plan_id = p.plan_id and p.status = 1
		inner join article a on a.article_id = pa.article_id and a.status = 1
		where p.patient_id = '{$id}' ";*/
		$userInfo = $this->userInfo();
		$userId = $userInfo['user_id'];
		$clinicId    =    $this->get_clinic_info($userId,"clinic_id");
		$therapistIds = $this->getporviderlist($clinicId);
		$query="(SELECT AR.article_id as articleID, NULL as plan_id,AR.article_name as article_name ,AR.headline as artcleHeadline,PAA.patient_id as patient_id,PAA.patientArticleId as patientArticleId, PAA.read_article as read_article
		FROM article AR
		LEFT JOIN patient_article PAA ON PAA.article_id = AR.article_id
		WHERE PAA.patient_id = {$id} AND AR.status='1' order by PAA.article_id DESC) UNION all
		(SELECT RS.article_id as articleID,P.plan_id as plan_id,RS.article_name as article_name,RS.headline as artcleHeadline,P.patient_id as patient_id, NULL as patientArticleId, PA.read_article as read_article
		from article RS
		LEFT JOIN plan_article PA ON RS.article_id = PA.article_id
		LEFT JOIN plan P ON P.plan_id = PA.plan_id AND P.status = '1'
		WHERE (P.patient_id ={$id})  AND RS.status='1' order by PA.article_id DESC) ORDER BY patientArticleId DESC";
		$shown_ids = array();

		$res = $this->execute_query($query);

		if($res !== false)
		{
			if($this->num_rows($res))
			{
				$articleLibComponent .= '<table cellpadding="1" cellspacing="1" border="0" width="100%" style="font-size:12px;"  id="paginate2" class="rowstyle-alt paginate-5 max-pages-5">';
				while($r = $this->fetch_array($res))
				{
					//$style = ($c++%2)?"line1":"line2";
					//if()
					//{
						if($r['plan_id']=='')
						$r['plan_id']=0;

					if($r['read_article']==2)
						$style='font-weight:bold';
					else
						$style='font-weight:normal';
					$articlename=$r['article_name'];
					$aid=$r['articleID'];
					$articleLibComponent .= '<tr class="'.$style.'"><td style="padding-left:12px;height:25px;" ><a id="a'.$aid.'" href="javascript:void(0)" style="'.$style.'"  onClick="showArticle(' . $r['articleID'].','.$r['plan_id'] . ')" style="">' .wordwrap($articlename, 50,"<br/>",TRUE).'</a></td></tr>';
					//    $shown_ids[] = $r['article_id'];
					//    }
				}
				$articleLibComponent .= '</table>';
		}
		else
		{
			// no assigned plan
			$articleLibComponent .= '<span style="padding-left:12px;font-size:13px;" >No Articles Assigned</span>';
		}
	}
	else
	{
		// failed getting plans
	}

	return $articleLibComponent;


}



/**
 * populating Plan History Component on patient home page.
 *
 * @param integer $id
 * @return string
 * @access public
 */
function planHistoryComponent($id)
{
	$planHistoryComponent = "<script language=\"JavaScript\" type=\"text/javascript\">
	<!--
	function showPlan(id)
	{
	if(! g_plan_win) var g_plan_win = window.open('index.php?action=planViewer&id='+id, 'g_plan_win', 'width=1024,height=768,scrollbars=auto');
	g_plan_win.focus();
}
//-->
</script>";
	$planHistoryComponent .= "<h6 class=\"smallH6\">PLAN HISTORY</h6>";


	$query = " select * from plan where patient_id = '{$id}' and status = 2 ";
	$res = $this->execute_query($query);

	if($res !== false)
	{
		if($this->num_rows($res))
		{
			$planHistoryComponent .= '<table cellpadding="1" cellspacing="1" border="0">';
			while($r = $this->fetch_array($res))
			{
				$planHistoryComponent .= '<tr><td><a href="javascript:void(0)" onMouseOver="help_text(this, \'Displays the plans which are archived by the therapist\')" onClick="showPlan(' . $r['plan_id'] . ')">' .$r['plan_name'].'</a></td>';
				$planHistoryComponent .= '</tr>';
			}
			$planHistoryComponent .= '</table>';
		}
		else
		{
			// no assigned plan
			$planHistoryComponent .= '<h4>No Plans in History</h4>';
		}
	}
	else
	{
		// failed getting plans
	}

	return $planHistoryComponent;

}


    /**
     * Change current password , Patient Unsubscribe, Billing Details
     *
     * @access public
     */
    function changePass()
    {
        $userInfo = $this->userInfo();

        $fileDest = "asset/images/profilepictures/".$userInfo['user_id'];

        if(!file_exists($fileDest))
        {
            mkdir($fileDest);

            chmod($fileDest, 0777);
        }

        $dir = "asset/images/profilepictures/".$userInfo['user_id'];

        $contents = $this->ReadFolderDirectory($dir);
            //print_r($contents);
        if($contents[2]!='')
        {
            $replace['propic'] = "$dir/$contents[2]";
        }
        else
        {
            $replace['propic'] = 'images/no-image.gif';
        }

        $clinic_id = $this->clinicInfo('clinic_id');

        if (!$userInfo)
        {
            header("location:index.php");
        }
        else
        {
            $userId = $userInfo['user_id'];
            $mass_message_access = $userInfo['mass_message_access'];

            $selectedtimezone = ($userInfo['timezone'] == NULL) ? $this->config['timezone']['frontend']['region'] : $userInfo['timezone'];
            $replace['timezoneOptions'] = $this->build_select_option($this->getAllTimezones(), $selectedtimezone);

            //$showForm = 1;

            if (isset($_POST['change_action']) && $_POST['change_action'] == 'ChangePassword')
            {
                //check for typed values validations that is the fields are not empty
                $errorMsg = "";
                $error = false;

                if(trim($this->value('old_password')) == "" && $error == false)
                {
                    $errorMsg = "Please enter the old password";
                    $error = true;
                }

                if($error == false && trim($this->value('new_password')) == "")
                {
                    $errorMsg = "Please enter the new password";
                    $error = true;
                }

                if($error == false && trim($this->value('new_password2')) == "")
                {
                    $errorMsg = "Please re-enter the new password";
                    $error = true;
                }

                if($error == false && strcmp($this->value('new_password'),$this->value('new_password2')) != 0)
                {
                    $errorMsg = "Please make sure that new password matches with confirm password";
                    $error = true;
                }

                // check that the old password matches with the database
                if($error == false && strcmp($this->value('old_password'),$userInfo['password']) != 0)
                {
                    $errorMsg = "Incorrect old password";
                    $error = true;
                }

                if ($error == false)
                {
                    //update and show success msg page
                    $updateArr = array(
                        'password'=>$this->value('new_password')
                    );

                    $where = " user_id = ".$userId;

                    $result = $this->update('user',$updateArr,$where);

                    $errorMsg = "Your password has been changed successfully.";
                    $_SESSION['password'] = $this->value('new_password');
                    //$showForm = 0;
                }
                else
                {
                    //$showForm = 1;
                }
            }
            else
            {
                //$showForm = 1;
            }

            $replace['error'] = $errorMsg;
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['footer'] = $this->build_template($this->get_template("footer"));
            $replace['browser_title'] = "Tx Xchange: Home";
            $replace['userName'] = $userInfo['name_first'];
            $replace['user_id']    =  $userId;
            
            if($mass_message_access==1 || $mass_message_access == NULL)
                $replace['mass_message_access']    =  " checked";
            else
                $replace['mass_message_access']    =  " ";
            $replace['sidebar'] = $this->sidebar();

            // Section to get Patient subscription information.

            $resultQuerySubscription = $this->getPatientSubscriptionDetails($userInfo['user_id'], $clinic_id);
            // Check If Patient Is under subscription.
            /*echo '<pre>';
            print_r($resultQuerySubscription);*/

            if(!empty($resultQuerySubscription))
            {
                $replace_unsubscribe_button_template['subscribe_id']=$resultQuerySubscription["user_subs_id"];
                $replace_unsubscribe_button_template['ServiceName']='Your current '.html_entity_decode($resultQuerySubscription["subscription_title"],ENT_QUOTES,'UTF-8').' billing period is';
                $ServiceNameTitle=html_entity_decode($resultQuerySubscription["subscription_title"],ENT_QUOTES,'UTF-8');
                $replace_unsubscribe_button_template['ServiceNameTitle']=str_replace('"', "'",$ServiceNameTitle);
                $replace_unsubscribe_button_template['subsStartDate']=$resultQuerySubscription["subsStartDate"];
                $replace_unsubscribe_button_template['subsEndDate']=$resultQuerySubscription["subsEndDate"].'.';
                $replace_unsubscribe_button_template['checkBoxCheck']='checked="checked"';
                $replace_unsubscribe_button_template['visibilityButton']='style="display:block;"';
                $replace_unsubscribe_button_template['trdisplay']='<tr><td colspan="2">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr> ';

                if($resultQuerySubscription["subs_status"]=="2")
                {
                    $replace_unsubscribe_button_template['checkBoxCheck']='checked="checked"';
                    $replace_unsubscribe_button_template['visibilityButton']='style="display:none;"';
                }

                if($resultQuerySubscription["paymentType"]=="1")
                {
                    $replace_unsubscribe_button_template['checkBoxCheck']='style="display:none;"';
                    $replace_unsubscribe_button_template['visibilityButton']='style="display:none;"';
                    $replace_unsubscribe_button_template['ServiceName']='Your current '.html_entity_decode($resultQuerySubscription["subscription_title"],ENT_QUOTES,'UTF-8').' period is';
                    $replace_unsubscribe_button_template['trdisplay']='';
                }

                $replace['unsubscribeButtonTemplate'] = $this->build_template($this->get_template("unsubscribe_button_template"),$replace_unsubscribe_button_template);
            }
            else
            {
                // Pateint has unsubscribed, so remove the unsubscription button
                $replace['unsubscribeButtonTemplate']="";
            }
            //$resultQuerySubscription=$this->getPatientSubscriptionDetails($userInfo['user_id'],$clinic_id);
            if(!empty($resultQuerySubscription) and $resultQuerySubscription['paymentType'] =='0' and $resultQuerySubscription['subs_status'] =='1') 
            {
                $errorCode=$this->value('errorCode');
                if($this->value('sucess')==1)
                {
                    {
                        $customerrormessage="Your credit card information has been changed successfully.";
                    }
                    $replace['errorMessage']='<tr><td colspan="4" style="height:20px; color:green; font-weight:bold;padding-bottom:15px;"><font style="color:green; font-weight:bold;">'.$customerrormessage.'</font></td></tr>';

                }
                if($errorCode!='')
                {
                    $customerrormessage=$this->PaypalErroCodeslist($errorCode);
                    if($customerrormessage=='')
                    {
                        $customerrormessage="Invalid Credit Card Details";
                    }
                    $replace['errorMessage']='<tr><td colspan="4" style="height:20px; color:#0069a0; font-weight:bold"><font style="color:#FF0000;font-weight:normal;">'.$customerrormessage.'</font></td></tr>';

                }

                if($this->value('validData')=='true')
                {
                    if(urldecode($this->value('namefValid'))!='')
                    {
                        $replace['namefValid']='<font style="color:#FF0000;font-weight:normal;">'.urldecode($this->value('namefValid')).'</font>';
                        $replace['retainedFname']='';
                    }

                    $replace['retainedFname']=$_SESSION['pateintSubs']['fname'] ;

                    if(urldecode($this->value('namelValid'))!='')
                    {
                        $replace['namelValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('namelValid').'</font>';

                        $replace['retainedlname']='' ;
                    }


                    $replace['retainedlname']=$_SESSION['pateintSubs']['lname'] ;


                    if(urldecode($this->value('cnumberValid'))!='')
                    {
                        $replace['cnumberValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('cnumberValid').'</font>';
                        $replace['retainedcardNumber'] = '';
                    }
                    $replace['retainedcardNumber'] = $_SESSION['pateintSubs']['cardNumber'];

                    if(urldecode($this->value('cexpValid'))!='')
                        $replace['cexpValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('cexpValid').'</font>';
                    if(urldecode($this->value('ccvvValid'))!='')
                    {
                        $replace['ccvvValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('ccvvValid').'</font>';
                        $replace['retainedcvvNumber'] = '';
                    }
                    $replace['retainedcvvNumber'] = $_SESSION['pateintSubs']['cvvNumber'];
                    if(urldecode($this->value('ctypeValid'))!='')
                    {
                        $replace['ctypeValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('ctypeValid').'</font>';
                        $replace['retainVisaType'] = '';
                        $replace['retainMasterType'] = '';

                    }

                    if($_SESSION['pateintSubs']['cardType'] == 'Visa')
                    {
                        $replace['retainVisaType'] = 'selected="selected"';
                        $replace['retainMasterType'] = '';
                    }
                    elseif($_SESSION['pateintSubs']['cardType'] == 'MasterCard')
                    {
                        $replace['retainMasterType'] = 'selected="selected"';
                        $replace['retainVisaType'] = '';
                    }
                    else
                    {
                        $replace['retainVisaType'] = '';
                        $replace['retainMasterType'] = '';
                    }

                    if(urldecode($this->value('address1Valid'))!='')
                    {
                        $replace['address1Valid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('address1Valid').'</font>';
                        $replace['retainedadd1']='' ;
                    }

                    $replace['retainedadd1']=$_SESSION['pateintSubs']['add1'] ;
                    if(urldecode($this->value('cityValid'))!='')
                    {
                        $replace['cityValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('cityValid').'</font>';
                        $replace['retainedcity']='';
                    }

                    $replace['retainedcity']=$_SESSION['pateintSubs']['city'] ;

                    if(urldecode($this->value('stateValid'))!='')
                    {
                        $replace['stateValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('stateValid').'</font>';
                        $replace['retainedstate']='';
                    }

                    $replace['retainedstate']=$_SESSION['pateintSubs']['state'] ;

                    if(urldecode($this->value('zipcodeValid'))!='')
                    {
                        $replace['zipcodeValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('zipcodeValid').'</font>';
                        $replace['retainedzip']='' ;
                    }


                    $replace['retainedzip']=$_SESSION['pateintSubs']['zip'] ;

                    if(urldecode($this->value('address2'))=='')
                        $replace['retainedadd2']=$_SESSION['pateintSubs']['add2'] ;
                }
                else
                {
                    $replace['namefValid']='';
                    $replace['retainedFname']='';
                    $replace['namelValid']='';
                    $replace['retainedlname']='' ;
                    $replace['cnumberValid']='';
                    $replace['cexpValid']='';
                    $replace['ccvvValid']='';
                    $replace['ctypeValid']='';
                    $replace['address1Valid']='';
                    $replace['retainedadd1']='' ;
                    $replace['retainedadd2']='' ;
                    $replace['cityValid']='';
                    $replace['retainedcity']='' ;
                    $replace['stateValid']='';
                    $replace['retainedstate']='' ;
                    $replace['zipcodeValid']='';
                    $replace['retainedzip']='' ;
                    $replace['RetaincountryUS']='' ;
                    $replace['RetaincountryCAN']='' ;
                    $replace['retainedcardNumber']='';
                    $replace['retainVisaType']='';
                    $replace['retainMasterType']='';
                    $replace['retainedcvvNumber']='';

                }
                $userInfo = $this->userInfo();
                $clinicInfo=$this->clinicInfo('clinic_id');
                require_once("include/class.paypal.php");
                if($resultQuerySubscription['payment_paypal_profile']!='')
                {
                    $API_UserName = urlencode($this->config["paypalprodetails"]["API_UserName"]);
                    $API_Password = urlencode($this->config["paypalprodetails"]["API_Password"]);
                    $API_Signature = urlencode($this->config["paypalprodetails"]["API_Signature"]);
                    $environment = urlencode($this->config["paypalprodetails"]["environment"]);
                    $currencyID = urlencode($this->config["paypalprodetails"]["currencyID"]);
                    
                    // Paypal Object
                    $paypalObjectCancel=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
                    $profileID=urlencode($Paypal_Profile);
                    $profileID=$resultQuerySubscription['payment_paypal_profile'];
                    $action=urlencode("Cancel");
                    $nvpStr="&PROFILEID=$profileID";
                    $paypalObjectCancel=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
                    $httpParsedResponseAr = $paypalObjectCancel->PPHttpPost('GetRecurringPaymentsProfileDetails', $nvpStr);
                    $replace['ccno']=$httpParsedResponseAr['ACCT'];
                    $replace['profileId']=$resultQuerySubscription['payment_paypal_profile'];
                    $replace['user_subs_id']=$resultQuerySubscription['user_subs_id'];
                    
                    if($this->value('validData')=='')
                    {
                        $replace['retainedadd1']=urldecode($httpParsedResponseAr['STREET']);
                        $replace['retainedadd2']=urldecode($httpParsedResponseAr['STREET2']);
                        $replace['retainedcity']=urldecode($httpParsedResponseAr['CITY']);
                        $replace['retainedzip']=urldecode($httpParsedResponseAr['ZIP']);
                        /*$replace['RetaincountryCAN']='';
                        $replace['RetaincountryUS']='';
                        //checks if user country is US in session
                                            $stateArray=array();
                                            $stateCanadaArray=array();
                                            if($httpParsedResponseAr['COUNTRYCODE']=='US'){
                                            $stateArray = array("" => "Choose state...");
                                            $stateArray = array_merge($stateArray,$this->config['state']);
                                            $replace['RetaincountryUS']='selected';
                                            $replace['RetaincountryCAN']='';
                                            $replace['patient_state_options'] = $this->build_select_option($stateArray, $httpParsedResponseAr['STATE']);
                                            //checks if user country is Canada in session
                                            }elseif($httpParsedResponseAr['COUNTRYCODE']=='CA'){

                                            $stateCanadaArray = array("" => "Choose Province...");
                                            $stateCanadaArray = array_merge($stateCanadaArray,$this->config['canada_state']);
                                            $replace['RetaincountryUS']='';
                                            $replace['RetaincountryCAN']='selected';
                                            $replace['stateOptions'] = $this->build_select_option($stateCanadaArray, $httpParsedResponseAr['STATE']);

                                            }else{
                                            $replace['patient_state_options'] = $this->build_select_option($stateArray, $replace['patient_state']);
                                            }*/
                        $countryarray=$this->country_list();
                        $replace['country']=$this->build_select_option($countryarray, $httpParsedResponseAr['COUNTRYCODE']);
                        $stateArray=$this->state_list($httpParsedResponseAr['COUNTRYCODE']);
                        $replace['stateOptions'] = $this->build_select_option($stateArray, urldecode($httpParsedResponseAr['STATE']));
                    }

                }
                $monthArray = array();
                $monthArray = $this->config['monthsArray'];
                $replace['monthsOptions'] = $this->build_select_option($monthArray, $_SESSION['pateintSubs']['exprMonth']);

                $yearArray = array();
                $yearArray = $this->config['yearArray'];
                $replace['yearOptions'] = $this->build_select_option($yearArray, $_SESSION['pateintSubs']['exprYear']);
                $replace['updatecraditcardinformation'] = $this->build_template($this->get_template("update_paypal_profile"),$replace);
                //replace the states array for US and CANADA
                /*$stateArray = array("" => "Choose state...");

                $stateArray = array_merge($stateArray,$this->config['state']);

                foreach($stateArray as $key=>$value){
                $stateJavascript.="'{$key}':'{$value}',";
                        }
                        $stateJavascript=trim($stateJavascript,',');


                        //$replace['state']=implode("','",$stateArray);
                        $replace['state']=$stateJavascript;


                        $stateCanadaArray = array("" => "Choose Province...");
                        $stateCanadaArray = array_merge($stateCanadaArray,$this->config['canada_state']);

                        foreach($stateCanadaArray as $key=>$value){
                        $canadastateJavascript.="'{$key}':'{$value}',";
                        }
                        $canadastateJavascript=trim($canadastateJavascript,',');

                        //$replace['canada_state']=implode("','",$stateCanadaArray);
                        $replace['canada_state']=$canadastateJavascript;
                        */
                $monthArray = array();
                $monthArray = $this->config['monthsArray'];
                $replace['monthsOptions'] = $this->build_select_option($monthArray, $_SESSION['pateintSubs']['exprMonth']);

                $yearArray = array();
                $yearArray = $this->config['yearArray'];
                $replace['yearOptions'] = $this->build_select_option($yearArray, $_SESSION['pateintSubs']['exprYear']);

                /*$replace['RetaincountryCAN']='';
                $replace['RetaincountryUS']='';
                //checks if user country is US in session
                if($_SESSION['pateintSubs']['country']=='US'){
                $replace['RetaincountryUS']='selected';
                        $replace['RetaincountryCAN']='';
                        $replace['patient_state_options'] = $this->build_select_option($stateArray, $_SESSION['pateintSubs']['state']);
                        //checks if user country is Canada in session
                        }elseif($_SESSION['pateintSubs']['country']=='CA'){
                        $replace['RetaincountryUS']='';
                        $replace['RetaincountryCAN']='selected';
                        $replace['stateOptions'] = $this->build_select_option($stateCanadaArray, $_SESSION['pateintSubs']['state']);

                        }*//*else{
                        $replace['patient_state_options'] = $this->build_select_option($stateArray, $replace['patient_state']);
                        }*/
                $countryarray=$this->country_list();
                $replace['country']=$this->build_select_option($countryarray, $_SESSION['pateintSubs']['country']);
                $stateArray=$this->state_list($_SESSION['pateintSubs']['country']);
                $replace['stateOptions'] = $this->build_select_option($stateArray, $_SESSION['pateintSubs']['state']);
                unset($_SESSION['pateintSubs']);
            }
            else
            {
                unset($_SESSION['pateintSubs']);
                $replace['updatecraditcardinformation'] = '';
            }

            $replace['intakeform'] ='';
            //echo $_SESSION['intakepaper'];
            //if($urlStr=="")
            {
                //if($_SESSION['intakepaper']!=='assign')
                {
                    $sqlintake="select * from patient_intake where p_user_id= ".$this->userInfo('user_id');
                    $resultintake=$this->execute_query($sqlintake);
                    if($this->num_rows($resultintake)>0)
                    {
                        $rowintake=$this->fetch_array($resultintake);
                        if($rowintake['intake_compl_status']==0)
                        {
                            if($rowintake['intake_last_page']=='')
                                $pagenumber=0;
                            else
                                $pagenumber=$rowintake['intake_last_page'];
                            $replace['intakeform'] ="<script>GB_showCenter('Intake Paperwork', '/index.php?action=fillintakepaperwork',720,960);</script>";
                            $_SESSION['intakepaper']='assign';
                        }
                    }
                    else
                    {
                        $_SESSION['intakepaper']='assign';
                    }
                }
            }
            
            if(isset($_SESSION['timezonechanged']) && !empty($_SESSION['timezonechanged']))
            {
                $replace['error'] = "Your time zone has been changed successfully";
                unset($_SESSION['timezonechanged']);
            }

            $replace['body'] = $this->build_template($this->get_template("changePass"), $replace);
            $this->output = $this->build_template($this->get_template("main"), $replace);
        }
    }
    
    /**
     * change timezone setting for current logged in user
     */
    function changetimezone()
    {
        //change timezone
        if($this->value('timezone') != "")
        {
            $data = array();
            $data = array(
                'modified' => date('Y-m-d H:i:s', time()),
                'timezone' => $this->value('timezone')
            );
            
            $where = " user_id = '" . $this->userInfo('user_id') . "'";
            $result = $this->update('user', $data, $where);
            
            if($result)
            {
                $_SESSION['timezonechanged'] = '1';
            }
            header("location:index.php?action=changePass");
        }
    }

/**
 * This function subscribes / unsubscribes patient from mass messaging.
 * @access public
 */
function patient_mass_subscribe(){//echo "<pre>";print_r($_REQUEST);EXIT;
	$userInfo = $this->userInfo();
	$userId = $userInfo['user_id'];
	$clinicName    =    $this->get_clinic_info($userId,"clinic_name");
	if($this->value('confirm') == "yes" ){
		if(is_numeric($this->value('mass_message_access'))){
			$query = "UPDATE user SET  mass_message_access=".$this->value('mass_message_access')." where           user_id = ".$userId;
			$result = @mysql_query($query);
		}
		$this->output = "<script language='javascript'>
		parent.parent.location.reload();
		//parent.parent.GB_hide();
		parent.parent.setTimeout('GB_CURRENT.hide()',1000);
		</script>";
		return;
	}

	if($userInfo['mass_message_access']==1)
		$replace['message'] = "By unsubscribing, you will not receive messages that contain market offers or business updates from ".$clinicName.". You will continue to receive messages from your provider that directly relate to the health, wellness, or fitness services you are receiving.<br /><br /><span style='padding-left:110px;'>Do you still want to unsubscribe?</span>";
	else
		$replace['message'] = "By subscribing, you will receive messages that contain market offers or business updates from ".$clinicName.". You will continue to receive messages from your provider that directly relate to the health, wellness, or fitness services you are receiving.<br /><br /><span style='padding-left:110px;'>Do you still want to subscribe?</span>";
	$replace['mass_message_access']=$_REQUEST['mass_message_access'];
	$replace['body'] = $this->build_template($this->get_template("massSubscribe"),$replace);
	$replace['browser_title'] = "Tx Xchange: Home";
	$this->output = $this->build_template($this->get_template("main"),$replace);
}

 
 


/**
 * Help file for Patient
 */
function patient_help(){
	$this->output = $this->build_template($this->get_template("patient_help"),$replace);
}
/**
 * Show thanks page.
 */
function thanks_page(){
	$replace['patient_id'] = $this->userInfo('user_id');
	$this->output = $this->build_template($this->get_template('thanks'),$replace);
}
/**
 * Show lbhra page.
 */
function lbhra(){
	$this->output = $this->build_template($this->get_template('lbhra'),$replace);
}
/**
 * Show employee thanks page.
 */
function employee_thanks_page(){
	$patient_id = $this->userInfo('user_id');
	if($this->value('submit') != "" && is_numeric($patient_id) ){
		$acn_patient_arr = array(
				'form_data'     =>  serialize($_REQUEST),
				'status'    =>  2
		);
		//$this->insert('lbhra',$acn_patient_arr);
		$where ="patient_id={$patient_id} and status =1";
		$this->update('lbhra',$acn_patient_arr,$where);
		$this->output = $this->build_template($this->get_template('employee_thanks_page'),$replace);
		return;
	}
	header("location:index.php?action=lbhra");
	exit();
}
function patient_video_conference(){
    
            /*
             * Applying the Code for OpenTok Against the Ticket TXM-07 Viedo conferencing on Date AUG-16-2013
             * By Rohit Mishra
             */
           //Comment Previoues code
            /*
             $replace['sname'] = $this->streamNamePatient();
              $this->output =  $this->build_template($this->get_template("video_conference_patient"),$replace);
            */
            
            // Opentok code Here
	if(isset($_REQUEST['step1'])&& !empty($_REQUEST['step1'])){
         
            $patainent_id = $this->userInfo('user_id');
            $provider_id = $_REQUEST['providerId'];
            $token='';
            $session_id='';
            
            //Checking if oprentak session and token exits or not if not create the new session and token other wise use old one
            $query = " select * from opentok_session where patainent_id='{$patainent_id}' AND provider_id='$provider_id' AND creation_date > DATE_SUB( NOW(), INTERVAL 24 HOUR)  ORDER BY id DESC";
		$result = $this->execute_query($query);
		if( $this->num_rows($result) > 0 ){
                    $row = $this->fetch_array($result);
		//$row['plan_id'];
                   
                    $token = $row['token'];
                    $session_id = $row['session_id'];
                   
		}
                else
                {
                   
                    $open_talkparam = $this->get_opentok_paramenter();
                           
                    
                    $opentok_session_arr = array('patainent_id' =>$patainent_id,
                                            'provider_id' =>$provider_id,
                                            'session_id' => $open_talkparam['sessionId'],
                                            'token' =>$open_talkparam['token'],
                                            'call_status'=>1        
                        
                        
                    );
                    $this->insert("opentok_session",$opentok_session_arr);
                    $token = $open_talkparam['token'];
                    $session_id = $open_talkparam['sessionId'];
                }
            
                  
                $replace['patainent_id']=$patainent_id;
                $replace['provider_id']=$provider_id;
                $replace['session_id']=$session_id;
                $replace['token']=$token;
                $replace['apiKey']=API_Config::API_KEY;
           
            
            
            $this->output =  $this->build_template($this->get_template("video_conference_patient1"),$replace); 
            
        }else{
        $provoderlist=$this->get_paitent_providerfor_opentok( $this->userInfo('user_id'));
        
      
        for($i=0;$i<count($provoderlist);$i++){
            
            $call_status = $this->check_call_status($this->userInfo('user_id'),$provoderlist[$i]['id']);
            if($call_status=='1'){
              
              $provider_html.='<li><input type="radio" name="providerId" value="'.$provoderlist[$i]['id'].'" disabled>'.ucfirst($provoderlist[$i]['name']).' <span style="color:red">[Provider is Busy With Another Patient.]</span></li>';  
            }else{
                
                
         $provider_html.='<li><input type="radio" name="providerId" value="'.$provoderlist[$i]['id'].'">'.ucfirst($provoderlist[$i]['name']).'</li>';
            }
        }
      
       $replace['providerlist'] = $provider_html;
        $this->output =  $this->build_template($this->get_template("video_conference_patient"),$replace);
        }
        
    
	//$this->output =  $this->build_template($this->get_template("video_conference_patient"),$replace);
}

function chage_call_status(){
    
    $provider_id = $_REQUEST['provider_id'];
    $patainent_id = $_REQUEST['patainent_id'];
    $call_staus = $_REQUEST['call_staus'];
    
    $query ="UPDATE  opentok_session SET call_status='$call_staus' where patainent_id='$patainent_id' AND provider_id='$provider_id'  AND creation_date >= DATE_SUB( NOW(), INTERVAL 24 HOUR)  ";
    $result = @mysql_query($query);
    
}
/**
 * Patient Contact us
 */
function patient_contact_us(){
	$this->output = $this->build_template($this->get_template("contact_us"));
}
/**
 * populating side panel on the page
 *
 * @return string
 * @access public
 */
function sidebar(){
		$userInfo = $this->userInfo();
	$data = array(
			// Personalized GUI
			'bulletin_board' => $this->bulletin_message(),
			'referral_link' => $this->referral_link(),
			'scheduling'=>$this->scheduling($this->clinicInfo('clinic_id')),
			'userName'=> $userInfo['name_first'],
                        'message'=>'<li >
        <a href="index.php?action=patient_message_listing&sort=sent_date&order=desc" >
        <span style="font-size:14px;">'.$_SESSION['patientLabel']['Messages'].'('.$this->num_messages().')</span>
        </a>
      </li>',
                        'teleconsultation'=>'<li >
        <a href="javascript:void(0);" onclick="mypopup();" >
        <span style="font-size:14px;">Teleconsultation</span>
        </a>
      </li>'
	);
        $checkservice=  $this->fetch_all_rows($this->execute_query("select * from addon_services where clinic_id={$this->get_clinic_info($this->userInfo('user_id'))}"));
        //$this->printR($checkservice,$this->get_clinic_info($this->userInfo('user_id')));
         if($checkservice[0]['message']=='1')
          unset($data['message']);   
         if($checkservice[0]['teleconference']=='1')    
          unset($data['teleconsultation']);
           //$this->printR($data);
	return $this->build_template($this->get_template("sidebar"),$data);
}
/**
 * This function is for returning stream name.
 */
function streamNamePatient(){
	$psn = $this->userInfo('username');
	if( is_string($psn) ){
		//$psn = md5($psn);
		$psn = $psn;
		$pid = $this->userInfo('userid');
		if( is_numeric($pid) && $pid > 0 ){
			//$psn = $psn . md5($pid);
			$psn = $psn . $pid;
		}
	}
	$rand=rand(10000, 50000);
	$provoderlist=$this->get_paitent_provider( $this->userInfo('user_id'));
	$provoderlist=trim($provoderlist,',');
	$sn = "localSname={$psn}&liveSname={$provoderlist}&type=pai&rand={$rand}&url={$this->config['images_url']}";

	//echo  $sn;
	return $sn;
}

/**
 * This function formats the E-mail and send it to user, using php mail function.
 *
 * @param numeric $user_id
 * @return none
 * @access public
 */
function new_post_notification( $user_id = "" ){

	if( $user_id != "" ){

		$data = array(
				'url' => $this->config['url'],
				'images_url' => $this->config['images_url']
		);
		$message = $this->build_template("mail_content/new_message_patient.php",$data);

		$to = $this->userInfo('username',$user_id);

		//$from = $this->userInfo("username");
		$subject = "You have a new message";

		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";

		// Additional headers
		//$headers .= 'To: '.$fullName.'<'.$_POST['email_address'].'>'. "\n";
		/*$headers .= "To: " . $this->userInfo('name_title',$user_id) . " " . $this->userInfo('name_first',$user_id) ." " . $this->userInfo('name_last',$user_id) ."<". $this->userInfo('username',$user_id) .">" . "\n"; */
		$headers .= "From: info@txxchange.com\n";
		//$headers .= "From: " . "{$from}" . "\n";
		//$headers .= 'Cc: example@example.com' . "\n";
		//$headers .= 'Bcc: example@example.com' . "\n";

		$returnpath = '-finfo@txxchange.com';
		// Mail it
		//mail($to, $subject, $message, $headers, $returnpath);
	}

}


/**
 * This function gets the template path from xml file.
 *
 * @param string $template - pass template file name as defined in xml file for that template file.
 * @return string - template file
 * @access private
 */
function get_template($template){
	$login_arr = $this->action_parser($this->action,'template') ;
	$pos =  array_search($template, $login_arr['template']['name']);
	return $login_arr['template']['path'][$pos];
}
/**
 * This function sends the output to browser.
 *
 * @access public
 */
function display(){
	view::$output =  $this->output;
}
/**
 * This function gets the template path from xml file.
 *
 * @param clinic id .
 * @return scheduling link
 * @access private
 */
function scheduling($clinic_id){
	$sql="select scheduling from addon_services where clinic_id='{$clinic_id}'";
	$query=$this->execute_query($sql);
	$num=$this->num_rows($query);
	if($num>0){
		$result=$this->fetch_object($query);
		if($result->scheduling==1){
			$sql1="select * from scheduling where clinic_id='{$clinic_id}'";
			$query1=$this->execute_query($sql1);
			$num1=$this->num_rows($query1);
			if($num1>0){
				$result1=$this->fetch_object($query1);
				if($result1->schedulUrl!=''){
					return $link="<li style='padding-top:13px;'><a style='font-size:12px; margin-right:-5px; font-weight:bold; display:block; line-height:15px;' href=".$result1->schedulUrl." target='_blank'><img src='images/icon-calendar-tx.jpg' align='left' style='margin-right:5px;'></a><a style='font-size:12px; margin-right:-5px; font-weight:bold; display:block; line-height:15px;' href=".$result1->schedulUrl." target='_blank'><span style='display:block; padding-top:1px;color:green'>Schedule Appointment</span></a><div style='clear:both;'></div></li>";
				}
			}
			return false;
		}
	}

}


/*
 Function Name : ReadFolderDirectory.
Desc : used to Read temporary Folder .
Param : VARCHAR $dir
Return : array $listDir
Access : public
Created Date : 9 May 2011

*/
function ReadFolderDirectory($dir)
{
	$listDir = array();
	if($handler = opendir($dir)) {
		while (($sub = readdir($handler)) !== FALSE) {
			if ($sub != "." && $sub != ".." && $sub != "Thumb.db") {
				if(is_file($dir."/".$sub)) {
					$listDir[] = $sub;
				}elseif(is_dir($dir."/".$sub)){
					$listDir[$sub] = $this->ReadFolderDirectory($dir."/".$sub);
				}
			}
		}
		closedir($handler);
	}
	return $listDir;
}
 

/**
 * @desc used to show popup message when user unsubscribe the e health services.
 * @param void
 * @return void
 * @access public
 */
 
public function patient_ehealth_unsubscribe(){
	$userInfo = $this->userInfo();
	$userId = $userInfo['user_id'];
	$clinicId    =    $this->get_clinic_info($userId,"clinic_id");

	$subscriptionDetail=$this->getPatientSubscriptionDetails($userId,$clinicId);

	// $replace['message'] = "Are you sure you want to stop using {$clinicName}�s E-Health Service? You may no longer have access to your personalized health portal and the information and services {$clinicName} provides online.";
	$replace['message'] = "You are about to unsubscribe from {$subscriptionDetail['subscription_title']}. If you do, you will no longer be able to log into your patient portal and receive online care from your practitioner at the end of your current service billing period. Are you sure you want to unsubscribe?";

	$replace['subscrp_id']=$_REQUEST['subscrp_id'];
	$replace['body'] = $this->build_template($this->get_template("currentEhealthUnsubscribe"),$replace);
	$replace['browser_title'] = "Tx Xchange: Home";
	$this->output = $this->build_template($this->get_template("main"),$replace);
}




/**
 * This function to assign persoanlized GUI for Patient based on Account Type into Session Variable
 * @access public
 * @return void
 */
public function call_patient_gui() {
	//Check to load the Session Once
	//print_r($_SESSION['patientLabel']);
	if(count($_SESSION['patientLabel'])>0){

	}else{
		$userInfoValue=$this->userInfo();
		$persoanlizedPatientGUI=new lib_gui($userInfoValue);
		// Label v/s Provider Types
		$AccountTypeLabelsArray=$persoanlizedPatientGUI->getLabelValueClinic();
		// print_r($AccountTypeLabelsArray);
		foreach($AccountTypeLabelsArray as $key=>$value){
			$_SESSION['patientLabel'][$key]=$value;
		}
		$clinicfeaturelist=$persoanlizedPatientGUI->getFeatureClinicType();
		foreach($clinicfeaturelist as $key=>$value){
			$_SESSION['clinicfeature'][$key]=$value;
		}
	}
	//print_r( $_SESSION);
}

function newtreatmentplan($userid){
	$sql="SELECT * ,DATE_FORMAT(create_datetime, '%m/%d/%Y') as date1 FROM soap_plan WHERE patient_id=".$userid." and status='1' order by soap_plan_id desc";
	$result=$this->execute_query($sql);
	$numrow=$this->num_rows($result);
	if($numrow>0){
		$var.="<div class=\"list\" id=\"soapnote\"><table cellpadding=\"2\" cellspacing=\"2\" border=\"0\" width=\"100%\" style=\"font-size:12px;\" >";
		while($row = $this->fetch_array($result)){
			//$style = ($c++%2)?"line1":"line2";
			$providerId=$row['provider_id'];
			$providername=$this->userInfo('name_title',$providerId).' '.$this->userInfo('name_first',$providerId).' '.$this->userInfo('name_last',$providerId);
			$date=$row['date1'];
			$var.="<tr class=\"$style\"><td style=\"padding-left:12px;height:25px;\" ><a href=\"index.php?action=creat_pdf_plan_program&soap_plan_id=$row[soap_plan_id]\" target=\"_blank\">".$providername." "."$date </a></td></tr>";
		}
		$var.='</table></div>';

		// $varhead="<div style=\"width:280px; float:left; color:#0069A0; background:url(/images/bg-gray-heading.gif) top left repeat-x;border-top:#bbb solid 1px;border-bottom:#bbb solid 1px;padding:2px 0px;margin:6px 5px;\"><b>SOAP Notes</b></div><div style=\"clear:both\"></div>";
		$varhead="<h6 class=\"smallH6\" style=\"letter-spacing:1px;line-height:32px;\" ><span name=\"slideTPp\" id=\"slideTPp\" class=\"slideTPp\" style=\"cursor: pointer;\">+</span>
		<span name=\"slideTPn\" id=\"slideTPn\" class=\"slideTPn\" style=\"cursor: pointer;padding-right:10px;\">-</span>TREATMENT PROGRAM</h6>";
	}

	return $varhead.$var;

}

public function ServicesSummaries($userid){
	$sql="select pro_bill_services_patient.*,DATE_FORMAT(pro_bill_datetime, '%m/%d/%Y') as format_pro_bill_datetime from pro_bill_services_patient where pro_bill_patient_id = '{$userid}' AND pro_status = '1'";
	$result=$this->execute_query($sql);
	$numrow=$this->num_rows($result);
	if($numrow>0){
		$var.="<div class=\"list\" id=\"summaryservices\"><table cellpadding=\"2\" cellspacing=\"2\" border=\"0\" width=\"100%\" style=\"font-size:12px;\" >";
		while($row = $this->fetch_array($result)){
			// $style = ($c++%2)?"line1":"line2";
			// Use to identify the Service summary from user
			$pro_bill_services_patient_id=$row['pro_bill_services_patient_id'];
			$date=$row['format_pro_bill_datetime'];
			$var.="<tr class=\"$style\"><td style=\"padding-left:12px;\" ><a href=\"#\" target=\"_blank\">".$date." </a></td></tr>";
		}
		$var.='</table></div>';

		// $varhead="<div style=\"width:280px; float:left; color:#0069A0; background:url(/images/bg-gray-heading.gif) top left repeat-x;border-top:#bbb solid 1px;border-bottom:#bbb solid 1px;padding:2px 0px;margin:6px 5px;\"><b>SOAP Notes</b></div><div style=\"clear:both\"></div>";
		 
	}
	$varhead="<h6 class=\"smallH6\" style=\"letter-spacing:1px;\" ><span name=\"slidePsum\" id=\"slidePsum\" class=\"slidePsum\" style=\"cursor: pointer;\">+</span><span name=\"slideNsum\" id=\"slideNsum\" class=\"slideNsum\" style=\"cursor: pointer;\">-</span>Services Summary</h6>";

	return $varhead.$var;

}
function GetstoreURL(){
	$userInfo = $this->userInfo();
	$userId = $userInfo['user_id'];
	$clinicId    =    $this->get_clinic_info($userId,"clinic_id");
	$sql="select storeURL from clinic where clinic_id=".$clinicId;
	$query=$this->execute_query($sql);
	if($this->num_rows($query)>0)
	{
		$row=$this->fetch_object($query);
		return $row->storeURL;
	}
	 
}
function troublesoot_patient(){
		
	$replace['body'] = $this->build_template($this->get_template("troublesoot_patient"),$replace);
	$this->output = $this->build_template($this->get_template("main"),$replace);

}
function PaypalErroCodeslist($errorCode){
	if($errorCode!='' or $errorCode!='0' ){
		$sql="select * from paypal_error_code where error_code='{$errorCode}'";
		$result=$this->execute_query($sql);
		if($this->num_rows($result)>0){
			$row=$this->fetch_object($result);
			$customerrormessage=$row->long_message;
		}else{
			$customerrormessage="Invalid Credit Card Details";
		}
	}else{
		$customerrormessage="Invalid Credit Card Details";
	}
	return $customerrormessage;
}

/*
 * finding the provider for a paitent for opentok
 * @author:rohit.mishra@hytechpro.com
 * Modified On:20 AUG 2013
 */
public function get_paitent_providerfor_opentok($pid){
    	$query = "select therapist_id from therapist_patient 
                                  where patient_id = '{$pid}' and status = 1 ";
                        $result = @mysql_query($query);
                        $provider_list=array();
                        //user1:name,user2:name
                        while( $row = @mysql_fetch_array($result)){
                            
                            $provider_list[]=array('id' => $row["therapist_id"],
                                                   'name' => $this->userInfo("name_first",$row["therapist_id"])." ".$this->userInfo("name_last",$row["therapist_id"])
                                                  );
                        	

                        	
                        }	
    	return $provider_list;
    }

     /**
        * This function is for Creating the opentok session and token.
        */
        function get_opentok_paramenter(){
            
            ini_set('memory_limit', '-1');
            ini_set('display_errors',"1");
            error_reporting(E_ALL);
                // Creating an OpenTok Object
                $apiObj = new OpenTokSDK( API_Config::API_KEY, API_Config::API_SECRET );

                // Creating Simple Session object, passing IP address to determine closest production server
                // Passing IP address to determine closest production server
                $session = $apiObj->createSession( $_SERVER["REMOTE_ADDR"] );

                // Creating Simple Session object 
                // Enable p2p connections
                $session = $apiObj->createSession( $_SERVER["REMOTE_ADDR"], array(SessionPropertyConstants::P2P_PREFERENCE=> "enabled") );

                // Getting sessionId from Sessions
                // Option 1: Call getSessionId()
                $sessionId = $session->getSessionId();
                //echo "Session=". $sessionId;
                // Option 2: Return the object itself


                // After creating a session, call generateToken(). Require parameter: SessionId
                $token = $apiObj->generateToken($sessionId);

                // Giving the token a moderator role, expire time 5 days from now, and connectionData to pass to other users in the session
                $token = $apiObj->generateToken($sessionId, RoleConstants::MODERATOR, time() + (30*24*60*60), "TxxChange Test" );
               // echo "<br>Token=". $token;
          
                $return =array();
            
                $return['sessionId'] = $sessionId;
                $return['token'] = $token;

               // print_r($return);
               // die;
                return $return;
            
        }
        
        function check_call_status($patainent_id,$provider_id)
        {
            $call_staus=0;
           $query = " select * from opentok_session where patainent_id!='{$patainent_id}' AND provider_id='$provider_id' AND call_status='1' AND creation_date >= DATE_SUB( NOW(), INTERVAL 24 HOUR)  ORDER BY id DESC";
                        $result = $this->execute_query($query);
                        if( $this->num_rows($result) > 0 ){
                        $call_staus =1;
                        } 

                        return $call_staus;
        }
// creating object of the class.

}

//this function check for opentok call staus


$obj = new patient();
?>
