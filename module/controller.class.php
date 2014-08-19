<?php
	/**
	 * 
	 * Copyright (c) 2008 Tx Xchange.
	 * 
	 * This class is for following functionality:
	 * 1) This class basically redirects the action to its module classs.
	 */	
	class controller extends common{
		/**
		 * This function returns module class name.
		 *
		 * @param string $action
		 * @return string
		 * @access public
		 */
		static public function httpRequest($action=""){
			$action = common::value($action);
			
                        // 0 for txuser and 5 for telespine
                        $array=  explode('.', $_SERVER['HTTP_HOST']) ;
                        if (in_array(db::$config['domain'], $array)) {
                            $user_type = 5;
                        }  else {
                            $user_type = 0;    
                        }
                        
			if($action == "" ){
				if( isset($_SESSION['username']) && isset($_SESSION['password']) ){
					$user_type = common::chk_login($_SESSION['username'],$_SESSION['password']);
				}
				$action = db::$config['default_action'][$user_type];
			}
			return common::get_module_name($action);
		}
		/**
		 * This function renders the output.
		 *
		 * @access public
		 */
		static public function httpResponse(){
			 view::render();
		}
	}
?>
