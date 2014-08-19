<?php


	/**
	 * 
	 * Copyright (c) 2008 Tx Xchange.
	 * 
	 * This class check whether user accept privacy policy and agreement or not.
	 * For Therapist and Account Admin only.
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
  	class agreement extends application{

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
		private $field_array;

		
		
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

				$str = "agreement"; //default if no action is specified

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

			$str = $str."()";
			if ($this->userInfo('agreement') == 1)
			{
				$this->redirect_default_action();
			}
			eval("\$this->$str;"); 
				
			$this->display();

		}

		/**
		 * Function to accept the Terms of Services & Privacy Policy and email promotion
		 * 
		 * @access public
		 */
		function agreement(){
            
			if ($this->userInfo('agreement') == 1)
			{
				$this->redirect_default_action();
			}
			
			
			$userInfo = $this->userInfo();			
			
			$replace['firstName'] = $userInfo['name_first'];
			$replace['lastName'] = $userInfo['name_last'];
			$replace['email'] = $userInfo['username'];
			$replace['id'] = $userInfo['user_id'];
			
			$replace['sidebar'] = application::sysadmin_link(false);
			
			$ret = "";
			
			if(!empty($userInfo['address'])) $ret .= $userInfo['address'] . '<br />';
			
			if(!empty($userInfo['address2'])) $ret .= $userInfo['address2'] . '<br />';			
			
			
			if(!empty($ret))
			{
			
				if (!empty($userInfo['city']) && !empty($userInfo['state']) && !empty($userInfo['zip']))
				{
					$ret .= $userInfo['city'] . ', '. $userInfo['state'] . ' ' .$userInfo['zip'].'<br />';									
	
				}
				else if (!empty($userInfo['city']) && !empty($userInfo['state']))
				{
					$ret .= $userInfo['city'] .' ' .$userInfo['state'].'<br />';
				}
				else if (!empty($userInfo['city']) && !empty($userInfo['zip']))
				{
					$ret .= $userInfo['city'] .' ' .$userInfo['zip'].'<br />';
				}
				else if (!empty($userInfo['state']) && !empty($userInfo['zip'])) 
				{
					$ret .= $userInfo['state'] .' ' .$userInfo['zip'].'<br />';	
				}
				else if (!empty($userInfo['city']))
				{
					$ret .= $userInfo['city'].'<br />';
				}
				else if (!empty($userInfo['state']))
				{
					$ret .= $userInfo['state'].'<br />';
				}
				else if (!empty($userInfo['zip']))
				{
					$ret .= $userInfo['zip'].'<br />';
				}
				$ret .= (empty($userInfo['phone1'])) ? "" :"Phone 1:".$userInfo['phone1']."<br/>";
				$ret .= (empty($userInfo['phone2'])) ? "" :"Phone 2:".$userInfo['phone2']."<br/>";			
				$ret .= (empty($userInfo['fax'])) ? "" :"Fax:".$userInfo['fax'];
			}
			else 
			{
				$ret = "No Address Information";
			}
			
			$replace['address'] = $ret;

			$replace['new_password'] = "";		
			$replace['new_password2'] = "";
			$replace['answer'] = "";
			$replace['agree_terms'] = "";
			$replace['email_promotions'] = "";
			
			
			//Questions from question table
			$questions = array(""=>"-- Select Security Question--");
			
			$query = "SELECT * FROM questions where question_id not in (3,7)";
			$result = $this->execute_query($query);
			
			
			if($this->num_rows($result)!= 0)
			{
				while($row = $this->fetch_array($result))
				{	
					$questions[$row['question_id']] = $row['question'];
				}
				
			}	
			
			$selectedQuestion = "";
			$replace['questionOptions']	= $this->build_select_option($questions,$selectedQuestion);
			
			$replace['header'] = $this->build_template($this->get_template("header"));			
			
			$replace['body'] = $this->build_template($this->get_template("container"),$replace);
			$replace['browser_title'] = "Tx Xchange: Provider Home";
			$this->output = $this->build_template($this->get_template("main"),$replace);

		}		

		/**
		 * Function to validate fields on Terms of Services & Privacy Policy page.
		 * 
		 * @access public
		 */		
		function submitAgreement()
		{
			
			$id = (int) $this->value('id');	
			
			$replace = array();
			
			$userInfo = $this->userInfo();				
			
			
			if ($userInfo['agreement'] != 1)
			{	
										
				$userInfo = $this->userInfo();			
				
				$replace['firstName'] = $userInfo['name_first'];
				$replace['lastName'] = $userInfo['name_last'];
				$replace['email'] = $userInfo['username'];
				
				
				
				$ret = "";
				
				if(!empty($userInfo['address'])) $ret .= $userInfo['address'] . '<br />';
				
				if(!empty($userInfo['address2'])) $ret .= $userInfo['address2'] . '<br />';
				
				if(!empty($ret))
				{
				
					if (!empty($userInfo['city']) && !empty($userInfo['state']) && !empty($userInfo['zip']))
					{
						$ret .= $userInfo['city'] . ', '. $userInfo['state'] . ' ' .$userInfo['zip'].'<br />';									
		
					}
					else if (!empty($userInfo['city']) && !empty($userInfo['state']))
					{
						$ret .= $userInfo['city'] .' ' .$userInfo['state'].'<br />';
					}
					else if (!empty($userInfo['city']) && !empty($userInfo['zip']))
					{
						$ret .= $userInfo['city'] .' ' .$userInfo['zip'].'<br />';
					}
					else if (!empty($userInfo['state']) && !empty($userInfo['zip'])) 
					{
						$ret .= $userInfo['state'] .' ' .$userInfo['zip'].'<br />';	
					}
					else if (!empty($userInfo['city']))
					{
						$ret .= $userInfo['city'].'<br />';
					}
					else if (!empty($userInfo['state']))
					{
						$ret .= $userInfo['state'].'<br />';
					}
					else if (!empty($userInfo['zip']))
					{
						$ret .= $userInfo['zip'].'<br />';
					}
				}
				else 
				{
					$ret = "No Address Information";
				}
				
				$replace['address'] = $ret;
				
				//Questions from question table
				$questions = array(""=>"-- Select Security Question--");
				
				// building query 
				$query = "SELECT * FROM questions where question_id not in (3,7)";
				$result = $this->execute_query($query);
				
				
				if($this->num_rows($result)!= 0)
				{
					while($row = $this->fetch_array($result))
					{	
						$questions[$row['question_id']] = $row['question'];
					}
					
				}							
				
				//form submitted check for validation
				
				$this->validateAgreement();											
			
				if($this->error == "")
				{
					//Form validated, no errors
					//go ahead store the values in db											
				
					$agreement = 0;
					$agreement_privacy_policy = 0;
					$email_promotions = 0; 
					
					
					if ((isset($_POST['agree_terms']))) 
					{
						$agreement = 1;	
					}
					
				
					if (isset($_POST['email_promotions'])) 
					{
						$email_promotions = 1;	
					}
					
					$updateArr = array(
										'password'=>$this->value('new_password'),
										'agreement'=>$agreement,
										'email_promotions'=>$email_promotions,
										'agreement_date' => date('Y-m-d H:i:s',time())	
										);
								
									
					$where = " user_id = ".$id;
						
					$result = $this->update('user',$updateArr,$where);							
					
					//Added Dec 26, 2007 Now check for the secret question answer record exist in answer table or not
					//if yes then update else insert the record in answer table	

					$queryAnswers = "SELECT * FROM answers WHERE user_id = '".$id."'";
					
					$resultAnswers = $this->execute_query($queryAnswers);		
			
					if($this->num_rows($resultAnswers)!= 0)
					{					
					
						$updateArr = array(
											'question_id' => $this->value('question_id'),
											'answer'=>$this->value('answer')										
											);
											
						$where = " user_id = ".$id;
							
						$result = $this->update('answers',$updateArr,$where);	
					}
					else 
					{
						$insertArr = array(
											'user_id'=>$id,
											'question_id' =>$this->value('question_id'),
											'answer' => $this->value('answer')				
											);						
						
						$result = $this->insert('answers',$insertArr);
					}
					
					$_SESSION['username'] = $userInfo['username'];

					$_SESSION['password'] = $this->value('new_password');
					
					{// Mail Block
					
						//have the HTML content						
						$replace['images_url'] = $this->config['images_url'];
						$replace['Fullname'] = $userInfo['name_first']." ".$userInfo['name_last'];						
						$replace['Datetime'] = date('Y-m-d H:i:s',time());
						$replace['logoname'] = 'TxXchange_LogoTag.jpg';
                        
                        // Get clinic name
                        $clinic_id = $this->clinicInfo("clinic_id");
                        if( isset($clinic_id) && $clinic_id != "" ){
                            $sql = "select clinic_name,clinic_channel from clinic where clinic_id = '{$clinic_id}'";
                            $sql_result = @mysql_query($sql);
                            if( $sql_row = @mysql_fetch_array($sql_result)){
                                 $replace['clinic_name'] = "(".html_entity_decode($sql_row['clinic_name'],ENT_QUOTES, 'ISO-8859-15').")";
                            }
                            else{
                                 $replace['clinic_name'] = "";
                            }
                            if($sql_row['clinic_channel']==1){
                            	$replace['logoname'] = 'TxXchange_LogoTag.jpg';
                            }else{
                            	$replace['logoname'] = 'wmdx.jpg';
                            }
                        }						
						// end
						$user_id = $this->userInfo('user_id');
                        $clinic_type = $this->getUserClinicType($user_id);
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
                        if( $clinic_channel == 1){
                        	$message = $this->build_template($this->get_template("agreementContent_plpto"),$replace);
                        }
                        else {
                            $message = $this->build_template($this->get_template("agreementContent_wx"),$replace);
                        }						
						//echo $message;die;
						$to = $this->config['from_email_address'];
						$subject = html_entity_decode($replace['Fullname'],ENT_QUOTES, 'ISO-8859-15')." has accepted the agreement";
						
						// To send HTML mail, the Content-type header must be set
						$headers  = 'MIME-Version: 1.0' . "\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						
						// Additional headers
						//$headers .= 'To: '.$fullName.'<'.$_POST['email_address'].'>'. "\n";
						
						//$headers .= 'From: support@txxchange.com'."\n";
						//$headers .= 'Cc: example@example.com' . "\n";
						//$headers .= 'Bcc: example@example.com' . "\n";
						//$returnpath = '-fsupport@txxchange.com';
							if( $clinic_channel == 1){
							    $headers .= "From:  <".$this->config['email_tx'].">" . "\n";
							    $returnpath = "-f".$this->config['email_tx'];
							    }else{
							    $headers .= "From:  <".$this->config['email_wx'].">" . "\n";
							    $returnpath = '-f'.$this->config['email_wx'];   
							    }
						// Mail it					
						mail($to, $subject, $message, $headers, $returnpath);		
					}
					
					// redirect to therapist home page
					$this->redirect_default_action();
					
					
				}
				else
				{
					
								
					$replace['error'] = $this->error;	
					
					//Also set the question
					$selectedQuestion = ($this->value('question_id') == "")? "": $this->value('question_id');	
					
					//Also the check marks
					$agree_terms = (isset($_POST['agree_terms']))? 'checked="checked"': '';
					$email_promotions = (isset($_POST['email_promotions']))? 'checked="checked"': '';
					
					$replace['sidebar'] = application::sysadmin_link(false);
					$replace['agree_terms'] = $agree_terms;
					$replace['email_promotions'] = $email_promotions;
					
					$replace['new_password'] = $this->value('new_password');					
					$replace['new_password2'] = $this->value('new_password2');
					
					$replace['answer'] = $this->value('answer');
					
				}
				
									
				
				$replace['id'] = $id;			
				
				$replace['questionOptions']	= $this->build_select_option($questions,$selectedQuestion);				
						
				
				$replace['header'] = $this->build_template($this->get_template("header"));			
			
				$replace['body'] = $this->build_template($this->get_template("container"),$replace);
				$replace['browser_title'] = "Tx Xchange: Provider Home";
				$this->output = $this->build_template($this->get_template("main"),$replace);				
					
			}
			else 
			{
				//header("location:index.php?action=therapist");
				$this->redirect_default_action();
			}
			
			
		}

		/**
		 * Function to validate form fields
		 *
		 * @access public
		 */
		function validateAgreement()
		{
					
			// creating validation object				
			$objValidationSet = new ValidationSet();					
			
			// validate password field
			$objValidationSet->addValidator(new  StringMinLengthValidator('new_password', 6, "Password cannot be empty and must be of minimum 6 character in length",$this->value('new_password')));					
				
			// match password and confirm password
			$arrFieldNames = array("new_password","new_password2");
			$arrFieldValues = array($_POST["new_password"],$_POST["new_password2"]);
			$objValidationSet->addValidator(new  IdenticalValuesValidator($arrFieldNames, "New password and  confirm password does not match",$arrFieldValues));					
			
			if ($this->value('question_id') == '')
			{
				$objValidationErr = new ValidationError('question_id',"Please select a secret question");
				$objValidationSet->addValidationError($objValidationErr);
			}
			else 
			{
				$objValidationErr = new ValidationError('question_id',"");
				$objValidationSet->addValidationError($objValidationErr);
			}			
			
			
			$objValidationSet->addValidator(new  StringMinLengthValidator('answer', 1, "Answer cannot be empty",$this->value('answer')));		
			
			
			
			
			
			
			$objValidationSet->validate();		
			
			
			// validating agree terms and policies check box
			if (isset($_POST['agree_terms']))
			{
				$objValidationErr = new ValidationError('usertype_id',"");
				$objValidationSet->addValidationError($objValidationErr);				
				
			}
			else 
			{
				$objValidationErr = new ValidationError('agree_terms',"Please Agree to the Terms of Service & Privacy Policy.");
				$objValidationSet->addValidationError($objValidationErr);
			}
				
		
			// check for errors
			if ($objValidationSet->hasErrors())
			{
				
				
				$arrayFields = array("new_password","question_id","answer",'agree_terms');
				
					
				
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
     * Returns the user to their home page if agreement accept user.
     * 
     * @access public
     */
    function redirect_default_action()
    {
        if($this->userInfo('usertype_id')  == '4' )
        {
            header("location:index.php?action=sysAdmin");
            exit();
        }
        elseif($this->userInfo('usertype_id')  == '2')
        {
            $clinic_id = $this->clinicInfo('clinic_id');

            //check if clinic is making payment with online mode
            if($this->clinicMadeOnlinePayment($clinic_id))
            {
                $querySubscription = "
                    SELECT 
                        *
                    FROM
                        provider_subscription ps
                    WHERE
                        ps.clinic_id = '{$clinic_id}'
                        AND (
                                (ps.subs_status = '1' AND subs_end_datetime > NOW())
                                    OR 
                                (ps.subs_status = '2' AND subs_end_datetime > NOW())
                            )
                   ORDER BY user_subs_id DESC";

                $resultSubscriptionStatus = $this->execute_query($querySubscription);
                if(!$this->num_rows($resultSubscriptionStatus))
                {
                    // Provider's subscription has expired, redirect him to update_paypal_profile page.
                    header("location:index.php?action=updateaccountpaypalprofile");
                    exit();
                }
            }

            if($this->userInfo('therapist_access') == '1')
            {
                header("location:index.php?action=therapist");
                exit();
            }
            elseif($this->userInfo('admin_access') == '1')
            {
                $clinicId = $this->clinicInfo("clinic_id",$this->userInfo('user_id'));
                $parentClinicId = $this->get_field($clinicId,'clinic','parent_clinic_id');
                if( is_numeric($parentClinicId) && $parentClinicId == 0)
                {
                    header("location:index.php?action=addonServicesHead");
                    exit();
                }
                else
                {
                    header("location:index.php?action=addonServices");
                    exit();
                }
            }
            else
            {
                echo  "You do not have any privilege contact your Account admin.";
                exit();
            }
        }
        else
        {
            echo  "You do not have any privilege contact your Account admin.";
            exit();
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

		

	}
	// creating the object of this class
	$obj = new agreement();

?>

