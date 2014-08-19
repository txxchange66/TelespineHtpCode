<?php
// including files	
	require_once("module/application.class.php");
    require_once("module/period.class.php");
	require_once("include/paging/my_pagina_class.php");	
	require_once("include/validation/_includes/classes/validation/ValidationSet.php");
	require_once("include/validation/_includes/classes/validation/ValidationError.php");	
    require_once("include/class.bargraph.php");    
    require_once("include/excel/excelwriter.inc.php");
	// class declaration
  	class systemReport extends application{
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
        * Highest numeric figure in Y-axis.
        */
        private $highestCount = 0;
        /**
        * Clinic Id
        */
        private $clinic_id = 0;
        /**
        * User Id
        */
        private $user_id;
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
				$str = "system_report"; //default if no action is specified
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
		
		function system_report(){
			// set template variables
			$replace['browser_title'] = 'Tx Xchange: Search Clinic';
			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['sidebar'] = $this->sidebar() ;
			$replace['heading'] = 'Search Clinic';
            $replace['search'] = $this->value('search');
            $replace['breadcrumb'] = $this->breadcrumb($this->value('action'));
			$replace['body'] = $this->build_template($this->get_template("search_clinic"),$replace);
			$this->output = $this->build_template($this->get_template("main"),$replace);
		}
        /** Function to search and display the listing of clinics **/
        function search_clinic(){
            $orderby = " order by clinic_name ";
            if($this->value('sort') != ""){
                if($this->value('order') == 'desc' ){
                    $orderby = " order by {$this->value('sort')} desc ";
                }
                else{
                    $orderby = " order by {$this->value('sort')} ";
                }
            }
            
            $searchString = $this->value('search');
            if( !empty($searchString) ){
                $searchWhere = " clinic_name like '%{$searchString}%' ";
                $sqlUser = "select * from clinic where {$searchWhere} {$orderby}";
                $link = $this->pagination($rows = 0,$sqlUser,'search_clinic',$searchString);                                          
                $replace['link'] = $link['nav'];
                $result = $link['result'];     
                if($this->num_rows($result)!= 0)
                {
                    while($row = $this->fetch_array($result))
                    {
                        $row['style'] = ($c++%2)?"line1":"line2";
                        /*$primaryClinic = $this->get_field($row['clinic_id'],'clinic','parent_clinic_id');
                        if( is_null($primaryClinic) || $primaryClinic == 0 ){
                            $row['style'] = "line3";
                        }*/
                        
                        $row['status'] = $this->get_status($row['status']);
                        $row['state'] = $this->config['state'][$row['state']];
                        $row['creationDate'] = $this->formatDate($row['creationDate']);
                        $replace['clinicListRecord'] .= $this->build_template($this->get_template("clinicListRecord"),$row);
                    }
                }
                else{
                        $replace['clinicListRecord'] = $this->build_template($this->get_template("clinicListNoRecord"),$row);
                }
            }                   
            
            $clinicListHead = array(
                'clinic_name' => 'Clinic Name',
                'city' => 'City',
                'state' => 'State',
                'status' => 'Status',
                'creationDate' => 'Created On'
            );
            
            $replace['search'] = $this->value('search');
            $replace['breadcrumb'] = $this->breadcrumb('search_clinic');
            $replace['listClinicHead'] = $this->build_template($this->get_template("clinicListingHead"),$this->table_heading($clinicListHead,"clinic_name",$query_string));
            $replace['browser_title'] = 'Tx Xchange: Clinic List';
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['sidebar'] = $this->sidebar() ;
            $replace['heading'] = 'Clinic List';
            $replace['body'] = $this->build_template($this->get_template("searchClinic"),$replace);
            $this->output = $this->build_template($this->get_template("main"),$replace);
        }
        /**
        * @desc function to select period of report.
        * 
        */
        function select_period(){
            $replace = array();
            $replace['browser_title'] = 'Tx Xchange: Select Period';
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['sidebar'] = $this->sidebar() ;
            $replace['heading'] = 'Period';
            $replace['clinic_id'] = $this->value('clinic_id');
            $replace['user_id'] = $this->value('user_id');
            $replace['report_for'] = '';
            $replace['report_type'] = $this->value('report_type');
            $replace['breadcrumb'] = $this->breadcrumb('select_period');
            $period = $this->value('period');
            $replace['period'] = $period != ""?$period:'one';
            if( isset($replace['clinic_id']) && is_numeric($replace['clinic_id']) ){
                $replace['report_for'] = 'clinic';                
                if(isset($replace['user_id']) && is_numeric($replace['user_id'])){
                    $replace['report_for'] = 'therapist';
                }
            }
            $replace['randnumber'] = time();
            $replace['body'] = $this->build_template($this->get_template("selectPeriod"),$replace);
            $this->output = $this->build_template($this->get_template("main"),$replace);
        }
        /**
        * @desc function to select period of report.
        * 
        */
        function select_period_global(){
            $replace = array();
            $replace['browser_title'] = 'Tx Xchange: Select Period';
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['sidebar'] = $this->sidebar() ;
            $replace['heading'] = 'Period';
            $replace['clinic_id'] = $this->value('clinic_id');
            $replace['report_type'] = $this->value('report_type');
            $replace['report_for'] = 'global_report';
            $navigate = array();
            $navigate['report_for'] = 'global_report';
            $replace['breadcrumb'] = $this->breadcrumb('select_period_global',$navigate);
            $period = $this->value('period');
            $replace['period'] = $period != ""?$period:'one';
            $replace['randnumber'] = time();
            $replace['body'] = $this->build_template($this->get_template("selectPeriod"),$replace);
            $this->output = $this->build_template($this->get_template("main"),$replace);
        }
        /**
         * This function is used to display the Graphical Report.
         * 
         * @access private
         * 
         */
        function graphical_report(){
            // set template variables
            $replace = array();
            $replace['browser_title'] = 'Tx Xchange: Graph Report';
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['sidebar'] = $this->sidebar() ;
            $clinic_id = $this->value('clinic_id');
            if( is_numeric($clinic_id) ){
                $this->clinic_id = $clinic_id;
            }
            $user_id = $this->value('user_id');
            if( is_numeric($user_id) ){
                $this->user_id = $user_id;
            }
            $period = $this->value('period');
            $replace['report_type'] = $this->value('report_type');
            $replace['report_for'] = $this->value('report_for');
            
            $timeInterval = $this->getInterval($period);
            $data = $this->getGraphData($replace['report_type'],$period,$timeInterval,$replace['report_for']);
            $data = array_reverse($data,true);
            
            $replace['graph'] ="<img src='index.php?action=generateGraph&report_for={$replace['report_for']}&report_type={$replace['report_type']}&period={$period}&clinic_id={$clinic_id}&user_id={$user_id}'   style='vertical-align:middle;' usemap ='#WeeklyGraph' border='0'>" ;
            $replace['graph'] .= "<map id ='WeeklyGraph' name='WeeklyGraph'>";
            $dim1 = 200;
            
            $cnt = 1;
            foreach( $data as $key => $value ){
               if( $cnt > 2 ){
                        $x1 = ($cnt * 40) - (10*++$c);
               }
               else{
                   $x1 = $cnt * 40;
               }
               $y1 = 100 - $value;
               $x2 = $x1 + 40;
               $cnt += 2;
               $replace['graph'] .= "<area shape ='rect' coords ='{$x1},{$y1},{$x2},230' alt='{$value}' title='{$value}'  />";
            }
            $replace['graph'] .= "</map>";

            $url_array = $this->tab_url($user_id,$clinic_id,$period);
            $replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$url_array);        
        
            $param = array();
            $param['report_for'] = $this->value('report_for');
            $param['report_type'] = $this->value('report_type');
            $param['period'] = $this->value('period');
            $replace['lineContent'] = $this->lineContent($param);
            
            $replace['tableHeader'] = $this->build_template($this->get_template("tableHeader")) ;
            
            $navigate = array();
            $navigate['report_for'] = $this->value('report_for');
            $replace['breadcrumb'] = $this->breadcrumb('graphical_report',$navigate);
            
            $replace['body'] = $this->build_template($this->get_template("graphReport"),$replace);
            $this->output = $this->build_template($this->get_template("main"),$replace);
        }
        /**
         * This function is used to display the Graphical Report.
         * 
         * @access private
         * 
         */
        function generateGraph(){
            // set template variables
            $clinic_id = $this->value('clinic_id');
            $this->clinic_id = $clinic_id;
            
            $user_id = $this->value('user_id');
            $this->user_id = $this->value('user_id');
            
            $period = $this->value('period');
            
            $replace['report_type'] = $this->value('report_type');
            $replace['report_for'] = $this->value('report_for');
            $this->drawGraph($replace['report_for'],$replace['report_type'],$period);
        }
        function lineContent($param = array()){
            $lineContent = "";
            if( $param['report_for'] == "global_report" ){
                if($param['report_type'] == "one" ){
                    $replace['line1Heading'] = 'Report On:';
                    $replace['line1Content'] = 'Number of Template Plan by all Users';
                    $lineContent = $this->build_template($this->get_template("lineContent"),$replace);
                }
                if($param['report_type'] == "two" ){
                    $replace['line1Heading'] = 'Report On:';
                    $replace['line1Content'] = 'Number of Assigned Plan by all Users';
                    $lineContent = $this->build_template($this->get_template("lineContent"),$replace);
                }
                if($param['report_type'] == "three" ){
                    $replace['line1Heading'] = 'Report On:';
                    $replace['line1Content'] = 'Number of New Patients created';
                    $lineContent = $this->build_template($this->get_template("lineContent"),$replace);
                }
                if($param['report_type'] == "four" ){
                    $replace['line1Heading'] = 'Report On:';
                    $replace['line1Content'] = 'Number of Message sent by all Users';
                    $lineContent = $this->build_template($this->get_template("lineContent"),$replace);
                }
                if($param['report_type'] == "five" ){
                    $replace['line1Heading'] = 'Report On:';
                    $replace['line1Content'] = 'Number of User Logins';
                    $lineContent = $this->build_template($this->get_template("lineContent"),$replace);
                }
                if($param['report_type'] == "six" ){
                    $replace['line1Heading'] = 'Report On:';
                    $replace['line1Content'] = 'Number of Patient Logins';
                    $lineContent = $this->build_template($this->get_template("lineContent"),$replace);
                }
                if($param['report_type'] == "seven" ){
                    $replace['line1Heading'] = 'Report On:';
                    $replace['line1Content'] = 'Number of invitation send by Patient';
                    $lineContent = $this->build_template($this->get_template("lineContent"),$replace);
                }
                if($param['report_type'] == "eight" ){
                    $replace['line1Heading'] = 'Report On:';
                    $replace['line1Content'] = 'Number of invitation send by Provider';
                    $lineContent = $this->build_template($this->get_template("lineContent"),$replace);
                }
            }
            if( $param['report_for'] == 'clinic' ){
                if($param['report_type'] == "one" ){
                    $replace['line1Heading'] = 'Report On:';
                    $replace['line1Content'] = "Number of Template Plans created by User's of clinic <i>{$this->get_field($this->clinic_id,'clinic','clinic_name')}</i>";
                    $lineContent = $this->build_template($this->get_template("lineContent"),$replace);
                }
                if($param['report_type'] == "two" ){
                    $replace['line1Heading'] = 'Report On:';
                    $replace['line1Content'] = "Number of Assigned Plans by User's of clinic <i>{$this->get_field($this->clinic_id,'clinic','clinic_name')}</i>";
                    $lineContent = $this->build_template($this->get_template("lineContent"),$replace);
                }
                if($param['report_type'] == "three" ){
                    $replace['line1Heading'] = 'Report On:';
                    $replace['line1Content'] = "Number of New Patients created by User's of clinic <i>{$this->get_field($this->clinic_id,'clinic','clinic_name')}</i>";
                    $lineContent = $this->build_template($this->get_template("lineContent"),$replace);
                }
                if($param['report_type'] == "four" ){
                    $replace['line1Heading'] = 'Report On:';
                    $replace['line1Content'] = "Number of Messages sent by User's of clinic <i>{$this->get_field($this->clinic_id,'clinic','clinic_name')}</i>";
                    $lineContent = $this->build_template($this->get_template("lineContent"),$replace);
                }
                if($param['report_type'] == "five" ){
                    $replace['line1Heading'] = 'Report On:';
                    $replace['line1Content'] = "Number of User Logins in clinic <i>{$this->get_field($this->clinic_id,'clinic','clinic_name')}</i>";
                    $lineContent = $this->build_template($this->get_template("lineContent"),$replace);
                }
                if($param['report_type'] == "six" ){
                    $replace['line1Heading'] = 'Report On:';
                    $replace['line1Content'] = "Number of Patient Logins <i>{$this->get_field($this->clinic_id,'clinic','clinic_name')}</i>";
                    $lineContent = $this->build_template($this->get_template("lineContent"),$replace);
                }
            }
            if( $param['report_for'] == 'therapist' ){
                $therapist = $this->get_field($this->user_id,'user','therapist_access')=='1'?"Therapist":"";
                $admin = $this->get_field($this->user_id,'user','admin_access')=='1'?"Admin":"";
                if($param['report_type'] == "one" ){
                    $replace['line1Heading'] = 'Report On:';
                    $replace['line1Content'] = "Number of Template Plans created by <i>{$this->get_field($this->user_id,'user','name_first')}&nbsp;{$this->get_field($this->user_id,'user','name_last')}</i>";
                }
                if($param['report_type'] == "two" ){
                    $replace['line1Heading'] = 'Report On:';
                    $replace['line1Content'] = "Number of Assigned Plans by <i>{$this->get_field($this->user_id,'user','name_first')}&nbsp;{$this->get_field($this->user_id,'user','name_last')}</i>";
                }
                if($param['report_type'] == "three" ){
                    $replace['line1Heading'] = 'Report On:';
                    $replace['line1Content'] = "Number of New Patients created by <i>{$this->get_field($this->user_id,'user','name_first')}&nbsp;{$this->get_field($this->user_id,'user','name_last')}</i>";
                }
                if($param['report_type'] == "four" ){
                    $replace['line1Heading'] = 'Report On:';
                    $replace['line1Content'] = "Number of Messages sent by <i>{$this->get_field($this->user_id,'user','name_first')}&nbsp;{$this->get_field($this->user_id,'user','name_last')}</i>";
                }
                if($param['report_type'] == "five" ){
                    $replace['line1Heading'] = 'Report On:';
                    $replace['line1Content'] = "Number of Logins";
                }
                if($param['report_type'] == "six" ){
                    $replace['line1Heading'] = 'Report On:';
                    $replace['line1Content'] = "Number of Patient Logins";
                }
                if( $therapist != "" ){
                    if( $admin != "" ){
                        $replace['line1Content'] .= " (Role: {$therapist}, {$admin})";
                    }
                    else{
                        $replace['line1Content'] .= " (Role: {$therapist})";                    
                    }
                }
                else{
                    if( $admin != "" ){
                        $replace['line1Content'] .= " (Role: {$admin})";
                    }
                }
                $lineContent = $this->build_template($this->get_template("lineContent"),$replace);    
            }
            if( $param['period'] == "one" ){
                        $replace['line1Heading'] = 'Period:';
                        $replace['line1Content'] = 'Last 7 days';
                        $lineContent .= $this->build_template($this->get_template("lineContent"),$replace);
            }
            if( $param['period'] == "two" ){
                $replace['line1Heading'] = 'Period:';
                $replace['line1Content'] = 'Current Month to date';
                $lineContent .= $this->build_template($this->get_template("lineContent"),$replace);
            }
            if( $param['period'] == "three" ){
                $replace['line1Heading'] = 'Period:';
                $replace['line1Content'] = 'Last Month';
                $lineContent .= $this->build_template($this->get_template("lineContent"),$replace);
            }
            if( $param['period'] == "four" ){
                $replace['line1Heading'] = 'Period:';
                $replace['line1Content'] = 'Last Quarter';
                $lineContent .= $this->build_template($this->get_template("lineContent"),$replace);
            }
            if( $param['period'] == "five" ){
                $replace['line1Heading'] = 'Period:';
                $replace['line1Content'] = 'Current Year to date';
                $lineContent .= $this->build_template($this->get_template("lineContent"),$replace);
            }

            return $lineContent;
        }
        function drawGraph($report_for,$report,$period){
            $timeInterval = $this->getInterval($period);
            $data = $this->getGraphData($report,$period,$timeInterval,$report_for);
            
            $g = new BarGraph();
            
            $g->SetGraphAreaHeight(200);
            $g->SetGraphPadding(20, 30, 20, 15);
            
            if($period == 'one'){
                $g->SetAxisStep(5);
            }
            else{
                $this->highestCount = (floor($this->highestCount/100) + 1) * 10;            
                $g->SetAxisStep(floor($this->highestCount));
            }
            $g->SetBarPadding(20);
            $data = array_reverse($data,true);
            $g->SetBarData($data);
            $g->SetGraphTitle("");
            $g->DrawGraph(); 
        }
        function getGraphData($report,$period,$timeInterval = array(),$report_for=""){
            
            switch ($period) {
            case 'one':
                   $data = array() ;
                   foreach( $timeInterval as $key => $value )
                   {
                       // Template plan created
                       if( $report == "one" ){
                            if( $report_for == "global_report" ){ 
                                $query = "SELECT count(distinct(plan_name)) FROM plan  where patient_id is null and date(creation_date) = '{$value}' ";
                            }
                            elseif( $report_for == "clinic" ){
                                if( is_numeric($this->clinic_id) && $this->clinic_id > 0 ){
                                    //$query = "SELECT count(distinct(plan_name)) FROM plan  
                                    //where patient_id is null and user_id in (select user_id from clinic_user where clinic_id = '{$this->clinic_id}' ) 
                                    //and  date(creation_date) = '{$value}' ";
                                    
                                    $query = "SELECT count( * ) FROM plan p1
                                                INNER JOIN ( SELECT min(plan_id) as plan_id FROM plan GROUP BY plan_name) AS p2 
                                                ON p2.plan_id = p1.plan_id
                                                WHERE p1.patient_id IS NULL
                                                AND p1.user_id
                                                IN (
                                                        SELECT user_id
                                                        FROM clinic_user
                                                        WHERE clinic_id = '{$this->clinic_id}'
                                                ) 
                                                and  date(creation_date) = '{$value}'";
                                    
                                }
                            }
                            elseif( $report_for == "therapist" ){
                                if( (is_numeric($this->clinic_id) && $this->clinic_id > 0) && (is_numeric($this->user_id) && $this->user_id > 0) ){
                                    //$query = "SELECT count(distinct(plan_name)) FROM plan  
                                    //where patient_id is null and user_id = '{$this->user_id}' and  date(creation_date) = '{$value}' ";
                                    $query = "SELECT count(*) FROM plan p1
                                                INNER JOIN ( SELECT min(plan_id) as plan_id FROM plan GROUP BY plan_name) AS p2 
                                                ON p2.plan_id = p1.plan_id
                                                WHERE p1.patient_id IS NULL
                                                AND p1.user_id = '{$this->user_id}'
                                                and date(creation_date) = '{$value}' ";
                                }
                            }
                       }
                      
                       // Assigned plan report
                       if( $report == "two" ){
                            if( $report_for == "global_report" ){ 
                                $query = "select count(*) from plan where (patient_id is not null or patient_id != '') and date(creation_date) = '{$value}' ";        
                            }
                            // Clinic level
                            elseif( $report_for == "clinic" ){
                                if( is_numeric($this->clinic_id) && $this->clinic_id > 0 ){
                                    $query = "select count(*) from plan where (patient_id is not null or patient_id != '') and user_id in (select user_id from clinic_user where clinic_id = '{$this->clinic_id}' ) and  date(creation_date) = '{$value}' ";
                                }
                            }
                            // Therapist level
                            elseif( $report_for == "therapist" ){
                                if( (is_numeric($this->clinic_id) && $this->clinic_id > 0) && (is_numeric($this->user_id) && $this->user_id > 0) ){
                                    $query = "select count(*) from plan where (patient_id is not null or patient_id != '') and user_id = '{$this->user_id}' and  date(creation_date) = '{$value}' ";
                                }
                            }
                       } 
                       // New Patients created
                        if( $report == "three" ){
                            if( $report_for == "global_report" ){ 
                                $query = "select count(*) from user where usertype_id = 1  and date(creation_date) = '{$value}' ";
                            }
                            // Clinic level
                            elseif( $report_for == "clinic" ){
                                if( is_numeric($this->clinic_id) && $this->clinic_id > 0 ){
                                    $query = " select count(*) from user where usertype_id = 1  and user_id in (select user_id from clinic_user where clinic_id = '{$this->clinic_id}' ) and date(creation_date) = '{$value}' ";
                                    
                                }
                            }
                            // Therapist level
                            elseif( $report_for == "therapist" ){
                                if( (is_numeric($this->clinic_id) && $this->clinic_id > 0) && (is_numeric($this->user_id) && $this->user_id > 0) ){
                                    $query = " select count(*) from user where usertype_id = 1  and created_by = '{$this->user_id}' and date(creation_date) = '{$value}' ";
                                }
                            }
                        }
                       // Message report
                        if( $report == "four" ){
                            if( $report_for == "global_report" ){ 
                                $query = "select count(*) from message where date(sent_date) = '{$value}' ";        
                            }
                            // Clinic level
                            elseif( $report_for == "clinic" ){
                                if( is_numeric($this->clinic_id) && $this->clinic_id > 0 ){
                                    $query = "select count(*) from message where sender_id in (select user_id from clinic_user where clinic_id = '{$this->clinic_id}' ) and date(sent_date) = '{$value}' ";
                                    
                                }
                            }
                            // Therapist level
                            elseif( $report_for == "therapist" ){
                                if( (is_numeric($this->clinic_id) && $this->clinic_id > 0) && (is_numeric($this->user_id) && $this->user_id > 0) ){
                                    $query = "select count(*) from message where sender_id = '{$this->user_id}' and date(sent_date) = '{$value}' ";
                                }
                            }
                        }
                        // User login
                        if( $report == "five" ){
                            if( $report_for == "global_report" ){ 
                                $query = "select count(*) from login_history where date(login_date_time) = '{$value}' and user_type = 2 ";
                            }
                            // Clinic level
                            elseif( $report_for == "clinic" ){
                                if( is_numeric($this->clinic_id) && $this->clinic_id > 0 ){
                                    $query = "select count(*) from login_history 
                                        where user_type = 2 and user_id in (select user_id from clinic_user where clinic_id = '{$this->clinic_id}' ) 
                                        and date(login_date_time) = '{$value}' ";
                                }
                            }
                            // Therapist level
                            elseif( $report_for == "therapist" ){
                                if( (is_numeric($this->clinic_id) && $this->clinic_id > 0) && (is_numeric($this->user_id) && $this->user_id > 0) ){
                                    $query = "select count(*) from login_history where user_type = 2 and user_id = '{$this->user_id}' and date(login_date_time) = '{$value}' ";
                                }
                            }
                        }
                        // Patient login
                        if( $report == "six" ){
                            if( $report_for == "global_report" ){ 
                                $query = "select count(*) from login_history where date(login_date_time) = '{$value}' and user_type = 1 ";
                            }
                            // Clinic level
                            elseif( $report_for == "clinic" ){
                                if( is_numeric($this->clinic_id) && $this->clinic_id > 0 ){
                                    $query = "select count(*) from login_history 
                                        where user_type = 1 and user_id in (select user_id from clinic_user where clinic_id = '{$this->clinic_id}' ) 
                                        and date(login_date_time) = '{$value}' ";
                                }
                            }
                            // Therapist level
                            elseif( $report_for == "therapist" ){
                                if( (is_numeric($this->clinic_id) && $this->clinic_id > 0) && (is_numeric($this->user_id) && $this->user_id > 0) ){
                                    $query = "select count(*) from login_history 
                                        where user_type = 1 and user_id in (select patient_id from therapist_patient where therapist_id = '{$this->user_id}' ) 
                                        and date(login_date_time) = '{$value}' ";
                                }
                            }
                        }
                         // report for send invite by patient
                        if( $report == "seven" ){
                            if( $report_for == "global_report" ){ 
                               $query = "select count(*) from sendinvite where  date(datefosend) = '{$value}' and user_type = 1";
                            }
                        }
                         // report for send invite by patient
                        if( $report == "eight" ){
                            if( $report_for == "global_report" ){ 
                                $query = "select count(*) from sendinvite where  date(datefosend) = '{$value}' and user_type = 2";
                            }
                        }
                                              
                        $result = $this->execute_query($query) ;
                        $cnt = $this->num_rows($result) ;
                        if($cnt >= 0){
                            if($row = $this->fetch_array($result)){
                                if($this->highestCount < $row[0] ){
                                    $this->highestCount = $row[0];
                                }
                                $data[$key] = $row[0] ;
                            }
                        }
                    }
                    return $data;
                    break;
            case 'two'||'three'||'four'||'five':
                   $data = array() ;
                   foreach( $timeInterval as $key => $value )
                   {
                       // Template plan created
                       if( $report == "one" ){
                           // Global
                            if( $report_for == "global_report" ){ 
                                $query = "SELECT count(distinct(plan_name)) FROM plan  where patient_id is null and date(creation_date ) >= '{$value['from']}' and date(creation_date ) <= '{$value['to']}' ";
                            }
                            // Clinic
                            elseif( $report_for == "clinic" ){
                                if( is_numeric($this->clinic_id) && $this->clinic_id > 0 ){
                                    //$query = "SELECT count(distinct(plan_name)) FROM plan  
                                    //where patient_id is null and user_id in (select user_id from clinic_user where clinic_id = '{$this->clinic_id}' ) 
                                    //and date(creation_date ) >= '{$value['from']}' and date(creation_date ) <= '{$value['to']}' ";
                                    $query = "SELECT count(*) FROM plan p1
                                                INNER JOIN ( SELECT min(plan_id) as plan_id FROM plan GROUP BY plan_name) AS p2 
                                                ON p2.plan_id = p1.plan_id
                                                WHERE p1.patient_id IS NULL
                                                AND p1.user_id
                                                IN (
                                                        SELECT user_id
                                                        FROM clinic_user
                                                        WHERE clinic_id = '{$this->clinic_id}'
                                                ) 
                                                and date(creation_date ) >= '{$value['from']}' and date(creation_date ) <= '{$value['to']}' ";
                                }
                            }
                            // Therapist
                            elseif( $report_for == "therapist" ){
                                if( (is_numeric($this->clinic_id) && $this->clinic_id > 0) && (is_numeric($this->user_id) && $this->user_id > 0) ){
                                    //$query = "SELECT count(distinct(plan_name)) FROM plan  
                                    //where patient_id is null and user_id = '{$this->user_id}' and date(creation_date ) >= '{$value['from']}' and date(creation_date ) <= '{$value['to']}' ";
                                    $query = "SELECT count(*) FROM plan p1
                                                INNER JOIN ( SELECT min(plan_id) as plan_id FROM plan GROUP BY plan_name) AS p2 
                                                ON p2.plan_id = p1.plan_id
                                                WHERE p1.patient_id IS NULL
                                                AND p1.user_id = '{$this->user_id}'
                                                and date(creation_date ) >= '{$value['from']}' and date(creation_date ) <= '{$value['to']}' ";
                                }
                            }
                       }
                       // Assigned plan report
                        if( $report == "two" ){
                            //Global level
                            if( $report_for == "global_report" ){ 
                                $query = "select count(*) from plan where (patient_id is not null or patient_id != '') and date(creation_date ) >= '{$value['from']}' and date(creation_date ) <= '{$value['to']}'";        
                            }
                            // Clinic level
                            elseif( $report_for == "clinic" ){
                                if( is_numeric($this->clinic_id) && $this->clinic_id > 0 ){
                                    $query = "select count(*) from plan where (patient_id is not null or patient_id != '') and user_id in (select user_id from clinic_user where clinic_id = '{$this->clinic_id}' ) and date(creation_date ) >= '{$value['from']}' and date(creation_date ) <= '{$value['to']}' ";
                                }
                            }
                            // Therapist level
                            elseif( $report_for == "therapist" ){
                                if( (is_numeric($this->clinic_id) && $this->clinic_id > 0) && (is_numeric($this->user_id) && $this->user_id > 0) ){
                                    $query = "select count(*) from plan where (patient_id is not null or patient_id != '') and user_id = '{$this->user_id}' and date(creation_date ) >= '{$value['from']}' and date(creation_date ) <= '{$value['to']}' ";
                                }
                            }
                       }
                       // New Patients created
                        if( $report == "three" ){
                            if( $report_for == "global_report" ){ 
                                $query = "select count(*) from user where usertype_id = 1  and date(creation_date) >= '{$value['from']}' and date(creation_date) <= '{$value['to']}'";
                            }
                            // Clinic level
                            elseif( $report_for == "clinic" ){
                                if( is_numeric($this->clinic_id) && $this->clinic_id > 0 ){
                                    $query = " select count(*) from user where usertype_id = 1  and user_id in (select user_id from clinic_user where clinic_id = '{$this->clinic_id}' ) and date(creation_date ) >= '{$value['from']}' and date(creation_date ) <= '{$value['to']}' ";
                                    
                                }
                            }
                            // Therapist level
                            elseif( $report_for == "therapist" ){
                                if( (is_numeric($this->clinic_id) && $this->clinic_id > 0) && (is_numeric($this->user_id) && $this->user_id > 0) ){
                                    $query = " select count(*) from user where usertype_id = 1  and created_by = '{$this->user_id}' and date(creation_date ) >= '{$value['from']}' and date(creation_date ) <= '{$value['to']}' ";
                                }
                            }
                        }
                       // Message report 
                       if( $report == "four" ){
                            if( $report_for == "global_report" ){ 
                                $query = "select count(*) from message where date(sent_date) >= '{$value['from']}' and date(sent_date) <= '{$value['to']}'";
                            }
                            // Clinic level
                            elseif( $report_for == "clinic" ){
                                if( is_numeric($this->clinic_id) && $this->clinic_id > 0 ){
                                    $query = "select count(*) from message where sender_id in (select user_id from clinic_user where clinic_id = '{$this->clinic_id}' ) and date(sent_date) >= '{$value['from']}' and date(sent_date) <= '{$value['to']}' ";
                                    
                                }
                            }
                            // Therapist level
                            elseif( $report_for == "therapist" ){
                                if( (is_numeric($this->clinic_id) && $this->clinic_id > 0) && (is_numeric($this->user_id) && $this->user_id > 0) ){
                                    $query = "select count(*) from message where sender_id = '{$this->user_id}' and date(sent_date) >= '{$value['from']}' and date(sent_date) <= '{$value['to']}' ";
                                }
                            }
                            
                        }
                        // User login    
                        if( $report == "five" ){
                            if( $report_for == "global_report" ){ 
                                $query = "select count(*) from login_history where date(login_date_time) >= '{$value['from']}' and date(login_date_time) <= '{$value['to']}' and user_type = 2 ";
                            }
                            // Clinic level
                            elseif( $report_for == "clinic" ){
                                if( is_numeric($this->clinic_id) && $this->clinic_id > 0 ){
                                    $query = "select count(*) from login_history 
                                        where user_type = 2 and user_id in (select user_id from clinic_user where clinic_id = '{$this->clinic_id}' ) 
                                        and date(login_date_time) >= '{$value['from']}' and date(login_date_time) <= '{$value['to']}'  ";
                                }
                            }
                            // Therapist level
                            elseif( $report_for == "therapist" ){
                                if( (is_numeric($this->clinic_id) && $this->clinic_id > 0) && (is_numeric($this->user_id) && $this->user_id > 0) ){
                                    $query = "select count(*) from login_history where user_type = 2 and user_id = '{$this->user_id}' and date(login_date_time) >= '{$value['from']}' and date(login_date_time) <= '{$value['to']}' ";
                                }
                            }
                        }
                        // User Patient login    
                        if( $report == "six" ){
                            if( $report_for == "global_report" ){ 
                                $query = "select count(*) from login_history where date(login_date_time) >= '{$value['from']}' and date(login_date_time) <= '{$value['to']}' and user_type = 1 ";
                            }
                            // Clinic level
                            elseif( $report_for == "clinic" ){
                                if( is_numeric($this->clinic_id) && $this->clinic_id > 0 ){
                                    $query = "select count(*) from login_history 
                                        where user_type = 1 and user_id in (select user_id from clinic_user where clinic_id = '{$this->clinic_id}' ) 
                                        and date(login_date_time) >= '{$value['from']}' and date(login_date_time) <= '{$value['to']}'  ";
                                }
                            }
                            // Therapist level
                            elseif( $report_for == "therapist" ){
                                if( (is_numeric($this->clinic_id) && $this->clinic_id > 0) && (is_numeric($this->user_id) && $this->user_id > 0) ){
                                    $query = "select count(*) from login_history 
                                        where user_type = 1 and user_id in (select patient_id from therapist_patient where therapist_id = '{$this->user_id}' ) 
                                        and date(login_date_time) >= '{$value['from']}' and date(login_date_time) <= '{$value['to']}' ";
                                }
                            }
                        }
                           // report for send invite for paitent
                        if( $report == "seven" ){
                            if( $report_for == "global_report" ){ 
                                $query = "select count(*) from sendinvite where  date(datefosend) >= '{$value['from']}' and date(datefosend) <= '{$value['to']}' and user_type=1";   
                            }
                        }
                            // report for send eight provider
                        if( $report == "eight" ){
                            if( $report_for == "global_report" ){ 
                                $query = "select count(*) from sendinvite where  date(datefosend) >= '{$value['from']}' and date(datefosend) <= '{$value['to']}' and user_type=2";   
                            }
                        }
                        $result = $this->execute_query($query) ;
                        $cnt = $this->num_rows($result) ;
                        if($cnt >= 0){
                            if($row = $this->fetch_array($result)){
                                if($this->highestCount < $row[0] ){
                                    $this->highestCount = $row[0];
                                }
                                $data[$key] = $row[0] ;
                            }
                        }
                    }
                    return $data;
                    
                break;
            }
        }    
        
        function getInterval($period=""){
            
            switch ($period) {
                case 'one':
                       $m= date("m");
                       $de= date("d");
                       $y= date("Y");
                       $date = array();
                       for($i=1; $i<=7; $i++){
                           $dateKey = date('m/d/Y',mktime(0,0,0,$m,($de-$i),$y));
                           $dateValue = date('Y-m-d',mktime(0,0,0,$m,($de-$i),$y));
                           $date[$dateKey] = $dateValue;
                       } 
                    return $date;
                    break;
                case 'two':
                       
                       $currentDate = date('Y-m-d'); 
                       $startDateOfMonth = date('Y') . "-" . date("m") . "-" . "01";
                       
                       $weekRangeDate[date("F",strtotime($startDateOfMonth))]['from'] = $startDateOfMonth;
                       $weekRangeDate[date("F",strtotime($currentDate))]['to'] = $currentDate;
                       
                       return $weekRangeDate;
                    break;
                case 'three':
                       $objPeriod = new Period();
                       $objPeriod->last_month();
                       $startDateofLastMonth = date("Y-m-d",strtotime($objPeriod->start));
                       $endDateofLastMonth = date("Y-m-d",strtotime($objPeriod->end));
                       
                       $weekRangeDate[date("F",strtotime($startDateofLastMonth))]['from'] = $startDateofLastMonth;
                       $weekRangeDate[date("F",strtotime($endDateofLastMonth))]['to'] = $endDateofLastMonth;
                       
                       return $weekRangeDate;
                    break;
                    
                case 'four':
                       $objPeriod = new Period();
                       $objPeriod->last_quarter();
                       $year = date("Y",strtotime($objPeriod->end));
                       $startMonth = date("m",strtotime($objPeriod->start));
                       $endMonth = date("m",strtotime($objPeriod->end));
                       $weekRangeDate = array();
                       while( $endMonth >= $startMonth ){
                           $startDateOfWeek =  $year . "-" . $endMonth ."-" . "01";
                           $endDateOfWeek = $year . "-" . $endMonth ."-" . date("t",strtotime($startDateOfWeek));
                           $fullMonth = date("F",strtotime($startDateOfWeek));
                           $weekRangeDate[$fullMonth]['from'] = $startDateOfWeek;
                           $weekRangeDate[$fullMonth]['to'] = $endDateOfWeek;
                           $endMonth = $endMonth - 1;
                           if( $endMonth < 10){
                               $endMonth = '0'.$endMonth;
                           }
                       }
                       return $weekRangeDate;
                    break;    
                case 'five':
                       $year = date("Y");
                       $startMonth = "01";
                       $endMonth = date("m",strtotime(date('Y-m-d')));
                       $weekRangeDate = array();
                       while( $endMonth >= $startMonth ){
                           $startDateOfWeek =  $year . "-" . $endMonth ."-" . "01";
                           $endDateOfWeek = $year . "-" . $endMonth ."-" . date("t",strtotime($startDateOfWeek));
                           $fullMonth = date("F",strtotime($startDateOfWeek));
                           $weekRangeDate[$fullMonth]['from'] = $startDateOfWeek;
                           $weekRangeDate[$fullMonth]['to'] = $endDateOfWeek;
                           $endMonth = $endMonth - 1;
                           if( $endMonth < 10){
                               $endMonth = '0'.$endMonth;
                           }
                       }
                       return $weekRangeDate;
                    break;    
            }
            
        }
        // Current quarter of the Year
        function quarter( $currentMonthValue="")
        {
            if(!is_numeric($currentMonthValue)){
                $currentMonthValue = date("m");
            }
            return ceil($currentMonthValue/3);
        }
        /**
         * This function is used to display the Tabular Report.
         * 
         * @access private
         * 
         */
        function tabular_report(){
            // set template variables
            $replace = array();
            $replace['browser_title'] = 'Tx Xchange: Tabular Report';
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['sidebar'] = $this->sidebar() ;

            $clinic_id = $this->value('clinic_id');
            if( is_numeric($clinic_id) ){
                $this->clinic_id = $clinic_id;
            }
            $user_id = $this->value('user_id');
            if( is_numeric($user_id) ){
                $this->user_id = $user_id;
            }
            $period = $this->value('period');
            $replace['report_type'] = $this->value('report_type');
            $replace['report_for'] = $this->value('report_for');
            $url_array = $this->tab_url($user_id,$clinic_id,$period);

            $replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$url_array);        
            $replace['clinic_name'] = 'SOMA VIRGINIA' ;
            
            $param = array();
            $param['report_for'] = $this->value('report_for');
            $param['report_type'] = $this->value('report_type');
            $param['period'] = $this->value('period');
            $replace['lineContent'] = $this->lineContent($param);

            $replace['tableHeader'] = $this->build_template($this->get_template("tableHeader")) ;
            $timeInterval = $this->getInterval($period);
            $data = $this->getGraphData($replace['report_type'],$period,$timeInterval,$replace['report_for']);
            foreach( $data as $key => $value ){
                if( $i++ % 2){ 
                    $row['styleName'] = 'background-color:#e0e0e0' ;
                }
                else{
                    $row['styleName'] = 'background-color:#f0f0f0' ;
                }
                $row['title'] = $key;
                $row['total'] = $value;
                $tabledata.= $this->build_template($this->get_template("tableRecord"),$row) ;
            }
            $replace['breadcrumb'] = $this->breadcrumb('tabular_report');
            $replace['tableRecords'] = $tabledata ;
            $replace['table'] = $this->build_template($this->get_template("getTable"),$replace) ;
            $replace['body'] = $this->build_template($this->get_template("tableReport"),$replace);
            $this->output = $this->build_template($this->get_template("main"),$replace);
        }
        /**
         * This function is used to select Type of Report for system wide Reports.
         * 
         */
         function global_report(){
            $replace['browser_title'] = 'Tx Xchange: Select Report Type';
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['sidebar'] = $this->sidebar() ;
            $replace['heading'] = 'Report Type';
            $replace['breadcrumb'] = $this->breadcrumb('global_report');
            $replace['report_type'] = $this->value('report_type') != ""?$this->value('report_type'):"one";
            $replace['body'] = $this->build_template($this->get_template("global_report"),$replace);
            $this->output = $this->build_template($this->get_template("main"),$replace);
        }
        /**
         * This function is used to give some option to user.
         * 
         */
         function new_member(){
            $replace['browser_title'] = 'Tx Xchange: New Member';
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['sidebar'] = $this->sidebar() ;
            $replace['heading'] = 'New Member List';
            //$replace['breadcrumb'] = $this->breadcrumb('global_report');
            $replace['report_type'] = $this->value('report_type') != ""?$this->value('report_type'):"one";
            $replace['body'] = $this->build_template($this->get_template("new_member"),$replace);
            $this->output = $this->build_template($this->get_template("main"),$replace);
        }
         /**
        * This function shows list of new member.
        */
        function member_list(){
            $privateKey = $this->config['private_key']; 
            $orderby = " order by AES_DECRYPT(UNHEX(name_first),'{$privateKey}') ";
            if($this->value('sort') != ""){
                if($this->value('order') == 'desc' ){
                    if( $this->value('sort') == 'name_first' ){
                        $orderby = "order by AES_DECRYPT(UNHEX({$this->value('sort')}),'{$privateKey}') desc";
                    }
                    else
                        $orderby = " order by {$this->value('sort')} desc ";
                }
                else{
                    if( $this->value('sort') == 'name_first' ){
                        $orderby = "order by AES_DECRYPT(UNHEX({$this->value('sort')}),'{$privateKey}') ";
                    }
                    else
                        $orderby = " order by {$this->value('sort')} ";
                }
            }
            
            $replace['search'] = $this->value('search');
            $searchString = $this->value('search');
            if( !empty($searchString) ){
                $privateKey = $this->config['private_key'];
                $searchWhere = " and (CAST(AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as CHAR) like '%{$searchString}%' or CAST(AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as CHAR ) like '%{$searchString}%' ) ";
            }
            $privateKey = $this->config['private_key'];
            $sqlUser = "SELECT *,
                        AES_DECRYPT(UNHEX(name_title),'{$privateKey}') as name_title, 
                        AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as name_first,
                        AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as name_last
                        FROM user WHERE usertype_id = 1 and (status = 1) {$searchWhere} {$orderby}";
            
            $link = $this->pagination($rows = 0,$sqlUser,'member_list',$searchString);                                          
            $replace['link'] = $link['nav'];
            $result = $link['result'];     
            if($this->num_rows($result)!= 0)
            {
                while($row = $this->fetch_array($result))
                {
                    $row['style'] = ($c++%2)?"line1":"line2";
                    $row['full_name'] = $row['name_first']."&nbsp;".$row['name_last'];
                    $row['creation_date'] = $this->formatDate($row['creation_date']);
                    $replace['listUsersRecord'].= $this->build_template($this->get_template("listUsersRecord"),$row) ;
                }
            }
            else{
                    $replace['userListingNoRecord'] = $this->build_template($this->get_template("userListingNoRecord"),$row);
            }
            
            //$replace['breadcrumb'] = $this->breadcrumb('viewTherapistOfClinic');
            $userListHead = array(
                'name_first' => 'Full Name',
                'username' => 'Username',
                'city' => 'City',
                'state' => 'State',
                'creation_date' => 'Created On'
            );
            
            $replace['listUsersHead'] = $this->build_template($this->get_template("listUsersHead"),$this->table_heading($userListHead,"name_first"));
            $replace['search'] = $this->value('search');
            $replace['browser_title'] = 'Tx Xchange: Member List';
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['sidebar'] = $this->sidebar() ;
            $replace['heading'] = 'Member List';
            $replace['body'] = $this->build_template($this->get_template("listUsers"),$replace);
            $this->output = $this->build_template($this->get_template("main"),$replace);
        }
         /**
        * This function returns url for tab.
        */
        function tab_url($user_id="",$clinic_id="",$period){
            $report_type = $this->value('report_type');
            $report_for = $this->value('report_for');
            $period = $period;
            $url_array = array();
            if( is_numeric($clinic_id) && $clinic_id > 0 ){
                $clinic_name = $this->get_field($clinic_id,'clinic','clinic_name');
                $url_array['graphlocation'] = "index.php?action=graphical_report&report_type={$report_type}&report_for={$report_for}&clinic_id={$clinic_id}&period={$period}";
                $url_array['tableLocation'] = "index.php?action=tabular_report&report_type={$report_type}&report_for={$report_for}&clinic_id={$clinic_id}&period={$period}";
                if(is_numeric($user_id) && $user_id > 0 ){
                    $url_array['graphlocation'] = "index.php?action=graphical_report&report_type={$report_type}&report_for={$report_for}&clinic_id={$clinic_id}&user_id={$user_id}&period={$period}";
                    $url_array['tableLocation'] = "index.php?action=tabular_report&report_type={$report_type}&report_for={$report_for}&clinic_id={$clinic_id}&user_id={$user_id}&period={$period}";    
                }
            }    
            else{
                if( $report_type != ""  ){
                    $url_array['graphlocation'] = "index.php?action=graphical_report&report_type={$report_type}&report_for={$report_for}&period={$period}";
                    $url_array['tableLocation'] = "index.php?action=tabular_report&report_type={$report_type}&report_for={$report_for}&period={$period}";
                }
            }
            return $url_array;
        }
        /**
        * This functino will list down Therapists of a clinic.
        */
        function viewTherapistOfClinic(){
            $orderby = " order by name_first ";
            if($this->value('sort') != ""){
                if($this->value('order') == 'desc' ){
                    $orderby = " order by {$this->value('sort')} desc ";
                }
                else{
                    $orderby = " order by {$this->value('sort')} ";
                }
            }
            
            $replace['search'] = $this->value('search');
            $clinic_id = $this->value('clinic_id');
            $searchString = $this->value('search');
            if( !empty($searchString) ){
                $privateKey = $this->config['private_key'];
                $searchWhere = " and (AES_DECRYPT(UNHEX(name_first),'{$privateKey}') like '%{$searchString}%' or AES_DECRYPT(UNHEX(name_last),'{$privateKey}') like '%{$searchString}%' ) ";
            }
            $privateKey = $this->config['private_key'];
            $sqlUser = "SELECT *, 
                        AES_DECRYPT(UNHEX(name_title),'{$privateKey}') as name_title, 
                        AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as name_first,
                        AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as name_last
                        FROM 
                        user WHERE usertype_id = 2 and (status = 1 or status = 2) 
            and user_id in (select user_id from clinic_user where clinic_id = '{$clinic_id}') {$searchWhere} {$orderby}";
            
            $link = $this->pagination($rows = 0,$sqlUser,'viewTherapistOfClinic',$searchString);                                          
            $replace['link'] = $link['nav'];
            $result = $link['result'];     
            if($this->num_rows($result)!= 0)
            {
                while($row = $this->fetch_array($result))
                {
                    $row['style'] = ($c++%2)?"line1":"line2";
                    $row['full_name'] = $row['name_first']."&nbsp;".$row['name_last'];
                    
                    $row['role'] = ""; 
                    if($row['admin_access'] == 1 && $row['therapist_access'] == 1)
                    {
                        $row['role'] = "Admin, Provider";                            
                    }
                    else if($row['admin_access'] == 1)
                    {
                        $row['role'] = "Admin";                            
                    }
                    else if($row['therapist_access'] == 1)
                    {
                        $row['role'] = "Provider";
                    }
                    
                    $row['last_login'] = !is_null($row['last_login'])?$this->formatDate($row['last_login']):"Never";
                    $replace['listUsersRecord'].= $this->build_template($this->get_template("listUsersRecord"),$row) ;
                }
            }
            else{
                    $replace['userListingNoRecord'] = $this->build_template($this->get_template("userListingNoRecord"),$row);
            }
            
            $replace['breadcrumb'] = $this->breadcrumb('viewTherapistOfClinic');
            $userListHead = array(
                'name_first' => 'Full Name',
                'username' => 'Username',
                'status' => 'Status',
                'last_login' => 'Last Login'
            );
            $query_string = array(
                'clinic_id' => $this->value('clinic_id')
            );
            
            $replace['listUsersHead'] = $this->build_template($this->get_template("listUsersHead"),$this->table_heading($userListHead,"name_first",$query_string));
            $replace['clinic_id'] = $clinic_id;
            $replace['search'] = $this->value('search');
            $replace['browser_title'] = 'Tx Xchange: User List';
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['sidebar'] = $this->sidebar() ;
            $replace['heading'] = 'User List';
            $replace['body'] = $this->build_template($this->get_template("listUsers"),$replace);
            $this->output = $this->build_template($this->get_template("main"),$replace);
        }
        /**
        * This function will update users table.
        */
        function updateUserTable(){
                $privateKey = $this->config['private_key'];
                $query = " select *,
                           AES_DECRYPT(UNHEX(name_title),'{$privateKey}') as name_title, 
                           AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as name_first,
                           AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as name_last 
                           from user where usertype_id = 1";
                $result = @mysql_query($query);
                while($row = @mysql_fetch_array($result) ){
                    echo $row['username']."&nbsp;";
                    $query2 = " select min(therapist_patient_id) from therapist_patient 
                            where patient_id = '{$row['user_id']}' group by patient_id";
                    $result2 = @mysql_query($query2);
                    if( $row2 = @mysql_fetch_array($result2) ){
                        $user_id = $this->get_field($row2[0],'therapist_patient','therapist_id');
                        echo $user_id."<br>";
                        $arr = array('created_by' => $user_id);
                        $where = " user_id = '{$row['user_id']}' and usertype_id = 1 ";
                        $this->update("user",$arr,$where);
                    }
                    else{
                        $cnt++;
                        echo "<br>";
                        
                    }
                }
                
                echo "<br>.Not assigned:". $cnt++;
                
        }
        /**
		 * Function to populate side panel of the page
		 *
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
        * This function shows list of invitee.
        */
        function invite_list(){
           
            $orderby = " order by first_name ";
            if($this->value('sort') != ""){
                if($this->value('order') == 'desc' ){
                    if( $this->value('sort') == 'first_name' ){
                        $orderby = "order by {$this->value('sort')} desc";
                    }
                    else
                        $orderby = " order by {$this->value('sort')} desc ";
                }
                else{
                    if( $this->value('sort') == 'first_name' ){
                        $orderby = "order by {$this->value('sort')} ";
                    }
                    else
                        $orderby = " order by {$this->value('sort')} ";
                }
            }
            
            $replace['search'] = $this->value('search');
            $searchString = $this->value('search');
            if( !empty($searchString) ){
                $privateKey = $this->config['private_key'];
                $searchWhere = " and (first_name like '%{$searchString}%' or last_name like '%{$searchString}%')";
            }
            $sqlUser = "SELECT *,sendinvite.status as statussend FROM sendinvite,practitioner WHERE sendinvite.provider=practitioner.practitioner_id {$searchWhere} {$orderby}";
            $link = $this->pagination($rows = 0,$sqlUser,'invite_list',$searchString,'','','20');                                          
            $replace['link'] = $link['nav'];
            $result = $link['result'];     
            if($this->num_rows($result)!= 0)
            {
                while($row = $this->fetch_array($result))
                {
                    //print_r($row);
                    $row['style'] = ($c++%2)?"line1":"line2";
                    $row['datefosend'] = $this->formatDate($row['datefosend']);
                    if($row['statussend']==1)
                    $row['status']='NA';
                    else
                    $row['status']='In Trial';
                    if($row['user_type']==1)
                    $row['sent_by']='Patient';
                    else
                    $row['sent_by']='Provider';
                    $replace['invitelistUsersRecord'].= $this->build_template($this->get_template('invitelistUsersRecord'),$row);
                
                }
            }
            else{
                    $replace['invitenoRecord'] = $this->build_template($this->get_template("invitenoRecord"),$row);
            }
            
            //$replace['breadcrumb'] = $this->breadcrumb('viewTherapistOfClinic');
            $userListHead = array(
                'user_type'   =>'Sent by',
                'first_name' => 'First Name',
                'last_name' => 'Last Name',
                'email' => 'Invitee Email',
                'name' => 'Type',
                'statussend' => 'Status',
                'datefosend' => 'Invite sent'
            );
            
            $replace['invitelistUsersHead'] = $this->build_template($this->get_template("invitelistUsersHead"),$this->table_heading($userListHead,"name_first"));
            $replace['search'] = $this->value('search');
            $replace['browser_title'] = 'Tx Xchange: Invitee List';
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['sidebar'] = $this->sidebar() ;
            $replace['heading'] = 'Invitee List';
            $replace['body'] = $this->build_template($this->get_template("inviteUsers"),$replace);
           //print_r($replace);
            $this->output = $this->build_template($this->get_template("main"),$replace);
        }
        
        
        function referralReports(){
            //print_r($_POST);
            $month= date('m');
            $year=date('Y');
               if($this->value('period')!=''){
                    $time=explode('~~',$this->value('period'));
                    $month1= $time[0];
                    $year1=$time[1];  
                }else{
                    $month1= date('m');
                    $year1=date('Y');    
                }
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
            $replace['downloadreport']='index.php?action=txReferralReportExcel&period='.$this->value('period');
            $replace['period']=$period;
            $reportperiod['period']=$this->value('period');            
            if($this->value('sub')=='Export Report'){
                $filename="report/".$month1.'_'.$year1.'_Referrals_report.xls';
                $filename=str_replace(" ",'_',$filename);
                $excel=new ExcelWriter($filename);
                if($excel==false)
                    echo $excel->error;
                $sqlclinic="Select clinic_id,clinic_name from clinic";
                $clinicResult=$this->execute_query($sqlclinic);
                $myArr=array("<b>Name of Clinic</b>","<b>Monthly Referral Limit</b>","<b>Name of Patient</b>","<b>Name of Person Referred</b>","<b>Email Address of Person Referred</b>","<b>Date Referral Sent</b>");
                $excel->writeLine($myArr);
            while($rowClinic=$this->fetch_array($clinicResult)){
                
                $sqlUser = "select user.name_first as p_name_first,user.name_last as p_name_last,
                                   patients_refferals.name_first as r_name_first,patients_refferals.name_last as r_name_last,
                                   sent_date,recipient_email
                              from patients_refferals,user 
                              where patients_refferals.user_id=user.user_id 
                                    and patients_refferals.status = 1 
                                    and clinic_id = '{$rowClinic['clinic_id']}'
                                    and MONTH(sent_date)={$month1} 
                                    and YEAR(sent_date)={$year1} order by sent_date desc";
                                   // die;
                $result = $this->execute_query($sqlUser);   
                if($this->num_rows($result)!= 0)
                {
                    $excelrow['Name of Clinic']=$rowClinic['clinic_name'];
                    $limit=$this->getTableValue('referral_limit','clinic_id',$rowClinic['clinic_id'],'clinic_refferal_limit');
                    $excelrow['Monthly Referral Limit']=$limit['clinic_refferal_limit'];
                    $excelrow['p_name_first'] = '';
                    $excelrow['r_name_first'] = '';
                    $excelrow['recipient_email']='';
                    $excelrow['sent_date'] = '';    
		            $excel->writeLine($excelrow);
                    while($row = $this->fetch_array($result))
                        {
                            $excelrow['Name of Clinic']='';
                            $excelrow['Monthly Referral Limit']="";
                            $excelrow['p_name_first'] = $this->decrypt_data($row['p_name_first']).' '.$this->decrypt_data($row['p_name_last']);
                            $excelrow['r_name_first'] = $row['r_name_first'].' '.$row['r_name_last'];
                            $excelrow['recipient_email']=$row['recipient_email'];
                            $excelrow['sent_date'] = $this->formatDate($row['sent_date']);
                            $excel->writeLine($excelrow);                    
                        }
                   }else{
                         continue;
                
                   }
            $excel->writeRow();
            $excel->writeCol("<b>Total Number of Referrals Sent (MTD)</b>");
	        $excel->writeCol("<b>".$this->num_rows($result)."</b>");
            $excel->writeRow();    
            }
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
           
            
            $replace['invitelistUsersHead'] = $this->build_template($this->get_template("invitelistUsersHead"),$this->table_heading($userListHead,"name_first"));
            $replace['search'] = $this->value('search');
            $replace['browser_title'] = 'Tx Xchange: Referral Reports';
            $replace['header'] = $this->build_template($this->get_template("header"));
            $replace['sidebar'] = $this->sidebar() ;
            $replace['heading'] = 'Referral Reports';
            $replace['body'] = $this->build_template($this->get_template("referralReport"),$replace);
           //print_r($replace);
            $this->output = $this->build_template($this->get_template("main"),$replace);
        }

  	}
  	// creating object of this class.
	$obj = new systemReport();

	?>
  	