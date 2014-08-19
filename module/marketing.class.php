<?php


/**
 *
 * Copyright (c) 2008 Tx Xchange.
 * This class adds maketing features in the application(like best practices and bulletin board.
 *
 *
 * // necessary classes
 * require_once("module/application.class.php");
 *
 *
 * // validation classes
 * require_once("include/validation/_includes/classes/validation/ValidationSet.php");
 * require_once("include/validation/_includes/classes/validation/ValidationError.php");
 *
 */



// including files
require_once("module/application.class.php");




// class declaration
class marketing extends application{


	// class variable declaration section

	/**
	 * The variable defines the action request
	 *
	 * @var string
	 * @access private
	 */
	private $action;


	/**
	 * The variable defines all the fields present in the form
	 *
	 * @var string Array
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
	 * The variable is used for getting final output template or string message to be displayed to the user
	 * This function of statement(s) are to handle all the actions supported by this Login class
	 * that is it could be the case that more then one action are handled by login
	 * for example at first the action is "login" then after submit say action is submit
	 * so if login is explicitly called we have the login action set (which is also our default action)
	 * else whatever action is it is set in $str.
	 *
	 * @var String
	 * @access Private
	 */
	private $output;

	/**
	 * Constructor
	 *
	 * Check for user login and cll appropriate function of the class.
	 * Get action from query string
	 * Also check that user have rights to access this class or not.
	 *
	 * Call display function to generate final output.
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
			$str = "bestPractices"; //default if no action is specified
		}
		$this->action = $str;
		if($this->get_checkLogin($this->action) == "true" ){
			// check for session
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
			// check logged in user is not a primary account admin.
			$clinic_id = $this->clinicInfo('clinic_id');
			$parent_clinic_id = $this->get_field($clinic_id,'clinic','parent_clinic_id');
			$arr_action = array('bestPractices','bulletinBoard');
			if( is_numeric($parent_clinic_id) && $parent_clinic_id == 0){
				if(in_array($str,$arr_action)){
					header('location:index.php?action=accountAdminClinicList');
					exit();
				}
			}
			// End
			$str = $str."()";
			eval("\$this->$str;");
		}
		else{
			$this->output = $this->config['error_message'];
		}
			
		$this->display();
	}

	/**
	 * Function to show best practices..
	 *
	 * @access public
	 */
	function bestPractices(){
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
		// if($cli_type['clinic_type']==4){
		$replace['shopshow']=1;
		/*}else{
		 $replace['shopshow']='0';
		}*/
		$replace['browser_title'] = 'Tx Xchange: Best Practices';
		$replace['footer'] = $this->build_template($this->get_template("footer"));
		$replace['header'] = $this->build_template($this->get_template("header"));
		$replace['url'] = $this->config['images_url'];
		$replace['sidebar'] = $this->sidebar();
		$replace['navigationTab'] = $this->build_template($this->get_template("navigationTab"));
		$replace['body'] = $this->build_template($this->get_template("bestPractices"),$replace);
		$replace['get_satisfaction'] = $this->get_satisfaction();
		$this->output = $this->build_template($this->get_template("main"),$replace);
	}
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
	 *
	 * @access public
	 */
	function bulletinBoard(){
			
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
		$replace['navigationTab'] = $this->build_template($this->get_template("navigationTab"));
		$replace['body'] = $this->build_template($this->get_template("bulletinBoard"),$replace);
		$replace['get_satisfaction'] = $this->get_satisfaction();
		$this->output = $this->build_template($this->get_template("main"),$replace);
	}

	/**
	 * Function to populate side panel dynamically.
	 *
	 * @return side bar menu template
	 * @access public
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
	 * This is used to get the name of the template file from the config_txxchange.xml
	 * for the action request
	 *
	 * @param String $template
	 * @return template page info
	 * @access public
	 */
	function get_template($template){
		$login_arr = $this->action_parser($this->action,'template') ;
		$pos =  array_search($template, $login_arr['template']['name']);
		return $login_arr['template']['path'][$pos];
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
	 * Function to show clinic logo image upload interface and process.
	 *
	 * @access public
	 */
	function EHS_introductory_email(){

		$replace = array();
                
                /* code for show telespine menu on list */
        $replace['showmenu']="no";
        if($this->clinicInfo('clinic_id')==$this->config['telespineid']){
            $replace['showmenu']="yes";
        }
        /* code end */
			$replace['message'] ="";
			$replace['color']='';
		if($_REQUEST['save_introductory_email']=='submit'){
		 $query = " select * from clinic_ehs_mail_template where clinic_id = '{$this->clinicInfo("clinic_id")}' ";
		 $result = @mysql_query($query);
		 if(trim($_REQUEST['subject']) ==''){
		 	$replace['message'] ="Please enter subject for EHS Email Template";
		 	$replace['color']='red;';
		 }
		 else
		 {
		 	$data['clinic_id']=$this->clinicInfo("clinic_id");
		 	$data['mailcontent']=$_REQUEST['subs_description'];
		 	$data['subject']=$_REQUEST['subject'];
		 	$data['status']='1';
		 	$data['accountadmin_id']=$this->userInfo('user_id');
		 	if($this->num_rows($result)>0){
		 		$this->update('clinic_ehs_mail_template', $data, 'clinic_id ='.$this->clinicInfo("clinic_id"));
		 	}else{
		 		$this->insert( 'clinic_ehs_mail_template', $data);
		 	}
		 	$replace['message'] ="EHS Mail Template saved successfully";
			$replace['color']='green;';
		 }

		}

		$userInfo = $this->userInfo();
		$cli_type=$this->getClinicDetails($this->userInfo('user_id'));
		// Make links for show and remove logo.
		//print_r($cli_type);
		$query = " select * from clinic_ehs_mail_template where clinic_id = '{$this->clinicInfo("clinic_id")}' ";
		$result = @mysql_query($query);
		if($this->num_rows($result)>0){
			$row=$this->fetch_object($result);
		}
		// set template variables

		$replace['browser_title'] = 'Tx Xchange: EHS Intro Email';
		$tab_array = $this->tab_url();
		//print_r($tab_array);
		if($_REQUEST['save_introductory_email']=="submit"){
			$replace['subject']=$_REQUEST['subject'];
			$replace['subs_description']=$_REQUEST['subs_description'] ;
		}else{
			$replace['subs_description']=$row->mailcontent ;
			$replace['subject']=$row->subject ;
		}
		$replace['navigationTab'] = $this->build_template($this->get_template("tabNavigationHead"),$tab_array);
		$replace['footer'] = $this->build_template($this->get_template("footer"));
		$replace['header'] = $this->build_template($this->get_template("header"));
		$replace['sidebar'] = $this->sidebar();
		if($this->is_corporate($clinicId)==1){
                 $replace['body'] = $this->build_template($this->get_template("corporate"),$replace);
                 }else{
                  $replace['body'] = $this->build_template($this->get_template("website"),$replace);
                 }
                
                
		$this->output = $this->build_template($this->get_template("main"),$replace);
                
	}





	/**
	 * This is used to display the final template page.
	 *
	 * @access public
	 */
	function display(){
		view::$output =  $this->output;
	}



}

// creating object of this class
$obj = new marketing();
?>
