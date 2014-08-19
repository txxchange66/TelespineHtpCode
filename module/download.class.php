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

  	class download extends application{

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
				/*
					This block of statement(s) are to handle all the actions supported by this Login class
					that is it could be the case that more then one action are handled by login
					for example at first the action is "login" then after submit say action is submit
					so if login is explicitly called we have the login action set (which is also our default action)
					else whatever action is it is set in $str.				
				*/
				$str = $this->value('action');
			}else{
				$str = "download_file"; //default if no action is specified
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
			//$this->display();
		}
		/**
		 * This function downloads files.
		 *
		 * @access public
		 */
		function download_file(){
			$content_type = array(
				'pdf' => 'application/pdf',
				'doc' => 'application/msword',
			);
			if($this->value('file_type') != "" ){
				$file_type = $content_type[$this->value('file_type')];
			}
			
			if( $this->value('file_name') != ""){
				$url = $this->config['images_url'];
				$download_file = $url.'/download/'.$this->value('file_name');
				$file_size = 'download/'.$this->value('file_name');
				$file_name = $this->value('file_name');
			}

			$filename = $_SERVER['DOCUMENT_ROOT'] . "/download/" . $file_name;
			header("Pragma: public");
			header("Expires: 0"); // set expiration time
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");	
			header('Content-disposition: attachment; filename='.basename($filename));
			header("Content-Type: ".$file_type);
			header("Content-Transfer-Encoding: binary");
			header('Content-Length: '. filesize($filename));
			readfile($filename);
			exit(0);
			
		}

	}

	$obj = new download();

?>

