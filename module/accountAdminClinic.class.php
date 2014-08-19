<?php
	/**
	 * 
	 * Copyright (c) 2008 Tx Xchange.
	 * It includes the functionality for list clinic, Edit existing and Add new Clinic.
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
	require_once("include/validation/_includes/classes/validation/ValidationSet.php");
	require_once("include/validation/_includes/classes/validation/ValidationError.php");
	// class declaration
  	class accountAdminClinic extends application{
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
			$this->form_array = $this->populate_form_array();
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
				$str = "accountAdminClinic"; //default if no action is specified
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
                $arr_action = array('accountAdminClinic','editClinicDetails');
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
		 * Function to get Patient listing for a clinic.
		 *
		 * @access public
		 */
		function accountAdminClinic(){
			$replace = array();
			// set template variables
		$replace['showmenu']="no";
                if($this->clinicInfo('clinic_id')==$this->config['telespineid']){
                    $replace['showmenu']="yes";
                 }	
		$userInfo = $this->userInfo();
            $cli_type=$this->getClinicDetails($this->userInfo('user_id'));
            //if($cli_type['clinic_type']==4){
                $replace['shopshow']=1;
            /*}else{
                $replace['shopshow']='0';
            }*/
			$replace['browser_title'] = 'Tx Xchange: Clinic Details';
			$replace['footer'] = $this->build_template($this->get_template("footer"));
			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['sidebar'] = $this->sidebar();
			
			// sql query
			$query = "select * from clinic where clinic_id='".$this->clinicInfo('clinic_id')."'";
			$rs = $this->execute_query($query);
			$row = $this->populate_array($rs);
			// Fetech Replace array from row
			$this->assignValueToArrayFields($this->form_array, $row, '1', &$replace);
			
			$replace['commaseprator'] = (!empty($replace['clinic_address2'])) ? '<br/>': '';
            $replace['navigationTab'] = $this->build_template($this->get_template("navigationTab"));
			$replace['body'] = $this->build_template($this->get_template("clinic_details"),$replace);
				$replace['get_satisfaction'] = $this->get_satisfaction();
            $this->output = $this->build_template($this->get_template("main"),$replace);
		}
		/**
		 * Function to Edit Clinic Details.
		 *
		 * @access public
		 */
		function editClinicDetails()
		{
			$replace = array();
		$userInfo = $this->userInfo();
            $cli_type=$this->getClinicDetails($this->userInfo('user_id'));
           // if($cli_type['clinic_type']==4){
                $replace['shopshow']=1;
            /*}else{
                $replace['shopshow']='0';
            }*/
			$option = $this->value('option');
			$replace['footer'] = $this->build_template($this->get_template("footer"));	
			$replace['browser_title'] = 'Tx Xchange: Clinic Details';
			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['sidebar'] = $this->sidebar();
			// When user modify the clinic details
			if($option == 'update') {
				$this->validateForm();
				if($this->error == ""){
					$this->assignValueToArrayFields($this->form_array);
					//  Populate FieldArray from FormArray
					$updateArr = $this->assignValueToArrayFields($this->form_array, '', '2', '', 'update');
					$where = " clinic_id = ".$this->clinicInfo('clinic_id');
					
					$result = $this->update('clinic',$updateArr,$where);
					header("location:index.php?action=accountAdminClinic");
                    exit();
				}else{
					$this->assignValueToArrayFields($this->form_array, '', '', &$replace, '0');
					$replace['error'] = $this->error;
				}
			// When user view the current clinic details in Edit mode				
			} else {
				$query = "select * from clinic where clinic_id='".$this->clinicInfo('clinic_id')."'";
				$rs = $this->execute_query($query);
				$row = $this->populate_array($rs);
				// Fetch Replace array from row
				// populate FormArray from FieldArray
				$this->assignValueToArrayFields($this->form_array, $row, '1', &$replace);
			}
			
			$stateArray = array("" => "Choose State...");
			$stateArray = array_merge($stateArray,$this->config['state']);
			$replace['stateOptions'] = 	$this->build_select_option($stateArray, $replace['clinic_state']);
            	$replace['navigationTab'] = $this->build_template($this->get_template("navigationTab"));
		$countryArray = $this->config['country'];
	        $replace['country']=implode("','",$countryArray); 
                $replace['patient_country_options'] = $this->build_select_option($countryArray, $row['country']);
		            
		            
		            
		           
	            if($row['country']=='US') {
	            $stateArray = array("" => "Choose State...");
	            $stateArray = array_merge($stateArray,$this->config['state']);              
	            $replace['stateOptions'] = $this->build_select_option($stateArray, $replace['clinic_state']);         
	            }
	           
	            else if($row['country']=='CAN') {
	            $stateArray = array("" => "Choose State...");
	            $stateArray = array_merge($stateArray,$this->config['canada_state']);
	            $replace['stateOptions'] = $this->build_select_option($stateArray, $replace['clinic_state']);         
	            } 	
            
            $replace['body'] = $this->build_template($this->get_template("clinic_details"),$replace);
			$replace['get_satisfaction'] = $this->get_satisfaction();
            $this->output = $this->build_template($this->get_template("main"),$replace);
		}
		/**
		 * Function to validate the patient credentials like name address email etc.
		 *
		 * @access public
		 */		
		function validateForm()
		{

			// creating validation object			
			$objValidationSet = new ValidationSet();
			
			// validate clinic name
			$objValidationSet->addValidator(new StringMinLengthValidator('clinic_name', 1, "Clinic Name cannot be empty",$this->value('clinic_name')));					
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('clinic_name', null,"Please enter valid characters in Clinic Name",$this->value('clinic_name')));
			
			// validating clinic address line 1
			$objValidationSet->addValidator(new StringMinMaxLengthValidator('clinic_address', 0,50, "Address cannot be more than 50 characters",$this->value('clinic_address')));			
			$objValidationSet->addValidator(new StringMinLengthValidator('clinic_address', 1,"Address cannot be empty",$this->value('clinic_address')));
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('clinic_address', null,"Please enter valid characters in address",$this->value('clinic_address')));
			
			// validating clinic address line 2
			$objValidationSet->addValidator(new StringMinMaxLengthValidator('clinic_address2',0,50, "Address line 2 cannot be more than 50 characters",$this->value('clinic_address2')));			
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('clinic_address2', null,"Please enter valid characters in address line 2",$this->value('clinic_address2')));

			// validating city name
			$objValidationSet->addValidator(new StringMinLengthValidator('clinic_city', 1,"City cannot be empty",$this->value('clinic_city')));
			$objValidationSet->addValidator(new StringMinMaxLengthValidator('clinic_city',0,20, "City name cannot be more than 20 characters",$this->value('clinic_city')));			
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('clinic_city', null,"Please enter valid characters in city",$this->value('clinic_city')));
			
			// validating state name
			$objValidationSet->addValidator(new StringMinLengthValidator('clinic_state', 2,"State cannot be empty",$this->value('clinic_state')));
			
			// validating zip code
			//$objValidationSet->addValidator(new AlphanumericOnlyValidator('clinic_zip', array(' '),"Please enter only alphanumeric values in zip",$this->value('clinic_zip')));
			//$objValidationSet->addValidator(new StringMinMaxLengthValidator('clinic_zip', 5, 7, "Zip code should be 5 to 7 alphanumeric characters",$this->value('clinic_zip')));
			 /*if($this->value('clinic_country')=='CAN'){
                $objValidationSet->addValidator(new  AlphanumericOnlyValidator('clinic_zip', array(' '), "Zip code should be of alphanumeric characters only",$this->value('zip')));                    
                $objValidationSet->addValidator(new  StringMinMaxLengthValidator('clinic_zip', 6,7, "Zip code should be  6 to 7 alphanumeric characters only",$this->value('zip')));        
               }else{
                $objValidationSet->addValidator(new  NumericOnlyValidator('clinic_zip', null, "Zip code should be of numeric characters only",$this->value('zip')));                    
                $objValidationSet->addValidator(new  StringMinMaxLengthValidator('clinic_zip', 5,5, "Zip code should be  5 numeric characters only",$this->value('zip')));
            }*/
            
			// validating phone
            $objValidationSet->addValidator(new StringMinMaxLengthValidator('clinic_phone',10,13, "Phone must have 10 digit number ",$this->value('clinic_phone')));
            $objValidationSet->addValidator(new NumericOnlyValidator('clinic_phone', array('-','.'),"Please enter valid number like 3035626737 or 303.562.6737 or 303-562-6737",$this->value('clinic_phone')));
            
            
			/*
				Check clinic name for duplicacy
			
			
			$clinic_id = $this->clinicInfo('clinic_id');
			
			$clinic_name = $this->value('clinic_name');
			$queryClinic = "SELECT clinic_id FROM clinic WHERE clinic_name = '".$clinic_name."' AND status <> 3 AND clinic_id <> ".$clinic_id;				
			
			
			$result = $this->execute_query($queryClinic);
				
			//if record found that means clinic name not unique else it is unique
			if ($this->num_rows($result) != 0)
			{
				$objValidationErr = new ValidationError('clinic_name',"Clinic Name : exists in the system. Please choose another.");
				$objValidationSet->addValidationError($objValidationErr);
			}							
				
			
			End of checking for duplicacy */
			
			
			
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
				
				$arrayFields = array("clinic_name","clinic_address","clinic_address2","clinic_city","clinic_state", "clinic_zip","clinic_phone");
				
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
		 * This is used to display the final template page.
		 *
		 * @access public
		 */
		function display(){
			view::$output =  $this->output;
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
							'clinic_name' 			=> 'clinic_name',
							'clinic_address' 		=> 'address',
							'clinic_address2' 		=> 'address2',
							'clinic_city' 			=> 'city',
							'clinic_state' 			=> 'state',
							'clinic_country' 		=> 'country',
							'clinic_zip' 			=> 'zip',
                            'clinic_phone'          =>  'phone'
						);
			return $arr;			
		}
		
	
	}
	
	// creating object of this class
	$obj = new accountAdminClinic();
?>
