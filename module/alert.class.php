<?php
    
    /**
     * 
     * Copyright (c) 2008 Tx Xchange.
     * 
     * This class is for following functionality:
     * 1) Display alert messages.
     * 
     * Neccessary class for getting access of application specific methods.
     * require_once("module/application.class.php");
     */
    
    
    require_once("module/application.class.php");

      class alert extends application{

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
         *       If it is not holding any value we are assigning default value in it.
         * 2) Check user is logged in or not.
         * 3) Check the logged in user have privileage or not to access this class.
         * 4) Show response by using display() method.
         * @param none
         * @return none
         * @access public
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
                $str = "therapist"; //default if no action is specified
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
                $this->output = $this->config['error_message'];
            } 
            $this->display();
        }
        /**
        * This function prepare message.
        */
        function show_message(){
            $replace = $this->get_tag_array($this->value('message'));
            $this->output = $this->build_template($this->get_template("alertTemplate"),$replace);
        }
        /**
        * Returns array of values for tag replacement in alert template.
        */
        function get_tag_array($action){
                $message_array = array(
                                       'save_publish' => array(
                                                                   'message' => 'This Template plan is already published.' 
                                                              ),
                                     );
                if( $action != "" ){
                    $key = $action;
                    if(array_key_exists($key,$message_array)){
                          return $message_array[$key];
                    }
                       
                }
                
                return "";
        }
        /**
         * To show the left navigation panel.
         *
         * @return string
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
         * This function returns template path from xml file.
         *
         * @param string $template
         * @return string
         */
        function get_template($template){

            $login_arr = $this->action_parser($this->action,'template') ;

            $pos =  array_search($template, $login_arr['template']['name']); 

            return $login_arr['template']['path'][$pos];

        }
        /**
         * This function display's the output.
         * @access public
         */
        function display(){

            view::$output =  $this->output;

        }

    }

    $obj = new alert();

?>

