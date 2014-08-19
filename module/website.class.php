<?php


    /**
     * 
     * Copyright (c) 2008 Tx Xchange.
     * This class gives website integration feature.
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
    require_once("include/fileupload/class.upload.php");
    require_once("module/application.class.php");
    
    
    
    
    // class declaration
      class website extends application{
          
          
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
                $str = "website"; //default if no action is specified
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
                $str = $str."()";
                eval("\$this->$str;"); 
            }
            else{
                $this->output = $this->config['error_message'];
            } 
            
            $this->display();
        }
        
        /**
         * Function to show clinic logo image upload interface and process.
         *
         * @access public
         */
        function website(){
            
            $replace = array();
             $userInfo = $this->userInfo();
            $cli_type=$this->getClinicDetails($this->userInfo('user_id'));
            //if($cli_type['clinic_type']==4){
                $replace['shopshow']=1;
            /*}else{
                $replace['shopshow']='0';
            }*/
            // Block for removing clinic logo.
            if( $this->value('link') == "remove"){
                $err_temp = $this->remove_logo();
                if( $err_temp == true ){
                    $error   = "Clinic logo successfully removed";
                    $replace['error'] = $error = '<span style="color:green" ><b>'.$error.'</b></span>';
                }
                else{
                    $error   = "Failed to remove Clinic logo";
                    $replace['error'] = $error = '<span style="color:green" ><b>'.$error.'</b></span>';
                }
            }
            // end 
            
            // Block executed after submitting form.
            if( $this->value('website_form_submit') == 'submit' ){
                
                $clinic_url = $this->value('clinic_url');
                // Check url is empty or not.
                
                if( isset($clinic_url) && trim($clinic_url) != ""  ){
                    // Check valid URL.
                    $value = '|' . $clinic_url . '|';
                    $check_pattern = '|http(s)?://([\w-]+\.)+[\w-]+(/[\w- ./?%&=]*)?|';
                    $check = preg_match($check_pattern, $value, $matches, PREG_OFFSET_CAPTURE);
                    if( $check === false || ($check == 0)){
                        $error[] = "Incorrect URL format";    
                    }
                    
                }
                $clinic_overview = $this->value('clinic_overview');
                 if( isset($clinic_overview) && trim($clinic_overview) != ""  ){
                    // Check over view
                    }
                
                // block executed after submitting form with file upload.
                if( is_array($_FILES) && $_FILES['clinic_logo']['error'] == 0 ){
                    $image_info = getimagesize($_FILES['clinic_logo']['tmp_name']);
                    $flag_image_info = false;
                    // print_r($image_info);
                    $width = $this->config['clinic_logo_image']['width'];
                    $height = $this->config['clinic_logo_image']['height'];
                    
                    if(isset($image_info[0]) && $image_info[0] > $width ){
                        $error[] = "Clinic logo image must be less than or equal to $width pixels in width";
                        $flag_image_info = true;
                    }
                    if(isset($image_info[1]) && $image_info[1] > $height ){
                        $error[] = "Clinic logo image must be less than or equal to $height pixels in height";
                        $flag_image_info = true;
                    }
                    $checkFile=$_FILES['clinic_logo']['name'];
                    $extarray=explode('.',$checkFile);
                    $counter=count($extarray);
                    $ext=$extarray[$counter-1];
                    if(strtolower($ext)=='jpeg' or strtolower($ext)=='gif' or strtolower($ext)=='png' or strtolower($ext)=='jpg'){
                        
                    }else{
                        $error[] = "Please upload jpeg,gif,png file only";
                        $flag_image_info = true;
                    }
                    // copy logo to cliniclog directory and save file name to database.
                    if( $flag_image_info == false ){
                        $err = $this->copy_clinic_logo();    
                        if( $err !== true){
                            $error[] = $err;    
                        }
                        
                    }
                }
                else{
                    // Block executed, if file is uploaded.
                    if( is_array($_FILES) && $_FILES['clinic_logo']['error'] == 4 ){
                         if($error==""){
                        $clinic_id = $this->clinicInfo("clinic_id");
                        $clinic_array = array(
                                        'clinic_website_address' => $this->value('clinic_url'),
                                         'clinic_overview'=>$this->value('clinic_overview'),
                                        );
                       $where = " clinic_id = '{$clinic_id}' ";
                       $this->update("clinic",$clinic_array,$where);
                       }   
                    }
                }
                
                // check error occoured or not.
                if( is_array($error) && count($error) > 0 ){
                    $replace['error'] = $this->show_error_notbold($error);
                }
                else{
                    $error   = "Company information successfully saved";
                    $replace['error'] = $error = '<span style="color:green;padding-left:0px" ><b>'.$error.'</b></span>';;
                }
                // End
            }
            // End
            // Block executed if form is not submitted. 
            else{
                $clinic_id = $this->clinicInfo("clinic_id");     
                if( is_numeric($clinic_id) && $clinic_id > 0 ){
                    $clinic_url = $this->get_field($clinic_id, "clinic", "clinic_website_address" );                     
                }
                if( is_numeric($clinic_id) && $clinic_id > 0 ){
                    $clinic_overview = $this->get_field($clinic_overview, "clinic", "clinic_overview" );                     
                }
            }
            //  End
            
            // Make links for show and remove logo.
            $query = " select * from clinic where clinic_id = '{$this->clinicInfo("clinic_id")}' ";
            $result = @mysql_query($query);
            if( $row = @mysql_fetch_array($result) ){
                $clinic_logo = $row['clinic_logo'];
                $clinic_overview=$row['clinic_overview'];
            }
             
            if( !empty($clinic_logo) || trim($clinic_logo) != "" ){
                
                $replace['show_link'] = "<a href='javascript:void(0)' onclick=\"toggleMediaDisplay('media_pic1')\">&nbsp;Show/Hide</a>";
                $replace['show_image'] = $clinic_logo;
                $replace['remove'] = "<a href='index.php?action=website&link=remove' >Remove</a>";
            }
            // End 
            
            
            
            // set template variables
            
            $replace['clinic_url'] = $clinic_url;
            $replace['clinic_overview'] = $clinic_overview;
            $replace['width'] = $this->config['clinic_logo_image']['width'];
            $replace['height'] = $this->config['clinic_logo_image']['height'];
            
            $replace['browser_title'] = 'Tx Xchange: Company Details';
            $replace['navigationTab'] = $this->build_template($this->get_template("navigationTab"));
            $replace['footer'] = $this->build_template($this->get_template("footer"));
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['sidebar'] = $this->sidebar();
            $replace['body'] = $this->build_template($this->get_template("website"),$replace);
            $replace['get_satisfaction'] = $this->get_satisfaction();
            $this->output = $this->build_template($this->get_template("main"),$replace);
        }
        function copy_clinic_logo(){
                
               $handle = new upload($_FILES['clinic_logo']);
               if ($handle->uploaded) {
                    
                   // New name of file being uploaded.
                   $clinic_id = $this->clinicInfo("clinic_id");
                   $new_file_name = "";
                   if( is_numeric($clinic_id) ){
                       $file_name = split("[/\\.]", $_FILES['clinic_logo']['name']) ; 
                       $new_file_name = $clinic_id . "_" . $file_name[0]; 
                       $handle->file_new_name_body   =  $new_file_name;
                   }
                   
                   $handle->file_overwrite = true;
                   $handle->allowed = array(
                                               "image/gif",
                                               "image/jpeg",
                                               "image/pjpeg",
                                               "image/png",
                                               "image/x-png",
                                               );
                   $handle->process($_SERVER['DOCUMENT_ROOT'] . '/asset/images/clinic_logo/');
                   
                   if ($handle->processed) {
                       $new_file_name_db = $handle->file_dst_name_body . "." . $handle->file_dst_name_ext;
                        $clinic_url = $this->value('clinic_url');
                        // Check url is empty or not.
                        
                        if( isset($clinic_url) && trim($clinic_url) != ""  ){
                            // Check valid URL.
                            $value = '|' . $clinic_url . '|';
                            $check_pattern = '|http(s)?://([\w-]+\.)+[\w-]+(/[\w- ./?%&=]*)?|';
                            $check = preg_match($check_pattern, $value, $matches, PREG_OFFSET_CAPTURE);
                            if( $check === false || ($check == 0)){
                                $error[] = "Incorrect URL format";    
                            }
                            
                        }
                        if($error==""){
                       $clinic_array = array(
                                        'clinic_website_address' => $this->value('clinic_url'),
                                        'clinic_logo' => $new_file_name_db,
                                        'clinic_overview'=>$this->value('clinic_overview'),
                                        );
                       $where = " clinic_id = '{$clinic_id}' ";
                       $this->update("clinic",$clinic_array,$where);
                       }
                       $handle->clean();
                   } else {
                       $error = $handle->error;
                       return $error;
                   }
               }
               
               return true;     
        }
        function remove_logo(){
            $clinic_id = $this->clinicInfo('clinic_id');
            if( isset($clinic_id) && is_numeric($clinic_id) > 0 ){
                $clinic_url = $this->get_field($clinic_id,"clinic","clinic_logo");
                if( is_string($clinic_url) && $clinic_url != "" ){
                    $path = $_SERVER['DOCUMENT_ROOT'] . '/asset/images/clinic_logo/';
                    if(@unlink($path . $clinic_url)){
                            $clinic_arr = array(
                                'clinic_logo' => ""
                            );
                            $where = " clinic_id = '{$clinic_id}' ";
                            $this->update("clinic",$clinic_arr,$where);
                            return true;
                    }
                }
            }

            return false;
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
    $obj = new website();
?>
