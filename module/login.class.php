<?php
/**
 *
 * Copyright (c) 2008 Tx Xchange.
 *
 * This class implements login functionality and forgot password functionality.
 *
 */


require_once("include/validation/_includes/classes/validation/ValidationSet.php");
require_once("include/validation/_includes/classes/validation/ValidationError.php");
require_once("module/application.class.php");


// class declaration
class login extends application
{

    /**
     * Action variable is used to hold the action param value.
     *
     * @var String
     * @access Private
     */
    private $action;

    /**
     * This can used in future enhancement.
     *
     * @var String
     * @access private
     */
    private $field_array;

    /**
     * This can used in future enhancement.
     *
     * @var String
     * @access Private
     */
    private $error;

    /**
     * Processed out is assigned to this member.
     *
     * @var String
     * @access Private
     */
    private $output;

    private $template_array;

    /**
     * In this method following activities are performed
     * 1) Checking action parameter, weather its holding any value or not.
     * 	  If it is not holding any value we are assigning default value in it.
     * 2) Check user is logged in or not.
     * 3) Check the logged in user have privileage or not to access this class.
     * 4) Show response by using display() method.
     * @param none
     * @return none
     * @access public
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
            $str = "login"; //default if no action is specified
        }

        $this->action = $str;

        if($this->get_checkLogin($this->action) == "true" )
        {
            if(isset($_SESSION['username']) && isset($_SESSION['password']))
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

        $str = $str."()";
        eval("\$this->$str;");

        $this->display();
    }

    /**
     * This function shows login page.
     *
     * @return login
     */
    function login()
    {
        $replace['error'] = "";
        if (isset($_SESSION['username']) && isset($_SESSION['password']))
        {
            if ($this->chk_login($_SESSION['username'],$_SESSION['password']))
            {
                $replace['error'] = "Someone has logged in as this user from a different computer or browser window. Only one person may login as a given user at a time. As a consequence, this session has been terminated.";
                $replace['error'] = $this->show_error($replace);
                session_destroy();
                
            }
        }
        /* user is misbehaving trying to login more then one browser*/
        
        $array=  explode('.', $_SERVER['HTTP_HOST']) ;
            if (in_array($this->config['domain'], $array)) {
                  header("Location:index.php?action=patientlogin");
                  die;
              }
        $replace['username'] = "";
        $replace['password'] = "";
        $replace['release_version'] = $this->config['release_version'];
        $replace['header'] = $this->build_template($this->get_template("header"));
        $replace['footer'] = $this->build_template($this->get_template("footer"));
        $replace['body'] = $this->build_template($this->get_template("login"),$replace);
        $replace['browser_title'] = "Tx Xchange: Login";

        $this->output = $this->build_template($this->get_template("main"),$replace);
    }

    /* Added function on Sept 05, 2007 AJ */
    function loginRecovery()
    {
            $this->error = "";
            $sendMail = false;
            $showForm1 = true;
            $showForm2 = false;
            $showForm3 = false;
            $showForm4 = false;
            //Also have the questions from question table

            //check if form has been submitted or not
            if (isset($_POST['submitted']) && $_POST['submitted'] == 'Retrieve Password')
            {
                    //form submitted check for valid credentials
                    $user_info = $this->validateFormRecovery1();
                    $arrValues = explode(',',$user_info);
                    //print_r($arrValues);
                    //echo $this->error;
                    //die;
    /*************Code modified by pawan khandelwal for TXM-15 bug in jira on 6 aug 2013 starts here******************/

                    if($this->error == "")
                    {

                            //Form validated, no errors
                            //go ahead send the password to the mail address if userType is patient else show form 2 ask for question and answers
        $questions = array();
                    $userid_query = "SELECT user_id FROM user WHERE username = '".$_POST['email_address']."'  and (status = 1 OR status = 2)";
        $result_userid = $this->execute_query($userid_query);
        $userid_array = $this->fetch_array($result_userid);
                    $query = "SELECT ques.*,ans.user_id FROM questions as ques JOIN answers as ans ON ques.question_id = ans.question_id  where ans.user_id = '".$userid_array['user_id']."' and  ques.question_id not in (3,7)";
                    $result = $this->execute_query($query);

                    if($this->num_rows($result)!= 0)
                    {
                            while($row = $this->fetch_array($result))
                            {
                                    $questions[$row['question_id']] = $row['question'];
                            }
                    }
        $privateKey = $this->config['private_key'];
        $queryEmail = "SELECT *,
                            AES_DECRYPT(UNHEX(name_title),'{$privateKey}') as name_title,
                            AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as name_first,
                            AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as name_last
                            FROM user WHERE username = '".$_POST['email_address']."' and agreement = '0' and (status = 1 OR status = 2)";
                            $result = $this->execute_query($queryEmail);
        $row = $this->fetch_array($result);
        if($this->num_rows($result) != 0 )
                    {
                            //Form validated, no errors
                            //go ahead send the password to the mail address
                            $user_id = null;

                            {
                                    //$row = $this->fetch_array($result);
                                    $row['password'] = $this->decrypt_data($row['password']);
                $userPass = $row['password'];
                                    $fullName = $row['name_first']. ' ' .$row['name_last'];
                                    $user_id = $row['user_id'];
                            }

                            //have the HTML content
                            $replace['username'] = $_POST['email_address'];
                            $replace['password'] = $userPass;
                            $replace['url'] = $this->config['url'];
                            $replace['images_url'] = $this->config['images_url'];
                            $clinic_channel=$this->getchannel($this->get_clinic_info($user_id,'clinic_id'));
                            if($clinic_channel==1){
                        $business_url=$this->config['business_tx'];
                        $support_email=$this->config['email_tx'];
                    }else{
                        $business_url=$this->config['business_wx'];
                        $support_email=$this->config['email_wx'];
                    }
                    $replace['business_url'] = $business_url;
                    $replace['support_email'] = $support_email;
            $clinic_type = $this->getUserClinicType($user_id);
            if( $clinic_channel == 1){
                    $message = $this->build_template($this->get_template("recoveryContent_plpto"),$replace);
            }
            else{
                $message = $this->build_template($this->get_template("recoveryContent_wx"),$replace);
            }
                            //$message = $this->build_template($this->get_template("recoveryContent"),$replace);
                            //$message = "User Name: ".$_POST['email_address']."<br> Password : ".$userPass;

                            $to = $fullName.'<'.$_POST['email_address'].'>';
            $clinicName=html_entity_decode($this->get_clinic_info($user_id,'clinic_name'), ENT_QUOTES, "UTF-8");
            $subject = "Information from ".$clinicName;

                            // To send HTML mail, the Content-type header must be set
                            $headers  = 'MIME-Version: 1.0' . "\n";
                            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";

                            // Additional headers
                            //$headers .= 'To: '.$fullName.'<'.$_POST['email_address'].'>'. "\n";
                            $headers .= "From:".$this->setmailheader($clinicName)." <do-not-reply@txxchange.com>\n";
                            //$headers .= 'Cc: example@example.com' . "\n";
                            //$headers .= 'Bcc: example@example.com' . "\n";

            $returnpath = '-fdo-not-reply@txxchange.com';
                            // Mail it
                            @mail($to, $subject, $message, $headers, $returnpath);

                            $sendMail = true;
                    }
        else{
                    if($arrValues[1] == 1){

                                    $query = "SELECT * FROM answers WHERE user_id = ".$arrValues[0];
                                    $result = $this->execute_query($query);

                                    if($this->num_rows($result) == 0)
                                    {
                                            $showForm1 = false;
                                            $showForm2 = false;
                                            $showForm3 = true;
                                            $showForm4 = false;
                                            $sendMail = false;
                                    }
                                    else
                                    {
                                            $showForm1 = false;
                                            $showForm2 = true;
                                            $showForm3 = false;
                                            $showForm4 = false;
                                            $sendMail = false;
                                    }
                            }elseif($arrValues[1]==2){
                            $query = "SELECT * FROM answers WHERE user_id = ".$arrValues[0];
                            $result = $this->execute_query($query);
                            if($this->num_rows($result) == 0)
                                    {
                                            $showForm1 = false;
                                            $showForm2 = false;
                                            $showForm3 = false;
                                            $showForm4 = true;
                                            $sendMail = false;

                                    }
                                    else
                                    {
                                            $showForm1 = false;
                                            $showForm2 = true;
                                            $showForm3 = false;
                                            $showForm4 = false;
                                            $sendMail = false;
                                    }

                                 }

                            else{
                                            $showForm1 = false;
                                            $showForm2 = true;
                                            $showForm3 = false;
                                            $showForm4 = false;
                                            $sendMail = false;
                            }
        }
        /*************Code modified by pawan khandelwal for TXM-15 bug in jira on 6 aug 2013 ends here******************/


                    }
                    else
                    {
                            $replace['email_address'] = $_POST['email_address'];
                            $replace['error'] = $this->error;
                            $showForm1 = true;
                            $sendMail = false;
                            $showForm2 = false;
                            $showForm3 = false;
                            $showForm4 = false;
                    }

            }
            else
            {
                    //first time edit form, so values from table
                    $replace['email_address'] = "";
                    $showForm1 = true;
                    $sendMail = false;
                    $showForm2 = false;
                    $showForm3 = false;
                    $showForm4 = false;
            }

            if ($showForm1 === true)
            {
                    $replace['header'] = $this->build_template($this->get_template("header"));
                    $replace['footer'] = $this->build_template($this->get_template("footer"));
                    $replace['body'] = $this->build_template($this->get_template("loginRecovery"),$replace);
                    $replace['browser_title'] = "Tx Xchange: Forgot Password";
                    $this->output = $this->build_template($this->get_template("main"),$replace);
            }

            if ($sendMail === true)
            {
                    $replace['header'] = $this->build_template($this->get_template("header"));
                    $replace['footer'] = $this->build_template($this->get_template("footer"));
                    $replace['body'] = $this->build_template($this->get_template("recoverySuccess"),$replace);
                    $replace['browser_title'] = "Tx Xchange: Forgot Password";
                    $this->output = $this->build_template($this->get_template("main"),$replace);
            }

            if ($showForm2 === true)
            {
                    $replace['questionOptions']	= $this->build_select_option($questions,$selectedQuestion);
                    $replace['answer'] = "";
                    $replace['email_address'] = $_POST['email_address'];
                    $replace['header'] = $this->build_template($this->get_template("header"));
                    $replace['footer'] = $this->build_template($this->get_template("footer"));
                    $replace['body'] = $this->build_template($this->get_template("loginRecovery2"),$replace);
                    $replace['browser_title'] = "Tx Xchange: Forgot Password";
                    $this->output = $this->build_template($this->get_template("main"),$replace);
            }
            if ($showForm3 === true)
            {
                    $questionsth = array(""=>"Choose...");
                    $queryth = "SELECT * FROM questions where question_id = 3";
                    $result = $this->execute_query($queryth);

                    if($this->num_rows($result)!= 0)
                    {
                            while($row = $this->fetch_array($result))
                            {
                                    $questionsth[$row['question_id']] = $row['question'];
                            }
                    }
                    $replace['questionOptions']	= $this->build_select_option($questionsth,$selectedQuestion);
                    $replace['therapistName'] = "";
                    $replace['email_address'] = $_POST['email_address'];
                    $replace['header'] = $this->build_template($this->get_template("header"));
                    $replace['footer'] = $this->build_template($this->get_template("footer"));
                    $replace['body'] = $this->build_template($this->get_template("loginRecovery3"),$replace);
                    $replace['browser_title'] = "Tx Xchange: Forgot Password";
                    $this->output = $this->build_template($this->get_template("main"),$replace);
            }
            if($showForm4 === true){
                    $questionsth = array(""=>"Choose...");
                     $queryth = "SELECT * FROM questions where question_id = 7";
                    $result = $this->execute_query($queryth);

                    if($this->num_rows($result)!= 0)
                    {
                            while($row = $this->fetch_array($result))
                            {
                                    $questionsth[$row['question_id']] = $row['question'];
                            }
                    }

                    $replace['questionOptions']	= $this->build_select_option($questionsth,$selectedQuestion);
                    $replace['businessname'] = "";
                    $replace['email_address'] = $_POST['email_address'];
                    $replace['header'] = $this->build_template($this->get_template("header"));
                    $replace['footer'] = $this->build_template($this->get_template("footer"));
                    $replace['body'] = $this->build_template($this->get_template("loginRecovery4"),$replace);
                    $replace['browser_title'] = "Tx Xchange: Forgot Password";
                    //print_r($replace);
                    $this->output = $this->build_template($this->get_template("main"),$replace);

            }
    }

    function loginRecovery2()
    {
            $this->error = "";

            //Also have the questions from question table
            $questions = array();
            $userid_query = "SELECT user_id FROM user WHERE username = '".$_POST['email_address']."'  and (status = 1 OR status = 2)";
$result_userid = $this->execute_query($userid_query);
$userid_array = $this->fetch_array($result_userid);
            $query = "SELECT ques.*,ans.user_id FROM questions as ques JOIN answers as ans ON ques.question_id = ans.question_id  where ans.user_id = '".$userid_array['user_id']."' and  ques.question_id not in (3,7)";
            $result = $this->execute_query($query);

            if($this->num_rows($result)!= 0)
            {
                    while($row = $this->fetch_array($result))
                    {
                            $questions[$row['question_id']] = $row['question'];
                    }
            }

            $sendMail = false;
            //check if form has been submitted or not

            if (isset($_POST['submit']) && $_POST['submit'] == 'Retrieve Password2')
            {
                    //form submitted check for valid credentials

                    $this->validateFormRecovery2();

                    if($this->error == "")
                    {
                            //Form validated, no errors
                            //go ahead send the password to the mail address
                            $privateKey = $this->config['private_key'];
                            $queryEmail = "SELECT *,
                        AES_DECRYPT(UNHEX(name_title),'{$privateKey}') as name_title,
                        AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as name_first,
                        AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as name_last
                        FROM user WHERE username = '".$_POST['email_address']."' and (status = 1 OR status = 2)";
                            $result = $this->execute_query($queryEmail);

                            $user_id = null;

                            {
                                    $row = $this->fetch_array($result);
                                    $row['password'] = $this->decrypt_data($row['password']);
            $userPass = $row['password'];
                                    $fullName = $row['name_first']. ' ' .$row['name_last'];
                                    $user_id = $row['user_id'];
                            }

                            //have the HTML content
                            $replace['username'] = $_POST['email_address'];
                            $replace['password'] = $userPass;
                            $replace['url'] = $this->config['url'];
                            $replace['images_url'] = $this->config['images_url'];
                            $clinic_channel=$this->getchannel($this->get_clinic_info($user_id,'clinic_id'));
                            if($clinic_channel==1){
                    $business_url=$this->config['business_tx'];
                    $support_email=$this->config['email_tx'];
                }else{
                    $business_url=$this->config['business_wx'];
                    $support_email=$this->config['email_wx'];
                }
                $replace['business_url'] = $business_url;
                $replace['support_email'] = $support_email;
            $clinic_type = $this->getUserClinicType($user_id);
            if( $clinic_channel == 1){
                    $message = $this->build_template($this->get_template("recoveryContent_plpto"),$replace);
            }
        else{
                $message = $this->build_template($this->get_template("recoveryContent_wx"),$replace);
            }
                            //$message = $this->build_template($this->get_template("recoveryContent"),$replace);
                            //$message = "User Name: ".$_POST['email_address']."<br> Password : ".$userPass;

                            $to = $fullName.'<'.$_POST['email_address'].'>';
        $clinicName=html_entity_decode($this->get_clinic_info($user_id,'clinic_name'), ENT_QUOTES, "UTF-8");
        $subject = "Information from ".$clinicName;

                            // To send HTML mail, the Content-type header must be set
                            $headers  = 'MIME-Version: 1.0' . "\n";
                            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";

                            // Additional headers
                            //$headers .= 'To: '.$fullName.'<'.$_POST['email_address'].'>'. "\n";
                            $headers .= "From:".$this->setmailheader($clinicName)." <do-not-reply@txxchange.com>\n";
                            //$headers .= 'Cc: example@example.com' . "\n";
                            //$headers .= 'Bcc: example@example.com' . "\n";

        $returnpath = '-fdo-not-reply@txxchange.com';
                            // Mail it
                            @mail($to, $subject, $message, $headers, $returnpath);

                            $sendMail = true;
                    }
                    else
                    {
                            $replace['email_address'] = $_POST['email_address'];
                            $selectedQuestion = $_POST['question_id'];
                            $replace['answer'] = $_POST['answer'];
                            $replace['error'] = $this->error;
                            //$replace['callout']='<div class="error"><a><span>&nbsp;&nbsp;Please remember answers are case-sensitive.</span></a></div>';
                            $sendMail = false;
                    }

            }
            else if (isset($_POST['submitted']) && $_POST['submitted'] == 'Retrieve Password')
            {
                    $replace['email_address'] = $_POST['email_address'];
                    $selectedQuestion = "";
                    $replace['answer'] = "";

            }
            else
            {
                    header("Location:index.php");
            }

            if ($sendMail === false)
            {
                    $replace['questionOptions']	= $this->build_select_option($questions,$selectedQuestion);
                    $replace['header'] = $this->build_template($this->get_template("header"));
                    $replace['footer'] = $this->build_template($this->get_template("footer"));
                    $replace['body'] = $this->build_template($this->get_template("loginRecovery2"),$replace);
                    $replace['browser_title'] = "Tx Xchange: Forgot Password";
                    $this->output = $this->build_template($this->get_template("main"),$replace);
            }
            else
            {
                    $replace['header'] = $this->build_template($this->get_template("header"));
                    $replace['footer'] = $this->build_template($this->get_template("footer"));
                    $replace['body'] = $this->build_template($this->get_template("recoverySuccess"),$replace);
                    $replace['browser_title'] = "Tx Xchange: Forgot Password";
                    $this->output = $this->build_template($this->get_template("main"),$replace);
            }
    }

    /**
     * This function is used for login information recovery.
     * This function is for patients login information recovery.
     *
     * @return integer
     */
    function validateFormRecovery1()
    {
        $usertypeId = null;

        $objValidationSet = new ValidationSet();

        $objValidationSet->addValidator(new  StringMinLengthValidator('email_address', 1, "Email cannot be empty",$this->value('email_address')));
        $objValidationSet->addValidator(new EmailValidator('email_address',"Invalid username",$this->value('email_address')));

        $objValidationSet->validate();

        if ($objValidationSet->hasErrors())
        {
            $arrayFields = array("email_address");

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
            //so invalid input is not there
            //now check if valid data from database
            $this->error = "";

            //check if its valid email
            //status=2 is used for so that discharge patients can also retrieve their password
            $queryEmail = "SELECT user_id,usertype_id FROM user WHERE (status = 1 OR status = 2) AND username = '".$_POST['email_address']."'";
            $result = $this->execute_query($queryEmail);

            $user_id = null;

            if ($this->num_rows($result) != 0)
            {
                //valid email get the user id
                $row = $this->fetch_array($result);
                $user_id = $row['user_id'];
                $usertypeId = $row['usertype_id'];
                $userValue = $user_id.",".$usertypeId;
            }

            if($user_id == null)
            {
                //error invalid email address or no user exist or inactive
                $this->error = "We're sorry we can't find that email address in our system. Please contact your Account Administrator or ".'<a href="mailto:support@txxchange.com" style="text-decoration:underline;" >'.'Tx Xchange Support'.'</a>';
            }
        }

        return $userValue;
    }

    /**
     * This function is used for login information recovery.
     * This function is for Therapist/Account login information recovery.
     *
     * @return integer
     */
    function validateFormRecovery2()
    {
        $objValidationSet = new ValidationSet();

        if ($this->value('question_id') == '')
        {
            $objValidationErr = new ValidationError('question_id',"Please select a secret question");
            $objValidationSet->addValidationError($objValidationErr);
        }

        $objValidationSet->addValidator(new  StringMinLengthValidator('answer', 1, "Answer cannot be empty",$this->value('answer')));

        $objValidationSet->validate();

        if ($objValidationSet->hasErrors())
        {
            $arrayFields = array("question_id","answer");

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
            //so invalid input is not there
            //now check if valid data from database

            $this->error = "";

            //status=2 is used for so that discharge patients can also retrieve their password
            $queryEmail = "SELECT user_id FROM user WHERE (status = 1 OR status = 2) AND username = '".$_POST['email_address']."'";
            $result = $this->execute_query($queryEmail);

            $row = $this->fetch_array($result);
            $user_id = $row['user_id'];

            //get the question id and answer id for this user from answer table
            $queryAns = "SELECT * FROM answers WHERE user_id = ".$user_id;
            $resultAns = $this->execute_query($queryAns);

            if ($this->num_rows($resultAns) != 0)
            {
                $row = $this->fetch_array($resultAns);

                //check if question and ans tally with the user input
                $questionId = $row['question_id'];
                $ans = $row['answer'];

                if (strtolower($ans) == strtolower($_POST['answer']) && $questionId == $_POST['question_id'])
                {	//valid question, answer input

                }
                else
                {
                    //error wrong question ans input
                    $this->error = "Please provide a different answer for this question.";
                }
            }
            else
            {
                $this->error = "We're sorry we can't find that email address in our system. Please contact your Account Administrator or ".'<a href="mailto:support@txxchange.com" style="text-decoration:underline;" >'.'Tx Xchange Support'.'</a>';

            }

        }

    }
                
    /**
     * This function is used for login information recovery.
     * This function ask Therapist name from Patient has never logged as well as not yet set their challenge question/answer.
     *
     * @return integer
     */
    function loginRecovery3()
    {
        $this->error = "";
        //Also have the questions from question table
        $questions = array(""=>"Choose...");

        $query = "SELECT * FROM questions where question_id = 3";
        $result = $this->execute_query($query);

        if($this->num_rows($result)!= 0)
        {
            while($row = $this->fetch_array($result))
            {
                    $questions[$row['question_id']] = $row['question'];
            }
        }
        
        $sendMail = false;
        //check if form has been submitted or not

        if (isset($_POST['submit']) && $_POST['submit'] == 'Retrieve Password3')
        {
            //form submitted check for valid credentials

            $this->validateFormRecovery3();

            if($this->error == "")
            {
                //Form validated, no errors
                //go ahead send the password to the mail address
                $privateKey = $this->config['private_key'];
                $queryEmail = "SELECT *,
                    AES_DECRYPT(UNHEX(name_title),'{$privateKey}') as name_title,
                    AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as name_first,
                    AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as name_last
                    FROM user WHERE username = '".$_POST['email_address']."' and (status = 1 OR status = 2)";
                $result = $this->execute_query($queryEmail);

                $user_id = null;
                {
                $row = $this->fetch_array($result);
                $row['password'] = $this->decrypt_data($row['password']);
                $userPass = $row['password'];
                $fullName = $row['name_first']. ' ' .$row['name_last'];
                $user_id = $row['user_id'];
                }

                //have the HTML content
                $replace['username'] = $_POST['email_address'];
                $replace['password'] = $userPass;
                $replace['url'] = $this->config['url'];
                $replace['images_url'] = $this->config['images_url'];

                $clinic_type = $this->getUserClinicType($user_id);

                if($clinic_channel==1)
                {
                    $business_url=$this->config['business_tx'];
                    $support_email=$this->config['email_tx'];
                }
                else
                {
                    $business_url=$this->config['business_wx'];
                    $support_email=$this->config['email_wx'];
                }
                
                $replace['business_url'] = $business_url;
                $replace['support_email'] = $support_email;
                $clinic_type = $this->getUserClinicType($user_id);
                
                if( $clinic_channel == 1)
                {
                    $message = $this->build_template($this->get_template("recoveryContent_plpto"),$replace);
                }
                else
                {
                    $message = $this->build_template($this->get_template("recoveryContent_wx"),$replace);
                }

                $to = $fullName.'<'.$_POST['email_address'].'>';
                $clinicName=html_entity_decode($this->get_clinic_info($user_id,'clinic_name'), ENT_QUOTES, "UTF-8");
                $subject = "Information from ".$clinicName;

                // To send HTML mail, the Content-type header must be set
                $headers  = 'MIME-Version: 1.0' . "\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";

                // Additional headers
                //$headers .= 'To: '.$fullName.'<'.$_POST['email_address'].'>'. "\n";
                $headers .= "From:".$this->setmailheader($clinicName)." <do-not-reply@txxchange.com>\n";
                //$headers .= 'Cc: example@example.com' . "\n";
                //$headers .= 'Bcc: example@example.com' . "\n";

                $returnpath = '-fdo-not-reply@txxchange.com';
                // Mail it
                @mail($to, $subject, $message, $headers, $returnpath);

                $sendMail = true;
            }
            else
            {
                $replace['email_address'] = $_POST['email_address'];
                $selectedQuestion = $_POST['question_id'];
                $replace['therapistName'] = $_POST['therapistName'];
                $replace['error'] = $this->error;
                $sendMail = false;
            }
        }
        else if (isset($_POST['submitted']) && $_POST['submitted'] == 'Retrieve Password')
        {
            $replace['email_address'] = $_POST['email_address'];
            $selectedQuestion = "";
            $replace['therapistName'] = "";
        }
        else
        {
            header("Location:index.php");
        }

        if ($sendMail === false)
        {
            $replace['questionOptions']	= $this->build_select_option($questions,$selectedQuestion);
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['footer'] = $this->build_template($this->get_template("footer"));
            $replace['body'] = $this->build_template($this->get_template("loginRecovery3"),$replace);
            $replace['browser_title'] = "Tx Xchange: Forgot Password";
            $this->output = $this->build_template($this->get_template("main"),$replace);
        }
        else
        {
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['footer'] = $this->build_template($this->get_template("footer"));
            $replace['body'] = $this->build_template($this->get_template("recoverySuccess"),$replace);
            $replace['browser_title'] = "Tx Xchange: Forgot Password";
            $this->output = $this->build_template($this->get_template("main"),$replace);
        }
    }

		/**
		 * This function is used for login information recovery.
		 * This function is for patients login information recovery.
		 *
		 * @return integer
		 */
		function validateFormRecovery3()
		{
			$objValidationSet = new ValidationSet();

			if ($this->value('question_id') == '')
			{
				$objValidationErr = new ValidationError('question_id',"Please select a secret question");
				$objValidationSet->addValidationError($objValidationErr);
			}


			$objValidationSet->addValidator(new  StringMinLengthValidator('therapistName', 1, "Please enter therapist's first name",$this->value('therapistName')));


			$objValidationSet->validate();

			if ($objValidationSet->hasErrors())
			{

				$arrayFields = array("question_id","therapistName");


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

				//so invalid input is not there
				//now check if valid data from database
				$user_info = $this->validateFormRecovery1();
				$arrValues = explode(',',$user_info);
				if($arrValues[1] == 1){
				$therapistName = null;
				$this->error = "";
				$private_key = db::$config['private_key'];

				 $queryEmail = "SELECT AES_DECRYPT(UNHEX(user.name_first),'{$private_key}') as first_name,
				 					   AES_DECRYPT(UNHEX(user.name_last),'{$private_key}') as name_last
				 					   FROM user
				 					   INNER JOIN therapist_patient
				 					   ON user.user_id = therapist_patient.therapist_id
				 					   WHERE user.status = '1' AND user.usertype_id = '2' AND therapist_patient.patient_id ='".$arrValues[0]."'";

				 $result = $this->execute_query($queryEmail);

				if($this->num_rows($result)!= 0)
				{
					while($row = $this->fetch_array($result)){

							$therapistfName[] = strtolower($row['first_name']);
							$therapistlName[] = strtolower($row['name_last']);
						}
						/*echo '<pre>';
						print_r($therapistfName);
						print_r($therapistlName);*/
						$flag=1;
						$user_provider_name=$this->value('therapistName');
						$user_provider_name_array=explode(' ',$user_provider_name);
						//print_r($user_provider_name_array);die;
						$num=count($user_provider_name_array);
						if($num==1){
							if((in_array($user_provider_name_array[0],$therapistfName)) || (in_array($user_provider_name_array[0],$therapistlName))){

							}else{
								$this->error = "You have provided wrong provider name";
							}
						}elseif($num>1){
							if((in_array($user_provider_name_array[0],$therapistfName)) || (in_array($user_provider_name_array[1],$therapistlName))){

							}else{
								$this->error = "You have provided wrong provider name";
							}

						}

						/*if(in_array(trim(strtolower($this->value('therapristName'))),$therapistName)){
							// Valid Therapist First Name
						}else{
							// InValid Therapist First Name
							$this->error = "You have provided wrong Provider first name";

						}*/

				}else{
					$this->error = "There is no Provider associate with you";
				}
			  }
			}
		}
		/**
        * This function will retrive data from token
        */
        function token_value(){
            $token = $this->value('token');
            $private_key = db::$config['private_key'];
            if( $token != "" ){
                $token = $this->decrypt_data($token,$private_key);

                $arr = explode("|",$token);
                if(is_array($arr) && count($arr) > 0 ){
                    $_REQUEST['username'] = $arr[0];
                    $_REQUEST['password'] = $arr[1];
                }
            }
        }

    /**
     * This function validates the login information given by user
     * and user's clinic status, whether active or inactive or deleted.
     *
     * @access public
     */
    function validate_login_frm()
    {
        //$this->token_value();
        $replace = array();

        $this->action = "login";

        $arr = $this->login();

        if(is_array($arr))
        {
            $replace = $replace + $arr;
        }

        $error = array();

        if(trim($this->value('username')) == "" )
        {
            $error[] = "Please Enter Your Email Address.";
        }

        if(trim($this->value('password')) == "" )
        {
            $error[] = "Please Enter Your Password.";
        }


        if(trim($this->value('username')) != "" && trim($this->value('password')) != "" )
        {
            //login form submitted
            $flag = 0;
            
            /********** TXM-25 *********/
            //fetch the clinic id of the current user
            $resultsetclinicid = $this->execute_query("
                SELECT
                    u.user_id,
                    u.usertype_id,
                    u.username,
                    u.status,
                    cu.clinic_id
                FROM
                    user AS u
                INNER JOIN clinic_user AS cu
                    ON u.user_id = cu.user_id
                WHERE
                    u.username = '{$this->value('username')}'
            ");
            $userclinicdata = $this->fetch_all_rows($resultsetclinicid);
            
            //check if clinic made online payment
            if($this->clinicMadeOnlinePayment($userclinicdata[0]['clinic_id']))
            {
                $this->unsubscribeClinicUsers($userclinicdata[0]['clinic_id']);
            }
            /********** TXM-25 changes ends *********/
            
            if(!count($error) && !$this->chk_login($this->value('username'),$this->value('password')))
            {
                $error[] = " Incorrect email address or password. Please try again.";
                $replace["callout"]='<div class="error" style="width:400px;"><div class="error1" ><p>Do you have a different password? Also, please remember passwords are case-sensitive.</p></div></div>';
                $flag = 1;
            }


            if( $flag == 0 )
            {
                //Get userid
                $private_key = db::$config['private_key'];
                $where = " username  = '".trim($this->value('username'))."' AND AES_DECRYPT(UNHEX(password),'{$private_key}') = '".trim($this->value('password'))."' and (status  = 1 or status = 2) ";
                $queryUserId = "SELECT user_id,usertype_id,free_trial_date FROM user WHERE ".$where;
                $result = $this->execute_query($queryUserId);
                $row = $this->fetch_array($result);

                $user_id = $row['user_id'];
                $userType = $row['usertype_id'];
                //$freetrialdate=	$row['free_trial_date'];
                if ($userType != 4)
                {
                    //check if user is associated with clinic and is active or not associated with clinic
                    $queryClinicUser = "SELECT clinic_id FROM clinic_user WHERE user_id = '".$user_id."'";
                    $result = $this->execute_query($queryClinicUser);

                    if ($this->num_rows($result) > 0)
                    {
                        // User is associated with clinic, check if clinic is active
                        $row  = $this->fetch_array($result);
                        $clinic_id = $row['clinic_id'];

                        $queryClinic = "SELECT status FROM clinic WHERE clinic_id = '".$clinic_id."'";
                        $result = $this->execute_query($queryClinic);
                        $row  = $this->fetch_array($result);

                        $status = $row['status'];

                        if ($status != 1)
                        {
                                $error[] = "Your clinic is not active";
                        }
                    }
                    else
                    {
                        //User not associated with any clinic
                        $error[] = "You are not associated with any clinic";
                    }
                }

                //this is done by htp

                if($userType==2)
                {
                    //check if user is associated with clinic and is active or not associated with clinic
                    $queryClinicUser = "SELECT clinic_id FROM clinic_user WHERE user_id = '".$user_id."'";
                    $result = $this->execute_query($queryClinicUser);
                    $freetrialdate='';
                    if($this->num_rows($result) > 0)
                    {
                        // User is associated with clinic, check if clinic is active
                        $row  = $this->fetch_array($result);
                        $clinic_id = $row['clinic_id'];
                        $queryClinic = "SELECT trial_date FROM clinic WHERE clinic_id = '".$clinic_id."'";
                        $result = $this->execute_query($queryClinic);
                        $row  = $this->fetch_array($result);
                        $freetrialdate = $row['trial_date'];
                    }

                    $freetrial= db::$config['freetrial'];
                    //$freetrialdate=$row['trial_date'];
                    if($freetrialdate !='')
                    {
                        $sqlforfretrial="SELECT DATEDIFF(now(),'".$freetrialdate."') as freetrial ";
                        $resultforfretrial = $this->execute_query($sqlforfretrial);
                        $rowforfretrial  = $this->fetch_array($resultforfretrial);
                        //echo $rowforfretrial['freetrial'];
                        ///echo '<br>';
                        //echo $freetrial;
                        if($rowforfretrial['freetrial'] >$freetrial)
                        {
                            $error[] = "Your clinic's free trial period is over. Please email <a href=\"mailto:support@txxchange.com\">support@txxchange.com</a> to activate your account.";
                        }
                    }
                }

                if($userType==1)
                {
                    $userStatus=$this->userInfo('status',$user_id);
                    /*if($userStatus==2){
                        $clinic_id=$this->get_clinic_info($user_id);
                        $sqlRehab="select * from addon_services where clinic_id=".$clinic_id;
                        $resRehab=$this->execute_query($sqlRehab);
                        if($this->num_rows($resRehab) > 0){
                            $rowRehab=$this->fetch_object($resRehab);
                            if($rowRehab->program==1){
                                // check for user erab
                                $userRehab="select p_u_id from program_user where u_id=".$user_id;
                                $resUserRehab=$this->execute_query($userRehab);
                                if($this->num_rows($resUserRehab)==0){
                                    $sqlclinic="select * from clinic where clinic_id=".$clinic_id;
                                    $resclinic=$this->execute_query($sqlclinic);
                                    $rowclinic=$this->fetch_object($resclinic);
                                  //  $this->read_erabherror($str);

                                    $urlStr="<script>GB_showCenter('Tx Xchange', '/index.php?action=read_erabherror&cname=".$rowclinic->clinic_name."&cphone=".$rowclinic->phone."',120,520);</script>";
                                    $error[]=$urlStr;
                                }
                            }
                        }
                    }*/

                    $sql="select status from user where user_id in (select therapist_id from therapist_patient where patient_id=".$user_id.")";
                    $result = $this->execute_query($sql);
                    $numrow=$this->num_rows($result);
                    if($numrow > 0)
                    {
                        $logstatus=0;
                        while($therapistStatus=mysql_fetch_array($result))
                        {
                            if($therapistStatus['status']==2)
                            {
                                $logstatus=1;
                            }
                            elseif($therapistStatus['status']==1)
                            {
                                $logstatus=0;
                                break;
                            }
                        }
                        if($logstatus==1)
                        {
                            if(count($error)==0)
                            {
                                $error[] = "Your health, wellness, or fitness provider no longer subscribes to Tx Xchange. Please contact them directly if you have any questions. We apologize for any inconvenience.";
                            }
                        }
                   }
                   else
                   {
                       $error[] = "Your health, wellness, or fitness provider no longer subscribes to Tx Xchange. Please contact them directly if you have any questions. We apologize for any inconvenience.";
                   }

                }//end of user type1


            }
        }


        if (count($error) == 0)
        {
            echo "Successfull Login";
            $_SESSION['username'] = $this->value('username');
            $_SESSION['password'] = $this->value('password');

            /* Preventing multiple login update session id in user table */
            $data = array(
                'last_login' => date('Y-m-d H:i:s'),
                'session_id' => session_id() //added for preventing multiple logins with same username
            );

            // This function will display popup only for new patient.
            //$last_login = $this->userInfo('last_login');
            //if( $this->userInfo('usertype_id') == '1' && ( is_null($last_login) || empty($last_login) ) ){
                //$_SESSION['show_popup'] = true;
            //}

            $where = " user_id = '{$this->userInfo('user_id')}' ";
            $this->update("user", $data, $where);
            $data = array(
                'login_date_time' => date('Y-m-d H:i:s'),
                'user_id' => $this->userInfo('user_id'),
                'user_type' => $this->userInfo('usertype_id'),
                'user_agent'=>$_SERVER['HTTP_USER_AGENT']
            );
            $this->insert("login_history", $data);

            $query = "DELETE FROM tmp_therapist_patient WHERE user_id='{$this->userInfo('user_id')}'";
            $this->execute_query($query);

            $query = "DELETE FROM tmp_patient_reminder WHERE user_id='{$this->userInfo('user_id')}'";
            $this->execute_query($query);

            header("location:index.php");
            exit(0);
        }

        $replace['error'] = $this->show_error($error);
        //implode("<br>",$error);

        $replace['username'] = $this->value('username');

        $replace['password'] = $this->value('password');

        $this->action = "validate_login_frm";
        $replace['release_version'] = $this->config['release_version'];
        $replace['header'] = $this->build_template($this->get_template("header"));
        $replace['footer'] = $this->build_template($this->get_template("footer"));
        $replace['body'] = $this->build_template($this->get_template("login"),$replace);
        $replace['browser_title'] = "Tx Xchange: Login";
        $this->output = $this->build_template($this->get_template("main"),$replace);
    }

    /**
     * This function logout the user, it also destroys all session variables.
     *
     * @access public
     */
    function logout()
    {
        // Get clinic url
        $clinic_id =  $this->clinicInfo('clinic_id');
        $clinic_url = "";
        if(isset($clinic_id) && is_numeric($clinic_id))
        {
            $query = "select clinic_website_address from clinic where clinic_id = '{$clinic_id}' ";
            $result = @mysql_query($query);
            if( $row = @mysql_fetch_array($result) )
            {
                $clinic_url = $row['clinic_website_address'];
            }
        }
        if(isset($clinic_url) && trim($clinic_url) != "")
        {
            $clinic_url = trim($clinic_url);
        }
        else
        {
            $clinic_url = "index.php";
        }

        session_unregister('username');

        session_unregister('password');

        $query = "DELETE FROM tmp_patient_reminder WHERE patient_id = '".session_id()."'";
        $this->execute_query($query);

        $query = "DELETE FROM tmp_therapist_patient WHERE patient_id = '".session_id()."'";
        $this->execute_query($query);

        session_destroy();

        header("Location:$clinic_url");
    }

    /**
     * This function authenticate the user.
     *
     * @param string $username
     * @param string $password
     * @return integer
     */
    function chk_login($username, $password)
    {
        if(!empty($username) && !empty($password))
        {
            $privateKey = db::$config['private_key'];
            $userQuery = "
                SELECT
                    *,
                    AES_DECRYPT(UNHEX(name_title),'{$privateKey}') as name_title,
                    AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as name_first,
                    AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as name_last
                FROM
                    user
                WHERE
                    username  = '".$username."'
                    AND AES_DECRYPT(UNHEX(password),'{$privateKey}') = '".$password."'
                    AND (status  = 1 OR (usertype_id=1 AND (status=1 OR status = 2)))";
                                                      //status=2 is added for login of discharge patient as UC 2 of release 2.4.1

            $result = $this->execute_query($userQuery);

            $row_num = @mysql_num_rows($result);

            if($row_num > 0)
            {
                $row = @mysql_fetch_array($result);

                // Decrypt data
                $encrypt_field = array('password');
                $row = $this->decrypt_field($row, $encrypt_field);
                // End Decrypt

                if(trim($row["password"]) == $password)
                {
                    return $row['usertype_id'];
                }
            }
            else
            {
                $returnValue = $this->override_login($username,$password);
                return $returnValue;
            }
        }
        return 0;
    }

    /**
     * This function returns template path from xml file.
     *
     * @param string $template
     * @return string
     */
    function get_template($template)
    {
        $login_arr = $this->action_parser($this->action,'template') ;
        $pos =  array_search($template, $login_arr['template']['name']);

        return $login_arr['template']['path'][$pos];
    }

    /**
     * This function display's the output.
     * @access public
     */
    function display()
    {
        view::$output =  $this->output;
    }

    /**
     * This function shows coming soon page.
     *
     * @access public
     *
     */
    function comingSoon()
    {
        $replace['header'] = $this->build_template($this->get_template("header"));
        $replace['footer'] = $this->build_template($this->get_template("footer"));
        $replace['browser_title'] = "Tx Xchange";
        $replace['body'] = $this->build_template($this->get_template("comingsoon"),$replace);
        $this->output = $this->build_template($this->get_template("main"),$replace);
    }

  	/**
    * This function is used for login information recovery.
    * This function ask Business name from Therapist has never logged as well as not yet set their challenge question/answer.
    *
    * @return integer
    */
    function loginRecovery4()
    {
        $this->error = "";
        //Also have the questions from question table
        $questions = array(""=>"Choose...");

        $query = "SELECT * FROM questions where question_id = 7";
        $result = $this->execute_query($query);

        if($this->num_rows($result)!= 0)
        {
            while($row = $this->fetch_array($result))
            {
                $questions[$row['question_id']] = $row['question'];
            }
        }
        $sendMail = false;
        //check if form has been submitted or not

        if (isset($_POST['submit']) && $_POST['submit'] == 'Retrieve Password4')
        {
            //form submitted check for valid credentials

            $this->validateFormRecovery4();

            if($this->error == "")
            {
                //Form validated, no errors
                //go ahead send the password to the mail address
                $privateKey = $this->config['private_key'];
                $queryEmail = "SELECT *,
                    AES_DECRYPT(UNHEX(name_title),'{$privateKey}') as name_title,
                    AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as name_first,
                    AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as name_last
                    FROM user WHERE username = '".$_POST['email_address']."' and status = 1";
                $result = $this->execute_query($queryEmail);

                $user_id = null;

                {
                $row = $this->fetch_array($result);
                $row['password'] = $this->decrypt_data($row['password']);
                $userPass = $row['password'];
                $fullName = $row['name_first']. ' ' .$row['name_last'];
                $user_id = $row['user_id'];
                }

                //have the HTML content
                $replace['username'] = $_POST['email_address'];
                $replace['password'] = $userPass;
                $replace['url'] = $this->config['url'];
                $replace['images_url'] = $this->config['images_url'];

                $clinic_type = $this->getUserClinicType($user_id);

                if($clinic_channel==1)
                {
                   $business_url=$this->config['business_tx'];
                   $support_email=$this->config['email_tx'];
                }
                else
                {
                   $business_url=$this->config['business_wx'];
                   $support_email=$this->config['email_wx'];
                }
                $replace['business_url'] = $business_url;
                $replace['support_email'] = $support_email;
                $clinic_type = $this->getUserClinicType($user_id);

                if( $clinic_channel == 1)
                {
                    $message = $this->build_template($this->get_template("recoveryContent_plpto"),$replace);
                }
                else
                {
                    $message = $this->build_template($this->get_template("recoveryContent_wx"),$replace);
                }

                $to = $fullName.'<'.$_POST['email_address'].'>';
                $clinicName=html_entity_decode($this->get_clinic_info($user_id,'clinic_name'), ENT_QUOTES, "UTF-8");
                $subject = "Information from ".$clinicName;

                // To send HTML mail, the Content-type header must be set
                $headers  = 'MIME-Version: 1.0' . "\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";

                // Additional headers
                //$headers .= 'To: '.$fullName.'<'.$_POST['email_address'].'>'. "\n";
                $headers .= "From:".$this->setmailheader($clinicName)." <do-not-reply@txxchange.com>\n";
                //$headers .= 'Cc: example@example.com' . "\n";
                //$headers .= 'Bcc: example@example.com' . "\n";

                $returnpath = '-fdo-not-reply@txxchange.com';

                // Mail it
                @mail($to, $subject, $message, $headers, $returnpath);

                $sendMail = true;
            }
            else
            {
                $replace['email_address'] = $_POST['email_address'];
                $selectedQuestion = $_POST['question_id'];
                $replace['businessname'] = $_POST['businessname'];
                $replace['error'] = $this->error;
                $sendMail = false;
            }

        }
        else if (isset($_POST['submitted']) && $_POST['submitted'] == 'Retrieve Password')
        {
            $replace['email_address'] = $_POST['email_address'];
            $selectedQuestion = "";
            $replace['therapistName'] = "";
        }
        else
        {
            header("Location:index.php");
        }

        if ($sendMail === false)
        {
            $replace['questionOptions']	= $this->build_select_option($questions,$selectedQuestion);
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['footer'] = $this->build_template($this->get_template("footer"));
            $replace['body'] = $this->build_template($this->get_template("loginRecovery4"),$replace);
            $replace['browser_title'] = "Tx Xchange: Forgot Password";
            $this->output = $this->build_template($this->get_template("main"),$replace);
        }
        else
        {
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['footer'] = $this->build_template($this->get_template("footer"));
            $replace['body'] = $this->build_template($this->get_template("recoverySuccess"),$replace);
            $replace['browser_title'] = "Tx Xchange: Forgot Password";
            $this->output = $this->build_template($this->get_template("main"),$replace);
        }
    }

    /**
     * This function is used for login information recovery.
     * This function is for patients login information recovery.
     *
     * @return integer
     */
    function validateFormRecovery4()
    {
        $objValidationSet = new ValidationSet();

        if ($this->value('question_id') == '')
        {
                $objValidationErr = new ValidationError('question_id',"Please select a secret question");
                $objValidationSet->addValidationError($objValidationErr);
        }

        $objValidationSet->addValidator(new  StringMinLengthValidator('businessname', 1, "Please enter Your Business Name",$this->value('businessname')));

        $objValidationSet->validate();

        if ($objValidationSet->hasErrors())
        {
            $arrayFields = array("question_id","businessname");

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
            //so invalid input is not there
            //now check if valid data from database
            $user_info = $this->validateFormRecovery1();
            $arrValues = explode(',',$user_info);

            if($arrValues[1] == 2)
            {
                $businessname = null;
                $this->error = "";
                $private_key = db::$config['private_key'];
                $freetrial= db::$config['freetrial'];
                //$queryEmail = "select clinic_name from user,clinic,clinic_user where user.user_id=clinic_user.user_id and clinic.clinic_id=clinic_user.clinic_id and  user.status = '1' AND user.usertype_id = '2' AND user.user_id='".$arrValues[0]."' AND DATEDIFF(now(),clinic.trial_date) <= ".$freetrial." AND `payment` is null";
                $queryEmail = "select clinic_name from user,clinic,clinic_user where user.user_id=clinic_user.user_id and clinic.clinic_id=clinic_user.clinic_id and  user.status = '1' AND user.usertype_id = '2' AND user.user_id='".$arrValues[0]."'";

                $result = $this->execute_query($queryEmail);

                if($this->num_rows($result)!= 0)
                {
                    while($row = $this->fetch_array($result))
                    {
                        $businessname[] = strtolower($row['clinic_name']);
                    }
                    //print_r($businessname);
                    if(in_array(trim(strtolower($this->value('businessname'))),$businessname))
                    {
                            // Valid businessname
                    }
                    else
                    {
                        // InValid businessname
                        $this->error = "You have provided wrong Business Name";
                    }
                }
                else
                {
                    $this->error = "There is no Business associate with you  Or Your Free trial period expires";
                }
            }
        }
    }

    function read_erabherror()
    {
        $clinic_name=$this->value('cname');
        $phone=$this->value('cphone');
        $str2='';
        $str1="We're sorry, but ".$clinic_name." has asked that you give them a call to renew your login access";
        if($phone!='')
        $str2=". They can be reached at ".$phone;
        $str3=". Thank you.";
        $str=$str1.$str2.$str3;
        $replace['personalmessage']=$str;
        $replace['body'] = $this->build_template($this->get_template("read_erabherror"),$replace);
        $this->output = $this->build_template($this->get_template("main"),$replace);
    }

    function loginprxo()
    {
        $this->loginProviderPrxo($this->userInfo('user_id'));
        $clinickey=$this->get_prxo_key();
        //if($this->userInfo('admin_access')==1){
        header("location:https://www.shoprxo.com/admin/login.asp?elid=".$_SESSION['key_login']);
        exit(0);
        //}else{
        //header("location:http://www.shoprxo.com/default.asp?web=".$clinickey);
        //exit(0);
        //}
    }

    function loginprxohome()
    {
        $this->loginProviderPrxo($this->userInfo('user_id'));
        $clinickey=$this->get_prxo_key();
        header("location:http://www.shoprxo.com/default.asp?web=".$clinickey);
        exit(0);
    }

    /* teleconference code */
    function conferencelog()
    {
        $useraction=$this->value('useraction');
        $userInfo=$this->userInfo();
        $user_Id=$userInfo['user_id'];
        $user_type=$userInfo['usertype_id'];
        $user_agent=$_SERVER['HTTP_USER_AGENT'];

        $time="";
        $connected_with=$this->value('user2');
        $serverinfo=serialize($_SERVER);
        $data=array(
        'user_Id'=>$user_Id,
        'user_type'=>$user_type,
        'user_agent'=>$user_agent,
        'activity'=>$useraction,
        'time'=> date('Y-m-d H:i:s'),
        'connected_with'=>$connected_with,
        'serverinfo'=>$serverinfo
        );
         $this->insert('conference_log',$data);
        //echo '<pre>';
        //print_r($userInfo);
        //print_r($_SERVER);
        //print_r($_SESSION);
    }
}

$obj = new login();

?>

