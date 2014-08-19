<?php

// including files    
    require_once("module/application.class.php");
    require_once("include/postgraph.class.php");    
    require_once("include/dashboardgraph.class.php");    
    require_once("include/outcomegraph.class.php");
    require_once("include/graph.class.php");
    // class declaration
      class bargraph extends application{
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
        function __construct($action=""){
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
                $str = "printImage"; //default if no action is specified
            }
            $this->action = $str;
            if($this->get_checkLogin($this->action) == "true" ){
                if( isset($_SESSION['username']) && isset($_SESSION['password']) ){
                    if(!$this->chk_login($_SESSION['username'],$_SESSION['password'])){
                        header("location:index.php");
                    }
                    else{
                        if($this->userAccess($str)){
                            $str = $str."()";
                            eval("\$this->$str;"); 
                        }
                        else{
                            $this->output = $this->config['error_message'];
                        }
                    }
                }
            }
            else{
                    $str = $str."()";
                    eval("\$this->$str;"); 
            }
            
            $this->display();
        }
        /**
        * Function to print graph.
        * 
        */
        function printImage(){
               
              // Get Patient Id.
              if($this->userInfo('usertype_id') == 1 ){
                $patient_id = $this->userInfo('user_id');
                $data = $this->getData($patient_id);
              }
              else{
                  $patient_id = $this->value('patient_id');
                  $data = $this->getData($patient_id);
              }
              
              
              // creates graph object
              $graph = new PostGraph(400,300);
              
              // set titles
              $graph->setGraphTitles('', '# of Assessments Taken', '% Level of Disability');

              // set format of number on Y axe
              $graph->setYNumberFormat('integer');

              // set number of ticks on Y axe
              $graph->setYTicks(10);

              // set data
              $graph->setData($data);

              $graph->setBackgroundColor(array(255, 255, 255));
              $graph->setBarsColor(array(72, 107, 143));

              $graph->setTextColor(array(0, 0, 0));

              // set orientation of text on X axe
              $graph->setXTextOrientation('horizontal');

              // prepare image
              $graph->drawImage();

              // print image to the screem
              $graph->printImage();

        }
        /**
        * Get login data for Graph.
        */
        function getLoginData($patient_id,$date=false){
            if( is_numeric($patient_id)){
             $current_date = date("Y-m-d");   
             $dateRange = array();
             $cnt =1;
             $breakcnt = 1;
             while(count($dateRange) <= 7 || $breakcnt == 200){
                $current_weekday = date('D',strtotime("{$current_date}"));
                if( $current_weekday == "Sun" ){
                    $startweeknumber = date('W',strtotime("{$current_date}"));
                    $enddate = $current_date;
                }
                if( $current_weekday == "Mon" ){
                    $endweeknumber = date('W',strtotime("{$current_date}"));
                    $startdate = $current_date;
                }
                if( $startweeknumber !="" && $endweeknumber != "" && $startweeknumber ==  $endweeknumber ){
                    $dateRange[$cnt]['enddate'] = $enddate;
                    $dateRange[$cnt]['startdate'] = $startdate;
                    $cnt++;
                }
                $current_date =  date('Y-m-d',strtotime("{$current_date}- 1days"));
                $breakcnt++;
             }
             $dateRange = array_reverse($dateRange, false);
             $cnt = 1;
             $graphData = array();
             foreach( $dateRange as $value ){
                 // Returns data only
                 if( $date === true ){
                   $graphData[$cnt++] = date("M-d-Y",strtotime($value['startdate']));  
                   continue;
                 }
                 // Returns specific value of bar
                 $query = " select count(*) as cnt from login_history where user_id = '{$patient_id}' and user_type = 1 and date( login_date_time ) >= '{$value['startdate']}' and date( login_date_time ) <= '{$value['enddate']}'"; 
                 $result = @mysql_query($query);
                 if($row = @mysql_fetch_array($result) ){
                     $graphData[$cnt] = $row['cnt'];
                 }
                 else{
                    $graphData[$cnt] = '';
                 }
                
                $cnt++;
            }
            return $graphData;
            }
        }
        
        /**
        * Function to login graph.
        * 
        */
        function loginGraph(){
             
              // Get Patient Id.
              $patient_id = $this->value('patient_id');
              $data = $this->getLoginData($patient_id);
              $graphdate = $this->getLoginData($patient_id,true); 
             
              
              /*$data = array( 
                '1' =>  5,
                '2' =>  15,
                '3' =>  9,
                '4' =>  20,
                '5' => 8,
                '6' => 3 ,
                '7' =>  7,
                '8' =>  2,
                );*/
              
              
              // creates graph object
              $graph = new DashboardGraph(190,260);
              
              // set titles
              $graph->setGraphTitles('Logins', 'Week of', 'Frequency');

              // set format of number on Y axe
              $graph->setYNumberFormat('integer');

              // set number of ticks on Y axe
              $graph->setYTicks(10);

              // set data
              $graph->setData($data);

              $graph->setBackgroundColor(array(255, 255, 255));
              $graph->setBarsColor(array(117, 154, 182));

              $graph->setTextColor(array(0, 0, 0));

              // set orientation of text on X axe
              $graph->setXTextOrientation('horizontal');

              // prepare image
              $graph->drawImage();
              
              // Map data
              if( $this->value('map') == '1' ){
                    
                    $mapstr = '<map name="loginMap">';
                    foreach( $graph->barValue as $key => $value ){
                        if( empty($value['value']) || $value['value'] == '' ){
                            $value['value'] = '0';
                        }
                        if( !empty($graphdate[$key]) ){
                            if( $value['value'] == '0' ){
                                $value['x1'] = $value['x1'] + 3;
                                $value['y1'] = $value['y1'] + 3;
                            }  
                            $mapstr .= "<area shape='rect' coords='{$value['x1']},{$value['y1']},{$value['x2']},{$value['y2']}' alt='{$value['value']} : {$graphdate[$key]}' title='{$value['value']} : {$graphdate[$key]}' href='#' />";
                        }
                    
                    }
                    $mapstr .= '</map>';
                    echo $mapstr;
                    return $mapstr;    
              }
              
              // print image to the screem
              $graph->printImage();
              
              

        }
        /**
        * Get login data for Graph.
        */
        function getPlanViewData($patient_id,$date=false){
            if( is_numeric($patient_id)){
             $current_date = date("Y-m-d");   
             $dateRange = array();
             $cnt =1;
             $breakcnt = 1;
             while(count($dateRange) <= 7 || $breakcnt == 200){
                $current_weekday = date('D',strtotime("{$current_date}"));
                if( $current_weekday == "Sun" ){
                    $startweeknumber = date('W',strtotime("{$current_date}"));
                    $enddate = $current_date;
                }
                if( $current_weekday == "Mon" ){
                    $endweeknumber = date('W',strtotime("{$current_date}"));
                    $startdate = $current_date;
                }
                if( $startweeknumber !="" && $endweeknumber != "" && $startweeknumber ==  $endweeknumber ){
                    $dateRange[$cnt]['enddate'] = $enddate;
                    $dateRange[$cnt]['startdate'] = $startdate;
                    $cnt++;
                }
                $current_date =  date('Y-m-d',strtotime("{$current_date}- 1days"));
                $breakcnt++;
             }
             $dateRange = array_reverse($dateRange, false);
             $cnt = 1;
             foreach( $dateRange as $value ){
                 // Returns data only
                 if( $date === true ){
                   $graphData[$cnt++] = date("M-d-Y",strtotime($value['startdate']));  
                   continue;
                 }
                 // Returns specific value of bar
                 $query = " select count(*) as cnt from plan_view where user_id = '{$patient_id}' and date( viewed_on ) >= '{$value['startdate']}' and date( viewed_on ) <= '{$value['enddate']}'"; 
                 $result = @mysql_query($query);
                 if($row = @mysql_fetch_array($result) ){
                     $graphData[$cnt] = $row['cnt'];
                 }
                 else{
                    $graphData[$cnt] = '';
                 }
                $cnt++;
            }
               
            /*echo "<pre>";
            print_r($graphData);
            echo "</pre>";
            exit();           */
            return $graphData;
            }
        }
        /**
        * Function to plan view graph.
        * 
        */
        function planViewGraph(){
               
              // Get Patient Id.
              $patient_id = $this->value('patient_id');
              $data = $this->getPlanViewData($patient_id);
              $graphdate = $this->getPlanViewData($patient_id,true); 
              
              /*$data = array( 
                '1' =>  5,
                '2' =>  15,
                '3' =>  9,
                '4' =>  20,
                '5' => 8,
                '6' => 3 ,
                '7' =>  7,
                '8' =>  2,
                );*/
              
              // creates graph object
              $graph = new DashboardGraph(190,260);
              
              // set titles
               $graph->setGraphTitles('Video & Article Views', 'Week of', 'Frequency');

              // set format of number on Y axe
              $graph->setYNumberFormat('integer');

              // set number of ticks on Y axe
              $graph->setYTicks(10);

              // set data
              $graph->setData($data);

              $graph->setBackgroundColor(array(255, 255, 255));
              $graph->setBarsColor(array(45, 101, 142));

              $graph->setTextColor(array(0, 0, 0));

              // set orientation of text on X axe
              $graph->setXTextOrientation('horizontal');

              // prepare image
              $graph->drawImage();

              // Map data
              if( $this->value('map') == '1' ){
                    
                    $mapstr = '<map name="planViewMap">';
                    foreach( $graph->barValue as $key => $value ){
                        if( empty($value['value']) || $value['value'] == '' ){
                            $value['value'] = '0';
                        }
                        if( !empty($graphdate[$key]) ){
                            if( $value['value'] == '0' ){
                                $value['x1'] = $value['x1'] + 3;
                                $value['y1'] = $value['y1'] + 3;
                            }  
                            $mapstr .= "<area shape='rect' coords='{$value['x1']},{$value['y1']},{$value['x2']},{$value['y2']}' alt='{$value['value']} : {$graphdate[$key]}' title='{$value['value']} : {$graphdate[$key]}' href='#' />";
                        }
                    
                    }
                    $mapstr .= '</map>';
                    echo $mapstr;
                    return $mapstr;    
              }
              
              // print image to the screem
              $graph->printImage();
        }
        /**
        * Get data for Graph.
        */
        function getDataNew($patient_id, $type = "0", $date=false ){
            if( is_numeric($patient_id)){
             $query = " select * from patient_om where patient_id = '{$patient_id}' and status = 2 and om_form = {$type} order by submited_on asc ";
             $result = @mysql_query($query);
             for( $cnt=1; $cnt<=10; $cnt++ ){
                 if($row = @mysql_fetch_array($result) ){
                     $graphData[$cnt] = $row['score'];
                     $graphDate[$cnt] = date("M-d-Y",strtotime($row['submited_on']));
                 }
                 else{
                    $graphData[$cnt] = '';
                    //$graphDate[$cnt] = date("M-d-Y",strtotime($row['submited_on']));
                 }
             }
            }
            if( $date == true ){
                return $graphDate;
            }
            return $graphData;
        }
        /**
        * Function to outcome measure graph.
        * 
        */
        function outcomeGraph(){
               
              // Get Patient Id.
              $patient_id = $this->value('patient_id');
              $type =   $this->value('type');
              $data = $this->getDataNew($patient_id, $type);
              $graphdate = $this->getDataNew($patient_id, $type, true);
              
              /*$data = array( 
                '1' =>  5,
                '2' =>  15,
                '3' =>  9,
                '4' =>  20,
                '5' => 8,
                '6' => 3 ,
                '7' =>  7,
                '8' =>  2,
                '9' =>  70,
                '10' => 40
                
                ); */
              
              // creates graph object
              $graph = new OutcomeGraph(240,260);  
              
              // set titles
              $graph->setGraphTitles('Outcome Measures', '# of Assessments Taken', 'Functional Score');

              // set format of number on Y axe
              $graph->setYNumberFormat('integer');

              // set number of ticks on Y axe
              $graph->setYTicks(10);
              // set maxdata
              // set data
              $graph->setData($data);

              $graph->setBackgroundColor(array(255, 255, 255));
              $graph->setBarsColor(array(41, 138, 0));

              $graph->setTextColor(array(0, 0, 0));

              // set orientation of text on X axe
              $graph->setXTextOrientation('horizontal');

              // prepare image
              $graph->drawImage();

              // Map data
              if( $this->value('map') == '1' ){
                    //$mapstr = '<map name="outcomeMap">';
                    foreach( $graph->barValue as $key => $value ){
                        if( empty($value['value']) || $value['value'] == '' ){
                            $value['value'] = '0';
                        }
                        if( !empty($graphdate[$key]) ){
                            if( $value['value'] == '0' ){
                                $value['x1'] = $value['x1'] + 3;
                                $value['y1'] = $value['y1'] + 3;
                            }  
                            $mapstr .= "<area shape='rect' coords='{$value['x1']},{$value['y1']},{$value['x2']},{$value['y2']}' alt='{$value['value']}% : {$graphdate[$key]}' title='{$value['value']}% : {$graphdate[$key]}' href='#' />";
                        }
                    
                    }
                    //$mapstr .= '</map>';
                    echo $mapstr;
                    return $mapstr;    
              }
              
              // print image to the screem
              $graph->printImage();

        }

        /**
        * Function to outcome Line graph for FABQ1 & FABQ2.
        * 
        */
        function outcomeLineGraph(){
               
              // Get Patient Id.
				$patient_id = $this->value('patient_id');
				$type =   $this->value('type');
				$data = $this->getDataNew($patient_id, $type);
				$graphdate = $this->getDataNew($patient_id, $type, true);
				// Get OSW Score
				$scoreOSW = $this->getLineGraphScore(1,$patient_id);
				// Get pain scale Score
				$scorePS = $this->getLineGraphScore(7,$patient_id);
				// Get FABQ1 & FABQ2 Score
				$scoreFABQ = $this->getLineGraphScore(4,$patient_id);
				foreach($scoreFABQ as $value){
						if( is_null($value['score']) ){
							$scoreFABQA[] = 0;
						}
						else{
							if( is_numeric($value['score']) ){
								$scoreFABQA[] = $value['score'];
							}
							else{
								$scoreFABQA[] = -1;
							}
						}
						
						if( is_null($value['score2']) ){
							$scoreFABQW[] = 0;
						}
						else{
							if( is_numeric($value['score2']) ){
								$scoreFABQW[] = $value['score2'];
							}
							else{
								$scoreFABQW[] = -1;
							}
						}
					$scoreFABQDate[] = $value['submited_on'];
				}				
				// creates graph object
				$chart = new graph(290,235);  
				$chart->parameter['path_to_fonts'] = 'font/'; 					// path to where fonts are stored
				$chart->parameter['title'] = '';
				$chart->parameter['x_label'] = '# of Assessments Taken';		// X-Axis Lavel
				$chart->parameter['y_label_left'] = 'Functional Score';			// Y-Axis Lavel
				$chart->parameter['inner_border_type'] = 'axis';				// Border X,Y-Axis
				$chart->parameter['y_ticks_colour'] = '';						// Y-Axis ticks
				$chart->parameter['x_ticks_colour'] = '';						// X-Axis ticks
				$chart->parameter['label_colour'] = 'green';					// X and Y-Axis color
				$chart->parameter['y_grid'] = '';								// Y- Axis Grid line
				$chart->parameter['axis_colour'] = 'black';						// colour of axis text
				$chart->parameter['y_min_left'] = 0;  							// start at 0
				$chart->parameter['y_max_left'] = 100; 							// and go to 50
				$chart->parameter['y_decimal_left'] = 0; 						// 0 decimal places for y axis.
				//$chart->parameter['y_axis_gridlines'] = 11;
				$chart->parameter['point_size'] = 4;
				$chart->parameter['y_resolution_left']= 1;
				$chart->parameter['y_decimal_left']= 0;
				$chart->parameter['axis_size'] = '9.6';
				$chart->parameter['axis_font'] = 'consolab.ttf';
				
				$chart->x_data = array(1,2,3,4,5,6,7,8,9,10);	// X-Axis parameters
				
				$arrayLines=array('fabqa','fabqw');
				
				$arr = array($scoreFABQA,$scoreFABQW);
				
				$arrDate = array($scoreFABQDate,$scoreFABQDate);
				
				$counter=0;
				foreach ($arrayLines as $value) {
					
					$chart->y_data[$value] = array($arr[$counter][0], $arr[$counter][1], $arr[$counter][2], $arr[$counter][3], $arr[$counter][4], $arr[$counter][5], $arr[$counter][6], $arr[$counter][7], $arr[$counter][8], $arr[$counter][9], $arr[$counter][10]);
					
					$chart->y_scoreDate[$value] = array($arrDate[$counter][0], $arrDate[$counter][1], $arrDate[$counter][2], $arrDate[$counter][3], $arrDate[$counter][4], $arrDate[$counter][5], $arrDate[$counter][6], $arrDate[$counter][7], $arrDate[$counter][8], $arrDate[$counter][9], $arrDate[$counter][10]);
					$counter++;
				}
				
				$chart->y_format['fabqa'] =
				  array('colour' => 'green', 'line' => 'line', 'point' => 'square');
				
				$chart->y_format['fabqw'] =
				  array('colour' => 'black', 'line' => 'line', 'point' => 'square');
				  
				// order in which to draw data sets.
				$chart->y_order =$arrayLines;
				
				// Map Type
				if( $this->value('map') == '4' ){
				// Show map
				$chart->drawAreaTag();
				$chart->getxy();
					foreach( $chart->valuesAll as $key => $value ){ 
				   if( $value[4] == -1){
					continue;
				   }
					$mapstr .= "<area shape='rect' coords='{$value[0]},{$value[1]},{$value[2]},{$value[3]}' alt='Score $value[4] : {$value[5]}' title='Score $value[4] : {$value[5]}' href='#' />";
				}
					echo $mapstr;
					return $mapstr;    
				}
				// print image to the screem
				$chart->draw();			
        }
        
        /**
        * Patient Questionaries Page.
        */
        function acn_patient(){
            
            //$patient_id = $this->value('patient_id');
            $patient_id = $this->userInfo('user_id');
            if($this->value('submit') != "" && is_numeric($patient_id) ){
                $acn_patient_arr = array(
                    'form_data'     =>  serialize($_REQUEST),
                    'patient_id'    =>  $patient_id,
                     'status' => 2
                );
                $where = " patient_id = '{$patient_id}' and status = 1 ";
                $this->update('acn_patient',$acn_patient_arr,$where);
                //$company = $this->get_field($patient_id,'user','company');
                if( 1 ){
                    $arr_assign_quest = array(
                        'status' => 2
                    );
                    $where = " patient_id = '{$patient_id}' and status = 1 ";
                    $this->update('assign_questionnaire', $arr_assign_quest, $where);
                    $this->output = $this->build_template($this->get_template("closePopup"));            
                    return;
                }
                else
                    header("location:index.php?action=show_outcome_form");
                exit();
            }
           $replace['javascript'] = "";
           $replace['patient_id'] = $patient_id;
           $this->output = $this->build_template($this->get_template("acn_patient"),$replace);            
        }
        
        /**
        * View Patient Questionaries Page.
        */
        function view_acn_patient(){
           $patient_id = $this->value('patient_id'); 
           $query = " Select * from acn_patient where patient_id = '{$patient_id}' and status =2 order by acn_patient_id desc limit 0,1";
           $result = @mysql_query($query); 
           $row = @mysql_fetch_array($result);
           $form_arr = unserialize($row['form_data']);
           $replace['javascript'] = "";   
           
           foreach( $form_arr as $key => $value ){            
               if(is_array($value)){
               if(count($value) == 1){
                    $str = addslashes(implode("','",$value));
               }    
               else{
                   $str = implode("','",$value);
               }
               
               
               if( $key == 'q1' ){
                   $str = str_replace("\r\n",'',$str);
                   $replace['javascript'] .= ' $("#q1id").text("'.$str.'"); ';
               }
               else
               {
                   $replace['javascript'] .= <<<EOC
$("input[@id='{$key}id']").val(['{$str}']);
EOC;
               }
               }
               $cnt++;
           }
           
          $this->output = $this->build_template($this->get_template("acn_patient"),$replace);            

        }
        /**
        * View lbhra Page.
        */
        function view_lbhra(){
           $patient_id = $this->value('patient_id'); 
           $query = " Select * from lbhra where patient_id = '{$patient_id}' and status =2 order by lbhra_patient_id desc limit 0,1";
           $result = @mysql_query($query); 
           $row = @mysql_fetch_array($result);
           $form_arr = unserialize($row['form_data']);
           $replace['javascript'] = "";   
           
           foreach( $form_arr as $key => $value )
           {            
               if(is_array($value)){
               if(count($value) == 1){
                    $str = addslashes(implode("','",$value));
               }    
               else{
                   $str = implode("','",$value);
               }
               
               
                   $replace['javascript'] .= <<<EOC
$("input[@id='{$key}id']").val(['{$str}']);
EOC;
               
               }
               $cnt++;
           }
           
          $this->output = $this->build_template($this->get_template("view_lbhra"),$replace);            

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

      }
      // creating object of this class.
      $obj = new bargraph();

   
    ?>
      