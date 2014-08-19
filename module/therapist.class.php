<?php

/**
 * 
 * Copyright (c) 2008 Tx Xchange.
 * 
 * This class is for following functionality:
 * 1) Display Therapist home page.
 * 2) Create patient by Therapist.
 * 3) Edit patient by Therapist.
 * 4) Delete patient by Therapist.
 * 
 * Neccessary class for pagination.
 * require_once("include/paging/my_pagina_class.php");
 * 
 * Neccessary class for getting access of application specific methods.
 * require_once("module/application.class.php");
 */
require_once("include/paging/my_pagina_class.php");
require_once("module/application.class.php");
require_once("include/postgraph.class.php");
require_once ("opentok/API_Config.php");
require_once ("opentok/OpenTokSDK.php");

class therapist extends application {

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
    function __construct() {

        parent::__construct();
        if ($this->value('action')) {
            /*
              This block of statement(s) are to handle all the actions supported by this Login class
              that is it could be the case that more then one action are handled by login
              for example at first the action is "login" then after submit say action is submit
              so if login is explicitly called we have the login action set (which is also our default action)
              else whatever action is it is set in $str.
             */
            $str = $this->value('action');
        } else {
            $str = "therapist"; //default if no action is specified
        }
        $this->action = $str;
        if ($this->get_checkLogin($this->action) == "true") {
            if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
                if (!$this->chk_login($_SESSION['username'], $_SESSION['password'])) {
                    header("location:index.php");
                }
            } else {
                header("location:index.php");
            }
        }
        if ($this->userAccess($str)) {

            // Code To Call Personalized GUI
            $this->call_gui();
            $str = $str . "()";

            eval("\$this->$str;");
        } else {
            $this->output = $this->config['error_message'];
        }
        $this->display();
    }

    /**
     * This function will return list of Goals.
     */
    function goal($userId) {
        $query = " select * from patient_goal where created_by = '{$userId}' and (status = '1' or status = '2') order by patient_goal_id desc ";
        $result = @mysql_query($query);
        $replace['view_all'] = '';
        if (@mysql_num_rows($result) > 0) {
            while ($row = @mysql_fetch_array($result)) {
                $strike = "";
                $checked = "";
                if ($row['status'] == 2) {
                    $strike = 'text-decoration: line-through';
                    $checked = 'checked';
                }
                //$row['goal'] = $this->lengthtcorrect($row['goal'],38);
                $replace['list_goal'] .= "<div id='div_{$row['patient_goal_id']}' onmouseover='show_trash(this,1);' onmouseout='show_trash(this,2);' style='width:250px;' >
                                    <span id='span_{$row['patient_goal_id']}'  style='{$strike};display: block; width: 210px; float: right; '  >" . $row['goal'] . "</span>
                                    <span id='trash_{$row['patient_goal_id']}' style='visibility:hidden;' onclick='del_goal(this);'>
                                        <img src='/images/trash.gif'  />
                                    </span><input type='checkbox' name='chk_{$row['patient_goal_id']}' value='{$row['patient_goal_id']}' $checked onclick='stikeout(this);' />
                                    <div style='clear:both;'></div></div>";
            }
            //$replace['view_all'] = '<a href="javascript:view_goal();"  id="allLink"  >View all</a>';
        }
        $output = $this->build_template($this->get_template('goal'), $replace);
        return $output;
    }

    /**
     * This function will return goal completed.
     */
    function goal_completed($userId = "") {
        if ($userId == "") {
            $userId = $this->value('userId');
        }
        $query = " select count(*) from patient_goal where created_by = '{$userId}' and (status = '1' or status = '2') order by created_on desc ";
        $result = @mysql_query($query);
        $total_goals = 0;
        if ($row = @mysql_fetch_array($result)) {
            $total_goals = $row[0];
        }
        $query = " select count(*) from patient_goal where created_by = '{$userId}' and status = '2' order by created_on desc ";
        $result = @mysql_query($query);
        $total_goals_completed = 0;
        if ($row = @mysql_fetch_array($result)) {
            $total_goals_completed = $row[0];
        }
        $percentage = 0;
        if ($total_goals > 0) {
            $percentage = round((($total_goals_completed / $total_goals) * 100), 0);
        }
        if ($this->value('userId') != "") {
            echo $percentage;
            return;
        }
        return $percentage;
    }

    /**
     * This function shows Therapist(s) home page.
     *
     * @access public
     */
    function therapist() {
        $replace['header'] = $this->build_template($this->get_template("header"));
        // Personalized GUI Implemented
        $replace['sidebar'] = $this->sidebar();


        // $therapistClinicId = $this->get_clinic_info($this->userInfo('user_id'));
        //$providerEhealthStatus = $this->getProviderEhealthStatus($therapistClinicId);
        //$_SESSION['providerEhealthStatus'] = $providerEhealthStatus;
        // echo "<pre>";print_r($_SESSION);
        $replace['get_satisfaction'] = $this->get_satisfaction();

        /* TopAssignedPlans() function is defined in application class. Application class is superclass of sysadmin class */

        $arr = $this->userInfo();
        $cnt = 0;
        $c = 0;
        $checkprxo = $this->checkprxoservice($this->userInfo('user_id'));

        if ($checkprxo == 'no') {
            $replace['mainTopAssignedPlans'] .= $this->build_template($this->get_template("plan"), $data);

            $result = $this->TopAssignedPlans($arr['usertype_id'], $arr['user_id']);
            while ($result && ($row = $this->fetch_array($result)) && $c < 10) {
                $row['style'] = ($c++ % 2) ? "line1" : "line2";
                $row['description'] = $this->lengthtcorrect($row['description'], 34);
                $replace['TopAssignedPlans'] .= $this->build_template($this->get_template("TopAssignedPlans"), $row);
            }

            // check for more link after listing of plans 10 plan.
            if (is_resource($result) && @mysql_num_rows($result) > 10) {
                $data = array(
                    'listAction' => 'therapistPlan'
                );
                $replace['TopAssignedPlans'] .= $this->build_template($this->get_template("more"), $data);
            }
            // End of check for more link.
        } else {
            $clinickey = $this->get_prxo_key();
            if ($this->userInfo('admin_access') == 1) {
                $location = "https://www.shoprxo.com/admin/login.asp?elid=" . $_SESSION['key_login'];
            } else {
                $location = "http://www.shoprxo.com/default.asp?web=" . $clinickey;
            }

            $data = array('clinicname' => $this->get_clinic_name($this->get_clinic_info($this->userInfo('user_id'))), 'location' => $location);

            $replace['mainTopAssignedPlans'] .= $this->build_template($this->get_template("prxo"), $data);
        }

        /* TopAssignedArticles() function is defined in application class. Application class is superclass of sysadmin class */
        $result = $this->TopAssignedArticles($arr['usertype_id'], $arr['user_id']);
        $c = 0;
        while ($result && ($row = $this->fetch_array($result)) && $c < 10) {
            $row['style'] = ($c++ % 2) ? "line1" : "line2";
            $replace['TopAssignedArticles'] .= $this->build_template($this->get_template("TopAssignedArticles"), $row);
        }

        // check for more link after listing of plans 10 article.
        if (is_resource($result) && @mysql_num_rows($result) > 10) {
            $data = array(
                'listAction' => 'therapistLibrary'
            );
            $replace['TopAssignedArticles'] .= $this->build_template($this->get_template("more"), $data);
        }
        // End of check for more link.
        // Current Patient Activity
        $privateKey = $this->config['private_key'];
        $query = "select 
            AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}') as name_first,
            AES_DECRYPT(UNHEX(u.name_last),'{$privateKey}') as name_last, 
            u.last_login, u.user_id 
            from therapist_patient tp inner join user u on tp.patient_id = u.user_id and u.status = '1' 
            where tp.therapist_id = '{$this->userInfo('user_id')}' order by u.modified desc  ";

        $result = $this->execute_query($query);
        $c = 0;
        while ($result && ($row = $this->fetch_array($result)) && $c < 10) {
            $row['style'] = ($c++ % 2) ? "line1" : "line2";
            // This line is commented to fix bug no. 10257. Now we have to show last login field value in current patient activity.
            //$row['modified'] = $this->formatDate($row['modified']);
            $row['modified'] = $this->formatDate($row['last_login']) != "" ? $this->formatDate($row['last_login']) : "Never";
            $replace['current_patient_activity'] .= $this->build_template($this->get_template("current_patient_activity"), $row);
        }
        // check for more link after listing of plans 10 article.
        if (is_resource($result) && @mysql_num_rows($result) > 10) {
            $data = array(
                'listAction' => 'myPatients'
            );
            $replace['current_patient_activity'] .= $this->build_template($this->get_template("more"), $data);
        }
        // End of check for more link.

        $privateKey = $this->config['private_key'];
        // Recent Message
        /* $query = "SELECT concat(AES_DECRYPT(UNHEX(name_first),'{$privateKey}'),' ',AES_DECRYPT(UNHEX(name_last),'{$privateKey}')) as from_name,m.subject
          ,m.recent_date AS sent_date,m.patient_id,m.message_id,
          replies,
          m.patient_id
          FROM message m
          inner join user u on u.user_id = m.patient_id
          WHERE m.patient_id IN (
          SELECT patient_id FROM therapist_patient WHERE therapist_id = '{$this->userInfo('user_id')}'
          )
          and m.message_id not in (select message_id from system_message where user_id = '{$this->userInfo('user_id')}')
          and m.parent_id = 0  order by  sent_date desc LIMIT 0 , 10 "; */
        //change for 1 to 1 messageing
        $where = " and m.mass_patient_id =0 and  m.patient_id IN (
                SELECT patient_id FROM therapist_patient WHERE therapist_id = '{$this->userInfo('user_id')}'
                )  and aa_message='0'  or  ((mass_patient_id ='0')) and sender_id='{$this->userInfo('user_id')}' and m.parent_id = '0' or  ((mass_patient_id ='0')) and patient_id='{$this->userInfo('user_id')}' and m.parent_id = '0' or  ((mass_patient_id ='0')) and sender_id='{$this->userInfo('user_id')}' and aa_message='1' and m.parent_id = '0' ";
        // if($this->userInfo('admin_access')==1){
        $where .= " or ((mass_patient_id !='0' and replies !='0') OR mass_status='1') and sender_id = '{$this->userInfo('user_id')}' and m.parent_id = '0'  or  ((mass_patient_id !='0' and replies !='0') OR mass_status='1') and mass_patient_id='{$this->userInfo('user_id')}' and m.parent_id = '0'  or  ((mass_patient_id ='0')) and sender_id='{$this->userInfo('user_id')}' and m.parent_id = '0' or  ((mass_patient_id ='0') ) and patient_id='{$this->userInfo('user_id')}' and m.parent_id = '0' or  ((mass_patient_id ='0') ) and sender_id='{$this->userInfo('user_id')}' and aa_message='1' and m.parent_id = '0' ";
        // }

        $query = "SELECT concat(AES_DECRYPT(UNHEX(name_first),'{$privateKey}'),' ',AES_DECRYPT(UNHEX(name_last),'{$privateKey}')) as from_name,m.subject
          ,m.recent_date AS sent_date,m.patient_id,m.message_id, 
          replies,
          m.patient_id,
          m.mass_status,
          m.mass_patient_id
        FROM message m
            inner join user u on u.user_id = m.patient_id  
        WHERE m.parent_id = '0' {$where}  and
            m.message_id not in (select message_id from system_message where user_id = '{$this->userInfo('user_id')}') 
            and m.parent_id = '0'  order by  sent_date desc LIMIT 0 , 10 ";

        $result = @mysql_query($query);
        while ($row = @mysql_fetch_array($result)) {
            $check = $this->check_therapist($row['patient_id']);
            $usertype = $this->userInfo('usertype_id', $row['patient_id']);
            if ($check == 1) {
                if ($usertype == 1)
                    $from_name = "<a href='index.php?action=therapistViewPatient&id={$row['patient_id']}'>" . $this->unread_message($row['message_id'], $row['from_name']) . "</a>";
                else
                    $from_name = "<a href='index.php?action=therapistViewPatient&id={$row['patient_id']}'>" . $this->unread_message($row['message_id'], $row['from_name']) . ' (Staff)' . "</a>";
            }
            else {
                if ($usertype == 1)
                    $from_name = "<span style='color:black'>" . $this->unread_message($row['message_id'], $row['from_name']) . "</span>";
                else
                    $from_name = "<span style='color:black'>" . $this->unread_message($row['message_id'], $row['from_name']) . ' (Staff)' . "</span>";
            }
            if ($row['mass_status'] == 1) {
                if ($row['mass_patient_id'] == -1) {
                    $from_name = "<span style='color:black'>" . 'All Current Patients' . "</span>";
                } elseif ($row['mass_patient_id'] == -2) {
                    $from_name = "<span style='color:black'>" . 'All Discharged Patients' . "</span>";
                } elseif ($row['mass_patient_id'] == -3) {
                    $from_name = "<span style='color:black'>" . 'All Patients' . "</span>";
                } elseif ($row['mass_patient_id'] == -4) {
                    $from_name = "<span style='color:black'>" . 'All Ehs Patients' . "</span>";
                }
            }
            $data = array(
                'style' => ($c++ % 2) ? "line1" : "line2",
                'from_name' => $from_name,
                'subject' => "<a href='index.php?action=set_unread_message&message_id={$row['message_id']}'>" . $this->unread_message($row['message_id'], $this->decrypt_data($row['subject'])) . "</a>",
                'sent_date' => "<a href='index.php?action=set_unread_message&message_id={$row['message_id']}'>" . $this->unread_message($row['message_id'], $this->formatDateExtra($row['sent_date'], $this->userInfo('timezone'))) . "</a>",
                'replies' => $row['replies']
            );
            $replace['recent_message'] .= $this->build_template($this->get_template("recent_message"), $data);
        }
        // End .
        //code for checking if user has any admin notification message
        $query = "select * from message_notifications_users where  user_id=" . $this->userInfo('user_id');
        $result = @mysql_query($query);
        if (mysql_num_rows($result) > 0) {
            while ($row = @mysql_fetch_object($result)) {
                $messageIds.=$row->message_id . ",";
            }
            $messageIds = substr($messageIds, 0, -1);
            $urlStr = "<script>GB_showCenter('Telehealth Network Updates', '/index.php?action=read_notification&ids=" . $messageIds . "',450,720);</script>";
        }
        else
            $urlStr = "";

        $replace['adminNotifications'] = $urlStr;
        $replace['body'] = $this->build_template($this->get_template("container"), $replace);

        // Personalized GUI
        $imageCreateNewTemplatePlan = $_SESSION['providerLabel']['images/createNewTemplatePlan.gif'] != '' ? $_SESSION['providerLabel']['images/createNewTemplatePlan.gif'] : "images/createNewTemplatePlan.gif";
        $replace['imageCreateNewTemplatePlan'] = $imageCreateNewTemplatePlan;

        $replace['browser_title'] = "Tx Xchange: Provider Home";
        $this->output = $this->build_template($this->get_template("main"), $replace);
    }

    /**
     * This function opens up the DHTML popup prompting provider to activate his trial account.
     * @access public
     */
    function trialperiodprompt() {
        $replace['body'] = $this->build_template($this->get_template("freetrialperiod"), $replace);
        $this->output = $this->build_template($this->get_template("main"), $replace);
    }

    /**
     * This function checks duplicate value in the table.
     *
     * @param string $table
     * @param string $field
     * @param mixed $value
     * @param string $where
     * @return bolean
     * @access public
     */
    function duplicate_check($table, $field, $value, $where = "") {
        if (trim($where) != "") {
            $query = " select count(*) from $table where $field = '" . $value . "' and $where";
        } else {
            $query = " select count(*) from $table where $field = '" . $value . "' ";
        }
        //die;
        $result = @mysql_query($query);
        if ($row = @mysql_fetch_array($result)) {
            if ($row[0] > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * This function lists Patient.
     * @access public
     */
    function myPatients() {

        $replace = array();

        $replace['header'] = $this->build_template($this->get_template("header"));

        $replace['footer'] = $this->build_template($this->get_template("footer"));

        $replace['sidebar'] = $this->sidebar();

        $replace['get_satisfaction'] = $this->get_satisfaction();


        include_once("template/therapist/therapistArray.php");

        $this->formArray = $patientListAction;
        $privateKey = $this->config['private_key'];
        if ($this->value('sort') != "") {
            if ($this->value('order') == 'desc') {
                if ($this->value('sort') == 'name_first') {
                    $orderby = " order by CAST(AES_DECRYPT(UNHEX(us.name_last),'{$privateKey}') as char) desc ";
                }
                else
                    $orderby = " order by {$this->value('sort')} desc ";
            }

            else {
                if ($this->value('sort') == 'name_first') {
                    $orderby = " order by CAST(AES_DECRYPT(UNHEX(us.name_last),'{$privateKey}') as char) ";
                }
                else
                    $orderby = " order by {$this->value('sort')} ";
            }
        }
        else {
            $orderby = " order by CAST(AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as char) ";
        }



        $replace['search'] = "";

        if ($this->value('search') == "" && $this->value('restore_search') != "") {

            $_REQUEST['search'] = $this->value('restore_search');
        }



        if ($this->value('search') != "") {

            $replace['search'] = $this->value('search');
            $privateKey = $this->config['private_key'];
            $query = "select us.*,
                      AES_DECRYPT(UNHEX(us.name_title),'{$privateKey}') as name_title, 
                      AES_DECRYPT(UNHEX(us.name_first),'{$privateKey}') as name_first,
                      AES_DECRYPT(UNHEX(us.name_last),'{$privateKey}') as name_last 
                      ,ps.subscription_title as subscription_title 
                      from therapist_patient as thp 
			          inner join user as us on thp.patient_id = us.user_id 
			          and us.usertype_id='{$this->config['user_type_num']['patient']}' and us.status != '3'
			          and ( CAST(AES_DECRYPT(UNHEX(us.name_first),'{$privateKey}') as CHAR) like '%{$this->value('search')}%' 
                      or CAST(AES_DECRYPT(UNHEX(us.name_last),'{$privateKey}') as CHAR) like '%{$this->value('search')}%') 
			          inner join clinic_user as cu on thp.patient_id = cu.user_id
			          inner join clinic as cln on cln.clinic_id = cu.clinic_id and cln.status = '1' 
			          left join patient_subscription as ps on us.user_id=ps.user_id and ((ps.subs_status='1'  and ps.paymentType='0') or (ps.subs_status='2' and subs_end_datetime > now()) or (ps.subs_status='1'  and ps.paymentType='1' and subs_end_datetime > now())) 
			          where thp.therapist_id = '{$this->userInfo('user_id')}' ";
        } else {
            $privateKey = $this->config['private_key'];
            $query = "select us.*, 
                      AES_DECRYPT(UNHEX(us.name_title),'{$privateKey}') as name_title, 
                      AES_DECRYPT(UNHEX(us.name_first),'{$privateKey}') as name_first,
                      AES_DECRYPT(UNHEX(us.name_last),'{$privateKey}') as name_last
                       ,ps.subscription_title as subscription_title   
                      from therapist_patient as thp 
			          inner join user as us on thp.patient_id = us.user_id 
			          and us.usertype_id='{$this->config['user_type_num']['patient']}' 
			          and us.status != '3'
			          inner join clinic_user as cu on thp.patient_id = cu.user_id
			          inner join clinic as cln on cln.clinic_id = cu.clinic_id and cln.status = '1' 
			          left join patient_subscription as ps on us.user_id=ps.user_id and ((ps.subs_status='1' and ps.paymentType='0') or (ps.subs_status='2' and subs_end_datetime > now())  or (ps.subs_status='1'  and ps.paymentType='1' and subs_end_datetime > now()))
			          where thp.therapist_id = '{$this->userInfo('user_id')}' {$orderby} ";
        }
        //echo $query;
        $link = $this->pagination($rows = 0, $query, $this->value('action'), $this->value('search'));
        //echo "XXX";
        //print_r($result);
        //die();
        $replace['link'] = $link['nav'];

        $result = $link['result'];

        if (is_resource($result)) {
            // show records                   
            while ($row = $this->fetch_array($result)) {
                //echp"<pre>";
                //print_r($row['user_id']);
                $row['style'] = ($c++ % 2) ? "line1" : "line2";
                $row['last_login'] = $this->formatDate($row['last_login'], "m/d/Y") != "" ? $this->formatDate($row['last_login'], "m/d/Y") : "Never";
                $row['status'] = $this->config['patientStatus'][$row['status']];
                $row['actionOption'] = $this->build_select_option($this->formArray);

                $user_id = $this->userInfo('user_id');
                // echo"<br/>";
                $clinic_id = $this->get_clinic_info($row['user_id'], 'clinic_id');
                $EHealthService = $this->getPatientSubscriptionDetails($row['user_id'], $clinic_id);
                if ($row['subscription_title'] != '') {
                    $row['EHealthService'] = $row['subscription_title'];
                } else {
                    $row['EHealthService'] = 'Not Subscribed';
                }

                $replace['patientRec'] .= $this->build_template($this->get_template("patientRec"), $row);
            }
            // no record found.
            if ($this->num_rows($result) == 0) {
                $replace['colspan'] = 5;
                $replace['patientRec'] = $this->build_template($this->get_template("no_record_found"), $replace);
            }
            $replace['patientHeading'] = $this->build_template($this->get_template("patientHeading"), $this->table_heading($patientHeading, "name_last"));

            $replace['body'] = $this->build_template($this->get_template("patientList"), $replace);
            $replace['browser_title'] = "Tx Xchange: My Patients";
            $this->output = $this->build_template($this->get_template("main"), $replace);
        } else {

            echo "Invalid resource";
        };
    }

    /**
     * This function check clinic erabh program.
     * @access public
     */
    function checkerabh() {
        //for show the program field
        $sql = "select * from addon_services where clinic_id=" . $this->get_clinic_info($this->userInfo('user_id'));
        $result_addon = $this->execute_query($sql);
        if ($this->num_rows($result_addon) == 0)
            return 'no';
        else {
            $rowaddon = $this->fetch_array($result_addon);
            if ($rowaddon['program'] == 1)
                return 'yes';
            else
                return 'no';
        }
    }

    /**
     * This function lists e-Maintenance Patient.
     * @access public
     */
    function eMaintenance() {
        $replace = array();
        $replace['header'] = $this->build_template($this->get_template("header"));
        $replace['footer'] = $this->build_template($this->get_template("footer"));
        $replace['sidebar'] = $this->sidebar();
        $replace['get_satisfaction'] = $this->get_satisfaction();
        include_once("template/therapist/therapistArray.php");
        $this->formArray = $patientListAction;
        if ($this->checkerabh() == 'yes') {

            if ($this->value('sort') != "") {
                if ($this->value('order') == 'desc') {
                    $orderby = " order by {$this->value('sort')} desc ";
                } else {
                    $orderby = " order by {$this->value('sort')} ";
                }
            } else {
                $orderby = " order by u.name_last ";
            }
            $privateKey = $this->config['private_key'];
            $query = "SELECT pu.p_type,u.user_id,u.username,
                      AES_DECRYPT(UNHEX(u.name_title),'{$privateKey}') as name_title, 
                      AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}') as name_first,
                      AES_DECRYPT(UNHEX(u.name_last),'{$privateKey}') as name_last, 
                      u.last_login, pu.p_type,pu.start_date  
                      FROM program_user pu
                      INNER JOIN therapist_patient as thp ON pu.u_id = thp.patient_id and thp.therapist_id = '{$this->userInfo('user_id')}'
                      INNER JOIN user u ON u.user_id = pu.u_id  {$orderby} ";


            $link = $this->pagination($rows = 0, $query, $this->value('action'), $this->value('search'));
            $replace['link'] = $link['nav'];
            $result = $link['result'];
            if (is_resource($result)) {
                // show records
                while ($row = $this->fetch_array($result)) {
                    $row['style'] = ($c++ % 2) ? "line1" : "line2";
                    $row['last_login'] = $this->formatDate($row['last_login']) != "" ? $this->formatDate($row['last_login']) : "Never";
                    $row['plan_type'] = $this->get_field($row['p_type'], "program", "p_name");
                    $row['start_date'] = $this->formatDate($row['start_date'], "m/d/Y");
                    $replace['eMaintenanceRec'] .= $this->build_template($this->get_template("eMaintenanceRec"), $row);
                }
                // no record found.
                if ($this->num_rows($result) == 0) {
                    $replace['colspan'] = 5;
                    $replace['eMaintenanceRec'] = $this->build_template($this->get_template("no_record_found"), $replace);
                }
                $replace['eMaintenanceHeading'] = $this->build_template($this->get_template("eMaintenanceHeading"), $this->table_heading($eMaintenanceHeading, "name_last"));

                $replace['body'] = $this->build_template($this->get_template("eMaintenanceList"), $replace);
                $replace['browser_title'] = "Tx Xchange: e-Rehab Patients";
                $this->output = $this->build_template($this->get_template("main"), $replace);
            } else {

                echo "Invalid resource";
            };
        } else {
            $replace['colspan'] = 5;
            $replace['eMaintenanceRec'] = $this->build_template($this->get_template("no_record_found"), $replace);
            $replace['eMaintenanceHeading'] = $this->build_template($this->get_template("eMaintenanceHeading"), $this->table_heading($eMaintenanceHeading, "name_last"));

            $replace['body'] = $this->build_template($this->get_template("eMaintenanceList"), $replace);
            $replace['browser_title'] = "Tx Xchange: e-Rehab Patients";
            $this->output = $this->build_template($this->get_template("main"), $replace);
        }
    }

    /**
     * This function creates Patients.
     * @access public
     */
    function therapistCreatePatient() {
        include_once("template/therapist/therapistArray.php");

        // populate the form array.
        $this->formArray = $formArray;
        $replace = $this->fillForm($this->formArray);
        $replace['prefixOption'] = $this->build_select_option($this->config['title']);
        $replace['suffixOption'] = $this->build_select_option($this->config['suffix']);
        $replace['statusOption'] = $this->build_select_option($this->config['patientStatus'], $this->formArray['status']);
        $countryArray = $this->config['country'];
        $replace['country'] = implode("','", $countryArray);

        $replace['patient_country_options'] = $this->build_select_option($countryArray, $row['country']);
        $replace['genderOption'] = $this->build_select_option($this->config['gender'], $replace['gender']);
        $replace['get_satisfaction'] = $this->get_satisfaction();
        $stateArray = array("" => "Choose State...");
        $stateArray = array_merge($stateArray, $this->config['state']);
        $replace['dob'] ='';
        //Check for the E-health service
        $clinicId = $this->clinicInfo("clinic_id");
        if ($this->is_corporate($clinicId) == 1) {
            $replace['corporate'] = 'none';
        } else {
            $replace['corporate'] = 'block';
        }

        if ($clinicId == EHSCLINICID || EHSCLINICID == '') {
            $replace['SHOWEHS'] = "display:block";
        } else {
            $replace['SHOWEHS'] = "display:none";
        }
        if (!empty($clinicId) && $clinicId > 0) {
            $sqlEhealth = "SELECT subs_status FROM clinic_subscription WHERE subs_clinic_id  = " . $clinicId;
            $queryEhealth = $this->execute_query($sqlEhealth);
            $numEhealthRow = $this->num_rows($queryEhealth);
            if ($numEhealthRow != '0') {
                $valueEhealth = $this->fetch_object($queryEhealth);
                if ($valueEhealth->subs_status == 0) {
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

        //End here

        $replace['stateOption'] = $this->build_select_option($stateArray);
        //$replace['stateOption'] = $this->build_select_option($this->config['state']);
        $replace['header'] = $this->build_template($this->get_template("header"));
        $replace['footer'] = $this->build_template($this->get_template("footer"));
        $replace['sidebar'] = $this->sidebar();
        $replace['body'] = $this->build_template($this->get_template("createPatient"), $replace);
        $replace['browser_title'] = "Tx Xchange: Create Patient";
        $this->output = $this->build_template($this->get_template("main"), $replace);
    }

    /**
     * This function edits Patient(s) profile.
     * @access public
     */
    function therapistEditPatient() {
        if ($this->value('id') != "" && $this->value('plan_id') != "") {
            if ($this->value('act') == 'current') {
                $query = "update plan set status = 1 where plan_id = '{$this->value('plan_id')}' ";
            }
            if ($this->value('act') == 'deletePlan') {
                $query = "update plan set status = 3 where plan_id = '{$this->value('plan_id')}' ";
            }
            if ($this->value('act') == 'archive') {
                $query = "update plan set status = 2 where plan_id = '{$this->value('plan_id')}' ";
            }
            if ($this->value('act') == 'editPlan') {

                // Update user's modified field.
                $data = array(
                    'modified' => date('Y-m-d H:i:s')
                );
                $where = " user_id = '{$this->value('id')}' ";
                $this->update($this->config['table']['user'], $data, $where);

                header("location:index.php?action=createNewPlan&act=plan_edit&type=finish&plan_id={$this->value('plan_id')}&patient_id={$this->value('id')}");
                exit(0);
            }

            if (isset($query) && $query != "") {

                // Update user's modified field.
                $data = array(
                    'modified' => date('Y-m-d H:i:s')
                );
                $where = " user_id = '{$this->value('id')}' ";
                $this->update($this->config['table']['user'], $data, $where);

                $result = $this->execute_query($query);
            }
        }
        $replace = array();
        include_once("template/therapist/therapistArray.php");
        if ($this->value('id')) {
            $where = " user_id = " . $this->value('id');
            $row = $this->table_record($this->config['table']['user'], $where);
            $encrypt_field[] = 'name_title';
            $encrypt_field[] = 'name_first';
            $encrypt_field[] = 'name_last';
            $row = $this->decrypt_field($row, $encrypt_field);
            $this->formArray = $formArray;

            if (is_array($row)) {
                if ($this->value('subaction') == "update") {
                    $replace = $this->fillForm($this->formArray, true);
                } else {
                    $replace = $this->fillForm($this->formArray, $row);
                    $replace['email_address'] = $row['username'];
                }

                $replace['id'] = $this->value('id');


                $replace['full_name'] = $this->fullName('', $row['name_first'], $row['name_last']);
                $replace['patient_name'] = $row['name_first'] . " " . $row['name_last'];
            }
            $replace['dob'] = $row['dob'];
            $replace['new_password'] = $this->value('new_password');
            $replace['confirm_password'] = $this->value('confirm_password');

            if (!isset($replace['new_password']) || empty($replace['new_password'])) {
                $replace['new_password'] = $this->userInfo('password', $this->value('id'));
            }
            if (!isset($replace['confirm_password']) || empty($replace['confirm_password'])) {
                $replace['confirm_password'] = $this->userInfo('password', $this->value('id'));
            }
        }

        if ($this->value('subaction') == "update" && trim($this->value('id')) != "") {



            $replace['error'] = $this->validateEditPatientForm();

            if ($replace['error'] == "") {

                //execute Update query

                $this->formArray = $tableFieldArray;

                $tableArray = ($this->fillTableArray($this->formArray, true));
                //$tableArray = $this->encrypt_field($tableArray,$encrypt_field);
                $where = " user_id = '" . $this->value('id') . "' and status != 3 ";

                if (trim($tableArray['password']) == "") {
                    unset($tableArray['password']);
                }
                //E health service
                /* if($this->value('ehsEnable') == 1)
                  $tableArray['ehs'] = '1';
                  else
                  $tableArray['ehs'] = '0'; */

                //End here

                if ($this->update($this->config['table']['user'], $tableArray, $where)) {
                    $replace['error'] = "successfull update of record";
                    //update program_user table.
                    $program_user_data = array(
                        'p_type' => $program['cash-based-program'],
                        'u_id' => $this->value('id'),
                        'p_status' => '1',
                        'start_date' => $this->CURRENT_TIMESTAMP()
                    );
                    if ($this->value('program') == '1') {
                        $user_id = $this->value('id');
                        $p_type = $program['cash-based-program'];
                        $sql = "select * from program_user where u_id = '{$user_id}' and p_type = '{$p_type}' ";
                        $result = @mysql_query($sql);
                        if (!(@mysql_num_rows($result) > 0)) {
                            $this->insert("program_user", $program_user_data);
                            $user_data = array(
                                'last_login' => ""
                            );
                            $where = " user_id = '{$user_id}' ";
                            $this->update("user", $user_data, $where);
                        }
                    } else {
                        $user_id = $this->value('id');
                        $p_type = $program['cash-based-program'];
                        $where = " u_id = '{$user_id}' and p_type = '{$p_type}' ";
                        $this->db_delete("program_user", $where);
                    }

                    // Update user's modified field.
                    $data = array(
                        'modified' => date('Y-m-d H:i:s')
                    );
                    $where = " user_id = '{$this->value('id')}' ";
                    $this->update($this->config['table']['user'], $data, $where);
                    header("location:index.php?action=therapistViewPatient&id=" . $this->value('id'));
                    exit();
                }
            }
            if ($this->value('program') == '1') {
                $checked = "checked";
            } else {
                $checked = "";
            }
            $replace['checked'] = $checked;
        } else {
            $user_id = $this->value('id');
            $p_type = $program['cash-based-program'];
            $sql = "select * from program_user where u_id = '{$user_id}' and p_type = '{$p_type}' ";
            $result = @mysql_query($sql);
            if (@mysql_num_rows($result) > 0) {
                $checked = "checked";
            } else {
                $checked = "";
            }
            $replace['checked'] = $checked;
        }
        //$replace['display']='inline'; // comment by htp for donot show program eraa
        //for show the program field
        /* $sql="select * from addon_services where clinic_id=".$this->get_clinic_info($this->value('id'));
          $result_addon=$this->execute_query($sql);
          if($this->num_rows($result_addon)==0)
          $replace['display']='none';
          else{
          $rowaddon=$this->fetch_array($result_addon);
          if($rowaddon['program']==1)
          $replace['display']='inline';
          else


          }

         */ $replace['display'] = 'none'; //remove if uncomment upper block          
        // show all the plans associated with patient.
        $replace['patientId'] = $this->value('id');
        $replace['patient_name_id'] = $this->value('id');
        if ($this->value('dob') == '')
            $replace['dob'] = $row['dob'];
        else
            $replace['dob'] = $this->value('dob');
        // For calender dates
        $replace['selected_dates'] = $this->selected_dates($this->value('id'));
        // End
        $replace['prefixOption'] = $this->build_select_option($this->config['title'], $replace['name_title']);

        $replace['suffixOption'] = $this->build_select_option($this->config['suffix'], $replace['name_suffix']);

        $replace['statusOption'] = $this->build_select_option($this->config['patientStatus'], $replace['status']);

        $replace['genderOption'] = $this->build_select_option($this->config['gender'], $replace['gender']);

        $countryArray = $this->config['country'];
        $replace['country'] = implode("','", $countryArray);
        $replace['patient_country_options'] = $this->build_select_option($countryArray, $row['country']);
        //    
        if ($row['country'] == 'US') {
            $stateArray = array("" => "Choose State...");
            $stateArray = array_merge($stateArray, $this->config['state']);
            $replace['stateOption'] = $this->build_select_option($stateArray, $row['state']);
        } else if ($row['country'] == 'CAN') {
            $stateArray = array("" => "Choose Provinces...");
            $stateArray = array_merge($stateArray, $this->config['canada_state']);
            $replace['stateOption'] = $this->build_select_option($stateArray, $row['state']);
        } else {
            $stateArray = array("" => "Choose State...");
            $stateArray = array_merge($stateArray, $this->config['state']);
            $replace['stateOption'] = $this->build_select_option($stateArray, $row['state']);
        }





        //$replace['stateOption'] = $this->build_select_option($this->config['state'],$replace['state']);

        $replace['creation_date'] = $this->formatDate($this->get_field($this->value('id'), "user", "creation_date"));


        $replace['header'] = $this->build_template($this->get_template("header"));

        $replace['footer'] = $this->build_template($this->get_template("footer"));

        $replace['sidebar'] = $this->sidebar();

        $replace['get_satisfaction'] = $this->get_satisfaction();



        $replace['body'] = $this->build_template($this->get_template("editPatient"), $replace);
        $replace['browser_title'] = "Tx Xchange: Edit Patient";

        $clinicId = $this->get_clinic_info($this->value('id'), 'clinic_id');
        /*
          @date: 13th october 2011
          @description:
          Check for the E-health service
          Functionality added on 13th october as per UC 2.7.4
          It will check the E health service turn /off condition. If the E health service is off then checkbox will become disabled.
          If the E- health service is enabled then it will check the patient subscription. If the patient is subscribed then it will be in disable mode
          else in enable mode.
         */
        /* $sqlEhealth = "SELECT subs_status FROM clinic_subscription WHERE subs_clinic_id  = " . $clinicId;
          $queryEhealth = $this->execute_query($sqlEhealth);
          $numEhealthRow = $this->num_rows($queryEhealth);
          if($numEhealthRow!= '0') {
          $valueEhealth = $this->fetch_object($queryEhealth);
          if($valueEhealth->subs_status == 0) {
          $replace['ehsEnable'] = 'disabled';
          } else {
          $sqlPaymentSubscription = "SELECT subs_status FROM patient_subscription where user_id = {$this->value('id')}";
          $querysubscription = $this->execute_query($sqlPaymentSubscription);
          $numquerysubscription = $this->num_rows($querysubscription);
          if($numquerysubscription!= '0') {
          $valuesubscription = $this->fetch_object($querysubscription);
          if($valueEhS->subs_status == 2) {
          $replace['ehsEnable'] = 'disabled';
          } else {
          $replace['ehsEnable'] = '';

          }
          } else {
          $replace['ehsEnable'] = '';

          }

          }
          } else {
          $replace['ehsEnable'] = 'disabled';

          } */

        //End here  

        /* $sqlEhealth = "SELECT subs_status FROM clinic_subscription WHERE subs_clinic_id  = " . $clinicId;
          $queryEhealth = $this->execute_query($sqlEhealth);
          $numEhealthRow = $this->num_rows($queryEhealth);
          if($numEhealthRow!= '0') {
          $valueEhealth = $this->fetch_object($queryEhealth);
          if($valueEhealth->subs_status == 0) {
          $replace['ehsEnable'] = 'disabled';
          } else {
          //Condition to check patient EHS service

          $sqEhscheck = "SELECT ehs FROM user where user_id = {$this->value('id')}";
          $queryEhs= $this->execute_query($sqEhscheck);
          $numqueryEhs = $this->num_rows($queryEhs);
          if($numqueryEhs!= '0') {
          $valueehsService = $this->fetch_object($queryEhs);
          if($valueehsService->ehs == 0 || $valueehsService->ehs == '') {
          $replace['ehsEnable'] = '';
          $replace['subs_status'] = '0';

          } else {
          $sqlPaymentSubscription = "SELECT subs_status,subscription_title, user_subs_id FROM patient_subscription where user_id = {$this->value('id')}";
          $querysubscription = $this->execute_query($sqlPaymentSubscription);
          $numquerysubscription = $this->num_rows($querysubscription);
          if($numquerysubscription!= '0') {

          $valuesubscription = $this->fetch_object($querysubscription);

          $replace['subs_status'] = $valuesubscription->subs_status;
          $replace['subscrp_id'] = $valuesubscription->user_subs_id;

          } else {
          $replace['subs_status'] = '0';

          }

          $replace['ehsEnable'] = 'checked';

          }


          }



          }
          } else {
          $replace['ehsEnable'] = 'disabled';

          } */




        $this->output = $this->build_template($this->get_template("main"), $replace);
    }

    /**
     * This function calls validatePatientForm function to valid submitted form data of patient and inserts it to database.
     * @access public
     *
     */
    function therapistSubmitPatient() {

        include_once("template/therapist/therapistArray.php");
        $this->formArray = $formArray;
        $replace = $this->fillForm($this->formArray, true);
        $replace['genderOption'] = $this->build_select_option($this->config['gender'], $this->value('gender'));
        $replace['error'] = $this->validatePatientForm();
        //Check for the E-health service
        $clinicId = $this->clinicInfo("clinic_id");
        if (!empty($clinicId) && $clinicId > 0) {
            $sqlEhealth = "SELECT subs_status FROM clinic_subscription WHERE subs_clinic_id  = " . $clinicId;
            $queryEhealth = $this->execute_query($sqlEhealth);
            $numEhealthRow = $this->num_rows($queryEhealth);
            if ($numEhealthRow != '0') {
                $valueEhealth = $this->fetch_object($queryEhealth);
                if ($valueEhealth->subs_status == 0) {
                    $replace['ehsEnable'] = 'disabled';
                    $replace['ehsDisable'] = '0';
                } else {
                    $replace['ehsEnable'] = 'checked';
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

        //End here	
        if ($replace['error'] == "") {
            //execute insert query
            $this->formArray = $tableFieldArray;
            $tableArray = ($this->fillTableArray($this->formArray));
            $tableArray['usertype_id'] = 1;
            $tableArray['created_by'] = $this->userInfo('user_id');
            $tableArray['creation_date'] = date('Y-m-d H:i:s');
            $tableArray['modified'] = date('Y-m-d H:i:s');
            $tableArray['mass_message_access'] = 1;
            $tableArray['country'] = $this->value('clinic_country');
            $tableArray['password'] = $this->value('name_last') . '01';
            
            
            //Modified on 13th oct for E-health service                                
            /* if($this->value('ehsEnable') == 1)
              $tableArray['ehs'] = '1';
              else
              $tableArray['ehs'] = '0'; */
            //$tableArray['ehs'] = '1';
            //End here//2nd november
            //print_r($_REQUEST);

            if ($_REQUEST['ehsDisable'] == "0") {
                $tableArray['ehs'] = '1';
            } elseif ($_REQUEST['ehsEnable'] == "1") {
                $tableArray['ehs'] = '1';
            } else {
                $tableArray['ehs'] = '0';
            }
            //print_r($tableArray);exit;
            //$tableArray = $this->encrypt_field($tableArray, $encrypt_field);
            if ($this->insert($this->config['table']['user'], $tableArray)) {
                $data = array(
                    'therapist_id' => $this->userInfo('user_id'),
                    'patient_id' => $this->insert_id(),
                    'creation_date' => date('Y-m-d H:i:s'),
                    'modified' => date('Y-m-d H:i:s'),
                    'status' => '1'
                );
                $new_patient_id = $data['patient_id'];
                if ($this->insert($this->config['table']['therapist_patient'], $data)) {
                    // Modified on 29-Nov-07
                    $data = array(
                        'clinic_id' => $this->clinicInfo('clinic_id'),
                        'user_id' => $new_patient_id,
                    );
                    // Insert row in clinic_user table to set relation between clinic and patient				 
                    if ($this->insert($this->config['table']['clinic_user'], $data)) {
                        $replace['error'] = "successfull entry of record";
                        // mail to new patient contains username and password.
                        //have the HTML content 
                        $clinicName = html_entity_decode($this->get_clinic_info($new_patient_id, 'clinic_name'), ENT_QUOTES, "UTF-8");
                        $clinic_channel = $this->getchannel($this->clinicInfo('clinic_id'));

                        if ($clinic_channel == 1) {
                            $business_url = $this->config['business_tx'];
                            $support_email = $this->config['email_tx'];
                        } else {
                            $business_url = $this->config['business_wx'];
                            $support_email = $this->config['email_wx'];
                        }
                        $data = array(
                            'username' => $this->userInfo("username", $new_patient_id),
                            'password' => $this->userInfo("password", $new_patient_id),
                            'url' => $this->config['url'],
                            'images_url' => $this->config['images_url'],
                            'clinic_name' => $clinicName,
                            'business_url' => $business_url,
                            'support_email' => $support_email,
                            'name' => $this->userInfo("name_first", $new_patient_id)
                        );

                        //$user_id = $this->userInfo('user_id');
                        $clinic_type = $this->getUserClinicType($new_patient_id);

                        if ($clinic_channel == '2') {
                            $message = $this->build_template("mail_content/wx/create_new_patient_mail_plpto.php", $data);
                        } else {
                            $message = $this->build_template("mail_content/plpto/create_new_patient_mail_plpto.php", $data);
                        }

                        /* elseif( $clinic_type == 'elpto' ){		
                          $company = $this->get_field($new_patient_id,'user','company');
                          if( !empty($company) && is_string($company) ){
                          $message = $this->build_template("mail_content/create_new_employee.php",$data);
                          }
                          else{
                          $message = $this->build_template("mail_content/create_new_patient_mail.php",$data);
                          }
                          } */
                        $to = $this->userInfo("username", $new_patient_id);

                        $subject = "Your " . $clinicName . " Health Record";
                        // To send HTML mail, the Content-type header must be set
                        $headers = 'MIME-Version: 1.0' . "\n";
                        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";

                        // Additional headers
                        //$headers .= 'To: '.$fullName.'<'.$_POST['email_address'].'>'. "\n";
                        //$headers .= "From: " . $this->config['from_email_address'] . "\n";
                        //$headers .= "From: ".$clinicName. " <support@txxchange.com>" . "\n";
                        //$returnpath = '-fsupport@txxchange.com';
                        if ($clinic_channel == 1) {
                            $headers .= "From: " . $this->setmailheader($clinicName) . " <" . $this->config['email_tx'] . ">" . "\n";
                            $returnpath = "-f" . $this->config['email_tx'];
                        } else {
                            $headers .= "From: " . $this->setmailheader($clinicName) . " <" . $this->config['email_wx'] . ">" . "\n";
                            $returnpath = '-f' . $this->config['email_wx'];
                        }
                        // Mail it
                        mail($to, $subject, $message, $headers, $returnpath);

                        /*
                          Edited by Manoj on 28-Nov-07
                          Purpose -- Same page is populated for Therapist and Account Admin, so when new patient is
                          created by therapist it return to therapist page while if account admin creates a
                          patient it will redirect to the account admin patient listing page.
                         */

                        header("location:index.php?action=myPatients");
                        exit();
                    }
                }
            }
        }


        if (isset($_REQUEST['clinic_country']) && $_REQUEST['clinic_country'] != '') {

            $countryArray = $this->config['country'];
            $replace['country'] = implode("','", $countryArray);
            $replace['patient_country_options'] = $this->build_select_option($countryArray, $_REQUEST['clinic_country']);


            if ($_REQUEST['clinic_country'] == 'US') {
                $stateArray = array("" => "Choose State...");
                $stateArray = array_merge($stateArray, $this->config['state']);
                $replace['stateOption'] = $this->build_select_option($stateArray, $_REQUEST['state']);
            } else if ($_REQUEST['clinic_country'] == 'CAN') {
                $stateArray = array("" => "Choose Province...");
                $stateArray = array_merge($stateArray, $this->config['canada_state']);
                $replace['stateOption'] = $this->build_select_option($stateArray, $_REQUEST['state']);
            }
        }
        $replace['prefixOption'] = $this->build_select_option($this->config['title'], $replace['name_title']);
        $replace['suffixOption'] = $this->build_select_option($this->config['suffix'], $replace['name_suffix']);
        $replace['statusOption'] = $this->build_select_option($this->config['patientStatus'], $replace['status']);

        $replace['header'] = $this->build_template($this->get_template("header"));
        $replace['footer'] = $this->build_template($this->get_template("footer"));
        $replace['sidebar'] = $this->sidebar();

        // Edited on 28-Nov-07
        $replace['actionActivateFrom'] = $this->value('actionActivateFrom');
        $replace['body'] = $this->build_template($this->get_template("createPatient"), $replace);
        $replace['browser_title'] = "Tx Xchange: Edit Patient";
        $this->output = $this->build_template($this->get_template("main"), $replace);
    }

    /**
     * This function validates Patient(s) form data.
     * 
     * @return string
     * @access public
     */
    function validatePatientForm() {
        $error = array();
        // Checks email address entered or not.			
        if (trim($this->value('email_address')) == "") {
            $error[] = "Please Enter Your Email Address.";
        }
        // Checks for valid email address.
        elseif (!$this->check_email($this->value('email_address'))) {
            $error[] = "Invalid Email Address.";
        }
        // Checks for length of password. Must be min 6 character.
        /* elseif(strlen($this->value('new_password')) < '6' ){
          $error[] = "Password must be minimum of 6 character.";
          } */
        // Checks for duplicate value.
        elseif ($this->duplicate_check($this->config['table']['user'], "username", trim($this->value('email_address')), "status != 3")) {
            $error[] = "Your Email Address Already Exist.";
        }
        // Checks for first name entered or not.
        if (trim($this->value('name_first')) == "") {
            $error[] = "Please Enter Your First Name.";
        }
        // Checks for last name entered or not.
        if (trim($this->value('name_last')) == "") {
            $error[] = "Please Enter Your Last Name.";
        }
        if ($this->value('dob') != '') {
            $date_regex = '/(0[1-9]|1[012])[\/](0[1-9]|[12][0-9]|3[01])[\/]((?:19|20)\d\d)/';
            if (!preg_match($date_regex, $this->value('dob'))) {
                $error[] = 'Please insert date in MM/DD/YYYY format';
            }
        }
        // Validates zip code.
        /* if($this->value('zip') != ""){
          if($this->value('clinic_country')=='CAN'){
          $str_pattern='/[^A-Za-z0-9 ]/';
          $var=1;
          if(strlen($this->value('zip')) < 6 || strlen($this->value('zip')) > 7){
          $error[] = "Zip code should be  6 to 7 alphanumeric characters only.";
          $var=2;
          }
          if(preg_match($str_pattern,$this->value('zip')) and $var==1){
          $error[] = "Zip code should be of alphanumeric characters only.";
          }
          }else{
          $str_pattern='/[^0-9]/';
          $var=1;
          if(strlen($this->value('zip')) != 5){
          $var=2;
          $error[] = "Zip code should be  5 numeric characters only";
          }
          if(preg_match($str_pattern,$this->value('zip')) and $var==1){

          $error[] = "Zip code should be numeric characters only";
          }

          }
          } */
        if (count($error) > 0) {
            $error = $this->show_error($error);
        } else {
            $error = "";
        }
        return $error;
    }

    /**
     * Validates Patient form when edited.
     *
     * @return string
     * @access public
     */
    function validateEditPatientForm() {

        $error = array();
        // Checks email address entered or not.	
        if (trim($this->value('email_address')) == "") {
            $error[] = "Please Enter Your Email Address.";
        }
        // Checks for valid email address.
        elseif (!$this->check_email($this->value('email_address'))) {
            $error[] = "Invalid Email Address.";
        }
        // Checks for duplicate value.
        elseif ($this->duplicate_check($this->config['table']['user'], "username", trim($this->value('email_address')), " user_id != {$this->value('id')} and status = 1 ")) {
            $error[] = "Your Email Address Already Exist.";
        }
        // Checks for password, min 6 character should be there.
        if (trim($this->value('new_password')) != "") {
            if (trim($this->value('new_password')) != "" && strlen($this->value('new_password')) < '6') {
                $error[] = "Password must be minimum of 6 character.";
            } elseif (trim($this->value('new_password')) != trim($this->value('confirm_password'))) {
                $error[] = "New password must be same as Confirm password.";
            }
        }
        // Checks for first name entered or not.
        if (trim($this->value('name_first')) == "") {
            $error[] = "Please Enter Your First Name.";
        }
        // Checks for last name entered or not.
        if (trim($this->value('name_last')) == "") {
            $error[] = "Please Enter Your Last Name.";
        }
        if ($this->value('dob') != '') {
            $date_regex = '/(0[1-9]|1[012])[\/](0[1-9]|[12][0-9]|3[01])[\/]((?:19|20)\d\d)/';
            if (!preg_match($date_regex, $this->value('dob'))) {
                $error[] = 'Please insert date in MM/DD/YYYY format';
            }
        }
        // Checks zip code, must be 5 char(s) only.
        /* if($this->value('zip') != ""){
          if($this->value('clinic_country')=='CAN'){
          $str_pattern='/[^A-Za-z0-9 ]/';
          $var=1;
          if(strlen($this->value('zip')) < 6 || strlen($this->value('zip')) > 7){
          $error[] = "Zip code should be  6 to 7 alphanumeric characters only.";
          $var=2;
          }
          if(preg_match($str_pattern,$this->value('zip')) and $var==1){
          $error[] = "Zip code should be of alphanumeric characters only.";
          }
          }else{
          $str_pattern='/[^0-9]/';
          $var=1;
          if(strlen($this->value('zip')) != 5){
          $var=2;
          $error[] = "Zip code should be  5 numeric characters only";
          }
          if(preg_match($str_pattern,$this->value('zip')) and $var==1){

          $error[] = "Zip code should be numeric characters only";
          }

          }
          } */
        if (count($error) > 0) {
            $error = $this->show_error($error);
        } else {
            $error = "";
        }
        return $error;
    }

    /**
     * This function shows Patient(s) profile with details of his/her 
     * Treatment plans, Therapist associated, reminders etc.
     * 
     * @access public
     */
    function therapistViewPatient() {
        //print_r($_SESSION);
        $this->clear_temp_article();
        $replace = array();
        /*
         * Code for checking the Viedo conffrence provider and patient status
         */
        $provider_id = $this->userInfo('user_id');
        $patient_id = $this->value('id');
        $call_status = $this->check_call_status($this->userInfo('user_id'), $provoderlist[$i]['id']);
        $replace['call_status'] = $call_status;


        unset($_SESSION['TempArticle']);
        if ($this->value('id') != "" && $this->value('plan_id') != "") {
            if ($this->value('act') == 'current') {
                $query = "update plan set status = '1' where plan_id = '{$this->value('plan_id')}' and status != '3' ";
            }
            if ($this->value('act') == 'deletePlan') {
                $query = "update plan set status = '3' where plan_id = '{$this->value('plan_id')}'  ";
            }
            if ($this->value('act') == 'archive') {
                $query = "update plan set status = '2' where plan_id = '{$this->value('plan_id')}' and status != '3' ";
            }
            if ($this->value('act') == 'editPlan') {
                header("location:index.php?action=createNewPlan&act=plan_edit&type=finish&plan_id={$this->value('plan_id')}&patient_id={$this->value('id')}");
                exit(0);
            }
            if ($this->value('act') == 'copyPlan') {
                header("location:index.php?action=copyExistingPlan&act=plan_edit&type=finish&plan_id={$this->value('plan_id')}&patient_id={$this->value('id')}");
                exit(0);
            }


            if (isset($query) && $query != "") {
                $result = $this->execute_query($query);
            }
        }


        include_once("template/therapist/therapistArray.php");

        $therapistClinicId = $this->get_clinic_info($this->userInfo('user_id'));

        $providerEhealthStatus = $this->getProviderEhealthStatus($therapistClinicId);


        if ($this->value('id')) {

            $where = " user_id = " . $this->value('id');

            $row = $this->table_record($this->config['table']['user'], $where);

            $this->formArray = $formArray;

            if (is_array($row)) {

                $replace = $this->fillForm($this->formArray, $row);

                $imageCreateNewTemplatePlan = $_SESSION['providerLabel']['images/createNewTemplatePlan.gif'] != '' ? $_SESSION['providerLabel']['images/createNewTemplatePlan.gif'] : "images/createNewTemplatePlan.gif";
                $imageAssignHeathTemplate = $_SESSION['providerLabel']['images/assignTemplatePlan.gif'] != '' ? $_SESSION['providerLabel']['images/assignTemplatePlan.gif'] : "images/assignTemplatePlan.gif";
                $replace['user_id'] = $this->value('id');

                if ($call_status == 0) {
                    $replace['navigation_image_view_patient'] = '
						<a href="index.php?action=createNewPlan&patient_id=<!id>&type=finish"><img src="' . $imageCreateNewTemplatePlan . '" width="127" height="81" alt="Create New Treatment Plan"></a></td><td>
						<a href="index.php?action=therapistPlan&patient_id=<!id>&path=my_patient"><img src="' . $imageAssignHeathTemplate . '" width="127" height="81" alt="Assign Plan"></a></td><td>
                        <a href="#" onclick="mypopup(<!id>);" ><img src="images/icon-video-conference.gif"  alt="Video Conference"></a>                     
                        ';
                } else {
                    $replace['navigation_image_view_patient'] = '
						<a href="index.php?action=createNewPlan&patient_id=<!id>&type=finish"><img src="' . $imageCreateNewTemplatePlan . '" width="127" height="81" alt="Create New Treatment Plan"></a></td><td>
						<a href="index.php?action=therapistPlan&patient_id=<!id>&path=my_patient"><img src="' . $imageAssignHeathTemplate . '" width="127" height="81" alt="Assign Plan"></a></td><td>
                        <a href="#"  ><img src="images/busy.png"  alt="Patient is Busy"></a>                     
                        ';
                }

                //print_r($row);
                if ($row['country'] == 'CAN')
                    $cntry = 'Canada';
                if ($row['country'] == 'US')
                    $cntry = 'United States';
                $replace['status'] = $this->config['patientStatus'][$row['status']];

                $replace['full_name'] = $this->fullName('', $this->decrypt_data($row['name_first']), $this->decrypt_data($row['name_last']));
                $replace['patient_name'] = $this->decrypt_data($row['name_first']) . " " . $this->decrypt_data($row['name_last']);
                $replace['state'] = $this->config['state'][$replace['state']];
                $replace['patient_address'] = trim($row['name_title']) != "" ? $this->decrypt_data($row['name_title']) . "&nbsp;" : "";
                $replace['patient_address'] .= trim($row['name_first']) != "" ? ucwords($this->decrypt_data($row['name_first'])) . "&nbsp;" : "";
                $replace['patient_address'] .= trim($row['name_last']) != "" ? ucwords($this->decrypt_data($row['name_last'])) : "";
                $replace['patient_address'] .= "<br>";
                $replace['patient_address'] .= trim($row['address']) != "" ? $this->decrypt_data($row['address']) . "<br>" : "";
                $replace['patient_address'] .= trim($row['address2']) != "" ? $this->decrypt_data($row['address2']) . "<br>" : "";
                $replace['patient_address'] .= trim($row['city']) != "" ? $this->decrypt_data($row['city']) . ',' . "&nbsp;" : "";
                $replace['patient_address'] .= trim($row['state']) != "" ? $this->decrypt_data($row['state']) . "&nbsp;" : "";
                $replace['patient_address'] .= trim($row['zip']) != "" ? $this->decrypt_data($row['zip']) . "<br>" : "";
                $replace['patient_address'] .= $cntry . "<br>";
                $replace['patient_address'] .= trim($row['phone1']) != "" ? "Phone 1:&nbsp;" . $this->decrypt_data($row['phone1']) . "<br>" : "";
                $replace['patient_address'] .= trim($row['phone2']) != "" ? "Phone 2:&nbsp;" . $this->decrypt_data($row['phone2']) . "<br>" : "";
                $replace['patient_address'] .= "<a href='mailto:{$row['username']}'>{$row['username']}</a>";
            }
        }

        /* Patient Id added by AJ */

        $replace['patientId'] = $this->value('id');
        $replace['id'] = $this->value('id');
        /* End */

        $replace['header'] = $this->build_template($this->get_template("header"));

        $replace['footer'] = $this->build_template($this->get_template("footer"));

        $replace['sidebar'] = $this->sidebar();

        # Added new valiable for counting replies. 13/02/2008
        $replies_count = 0;

        if ($this->value('id') != "" && is_numeric($this->value('id'))) {
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
					  WHERE m.patient_id IN (
						SELECT patient_id FROM therapist_patient WHERE therapist_id = '{$this->userInfo('user_id')}'
                     ) and m.parent_id = 0 and  m.mass_status = 0 and m.patient_id = '{$this->value('id')}' 
                     and m.message_id not in ( select message_id from system_message where user_id = '{$this->value('id')}' )
                     order by  sent_date desc LIMIT 0 , 5 ";

            ####################### Start for counting replies #########################
            $query11 = "SELECT m.patient_id,m.message_id, 
					  (	
					  	select count(*) from message_user mu
					  	where  mu.message_id = m.message_id 
					  	and mu.user_id = '{$this->userInfo('user_id')}' 
					  )
					  as replies
					  FROM message m
					  inner join user u on u.user_id = m.sender_id  
					  WHERE m.patient_id IN (
						SELECT patient_id FROM therapist_patient WHERE therapist_id = '{$this->userInfo('user_id')}'
                     ) and m.parent_id = 0 and m.patient_id = '{$this->value('id')}'";
            //echo $query11;
            $result11 = $this->execute_query($query11);
            //$row11 = @mysql_fetch_array($result11);

            while ($row11 = @mysql_fetch_array($result11)) {
                $replies_count += $row11['replies'] > 0 ? ($row11['replies'] - 1) : $row11['replies'];
            }
            ####################### End for counting replies #########################
            $replies_count = 0;
            $result = $this->execute_query($query);
            if ($this->num_rows($result) > 0) {
                while ($row = $this->fetch_array($result)) {
                    $data = array(
                        'style' => ($c++ % 2) ? "line1" : "line2",
                        'from_name' => "<a href='index.php?action=set_unread_message&message_id={$row['message_id']}'>" . $this->unread_message($row['message_id'], $row['from_name']) . "</a>",
                        'subject' => "<a href='index.php?action=set_unread_message&message_id={$row['message_id']}'>" . $this->unread_message($row['message_id'], $this->decrypt_data($row['subject'])) . "</a>",
                        'sent_date' => "<a href='index.php?action=set_unread_message&message_id={$row['message_id']}'>" . $this->unread_message($row['message_id'], $this->formatDateExtra($row['sent_date'], $this->userInfo('timezone'))) . "</a>",
                        'replies' => $row['replies']
                    );
                    $replies_count += $row['replies'];
                    $replace['recent_message_record'] .= $this->build_template($this->get_template("recent_message_record"), $data);
                }
            }
            $replace['recent_message_record'] .= '</table>';
        }

        // End of recent message list.

        $head_arr['replies'] = $replies_count;
        // Recent message of Patient.
        $replace['recent_message_head'] = $this->build_template($this->get_template("recent_message_head"), $head_arr);
        /*         * ***************************************** */
        // show all the articles associated with patient.
        $replace['viewPatientArticleRecordHeader'] = '
			<span style="font-size: 110%;color: #ed1f24;font-weight: bold;" >Assigned Articles</span>
			<table border="0" cellpadding="2" cellspacing="1" width="100%"  class="list" style="margin-top:5px;" >
			<thead>
			<tr>
			<th width="35%" class="nosort" style="white-space:nowrap;">Article Name</th>
			<th width="30%" class="nosort" style="white-space:nowrap;">Headline</th>
			<th width="8%" class="nosort" style="white-space:nowrap;text-align:right">&nbsp;</th>
			</tr>
			</thead>
			';
        $replace['viewPatientArticleRecord'] = "";
        if ($this->value('id') != "" && is_numeric($this->value('id'))) {

            $therapistIds = $this->getporviderlist($therapistClinicId); //print_r($therapistIds);
            /* $query = "SELECT *, P.patient_id as planPatientId
              FROM (
              SELECT AR.article_id as articleID, AR.article_name as article_name ,
              AR.headline as artcleHeadline,
              PAA.clinicId, PAA.patient_id as PAAPAtient_id, PAA.article_id as PAAarticleID
              FROM article AR
              LEFT JOIN patient_article PAA ON PAA.article_id = AR.article_id
              WHERE AR.user_id IN ({$therapistIds})
              ) AS RS

              LEFT JOIN plan_article PA ON RS.articleID = PA.article_id
              LEFT JOIN plan P ON P.plan_id = PA.plan_id AND P.status = '1'
              WHERE (P.patient_id ={$this->value('id')}
              OR RS.PAAPAtient_id ={$this->value('id')})
              GROUP BY RS.articleID";
             */
            $query = "(SELECT AR.article_id as articleID, NULL as plan_id,AR.article_name as article_name ,AR.headline as artcleHeadline,PAA.patient_id as patient_id,PAA.patientArticleId as patientArticleId
                                                FROM article AR
                                                LEFT JOIN patient_article PAA ON PAA.article_id = AR.article_id
                                                WHERE  AR.status='1' and PAA.patient_id = {$this->value('id')} ) UNION all
                                        (SELECT RS.article_id as articleID,P.plan_id as plan_id,RS.article_name as article_name,RS.headline as artcleHeadline,P.patient_id as patient_id, NULL as patientArticleId from article RS
                                        	LEFT JOIN plan_article PA ON RS.article_id = PA.article_id
                                        	LEFT JOIN plan P ON P.plan_id = PA.plan_id AND P.status = '1'
                                        	WHERE RS.status='1' and (P.patient_id ={$this->value('id')}))";
            $result = $this->execute_query($query);
            if ($this->num_rows($result) > 0) {
                while ($row = $this->fetch_array($result)) {
                    $article_id = $row['articleID'];
                    $data = array(
                        'article_id' => $article_id,
                        'article_name' => wordwrap($row['article_name'], 50, "<br/>", TRUE),
                        'headline' => wordwrap($row['artcleHeadline'], 45, "<br/>", TRUE),
                        'actionOption' => $row['status'] == '1' ? $patientStatusCurrent : $patientStatusArchive,
                        'patient_id' => $row['patient_id'],
                        'patientArticleId' => $row['patientArticleId']
                    );

                    $data['actionOption'] = $this->build_select_option($articleAddOption);
                    $replace['viewPatientArticleRecord'] .= $this->build_template($this->get_template("viewPatientArticleRecord"), $data);
                }
            }
        }
        $replace['viewPatientArticleRecord'] .= '</table>';



        /*         * ***************************************** */




        // show all the plans associated with patient.
        $replace['viewPatientPlanRecordHeader'] = '
			<span style="font-size: 110%;color: #ed1f24;font-weight: bold;" >Assigned Plans</span>
			<table border="0" cellpadding="2" cellspacing="1" width="100%"  class="list" style="margin-top:5px;" >
			<thead>
			<tr>
			<th width="35%" class="nosort" style="white-space:nowrap;">Treatment Plan Name</th>
			<th width="20%" class="nosort" style="white-space:nowrap;">Status</th>
			<th width="8%" class="nosort" style="white-space:nowrap;text-align:right">&nbsp;</th>
			</tr>
			</thead>
			';
        $replace['viewPatientPlanRecord'] = "";
        if ($this->value('id') != "" && is_numeric($this->value('id'))) {


            /* if($providerEhealthStatus == '1') {
              //$ehsStatus = '(OR ehsFlag = '1')';
              } else {
              $ehsStatus = '(AND ehsFlag!= '1')';
              } */

            $query = " select * from plan where patient_id = '{$this->value('id')}' and status !=3 ORDER BY plan_id DESC";
            $result = $this->execute_query($query);
            if ($this->num_rows($result) > 0) {
                while ($row = $this->fetch_array($result)) {
                    $data = array(
                        'plan_id' => $row['plan_id'],
                        'plan_name' => $row['plan_name'],
                        'status' => $row['status'] == '1' ? 'Current' : ($row['status'] == '2' ? 'Archived' : 'Deleted Plan'),
                        'actionOption' => $row['status'] == '1' ? $patientStatusCurrent : $patientStatusArchive
                    );
                    $data['actionOption'] = $this->build_select_option($data['actionOption']);
                    $replace['viewPatientPlanRecord'] .= $this->build_template($this->get_template("viewPatientPlanRecord"), $data);
                }
            }
        }
        $replace['viewPatientPlanRecord'] .= '</table>';
        //echo "aaaaa".$replace['viewPatientPlanRecord']; exit;
        // End of plans list.
        // Start e-maintenence patient
        if ($this->checkerabh() == 'yes') {
            if ($this->value('id') != "") {
                $user_id = $this->value('id');
                $p_type = $program['cash-based-program'];
                $sql = "select * from program_user where u_id = '{$user_id}' and p_type = '{$p_type}' ";
                $result = @mysql_query($sql);
                if (@mysql_num_rows($result) > 0) {
                    $replace['program'] = "e-Rehab Program";
                } else {
                    $replace['program'] = "No current programs";
                }
            }
        } else {
            $replace['program'] = "No current programs";
        }
        // End e-maintenence
        // EHealthService start

        $user_id = $this->value('id');
        //echo $this->get_clinic_info($user_id,'clinic_id');
        $EHealthService = $this->getPatientSubscriptionDetails($user_id, $this->get_clinic_info($user_id, 'clinic_id'));
        if ($EHealthService['subs_title'] != '') {
            $replace['EHealthService'] = $EHealthService['subscription_title'];
        } else {
            $replace['EHealthService'] = 'Not Subscribed';
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
        $clinicId = $this->clinicInfo("clinic_id");
        $replace['clinicId'] = $clinicId;
        $replace['userId'] = $this->value('id');

        if ($clinicId && $clinicId > 0) {
            $sqlEhealth = "SELECT subs_status FROM clinic_subscription WHERE subs_clinic_id  = " . $clinicId;
            $queryEhealth = $this->execute_query($sqlEhealth);
            $numEhealthRow = $this->num_rows($queryEhealth);
            if ($numEhealthRow != '0') {
                $valueEhealth = $this->fetch_object($queryEhealth);
                if ($valueEhealth->subs_status == 0) {
                    $replace['ehsEnable'] = 'disabled';
                } else {
                    // $replace['ehsEnable'] = '';

                    $sqlPaymentSubscription = "SELECT subs_status FROM patient_subscription where user_id = {$user_id}";
                    $querysubscription = $this->execute_query($sqlPaymentSubscription);
                    $numquerysubscription = $this->num_rows($querysubscription);
                    if ($numquerysubscription != '0') {
                        $valuesubscription = $this->fetch_object($querysubscription);
                        if ($valueEhS->subs_status == 2) {
                            $replace['ehsEnable'] = 'disabled';
                        } else {
                            $replace['ehsEnable'] = '';
                        }
                    } else {
                        $replace['ehsEnable'] = '';
                    }
                }
            } else {
                $replace['ehsEnable'] = 'disabled';
            }
        }

        //E-health service End here
        // Intake Paper Work Code Starts Here

        $intakeview = $this->execute_query("select * from patient_intake where p_user_id=" . $this->value('id'));
        $numcount = $this->num_rows($intakeview);
        $brifintakeview = $this->execute_query("select * from patient_brief_intake where p_user_id=" . $this->value('id'));
        $briefnumcount = $this->num_rows($brifintakeview);

        if ($numcount == '0') {
            $replaceIntake['labelintakepaperwork'] = "Assign Adult Intake Paperwork";
            $replaceIntake['intakepaperwork'] = "value='Assign' onclick='assignintake(" . $this->userInfo('user_id') . " ," . $this->value('id') . ");' style='display:block;float:right;font-size:11px;' ";
            $replaceIntake['display1'] = 'none';
        } else {
            $rowintake = $this->fetch_array($intakeview);
            //print_r($rowintake);
            if ($rowintake['intake_compl_status'] == '0') {
                $replaceIntake['labelintakepaperwork'] = "Adult Intake Paperwork Assigned";
                $replaceIntake['intakepaperwork'] = "value='Assign' onclick='assignintake(" . $this->userInfo('user_id') . " ," . $this->value('id') . ");' style='display:none;float:right;font-size:11px;' ";
                $replaceIntake['display1'] = 'block';
            } else {
                $replaceIntake['labelintakepaperwork'] = "Adult Intake Paperwork Completed";
                $replaceIntake['intakepaperwork'] = "value='View' onclick='javascript:view_intake_paper();'style='display:block;float:right;font-size:11px;' ";
                $replaceIntake['display1'] = 'none';
            }
        }
        if ($briefnumcount == '0') {
            $replaceIntake['labelbriefintakepaperwork'] = "Assign Brief Intake Paperwork";
            $replaceIntake['briefintakepaperwork'] = "value='Assign' onclick='assignbriefintake(" . $this->userInfo('user_id') . " ," . $this->value('id') . ");' style='display:block;float:right;font-size:11px;' ";
            $replaceIntake['display'] = 'none';
        } else {
            $rowbrifeintake = $this->fetch_array($brifintakeview);
            //print_r($rowintake);
            if ($rowbrifeintake['intake_compl_status'] == '0') {
                $replaceIntake['labelbriefintakepaperwork'] = "Brief Intake Paperwork Assigned";
                $replaceIntake['briefintakepaperwork'] = "value='Assign' onclick='assignbriefintake(" . $this->userInfo('user_id') . " ," . $this->value('id') . ");' style='display:none;float:right;font-size:11px;' ";
                $replaceIntake['display'] = 'block';
            } else {
                $replaceIntake['labelbriefintakepaperwork'] = "Brief Intake Paperwork Completed";
                $replaceIntake['briefintakepaperwork'] = "value='View' onclick='javascript:view__brief_intake_paper();'style='display:block;float:right;font-size:11px;' ";
                $replaceIntake['display'] = 'none';
            }
        }


        /*  if($numcount=='0' && $briefnumcount=='0'){
          $replaceIntake['labelintakepaperwork']="<b>Adult Intake Paperwork</b>";
          $replaceIntake['intakepaperwork']="value='Assign' onclick='assignintake(".$this->userInfo('user_id')." ,".$this->value('id').");' style='display:block;float:right;font-size:11px;' ";
          $replaceIntake['unassignintakepaperwork']="value='Unassign' style='display:none;'";
          $replaceIntake['labelbriefintakepaperwork']='<b>Brief Intake Paperwork</b>  ';
          $replaceIntake['briefintakepaperwork']="value='Assign' onclick='assignbriefintake(".$this->userInfo('user_id')." ,".$this->value('id').");' style='display:block;float:right;font-size:11px;' ";
          $replaceIntake['unassignbriefintakepaperwork']="value='Unassign' style='display:none;'";
          }elseif($numcount==1 && $briefnumcount=='0'){
          //echo "1";
          $rowintake=$this->fetch_array($intakeview);
          //print_r($rowintake);
          if($rowintake['intake_compl_status']=='0') {
          $replaceIntake['labelintakepaperwork']='<b>Intake Paperwork Assigned</b>';
          $replaceIntake['intakepaperwork']="value='Assign' style='display:none;'";
          $replaceIntake['unassignintakepaperwork']="value='Unassign' onclick='unassignintake(".$this->userInfo('user_id')." ,".$this->value('id').");' style='display:block;float:right;font-size:11px;' ";
          $replaceIntake['labelbriefintakepaperwork']='<b>Brief Intake Paperwork</b>  ';
          $replaceIntake['briefintakepaperwork']="value='Assign' style='display:block;float:right;font-size:11px;' disabled='true' ";
          $replaceIntake['unassignbriefintakepaperwork']="value='Unassign' style='display:none;'";
          }
          else {
          $replaceIntake['labelintakepaperwork']='<b>Intake Paperwork Completed</b>  ';
          $replaceIntake['intakepaperwork']='value="View" onclick="javascript:view_intake_paper();" ';
          $replaceIntake['unassignintakepaperwork']="value='Unassign' style='display:none;'";
          $replaceIntake['labelbriefintakepaperwork']='<b>Brief Intake Paperwork</b>  ';
          $replaceIntake['briefintakepaperwork']="value='Assign' onclick='assignbriefintake(".$this->userInfo('user_id')." ,".$this->value('id').");' style='display:block;float:right;font-size:11px;' ";
          $replaceIntake['unassignbriefintakepaperwork']="value='Unassign' style='display:none;'";

          }
          }elseif($numcount=='0' && $briefnumcount=='1'){
          //echo "2";
          $rowbrifeintake=$this->fetch_array($brifintakeview);
          //print_r($rowintake);
          if($rowbrifeintake['intake_compl_status']=='0') {
          $replaceIntake['labelintakepaperwork']='<b>Adult Intake Paperwork</b>  ';
          $replaceIntake['intakepaperwork']="value='Assign' disabled='true' style='display:block;float:right;font-size:11px;'  ";
          $replaceIntake['unassignintakepaperwork']="value='Unassign' style='display:none;'";
          $replaceIntake['labelbriefintakepaperwork']='<b>Brief Intake Paperwork Assigned</b>  ';
          $replaceIntake['briefintakepaperwork']="value='Assign' style='display:none;'";
          $replaceIntake['unassignbriefintakepaperwork']="value='Unassign' onclick='unassignbriefintake(".$this->userInfo('user_id')." ,".$this->value('id').");' style='display:block;float:right;font-size:11px;' ";
          }
          else {
          $replaceIntake['labelbriefintakepaperwork']='<b>Brief Intake Paperwork Completed</b>  ';
          $replaceIntake['briefintakepaperwork']='value="View" onclick="javascript:view__brief_intake_paper();" ';
          $replaceIntake['unassignbriefintakepaperwork']="value='Unassign' style='display:none;'";
          $replaceIntake['labelintakepaperwork']='<b>Adult Intake Paperwork</b>  ';
          $replaceIntake['intakepaperwork']="value='Assign' onclick='assignintake(".$this->userInfo('user_id')." ,".$this->value('id').");' style='display:block;float:right;font-size:11px;' ";
          $replaceIntake['unassignintakepaperwork']="value='Unassign' style='display:none;'";
          }

          }else{
          //echo '4';
          $rowintake=$this->fetch_array($intakeview);
          $rowbrifeintake=$this->fetch_array($brifintakeview);
          if($rowintake['intake_compl_status']=='0'){
          $replaceIntake['labelintakepaperwork']='<b>Intake Paperwork Assigned</b>';
          $replaceIntake['intakepaperwork']="value='Assign' style='display:none;' ";
          $replaceIntake['unassignintakepaperwork']="value='Unassign' onclick='unassignintake(".$this->userInfo('user_id')." ,".$this->value('id').");' style='display:block;float:right;font-size:11px;' ";
          }else{
          $replaceIntake['labelintakepaperwork']='<b>Intake Paperwork Completed</b>  ';
          $replaceIntake['intakepaperwork']='value="View" onclick="javascript:view_intake_paper();"';
          }
          if($rowbrifeintake['intake_compl_status']=='0') {
          $replaceIntake['labelbriefintakepaperwork']='<b>Brief Intake Paperwork Assigned</b>  ';
          $replaceIntake['briefintakepaperwork']="value='Assign' style='display:none;' ";
          $replaceIntake['unassignbriefintakepaperwork']="value='Unassign' onclick='unassignbriefintake(".$this->userInfo('user_id')." ,".$this->value('id').");' style='display:block;float:right;font-size:11px;' ";
          }else{
          $replaceIntake['labelbriefintakepaperwork']='<b>Brief Intake Paperwork</b>  ';
          $replaceIntake['briefintakepaperwork']="value='Assign' onclick='assignbriefintake(".$this->userInfo('user_id')." ,".$this->value('id').");' style='display:block;float:right;font-size:11px;' ";}
          $replaceIntake['unassignbriefintakepaperwork']="value='Unassign' style='display:none;'";
          } */
        $replaceIntake['patient_id'] = $this->value('id');
        $replaceIntake['provider_id'] = $this->userInfo('user_id');
        $replaceIntake['id'] = $this->userInfo('user_id');
        $replaceIntake['p_user_id'] = $this->value('id');

        /* $replaceIntake['labelbriefintakepaperwork']='<b>Intake Paperwork Completed</b>  ';
          $replaceIntake['briefintakepaperwork']='value="View" onclick="javascript:view_intake_paper();" '; */
        //  print_r($_SESSION);
        if ($_SESSION['accountFeature']['Intake Paperwork'] == '1')
            $replace['intakeFeatureTemplate'] = $this->build_template($this->get_template('intakeFeatureTemplate'), $replaceIntake);

        $replace['intakeBreafeTemplate'] = $this->build_template($this->get_template('intakeBreafeTemplate'), $replaceIntake);


        // Intake Paper Work Code Ends Here 
        //end of EHealthService
        // patient reminder List.
        $replace['patient_reminder_set1'] = $this->patient_reminder($this->value('id'), 1);
        $replace['patient_reminder_set2'] = $this->patient_reminder($this->value('id'), 2);

        // End of patient reminder List.
        if ($this->value('id') != "") {
            $replace['soapRecord'] = $this->soapNotesList($this->value('id'));
        }
        // Returns list of associated therapist.
        $replace['therapistName'] = $this->getAssociatedTherapist($this->value('id'));

        // For calender dates
        $replace['selected_dates_set1'] = $this->selected_dates($this->value('id'), 1);
        $replace['selected_dates_set2'] = $this->selected_dates($this->value('id'), 2);
        // End
        // Graph
        $replace['graphMap'] = $this->showMap($this->value('id'));
        // End
        $replace['creation_date'] = $this->formatDate($this->get_field($this->value('id'), "user", "creation_date"));
        $replace['patient_name_id'] = $this->value('id');
        $id = $this->value('id');
        $withwhom = $this->get_field($id, "user", "withwhom");
        if (is_null($withwhom) || empty($withwhom)) {
            $replace['insurance'] = "Not Insured";
        } else {
            $replace['insurance'] = $withwhom;
        }

        $sql = "select lab_report_id,lab_title,DATE_FORMAT(lab_datetime, '%m/%d/%Y') as labdate,lab_path from patient_lab_reports where pat_user_id ={$this->value('id')} order by lab_report_id desc";
        $query = $this->execute_query($sql);
        //$row=$this->fetch_array($query);

        $rowLab = $this->populate_array($query, 0);
        if ($rowLab !== '') {
            foreach ($rowLab as $klab => $kValue) {
                //print_r($kValue);
                $replace['labresult_display'].="<tr><td style=\"padding:2px 1px;\"><a href=index.php?action=downloadfile&lab_report_id=" . $kValue['lab_report_id'] . " target=\"_blank\" style=\"color:#0069A0; display:block; padding:4px 5px;\">" . $kValue['lab_title'] . ' ' . $kValue['labdate'] . "</a></td><td><input type='image' src='/images/dustbin_icon.png' onclick='deletedownloadfile(" . $kValue['lab_report_id'] . ")' name='delete' id='delete' value='Delete' style='font-size:11px;' /></td></tr>";

                // $replace['labresult_display'].=$kValue['lab_title'].' '.$kValue['labdate'];
            }
        }



        // Services Listing


        $selectQueryServices = $this->execute_query("select pro_bill_services_patient.*,DATE_FORMAT(pro_bill_datetime, '%m/%d/%Y') as format_pro_bill_datetime from pro_bill_services_patient where pro_bill_patient_id = '{$id}' AND pro_status = '1' order by pro_bill_services_patient_id desc");
        $replace['services_display'] = '';
        if ($this->num_rows($selectQueryServices) > 0) {
            while ($resultServices = $this->fetch_array($selectQueryServices)) {
                $replace['services_display'].="<tr><td style=\"padding:2px 1px;\"><a href='index.php?action=createServicesBillingPdf&id={$resultServices[pro_bill_services_patient_id]}' style=\"color:#0069A0; display:block; padding:4px 5px;\" target=\"_blank\">" . $resultServices['format_pro_bill_datetime'] . "</a></td></tr>";
            }
        }

        $replace['LabResult'] = "<a href='javascript:void(0);' onclick=\"GB_showCenter('Upload Results & Documents', '/index.php?action=aa_upload_lab_result&pid={$this->value('id')}&role=pro',190,450);\"><input type='button'' value='Upload' style='float:right;font-size:11px;'></a>";

        /*         * *code for shop note */
        $soapList = $this->get_soap_list($this->value('id'));
        $replaceSOAP['newsoapnotelist'] .= $soapList;
        $replaceSOAP['id'] = $this->value('id');

        //if($_SESSION['accountFeature']['SOAP Note']=='1')
        //	print_r($_SESSION);
        if ($_SESSION['providerFeature']['SOAP Note'] == '1')
            $replace['soapFeatureTemplate'] = $this->build_template($this->get_template('soapFeatureTemplate'), $replaceSOAP);
        if ($_SESSION['accountFeature']['Outcomes Measures Graph'] == '1')
            $replace['outcomeFeatureTemplate'] = $this->build_template($this->get_template('outcomeFeatureTemplate'), $replaceSOAP);

        $get_satisfaction = $this->get_satisfaction();
        $replace['id'] = $this->value('id');
        $replace['withwhom'] = $this->value('id');
        $replace['member_goal'] = $this->goal($this->value('id'));
        $replace['goal_completed'] = $this->goal_completed($this->value('id'));
        $replace['dob'] = $this->get_field($this->value('id'), "user", "dob");
        $replace['gender'] = $this->get_field($this->value('id'), "user", "gender");
        $replace['body'] = $this->build_template($this->get_template("viewPatient"), $replace);
        $replace['browser_title'] = "Tx Xchange: View Patient";

        // Personalized GUI  
        $AssignedPlans = $_SESSION['providerLabel']['Assigned Plans'] != '' ? $_SESSION['providerLabel']['Assigned Plans'] : 'Assigned Plans';
        $replace['labelAssignedPlans'] = $AssignedPlans;

        $PatientHistory = $_SESSION['providerLabel']['Patient Documents'] != '' ? $_SESSION['providerLabel']['Patient Documents'] : 'Patient Documents';
        $replace['labelPatientHistory'] = $PatientHistory;

        $msgFlag = $this->value('msgFlag');
        $replace['msgFlag'] = $msgFlag;
        if (isset($msgFlag) && $msgFlag == '1') {
            $removePlanAricleMsg = "<script>GB_showCenter('Plan Article', '/index.php?action=planArticleAssociate1',140,500);</script>";
            $replace['removePlanAricleMsg'] = $removePlanAricleMsg;
        }


        $this->output = $this->build_template($this->get_template("main"), $replace);
        $this->output .= $get_satisfaction;
    }

    function patient_reminder($id, $reminder_set) {
        $query = "select * from patient_reminder where patient_id = '{$id}' and status = '1' and reminder_set='{$reminder_set}' order by patient_reminder_id desc";
        $result = $this->execute_query($query);
        $patient_reminder = '';
        if ($this->num_rows($result) > 0) {
            $patient_reminder .= '<ol>';
            $cnt = 1;
            while ($row = $this->fetch_array($result)) {
                $row['cnt'] = $cnt++;
                $row['reminder'] = $this->decrypt_data($row['reminder']);
                $patient_reminder .= $this->build_template($this->get_template("patient_reminder"), $row);
            }
            $patient_reminder .= '</ol>';
        } else {
            $patient_reminder = '<ul style="list-style:none;"><li style="padding-top:10px;padding-left:10px;">There are no current reminders.</li></ul>';
        }
        return $patient_reminder;
    }

    function planArticleAssociate1() {
        $replace['removePlanAricleMsg'] = "Article cannot be removed as it is associated with assigned plan.";
        $replace['body'] = $this->build_template($this->get_template("planArticleAssociate"), $replace);
        $this->output = $this->build_template($this->get_template("main"), $replace);
    }

    /**
     * Get Map co-ordinate   
     */
    function showMap($patient_id) {

        $data = $this->getData($patient_id);
        $graphdate = $this->getGraphDate($patient_id);

        // creates graph object
        $graph = new PostGraph(400, 300);

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
        foreach ($graph->barValue as $key => $value) {
            if (empty($value['value']) || $value['value'] == '') {
                $value['value'] = '0';
            }
            if (!empty($graphdate[$key])) {
                if ($value['value'] == '0') {
                    $value['x1'] = $value['x1'] + 3;
                    $value['y1'] = $value['y1'] + 3;
                }
                $mapstr .= "<area shape='rect' coords='{$value['x1']},{$value['y1']},{$value['x2']},{$value['y2']}' alt='{$value['value']}%:{$graphdate[$key]}' title='{$value['value']}%:{$graphdate[$key]}' href='#' />";
            }
        }
        $mapstr .= '</map>';

        return $mapstr;
    }

    /**
     * Get associated Therapist.
     */
    function getAssociatedTherapist($patientId = '') {
        if (is_numeric($patientId) && $patientId > 0) {
            $privateKey = $this->config['private_key'];
            $query = "  select u.username,
                            AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}') as name_first,
                            AES_DECRYPT(UNHEX(u.name_last),'{$privateKey}') as name_last 
                            from therapist_patient tp
                            inner join user u on u.user_id = tp.therapist_id 
                            where tp.patient_id  = '{$patientId}' ";

            $result = @mysql_query($query);
            if (@mysql_num_rows($result) > 0) {
                $therapistName = '<table border="0" cellpadding="0" cellspacing="0" width="100%" >';
                while ($row = @mysql_fetch_array($result)) {
                    $therapistName .= '<tr><td>' . $row['name_first'] . '&nbsp;' . $row['name_last'] . '</td></tr>';
                }
                $therapistName .= '</table>';
                return $therapistName;
            }
        }
        return '';
    }

    /**
     *  Add Reminder in reminder_schedule table.
     *  @access public.
     */
    function addReminderSchedule() {
        //echo $this->value('reminder_set');
        if ($this->value('patient_id') != "" && $this->value('scheduled_date') != "") {
            $status = $this->userInfo("status", $this->value("patient_id"));
            if ($status == "1" || $status == "2") {
                $reminder_arr = array(
                    'patient_id' => $this->value('patient_id'),
                    'scheduled_date' => $this->value('scheduled_date'),
                    'status' => 'pending',
                    'reminder_set' => $this->value('reminder_set')
                );
                $this->insert("reminder_schedule", $reminder_arr);
                $this->output = true;
                return;
            } else {
                $this->output = false;
                return;
            }
        } else {
            $this->output = false;
            return;
        }
    }

    /**
     * 
     */
    function removeReminderSchedule() {
        if ($this->value(scheduled_date) != "") {
            $arr = explode(",", $this->value(scheduled_date));
            foreach ($arr as $value) {
                $where = " patient_id = '{$this->value('patient_id')}' && scheduled_date = '{$value}' and reminder_set='{$this->value('reminder_set')}'";
                $this->db_delete("reminder_schedule", $where);
            }
        }

        $this->output = $this->value(scheduled_date);
    }

    /**
     * Retrive dates from reminder schedule table.
     *
     */
    function selected_dates_ehs($clinic_id) {
        $selected_date = array();
        $query = "select DATE_FORMAT(scheduled_date,'%d %b %Y %T') as selected_dates from reminder_schedule where clinicId = '{$clinic_id}' and scheduled_date >= curdate()";
        $result = @mysql_query($query);
        while ($row = @mysql_fetch_array($result)) {
            $selected_date[] = "new Date('" . $row['selected_dates'] . "')";
        }
        if (count($selected_date) > 0) {
            $selected_date = implode(",", $selected_date);
            return $selected_date;
        } else {
            return "";
        }
    }

    /**
     * Retrive dates from reminder schedule table.
     *
     */
    function selected_dates($patient_id, $set = 1) {
        $selected_date = array();
        $query = "select DATE_FORMAT(scheduled_date,'%d %b %Y %T') as selected_dates from reminder_schedule where patient_id = '{$patient_id}' and scheduled_date >= curdate() and reminder_set=" . $set;
        $result = @mysql_query($query);
        while ($row = @mysql_fetch_array($result)) {
            $selected_date[] = "new Date('" . $row['selected_dates'] . "')";
        }
        if (count($selected_date) > 0) {
            $selected_date = implode(",", $selected_date);
            return $selected_date;
        } else {
            return "";
        }
    }

    /**
     * This function deletes patient(s) record. And redirects to listing page of patient.
     * 
     * @access public
     */
    function therapistDeletePatient() {

        if ($this->value('id')) {

            $data = array(
                'status' => '2',
            );

            $where = " user_id = " . $this->value('id');

            if ($this->update($this->config['table']['user'], $data, $where)) {

                header("location:index.php?action=myPatients");
            }
        }
    }

    function CURRENT_TIMESTAMP() {
        $sql = "SELECT CURRENT_TIMESTAMP ";
        $result = @mysql_query($sql);
        $row = @mysql_fetch_array($result);
        return $row['CURRENT_TIMESTAMP'];
    }

    /**
     * This function is special made for system admin. Access of this function is only for system admin.
     * Functionality of this function is to take the system admin back to his/her home page/interface.
     *
     * @access public
     */
    function switch_back() {
        if (isset($_SESSION['tmp_username']) && isset($_SESSION['tmp_password'])) {
            $_SESSION['username'] = $_SESSION['tmp_username'];
            $_SESSION['password'] = $_SESSION['tmp_password'];
            session_unregister('tmp_username');
            session_unregister('tmp_password');
            header("location:index.php");
            exit();
        }
    }

    /**
     * This function shows the login history of Patients.
     * 
     * @access public
     *
     */
    function loginHistory() {
        $query = "select * from login_history where user_id = '{$this->value('patient_id')}' and user_type = '1' ORDER BY login_date_time DESC ";
        $result = @mysql_query($query);
        while ($row = @mysql_fetch_array($result)) {
            $login_data = date("l,F j, Y - h:ia ", strtotime($row['login_date_time']));
            $data = array(
                'login_data' => $login_data,
                'style' => $cnt++ % 2 ? "ineerwhite" : "inergrey"
            );
            $replace['loginHistoryRecord'] .= $this->build_template($this->get_template("loginHistoryRecord"), $data);
        }
        $replace['browser_title'] = "Tx Xchange: View Login History";
        $this->output = $this->build_template($this->get_template("loginHistory"), $replace);
    }

    /**
     *  This function sends mail of login detail to patient.
     */
    function mail_login_detail_patient() {
        if (is_numeric($this->value('patient_id'))) {
            $query = "select user_id,username,password from user where usertype_id = 1 and user_id = '{$this->value('patient_id')}' and (status = 1 or status = 2)";
            $result = @mysql_query($query);
            if ($row = @mysql_fetch_array($result)) {
                $email_address = $row['username'];
                $to = $email_address;
                $clinicName = html_entity_decode($this->get_clinic_info($this->value('patient_id'), "clinic_name"), ENT_QUOTES, "UTF-8");
                $subject = "Information from " . $clinicName;
                $password = $this->decrypt_data($row['password']);
                $images_url = $this->config['images_url'];
                $user_id = $row['user_id'];
                $clinic_channel = $this->getchannel($this->get_clinic_info($this->value('patient_id'), "clinic_id"));
                if ($clinic_channel == 1) {
                    $business_url = $this->config['business_tx'];
                    $support_email = $this->config['email_tx'];
                } else {
                    $business_url = $this->config['business_wx'];
                    $support_email = $this->config['email_wx'];
                }
                $data = array(
                    'images_url' => $images_url,
                    'username' => $email_address,
                    'password' => $password,
                    'business_url' => $business_url,
                    'support_email' => $support_email
                );

                $clinic_type = $this->getUserClinicType($user_id);

                if ($clinic_channel == 1) {
                    $message = $this->build_template($this->get_template("resend_login_detail_plpto"), $data);
                } else {
                    $message = $this->build_template($this->get_template("resend_login_detail_wx"), $data);
                }


                // To send HTML mail, the Content-type header must be set
                $headers = 'MIME-Version: 1.0' . "\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
                $headers .= "From: " . $this->setmailheader($clinicName) . "<do-not-reply@txxchange.com>" . "\n";
                $returnpath = '-fdo-not-reply@txxchange.com';
                //echo $subject;exit;
                $success = mail($to, $subject, $message, $headers, $returnpath);
                if ($success == true) {
                    echo "success";
                    return;
                } else {
                    echo "failed";
                    return;
                }
            }
        }

        echo "Email address not found.";
        return;
    }

    /**
     * Add SOAP Notes
     */
    function addSoapNote() {
        $replace['subjective'] = $this->value('subjective');
        $replace['objective'] = $this->value('objective');
        $replace['assessment'] = $this->value('assessment');
        $replace['plan'] = $this->value('plan');
        $replace['user_id'] = $this->userInfo('user_id');
        $replace['patient_id'] = $this->value('patient_id');
        $replace['javascript'] = "";
        $submit = $this->value('save');

        if ($submit == 'Save') {
            if (( $replace['subjective'] != "" || $replace['assessment'] != "" || $replace['plan'] != "" ) && ( is_numeric($replace['user_id']) && is_numeric($replace['patient_id']) )) {
                $insert_arr = array(
                    'subjective' => $this->encrypt_data(nl2br($replace['subjective'])),
                    'objective' => $this->encrypt_data(nl2br($replace['objective'])),
                    'assessment' => $this->encrypt_data(nl2br($replace['assessment'])),
                    'plan' => $this->encrypt_data(nl2br($replace['plan'])),
                    'patient_id' => $replace['patient_id'],
                    'therapist_id' => $replace['user_id']
                );
                $this->insert('soap_notes', $insert_arr);
            }
            $replace['javascript'] = "parent.parent.reloadlab();parent.parent.GB_hide();";
        }
        $this->output = $this->build_template($this->get_template("addSoapNote"), $replace);
    }

    /**
     * View SOAP Notes
     */
    function viewSoapNote() {
        $soap_notes_id = $this->value('soap_notes_id');
        $query = " select * from soap_notes where soap_notes_id = '{$soap_notes_id}' ";
        $result = @mysql_query($query);
        if ($row = @mysql_fetch_array($result)) {
            $encrypt_field = array('subjective', 'objective', 'assessment', 'plan');
            $row = $this->decrypt_field($row, $encrypt_field);
            $this->output = $this->build_template($this->get_template("viewSoapNote"), $row);
        }
    }

    /**
     * List the SOAP notes record.
     */
    function soapNotesList($patient_id) {
        if (is_numeric($patient_id) && $patient_id > 0) {
            $query = "select * from soap_notes where patient_id = '{$patient_id}' order by status asc,created_on desc ";
            $result = @mysql_query($query);
            while ($row = @mysql_fetch_array($result)) {
                $row['date'] = $this->formatDate($row['created_on'], 'M-d-Y h:iA');
                $output .= $this->build_template($this->get_template("soapRecord"), $row);
            }
        }
        //return $output;
    }

    /**
     * Patient History Questionaries Form
     */
    function acnPatient() {
        $this->output = $this->build_template($this->get_template("acnPatient"));
    }

    /**
     * Assign Outcome Measure
     */
    function assign_om() {
        $om = $this->value('outcome_measure');
        //$_REQUEST[outcome_measure];


        $patient_id = $this->value('patient_id');
        $user_id = $this->userInfo('user_id');
        $replace['javascript'] = "";
        if ($this->value('Submit') != "") {
            $num = count($_REQUEST['outcome_measure']);
            if ($num == 0) {
                $error = "alert('Please select an Outcome measure Form.');";
                $replace['javascript'] = $error;
            }
            // print_r($_REQUEST['outcome_measure']);
            // die;

            if (count($_REQUEST['outcome_measure']) > 0) {
                //$duplicate = $this->duplicate_check('patient_om','om_form',$om," patient_id ={$patient_id} and status = 1 ");
                //if( !$duplicate ){
                foreach ($_REQUEST['outcome_measure'] as $key => $val) {

                    if ($val == 7) {
                        $lbhra = array(
                            'patient_id' => $patient_id,
                            'submitted_on' => date('Y-m-d H:i:s'),
                            'status' => 1
                        );
                        $this->insert('lbhra', $lbhra);
                    } elseif ($val == 8) {
                        $acn_patient = array(
                            'patient_id' => $patient_id,
                            'submitted_on' => date('Y-m-d H:i:s'),
                            'status' => 1
                        );
                        $this->insert('acn_patient', $acn_patient);
                    } else {
                        $patient_om = array(
                            'om_form' => $val,
                            'patient_id' => $patient_id,
                            'therapist_id' => $user_id,
                            'status' => 1
                        );
                        $this->insert('patient_om', $patient_om);
                    }
                }
                $replace['javascript'] = "parent.parent.GB_hide();";
                // }
                /* else{
                  $error = "alert('The patient needs to fill out an existing assessment before you can assign a new one.');";
                  $replace['javascript'] = $error;
                  } */
            } else {
                $error = "alert('Please select an Outcome measure Form.');";
                $replace['javascript'] = $error;
            }
        }
        $checked1 = "";
        $checked2 = "";
        $checked3 = "";
        $checked4 = "";
        $checked5 = "";
        $checked6 = "";
        $checked7 = "";
        $disabled1 = "";
        $disabled2 = "";
        $disabled3 = "";
        $disabled4 = "";
        $disabled5 = "";
        $disabled6 = "";
        $disabled7 = "";
        $disabled8 = "";
        $replace['checked1'] = $checked1;
        $replace['checked2'] = $checked2;
        $replace['checked3'] = $checked3;
        $replace['checked4'] = $checked4;
        $replace['checked5'] = $checked5;
        $replace['checked6'] = $checked6;
        $replace['checked7'] = $checked7;
        $replace['checked8'] = $checked8;
        $replace['disabled1'] = $disabled1;
        $replace['disabled2'] = $disabled2;
        $replace['disabled3'] = $disabled3;
        $replace['disabled4'] = $disabled4;
        $replace['disabled5'] = $disabled5;
        $replace['disabled6'] = $disabled6;
        $replace['disabled7'] = $disabled7;
        $replace['disabled8'] = $disabled8;
        $sqlOm = "select * from patient_om where patient_id=" . $this->value('patient_id') . " and status=1";
        $resOm = $this->execute_query($sqlOm);
        $numrow = $this->num_rows($resOm);
        if ($numrow > 0) {
            while ($rowOm = $this->fetch_object($resOm)) {
                $replace['checked' . $rowOm->om_form] = 'checked';
                $replace['disabled' . $rowOm->om_form] = 'disabled';
            }
        }
        $sqlLBHRA = "select * from lbhra where patient_id=" . $this->value('patient_id') . " and status=1";
        $reslbhra = $this->execute_query($sqlLBHRA);
        $numrowLBHRA = $this->num_rows($reslbhra);
        if ($numrowLBHRA > 0) {

            $replace['checked7'] = 'checked';
            $replace['disabled7'] = 'disabled';
        }
        $sqlacn_patient = "select * from acn_patient where patient_id=" . $this->value('patient_id') . " and status=1";
        $resacn_patient = $this->execute_query($sqlacn_patient);
        $numrowacn_patient = $this->num_rows($resacn_patient);
        if ($numrowacn_patient > 0) {

            $replace['checked8'] = 'checked';
            $replace['disabled8'] = 'disabled';
        }

        $replace['patient_id'] = $this->value('patient_id');
        //print_r($replace);
        $this->output = $this->build_template($this->get_template("assign_om"), $replace);
    }

    /**
     * check whether patient has submitted history questionaries or not.
     *
     * @return string
     * @access public
     */
    function check_hist_ques() {
        $flag = 2;
        $patient_id = $this->value('patient_id');
        if (is_numeric($patient_id)) {
            $query = " select * from acn_patient where patient_id = '{$patient_id}' ";
            $result = @mysql_query($query);
            if (@mysql_num_rows($result) > 0) {
                $flag = 1;
            }
        }
        // echo $flag;
    }

    /**
     * check whether patient has submitted LBHRA form or not.
     *
     * @return string
     * @access public
     */
    function check_lbhra() {
        $flag = 2;
        $patient_id = $this->value('patient_id');
        if (is_numeric($patient_id)) {
            $query = " select * from lbhra where patient_id = '{$patient_id}' ";
            $result = @mysql_query($query);
            if (@mysql_num_rows($result) > 0) {
                $flag = 1;
            }
        }
        echo $flag;
    }

    /**
     * check whether patient is for elpto service or plpto service
     */
    function check_service() {
        $flag = 'elpto';
        $patient_id = $this->value('patient_id');
        if (is_numeric($patient_id)) {
            $query = " select * from clinic_user where user_id = '{$patient_id}' ";
            $result = @mysql_query($query);
            if ($row = @mysql_fetch_array($result)) {
                $clinic_id = $row['clinic_id'];
                if (is_numeric($clinic_id)) {
                    $query = "select * from clinic_service where clinic_id = '{$clinic_id}' ";
                    $result = @mysql_query($query);
                    if ($row = @mysql_fetch_array($result)) {
                        echo $row['service_name'];
                        exit();
                    }
                }
            }
        }
        echo $flag;
    }

    /**
     * check patient questionnaire is assigned or not.
     *
     * @return string
     * @access public
     */
    function check_questionnaire() {
        $flag = 2;
        $patient_id = $this->value('patient_id');
        if (is_numeric($patient_id)) {
            $query = " select * from assign_questionnaire where patient_id = '{$patient_id}' and status = 1 ";
            $result = @mysql_query($query);
            if (@mysql_num_rows($result) > 0) {
                $flag = 1;
            }
        }
        echo $flag;
    }

    /**
     * Assign questionnaire to employees from patient record page  
     */
    function assign_questionnaire() {
        $patient_id = $this->value('patient_id');
        if (is_numeric($patient_id)) {
            $acn_patient_arr = array(
                'patient_id' => $patient_id,
                'status' => 1
            );
            echo $this->insert('assign_questionnaire', $acn_patient_arr);
            return;
        }
        echo 2;
    }

    /**
     * To show the left navigation panel.
     *
     * @return string
     * @access public
     */
    function sidebar() {

        //code for checking the trial period days left for Provider/AA
        //$freetrialstr=$this->getFreeTrialDaysLeft($this->userInfo('user_id'));


        $data = array(
            'name_first' => $this->userInfo('name_first'),
            'name_last' => $this->userInfo('name_last'),
            'sysadmin_link' => $this->sysadmin_link(),
            'therapist_link' => $this->therapist_link(),
            'freetrial_link' => $freetrialstr
        );

        return $this->build_template($this->get_template("sidebar"), $data);
    }

    /**
     * This function returns template path from xml file.
     *
     * @param string $template
     * @return string
     */
    function get_template($template) {

        $login_arr = $this->action_parser($this->action, 'template');

        $pos = array_search($template, $login_arr['template']['name']);

        return $login_arr['template']['path'][$pos];
    }

    /**
     * This function display's the help page Therapist.
     * 
     */
    function help_therapist() {
        $this->output = $this->build_template($this->get_template("help_therapist"));
    }

    /**
     * This function display's the output.
     * @access public
     */
    function display() {

        view::$output = $this->output;
    }

    /**
     * This function is for video conference page
     */
    function video_conference() {

        // echo "<br>Patainent Id=".$this->value('patient_id');
        // echo "<br>Therpist Id =".$this->userInfo('user_id');



        /*
         * Applying the Code for OpenTok Against the Ticket TXM-07 Viedo conferencing on Date AUG-16-2013
         * By Rohit Mishra
         */
        //Comment Previoues code
        /*
          $replace['id'] = $this->value('patient_id');
          $replace['sname'] = $this->streamNameTherapist();
          //add random varibale
          $replace['sname'] .='&rand='.rand(10000, 50000);
          $this->output =  $this->build_template($this->get_template("video_conference_therapist"),$replace);
         */

        // Open tok code Here

        $patainent_id = $this->value('patient_id');
        $provider_id = $this->userInfo('user_id');
        $token = '';
        $session_id = '';
        //Checking if oprentak session and token exits or not if not create the new session and token other wise use old one
        $query = " select * from opentok_session where patainent_id='{$patainent_id}' AND provider_id='$provider_id' AND creation_date > DATE_SUB( NOW(), INTERVAL 24 HOUR)  ORDER BY id DESC";
        $result = $this->execute_query($query);
        if ($this->num_rows($result) > 0) {
            $row = $this->fetch_array($result);
            //$row['plan_id'];

            $token = $row['token'];
            $session_id = $row['session_id'];
        } else {

            $open_talkparam = $this->get_opentok_paramenter();


            $opentok_session_arr = array('patainent_id' => $patainent_id,
                'provider_id' => $provider_id,
                'session_id' => $open_talkparam['sessionId'],
                'token' => $open_talkparam['token'],
                'call_status' => 1
            );
            $this->insert("opentok_session", $opentok_session_arr);
            $token = $open_talkparam['token'];
            $session_id = $open_talkparam['sessionId'];
        }


        $replace['patainent_id'] = $patainent_id;
        $replace['provider_id'] = $provider_id;
        $replace['session_id'] = $session_id;
        $replace['token'] = $token;
        $replace['apiKey'] = API_Config::API_KEY;
        $this->output = $this->build_template($this->get_template("video_conference_therapist"), $replace);
    }

    function chage_call_status_provider() {




        $provider_id = $_REQUEST['provider_id'];
        $patainent_id = $_REQUEST['patainent_id'];
        $call_staus = $_REQUEST['call_staus'];

        $query = "UPDATE  opentok_session SET call_status='$call_staus' where patainent_id='$patainent_id' AND provider_id='$provider_id'  AND creation_date >= DATE_SUB( NOW(), INTERVAL 24 HOUR)  ";
        $result = @mysql_query($query);
    }

    /**
     * This function is for Creating the opentok session and token.
     */
    function get_opentok_paramenter() {

        ini_set('memory_limit', '-1');
        ini_set('display_errors', "1");
        error_reporting(E_ALL);
        // Creating an OpenTok Object
        $apiObj = new OpenTokSDK(API_Config::API_KEY, API_Config::API_SECRET);

        // Creating Simple Session object, passing IP address to determine closest production server
        // Passing IP address to determine closest production server
        $session = $apiObj->createSession($_SERVER["REMOTE_ADDR"]);

        // Creating Simple Session object 
        // Enable p2p connections
        $session = $apiObj->createSession($_SERVER["REMOTE_ADDR"], array(SessionPropertyConstants::P2P_PREFERENCE => "enabled"));

        // Getting sessionId from Sessions
        // Option 1: Call getSessionId()
        $sessionId = $session->getSessionId();
        //echo "Session=". $sessionId;
        // Option 2: Return the object itself
        // After creating a session, call generateToken(). Require parameter: SessionId
        $token = $apiObj->generateToken($sessionId);

        // Giving the token a moderator role, expire time 5 days from now, and connectionData to pass to other users in the session
        $token = $apiObj->generateToken($sessionId, RoleConstants::MODERATOR, time() + (30 * 24 * 60 * 60), "TxxChange Test");
        // echo "<br>Token=". $token;

        $return = array();

        $return['sessionId'] = $sessionId;
        $return['token'] = $token;

        // print_r($return);
        // die;
        return $return;
    }

    /**
     * This function is for returning stream name.
     */
    function streamNameTherapist() {

        $tsn = $this->userInfo('username');
        if (is_string($tsn)) {
            //$tsn = md5($tsn);
            $tsn = $tsn;
            $tid = $this->userInfo('userid');
            if (is_numeric($tid) && $tid > 0) {
                //$tsn = $tsn . md5($tid);
                $tsn = $tsn . $tid;
            }
        }
        $psn = $this->userInfo('username', $this->value('patient_id'));
        if (is_string($psn)) {
            //$psn = md5($psn);
            $psn = $psn;
            $pid = $this->userInfo('userid', $this->value('patient_id'));
            if (is_numeric($pid) && $pid > 0) {
                //$psn = $psn . md5($pid);
                $psn = $psn . $pid;
            }
        }
        $psn = $psn . ':';
        $sn = "localSname={$tsn}&liveSname={$psn}&url={$this->config['url']}";
        //echo  $sn;
        return $sn;
    }

    /**
     * This function display's the FAQ page Therapist.
     * 
     */
    function faq_therapist_head() {
        $this->output = $this->build_template($this->get_template("faq_therapist_head"));
    }

    /**
     * This function fetch the soap notes list fromdatabase
     * @access public
     * @return void
      ` */
    public function get_soap_list($pat_id) {
        $userInfo = $this->userInfo();
        $query = "select *,DATE_FORMAT(create_datetime, '%m/%d/%Y') as date1 from new_soap_note where patient_id={$pat_id} order by status asc, cast(create_datetime as UNSIGNED) desc";
        $result = $this->execute_query($query);
        while ($row = $this->fetch_array($result)) {
            $userId = $row['provider_id'];
            $providername = $this->userInfo('name_title', $userId) . ' ' . $this->userInfo('name_first', $userId) . ' ' . $this->userInfo('name_last', $userId);
            $date = $row['date1'];
            if ($row['status'] == 1) {
                $var.="<tr><td style=\"padding:2px 1px;height:30px;\"><a href=\"index.php?action=provider_view_soap_note_readonly&id=$pat_id&shopnoteid=$row[soap_note_id]\" style=\"color:#0069A0; display:block; padding:1px 0px;\"><img src=\"/images/icon_final.jpg\" name=\"Final SOAP Note \" align=\"right\" style=\"padding:1px 5px; \" />" . $providername . " " . "$date <div style=\"clear:both;\"></div></a></td></tr>";
            } else {
                $var.="<tr><td style=\"padding:2px 1px; height:30px;\"><a href=\"index.php?action=provider_view_soap_note_draft&id=$pat_id&shopnoteid=$row[soap_note_id]\" style=\"color:#0069A0; display:block; padding:1px 0px;\"><img src=\"/images/icon_draft.jpg\" name=\"Draft SOAP Note \" align=\"right\" style=\"padding:1px 5px; \" >" . $providername . " " . "$date <div style=\"clear:both;\"></div></a></td></tr>";
            }
        }

        return $var;
    }

    /**
     * This function subscribes / unsubscribes patient from mass messaging.
     * @access public
     */
    function eHealthServicePatient() {

        $userId = $this->value('userId');
        $ehs = $this->value('ehs');

        if ($this->value('confirm') == "yes") {
            if (is_numeric($this->value('ehs'))) {
                $query = "UPDATE user SET  ehs = '" . $this->value('ehs') . "' WHERE user_id = " . $userId;
                $result = @mysql_query($query);
            }
            $this->output = "<script language='javascript'> 
                                        parent.parent.location.reload();
                                        //parent.parent.GB_hide();
                                        parent.parent.setTimeout('GB_CURRENT.hide()',1000); 
                                    </script>";
            return;
        }

        if ($ehs == 0) {
            $replace['message'] = "By clicking E health service your service will become disable.<br /><br /><span style='padding-left:110px;'>Do you still want to disable?</span>";
        } else {
            $replace['message'] = "By clicking E health service your service will become enabled.<br /><br /><span style='padding-left:110px;'>Do you still want to enable?</span>";
        }
        $replace['ehs'] = $this->value('ehs');
        $replace['userId'] = $this->value('userId');
        $replace['body'] = $this->build_template($this->get_template("ehealthservice"), $replace);
        $replace['browser_title'] = "Tx Xchange: Home";
        $this->output = $this->build_template($this->get_template("main"), $replace);
    }

    /**
     * Function for the mass assignment of all the EHS patients of provider
     * @access public
     * @SKS
     * @18th november 2011
     * @param ''
     * @return 
     */
    public function therapistEhsPatient() {
        //subactions for the EHS patients
        unset($_SESSION['TempArticleEhs']);
        $this->clear_temp_article();
        $clinicId = $this->clinicInfo('clinic_id');        
//$ehsPatientArr=array();
        if ($this->is_corporate($clinicId) == 1) {
            //echo "corporate";
            $ehsPatientArr = $this->get_paitent_list($clinicId);
        } else {
            //echo "ehs";
            $ehsPatientArr = $this->getProviderEHSPatients($clinicId);
        }
        //print_r($ehsPatientArr );
        $patientCount = count($ehsPatientArr);
        if ($this->value('plan_id') != "") {
            if ($this->value('act') == 'current') {
                $pat = 0;
                while ($pat < $patientCount) {
                    $query1 = "update plan set status = '1' where parent_template_id = '{$this->value('plan_id')}' AND patient_id = '$ehsPatientArr[$pat]' ";
                    $result1 = $this->execute_query($query1);
                    $pat++;
                }
                $query = "update plan set status = '1' where plan_id = '{$this->value('plan_id')}' and status != '3' ";
            }
            if ($this->value('act') == 'deletePlan') {
                $pat = 0;
                while ($pat < $patientCount) {
                    $query1 = "update plan set status = '3' where  parent_template_id = '{$this->value('plan_id')}' AND patient_id = '$ehsPatientArr[$pat]' ";
                    $result1 = $this->execute_query($query1);
                    $pat++;
                }
                $query = "update plan set status = '3' where plan_id = '{$this->value('plan_id')}'  ";
            }
            if ($this->value('act') == 'archive') {
                $pat = 0;
                while ($pat < $patientCount) {
                    $query1 = "update plan set status = '2' where parent_template_id = '{$this->value('plan_id')}' AND patient_id = '$ehsPatientArr[$pat]' ";
                    $result1 = $this->execute_query($query1);
                    $pat++;
                }
                $query = "update plan set status = '2' where plan_id = '{$this->value('plan_id')}' and status != '3' ";
            }
            if ($this->value('act') == 'editPlan') {
                header("location:index.php?action=createNewEhsPlan&act=plan_edit&schd=edit&type=finish&plan_id={$this->value('plan_id')}");
                exit(0);
            }
            if ($this->value('act') == 'copyPlan') {
                header("location:index.php?action=copyExistingEhsPlan&act=plan_edit&type=finish&plan_id={$this->value('plan_id')}");
                exit(0);
            }

            if (isset($query) && $query != "") {
                $result = $this->execute_query($query);
            }

            if (isset($query1) && $query1 != "") {
                $result1 = $this->execute_query($query1);
            }
        }

        $replace = array();

        include_once("template/therapistEHS/therapistArray.php");

        //Provider EHS buttons
        // echo '<pre>'; 
        //print_r($_SESSION);
        $imageCreateNewTemplatePlan = $_SESSION['providerLabel']['images/createNewTemplateEhsPlan.gif'] != '' ? $_SESSION['providerLabel']['images/createNewTemplateEhsPlan.gif'] : "images/createNewTemplateEhsPlan.gif";
        $imageAssignHeathTemplate = $_SESSION['providerLabel']['images/assignTemplatePlan.gif'] != '' ? $_SESSION['providerLabel']['images/assignTemplatePlan.gif'] : "images/assignTemplatePlan.gif";
        $replace['navigation_image_view_patient'] = '<a href="index.php?action=createNewEhsPlan&ehsflag=1&type=finish"><img src="' . $imageCreateNewTemplatePlan . '" width="127" height="81" alt="Create New Treatment Plan"></a></td><td><a href="index.php?action=therapistEhsPlan&ehsflag=1&path=my_patient"><img src="' . $imageAssignHeathTemplate . '" width="127" height="81" alt="Assign Plan"></a></td><td>';
        $sqlehs="SELECT * FROM addon_services WHERE clinic_id=".$clinicId;
        $queryehs=$this->execute_query($sqlehs);
        $rowehs=  $this->fetch_object($queryehs);
        if($rowehs->automatic_scheduling==1){
        $replace['automaticscheduling'] ='<a href="index.php?action=createautoschprogram"><img  alt="Create Automatic scheduling" src="images/create_autobtn.png"></a>';
        }else {
        $replace['automaticscheduling'] ='';
        }
        
        $replace['header'] = $this->build_template($this->get_template("header"));

        $replace['footer'] = $this->build_template($this->get_template("footer"));

        $replace['sidebar'] = $this->sidebar();

        $replies_count = 0;
        //start message
        $privateKey = $this->config['private_key'];

        //$patient=implode(',',$this->getProviderEHSPatients());
        if ($this->is_corporate($clinicId) == 1) {
            $patient = implode(',', $this->get_paitent_list($clinicId));
        } else {
            $patient = implode(',', $this->getProviderEHSPatients());
        }

        $where = " and mass_patient_id =-4 and mass_status=1 and sender_id in ({$this->getporviderlist()})";
        $query = "SELECT concat(AES_DECRYPT(UNHEX(name_first),'{$privateKey}'),' ',AES_DECRYPT(UNHEX(name_last),'{$privateKey}')) as from_name,
                AES_DECRYPT(UNHEX(m.subject),'{$this->config['private_key']}') as subject
                ,m.recent_date AS sent_date,m.patient_id,m.message_id, 
                replies,
                m.patient_id,
                m.subject,
                m.mass_patient_id,
                m.mass_status 
                FROM message m
                inner join user u on u.user_id = m.patient_id 
                WHERE m.parent_id = 0 {$where} 
                or ((mass_patient_id =-4 and replies !=0)) and sender_id in ({$this->getporviderlist()}) and m.message_id not in (select message_id from system_message where user_id IN({$this->getporviderlist()})) 
                ORDER BY m.message_id desc lIMIT 0,5";

        ####################### Start for counting replies #########################
        $query11 = "SELECT m.patient_id,m.message_id, 
                    (	
                          select count(*) from message_user mu
                          where  mu.message_id = m.message_id 
                          and mu.user_id = '{$this->userInfo('user_id')}' 
                    )
                    as replies
                    FROM message m
                    inner join user u on u.user_id = m.sender_id  
                    WHERE m.patient_id IN (
                        SELECT patient_id FROM therapist_patient WHERE therapist_id = '{$this->userInfo('user_id')}'
                    ) and m.parent_id = 0 and m.patient_id = '{$this->value('id')}'";
        //echo $query11;
        $result11 = $this->execute_query($query11);
        //$row11 = @mysql_fetch_array($result11);

        while ($row11 = @mysql_fetch_array($result11)) {
            $replies_count += $row11['replies'] > 0 ? ($row11['replies'] - 1) : $row11['replies'];
        }
        ####################### End for counting replies #########################
        $replies_count = 0;
        $result = $this->execute_query($query);
        if ($this->num_rows($result) > 0) {
            while ($row = $this->fetch_array($result)) {
                $data = array(
                    'style' => ($c++ % 2) ? "line1" : "line2",
                    'from_name' => "<a href='index.php?action=set_unread_message_ehs&message_id={$row['message_id']}'>" . $this->unread_message($row['message_id'], $row['from_name']) . "</a>",
                    'subject' => "<a href='index.php?action=set_unread_message_ehs&message_id={$row['message_id']}'>" . $this->unread_message($row['message_id'], $this->decrypt_data($row['subject'])) . "</a>",
                    'sent_date' => "<a href='index.php?action=set_unread_message_ehs&message_id={$row['message_id']}'>" . $this->unread_message($row['message_id'], $this->formatDateExtra($row['sent_date'], $this->userInfo('timezone'))) . "</a>",
                    'replies' => $row['replies']
                );
                $replies_count += $row['replies'];
                $replace['recent_message_record'] .= $this->build_template($this->get_template("recent_message_record"), $data);
            }
        }
        $replace['recent_message_record'] .= '</table>';
        //} 

        $head_arr['replies'] = $replies_count;
        // Recent message of Patient.
        $replace['recent_message_head'] = $this->build_template($this->get_template("recent_message_head"), $head_arr);

        // End of recent message list.
        /*         * ***************************************** */
        //start of article
       

        // show all the articles associated with patient.
        $replace['viewPatientArticleRecordHeader'] = '
        <span style="font-size: 110%;color: #ed1f24;font-weight: bold;" >Assigned Articles</span>
        <table border="0" cellpadding="2" cellspacing="1" width="100%"  class="list" style="margin-top:5px;" >
            <thead>
                <tr>
                    <th width="35%" class="nosort" style="white-space:nowrap;">Article Name</th>
                    <th width="30%" class="nosort" style="white-space:nowrap;">Headline</th>
                    <th width="8%" class="nosort" style="white-space:nowrap;text-align:right">&nbsp;</th>
                </tr>
            </thead>
        ';
        $replace['viewPatientArticleRecord'] = "";


        $therapistIds = $this->getporviderlist($clinicId); //print_r($therapistIds);
        $query = "(SELECT AR.article_id as articleID, NULL as plan_id,AR.article_name as article_name ,AR.headline as headline,PAA.patient_id as patient_id,PAA.ehsFlag as ehsFlag,PAA.patientArticleId as patientArticleId 
                 FROM article AR
                 LEFT JOIN patient_article PAA ON PAA.article_id = AR.article_id
                 WHERE PAA.therapistId in ({$therapistIds}) AND PAA.ehsFlag='1' AND PAA.patient_id IS NULL and PAA.status='1') UNION all 
         (SELECT RS.article_id as articleID,P.plan_id as plan_id,RS.article_name as article_name,RS.headline as headline,P.patient_id as patient_id,P.ehsFlag as ehsFlag, NULL as patientArticleId
                    from article RS
                    LEFT JOIN plan_article PA ON RS.article_id = PA.article_id
                LEFT JOIN plan P ON P.plan_id = PA.plan_id AND P.status = '1' AND P.ehsFlag='1' and  P.patient_id is null
                WHERE (P.user_id in ({$therapistIds})) )";


        $result = $this->execute_query($query);
        if ($this->num_rows($result) > 0) {
            while ($row = $this->fetch_array($result)) {
                $article_id = $row['articleID'];
                $data = array(
                    'article_id' => $article_id,
                    'article_name' => wordwrap($row['article_name'], 50, "<br/>", TRUE),
                    'headline' => wordwrap($row['headline'], 45, "<br/>", TRUE),
                    'actionOption' => $row['status'] == '1' ? $patientStatusCurrent : $patientStatusArchive,
                    'patientArticleId' => $row['patientArticleId']
                );
                $data['actionOption'] = $this->build_select_option($articleAddOption);
                $replace['viewPatientArticleRecord'] .= $this->build_template($this->get_template("viewPatientArticleRecord"), $data);
            }
        }

        $replace['viewPatientArticleRecord'] .= '</table>';

        //end of article
        //start of plan

        /*         * ***************************************** */

        // show all the plans associated with patient.
        $replace['viewPatientPlanRecordHeader'] = '
                <span style="font-size: 110%;color: #ed1f24;font-weight: bold;" >Assigned Plans</span>
                <table border="0" cellpadding="2" cellspacing="1" width="100%"  class="list" style="margin-top:5px;" >
                    <thead>
                        <tr>
                            <th width="35%" class="nosort" style="white-space:nowrap;">Treatment Plan Name</th>
                            <th width="20%" class="nosort" style="white-space:nowrap;">Status</th>
                            <th width="8%" class="nosort" style="white-space:nowrap;text-align:right">&nbsp;</th>
                        </tr>
                    </thead>';

        $replace['viewPatientPlanRecord'] = "";

        $therpaistId = $this->getProviderId($_SESSION['username']);

        $replace['clinicId'] = $clinicId;

        $replace['therpaistId'] = $therpaistId;

        if ($therpaistId != "" && is_numeric($therpaistId)) {
            $query = " select * from plan where status !=3 AND patient_id IS NULL AND clinicId = '{$clinicId}' AND ehsFlag = '1' and status not in ('4','-1') ORDER BY plan_id DESC";
            $result = $this->execute_query($query);
            if ($this->num_rows($result) > 0) {
                while ($row = $this->fetch_array($result)) {
                    $data = array(
                        'plan_id' => $row['plan_id'],
                        'plan_name' => $row['plan_name'],
                        'status' => $row['status'] == '1' ? 'Current' : ($row['status'] == '2' ? 'Archived' : 'Deleted Plan'),
                        'actionOption' => $row['status'] == '1' ? $patientStatusCurrent : $patientStatusArchive
                    );
                    $data['actionOption'] = $this->build_select_option($data['actionOption']);
                    $replace['viewPatientPlanRecord'] .= $this->build_template($this->get_template("viewPatientPlanRecord"), $data);
                }
            }
        }
        $replace['viewPatientPlanRecord'] .= '</table>';
        // End of plans list.
        // patient reminder List.
        $query = "select * from patient_reminder where  status = '1' AND ehsReminder = '1' AND clinicId = '{$clinicId}' AND parent_reminder_id = '0' order by patient_reminder_id desc";
        $result = $this->execute_query($query);

        if ($this->num_rows($result) > 0) {
            $replace['patient_reminder'] .= '<ol>';
            $cnt = 1;
            while ($row = $this->fetch_array($result)) {
                $row['cnt'] = $cnt++;
                $row['reminder'] = $this->decrypt_data($row['reminder']);
                $replace['patient_reminder'] .= $this->build_template($this->get_template("patient_reminder"), $row);
            }
            $replace['patient_reminder'] .= '</ol>';
        } else {
            $replace['patient_reminder'] = '<ul style="list-style:none;"><li style="padding-top:10px;padding-left:10px;">There are no current reminders.</li></ul>';
        }
        // End of patient reminder List.
        // Returns list of associated therapist.
        $replace['therapistName'] = $this->getAssociatedTherapist($this->value('id'));

        // For calender dates
        $replace['selected_dates'] = $this->selected_dates_ehs($clinicId);
        //$replace['selected_dates_set2'] = $this->selected_dates($this->value('id'),2);
        // End

        $replace['member_goal'] = $this->goalEhs();
        //$replace['goal_completed'] = $this->goal_completed_ehs($therpaistId);
        $replace['dob'] = $this->get_field($this->value('id'), "user", "dob");
        $replace['gender'] = $this->get_field($this->value('id'), "user", "gender");
        $replace['body'] = $this->build_template($this->get_template("viewPatient"), $replace);
        $replace['browser_title'] = "Tx Xchange: Mass Management of E-Health Service (EHS) Patients";

        //$AssignedPlans=$_SESSION['providerLabel']['Assigned Plans']!=''?$_SESSION['providerLabel']['Assigned Plans']:'Assigned Plans';
        $AssignedPlans = " Assigned Health Video Series";

        $replace['labelAssignedPlans'] = $AssignedPlans;
        //

        $message = $this->value('mass');
        if (isset($message) && $message != '')
            $replace['mass_message_message'] = "Your " . $this->value('mass') . " has been queued for processing. This process could take a from a few minutes to a few hours.<tr><td>&nbsp;</td></tr>";

        $ehsunsub = $this->value('ehsunsub');
        $replace['ehsunsub'] = $ehsunsub;
        if (isset($ehsunsub) && $ehsunsub == '0') {
            //$replace['mass_message_message']="No EHS patient(s) subscribed with this clinic, So Mass assignment can not be made for this clinic.<tr><td>&nbsp;</td></tr>";

            $mass_message_message = "<script>GB_showCenter('EHS patient subscription', '/index.php?action=noTherapistEhsPatient',140,500);</script>";

            $replace['mass_message_message'] = $mass_message_message;
        }

        $msgFlag = $this->value('msgFlag');
        $replace['msgFlag'] = $msgFlag;
        if (isset($msgFlag) && $msgFlag == '1') {
            $removePlanAricleMsg = "<script>GB_showCenter('Plan Article', '/index.php?action=planArticleAssociate2',140,500);</script>";
            $replace['removePlanAricleMsg'] = $removePlanAricleMsg;
        }

        $this->output = $this->build_template($this->get_template("main"), $replace);
        $this->output .= $get_satisfaction;
    }

    function planArticleAssociate2() {
        $replace['removePlanAricleMsg'] = "Article cannot be removed as it is associated with assigned plan.";
        $replace['body'] = $this->build_template($this->get_template("planArticleAssociate"), $replace);
        $this->output = $this->build_template($this->get_template("main"), $replace);
    }

    /**
     * This function will return goal completed.
     */
    function goal_completed_ehs($userId = "") {
        if ($userId == "") {
            $userId = $this->value('userId');
        }
        $query = " select count(*) from patient_goal where created_by = '{$userId}' and (status = '1' or status = '2') and parent_goal_id = '0' and ehsGoal = '1' order by created_on desc ";
        $result = @mysql_query($query);
        $total_goals = 0;
        if ($row = @mysql_fetch_array($result)) {
            $total_goals = $row[0];
        }
        $query = " select count(*) from patient_goal where created_by = '{$userId}' and status = '2' and parent_goal_id = '0' and ehsGoal = '1' order by created_on desc ";
        $result = @mysql_query($query);
        $total_goals_completed = 0;
        if ($row = @mysql_fetch_array($result)) {
            $total_goals_completed = $row[0];
        }
        $percentage = 0;
        if ($total_goals > 0) {
            $percentage = round((($total_goals_completed / $total_goals) * 100), 0);
        }
        if ($this->value('userId') != "") {
            echo $percentage;
            return;
        }
        return $percentage;
    }

    /**
     *  Add Reminder in reminder_schedule table.
     *  @access public.
     */
    function addReminderScheduleEhs() {
        if ($this->value('therpaistId') != "" && $this->value('scheduled_date') != "") {
            $status = $this->userInfo("status", $this->value("therpaistId"));
            $clinicId = $this->clinicInfo('clinic_id');
            if ($status == "1" || $status == "2") {
                $reminder_arr = array(
                    'patient_id' => '0',
                    'clinicId' => $clinicId,
                    'therapistId' => $this->value('therpaistId'),
                    'parent_reminder_schedule_id' => '0',
                    'scheduled_date' => $this->value('scheduled_date'),
                    'reminderEhsFlag' => '1',
                    // 'scheduler_status' => '1',
                    'status' => 'pending',
                    'reminder_set' => $this->value('reminder_set')
                );
                $this->insert("reminder_schedule", $reminder_arr);
                $insertId = $this->insert_id();


                if ($this->is_corporate($clinicId) == 1) {
                    $ehsPatientArr = $this->get_paitent_list($clinicId);
                } else {
                    $ehsPatientArr = $this->getProviderEHSPatients($this->value('patient_id'));
                }
                $patientCount = count($ehsPatientArr);
                $pat = 0;
                //echo $this->value('scheduled_date');     
                while ($pat < $patientCount) {
                    $date = substr($this->value('scheduled_date'), 0, 10);
                    $sql = "select * from reminder_schedule where  DATEDIFF(DATE_FORMAT(scheduled_date,'%Y-%m-%d'),'{$date}') =0 and reminder_set=1 and patient_id=" . $ehsPatientArr[$pat];
                    $res = $this->execute_query($sql);
                    $num = $this->num_rows($res);
                    if ($num == 0) {
                        $reminder_arr1 = array(
                            'patient_id' => $ehsPatientArr[$pat],
                            'therapistId' => $this->value('therpaistId'),
                            'parent_reminder_schedule_id' => $insertId,
                            'scheduled_date' => $this->value('scheduled_date'),
                            'reminderEhsFlag' => '1',
                            'status' => 'pending',
                            'reminder_set' => $this->value('reminder_set')
                        );
                        $this->insert("reminder_schedule", $reminder_arr1);
                    }
                    $pat++;
                }

                $this->output = true;
                return;
            } else {
                $this->output = false;
                return;
            }
        } else {
            $this->output = false;
            return;
        }
    }

    /**
     * 
     */
    function removeReminderScheduleEhs() {
        if ($this->value(scheduled_date) != "") {
            $arr = explode(",", $this->value(scheduled_date));
            foreach ($arr as $value) {
                $sql = "SELECT * FROM reminder_schedule WHERE scheduled_date= '{$value}' AND  clinicId = '{$this->value('clinicId')}'";
                $res = $this->execute_query($sql);
                if ($this->num_rows($res) > 0) {
                    $row = $this->fetch_object($res);
                    echo $sql1 = "delete from reminder_schedule where parent_reminder_schedule_id=" . $row->reminder_schedule_id;
                    $this->execute_query($sql1);
                }

                $where = " clinicId = '{$this->value('clinicId')}' && scheduled_date = '{$value}' ";
                $this->db_delete("reminder_schedule", $where);
            }
        }

        $this->output = $this->value(scheduled_date);
    }

    function noTherapistEhsPatient() {
        $replace['noEHSMessage'] = "There are currently no patients subscribed to your EHS. EHS Mass Management can only be used once patients have started to subscribe to your EHS.";

        $replace['body'] = $this->build_template($this->get_template("noTherapistEhsPatient"), $replace);
        $this->output = $this->build_template($this->get_template("main"), $replace);
    }

    function troublesoot_therapist() {
        $replace['body'] = $this->build_template($this->get_template("troublesoot_therapist"), $replace);
        $this->output = $this->build_template($this->get_template("main"), $replace);
    }

    function check_call_status($patainent_id, $provider_id) {
        $call_staus = 0;
        $query = " select * from opentok_session where patainent_id='{$patainent_id}' AND provider_id!='$provider_id' AND call_status='1' AND creation_date >= DATE_SUB( NOW(), INTERVAL 24 HOUR)  ORDER BY id DESC";
        $result = $this->execute_query($query);
        if ($this->num_rows($result) > 0) {
            $call_staus = 1;
        }

        return $call_staus;
    }

}

$obj = new therapist();
?>

