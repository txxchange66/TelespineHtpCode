<?php



/**
 *
 * Copyright (c) 2008 Tx Xchange
 *
 * It includes the functionality for clinic listing, Edit existing and Add new clinic.
 * it also includes the functionality for list user, edit, changestatus and delete for selected clinic from list.
 *
 * // necessary classes
 * require_once("module/application.class.php");
 *
 * // pagination class
 * require_once("include/paging/my_pagina_class.php");
 *
 * //Server side form validation classes
 * require_once("include/validation/_includes/classes/validation/ValidationSet.php");
 * require_once("include/validation/_includes/classes/validation/ValidationError.php");
 *
 */


// including files
require_once("include/fileupload/class.upload.php");
require_once("module/application.class.php");
require_once("include/paging/my_pagina_class.php");
require_once("include/validation/_includes/classes/validation/ValidationSet.php");
require_once("include/validation/_includes/classes/validation/ValidationError.php");
require_once("include/excel/excelwriter.inc.php");
require_once("include/class.paypal.php");

// class declaration
class headAccountAdmin extends application{

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
			$str = $this->value('action');
		}else{
			$str = "accountAdminClinicList"; //default if no action is specified
		}
		$this->action = $str;
		if($this->get_checkLogin($this->action) == "true" ){
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
			$str = $str."()";
			$clinicId =  $this->clinicInfo('clinic_id');
			$parentClinicId = $this->get_field($clinicId,'clinic','parent_clinic_id');
			if( is_numeric($parentClinicId) && $parentClinicId != 0){
				header('location:index.php?action=userListing');
				exit();
			}

			// Code To Call Personalized GUI
			$this->call_gui();

			eval("\$this->$str;");
		}
		else{
			$this->output = $this->config['error_message'];
		}
		$this->display();
	}
	 
	/**
	 * This function returns url for tab.
	 */
	function tab_url(){
		$url_array = array();
		$clinicId = $this->clinicInfo("clinic_id");
		if( !empty($clinicId) ){
			$url_array['location'] = "index.php?action=accountAdminClinicList&clinic_id={$clinicId}";
			$url_array['userLocation'] = "index.php?action=userListingHead&clinic_id={$clinicId}";
			$url_array['patientLocation'] = "index.php?action=patientListingHead&clinic_id={$clinicId}";
		}
		return $url_array;
	}
	/**
	 * This function is used to display the patient listing for the particular account admin.
	 *
	 * @access public
	 */
	function patientListingHead(){

		// Retian page value.
		$arr = array(
				'clinic_id' => $this->clinicInfo('clinic_id')
		);
		//$this->value('clinic_id')
		$this->set_session_page($arr);
		// set templating variables
		$userInfo = $this->userInfo();
		$cli_type=$this->getClinicDetails($this->userInfo('user_id'));
		//if($cli_type['clinic_type']==4){
		$replace['shopshow']=1;
                 /* code for show telespine menu on list */
        $replace['showmenu']="no";
        if($this->clinicInfo('clinic_id')==$this->config['telespineid']){
            $replace['showmenu']="yes";
        }
        /* code end */
		/*}else{
		 $replace['shopshow']='0';
		}*/
		$replace['browser_title'] = 'Tx Xchange: Patient List';
		$replace['header'] = $this->build_template($this->get_template("header"));
		$replace['sidebar'] = $this->sidebar();
		$replace['footer'] = $this->build_template($this->get_template("footer"));
		$msg = $this->value('msg');
		$replace['error'] = "";
		if(!empty($msg)){
			$replace['error'] = $this->errorPatientListModule($msg);
		}
		if($this->value('sort') != ""){
			if($this->value('order') == 'desc' ){
				$orderby = " order by {$this->value('sort')} desc ";
			}
			else{
				$orderby = " order by {$this->value('sort')} ";
			}
		}
		else{
			$orderby = " order by name_first ";
		}



		/**
		 *    Query for generating the patient list
		 *    CONDITIONS :
		 *    1.  Patient must associated with the clinic whose Therapist(Accound Admin) is logged in
		 *    2.  Patient must have one associated therapist
		 *    3.  Patient status != 3, either Active or InActive
		 */
		$privateKey = $this->config['private_key'];
		$clinicId = $this->clinicInfo('clinic_id');//$this->value('clinic_id');
		// Search string
		$searchString = $this->value('search');
		if( !empty($searchString) ){
			$searchString = $this->value('search');
			$searchWhere = " and ( CAST(AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}') AS CHAR) like '%{$this->value('search')}%' or CAST(AES_DECRYPT(UNHEX(u.name_last),'{$privateKey}') AS CHAR) like '%{$this->value('search')}%') ";
		}
		$privateKey = $this->config['private_key'];
		$query = "select  u.user_id,
		AES_DECRYPT(UNHEX(u.name_title),'{$privateKey}') as name_title,
		cast(AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}') as char) as name_first,
		AES_DECRYPT(UNHEX(u.name_last),'{$privateKey}') as name_last,
		u.username, u.status, cu.clinic_id, clinic.clinic_name ,ps.subscription_title as subscription_title
		from user as u left join patient_subscription as ps on u.user_id=ps.user_id and ((ps.subs_status='1' and ps.paymentType='0') or (ps.subs_status='2' and subs_end_datetime >= now()) or (ps.subs_status='1'  and ps.paymentType='1' and subs_end_datetime > now()))
		inner join clinic_user as cu on u.user_id=cu.user_id
		and cu.clinic_id in (select clinic_id from clinic where (parent_clinic_id = '{$clinicId}' or clinic_id = '{$clinicId}') and ( status = 1 or status = 2 ) )
		inner join clinic  on clinic.clinic_id = cu.clinic_id and (clinic.status = '1' or clinic.status = '2')
		where u.usertype_id='1' and (u.status=1 or u.status = 2)
		{$searchWhere} {$orderby}";
		$sqlcount="select  count(1)
		from user as u
		inner join clinic_user as cu on u.user_id=cu.user_id
		and cu.clinic_id in (select clinic_id from clinic where (parent_clinic_id = '{$clinicId}' or clinic_id = '{$clinicId}') and ( status = 1 or status = 2 ) )
		inner join clinic  on clinic.clinic_id = cu.clinic_id and (clinic.status = '1' or clinic.status = '2')
		where u.usertype_id='1' and (u.status='1' or u.status = '2')
		{$searchWhere}";

		// pagination
		$link = $this->pagination($rows = 0,$query,'patientListingHead',$searchString,array('msg'),'','',$sqlcount);
		$replace['link'] = $link['nav'];
		$result = $link['result'];

		// check sql query result
		if(is_resource($result)){
			while($row = $this->fetch_array($result)){
				$therapistName = $row['therapist'];
				if( $this->viewTherapistHead(true,$row['user_id'],$clinicId) ){
					$therapistName = "<a  href='index.php?action=viewTherapistHead&patient_id={$row['user_id']}&clinic_id={$clinicId}'  title='ASSOCIATED PROVIDER' rel='gb_page_center[600, 310]'>View</a> ";
				}
				else{
					$therapistName = "Not Assigned";
				}
				/* $EHealthService=$this->getPatientSubscriptionDetails($row['user_id'],$clinicId);
				 if($EHealthService['subs_title']!=''){
				$replace['EHealthService']= $EHealthService['subs_title'];
				}else{
				$replace['EHealthService']= 'Not Subscribed';
				}
				*/
				$replace['classname'] = (++$k%2) ? 'line2' : 'line1';
				$replace['userId'] = $row['user_id'];
				$replace['patientName'] = $row['name_title'].' '.$row['name_first'].' '.$row['name_last'];
				$replace['patientEmailId'] = $row['username'];
				$replace['associatedTherapistName'] = $therapistName;
				$replace['associatedClinicName'] = $this->get_clinic_info($row['user_id'],"clinic_name");
				if($row['subscription_title']=='')
					$replace['EHealthService']= 'Not Subscribed';
				else
					$replace['EHealthService']=$row['subscription_title'];
				$replace['patientStatus'] = $row['status'] != "" ? $this->config['patientStatus'][$row['status']] : "";
				$rowdata .= $this->build_template($this->get_template("temp_patientListRecord"),$replace);
			}
		}
		$patientAcctListHead = array(
				'name_first' => 'Patient Name',
				'u.username' => 'Email Address',
				'clinic.clinic_name' => 'Clinic',
				'status' => 'Status',
				'subscription_title'=>'E-Health Service',
		);

		$query_string = array(
				'clinic_id' => $clinicId
		);
		$replace['patientAcctListHead'] = $this->build_template($this->get_template("patientAcctListHead"),$this->table_heading($patientAcctListHead,"name_first",$query_string));

		$replace['rowdata'] = $rowdata;
		$replace['clinic_id'] = $clinicId;//$this->value('clinic_id');
		$url_array = $this->tab_url();
		$replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$url_array);
		$replace['patientLocation'] = $url_array['patientLocation'];
		$replace['body'] = $this->build_template($this->get_template("patient_list"),$replace);
		$replace['get_satisfaction'] = $this->get_satisfaction();
		$this->output = $this->build_template($this->get_template("main"),$replace);
	}
	/**
	 * Function to Add or Edit the patient Details
	 *
	 * @access public
	 */
	function HeadAdminEditPatients() {

		$this->form_array = $this->populate_form_array();

		$replace['clinic_id'] = $this->value('clinic_id');
                /* code for show telespine menu on list */
        $replace['showmenu']="no";
        if($this->clinicInfo('clinic_id')==$this->config['telespineid']){
            $replace['showmenu']="yes";
        }
        /* code end */
		$clinicId = $this->value('clinic_id');
		$url_array = $this->tab_url();
		$userInfo = $this->userInfo();
		$cli_type=$this->getClinicDetails($this->userInfo('user_id'));
		//if($cli_type['clinic_type']==4){
		$replace['shopshow']=1;
		/*}else{
		 $replace['shopshow']='0';
		}*/
		$replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$url_array);
		$patient_id = $this->value('patient_id')!=session_id()?$this->value('patient_id'):"";
		$option = $this->value('option');
		$replace['patient_id'] = $patient_id;

		$replace['footer'] = $this->build_template($this->get_template("footer"));
		$replace['header'] = $this->build_template($this->get_template("header"));
		$replace['sidebar'] = $this->sidebar();
                if($this->is_corporate($clinicId)==1){
                      $replace['corporate']='none';
                } else {
                     $replace['corporate']='block';
                }    
		if($this->value('clinic_id') == EHSCLINICID || EHSCLINICID == '') {
			$replace['SHOWEHS'] = "display:block";
		} else {
			$replace['SHOWEHS'] = "display:none";
		}

		// In case of Add
		if(empty($patient_id))
		{
			// Populate BLANK FORM fields
			$this->assignValueToArrayFields($this->form_array, '', '', &$replace);
			$replace['operationTitle'] = 'Add';
			$replace['browser_title'] = 'Tx Xchange: Add Patient';
			$replace['buttonName'] = 'Add Patient';

			$status_arr['patientStatus'] =  array( '1' => "Current");
			$replace['patient_status_options'] = $this->build_select_option($status_arr['patientStatus'], $replace['patient_status']);

			/*
			 @date: 13th october 2011
			@description:
			Check for the E-health service
			Functionality added on 13th october as per UC 2.7.4
			It will check the E health service turn /off condition. If the E health service is off then checkbox will become disabled.
			If the E- health service is enabled then it will check the patient subscription. If the patient is subscribed then it will be in disable mode
			else in enable mode.
			*/
			if(!empty($clinicId) && $clinicId > 0) {
				$sqlEhealth = "SELECT subs_status FROM clinic_subscription WHERE subs_clinic_id  = " . $clinicId;
				$queryEhealth = $this->execute_query($sqlEhealth);
				$numEhealthRow = $this->num_rows($queryEhealth);
				if($numEhealthRow!= '0') {
					$valueEhealth = $this->fetch_object($queryEhealth);
					if($valueEhealth->subs_status == 0) {
						$replace['ehsEnable'] = 'disabled';
						$replace['ehsDisable'] = '0';
					} else {
						$replace['ehsEnable'] = '';
						$replace['ehsDisable'] = '1';

					}
				} else {
					$replace['ehsEnable'] = 'disabled';
					$replace['ehsDisable'] = '0';

				}
			} else {
				$replace['ehsEnable'] = 'disabled';
				$replace['ehsDisable'] = '0';

			}

			if($_REQUEST['ehsEnable'] == '' && $_REQUEST['Submit']!= '')
				$replace['ehsEnable'] = '';

			//End here

			/*  if(($replace['newPassword']) == ''){
			 $replace['newPassword'] = $this->generateCode(9);
			}*/
			//$replace['newPasswordLabel'] = '<div align="right" onMouseOver="help_text(this, \'You can also type your password\')"><strong>New Password </strong> </div>';
			//$replace['newPasswordField'] = '<input tabindex="7" type="text" name="newPassword" onMouseOver="help_text(this, \'You can also type your password\')" value="'.$replace['newPassword'].'" />';
			$replace['newPasswordLabel'] = '';
			$replace['newPasswordField'] = '';

			// Because of passing session_id as patient_id
			$patient_id = session_id();
			$clinic_id = $this->value('clinic_id');
			$replace['editReminderlink'] = $patient_id;

			$patient_id = "";

			if(!empty($option)){
				// validating form
				$this->validateForm('add');
				if(($this->error) != ""){
					// if error found in form
					$this->assignValueToArrayFields($this->form_array, '', '', &$replace, '0');
					$replace['error'] = $this->error;




				}else{
					 
					$updateArr = $this->assignValueToArrayFields($this->form_array, '', '2', '', 'insert');
					$updateArr['status'] = '1';
					$updateArr['usertype_id'] = '1';
					$updateArr['creation_date'] = date('Y-m-d H:i:s',time());
					$updateArr['modified'] = date('Y-m-d H:i:s',time());
					$updateArr['mass_message_access'] = 1;
					$updateArr['password'] = $this->value('patient_last_name').'01';

					//Modified on 2nd november for E-health service
					if($_REQUEST['ehsDisable']== "0") {
						$updateArr['ehs'] = '1';
					} elseif($_REQUEST['ehsEnable'] == "1") {
						$updateArr['ehs'] = '1';
					} else {
						$updateArr['ehs'] = '0';
					}
					//End here

					//echo "<pre>";print_r($updateArr);exit;
					// Encrypt the data
					//$encrypt_field = array('address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2');
					//$updateArr = $this->encrypt_field($updateArr, $encrypt_field);

					$result = $this->insert('user',$updateArr);
					$insertedId = $this->insert_id();

					/**
					 *  Replace Records from temprory table to original Table
					 *  1.  From tmp_therapist_patient  to  therapist_patient
					 *  2.  From tmp_patient_reminder  to  patient_reminder
					 */
					$clinicUserArr = array('clinic_id' => $this->value('clinic_id'), 'user_id' => $insertedId);
					$result = $this->insert('clinic_user',$clinicUserArr);

					$query = "SELECT * FROM tmp_patient_reminder WHERE patient_id = '".session_id()."'";
					$rs = $this->execute_query($query);
					$rows = $this->populate_array($rs, '0');

					// count no of rows
					if((count($rows)) > 0 )
						foreach($rows as $row){
						$PatientReminderArr = array('patient_id' => $insertedId,
								'user_id' => $row['user_id'],
								'reminder' => $row['reminder'],
								'creation_date' => $row['creation_date'],
								'modified' => $row['modified'],
								'status' => '1'
						);
						$result = $this->insert('patient_reminder',$PatientReminderArr);

						// deleting temprory tables
						$query = "DELETE FROM tmp_patient_reminder WHERE patient_reminder_id = '".$row['patient_reminder_id']."'";
						$this->execute_query($query);
					}

					$query = "SELECT * FROM tmp_therapist_patient WHERE patient_id = '".session_id()."'";
					$rs = $this->execute_query($query);
					$rows = $this->populate_array($rs, '0');

					if((count($rows)) > 0 )
						foreach($rows as $row){
						$therapistPatientArr = array('therapist_id' => $row['therapist_id'],
								'patient_id' => $insertedId,
								'creation_date' => $row['creation_date'],
								'modified' => $row['modified'],
								'status' => '1'
						);
						$result = $this->insert('therapist_patient',$therapistPatientArr);

						$query = "DELETE FROM tmp_therapist_patient WHERE therapist_patient_id = '".$row['therapist_patient_id']."'";
						$this->execute_query($query);
					}

					 
					$clinicName=html_entity_decode($this->get_clinic_name($this->value('clinic_id')), ENT_QUOTES, "UTF-8");
					//have the HTML content
					$clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
					if($clinic_channel==2){
						$business_url=$this->config['business_wx'];
						$support_email=$this->config['email_wx'];
					}else{
						$business_url=$this->config['business_tx'];
						$support_email=$this->config['email_tx'];
					}
					$data = array(
							'username' => $this->userInfo("username",$insertedId),
							'password' => $this->userInfo("password",$insertedId),
							'url' => $this->config['url'],
							'images_url' => $this->config['images_url'],
							'clinic_name' => $clinicName,
							'business_url'=>$business_url,
							'support_email'=>$support_email,
							'name'=>$this->userInfo("name_first",$insertedId)
					);


					/*  Mail Section  */
					//$user_id = $this->userInfo('user_id');
					$clinic_type = $this->getUserClinicType($insertedId);

					if( $clinic_channel == 2){
						 
						$message = $this->build_template("mail_content/wx/create_new_patient_mail_plpto.php",$data);
					}
					else{
						$message = $this->build_template("mail_content/plpto/create_new_patient_mail_plpto.php",$data);

					}
					//$message = $this->build_template("mail_content/create_new_patient_mail.php",$data);
					$to = $updateArr['username'];
					$subject = "Your ".$clinicName." Health Record";
					// To send HTML mail, the Content-type header must be set
					$headers  = 'MIME-Version: 1.0' . "\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";

					// Additional headers
					if( $clinic_channel == 2){
						$headers .= "From: ".$this->setmailheader($clinicName). " <".$this->config['email_wx'].">" . "\n";
						$returnpath = '-f'.$this->config['email_wx'];
					}else{
						$headers .= "From: ".$this->setmailheader($clinicName). " <".$this->config['email_tx'].">" . "\n";
						$returnpath = "-f".$this->config['email_tx'];
					}
					// Mail it
					mail($to, $subject, $message, $headers, $returnpath);
					$url = $this->redirectUrl('patientListingHead').'&msg=patient_added';
					header("location:$url");
					exit();
				}
			}else{
				//  Deleting Temprory table rows for "Edit Reminder" and "Edit associate link", if user comes after clicking button "Add New Patient"
				$query = "DELETE FROM tmp_patient_reminder WHERE patient_id = '".session_id()."'";
				$this->execute_query($query);

				$query = "DELETE FROM tmp_therapist_patient WHERE patient_id = '".session_id()."'";
				$this->execute_query($query);
			}

			$stateArray = array("" => "Choose State...");
			$stateArray = array_merge($stateArray,$this->config['state']);
			$countryArray = $this->config['country'];
			$replace['country']=implode("','",$countryArray);
			$replace['patient_country_options'] = $this->build_select_option($countryArray, $row['country']);



			 
			$stateArray = array("" => "Choose State...");
			$stateArray = array_merge($stateArray,$this->config['state']);
			$replace['patient_state_options'] = $this->build_select_option($stateArray, $row['state']);

			$replace['patient_title_options'] = $this->build_select_option(array_merge(array('' => 'Choose...'), $this->config['title']), $replace['patient_title']);
			$replace['patient_suffix_options'] = $this->build_select_option(array_merge(array('' => 'Choose...'), $this->config['suffix']), $replace['patient_suffix']);
			$url_array = $this->tab_url();
			$countryArray = $this->config['country'];
			$replace['country']=implode("','",$countryArray);
			$replace['patient_country_options'] = $this->build_select_option($countryArray, $row['country']);

			if( isset($_REQUEST['clinic_country']) && $_REQUEST['clinic_country']!='')

			{
				 
				 
				 
				$replace['patient_country_options'] = $this->build_select_option($countryArray, $_REQUEST['clinic_country']);
				 
				if($_REQUEST['clinic_country']=='US') {
					$stateArray = array("" => "Choose State...");
					$stateArray = array_merge($stateArray,$this->config['state']);
					$replace['patient_state_options'] = $this->build_select_option($stateArray, $_REQUEST['patient_state']);
				}
				 
				else if($_REQUEST['clinic_country']=='CAN') {
					$stateArray = array("" => "Choose Province...");
					$stateArray = array_merge($stateArray,$this->config['canada_state']);
					$replace['patient_state_options'] = $this->build_select_option($stateArray, $_REQUEST['patient_state']);
				}
				 
				 
				 
			}

			if(!isset($_REQUEST['clinic_country']))
			{


				if($row['country']=='US') {
					$stateArray = array("" => "Choose State...");
					$stateArray = array_merge($stateArray,$this->config['state']);
					$replace['patient_state_options'] = $this->build_select_option($stateArray, $row['state']);
				}

				else if($row['country']=='CAN') {
					$stateArray = array("" => "Choose Province...");
					$stateArray = array_merge($stateArray,$this->config['canada_state']);
					$replace['patient_state_options'] = $this->build_select_option($stateArray, $replace['patient_state']);
				}
				 
				 
			}
			$replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$url_array);
			$replace['body'] = $this->build_template($this->get_template("accountAdminAddPatient"),$replace);
			// In case of Edit
		}else{
			$replace['operationTitle'] = 'Edit';
			$replace['buttonName'] = 'Save Patient';
			$replace['browser_title'] = 'Tx Xchange: Edit Patient';
			$replace['newPasswordLabel'] = '<div align="right" ><strong>Password </strong> </div>';
			$replace['newPassword'] = $this->value('newPassword');
			$replace['cnewPassword'] = $this->value('cnewPassword');

			if( !isset($replace['newPassword']) || empty($replace['newPassword']) ){
				$replace['newPassword'] = $this->userInfo('password',$patient_id);
			}
			if( !isset($replace['cnewPassword']) || empty($replace['cnewPassword']) ){
				$replace['cnewPassword'] = $this->userInfo('password',$patient_id);
			}
			$replace['newPasswordField'] = '<input tabindex="7" type="password" name="newPassword" value="'.$replace['newPassword'].'" />';
			$replace['confirmPasswordField'] = '<input tabindex="8" type="password" name="cnewPassword" value="'.$replace['cnewPassword'].'" />';
			$replace['confirmPasswordTextField'] = '<div align="right" ><strong>Confirm Password &nbsp;</strong> </div>';
			$clinic_id = $this->value('clinic_id');
			$replace['editReminderlink'] = $patient_id;
			if($option == 'update'){
				$this->validateForm();
				if($this->error == ""){
					//  Populate FieldArray from FormArray
					//print_r($_POST);exit;
					if(!empty($patient_id)){
						$updateArr = $this->assignValueToArrayFields($this->form_array, '', '2', '', 'insert');
						if($updateArr['mass_message_access']==1)
							$updateArr['mass_message_access']=2;
						else
							$updateArr = $this->removeFromArray($updateArr, array('mass_message_access'=>''));

						//Modified on 2nd november for E-health service
						 
						if($_REQUEST['ehsDisable']== "0") {
							$updateArr['ehs'] = '1';
						} elseif($_REQUEST['ehsEnable'] == "on") {
							$updateArr['ehs'] = '1';
						} else {
							$updateArr['ehs'] = '0';
						}

						$changedPassword = $this->value('newPassword');

						// If user doesnot change the existing password, password field removed from array
						if(empty($changedPassword)){
							$updateArr = $this->removeFromArray($updateArr, array('password'=>''));
						}


						// Encrypt the data
						//$encrypt_field = array('address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2');
						//$updateArr = $this->encrypt_field($updateArr, $encrypt_field);

						$where = " user_id = '".$patient_id."'";
						//print_r($updateArr);exit;
						$result = $this->update('user',$updateArr,$where);

						if($updateArr['status'] == 3){

							// Remove clinic patient relationship from clinic_user Table
							$query_removeClinicPatientRelation = "delete from clinic_user where user_id='".$patient_id."'";
							$this->execute_query($query_removeClinicPatientRelation);

							// Remove therapist patient relationship from therapist_patient table
							$query_removetherapistPatientRelation = "delete from therapist_patient where patient_id='".$patient_id."'";
							$this->execute_query($query_removetherapistPatientRelation);
						}

						/// If account admin change patient's password, a mail should be sent to patient.
						$url = $this->redirectUrl("patientListingHead").'&msg=1';
						header("location:$url");
						exit();
					}
				}else{
					//Show errors and populate FORM fields from $_POST.

					$this->assignValueToArrayFields($this->form_array, '', '', &$replace, '0');
					$replace['error'] = $this->error;
				}
			}else{
				//Populate FORM fields from database.

				$query = "select * from user where user_id='".$patient_id."'";
				$rs = $this->execute_query($query);
				$row = $this->populate_array($rs);

				// Fetch Replace array from row
				// populate FormArray from FieldArray
				$encrypt_field = array('name_title','name_first','name_last','password','address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2');
				$row = $this->decrypt_field($row, $encrypt_field);
				$this->assignValueToArrayFields($this->form_array, $row, '1', &$replace);
				//print_r($row);
			}
			$replace['patient_status_options'] = $this->build_select_option($this->config['patientStatus'], $replace['patient_status']);

			$stateArray = array("" => "Choose State...");
			$stateArray = array_merge($stateArray,$this->config['state']);
			/******************************************************************************************/
			/***code starts here for providing AA to opt out for mass message feature for the patient**/
			$mass_message_access=$row['mass_message_access'];
			$replace['mass_message_access'] = $mass_message_access;
			if($mass_message_access==2)
			{
				$replace['mass_message_checked'] = "checked";
				$replace['mass_message_disabled'] = "disabled";
			}
			else
			{ $replace['mass_message_checked'] = "";
			$replace['mass_message_disabled'] = "";
			}

			/***code ends above for providing AA to opt out for mass message feature for the patient**/
			/****************************************************************************************/
			/* code for view intake paperwork link*/
			$replaceIntake=array();
			$intakeview=$this->execute_query("select * from patient_intake where p_user_id=".$patient_id);
			$numcount=$this->num_rows($intakeview);
			$brifintakeview=$this->execute_query("select * from patient_brief_intake where p_user_id=".$patient_id);
                	$briefnumcount=$this->num_rows($brifintakeview);
			if($numcount=='0'){
				$replaceIntake['labelintakepaperwork']="<b>Adult Intake Paperwork</b>";
				$replaceIntake['intakepaperwork']="value='Assign' onclick='assignintake(".$this->userInfo('user_id')." ,".$patient_id.");' style='display:block;width:80px;";
				$replaceIntake['display1']='none';
			}else{
				$rowintake=$this->fetch_array($intakeview);
				//print_r($rowintake);
				if($rowintake['intake_compl_status']=='0') {
				       $replaceIntake['labelintakepaperwork']="<b>Adult Intake Paperwork Assigned</b>";
			               $replaceIntake['intakepaperwork']="value='Assign' onclick='assignintake(".$this->userInfo('user_id')." ,".$patient_id.");'   style='display:none;width:80px;' ";
			               $replaceIntake['display1']='block';

				}
				else {
					$replaceIntake['labelintakepaperwork']="<b>Adult Intake Paperwork Completed</b>  ";  
					$replaceIntake['intakepaperwork']="value='View' onclick='javascript:view_intake_paper();' style='width: 80px;' ";
					$replaceIntake['display1']='none';
				}
			}
		if($briefnumcount=='0'){
                    $replaceIntake['labelbriefintakepaperwork']="<b>Brief Intake Paperwork</b>";    
                    $replaceIntake['briefintakepaperwork']="value='Assign' onclick='assignbriefintake(".$this->userInfo('user_id')." ,".$patient_id.");' style='display:block;width:80px;' ";   
                    $replaceIntake['display']='none';
                }else{
                 $rowbrifeintake=$this->fetch_array($brifintakeview);
                 //print_r($rowintake);
                  if($rowbrifeintake['intake_compl_status']=='0') {
                     $replaceIntake['labelbriefintakepaperwork']="<b>Brief Intake Paperwork Assigned</b>";
                     $replaceIntake['briefintakepaperwork']="value='Assign' onclick='assignbriefintake(".$this->userInfo('user_id')." ,".$patient_id.");'  style='display:none;width:80px;' ";
                     $replaceIntake['display']='block';
                  }
                  else {
                  $replaceIntake['labelbriefintakepaperwork']="<b>Brief Intake Paperwork Completed</b>  ";  
                  $replaceIntake['briefintakepaperwork']="value='View' onclick='javascript:view__brief_intake_paper();' style='width: 80px;' ";
                  $replaceIntake['display']='none';
                  }
                }	
$replaceIntake['provider_id']=$this->userInfo('user_id');
$replaceIntake['patient_id']=$patient_id;
			if($_SESSION['accountFeature']['Intake Paperwork']=='1')
				$replace['intakeAssign']=$this->build_template($this->get_template('intakeAssign'),$replaceIntake);
				$replace['briefintakeAssign']=$this->build_template($this->get_template('briefintakeAssign'),$replaceIntake);



			/***end of view intake paper work***/
			 
			$sql="select lab_report_id,lab_title,DATE_FORMAT(lab_datetime, '%m/%d/%Y') as labdate,lab_path from patient_lab_reports where pat_user_id ={$patient_id} order by lab_report_id desc";
			$query=$this->execute_query($sql);
			//$row=$this->fetch_array($query);
			 
			$rowLab = $this->populate_array($query,0);
			if($rowLab!='')
			{
				foreach($rowLab as $klab => $kValue)
				{
					//print_r($kValue);
					$replace['labresult_display'].='<tr>
					<td style=" border-bottom:1px solid #cbcbcb;color:#005b85; padding-left:5px; width:229px; "><a href="index.php?action=downloadfile&lab_report_id='.$kValue['lab_report_id'].'" target="_NEW" >'.$kValue['lab_title'].' '.$kValue['labdate'].'</a> </td>
					</tr>';
				}
			}
			/***end of view intake paper work***/



			/* code for show lab result*/
			$pid=$this->value('patient_id');
			$replace['LabResult']="<a href='javascript:void(0);' onclick=\"GB_showCenter('Upload Results & Documents', '/index.php?action=aa_upload_lab_result&pid={$pid}&role=headAA',190,430);\"><input type='button'' value='Upload'></a>";

			/* end of code */

			$replace['patient_state_options'] = $this->build_select_option($stateArray, $replace['patient_state']);
			$replace['patient_title_options'] = $this->build_select_option(array_merge(array('' => 'Choose...'), $this->config['title']), $replace['patient_title']);
			$replace['patient_suffix_options'] = $this->build_select_option(array_merge(array('' => 'Choose...'), $this->config['suffix']), $replace['patient_suffix']);
			$url_array = $this->tab_url();
			$replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$url_array);

			$countryArray = $this->config['country'];
			$replace['country']=implode("','",$countryArray);
			 
			 
			 
			$replace['patient_country_options'] = $this->build_select_option($countryArray, $row['country']);


			 

			if( isset($_REQUEST['clinic_country']) && $_REQUEST['clinic_country']!='')

			{
				 
				 
				 
				$replace['patient_country_options'] = $this->build_select_option($countryArray, $_REQUEST['clinic_country']);
				 
				if($_REQUEST['clinic_country']=='US') {
					$stateArray = array("" => "Choose State...");
					$stateArray = array_merge($stateArray,$this->config['state']);
					$replace['patient_state_options'] = $this->build_select_option($stateArray, $_REQUEST['patient_state']);
				}
				 
				else if($_REQUEST['clinic_country']=='CAN') {
					$stateArray = array("" => "Choose Province...");
					$stateArray = array_merge($stateArray,$this->config['canada_state']);
					$replace['patient_state_options'] = $this->build_select_option($stateArray, $_REQUEST['patient_state']);
				}
				 
				 
				 
			}

			if(!isset($_REQUEST['clinic_country']))
			{
				$replace['country']=implode("','",$countryArray);
				$replace['patient_country_options'] = $this->build_select_option($countryArray, $row['country']);

				if($row['country']=='US') {
					$stateArray = array("" => "Choose State...");
					$stateArray = array_merge($stateArray,$this->config['state']);
					$replace['patient_state_options'] = $this->build_select_option($stateArray, $row['state']);
				}
				 
				else if($row['country']=='CAN') {
					$stateArray = array("" => "Choose Province...");
					$stateArray = array_merge($stateArray,$this->config['canada_state']);
					$replace['patient_state_options'] = $this->build_select_option($stateArray, $replace['patient_state']);
				}
			}

			/*
			 @date: 13th october 2011
			@description:
			Check for the E-health service
			Functionality added on 13th october as per UC 2.7.4
			It will check the E health service turn /off condition. If the E health service is off then checkbox will become disabled.
			If the E- health service is enabled then it will check the patient subscription. If the patient is subscribed then it will be in disable mode
			else in enable mode.
			*/

			$replace['clinicId'] = $clinicId;
			$ehsAction = $_REQUEST['action'];
			$replace['ehsAction'] = $ehsAction;

			$sqlEhealth = "SELECT subs_status,paymentType FROM clinic_subscription WHERE subs_clinic_id  = " . $clinicId;
			$queryEhealth = $this->execute_query($sqlEhealth);
			$numEhealthRow = $this->num_rows($queryEhealth);
			if($numEhealthRow!= '0') {
				$valueEhealth = $this->fetch_object($queryEhealth);
				if($valueEhealth->subs_status == 0) {
					$replace['ehsEnable'] = 'disabled';
					$replace['ehsDisable'] = '0';
				} else {
					//Condition to check patient EHS service

					//$replace['paymentType'] = $valueEhealth->paymentType;
					$sqEhscheck = "SELECT ehs FROM user where user_id = {$pid}";
					$queryEhs= $this->execute_query($sqEhscheck);
					$numqueryEhs = $this->num_rows($queryEhs);
					if($numqueryEhs!= '0') {
						$valueehsService = $this->fetch_object($queryEhs);
						if($valueehsService->ehs == 0 || $valueehsService->ehs == '') {
							$replace['ehsEnable'] = '0';
							$replace['subs_status'] = '0';
							$replace['subscrp_id'] = '0';
							$replace['ehsDisable'] = '1';
							 
						} else {

							$sqlPaymentSubscription="SELECT subs_status,subscription_title, user_subs_id,paymentType,subs_end_datetime, now() as todayDate
							FROM patient_subscription
							WHERE user_id={$pid} AND ((subs_status='1' AND subs_end_datetime > now())
							OR (subs_status='2' AND subs_end_datetime > now()))";

							$querysubscription = $this->execute_query($sqlPaymentSubscription);
							$numquerysubscription = $this->num_rows($querysubscription);

							if($numquerysubscription!= '0') {

								$valuesubscription = $this->fetch_object($querysubscription);
								//$subscription_end_date =  strtotime($valuesubscription->subs_end_datetime);
								//$todayDate = strtotime($valuesubscription->todayDate);
								$replace['subs_status'] = $valuesubscription->subs_status;
								$replace['subscrp_id'] = $valuesubscription->user_subs_id;
								//echo $valuesubscription->paymentType;
								/*if($valuesubscription->paymentType > 0) {
								if($subscription_end_date > $todayDate) {
								$replace['paymentType'] = $valuesubscription->paymentType;
								$replace['onetimePaymentStatus'] = '1';

								} else {
								$replace['paymentType'] = '0';
								$replace['onetimePaymentStatus'] = '0';
								}

								}*/
								$replace['paymentType'] = $valuesubscription->paymentType;
								//$replace['onetimePaymentStatus'] = '1';
							} else {

								$replace['subs_status'] = '0';
								$replace['subscrp_id'] = '0';
								//$replace['subs_status'] = '1';
								//$replace['subscrp_id'] = '1';
								//$replace['paymentType'] = $valuesubscription->paymentType;
								//$replace['onetimePaymentStatus'] = '0';
							}

							$replace['ehsEnable'] = 'checked';
							$replace['ehsDisable'] = '1';
						}
					}
				}
			} else {
				$replace['ehsEnable'] = 'disabled';
				$replace['ehsDisable'] = '0';
			}
			/* } else {
			 $replace['ehsEnable'] = 'disabled';
			$replace['ehsDisable'] = '0';
			}*/
			//End here


			$replace['body'] = $this->build_template($this->get_template("accountAdminEditPatient"),$replace);
		}

		$replace['get_satisfaction'] = $this->get_satisfaction();
		$this->output = $this->build_template($this->get_template("main"),$replace);

		$updateArr = $this->assignValueToArrayFields($this->form_array, '', '2');
	}
	/**
	 * Function to match database table fields name with form fields name
	 *
	 * @return String array
	 * @access public
	 */
	function populate_form_array()
	{
		$arr = array(
				'patient_title'             => 'name_title',
				'patient_first_name'         => 'name_first',
				'patient_last_name'         => 'name_last',
				'patient_suffix'             => 'name_suffix',
				'patient_email'             => 'username',
				'newPassword'                => 'password',
				'patient_cemail'            => '',
				'patient_phone1'            => 'phone1',
				'patient_phone2'            => 'phone2',
				'patient_address'            => 'address',
				'patient_address2'            => 'address2',
				'patient_city'                => 'city',
				'patient_state'                => 'state',
				'clinic_country'                => 'country',
				'patient_zip'                 => 'zip',
				'patient_status'            => 'status',
				'refering_physician'        => 'refering_physician',
				'mass_message_access'        => 'mass_message_access',
				'ehs'        => 'ehs'
					
		);
		return $arr;
	}
	/**
	 * View associated therapist in patient listing page.
	 *
	 * @return string - template file
	 * @access private
	 */
	function viewTherapistHead($check=false, $patientId = "", $clinicId = "" ){

		if( !( !empty($patientId) && is_numeric($patientId) ) ){
			$patientId = $this->value('patient_id');
		}
		if( !( !empty($clinicId) && is_numeric($clinicId) ) ){
			$clinicId = $this->value('clinic_id');
		}

		$privateKey = $this->config['private_key'];

		$sql = "SELECT *,
		AES_DECRYPT(UNHEX(u.name_title),'{$privateKey}') as name_title,
		AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}') as name_first,
		AES_DECRYPT(UNHEX(u.name_last),'{$privateKey}') as name_last

		FROM therapist_patient tp
		INNER JOIN user u ON tp.therapist_id = u.user_id and u.usertype_id ='2'
		WHERE tp.patient_id = '{$patientId}' AND tp.therapist_id
		IN (
		SELECT cu.user_id FROM clinic cl
		INNER JOIN clinic_user cu ON cu.clinic_id = cl.clinic_id
		AND (cl.clinic_id = '{$clinicId}' OR cl.parent_clinic_id ='{$clinicId}' )
		)";
		$result = @mysql_query($sql);
		if( $check == true ){
			$num_row = @mysql_num_rows($result);
			if( $num_row > 0 ){
				return true;
			}
			else{
				return false;
			}
		}
		if( @mysql_num_rows($result) > 0){
			$replace['listTherapist'] .= '<ol style="padding-left:50px;"  >';
			while( $row = @mysql_fetch_array($result)){
				$replace['listTherapist'] .= "<li>".$row['name_title'].$row['name_first'].' '.$row['name_last']."</li>";
			}
			$replace['listTherapist'] .= '</ol>';
		}
		else{
		}
		$replace['clinic_id'] = $this->value('clinic_id');
		$replace['body'] = $this->build_template($this->get_template("view_therapist"),$replace);
		$replace['browser_title'] = "Tx Xchange: Clinic List";

		$this->output = $this->build_template($this->get_template("main"),$replace);
	}
	/**
	 * Preserve query string parameters when action changed to sort list while using pagination.
	 *
	 * @param array $replace
	 * @param string $action
	 * @param string $queryStr
	 * @access public
	 */
	function setSortFields(&$replace,$action,$queryStr)
	{
		include_once("template/clinicDetail/userArray.php");

		$orderByClause = "";

		foreach ($sortColTblArray as $key => $value)
		{
			$strKey = $key.'Img';

			if ($this->value('sort') == $key)
			{
				if($this->value('order') == "ASC")
				{
					$replace[$key] = "action=".$action.$queryStr."&sort=".$key."&order=DESC"."&clinic_id=".$this->value('clinic_id');

					$replace[$strKey] = '&nbsp;<img src="images/sort_asc.gif">';
				}
				else
				{
					$replace[$key] = "action=".$action.$queryStr."&sort=".$key."&order=ASC"."&clinic_id=".$this->value('clinic_id');
					$replace[$strKey] = '&nbsp;<img src="images/sort_desc.gif">';
				}

				$replace['orderByClause'] = $value[$this->value('order')];

			}
			else
			{

				$replace[$key] = "action=".$action.$queryStr."&sort=".$key."&order=ASC"."&clinic_id=".$this->value('clinic_id');
				$replace[$strKey] = '';

			}

		}


	}

	/**
	 * Return number of article for given user.
	 *
	 */
	function article_count($id = ""){
		if(is_numeric($id)){
			$query = "SELECT count(*) FROM article WHERE (status = 1 or status = 2)  AND user_id = '".$id."' ";
			$result = @mysql_query($query);
			if($row = @mysql_fetch_array($result)){
				return $row[0];
			}
		}
		return 0;
	}
	/**
	 * Return number of plan for given user.
	 *
	 * @param integer userid.
	 * @return string - template file
	 * @access private
	 */
	function plan_count($id = ""){
		if(is_numeric($id)){
			$query = "select count(*) from plan p where p.user_id = '{$id}' and p.user_type = '2' and p.patient_id is null and p.status = 1 ";
			$result = @mysql_query($query);
			if($row = @mysql_fetch_array($result)){
				return $row[0];
			}
		}
		return 0;
	}
        
    /**
     * Function used to inactive primary clinic and clinics in the account.
     */
    function confirmStatusChangeClinicHead()
    {
        $clinic_id = $this->value('clinic_id');

        $parent_clinic_id = $this->get_field($clinic_id, "clinic", "parent_clinic_id");
        
        //user clicked 'yes' in the confirm dialog box
        if($this->value('confirm') == "yes")
        {
            if(is_numeric($clinic_id) && $clinic_id != '0')
            {
                $status = $this->get_field($clinic_id, "clinic", "status");
                if($status == "1")
                {
                    $newStatus = "2";
                }
                elseif($status == "2")
                {
                    $newStatus = "1";
                }
                
                if($status == "1")
                {
                    //txm-25 account unsubscribes
                    //check if the payment is made through online or offline mode
                    if($this->clinicMadeOnlinePayment($clinic_id))
                    {
                        //payment mode is online,  suspend the patients paypal profile
                        $this->unsubscribeClinicEHSPatients($clinic_id);
                        
                        //suspend provider paypal profile
                        $this->unsubscribeClinicEHSProviders($clinic_id);
                        
                        //make status updates in database.
                        $this->update("provider_subscription", array('unsubscribed' => 'yes'), "clinic_id = {$clinic_id}");
                    }
                    else
                    {
                        //remaining (previous) code for offline payments go here.

                        //payment mode is offline
                        //deactivate all the associated users
                        $sql = "select clinic_id from clinic where clinic_id = '{$clinic_id}' or parent_clinic_id = '{$clinic_id}'";
                        $sqlrow = @mysql_query($sql);
                        $numrow = mysql_num_rows($sqlrow);
                        if($numrow > 0)
                        {
                            while($res = mysql_fetch_array($sqlrow))
                            {
                                $this->changeClinicUserStatus($res['clinic_id'], $newStatus);
                            }
                        }
                        
                        //update the 'status' column in database table
                        $query = "update clinic set status = '{$newStatus}', trial_status=null, trial_date=null where clinic_id = '{$clinic_id}' or parent_clinic_id = '{$clinic_id}' ";
                    }


                    // fetch all account admins of clinic
                    $sqlforaccountadmins = "
                        SELECT 
                            `user`.`username`, 
                            `user`.`admin_access`  
                        FROM 
                            `clinic_user` 
                        INNER JOIN user ON (`user`.`user_id` = `clinic_user`.`user_id`)
                        WHERE 
                            `clinic_user`.`clinic_id` = '{$clinic_id}'
                             AND `user`.`admin_access` = '1'
                    ";

                    $resultsetaccountadmins = $this->execute_query($sqlforaccountadmins);
                    
                    if($this->num_rows($resultsetaccountadmins) > 0)
                    {
                        $viewtemplatevars = array();
                        $viewtemplatevars['images_url'] = $this->config['images_url'];

                        $emailtx = $this->config['email_tx'];
                        $subject = "You have unsubscribed from Tx Xchange";
                        $headers = 'MIME-Version: 1.0' . "\n";
                        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
                        $headers .= "From: <" . $emailtx . ">" . "\n";
                        $returnpath = "-f" . $emailtx;
                        
                        $accountadmins = $this->fetch_all_rows($resultsetaccountadmins);
                        foreach($accountadmins as $acadmindetails)
                        {
                            //inform all the account admins of current clinic about the unsubscribe {send email}
                            $to = "";
                            $to = trim($acadmindetails['username']); // this will be the email of user to whom notifications will be sent.

                            $message = "";
                            $message = $this->build_template($this->get_template("accountUnsubscribe"), $viewtemplatevars);

                            //email to all account admins of the clinic
                            @mail($to, $subject, $message, $headers, $returnpath);
                        }
                    }
                }
                else
                {
                    $this->changeClinicUserStatus($clinic_id, $newStatus);
                    $query = "update clinic set status = '{$newStatus}', trial_status=null, trial_date=null  where clinic_id = '{$clinic_id}' ";
                }
                @mysql_query($query);
            }
            
            $this->output = "<script language='javascript'>
			parent.parent.location.reload();
			//parent.parent.GB_hide();
			parent.parent.setTimeout('GB_CURRENT.hide()',1000);
			</script>";
            return;
        }
        
        $status = $this->get_field($clinic_id, "clinic", "status");
        if($status == "1")
        {
            $statusText = "unsubscribe";
        }
        elseif($status == "2")
        {
            $statusText = "activate";
        }

        //user clicked the 'Unsubscribe Account' option
        if($status == "1")
        {
            $replace['message'] = "You are about to {$statusText} from Tx Xchange. By doing so, you and your patients or clients will no longer be able to log in at the end of the current billing cycle. Are you sure you want to {$statusText}?";
        }
        else
        {
            $replace['message'] = "Are you sure you want to $statusText selected clinic?";
        }
        
        $replace['clinic_id'] = $this->value('clinic_id');
        $replace['body'] = $this->build_template($this->get_template("confirmStatusChangeClinic"), $replace);
        $replace['browser_title'] = "Tx Xchange: Clinic List";
        $replace['get_satisfaction'] = $this->get_satisfaction();
        $this->output = $this->build_template($this->get_template("main"), $replace);
    }

	/**
	 * Function to change the status of Clinic user
	 * @param integer $clinicId,$status
	 * @return string - template file
	 * @access private
	 */
	function changeClinicUserStatus($clinicId,$status){
		$date=date("Y-m-d H:i:s");
		$sql="select clinic_user.user_id  as user_id from clinic_user,user where clinic_user.user_id=user.user_id and clinic_user.clinic_id='{$clinicId}' and user.usertype_id=2";
		$res=@mysql_query($sql);
		$numrow=mysql_num_rows($res);
		if($numrow > 0){
			while($row=mysql_fetch_array($res)){
				$sqluser="update user set trial_status=null ,free_trial_date=null,status='{$status}' where user_id=".$row['user_id'];
				@mysql_query($sqluser);

			}

		}
		return;

	}

    /**
     * This function displays list clinics in an account.
     */
    function accountAdminClinicList()
    {
        $replace = array();
        /* code for show telespine menu on list */
        $replace['showmenu']="no";
        if($this->clinicInfo('clinic_id')==$this->config['telespineid']){
            $replace['showmenu']="yes";
        }
        /* code end */
        $clinicId = $this->clinicInfo("clinic_id");
        if( !is_numeric($clinicId) || $clinicId == 0 )
        {
            header("location:index.php?action=logout");
            exit();
        }
        
        $replace['updatecardmessage'] = '';
        if($_REQUEST['status'] == 1)
        {
            $updatecardmessage = "<script>GB_showCenter('successfully updated your credit card', '/index.php?action=updatecardmessage',240,500);</script>";
            $replace['updatecardmessage'] = $updatecardmessage;
        }

        $userInfo = $this->userInfo();
        $cli_type = $this->getClinicDetails($this->userInfo('user_id'));
        
        //if($cli_type['clinic_type']==4){
        $replace['shopshow']=1;
        /* }else{
         $replace['shopshow']='0';
        }*/
        // Retian page value.
        $arr = array(
            'clinic_id' => $clinicId
        );
        
        $msg = $this->value('msg');
        if( !empty($msg) )
        {
            $replace['error'] = $this->errorClinicListModule($msg);
        }
        $this->set_session_page($arr);
        //Get  acccount id
        if($this->value('sort') != "")
        {
            if($this->value('order') == 'desc' )
            {
                $orderby = " order by {$this->value('sort')} desc ";
            }
            else
            {
                $orderby = " order by {$this->value('sort')} ";
            }
        }
        else
        {
            $orderby = " order by clinic_name ";
        }

        $searchString = $this->value('search');
        if( !empty($searchString) )
        {
            $searchString = $this->value('search');
            $searchWhere = " and clinic_name like '%{$this->value('search')}%' ";
        }
        
        //Get the clinic list
        if( is_numeric($clinicId) )
        {
            /** TXm-25, sql query will be changed if clinic made online payment for subsciption **/
            if($this->clinicMadeOnlinePayment($clinicId))
            {
                $sqlUser = "
                    select 
                        clinic.*,
                        provider_subscription.unsubscribed
                    from 
                        clinic 
                    left join provider_subscription on (clinic.clinic_id = provider_subscription.clinic_id)
                    where 
                        (clinic.status = 1 or clinic.status = 2) 
                        and (clinic.parent_clinic_id = '{$clinicId}' or clinic.clinic_id = '{$clinicId}')
                    {$searchWhere} {$orderby}
                ";
            }
            else
            {
                $sqlUser = "select * from clinic where (status = 1 or status = 2) and (parent_clinic_id = '{$clinicId}' or clinic_id = '{$clinicId}') {$searchWhere} {$orderby}";
            }
        }
        
        $link = $this->pagination($rows = 0,$sqlUser,'accountAdminClinicList',$searchString);
        $replace['link'] = $link['nav'];
        $result = $link['result'];

        if($this->num_rows($result)!= 0)
        {
            $replace['clinicTblHead'] = $this->build_template($this->get_template("clinicTblHead"),$replace);
            while($row = $this->fetch_array($result))
            {
                $row['style'] = ($c++%2)?"line1":"line2";
                if( $clinicId == $row['clinic_id'] )
                {
                    $row['style'] = "line3";
                }
                
                /** TXM-25 **/
                if($this->clinicMadeOnlinePayment($row['clinic_id']))
                {
                    $clinicstatus = ($row['unsubscribed'] == 'yes') ? 'Unsubscribed' : 'Subscribed';
                    $statustext = ($this->get_status($row['status'])=='Active' && $row['unsubscribed'] == 'no') ? "Unsubscribe Account" : "Activate Account";
                }
                else
                {
                    $clinicstatus = $this->get_status($row['status']);
                    $statustext = $this->get_status($row['status'])=='Active'?"Unsubscribe Account":"Activate Account";
                }
                
                $row['status'] = $clinicstatus;
                $row['status_text'] = $statustext;
                /** TXM-25 ends **/

                if($row['trial_status']==1 and ($row['trial_date']!='' or !empty($row['trial_date'])))
                {
                    $row['status'] = 'Trial';
                }
                if($row['country']=='US')
                    $row['state'] = $this->config['state'][$row['state']];
                
                if($row['country']=='CAN')
                    $row['state'] = $this->config['canada_state'][$row['state']];

                $row['creationDate'] = $this->formatDate($row['creationDate']);
                $replace['clinicTblRecord'] .= $this->build_template($this->get_template("clinicTblRecord"),$row);
            }
        }


        $clinicListHead = array(
                        'clinic_name' => 'Clinic Name',
                        'city' => 'City',
                        'state' => 'State',
                        'status' => 'Status',
                        'creationDate' => 'Created On'
        );

        $replace['clinicTblHead'] = $this->build_template($this->get_template("clinicTblHead"),$this->table_heading($clinicListHead,"clinic_name"));
        $replace['clinic_id'] = $clinicId;
        $url_array = $this->tab_url();
        $replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$url_array);
        $replace['location'] = $url_array['location'];
        $replace['header'] = $this->build_template($this->get_template("header"));
        $replace['footer'] = $this->build_template($this->get_template("footer"));
        $replace['sidebar'] = $this->sidebar();
        $replace['body'] = $this->build_template($this->get_template("clinicTemplate"),$replace);
        $replace['browser_title'] = "Tx Xchange: Clinic List";
        $replace['get_satisfaction'] = $this->get_satisfaction();
        $this->output = $this->build_template($this->get_template("main"),$replace);
    }



	/**
	 * Create a new Clinic for head account.
	 *
	 * @access public
	 */
	function addEditClinicInHeadAccount()
	{
		 
		$replace = array();
                 /* code for show telespine menu on list */
        $replace['showmenu']="no";
        if($this->clinicInfo('clinic_id')==$this->config['telespineid']){
            $replace['showmenu']="yes";
        }
        /* code end */
		$option = $this->value('option');
		$clinic_id = $this->value("clinic_id");
		$userInfo = $this->userInfo();
		$cli_type=$this->getClinicDetails($this->userInfo('user_id'));
		//if($cli_type['clinic_type']==4){
		$replace['shopshow']=1;
		/*}else{
		 $replace['shopshow']='0';
		}*/

		$replace['header'] = $this->build_template($this->get_template("header"));
		$replace['sidebar'] = $this->sidebar();
		$replace['clinic_id'] = $clinic_id;
		$replace['subaction'] = $this->value('subaction');
		if( $replace['subaction'] == "edit" ){
			$replace['heading1'] = 'EDIT CLINIC';
			$replace['heading'] = 'Edit Clinic';
			$replace['browser_title'] = 'Tx Xchange: Edit Clinic';
		}
		elseif($replace['subaction'] == "add"){
			$replace['heading1'] = 'ADD CLINIC';
			$replace['heading'] = 'Add New Clinic';
			$replace['browser_title'] = 'Tx Xchange: Add Clinic';
		}
		$this->form_array = $this->populate_clinic_form_array();
		// When user modify the clinic details
		if( $replace['subaction'] == "add" && (empty($option) ))
		{
			// Populate BLANK FORM fields
			$this->assignValueToArrayFields($this->form_array, '', '1', &$replace);
		}
		elseif( $replace['subaction'] == "edit" && empty($option) ){
			//Populate FORM fields from database.
			// echo"gggggggggggg";
			$query = "select * from clinic where clinic_id='".$clinic_id."'";
			$rs = $this->execute_query($query);
			$row = $this->populate_array($rs);
			//print_r( $row);
			// Fetch Replace array from row
			// populate FormArray from FieldArray
			 
			$this->assignValueToArrayFields($this->form_array, $row, '1', &$replace);
		}
		else{

			if($option == 'update'){
				$this->validateClinicForm();
				if($this->error == ""){
					//  Populate FieldArray from FormArray
					if(!empty($clinic_id) && $replace['subaction'] == "edit"){
						$updateArr = $this->assignValueToArrayFields($this->form_array, '', '2', '', 'insert');
						$where = " clinic_id = '".$clinic_id."'";
						$result = $this->update('clinic',$updateArr,$where);
						$location = $this->redirectUrl("accountAdminClinicList").'&msg=clinic_updated';
						header("location:{$location}");
						exit();
					}elseif(!empty($clinic_id) && $replace['subaction'] == "add"){
						$insertArr = $this->assignValueToArrayFields($this->form_array, '', '2', '', 'insert');
						$insertArr['status'] = '1';
						$insertArr['creationDate'] = date("Y-m-d H:i:s");
						if( is_numeric($clinic_id) && $clinic_id != 0 ){
							$insertArr['parent_clinic_id'] = $clinic_id;
						}
						$result = $this->insert('clinic',$insertArr);
						$location = $this->redirectUrl("accountAdminClinicList").'&msg=clinic_added';
						header("location:{$location}");
						exit();
					}

				}else{
					 
					 
					 
					//  bug id :28727,,, but changes are not reflecting in the page so i wiill take this bug on last
					 
					 
					//Show errors and populate FORM fields from $_POST.
					$this->assignValueToArrayFields($this->form_array, '', '', &$replace, '0');
					$replace['error'] = $this->error;
				}
			}
		}

		// creating state drop down element
		$stateArray = array("" => "Choose State...");
		$stateArray = array_merge($stateArray,$this->config['state']);
		$replace['stateOptions'] = $this->build_select_option($stateArray, $row['clinic_state']);
		$url_array = $this->tab_url();
		$replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$url_array);
		 
		$countryArray = $this->config['country'];
		$replace['country']=implode("','",$countryArray);




		if($_REQUEST['clinic_country']!='' && isset($_REQUEST['clinic_country']))
		{
			// echo"hi";
			 
			//print_r($_REQUEST);
			$countryArray = $this->config['country'];
			$replace['country']=implode("','",$countryArray);
			$replace['patient_country_options'] = $this->build_select_option($countryArray, $_REQUEST['clinic_country']);



			 
			if($_REQUEST['clinic_country']=='US') {
				$stateArray = array("" => "Choose State...");
				$stateArray = array_merge($stateArray,$this->config['state']);
				$replace['stateOptions'] = $this->build_select_option($stateArray, $_REQUEST['clinic_state']);
			}
			 
			else if($_REQUEST['clinic_country']=='CAN') {
				$stateArray = array("" => "Choose Province...");
				$stateArray = array_merge($stateArray,$this->config['canada_state']);
				$replace['stateOptions'] = $this->build_select_option($stateArray, $_REQUEST['clinic_state']);
			}
		}

		else {
			$replace['patient_country_options'] = $this->build_select_option($countryArray, $row['country']);



			 
			if($row['country']=='US') {
				$stateArray = array("" => "Choose State...");
				$stateArray = array_merge($stateArray,$this->config['state']);
				$replace['stateOptions'] = $this->build_select_option($stateArray, $replace['clinic_state']);
			}
			 
			else if($row['country']=='CAN') {
				$stateArray = array("" => "Choose Province...");
				$stateArray = array_merge($stateArray,$this->config['canada_state']);
				$replace['stateOptions'] = $this->build_select_option($stateArray, $replace['clinic_state']);
			}

		}
		$replace['body'] = $this->build_template($this->get_template("addClinicInAccount"),$replace);
		$replace['get_satisfaction'] = $this->get_satisfaction();
		$this->output = $this->build_template($this->get_template("main"),$replace);
	}
	/**
	 * Function to validate the patient credentials like name address email etc.
	 *
	 * @access public
	 */
	function validateClinicForm()
	{

		// creating validation object
		$objValidationSet = new ValidationSet();

		// validate clinic name
		$objValidationSet->addValidator(new StringMinLengthValidator('clinic_name', 1, "Clinic Name cannot be empty",$this->value('clinic_name')));
		//$objValidationSet->addValidator(new AlphanumericOnlyValidator('clinic_name', null,"Please enter valid characters in Clinic Name",$this->value('clinic_name')));

		// validating clinic address line 1
		// $objValidationSet->addValidator(new StringMinMaxLengthValidator('clinic_address', 0,50, "Address cannot be more than 50 characters",$this->value('clinic_address')));
		$objValidationSet->addValidator(new StringMinLengthValidator('clinic_address', 1,"Address cannot be empty",$this->value('clinic_address')));
		// $objValidationSet->addValidator(new AlphanumericOnlyValidator('clinic_address', null,"Please enter valid characters in address",$this->value('clinic_address')));

		// validating clinic address line 2
		$objValidationSet->addValidator(new StringMinMaxLengthValidator('clinic_address2',0,50, "Address line 2 cannot be more than 50 characters",$this->value('clinic_address2')));
		//$objValidationSet->addValidator(new AlphanumericOnlyValidator('clinic_address2', null,"Please enter valid characters in address line 2",$this->value('clinic_address2')));

		// validating city name
		//$objValidationSet->addValidator(new StringMinLengthValidator('clinic_city', 1,"City cannot be empty",$this->value('clinic_city')));
		$objValidationSet->addValidator(new StringMinMaxLengthValidator('clinic_city',1,20, "City name cannot be more than 20 characters",$this->value('clinic_city')));
		//$objValidationSet->addValidator(new AlphabeticalOnlyValidator('clinic_city', array(' ',',','.'),"Please enter valid characters in city",$this->value('clinic_city')));

		// validating state name
		$objValidationSet->addValidator(new StringMinLengthValidator('clinic_state', 2,"State cannot be empty",$this->value('clinic_state')));

		// validating zip code
		/*$objValidationSet->addValidator(new AlphanumericOnlyValidator('clinic_zip', array(' ') ,"Please enter only alphanumeric values in zip",$this->value('clinic_zip')));
		$objValidationSet->addValidator(new StringMinMaxLengthValidator('clinic_zip', 5, 7, "Zip code should be 5 to 7 alphanumeric characters",$this->value('clinic_zip')));*/
		// echo $this->value('clinic_country');
		/*if($this->value('clinic_country')=='CAN'){
		$objValidationSet->addValidator(new  AlphanumericOnlyValidator('clinic_zip', array(' '), "Zip code should be of alphanumeric characters only",$this->value('clinic_zip')));
		$objValidationSet->addValidator(new  StringMinMaxLengthValidator('clinic_zip', 6,7, "Zip code should be  6 to 7 alphanumeric characters only",$this->value('clinic_zip')));
		}else{
		$objValidationSet->addValidator(new  NumericOnlyValidator('clinic_zip', null, "Zip code should be of numeric characters only",$this->value('clinic_zip')));
		$objValidationSet->addValidator(new  StringMinMaxLengthValidator('clinic_zip', 5,5, "Zip code should be  5 numeric characters only",$this->value('clinic_zip')));
		}*/
		$objValidationSet->addValidator(new  StringMinMaxLengthValidator('clinic_zip', 5,7, "Zip code should be  5 to 7 characters only",$this->value('clinic_zip')));


		// validating phone
		$objValidationSet->addValidator(new StringMinLengthValidator('clinic_phone',10, "Phone must have 10 digit number ",$this->value('clinic_phone')));
		$objValidationSet->addValidator(new NumericOnlyValidator('clinic_phone', array('-','.'),"Please enter valid number like 3035626737 or 303.562.6737 or 303-562-6737",$this->value('clinic_phone')));
		$str='';
		if(ereg("^[0-9]{3}-[0-9]{3}-[0-9]{4}$", $this->value('clinic_phone'))){
			$str='done';
		}elseif(ereg("^[0-9]{3}\.[0-9]{3}\.[0-9]{4}$", $this->value('clinic_phone')))
		{
			$str='done';
		}elseif(ereg("^[0-9]{10}$", $this->value('clinic_phone')) and strlen($this->value('clinic_phone'))==10){
			$str='done';
		}
		if($str!='done'){
			$objValidationErr = new ValidationError('clinic_phone',"Please enter 10 valid number like 3035626737 or 303.562.6737 or 303-562-6737.");
			$objValidationSet->addValidationError($objValidationErr);
		}



		/*
		 Check clinic name for duplicacy
		*/

		/*  $clinic_id = $this->value('clinic_id');

		$clinic_name = $this->value('clinic_name');
		$queryClinic = "SELECT clinic_id FROM clinic WHERE clinic_name = '".$clinic_name."' AND status <> 3 AND clinic_id <> ".$clinic_id;


		$result = $this->execute_query($queryClinic);

		//if record found that means clinic name not unique else it is unique
		if ($this->num_rows($result) != 0)
		{
		$objValidationErr = new ValidationError('clinic_name',"Clinic Name : exists in the system. Please choose another.");
		$objValidationSet->addValidationError($objValidationErr);
		}     */


		/* End of checking for duplicacy */



		$objValidationSet->validate();

		if ($objValidationSet->hasErrors())
		{
			/*
			 $clinic_zip = $this->value('clinic_zip');
			if(!empty($clinic_zip)){
			$arrayFields = array("clinic_name","clinic_address","clinic_address2","clinic_city","clinic_state", "clinic_zip");
			}else{
			$arrayFields = array("clinic_name","clinic_address","clinic_address2","clinic_city", "clinic_state");
			}
			*/

			$arrayFields = array('clinic_name','clinic_address','clinic_address2','clinic_city','clinic_state','clinic_country', 'clinic_zip','clinic_phone');

			for($i=0;$i<count($arrayFields);++$i)
			{
				$errorMsg = $objValidationSet->getErrorByFieldName($arrayFields[$i]);

				if ($errorMsg != "")
				{
					$this->error = $errorMsg."<br>";
					break;
				}
			}
		}
		else
		{
			$this->error = "";
		}

	}
	/**
	 * Function to match database table fields name with form fields name
	 *
	 * @return String array
	 * @access public
	 */
	function populate_clinic_form_array()
	{
		$arr = array(
				'clinic_name'             => 'clinic_name',
				'clinic_address'         => 'address',
				'clinic_address2'         => 'address2',
				'clinic_city'             => 'city',
				'clinic_state'             => 'state',
				'clinic_zip'             => 'zip',
				'clinic_phone'          => 'phone',
				'clinic_country'          => 'country',
		);
		return $arr;
	}
	/**
	 * Create a new user (therapist or account admin) for clinic.
	 *
	 * @access public
	 */
	function addUserHead()
	{

		$replace = array();
                  /* code for show telespine menu on list */
        $replace['showmenu']="no";
        if($this->clinicInfo('clinic_id')==$this->config['telespineid']){
            $replace['showmenu']="yes";
        }
        /* code end */
		$userInfo = $this->userInfo();
		$userInfo = $this->userInfo();
		$cli_type=$this->getClinicDetails($this->userInfo('user_id'));
		// if($cli_type['clinic_type']==4){
		$replace['shopshow']=1;
		/*}else{
		 $replace['shopshow']='0';
		}*/
		$userId = $userInfo['user_id'];
		if (!$userInfo)
		{
			header("location:index.php");
			exit();
		}
		else
		{
			//check if form has been submitted or not

			include_once("template/headAccountAdminUser/userArray.php");
			$this->formArray = $formArray;
			$clinicId = $this->value('clinic_id');
			if (isset($_POST['submitted_add']) && $_POST['submitted_add'] == 'Add User' && is_numeric($clinicId) && $clinicId > 0 )
			{
				//form submitted check for validation
				$this->validateFormUser("Add");
				if($this->error == "")
				{
					//Form validated, no errors
					//go ahead store the values in db

					$admin_access = isset($_POST['adminAccess'])? 1 : 0;
					$therapist_access = isset($_POST['therapistAccess'])? 1 : 0;

					$insertArr = array(
							'usertype_id'=> 2,
							'name_title'=>$this->value('provider_title'),
							'username' =>$this->value('username'),
							'password' => $this->value('new_password'),
							'name_first' => $this->value('name_first'),
							'name_last' => $this->value('name_last'),
							'address' => $this->value('address'),
							'address2' => $this->value('address2'),
							'city' => $this->value('city'),
							'state' => $this->value('state'),
							'country' => $this->value('clinic_country'),
							'zip' => $this->value('zip'),
							'phone1' => $this->value('phone1'),
							/*'phone2' => $this->value('phone2'),*/
							'fax' => $this->value('fax'),
							'creation_date' => date('Y-m-d H:i:s',time()),
							'modified' => date('Y-m-d H:i:s',time()),
							'admin_access' => $admin_access,
							'therapist_access' => $therapist_access,
							'status'=> 1,
							//'free_trial_date'=>date('Y-m-d H:i:s',time()),
							//'trial_status'=>1,
							'practitioner_type'=>$this->value('practitioner_type')
					);
					// Encrypt data
					//$encrypt_field = array('address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2','fax');
					//$insertArr = $this->encrypt_field($insertArr, $encrypt_field);
					$this->check_send_invitation($this->value('username'),$this->value('name_first'),$this->value('name_last'),$this->value('practitioner_type'));
					$result = $this->insert('user',$insertArr);
					$newlyCreatedUserId = $this->insert_id();

					//Also associate this new user with the clinic so insert the record in clinic_user table
					$insertArr = array(
							'user_id'=>$newlyCreatedUserId,
							'clinic_id' =>$clinicId
					);
					$result = $this->insert('clinic_user',$insertArr);
					// copy publish plan
					if(is_numeric($clinicId)){
						$this->copyPublishPlan($newlyCreatedUserId, $clinicId);
					}
					// Also handle the copying of article and treatment template for user if user has the therapist privelege
					{//usertype = therapist
						if    (($therapist_access == 1) || ($admin_access == 1))
						{
							//Article Block
							{
								// Original Query : New User not getting the Articles created by Account Admin
								//$queryArticle = "SELECT article.* FROM article INNER JOIN user ON article.user_id = user.user_id
								//                    WHERE user.usertype_id = 4 AND user.status = 1 AND article.status != 3";
								$queryArticle = "SELECT article.* FROM article INNER JOIN user ON article.user_id = user.user_id
								WHERE (user.usertype_id = 4 OR (clinic_id= '{$clinicId}' AND parent_article_id IS NULL )) AND user.status = 1 AND article.status != 3";
								$resultArticle = $this->execute_query($queryArticle);
								// now copy article data with user_id as $newlyCreatedUserId
								if($this->num_rows($resultArticle)!= 0)
								{
									while($row = $this->fetch_array($resultArticle))
									{

										$insertArr = array(
												'article_name'=>$row['article_name'],
												'headline' =>$row['headline'],
												'article_type' => $row['article_type'],
												'link_url' => $row['link_url'],
												'file_url' => $row['file_url'],
												'user_id' => $newlyCreatedUserId,
												'creation_date' => $row['creation_date'],
												'modified'=>$row['modified'],
												'status'=> $row['status'],
												'parent_article_id'    => $row['article_id']
										);


										$result = $this->insert('article',$insertArr);
										$newArticleId = $this->insert_id();



										if (!empty($row['file_url']))
										{
											/*$fileDest = $_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url']. $newArticleId . '/';
											 if(!file_exists($fileDest))
											 {
											mkdir($fileDest);
											chmod($fileDest, 0777);
											}


											$fileDestPath = $_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url']. $newArticleId . '/'.$row['file_url'];

											$fileSourcePath = $_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url'].$row['article_id'] . '/'.$row['file_url'];

											copy($fileSourcePath,$fileDestPath);*/
											 
											$fileDest = $this->check_article_path($newArticleId);
											$fileDestPath = $this->get_article_path($newArticleId);
											$fileSourcePath = $this->get_article_path($row['article_id']);
											copy($fileSourcePath,$fileDestPath);
											$this->update_article_path($newArticleId);
										}


									}
								}
							}

							//Plan Block
							{
								$queryPlan = "SELECT plan.* FROM plan INNER JOIN user ON plan.user_id = user.user_id
								WHERE user.usertype_id = 4 AND user.status = 1 AND plan.patient_id IS NULL AND plan.status = 1 AND plan.is_public = 1";
								$resultPlan = $this->execute_query($queryPlan);

								$plan_id = 0;

								if($this->num_rows($resultPlan)!= 0)
								{
									while($row = $this->fetch_array($resultPlan))
									{

										$insertArr = array(
												'plan_name'=>$row['plan_name'],
												'parent_template_id' =>NULL,
												'user_id' => $newlyCreatedUserId,
												'patient_id' => NULL,
												'user_type' => 2,
												'is_public' => NULL,
												'creation_date' => $row['creation_date'],
												'status'=> $row['status']
										);

										$result = $this->insert('plan',$insertArr);

										$plan_id = $row['plan_id'];

										//Newly plan id
										$newlyPlanId = $this->insert_id();



										{//Plan_Article Block

											/*
											 Also for this particular DBrow fetch from plan table have the plan_id
											now find this plan_id in table plan_article    get the rows for this plan_id
											insert rows into plan_article with all data same except replace plan_id with newly created plan_id

											*/

											$queryPlanArticle = "SELECT * FROM plan_article WHERE status = 1 AND plan_id = ".$plan_id ;
											$resultPlanArticle = $this->execute_query($queryPlanArticle);



											if($this->num_rows($resultPlanArticle)!= 0)
											{
												while($row = $this->fetch_array($resultPlanArticle))
												{

													$insertArr = array(
															'plan_id'=> $newlyPlanId,
															'article_id' => $row['article_id'],
															'creation_date' => date('Y-m-d H:i:s',time()),
															'status'=> $row['status']
													);

													$result = $this->insert('plan_article',$insertArr);

												}
											}
										}

										{//Plan_Treatment Block

											/*
											 Also for this particular DBrow fetch from plan table have the plan_id
											now find this plan_id in table plan_treatment    get the rows for this plan_id
											insert rows into plan_treatment with all data same except replace plan_id with newly created plan_id

											*/

											$queryPlanTreatment = "SELECT * FROM plan_treatment WHERE plan_id = ".$plan_id ;
											$resultPlanTreatment = $this->execute_query($queryPlanTreatment);



											if($this->num_rows($resultPlanTreatment)!= 0)
											{
												while($row = $this->fetch_array($resultPlanTreatment))
												{

													$row['sets'] = (empty($row['sets']))? "":$row['sets'];
													$row['reps'] = (empty($row['reps']))? "":$row['reps'];
													$row['hold'] = (empty($row['hold']))? "":$row['hold'];
													$row['lrb'] = (empty($row['lrb']))? "":$row['lrb'];
													$row['treatment_order'] = (empty($row['treatment_order']))? "":$row['treatment_order'];

													$insertArr = array(
															'plan_id'=> $newlyPlanId,
															'treatment_id' => $row['treatment_id'],
															'instruction' => $row['instruction'],
															'sets'=> $row['sets'],
															'reps'=> $row['reps'],
															'hold' => $row['hold'],
															'benefit'=> $row['benefit'],
															'lrb'=> $row['lrb'],
															'treatment_order' => $row['treatment_order'],
															'creation_date' => date('Y-m-d H:i:s',time())
													);

													$result = $this->insert('plan_treatment',$insertArr);

												} // end of while
											} // end of if block of result of plan_treatment
										}//End of plan treatment block
									} //While block for result rows from plan table
								}//End of if block of result rows from plan table

							}//End of plan block

							//Also update the user table with expiry date for this user as therapist access is given to him for the first time

							$updateArr = array(

									'expiry_date' => date('Y-m-d H:i:s',mktime(date('H'), date('i'), date('s'), date("m")  , date("d")+15, date("Y")))

							);

							$where = " user_id = ".$newlyCreatedUserId;

							$result = $this->update('user',$updateArr,$where);

						}//end of if (access privilege == therapist)

					}//End of usertype = therapist


					//send mail that user created successfully
					{
						//have the HTML content
						$clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
						if($clinic_channel==1){
							$business_url=$this->config['business_tx'];
							$support_email=$this->config['email_tx'];
						}else{
							$business_url=$this->config['business_wx'];
							$support_email=$this->config['email_wx'];
						}
						$replace['business_url'] = $business_url;
						$replace['support_email'] = $support_email;
						$fullName = $this->value('name_first')." ".$this->value('name_last');
						$replace['username'] = $this->value('username');
						$replace['password'] = $this->value('new_password');
						$replace['url'] = $this->config['url'];
						$replace['images_url'] = $this->config['images_url'];

						 
						$clinic_type = $this->getUserClinicType($newlyCreatedUserId);
						$queryUserType = "SELECT * FROM user WHERE status = 1 AND user_id = ".$newlyCreatedUserId;
						$result = $this->execute_query($queryUserType);
							
						if($this->num_rows($result)!= 0)
						{
							$rowID = $this->fetch_array($result);
							$user_Admin = $rowID['admin_access'];
							$user_Therapist = $rowID['therapist_access'];

								
							if( $clinic_channel == 1){
								if($user_Therapist == 1 && $user_Admin != 1){
									$subject = "Your Tx Xchange Account Information";
									$message = $this->build_template($this->get_template("newUserMailContent_plpto"),$replace);
								}elseif($user_Admin == 1){
									$subject = "Tx Xchange Account Information";
									$message = $this->build_template($this->get_template("newUserAdminMailContent_plpto"),$replace);
								}
							}else{
								if($user_Therapist == 1 && $user_Admin != 1){
									$subject = "Your Wholemedx Account Information";
									$message = $this->build_template($this->get_template("newUserMailContent_wx"),$replace);
								}elseif($user_Admin == 1){
									$subject = "Wholemedx Account Information";
									$message = $this->build_template($this->get_template("newUserAdminMailContent_wx"),$replace);
								}
							}
						}
						/*if( $clinic_type == 'plpto'){
						 $subject = "Your Login Details For Tx Xchange";
						$message = $this->build_template($this->get_template("newUserMailContent_plpto"),$replace);
						}
						elseif( $clinic_type == 'elpto' ){
						$subject = "Your Login Details For LivePTOnline.com";
						$message = $this->build_template($this->get_template("newUserMailContent"),$replace);
						}*/

						//$message = $this->build_template($this->get_template("newUserMailContent"),$replace);
						//$message = "User Name: ".$_POST['email_address']."<br> Password : ".$userPass;

						$to = $fullName.'<'.$this->value('username').'>';

						// To send HTML mail, the Content-type header must be set
						$headers  = 'MIME-Version: 1.0' . "\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";

						// Additional headers
						//$headers .= 'To: '.$fullName.'<'.$_POST['email_address'].'>'. "\n";
						if( $clinic_channel == 1){
							$headers .= "From:  <".$this->config['email_tx'].">" . "\n";
							$returnpath = "-f".$this->config['email_tx'];
						}else{
							$headers .= "From: <".$this->config['email_wx'].">" . "\n";
							$returnpath = '-f'.$this->config['email_wx'];
						}
						//$headers .= 'From: support@txxchange.com' . "\n";
						//$headers .= 'Cc: example@example.com' . "\n";
						//$headers .= 'Bcc: example@example.com' . "\n";
						//$returnpath = '-fsupport@txxchange.com';
						// Mail it
						@mail($to, $subject, $message, $headers, $returnpath);

					}


					// redirect to list of subscriber
					//header("location:index.php?action=user_listing&clinic_id=".$this->value('clinic_id')."&msg=success");
					$location = $this->redirectUrl('userListingHead').'&msg=user_added';
					header("location:$location");
					exit();

				}
				else
				{

					$replace = $this->fillForm($this->formArray,$_POST);

					$replace['error'] = $this->error;

					//Also the state
					$selectedState = ($this->value('state') == "")? "": $this->value('state');

					if (isset($_POST['therapisAccess']))
					{
						$replace['checkedTherapist'] = "checked";
					}
					else
					{
						$replace['checkedTherapist'] = "";
					}

					if (isset($_POST['adminAccess']))
					{
						$replace['checkedAdmin'] = "checked";
					}
					else
					{
						$replace['checkedAdmin'] = "";
					}

					if (isset($_POST['clinicAddress']))
					{
						$replace['checkedAddress'] = "checked";
					}
					else
					{
						$replace['checkedAddress'] = "";
					}




				}

			}
			else
			{
				//first time form
				$replace = $this->fillForm($this->formArray);
				$selectedState = "";

				$replace['checkedTherapist'] = "checked";
				$replace['checkedAdmin'] = "";
				$replace['checkedAddress'] = "";

			}
			$selectedPractitioner=$this->value('practitioner_type');
			$PractitionerArray = array("" => "Choose Provider...");
			$query="select * from practitioner where   status =1";
			$result=$this->execute_query($query);
			while($rowPractitioner=$this->fetch_array($result)){
				$PractitionerArray[$rowPractitioner['practitioner_id']]=$rowPractitioner['name'];
			}
			$replace['PractitionerOptions'] = 	$this->build_select_option($PractitionerArray, $selectedPractitioner);


			$stateArray = array("" => "Choose State...");

			$stateArray = array_merge($stateArray,$this->config['state']);

			$replace['stateOptions'] =     $this->build_select_option($stateArray,$selectedState);
			$replace['clinic_id'] = $this->value('clinic_id');
			$url_array = $this->tab_url();
			$replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$url_array);
			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['footer'] = $this->build_template($this->get_template("footer"));

			$replace['sidebar'] = $this->sidebar();
			$countryArray = $this->config['country'];
			 
			if( isset($_REQUEST['clinic_country']) && $_REQUEST['clinic_country']!='')

			{
				 
				$replace['country']=implode("','",$countryArray);
				$replace['patient_country_options'] = $this->build_select_option($countryArray, $_REQUEST['clinic_country']);



				 
				if($_REQUEST['clinic_country']=='US') {
					$stateArray = array("" => "Choose State...");
					$stateArray = array_merge($stateArray,$this->config['state']);
					$replace['stateOptions'] = $this->build_select_option($stateArray, $_REQUEST['state']);
				}
				 
				else if($_REQUEST['clinic_country']=='CAN') {
					$stateArray = array("" => "Choose Province...");
					$stateArray = array_merge($stateArray,$this->config['canada_state']);
					$replace['stateOptions'] = $this->build_select_option($stateArray, $_REQUEST['state']);
				}
				 
				 
				 
			}

			if(!isset($_REQUEST['clinic_country']))
			{
				$replace['country']=implode("','",$countryArray);
				$replace['patient_country_options'] = $this->build_select_option($countryArray, $replace['country']);



				 
				if($row['country']=='US') {
					$stateArray = array("" => "Choose State...");
					$stateArray = array_merge($stateArray,$this->config['state']);
					$replace['stateOptions'] = $this->build_select_option($stateArray, $replace['clinic_state']);
				}
				 
				else if($row['country']=='CAN') {
					$stateArray = array("" => "Choose Province...");
					$stateArray = array_merge($stateArray,$this->config['canada_state']);
					$replace['stateOptions'] = $this->build_select_option($stateArray, $replace['clinic_state']);
				}

			}
			 
			// calling template
			$replace['body'] = $this->build_template($this->get_template("createUser"),$replace);
			$replace['browser_title'] = "Tx Xchange: Add User";
			$replace['get_satisfaction'] = $this->get_satisfaction();
			$replace['provider_title'] = $this->build_select_option(array_merge(array('' => 'Choose...'), $this->config['title']), $replace['patient_title']);
			$this->output = $this->build_template($this->get_template("main"),$replace);

		}
	}
	/**
	 * validating form fields of page.
	 *
	 * @param string $formType
	 * @param boolean $uniqueId
	 */
	function validateFormUser($formType, $uniqueId = false)
	{


		$objValidationSet = new ValidationSet();


		// validating first name
		$objValidationSet->addValidator(new  StringMinLengthValidator('name_first', 1, "First Name cannot be empty",$this->value('name_first')));
		$objValidationSet->addValidator(new AlphabeticalOnlyValidator('name_first',array(' '),"Please enter valid characters in first name",$this->value('name_first')));

		// validating last name
		$objValidationSet->addValidator(new  StringMinLengthValidator('name_last', 1, "Last Name cannot be empty",$this->value('name_last')));
		$objValidationSet->addValidator(new AlphabeticalOnlyValidator('name_last',array(' '),"Please enter valid characters in last name",$this->value('name_last')));

		// validating email (username)
		$objValidationSet->addValidator(new  StringMinLengthValidator('username', 1, "Email cannot be empty",$this->value('username')));
		$objValidationSet->addValidator(new EmailValidator('username',"Invalid email address",$this->value('username')));
		$objValidationSet->addValidator(new  StringMinLengthValidator('new_password', 6, "Password cannot be empty and should be of 6 characters in length",$this->value('new_password')));
		 
		//validation practitioner_type
		$objValidationSet->addValidator(new  StringMinLengthValidator('practitioner_type', 1, "Please choose Provider type",$this->value('practitioner_type')));
		/*if ($this->value('practitioner_type') == '')
		 {
			
		$objValidationErr = new ValidationError('practitioner_type',"Please choose Provider type");
		$objValidationSet->addValidationError($objValidationErr);
		}*/
		if($formType === 'Add')
		{
			$arrFieldNames = array("username","confirmUsername");
			$arrFieldValues = array($_POST["username"],$_POST["confirmUsername"]);
			$objValidationSet->addValidator(new  IdenticalValuesValidator($arrFieldNames, "Email and  confirm email address does not match",$arrFieldValues));
		}

		$arrFieldNames = array("new_password","new_password2");
		$arrFieldValues = array($_POST["new_password"],$_POST["new_password2"]);
		$objValidationSet->addValidator(new  IdenticalValuesValidator($arrFieldNames, "New password and  confirm passsword does not match",$arrFieldValues));

		/*
		 Also check the validation check for account privileges if user is editing his own account
		then admin_access check mark is disabled in HTML logically it means it has the admin access
		so take care of this scenario
		*/


		$skipAdminAccessCheck = false;

		if ($formType === 'Add')
		{
			$skipAdminAccessCheck = false;
		}
		else if ($formType === 'Edit')
		{
			$userInfo = $this->userInfo();

			$userId = $userInfo['user_id'];

			if ($userId == $uniqueId)
			{
				$skipAdminAccessCheck = true;
			}
			else
			{
				$skipAdminAccessCheck = false;
			}
		}

		if (isset($_POST['therapistAccess']) == false)
		{
			if ($skipAdminAccessCheck === false)
			{
				if (isset($_POST['adminAccess']) == false)
				{
					$objValidationErr = new ValidationError('AccountPrivileges',"Please specify the account privileges for the user");
					$objValidationSet->addValidationError($objValidationErr);
				}
			}
		}



		//Jan 22, 2008 Updation, address state city zip fax and phone will not be mandatory
		//$objValidationSet->addValidator(new  StringMinLengthValidator('address', 1, "Address cannot be empty",$this->value('address')));
		$objValidationSet->addValidator(new AlphanumericOnlyValidator('address',null,"Please enter valid characters in address",$this->value('address')));

		$objValidationSet->addValidator(new AlphanumericOnlyValidator('address2',null,"Please enter valid characters in address2",$this->value('address2')));

		//$objValidationSet->addValidator(new  StringMinLengthValidator('city', 1, "City cannot be empty",$this->value('city')));
		$objValidationSet->addValidator(new AlphabeticalOnlyValidator('city',array(' ',',','.'),"Please enter valid characters in city",$this->value('city')));


		/*if ($this->value('zip') != '')
		 {
		if($this->value('clinic_country')=='CAN'){
		$objValidationSet->addValidator(new  AlphanumericOnlyValidator('zip', array(' '), "Zip code should be of alphanumeric characters only",$this->value('zip')));
		$objValidationSet->addValidator(new  StringMinMaxLengthValidator('zip', 6,7, "Zip code should be  6 to 7 alphanumeric characters only",$this->value('zip')));
		}else{
		$objValidationSet->addValidator(new  NumericOnlyValidator('zip', null, "Zip code should be of numeric characters only",$this->value('zip')));
		$objValidationSet->addValidator(new  StringMinMaxLengthValidator('zip', 5,5, "Zip code should be  5 numeric characters only",$this->value('zip')));
		}
		}*/


		if ($uniqueId === false)
		{
			$queryEmail = "SELECT user_id FROM user WHERE username = '".$_POST['username']."' AND status <> 3";
			$result = $this->execute_query($queryEmail);

			//if record found that means email not unique else it is unique
			if ($this->num_rows($result) != 0)
			{
				$objValidationErr = new ValidationError('username',"Email address : exists in the system. Please choose another.");
				$objValidationSet->addValidationError($objValidationErr);
			}

		}
		else
		{
			$queryEmail = "SELECT user_id FROM user WHERE username = '".$_POST['username']."' AND status <> 3 AND user_id <> ".$uniqueId;
			$result = $this->execute_query($queryEmail);

			//if record found that means email not unique else it is unique
			if ($this->num_rows($result) != 0)
			{
				$objValidationErr = new ValidationError('username',"Email address : exists in the system. Please choose another.");
				$objValidationSet->addValidationError($objValidationErr);
			}

		}

		$objValidationSet->validate();


		if ($objValidationSet->hasErrors())
		{

			$arrayFields = array("name_first","name_last","username","new_password","AccountPrivileges","address","address2","city","state","zip","practitioner_type");

			for($i=0;$i<count($arrayFields);++$i)
			{
				$errorMsg = $objValidationSet->getErrorByFieldName($arrayFields[$i]);
				//echo "msg : $errorMsg<br>";

				if ($errorMsg != "")
				{
					$this->error = $errorMsg."<br>";
					break;
				}
			}

		}
		else
		{
			$this->error = "";
		}

	}
	/**
	 * Function to validate the patient credentials like name address email etc.
	 *
	 * @access public
	 */
	function validateForm($form_type="")
	{
		$error = "";

		$objValidationSet = new ValidationSet();

		// first name validation
		$allowchar=array('0'=>'@','1'=>'.','2'=>'_','3'=>'-','4'=>'"','5'=>'');
		$objValidationSet->addValidator(new StringMinLengthValidator('patient_first_name', 1, "First Name cannot be empty",$this->value('patient_first_name')));
		$objValidationSet->addValidator(new AlphabeticalOnlyValidator('patient_first_name',$allowchar,"Please enter valid characters in First Name",$this->value('patient_first_name')));

		// last name validation
		$objValidationSet->addValidator(new StringMinLengthValidator('patient_last_name', 1,"Last Name cannot be empty",$this->value('patient_last_name')));
		$objValidationSet->addValidator(new AlphabeticalOnlyValidator('patient_last_name',$allowchar,"Please enter valid characters in last Name",$this->value('patient_last_name')));

		// email validation
		$objValidationSet->addValidator(new StringMinLengthValidator('patient_email', 1,"Email cannot be empty",$this->value('patient_email')));
		$objValidationSet->addValidator(new EmailValidator('patient_email',"Invalid email address",$this->value('patient_email')));

		// phone number validation
		$objValidationSet->addValidator(new NumericOnlyValidator('patient_phone1', null,"Please enter only numeric values in 1st phone",$this->value('patient_phone1')));
		$objValidationSet->addValidator(new NumericOnlyValidator('patient_phone2', null,"Please enter only numeric values in 2nd phone",$this->value('patient_phone2')));

		// address line 1 validation (checking for null values)
		$objValidationSet->addValidator(new StringMinMaxLengthValidator('patient_address', 0,50, "Address cannot be more than 50 characters",$this->value('patient_address')));
		$objValidationSet->addValidator(new AlphanumericOnlyValidator('patient_address',null,"Please enter valid characters in Address",$this->value('patient_address')));

		// address line 2 validation (checking for null values)
		$objValidationSet->addValidator(new StringMinMaxLengthValidator('patient_address2',0,50, "Address line 2 cannot be more than 50 characters",$this->value('patient_address2')));
		$objValidationSet->addValidator(new AlphanumericOnlyValidator('patient_address2',null,"Please enter valid characters in address line 2",$this->value('patient_address2')));

		// city name validation (checking for null)
		$objValidationSet->addValidator(new StringMinMaxLengthValidator('patient_city',0,20, "City name cannot be more than 20 characters",$this->value('patient_city')));
		$objValidationSet->addValidator(new AlphabeticalOnlyValidator('patient_city',array(' ',',','.'),"Please enter valid characters in city",$this->value('patient_city')));

		// zip code validation
		/*  $objValidationSet->addValidator(new AlphanumericOnlyValidator('patient_zip', null,"Please enter alphanumeric values in zip",$this->value('patient_zip')));
		$objValidationSet->addValidator(new StringMinMaxLengthValidator('patient_zip', 5, 7, "Zip code should be 5 to 7 alphanumeric characters only",$this->value('patient_zip')));*/
		/*  if($this->value('clinic_country')=='CAN'){
		 $objValidationSet->addValidator(new  AlphanumericOnlyValidator('patient_zip', array(' '), "Zip code should be of alphanumeric characters only",$this->value('patient_zip')));
		$objValidationSet->addValidator(new  StringMinMaxLengthValidator('patient_zip', 6,7, "Zip code should be  6 to 7 alphanumeric characters only",$this->value('patient_zip')));
		}else{
		$objValidationSet->addValidator(new  NumericOnlyValidator('patient_zip', null, "Zip code should be of numeric characters only",$this->value('patient_zip')));
		$objValidationSet->addValidator(new  StringMinMaxLengthValidator('patient_zip', 5,5, "Zip code should be  5 numeric characters only",$this->value('patient_zip')));
		}*/

		// for password while adding patient. password cannot be empty
		//$objValidationSet->addValidator(new  StringMinLengthValidator('newPassword', 6, "Password cannot be empty and should be of 6 characters in length",$this->value('newPassword')));
		if( $form_type == 'add' ){
			// confirm email validation
			$objValidationSet->addValidator(new StringMinLengthValidator('patient_cemail', 1,"Confirm emial cannot be empty",$this->value('patient_cemail')));
			$objValidationSet->addValidator(new EmailValidator('patient_cemail',"Invalid Confirm email address",$this->value('patient_cemail')));

			// matching email and confirm email
			$arrFieldNames = array("patient_email","patient_cemail");
			$arrFieldValues = array($_POST["patient_email"],$_POST["patient_cemail"]);
			$objValidationSet->addValidator(new  IdenticalValuesValidator($arrFieldNames, "Email and  confirm email does not match",$arrFieldValues));
		}
		else{
			// matching password and confirm password
			$arrPasswordFieldNames = array("newPassword","cnewPassword");
			$arrPasswordFieldValues = array($_POST["newPassword"],$_POST["cnewPassword"]);
			$objValidationSet->addValidator(new  IdenticalValuesValidator($arrPasswordFieldNames, "Password and  confirm password does not match",$arrPasswordFieldValues));
		}
		$objValidationSet->validate();

		// if error found
		if ($objValidationSet->hasErrors())
		{
			$patient_id = $this->value('patient_id');
			$option = $this->value('option');
			$patient_zip = $this->value('patient_zip');
			if(!empty($patient_zip)){
				$arrayFields = array("patient_first_name","patient_last_name","patient_email","patient_cemail", "patient_phone1", "patient_phone2", "patient_address", "patient_address2", "patient_city", "patient_zip");
			}else{
				$arrayFields = array("patient_first_name","patient_last_name","patient_email","patient_cemail", "patient_phone1", "patient_phone2", "patient_address", "patient_address2", "patient_city");
			}

			if((!empty($patient_id)) && (!empty($option))){
				$arrayFields = $this->addToArray($arrayFields, array("newPassword", "cnewPassword"));
			}
			else{
				$arrayFields = $this->addToArray($arrayFields, array("newPassword"));
			}
			for($i=0;$i<count($arrayFields);++$i)
			{
				$errorMsg = $objValidationSet->getErrorByFieldName($arrayFields[$i]);
				if ($errorMsg != "")
				{
					$error = $errorMsg."<br>";
					break;
				}
			}
		}

		/*
		 Check email address for duplicacy
		*/
		$emailAddress = $this->value('patient_email');
		$query = "select user_id from user where username='".$emailAddress."' and status != '3'";
		$rs = $this->execute_query($query);
		$num_rows = $this->num_rows($rs);
		if($num_rows == 1){
			$row = $this->populate_array($rs);
			$patient_id = $this->value('patient_id');

			// If userId not mataches with the current patient, which one edited
			if($row['user_id'] != $patient_id){
				$error .= "Email address already Exist";
			}
		}
		if($num_rows > 1){
			$error .= "Email address already Exist";
		}
		/* End of checking for duplicacy */

		// return
		$this->error = $error;
	}
	/**
	 * Returns error message for user list module.
	 */
	function errorClinicListModule($msg){
		if( $msg == 'clinic_added'){
			$error[] = 'New Clinic added successfully';
			$replace['error'] = $this->show_error($error,'green');
		}
		elseif( $msg == 'clinic_updated'){
			$error[] = 'Clinic record updated successfully';
			$replace['error'] = $this->show_error($error,'green');
		}

		return $replace['error'];
	}
	/**
	 * Returns error message for user list module.
	 */
	function errorUserListModule($msg){

		if( $msg == "1" ){
			$error[] = 'There are patient(s) who are associated with this therapist, please remove the association and do the action.';
			$replace['error'] = $this->show_error($error);
		}
		elseif($msg == "2"){
			$error[] = 'Not a valid user.';
			$replace['error'] =  $this->show_error($error);
		}
		elseif($msg == 3){
			$error[] = 'User is successfully activated';
			$replace['error'] =  $this->show_error($error,'green');
		}
		elseif($msg == 4){
			$error[] = 'User is successfully inactivated';
			$replace['error'] =  $this->show_error($error,'green');
		}
		elseif($msg == 5){
			$error[] = 'User successfully deleted';
			$replace['error'] =  $this->show_error($error,'green');
		}
		elseif($msg == 'user_updated'){
			$error[] = 'User record updated successfully';
			$replace['error'] =  $this->show_error($error,'green');
		}
		elseif( $msg == 'user_added'){
			$error[] = 'New user added successfully';
			$replace['error'] = $this->show_error($error,'green');
		}
		return $replace['error'];
	}
	/**
	 * Returns error message for user list module.
	 */
	function errorPatientListModule($msg){

		if( $msg == "1" ){
			$error[] = 'Record has been updated';
			$replace['error'] = $this->show_error($error,'green');
		}
		elseif( $msg == "patient_added" ){
			$error[] = 'New Patient added successfully';
			$replace['error'] = $this->show_error($error,'green');
		}
		return $replace['error'];
	}
	/**
	 * List of users (therapist) of that particluar clinic
	 *
	 * @access public
	 */
	function userListingHead()
	{

		$replace = array();
                 /* code for show telespine menu on list */
        $replace['showmenu']="no";
        if($this->clinicInfo('clinic_id')==$this->config['telespineid']){
            $replace['showmenu']="yes";
        }
        /* code end */
		$msg = $this->value('msg');
		$replace['error'] = $this->errorUserListModule($msg);

		$userInfo = $this->userInfo();
		$cli_type=$this->getClinicDetails($this->userInfo('user_id'));
		//if($cli_type['clinic_type']==4){
		$replace['shopshow']=1;
		/*}else{
		 $replace['shopshow']='0';
		}*/
		if (!$userInfo)
		{
			header("location:index.php");
			exit();
		}
		else
		{
			//Get  clinic id
			$clinicId = $this->clinicInfo('clinic_id');
			// Retian page value.
			$arr = array(
					'clinic_id' => $clinicId
			);
			$this->set_session_page($arr);

			/* Defining Sorting */
			$orderByClause = "";
			$privateKey = $this->config['private_key'];
			if($this->value('sort') != ""){
				if($this->value('order') == 'desc' ){
					if( $this->value('sort') == 'user.name_first' ){
						$orderByClause = " order by cast(AES_DECRYPT(UNHEX({$this->value('sort')}),'{$privateKey}') as char) desc ";
					}
					else
						$orderByClause = " order by {$this->value('sort')} desc ";
				}
				else{
					if( $this->value('sort') == 'user.name_first' ){
						$orderByClause = " order by cast(AES_DECRYPT(UNHEX({$this->value('sort')}),'{$privateKey}') as char)  ";
					}
					else
						$orderByClause = " order by {$this->value('sort')} ";
				}
			}
			else{
				$orderByClause = " order by clinic.clinic_name ";
			}
			/* End */

			// Search string
			$searchString = $this->value('search');
			if( !empty($searchString) ){
				$searchString = $this->value('search');
				$privateKey = $this->config['private_key'];
				$searchWhere = " and ( CAST(AES_DECRYPT(UNHEX(user.name_first),'{$privateKey}') AS CHAR) like '%{$this->value('search')}%' or CAST(AES_DECRYPT(UNHEX(user.name_last),'{$privateKey}') AS CHAR) like '%{$this->value('search')}%') ";
			}
			//Get the therapist list
			$privateKey = $this->config['private_key'];
			$sqlUser = "SELECT user.*, CONCAT_WS(' ',
			AES_DECRYPT(UNHEX(user.name_title),'{$privateKey}'),
			AES_DECRYPT(UNHEX(user.name_first),'{$privateKey}'),
			AES_DECRYPT(UNHEX(user.name_last),'{$privateKey}'),
			name_suffix) AS full_name,
			clinic_user.clinic_id,clinic.clinic_name FROM user
			inner join clinic_user on clinic_user.user_id = user.user_id
			and (user.status = '1' or user.status = '2')
			and clinic_user.clinic_id in (select clinic_id from clinic where parent_clinic_id = '{$clinicId}' or clinic_id = '{$clinicId}' and ( status = 1 or status = 2) )
			inner join clinic on clinic.clinic_id = clinic_user.clinic_id and ( clinic.status = '1' or clinic.status = '2' ) WHERE user.usertype_id = '2'
			{$searchWhere}  ".$orderByClause;
			$sqlcount= "SELECT count(1) FROM user
			inner join clinic_user on clinic_user.user_id = user.user_id
			and (user.status = '1' or user.status = '2')
			and clinic_user.clinic_id in (select clinic_id from clinic where parent_clinic_id = '{$clinicId}' or clinic_id = '{$clinicId}' and ( status = 1 or status = 2) )
			inner join clinic on clinic.clinic_id = clinic_user.clinic_id and ( clinic.status = '1' or clinic.status = '2' ) WHERE user.usertype_id = '2'
			{$searchWhere}  ";

			$skipValue = array('msg' );
			$link = $this->pagination($rows = 0,$sqlUser,'userListingHead',$searchString,$skipValue,'','',$sqlcount);

			$replace['link'] = $link['nav'];

			$result = $link['result'];
			$sqlTotTherapists = "SELECT count(user.user_id) AS totTherapists FROM user
			inner join clinic_user on clinic_user.user_id = user.user_id and user.status = '1'  and clinic_user.clinic_id in (select clinic_id from clinic where parent_clinic_id = '{$clinicId}' or clinic_id = '{$clinicId}' )
			inner join clinic on clinic.clinic_id = clinic_user.clinic_id and (clinic.status = '1' or clinic.status = '2')
			where user.therapist_access = '1' AND user.usertype_id = '2'";


			$resultTotTherapists = $this->execute_query($sqlTotTherapists);
			$rowTotTherapists = $this->fetch_array($resultTotTherapists);
			$replace['totTherapist'] = $rowTotTherapists['totTherapists'];

			//$therapist_access = 0;
			//$patient_association = 0;
			$userListHead = array(
					'user.name_first' => 'Full Name',
					'user.username' => 'Username',
					'clinic.clinic_name' => 'Clinic',
					'user.last_login' => 'Last Login',
			);
			$query_string = array(
					'clinic_id' => $clinicId
			);
			$replace['userTblHead'] = $this->build_template($this->get_template("userTblHead"),$this->table_heading($userListHead,'user.name_first',$query_string));
			if($this->num_rows($result)!= 0)
			{
				while($row = $this->fetch_array($result))
				{
					{//Also if Therapist
						if ($row['therapist_access'] == 1)
						{
							//check if there are patient(s) associated with this therapist
							$queryAssocPatients = "SELECT patient_id FROM therapist_patient WHERE therapist_id = ".$row['user_id'];
							$resultAssocPatients = $this->execute_query($queryAssocPatients);

							if ($this->num_rows($resultAssocPatients) == 0)
							{
								//No Patients association
								//$patient_association = 0;
								$row['patient_association'] = 0;
							}
							else
							{
								//There exist patients association
								//$patient_association = 1;
								$row['patient_association'] = 1;
							}
						}
					}

					$row['style'] = ($c++%2)?"line1":"line2";

					/*Added last login column Feb 19, 2008 */
					if ($row['last_login'] != null)
					{
						$row['last_login'] = $this->formatDate($row['last_login']);
					}
					else
					{
						$row['last_login'] = "Never";
					}
					/* End */

					if($row['admin_access'] == 1 && $row['therapist_access'] == 1)
					{
						$row['usertype'] = "Admin, Provider";
					}
					else if($row['admin_access'] == 1)
					{
						$row['usertype'] = "Admin";
					}
					else if($row['therapist_access'] == 1)
					{
						$row['usertype'] = "Provider";
					}
					else
					{
						$row['usertype'] = "";
					}
					if($row['status'] == '1'){
						$row['status_text'] = "Inactivate";
						$row['status_url'] = 'inactiveUserHead';//"index.php?action=inactiveUserSystem&id={$row['user_id']}";
					}
					if($row['status'] == '2'){
						$row['status_text'] = "Activate";
						$row['status_url'] = 'activeUserHead';//"index.php?action=activeUserSystem&id={$row['user_id']}";
					}
					$replace['clinic_name'] = $row['clinic_name'];
					$row['clinic_id'] = $row['clinic_id'];//$clinicId;
					$replace['userTblRecord'] .=  $this->build_template($this->get_template("userTblRecord"),$row);

				}
			}
			else
			{
				$replace['userTblRecord'] =  '<tr> <td colspan="6">No Users to list</td></tr>';
				$replace['link'] = "&nbsp;";
			}

			$replace['clinic_id'] = $clinicId; //$this->value('clinic_id');
			$url_array = $this->tab_url();
			$replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$url_array);
			$replace['userLocation'] = $url_array['userLocation'];
			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['footer'] = $this->build_template($this->get_template("footer"));
			$replace['sidebar'] = $this->sidebar();

			$replace['body'] = $this->build_template($this->get_template("userTemplate"),$replace);
			$replace['browser_title'] = "Tx Xchange: Users List";
			$replace['get_satisfaction'] = $this->get_satisfaction();
			$this->output = $this->build_template($this->get_template("main"),$replace);
		}

	}
	/**
	 * Populating clinic address as address of therapist by Ajax
	 *
	 * @access public
	 */
	function populateAddressHead()
	{
		$userInfo = $this->userInfo();

		$userId = $userInfo['user_id'];

		$replace = array();

		//Get the clinic address for this user to which this user is associated with

		$clinicId = $this->value('clinic_id');

		$query = "SELECT clinic.* FROM clinic WHERE clinic_id = '".$clinicId."'";

		$result = $this->execute_query($query);

		if ($this->num_rows($result)!=0)
		{
			$row = $this->fetch_array($result);

			$replace['address']= $row['address'];
			$replace['address2']= $row['address2'];
			$replace['city']= $row['city'];
			$replace['zip']= $row['zip'];
			$replace['phone1']= $row['phone'];
			$replace['fax']= $row['fax'];
			$selectedCountry = $row['country'];
			$selectedState = $row['state'];

			$stateArray = array("" => "Choose State...");

			$stateArray = array_merge($stateArray,$this->config['state']);
			if($_REQUEST['clinic_country']=='')
				$selectedCountry=$row['country'];
			else
				$selectedCountry=$_REQUEST['clinic_country'];

			$replace['stateOptions'] =     $this->build_select_option($stateArray,$selectedState);
			$countryArray = $this->config['country'];
			$replace['country']=implode("','",$countryArray);
			$replace['patient_country_options'] = $this->build_select_option($countryArray, $selectedCountry);



			 
			if($selectedCountry=='US') {
				$stateArray = array("" => "Choose State...");
				$stateArray = array_merge($stateArray,$this->config['state']);
				$replace['stateOptions'] = $this->build_select_option($stateArray, $selectedState);
			}
			 
			else if($selectedCountry=='CAN') {
				$stateArray = array("" => "Choose Province...");
				$stateArray = array_merge($stateArray,$this->config['canada_state']);
				$replace['stateOptions'] = $this->build_select_option($stateArray, $selectedState);
			}


		}
		else
		{

			$replace['address']= "";
			$replace['address2']= "";
			$replace['city']= "";
			$replace['zip']= "";
			$replace['phone1']= "";
			//$replace['phone2']= "";
			$replace['fax']= "";

			$selectedState = "";
			$replace['patient_country_options']='';

			$stateArray = array("" => "Choose State...");

			$stateArray = array_merge($stateArray,$this->config['state']);

			$replace['stateOptions'] =     $this->build_select_option($stateArray,$selectedState);
		}

		$this->output = $this->build_template($this->get_template("populateAddress"),$replace);
	}
	/**
	 * Edit the existing user (Therapist or Account Admin)of clinic.
	 *
	 * @access public
	 */
	function editUserHead()
	{

		 
		$id = (int) $this->value('id');
		$replace = array();
                 /* code for show telespine menu on list */
        $replace['showmenu']="no";
        if($this->clinicInfo('clinic_id')==$this->config['telespineid']){
            $replace['showmenu']="yes";
        }
        /* code end */
		$firstSubscription= "";
		$userInfo = $this->userInfo();
		$cli_type=$this->getClinicDetails($this->userInfo('user_id'));
		//if($cli_type['clinic_type']==4){
		$replace['shopshow']=1;
		/*}else{
		 $replace['shopshow']='0';
		}*/
		$userId = $userInfo['user_id'];
		//$userType = ($userInfo['usertype_id'] == 4) ? "SysAdmin" : "";
		if (!$userInfo)
		{
			header("location:index.php");
		}
		else
		{
			include_once("template/clinicDetail/userArray.php");
			$this->formArray = $formArray;
			//PRINT_R($_REQUEST);die;
			### Get clinic id from url ###
			$clinicId = $this->value('clinic_id');
			//check if form has been submitted or not
			if (isset($_POST['submitted_edit']) && $_POST['submitted_edit'] == 'Edit User')
			{

				 
				//form submitted check for validation
				$this->validateFormUser("Edit",$id);
				if($this->error == "")
				{
					//Form validated, no errors
					//go ahead store the values in db

					if ($userId == $id)
					{
						$admin_access = 1;
					}
					else
					{
						$admin_access = isset($_POST['adminAccess'])? 1 : 0;
					}
					 
					$therapist_access_checkbox = isset($_POST['therapistAccess'])? 1 : 0;
					$updateArr = array(
							'name_title' => $this->value('name_title'),
							'username' =>$this->value('username'),
							'name_first' => $this->value('name_first'),
							'name_last' => $this->value('name_last'),
							'address' => $this->value('address'),
							'address2' => $this->value('address2'),
							'city' => $this->value('city'),
							'state' => $this->value('state'),
							'country' => $this->value('clinic_country'),
							'zip' => $this->value('zip'),
							'phone1' => $this->value('phone1'),
							/*'phone2' => $this->value('phone2'),*/
							'fax' => $this->value('fax'),
							'modified' => date('Y-m-d H:i:s',time()),
							'admin_access' => $admin_access,
							'therapist_access' => $therapist_access_checkbox,
							'practitioner_type'=>$this->value('practitioner_type')
					);
					// print_r($updateArr);die;

					//$encrypt_field = array('address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2','fax');
					//$updateArr = $this->encrypt_field($updateArr, $encrypt_field);

					$where = " user_id = ".$id;
					$result = $this->update('user',$updateArr,$where);
					//Check if password need to be updated or not

					//unset($updateArr['name_title']);
					// print_r($updateArr);die;
					if ($this->value('new_password') != '')
					{
						$updateArr = array(
								'password'=>$this->value('new_password')
						);
						$where = " user_id = ".$id;
						$result = $this->update('user',$updateArr,$where);
					}
					{
						## if earlier user was having therapist access but now removed then delete therapist_patient association
						if ($_POST['therapist_access'] == 1)
						{

							##    so earlier this user was having therapist access now check if therapist check box is still
							##    check or not if not remove the therapist patient association
							if($therapist_access_checkbox == 0)
							{
								## remove the records from therapist_patient table

								$sqlUpdate = "DELETE FROM therapist_patient WHERE therapist_id = '".$id."'";
								$result = $this->execute_query($sqlUpdate);
							}

						}
					}




					{//usertype = therapist    and first time subscription
						//if    ($therapist_access_checkbox == 1 && $this->value('firstSubscription') == 'Yes')
						if ($therapist_access_checkbox == 1 )
						{

							//Article Block
							{

								// Count number of Articles for new user. If zero article comes create new articles.
								if( !($this->article_count($id) > 0) ){


									// Original Query : New User not getting the Articles created by Account Admin
									//$queryArticle = "SELECT article.* FROM article INNER JOIN user ON article.user_id = user.user_id
									//                    WHERE user.usertype_id = 4 AND user.status = 1 AND article.status != 3";
									$queryArticle = "SELECT article.* FROM article INNER JOIN user ON article.user_id = user.user_id
									WHERE (user.usertype_id = 4 OR (clinic_id= '{$clinicId}' AND parent_article_id IS NULL )) AND user.status = 1 AND article.status != 3";

									$resultArticle = $this->execute_query($queryArticle);


									// now copy article data with user_id as $newlyCreatedUserId

									if($this->num_rows($resultArticle)!= 0)
									{
										while($row = $this->fetch_array($resultArticle))
										{

											$insertArr = array(
													'article_name'=>$row['article_name'],
													'headline' =>$row['headline'],
													'article_type' => $row['article_type'],
													'link_url' => $row['link_url'],
													'file_url' => $row['file_url'],
													'user_id' => $id,
													//'creation_date' => date('Y-m-d H:i:s',time()),
													'creation_date' => $row['creation_date'],
													'modified'=>$row['modified'],
													'status'=> $row['status'],
													'parent_article_id'    => $row['article_id']
											);


											$result = $this->insert('article',$insertArr);
											$newArticleId = $this->insert_id();

											if (!empty($row['file_url']))
											{
												/*$fileDest = $_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url']. $newArticleId . '/';
												 if(!file_exists($fileDest))
												 {
												mkdir($fileDest);
												chmod($fileDest, 0777);
												}


												$fileDestPath = $_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url']. $newArticleId . '/'.$row['file_url'];

												$fileSourcePath = $_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url'].$row['article_id'] . '/'.$row['file_url'];

												copy($fileSourcePath,$fileDestPath);*/
												$fileDest = $this->check_article_path($newArticleId);
												$fileDestPath = $this->update_article_path($newArticleId);
												$fileSourcePath = $this->update_article_path($row['article_id']);
												copy($fileSourcePath,$fileDestPath);
												$this->update_article_path($row['article_id']);
											}


										}
									}

								}
							}

							//Plan Block
							{


								// Count number of Articles for new user. If zero article comes create new articles.
								if( !($this->plan_count($id) > 0) ){

									$queryPlan = "SELECT plan.* FROM plan INNER JOIN user ON plan.user_id = user.user_id
									WHERE user.usertype_id = 4 AND user.status = 1 AND plan.patient_id IS NULL AND plan.status = 1 AND plan.is_public = 1";
									$resultPlan = $this->execute_query($queryPlan);

									$plan_id = 0;

									if($this->num_rows($resultPlan)!= 0)
									{
										while($row = $this->fetch_array($resultPlan))
										{

											$insertArr = array(
													'plan_name'=>$row['plan_name'],
													'parent_template_id' =>NULL,
													'user_id' => $id,
													'patient_id' => NULL,
													'user_type' => 2,
													'is_public' => NULL,
													//'creation_date' => date('Y-m-d H:i:s',time()),
													'creation_date' => $row['creation_date'],
													'status'=> $row['status']
											);

											$result = $this->insert('plan',$insertArr);

											$plan_id = $row['plan_id'];

											//Newly plan id
											$newlyPlanId = $this->insert_id();



											{//Plan_Article Block


												//    Also for this particular DBrow fetch from plan table have the plan_id
												//    now find this plan_id in table plan_article    get the rows for this plan_id
												//    insert rows into plan_article with all data same except replace plan_id with newly created plan_id



												$queryPlanArticle = "SELECT * FROM plan_article WHERE status = 1 AND plan_id = ".$plan_id ;
												$resultPlanArticle = $this->execute_query($queryPlanArticle);



												if($this->num_rows($resultPlanArticle)!= 0)
												{
													while($row = $this->fetch_array($resultPlanArticle))
													{

														$insertArr = array(
																'plan_id'=> $newlyPlanId,
																'article_id' => $row['article_id'],
																'creation_date' => date('Y-m-d H:i:s',time()),
																'status'=> $row['status']
														);

														$result = $this->insert('plan_article',$insertArr);

													}
												}
											}

											{//Plan_Treatment Block


												//    Also for this particular DBrow fetch from plan table have the plan_id
												//    now find this plan_id in table plan_treatment    get the rows for this plan_id
												//    insert rows into plan_treatment with all data same except replace plan_id with newly created plan_id



												$queryPlanTreatment = "SELECT * FROM plan_treatment WHERE plan_id = ".$plan_id ;
												$resultPlanTreatment = $this->execute_query($queryPlanTreatment);



												if($this->num_rows($resultPlanTreatment)!= 0)
												{
													while($row = $this->fetch_array($resultPlanTreatment))
													{

														$row['sets'] = (empty($row['sets']))? "":$row['sets'];
														$row['reps'] = (empty($row['reps']))? "":$row['reps'];
														$row['hold'] = (empty($row['hold']))? "":$row['hold'];
														$row['lrb'] = (empty($row['lrb']))? "":$row['lrb'];
														$row['treatment_order'] = (empty($row['treatment_order']))? "":$row['treatment_order'];

														$insertArr = array(
																'plan_id'=> $newlyPlanId,
																'treatment_id' => $row['treatment_id'],
																'instruction' => $row['instruction'],
																'sets'=> $row['sets'],
																'reps'=> $row['reps'],
																'hold' => $row['hold'],
																'benefit'=> $row['benefit'],
																'lrb'=> $row['lrb'],
																'treatment_order' => $row['treatment_order'],
																'creation_date' => date('Y-m-d H:i:s',time())
														);

														$result = $this->insert('plan_treatment',$insertArr);

													} // end of while
												} // end of if block of result of plan_treatment
											}//End of plan treatment block
										} //While block for result rows from plan table
									}//End of if block of result rows from plan table

								}//End of if block of plan_count.

							}//End of plan block

							//Also update the user table with expiry date for this user as therapist access is given to him for the first time

							$updateArr = array(

									'expiry_date' => date('Y-m-d H:i:s',mktime(date('H'), date('i'), date('s'), date("m")  , date("d")+15, date("Y")))

							);

							$where = " user_id = ".$id;

							$result = $this->update('user',$updateArr,$where);


						}//end of if (access privilege == therapist)

					}//End of usertype = therapist


					// redirect to list of subscriber
					$url = $this->redirectUrl("userListingHead").'&msg=user_updated';
					header("location:".$url);


				}
				else
				{

					$replace = $this->fillForm($this->formArray,$_POST);

					$firstSubscription = $this->value('firstSubscription');

					$replace['error'] = $this->error;

					//Also the state
					$selectedState = ($this->value('state') == "")? "": $this->value('state');


					if (isset($_POST['therapistAccess']))
					{
						$replace['checkedTherapist'] = "checked";
					}
					else
					{
						$replace['checkedTherapist'] = "";
					}

					if (isset($_POST['adminAccess']))
					{
						$replace['checkedAdmin'] = "checked";
					}
					else
					{
						if ($userId == $id)
						{
							$replace['checkedAdmin'] = "checked";
						}
						else
						{
							$replace['checkedAdmin'] = "";
						}
					}


					if (isset($_POST['clinicAddress']))
					{
						$replace['checkedAddress'] = "checked";
					}
					else
					{
						$replace['checkedAddress'] = "";
					}

					$replace['therapist_access'] = $_POST['therapist_access'];
					$replace['patient_association'] = $_POST['patient_association'];

				}

			}
			else
			{
				//first time edit form, so values from table

				$query = "SELECT * FROM user WHERE user_id = ". $id;
				$result = $this->execute_query($query);

				$row = null;

				if ($this->num_rows($result) != 0)
				{
					$row = $this->fetch_array($result);
					// Encrypt the data
					$encrypt_field = array('name_title','name_first','name_last','password','address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2','fax');
					$row = $this->decrypt_field($row, $encrypt_field);
				}

				if (empty($row['expiry_date']))
				{
					$firstSubscription = "Yes";
				}
				else
				{
					$firstSubscription = "No";
				}

				$selectedState = $row['state'];


				//$row['confirmUsername'] = $row['username'];
				$replace = $this->fillForm($this->formArray,$row);
				//$replace['subscriber'] = strtoupper($row['name_first']." ".$row['name_last']);

				$replace['therapist_access'] = ($row['therapist_access'] == 1)? 1 :0;

				{

					//Also if Therapist

					if ($row['therapist_access'] == 1)
					{
						//check if there are patient(s) associated with this therapist
						$queryAssocPatients = "SELECT patient_id FROM therapist_patient WHERE therapist_id = ".$id;
						$resultAssocPatients = $this->execute_query($queryAssocPatients);

						if ($this->num_rows($resultAssocPatients) == 0)
						{
							//No Patients association
							//$patient_association = 0;
							$replace['patient_association'] = 0;
						}
						else
						{
							//There exist patients association
							//$patient_association = 1;
							$replace['patient_association'] = 1;

						}

					}

				}
				$replace['checkedTherapist'] = ($row['therapist_access'] == 1)? "checked":"";
				$replace['checkedAdmin'] = ($row['admin_access'] == 1)? "checked":"";
				$replace['checkedAddress'] = "";
			}

			$replace['new_password'] = $this->value('new_password');
			$replace['new_password2'] = $this->value('new_password2');

			if( !isset($replace['new_password']) || empty($replace['new_password']) ){
				$replace['new_password'] = $this->userInfo('password',$id);
			}
			if( !isset($replace['new_password2']) || empty($replace['new_password2']) ){
				$replace['new_password2'] = $this->userInfo('password',$id);
			}

			//If clinic admin is editing his own details
			$replace['name_title'] = $this->build_select_option(array_merge(array('' => 'Choose...'), $this->config['title']), $row['name_title']);
			$replace['editAccess'] = ($userId == $id)?"disabled":"";
			$replace['firstSubscription'] = $firstSubscription;
			$replace['id'] = $id;
			$selectedPractitioner=$row['practitioner_type'];
			$PractitionerArray = array("" => "Choose Provider...");
			$query="select * from practitioner where   status =1";
			$result=$this->execute_query($query);
			while($rowPractitioner=$this->fetch_array($result)){
				$PractitionerArray[$rowPractitioner['practitioner_id']]=$rowPractitioner['name'];
			}
			$replace['PractitionerOptions'] = 	$this->build_select_option($PractitionerArray, $selectedPractitioner);
			$stateArray = array("" => "Choose State...");
			$stateArray = array_merge($stateArray,$this->config['state']);
			$replace['stateOptions'] =     $this->build_select_option($stateArray,$selectedState);
			$replace['clinic_id'] = $this->value('clinic_id');
			$url_array = $this->tab_url();
			$replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$url_array);
			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['footer'] = $this->build_template($this->get_template("footer"));
			$replace['sidebar'] = $this->sidebar();
			$countryArray = $this->config['country'];
			$replace['country']=implode("','",$countryArray);

			if( isset($_REQUEST['clinic_country']) && $_REQUEST['clinic_country']!='')

			{
				 
				 
				$replace['patient_country_options'] = $this->build_select_option($countryArray, $_REQUEST['clinic_country']);
				if($_REQUEST['clinic_country']=='US') {
					$stateArray = array("" => "Choose State...");
					$stateArray = array_merge($stateArray,$this->config['state']);
					$replace['stateOptions'] = $this->build_select_option($stateArray, $_REQUEST['state']);
				}
				 
				else if($_REQUEST['clinic_country']=='CAN') {
					$stateArray = array("" => "Choose Province...");
					$stateArray = array_merge($stateArray,$this->config['canada_state']);
					$replace['stateOptions'] = $this->build_select_option($stateArray, $_REQUEST['state']);
				}
			}

			if(!isset($_REQUEST['clinic_country']))
			{
				$replace['patient_country_options'] = $this->build_select_option($countryArray, $row['country']);
				if($row['country']=='US') {
					$stateArray = array("" => "Choose State...");
					$stateArray = array_merge($stateArray,$this->config['state']);
					$replace['stateOptions'] = $this->build_select_option($stateArray, $selectedState);
				}
				 
				else if($row['country']=='CAN') {
					$stateArray = array("" => "Choose Province...");
					$stateArray = array_merge($stateArray,$this->config['canada_state']);
					$replace['stateOptions'] = $this->build_select_option($stateArray, $selectedState);
				}


			}



			$replace['body'] = $this->build_template($this->get_template("editUser"),$replace);
			$replace['browser_title'] = "Tx Xchange: Edit User";
			$replace['get_satisfaction'] = $this->get_satisfaction();
			$this->output = $this->build_template($this->get_template("main"),$replace);

		}
	}
	/**
	 * Set status InActive for the user.
	 *
	 * @access public
	 */
	function inactiveUserHead()
	{
		/*
		 Use to inactivate the subscriber
		*/
		$userInfo = $this->userInfo();
		if (!$userInfo)
		{
			header("location:index.php");
		}
		else
		{
			$user_id = (int) $this->value('id');
			$err = "";
			// only sys admin has access to delete
			//But if the usertype of this userid is sysadmin it can't be inactivated
			$query = "SELECT usertype_id FROM user WHERE user_id = '{$user_id}' and usertype_id = 2 ";
			$result = $this->execute_query($query);
			if ($this->num_rows($result)!=0)
			{
				$row = $this->fetch_array($result);
				if ($row['usertype_id'] == 2)
				{
					//Therapist
					//allow only if there are no patient(s) associated with this therapist
					//check

					$queryAssocPatients = "SELECT patient_id FROM therapist_patient WHERE therapist_id = '{$user_id}' ";
					$resultAssocPatients = $this->execute_query($queryAssocPatients);

					if ($this->num_rows($resultAssocPatients) == 0)
					{
						$queryUpdate = "UPDATE user SET status = 2 WHERE user_id = ". $user_id;

						$this->execute_query($queryUpdate);
						$msg = 4;
					}
					else
					{
						$msg = 1;
						//There exist relationship
						$err = "There are patient(s) who are associated with this therapist, please remove the association and do the action";

					}

				}
			}
			else
			{
				$msg = 2;
				//No user exist with this id
				$err = "Not a valid user";

			}
			$url = $this->redirectUrl("userListingHead")."&msg=".$msg;
			header("location:".$url);
			return $err;
		}
	}
	/**
	 * Set status Active for the user.
	 *
	 * @access public
	 */
	function activeUserHead()
	{
		/*
		 Use to activate the subscriber
		*/
		$userInfo = $this->userInfo();
		if (!$userInfo)
		{
			header("location:index.php");
		}
		else
		{
			$user_id = (int) $this->value('id');
			//extra precaution check if user has access
			$userType = ($userInfo['usertype_id'] == 2) ? "Therapist" : "";
			if ("Therapist" == $userType)
			{
				// only sys admin has access to delete
				$queryUpdate = "UPDATE user SET status = 1 WHERE user_id = '{$user_id}' and usertype_id = 2 ";
				$this->execute_query($queryUpdate);
				$msg = 3;
				$url = $this->redirectUrl("userListingHead")."&msg=".$msg;
				header("location:".$url);
			}
			else
			{
				unset($_GET['id']);
				header("location:index.php");
			}
		}
	}
	/**
	 * Showing confirmation popup message if you removes the user.
	 *
	 * @access public
	 */
	function popupConfirmRemoveHead()
	{
		$replace = array();
		$therapistId = $this->value('id');

		$queryUser = "select * FROM user WHERE user_id = '".$therapistId."'";
		$resultUser = $this->execute_query($queryUser);
		$rowUser = $this->fetch_array($resultUser);
		$hasAdminAccess = $rowUser['admin_access'];
		$hasTherapistAccess = $rowUser['therapist_access'];

		$privateKey = $this->config['private_key'];
		$queryAssocPatients = "SELECT patient_id,
		AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as name_first,
		AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as name_last,
		username FROM therapist_patient as tp, user WHERE user.user_id = tp.patient_id AND tp.therapist_id = '".$therapistId."'";
		$resultAssocPatients = $this->execute_query($queryAssocPatients);

		if($this->num_rows($resultAssocPatients)!= 0)
		{
			if($this->num_rows($resultAssocPatients) <= 6){
				$replace['rightPadding'] = "padding-right:17px;";
			}
			while($row = $this->fetch_array($resultAssocPatients))
			{

				$row['style'] = ($c++%2)?"line1":"line2";
				 
				$replaceTbl['patientListTblRecord'] .=  $this->build_template($this->get_template("patientListTblRecord"),$row);

			}

			$replace['patientListTbl'] = $this->build_template($this->get_template("patientListTbl"),$replaceTbl);                    ;

		}
		else
		{
			// no patients list
			$replace['patientListTbl'] = "&nbsp;";
		}


		//Tot Therapists
		$totTherapists = 0;

		$clinicId = $this->get_clinic_info($therapistId,'clinic_id');
		$queryTotTherapists  = " SELECT count(user.user_id) AS totTherapists FROM user
		inner join clinic_user on clinic_user.user_id = user.user_id and (user.status = '1') and clinic_user.clinic_id = '$clinicId'
		inner join clinic on clinic.clinic_id = clinic_user.clinic_id and ( clinic.status = '1' )
		where user.therapist_access = '1' AND user.usertype_id = '2' ";

		$resultTotTherapists = $this->execute_query($queryTotTherapists);

		if($this->num_rows($resultTotTherapists)!= 0)
		{
			$row = $this->fetch_array($resultTotTherapists);
			$totTherapists = $row['totTherapists'];

			if ($hasAdminAccess == 1 AND ($hasTherapistAccess == NULL OR $hasTherapistAccess == 0))
			{
				//do nothing
				//$totTherapists = $totTherapists;
			}
			else
			{
				$totTherapists -= 1;
			}


		}
		else
		{
			$totTherapists = 0;
		}

		if( $this->get_field($clinicId,'clinic','status') != 1 ){
			$totTherapists = 0;
		}
		//totTherapists End

		$replace['totTherapists'] = $totTherapists;



		// First Name Last Name of this Therapist which is to be removed
		$privateKey = $this->config['private_key'];
		$queryFullName = "SELECT
		AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as name_first,
		AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as name_last
		FROM user WHERE user_id ='".$therapistId."'";
		$resultFullName = $this->execute_query($queryFullName);
		$row = $this->fetch_array($resultFullName);

		$replace['name_first'] = $row['name_first'];
		$replace['name_last'] = $row['name_last'];

		$clinic_name = $this->get_field($clinicId,'clinic','clinic_name');
		if(!empty($clinic_name)){
			$replace['clinic_name'] = ' from '.$clinic_name.' clinic.';
		}
		else{
			$replace['clinic_name'] = '';
		}
		$replace['id'] = $therapistId;
		if ($hasAdminAccess == 1 AND ($hasTherapistAccess == NULL OR $hasTherapistAccess == 0))
		{
			$this->output = $this->build_template($this->get_template("popupConfirmRemoveAdmin"),$replace);
		}
		else
		{
			$this->output = $this->build_template($this->get_template("popupConfirmRemove"),$replace);
		}



	}
	/**
	 * Showing confirmation popup message box.
	 *
	 * @access public
	 */
	function showPopupMsgHead()
	{

		$replace = array();
		$therapistId = $this->value('id');
		$privateKey = $this->config['private_key'];
		$queryAssocPatients = "SELECT patient_id,
		AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as name_first,
		AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as name_last,
		username FROM therapist_patient as tp, user WHERE user.user_id = tp.patient_id AND tp.therapist_id = '".$therapistId."'";
		$resultAssocPatients = $this->execute_query($queryAssocPatients);

		if($this->num_rows($resultAssocPatients)!= 0)
		{
			if($this->num_rows($resultAssocPatients) <= 6){
				$replace['rightPadding'] = "padding-right:17px;";
			}
			while($row = $this->fetch_array($resultAssocPatients))
			{

				$row['style'] = ($c++%2)?"line1":"line2";
				 
				$replaceTbl['patientListTblRecord'] .=  $this->build_template($this->get_template("patientListTblRecord"),$row);

			}

			$replace['patientListTbl'] = $this->build_template($this->get_template("patientListTbl"),$replaceTbl);                    ;

		}
		else
		{
			// no patients list, this will not be the case becoz popup will open only if atleast a single association is there
			$replace['patientListTbl'] = "&nbsp;";
		}



		//Tot Therapists
		$totTherapists = 0;
		$clinicId = $this->get_clinic_info($therapistId,'clinic_id');
		$queryTotTherapists  = " SELECT count(user.user_id) AS totTherapists FROM user
		inner join clinic_user on clinic_user.user_id = user.user_id and (user.status = '1' or user.status = '2' )  and clinic_user.clinic_id = '$clinicId'
		where user.therapist_access = '1' AND user.usertype_id = '2' ";

		$resultTotTherapists = $this->execute_query($queryTotTherapists);

		if($this->num_rows($resultTotTherapists)!= 0)
		{
			$row = $this->fetch_array($resultTotTherapists);
			$totTherapists = $row['totTherapists'];
			$totTherapists -= 1;
		}
		else
		{
			$totTherapists = 0;
		}
		//totTherapists End
		if( $this->get_field($clinicId,'clinic','status') != 1 ){
			$totTherapists = 0;
		}
		$replace['totTherapists'] = $totTherapists;

		$privateKey = $this->config['private_key'];
		// First Name Last Name of this Therapist which is to be removed
		$queryFullName = "SELECT
		AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as name_first,
		AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as name_last
		FROM user WHERE user_id ='".$therapistId."'";
		$resultFullName = $this->execute_query($queryFullName);
		$row = $this->fetch_array($resultFullName);

		$replace['name_first'] = $row['name_first'];
		$replace['name_last'] = $row['name_last'];

		$clinic_name = $this->get_field($clinicId,'clinic','clinic_name');
		if(!empty($clinic_name)){
			$replace['clinic_name'] = ' from '.$clinic_name.' clinic.';
		}
		else{
			$replace['clinic_name'] = '';
		}

		$this->output = $this->build_template($this->get_template("thpstAccessRemovalPopup"),$replace);

	}
	/**
	 * Delete the user (Therapist or Account admin) of clinic.
	 *
	 * @access public
	 *
	 */
	function deleteUserHead()
	{
		$userInfo = $this->userInfo();

		if (!$userInfo)
		{
			header("location:index.php");
		}
		else
		{
			$therapist_id = (int) $this->value('id');

			$queryUpdate = "UPDATE user SET status = 3 WHERE user_id = ". $therapist_id;
			$this->execute_query($queryUpdate);

			$sqlUpdate = "DELETE FROM therapist_patient WHERE therapist_id = '".$therapist_id."'";
			$result = $this->execute_query($sqlUpdate);

			/*$strJS =  '<script language="JavaScript">
			 opener.window.document.location.reload();
			window.close();</script>';*/
			$strJS =  '<script language="JavaScript">
			opener.window.document.location.href = "index.php?action=userListingHead&msg=5";
			window.close();</script>';
			echo $strJS;

		}

	}
	/**
	 * This function shows the login history of Patients.
	 *
	 * @access public
	 *
	 */
	function headLoginHistory(){
		$query = "select * from login_history where user_id = '{$this->value('patient_id')}' and user_type = '1' ORDER BY login_date_time DESC ";
		$result = @mysql_query($query);
		while($row = @mysql_fetch_array($result)){
			$login_data = date("l,F j, Y - h:ia ",strtotime($row['login_date_time']));
			$data = array(
					'login_data' => $login_data,
					'style' => $cnt++%2?"ineerwhite":"inergrey"
			);
			$replace['loginHistoryRecord'] .= $this->build_template($this->get_template("loginHistoryRecord"),$data);
		}
		$replace['browser_title'] = "Tx Xchange: View Login History";
		$this->output = $this->build_template($this->get_template("loginHistory"),$replace);
	}
	/**
	 * Function to show best practices..
	 * @access public
	 */
	function bestPracticesHead(){
		$replace = array();
                
                  /* code for show telespine menu on list */
        $replace['showmenu']="no";
        if($this->clinicInfo('clinic_id')==$this->config['telespineid']){
            $replace['showmenu']="yes";
        }
        /* code end */
		// set template variables
		$userInfo = $this->userInfo();
		$cli_type=$this->getClinicDetails($this->userInfo('user_id'));
		//if($cli_type['clinic_type']==4){
		$replace['shopshow']=1;
		/*}else{
		 $replace['shopshow']='0';
		}*/
		$replace['browser_title'] = 'Tx Xchange: Best Practices';
		$replace['footer'] = $this->build_template($this->get_template("footer"));
		$replace['header'] = $this->build_template($this->get_template("header"));
		$replace['url'] = $this->config['images_url'];
		$replace['sidebar'] = $this->sidebar();
		$tab_array = $this->tab_url();
		$replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$tab_array);
		$replace['body'] = $this->build_template($this->get_template("bestPractices"),$replace);
		$replace['get_satisfaction'] = $this->get_satisfaction();
		$this->output = $this->build_template($this->get_template("main"),$replace);
	}

	/**
	 *  This function sends mail of login detail to patient.
	 */
	function Headmail_login_detail_patient(){
		if(is_numeric($this->value('patient_id'))){
			$query = "select user_id,username,password from user where usertype_id = 1 and user_id = '{$this->value('patient_id')}' and (status = 1 or status = 2)";
			$result = @mysql_query($query);
			if( $row = @mysql_fetch_array($result) ){
				$email_address = $row['username'];
				$to = $email_address;
				//$to = "manabendra.sarkar@hytechpro.com";
				$clinicName=html_entity_decode($this->get_clinic_info($row['user_id'],'clinic_name'), ENT_QUOTES, "UTF-8");
				$subject = "Information from ".$clinicName;
				$password = $this->decrypt_data($row['password']);
				$images_url = $this->config['images_url'];
				$user_id = $row['user_id'];
				$clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
				if($clinic_channel==1){
					$business_url=$this->config['business_tx'];
					$support_email=$this->config['email_tx'];
				}else{
					$business_url=$this->config['business_wx'];
					$support_email=$this->config['email_wx'];
				}

				$data = array(
						'images_url' => $images_url,
						'username' => $email_address,
						'password' => $password,
						'business_url'=>$business_url,
						'support_email'=>$support_email

				);

				$clinic_type = $this->getUserClinicType($user_id);
				 
				if( $clinic_channel == 1){
					$message = $this->build_template($this->get_template("resend_login_detail_plpto"),$data);
				}
				else{
					$message = $this->build_template($this->get_template("resend_login_detail_wx"),$data);
				}


				//$message = $this->build_template($this->get_template("resend_login_detail"),$data);
				// To send HTML mail, the Content-type header must be set
				$headers  = 'MIME-Version: 1.0' . "\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
				$headers .= "From:".$this->setmailheader($clinicName)." <do-not-reply@txxchange.com>\n";
				$returnpath = '-fdo-not-reply@txxchange.com';

				$success = mail($to,$subject,$message,$headers,$returnpath);
				if( $success == true){
					echo "success";
					return;
				}
				else{
					echo "failed";
					return;
				}
			}
		}

		echo "Email address not found.";
		return;

	}

	/**
	 * Function to Account admin mass patient list uplaod..
	 * @access public
	 */
	function uploadPatientListHead(){
		ini_set('max_execution_time', -1);
		$replace = array();
                    /* code for show telespine menu on list */
        $replace['showmenu']="no";
        if($this->clinicInfo('clinic_id')==$this->config['telespineid']){
            $replace['showmenu']="yes";
        }
        /* code end */
		$error = array();
		$color = '#FF0000';
		$user_id=$this->userInfo('user_id');
		$userInfo = $this->userInfo();
		$cli_type=$this->getClinicDetails($this->userInfo('user_id'));
		//if($cli_type['clinic_type']==4){
		$replace['shopshow']=1;
		/*}else{
		 $replace['shopshow']='0';
		}*/
		//code to show results link if uploading is completed
		$query="SELECT * FROM mass_uploaded_files WHERE status=3 AND user_id=".$user_id." ORDER BY id DESC LIMIT 0,1";
		$res=@mysql_query($query);
		if(@mysql_num_rows($res)>0)
		{
			$row=mysql_fetch_object($res);
			$str="<tr><td style='padding-left:22px;padding-top:20px;'><span class='downloadfile'><a href='javascript:void(0);' onclick='showHide();'>Results - ".$this->formatDate($row->timestamp)."</a></span><br /><div style='display:none;' id='result'>".stripslashes($row->message)."</div></td></tr>";
			$replace['results']=$str;
		}
			
			
		if($this->value('submit_patientlist')=="UPLOAD")
		{	 //echo "<pre />";print_r($_FILES);exit;
			$size = $_FILES['patient_list']['size'];
			$files_size_limit = 4*1024*1024;
			$newfilename=$user_id."-".date("m-d-y-g-i-s")."-".$_FILES['patient_list']['name'];
			$tmpname=$_FILES['patient_list']['tmp_name'];
			$ext = explode(".",$_FILES['patient_list']['name']);
			$last=count($ext)-1;
			if($ext[$last]!="csv")
			{
				$error[]="Uploaded file is not in correct format (Only .csv allowed)";
			}
			elseif($files_size_limit<$size)
			{
				$error[]="File size is large.Maximum filesize allowed is :".($files_size_limit/(1024*1024))." MB";
			}
			else
			{	//vaild condition for uploading and extracting the xls file to insert records.
					
				$target_path=$_SERVER['DOCUMENT_ROOT'].'/patient_lists_uploaded/'.$newfilename;
				if(move_uploaded_file($tmpname,$target_path))
				{
					$error[]="Your file has been uploaded and queued for processing. Once your health records are created, you will receive an email asking you to log back in and check this page for the results. Depending on the number of records you are creating and other server activity, this process could take from a few minutes to a few hours.<br />";
					$color = '#54A113';
					//code to insert the details into mass_uploaded_files table for cron
					$tableArray['user_id'] = $user_id;
					$tableArray['file_name'] = $newfilename;
					$tableArray['timestamp'] = date("Y-m-d H:i:s");
					$tableArray['status'] = "1";
					$tableArray['message'] = "";
					$this->insert($this->config['table']['mass_uploaded_files'], $tableArray);

					if($ext[1]=="xls")
					{	/*require_once 'excel/reader.php';
						$data = new Spreadsheet_Excel_Reader();
						// Set output Encoding.
						$data->setOutputEncoding('CP1251');
						$data->read($target_path);
						error_reporting(E_ALL ^ E_NOTICE);
						error_reporting(0);
						//pr($data->sheets);
						$str="";
						$errorCount=0;
						$totalRecords=$data->sheets[0]['numRows']-1;
						for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++)
						{   if(strtolower($data->sheets[0]['cells'][$i][4])=="current")
						$status=1;
						elseif(strtolower($data->sheets[0]['cells'][$i][4])=="discharged")
						$status=2;
						if(empty($data->sheets[0]['cells'][$i][1]) || empty($data->sheets[0]['cells'][$i][2]) || empty($data->sheets[0]['cells'][$i][3]) || empty($data->sheets[0]['cells'][$i][4]))
						{	$str.="<li><strong style='color:#0069A0;'>Record no. ".($i-1)."</strong>- Any of the four required fields missing values.</li>";
						$errorCount++;
						}
						elseif(!$this->validateEmail($data->sheets[0]['cells'][$i][3]))
						{	$str.="<li><strong style='color:#0069A0;'>Record no. ".($i-1)."</strong>- Email address format is not legitimate.</li>";
						$errorCount++;
						}
						elseif($this->checkUserNameExist($data->sheets[0]['cells'][$i][3])>0)
						{	$str.="<li><strong style='color:#0069A0;'>Record no. ".($i-1)."</strong>- Email address already exists in system.</li>";
						$errorCount++;
						}
						else
						{
						include_once("template/therapist/therapistArray.php");
						$tableArray['username'] = $data->sheets[0]['cells'][$i][3];
						$tableArray['password'] = $this->generateCode(9);
						$tableArray['refering_physician'] = $data->sheets[0]['cells'][$i][12];
						$tableArray['company'] = "";
						$tableArray['name_title'] = $data->sheets[0]['cells'][$i][13];
						$tableArray['name_first'] = $data->sheets[0]['cells'][$i][1];
						$tableArray['name_last'] = $data->sheets[0]['cells'][$i][2];
						$tableArray['name_suffix'] = $data->sheets[0]['cells'][$i][14];
						$tableArray['address'] = $data->sheets[0]['cells'][$i][5];
						$tableArray['address2'] = $data->sheets[0]['cells'][$i][6];
						$tableArray['city'] = $data->sheets[0]['cells'][$i][7];
						$tableArray['state'] = $data->sheets[0]['cells'][$i][8];
						$tableArray['zip'] = $data->sheets[0]['cells'][$i][9];
						$tableArray['phone1'] = $data->sheets[0]['cells'][$i][10];
						$tableArray['phone2'] = $data->sheets[0]['cells'][$i][11];
						$tableArray['status'] = $status;
						$tableArray['usertype_id'] = 1;
						$tableArray['creation_date'] = date('Y-m-d H:i:s');
						$tableArray['modified'] = date('Y-m-d H:i:s');
						//echo "<pre/>";print_r($tableArray);
						if($this->insert($this->config['table']['user'], $tableArray))
						{	if($tableArray['status']==1){
						$patientReplace = array(
								'username' => $tableArray['username'],
								'password' => $tableArray['password'],
								'url' => $this->config['url'],
								'images_url' => $this->config['images_url']
						);
						$message = $this->build_template("mail_content/create_new_patient_mail.php",$patientReplace);
						$to = $tableArray['username'];
						$subject = "Your Tx Xchange Health Record";
						// To send HTML mail, the Content-type header must be set
						$headers  = 'MIME-Version: 1.0' . "\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						$headers .= "From: " . "support@txxchange.com" . "\n";
						$returnpath = '-fsupport@txxchange.com';
						// Mail it
						//echo $message;
						//mail($to, $subject, $message, $headers, $returnpath);
						}
						}

						}

						}*/
					}
					//This below else condition is to be used in cron file
					/*else	//For CSV
					{	$i = 0;
					$handle = fopen($target_path, "rb");
					$str="";
					$errorCount=0;
					$errorFormat=0;
					while (($data = fgetcsv($handle, 100000, ",")) !== FALSE) {
						
					if($i==0 && (strtolower($data[0])!="first name" || strtolower($data[1])!="last name" || strtolower($data[2])!="email address" || strtolower($data[3])!="patient/client status" || strtolower($data[4])!="subscribe/unsubscribe"))
					{$errorFormat=1;
					$str.="<li><strong style='color:#0069A0;'>Heading Record </strong>- One of the column headings does not match the required headings. See example template.</li>";
					$errorCount++;
					}
					if($errorFormat==0 && $i>0 ){
					if($data[0]=="" and $data[1]=="" and $data[2]=="" and $data[3]=="" and $data[4]=="" and $data[5]=="" and $data[6]=="" and $data[7]=="" and $data[8]=="" and $data[9]=="" and $data[10]=="" and $data[11]=="" and $data[12]=="")
						continue;
					}
					if($errorFormat==0 && $i>0 )
					{
					$status=0;
					$mass_message_access=0;
					if(strtolower($data[3])=="current")
						$status=1;
					elseif(strtolower($data[3])=="discharged")
					$status=2;
					if(strtolower($data[4])=="subscribe")
						$mass_message_access=1;
					elseif(strtolower($data[4])=="unsubscribe")
					$mass_message_access=2;

					if($status==0){
					$str.="<li><strong style='color:#0069A0;'>Record no. ".($i)."</strong>- The status must be either current or discharged.</li>";
					$errorCount++;
					}
					elseif($mass_message_access==0){
					$str.="<li><strong style='color:#0069A0;'>Record no. ".($i)."</strong>- The status must be either subscribe or unsubscribe.</li>";
					$errorCount++;
					}
					elseif(empty($data[0]))
					{	$str.="<li><strong style='color:#0069A0;'>Record no. ".($i)."</strong>- The first name field is missing.</li>";
					$errorCount++;
					}
					elseif(empty($data[1]))
					{	$str.="<li><strong style='color:#0069A0;'>Record no. ".($i)."</strong>- The last name field is missing.</li>";
					$errorCount++;
					}
					elseif(empty($data[2]))
					{	$str.="<li><strong style='color:#0069A0;'>Record no. ".($i)."</strong>- The email field is missing.</li>";
					$errorCount++;
					}
					elseif(empty($data[3]))
					{	$str.="<li><strong style='color:#0069A0;'>Record no. ".($i)."</strong>- The status field is missing.</li>";
					$errorCount++;
					}
					elseif(empty($data[4]))
					{	$str.="<li><strong style='color:#0069A0;'>Record no. ".($i)."</strong>- The subscribe/unsubscribe field is missing.</li>";
					$errorCount++;
					}
					elseif(!$this->validateEmail($data[2]))
					{	$str.="<li><strong style='color:#0069A0;'>Record no. ".($i)."</strong>- The email address format is incorrect.</li>";
					$errorCount++;
					}
					elseif($this->checkUserNameExist($data[2])>0)
					{	$str.="<li><strong style='color:#0069A0;'>Record no. ".($i)."</strong>- The email address already exists in the system.</li>";
					$errorCount++;
					}
					else
					{
					include_once("template/therapist/therapistArray.php");
					$tableArray['username'] = $data[2];
					$tableArray['password'] = $this->generateCode(9);
					$tableArray['refering_physician'] = $data[12];
					$tableArray['company'] = "";
					$tableArray['name_first'] = $data[0];
					$tableArray['name_last'] = $data[1];
					$tableArray['mass_message_access'] = $mass_message_access;
					$tableArray['address'] = $data[5];
					$tableArray['address2'] = $data[6];
					$tableArray['city'] = $data[7];
					$tableArray['state'] = $data[8];
					$tableArray['zip'] = $data[9];
					$tableArray['phone1'] = $data[10];
					$tableArray['phone2'] = $data[11];
					$tableArray['status'] = $status;
					$tableArray['usertype_id'] = 1;
					$tableArray['creation_date'] = date('Y-m-d H:i:s');
					$tableArray['modified'] = date('Y-m-d H:i:s');
					//echo "<pre/>";print_r($tableArray);
					if($this->insert($this->config['table']['user'], $tableArray))
					{		$therapistArray = array(
							'therapist_id' => $this->userInfo('user_id'),
							'patient_id' => $this->insert_id(),
							'creation_date' => date('Y-m-d H:i:s'),
							'modified' => date('Y-m-d H:i:s'),
							'status' => '1'
					);
					$new_patient_id = $therapistArray['patient_id'];
					if($this->userInfo('therapist_access')==1)
					{$this->insert($this->config['table']['therapist_patient'], $therapistArray);
					}
					$clinicArray = array(
							'clinic_id' => 	$this->clinicInfo('clinic_id'),
							'user_id'	=>  $new_patient_id,
					);
					// Insert row in clinic_user table to set relation between clinic and patient
					$this->insert($this->config['table']['clinic_user'], $clinicArray);
						
					$clinicName	=	$this->get_clinic_info($new_patient_id,"clinic_name");
					if($tableArray['status']==1){
					$patientReplace = array(
							'username' => $tableArray['username'],
							'password' => $tableArray['password'],
							'url' => $this->config['url'],
							'images_url' => $this->config['images_url'],
							'clinic_name' => $clinicName
					);
					$message = $this->build_template("mail_content/create_new_patient_mail.php",$patientReplace);
					$to = $tableArray['username'];
					$subject = "Your ".$clinicName." Health Record";
					// To send HTML mail, the Content-type header must be set
					$headers  = 'MIME-Version: 1.0' . "\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
					$headers .= "From: " .$clinicName. "<support@txxchange.com>" . "\n";
					$returnpath = '-fsupport@txxchange.com';
					// Mail it
					//echo $message;
					mail($to, $subject, $message, $headers, $returnpath);
					}
					}

					}
					}
					$i++;
						
					}
					fclose($handle);
					}*/

				}
				else
				{   $error[]="Some failure in file uploading occurred, Try later.";
				}
			}
				
		}
			
		// set template variables
		$replace['error'] = $this->show_error($error,$color);
		$replace['browser_title'] = 'Tx Xchange: Upload Patient List';
		$replace['footer'] = $this->build_template($this->get_template("footer"));
		$replace['header'] = $this->build_template($this->get_template("header"));
		$replace['url'] = $this->config['images_url'];
		$replace['sidebar'] = $this->sidebar();
		$tab_array = $this->tab_url();
		$replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$tab_array);
		/*if(isset($errorCount))
			{	if($errorFormat==1)$errorCount=($i-1);
		$replace['successnum']=($i-1)-$errorCount;
		$replace['totalnum']=($i-1);
		if($errorCount>0)
		{	$replace['failnum']="There was a problem with ".$errorCount." of them:";
		$custommessage="Please update the records that contain a problem and upload a new CSV file with only the updated records.";
		}
		$replace['statusMessage']=$str;
		$replace['custommessage']=$custommessage;
		$replace['body'] = $this->build_template($this->get_template("uploadPatientListHeadStatus"),$replace);
		}
		else
		{	$replace['body'] = $this->build_template($this->get_template("uploadPatientListHead"),$replace);
		}*/
			
		$replace['body'] = $this->build_template($this->get_template("uploadPatientListHead"),$replace);
		$replace['get_satisfaction'] = $this->get_satisfaction();
		$this->output = $this->build_template($this->get_template("main"),$replace);
	}
	/**
	 * This function replace links with new url.
	 */
	function replace_link_hyperlink($input){

		//$regex = '|http(s)?://([\w-]+\.)+[\w-]+(/[\w- ./?%&=]*)?|i';
		$regex = '|^((http(s)?://)?(www.))?([\w-]+\.)+[\w-]+(/[\w- ./?%&=]*)?|i';
		preg_match_all($regex,$input,$match);
		$replace = $match[0];

		while( is_array($replace) && list($key,$value) = each($replace) ){

			$patterns = '|' . $value . '|';

			$check_pattern = '|^http(s)?://|';

			if( ! preg_match($check_pattern, $value, $matches, PREG_OFFSET_CAPTURE) ){
				$value_url = "http://" . $value;
			}
			else{
				$value_url = $value;
			}


			$input = preg_replace($patterns, "<a href='{$value_url}' target=new >".$value."</a>", $input);
		}

		return $input;

	}
	/**
	 * Function to add/edit Bulletin board message.
	 * @access public
	 */
	function bulletinBoardHead(){

		// Retrive current message for this clinic.
		$query = "select * from bulletin_board where clinic_id = '{$this->clinicInfo('clinic_id')}' ";
		$result = @mysql_query($query);
		if( $cnt  = @mysql_num_rows($result) > 0 ){
			if( $row = @mysql_fetch_array($result) ){
				$message = $row['message'];
			}
		}
		else{
			$message = "";
		}

		// Add, if there is no message for clinic or update if exist, with message submitted by user.
		if( $this->value('submit') != "" ){

			$pattern = '/<a href.*?=[\s|\'|\"](.*?)[\s|\'|\"].*?>.*?<\/a>/';
			$replacement = '$1';
			$bulletin_board_message = preg_replace($pattern, $replacement, stripslashes($_REQUEST['message']));
			//$bulletin_board_message = $this->replace_link_hyperlink($bulletin_board_message);

			if( $cnt > 0 ){
				$bulletin_board = array(
						'clinic_id' => $this->clinicInfo("clinic_id"),
						'user_id' => $this->userInfo("user_id"),
						//'message' => $this->value('message'),
						'message' => $bulletin_board_message //$bulletin_board_message
				);
				$where = " clinic_id = '{$this->clinicInfo('clinic_id')}' ";
				$this->update("bulletin_board",$bulletin_board,$where);
				$success_flag = 1;
			}
			else{
				$bulletin_board = array(
						'clinic_id' => $this->clinicInfo("clinic_id"),
						'user_id' => $this->userInfo("user_id"),
						//'message' => $this->value('message'),
						'message' => $bulletin_board_message
				);
				$this->insert("bulletin_board",$bulletin_board);
				$success_flag = 1;
			}


			// Retrive updated message from table for clinic.
			$query = "select * from bulletin_board where clinic_id = '{$this->clinicInfo('clinic_id')}' ";
			$result = @mysql_query($query);
			if( $cnt  = @mysql_num_rows($result) > 0 ){
				if( $row = @mysql_fetch_array($result) ){
					$message = $row['message'];
				}
			}
		}

		$replace = array();
                /* code for show telespine menu on list */
        $replace['showmenu']="no";
        if($this->clinicInfo('clinic_id')==$this->config['telespineid']){
            $replace['showmenu']="yes";
        }
        /* code end */
		if( isset($success_flag) && $success_flag == 1 ){
			$replace['error'] = "<strong>Patient bulletin board message has been set successfully.</strong>";
		}
		else{
			$replace['error'] = "";
		}
		$replace['message'] = $message;
		// set template variables
		$replace['browser_title'] = 'Tx Xchange: Bulletin Board';
		$replace['footer'] = $this->build_template($this->get_template("footer"));
		$replace['header'] = $this->build_template($this->get_template("header"));
		$replace['sidebar'] = $this->sidebar();
		$tab_array = $this->tab_url();
		$replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$tab_array);
		$replace['body'] = $this->build_template($this->get_template("bulletinBoard"),$replace);
		$replace['get_satisfaction'] = $this->get_satisfaction();
		$this->output = $this->build_template($this->get_template("main"),$replace);
	}
	/**
	 * Function to show clinic logo image upload interface and process.
	 *
	 * @access public
	 */
	function websiteHead(){

		$replace = array();
                /* code for show telespine menu on list */
        $replace['showmenu']="no";
        if($this->clinicInfo('clinic_id')==$this->config['telespineid']){
            $replace['showmenu']="yes";
        }
        /* code end */
		$userInfo = $this->userInfo();
		$cli_type=$this->getClinicDetails($this->userInfo('user_id'));
		//if($cli_type['clinic_type']==4){
		$replace['shopshow']=1;
		/*}else{
		 $replace['shopshow']='0';
		}*/
		// Block for removing clinic logo.
		if( $this->value('link') == "remove"){
			$err_temp = $this->remove_logo();
			if( $err_temp == true ){
				$error[]   = "Clinic logo successfully removed";
				$replace['error'] = $this->show_error($error,'green');
			}
			else{
				$error[]   = "Failed to remove Clinic logo";
				$replace['error'] = $this->show_error($error,'green');
			}
		}
		// end

		// Block executed after submitting form.
		if( $this->value('website_form_submit') == 'submit' ){

			$clinic_url = $this->value('clinic_url');
			// Check url is empty or not.

			if( isset($clinic_url) && trim($clinic_url) != ""  ){
				// Check valid URL.
				$value = '|' . $clinic_url . '|';
				$check_pattern = '|http(s)?://([\w-]+\.)+[\w-]+(/[\w- ./?%&=]*)?|';
				$check = preg_match($check_pattern, $value, $matches, PREG_OFFSET_CAPTURE);
				if( $check === false || ($check == 0)){
					$error[] = "Incorrect URL format";
				}

			}
			$clinic_overview = $this->value('clinic_overview');
			if( isset($clinic_overview) && trim($clinic_overview) != ""  ){
				// Check over view
			}

			// block executed after submitting form with file upload.
			if( is_array($_FILES) && $_FILES['clinic_logo']['error'] == 0 ){
				$image_info = getimagesize($_FILES['clinic_logo']['tmp_name']);
				$flag_image_info = false;
				// print_r($image_info);
				$width = $this->config['clinic_logo_image']['width'];
				$height = $this->config['clinic_logo_image']['height'];

				if(isset($image_info[0]) && $image_info[0] > $width ){
					$error[] = "Clinic logo image must be less than or equal to $width pixels in width";
					$flag_image_info = true;
				}
				if(isset($image_info[1]) && $image_info[1] > $height ){
					$error[] = "Clinic logo image must be less than or equal to $height pixels in height";
					$flag_image_info = true;
				}

				$checkFile=$_FILES['clinic_logo']['name'];
				$extarray=explode('.',$checkFile);
				$counter=count($extarray);
				$ext=$extarray[$counter-1];
				if(strtolower($ext)=='jpeg' or strtolower($ext)=='gif' or strtolower($ext)=='png' or strtolower($ext)=='jpg'){

				}else{
					$error[] = "Please upload jpeg,gif,png file only";
					$flag_image_info = true;
				}
				// copy logo to cliniclog directory and save file name to database.
				if( $flag_image_info == false ){
					$err = $this->copy_clinic_logo();
					if( $err !== true){
						$error[] = $err;
					}

				}
			}
			else{
				// Block executed, if file is uploaded.testing
				if( is_array($_FILES) && $_FILES['clinic_logo']['error'] == 4 ){
					$clinic_id = $this->clinicInfo("clinic_id");
					if($error==""){
						$clinic_array = array(
								'clinic_website_address' => $this->value('clinic_url'),
								'clinic_overview'=>$this->value('clinic_overview'),
						);
						$where = " clinic_id = '{$clinic_id}' ";
						$this->update("clinic",$clinic_array,$where);
					}
				}
			}

			// check error occoured or not.
			if( is_array($error) && count($error) > 0 ){
				$replace['error'] = $this->show_error_notbold($error);
			}
			else{
				$error[]   = "Company information successfully saved";
				$replace['error'] = $this->show_error($error,'green');
			}
			// End
		}
		// End
		// Block executed if form is not submitted.
		else{
			$clinic_id = $this->clinicInfo("clinic_id");
			if( is_numeric($clinic_id) && $clinic_id > 0 ){
				$clinic_url = $this->get_field($clinic_id, "clinic", "clinic_website_address" );
			}
			if( is_numeric($clinic_id) && $clinic_id > 0 ){
				$clinic_overview = $this->get_field($clinic_overview, "clinic", "clinic_overview" );
			}
		}
		//  End

		// Make links for show and remove logo.
		$query = " select * from clinic where clinic_id = '{$this->clinicInfo("clinic_id")}' ";
		$result = @mysql_query($query);
		if( $row = @mysql_fetch_array($result) ){
			$clinic_logo = $row['clinic_logo'];
			$clinic_overview=$row['clinic_overview'];
		}
		 
		if( !empty($clinic_logo) || trim($clinic_logo) != "" ){

			$replace['show_link'] = "<a href='javascript:void(0)' onclick=\"toggleMediaDisplay('media_pic1')\">&nbsp;Show/Hide</a>";
			$replace['show_image'] = $clinic_logo;
			$replace['remove'] = "<a href='index.php?action=websiteHead&link=remove' >Remove</a>";
		}
		// End



		// set template variables

		$replace['clinic_url'] = $clinic_url;
		$replace['clinic_overview'] = $clinic_overview;
		$replace['width'] = $this->config['clinic_logo_image']['width'];
		$replace['height'] = $this->config['clinic_logo_image']['height'];

		$replace['browser_title'] = 'Tx Xchange: Company Details';
		$tab_array = $this->tab_url();
		$replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$tab_array);
		$replace['footer'] = $this->build_template($this->get_template("footer"));
		$replace['header'] = $this->build_template($this->get_template("header"));
		$replace['sidebar'] = $this->sidebar();
		$replace['body'] = $this->build_template($this->get_template("website"),$replace);
		$replace['get_satisfaction'] = $this->get_satisfaction();
		$this->output = $this->build_template($this->get_template("main"),$replace);
	}
	/**
	 * Function to copy clinic logo image .
	 *
	 * @access public
	 */
	function copy_clinic_logo(){

		$handle = new upload($_FILES['clinic_logo']);
		if ($handle->uploaded) {

			// New name of file being uploaded.
			$clinic_id = $this->clinicInfo("clinic_id");
			$new_file_name = "";
			if( is_numeric($clinic_id) ){
				$file_name = split("[/\\.]", $_FILES['clinic_logo']['name']) ;
				$new_file_name = $clinic_id . "_" . $file_name[0];
				$handle->file_new_name_body   =  $new_file_name;
			}
			 
			$handle->file_overwrite = true;
			$handle->allowed = array(
					"image/gif",
					"image/jpeg",
					"image/pjpeg",
					"image/png",
					"image/x-png",
			);
			$handle->process($_SERVER['DOCUMENT_ROOT'] . '/asset/images/clinic_logo/');
			 
			if ($handle->processed) {
				$new_file_name_db = $handle->file_dst_name_body . "." . $handle->file_dst_name_ext;
				$clinic_url = $this->value('clinic_url');
				// Check url is empty or not.

				if( isset($clinic_url) && trim($clinic_url) != ""  ){
					// Check valid URL.
					$value = '|' . $clinic_url . '|';
					$check_pattern = '|http(s)?://([\w-]+\.)+[\w-]+(/[\w- ./?%&=]*)?|';
					$check = preg_match($check_pattern, $value, $matches, PREG_OFFSET_CAPTURE);
					if( $check === false || ($check == 0)){
						$error[] = "Incorrect URL format";
					}

				}
				 
				if($error==""){
					$clinic_array = array(
							'clinic_website_address' => $this->value('clinic_url'),
							'clinic_logo' => $new_file_name_db,
							'clinic_overview'=>$this->value('clinic_overview')
					);
					$where = " clinic_id = '{$clinic_id}' ";
					$this->update("clinic",$clinic_array,$where);
				}
				$handle->clean();
			} else {
				$error = $handle->error;
				return $error;
			}
		}
		 
		return true;
	}
	/**
	 * Function to delete clinic logo image.
	 *
	 * @access public
	 */
	function remove_logo(){
		$clinic_id = $this->clinicInfo('clinic_id');
		if( isset($clinic_id) && is_numeric($clinic_id) > 0 ){
			$clinic_url = $this->get_field($clinic_id,"clinic","clinic_logo");
			if( is_string($clinic_url) && $clinic_url != "" ){
				$path = $_SERVER['DOCUMENT_ROOT'] . '/asset/images/clinic_logo/';
				if(@unlink($path . $clinic_url)){
					$clinic_arr = array(

							'clinic_logo' => ""
					);
					$where = " clinic_id = '{$clinic_id}' ";
					$this->update("clinic",$clinic_arr,$where);
					return true;
				}
			}
		}

		return false;
	}
	/**
	 * Function to show best practices..
	 *
	 * @access public
	 */
	function cashBasedProgramHead(){
		// set templating variables
                $replace=array();
                /* code for show telespine menu on list */
        $replace['showmenu']="no";
        if($this->clinicInfo('clinic_id')==$this->config['telespineid']){
            $replace['showmenu']="yes";
        }
        /* code end */
		$replace['browser_title'] = 'Tx Xchange: Maintenance Patients';
		$replace['header'] = $this->build_template($this->get_template("header"));
		$replace['sidebar'] = $this->sidebar();
		$replace['footer'] = $this->build_template($this->get_template("footer"));

		if($this->value('sort') != ""){
			if($this->value('order') == 'desc' ){
				$orderby = " order by {$this->value('sort')} desc ";
			}
			else{
				$orderby = " order by {$this->value('sort')} ";
			}
		}
		else{
			$orderby = " order by u.name_last ";
		}

		/**
		 *    Query for generating the patient list
		 *    CONDITIONS :
		 *    1.  Patient must associated with the clinic whose Therapist(Accound Admin) is logged in
		 *    2.  Patient must have one associated therapist
		 *    3.  Patient status != 3, either Active or InActive
		 */
		 
		$privateKey = $this->config['private_key'];
		$clinic_id = $this->clinicInfo("clinic_id");
		$query = "SELECT pu.p_type,u.user_id,u.username,
		AES_DECRYPT(UNHEX(u.name_title),'{$privateKey}') as name_title,
		AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}') as name_first,
		AES_DECRYPT(UNHEX(u.name_last),'{$privateKey}') as name_last,
		date_format(u.last_login,'%m/%d/%Y %H:%i %p') as last_login,pu.start_date
		FROM program_user pu
		INNER JOIN clinic_user cu ON pu.u_id = cu.user_id AND cu.clinic_id = '{$clinic_id}'
		INNER JOIN user u ON u.user_id = pu.u_id {$orderby}";

		// pagination
		$link = $this->pagination($rows = 0,$query,'cashBasedProgramHead');
		$replace['link'] = $link['nav'];
		$result = $link['result'];

		// check sql query result
		if(is_resource($result)){
			if( @mysql_num_rows($result) > 0 ){
				while($row = $this->fetch_array($result)){
					$replace['classname'] = (++$k%2) ? 'line2' : 'line1';
					$replace['userId'] = $row['user_id'];
					$replace['fullName'] = $row['name_title'].' '.$row['name_first'].' '.$row['name_last'];
					$replace['username'] = $row['username'];
					$replace['planType'] = $this->get_field($row['p_type'],"program","p_name");
					$replace['start_date'] = $this->formatDate($row['start_date'],"m/d/Y");
					$replace['lastLogin'] = $row['last_login']!=""?$row['last_login']:"Never";
					$rowdata .= $this->build_template($this->get_template("listRecord"),$replace);
				}
			}
			else{
				$rowdata = $this->build_template($this->get_template("recordNotFound"),$replace);
			}
		}
		else{
			$rowdata = $this->build_template($this->get_template("recordNotFound"),$replace);
		}
		$listHead = array(
				'u.name_first' => 'Full Name',
				'u.username' => 'User Name',
				'pu.start_date' => 'Start Date',
				'u.last_login' => 'Last Login'
		);
		$replace['listHead'] = $this->build_template($this->get_template("listHead"),$this->table_heading($listHead,"u.name_first"));
		$replace['rowdata'] = $rowdata;
		$tab_array = $this->tab_url();
		$replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$tab_array);
		$replace['body'] = $this->build_template($this->get_template("cash_based_program"),$replace);
		$replace['get_satisfaction'] = $this->get_satisfaction();
		$this->output = $this->build_template($this->get_template("main"),$replace);
	}
	/**
	 * This function renders sidebar of headaccountadmin user.
	 */
	function sidebar(){
		//code for checking the trial period days left for Provider/AA
		$freetrialstr=$this->getFreeTrialDaysLeft($this->userInfo('user_id'));
		$data = array(
				'name_first' => $this->userInfo('name_first'),
				'name_last' =>  $this->userInfo('name_last'),
				'sysadmin_link' => $this->sysadmin_link(),
				'therapist_link' => $this->therapist_link(),
				'freetrial_link' => $freetrialstr
		);
		return $this->build_template($this->get_template("sidebar"),$data);
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
	 * Copy published plan to newly created Therapist
	 */
	function copyPublishPlan($user_id,$clinic_id){

		if( is_numeric($clinic_id) && $clinic_id!= 0 ){
			$parent_clinic_id = $this->get_field($clinic_id,'clinic','parent_clinic_id');
			if( is_numeric($parent_clinic_id)){
				if( $parent_clinic_id == 0){
					$query = "SELECT cu.user_id
					FROM clinic_user AS cu
					INNER JOIN clinic AS cl ON cu.clinic_id = cl.clinic_id
					INNER JOIN user as us on us.user_id = cu.user_id and us.status = 1 and us.usertype_id  = 2
					WHERE (cl.parent_clinic_id = '{$clinic_id}' or cl.clinic_id = '{$clinic_id}')
					AND cl.status =1
					";
				}
				else{
					$query = "SELECT cu.user_id
					FROM clinic_user AS cu
					INNER JOIN clinic AS cl ON cu.clinic_id = cl.clinic_id
					INNER JOIN user as us on us.user_id = cu.user_id and us.status = 1 and us.usertype_id  = 2
					WHERE cl.parent_clinic_id = '{$parent_clinic_id}' or cl.clinic_id = '{$parent_clinic_id}'
					AND cl.status =1
					";
				}

				if( !empty($query) ){
					$sql = "select * from plan
					where
					status = 1
					and user_type = 2
					and is_public = 1
					and patient_id is null
					and user_id in

					(
					$query
					)";

					$result = @mysql_query($sql);
					while( $row = @mysql_fetch_array($result)){
						$this->copy_plan($user_id,$row['plan_id']);
					}
				}
			}
		}
		return "";
	}

	/**
	 * This function will copy those articles,  which are associated with plan.
	 *
	 */
	function copy_article($newlyPlanId,$plan_id){

		{//Plan_Article Block

			$queryPlanArticle = "SELECT * FROM plan_article WHERE status = 1 AND plan_id = '{$plan_id}' " ;
			$resultPlanArticle = $this->execute_query($queryPlanArticle);

			if($this->num_rows($resultPlanArticle)!= 0)
			{
				while($row = $this->fetch_array($resultPlanArticle))
				{

					$insertArr = array(
							'plan_id'=> $newlyPlanId,
							'article_id' => $row['article_id'],
							'creation_date' => date('Y-m-d H:i:s',time()),
							'status'=> $row['status']
					);

					$result = $this->insert('plan_article',$insertArr);

				}
			}
		}
	}
	/**
	 * This function copies plan information from plan for same clinic user.
	 *
	 */
	function copy_plan($userId,$planId,$new_name=""){
		if( is_numeric($userId) && is_numeric($planId) ){
			 
			if($new_name == "" ){
				// Retrive plan from plan table.
				$query = "select * from plan where plan_id = '{$planId}' ";
				$result = @mysql_query($query);
				$row = @mysql_fetch_array($result);
				$new_name = $row['plan_name'];
			}

			// Create array for inserting record.
			$insertArr = array(
					'plan_name'=> $new_name,
					'parent_template_id' => NULL,
					'user_id' => $userId,
					'patient_id' => NULL,
					'user_type' => 2,
					'is_public' => NULL,
					'creation_date' => $row['creation_date'],
					'status' => 1
			);

			// Insert record.
			$result = $this->insert('plan',$insertArr);

			//Get new plan id
			$newlyPlanId = $this->insert_id();

			// Copy treatments associated with planId.
			if(is_numeric($newlyPlanId)){
				// copy treatments in the plan.
				$this->copy_plan_treatment($newlyPlanId,$planId);
				// copy articles in the plan.
				$this->copy_article($newlyPlanId,$planId);
			}
		}

	}
	/**
	 * @desc This function copies all Treatments associated with the given plan.
	 */
	function copy_plan_treatment($newlyPlanId,$planId){

		// Check planId is numeric or not.
		if(is_numeric($planId)){

			// Get treatment from plan_treatment table.
			$queryPlanTreatment = "SELECT * FROM plan_treatment WHERE plan_id = '{$planId}' " ;
			$resultPlanTreatment = @mysql_query($queryPlanTreatment);

			// Check for number of treatment in the plan. Must be greater then Zero.
			if( @mysql_num_rows($resultPlanTreatment) > 0 )
			{
				// Create Array for Treatment present in the planId.
				while($row = $this->fetch_array($resultPlanTreatment))
				{

					$row['sets'] = (empty($row['sets']))? "":$row['sets'];
					$row['reps'] = (empty($row['reps']))? "":$row['reps'];
					$row['hold'] = (empty($row['hold']))? "":$row['hold'];
					$row['lrb'] = (empty($row['lrb']))? "":$row['lrb'];
					$row['treatment_order'] = (empty($row['treatment_order']))? "":$row['treatment_order'];

					$insertArr = array(
							'plan_id'=> $newlyPlanId,
							'treatment_id' => $row['treatment_id'],
							'instruction' => $row['instruction'],
							'sets'=> $row['sets'],
							'reps'=> $row['reps'],
							'hold' => $row['hold'],
							'benefit'=> $row['benefit'],
							'lrb'=> $row['lrb'],
							'treatment_order' => $row['treatment_order'],
							'creation_date' => $row['creation_date']
					);

					// Insert Treatment in plan_treatment table for PlanId.
					$result = $this->insert('plan_treatment',$insertArr);

				}
			}
		}
	}

	/**
	 * This function opens up the DHTML popup prompting provider to activate his trial account.
		* @access public
		*/
	function headtrialperiodprompt(){

		$replace['body'] = $this->build_template($this->get_template("freetrialperiod"),$replace);
		$this->output = $this->build_template($this->get_template("main"),$replace);
	}
	// Function ends here

	/**
	 * This function displays list clinics in an account.
	 */
	function txReferralSetting(){
		$clinicId = $this->clinicInfo("clinic_id");
		$userInfo = $this->userInfo();
		if( !is_numeric($clinicId) || $clinicId == 0 ){
			header("location:index.php?action=logout");
			exit();
		}
		$replace = array();
		if($this->value('action_submit')=="submit"){
			if( is_numeric($clinicId) ){
				$sqlUser = "select * from referral_limit where clinic_id = '{$clinicId}'";
				$result=$this->execute_query($sqlUser);
			}
			if($this->num_rows($result)!= 0)
			{
				$this->validateFormReferral(false);

				if($this->error == "")
				{
					$row = $this->fetch_array($result);
					$this->update('referral_limit',array('clinic_refferal_limit'=>$this->value('clinic_refferal_limit'),'clinic_user_limit'=>$this->value('clinic_user_limit')),'id='.$row['id']);
				}else
				{

					$replace['error'] = $this->error;
				}
				 
			}else{
				// add
				$this->validateFormReferral(false);
				if($this->error == "")
				{
					$insertArr = array(
							'user_id'                => $userInfo['user_id'],
							'clinic_id'              => $clinicId,
							'clinic_refferal_limit'  =>	$this->value('clinic_refferal_limit'),
							'clinic_user_limit'      =>	$this->value('clinic_user_limit'),
							'status'                 =>  1
					);

					$result = $this->insert('referral_limit',$insertArr);
				}else
				{
					$replace['error'] = $this->error;
				}


			}

		}






		// Retian page value.
		$arr = array(
				'clinic_id' => $clinicId
		);
		$msg = $this->value('msg');
		if( !empty($msg) ){
			$replace['error'] = $this->errorClinicListModule($msg);
		}
		$searchString = $this->value('search');
		if( !empty($searchString) ){
			$searchString = $this->value('search');
			$searchWhere = " and clinic_name like '%{$this->value('search')}%' ";
		}
		//Get the clinic list
		if( is_numeric($clinicId) ){
			$sqlUser = "select * from referral_limit where clinic_id = '{$clinicId}'";
			$result=$this->execute_query($sqlUser);
		}
		if($this->num_rows($result)!= 0)
		{
			$row = $this->fetch_array($result);
			$replace['clinic_refferal_limit'] = $row['clinic_refferal_limit'];
			$replace['clinic_user_limit'] = $row['clinic_user_limit'];
		}
		else
		{
			$replace['clinic_refferal_limit'] = 0;
			$replace['clinic_user_limit'] = 0;
		}
		$replace['clinic_id'] = $clinicId;
		// $replace['header'] = $this->build_template($this->get_template("header"));
		// $replace['footer'] = $this->build_template($this->get_template("footer"));
		// $replace['sidebar'] = $this->sidebar();
		$replace['body'] = $this->build_template($this->get_template("txReferralSetting"),$replace);
		//$replace['browser_title'] = "Tx Xchange: Clinic List";
		$replace['get_satisfaction'] = $this->get_satisfaction();
		$this->output = $this->build_template($this->get_template("main"),$replace);

	}

	/**
	 * This function validate the referral form.
	 */
	function validateFormReferral($uniqueId)
	{
		// creating validation object
		$objValidationSet = new ValidationSet();
			
		// validating username (email address)
		$objValidationSet->addValidator(new  StringMinLengthValidator('clinic_refferal_limit', 1, "Referrals sent by all patients in a month cannot be empty",$this->value('clinic_refferal_limit')));
		$objValidationSet->addValidator(new NumericOnlyValidator('clinic_refferal_limit','null',"Please enter the numeric value",$this->value('clinic_refferal_limit')));
		if($this->value('clinic_refferal_limit')==0){
			$objValidationErr = new ValidationError('clinic_user_limit',"Referrals sent by all patients in a month cannot be zero.");
			$objValidationSet->addValidationError($objValidationErr);
		}

		// validating first name field
		$objValidationSet->addValidator(new  NumericOnlyValidator('clinic_user_limit','null', "Please enter Numeric value in referrals sent by any one patient in a month ",$this->value('clinic_user_limit')));
			
		if ($this->value('clinic_refferal_limit') !=0  and $this->value('clinic_user_limit') !=0){
			if($this->value('clinic_refferal_limit') < $this->value('clinic_user_limit')){
				$objValidationErr = new ValidationError('clinic_user_limit',"Referrals sent by any one patient in a month can not be greater than referral sent by all patients in a month.");
				$objValidationSet->addValidationError($objValidationErr);
			}


		}
		$objValidationSet->validate();

		$formType='Add';
		if ($objValidationSet->hasErrors())
		{
			if($formType == "Add" )
			{
				//$arrayFields = array("username","new_password","question_id","answer","name_first","name_last","address","address2","city","zip","usertype_id","clinic_id");
				$arrayFields = array("clinic_refferal_limit","clinic_user_limit");
			}
			for($i=0;$i<count($arrayFields);++$i)
			{
				$errorMsg = $objValidationSet->getErrorByFieldName($arrayFields[$i]);
				//echo "msg : $errorMsg<br>";
					
				if ($errorMsg != "")
				{
					$this->error = $errorMsg."<br>";
					break;
				}
			}

		}
		else
		{

			$this->error = "";
		}
			
	}

	/**
	 * This function validate the E-Helth Service form.
	 */
	function validateFormHealthservice($uniqueId)
	{
		//echo "<pre>";print_r($_REQUEST);EXIT;

		//echo "sss".$this->value('onetime_price');exit;
		// echo html_entity_decode($this->value('subs_title'), ENT_QUOTES, "UTF-8");// creating validation object
		$objValidationSet = new ValidationSet();
		if(($this->value('subs_title')=='') and ($this->value('subs_price')=='') and ($this->value('subs_description')=='' )){
			$objValidationErr = new ValidationError('subs_title',"Please enter information in the service fields so your patients know what they are signing up for");
			$objValidationSet->addValidationError($objValidationErr);
		}

		// validating username (email address)
		$objValidationSet->addValidator(new  StringMinLengthValidator('subs_title', 1, "Please enter your E-Health Service Name",$this->value('subs_title')));
		$objValidationSet->addValidator(new  StringMaxLengthValidator('subs_title', 41, "Please Enter Maximum 40 character in E-Health Service Name",html_entity_decode($this->value('subs_title'), ENT_QUOTES, "UTF-8")));

		if($this->value('paymentType') == '0' ) {

			$objValidationSet->addValidator(new StringMinLengthValidator('subs_price',1,"Please enter the price you will charge for this service on a monthly basis",$this->value('subs_price')));


		} else {
			if($this->value('healthEnable') == '1') {
				$objValidationSet->addValidator(new StringMinLengthValidator('ehsTimePeriod',1,"Please select the duration for one time payment",$this->value('ehsTimePeriod')));
			}

			$objValidationSet->addValidator(new StringMinLengthValidator('onetime_price',1,"Please enter the price you will charge for this service on a one time payment basis",$this->value('onetime_price')));

		}


		 
		//$objValidationSet->addValidator(new  StringMaxLengthValidator('subs_price', 10, "Please Enter Maximum 7 character in Price",$this->value('subs_price')));
		if($this->value('subs_price') > 999999){
			$objValidationErr = new ValidationError('subs_price',"Please Enter Maximum 7 character in Price");
			$objValidationSet->addValidationError($objValidationErr);
		}
		if(preg_match('/^[0-9]*+(\.[0-9]{0,2})?$/', $this->value('subs_price'))=='0' and $this->value('subs_price')!=''){
			$objValidationErr = new ValidationError('subs_price',"Please enter Numeric value in  price like 30.00");
			$objValidationSet->addValidationError($objValidationErr);
		}
		//$objValidationSet->addValidator(new NumericOnlyValidator('subs_price',array('.'),"Please enter Numeric value in  price",$this->value('subs_price')));
		if($this->value('onetime_price')=='0' && $this->value('paymentType') == '1' ){
			$objValidationErr = new ValidationError('onetime_price',"Price should be greater than 0.");
			$objValidationSet->addValidationError($objValidationErr);
		}

		if($this->value('onetime_price') > 999999){
			$objValidationErr = new ValidationError('onetime_price',"Please Enter Maximum 7 character in Price");
			$objValidationSet->addValidationError($objValidationErr);
		}
		if(preg_match('/^[0-9]*+(\.[0-9]{0,2})?$/', $this->value('onetime_price'))=='0' and $this->value('onetime_price')!=''){
			$objValidationErr = new ValidationError('onetime_price',"Please enter Numeric value in  price like 30.00");
			$objValidationSet->addValidationError($objValidationErr);
		}
		//$objValidationSet->addValidator(new NumericOnlyValidator('subs_price',array('.'),"Please enter Numeric value in  price",$this->value('subs_price')));
		if($this->value('subs_price')=='0' && $this->value('paymentType') == '0' ){
			$objValidationErr = new ValidationError('subs_price',"Price should be greater than 0.");
			$objValidationSet->addValidationError($objValidationErr);
		}

		// validating first name field html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8")
		//echo  strlen(($this->value('subs_feature1')));
		$objValidationSet->addValidator(new  StringMinLengthValidator('subs_description', 1, "Please enter your E-Health Service Description",html_entity_decode($this->value('subs_description'))));
		$objValidationSet->addValidator(new  StringMaxLengthValidator('subs_description', 900, "Please enter maximum 750 characters in E-Health Service description",html_entity_decode($this->value('subs_description'), ENT_QUOTES, "UTF-8")));
		$objValidationSet->addValidator(new  StringMaxLengthValidator('subs_feature1', 54, "Please enter maximum 50 characters in Key Features",html_entity_decode($this->value('subs_feature1'), ENT_QUOTES, "UTF-8")));
		$objValidationSet->addValidator(new  StringMaxLengthValidator('subs_feature2', 54, "Please enter maximum 50 characters in Key Features",html_entity_decode($this->value('subs_feature2'), ENT_QUOTES, "UTF-8")));
		$objValidationSet->addValidator(new  StringMaxLengthValidator('subs_feature3', 54, "Please enter maximum 50 characters in Key Features",html_entity_decode($this->value('subs_feature3'), ENT_QUOTES, "UTF-8")));
		$objValidationSet->addValidator(new  StringMaxLengthValidator('subs_feature4', 54, "Please enter maximum 50 characters in Key Features",html_entity_decode($this->value('subs_feature4'), ENT_QUOTES, "UTF-8")));
		$objValidationSet->addValidator(new  StringMaxLengthValidator('subs_feature5', 54, "Please enter maximum 50 characters in Key Features",html_entity_decode($this->value('subs_feature5'), ENT_QUOTES, "UTF-8")));
		$objValidationSet->addValidator(new  StringMaxLengthValidator('subs_feature6', 54, "Please enter maximum 50 characters in Key Features",html_entity_decode($this->value('subs_feature6'), ENT_QUOTES, "UTF-8")));
		$objValidationSet->addValidator(new  StringMaxLengthValidator('subs_feature7', 54, "Please enter maximum 50 characters in Key Features",html_entity_decode($this->value('subs_feature7'), ENT_QUOTES, "UTF-8")));


		$objValidationSet->validate();
		$formType='Add';
		if ($objValidationSet->hasErrors())
		{
			if($formType == "Add" )
			{
				$arrayFields = array("subs_title","subs_description","subs_price","subs_feature1","subs_feature2","subs_feature3","subs_feature4","subs_feature5","subs_feature6","subs_feature7","ehsTimePeriod","onetime_price");
			}
			for($i=0;$i<count($arrayFields);++$i)
			{
				$errorMsg = $objValidationSet->getErrorByFieldName($arrayFields[$i]);
				//echo "msg : $errorMsg<br>";
					
				if ($errorMsg != "")
				{
					$this->error = $errorMsg."<br>";
					break;
				}
			}

		}
		else
		{

			$this->error = "";
		}
			
	}
	/**
	 * This function displays ON/OFF settings for add on services.
	 * @modification 10/02/2012
	 * @New services - widgets, notification and integrated wellness store added
	 */
	function addonServicesHead(){

		$from_email_address = $this->config['from_email_address'];
		$clinicId = $this->clinicInfo("clinic_id");
		$userInfo = $this->userInfo();
		$clinicURL = $this->getClinicURL($clinicId);

		//If clinic id empty or not valied then redirection to logout page
		if( !is_numeric($clinicId) || $clinicId == 0 ) {
			header("location:index.php?action=logout");
			exit();
		}
		//End here

		//If clinic type is 4 ie Naturopathic then
		$replace = array();
                /* code for show telespine menu on list */
        $replace['showmenu']="no";
        if($this->clinicInfo('clinic_id')==$this->config['telespineid']){
            $replace['showmenu']="yes";
        }
        /* code end */
		$cli_type = $this->getClinicDetails($this->userInfo('user_id'));
		if($cli_type['clinic_type'] == 4) {
			$replace['shopshow'] = 1;
		}else{
			$replace['shopshow'] = '0';
		}
		//End here
		 $replace['autoschedule'] = '';
                 $replace['message'] = '';
                 $replace['teleconference'] = '';
                 
		//Check for the integrated wellness store
		$sqlClinic= "select storeURL  from clinic where clinic_id = '{$clinicId}'";
		$resultsqlClinic = $this->execute_query($sqlClinic);
		$replace['wellnessStoreUrl'] == '';
		if($this->num_rows($resultsqlClinic)!= 0)   {
			$rowClinic = $this->fetch_array($resultsqlClinic);
			if($rowClinic['storeURL']!= '') {
				$replace['wellnessStoreUrl'] = $rowClinic['storeURL'];
			}
			else {
				$replace['wellnessStoreUrl'] = '';
			}
		}

		//End here


		//Form submission starts here
		if($this->value('action_submit') == "submit") {


			//Code for the referal mimit
			if( is_numeric($clinicId) ){
				$sqlUser = "select * from referral_limit where clinic_id = '{$clinicId}'";
				$result=$this->execute_query($sqlUser);
			}
			if($this->value('add-on') == 1)
				$referral_report='1';
			else
				$referral_report='0';

			//End here

			//Provider Store
			if($this->value('store')==1)
				$store=1;
			else
				$store='0';
			//Integarted Schedule check
			if($this->value('schedul')==1)
				$schedul=1;
			else
				$schedul='0';
			//E- Health Service chek
			if($this->value('healthEnable')==1)
				$healthprogram = 1;
			else
				$healthprogram = '0';
			////Integrated Wellness Store
			$wellness_store_check_val = 0;
			if($this->value('wellness_store_check') == 1){
				$wellness_store_check = 1;
				$wellness_store_check_val = 1;
			}else{
				$wellness_store_check = '0';
				$wellness_store_check_val = '0';
			}
			//News Widget check
			if($this->value('newsWidget') == 1)
				$newsWidget = 1;
			else
				$newsWidget = '0';
                        //message turn off 
                        if($this->value('messagedisable') == 1)
				$messagedisable = 1;
			else
				$messagedisable = '0';
                        
                        //teleconfenren turn off
                        if($this->value('teleconferencedisable') == 1)
				$teleconferencedisable = 1;
			else
				$teleconferencedisable = '0';
                        
			//Goal Notification Check
			if($this->value('goal_notification') == 1)
				$goal_notification = 1;
			else
				$goal_notification = '0';
			//Patient Email Notification Check
			if($this->value('patient_email_notification') == 1)
				$patient_email_notification = 1;
			else
				$patient_email_notification = '0';
			$wellnessStoreUrl = $this->value('wellnessStoreUrl');


			// Integrated Wellness Store Checks
			//Check url is empty or not.
			if($wellness_store_check == 1) {
				if( isset($wellnessStoreUrl) && trim($wellnessStoreUrl) != ""  ) {
					// Check valid URL.
					$value = '|' . $wellnessStoreUrl . '|';
					$check_pattern = '|http(s)?://([\w-]+\.)+[\w-]+(/[\w- ./?%&=]*)?|';
					$check = preg_match($check_pattern, $value, $matches, PREG_OFFSET_CAPTURE);
					if( $check === false || ($check == 0)){
						$replace['addonval'] = 'wellness_store';
						$replace['error'] = "Incorrect URL format";
						$wellness_store_check_val=0;
					}
				}else{
					if($wellness_store_check==1){
						$replace['addonval'] = 'wellness_store';
						$replace['error'] = "Please enter store web Address/URL ";
						$wellness_store_check_val=0;
					}
				}
				//Both store cannot be turned on, If it happens then checks for them in below conditions
				$sqlAddon = "select * from addon_services where clinic_id = '{$clinicId}'";
				$resultAddon=$this->execute_query($sqlAddon);
				if($this->num_rows($resultAddon)!= 0) {
					$rowcheckaddon=$this->fetch_object($resultAddon);
					if($store  == 1 && $wellness_store_check == '1') {
						if($rowcheckaddon->store  == 1) {
							$replace['error'] = "Provider Branded Wellness Store is already turned on. Please turn off it first to use Integrated Wellness store";
							$replace['addonval'] = 'wellnessStore';
							$wellness_store_check_val = 0;
						}
						elseif($rowcheckaddon->wellness_store  == 1) {
							$replace['error'] = "Integrated Wellness Store is already turned on. Please turn off it first to use Provider Branded Wellness store";
							$replace['addonval'] = 'wellness_store';
							$wellness_store_check_val = 1;
						}

						else  {
							$replace['addonval'] = 'wellnessStore';
							 
							$replace['error'] = "Both Provider Branded Wellness Store and Integrated Wellness Store can not be
							turned on at same time. Please turn on only one.";
						}
					}

				}

			}

			// Integrated Wellness Store Checks ends here


			if($replace['error'] == '') {

				if($wellnessStoreUrl == '') {
					$wellnessStoreUrl = '';
				}
				$updateStoreURL = array(
						'storeURL'        =>  $wellnessStoreUrl
				);
				$resultupdateStoreURL = $this->update('clinic',$updateStoreURL,'clinic_id='.$clinicId);

				//Check for the integrated wellness store
				$sqlClinic= "select storeURL  from clinic where clinic_id = '{$clinicId}'";
				$resultsqlClinic = $this->execute_query($sqlClinic);
				$replace['wellnessStoreUrl'] == '';
				if($this->num_rows($resultsqlClinic)!= 0)   {
					$rowClinic = $this->fetch_array($resultsqlClinic);
					if($rowClinic['storeURL']!= '') {
						$replace['wellnessStoreUrl'] = $rowClinic['storeURL'];
					}
					else {
						$replace['wellnessStoreUrl'] = '';
					}
				}
			}

			//End here
       if($this->value('autoscheduleEnable')=='')
                                    $automatic_scheduling   = $this->value('autoscheduleEnable');
                                else
                                    $automatic_scheduling   = 0;

                                if($this->value('messagedisable')=='')
                                    $message                =   $this->value('messagedisable');
                                else
                                    $message                =   0;
                                if($this->value('teleconferencedisable')=='')
                                    $teleconference         =   $this->value('teleconferencedisable');
                                else
                                    $teleconference         =   0;
			 
			if($replace['error'] == "") {
				$sqlAddon = "select * from addon_services where clinic_id = '{$clinicId}'";
				$resultAddon=$this->execute_query($sqlAddon);
				if($this->num_rows($resultAddon)!= 0) {
					$SubjectLine="";
					$rowcheckaddon=$this->fetch_object($resultAddon);
					//Integrated store mail starts here
					//echo $rowcheckaddon->wellness_store."---".$wellness_store_check;
					if($rowcheckaddon->wellness_store!= $wellness_store_check) {
						if($wellness_store_check == 1) {
							$SubjectLine="Integrated Wellness Store turned on by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
						}
						else
						{
							$SubjectLine="Integrated Wellness Store turned off by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
						}
						$to = $from_email_address;
						// To send HTML mail, the Content-type header must be set
						$headers  = 'MIME-Version: 1.0' . "\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						//$headers .= "From: <support@txxchange.com>" . "\n";
						//$returnpath = '-fsupport@txxchange.com';
						$clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
						if( $clinic_channel == 1){
							$headers .= "From:  <".$this->config['email_tx'].">" . "\n";
							$returnpath = "-f".$this->config['email_tx'];
						}else{
							$headers .= "From: <".$this->config['email_wx'].">" . "\n";
							$returnpath = '-f'.$this->config['email_wx'];
						}
						$headers .= 'Bcc: sanjay.gairola@hytechpro.com' . "\r\n";
						@mail($to, $SubjectLine, '', $headers, $returnpath);

					}

					//Integrated store mail ends here

					//Patient goal notification mail starts here

					/* if($rowcheckaddon->goal_notification!= $goal_notification) {
					 if($goal_notification == 1) {
					$SubjectLine="Goal notification turned on by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
					}
					else
					{
					$SubjectLine="Goal notification turned off by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
					}
					$to = $from_email_address;
					// To send HTML mail, the Content-type header must be set
					$headers  = 'MIME-Version: 1.0' . "\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
					$clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
					if( $clinic_channel == 1){
					$headers .= "From:  <".$this->config['email_tx'].">" . "\n";
					$returnpath = "-f".$this->config['email_tx'];
					}else{
					$headers .= "From: <".$this->config['email_wx'].">" . "\n";
					$returnpath = '-f'.$this->config['email_wx'];
					}
					@mail($to, $SubjectLine, '', $headers, $returnpath);

					}

					//Patient goal notification mail ends here

					//Patient Email notification mail starts here
					if($rowcheckaddon->patient_email_notification!= $patient_email_notification) {
					if($patient_email_notification == 1) {
					$SubjectLine="Patient Message notification turned on by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
					}
					else
					{
					$SubjectLine="Patient Message notification turned off by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
					}
					$to = $from_email_address;
					// To send HTML mail, the Content-type header must be set
					$headers  = 'MIME-Version: 1.0' . "\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
					$clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
					if( $clinic_channel == 1){
					$headers .= "From:  <".$this->config['email_tx'].">" . "\n";
					$returnpath = "-f".$this->config['email_tx'];
					}else{
					$headers .= "From: <".$this->config['email_wx'].">" . "\n";
					$returnpath = '-f'.$this->config['email_wx'];
					}
					@mail($to, $SubjectLine, '', $headers, $returnpath);
					}

					//Patient Email notification mail ends here

					//News Feed Widget mail starts here
					if($rowcheckaddon->widget!= $widget) {
					if($widget == 1) {
					$SubjectLine="News Feed Widget turned on by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
					}
					else
					{
					$SubjectLine="News Feed Widget turned off by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
					}
					$to = $from_email_address;
					// To send HTML mail, the Content-type header must be set
					$headers  = 'MIME-Version: 1.0' . "\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
					$clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
					if( $clinic_channel == 1){
					$headers .= "From:  <".$this->config['email_tx'].">" . "\n";
					$returnpath = "-f".$this->config['email_tx'];
					}else{
					$headers .= "From: <".$this->config['email_wx'].">" . "\n";
					$returnpath = '-f'.$this->config['email_wx'];
					}
					@mail($to, $SubjectLine, '', $headers, $returnpath);
					}*/

					//News Feed Widget mail ends here
				}else{
					 
					if($wellness_store_check == 1) {
						$SubjectLine="Integrated Wellness Store turned on by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
						 
						$to = $from_email_address;
						// To send HTML mail, the Content-type header must be set
						$headers  = 'MIME-Version: 1.0' . "\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						//$headers .= "From: <support@txxchange.com>" . "\n";
						//$returnpath = '-fsupport@txxchange.com';
						$clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
						if( $clinic_channel == 1){
							$headers .= "From:  <".$this->config['email_tx'].">" . "\n";
							$returnpath = "-f".$this->config['email_tx'];
						}else{
							$headers .= "From: <".$this->config['email_wx'].">" . "\n";
							$returnpath = '-f'.$this->config['email_wx'];
						}
						$headers .= 'Bcc: sanjay.gairola@hytechpro.com' . "\r\n";
						@mail($to, $SubjectLine, '', $headers, $returnpath);
					}

				}
			}
			 
			//Mails for the services end here

			//Update the new Services in the database
                        
                                if($this->value('autoscheduleEnable')=='')
                                    $automatic_scheduling   = 0;
                                else
                                    $automatic_scheduling   = $this->value('autoscheduleEnable');
                                
                                if($this->value('messagedisable')=='')
                                    $messagedisable     =   0;
                                else
                                     $messagedisable    =   $this->value('messagedisable');
                                if($this->value('teleconferencedisable')=='')
                                    $teleconferencedisable         =   0;
                                else
                                    $teleconferencedisable         =   $this->value('teleconferencedisable');
                                
                                
                                
			if($replace['error'] == "") {
                                
				$updateArrServices = array(
						'wellness_store'        =>  $wellness_store_check_val,
						'widget' =>	$newsWidget,
						'goal_notification' => $goal_notification,
						'patient_email_notification' => $patient_email_notification,
                                                'automatic_scheduling'=>$autoscheduleEnable,
                                                'message'=>$messagedisable,
                                                'teleconference'=>$teleconferencedisable
				);
				$where=" clinic_id  =   '{$clinicId}'";
				$result=$this->update('addon_services', $updateArrServices, $where);
                                //$this->printR($updateArrServices);die;
			}
			//End here
			 
			//If provider wellness store is checked then updation starts here
			if($store == 1) {
				$sqlpr="select * from clinic where clinic_id=".$clinicId;
				$respr=$this->execute_query($sqlpr);
				$row=$this->fetch_object($respr);


				if($row->address=='' || $row->city=='' || $row->state=='' || $row->zip='' || $row->phone=='') {
					$urlStr="<script>GB_showCenter('Update Clinic Information', '/index.php?action=prxoclinicerrorHead',120,520);</script>";
					$replace['error']=$urlStr;

				}else{
					$prxo=$this->checkprxostatus($this->userInfo('user_id'));
					if($prxo=='not register')
					{
						$res=$this->registerProviderPrxo($this->userInfo('user_id'));
					}
				}


			}

			//Ends here

			 
			//Referal Limits insertion starts here
			if($this->value('add-on')==1) {

				if($this->num_rows($result)!= 0) {
					$this->validateFormReferral(false);
					if($this->error == "") {
						$row = $this->fetch_array($result);
						$this->update('referral_limit',array('clinic_refferal_limit'=>$this->value('clinic_refferal_limit'),'clinic_user_limit'=>$this->value
								('clinic_user_limit')),'id='.$row['id']);
					} else {
						$replace['addonval']='settings';
						$replace['error'] = $this->error;
					}
					 
				} else {
					// add
					$this->validateFormReferral(false);
					if($this->error == "") {
						$insertArr = array(
								'user_id'                => $userInfo['user_id'],
								'clinic_id'              => $clinicId,
								'clinic_refferal_limit'  =>	$this->value('clinic_refferal_limit'),
								'clinic_user_limit'      =>	$this->value('clinic_user_limit'),
								'status'                 =>  1
						);

						$result = $this->insert('referral_limit',$insertArr);
					} else {
						$replace['addonval']='settings';
						if($this->value('add-on') == 1) {
							$replace['addon'] = 'checked';
						}
						$replace['error'] = $this->error;
					}
				}
			}

			//Referal Limits updation ends here


			//Integrated Scheduling insertion starts here
			$schedul_url = $this->value('schedulUrl');
			// Check url is empty or not.
			 
			//if($replace['error']!= '' && $replace['error']!= "Your changes have been saved" && $schedul == 1) {

			if($schedul==1 && $schedul_url==''){
				$replace['schedul'] = 'checked';
				$replace['schedulUrl'] = $schedul_url;
				$replace['addonval'] = 'scheduling';
				$replace['error'] = "Please Enter schedule Web Address/URL";
			}
			if( isset($schedul_url) && trim($schedul_url) != ""  ){
				// Check valid URL.
				$value = '|' . $schedul_url . '|';
				$check_pattern = '|http(s)?://([\w-]+\.)+[\w-]+(/[\w- ./?%&=]*)?|';
				$check = preg_match($check_pattern, $value, $matches, PREG_OFFSET_CAPTURE);
				if( $check === false || ($check == 0)){
					$replace['addonval'] = 'scheduling';
					$replace['schedul'] = 'checked';
					$replace['schedulUrl'] = $schedul_url;
					$replace['error'] = "Incorrect URL format";
				}
			}

			// }


			if($replace['error'] == "") {
				$sqlSchedule="select * from scheduling where clinic_id  =   '{$clinicId}'";
				$query=$this->execute_query($sqlSchedule);
				$numrows=$this->num_rows($query);
				if($numrows > 0){
					$updateArrschedule = array(
							'user_id'    => $userInfo['user_id'],
							'schedulUrl' =>	$this->value('schedulUrl')
					);
					$where=" clinic_id  =   '{$clinicId}'";
					$result=$this->update('scheduling',$updateArrschedule,$where);
				}else{
					$insertArrschedule = array(
							'user_id'    => $userInfo['user_id'],
							'clinic_id'  => $clinicId,
							'schedulUrl' =>	$this->value('schedulUrl'),
							'status'     =>  1
					);
					$result=$this->insert('scheduling',$insertArrschedule);
				}
			}

			//Integrated scheduling ends here

			//}

			//E- Health Service Code starts here
			if($healthprogram == 1) {
				$this->validateFormHealthservice(false);
                                $automatic_scheduling   =   $this->value('autoscheduleEnable');
                                $message                =   $this->value('messagedisable');
                                $teleconference         =   $this->value('teleconferencedisable');
				if($this->error == "") {
					$sqlhealth="select * from clinic_subscription where subs_clinic_id =".$clinicId;
					$queryHealth=$this->execute_query($sqlhealth);
					$numHealthRow=$this->num_rows($queryHealth);
					if($numHealthRow==0){

						if($this->value('ehsTimePeriod')!= '') {
							$ehsTimePeriod = $this->value('ehsTimePeriod');
						}

						if($this->value('paymentType') == '0') {
							$subs_price = $this->value('subs_price');
							$onetime_price = '';
							$ehsTimePeriod = '';

						}  else if ($this->value('paymentType') == '1') {
							$subs_price = '';
							$onetime_price = $this->value('onetime_price');
							$ehsTimePeriod = $this->value('ehsTimePeriod');

						}

						//Insert data into health settings table
						$insertArrhealthSettings = array(
								'setting_clinic_id'          =>  $clinicId,
								'setting_user_id'          =>  $userInfo['user_id'],
								'setting_trial_days'        =>  0,
								'setting_paypal_username'  =>  '',
								'setting_c_date'           =>  date("Y-m-d H:i:s"),
								'setting_m_date'           =>  date("Y-m-d H:i:s"),
								'setting_m_user_id'        =>  $userInfo['user_id'],
								'setting_tx_commision'     =>   10,
								'setting_trial'            =>      1,
								'setting_status'           =>  1
						);
						$resultSetting=$this->insert('health_settings',$insertArrhealthSettings);
						//Ends here
						$settingId=$this->insert_id();
						//Insert data into Clinic subscription table
						$insertArrHealth = array(
								'subs_title'        =>  html_entity_decode($this->value('subs_title')),
								'subs_description'  =>  html_entity_decode($this->value('subs_description')),
								'subs_feature1'     =>	html_entity_decode($this->value('subs_feature1')),
								'subs_feature2'     =>  html_entity_decode($this->value('subs_feature2')),
								'subs_feature3'     =>  html_entity_decode($this->value('subs_feature3')),
								'subs_feature4'     =>  html_entity_decode($this->value('subs_feature4')),
								'subs_feature5'     =>  html_entity_decode($this->value('subs_feature5')),
								'subs_feature6'     =>  html_entity_decode($this->value('subs_feature6')),
								'subs_feature7'     =>  html_entity_decode($this->value('subs_feature7')),
								'paymentType'       =>  $this->value('paymentType'),
								'subs_price'        =>  $this->value('subs_price'),
								'onetime_price'     =>  $this->value('onetime_price'),
								'ehsTimePeriod'    => $this->value('ehsTimePeriod'),
								'subs_c_datetime'   =>  date("Y-m-d H:i:s"),
								'subs_clinic_id'    =>  $clinicId,
								'subs_c_user_id'    =>  $userInfo['user_id'],
								'subs_m_user_id'    =>  $userInfo['user_id'],
								'subs_m_datetime'   => date("Y-m-d H:i:s"),
								'subs_status'       =>  1,
								'setting_id'        =>  $settingId
						);
						$result=$this->insert('clinic_subscription',$insertArrHealth);
						//Ends here
						//Mail sent first time when service is turned on and successfully submitted
						$clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
						$SubjectLine="E-Health Service turned on by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
						$to = $from_email_address;
						// To send HTML mail, the Content-type header must be set
						$headers  = 'MIME-Version: 1.0' . "\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						if( $clinic_channel == 1) {
							$headers .= "From: <".$this->config['email_tx'].">" . "\n";
							$returnpath = "-f".$this->config['email_tx'];
						}else {
							$headers .= "From: <".$this->config['email_wx'].">" . "\n";
							$returnpath = '-f'.$this->config['email_wx'];
						}
						//$headers .= "From: <support@txxchange.com>" . "\n";
						//$returnpath = '-fsupport@txxchange.com';
						//echo $message;exit;
						// Mail it
						$headers .= 'Bcc: sanjay.gairola@hytechpro.com' . "\r\n";
						@mail($to, $SubjectLine, '', $headers, $returnpath);
						//First time mail sent ends here
					}else{

						$valuehealth = $this->fetch_array($queryHealth);
						$SubjectLine = "";
						//Condition if service is already submitted and its turn on and off then mail will be sent in either case
						if($valuehealth['subs_status']!= $healthprogram) {
							if($healthprogram==1) {
								$SubjectLine="E-Health Service turned on by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
							}
							$to = $from_email_address;
							// To send HTML mail, the Content-type header must be set
							$headers  = 'MIME-Version: 1.0' . "\n";
							$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
							$clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
							if( $clinic_channel == 1) {
								$headers .= "From: <".$this->config['email_tx'].">" . "\n";
								$returnpath = "-f".$this->config['email_tx'];
							}else{
								$headers .= "From: <".$this->config['email_wx'].">" . "\n";
								$returnpath = '-f'.$this->config['email_wx'];
							}
							//$headers .= "From: <support@txxchange.com>" . "\n";
							//$returnpath = '-fsupport@txxchange.com';
							//echo $message;exit;
							// Mail it
							$headers .= 'Bcc: sanjay.gairola@hytechpro.com' . "\r\n";
							@mail($to, $SubjectLine, '', $headers, $returnpath);
						}

						//Ends here

						if($this->value('paymentType')!= '') {
							$paymentType = $this->value('paymentType');
						}

						if($this->value('ehsTimePeriod')!= '') {
							$ehsTimePeriod = $this->value('ehsTimePeriod');

						} else {

							$ehsTimePeriod = $valuehealth['ehsTimePeriod'];
						}

						if($this->value('paymentType') == '0') {
							$subs_price = $this->value('subs_price');
							$onetime_price = '';
							$ehsTimePeriod = '';

						}  else if ($this->value('paymentType') == '1') {

							$subs_price = '';
							$onetime_price = $this->value('onetime_price');
							$ehsTimePeriod = $this->value('ehsTimePeriod');

						}
						$updateArrHealth = array(
								'subs_title'        =>  $this->value('subs_title'),
								'subs_description'  =>  $this->value('subs_description'),
								'subs_feature1'     =>	$this->value('subs_feature1'),
								'subs_feature2'     =>  $this->value('subs_feature2'),
								'subs_feature3'     =>  $this->value('subs_feature3'),
								'subs_feature4'     =>  $this->value('subs_feature4'),
								'subs_feature5'     =>  $this->value('subs_feature5'),
								'subs_feature6'     =>  $this->value('subs_feature6'),
								'subs_feature7'     =>  $this->value('subs_feature7'),
								'subs_price'        =>  $subs_price,
								'onetime_price'     =>  $onetime_price,
								'subs_clinic_id'    =>  $clinicId,
								'subs_m_user_id'    =>  $userInfo['user_id'],
								'subs_m_datetime'   => date("Y-m-d H:i:s"),
								//'paymentType'    =>  $this->value('paymentType'),
								'paymentType'    =>  $paymentType,
								'ehsTimePeriod'    => $ehsTimePeriod,
								'subs_status'       =>  1
						);//echo "<pre>";print_r($updateArrHealth);exit;
						$result=$this->update('clinic_subscription',$updateArrHealth,'subs_id='.$valuehealth['subs_id']);                       //update
					}
				}else {
					if($this->value('paymentType') == '0') {
						$monthlyCheck = 'checked';
						$oneTimeCheck  = '';

					} else {
						$monthlyCheck = '';
						$oneTimeCheck = 'checked';
					}

					$replace['subs_price'] = $this->value('subs_price');
					$replace['onetime_price'] = $this->value('onetime_price');
					$replace['subs_description'] = $this->value('subs_description');
					$replace['subs_feature1'] = $this->value('subs_feature1');
					$replace['subs_feature2'] = $this->value('subs_feature2');
					$replace['subs_feature3'] = $this->value('subs_feature3');
					$replace['subs_title'] = $this->value('subs_title');
					$replace['addonval'] = 'healthservice';
					$replace['error']=$this->error;

				}
			}else{
				$sqlhealth="select * from clinic_subscription where subs_clinic_id =".$clinicId;
				$queryHealth=$this->execute_query($sqlhealth);
				$numHealthRow=$this->num_rows($queryHealth);
				if($numHealthRow!=0){
					$valuehealth=$this->fetch_array($queryHealth);
					$SubjectLine="";

					if($valuehealth['subs_status']!=$healthprogram) {
						if($healthprogram==0) {
							$SubjectLine="E-Health Service turned off by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
						}
						$to = $from_email_address;
						// To send HTML mail, the Content-type header must be set
						$headers  = 'MIME-Version: 1.0' . "\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						//$headers .= "From: <support@txxchange.com>" . "\n";
						//$returnpath = '-fsupport@txxchange.com';
						$clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
						if( $clinic_channel == 1) {
							$headers .= "From: <".$this->config['email_tx'].">" . "\n";
							$returnpath = "-f".$this->config['email_tx'];
						} else {
							$headers .= "From: <".$this->config['email_wx'].">" . "\n";
							$returnpath = '-f'.$this->config['email_wx'];
						}
						$headers .= 'Bcc: sanjay.gairola@hytechpro.com' . "\r\n";

						// Mail it
						@mail($to, $SubjectLine, '', $headers, $returnpath);
					}
					$this->update('clinic_subscription',array('subs_status'=>'0','subs_m_datetime'=>date("Y-m-d H:i:s"),'subs_m_user_id'=>$userInfo['user_id']),'subs_id='.$valuehealth['subs_id']);//
				}
			}

			//E- Health service ends here

			if($replace['error'] == '') {
				$sqlAddon = "select * from addon_services where clinic_id = '{$clinicId}'";
				$resultAddon=$this->execute_query($sqlAddon);
				if($this->num_rows($resultAddon)!= 0) {
					$SubjectLine="";
					$rowcheckaddon=$this->fetch_object($resultAddon);

					if($rowcheckaddon->referral_report!=$referral_report) {
						if($referral_report==1) {
							$SubjectLine="Referral Service turned on by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
						}
						else {
							$SubjectLine="Referral Service turned off by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
						}
						$to = $from_email_address;
						// To send HTML mail, the Content-type header must be set
						$headers  = 'MIME-Version: 1.0' . "\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						//$headers .= "From: <support@txxchange.com>" . "\n";
						//$returnpath = '-fsupport@txxchange.com';
						$clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
						if( $clinic_channel == 1) {
							$headers .= "From: <".$this->config['email_tx'].">" . "\n";
							$returnpath = "-f".$this->config['email_tx'];
						}else {
							$headers .= "From:  <".$this->config['email_wx'].">" . "\n";
							$returnpath = '-f'.$this->config['email_wx'];
						}
						$headers .= 'Bcc: sanjay.gairola@hytechpro.com' . "\r\n";

						// Mail it
						@mail($to, $SubjectLine, '', $headers, $returnpath);
					}

					//Provider store mail starts here
					if($rowcheckaddon->store!=$store) {
						if($store==1) {
							$SubjectLine="Store turned on by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
						}
						else
						{
							$SubjectLine="Store turned off by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
						}
						$to = $from_email_address;
						// To send HTML mail, the Content-type header must be set
						$headers  = 'MIME-Version: 1.0' . "\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						//$headers .= "From: <support@txxchange.com>" . "\n";
						//$returnpath = '-fsupport@txxchange.com';
						$clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
						if( $clinic_channel == 1){
							$headers .= "From:  <".$this->config['email_tx'].">" . "\n";
							$returnpath = "-f".$this->config['email_tx'];
						}else{
							$headers .= "From: <".$this->config['email_wx'].">" . "\n";
							$returnpath = '-f'.$this->config['email_wx'];
						}
						$headers .= 'Bcc: sanjay.gairola@hytechpro.com' . "\r\n";
						@mail($to, $SubjectLine, '', $headers, $returnpath);

					}

					//Provider store mail ends here
					if($rowcheckaddon->scheduling!=$schedul)
					{
						if($schedul==1){
							$SubjectLine="Scheduling turned on by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
						}
						else
						{
							$SubjectLine="Scheduling turned off by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
						}
						$to = $from_email_address;
						// To send HTML mail, the Content-type header must be set
						$headers  = 'MIME-Version: 1.0' . "\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						//$headers .= "From: <support@txxchange.com>" . "\n";
						//$returnpath = '-fsupport@txxchange.com';
						$clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
						if( $clinic_channel == 1){
							$headers .= "From:  <".$this->config['email_tx'].">" . "\n";
							$returnpath = "-f".$this->config['email_tx'];
						}else{
							$headers .= "From: <".$this->config['email_wx'].">" . "\n";
							$returnpath = '-f'.$this->config['email_wx'];
						}

						//echo $message;exit;
						// Mail it
						$headers .= 'Bcc: sanjay.gairola@hytechpro.com' . "\r\n";
						@mail($to, $SubjectLine, '', $headers, $returnpath);

					}
					/*if($rowcheckaddon->program!=$program)
					 {
					if($program==1)
					{
					$SubjectLine="E-Rehab turned on by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
					}
					else
					{
					$SubjectLine="E-Rehab turned off by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
					}
					$to = $from_email_address;
					// To send HTML mail, the Content-type header must be set
					$headers  = 'MIME-Version: 1.0' . "\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
					$headers .= "From: <support@txxchange.com>" . "\n";
					$returnpath = '-fsupport@txxchange.com';
					//echo $message;exit;
					// Mail it
					@mail($to, $SubjectLine, '', $headers, $returnpath);
					 
					}*/
					//$this->update('addon_services',array('referral_report'=>$referral_report,'program'=>$program,'store'=>$store,'scheduling'=>$schedul),'clinic_id='.$clinicId);//
					$this->update('addon_services',array('referral_report'=>$referral_report,'store'=>$store,'scheduling'=>$schedul,'automatic_scheduling'=>$automatic_scheduling,'message'=>$message, 'teleconference'=>$teleconference),'clinic_id='.$clinicId);//
					if($this->error==''){
						$error   = "Your changes have been saved";
						$replace['error'] = $error = '<span style="color:green" ><b>'.$error.'</b></span>';
					}
					 
				}
				else {		
                                    
                                    
                                    $insertAddonArr = array(
						'user_id'                => $userInfo['user_id'],
						'clinic_id'              => $clinicId,
						'referral_report'        => $referral_report,
						//'program'                => $program,
						'store'                  => $store,
						'status'                 =>  1,
						'scheduling'             => $schedul,
                                                'automatic_scheduling'  =>  $automatic_scheduling,
                                                'message'               =>  $messagedisable,
                                                'teleconference'        =>  $teleconferencedisable
				);


				$result = $this->insert('addon_services',$insertAddonArr);
				//send mail to jonathan
				if($referral_report==1)
				{
					$SubjectLine="Referral Service turned on by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
					$to = $from_email_address;
					// To send HTML mail, the Content-type header must be set
					$headers  = 'MIME-Version: 1.0' . "\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
					//$headers .= "From: <support@txxchange.com>" . "\n";
					//$returnpath = '-fsupport@txxchange.com';
					$clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
					if( $clinic_channel == 1){
						$headers .= "From:  <".$this->config['email_tx'].">" . "\n";
						$returnpath = "-f".$this->config['email_tx'];
					}else{
						$headers .= "From: <".$this->config['email_wx'].">" . "\n";
						$returnpath = '-f'.$this->config['email_wx'];
					}

					// Mail it
					$headers .= 'Bcc: sanjay.gairola@hytechpro.com' . "\r\n";
					@mail($to, $SubjectLine, '', $headers, $returnpath);
				}
				if($store==1)
				{
					$SubjectLine="Store turned on by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
					$to = $from_email_address;
					// To send HTML mail, the Content-type header must be set
					$headers  = 'MIME-Version: 1.0' . "\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
					//$headers .= "From: <support@txxchange.com>" . "\n";
					//$returnpath = '-fsupport@txxchange.com';
					$clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
					if( $clinic_channel == 1){
						$headers .= "From: <".$this->config['email_tx'].">" . "\n";
						$returnpath = "-f".$this->config['email_tx'];
					}else{
						$headers .= "From: <".$this->config['email_wx'].">" . "\n";
						$returnpath = '-f'.$this->config['email_wx'];
					}

					// Mail it
					$headers .= 'Bcc: sanjay.gairola@hytechpro.com' . "\r\n";
					@mail($to, $SubjectLine, '', $headers, $returnpath);
				}
				/*if($program==1)
				 {
				$SubjectLine="E-Rehab turned on by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
				$to = $from_email_address;
				// To send HTML mail, the Content-type header must be set
				$headers  = 'MIME-Version: 1.0' . "\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
				$headers .= "From: <support@txxchange.com>" . "\n";
				$returnpath = '-fsupport@txxchange.com';
				// Mail it
				@mail($to, $SubjectLine, '', $headers, $returnpath);
				}*/
				if($schedul==1)
				{
					$SubjectLine="Scheduling turned on by ".html_entity_decode($this->get_clinic_name($clinicId), ENT_QUOTES, "UTF-8");
					$to = $from_email_address;
					// To send HTML mail, the Content-type header must be set
					$headers  = 'MIME-Version: 1.0' . "\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
					//$headers .= "From: <support@txxchange.com>" . "\n";
					//$returnpath = '-fsupport@txxchange.com';
					$clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
					if( $clinic_channel == 1){
						$headers .= "From: <".$this->config['email_tx'].">" . "\n";
						$returnpath = "-f".$this->config['email_tx'];
					}else{
						$headers .= "From: <".$this->config['email_wx'].">" . "\n";
						$returnpath = '-f'.$this->config['email_wx'];
					}

					// Mail it
					$headers .= 'Bcc: sanjay.gairola@hytechpro.com' . "\r\n";
					@mail($to, $SubjectLine, '', $headers, $returnpath);
				}
				if($this->error == "") {
					$error   = "Your changes have been saved";
					$replace['error'] = $error = '<span style="color:green" ><b>'.$error.'</b></span>';
				}
				}
			}
		}
		// Retian page value.
		$arr = array(
				'clinic_id' => $clinicId
		);
		$msg = $this->value('msg');
		if( !empty($msg) ){
			$replace['error'] = $this->errorClinicListModule($msg);
		}
		$searchString = $this->value('search');
		if( !empty($searchString) ){
			$searchString = $this->value('search');
			$searchWhere = " and clinic_name like '%{$this->value('search')}%' ";
		}
		//Get the clinic list
		if( is_numeric($clinicId) ){
			$sqlUser = "select * from referral_limit where clinic_id = '{$clinicId}'";
			$result=$this->execute_query($sqlUser);
		}
		if($this->num_rows($result)!= 0)
		{
			$row = $this->fetch_array($result);
			if($replace['error']!= '') {
				$replace['clinic_refferal_limit'] = $this->value('clinic_refferal_limit');
				$replace['clinic_user_limit'] = $this->value('clinic_user_limit');
			} else {
				$replace['clinic_refferal_limit'] = $row['clinic_refferal_limit'];
				$replace['clinic_user_limit'] = $row['clinic_user_limit'];
			}
			 
		}
		else
		{

			if($this->value('clinic_refferal_limit')!= '')
				$replace['clinic_refferal_limit'] = $this->value('clinic_refferal_limit');
			else
				$replace['clinic_refferal_limit'] = 0;

			if($this->value('clinic_user_limit')!= '')
				$replace['clinic_user_limit'] = $this->value('clinic_user_limit');
			else
				$replace['clinic_user_limit'] = 0;
		}

		$replace['display']="none;";
		if($replace['error']!= '' && $replace['error']!= "Your changes have been saved" && $this->value('add-on') == 1) {
			$replace['addon']= "checked";
		} else {
			$replace['addon']= " ";
		}
		$sqlAddon = "select * from addon_services where clinic_id = '{$clinicId}'";
		$resultAddon=$this->execute_query($sqlAddon);
		if($this->num_rows($resultAddon)!= 0)
		{
			$rowAddon = $this->fetch_array($resultAddon);
			if($rowAddon['referral_report']==1)
			{
				$replace['addon']="checked";
				$replace['display']="inline;";
			}
		}
		$url_array = $this->tab_url();
		$sqlAddon = "select * from addon_services where clinic_id = '{$clinicId}'";
		$resultAddon=$this->execute_query($sqlAddon);
		if($this->num_rows($resultAddon)!= 0)
		{

			$rowAddon = $this->fetch_array($resultAddon);
			if($rowAddon['referral_report']==1)
			{
				$replace['addon']="checked";
				$replace['display']="inline;";
			}
			if($rowAddon['program']==1)
			{
				$replace['program']="checked";
				// $replace['display']="inline;";
			}else{
				$replace['program']="";
			}


			if($rowAddon['store']==1){

				$replace['store']="checked";
				$replace['storelink']='<a href="index.php?action=loginprxo" style="font-weight:bold;" target="_blank">Manage Your Store</a>';
			} elseif($rowAddon['wellness_store'] == 1) {

				if($replace['error']!='' && $store == 1){
					$replace['store'] = "checked";
				} else {
					$replace['store'] = "";
				}
				$replace['wellness_store_check'] = "checked";
				//$wellnessStoreUrl =  $this->value('wellnessStoreUrl');
				//$replace['storelink']='<a href="'.$wellnessStoreUrl.'" style="font-weight:bold;" target="_blank">Manage Your Store</a>';
				$replace['storelink']='<span style="font-weight:bold;"><a href="javascript:void(0);" style="font-weight:bold;">Manage Your Store</a></span>&nbsp;<span style="color:red;">Please turn on your store first and click Save.</span>';
			}

			else {
				//$replace['store']="";
				if($replace['error']!= '' && $replace['error']!= "Your changes have been saved" && $store == 1){
					$replace['store'] = "checked";
				} else {
					$replace['store'] = "";
				}
				$replace['wellness_store_check'] = "";
				// $replace['storelink']='<a href="javascript:void(0);" style="font-weight:bold;">Manage Your Store</a>&nbsp;<span style="color:red;">Please turn on your store first and click Save.</span>';

				$replace['storelink']='<span style="font-weight:bold;"><a href="javascript:void(0);" style="font-weight:bold;">Manage Your Store</a></span>&nbsp;<span style="color:red;">Please turn on your store first and click Save.</span>';
			}
			if($rowAddon['scheduling']==1) {
				$sql="select schedulUrl from scheduling where clinic_id = '{$clinicId}'";
				$querys=$this->execute_query($sql);
				$num=$this->num_rows($querys);
				if($num>0)
				{
					$resu=$this->fetch_array($querys);
					// print_r($resu);die;
					$replace['schedul']="checked";
					$replace['schedulUrl']=$resu['schedulUrl'];
					$replace['disabled']='';
				}
				else{
					$replace['schedul']="";
					$replace['schedulUrl']='';
					$replace['disabled']='disabled'." style='background:#ebebe4'";
				}

				if($replace['error']!= '' && $replace['error']!= 'Your changes have been saved' && $this->value('schedul') == 1) {
					$replace['schedul']="checked";
					$replace['schedulUrl'] = $this->value('schedulUrl');
					$replace['disabled']='';
				}

				 
			}else{
				$sql="select schedulUrl from scheduling where clinic_id = '{$clinicId}'";
				$querys=$this->execute_query($sql);
				$num=$this->num_rows($querys);
				if($num>0)
				{
					$resu=$this->fetch_array($querys);
					// print_r($resu);die;
					$replace['schedulUrl']=$resu['schedulUrl'];
				}
				else{
					$replace['schedulUrl']='';
					$replace['disabled']='disabled'." style='background:#ebebe4'";
				}
				if($replace['error']!= '' && $replace['error']!= 'Your changes have been saved' && $this->value('schedul') == 1) {
					$replace['schedul']="checked";
					$replace['schedulUrl'] = $this->value('schedulUrl');
					$replace['disabled']='';
				} else {
					$replace['schedul'] = "";
					$replace['schedulUrl'] = "";
					$replace['disabled']='disabled'." style='background:#ebebe4'";

				}

			}
		}
		else{
			$replace['schedulUrl']='';
			$replace['disabled']='disabled'." style='background:#ebebe4'";
		 $replace['program']="";
		 $replace['store']="";
		  
		 $replace['schedul']="";
		 //$replace['storelink']='<a href="javascript:void(0);" style="font-weight:bold;">Manage Your Store</a>&nbsp;<span style="color:red;">Please turn on your store first and click Save.</span>';

		 $replace['storelink']='<span style="font-weight:bold;"><a href="javascript:void(0);" style="font-weight:bold;" >Manage Your Store</a></a>&nbsp;</span><span style="color:red;">Please turn on your store first and click Save.</span>';
		}
		/*code for e health service*/
		$sqlhealth="select * from clinic_subscription where subs_clinic_id =".$clinicId;
		$queryHealth=$this->execute_query($sqlhealth);
		$numHealthRow=$this->num_rows($queryHealth);

		if($numHealthRow!=0){
			$valuehealth=$this->fetch_object($queryHealth);

			if($this->error!= '') {
				$paymentType = $this->value('paymentType');
			} else {
				$paymentType = $valuehealth->paymentType;
			}
			if($paymentType == '0') {
				$monthlyCheck = 'checked';
				$oneTimeCheck = '';
				$replace['subs_price']=$valuehealth->subs_price;

			} else {
				$monthlyCheck = '';
				$oneTimeCheck = 'checked';
			}
			if($valuehealth->subs_status==0){
				$replace['health']='';
                                $replace['autoschedule']='';
                                $replace['message']='';
                                $replace['teleconference']='';
				$replace['subpricereadonly']='readOnly'." style='background:#ebebe4'";
				$replace['readonly']='readOnly'." style='background:#ebebe4'";
				$replace['onetimepricereadonly']='readOnly'." style='background:#ebebe4'";
				$replace['display']='none';
				$replace['Durationreadonly']='disabled';
				$replace['onetimedisabled']='disabled';
				$replace['paymentTypeDisable']= "disabled='true'";
				$replace['disabletine']="style='opacity:.4'";
			}
			else{
				 
				$replace['health']='checked';
				$replace['disabletine']="";
				$replace['readonly']=''. "style ='background:#ffffff'";
				$replace['display']='block';
				$replace['paymentTypeDisable']= "";
				if($paymentType == '0') {
					$replace['onetimepricereadonly']='readOnly'." style='background:#ebebe4'";
					$replace['Durationreadonly']='disabled';
					$replace['subpricereadonly']='';
				} else {
					$replace['subpricereadonly']='readOnly'." style='background:#ebebe4'";
					$replace['onetimepricereadonly']='';
				}
                                if($rowAddon['automatic_scheduling']==1){
                                  $replace['autoschedule']='checked' ;
                                }else{
                                  $replace['autoschedule']='' ;  
                                }
                                if($rowAddon['message']==1){
                                    $replace['message']='checked';
                                }else{
                                    $replace['message']='';
                                }
                                if($rowAddon['teleconference']==1){
                                   $replace['teleconference']='checked';
                                }else{
                                   $replace['teleconference']='';
                                }
                                    

			}
			$replace['monthlyCheck']=$monthlyCheck;
			$replace['oneTimeCheck']=$oneTimeCheck;

			if($this->error!= '') {

				if($this->value('healthEnable') == '1')
					$replace['health']='checked';
				else
					$replace['health']='';
				 
				$replace['readonly']='';
				$replace['disabletine']="";
				if($this->value('paymentType') == '0') {
					$replace['monthlyCheck'] = 'checked';
					$replace['oneTimeCheck'] = '';
					$replace['Durationreadonly']='disabled';
					$replace['onetimedisabled']='disabled';
					$replace['paymentTypeDisable']= "";
					$replace['onetimepricereadonly']='readOnly'." style='background:#ebebe4'";
					$replace['subpricereadonly']='';
				} elseif($this->value('paymentType') == '1') {
					$replace['monthlyCheck']='';
					$replace['oneTimeCheck']='checked';
					$replace['Durationreadonly']='';
					$replace['onetimedisabled']='';
					$replace['paymentTypeDisable']= "";
					$replace['onetimepricereadonly']='';
					$replace['subpricereadonly']='readOnly'." style='background:#ebebe4'";
				} else {
					$replace['monthlyCheck']='checked';
					$replace['oneTimeCheck']='';
					$replace['Durationreadonly']='disabled';
					$replace['onetimedisabled']='disabled';
					$replace['paymentTypeDisable']= "disabled='true'";
					$replace['onetimepricereadonly']='readOnly'." style='background:#ebebe4'";
					$replace['subpricereadonly']='readOnly'." style='background:#ebebe4'";
				}
				 

				$replace['subs_price'] = $this->value('subs_price');
				$replace['onetime_price'] = $this->value('onetime_price');
				$replace['subs_description'] = $this->value('subs_description');
				$replace['subs_feature1'] = $this->value('subs_feature1');
				$replace['subs_feature2'] = $this->value('subs_feature2');
				$replace['subs_feature3'] = $this->value('subs_feature3');
				$replace['subs_title'] = $this->value('subs_title');
				$replace['count']=3;
				$editSubsFeature='';
				if($this->value('subs_feature4')!=''){
					$replace['subs_feature4']=$this->value('subs_feature4');
					$editSubsFeature="<tr><td><input name='subs_feature4' type='text' maxlength='50' id='subs_feature4' value='{$this->value('subs_feature4')}' {$replace['readonly']} /></td>
					<td>&nbsp;</td></tr>";
					$replace['count']=4;
				}
				if($this->value('subs_feature5')!=''){
					$replace['subs_feature5']=$this->value('subs_feature5');
					$editSubsFeature="<tr><td><input name='subs_feature4' type='text' maxlength='50' id='subs_feature4' value='{$this->value('subs_feature4')}' {$replace['readonly']} /></td>
					<td>&nbsp;</td></tr><tr><td><input name='subs_feature5' type='text' maxlength='50' id='subs_feature5' value='{$this->value('subs_feature5')}' {$replace['readonly']} /></td>
					<td>&nbsp;</td></tr>";
					$replace['count']=5;
				}
				if($this->value('subs_feature6')!=''){
					$replace['subs_feature6']=$this->value('subs_feature6');
					$editSubsFeature="<tr><td><input name='subs_feature4' type='text' maxlength='50' id='subs_feature4' value='{$this->value('subs_feature4')}' {$replace['readonly']} /></td>
					<td>&nbsp;</td></tr><tr><td><input name='subs_feature5' type='text' maxlength='50' id='subs_feature5' value='{$this->value('subs_feature5')}' {$replace['readonly']} /></td>
					<td>&nbsp;</td></tr><tr><td><input name='subs_feature6' type='text' maxlength='50' id='subs_feature6' value='{$this->value('subs_feature6')}' {$replace['readonly']} /></td>
					<td>&nbsp;</td></tr>";
					$replace['count']=6;
				}
				if($this->value('subs_feature7')!=''){
					$replace['subs_feature7']=$valuehealth->subs_feature7;
					$editSubsFeature="<tr><td><input name='subs_feature4' type='text' maxlength='50' id='subs_feature4' value='{$this->value('subs_feature7')}' {$replace['readonly']} /></td>
					<td>&nbsp;</td></tr><tr><td><input name='subs_feature5' type='text' maxlength='50' id='subs_feature5' value='{$this->value('subs_feature5')}' {$replace['readonly']} /></td>
					<td>&nbsp;</td></tr><tr><td><input name='subs_feature6' type='text' maxlength='50' id='subs_feature6' value='{$this->value('subs_feature6')}' {$replace['readonly']} /></td>
					<td>&nbsp;</td></tr><tr><td><input name='subs_feature7' type='text' maxlength='50' id='subs_feature7' value='{$this->value('subs_feature7')}' {$replace['readonly']} /></td>
					<td>&nbsp;</td></tr>";
					$replace['count']=7;
					$replace['js']='<script language="javascript" type="text/javascript">document.getElementById("link_more").style.display="none";
					document.getElementById("link_more_help").style.display="none";</script>';
				}
				$replace['editSubsFeature']=$editSubsFeature;
			} else {


				if($valuehealth->subs_price!= '') {
					$replace['subs_price'] = $valuehealth->subs_price;
				}
				else {
					$replace['subs_price'] = '';
				}
				if($valuehealth->onetime_price!= '') {
					$replace['onetime_price'] = $valuehealth->onetime_price;
				}
				else {
					$replace['onetime_price'] = '';
				}

				$replace['subs_title']=$valuehealth->subs_title;

				 
				$replace['subs_description']=str_replace('$','$ ',$valuehealth->subs_description);
				$replace['subs_feature1']=$valuehealth->subs_feature1;
				$replace['subs_feature2']=$valuehealth->subs_feature2;
				$replace['subs_feature3']=$valuehealth->subs_feature3;
				$replace['count']=3;
				$editSubsFeature='';
				if($valuehealth->subs_feature4!=''){
					$replace['subs_feature4']=$valuehealth->subs_feature4;
					$editSubsFeature="<tr><td><input name='subs_feature4' type='text' maxlength='50' id='subs_feature4' value='{$valuehealth->subs_feature4}' {$replace['readonly']} /></td>
					<td>&nbsp;</td></tr>";
					$replace['count']=4;
				}
				if($valuehealth->subs_feature5!=''){
					$replace['subs_feature5']=$valuehealth->subs_feature5;
					$editSubsFeature="<tr><td><input name='subs_feature4' type='text' maxlength='50' id='subs_feature4' value='{$valuehealth->subs_feature4}' {$replace['readonly']} /></td>
					<td>&nbsp;</td></tr><tr><td><input name='subs_feature5' type='text' maxlength='50' id='subs_feature5' value='{$valuehealth->subs_feature5}' {$replace['readonly']} /></td>
					<td>&nbsp;</td></tr>";
					$replace['count']=5;
				}
				if($valuehealth->subs_feature6!=''){
					$replace['subs_feature6']=$valuehealth->subs_feature6;
					$editSubsFeature="<tr><td><input name='subs_feature4' type='text' maxlength='50' id='subs_feature4' value='{$valuehealth->subs_feature4}' {$replace['readonly']} /></td>
					<td>&nbsp;</td></tr><tr><td><input name='subs_feature5' type='text' maxlength='50' id='subs_feature5' value='{$valuehealth->subs_feature5}' {$replace['readonly']} /></td>
					<td>&nbsp;</td></tr><tr><td><input name='subs_feature6' type='text' maxlength='50' id='subs_feature6' value='{$valuehealth->subs_feature6}' {$replace['readonly']}/></td>
					<td>&nbsp;</td></tr>";
					$replace['count']=6;
				}
				if($valuehealth->subs_feature7!=''){
					$replace['subs_feature7']=$valuehealth->subs_feature7;
					$editSubsFeature="<tr><td><input name='subs_feature4' type='text' maxlength='50' id='subs_feature4' value='{$valuehealth->subs_feature4}' {$replace['readonly']} /></td>
					<td>&nbsp;</td></tr><tr><td><input name='subs_feature5' type='text' maxlength='50' id='subs_feature5' value='{$valuehealth->subs_feature5}' {$replace['readonly']} /></td>
					<td>&nbsp;</td></tr><tr><td><input name='subs_feature6' type='text' maxlength='50' id='subs_feature6' value='{$valuehealth->subs_feature6}' {$replace['readonly']} /></td>
					<td>&nbsp;</td></tr><tr><td><input name='subs_feature7' type='text' maxlength='50' id='subs_feature7' value='{$valuehealth->subs_feature7}' {$replace['readonly']} /></td>
					<td>&nbsp;</td></tr>";
					$replace['count']=7;
					$replace['js']='<script language="javascript" type="text/javascript">document.getElementById("link_more").style.display="none";
					document.getElementById("link_more_help").style.display="none";</script>';
				}
				$replace['editSubsFeature']=$editSubsFeature;

			}
		}else{

			$replace['subs_title'] = $this->value('subs_title');
			//$replace['paymentType'] = $this->value('paymentType');
			$replace['subs_price'] = $this->value('subs_price');
			$replace['onetime_price'] = $this->value('onetime_price');
			$replace['subs_description'] = $this->value('subs_description');
			$replace['subs_feature1'] = $this->value('subs_feature1');
			$replace['subs_feature2'] = $this->value('subs_feature2');
			$replace['subs_feature3'] = $this->value('subs_feature3');

			$editSubsFeature='';
			if($this->value('subs_feature4')!=''){
				$replace['subs_feature4']=$this->value('subs_feature4');
				$editSubsFeature="<tr><td><input name='subs_feature4' type='text' maxlength='50' id='subs_feature4' value='{$this->value('subs_feature4')}' {$replace['readonly']} /></td>
				<td>&nbsp;</td></tr>";
				$replace['count']=4;
			}
			if($this->value('subs_feature5')!=''){
				$replace['subs_feature5']=$this->value('subs_feature5');
				$editSubsFeature="<tr><td><input name='subs_feature4' type='text' maxlength='50' id='subs_feature4' value='{$this->value('subs_feature4')}' {$replace['readonly']} /></td>
				<td>&nbsp;</td></tr><tr><td><input name='subs_feature5' type='text' maxlength='50' id='subs_feature5' value='{$this->value('subs_feature5')}' {$replace['readonly']} /></td>
				<td>&nbsp;</td></tr>";
				$replace['count']=5;
			}
			if($this->value('subs_feature6')!=''){
				$replace['subs_feature6']=$this->value('subs_feature6');
				$editSubsFeature="<tr><td><input name='subs_feature4' type='text' maxlength='50' id='subs_feature4' value='{$this->value('subs_feature4')}' {$replace['readonly']} /></td>
				<td>&nbsp;</td></tr><tr><td><input name='subs_feature5' type='text' maxlength='50' id='subs_feature5' value='{$this->value('subs_feature5')}' {$replace['readonly']} /></td>
				<td>&nbsp;</td></tr><tr><td><input name='subs_feature6' type='text' maxlength='50' id='subs_feature6' value='{$this->value('subs_feature6')}' {$replace['readonly']} /></td>
				<td>&nbsp;</td></tr>";
				$replace['count']=6;
			}
			if($this->value('subs_feature7')!=''){
				$replace['subs_feature7']=$valuehealth->subs_feature7;
				$editSubsFeature="<tr><td><input name='subs_feature4' type='text' maxlength='50' id='subs_feature4' value='{$this->value('subs_feature7')}' {$replace['readonly']} /></td>
				<td>&nbsp;</td></tr><tr><td><input name='subs_feature5' type='text' maxlength='50' id='subs_feature5' value='{$this->value('subs_feature5')}' {$replace['readonly']} /></td>
				<td>&nbsp;</td></tr><tr><td><input name='subs_feature6' type='text' maxlength='50' id='subs_feature6' value='{$this->value('subs_feature6')}' {$replace['readonly']} /></td>
				<td>&nbsp;</td></tr><tr><td><input name='subs_feature7' type='text' maxlength='50' id='subs_feature7' value='{$this->value('subs_feature7')}' {$replace['readonly']} /></td>
				<td>&nbsp;</td></tr>";
				$replace['count']=7;
				$replace['js']='<script language="javascript" type="text/javascript">document.getElementById("link_more").style.display="none";
				document.getElementById("link_more_help").style.display="none";</script>';
			}
			$replace['editSubsFeature']=$editSubsFeature;




			if($this->value('paymentType') == '0') {
				$replace['monthlyCheck'] = 'checked';
				$replace['oneTimeCheck'] = '';
				$replace['Durationreadonly']='disabled';
				$replace['onetimedisabled']='disabled';
				$replace['paymentTypeDisable']= "";
				$replace['onetimepricereadonly']='readOnly'." style='background:#ebebe4'";
				$replace['subpricereadonly']='';
			} elseif($this->value('paymentType') == '1') {
				$replace['monthlyCheck']='';
				$replace['oneTimeCheck']='checked';
				$replace['Durationreadonly']='';
				$replace['onetimedisabled']='';
				$replace['paymentTypeDisable']= "";
				$replace['onetimepricereadonly']='';
				$replace['subpricereadonly']='readOnly'." style='background:#ebebe4'";
			} else {
				$replace['monthlyCheck']='checked';
				$replace['oneTimeCheck']='';
				$replace['Durationreadonly']='disabled';
				$replace['onetimedisabled']='disabled';
				$replace['paymentTypeDisable']= "disabled='true'";
				$replace['onetimepricereadonly']='readOnly'." style='background:#ebebe4'";
				$replace['subpricereadonly']='readOnly'." style='background:#ebebe4'";
			}

			if($this->value('healthEnable') == '1')
				$replace['health']='checked';
			else
				$replace['health']='';
			$replace['count']=3;
			if($this->error!='') {
				$replace['readonly']='';
				$replace['disabletine']="";
			}
			else {
				$replace['readonly']='readOnly'." style='background:#ebebe4'";
				$replace['disabletine']="style='opacity:.4'";
			}
			$replace['display']='block';
			$replace['js']='<script language="javascript" type="text/javascript">document.getElementById("link_more").style.display="none";
			document.getElementById("link_more_help").style.display="none";</script>';
		}
		/*end of code*/



		//HTP

		$sqlAddonServices = "select * from addon_services where clinic_id = '{$clinicId}'";
		$resultAddonServices=$this->execute_query($sqlAddonServices);
		if($this->num_rows($resultAddonServices)!= 0) {

			if($replace['error']!=''){
				$replace['patient_email_notification_check'] = "";
				if($this->value('wellness_store_check') == '1') {
					$replace['wellness_store_check'] = 'checked';
					$replace['urldisabled'] = "";
					$replace['wellnessStoreUrl'] = $this->value('wellnessStoreUrl');
					 
				} else {

					$replace['wellness_store_check'] = "";
					$replace['urldisabled'] = "disabled='disabled'"." style='background:#ebebe4'";

				}
				if($this->value('newsWidget') == 1)
					$replace['newsWidget_check'] = "checked";
				else
					$replace['newsWidget_check'] = "";
				if($this->value('patient_email_notification') == 1)
					$replace['patient_email_notification_check'] = "checked";
				else
					$replace['patient_email_notification'] = "";
				if($this->value('goal_notification') == 1)
					$replace['goal_notification_check'] = "checked";
				else
					$replace['goal_notification_check'] = "";
                                if($this->value('messagedisable')==1){
                                    $replace['message']='checked';
                                }else{
                                    $replace['message']='';
                                }
                                if($this->value('teleconferencedisable')==1){
                                   $replace['teleconference']='checked';
                                }else{
                                   $replace['teleconference']='';
                                }        
			} else {//echo "test";
				$rowaddonServices = $this->fetch_object($resultAddonServices);
				if($rowaddonServices->widget == 1)
					$replace['newsWidget_check'] = "checked";
				else
					$replace['newsWidget_check'] = "";


				if($rowaddonServices->goal_notification == 1)
					$replace['goal_notification_check'] = "checked";
				else
					$replace['goal_notification_check'] = "";

				if($rowaddonServices->patient_email_notification == 1)
					$replace['patient_email_notification_check'] = "checked";
				else
					$replace['patient_email_notification_check'] = "";

				$db_wellness_store = $rowaddonServices->wellness_store;
				if($db_wellness_store == 1){
					$replace['wellness_store_check'] = "checked";
					$replace['urldisabled'] = "";
				} else {
					$replace['wellness_store_check'] = "";
					$replace['urldisabled'] = "disabled='disabled'"." style='background:#ebebe4'";
				}
                                if($rowaddonServices->message==1){
                                    $replace['message']='checked';
                                }else{
                                    $replace['message']='';
                                }
                                if($rowaddonServices->teleconference==1){
                                   $replace['teleconference']='checked';
                                }else{
                                   $replace['teleconference']='';
                                }


			}

			 

		} else {
			$replace['newsWidget_check'] = "";
			$replace['wellness_store_check'] = "";
			$replace['patient_email_notification_check'] = "";
			$replace['goal_notification_check'] = "";
			$replace['urldisabled'] = "";
                        
		}

		//Code to open the expanded option starts here
                
		if($replace['addonval'] == '') {
			$addonval = $_REQUEST['addonval'];
			if($addonval!= '') {
				$replace['addonval']=$addonval;
			} else {

                            if($this->is_corporate($clinicId)==1){
                                $replace['addonval']='wellnessStore';
                                $replace['corporate']='none';
                            } else {
                                $replace['addonval']='healthservice';
                                $replace['corporate']='block';
                            }
			}
                        
		}

		//Code to open the expanded option End here
		if($this->value('wellness_store_check') == '1') {
			$replace['wellness_store_check'] = 'checked';
			$replace['urldisabled'] = "";
			$replace['wellnessStoreUrl'] = $this->value('wellnessStoreUrl');
			 
		} else {

			$replace['wellness_store_check'] = "";
			$replace['urldisabled'] = "disabled='disabled'"." style='background:#ebebe4'";

		}
		$replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$url_array);
		$replace['clinic_id'] = $clinicId;
		$replace['header'] = $this->build_template($this->get_template("header"));
		$replace['footer'] = $this->build_template($this->get_template("footer"));
		$replace['sidebar'] = $this->sidebar();
		$replace['reportTblHead'] = $this->build_template($this->get_template("reportTblHead"),$this->table_heading($reportListHead,"p_name_first",$reportperiod));
		$replace['body'] = $this->build_template($this->get_template("txAddonServicesHead"),$replace);
		$replace['referralSettings'] = $this->build_template($this->get_template("txReferralSetting"),$replace);
		// $durationArray = array("0" => "Select Month");
		 
		if($this->value('ehsTimePeriod') == '') {
			if($this->error!='') {
				$selehsTimePeriod ='';
			} else {
				$selehsTimePeriod = $valuehealth->ehsTimePeriod;
			}
		} else {
			$selehsTimePeriod = $this->value('ehsTimePeriod');
		}
		$durationArray = $this->config['paymentDuration'];//echo $valuehealth->ehsTimePeriod;
		$replace['durationOptions'] = $this->build_select_option($durationArray, $selehsTimePeriod);
		$replace['clinicURL'] = $clinicURL;
		if($this->error!='')
			$replace['error'] = $this->error;

		$replace['healthsevice'] = $this->build_template($this->get_template("txHealthSevice"),$replace);
		$replace['browser_title'] = "Tx Xchange: App & Services Store";
		$replace['get_satisfaction'] = $this->get_satisfaction();
                if($this->is_corporate($clinicId)==1){
                  $replace['corporate']='none';
                 }else{
                   $replace['corporate']='block';
                 }
		$this->output = $this->build_template($this->get_template("main"),$replace);

	}
	/**
	 * This function displays error prox.
	 */
	function prxoclinicerrorHead(){
		$replace['body'] = $this->build_template($this->get_template("prxoclinicerror"));
		$this->output = $this->build_template($this->get_template("main"),$replace);
	}

	/**
	 * This function displays list clinics in an account.
	 */
	function txReferralReport(){
		$month= date('m');
		$year=date('Y');
		$clinicId = $this->clinicInfo("clinic_id");
		if( !is_numeric($clinicId) || $clinicId == 0 ){
			header("location:index.php?action=logout");
			exit();
		}

		$replace = array();
                 /* code for show telespine menu on list */
        $replace['showmenu']="no";
        if($this->clinicInfo('clinic_id')==$this->config['telespineid']){
            $replace['showmenu']="yes";
        }
        /* code end */
		// Retian page value.
		$userInfo = $this->userInfo();
		$cli_type=$this->getClinicDetails($this->userInfo('user_id'));
		//if($cli_type['clinic_type']==4){
		$replace['shopshow']=1;
		/*}else{
		 $replace['shopshow']='0';
		}*/
		$arr = array(
				'clinic_id' => $clinicId
		);
		$msg = $this->value('msg');
		if( !empty($msg) ){
			$replace['error'] = $this->errorClinicListModule($msg);
		}
		$this->set_session_page($arr);
		//Get  acccount id
		if($this->value('sort') != ""){
			if($this->value('order') == 'desc' ){
				$orderby = " order by {$this->value('sort')} desc ";
			}
			else{
				$orderby = " order by {$this->value('sort')} ";
			}
		}
		else{
			$orderby = " order by sent_date ";
		}

		//Get the clinic list
		if( is_numeric($clinicId) ){
			if($this->value('period')!=''){
				$time=explode('~~',$this->value('period'));
				$month1= $time[0];
				$year1=$time[1];
			}else{
				$month1= date('m');
				$year1=date('Y');
			}


			$sqlUser = "select user.name_first as p_name_first,user.name_last as p_name_last,
			patients_refferals.name_first as r_name_first,patients_refferals.name_last as r_name_last,
			sent_date,recipient_email
			from patients_refferals,user
			where patients_refferals.user_id=user.user_id
			and patients_refferals.status = 1
			and clinic_id = '{$clinicId}'
			and MONTH(sent_date)={$month1}
			and YEAR(sent_date)={$year1}
			{$orderby}";
			$sqlquery = "select count(1) as total
			from patients_refferals,user
			where patients_refferals.user_id=user.user_id
			and patients_refferals.status = 1
			and clinic_id = '{$clinicId}'
			and MONTH(sent_date)={$month1}
			and YEAR(sent_date)={$year1}";
		}
		$link = $this->pagination($rows = 0,$sqlUser,'txReferralReport',$searchString,'','',20);
		$replace['link'] = $link['nav'];
		$result = $link['result'];

		if($this->num_rows($result)!= 0)
		{
			$replace['reportTblHead'] = $this->build_template($this->get_template("reportTblHead"),$replace);
			while($row = $this->fetch_array($result))
			{
				$row['style'] = ($c++%2)?"line1":"line2";
				$row['p_name_first'] = $this->decrypt_data($row['p_name_first']).' '.$this->decrypt_data($row['p_name_last']);
				$row['r_name_first'] = $row['r_name_first'].' '.$row['r_name_last'];
				$row['sent_date'] = $this->formatDate($row['sent_date']);
				$replace['reportTblRecord'] .= $this->build_template($this->get_template("reportTblRecord"),$row);
			}
		}else{
			$row['p_name_first'] = 'No Records Found';
			$row['r_name_first'] = '';
			$row['recipient_email'] = '';
			$row['sent_date'] = '';
			$replace['reportTblRecord'] .= $this->build_template($this->get_template("reportTblRecord"),$row);
		}
		 

		$reportListHead = array(
				'p_name_first' => 'Name of Referring Patient',
				'r_name_first' => 'Name of Person Referred',
				'recipient_email' => 'Email Address of Person Referred',
				'sent_date' => 'Date Referral Sent',
		);

		$mon=$month;
		$yer=$year;
		$monthname=array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Aug','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
		for($i=0;$i<12;$i++){
			$select='';
			if($this->value('period')!=''){
				$time=explode('~~',$this->value('period'));
				$month1= $time[0];
				$year1=$time[1];
				if($month1==$mon and $year1==$yer)
					$select='selected='."selected";
			}
			 
			$monview=$mon;
			if(strlen($mon)==1)
				$monview='0'.$mon;
			else{
				$monview=$mon;
			}
			$period .= "<option value='".$mon."~~".$yer."'".$select.">".$monthname[$monview].','.$yer;
			$mon=$mon-1;
			if($mon==0)
			{
				$mon=12;
				$yer=$yer-1;
			}
			 
			 
		}
		$res=$this->execute_query($sqlquery);
		$totalno=@mysql_fetch_array($res);
		$replace['totalReferrals']=$totalno['total'];
		$replace['downloadreport']='index.php?action=txReferralReportExcel&period='.$this->value('period');
		$replace['period']=$period;
		$reportperiod['period']=$this->value('period');
		$replace['reportTblHead'] = $this->build_template($this->get_template("reportTblHead"),$this->table_heading($reportListHead,"p_name_first",$reportperiod));
		$replace['clinic_id'] = $clinicId;
		$url_array = $this->tab_url();
		$replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$url_array);
		$replace['location'] = $url_array['location'];
		$replace['header'] = $this->build_template($this->get_template("header"));
		$replace['footer'] = $this->build_template($this->get_template("footer"));
		$replace['sidebar'] = $this->sidebar();
		$replace['body'] = $this->build_template($this->get_template("reportTemplate"),$replace);
		$replace['browser_title'] = "Tx Xchange: Referral Report";
		$replace['get_satisfaction'] = $this->get_satisfaction();
		$this->output = $this->build_template($this->get_template("main"),$replace);


	}
	function txReferralReportExcel(){
		$month= date('m');
		$year=date('Y');
		$clinicId = $this->clinicInfo("clinic_id");
		$clinicName=$this->get_clinic_name($clinicId);
		if( !is_numeric($clinicId) || $clinicId == 0 ){
			header("location:index.php?action=logout");
			exit();
		}
		// Retian page value.
		$arr = array(
				'clinic_id' => $clinicId
		);
		//Get the clinic list
		if( is_numeric($clinicId) ){
			if($this->value('period')!=''){
				$time=explode('~~',$this->value('period'));
				$month1= $time[0];
				$year1=$time[1];
			}else{
				$month1= date('m');
				$year1=date('Y');
			}
			$filename="report/".$clinicName.'_'.$month1.'_'.$year1.'_Referrals_report.xls';
			$filename=str_replace(" ",'_',$filename);
			 
			$excel=new ExcelWriter($filename);
			if($excel==false)
				echo $excel->error;
			$sqlUser = "select user.name_first as p_name_first,user.name_last as p_name_last,
			patients_refferals.name_first as r_name_first,patients_refferals.name_last as r_name_last,
			sent_date,recipient_email
			from patients_refferals,user
			where patients_refferals.user_id=user.user_id
			and patients_refferals.status = 1
			and clinic_id = '{$clinicId}'
			and MONTH(sent_date)={$month1}
			and YEAR(sent_date)={$year1} order by sent_date desc";
		}
		$result = $this->execute_query($sqlUser);

		if($this->num_rows($result)!= 0)
		{

			$myArr=array("<b>Name of Referring Patient</b>","<b>Name of Person Referred</b>","<b>Email Address of Person Referred<b>","<b>Date Referral Sent</b>");
			$excel->writeLine($myArr);
			while($row = $this->fetch_array($result))
			{
				$excelrow['p_name_first'] = $this->decrypt_data($row['p_name_first']).' '.$this->decrypt_data($row['p_name_last']);
				$excelrow['r_name_first'] = $row['r_name_first'].' '.$row['r_name_last'];
				$excelrow['recipient_email']=$row['recipient_email'];
				$excelrow['sent_date'] = $this->formatDate($row['sent_date']);
				$excel->writeLine($excelrow);
			}
		}else{
			$excelrow['p_name_first'] = 'No Record Found';
			$excel->writeLine($excelrow);

		}
		$excel->writeRow();
		$excel->writeRow();
		$excel->writeRow();
		$excel->writeCol("<b>Total Number of Referrals Sent (MTD)<b>");
		$excel->writeCol("<b>".$this->num_rows($result)."</b>");
		$excel->close();
		if (file_exists($filename)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.basename($filename));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($filename));
			ob_clean();
			flush();
			readfile($filename);
			exit;
		}
		 
	}

	/**
	 * This function display's the FAQ page Therapist.
	 *
	 */
	function faq_therapist_head(){
		$this->output =  $this->build_template($this->get_template("faq_therapist_head"));
	}
	/**
	 * This function display's the FAQ page Therapist.
	 *
	 */
	function faq_therapist_head_erehab(){
		$this->output =  $this->build_template($this->get_template("faq_therapist_head_erehab"));
	}
	/**
	 * This function display's the FAQ page Therapist.
	 *
	 */
	function faq_therapist_head_store(){
		$this->output =  $this->build_template($this->get_template("faq_therapist_head_store"));
	}

	/**
	 * This function display's the FAQ page for integrated wellness store.
	 *
	 */
	function faq_therapist_integrated_wellness(){
		$this->output =  $this->build_template($this->get_template("faq_therapist_integrated_wellness"));
	}
	/**
	 * This function display's the FAQ page Therapist.
	 *
	 */
	function faq_therapist_head_scheduling(){
		$this->output =  $this->build_template($this->get_template("faq_therapist_head_scheduling"));
	}

	/**
	 * This function Enable /Disable E health service
	 * @access public
	 */
	function eHealthServiceProvider() {

		$userId = $this->value('userId');
		$ehs = $this->value('ehs');

		$ehsAction = $_REQUEST['ehsAction'];
		$replace['ehsAction'] = $ehsAction;
		 
		if($this->value('confirm') == "yes" ) {
			if(is_numeric($this->value('ehs'))) {
				$query = "UPDATE user SET  ehs = '".$this->value('ehs')."' WHERE user_id = ".$userId;
				$result = @mysql_query($query);
			}
			$this->output = "<script language='javascript'>
			parent.parent.location.reload();
			//parent.parent.GB_hide();
			parent.parent.setTimeout('GB_CURRENT.hide()',1000);
			</script>";
			return;
		}

		if($ehs == 0) {
			$replace['message'] = "You are about to unsubscribe this patient from your E-Health Service.";
		} else {
			$replace['message'] = "You are about to turn on E-Health Service for this patient.";
		}
		$replace['ehs'] = $this->value('ehs');
		$replace['userId'] = $this->value('userId');
		$replace['clinicId']=$_REQUEST['clinicId'];
		$replace['body'] = $this->build_template($this->get_template("ehealthservice"),$replace);
		$replace['browser_title'] = "Tx Xchange: Home";
		$this->output = $this->build_template($this->get_template("main"),$replace);
	}



	/**
	 * This function Enable /Disable E health service
	 * @access public
	 */
	function ehsonetimepaymentComplete() {

		$userId = $this->value('userId');
		$ehs = $this->value('ehs');

		//$ehsAction = $_REQUEST['ehsAction'];
		// $replace['ehsAction'] = $ehsAction;
		 
		if($this->value('confirm') == "yes" ) {
			if(is_numeric($this->value('ehs'))) {
				$query = "UPDATE user SET  ehs = '{$ehs}' WHERE user_id = ".$userId;
				$result = @mysql_query($query);
			}
			$this->output = "<script language='javascript'>
			parent.parent.location.reload();
			//parent.parent.GB_hide();
			parent.parent.setTimeout('GB_CURRENT.hide()',1000);
			</script>";
			return;
		}

		if($ehs == 0) {
			$replace['message'] = "You are about to turn on E-Health Service for this patient.";
		} else {


			$replace['message'] = "You are about to unsubscribe this patient from your E-Health Service.";
		}
		$replace['ehs'] = $this->value('ehs');
		$replace['userId'] = $this->value('userId');
		$replace['clinicId']=$_REQUEST['clinicId'];
		$replace['body'] = $this->build_template($this->get_template("ehealthserviceonetime"),$replace);
		$replace['browser_title'] = "Tx Xchange: Home";
		$this->output = $this->build_template($this->get_template("main"),$replace);
	}


	/**
	 * This function Enable /Disable E health service
	 * @access public
	 */
	function ehsonetimepayment() {

		$userId = $this->value('userId');
		$ehs = $this->value('ehs');

		$replace['message'] = "It is one Time subscribed patient so not able to unsubscribe from EHS";
		$replace['ehs'] = $this->value('ehs');
		$replace['userId'] = $this->value('userId');
		$replace['clinicId']=$_REQUEST['clinicId'];
		$replace['body'] = $this->build_template($this->get_template("ehsonetimepayment"),$replace);
		$replace['browser_title'] = "Tx Xchange: Home";
		$this->output = $this->build_template($this->get_template("main"),$replace);
	}



	/**
	 * @desc used to show popup message when user unsubscribe the e health services.
	 * @param void
	 * @return void
	 * @access public
	 */
	 
	public function patient_ehs_unsubscribe() {
		//$userInfo = $this->userInfo();
		//$userId = $userInfo['user_id'];
		//$userId = '113531';
		//echo $clinicId    =    $this->get_clinic_info($userId,"clinic_id");
		$userId = $this->value('userId');
		$clinicId = $_REQUEST['clinicId'];
		$ehsAction = $_REQUEST['ehsAction'];
		$replace['ehsAction'] = $ehsAction;
		$subscriptionDetail=$this->getPatientSubscriptionDetails($userId,$clinicId);
		// $replace['message'] = "Are you sure you want to stop using {$clinicName}�s E-Health Service? You may no longer have access to your personalized health portal and the information and services {$clinicName} provides online.";
		$replace['message'] = "You are about to unsubscribe this Health Record from {$subscriptionDetail['subscription_title']}. If you do, this patient will no longer be able to log into his patient portal and receive online care from the practitioner at the end of your current service billing period. Are you sure you want to unsubscribe?";
		//$replace['message'] = "You are about to unsubscribe from E Health Service . If you do, you will no longer be able to log into your patient portal and receive online care from your practitioner at the end of your current service billing period. Are you sure you want to unsubscribe?";
		$replace['subscrp_id']=$_REQUEST['subscrp_id'];
		$replace['userId']=$_REQUEST['userId'];
		$replace['clinicId']=$_REQUEST['clinicId'];
		$replace['body'] = $this->build_template($this->get_template("currentEhealthUnsubscribe"),$replace);
		$replace['browser_title'] = "Tx Xchange: Home";
		$this->output = $this->build_template($this->get_template("main"),$replace);
	}
	

        /**
	 * update clinic credit card information .
	 *
	 * @access public
	 */
	function updatecliniccreditcard()
	{
            //$this->printR($_SESSION['clinic']); 
		$replace = array();
		 $errorCode=$this->value('errorCode');
                        if($errorCode!='') {
                            $customerrormessage=$this->PaypalErroCodeslist($errorCode);
                            if($customerrormessage=='')                    
                                {
                                    $customerrormessage="Invalid Credit Card Details";                        
                                }
                            $replace['errorMessage']='<tr><td colspan="4" style="height:20px; color:#0069a0;; font-weight:bold"><font style="color:#FF0000;font-weight:normal;">'.$customerrormessage.'</font></td></tr><tr><td colspan="4" style="height:5px; color:#0069a0;; font-weight:bold"></td></tr>';   
                            
                   }
                
                
                $option = $this->value('option');
		$clinic_id = $this->value("clinic_id");
		$userInfo = $this->userInfo();
		$cli_type=$this->getClinicDetails($this->userInfo('user_id'));
		//if($cli_type['clinic_type']==4){
		$replace['shopshow']=1;
		$replace['header'] = $this->build_template($this->get_template("header"));
		$replace['sidebar'] = $this->sidebar();
		$replace['clinic_id'] = $clinic_id;
		$replace['subaction'] = $this->value('subaction');
		
		$stateArray = array("" => "Choose State...");
		$stateArray = array_merge($stateArray,$this->config['state']);
		$replace['stateOptions'] = $this->build_select_option($stateArray, $row['clinic_state']);
		$url_array = $this->tab_url();
		$replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$url_array);
$replace['namefValid']='<font style="color:#FF0000;font-weight:normal;">'.urldecode($this->value('namefValid')).'</font>';
            if($this->value('validData')=='true')   {
                    if(urldecode($this->value('namefValid'))!=''){      
                        $replace['namefValid']='<font style="color:#FF0000;font-weight:normal;">'.urldecode($this->value('namefValid')).'</font>';                                         
                     $replace['retainedFname']='';
                    }     
                      $replace['retainedFname']=$_SESSION['clinic']['fname'] ;                                                                            
                    if(urldecode($this->value('namelValid'))!='')       {
                        $replace['namelValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('namelValid').'</font>';                                                  
                     $replace['retainedlname']='' ; 
                    }
                    $replace['retainedlname']=$_SESSION['clinic']['lname'] ;                     
                     if(urldecode($_REQUEST['emailValid'])!='')       {
                        $replace['emailValid']='<font style="color:#FF0000;font-weight:normal;">'.$_REQUEST['emailValid'].'</font>';                                                  
                    
                     $replace['retainedEmail']='' ; 
                    }
                        $replace['retainedEmail']=$_SESSION['clinic']['emailAddress'] ;  
                                                                    
                    if(urldecode($this->value('cnumberValid'))!='')   {     
                        $replace['cnumberValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('cnumberValid').'</font>';
                        $replace['retainedcardNumber'] = '';
                     } 
                                                                                      
                    $replace['retainedcardNumber'] = $_SESSION['clinic']['cardNumber']; 
                                                                                                          
                    if(urldecode($this->value('cexpValid'))!='')  {           
                        $replace['cexpValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('cexpValid').'</font>';
                        //$replace['retainedcardNumber'] = '';
                      }                                                          
                    if(urldecode($this->value('ccvvValid'))!='') {            
                        $replace['ccvvValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('ccvvValid').'</font>'; 
                        $replace['retainedcvvNumber'] = '';
                    }                                      
                    $replace['retainedcvvNumber'] = $_SESSION['clinic']['cvvNumber'];                                        
                    if(urldecode($this->value('ctypeValid'))!='') {                
                        $replace['ctypeValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('ctypeValid').'</font>'; 
                        $replace['retainVisaType'] = '';
                        $replace['retainMasterType'] = '';
                     }
                        if($_SESSION['clinic']['cardType'] == 'Visa') {
                                $replace['retainVisaType'] = 'selected="selected"';
                                $replace['retainMasterType'] = '';
                        }
                        elseif($_SESSION['clinic']['cardType'] == 'MasterCard') {
                                $replace['retainMasterType'] = 'selected="selected"';
                                $replace['retainVisaType'] = '';
                        }
                        else {
                                $replace['retainVisaType'] = '';
                                $replace['retainMasterType'] = '';
                        }
                                                                    
                    if(urldecode($this->value('address1Valid'))!='')   {           
                       $replace['address1Valid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('address1Valid').'</font>';                                              
                       $replace['retainedadd1']='' ;  
                    }     
                       
                       
                    
                        $replace['retainedadd1']=$_SESSION['clinic']['address1'] ;
                     
                       
                    if(urldecode($this->value('cityValid'))!='')    {               
                        $replace['cityValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('cityValid').'</font>';                                                         
                        $replace['retainedcity']='';  
                    }
                        
                        $replace['retainedcity']=$_SESSION['clinic']['city'] ;
                     
                                                            
                    if(urldecode($this->value('stateValid'))!='')  {                  
                        $replace['stateValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('stateValid').'</font>';                                             
                    
                        $replace['retainedstate']=''; 
                    
                    }         
                        
                        $replace['retainedstate']=$_SESSION['clinic']['state'] ;
                     
                            
                    if(urldecode($this->value('zipcodeValid'))!='')    {               
                        $replace['zipcodeValid']='<font style="color:#FF0000;font-weight:normal;">'.$this->value('zipcodeValid').'</font>';                                               
                    
                        $replace['retainedzip']='' ;
                    }
                        
                    
                        $replace['retainedzip']=$_SESSION['clinic']['zipcode'] ;

                

                    if(urldecode($this->value('address2'))=='')
                        $replace['retainedadd2']=$_SESSION['clinic']['address2'] ;
                    } else {
                    $replace['namefValid']='';
                    $replace['retainedFname']='';      
                    $replace['namelValid']=''; 
                    $replace['emailValid']=''; 
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
                    $replace['retainedEmail']='';  
                    $replace['retainedcardNumber']='';             
                    $replace['retainVisaType']=''; 
                    $replace['retainMasterType']=''; 
                    $replace['retainedcvvNumber']='';
                         
             }
            //echo "test".$_SESSION['pateintSubs']['clinicProvider'];
            
          //$clinicProvidersArray = array("" => "Select Provider");
           $clinicProvidersArray = $clinicProviders;//print_r($clinicProvidersArray);exit;
           $replace['clinicProvidersOptions'] = $this->build_select_option($clinicProvidersArray, $_SESSION['clinic']['clinicProvider']);

            $logotx='txlogo';
	    $header_1 = "<div id='line'><div id='".$logotx."' style='float:left; padding-left:55px;' ></div><div id='subheader' style='float:right; padding-right:55px;' ><div id='cliniclogo'></div></div><div style='clear:both;'></div></div>";
	    $replace['header']=$header_1;
            $replace['footer'] = $this->build_template($this->get_template("footer"));    
            $replace['HealthProgramPrice']=$this->config['clinic_price'];
             $replace['HealthProgramDuration']="per User/Month";
            $replace['HealthProgramData'] = "With Tx Xchange, there are no contracts, upfront, training, or support costs. Start today and begin to receive the significant financial, operational, and clinical benefits we provide.";
            $replace['keyFeatures']="<li>Create New Revenue Streams </li>";
            $replace['keyFeatures'].="<li>Increase Patient/Client Retention </li>";
            $replace['keyFeatures'].="<li>Reduce Uncompensated Time </li>";
            $replace['keyFeatures'].="<li>Improve Patient/Client Outcomes</li>";
            $replace['KeyFeatureHeading']="Key Benefits";
            $replace['browser_title'] = "Tx Xchange: Payment Information";
            if($_SESSION['clinic']['cardType'] == 'Visa') {
                                $replace['retainVisaType'] = 'selected="selected"';
                                $replace['retainMasterType'] = '';
                        }
                        elseif($_SESSION['clinic']['cardType'] == 'MasterCard') {
                                $replace['retainMasterType'] = 'selected="selected"';
                                $replace['retainVisaType'] = '';
                        }
                        else {
                                $replace['retainVisaType'] = '';
                                $replace['retainMasterType'] = '';
                        }
            $replace['cardNumber']	=	$_SESSION['clinic']['cardNumber'];
            $replace['cvvNumber']	=	$_SESSION['clinic']['cvvNumber'];
            $replace['address1']	=	$_SESSION['clinic']['address1'];
            $replace['address2']	=	$_SESSION['clinic']['address2'];
            $replace['city']		=	$_SESSION['clinic']['city'];
            $replace['zipcode']		=	$_SESSION['clinic']['zipcode'];
            
            $monthArray = array();
            $monthArray = $this->config['monthsArray'];
            $replace['monthsOptions'] = $this->build_select_option($monthArray, $_SESSION['clinic']['exprMonth']); 
           
            $yearArray = array();
            $yearArray = $this->config['yearArray'];
            $replace['yearOptions'] = $this->build_select_option($yearArray, $_SESSION['clinic']['exprYear']);
            $countryarray=$this->country_list(); 
			$replace['country']=$this->build_select_option($countryarray, $_SESSION['clinic']['country']);
           if($_SESSION['clinic']['country']=='')
            $country_code='US';
		else
	    $country_code=$_SESSION['clinic']['country'];
	    $stateArray=$this->state_list($country_code);
	    $replace['stateOptions']=$this->build_select_option($stateArray, $_SESSION['clinic']['state']);		 

            
            $sql="select * from provider_subscription  where clinic_id =".$clinic_id." order by user_subs_id limit 0,1";
                $query=$this->execute_query($sql);
                if($this->num_rows($query)==1){
                 $row=$this->fetch_object($query);
                 //include 'freetrial.class.php';
                 //$obj=new freetrial();
                 $httpParsedResponseAr=  $this->getpaypalprofiledetail($row->reurring_profile_id);
                 $replace['ccno']=$httpParsedResponseAr['ACCT'];
            }
            $replace['cardPayment']=1;
	   $replace['body'] = $this->build_template($this->get_template("updatecreditcard"),$replace);
	   $replace['get_satisfaction'] = $this->get_satisfaction();
	   $this->output = $this->build_template($this->get_template("main"),$replace);
	}
        function getpaypalprofiledetail($Paypal_Profile){
                    $API_UserName       =       urlencode($this->config["clinicpaypalprodetails"]["API_UserName"]);
                    $API_Password       =       urlencode($this->config["clinicpaypalprodetails"]["API_Password"]);
                    $API_Signature      =       urlencode($this->config["clinicpaypalprodetails"]["API_Signature"]);
                    $environment        =       urlencode($this->config["clinicpaypalprodetails"]["environment"]);
                    $currencyID         =       urlencode($this->config["clinicpaypalprodetails"]["currencyID"]);
                    $Frequency          =       urlencode($this->config["clinicpaypalprodetails"]["billingPeriod"]);
                    $billingFreq        =       urlencode($this->config["clinicpaypalprodetails"]["billingFreq"]);
                    
                    $paypalObjectCancel=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
                    $profileID=urlencode($Paypal_Profile);
                    $action=urlencode("Cancel");
                    $nvpStr="&PROFILEID=$profileID";
                    $paypalObjectCancel=new paypalProRecurring($API_UserName,$API_Password,$API_Signature,$environment);
                    $httpParsedResponseAr = $paypalObjectCancel->PPHttpPost('GetRecurringPaymentsProfileDetails', $nvpStr);
                    return $httpParsedResponseAr;
                    
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
function updatecardmessage()
    {
        $replace['updatecardmessage']="You have successfully updated your credit card.<br>Thank you.<br>

To verify your account information, 
we placed a temporary authorization hold on your payment card account in the amount of &#36;1.00.
While these authorization holds are removed,
you might see them on your online statement depending on your card statement cycle.";

        $replace['body'] = $this->build_template($this->get_template("updatecardmessage"),$replace);
        $this->output = $this->build_template($this->get_template("main"),$replace);
    }
}
// creating object of this class.
$obj = new headAccountAdmin();

/*
 header("Pragma: public"); // required
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false); // required for certain browsers
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"".basename($filename)."\";" );
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".filesize($filename));
readfile("$filename");
exit;

*/
?>
