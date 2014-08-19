<?php
	/**
	 * 
	 * Copyright (c) 2008 Tx Xchange.
	 * 
	 * This class is for following functionality:
	 * 1) Display System home page.
	 *  
	 * Neccessary class for getting access of application specific methods.
	 * require_once("module/application.class.php");
	 */
    require_once("include/paging/my_pagina_class.php"); 
	require_once("module/application.class.php");

  	class sysAdmin extends application{

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
		function __construct(){

			parent::__construct();

			if($this->value('action')){

				$str = $this->value('action');

			}else{

				$str = "sysAdmin"; //default if no action is specified

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
		 * This function shows System admin(s) home page.
		 *
		 * @access public
		 */
		function sysadmin(){
			$replace['header'] = $this->build_template($this->get_template("header"));
            $replace['footer'] = $this->build_template($this->get_template("footer"));
			$replace['sidebar'] = $this->sidebar();

			/* TopAssignedPlans() function is defined in application class. Application class is superclass of sysadmin class */

			$arr = $this->userInfo();
			
			$c = 0;
			$result = $this->TopAssignedPlans($arr['usertype_id'],$arr['user_id']);
			while( $result && ($row = $this->fetch_array($result)) && $c < 10 ){
				 $row['style'] = ($c++%2)?"line1":"line2";
				 $row['description'] = $this->lengthtcorrect($row['description'],34);	
				 $replace['TopAssignedPlans'] .= $this->build_template($this->get_template("TopAssignedPlans"),$row);
			}
			
			// check for more link after listing of plans 10 plan.
			if( is_resource($result) && @mysql_num_rows($result) > 10 ){
				$data = array(
							'listAction' => 'systemPlan'
						);
				$replace['TopAssignedPlans'] .= $this->build_template($this->get_template("more"),$data);
			}
			// End of check for more link.
			
			/* TopAssignedArticles() function is defined in application class. Application class is superclass of sysadmin class */
			$c = 0;
			$result = $this->TopAssignedArticles($arr['usertype_id'],$arr['user_id']);
			while( $result && ($row = $this->fetch_array($result)) && $c < 10 ){
				 $row['style'] = ($c++%2)?"line1":"line2";
				 $replace['TopAssignedArticles'] .= $this->build_template($this->get_template("TopAssignedArticles"),$row);
			}
			
			// check for more link after listing of plans 10 article.
			if( is_resource($result) && @mysql_num_rows($result) > 10 ){
				$data = array(
							'listAction' => 'articleList'
						);
				$replace['TopAssignedArticles'] .= $this->build_template($this->get_template("more"),$data);
			}
			// End of check for more link.
			
			$replace['body'] = $this->build_template($this->get_template("sysadmin"),$replace);
			$replace['browser_title'] = "Tx Xchange: Home";
			$this->output = $this->build_template($this->get_template("main"),$replace);
		}
		/**
		 * This function helps system admin to switch, from his account to some other Therapist or Account admin(s) account.
		 *
		 * @access public
		 */
		function switch_user(){
            if($this->value('id') != ""){
                $query = " select * from user where user_id = '{$this->value('id')}'  and usertype_id = 2 and status = 1  ";
                $result = $this->execute_query($query);
                if( $row = $this->fetch_array($result)){
                    // Encrypt data
                    $encrypt_field = array('name_title','name_first','name_last','password','address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2','fax');
                    $row = $this->decrypt_field($row, $encrypt_field);
                    // End Encryption
                    if(isset($_SESSION['username']) && isset($_SESSION['password']) ){
                        if( $this->userInfo('usertype_id') == "4" ){
                            $_SESSION['tmp_username'] = $_SESSION['username'];
                            $_SESSION['tmp_password'] = $_SESSION['password'];
                            $_SESSION['username'] = $row['username'];
                            $_SESSION['password'] = $row['password'];
                            header("location:index.php");
                            exit();
                        }
                        
                    }
                }
            }
            $privateKey = $this->config['private_key'];
            if($this->value('sort') != ""){
                if($this->value('order') == 'desc' ){
                    if( $this->value('sort') == "u.name_first" || $this->value('sort') == "u.name_last" ){
                        $orderby = " order by CAST(AES_DECRYPT(UNHEX({$this->value('sort')}) ,'{$privateKey}') as char) desc ";
                        
                    }
                    else
                        $orderby = " order by {$this->value('sort')} desc ";
                }
                else{
                    if( $this->value('sort') == "u.name_first" || $this->value('sort') == "u.name_last"  ){
                        $orderby = " order by cast(AES_DECRYPT(UNHEX({$this->value('sort')}),'{$privateKey}') as char) ";
                    }
                    else
                        $orderby = " order by {$this->value('sort')} ";
                }
                if($this->value('sort')=='u.username'){
                     if($this->value('order') == 'desc' ){	
                	       $orderby = " order by u.username desc";
                     }else{
                	       $orderby = " order by u.username ASC ";
                    }
                }
                
            }
            else{
                $orderby = " order by CAST(AES_DECRYPT(UNHEX(u.name_first) ,'{$privateKey}') as char),CAST(AES_DECRYPT(UNHEX(u.name_last) ,'{$privateKey}') as char) ASC ";
            }
            
            $privateKey = $this->config['private_key'];
            $query = " select u.username,
                            AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}') as name_first,
                            AES_DECRYPT(UNHEX(u.name_last),'{$privateKey}') as name_last,
                            u.user_id from user u
                            inner join clinic_user cu on cu.user_id = u.user_id 
                            inner join clinic c on c.clinic_id = cu.clinic_id and c.status = 1 
                            where u.usertype_id = 2 and u.status = 1 {$orderby} ";
            //echo $query;

            $result = @mysql_query($query);
            while($row = @mysql_fetch_array($result)){
                $row['style'] = ($c++%2)?"line1":"line2";
                $replace['therapistRecord'] .= $this->build_template($this->get_template("therapistRecord"),$row);
            }
            
            $switchHead = array(
                'u.username' => 'Username',
                'u.name_first' => 'First Name',
                'u.name_last' => 'Last Name',
            );
            
            
            $replace['therapistListHead'] = $this->build_template($this->get_template("therapistListHead"),$this->table_heading($switchHead,"username"));
            $replace['header'] .= $this->build_template($this->get_template("header"));
            $replace['sidebar'] = $this->sidebar();
            $replace['body'] = $this->build_template($this->get_template("therapistList"),$replace);
            $replace['browser_title'] = "Tx Xchange: Switch User";
            $this->output = $this->build_template($this->get_template("main"),$replace);
            
		}
		/**
         * To show the left navigation panel.
         *
         * @return string
         * @access public
         */
		function sidebar(){
			$data = array(
				'name_first' => $this->userInfo('name_first'),
				'name_last' =>  $this->userInfo('name_last'),
				'sysadmin_link' => $this->sysadmin_link()
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

	$obj = new sysAdmin();

?>

