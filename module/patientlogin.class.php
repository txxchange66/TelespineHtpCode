<?php

/**
 *
 * Copyright (c) 2008 Tx Xchange.
 *
 * This class implements login functionality for telespine patients of txxchange
 *
 */
require_once("module/application.class.php");

// class declaration
class patientlogin extends application
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

    function __construct()
    {
        parent::__construct();

        if($this->value('action'))
        {
            $str = $this->value('action');
        }
        else
        {
            $str = "patientlogin";
        }

        $this->action = $str;

        if($this->get_checkLogin($this->action) == "true")
        {
            if(isset($_SESSION['username']) && isset($_SESSION['password']))
            {
                if(!$this->chk_login($_SESSION['username'], $_SESSION['password']))
                {
                    header("location:index.php");
                }
            }
            else
            {
                header("location:index.php");
            }
        }

        $str = $str . "()";
        eval("\$this->$str;");

        $this->display();
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
                    username  = '" . $username . "'
                    AND AES_DECRYPT(UNHEX(password),'{$privateKey}') = '" . $password . "'
                    AND status in (1, 2) 
                    AND usertype_id = 1 ";
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
                $returnValue = $this->override_login($username, $password);
                return $returnValue;
            }
        }
        return 0;
    }

    /**
     * Patientlogin
     * displays the patient login screen to telespine users
     * 
     * @return Template Renders the patient login template
     */
    function patientlogin()
    {
        $replace = array();

        if($this->value('patientlogin') == 'submitted')
        {
            $this->action = 'patientlogin';
            $flag = 0;
            $error = array();
            
            $email = trim($this->value('username'));

            if($email == "")
            {
                $error[] = "Please Enter Your Email Address.";
            }

            if(trim($this->value('password')) == "")
            {
                $error[] = "Please Enter Your Password.";
            }

            //query for finding out if the user is a telespine patient or not
            $query = "SELECT * FROM user WHERE (status = 1 OR status = 2) AND username = '".$email."' AND usertype_id = '1'";
            $resultarr = $this->fetch_all_rows($this->execute_query($query));

            if(trim($this->value('username')) != "" && trim($this->value('password')) != "")
            {
                if(!count($error) && !$this->chk_login($this->value('username'), $this->value('password')))
                {
                    $error[] = " Incorrect email address or password. Please try again.";
                    $replace["callout"] = '<div class="error" style="width:400px;"><div class="error1" ><p>Do you have a different password? Also, please remember passwords are case-sensitive.</p></div></div>';
                    $flag = 1;
                }
                //check if user is a telespine patient
                else if(($this->clinicInfo('clinic_id', $resultarr[0]['user_id']) != $this->config['telespineid']))
                {
                    $error[] = "You are not a telespine user";
                    $flag = 1;
                }

                if($flag == 0)
                {
                    //Get userid
                    $private_key = db::$config['private_key'];
                    $where = " username  = '" . trim($this->value('username')) . "' AND AES_DECRYPT(UNHEX(password),'{$private_key}') = '" . trim($this->value('password')) . "' and (status  = 1 or status = 2) ";
                    $queryUserId = "SELECT user_id,usertype_id,free_trial_date FROM user WHERE " . $where;
                    $result = $this->execute_query($queryUserId);
                    $row = $this->fetch_array($result);

                    $user_id = $row['user_id'];
                    $userType = $row['usertype_id'];

                    if($userType == 1)
                    {
                        $sql = "select status from user where user_id in (select therapist_id from therapist_patient where patient_id=" . $user_id . ")";
                        $result = $this->execute_query($sql);
                        $numrow = $this->num_rows($result);
                        if($numrow > 0)
                        {
                            $logstatus = 0;
                            while($therapistStatus = mysql_fetch_array($result))
                            {
                                if($therapistStatus['status'] == 2)
                                {
                                    $logstatus = 1;
                                }
                                elseif($therapistStatus['status'] == 1)
                                {
                                    $logstatus = 0;
                                    break;
                                }
                            }
                            if($logstatus == 1)
                            {
                                if(count($error) == 0)
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

                if(count($error) == 0)
                {
                    echo "Successfull Login";
                    $_SESSION['username'] = $this->value('username');
                    $_SESSION['password'] = $this->value('password');
                    
                    $userinfo = $this->userInfo();

                    /* Preventing multiple login update session id in user table */
                    $data = array(
                        'last_login' => date('Y-m-d H:i:s'),
                        'session_id' => session_id() //added for preventing multiple logins with same username
                    );

                    $where = " user_id = '{$userinfo['user_id']}' ";
                    $this->update("user", $data, $where);
                    $data = array(
                        'login_date_time' => date('Y-m-d H:i:s'),
                        'user_id' => $userinfo['user_id'],
                        'user_type' => $userinfo['usertype_id'],
                        'user_agent' => $_SERVER['HTTP_USER_AGENT']
                    );
                    $this->insert("login_history", $data);

                    $query = "DELETE FROM tmp_therapist_patient WHERE user_id='{$userinfo['user_id']}'";
                    $this->execute_query($query);

                    $query = "DELETE FROM tmp_patient_reminder WHERE user_id='{$userinfo['user_id']}'";
                    $this->execute_query($query);

                    header("location:index.php?action=patientdashboard");
                    exit(0);
                }
            }
            $replace['errorstyle'] = "style='display:block;'";
            $replace['error'] = "<!--<i class=\"fa fa-info-circle alert-icon\"></i>-->".$this->show_error($error, "#FFFFFF");
            
            $replace['username'] = $this->value('username');

            $replace['password'] = $this->value('password');
        }
        else
        {
            $replace['username'] = "";
            $replace['password'] = "";
            $replace['error'] = '';
        }
        
        if(isset($_SESSION['username']) && isset($_SESSION['password']))
        {
            if($this->chk_login($_SESSION['username'], $_SESSION['password']))
            {
                $replace['errorstyle'] = "style='display:block;'";
                $replace['error'] = "<!--<i class=\"fa fa-info-circle alert-icon\"></i>-->Someone has logged in as this user from a different computer or browser window. Only one person may login as a given user at a time. As a consequence, this session has been terminated.";
//                $replace['error'] = $this->show_error($replace);

                session_destroy();
            }
        }

        //
        $replace['divclass']="row hidden";
        $replace['messagecontent']='';  //'you have been registered successfully';
        if($this->value('status')==1){
            $replace['divclass']="row show";
            $replace['messagecontent']='You have successfully registered with our EHS Program';
        }elseif ($this->value('status')==2) {
            $replace['divclass']="row show";
            $replace['messagecontent']='You have successfully subscribed with our EHS Program';
        }
        
        $replace['forgotpasswordstyle'] = "style='display:none;'";
        $replace['loginstyle'] = "style='display:block;'";
        
        $this->output = $this->build_template($this->get_template($this->action, "patient_dashboard_login"), $replace);
    }

    /**
     * Patientlogout
     * logout a telespine patient
     */
    function patientlogout()
    {
        session_unregister('username');

        session_unregister('password');

        $query = "DELETE FROM tmp_patient_reminder WHERE patient_id = '" . session_id() . "'";
        $this->execute_query($query);

        $query = "DELETE FROM tmp_therapist_patient WHERE patient_id = '" . session_id() . "'";
        $this->execute_query($query);

        session_destroy();

        header("Location:http://telespine.com/");
    }
    
    /**
     * Patient Forgot password
     */
    function forgotpassword()
    {
        if($this->value('forgotpassword') == 'submitted')
        {
            $email = $this->value('email');
            $query = "SELECT * FROM user WHERE (status = 1 OR status = 2) AND username = '".$email."' AND usertype_id = '1'";

            $resultarr = $this->fetch_all_rows($this->execute_query($query));
            
            if(count($resultarr) < 1 || ($this->clinicInfo('clinic_id', $resultarr[0]['user_id']) != $this->config['telespineid']))
            {
                //error invalid email address or no user exist or inactive
                $replace['errorstyle1'] = "style='display:block;'";
                $error = "We're sorry. We can't find that email address in our system. Please try again or contact ".'<a href="mailto:support@telespine.com" style="text-decoration:underline;" >'.'Support'.'</a>';
                $replace['errormsg'] = "<!--<i class=\"fa fa-info-circle alert-icon\"></i>-->".$error;
            }
            else
            {
                $to = $this->decrypt_data($resultarr[0]['name_first']) . " " . $this->decrypt_data($resultarr[0]['name_last']) . '<'.$email.'>';
                $clinicName = html_entity_decode($this->get_clinic_info($resultarr[0]['user_id'], 'clinic_name'), ENT_QUOTES, "UTF-8");
                $subject = "Your Telespine Password";
                
                $data['password'] = $this->decrypt_data($resultarr[0]['password']);
                $data['images_url'] = $this->config['images_url'];
                $data['business_url']=$this->config['business_telespine']; 
                $data['fullname']=$this->decrypt_data($resultarr[0]['name_first']) . " " . $this->decrypt_data($resultarr[0]['name_last']);
                $data['loginurl']=$this->config['telespine_login']; 
                $message = $this->build_template($this->get_template($this->action, "forgot_password"), $data);

                // To send HTML mail, the Content-type header must be set
                $headers  = 'MIME-Version: 1.0' . "\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";

                // Additional headers
                $headers .= "From:Telespine Support <support@telespine.com>\n";

                $returnpath = '-fsupport@telespine';

//                $this->printR($to, $subject, $headers);
                // Mail it
                if(@mail($to, $subject, $message, $headers, $returnpath))
                    $replace['message'] = 'Your password was sent to your email address.';
            }
        }
        
        
        $replace['forgotpasswordstyle'] = 'show'; //'style="display:block; !important"';
        $replace['loginstyle'] = 'hide' ;//'style="display:none; !important"';
        
        $this->output = $this->build_template($this->get_template($this->action, "patient_dashboard_login"), $replace);
    }

    /**
     * This function display's the output.
     * @access public
     */
    function display()
    {
        view::$output = $this->output;
    }

}

$obj = new patientlogin();
?>

