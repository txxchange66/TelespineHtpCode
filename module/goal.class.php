<?php
    /**
     *
     * Copyright (c) 2008 Tx Xchange
     *
     * It includes the functionality of view reminders, view messages, plans and article library.
     * 
     * // necessary classes 
     * require_once("module/application.class.php");
     * 
     */
        
    
    // including files
    require_once("module/application.class.php");
    // class declaration
    class goal extends application{    
          
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
                $str = ""; //default if no action is specified
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
                eval("\$this->$str;"); 
            }
            else{
                $this->output =$this->config['error_message'];
            } 

            $this->display();
            /*
            $str = $str."()";
            eval("\$this->$str;"); 
            $this->display();            
            */
        }
        /**
        * This function will list all goal in detail.
        */
        function view_goal(){
            if($this->value('patient_id') != "" ){
                $userId = $this->value('patient_id');
            }
            else{
                $userId = $this->userInfo('user_id');
            }
            $query = " select * from patient_goal where created_by = '{$userId}' and (status = 1 or status = 2) order by created_on desc ";
            $result = @mysql_query($query);
            if( @mysql_num_rows($result) > 0 ){
                while( $row = @mysql_fetch_array($result) ){
                    $strike = "";
                    $checked = "";
                    if($row['status'] == 2 ){
                        $strike = 'text-decoration: line-through';
                        $checked = 'checked';
                    }
                    
                    $replace['list_goal'] .= "<div id='div_{$row['patient_goal_id']}' onmouseover='show_trash(this,1);' onmouseout='show_trash(this,2);' style='padding-left:0px;' >
                                    <span id='trash_{$row['patient_goal_id']}' style='visibility:hidden;' onclick='del_goal(this);'>
                                        <img src='/images/trash.gif' />
                                    </span><input type='checkbox' name='chk_{$row['patient_goal_id']}' value='{$row['patient_goal_id']}' $checked onclick='stikeout(this);' align='bottom' />
                                    &nbsp;<span id='span_{$row['patient_goal_id']}'  style='{$strike}'  >".$row['goal']."</span></div>";
                    
                }
            }
            $this->output = $this->build_template($this->get_template('goal'),$replace);
        }
        /**
        * This function will return list of Goals.
        */
        function update_goal(){
            $patient_goal_id = $this->value('patient_goal_id');
            $status = $this->value('status');
            $side= $this->value('side');
            if( is_numeric($patient_goal_id) ){
                $query = " update patient_goal set status = $status where  patient_goal_id = '{$patient_goal_id}' ";
                $result = @mysql_query($query);
                if( $result == '1' ){
                    $this->output = 'success';
                    
                    if($status==2 && $side=='patient' ){//check for goal is complete form patient side
                    	 $clinicId = $this->clinicInfo('clinic_id'); 
                    	$sql_addon="select goal_notification from addon_services where clinic_id=".$clinicId;
                    	$query_addon=$this->execute_query($sql_addon);
                    	if($this->num_rows($query_addon)>0) {
                    		$rowaddon=$this->fetch_array($query_addon);
                    		//print_r($rowaddon);
                    		//die;
                    		if($rowaddon[0]['goal_notification']==1){
                    	         $userInfo=$this->userInfo();
/////message for complete goal          
                                        $subject= $this->userInfo('name_first',$userId).' '.$this->userInfo('name_last',$userId)." has completed a goal";
                                        $content= $this->userInfo('name_first',$userId).' '.$this->userInfo('name_last',$userId)." has completed one of their goals:".$this->get_field($patient_goal_id, 'patient_goal', "goal");
                    	                $data = array(
				 		'patient_id' => $this->userInfo("user_id"),
				 		'sender_id' => $this->userInfo("user_id"),
				 		'subject' => $this->encrypt_data($subject),
				 		'content' => $this->encrypt_data($content),
				 		'parent_id' => '0',
				 		'sent_date' => date('Y-m-d H:i:s',time()),
                                                'recent_date' => date('Y-m-d H:i:s',time()),
                                                'replies' => '0'
				 	);
				 	if($this->insert("message",$data)){
				 		$message_id = $this->insert_id();
				 		$query = "select therapist_id from therapist_patient 
				 				  where patient_id = '{$this->userInfo("user_id")}' and status = 1 ";
				 		$result = @mysql_query($query);
				 		while( $row = @mysql_fetch_array($result)){
				 			// Entry for Therapist
				 			$data = array(
				 				'message_id' => $message_id,
				 				'user_id' => $row['therapist_id'],
				 				'unread_message' => '1'
				 			);
				 			$this->insert("message_user",$data);
				 			}
				 		
				 		// Entry for Patient
				 		$data = array(
				 				'message_id' => $message_id,
				 				'user_id' => $this->userInfo("user_id"),
				 				'unread_message' => '2'
				 		);
				 		$this->insert("message_user",$data);
				 		}


//end of message


                    	   }
                        }//end of num rows
                    }
                }
                else{
                    $this->output = 'failed';
                }
                return;
            }
            $this->output = 'failed';
        }

          /**
     * This function sends TX message from patient to provider.
     * @param Integer $sender_id
     * @param Integer $rec_id
     * @param String  $sub
     * @param String  $content
     * @return void
     * @access public
     */
    
    public function sendTxMessageByPatToPro($sender_id, $rec_id, $sub, $content) {
        $data = array ('sender_id' => $sender_id,'patient_id' => $rec_id, 'subject' => $this->encrypt_data ( $sub ), 'content' => $this->encrypt_data ( $content ), 'parent_id' => '0', 'sent_date' => date ( 'Y-m-d H:i:s', time () ), 'recent_date' => date ( 'Y-m-d H:i:s', time () ), 'replies' => '0','sent_visible' =>'0' );
        
        if ($this->insert ( "message", $data )) {
            $message_id = $this->insert_id ();
            // Entry for displaying unread messages count in pat section
            $data = array ('message_id' => $message_id, 'user_id' => $sender_id, 'unread_message' => '2' );
            $this->insert ( "message_user", $data );
            
            
             $thid = "select * from therapist_patient where patient_id = {$sender_id}";
            $queryTh = $this->execute_query ( $thid);   
            
                    while($rowTh = $this->fetch_array ( $queryTh))
                    {   
            
            // Entry for therapist
            

            $data = array ('message_id' => $message_id, 'user_id' => $rowTh['therapist_id'], 'unread_message' => '1' );
            $this->insert ( "message_user", $data );
                    }
        }
    
    }
        
        
        /**
        * This function will schedule goal for the ehs patients
        */
        function update_goal_ehs(){
            $patient_goal_id = $this->value('patient_goal_id');
            $status = $this->value('status');
            if( is_numeric($patient_goal_id) ){
                //$query = " update patient_goal set status = $status, scheduler_status = '1' ,schdulerAction = '{$status}' where  patient_goal_id = '{$patient_goal_id}' ";
                $query = " update patient_goal set status = $status where  patient_goal_id = '{$patient_goal_id}' ";
                $result = @mysql_query($query);
                if( $result == '1' ){
                        $clinicId = $this->clinicInfo('clinic_id');        
                        $ehsPatientArr = $this->getProviderEHSPatients($clinicId);
                        $patientCount = count($ehsPatientArr);
                        $pat = 0;        
                        
                        while($pat < $patientCount) { 

                                $query1 = " update patient_goal set status = '{$status}' where  parent_goal_id = '{$patient_goal_id}' ";
                                $result1 = @mysql_query($query1);

                                $pat++;
                        }   
                    $this->output = 'success';
                }
                else{
                    $this->output = 'failed';
                }
                return;
            }
            $this->output = 'failed';
        }

        /**
        * This function adds a goal.
        */
        function add_goal(){
            $goal = $this->value('goal');
            
            if( is_numeric($this->value('patient_id'))  ){
                $user_id = $this->value('patient_id');
                $replace['patient_id'] = $user_id;
            }
            else{
                $user_id = $this->userInfo('user_id');
            }
            
            if( isset($goal) && $goal != "" ){
                $goal_arr = array(
                                    'goal' => $goal,
                                    'status' => 1,
                                    'created_by' => $user_id
                            );
                $this->insert('patient_goal',$goal_arr);
                $insert_id = $this->insert_id();
                if( is_numeric($insert_id) ){
                    /*if( strlen($goal) > 0){
                        $goal = $this->lengthtcorrect($goal,38);
                    }*/
                     
                    $this->output = "<tr class='line1'><td><div id='div_{$insert_id}' onmouseover='show_trash(this,1);' onmouseout='show_trash(this,2);'  style='border-bottom:1px solid #CCCCCC; text-align:left;padding-top:4px;' >
                                    <span id='span_{$insert_id}' style='display:block; width:312px; float:right;font-size:12px;'>".$goal."</span>
                                    <span id='trash_{$insert_id}' style='visibility:hidden;' onclick='del_goal(this);'>
                                        <img src='/images/trash.gif' />
                                    </span><input type='checkbox' name='chk_{$insert_id}' value='{$insert_id}' onclick='stikeout(this);'  />
                                    <div style='clear:both;'></div></div></td></tr>";
                    //$this->output = "success";
                }
                else{
                    $this->output = "failed";
                }
                return;
            }
            $this->output = $this->build_template($this->get_template('add_goal'),$replace);
        }


	/**
        * This function adds a goal.
        */
        function add_goal_ehs(){
            $goal = $this->value('goal');

            $clinicId = $this->clinicInfo('clinic_id');
            $replace['clinicId'] = $clinicId;

            $ehsunsub=$this->value('ehsunsub');
            if($ehsunsub == '0') {
                $replace['ehsunsub']=$ehsunsub; 
                echo "There are currently no patients subscribed to your EHS. EHS Mass Management can only be used once patients have started to subscribe to your EHS.";
                exit;
            }    
            
            if( is_numeric($this->value('therapistId'))  ){
                $therapistId = $this->value('therapistId');
                $replace['therapistId'] = $therapistId;
            }
            else{
                $therapistId = $this->userInfo('user_id');
            }

            if( isset($goal) && $goal != "" ) {
                $goal_arr = array(
                                    'goal' => $goal,
                                    'parent_goal_id' => '0',
                                    'status' => '0',
                                    'ehsGoal' => 1,
                                    'clinicId' => $clinicId,
                                    'schdulerAction' => '1',
                                    'scheduler_status' => '1',
                                    'created_by' => $therapistId
                                    
                            );
                $this->insert('patient_goal',$goal_arr);
                $insert_id = $this->insert_id();
                
               
                if( is_numeric($insert_id) ) {
                       
                        /* $serializeArray = array(
                                    'patient_goal_id' => $insert_id,
                                    'goal' => $goal,
                                    'parent_goal_id' => '0',
                                    'status' => '0',
                                    'ehsGoal' => 1,
                                    'clinicId' => $clinicId,
                                    'scheduler_status' => '0',
                                    'created_by' => $therapistId
                                    
                            );

                       $serializeArray = serialize($serializeArray);

                        $sqlinsert = "INSERT INTO mass_ehs_assignment_scheduler SET
							        schedulerType = '2',
							        schedulerAction = '1',
							        schedulerData = '".$serializeArray."',
							        schedulerAddDate = '".date("Y-m-d")."',
							        schedulerModifiedDate = '".date("Y-m-d")."',
                                                                schedulerStatus = '1'";

			  $result = @mysql_query($sqlinsert);*/

                
            
                    /*$this->output = "<tr class='line1'><td><div id='div_{$insert_id}' onmouseover='show_trash(this,1);' onmouseout='show_trash(this,2);'  style='border-bottom:1px solid #CCCCCC; text-align:left;padding-top:4px;' >
                                    <span id='span_{$insert_id}' style='display:block; width:312px; float:right;font-size:12px;'>".$goal."</span>
                                    <span id='trash_{$insert_id}' style='visibility:hidden;' onclick='del_goal(this);'>
                                        <img src='/images/trash.gif' />
                                    </span><input type='checkbox' name='chk_{$insert_id}' value='{$insert_id}' onclick='stikeout(this);'  />
                                    <div style='clear:both;'></div></div></td></tr>";*/
                    $this->output = "Your goal is in queue. It will assigned to all the patient after some time.";
                         
                    
                }
                else{
                    $this->output = "failed";
                }
                return;
            }
               
            $this->output = $this->build_template($this->get_template('add_goal_ehs'),$replace);
        }



        /**
         * populating side panel on the page
         *
         * @return string
         * @access public
         */
        function sidebar(){
            $data = array(
                'replies' => $this->num_messages(),
                'bulletin_board' => $this->bulletin_message()
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
    }
    // creating object of the class.
    $obj = new goal();
?>
