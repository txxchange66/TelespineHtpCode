<?php

    

    /**
     *
     * Copyright (c) 2008 Tx Xchange
     * 
     * It includes the functionality for clinic listing, Edit existing and Add new clinic.
     * it also includes the functionality for list user, edit, changestatus and delete for selected clinic from list.
     * 
     * // necessary classes 
     * require_once("module/application.class.php");
     * 
     * // pagination class
     * require_once("include/paging/my_pagina_class.php");    
     * 
     * //Server side form validation classes
     * require_once("include/validation/_includes/classes/validation/ValidationSet.php");
     * require_once("include/validation/_includes/classes/validation/ValidationError.php");
     * 
     */
        
    
    // including files    
	require_once("include/fileupload/class.upload.php");
    require_once("module/application.class.php");
    require_once("include/paging/my_pagina_class.php");
    require_once("include/validation/_includes/classes/validation/ValidationSet.php");
    require_once("include/validation/_includes/classes/validation/ValidationError.php");    
    require_once("include/excel/excelwriter.inc.php");
    
    // class declaration
      class healthServiceReport extends application{
          
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
                $str = "healthServiceReport"; //default if no action is specified
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
        * This function returns url for tab.
        */
        function tab_url(){
            $url_array = array();
            $clinicId = $this->clinicInfo("clinic_id");
            if( !empty($clinicId) ){
                $url_array['location'] = "index.php?action=accountAdminClinicList&clinic_id={$clinicId}";
                $url_array['userLocation'] = "index.php?action=userListingHead&clinic_id={$clinicId}";
                $url_array['patientLocation'] = "index.php?action=patientListingHead&clinic_id={$clinicId}";
            }
            return $url_array;
        }
        /**
         * This function is used to display the patient listing for the particular account admin.
         *
         * @access public
         */
     
        /**
        * This function renders sidebar of headaccountadmin user.
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
        
    
        /**
        * This function displays list clinics in an account.        
        */
        function healthServiceReport(){
            $month= date('m');
            $year=date('Y');
            $clinicId = $this->clinicInfo("clinic_id");
            if( !is_numeric($clinicId) || $clinicId == 0 ){
                header("location:index.php?action=logout");
                exit();
            }
            
            $replace = array();
            // Retian page value.
            /* code for show telespine menu on list */
        $replace['showmenu']="no";
        if($this->clinicInfo('clinic_id')==$this->config['telespineid']){
            $replace['showmenu']="yes";
        }
        /* code end */
            $userInfo = $this->userInfo();
            $cli_type=$this->getClinicDetails($this->userInfo('user_id'));
           // if($cli_type['clinic_type']==4){
                $replace['shopshow']=1;
            /*}else{
                $replace['shopshow']='0';
            }*/
            $arr = array(
                'clinic_id' => $clinicId
            );
            $msg = $this->value('msg');
            if( !empty($msg) ){
                $replace['error'] = $this->errorClinicListModule($msg);
            }
            $this->set_session_page($arr);
            //Get  acccount id
            if($this->value('sort') != ""){
                if($this->value('order') == 'desc' ){
                    $orderby = " order by {$this->value('sort')} desc ";
                }
                else{
                    $orderby = " order by {$this->value('sort')} ";
                }
            }
            else{
                $orderby = " order by payment_date desc";
            }
            
            //Get the clinic list
            if( is_numeric($clinicId) ){
                if($this->value('period')!=''){
                    $time=explode('~~',$this->value('period'));
                    $month1= $time[0];
                    $year1=$time[1];  
                }else{
                    $month1= date('m');
                    $year1=date('Y');    
                }
                $num = cal_days_in_month(CAL_GREGORIAN, $month1, $year1);
                 $privateKey = $this->config['private_key'];
                $sqlUser = "select AES_DECRYPT(UNHEX(U.name_first),'{$privateKey}') as p_name_first ,AES_DECRYPT(UNHEX(U.name_last),'{$privateKey}')  as p_name_last,PS.subscription_title as service_name,date_format(PP.paymnet_datetime,'%Y-%m-%d') as payment_date
                            from patient_subscription PS, patient_payment PP,user U,clinic_subscription CS 
                            where CS.subs_id=PS.subs_id 
                            AND PS.user_subs_id = PP.patient_subscription_user_subs_id 
                            AND PS.user_id=U.user_id 
                            AND PS.clinic_id = '{$clinicId}' 
                            AND PP.paymnet_datetime between '{$year1}-{$month1}-01' AND '{$year1}-{$month1}-{$num}' {$orderby}";                    
                $sqlquery = "select count(1) as total
                              from patient_subscription PS, patient_payment PP,user U,clinic_subscription CS 
                            where CS.subs_id=PS.subs_id 
                            AND PS.user_subs_id = PP.patient_subscription_user_subs_id 
                            AND PS.user_id=U.user_id 
                            AND PS.clinic_id = '{$clinicId}' 
                            AND PP.paymnet_datetime between '{$year1}-{$month1}-01' AND '{$year1}-{$month1}-{$num}'";
            }
            $link = $this->pagination($rows = 0,$sqlUser,'txReferralReport',$searchString,'','',20);                                          
            $replace['link'] = $link['nav'];
            $result = $link['result'];     
            
            if($this->num_rows($result)!= 0)
            {
                $replace['reportTblHead'] = $this->build_template($this->get_template("reportTblHead"),$replace);
                while($row = $this->fetch_array($result))
                {
                    $row['style'] = ($c++%2)?"line1":"line2";
                    $row['p_name_first'] = $row['p_name_first'].' '.$row['p_name_last'];
                    $row['service_name'] = $row['r_name_first'].' '.$row['service_name'];
                    $row['payment_date'] = $this->formatDate($row['payment_date'],"m/d/Y");
                    $replace['reportTblRecord'] .= $this->build_template($this->get_template("reportTblRecord"),$row);
                }
            }else{
            $row['p_name_first'] = 'No Records Found';
			$row['service_name'] = '';
			//$row['recipient_email'] = '';
            $row['payment_date'] = '';
            $replace['reportTblRecord'] .= $this->build_template($this->get_template("reportTblRecord"),$row);   
            }
                               
            
            $reportListHead = array(
                'p_name_first' => 'Patient Name',
                'service_name' => 'Name of E-Health Service',
                'payment_date' => 'Last Payment Date',
                );
            
            $mon=$month;
            $yer=$year;
            $monthname=array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Aug','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
            for($i=0;$i<12;$i++){
              $select='';
              if($this->value('period')!=''){
                    $time=explode('~~',$this->value('period'));
                    $month1= $time[0];
                    $year1=$time[1];  
               if($month1==$mon and $year1==$yer)
               $select='selected='."selected";
               }  
               
             $monview=$mon;
               if(strlen($mon)==1)
                 $monview='0'.$mon;
                 else{
                    $monview=$mon;
                 }
             $period .= "<option value='".$mon."~~".$yer."'".$select.">".$monthname[$monview].','.$yer;
             $mon=$mon-1;
             if($mon==0)
                {
                    $mon=12;
                    $yer=$yer-1;
                }
             
             
             }
            $res=$this->execute_query($sqlquery);
            $totalno=@mysql_fetch_array($res); 
            $replace['totalReferrals']=$totalno['total'];
            $replace['downloadreport']='index.php?action=healthServiceReportExcel&period='.$this->value('period');
            $replace['period']=$period;
            $reportperiod['period']=$this->value('period');
            $replace['reportTblHead'] = $this->build_template($this->get_template("reportTblHead"),$this->table_heading($reportListHead,"p_name_first",$reportperiod));
            $replace['clinic_id'] = $clinicId;
            $parentClinicId = $this->get_field($clinicId,'clinic','parent_clinic_id');
            
            if( is_numeric($parentClinicId) && $parentClinicId == 0){
            //echo '2';
            $url_array = $this->tab_url();
                $replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$url_array);
                $replace['navigationTab']='';
                $replace['menufile']="<script language='JavaScript' src='js/show_menu_head.js'></script>";
            }else{
                $replace['menufile']="<script language='JavaScript' src='js/show_menu.js'></script>";
                $replace['navigationTab'] = $this->build_template($this->get_template("navigationTab"));
                //echo "<textarea>".$replace['navigationTab']."</textarea>";
                $replace['tabNavigation']='';
              }
            //echo $parent_clinic_id = $this->get_field($clinic_id,'clinic','parent_clinic_id');
            //if( is_numeric($parent_clinic_id) && $parent_clinic_id == 0){
            // echo '1';
            //$replace['navigationTab'] = $this->build_template($this->get_template("navigationTab"));            
            // }
            $replace['location'] = $url_array['location'];
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['footer'] = $this->build_template($this->get_template("footer"));    
            $replace['sidebar'] = $this->sidebar();
            $replace['body'] = $this->build_template($this->get_template("reportTemplate"),$replace);
            $replace['browser_title'] = "Tx Xchange: E-Health Service Reports";
            $replace['get_satisfaction'] = $this->get_satisfaction();
            $this->output = $this->build_template($this->get_template("main"),$replace);            
                        
            
        }
        function healthServiceReportExcel(){
            $month= date('m');
            $year=date('Y');
           
            $clinicId = $this->clinicInfo("clinic_id");
            $clinicName=$this->get_clinic_name($clinicId);
            if( !is_numeric($clinicId) || $clinicId == 0 ){
                header("location:index.php?action=logout");
                exit();
            }
            // Retian page value.
            $arr = array(
                'clinic_id' => $clinicId
            );
            //Get the clinic list
            if( is_numeric($clinicId) ){
                if($this->value('period')!=''){
                    $time=explode('~~',$this->value('period'));
                    $month1= $time[0];
                    $year1=$time[1];  
                }else{
                    $month1= date('m');
                    $year1=date('Y');    
                }
                 $num = cal_days_in_month(CAL_GREGORIAN, $month1, $year1);
            if(!is_dir($_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url'].'report')){
            mkdir($_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url'].'report');
            chmod($_SERVER['DOCUMENT_ROOT'].$this->config['tx']['article']['media_url'].'report',0777);
        }
               $filename="report/".$clinicName.'_'.$month1.'_'.$year1.'_Health_report.xls';
               $filename=str_replace(" ",'_',$filename);
               
                $excel=new ExcelWriter($filename);
                if($excel==false)
                echo $excel->error;
                $sqlUser = "select U.name_first as p_name_first ,U.name_last as p_name_last,PS.subscription_title as service_name,date_format(PP.paymnet_datetime,'%Y-%m-%d') as payment_date
                            from patient_subscription PS, patient_payment PP,user U,clinic_subscription CS 
                            where CS.subs_id=PS.subs_id 
                            AND PS.user_subs_id = PP.patient_subscription_user_subs_id 
                            AND PS.user_id=U.user_id 
                            AND PS.clinic_id = '{$clinicId}' 
                            AND PP.paymnet_datetime between '{$year1}-{$month1}-01' AND '{$year1}-{$month1}-{$num}' order by payment_date desc";
            }
            $result = $this->execute_query($sqlUser);     
            
            if($this->num_rows($result)!= 0)
            {
                
		        $myArr=array("<b>Patient Name</b>","<b>Name of E-Health Service</b>","<b>Last Payment Date<b>");
                $excel->writeLine($myArr);
                while($row = $this->fetch_array($result))
                {
                    $excelrow['p_name_first'] = $this->decrypt_data($row['p_name_first']).' '.$this->decrypt_data($row['p_name_last']);
                    $excelrow['service_name'] = $row['service_name'];
                    $excelrow['payment_date'] = $this->formatDate($row['payment_date'],"m/d/Y");
                    $excel->writeLine($excelrow);                    
                }
            }else{
               $excelrow['p_name_first'] = 'No Record Found'; 
               $excel->writeLine($excelrow);
                
            }
            $excel->writeRow();
            $excel->writeRow();
            $excel->writeRow();
           // $excel->writeCol("<b>Total Number of Payment<b>");
	      //  $excel->writeCol("<b>".$this->num_rows($result)."</b>");
	        $excel->close();
            if (file_exists($filename)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename='.basename($filename));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                header('Content-Length: ' . filesize($filename));
                ob_clean();
                flush();
                readfile($filename);
                exit;
            }
	        
        }
    function faq_HealthServies(){

              //Get the clinic channel based on clinic is		
              $clinic_channel = $this->getchannel($this->clinicInfo('clinic_id')); 

              if($clinic_channel == 2)

                      $this->output =  $this->build_template($this->get_template("wx_faq_HealthServies"));
              else
                      $this->output =  $this->build_template($this->get_template("faq_HealthServies"));   
    }
      
    
    /**
     * Renders the template for Telespine Reports
     * 
     * TEL-69
     */
    function telespinereports()
    {
        $replace['header'] = $this->build_template($this->get_template("header"));
        $replace['footer'] = $this->build_template($this->get_template("footer"));
        $replace['sidebar'] = $this->sidebar();
        $replace['showmenu']="no";
        if($this->clinicInfo('clinic_id')==$this->config['telespineid']){
            $replace['showmenu']="yes";
        }
        //this will be depending upon the report type selected
        $report = $this->value('report');
        if(!empty($report))
        {
            $commonreportcontent = "<link rel=\"stylesheet\" href=\"js/autocomplete/themes/base/jquery.ui.core.css\">
                                <link rel=\"stylesheet\" href=\"js/autocomplete/themes/base/jquery.ui.datepicker.css\">
                                <link rel=\"stylesheet\" href=\"js/autocomplete/themes/base/jquery.ui.theme.css\">
                                <script src=\"js/autocomplete/jquery-1.5.1.js\"></script>
                                <script src=\"js/autocomplete/ui/jquery-ui-1.8.14.custom.js\"></script>
                                <script src=\"js/autocomplete/ui/minified/jquery.ui.datepicker.min.js\"></script>
								 <script src=\"js/paginate.js\"></script>


                                <div class=\"subheading\">
                                   <strong> REPORT_NAME</strong>
                                </div>";
								
								if($report!='averageoswestryscore' && $report!='averagepainscore' && $report!='percentageoswestryfilled' ){

                                $commonreportcontent .=  "<div id=\"reportcontrols\">
                                    <div style=\"float:left;\">
                                        <form id=\"reportform\" name=\"reportform\" action=\"index.php?action=telespinereports&report={$report}\" method=\"post\">
                                            <label for=\"from\">From</label>
                                            <input type=\"text\" id=\"from\" name=\"from\" value=\"{$this->value('from')}\">
                                            <label for=\"to\">to</label>
                                            <input type=\"text\" id=\"to\" name=\"to\" value=\"{$this->value('to')}\">

                                            <input type=\"submit\" value=\"Go\" name=\"btnok\" />
                                        </form>
                                    </div>";
                                }
								
								
                                    $commonreportcontent .= " <div style=\"float:right;\">
                                        <a href=\"index.php?action=telespinereports&report={$report}&export=csv\">Export CSV</a>
                                    </div>
                                </div>
                                ";
								$client_user_sql="SELECT COUNT(cu.user_id) AS CC FROM clinic_user cu left join user usr ON cu.user_id = usr.user_id WHERE  cu.clinic_id ='".$this->config['telespineid']."' AND usr.usertype_id='1'";
								
							$result_count_usr = $this->fetch_all_rows($this->execute_query($client_user_sql));	
							
							$cl_usr = 0;
							foreach($result_count_usr as $result){
							$cl_usr = $cl_usr + $result['CC'];
							}
							
					
				
            
            switch($report)
            {
                case 'uniquepageviews':
                    $reportname = 'Unique page views';
					
					$dashboard_count = 0;
					$article_count = 0;
					$video_count = 0;
					$myaccount_count = 0;
					
					if( $this->value('from') && $this->value('to') )
                    {
						$dashboard_query = "SELECT COUNT(  user_id ) AS CC FROM page_visits_report WHERE page =  'dashboard' AND created BETWEEN '".$this->value('from')." 0:0:0' AND '".$this->value('to')." 23:59:59'  AND clinic_id='".$this->config['telespineid']."'   GROUP BY DATE_FORMAT( created,  '%Y-%m-%d' ) ";
						
						$article_query = "SELECT COUNT(  user_id ) AS CC FROM page_visits_report WHERE page =  'article' AND created BETWEEN '".$this->value('from')." 0:0:0' AND '".$this->value('to')." 23:59:59'  AND clinic_id='".$this->config['telespineid']."' GROUP BY DATE_FORMAT( created,  '%Y-%m-%d' ) ";
						
						$video_query = "SELECT COUNT(  user_id ) AS CC FROM page_visits_report WHERE page =  'video' AND created BETWEEN '".$this->value('from')." 0:0:0' AND '".$this->value('to')." 23:59:59'  AND clinic_id='".$this->config['telespineid']."' GROUP BY DATE_FORMAT( created,  '%Y-%m-%d' ) ";
						
						$myaccount_query = "SELECT COUNT(  user_id ) AS CC FROM page_visits_report WHERE page =  'myaccount' AND created BETWEEN '".$this->value('from')." 0:0:0' AND '".$this->value('to')." 23:59:59'  AND clinic_id='".$this->config['telespineid']."' GROUP BY DATE_FORMAT( created,  '%Y-%m-%d' ) ";
						
					
					}else{
					
						$dashboard_query = "SELECT COUNT(  user_id ) AS CC FROM page_visits_report WHERE page =  'dashboard' AND clinic_id='".$this->config['telespineid']."'   GROUP BY DATE_FORMAT( created,  '%Y-%m-%d' ) ";
						
						$article_query = "SELECT COUNT(  user_id ) AS CC FROM page_visits_report WHERE page =  'article' AND clinic_id='".$this->config['telespineid']."'  GROUP BY DATE_FORMAT( created,  '%Y-%m-%d' ) ";
						
						$video_query = "SELECT COUNT(  user_id ) AS CC FROM page_visits_report WHERE page =  'video' AND clinic_id='".$this->config['telespineid']."'  GROUP BY DATE_FORMAT( created,  '%Y-%m-%d' ) ";
						
						$myaccount_query = "SELECT COUNT(  user_id ) AS CC FROM page_visits_report WHERE page =  'myaccount' AND clinic_id='".$this->config['telespineid']."'  GROUP BY DATE_FORMAT( created,  '%Y-%m-%d' ) ";
					
					}
					
					 $resultarr_dashboard = $this->fetch_all_rows($this->execute_query($dashboard_query));
					 $resultarr_article = $this->fetch_all_rows($this->execute_query($article_query));
					 $resultarr_video = $this->fetch_all_rows($this->execute_query($video_query));
					 $resultarr_myaccount = $this->fetch_all_rows($this->execute_query($myaccount_query));
               
					foreach($resultarr_dashboard as $result){
						
						$dashboard_count = $result['CC'] +$dashboard_count;
						
						}
						
					foreach($resultarr_article as $result){
						
						$article_count = $result['CC'] +$article_count;
						
						}
						
					foreach($resultarr_video as $result){
						
						$video_count = $result['CC'] +$video_count;
						
						}
						
					foreach($resultarr_myaccount as $result){
						
						$myaccount_count = $result['CC'] +$myaccount_count;
						
						}
						
					
					
                    $commonreportcontent .= "<table cellpadding=\"2\" width=\"100%\" class=\"serReport\" >
                                                <tr>
                                                    <td valign=\"top\" class=\"topnavnew\" style='text-align:center'>
                                                        Page
                                                    </td>
                                                    <td valign=\"top\" class=\"topnavnew\" style='text-align:center'>Views</td>
                                                </tr>
                                                <tr>
                                                    <td style='text-align:center'>Dashboard</td>
                                                    <td style='text-align:center'>".$dashboard_count."</td>
                                                </tr>
                                                <tr>
                                                    <td style='text-align:center'>Videos</td>
                                                    <td style='text-align:center'>".$video_count."</td>
                                                </tr>
                                                <tr>
                                                    <td style='text-align:center'>Articles</td>
                                                    <td style='text-align:center'>".$article_count."</td>
                                                </tr>
                                                <tr>
                                                    <td style='text-align:center'>My Account</td>
                                                    <td style='text-align:center'>".$myaccount_count."</td>
                                                </tr>
                                            </table>";
											
					if($this->value('export') == 'csv')
                    {
                        $filename = $this->config['application_path'] . 'tmp_cache' . DIRECTORY_SEPARATOR . "$report.csv";
                        $fp = fopen($filename, 'w+');
                        fputcsv($fp, array('Page', 'Views'));
                        fputcsv($fp, array('Dashboard', $dashboard_count));
                        fputcsv($fp, array('Videos', $video_count));
						fputcsv($fp, array('Article', $article_count));
						fputcsv($fp, array('MyAccount', $myaccount_count));
                        fclose($fp);

                        if (file_exists($filename))
                        {
                            header('Content-Description: File Transfer');
                            header('Content-Type: application/octet-stream');
                            header('Content-Disposition: attachment; filename='.basename($filename));
                            header('Content-Transfer-Encoding: binary');
                            header('Expires: 0');
                            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                            header('Pragma: public');
                            header('Content-Length: ' . filesize($filename));
                            ob_clean();
                            flush();
                            readfile($filename);
                            exit;
                        }
                    }	
											
										
											
											
											
                    break;
                
                case 'videosusagestats':
                    $reportname = 'Videos usage stats';
                    
                    if( $this->value('from') || $this->value('to') )
                    {
                        $query = "SELECT viewed_from, count(id) as views FROM `video_report` WHERE created_date BETWEEN '".$this->value('from')." 0:0:0' AND '".$this->value('to')." 23:59:59' AND clinic_id='".$this->config['telespineid']."' group by viewed_from";
                    }
                    else
                    {
                        $query = "SELECT viewed_from, count(id) as views FROM `video_report` WHERE  clinic_id='".$this->config['telespineid']."' group by viewed_from";
                    }
                    
                    $resultarr = $this->fetch_all_rows($this->execute_query($query));
						$dashboardviews = 0;
                        $videoviews = 0;
                    if(count($resultarr) > 0)
                    {
                        foreach($resultarr as $result)
                        {
                            if($result['viewed_from'] == 'dashboard')
                            {
                                $dashboardviews = $result['views'];
								if( $dashboardviews<0){
								 $dashboardviews =0;
								}
                            }

                            if($result['viewed_from'] == 'videopage')
                            {
                                $videoviews = $result['views'];
								
								if( $videoviews<0){
								 $videoviews =0;
								}
                            }
                        }
                    }
                    else
                    {
                        $dashboardviews = 0;
                        $videoviews = 0;
                    }

                    $commonreportcontent .= "<table cellpadding=\"2\" width=\"100%\" class=\"serReport\">
                                                <tr>
                                                    <td valign=\"top\" class=\"topnavnew\" style='text-align:center'>
                                                        Page
                                                    </td>
                                                    <td valign=\"top\" class=\"topnavnew\" style='text-align:center'>Views</td>
                                                </tr>
                                                <tr>
                                                    <td style='text-align:center'>Dashboard</td>
                                                    <td style='text-align:center'>{$dashboardviews}</td>
                                                </tr>
                                                <tr>
                                                    <td style='text-align:center'>Videos</td>
                                                    <td style='text-align:center'>{$videoviews}</td>
                                                </tr> 
                                            </table>";
                    
                    if($this->value('export') == 'csv')
                    {
                        $filename = $this->config['application_path'] . 'tmp_cache' . DIRECTORY_SEPARATOR . "$report.csv";
                        $fp = fopen($filename, 'w+');
                        fputcsv($fp, array('Page', 'Views'));
                        fputcsv($fp, array('Dashboard', $dashboardviews));
                        fputcsv($fp, array('Videos', $replace));
                        fclose($fp);

                        if (file_exists($filename))
                        {
                            header('Content-Description: File Transfer');
                            header('Content-Type: application/octet-stream');
                            header('Content-Disposition: attachment; filename='.basename($filename));
                            header('Content-Transfer-Encoding: binary');
                            header('Expires: 0');
                            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                            header('Pragma: public');
                            header('Content-Length: ' . filesize($filename));
                            ob_clean();
                            flush();
                            readfile($filename);
                            exit;
                        }
                    }
                    
                    break;
                
                case 'articlesusagestats':
                    $reportname = 'Articles usage stats';
                    if( $this->value('from') || $this->value('to') )
                    {
                        $query = "SELECT viewed_from, count(id) as views FROM `article_report` WHERE clinic_id='".$this->config['telespineid']."' AND created_date BETWEEN '".$this->value('from')." 0:0:0' AND '".$this->value('to')." 23:59:59'  group by viewed_from";
                    }
                    else
                    {
                        $query = "SELECT viewed_from, count(id) as views FROM `article_report` WHERE clinic_id='".$this->config['telespineid']."'  group by viewed_from";
                    }
                    
                    $resultarr = $this->fetch_all_rows($this->execute_query($query));
						$dashboardviews = 0;
                        $articleviews = 0;
                    if(count($resultarr) > 0)
                    {
                        foreach($resultarr as $result)
                        {
                            if($result['viewed_from'] == 'dashboard')
                            {
                                $dashboardviews = $result['views'];
								if( $dashboardviews<0){
								 $dashboardviews =0;
								}
								
								
                            }

                            if($result['viewed_from'] == 'articlepage')
                            {
                                $articleviews = $result['views'];
								
								if( $articleviews==' '){
								$articleviews =0;
								}
								 
								
                            }
                        }
                    }
                    else
                    {
                        $dashboardviews = 0;
                        $articleviews = 0;
                    }

                    $commonreportcontent .= "<table cellpadding=\"2\" width=\"100%\" class=\"serReport\">
                                                <tr>
                                                    <td valign=\"top\" class=\"topnavnew\" style='text-align:center'>
                                                        Page
                                                    </td>
                                                    <td valign=\"top\" class=\"topnavnew\" style='text-align:center'>Views</td>
                                                </tr>
                                                <tr>
                                                    <td style='text-align:center'>Dashboard</td>
                                                    <td style='text-align:center'>{$dashboardviews}</td>
                                                </tr>
                                                <tr>
                                                    <td style='text-align:center'>Articles</td>
                                                    <td style='text-align:center'>{$articleviews}</td>
                                                </tr> 
                                            </table>";
                    break;
                
                case 'averagevideoswatched':
                    $reportname = 'Average videos watched per user per day';
					
					 if( $this->value('from') || $this->value('to') )
                    {
                         $query = "SELECT COUNT( id ) AS CC, DATE_FORMAT( created_date,  '%m-%d-%Y' ) AS CD FROM `video_report` WHERE created_date BETWEEN '".$this->value('from')." 0:0:0' AND '".$this->value('to')." 23:59:59' AND clinic_id='".$this->config['telespineid']."' GROUP BY DATE_FORMAT( created_date,  '%Y-%m-%d' ) ORDER BY created_date";
                    }
                    else
                    {
                        $query = "SELECT COUNT( id ) AS CC, DATE_FORMAT( created_date,  '%m-%d-%Y' ) AS CD FROM video_report WHERE  clinic_id='".$this->config['telespineid']."' GROUP BY DATE_FORMAT( created_date,  '%Y-%m-%d' ) ORDER BY created_date";
                    }
                    
                    $resultarr = $this->fetch_all_rows($this->execute_query($query));
					
					
                    $commonreportcontent .= "<table cellpadding=\"2\" width=\"100%\" class=\"serReport\" id=\"paginate1\" class=\"rowstyle-alt max-pages-200 paginate-2\">
                                                <tr>
                                                    <th valign=\"top\" class=\"topnavnew\" style='text-align:center'>
                                                        Day
                                                    </th>
                                                    <th valign=\"top\" class=\"topnavnew\" style='text-align:center'>Average videos watched</th>
                                                </tr>";
												
												foreach($resultarr as $result){
						
						$commonreportcontent .= "<tr>
                                                    <td style='text-align:center'>".$result['CD']."</td>
                                                    <td style='text-align:center'>".round($result['CC']/$cl_usr)."</td>
                                                </tr>";
						
						}
                                                
                                                
                                      $commonreportcontent .= "      </table>";
									  
									  
									  
				if($this->value('export') == 'csv')
                    {
                        $filename = $this->config['application_path'] . 'tmp_cache' . DIRECTORY_SEPARATOR . "$report.csv";
                        $fp = fopen($filename, 'w+');
                        fputcsv($fp, array('Day', 'Average videos watched'));
						
						
						foreach($resultarr as $result){
                        fputcsv($fp, array($result['CD'], round($result['CC']/$cl_usr)));
						}
                       
                        fclose($fp);

                        if (file_exists($filename))
                        {
                            header('Content-Description: File Transfer');
                            header('Content-Type: application/octet-stream');
                            header('Content-Disposition: attachment; filename='.basename($filename));
                            header('Content-Transfer-Encoding: binary');
                            header('Expires: 0');
                            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                            header('Pragma: public');
                            header('Content-Length: ' . filesize($filename));
                            ob_clean();
                            flush();
                            readfile($filename);
                            exit;
                        }
                    }
                    
                   
									  
									  
									  
									  
									  
                    break;
                
                case 'averagearticlesviewed':
                    $reportname = 'Average articles viewed per user per day';
					
					
					 if( $this->value('from') || $this->value('to') )
                    {
                          $query = "SELECT COUNT( id ) AS CC, DATE_FORMAT( created_date,  '%m-%d-%Y' ) AS CD FROM `article_report` WHERE created_date BETWEEN '".$this->value('from')." 0:0:0' AND '".$this->value('to')." 23:59:59' AND clinic_id='".$this->config['telespineid']."' GROUP BY DATE_FORMAT( created_date,  '%Y-%m-%d' ) ORDER BY created_date";
                    }
                    else
                    {
                        $query = "SELECT COUNT( id ) AS CC, DATE_FORMAT( created_date,  '%m-%d-%Y' ) AS CD FROM article_report WHERE  clinic_id='".$this->config['telespineid']."' GROUP BY DATE_FORMAT( created_date,  '%Y-%m-%d' ) ORDER BY created_date";
                    }
                    
					
					
                    $resultarr = $this->fetch_all_rows($this->execute_query($query));
					
					
                    $commonreportcontent .= "<table cellpadding=\"2\" width=\"100%\" class=\"serReport\" id=\"paginate1\" class=\"rowstyle-alt max-pages-200 paginate-2\">
                                                <tr>
                                                    <th valign=\"top\" class=\"topnavnew\" style='text-align:center'>
                                                        Day
                                                    </th>
                                                    <th valign=\"top\" class=\"topnavnew\" style='text-align:center'>Average articles viewed</th>
                                                </tr>";
												
								foreach($resultarr as $result){
						
											$commonreportcontent .= "<tr>
																		<td style='text-align:center'>".$result['CD']."</td>
																		<td style='text-align:center'>".round($result['CC']/$cl_usr)."</td>
																	</tr>";
						
						}
                                                
                                                
                                      $commonreportcontent .= "      </table>";
											
											
											
											
											
					if($this->value('export') == 'csv')
                    {
                        $filename = $this->config['application_path'] . 'tmp_cache' . DIRECTORY_SEPARATOR . "$report.csv";
                        $fp = fopen($filename, 'w+');
                        fputcsv($fp, array('Day', 'Average articles viewed'));
						
						
						foreach($resultarr as $result){
                        fputcsv($fp, array($result['CD'], round($result['CC']/$cl_usr)));
						}
                       
                        fclose($fp);

                        if (file_exists($filename))
                        {
                            header('Content-Description: File Transfer');
                            header('Content-Type: application/octet-stream');
                            header('Content-Disposition: attachment; filename='.basename($filename));
                            header('Content-Transfer-Encoding: binary');
                            header('Expires: 0');
                            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                            header('Pragma: public');
                            header('Content-Length: ' . filesize($filename));
                            ob_clean();
                            flush();
                            readfile($filename);
                            exit;
                        }
                    }
											
											
											
											
											
                    break;
                
                case 'averagegoalscreated':
                    $reportname = 'Average goals created per user per day';
					
					 if( $this->value('from') || $this->value('to') )
                    {
                         $query = "SELECT COUNT( patient_goal_id ) AS CC, DATE_FORMAT( created_on,  '%m-%d-%Y' ) AS CD FROM patient_goal pa  LEFT JOIN clinic_user cu  ON  pa.created_by = cu.user_id WHERE cu.clinic_id ='".$this->config['telespineid']."' AND created_on BETWEEN '".$this->value('from')." 0:0:0' AND '".$this->value('to')." 23:59:59' AND pa.schduleday IS NULL AND pa.assignday IS NULL GROUP BY DATE_FORMAT( created_on,  '%Y-%m-%d' ) ORDER BY created_on";
						 
						 
						 
						 
                    }
                    else
                    {
                        $query = "SELECT COUNT( patient_goal_id ) AS CC, DATE_FORMAT( created_on,  '%m-%d-%Y' ) AS CD FROM patient_goal pa  LEFT JOIN clinic_user cu  ON  pa.created_by = cu.user_id WHERE cu.clinic_id ='".$this->config['telespineid']."' AND pa.schduleday IS NULL AND pa.assignday IS NULL GROUP BY DATE_FORMAT( created_on,  '%Y-%m-%d' ) ORDER BY created_on";
                    }
					
					$resultarr = $this->fetch_all_rows($this->execute_query($query));
					
                    $commonreportcontent .= "<table cellpadding=\"2\" width=\"100%\" class=\"serReport\" id=\"paginate1\" class=\"rowstyle-alt max-pages-200 paginate-2\">
                                                <tr>
                                                    <th valign=\"top\" class=\"topnavnew\" style='text-align:center'>
                                                        Day
                                                    </th>
                                                    <th valign=\"top\" class=\"topnavnew\" style='text-align:center'>Average goals created</th>
                                                </tr>";
												
								foreach($resultarr as $result){
						
											$commonreportcontent .= "<tr>
																		<td style='text-align:center'>".$result['CD']."</td>
																		<td style='text-align:center'>".round($result['CC']/$cl_usr)."</td>
																	</tr>";
						
						}
                                                
                                                
                                      $commonreportcontent .= "      </table>";
									  
									  
							if($this->value('export') == 'csv')
                    {
                        $filename = $this->config['application_path'] . 'tmp_cache' . DIRECTORY_SEPARATOR . "$report.csv";
                        $fp = fopen($filename, 'w+');
                        fputcsv($fp, array('Day', 'Average goals created'));
						
						
						foreach($resultarr as $result){
                        fputcsv($fp, array($result['CD'], round($result['CC']/$cl_usr)));
						}
                       
                        fclose($fp);

                        if (file_exists($filename))
                        {
                            header('Content-Description: File Transfer');
                            header('Content-Type: application/octet-stream');
                            header('Content-Disposition: attachment; filename='.basename($filename));
                            header('Content-Transfer-Encoding: binary');
                            header('Expires: 0');
                            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                            header('Pragma: public');
                            header('Content-Length: ' . filesize($filename));
                            ob_clean();
                            flush();
                            readfile($filename);
                            exit;
                        }
                    }		  
									  
									  
									  
									  
									  
									  
									  
                    break;
                
                case 'averagegoalscompleted':
                    $reportname = 'Average goals completed per user per day';
					
					 if( $this->value('from') || $this->value('to') )
                    {
                         $query = "SELECT COUNT( patient_goal_id ) AS CC, DATE_FORMAT( update_date,  '%m-%d-%Y' ) AS CD FROM patient_goal pa  LEFT JOIN clinic_user cu  ON  pa.created_by = cu.user_id WHERE cu.clinic_id ='".$this->config['telespineid']."' AND update_date BETWEEN '".$this->value('from')." 0:0:0' AND '".$this->value('to')." 23:59:59' AND pa.status='2' AND update_date IS NOT NULL GROUP BY DATE_FORMAT( update_date,  '%Y-%m-%d' ) ORDER BY update_date";
						 
						 
						 
						 
                    }
                    else
                    {
                        $query = "SELECT COUNT( patient_goal_id ) AS CC, DATE_FORMAT( update_date,  '%m-%d-%Y' ) AS CD FROM patient_goal pa  LEFT JOIN clinic_user cu  ON  pa.created_by = cu.user_id WHERE cu.clinic_id ='".$this->config['telespineid']."' AND pa.status='2' AND update_date IS NOT NULL GROUP BY DATE_FORMAT( update_date,  '%Y-%m-%d' ) ORDER BY update_date";
                    }
					
					$resultarr = $this->fetch_all_rows($this->execute_query($query));
					
					
                    $commonreportcontent .= "<table cellpadding=\"2\" width=\"100%\" class=\"serReport\" id=\"paginate1\" class=\"rowstyle-alt max-pages-200 paginate-2\">
                                                <tr>
                                                    <th valign=\"top\" class=\"topnavnew\" style='text-align:center'>
                                                        Day
                                                    </th>
                                                    <th valign=\"top\" class=\"topnavnew\" style='text-align:center'>Average goals completed</th>
                                                </tr>";
												
								foreach($resultarr as $result){
						
											$commonreportcontent .= "<tr>
																		<td style='text-align:center'>".$result['CD']."</td>
																		<td style='text-align:center'>".round($result['CC']/$cl_usr)."</td>
																	</tr>";
						
						}
                                                
                                                
                                      $commonreportcontent .= "      </table>";
									  
									  
							if($this->value('export') == 'csv')
                    {
                        $filename = $this->config['application_path'] . 'tmp_cache' . DIRECTORY_SEPARATOR . "$report.csv";
                        $fp = fopen($filename, 'w+');
                        fputcsv($fp, array('Day', 'Average goals completed'));
						
						
						foreach($resultarr as $result){
                        fputcsv($fp, array($result['CD'], round($result['CC']/$cl_usr)));
						}
                       
                        fclose($fp);

                        if (file_exists($filename))
                        {
                            header('Content-Description: File Transfer');
                            header('Content-Type: application/octet-stream');
                            header('Content-Disposition: attachment; filename='.basename($filename));
                            header('Content-Transfer-Encoding: binary');
                            header('Expires: 0');
                            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                            header('Pragma: public');
                            header('Content-Length: ' . filesize($filename));
                            ob_clean();
                            flush();
                            readfile($filename);
                            exit;
                        }
                    }		  
									  
						
                    break;
                
                case 'averageoswestryscore':
                    $reportname = 'Average oswestry score per user';
					
					
						
					
                        $query1 = "SELECT SUM( pom.score ) AS TOT, COUNT( DISTINCT pom.patient_id ) AS CC,SUM( pom.score )/ COUNT( DISTINCT pom.patient_id ) AS AVG1 FROM patient_om pom LEFT JOIN clinic_user cu  ON pom.patient_id=cu.user_id  WHERE pom.score IS NOT NULL  AND pom.filledagainstsurvey ='oswestry survey 1' AND cu.clinic_id ='".$this->config['telespineid']."'";
						
						$query2 = "SELECT SUM( pom.score ) AS TOT, COUNT( DISTINCT pom.patient_id ) AS CC,SUM( pom.score )/ COUNT( DISTINCT pom.patient_id ) AS AVG1 FROM patient_om pom LEFT JOIN clinic_user cu  ON pom.patient_id=cu.user_id  WHERE pom.score IS NOT NULL  AND pom.filledagainstsurvey ='oswestry survey 2'  AND cu.clinic_id ='".$this->config['telespineid']."'";
						
						$query3 = "SELECT SUM( pom.score ) AS TOT, COUNT( DISTINCT pom.patient_id ) AS CC,SUM( pom.score )/ COUNT( DISTINCT pom.patient_id ) AS AVG1 FROM patient_om pom LEFT JOIN clinic_user cu  ON pom.patient_id=cu.user_id  WHERE pom.score IS NOT NULL  AND pom.filledagainstsurvey ='oswestry survey 3'  AND cu.clinic_id ='".$this->config['telespineid']."'";
						
						$query4 = "SELECT SUM( pom.score ) AS TOT, COUNT( DISTINCT pom.patient_id ) AS CC,SUM( pom.score )/ COUNT( DISTINCT pom.patient_id ) AS AVG1 FROM patient_om pom LEFT JOIN clinic_user cu  ON pom.patient_id=cu.user_id  WHERE pom.score IS NOT NULL  AND pom.filledagainstsurvey ='oswestry survey 4'  AND cu.clinic_id ='".$this->config['telespineid']."'";
                   
					
					$resultarr1 = $this->fetch_all_rows($this->execute_query($query1));
					
					$resultarr2 = $this->fetch_all_rows($this->execute_query($query2));
					
					$resultarr3 = $this->fetch_all_rows($this->execute_query($query3));
					
					$resultarr4 = $this->fetch_all_rows($this->execute_query($query4));
					
					$result1 = $resultarr1[0];
					$result2 = $resultarr2[0];
					$result3 = $resultarr3[0];
					$result4 = $resultarr4[0];
					
					
                    $commonreportcontent .= "<table cellpadding=\"2\" width=\"100%\"class=\"serReport\">
                                                <tr>
                                                    
                                                    <td valign=\"top\" class=\"topnavnew\" style='text-align:center'>Oswestry 1</td>
                                                    <td valign=\"top\" class=\"topnavnew\" style='text-align:center'>Oswestry 2</td>
                                                    <td valign=\"top\" class=\"topnavnew\" style='text-align:center'>Oswestry 3</td>
                                                    <td valign=\"top\" class=\"topnavnew\" style='text-align:center'>Oswestry 4</td>
													
													</tr>
                                                ";
												
								
						
											$commonreportcontent .= "<tr>
																		<td style='text-align:center'>".round($result1['AVG1'])."</td>
																		<td style='text-align:center'>".round($result2['AVG1'])."</td>
																		<td style='text-align:center'>".round($result3['AVG1'])."</td>
																		<td style='text-align:center'>".round($result4['AVG1'])."</td>
																	</tr>";
						
						
                                                
                                                
                                      $commonreportcontent .= "      </table>";
									  
									  
							if($this->value('export') == 'csv')
                    {
                        $filename = $this->config['application_path'] . 'tmp_cache' . DIRECTORY_SEPARATOR . "$report.csv";
                        $fp = fopen($filename, 'w+');
                        fputcsv($fp, array('Oswestry 1', 'Oswestry 2','Oswestry 3', 'Oswestry 4'));
						
						
					
                        fputcsv($fp, array($result1['AVG1'], $result2['AVG1'],$result3['AVG1'], $result4['AVG1']));
						
                       
                        fclose($fp);

                        if (file_exists($filename))
                        {
                            header('Content-Description: File Transfer');
                            header('Content-Type: application/octet-stream');
                            header('Content-Disposition: attachment; filename='.basename($filename));
                            header('Content-Transfer-Encoding: binary');
                            header('Expires: 0');
                            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                            header('Pragma: public');
                            header('Content-Length: ' . filesize($filename));
                            ob_clean();
                            flush();
                            readfile($filename);
                            exit;
                        }
                    }	
					
					
					
					
                    break;
                
                case 'averagepainscore':
                    $reportname = 'Average pain score per user';
                    											
					 $query1 = "SELECT SUM( pom.score2 ) AS TOT, COUNT( DISTINCT pom.patient_id ) AS CC,SUM( pom.score2 )/ COUNT( DISTINCT pom.patient_id ) AS AVG1 FROM  patient_om   pom LEFT JOIN clinic_user cu  ON pom.patient_id=cu.user_id WHERE pom.score2 IS NOT NULL  AND pom.filledagainstsurvey ='oswestry survey 1' AND cu.clinic_id ='".$this->config['telespineid']."'";
						
					$query2 = "SELECT SUM( pom.score2 ) AS TOT, COUNT( DISTINCT pom.patient_id ) AS CC,SUM( pom.score2 )/ COUNT( DISTINCT pom.patient_id ) AS AVG1 FROM  patient_om   pom LEFT JOIN clinic_user cu  ON pom.patient_id=cu.user_id WHERE pom.score2 IS NOT NULL  AND pom.filledagainstsurvey ='oswestry survey 2' AND cu.clinic_id ='".$this->config['telespineid']."'";
						
					$query3 = "SELECT SUM( pom.score2 ) AS TOT, COUNT( DISTINCT pom.patient_id ) AS CC,SUM( pom.score2 )/ COUNT( DISTINCT pom.patient_id ) AS AVG1 FROM  patient_om   pom LEFT JOIN clinic_user cu  ON pom.patient_id=cu.user_id WHERE pom.score2 IS NOT NULL  AND pom.filledagainstsurvey ='oswestry survey 3' AND cu.clinic_id ='".$this->config['telespineid']."'";
						
					$query4 = "SELECT SUM( pom.score2 ) AS TOT, COUNT( DISTINCT pom.patient_id ) AS CC,SUM( pom.score2 )/ COUNT( DISTINCT pom.patient_id ) AS AVG1 FROM  patient_om   pom LEFT JOIN clinic_user cu  ON pom.patient_id=cu.user_id WHERE pom.score2 IS NOT NULL  AND pom.filledagainstsurvey ='oswestry survey 4' AND cu.clinic_id ='".$this->config['telespineid']."'";
                   
					
					$resultarr1 = $this->fetch_all_rows($this->execute_query($query1));
					
					$resultarr2 = $this->fetch_all_rows($this->execute_query($query2));
					
					$resultarr3 = $this->fetch_all_rows($this->execute_query($query3));
					
					$resultarr4 = $this->fetch_all_rows($this->execute_query($query4));
					
					$result1 = $resultarr1[0];
					$result2 = $resultarr2[0];
					$result3 = $resultarr3[0];
					$result4 = $resultarr4[0];
					
					
					
					
					
					
                    $commonreportcontent .= "<table cellpadding=\"2\" width=\"100%\" class=\"serReport\">
                                                <tr>
                                                   <td valign=\"top\" class=\"topnavnew\" style='text-align:center'>Oswestry 1</td>
                                                    <td valign=\"top\" class=\"topnavnew\" style='text-align:center'>Oswestry 2</td>
                                                    <td valign=\"top\" class=\"topnavnew\" style='text-align:center'>Oswestry 3</td>
                                                    <td valign=\"top\" class=\"topnavnew\" style='text-align:center'>Oswestry 4</td>
                                                </tr>
                                                ";
												
						
						
											$commonreportcontent .= "<tr>
																		<td style='text-align:center'>".round($result1['AVG1'])."</td>
																		<td style='text-align:center'>".round($result2['AVG1'])."</td>
																		<td style='text-align:center'>".round($result3['AVG1'])."</td>
																		<td style='text-align:center'>".round($result4['AVG1'])."</td>
																	</tr>";
						
					
                                                
                                                
                                      $commonreportcontent .= "      </table>";
									  
									  
							if($this->value('export') == 'csv')
                    {
                        $filename = $this->config['application_path'] . 'tmp_cache' . DIRECTORY_SEPARATOR . "$report.csv";
                        $fp = fopen($filename, 'w+');
                        fputcsv($fp, array('Oswestry 1', 'Oswestry 2','Oswestry 3', 'Oswestry 4'));
						
						
					
                        fputcsv($fp, array($result1['AVG1'], $result2['AVG1'],$result3['AVG1'], $result4['AVG1']));
                       
                        fclose($fp);

                        if (file_exists($filename))
                        {
                            header('Content-Description: File Transfer');
                            header('Content-Type: application/octet-stream');
                            header('Content-Disposition: attachment; filename='.basename($filename));
                            header('Content-Transfer-Encoding: binary');
                            header('Expires: 0');
                            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                            header('Pragma: public');
                            header('Content-Length: ' . filesize($filename));
                            ob_clean();
                            flush();
                            readfile($filename);
                            exit;
                        }
                    }	
					
					
											
											
											
											
											
											
											
											
											
                    break;
                
                case 'percentageoswestryfilled':
                    $reportname = 'Percentage of oswestrys filled';
                    
					/*
                    $query = "SELECT count(patient_om_id) as om ,filledagainstsurvey,sum(score) as total ,sum(score)/count(patient_om_id) as avg FROM patient_om WHERE filledagainstsurvey is NOT NULL GROUP BY filledagainstsurvey";
                    $result= $this->execute_query($query);
					
					SELECT SUM( pom.score2 ) AS TOT, COUNT( DISTINCT pom.patient_id ) AS CC,SUM( pom.score2 )/ COUNT( DISTINCT pom.patient_id ) AS AVG1 FROM  patient_om   pom LEFT JOIN clinic_user cu  ON pom.patient_id=cu.user_id WHERE pom.score2 IS NOT NULL  AND pom.filledagainstsurvey ='oswestry survey 1'
					*/
					$query1 = "SELECT  COUNT( DISTINCT pom.patient_id ) AS CC FROM patient_om pom LEFT JOIN clinic_user cu  ON pom.patient_id=cu.user_id WHERE score IS NOT NULL  AND pom.filledagainstsurvey ='oswestry survey 1' AND cu.clinic_id ='".$this->config['telespineid']."'";
						
					$query2 = "SELECT  COUNT( DISTINCT pom.patient_id ) AS CC FROM patient_om pom LEFT JOIN clinic_user cu  ON pom.patient_id=cu.user_id WHERE score IS NOT NULL  AND pom.filledagainstsurvey ='oswestry survey 2' AND cu.clinic_id ='".$this->config['telespineid']."'";
						
					$query3 = "SELECT  COUNT( DISTINCT pom.patient_id ) AS CC FROM patient_om pom LEFT JOIN clinic_user cu  ON pom.patient_id=cu.user_id WHERE score IS NOT NULL  AND pom.filledagainstsurvey ='oswestry survey 3' AND cu.clinic_id ='".$this->config['telespineid']."'";
						
					$query4 = "SELECT  COUNT( DISTINCT pom.patient_id ) AS CC FROM patient_om pom LEFT JOIN clinic_user cu  ON pom.patient_id=cu.user_id WHERE score IS NOT NULL  AND pom.filledagainstsurvey ='oswestry survey 4' AND cu.clinic_id ='".$this->config['telespineid']."'";
                   
					
					$resultarr1 = $this->fetch_all_rows($this->execute_query($query1));
					
					$resultarr2 = $this->fetch_all_rows($this->execute_query($query2));
					
					$resultarr3 = $this->fetch_all_rows($this->execute_query($query3));
					
					$resultarr4 = $this->fetch_all_rows($this->execute_query($query4));
					
					$result1 = $resultarr1[0];
					$result2 = $resultarr2[0];
					$result3 = $resultarr3[0];
					$result4 = $resultarr4[0];
					
					$avg1 = round($result1['CC']*100/$cl_usr);
					$avg2 = round($result2['CC']*100/$cl_usr);
					$avg3 = round($result3['CC']*100/$cl_usr);
					$avg4 = round($result4['CC']*100/$cl_usr);
					
					
					
					
					
                    
                    $commonreportcontent .= "<table cellpadding=\"2\" width=\"100%\" class=\"serReport\">
                                                <tr>
                                                    <td valign=\"top\" class=\"topnavnew\">
                                                        Total Users
                                                    </td>
                                                    <td valign=\"top\" class=\"topnavnew\" style='text-align:center'>Oswestry 1</td>
                                                    <td valign=\"top\" class=\"topnavnew\" style='text-align:center'>Oswestry 2</td>
                                                    <td valign=\"top\" class=\"topnavnew\" style='text-align:center'>Oswestry 3</td>
                                                    <td valign=\"top\" class=\"topnavnew\" style='text-align:center'>Oswestry 4</td>
                                                </tr>
                                                <tr> <td style='text-align:center'>".$cl_usr."</td>";
                                                  
															if($avg1==0){
															$commonreportcontent .="<td style='text-align:center'> </td>";
															}
															else{
                                                           $commonreportcontent .="<td style='text-align:center'>".$avg1." %</td>";
														   }
														   
														   if($avg2==0){
															$commonreportcontent .="<td style='text-align:center'> </td>";
															}
															else{
                                                           $commonreportcontent .="<td style='text-align:center'>".$avg2." %</td>";
														   }
														   
														   if($avg3==0){
															$commonreportcontent .="<td style='text-align:center'> </td>";
															}
															else{
                                                           $commonreportcontent .="<td style='text-align:center'>".$avg3." %</td>";
														   }
														   
														   if($avg4==0){
															$commonreportcontent .="<td style='text-align:center'> </td>";
															}
															else{
                                                           $commonreportcontent .="<td style='text-align:center'>".$avg4." %</td>";
														   }
                                                      
                                                         
                                                       
                                                           
                                                  
                                                    
                                                    
                    $commonreportcontent .=   "</tr>
                                            </table>";
                    if($this->value('export') == 'csv')
                    {
                        $filename = $this->config['application_path'] . 'tmp_cache' . DIRECTORY_SEPARATOR . "$report.csv";
                        $fp = fopen($filename, 'w+');
                        
                        fputcsv($fp, array('Total Users', 'Oswestry 1','Oswestry 2','Oswestry 3','Oswestry 4'));
				                $array=array();   
                                                $array[0]=$cl_usr;
                                                
                                                   // print_r($row);
                                                   
                                                          $array[1]=$avg1;
                                                      
                                                           $array[2]=$avg2;
                                                      
                                                           $array[3]=$avg3;
                                                     
                                                           $array[4]=$avg4;
                                                
                                                   
                                 fputcsv($fp, $array);   
                                 //print_r($array);
                       
                        fclose($fp);

                        if (file_exists($filename))
                        {
                            header('Content-Description: File Transfer');
                            header('Content-Type: application/octet-stream');
                            header('Content-Disposition: attachment; filename='.basename($filename));
                            header('Content-Transfer-Encoding: binary');
                            header('Expires: 0');
                            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                            header('Pragma: public');
                            header('Content-Length: ' . filesize($filename));
                            ob_clean();
                            flush();
                            readfile($filename);
                            exit;
                        }
                    }
                    break;
                
                case 'averageuserlogins':
                    $reportname = 'Average number of user logins';
					
					 if( $this->value('from') || $this->value('to') )
                    {
                         $query = "SELECT COUNT( lh.user_id ) AS CC FROM login_history lh LEFT JOIN clinic_user cu ON lh.user_id = cu.user_id WHERE cu.clinic_id ='".$this->config['telespineid']."' AND lh.user_type =1 AND login_date_time BETWEEN '".$this->value('from')." 0:0:0' AND '".$this->value('to')." 23:59:59'";
						 
						 
						 
						 
                    }
                    else
                    {
                        $query = "SELECT COUNT( lh.user_id ) AS CC FROM login_history lh LEFT JOIN clinic_user cu ON lh.user_id = cu.user_id WHERE cu.clinic_id ='".$this->config['telespineid']."' AND lh.user_type =1";
                    }
					
					$resultarr = $this->fetch_all_rows($this->execute_query($query));
					
					
					
					
                    $commonreportcontent .= "<table cellpadding=\"2\" width=\"100%\" class=\"serReport\">
                                                <tr>
                                                    <td valign=\"top\" class=\"topnavnew\" style='text-align:center'>
                                                        Average logins
                                                    </td>
                                                </tr>";
											
											foreach($resultarr as $result){
						
											$commonreportcontent .= "<tr>
																		
																		<td style='text-align:center'>".round($result['CC']/$cl_usr)."</td>
																	</tr>";
						
						}
                                                
                                                
                                      $commonreportcontent .= "      </table>";
									  
									  
							if($this->value('export') == 'csv')
                    {
                        $filename = $this->config['application_path'] . 'tmp_cache' . DIRECTORY_SEPARATOR . "$report.csv";
                        $fp = fopen($filename, 'w+');
                        fputcsv($fp, array('Average logins'));
						
						
						foreach($resultarr as $result){
                        fputcsv($fp, array( round($result['CC']/$cl_usr)));
						}
                       
                        fclose($fp);

                        if (file_exists($filename))
                        {
                            header('Content-Description: File Transfer');
                            header('Content-Type: application/octet-stream');
                            header('Content-Disposition: attachment; filename='.basename($filename));
                            header('Content-Transfer-Encoding: binary');
                            header('Expires: 0');
                            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                            header('Pragma: public');
                            header('Content-Length: ' . filesize($filename));
                            ob_clean();
                            flush();
                            readfile($filename);
                            exit;
                        }
                    }	
											
											
											
											
											
											
											
											
                    break;
                    
                    case 'promocodereport':
                    $reportname = 'Sign-up Code Report';
                    $privateKey = $this->config['private_key'];
					
					 if( $this->value('from') || $this->value('to') )
                    {
                         
			 $query = "SELECT * ,AES_DECRYPT(UNHEX(user.name_title),'{$privateKey}') as name_title, AES_DECRYPT(UNHEX(user.name_first),'{$privateKey}') as name_first,
                                  AES_DECRYPT(UNHEX(user.name_last),'{$privateKey}') as name_last FROM `telespine_promocode`,user where `clinic_id`='{$this->config['telespineid']}' and telespine_promocode.status='1' and telespine_promocode.userid=user.user_id AND assigndate BETWEEN '".$this->value('from')." 0:0:0' AND '".$this->value('to')." 23:59:59'";
			 
						 
						 
						 
                    }
                    else
                    {
                        $query = "SELECT * ,AES_DECRYPT(UNHEX(user.name_title),'{$privateKey}') as name_title, AES_DECRYPT(UNHEX(user.name_first),'{$privateKey}') as name_first,
                                  AES_DECRYPT(UNHEX(user.name_last),'{$privateKey}') as name_last FROM `telespine_promocode`,user where `clinic_id`='{$this->config['telespineid']}' and telespine_promocode.status='1' and telespine_promocode.userid=user.user_id";
                    }
                    $link = $this->pagination($rows = 0,$query,'telespinereports&report=promocodereport',$searchString,'','',20);                                          
                    //$this->printR($link );
                    $replace['link'] = $link['nav'];
                    $resultnav = $link['result'];   
					
			$resultarr = $this->fetch_all_rows($resultnav);
					
			$commonreportcontent .= "<table cellpadding=\"2\" width=\"100%\" class=\"serReport\" id=\"paginate1\" class=\"rowstyle-alt max-pages-200 paginate-2\">
                                                 <tr>
                                                    <th valign=\"top\" class=\"topnavnew\">First Name</th>
                                                    <th valign=\"top\" class=\"topnavnew\"> Last Name</th>
                                                    <th valign=\"top\" class=\"topnavnew\">Code</th>
                                                    <th valign=\"top\" class=\"topnavnew\">Code Type</th>
                                                    <th valign=\"top\" class=\"topnavnew\">Channel Name</th>
                                                </tr>";
						foreach($resultarr as $result){
                                                    $chanel= explode('-', $result['promocode']);
                                                    $channelname=$this->config['channelname'][$chanel[0]];
                                                    $commonreportcontent .= "<tr>
						    <td style='text-align:left'>".$result['name_first']."</td>
                                                     <td style='text-align:left'>".$result['name_last']."</td>   
                                                     <td style='text-align:left'>".$result['promocode']."</td>
                                                     <td style='text-align:left'>Sign-up code</td>
                                                     <td style='text-align:left'>".$channelname."</td>
                                                    </tr>";
						}
                                      $commonreportcontent .= "</table>";
        			if($this->value('export') == 'csv')
                                    {
                                    $resultarr = $this->fetch_all_rows($this->execute_query($query));
                                    $filename = $this->config['application_path'] . 'tmp_cache' . DIRECTORY_SEPARATOR . "$report.csv";
                                    $fp = fopen($filename, 'w+');
                                    fputcsv($fp, array('First Name','Last Name','Code','Code Type','Channel Name'));
                                    foreach($resultarr as $result){
                                            fputcsv($fp, array($result['name_first'],$result['name_last'],$result['promocode'],'Sign-up code',$channelname  ));
                                    }
                       fclose($fp);
                        if (file_exists($filename))
                        {
                            header('Content-Description: File Transfer');
                            header('Content-Type: application/octet-stream');
                            header('Content-Disposition: attachment; filename='.basename($filename));
                            header('Content-Transfer-Encoding: binary');
                            header('Expires: 0');
                            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                            header('Pragma: public');
                            header('Content-Length: ' . filesize($filename));
                            ob_clean();
                            flush();
                            readfile($filename);
                            exit;
                        }
                    }	
	         break;
                 
                    case 'discountcoupon':
                    $reportname = 'Discount Coupon Report';
                    $privateKey = $this->config['private_key'];
					
					 if( $this->value('from') || $this->value('to') )
                    {
                        
			 $query = "SELECT * ,AES_DECRYPT(UNHEX(user.name_title),'{$privateKey}') as name_title, AES_DECRYPT(UNHEX(user.name_first),'{$privateKey}') as name_first,
                                  AES_DECRYPT(UNHEX(user.name_last),'{$privateKey}') as name_last FROM `telespine_discountcoupon_user`,user where telespine_discountcoupon_user.status='1' and telespine_discountcoupon_user.userid=user.user_id AND usedate BETWEEN '".$this->value('from')." 0:0:0' AND '".$this->value('to')." 23:59:59'";
			 
						 
						 
						 
                    }
                    else
                    {
                        $query = "SELECT * ,AES_DECRYPT(UNHEX(user.name_title),'{$privateKey}') as name_title, AES_DECRYPT(UNHEX(user.name_first),'{$privateKey}') as name_first,
                                  AES_DECRYPT(UNHEX(user.name_last),'{$privateKey}') as name_last FROM `telespine_discountcoupon_user`,user where  telespine_discountcoupon_user.status='1' and telespine_discountcoupon_user.userid=user.user_id";
                    }
                    $link = $this->pagination($rows = 0,$query,'telespinereports&report=discountcoupon',$searchString,'','',20);                                          
                    //$this->printR($link );
                    $replace['link'] = $link['nav'];
                    $resultnav = $link['result'];   
                    
			$resultarr = $this->fetch_all_rows(($resultnav));
					
			$commonreportcontent .= "<table cellpadding=\"2\" width=\"100%\" class=\"serReport\" id=\"paginate1\" class=\"rowstyle-alt max-pages-200 paginate-5\">
                                                 <tr>
                                                    <th valign=\"top\" class=\"topnavnew\">First Name</th>
                                                    <th valign=\"top\" class=\"topnavnew\"> Last Name</th>
                                                    <th valign=\"top\" class=\"topnavnew\">Code</th>
                                                    <th valign=\"top\" class=\"topnavnew\">Code Type</th>
                                                    <th valign=\"top\" class=\"topnavnew\">Channel Name</th>
                                                </tr>";
						foreach($resultarr as $result){
                                                    $chanel= explode('-', $result['code']);
                                                    $channelname=$this->config['channelname'][strtolower($chanel[0])];
                                                    $commonreportcontent .= "<tr>
						    <td style='text-align:left'>".$result['name_first']."</td>
                                                     <td style='text-align:left'>".$result['name_last']."</td>   
                                                     <td style='text-align:left'>".$result['code']."</td>
                                                     <td style='text-align:left'>Discount Code</td>
                                                     <td style='text-align:left'>".$channelname."</td>
                                                    </tr>";
						}
                                      $commonreportcontent .= "      </table>";
                                      
        			if($this->value('export') == 'csv')
                                    {
                                    $resultarr = $this->fetch_all_rows($this->execute_query($query));
                                    $filename = $this->config['application_path'] . 'tmp_cache' . DIRECTORY_SEPARATOR . "$report.csv";
                                    $fp = fopen($filename, 'w+');
                                    fputcsv($fp, array('First Name','Last Name','Code','Code Type','Channel Name'));
                                    foreach($resultarr as $result){
                                            fputcsv($fp, array($result['name_first'],$result['name_last'],$result['code'],'Discount Code',$channelname ));
                                    }
                       fclose($fp);
                        if (file_exists($filename))
                        {
                            header('Content-Description: File Transfer');
                            header('Content-Type: application/octet-stream');
                            header('Content-Disposition: attachment; filename='.basename($filename));
                            header('Content-Transfer-Encoding: binary');
                            header('Expires: 0');
                            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                            header('Pragma: public');
                            header('Content-Length: ' . filesize($filename));
                            ob_clean();
                            flush();
                            readfile($filename);
                            exit;
                        }
                    }	
	         break;
                default:
                    break;
            }
            
            $commonreportcontent .= "<script language=\"JavaScript1.2\">
                                        $(function() {
                                            $(\"#from\").datepicker({
                                                defaultDate: \"w\",
                                                changeMonth: true,
                                                numberOfMonths: 1,
                                                dateFormat: 'yy-mm-dd',
                                                onClose: function(selectedDate) {
                                                    $(\"#to\").datepicker(\"option\", \"minDate\", selectedDate);
                                                }
                                            });
                                            $(\"#to\").datepicker({
                                                defaultDate: \"w\",
                                                changeMonth: true,
                                                numberOfMonths: 1,
                                                dateFormat: 'yy-mm-dd',
                                                onClose: function(selectedDate) {
                                                    $(\"#from\").datepicker(\"option\", \"maxDate\", selectedDate);
                                                }
                                            });
                                        });
										
										
										
										$(\"#reportform\").submit(function () {
										
										
										var to = $.trim($('#to').val());
										var from = $.trim($('#from').val());

										// Check if empty of not
										if (to  === '') {
											alert('Date field should not empty.');
											return false;
										}
										// Check if empty of not
										if (from  === '') {
											alert('Date field should not empty.');
											return false;
										}
										
										
										
										
										
										
										});

                                    </script>";
            
        }
        else
        {
            $commonreportcontent = $this->build_template($this->get_template("telespinereports"), $replace);
        }
        
        $replace['report'] = str_replace("REPORT_NAME", $reportname, $commonreportcontent);
        
        $replace['body'] = $this->build_template($this->get_template("telespinereportstemplate"), $replace);
        

        $replace['browser_title'] = "Tx Xchange: TeleSpine Reports";
        
        $url_array = $this->tab_url();
        $replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"), $url_array);
        
        $this->output = $this->build_template($this->get_template("main"), $replace);
    }
       
}
    // creating object of this class.
    $obj = new healthServiceReport();

				/*
				header("Pragma: public"); // required
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Cache-Control: private",false); // required for certain browsers 
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename=\"".basename($filename)."\";" );
				header("Content-Transfer-Encoding: binary");
				header("Content-Length: ".filesize($filename));
				readfile("$filename");
				exit;

				*/
?>
