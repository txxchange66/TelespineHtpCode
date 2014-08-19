<?php

// including files    
    require_once("module/application.class.php");
    require_once("include/graph.class.php");
    // class declaration
      class linegraph extends application{
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
                $str = "printLineGraph"; //default if no action is specified
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
         * This function sends the output to browser.
         * 
         * @access public
         */        
        function display(){
            view::$output =  $this->output;
        }
        /**
        * New Line Function to print graph.
        * 
        */
		function printLineGraph(){
				 //Get Patient Id.
				$patient_id = $this->userInfo('user_id');

				// Get OSW Score
				$scoreOSW = $this->getLineGraphScore(1,$patient_id);
				foreach($scoreOSW as $key => $value){
						if( is_null($value['score']) ){
							$scoreOSW1[] = 0;
						}
						else{
							if( is_numeric($value['score']) ){
								$scoreOSW1[] = $value['score'];
							}
							else{
								$scoreOSW1[] = -1;
							}
						}
						$scoreOSWDate[] = $value['submited_on'];
						$TypeOSW[] = 1;
				}
				// Get painscale Score
				$scorePS = $this->getLineGraphScore(7,$patient_id);
				foreach($scorePS as $key => $value){
						if( is_null($value['score']) ){
							$scorePS1[] = 0;
						}
						else{
							if( is_numeric($value['score']) ){
								$scorePS1[] = $value['score'];
							}
							else{
								$scorePS1[] = -1;
							}
						}
						$scorePSDate[] = $value['submited_on'];
						$TypePS[] = 7;
				}
				// Get NDI Score
				$scoreNDI = $this->getLineGraphScore(2,$patient_id);
				foreach($scoreNDI as $value){
						if( is_null($value['score']) ){
							$scoreNDI1[] = 0;
						}
						else{
							if( is_numeric($value['score']) ){
								$scoreNDI1[] = $value['score'];
							}
							else{
								$scoreNDI1[] = -1;
							}
						}
						$scoreNDIDate[] = $value['submited_on'];
						$TypeNDI[] = 2;
				}
				
				// Get FABQ1 & FABQ2 Score
				$scoreFABQ = $this->getLineGraphScore(4,$patient_id);
				foreach($scoreFABQ as $value){
						//$scoreFABQA[] = is_null($value['score']) || !is_numeric($value['score'])?-1:$value['score'];
						//$scoreFABQW[] = is_null($value['score2']) || !is_numeric($value['score2'])?-1:$value['score2'];
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
						$TypeFABQ[] = 4;
				}

				// Get LEFS Score
				$scoreLEFS = $this->getLineGraphScore(5,$patient_id);
				foreach($scoreLEFS as $key => $value){
						if( is_null($value['score']) ){
							$scoreLEFS1[] = 0;
						}
						else{
							if( is_numeric($value['score']) ){
								$scoreLEFS1[] = $value['score'];
							}
							else{
								$scoreLEFS1[] = -1;
							}
						}
						$scoreLEFSDate[] = $value['submited_on'];
						$TypeLEFS[] = 1;
				}

				// Get DASH Score
				$scoreDASH = $this->getLineGraphScore(6,$patient_id);
				foreach($scoreDASH as $key => $value){
						if( is_null($value['score']) ){
							$scoreDASH1[] = 0;
						}
						else{
							if( is_numeric($value['score']) ){
								$scoreDASH1[] = $value['score'];
							}
							else{
								$scoreDASH1[] = -1;
							}
						}
						$scoreDASHDate[] = $value['submited_on'];
						$TypeDASH[] = 6;
				}

			// creates line graph object
			$chart = new graph(400,300);
			
			$chart->parameter['path_to_fonts'] = 'font/'; 					// path to where fonts are stored
			$chart->parameter['title'] = ' ';
			$chart->parameter['x_label'] = '# of Assessments Taken';		// X-Axis Lavel
			$chart->parameter['y_label_left'] = 'Functional Score';			// Y-Axis Lavel
			$chart->parameter['inner_border_type'] = 'axis';				// Border X,Y-Axis
			$chart->parameter['y_ticks_colour'] = '';						// Y-Axis ticks
			$chart->parameter['x_ticks_colour'] = '';						// X-Axis ticks
			$chart->parameter['label_colour'] = 'gray33';					// X and Y-Axis color
			$chart->parameter['y_grid'] = 'line';							// Y- Axis Grid line
			$chart->parameter['axis_colour'] = 'gray33';					// colour of axis text
			$chart->parameter['y_min_left'] = 0;  							// start at 0
			$chart->parameter['y_max_left'] = 100; 							// and go to 50
			$chart->parameter['y_decimal_left'] = 0; 						// 0 decimal places for y axis.
			$chart->parameter['point_size'] = 6;
			$chart->parameter['y_resolution_left']= 1;
			$chart->parameter['y_decimal_left']= 0;
			
								
			$chart->x_data = array(1,2,3,4,5,6,7,8,9,10);					// X-Axis parameters
			
			$arrayLines = array('osw','ndi','fabqa','fabqw','lefs','dash','painscale');					// Names of lines
			// Score array
			$arr = array($scoreOSW1,$scoreNDI1,$scoreFABQA,$scoreFABQW,$scoreLEFS1,$scoreDASH1,$scorePS1);

			// Date array			
			$arrDate = array($scoreOSWDate,$scoreNDIDate,$scoreFABQDate,$scoreFABQDate,$scoreLEFSDate,$scoreDASHDate,$scorePSDate);
			// Form type array
			$arrayformType = array($TypeOSW,$TypeNDI,$TypeFABQ,$TypeFABQ,$TypeLEFS,$TypeDASH,$TypePS);
			
			$counter=0;
			foreach ($arrayLines as $value) {
				$chart->y_data[$value] = array($arr[$counter][0], $arr[$counter][1], $arr[$counter][2], $arr[$counter][3], $arr[$counter][4], $arr[$counter][5], $arr[$counter][6], $arr[$counter][7], $arr[$counter][8], $arr[$counter][9], $arr[$counter][10]);
				
				$chart->y_scoreDate[$value] = array($arrDate[$counter][0], $arrDate[$counter][1], $arrDate[$counter][2], $arrDate[$counter][3], $arrDate[$counter][4], $arrDate[$counter][5], $arrDate[$counter][6], $arrDate[$counter][7], $arrDate[$counter][8], $arrDate[$counter][9], $arrDate[$counter][10]);
				
				$chart->y_formType[$value] = array($arrayformType[$counter][0], $arrayformType[$counter][1], $arrayformType[$counter][2], $arrayformType[$counter][3], $arrayformType[$counter][4], $arrayformType[$counter][5], $arrayformType[$counter][6], $arrayformType[$counter][7], $arrayformType[$counter][8], $arrayformType[$counter][9], $arrayformType[$counter][10]);
				$counter++;
			}
			
			$chart->y_format['osw'] =
			  array('colour' => 'blue', 'line' => 'line', 'point' => 'square');
			
			$chart->y_format['ndi'] =
			  array('colour' => 'red', 'line' => 'line', 'point' => 'square');
			
			$chart->y_format['fabqa'] =
			  array('colour' => 'green', 'line' => 'line', 'point' => 'square');
			
			$chart->y_format['fabqw'] =
			  array('colour' => 'black', 'line' => 'line', 'point' => 'square');
			
			  $chart->y_format['lefs'] =
			  array('colour' => 'aqua', 'line' => 'line', 'point' => 'square');

			$chart->y_format['dash'] =
			  array('colour' => 'maroon', 'line' => 'line', 'point' => 'square');

            $chart->y_format['painscale'] =
			  array('colour' => 'yellow', 'line' => 'line', 'point' => 'square');
			// order in which to draw data sets.
			$chart->y_order =$arrayLines;
			
			// chart draw it.
			$chart->draw();
			//echo '<pre>';
			//$chart->getxy();
		}
      }
      // creating object of this class.
      $obj = new linegraph();
?>
      