<?php	
/**
 *
 * Copyright (c) 2008 Tx Xchange
 *
 * This class includes the functionality like view and edit you profile.
 * 
 * // necessary classes 
 * require_once("module/application.class.php");
 * require_once("include/netsuite/netsuite_functions.php");
 * 
 * // file upload class
 * require_once("include/fileupload/class.upload.php");
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
require_once("include/paging/my_pagina_class.php");	
require_once("include/fileupload/class.upload.php");
require_once("module/application.class.php");
require_once("include/validation/_includes/classes/validation/ValidationSet.php");
require_once("include/validation/_includes/classes/validation/ValidationError.php");	

// class declaration
class myAccount extends application{

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
                $str = "therapist"; //default if no action is specified
            }
            $this->action = $str;
            if($this->get_checkLogin($this->action) == "true" )
            {
                if( isset($_SESSION['username']) && isset($_SESSION['password']) )
                {
                    if(!$this->chk_login($_SESSION['username'],$_SESSION['password']))
                    {
                        header("location:index.php");
                    }
                }
                else
                {
                        header("location:index.php");
                }
            }

            //$str = $str."()";
            //eval("\$this->$str;"); 

            if($this->userAccess($str))
            {
                $str = $str."()";
                eval("\$this->$str;"); 
            }
            else
            {
                $this->output = $this->config['error_message'];
            }

            $this->display();
        }


    /**
     * Display your account details.
     * 
     * @access public
     */
    function viewMyAccount()
    {
        $replace = array();

        $userInfo = $this->userInfo();
        // Encrypt data
        $encrypt_field = array('name_title','name_first','name_last','password','address', 'address2', 'country','city', 'state', 'zip', 'phone1', 'phone2','fax');
        //$userInfo = $this->decrypt_field($userInfo, $encrypt_field);
        if (!$userInfo)
        {
            header("location:index.php");
        }
        else 
        {	
            $userId = $userInfo['user_id'];
            $replace['user_id'] = $userId;

            $userType = ($userInfo['usertype_id'] == 2) ? "Therapist" : "";					

            // format subscriber details 

            if(!empty($userInfo['name_title']))
            {
                $ret .= $userInfo['name_title'] . ' ';
            }

            $ret .= '<b style="font-size:14px;"><font>'.$userInfo['name_first'] . ' ' .$userInfo['name_last'].'</font></b>';

            if(!empty($userInfo['name_suffix']))
            {
                $ret .= ', ' . $userInfo['name_suffix'];
            }

            $ret .= '<br /><br />';	

            if(!empty($userInfo['address'])) $ret .= $userInfo['address'] . '<br><br />';
            if(!empty($userInfo['address2'])) $ret .= $userInfo['address2'] . '<br><br />';

            if (!empty($userInfo['city']) && !empty($userInfo['state']) && !empty($userInfo['zip']))
            {
                $key   =   $userInfo['state'];

                if($userInfo['country']=='US')
                {
                    $state=$this->config['state'][$key];
                }
                else
                {
                    $state=$this->config['canada_state'][$key];
                }

                $ret .= $userInfo['city'] . ', '. $state . ', ' .$userInfo['zip'].'<br><br />';									

            }
            else if (!empty($userInfo['city']) && !empty($userInfo['state']))
            {
                $key   =   $userInfo['state'];

                if($userInfo['country']=='US')
                {
                    $state=$this->config['state'][$key];
                }
                else
                {
                    $state=$this->config['canada_state'][$key];
                }
                $ret .= $userInfo['city'] .' ' .$state.'<br><br />';
            }
            else if (!empty($userInfo['city']) && !empty($userInfo['zip']))
            {
                $ret .= $userInfo['city'] .' ' .$userInfo['zip'].'<br><br />';
            }
            else if (!empty($userInfo['state']) && !empty($userInfo['zip'])) 
            {
                $key   =   $userInfo['state'];

                if($userInfo['country']=='US')
                {
                    $state=$this->config['state'][$key];
                }
                else
                {
                    $state=$this->config['canada_state'][$key];
                }
                $ret .= $state .' ' .$userInfo['zip'].'<br><br />';	
            }
            else if (!empty($userInfo['city']))
            {
                $ret .= $userInfo['city'].'<br><br />';
            }
            else if (!empty($userInfo['state']))
            {
                $key   =   $userInfo['state'];

                if($userInfo['country']=='US')
                {
                    $state=$this->config['state'][$key];
                }
                else
                {
                    $state=$this->config['canada_state'][$key];
                }
                $ret .= $state.'<br><br />';
            }
            else if (!empty($userInfo['zip']))
            {
                $ret .= $userInfo['zip'].'<br><br />';
            }
            if (!empty($userInfo['country']))
            {
                $key=$userInfo['country'];
                $ret .= $this->config['country'][$key].'<br><br />';
            }	

            //$ret .= '<br />';
            if(!empty($userInfo['username'])) $ret .= '<font><a href="mailto:' . $userInfo['username'] . '" style="font-size:11px;">' . $userInfo['username'] . '</a></font><br><br />';
            if(!empty($userInfo['phone1'])) $ret .= 'Phone 1: ' . $userInfo['phone1'] . '<br><br />';
//            if(!empty($userInfo['phone2'])) $ret .= 'Phone 2: ' .$userInfo['phone2'] . '<br><br />';
            $ret .= (!empty($userInfo['timezone'])) ? 'Time Zone: ' . $userInfo['timezone'] . '<br><br />' : 'Time Zone: ' . $this->config['timezone']['frontend']['region'] . '<br><br />';
            if(!empty($userInfo['fax'])) $ret .= 'Fax: ' .$userInfo['fax'] . '<br><br />';

            $replace['therapistInfo'] = $ret;

        }	

        $replace['header'] = $this->build_template($this->get_template("header"));
        $replace['footer'] = $this->build_template($this->get_template("footer"));	
        //$replace['sidebar'] = $this->build_template($this->get_template("sidebar"));					
        $replace['sidebar'] = $this->sidebar();

        $replace['body'] = $this->build_template($this->get_template("myAccountView"),$replace);
        $replace['browser_title'] = "Tx Xchange: My Account";

        $dir = "asset/images/profilepictures/".$userId;

        $contents = $this->ReadFolderDirectory($dir);
           //print_r($contents);
        if($contents[1]!='')
        {                         
            $replace['propic'] = "$dir/$contents[1]";
        }
        else
        {
            $replace['propic'] = 'images/no-image.gif';    
        }

        $replace['get_satisfaction'] = $this->get_satisfaction();

        $this->output = $this->build_template($this->get_template("main"),$replace);	
    }

        /**
         * Edit your account details.
         *
         * @access public
         */
        function editMyAccount()
        {
            $id = (int) $this->value('id');	

            $replace = array();

            $userInfo = $this->userInfo();			
            $userId = $userInfo['user_id'];
            $userType = ($userInfo['usertype_id'] == 2) ? "Therapist" : "";	

            $fileDest = "asset/images/profilepictures/".$userId;  


            if(!file_exists($fileDest))
            {
                mkdir($fileDest);
                chmod($fileDest, 0777);
            }

            if (!$userInfo)
            {
                header("location:index.php");
            }
            else if ($userType == "Therapist" && $userId == $id ) 			
            {
                include_once("template/myAccount/myAccountArray.php");

                $this->formArray = $formArray;			

                //Also have the questions from question table
                $questions = array(""=>"Choose...");

                $query = "SELECT * FROM questions where question_id != 3";
                $result = $this->execute_query($query);

                if($this->num_rows($result)!= 0)
                {
                    while($row = $this->fetch_array($result))
                    {
                        $questions[$row['question_id']] = $row['question'];
                    }
                }

                //check if form has been submitted or not
                if (isset($_POST['submitted_edit']) && $_POST['submitted_edit'] == 'Save Changes')
                {
                    //form submitted check for validation
                    $this->validateForm("Edit", $id);
                    if($this->error == "")
                    {
                        //Form validated, no errors
                        $updateArr = array(
                            'username'      => $this->value('username'),
                            'name_first'    => $this->value('name_first'),
                            'name_last'     => $this->value('name_last'),
                            'address'       => $this->value('address'),
                            'address2'      => $this->value('address2'),
                            'city'          => $this->value('city'),
                            'state'         => $this->value('state'),
                            'country'       => $this->value('clinic_country'),
                            'zip'           => $this->value('zip'),
                            'phone1'        => $this->value('phone1'),
                            'phone2'        => $this->value('phone2'),
                            'fax'           => $this->value('fax'),
                            'modified'      => date('Y-m-d H:i:s', time()),
                            'timezone'      => $this->value('timezone')
                        );
                        
                        // Encrypt data
                        //$encrypt_field = array('address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2','fax');
                        //$updateArr = $this->encrypt_field($updateArr, $encrypt_field);

                        $where = " user_id = '" . $id . "'";
                        $result = $this->update('user', $updateArr, $where);

                        //Check if password need to be updated or not
                        if($this->value('new_password') != '')
                        {
                            //update password too
                            /*
                            $query = "UPDATE user SET
                            password = '{$this->value('new_password')}'
                            WHERE user_id = ".$id;
                            $result = $this->execute_query($query);
                             */

                            $updateArr = array(
                                'password'=>$this->value('new_password')
                            );

                            $where = " user_id = " . $id;
                            $result = $this->update('user', $updateArr, $where);
                        }

                        //Also update the record in answer table
                        /*
                        $query = "UPDATE answers SET
                        question_id ={$this->value('question_id')},
                        answer ='{$this->value('answer')}' WHERE user_id = ".$id;
                        $result = $this->execute_query($query);*/

                        $updateArr = array(
                            'question_id' => $this->value('question_id'),
                            'answer' => $this->value('answer')
                        );

                        $where = " user_id = ".$id;

                        $result = $this->update('answers', $updateArr, $where);

                        // redirect
                        $randam=rand(5, 15);
                        header("location:index.php?action=viewMyAccount&rand=$randam");
                    }
                    else
                    {
                        $replace = $this->fillForm($this->formArray, $_POST);
                        $replace['error'] = $this->error;	

                        //Also set the question
                        $selectedQuestion = ($this->value('question_id') == "")? "": $this->value('question_id');

                        //Also the state
                        $selectedState = ($this->value('state') == "")? "": $this->value('state');
                        $countryArray = $this->config['country'];
                        $replace['country']=implode("','",$countryArray); 
                        //print_r($row);
                        $replace['patient_country_options'] = $this->build_select_option($countryArray, $row['country']);
                        
                        $selectedtimezone = ($this->value('timezone') == "") ? $this->config['timezone']['frontend']['region'] : $this->value('timezone');

                        if($this->value('clinic_country')=='US')
                        {
                            $stateArray = array("" => "Choose State...");
                            $stateArray = array_merge($stateArray,$this->config['state']);
                            $replace['stateOptions'] =  $this->build_select_option($stateArray,$selectedState);     
                        }

                        if($this->value('clinic_country')=='CAN')
                        {
                            $stateArray = array("" => "Choose Province...");
                            $stateArray = array_merge($stateArray,$this->config['canada_state']);
                            $replace['stateOptions'] =  $this->build_select_option($stateArray, $selectedState);
                        }
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
                        // Encrypt data
                        $encrypt_field = array('name_title','name_first','name_last','password','address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2','fax');
                        $row = $this->decrypt_field($row, $encrypt_field);
                    }

                    //also get the question answer
                    $query = "SELECT * FROM answers WHERE user_id = ".$id;

                    $result = $this->execute_query($query);

                    $rowAnswer = null;

                    if ($this->num_rows($result) != 0)
                    {
                        $rowAnswer = $this->fetch_array($result);	
                    }

                    $selectedQuestion = $rowAnswer['question_id'];
                    $selectedState = $row['state'];
                    $selectedUserType = $row['usertype_id'];
                    $selectedStatus = $row['status'];
                    $selectedtimezone = ($row['timezone'] == NULL) ? $this->config['timezone']['frontend']['region'] : $row['timezone'];

                    $row['answer'] = ($rowAnswer == null)? "":$rowAnswer['answer'];

                    $replace = $this->fillForm($this->formArray,$row);

                }

                $replace['id'] = $id;	


                $countryArray = $this->config['country'];
                
                $replace['country']=implode("','",$countryArray);

                $replace['patient_country_options'] = $this->build_select_option($countryArray, $row['country']);


                if($row['country']=='US')
                {
                    $stateArray = array("" => "Choose State...");

                    $stateArray = array_merge($stateArray,$this->config['state']);

                    $replace['stateOptions'] = 	$this->build_select_option($stateArray,$selectedState);		
                }
                if($row['country']=='CAN')
                {
                    $stateArray = array("" => "Choose Province...");

                    $stateArray = array_merge($stateArray,$this->config['canada_state']);

                    $replace['stateOptions'] = 	$this->build_select_option($stateArray,$selectedState);		
                }



                $replace['questionOptions'] = $this->build_select_option($questions,$selectedQuestion);

                $replace['timezoneOptions'] = $this->build_select_option($this->getAllTimezones(), $selectedtimezone);

                $replace['new_password'] = $this->value('new_password');
                $replace['new_password2'] = $this->value('new_password2');

                if( !isset($replace['new_password']) || empty($replace['new_password']) )
                {
                    $replace['new_password'] = $this->userInfo('password',$id);
                }
                if( !isset($replace['new_password2']) || empty($replace['new_password2']) )
                {
                    $replace['new_password2'] = $this->userInfo('password',$id);
                }


                $dir = "asset/images/profilepictures/".$userId;

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
                $replace['user_id'] = $userId;  		

                $replace['header'] = $this->build_template($this->get_template("header"));
                $replace['footer'] = $this->build_template($this->get_template("footer"));							
                $replace['sidebar'] = $this->sidebar();					

                $replace['body'] = $this->build_template($this->get_template("myAccountEdit"),$replace);
                $replace['browser_title'] = "Tx Xchange: Edit Account";


                $replace['get_satisfaction'] = $this->get_satisfaction();
                $this->output = $this->build_template($this->get_template("main"),$replace);
            }
            else 
            {
                header("location:index.php");
            }
        }

        /**
         * validating form fields.
         *
         * @param string $formType
         * @param boolean $uniqueId
         */
        function validateForm($formType, $uniqueId = false)
        {

                // creating validation object				
                $objValidationSet = new ValidationSet();					

                // validating username (email address)
                $objValidationSet->addValidator(new  StringMinLengthValidator('username', 1, "Login cannot be empty",$this->value('username')));					
                $objValidationSet->addValidator(new EmailValidator('username',"Invalid Login/email address",$this->value('username')));

                if ($formType == "Add") 
                {

                        $objValidationSet->addValidator(new  StringMinLengthValidator('new_password', 1, "Password cannot be empty",$this->value('new_password')));					

                }	

                // match password and confirm password
                $arrFieldNames = array("new_password","new_password2");
                $arrFieldValues = array($_POST["new_password"],$_POST["new_password2"]);
                $objValidationSet->addValidator(new  IdenticalValuesValidator($arrFieldNames, "New Password Confirm Password mismatch",$arrFieldValues));					

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

                // validating first name
                $objValidationSet->addValidator(new  StringMinLengthValidator('name_first', 1, "First Name cannot be empty",$this->value('name_first')));		
                $objValidationSet->addValidator(new AlphanumericOnlyValidator('name_first',null,"Please enter valid characters in first name",$this->value('name_first')));

                $objValidationSet->addValidator(new  StringMinLengthValidator('name_last', 1, "Last Name cannot be empty",$this->value('name_last')));		
                $objValidationSet->addValidator(new AlphanumericOnlyValidator('name_last',null,"Please enter valid characters in last name",$this->value('name_last')));

                //$arrSpecialChars = array(" ","/","-",",","#",".","(",")",";","+","%");

                $objValidationSet->addValidator(new AlphanumericOnlyValidator('address',null,"Please enter valid characters in address",$this->value('address')));
                $objValidationSet->addValidator(new AlphanumericOnlyValidator('address2',null,"Please enter valid characters in address2",$this->value('address2')));
                $objValidationSet->addValidator(new AlphanumericOnlyValidator('city',null,"Please enter valid characters in city",$this->value('city')));

                /*$objValidationSet->addValidator(new  ZipValidator('zip',"Invalid zip code, Please enter valid 5 digit code",$this->value('zip')));		
                $objValidationSet->addValidator(new PhoneValidator('phone1',true, false,"Invalid phone number",$this->value('phone1')));
                $objValidationSet->addValidator(new PhoneValidator('phone2',true, false,"Invalid phone number",$this->value('phone2')));
                $objValidationSet->addValidator(new PhoneValidator('fax',true, false,"Invalid fax number",$this->value('fax')));		
                */
            /*if ($this->value('zip') != '')
    {
        //$objValidationSet->addValidator(new  AlphanumericOnlyValidator('zip', array(' '), "Zip code should be of alphanumeric characters only",$this->value('zip')));                 
        //$objValidationSet->addValidator(new  StringMinMaxLengthValidator('zip', 5,7, "Zip code should be 5 to 7 alphanumeric characters only",$this->value('zip')));
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
                                $objValidationErr = new ValidationError('username',"Login / Email address : exists in the system. Please choose another.");
                                $objValidationSet->addValidationError($objValidationErr);
                        }


                        //$objValidationSet->addValidator(new UniqueValueInColumnValidator('username','user','username',null,null,'Login / Email address : exists in the system. Please choose another.',$_POST['username']));
                }
                else
                {
                        $queryEmail = "SELECT user_id FROM user WHERE username = '".$_POST['username']."' AND status <> 3 AND user_id <> ".$uniqueId;
                        $result = $this->execute_query($queryEmail);

                        //if record found that means email not unique else it is unique
                        if ($this->num_rows($result) != 0)
                        {
                                $objValidationErr = new ValidationError('username',"Login / Email address : exists in the system. Please choose another.");
                                $objValidationSet->addValidationError($objValidationErr);
                        }

                        //$objValidationSet->addValidator(new UniqueValueInColumnValidator('username','user','username','user_id',$uniqueId,'Login / Email address : exists in the system. Please choose another.',$_POST['username']));
                }	

                /*

                if ($uniqueId === false)
                {				
                        $objValidationSet->addValidator(new UniqueValueInColumnValidator('username','user','username',null,null,'Login / Email address : exists in the system. Please choose another.',$_POST['username']));
                }
                else
                {

                        $objValidationSet->addValidator(new UniqueValueInColumnValidator('username','user','username','user_id',$uniqueId,'Login / Email address : exists in the system. Please choose another.',$_POST['username']));
                }	

                */
                $objValidationSet->validate();		

                /*
                if ($this->value('usertype_id') == '')
                {
                        $objValidationErr = new ValidationError('usertype_id',"Please select a user type");
                        $objValidationSet->addValidationError($objValidationErr);
                }
                else 
                {
                        $objValidationErr = new ValidationError('usertype_id',"");
                        $objValidationSet->addValidationError($objValidationErr);
                }
                */

                if ($objValidationSet->hasErrors())
                {
                        if($formType == "Edit" )
                        {
                                if ($_POST['new_password'] == '')
                                {
                                        $arrayFields = array("username","question_id","answer","name_first","name_last","address","address2","city","zip");						
                                }
                                else 
                                {
                                        $arrayFields = array("username","new_password","question_id","answer","name_first","name_last","address","address2","city","zip");												
                                }

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



 /*
 Function Name : ReadFolderDirectory.
 Desc : used to Read temporary Folder .
 Param : VARCHAR $dir
 Return : array $listDir
 Access : public
 Author : Abhishek Sharma
 Created Date : 10 May 2011
 Organization : Hytech Professionals
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
         * populate side panel in page.
         *
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
* Patient Contact us
*/
function provider_contact_us(){
    $userInfo = $this->userInfo();
    $clinic_channel=$this->getchannel($this->clinicInfo('clinic_id'));
    if($clinic_channel==1)
        $this->output = $this->build_template($this->get_template("contact_us"));
        else
        $this->output = $this->build_template($this->get_template("contact_us_wx"));
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

$obj = new myAccount();
?>