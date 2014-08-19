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

class patientdashboard extends application
{

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
            $str = "patientdashboard"; //default if no action is specified
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
          //  $this->call_patient_gui();
            //$this->call_gui;
            $str = $str . "()";
            eval("\$this->$str;");
        }
        else
        {
            $this->output = $this->config['error_message'];
        }
//$this->printR($this->userInfo());
        $this->display();
        /*
          $str = $str."()";
          eval("\$this->$str;");
          $this->display();
         */
    }

    /**
     * This chage have been made on data 20-1-2014 by Rohit Mishra for Ticket TXM-33
     */
    function addpatientpainlevel()
    {
        $userid = $this->userInfo('user_id');
        $painlevel = $_REQUEST['painlevel'];
        $query = "INSERT INTO  patient_pain_level(patient_id,painlevel,creation_on,modification_on) VALUES('$userid','$painlevel',now(),now())";
        $result = @mysql_query($query);

        if($result)
        {
            //echo "add";
        }
        else
        {
            //echo "fail";
        }
    }

    /**
     * This function prepares an HTML of all the goals of current logged in patient
     * 
     * @return Template Renders the goals template
     */
//    function patientlogin()
//    {
//        $variables = array();
//        $this->output = $this->build_template($this->get_template('patient_dashboard_login'), $variables);
//    }

    /**
     * Main Dashboard page
     *
     * @return Template Renders the Dashboard template containing all the modules
     */
    function patientdashboard()
    {
        $userInfo = $this->userInfo();

        $replace['profile_picture'] = 'assets/img/avatar.jpg';
        $pimage = $userInfo['profile_picture'];
        if(isset($pimage) && !empty($pimage))
        {
            $replace['profile_picture'] = 'asset/images/profilepictures/'.$userInfo['user_id'].'/'.$userInfo['profile_picture'];
        }
        
        $replace['meta_head'] = $this->build_template($this->get_template("meta_head"));
        $replace['header'] = $this->build_template($this->get_template("patient_dashboard_header"),$replace);
        $replace['footer'] = $this->build_template($this->get_template("patient_dashboard_footer"));
        $replace['currentday'] = $this->getPaitentCurrentDay($this->userInfo('user_id'));
        $replace['currentweek'] = round($this->getPaitentCurrentDay($this->userInfo('user_id'))/7);
        $replace['currentDate'] = date("l, F d, Y");
        $replace['startdate'] = $this->formatDate($userInfo['creation_date'], 'Y-m-d');
        $replace['currentdate'] = $this->formatDate(date('Y-m-d'), 'Y-m-d');
        
        $userId = $userInfo['user_id'];
        $replace['userName'] = $userInfo['name_first'];
        $replace['userLastName'] = $userInfo['name_last'];
        $replace['lastlogin'] = $userInfo['last_login'];
		
		
		
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
		  
		$report_query ="INSERT INTO page_visits_report(page,ip,user_id,clinic_id,created) VALUES('dashboard','".$ip."','".$user_id."','".$clinic_id."',now())";
	
		$result1 = @mysql_query($report_query);
		
		/*
		*  End 
		*/
		
		
		
		
		
        
        //variables for triggering firsttimelogin/user flow
        //status : pending or complete
        $patienthistory = $this->fetch_all_rows($this->execute_query("select * from patient_history where patient_id = {$userId}"));
        
        $replace['passwordchangestatus'] = 'pending';
        $replace['oswestrysurvey1status'] = 'pending';
        $replace['oswestrysurvey2status'] = 'pending';
        $replace['oswestrysurvey3status'] = 'pending';
        $replace['oswestrysurvey4status'] = 'pending';
        $replace['readyforchangesurveystatus'] = 'pending';
        
        if(count($patienthistory) > 0)
        {
            foreach($patienthistory as $history)
            {
//                $status = ($history['action_status'] == NULL) ? 'pending' : $history['action_status'];
                
                /*
                 * void surveys are deferred surveys which user hasn't attended on time., 
                 * so we mark them as complete and ignore them
                 */
                if( (stristr($history['action_type'], 'void')) || ($history['action_type'] == NULL) || ($history['action_status'] == 'complete') )
                {
                    $status = 'complete';
                }
                else
                {
                    $status = 'pending';
                }
                               
                switch($history['action'])
                {
                    case 'password change':
                        $replace['passwordchangestatus'] = $status;
                        break;
                    case 'oswestry survey 1':
                        $replace['oswestrysurvey1status'] = $status;
                        break;
                    case 'oswestry survey 2':
                        $replace['oswestrysurvey2status'] = $status;
                        break;
                    case 'oswestry survey 3':
                        $replace['oswestrysurvey3status'] = $status;
                        break;
                    case 'oswestry survey 4':
                        $replace['oswestrysurvey4status'] = $status;
                        break;
                    case 'ready for change survey':
                        $replace['readyforchangesurveystatus'] = $status;
                        break;
                            
                    default:
                        //make all variables to pending then
                        $replace['passwordchangestatus'] = 'pending';
                        $replace['oswestrysurvey1status'] = 'pending';
                        $replace['oswestrysurvey2status'] = 'pending';
                        $replace['oswestrysurvey3status'] = 'pending';
                        $replace['oswestrysurvey4status'] = 'pending';
                        $replace['readyforchangesurveystatus'] = 'pending';
                        break;
                }
                
            }
        }
        /*
         * 
         * code for time line video and article
         */
        $videos = $this->getAllDayPaitentVideo($userId);
        $videolist = array();
        $i = 0;
        $previousday=0;
        $daycount=1;
        foreach($videos as $keys => $values)
        {
            
            if($previousday!=$videolist[$i]['day']){
                 $daycount=1;
            }
           // $this->printR($values);
            if($values['video'] != '')
            {
                if($values['lrb']>0){
			
			$lrb= $this->config['lrb'][$values['lrb']];
			}else{
			$lrb='None';
			}
                        if($values['sets']=='')
                                $values['sets']=0;
                        if($values['reps']=='')
                                $values['reps']=0;
                         if($values['hold']=='')
                                $values['hold']=0;
			
		$set = array('Sets'=>$values['sets'],'Reps'=>$values['reps'],'Hold'=>$values['hold'],'Side'=>$lrb);
                $title="Day ".$values['assignday'].", Video ".$daycount.", ".str_replace('"', "", $values['treatment_name']);
                $videolist[$i]['day'] = ($values['assignday'] == null) ? 0 : $values['assignday'];
                $videolist[$i]['label'] = str_replace('"', "", $values['treatment_name']);
                $videolist[$i]['title'] = $title;
                $videolist[$i]['type'] = 'posture';
                $videolist[$i]['kind'] = 'video';
                $filename = substr($values['video'], 0, -4);
                $newfile = $filename . ".mp4";
                $url = $this->config['telespine_login'] . '/' . $this->config['tx']['treatment']['media_url'] . $values['treatment_id'] . '/' . $newfile;
                $videolist[$i]['media'] = $url;
                $videolist[$i]['videoprop'] = $set;
                $videolist[$i]['id'] = $values['plan_treatment_id'];
                $imageurl = $this->config['telespine_login'] . $this->config['tx']['treatment']['media_url'] . $values['treatment_id'] . '/videolarge.jpg';
                $videolist[$i]['canvasurl'] = $imageurl;
                $i++;
                $daycount++;
            }
            
            $previousday=$videolist[$i]['day'];
        }
        //$this->printR($videos);

        $article = $this->getAllDayPaitentArticle($userId);
       // $this->printR($article);
        $articlelist = array();
        foreach($article as $keys => $values)
        {

            $articlelist[$i]['day'] = ($values['assignday'] == null) ? 0 : $values['assignday'];
            $articlelist[$i]['label'] = $values['article_name'];
            $articlelist[$i]['type'] = 'mobility';
            $articlelist[$i]['kind'] = 'article';
            $url = $this->config['telespine_login'] . '/' . $this->config['tx']['article']['media_url'] . $values['path'];
            $articlelist[$i]['media'] = $url;
            $articlelist[$i]['id'] = $values['articleID'];
            if($values['plan_id']=''){
                $articlelist[$i]['plan_id']=0;
            }else {
                $articlelist[$i]['plan_id']=$values['plan_id'];
            }
            $i++;
        }
        /*echo '<pre>';
        print_r($articlelist);
        print_r($videolist);*/
        $activities = $this->jsonencode(array_merge($videolist,$articlelist));
        $replace['activities'] = $activities;
        /*
         * 
         * code Edd for time line video and article
         */
        
        /*
         * getting the patient pain level data 
         */
        $painlevel_sql = " SELECT * FROM patient_pain_level WHERE patient_id='$userId'";
        $scorePS1 = array();

        $resultpainlevel = @mysql_query($painlevel_sql);

        if(@mysql_num_rows($resultpainlevel) > 0)
        {
            while($rowpainlevel = @mysql_fetch_array($resultpainlevel))
            {
                $scorePS1[] = $rowpainlevel['painlevel'];
            }
        }


        if(count($scorePS1) > 1)
            $scorePS1_str = implode(",", $scorePS1);
        else
            $scorePS1_str = "0," . $scorePS1[0];
        
        $replace['painscorestring'] = $scorePS1_str;
        
        
       // $this->printR($replace['painscorestring'], $scorePS1);


        $lastpainlevel = $this->getPaitentLastPainLevel($userId);

        $replace['lastpainlevel'] = $lastpainlevel['last_pain_level'];
        $replace['lastpainlevelupdate'] = $lastpainlevel['date'];
		
		
		$replace['theme'] ="default";
		 $sql="SELECT * FROM patient_application_preferences WHERE user_id='".$this->userInfo('user_id')."'";
		 $rs = $this->execute_query($sql);
		$num_rows = $this->num_rows($rs);
		
		if($num_rows > 0){
		$row = $this->populate_array($rs);
		$replace['theme'] = $row['theme'];
		
		}

        $this->output = $this->build_template($this->get_template("patient_dashboard"), $replace);
    }

    /**
     * This function prepares an HTML of all the goals of current logged in patient
     * 
     * @return Template Renders the goals template
     */
    function patientgoals()
    {
        $userId = $this->userInfo('user_id');

        $resultarray = $this->getGoals($userId);

        $totalgoals = count($resultarray);
        $completedgoals = $this->getCompletedGoals($userId);

        if($totalgoals > 0)
        {
            foreach($resultarray as $row)
            {
                $strike = "";
                $checked = "fa fa-square-o";
                $opacity = '';

                if($row['status'] == 2)
                {
                    $opacity = 'opacity: 0.45;';
                    $strike = $opacity . 'text-decoration: line-through';
                    $checked = 'fa fa-check-square-o';
                }

                $replace['goals'] .= "<li id='goal_{$row['patient_goal_id']}' style='min-height:40px;'>
                                        <span class='todo-actions goal-actions' style='$opacity'>
                                            <a href='javascript:void(0);' id='{$row['patient_goal_id']}'> 
                                                <i onclick='App.updateGoal(event);' id='Goal{$row['patient_goal_id']}' class='{$checked}'></i>
                                            </a> 
                                        </span>
                                        <span id='span_{$row['patient_goal_id']}'  style='{$strike}' class=\"desc\">{$row['goal']}</span> </li>
                                      </li>";
            }

            $replace['goalspercentage'] = ($completedgoals == 0) ? 0 : $this->calculatePercentage($completedgoals, $totalgoals);
            $replace['totalgoals'] = $totalgoals;
            $replace['completedgoals'] = $completedgoals;
            
        }
        else
        {
            $replace['goalspercentage'] = 0;
            $replace['totalgoals'] = 0;
            $replace['completedgoals'] = 0;
        }
        $totallogins = $this->fetch_all_rows($this->execute_query("SELECT COUNT('login_history_id') as totallogins FROM login_history WHERE user_id = {$userId}"));
        $replace['totallogins'] = $totallogins[0]['totallogins'];

        $this->output = $this->build_template($this->get_template('patientgoals'), $replace);
    }

    /**
     * AJAX function to change the password
     */
    function changepass_firsttime()
    {
        $userId = $this->userInfo('user_id');
        
        $result = $this->update('user', array('password' => $this->value('password'), 'modified' => date('Y-m-d H:i:s')), " user_id = ".$userId);
        
        if($result === TRUE)
        {
            //this will prevent user from being logged out automatically. (as it happens in old implementation)
            $_SESSION['password'] = $this->value('password');
            
            //update the patient_history table with "password change" action to 'complete'
            $data = array(
                'patient_id' => $userId,
                'action_type' => 'first time login',
                'action' => 'password change', 
                'action_status' => 'complete',
                'action_time' => date('Y-m-d H:i:s')
                );
            $this->insert('patient_history', $data);
            
            echo "{success:true, message:'Password updated successfully'}";
        }
        else
        {
            echo "{success:false, message:'Password update failure'}";
        }
    }

    /**
     * This function adds a goal via an ajax call
     * 
     * @return string Containing HTMl code having newly inserted goal id.
     */
    function addgoal()
    {
        $goal = $this->value('goal');

        $user_id = $this->userInfo('user_id');
		
		 $clinic_id = $this->get_clinic_info($user_id);

        if(isset($goal) && $goal != "")
        {
            $goal_arr = array(
                'goal' => $goal,
                'status' => 1,
                'created_by' => $user_id,
				'clinicId' => $clinic_id
            );

            $this->insert('patient_goal', $goal_arr);
            $newlyinsertedgoalid = $this->insert_id();

            echo "<li id='goal_{$newlyinsertedgoalid}' style=\"min-height:40px;\"> 
                      <span class=\"todo-actions goal-actions\"> 
                          <a href=\"javaScript:void(0);\" id=\"{$newlyinsertedgoalid}\">
                              <i onclick='App.updateGoal(event);' id=\"Goal{$newlyinsertedgoalid}\" class=\"fa fa-square-o\"></i>
                          </a> 
                          </span> 	
                      <span id='span_{$newlyinsertedgoalid}' class=\"desc\">{value}</span> 
                  </li>";
        }
        else
        {
            echo "Goal is blank";
        }
    }

    /**
     * Returns all goals of a patient
     * 
     * @param type $patiendid
     * @return Array An array of all goals of a patient
     */
    public function getGoals($patiendid)
    {
        $query = "select * from patient_goal where created_by = '{$patiendid}' and (status = 1 or status = 2) order by created_on desc ";

        $resultset = $this->execute_query($query);
        return $this->fetch_all_rows($resultset);
    }

    /**
     * Calculates the percentage
     * 
     * @param Int score obtained
     * @param Int total scores
     * @return Int rounded value
     */
    public function calculatePercentage($completedgoals, $totalgoals)
    {
        return @round(($completedgoals / $totalgoals) * 100, 0, PHP_ROUND_HALF_UP);
    }

    /**
     * Calculates the completed goals percentage
     * 
     * @param Int patientid
     * @return Int percentage of goals completed
     */
    public function getGoalsCompletedPercentage($userid)
    {
        $resultarray = $this->getGoals($userid);

        $totalgoals = count($resultarray);

        return $this->calculatePercentage($this->getCompletedGoals($userid), $totalgoals);
    }

    /**
     * Returns the completed goals of a patient
     * 
     * @param type $userid
     * @return int Completed goals
     */
    public function getCompletedGoals($userid)
    {
        $sql = "SELECT count(patient_goal_id) as completedgoals FROM patient_goal WHERE status = 2 AND created_by = {$userid}";

        $completedgoals = $this->fetch_all_rows($this->execute_query($sql));

        return $completedgoals[0]['completedgoals'];
    }

    /**
     * Updates a goal via an ajax call
     * 
     * @return int Echoes the completed goals of a patient
     */
    function updategoal()
    {
        $goalid = $this->value('id');
        $status = $this->value('status');
        $userdetails = $this->userInfo();
		
		
		
		$application_path = $this->config['application_path'];
		$telespine_id = $this->config['telespineid'];
		
		$business_url = $this->config['business_telespine']; 
		$support_email = $this->config['email_telespine'];
		$images_url=$this->config['images_url'];
		$data['images_url'] = $images_url;
		$data['support_email'] = $support_email ;
                $data['loginurl']=$this->config['telespine_login'];
		$result = $this->execute_query("UPDATE patient_goal SET status = {$status},update_date=now() WHERE patient_goal_id = {$goalid}");
		
		
		if($status==2){
		$templatePath = $application_path."mail_content/telespine/goal_completed.php";
		$fullname = $userdetails['name_first'].'  '.$userdetails['name_last'];
		$data['fullname'] = $fullname;
		$message = $this->build_template($templatePath,$data);
		$data['support_email'] = $support_email;
		
		$to = $fullname.'<'.$userdetails['username'].'>';
		$subject ="Telespine Update - Good for You!";
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
		$headers .= "From: Telespine Support<".$support_email.">" . "\n";
		$returnpath = "-f".$support_email;
		@mail($to, $subject, $message, $headers, $returnpath); 
		}
		

        echo $this->getCompletedGoals($userdetails['user_id']);
    }

    /**
     * Message center section
     * displays the notifications to current logged in user
     * 
     * @return Template Renders the messagecenter template
     */
    function messagecenter()
    {
        $variables = array();
        $userdetails = $this->userInfo();

        $messages = $this->fetch_all_rows($this->execute_query("SELECT * FROM patient_message_center WHERE patient_id = '{$userdetails['user_id']}'"));

        if(count($messages) > 0)
        {
            $variables['messages'] = "<p><b>You have a New Message!</b></p>";
            foreach($messages as $message)
            {
                $variables['messages'] .= "<p>{$message['message']}</p>";
            }
        }
        else
        {
            $variables['messages'] = "<p>You have no messages.</p>";
        }

        $this->output = $this->build_template($this->get_template('messagecenter'), $variables);
    }

    /**
     * Healthy Back Habits section
     * displays the reminders to current logged in patient
     * 
     * @return Template Renders the Healthy Back Habits template
     */
    function healthybackhabits()
    {
        $variables = array();

        $userdetails = $this->userInfo();
        $currentday = $this->getPaitentCurrentDay($this->userInfo('user_id'));
        
        $sql = "SELECT 
                    patient_reminder_id,
                    creation_date,
                    status,
                    AES_DECRYPT(UNHEX(reminder), '{$this->config['private_key']}') as reminder,
                    scheduler_status,
                    reminder_set
                FROM 
                    patient_reminder 
                WHERE 
                    patient_id = '{$userdetails['user_id']}' and assignday='{$currentday}' ORDER BY patient_reminder_id DESC";
                    
        $reminders = $this->fetch_all_rows($this->execute_query($sql));

        foreach($reminders as $reminder)
        {
            $variables['reminders'] .= "<li style=\"padding-left:12px\"><span class=\"desc\">" . $reminder['reminder'] . "</span> </li>";
        }

        $this->output = $this->build_template($this->get_template('healthybackhabits'), $variables);
    }

    /**
     * Functional Score section
     * displays the functional score of current logged in patient
     * 
     * @return Template Renders the Functional score template
     */
    function functionalscore()
    {
        $variables = array();
        $userdetails = $this->userInfo();

        $oswestryscores = $this->getOutcomeMeasureScore(1, $userdetails['user_id']);
        $oswestryscorestring = $this->prepareFunctionalScoreDataString($oswestryscores);

        $variables['oswestryscores'] = $oswestryscorestring;

        $this->output = $this->build_template($this->get_template('functionalscore'), $variables);
    }
    
    /**
     * Prepares the data for functional score section on dashboard
     * 
     * @param Array Associative array of oswestryscores
     * @return string like this [[48, 30],[42, 60],[8, 10],[62, 60]] or []
     */
    public function prepareFunctionalScoreDataString(array $oswestryscores)
    {
        //initial score string
        $scorestring = "[]";
        
        //no scores present in the database
        if(!is_array($oswestryscores) || (count($oswestryscores) == 0))
        {
            $userdetails = $this->userInfo();
            $therapistid = $this->fetch_all_rows($this->execute_query("select therapist_id from therapist_patient where patient_id = '{$userdetails['user_id']}' and status = 1"));
            
            //calculate how many surveys are due
            $patientcurrentday = $this->getPaitentCurrentDay($userdetails['user_id']);
            
            if($patientcurrentday >= 1 && $patientcurrentday < 18)
            {
                $scorestring = "[]";
            }
            else if($patientcurrentday >= 18 && $patientcurrentday < 37)
            {
                //mark survey 1 as void only if it is not complete
                $this->markOswestrySurveyVoid(1, $userdetails['user_id'], $therapistid[0]['therapist_id']);

                $scorestring = "[[0,0]]";
            }
            else if($patientcurrentday >= 37 && $patientcurrentday < 53)
            {
                //mark survey 1 as void only if it is not complete
                $this->markOswestrySurveyVoid(1, $userdetails['user_id'], $therapistid[0]['therapist_id']);

                //mark survey 2 as void only if it is not complete
                $this->markOswestrySurveyVoid(2, $userdetails['user_id'], $therapistid[0]['therapist_id']);

                $scorestring = "[[0,0],[0,0]]";
            }
            else if($patientcurrentday >= 53)
            {
                //mark survey 1 as void only if it is not complete
                $this->markOswestrySurveyVoid(1, $userdetails['user_id'], $therapistid[0]['therapist_id']);

                //mark survey 2 as void only if it is not complete
                $this->markOswestrySurveyVoid(2, $userdetails['user_id'], $therapistid[0]['therapist_id']);

                //mark survey 3 as void only if it is not complete
                $this->markOswestrySurveyVoid(3, $userdetails['user_id'], $therapistid[0]['therapist_id']);

                $scorestring = "[[0,0],[0,0],[0,0]]";
            }
            else
            {
                $scorestring = "[]";
            }
        }
        else
        {
            $scorestring = "[";
            $string = "";
            foreach($oswestryscores as $score)
            {
                if($score == "")
                {
                    $score['score'] = 0;
                    $score['score2'] = 0;
                }

                $string .= "[{$score['score']}, {$score['score2']}],";
            }
            $scorestring .= rtrim($string, ",");
            
            $scorestring .= "]";
        }

        return $scorestring;
    }
    
    /**
     * Returns the scores of surveys taken by a patient
     * 
     * @param type $omFormType
     * @param type $patient_id
     * @return Array Containing the latest 4 scores and pain level of a patient
     */
    public function getOutcomeMeasureScore($omFormType, $patient_id)
    {
        $graphScore = array();
        
        if(is_numeric($omFormType))
        {
            $query = " select * from patient_om where patient_id = '{$patient_id}' and om_form = '{$omFormType}' and status = 2 order by submited_on asc limit 0,4";
            
            $result = $this->execute_query($query);
            
            $rows = $this->num_rows($result);
            if($rows)
            {
                /*
                * get oswestry values from database
                * currently more than 4 scores are present in database, but for telespine it will not be more than 4
                */
                for($count = 1; $count <= $rows; $count++)
                {
                    if($row = @mysql_fetch_array($result))
                    {
                        $graphScore[$count]['score'] = ($row['score'] == NULL)?0:$row['score'];
                        $graphScore[$count]['score2'] = ($row['score2'] == NULL)?0:$row['score2'];
                    }
                    else
                    {
                        $graphScore[$count]['score'] = 0;
                        $graphScore[$count]['score2'] = 0;
                    }
                }
            }
        }

        return $graphScore;
    }
    
    /**
     * TODO, if agreement page is not having password change functionality in it then we need to update the
     * patient_history table for maintaining the status of surveys, 
     * otherwise we will fetch password change from user->agreement column and oswestry details from  patient_om table
     * 
     * ajax action to submit the oswestry survey
     * 
     * @return array
     */
    function submitoswestrysurvey()
    {
        $userdetails = $this->userInfo();
        
        $therapistid = $this->fetch_all_rows($this->execute_query("select therapist_id from therapist_patient where patient_id = '{$userdetails['user_id']}' and status = 1"));
        
        $oswestrydata = $this->jsondecode($_REQUEST['oswestrydata'], true);
        
        $score = 0;
        $count = 0;
        foreach($oswestrydata as $key => $value)
        {
            if($key == 'painscale')
                continue;
            $score += ($value - 1);
            $count++;
        }
        
        /*
         * algorithm for calculating the score
         */
        $score = round(($score / (5 * $count)) * 100);
        $painscale = ($oswestrydata['painscale']) * 10;
        
        //determine which oswestry survey is taken
        
        /*
         * The Oswestry Outcome Measure (i.e. survey) will be assigned at the 
         * following intervals automatically. Day 1, Day 18, Day 37, Day 53.
         */
		  $surveynumber=1;
        if($_REQUEST['surveytakenon'] >= 1 && $_REQUEST['surveytakenon'] < 18)
        {
            $action = 'oswestry survey 1';
			$surveynumber=1;
        }
        else if($_REQUEST['surveytakenon'] >= 18 && $_REQUEST['surveytakenon'] < 37)
        {
            $action = 'oswestry survey 2';
			$surveynumber=2;
            
            //mark survey 1 as void only if it is not complete
            $this->markOswestrySurveyVoid(1, $userdetails['user_id'], $therapistid[0]['therapist_id']);
        }
        else if($_REQUEST['surveytakenon'] >= 37 && $_REQUEST['surveytakenon'] < 53)
        {
            $action = 'oswestry survey 3';
			$surveynumber=3;
            
            //mark survey 1 as void only if it is not complete
            $this->markOswestrySurveyVoid(1, $userdetails['user_id'], $therapistid[0]['therapist_id']);
            
            //mark survey 2 as void only if it is not complete
            $this->markOswestrySurveyVoid(2, $userdetails['user_id'], $therapistid[0]['therapist_id']);
        }
        else if($_REQUEST['surveytakenon'] >= 53)
        {
            $action = 'oswestry survey 4';
            $surveynumber=4;
            //mark survey 1 as void only if it is not complete
            $this->markOswestrySurveyVoid(1, $userdetails['user_id'], $therapistid[0]['therapist_id']);

            //mark survey 2 as void only if it is not complete
            $this->markOswestrySurveyVoid(2, $userdetails['user_id'], $therapistid[0]['therapist_id']);
            
            //mark survey 3 as void only if it is not complete
            $this->markOswestrySurveyVoid(3, $userdetails['user_id'], $therapistid[0]['therapist_id']);
        }
        
        //check if the score has already been inserted in the database.
         $sqlforomcheck = "SELECT * FROM patient_om WHERE patient_id = {$userdetails['user_id']} AND filledagainstsurvey = '{$action}' AND om_form = '1'";
        
        //oswestry has already been taken, so returned string will be 
        $string = "";
       
        if($this->num_rows($this->execute_query($sqlforomcheck)) == 0)
		
        {
            //oswestry has not been taken

            //insert in patient_om table
            $formtype = 1; //for oswestry form type
            $patientomdata = array(
                'om_form' => $formtype,
                'score' => $score,
                'score2' => $painscale,
                'patient_id' => $userdetails['user_id'],
                'therapist_id' => $therapistid[0]['therapist_id'],
                'filledagainstsurvey' => $action,
                'status' => 2,
                'created_on' => date('Y-m-d H:i:s'),
                'submited_on' => date('Y-m-d H:i:s')
            );

            $this->insert('patient_om', $patientomdata);
            $newlyinsertedomid = $this->insert_id();
			

            //update the patient_history table
            $patienthistoryarr = array(
                'patient_id' => $userdetails['user_id'],
                'action' => $action,
                'action_status' => 'complete', 
                'action_type' => 'outcome measure',
                'action_time' => date('Y-m-d H:i:s')
            );
            $this->insert('patient_history', $patienthistoryarr);
            
            $string = "{$score},{$painscale}";
			
			//sending notification message
			
			
		$this->sendOswestryNotificion($surveynumber,$score, $userdetails, $therapistid[0]['therapist_id']);	
		$this->sendPainlevelNotificion($surveynumber,$oswestrydata['painscale'], $userdetails, $therapistid[0]['therapist_id']);	
			
			
			
			
			
			
			
        }
        
        echo $string;
    }
    
    /**
     * Marks the $oswestrysurveynumber as void in various database tables
     * 
     * @param Int $surveynumber The survey that is to marked as void
     * @param Int $patientid Patient's ID
     * @param Int $therapistid Patient's therapist ID
     */
    public function markOswestrySurveyVoid($surveynumber, $patientid, $therapistid)
    {
        //mark survey as void only if it is not complete
        $sql = "SELECT * FROM patient_history WHERE patient_id = '{$patientid}' AND action = 'oswestry survey {$surveynumber}' AND action_status = 'complete'";

        $result = $this->execute_query($sql);

        //survey is not taken, mark it as void
        if(!$this->num_rows($result))
        {
            $this->insert('patient_history', array(
                'patient_id' => $patientid,
                'action' => "oswestry survey {$surveynumber}",
                'action_type' => 'void outcome measure',
                'action_status' => 'complete',
                'action_time' => date('Y-m-d H:i:s')
            ));

            //insert in om table blank data for oswestry survey 1
            $this->insert('patient_om', array(
                'om_form' => '1',
                'patient_id' => $patientid,
                'therapist_id' => $therapistid,
                'filledagainstsurvey' => "oswestry survey {$surveynumber}",
                'status' => 2,
                'created_on' => date('Y-m-d H:i:s'),
                'submited_on' => date('Y-m-d H:i:s')
            ));
        }
    }

    /**
     * Functional Score section
     * displays the functional score of current logged in patient
     * 
     * @return Template Renders the Functional score template
     */
    function todaysactivities()
    {
        $variables = array();
        $userdetails = $this->userInfo();
        $paitent_id = $userdetails['user_id'];
        $day = $this->getPaitentCurrentDay($paitent_id);
        $day = ($day % 7) + 1;
        $variables['currday'] = $day;

        $video = $this->getPaitentVideoByDay($paitent_id, $day);
        $total_count = count($video);
        $variables['total_items'] = $total_count;
        //print_r($video);

        $this->output = $this->build_template($this->get_template('todaysactivities'), $variables);
    }

    /**
     * Current pain level section
     * displays the current pain level of current logged in patient
     * 
     * @return Template Renders the Current Pain level template
     */
//    function currentpainlevel()
//    {
//        $variables = array();
//
//        $this->output = $this->build_template($this->get_template('currentpainlevel'), $variables);
//    }
    
    /**
     * Ready for change survey submit action
     */
    function submitreadyforchangesurvey()
    {
        $userdetails = $this->userInfo();
        
        $r4csurveydata = $this->jsondecode($_REQUEST['r4csurveydata'], true);
        
        $i = 1;
        $arr = array(
            'patient_id' => $userdetails['user_id'],
            'created' => date('Y-m-d H:i:s')
        );
        foreach($r4csurveydata as $question=>$answer)
        {
            $arr["question$i"] = $question;
            $arr["answer$i"] = $answer;
            
            $i++;
        }
        
        $this->insert('patient_survey_ready_for_change', $arr);
        
        //update the patient_history table
        $patienthistoryarr = array(
            'patient_id' => $userdetails['user_id'],
            'action' => 'ready for change survey',
            'action_status' => 'complete',
            'action_time' => date('Y-m-d H:i:s')
        );

        $this->insert('patient_history', $patienthistoryarr);
        
        echo $this->insert_id();
    }

    /**
     * Displays the faqs page
     * 
     * @return Template Renders the FAQs page
     */
    function faqs()
    {
        $variables = array();
		
		$variables['profile_picture'] = 'assets/img/avatar.jpg';
		$pimage = $this->userInfo('profile_picture');
		if(isset($pimage) && !empty($pimage)){
		$variables['profile_picture'] = 'asset/images/profilepictures/'.$this->userInfo('user_id').'/'.$this->userInfo('profile_picture');
		}
		
		$variables['theme'] ="default";
		 $sql="SELECT * FROM patient_application_preferences WHERE user_id='".$this->userInfo('user_id')."'";
		 $rs = $this->execute_query($sql);
		$num_rows = $this->num_rows($rs);
		
		if($num_rows > 0){
		$row = $this->populate_array($rs);
		$variables['theme'] = $row['theme'];
		
		}

        $variables['meta_head'] = $this->build_template($this->get_template("meta_head"),$variables);
        $variables['header'] = $this->build_template($this->get_template("patient_dashboard_header"),$variables);
        $variables['footer'] = $this->build_template($this->get_template("patient_dashboard_footer"));

        $this->output = $this->build_template($this->get_template('faqs'), $variables);
    }

	/**
     * Send the oswestry notification to client
     * 
     * @param Int $surveynumber The survey that is to marked as void
     * @param Int $patientid Patient's ID
     * @param Int $therapistid Patient's therapist ID
     */
    public function sendOswestryNotificion($surveynumber,$oswestryscore, $userdetails, $therapistid)
    {
	
		
		$fullname = $userdetails['name_first'].'  '.$userdetails['name_last'];
		$data['fullname'] = $fullname;
		$to = $fullname.'<'.$userdetails['username'].'>';
		
		
		$application_path = $this->config['application_path'];
		$telespine_id = $this->config['telespineid'];
		
		
		$business_url = $this->config['business_telespine']; 
		$support_email = $this->config['email_telespine'];
		$images_url=$this->config['images_url'];
		$data['images_url'] = $images_url;
		$data['support_email'] = $support_email ;
		$data['loginurl']=$this->config['telespine_login'];
		$templatePath = $application_path."mail_content/telespine/oswestry_notification.php";
			
		
		$data_base_message = '';
		$mail_message = '';
		$subject ="Telespine Update - Your Oswestry Results";
		if($oswestryscore<=20){
		
		
		
		$data_base_message = 'Your functional score demonstrates that while you may be experiencing pain you, are still able to participate in most activities. Your score indicates that it is especially  important for you to be sure to be using proper lifting techniques, sit with good posture and to add core exercises. We will help you to maximize your healing process with posture tips, stress reduction techniques and exercises. If at any time you are in doubt or concerned about the pain you are having, contact your health care professional.';
		
		$mail_message = '<p style="margin:20px 0 0 0; padding:0;font-family:Arial;font-size:13px;">Your most recent functional score fell between 0-20% which demonstrates that while you may be experiencing some pain, you are still able to participate in most activities. Your score indicates that it is especially important for you to be using proper lifting techniques, sitting with good posture, and doing your core exercises. </p><p style="margin:20px 0 0 0; padding:0;font-family:Arial;font-size:13px;">As part of Telespine, we’ll continue to provide posture tips, stress reduction techniques, and exercises to help give you a healthier, stronger, and more flexible spine.  </p><p style="margin:20px 0 0 0; padding:0;font-family:Arial;font-size:13px;">If at any time, you have doubt or concern about the pain you are having, please contact your health care professional.  </p>';
		
		
		}
		elseif($oswestryscore>20 && $oswestryscore<=40 ){
		
		$data_base_message = 'Your functional score demonstrates that your lower back is causing you significant pain, making it hard for you to sit, lift and stand. Your score indicates that it is especially important for you to strengthen your inner core muscles, sit with proper posture and add low back specific exercises. We will help you to maximize your healing process with posture tips, stress reduction techniques and exercises. If at any time you are in doubt or concerned about the pain you are having, contact your health care professional.';
		
		$mail_message = '<p style="margin:20px 0 0 0; padding:0;font-family:Arial;font-size:13px;">Your most recent functional score fell between 21-40% which demonstrates that your lower back is causing you pain, which may be making it hard for you to sit, lift, and/or stand. Your score indicates that it is especially important for you to strengthen your inner core muscles, sit with proper posture, and continue to do low back specific exercises.  </p><p style="margin:20px 0 0 0; padding:0;font-family:Arial;font-size:13px;">As part of Telespine, we`ll continue to provide posture tips, stress reduction techniques, and exercises to help give you a healthier, stronger, and more flexible spine.  </p><p style="margin:20px 0 0 0; padding:0;font-family:Arial;font-size:13px;">If at any time, you have doubt or concern about the pain you are experiencing, please contact your health care professional.</p>';
		
		
		}
		
		elseif($oswestryscore>40 && $oswestryscore<=60 ){
		
		$data_base_message = 'Your functional score shows your lower back pain is significantly affecting your life and causing you a great deal of pain. Your score indicates that you need to take extra care in doing the healing exercises and activities. Be sure not to bend at the waist and to avoid sitting for prolonged periods of time.  To maximize your healing process be sure to follow all the program guidelines and instructions including posture tips, stress reduction techniques and exercises. If at any time you are in doubt or concerned about the pain you are having, contact your health care professional. ';
		
		$mail_message = '<p style="margin:20px 0 0 0; padding:0;font-family:Arial;font-size:13px;">Your most recent functional score fell between 41-60% which demonstrates that your lower back pain may be significantly affecting your life and causing you a lot of  pain. Your score indicates that you need to take extra care in doing the healing exercises and activities. Be sure not to bend at the waist and please avoid sitting for prolonged periods of time.   </p><p style="margin:20px 0 0 0; padding:0;font-family:Arial;font-size:13px;">To maximize your healing process, be sure to follow all the program guidelines and instructions. These including posture tips, stress reduction techniques, and exercises. </p><p style="margin:20px 0 0 0; padding:0;font-family:Arial;font-size:13px;">If at any time, you have doubt or concern about the pain you are having, please contact your health care professional.</p>';
		
		
		}
		
		elseif($oswestryscore>60 && $oswestryscore<=80 ){
		
		$data_base_message = 'Your functional score shows your lower back pain is causing you such significant pain that it is hard for you to participate in everyday activities. Based on your score, at this time to ensure your safety and success in healing from your lower back pain, we suggest that you contact your health professional before continuing this program. ';
		
		$mail_message = '<p style="margin:20px 0 0 0; padding:0;font-family:Arial;font-size:13px;">Your most recent functional score fell between 61-80% which demonstrates that your lower back pain is causing you significant pain that it’s likely hard for you to participate in everyday activities.    </p><p style="margin:20px 0 0 0; padding:0;font-family:Arial;font-size:13px;">Based on your score and to ensure your safety, we suggest that you contact your health professional before continuing this program.</p>';
		
		
		}
		
		elseif($oswestryscore>80 && $oswestryscore<=100 ){
		
		$data_base_message = 'Your functional score is showing that you are unable to perform normal daily activities. At this time, to ensure your safety and success in healing from your lower back pain, please consult your healthcare professional before continuing this program.';
		
		$mail_message = '<p style="margin:20px 0 0 0; padding:0;font-family:Arial;font-size:13px;">Your most recent functional score fell between 81-100% which demonstrates that you are  unable to perform most normal daily activities. </p><p style="margin:20px 0 0 0; padding:0;font-family:Arial;font-size:13px;">At this time and to ensure your safety, we recommend you consult your healthcare professional before continuing this program.</p>';
		
		
		}
		
		$data['message'] = $mail_message;
		
		$message = $this->build_template($templatePath,$data);
		$data['support_email'] = $support_email;
		$to = $fullname.'<'.$userdetails['username'].'>';	
		
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
		$headers .= "From: Telespine Support<".$support_email.">" . "\n";
		$returnpath = "-f".$support_email;
		@mail($to, $subject, $message, $headers, $returnpath); 
		
		$sqlinsert="INSERT INTO patient_message_center set 
					patient_id='".$userdetails['user_id']."',
					sender_id='".$therapistid."',
					subject='Oswestry Score',
					message='".$data_base_message."',
					creation_date=now(),
					read_date=now(),
					is_read=0";
		$resultMessage=$this->execute_query($sqlinsert);
			
	
	
	
	
	}	


	/**
     * Send the pain level notification to client
     * 
     * @param Int $surveynumber 
     * @param Int $patientid Patient's ID
     * @param Int $therapistid Patient's therapist ID
     */
    public function sendPainlevelNotificion($surveynumber,$painscore, $userdetails, $therapistid)
    {
	
		$fullname = $userdetails['name_first'].'  '.$userdetails['name_last'];
		$data['fullname'] = $fullname;
		$to = $fullname.'<'.$userdetails['username'].'>';
		
		$application_path = $this->config['application_path'];
		$telespine_id = $this->config['telespineid'];
		
		
		$business_url = $this->config['business_telespine']; 
		$support_email = $this->config['email_telespine'];
		$images_url=$this->config['images_url'];
		$data['images_url'] = $images_url;
		$data['support_email'] = $support_email ;
		$data['loginurl']=$this->config['telespine_login'];
		$templatePath = $application_path."mail_content/telespine/painlevel_notification.php";
			
		
		$data_base_message = '';
		$mail_message = '';
		
		if($painscore<=2){
		
		$data_base_message = 'Your pain score results demonstrates that while you are having some discomfort,  you are able to participate in almost all daily activities. To maximize your healing process and to prevent lower back pain in the future, be sure to follow all the program guidelines and instructions including posture tips, stress reduction techniques and exercises. If at any time you are in doubt or concerned about the pain you are having, contact your health care professional.';
		
		$mail_message = '<p style="margin:20px 0 0 0; padding:0;font-family:Arial;font-size:13px;">your pain score falls between 0-2 which demonstrates that while you are having some discomfort,  you are able to participate in almost all daily activities. To maximize your healing process and to prevent lower back pain in the future, be sure to follow all the program guidelines and instructions including posture tips, stress reduction techniques and exercises. </p><p style="margin:20px 0 0 0; padding:0;font-family:Arial;font-size:13px;">If at any time you are in doubt or concerned about the pain you are having, contact your health care professional.</p>';
		
		$subject ="Score 0-2: What your Pain Scale Score Means";
		
		}
		elseif($painscore>2 && $painscore<=5 ){
		
		$data_base_message = 'Your pain score indicates that your back is bothering quite a bit of the time but that you can tolerate most every day daily activities. To maximize your healing process be sure to follow all the program guidelines and instructions including posture tips, stress reduction techniques and exercises. If at any time you are in doubt or concerned about the pain you are having, contact your health care professional.';
		
		$mail_message = '<p style="margin:20px 0 0 0; padding:0;font-family:Arial;font-size:13px;">your pain score falls between 3-5 which indicates that your back is bothering quite a bit of the time but that you can tolerate most every day daily activities. To maximize your healing process be sure to follow all the program guidelines and instructions including posture tips, stress reduction techniques and exercises.</p><p style="margin:20px 0 0 0; padding:0;font-family:Arial;font-size:13px;">If at any time you are in doubt or concerned about the pain you are having, contact your health care professional.</p>';
		
		$subject ="Score 3-5: What your Pain Scale Score Means";
		
		}
		
		elseif($painscore>5 && $painscore<=8 ){
		
		$data_base_message = 'Your pain score result shows that you are in a lot of pain.  Experiencing pain at this level can be frightening and frustrating. By participating in the program you should start to feel better in a couple of days. If you are in near disabling pain or  if at any time you are in doubt or concerned about the pain you are having, contact your health care professional. ';
		
		$mail_message = '<p style="margin:20px 0 0 0; padding:0;font-family:Arial;font-size:13px;">your pain score falls between 6-8 which indicates that you are in a lot of pain.  Experiencing pain at this level can be frightening and frustrating. By participating in the program you should start to feel better in a couple of days.</p><p style="margin:20px 0 0 0; padding:0;font-family:Arial;font-size:13px;">If you are in near disabling pain or  if at any time you are in doubt or concerned about the pain you are having, contact your health care professional. </p>';
		
		$subject ="Score 6-8: What your Pain Scale Score Means";
		
		}
		
		elseif($painscore>8 && $painscore<=10 ){
		
		$data_base_message = 'Your pain score result tells us that you are in disabling pain. In the interest of your safety, please consult your healthcare professional before continuing with this program.  ';
		
		$mail_message = '<p style="margin:20px 0 0 0; padding:0;font-family:Arial;font-size:13px;">your pain score falls between 9-10: Your pain score result tells us that you are in disabling pain. In the interest of your safety, please consult your healthcare professional before continuing with this program. </p>';
		
		$subject ="Score 0-2: What your Pain Scale Score Means";
		
		}
		
		
		
		$data['message'] = $mail_message;
		
		$message = $this->build_template($templatePath,$data);
		$data['support_email'] = $support_email;
		
		
		
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
		$headers .= "From: Telespine Support<".$support_email.">" . "\n";
		$returnpath = "-f".$support_email;
		$sqlinsert="INSERT INTO patient_message_center set 
					patient_id='".$userdetails['user_id']."',
					sender_id='".$therapistid."',
					subject='Pain level',
					message='".$data_base_message."',
					creation_date=now(),
					read_date=now(),
					is_read=0";
                //as per jonathan this is not part of release 101
		//$resultMessage=$this->execute_query($sqlinsert);
			
	
	
	
	
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

    function getDayMessage(){
        $day=$_REQUEST['day'];
        $variables = array();
        $userdetails = $this->userInfo();
        $currentday = $this->getPaitentCurrentDay($userdetails['user_id']);
        //creation_date
        //$this->printR($userdetails);
        $sql="SELECT * FROM patient_message_center WHERE DATEDIFF( creation_date, DATE('{$userdetails['creation_date']}'))+1 ='{$day}' AND patient_id = '{$userdetails['user_id']}'";
        $messages = $this->fetch_all_rows($this->execute_query($sql));

        if(count($messages) > 0)
        {
            foreach($messages as $message)
            {
                $variables['messages'] .= "<p>{$message['message']}</p>";
            }
        }
        else
        {
            if($day<=$currentday){
                $variables['messages'] ="";
            }else{
                $variables['messages'] = "<p>Your future messages will be shown here.</p>";
            }
        }
        echo str_replace("`", "'", $variables['messages']);
    }
    
    
}

$obj = new patientdashboard();
?>
