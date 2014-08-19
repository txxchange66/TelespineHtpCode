<?php
	/**
	 * 
	 * Copyright (c) 2008 Tx Xchange.
	 * 
	 * This class is for following functionality:
	 * 1) This function returns the processed information as a response to the request.
	 */	
	class view extends common{
		/**
		 * This is $config member for loading configuration.
		 *
		 * @var array
		 */
		static public $config;
		/**
		 * This member hold the processed output.
		 *
		 * @var string
		 */
		static public $output;
		/**
		 * This function intialize the static member $config.
		 *
		 * @access pubic
		 */
		static public function set_config(){
			include_once("config.php");
			self::$config = $txxchange_config;
		}
		/**
		 * This function echos output to browser.
		 *
		 * @param string $type
		 */
		static public function render($type=""){
			if($type != ""){
				header("content-type: text/xml");
			}
			if(!empty(self::$output)){
				echo self::$output;
			}
		}
	}
?>