<?php

/**
 *
 * Copyright (c) 2008 Tx Xchange
 *
 *
 * // necessary classes
 * require_once("module/application.class.php");
 *
 */

require_once("module/application.class.php");

class patientaccount extends application
{
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
    function __construct()
    {
        parent::__construct();
        if($this->value('action'))
        {
            /*
              This block of statement(s) are to handle all the actions supported by this Login class
              that is it could be the case that more then one action are handled by login
              for example at first the action is "login" then after submit say action is submit
              so if login is explicitly called we have the login action set (which is also our default action)
              else whatever action is it is set in $str.
             */
            $str = $this->value('action');
        }
        else
        {
            $str = "patientaccount"; //default if no action is specified
        }
        $this->action = $str;
        if($this->get_checkLogin($this->action) == "true")
        {

            if(isset($_SESSION['username']) && isset($_SESSION['password']))
            {

                if(!$this->chk_login($_SESSION['username'], $_SESSION['password']))
                {

                    header("location:index.php?action=patientlogin");
                }
            }
            else
            {

                header("location:index.php");
            }
        }

        if($this->userAccess($str))
        {
            // Code To Call Personalized GUI
            $this->call_patient_gui();
            //$this->call_gui;
            $str = $str . "()";
            eval("\$this->$str;");
        }
        else
        {
            $this->output = $this->config['error_message'];
        }

        $this->display();
        /*
          $str = $str."()";
          eval("\$this->$str;");
          $this->display();
         */
    }

    /**
     * Renders the patient videos page
     */
    function patientaccount()
    {
        $replace['currentDate']= date("l, F d, Y");
        $replace['currentday'] = $this->getPaitentCurrentDay($this->userInfo('user_id'));
		
		$replace['profile_picture'] = 'assets/img/avatar.jpg';
		$pimage = $this->userInfo('profile_picture');
		if(isset($pimage) && !empty($pimage)){
		$replace['profile_picture'] = 'asset/images/profilepictures/'.$this->userInfo('user_id').'/'.$this->userInfo('profile_picture');
		}
		
		
		/*
		*  Insert Data for  page_visits_report
		*/
			$ip='';
			if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
			} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
			$ip = $_SERVER['REMOTE_ADDR'];
			}
		
		$user_id=$this->userInfo('user_id');
		$clinic_id = $this->get_clinic_info($user_id);
		  
		$report_query ="INSERT INTO page_visits_report(page,ip,user_id,clinic_id,created) VALUES('myaccount','".$ip."','".$user_id."','".$clinic_id."',now())";
	
		$result1 = @mysql_query($report_query);
		
		/*
		*  End 
		*/
		
		
		
		
		
		
		 $replace['name_first'] = $this->userInfo('name_first');
		 $replace['name_last'] = $this->userInfo('name_last');
		 $replace['username'] = $this->userInfo('username');
		 $replace['password'] = $this->userInfo('password');
		 
		 
		 $replace['theme'] ="default";
		 $sql="SELECT * FROM patient_application_preferences WHERE user_id='".$this->userInfo('user_id')."'";
		 $rs = $this->execute_query($sql);
		$num_rows = $this->num_rows($rs);
		
		if($num_rows > 0){
		$row = $this->populate_array($rs);
		$replace['theme'] = $row['theme'];
		
		}
		 
        
        $replace['meta_head'] = $this->build_template($this->get_template("meta_head"),$replace);
        $replace['header'] = $this->build_template($this->get_template("patient_dashboard_header"),$replace);
        $replace['footer'] = $this->build_template($this->get_template("patient_dashboard_footer"));

        $this->output = $this->build_template($this->get_template("patient_account"),$replace);
    }
    
    function profileaccount()
    {
        
		
		$user_id = $this->userInfo('user_id');
		
		$data1 =$this->jsondecode($_REQUEST['data'], true);
		
		$first_name = $data1['firstName'];
		$last_name = $data1['lastName'];
		
		
		 $updateArr = array(
                        'name_first'=>$first_name,
						'name_last'=>$last_name
                    );

        $where = " user_id = ".$user_id;
				  

        $result = $this->update('user',$updateArr,$where);
		
		if($result){
         echo $this->jsonencode(array('status'=>1,'message'=>'Changed')); 
		 }else{
		 
		 echo $this->jsonencode(array('status'=>0,'message'=>'Unable to save.')); 
		 }
		 
		
    }
	
	
	function profilechangepass()
	{
		
		
		$userInfo = $this->userInfo();
		$userId = $userInfo['user_id'];
		
		$data1 =$this->jsondecode($_REQUEST['data'], true);
		
		 $password = $data1['password'];
		 $newPassword = $data1['newPassword'];
		 $confirmNewPassword = $data1['confirmNewPassword'];
		  $error = false;
		  
		  
		  
		  if($error == false && strcmp($password,$userInfo['password']) != 0)
			{
				$errorMsg = "Incorrect old password";
				$error = true;
			}
		  
		  if($error == false && strcmp($newPassword,$confirmNewPassword) != 0)
			{
				$errorMsg = "Please make sure that new password matches with confirm password";
				$error = true;
			}
	 
	
			if ($error == false)
                {
                    //update and show success msg page
                    /*$updateArr = array(
                        'password'=>$newPassword
                    );

                    $where = " user_id = ".$userId;

                    $result = $this->update('user',$updateArr,$where);

                    $errorMsg = "Your password has been changed successfully.";
                    
                    $_SESSION['password'] = $newPassword;*/
                    $key=  $this->random_string(32);
                   $data=array();
                    $insertArr = array(
                        'userid'=>$userId,
                        'password'=>$newPassword,
                        'passwordkeys'=>$key,
                        'status'=>1,
                        'clinicid'=>$this->get_clinic_info($userId)
                    );  
                    $sqlcheck="select * from telespine_change_password where userid='{$userId}' and status='1' and password='{$newPassword}' and TIMESTAMPDIFF(SECOND,`date`,now())<5";
                    $querycheck=  $this->execute_query($sqlcheck);
                    if($this->num_rows($querycheck)<=0)
                    { 
                            $this->insert('telespine_change_password', $insertArr);
                            $id=  $this->insert_id();
                            //send email to user
                            $to = $userInfo['name_first']. " " .$userInfo['name_last'].' <'.$userInfo['username'].'>';
                            $clinicName = html_entity_decode($this->get_clinic_info($userId, 'clinic_name'), ENT_QUOTES, "UTF-8");
                           $subject = "Telespine Password Change Confirmation";
                           $data['images_url'] = $this->config['images_url'];
                           $data['business_url']=$this->config['business_telespine']; 
                           $data['change_pass_link']=  $this->config['telespine_login'].'/index.php?action=changepassword&userid='.$userId.'&key='.$key;
                           //$data['fullname']=$this->decrypt_data($resultarr[0]['name_first']) . " " . $this->decrypt_data($resultarr[0]['name_last']);
                           //$data['loginurl']=$this->config['telespine_login']; 
                           $message = $this->build_template($this->get_template($this->action, "change_password"), $data);

                       // To send HTML mail, the Content-type header must be set
                           $headers  = 'MIME-Version: 1.0' . "\n";
                           $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";

                       // Additional headers
                           $headers .= "From:Telespine Support <support@telespine.com>\n";

                           $returnpath = '-fsupport@telespine';

                       // $this->printR($to, $subject, $headers);
                       // Mail it
                       if(mail($to, $subject, $message, $headers, $returnpath)){
                           $errorMsg = "Please check your email to confirm your new password."; 
                            
                       }
                           
                    }
                    //$showForm = 0;
                }
		
		if($error == false){
		echo $this->jsonencode(array('status'=>1,'message'=>$errorMsg));
		}else{
		
		echo $this->jsonencode(array('status'=>0,'message'=>$errorMsg));
		}
	}
	
        function random_string($length) {
        $key = '';
        $keys = array_merge(range(1, 9), range('a', 'z'),range('A', 'Z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
        }
	function profilechangeemail()
	{
		$user_id = $this->userInfo('user_id');
		
		$data1 =$this->jsondecode($_REQUEST['data'], true);
		
		 $email = $data1['email'];
		 $emailConfirm = $data1['emailConfirm'];
		
		$exits=0;
		
			/*
			Check email address for duplicacy
			*/
			
			$query = "select user_id from user where username='".$email."' and status != '3'";
			$rs = $this->execute_query($query);
			$num_rows = $this->num_rows($rs);
			$row = $this->populate_array($rs);
			
			
			if($num_rows == 1){
			
			$patient_id = $this->value('patient_id');

			// If userId not mataches with the current patient, which one edited
			if($row['user_id'] != $user_id){
			$error .= "Email address already Exist";
			$exits = 1;
			}
			}
			if($num_rows > 1){
			$error .= "Email address already Exist";
			$exits = 1;
			}
			/* End of checking for duplicacy */
		
		
		if($exits == 0){
		
		
		 $updateArr = array(
                        'username'=>$email
						
                    );

        $where = " user_id = ".$user_id;
				  

        $result = $this->update('user',$updateArr,$where);
		
		echo $this->jsonencode(array('status'=>1,'message'=>'Email Changed'));
		
	
		}else{
		
		echo $this->jsonencode(array('status'=>0,'message'=>$error));
		}
	
	}
	
	
	function profileapplicationpreferences()
	{
	
		$user_id = $this->userInfo('user_id');
		$data1 =$this->jsondecode($_REQUEST['data'], true);
		
		$theme = $data1['theme'];
		
		if (!empty($_FILES)) {
     
		  $tempFile = $_FILES['file']['tmp_name'];
		  $_FILES['file']['name'];
		
			$filecheck = $_FILES['file']['name'];
			 $ext = strtolower(substr($filecheck, strrpos($filecheck, '.') + 1));
			

			if (!(($ext == "jpg" || $ext == "gif" || $ext == "png" || $ext == "bmp") )){

				echo $this->jsonencode(array('status'=>0,'message'=>'Upload image file '));

				die;
    
				}else{
				
				$targetPath = $this->config['application_path'].'asset/images/profilepictures/'.$user_id.'/';  
				
				
                                if(!is_dir($targetPath)){
                    mkdir($targetPath);
                    chmod($targetPath,0777);
                }   
	 
	 
	 
	 
	 
				$targetFile =  $targetPath. $_FILES['file']['name'];  
				move_uploaded_file($tempFile,$targetFile); //6

				 $updateArr = array(
                        'profile_picture'=> $_FILES['file']['name']
						
                    );

        $where = " user_id = ".$user_id;
				  

        $result = $this->update('user',$updateArr,$where);
		
		if($result){
		echo $this->jsonencode(array('status'=>1,'message'=>'Updated'));
		die;
		}else{
		echo $this->jsonencode(array('status'=>0,'message'=>'Upload image file '));
		die;
		}
				
				
				}
		
		
		
		}

		
		$query = "select * from patient_application_preferences where user_id='".$user_id."' ";
		$rs = $this->execute_query($query);
		$num_rows = $this->num_rows($rs);
		
		if($num_rows == 0){
		
		if(isset($theme) && !empty($theme)){
		$sql = "INSERT INTO patient_application_preferences(user_id,theme) VALUES('".$user_id."','".$theme."')";
		}
		}else{
		if(isset($theme) && !empty($theme)){
		$sql = "UPDATE patient_application_preferences SET theme='".$theme."' WHERE user_id='".$user_id."'";
		}
		}
		
		$rs = $this->execute_query($sql);
		
		
		
		echo $this->jsonencode(array('status'=>1,'message'=>'Updated'));
	
	}
	
	
	

    /**
     * This function gets the template path from xml file.
     *
     * @param string $template - pass template file name as defined in xml file for that template file.
     * @return string - template file
     * @access private
     */
    function get_template($template)
    {
        $login_arr = $this->action_parser($this->action, 'template');
        $pos = array_search($template, $login_arr['template']['name']);
        return $login_arr['template']['path'][$pos];
    }

    /**
     * This function sends the output to browser.
     *
     * @access public
     */
    function display()
    {
        view::$output = $this->output;
    }

    /**
     * This function to assign persoanlized GUI for Patient based on Account Type into Session Variable
     * @access public
     * @return void
     */
    public function call_patient_gui()
    {
        //Check to load the Session Once
        //print_r($_SESSION['patientLabel']);
        if(count($_SESSION['patientLabel']) > 0)
        {
            
        }
        else
        {
            $userInfoValue = $this->userInfo();
            $persoanlizedPatientGUI = new lib_gui($userInfoValue);
            // Label v/s Provider Types
            $AccountTypeLabelsArray = $persoanlizedPatientGUI->getLabelValueClinic();
            // print_r($AccountTypeLabelsArray);
            foreach($AccountTypeLabelsArray as $key => $value)
            {
                $_SESSION['patientLabel'][$key] = $value;
            }
            $clinicfeaturelist = $persoanlizedPatientGUI->getFeatureClinicType();
            foreach($clinicfeaturelist as $key => $value)
            {
                $_SESSION['clinicfeature'][$key] = $value;
            }
        }
        //print_r( $_SESSION);
    }
   
}

$obj = new patientaccount();
?>
