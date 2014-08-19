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
    require_once("include/paging/my_pagina_class.php");
    require_once("module/application.class.php");
    
    
    
    
    // class declaration
      class maintenance extends application{
          
          
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
                $str = "cash_based_program"; //default if no action is specified
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
                $arr_action = array('cash_based_program');
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
        function cash_based_program(){
            // set templating variables
            $replace['browser_title'] = 'Tx Xchange: Maintenance Patients';
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['sidebar'] = $this->sidebar();
            $replace['footer'] = $this->build_template($this->get_template("footer"));    
            
            if($this->value('sort') != ""){
                if($this->value('order') == 'desc' ){
                    $orderby = " order by {$this->value('sort')} desc ";
                }
                else{
                    $orderby = " order by {$this->value('sort')} ";
                }
            }
            else{
                    $orderby = " order by u.name_last ";
            }
            
            /**
             *    Query for generating the patient list
             *    CONDITIONS :
             *    1.  Patient must associated with the clinic whose Therapist(Accound Admin) is logged in
             *    2.  Patient must have one associated therapist
             *    3.  Patient status != 3, either Active or InActive
             */
                     
            $privateKey = $this->config['private_key'];
            $clinic_id = $this->clinicInfo("clinic_id");
            $query = "SELECT pu.p_type,u.user_id,u.username,
                      AES_DECRYPT(UNHEX(u.name_title),'{$privateKey}') as name_title, 
                      AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}') as name_first,
                      AES_DECRYPT(UNHEX(u.name_last),'{$privateKey}') as name_last,
                      date_format(u.last_login,'%m/%d/%Y %H:%i %p') as last_login,pu.start_date  
                      FROM program_user pu
                      INNER JOIN clinic_user cu ON pu.u_id = cu.user_id AND cu.clinic_id = '{$clinic_id}'
                      INNER JOIN user u ON u.user_id = pu.u_id {$orderby}";
            
            // pagination
            $link = $this->pagination($rows = 0,$query,'cash_based_program');                                          
            $replace['link'] = $link['nav'];
            $result = $link['result'];     
    
            // check sql query result
            if(is_resource($result)){
                if( @mysql_num_rows($result) > 0 ){
                    while($row = $this->fetch_array($result)){
                        $replace['classname'] = (++$k%2) ? 'line2' : 'line1';
                        $replace['userId'] = $row['user_id'];
                        $replace['fullName'] = $row['name_title'].' '.$row['name_first'].' '.$row['name_last'];
                        $replace['username'] = $row['username'];
                        $replace['planType'] = $this->get_field($row['p_type'],"program","p_name");
                        $replace['start_date'] = $this->formatDate($row['start_date'],"m/d/Y");
                        $replace['lastLogin'] = $row['last_login']!=""?$row['last_login']:"Never";
                        $rowdata .= $this->build_template($this->get_template("listRecord"),$replace);
                    }
                }
                else{
                    $rowdata = $this->build_template($this->get_template("recordNotFound"),$replace);
                }
            }
            else{
                $rowdata = $this->build_template($this->get_template("recordNotFound"),$replace);
            }
            $listHead = array(
                'u.name_first' => 'Full Name',
                'u.username' => 'User Name',
                'pu.start_date' => 'Start Date',
                'u.last_login' => 'Last Login'
            );
            $replace['listHead'] = $this->build_template($this->get_template("listHead"),$this->table_heading($listHead,"u.name_first"));
            $replace['rowdata'] = $rowdata;
            $replace['navigationTab'] = $this->build_template($this->get_template("navigationTab"));
            $replace['body'] = $this->build_template($this->get_template("cash_based_program"),$replace);
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
         * This is used to display the final template page.
         *
         * @access public
         */
        function display(){
            view::$output =  $this->output;
        }
        

    
    }
    
    // creating object of this class
    $obj = new maintenance();
?>
