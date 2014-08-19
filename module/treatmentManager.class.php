<?php
	/**
	 * 
	 * Copyright (c) 2008 Tx Xchange.
	 * It includes the functionality for patient listing, Edit existing and Add new patient
	 * 
	 * 
	 * // necessary classes 
	 * require_once("module/application.class.php");
	 * 
	 * // pagination class
	 * require_once("include/paging/my_pagina_class.php");		
	 * 
	 * // validation classes
	 * require_once("include/validation/_includes/classes/validation/ValidationSet.php");
	 * 
	 * // file upload class
	 * require_once("include/fileupload/class.upload.php");	
	 * 
	 */

	require_once("include/paging/my_pagina_class.php");	
	require_once("include/fileupload/class.upload.php");	
	require_once("module/application.class.php");
	require_once("include/validation/_includes/classes/validation/ValidationSet.php");	
	// Define Constants
	// --IMAGE CONSTANTS
	define('RESTRICT_WIDTH', 1);
	define('RESTRICT_HEIGHT', 2);

	if(!defined('IMAGETYPE_GIF')) define('IMAGETYPE_GIF', 1);
	if(!defined('IMAGETYPE_JPEG')) define('IMAGETYPE_JPEG', 2);
	if(!defined('IMAGETYPE_PNG')) define('IMAGETYPE_PNG', 3);

	// class declaration
  	class treatmentManager extends application{

  	// class variable declaration section
  		/**
  		 * The variable defines the action request
  		 *
  		 * @var string
  		 * @access private
  		 */  		
		private $action;
		/**
		 * array
		 *
		 * @var unknown_type
		 * @access private
		 */
		private $arr;
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
  		 * @var String
  		 * @access Private
  		 */		
		private $output;
		/**
		 * 
		 *
		 * @var string
		 * @access private
		 */
		private $invalid;
		/**
         * 
		 *
		 * @var boolean
		 * @access private
		 */
		private $uploaded;
		// constructor
		function __construct(){
			parent::__construct();
			if($this->value('action')){
				/*
					This block of statement(s) are to handle all the actions supported by this class
					that is it could be the case that more then one action are handled by the class
					for example at first the action is "treatmentManager" then after submit say action is submit
					so if treatmentManager is explicitly called we have the treatmentManager action set 
					(which is also our default action) else whatever action is it is set in $str.				
				*/
				$str = $this->value('action');
			}else{
				header("location:index.php?sysAdmin");//default if no action is specified
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
		 * Displays list of treatments created by systemadmin or (A/c admin / therapist)
		 *
		 * @access public
		 */
		function treatmentManager()
		{



			//start : code to convert video in flv if newvideo/video is uploaded
		$tid = $_GET['tid'];
		$mysql_query_vStatus = "select vconversion_status from  treatment where treatment_id = {$_GET['tid']}";
		    $resultStatus = mysql_query($mysql_query_vStatus);
		     $data = mysql_fetch_array($resultStatus);


	

		if(isset($_GET['tid']) && $data[vconversion_status] =='0' && $_GET['tid']!='')
		
		{
		
		     $destintn  = 'asset/images/treatment/'.$tid;
		      $a = $this->ReadFolderDirectory($destintn);
		     // print_r($a);
		
			foreach($a as $kv=>$vv)
			{
				$fileNameParts = explode( ".", $vv ); // seperate the name from the ext
			   	$fileExtension = end( $fileNameParts ); // part behind last dot
			   	$fileExtension = strtolower( $fileExtension );
				if(substr($fileNameParts[0], 0, 5) == 'video')
				{
			
					
						$pth = $this->config['images_url'];
						//$pth = substr($pth,0,-1);
						//echo $pth;
					
						//echo"video file found";
						$querya = "update treatment set status='4' WHERE treatment_id = $tid ";
						$resulta = @mysql_query($querya) ;
			
					$replace['convertV'] ="<script type='text/javascript'>$(document).ready(function() { $.ajax({type: 'POST',url: '$pth/index.php?action=ConvertVideo&tid=$tid&file=$vv'});});</script>";	//$mysql_query="update treatment set video='video.flv' where treatment_id='$treatment_id'";
				              //  @mysql_query($mysql_query);
					
					
				}
				
		
			 }
		      
		     	
			
		
		
		
		
		}
		else{$replace['convertV']=='';}
		//end : code to convert video in flv if newvideo/video is uploaded
			$this->set_session_page();
			if(isset($_SESSION['catArr']))
			{
				unset($_SESSION['catArr']);
			}

			//First know about user type systemadmin or (A/c admin / therapist)
			$userInfo = $this->userInfo();
			$userId = $userInfo['user_id'];			

			/* Defining Sorting */				
            if($this->value('sort') != ""){
                if($this->value('order') == 'desc' ){
                    $orderByClause = " {$this->value('sort')} desc ";
                }
                else{
                    $orderByClause = " {$this->value('sort')} ";
                }
            }
            else{
                    $orderByClause = " treatment.treatment_name ";
            }
           
            
            $replace['search'] = "";
            if($this->value('search') == "" && $this->value('restore_search') != "" ){
                $_REQUEST['search'] = $this->value('restore_search');
            }
 			 

			/* End */			

			$sqlWhere = "";
			
			if ($userInfo['usertype_id'] == 4)
			{
                if($this->value('search')!=''){
                        $searchKey = trim($this->value('search'));
                        $searchKey = $this->formatSearchTag($searchKey);
                        $searchWhere = " AND (( MATCH (treatment.treatment_tag) AGAINST ('{$searchKey}' IN BOOLEAN MODE )  ) OR treatment.treatment_name = '". trim($this->value('search')) ."' )";
                }
                
                $query = "SELECT  treatment.* 
                                 FROM  treatment 
                                 WHERE  (treatment.status = 1 or treatment.status = 2 ) 
                                 $searchWhere           
                                 group by treatment.treatment_id order by $orderByClause ,treatment.treatment_counts desc ";
                                 
               $sqlcount="SELECT  count(1) FROM  treatment WHERE (treatment.status = 1 or treatment.status = 2 ) $searchWhere ";
                
                
			}
			elseif ($userInfo['usertype_id'] == 2)
			{
				$clinic_id = $this->clinicInfo('clinic_id');
                $parent_clinic_id = $this->get_field($clinic_id,'clinic','parent_clinic_id');
                
                if((is_numeric($clinic_id) && $clinic_id > 0 ) && is_numeric($parent_clinic_id) ){
                     if( $parent_clinic_id == 0 ){
                         $parent_clinic_id = $clinic_id;
                     }
                     else{
                         $clinic_id = $parent_clinic_id;
                     }

                    if($this->value('search')!=''){
                         $searchKey = trim($this->value('search'));
                        $searchKey = $this->formatSearchTag($searchKey);
                        $searchWhere = " AND (( MATCH (treatment.treatment_tag) AGAINST ('{$searchKey}' IN BOOLEAN MODE )  ) OR treatment.treatment_name like '". trim($this->value('search')) ."' )";
                    }
                   $query = " SELECT  treatment.*,treatment_favorite.treatment_favorite_id as treatment_favorite_id
                                 FROM  treatment 
                                 LEFT JOIN treatment_favorite on treatment.treatment_id = treatment_favorite.treatment_id  and treatment_favorite.user_id  = '{$userInfo['user_id']}' 
                                 WHERE  (treatment.status = 1 or treatment.status = 2 )
                                 AND ( 
                                        treatment.clinic_id IN  ( select clinic_id from clinic where clinic_id = '{$clinic_id}' OR  parent_clinic_id = '{$parent_clinic_id}' )
                                        OR treatment.clinic_id IS NULL
                                ) 
                                $searchWhere  
                                group by treatment.treatment_id order by $orderByClause ,treatment.treatment_counts desc ";
                                
                      $sqlcount = " SELECT count(1)
                                 FROM  treatment 
                                 WHERE  (treatment.status = 1 or treatment.status = 2 )
                                 AND ( 
                                        treatment.clinic_id IN  ( select clinic_id from clinic where clinic_id = '{$clinic_id}' OR  parent_clinic_id = '{$parent_clinic_id}' )
                                        OR treatment.clinic_id IS NULL
                                ) 
                                $searchWhere  
                                ";           
                                
				}
			}
            
			$link = $this->pagination($rows = 0,$query,'treatmentManager',$this->value('search'),'','','',$sqlcount);                                          
            $replace['link'] = $link['nav'];
            $result = $link['result'];              

			if($this->num_rows($result)!= 0)
			{
                
				while($row = $this->fetch_array($result))
				{					
					$row['style'] = ($c++%2)?"line1":"line2";

					/**** Preparing Treatment Record ****/
                        if ($userInfo['usertype_id'] == 2){
						//First Block
							/* For each treatment id count the number of categories */
								if( !is_null($row['treatment_favorite_id']) )
								{							
										$row['categories'] = "<div id='favorite_{$row['treatment_id']}' style='padding-left:18px;' ><img src='images/favorite.gif' /></div>";									
                                        
								}
                                else 
                                {
                                        $row['categories'] = "<div id='favorite_{$row['treatment_id']}' style='padding-left:18px;' >&nbsp;</div>";
                                }                                    

						//End
                        }
                         

						//Second Block
							/* For each treatment id count the images*/
								$picCount = 0;																					
								if(!empty($row['pic1'])) $picCount++;
								if(!empty($row['pic2'])) $picCount++;
								if(!empty($row['pic3'])) $picCount++;
								if($picCount) $row['mediaImages'] = $picCount . ' Pictures,';
								else $row['mediaImages'] = 'No Pictures,';																	
							/* */
						//End

						//Third Block
							/* For each treatment id count the media video  */
							if ($row['video']!='')
							{
								$row['mediaVideo'] = ' 1 Video';
							}
							else 
							{
								$row['mediaVideo'] = ' no Video';
							}									
							/* */
						//End							
				
					/**** End ****/				
					if ($row['status'] == 1) 
					{
						/* Treatment Status */
						// We could have set the status out of the if block but then
						// we need to compare as $row['status']== "Active" instead of 1 
						// and 1 means active/current or may be any value so if we change the word
						// we need not to change here unless meaning is changed

						$row['status'] = $this->config['treatmentStatus'][$row['status']];

						//check if usertype is sysadmin then give the action drop down
						if ($userInfo['usertype_id'] == 4)
						{
							//$replace['treatmentRecord'] .=  $this->build_template($this->get_template("treatmentRecord"),$row);												
                            $replace['treatmentRecord'] .=  $this->build_template($this->get_template("treatmentRecordSystem"),$row);
						}
						else if ($userInfo['usertype_id'] == 2)
						{
							//that is either therapist or account admin
							//so do not give action drop down in the treatments, created by sysadmin
                            
                            $user_check_id = $this->userInfo('user_id');
							if ( is_null($row['clinic_id']) || $row['user_id'] != $user_check_id )
							{
								$replace['treatmentRecord'] .=  $this->build_template($this->get_template("treatmentRecordNoAction"),$row);	
							}
							else 
							{
								$replace['treatmentRecord'] .=  $this->build_template($this->get_template("treatmentRecord"),$row);							
							}
						}
					}
					else 
					{
						/* Treatment Status */
						$row['status'] = $this->config['treatmentStatus'][$row['status']];

						//check if usertype is sysadmin then give the action drop down
						if ($userInfo['usertype_id'] == 4)
						{
							//$replace['treatmentRecord'] .=  $this->build_template($this->get_template("treatmentRecordInactive"),$row);												
                            $replace['treatmentRecord'] .=  $this->build_template($this->get_template("treatmentRecordInactiveSystem"),$row);
						}
						else if ($userInfo['usertype_id'] == 2)
						{
							//that is either therapist or account admin
							//so do not give action drop down in the treatments, created by sysadmin
                            $user_check_id = $this->userInfo('user_id');
                            if ( is_null($row['clinic_id']) || $row['user_id'] != $user_check_id )
							{
								$replace['treatmentRecord'] .=  $this->build_template($this->get_template("treatmentRecordNoAction"),$row);	
							}
							else 
							{
								$replace['treatmentRecord'] .=  $this->build_template($this->get_template("treatmentRecordInactive"),$row);							
							}
						}						
					}					
				}

			}
			else 
			{
				$replace['treatmentRecord'] = '<tr><td colspan="5" >No treatment(s) to list.<br/></td></tr>';
				$replace['link'] = "&nbsp;";
			}
			$replace['header'] = $this->build_template($this->get_template("header"));
			$replace['footer'] = $this->build_template($this->get_template("footer"));
			//$replace['sidebar'] = $this->build_template($this->get_template("sidebar"));	
			$replace['sidebar'] = $this->sidebar();
            $replace['keyword'] = $this->value('search');
            
			if ($sqlWhere == "") 			
			{						
				$replace['filter'] = $this->build_template($this->get_template("treatmentFilter"),$replace);	
			}
			else {
				$searchOn['searchOn'] ="";
				$replace['filter'] = $this->build_template($this->get_template("treatmentFilterClear"),$searchOn);
			}	
			if ($userInfo['usertype_id'] == 4)
			{
				$replace['manageCategoryIcon'] = '<td class="iconLabel">
													<a href="index.php?action=categoryManager">
														<img src="images/manageTreatmentCategories.gif" width="127" height="81" alt="Manage Treatment Categories">
													</a>
												  </td>';
                $replace['categoryHead'] = '';
                $replace['tag_title'] = 'Add Tags';
                $treatmentManagerHead = array(
                        'treatment.treatment_name' => 'Video Name',
                        'treatment.status' => 'Status'
                        );
                $query_string = array(
                       'search' => $this->value('search') 
                );
                $replace['treatmentManagerHead'] = $this->build_template($this->get_template("treatmentManagerHeadSystem"),$this->table_heading($treatmentManagerHead,"treatment.treatment_name",$query_string));
			}
			else 
			{
				$replace['manageCategoryIcon'] = "";
                $order2 = $replace['order2'];
                $searchStr = $replace['searchStr'];
                $replace['categoryHead'] = $this->build_template($this->get_template('categoryHead'),$replace);
                $replace['tag_title'] = 'Add Tags/Favorites';
                
                $treatmentManagerHead = array(
                        'treatment.treatment_name' => 'Video Name',
                        'treatment_favorite.treatment_favorite_id' => 'Favorites',
                        'treatment.status' => 'Status'
                        );
                $query_string = array(
                       'search' => $this->value('search') 
                );
                $replace['treatmentManagerHead'] = $this->build_template($this->get_template("treatmentManagerHead"),$this->table_heading($treatmentManagerHead,"treatment.treatment_name",$query_string));
			}
            
            
			$replace['body'] = $this->build_template($this->get_template("treatmentManager"),$replace);
			$replace['browser_title'] = "Tx Xchange: Tx Manager";
		 
			$replace['get_satisfaction'] = $this->get_satisfaction();

            // Personalized GUI  
            
            $createTreatmentImage=$_SESSION['providerLabel']['images/createNewTreatment.gif']!=''?$_SESSION['providerLabel']['images/createNewTreatment.gif']:'images/createNewTreatment.gif';
            $replace['imageCreateTreatment']=$createTreatmentImage;			 
            
			$this->output = $this->build_template($this->get_template("main"),$replace);
		}
  	     function formatSearchTag( $tag_search ){
            /*$tag_search = trim($tag_search);
            if( $tag_search != "" ){
               $search_array = explode(" ",$tag_search);
               if( is_array($search_array) && count($search_array) > 0 ){
                   foreach( $search_array as $value ){
                        if( trim($value) != "" ){
                            $str .= "+".trim($value)."*"." ";
                        }
                    }
                }
            }
            $str = rtrim($str);
            return $str;*/    
            $tag_search = trim($tag_search);
            if( $tag_search != "" ){
            $search_array = explode(" ",$tag_search);
            if( is_array($search_array) && count($search_array) > 0 ){
                foreach( $search_array as $value ){
                    $temp = trim($value);
                    if( $temp != "" ){
                        if( $this->stopword($temp) === true){
                            continue;
                        }
                        $temp = trim($value);
                        if( is_string($this->sliceword($temp)) ){
                            $strtmp = $this->sliceword($temp);
                            if(strlen($strtmp) > 2){
                                $str .= "+" . $strtmp . "*"." ";
                            }else {
                                $str .= "+" . $strtmp;
                            }
                        }
                        else{
                            if(strlen($temp) > 2){
                                $str .= "+" . $temp . "*"." ";
                            }else {
                                $str .= "+" . $temp;
                            } 
                        }
                    }
                }
            }
            }
            $str = rtrim($str);
            return $str;
        }
        
        function stopword($word){
            $stopword = array("a's","able","about","above","according","accordingly","across","actually","after","afterwards","again","against","ain't","all","allow","allows","almost","alone","along","already","also","although","always","am","among","amongst","an","and","another","any","anybody","anyhow","anyone","anything","anyway","anyways","anywhere","apart","appear","appreciate","appropriate","are","aren't","around","as","aside","ask","asking","associated","at","available","away","awfully","be","became","because","become","becomes","becoming","been","before","beforehand","behind","being","believe","below","beside","besides","best","better","between","beyond","both","brief","but","by","c'mon","c's","came","can","can't","cannot","cant","cause","causes","certain","certainly","changes","clearly","co","com","come","comes","concerning","consequently","consider","considering","contain","containing","contains","corresponding","could","couldn't","course","currently","definitely","described","despite","did","didn't","different","do","does","doesn't","doing","don't","done","down","downwards","during","each","edu","eg","eight","either","else","elsewhere","enough","entirely","especially","et","etc","even","ever","every","everybody","everyone","everything","everywhere","ex","exactly","example","except","far","few","fifth","first","five","followed","following","follows","for","former","formerly","forth","four","from","further","furthermore","get","gets","getting","given","gives","go","goes","going","gone","got","gotten","greetings","had","hadn't","happens","hardly","has","hasn't","have","haven't","having","he","he's","hello","help","hence","her","here","here's","hereafter","hereby","herein","hereupon","hers","herself","hi","him","himself","his","hither","hopefully","how","howbeit","however","i'd","i'll","i'm","i've","ie","if","ignored","immediate","in","inasmuch","inc","indeed","indicate","indicated","indicates","inner","insofar","instead","into","inward","is","isn't","it","it'd","it'll","it's","its","itself","just","keep","keeps","kept","know","knows","known","last","lately","later","latter","latterly","least","less","lest","let","let's","like","liked","likely","little","look","looking","looks","ltd","mainly","many","may","maybe","me","mean","meanwhile","merely","might","more","moreover","most","mostly","much","must","my","myself","name","namely","nd","near","nearly","necessary","need","needs","neither","never","nevertheless","new","next","nine","no","nobody","non","none","noone","nor","normally","not","nothing","novel","now","nowhere","obviously","of","off","often","oh","ok","okay","old","on","once","one","ones","only","onto","or","other","others","otherwise","ought","our","ours","ourselves","out","outside","over","overall","own","particular","particularly","per","perhaps","placed","please","plus","possible","presumably","probably","provides","que","quite","qv","rather","rd","re","really","reasonably","regarding","regardless","regards","relatively","respectively","right","said","same","saw","say","saying","says","second","secondly","see","seeing","seem","seemed","seeming","seems","seen","self","selves","sensible","sent","serious","seriously","seven","several","shall","she","should","shouldn't","since","six","so","some","somebody","somehow","someone","something","sometime","sometimes","somewhat","somewhere","soon","sorry","specified","specify","specifying","still","sub","such","sup","sure","t's","take","taken","tell","tends","th","than","thank","thanks","thanx","that","that's","thats","the","their","theirs","them","themselves","then","thence","there","there's","thereafter","thereby","therefore","therein","theres","thereupon","these","they","they'd","they'll","they're","they've","think","third","this","thorough","thoroughly","those","though","three","through","throughout","thru","thus","to","together","too","took","toward","towards","tried","tries","truly","try","trying","twice","two","un","under","unfortunately","unless","unlikely","until","unto","up","upon","us","use","used","useful","uses","using","usually","value","various","very","via","viz","vs","want","wants","was","wasn't","way","we","we'd","we'll","we're","we've","welcome","well","went","were","weren't","what","what's","whatever","when","whence","whenever","where","where's","whereafter","whereas","whereby","wherein","whereupon","wherever","whether","which","while","whither","who","who's","whoever","whole","whom","whose","why","will","willing","wish","with","within","without","won't","wonder","would","would","wouldn't","yes","yet","you","you'd","you'll","you're","you've","your","yours","yourself","yourselves","zero");
            if( in_array($word,$stopword) ){
                return true;
            }
            else
                return false;
       }
    
        function sliceword($word){
            $input = array('ed', 'ing', 'es', 's', 'tion');
            foreach($input as $value){
                if(  preg_match( "/{$value}$/i" , $word ) ){
                    $flag = preg_replace( "/{$value}$/i" , '',$word );
                    return $flag;
                }
            }
            return false;
        }
		/**
		 * Functionality to create new treatment 
		 *
		 * @access public
		 */

		function createTreatment()
		{
			include_once("template/treatmentManager/createTreatmentArray.php");
            
            
			//First know about user type systemadmin or (A/c admin / therapist)
			$userInfo = $this->userInfo();
			$userId = $userInfo['user_id'];		
			$this->arr = $formArray;
			$replace = $this->fillForm($this->arr,true);
			$showForm = 1;
			$showList = 0;                                
                                                 
			if(isset($_SESSION['catArr']) && $this->value('formSubmit') == "")
			{
				unset($_SESSION['catArr']);
			}

			if( $this->value('formSubmit') == "submit" )
			{				
                
              
				$this->validateFormTreatment();
                $replace['error'] = $this->error;   
                               
                                 
				if($replace['error'] == "")
				{
					//execute insert process
					//First insert record in treatment table and get the treatment_id 
					$treatment_id = $this->insertTreatmentRecord();
                                                            
                                                   
                    
            /*
            *Work : implement Uplodify in create treatment page
            *Code Desc : code for moving from folder ,placed by uplodify,start
            *Date : 3 March 2011
            */           
                         
            
            $fileDest = "asset/images/treatment/".$treatment_id ;  
                                       //\asset\images\treatment
                                                       
                  if(!file_exists($fileDest))

                    {

                        mkdir($fileDest);
                        chmod($fileDest, 0777);

                    }
                                            
                           $Source  = 'asset/temporary/'.$userId;
                           $destintn  = 'asset/images/treatment/'.$treatment_id;
             
                        $this->copydir("$Source","$destintn","$treatment_id");
               /*
               *code for moving from folder ,placed by uplodify,ends
               */          
   				//check for fileupload
					$arrFileContent = $this->handleFileUpload($treatment_id);	
					if (!empty($this->invalid))
					{
						//error
						$searchArray = array('pic1','pic2','pic3');
						for ($i=0;$i<count($searchArray);++$i)
						{
							if (array_key_exists($searchArray[$i],$this->invalid))
							{
								$replace['error'] = 'Invalid image type for'.$searchArray[$i].', please make sure the image extension is of type jpeg/jpg/png/bmp/gif';								
								break;
							}
						}
						if ($replace['error'] == "")
						{
							if (array_key_exists('video',$this->invalid))
							{
								$replace['error'] = 'Invalid video type, please specify that the video file is of extension .flv';	
							}
						}

						// Delete the treatment record
						$this->deleteBadTreatmentRecord($treatment_id);
						$showForm = 1;		
					}						
					else 
					{
						//everything goes well
						//insert the rest of the record fields
						//also check if usertype is sysadmin then clinic_id = null else clinicid 
						if ($userInfo['usertype_id'] == 4)
						{
							$clinic_id = null;
						}
						else 
						{
							$clinic_id = $this->clinicInfo('clinic_id');
						}
						$condition = " treatment_id = ".$treatment_id;
						$updateFieldsArray = array(
													'benefit' => $this->value('benefit'),
													'instruction' => $this->value('instruction'),
													'sets' => $this->value('sets'),
													'reps' => $this->value('reps'),
													'hold' => $this->value('hold'),
													'lrb' => $this->value('lrb'),
													'status' => $this->value('status'),
													'user_id' => $userId,
													'clinic_id' => $clinic_id
													);

						$tableName = 'treatment';																	

						if ($arrFileContent != null)
						{
							foreach ($arrFileContent as $key => $value )
							{		
								if (!empty($value)) 
                                {										
									$updateFieldsArray[$key] = 	$value;									
								}
							}							
						}
                        
						$result = $this->update($tableName,$updateFieldsArray,$condition);
                        
                        // Add tags insertTag
                        if( ($userInfo['usertype_id'] == 4  || $userInfo['usertype_id'] == 2) && $result ){
                            $tag = $this->value('tag');
                            $tag_treatment_id = $treatment_id;
                            if( strlen($tag) > 0 && is_numeric($treatment_id) && $treatment_id > 0 ){
                                $this->insertTag($tag,$tag_treatment_id);
                            }
                        }
                        
						//insert categories						
                        // Commented on 1090 release.
                        
						/*if ($userInfo['usertype_id'] == 2  && !empty($_SESSION['catArr']) && $result )
						{
							$flagAllFields = true;							
							$tableName = "treatment_category";
							for($i=0;$i<count($_SESSION['catArr']);++$i)
							{
								$fieldArray = array(
												"treatment_id" => $treatment_id,
												"category_id" => $_SESSION['catArr'][$i],
												"creation_date" => date('Y-m-d H:i:s',time())							
												);

								$result = $this->insert($tableName,$fieldArray);

								if (!$result)
								{
									break;
								}
							}						
						}*/

						//insert speciality
						if ($result)
						{
							$tableName = "treatment_speciality";
							$fieldArray = null;

							for($i=0;$i<count($_POST['speciality']);++$i)
							{
								$fieldArray = array(
												"treatment_id" => $treatment_id,
												"speciality" => $_POST['speciality'][$i],
												"creation_date" => date('Y-m-d H:i:s',time())							
												);

								$result = $this->insert($tableName,$fieldArray);																
								if (!$result)
								{
									break;
								}
							}	
						}

						if ($result)
						{
							// updation successful
							if(isset($_SESSION['catArr']))
							{
								unset($_SESSION['catArr']);
							}
							$showList = 1;				
						}
						else 
						{
                        	$replace['error'] = 'Unable to insert into the database.';
                        	$showForm = 1;
						}
					}
				}	
				else 
				{
					$showForm = 1;
				}	
			}
			else 
			{
				$showForm = 1;
			}

			if ($showForm == 1)
			{
                 
            /*
            *Work : implement Uplodify in create treatment page.
            *Code Desc : to make temporary folder empty code : start
            *Developed by : Abhishek Sharma
            *Organization : Hytech Professionals 
            *Date : 3 May 2011
            */   
                $fileDest = "asset/temporary/".$userId ;  
               
                                                       
                  if(!file_exists($fileDest))

                    {

                        mkdir($fileDest);

                        chmod($fileDest, 0777);

                    }
                    
                    
                $chkDirContent = $this->checkFolderIsEmptyOrNot($fileDest);                                                     
                //echo"<pre>";
                // print_r($chkDirContent );
                if(is_dir($fileDest))
                {
                 $MakeEmptyFolder = $this->empty_folder($fileDest);                  
                }                
                
                
                
            /*
            *Work : to make temporary folder empty code : ends
            */   
				$this->formArray = $tableFieldArray;	
				$tableArray = ($this->fillTableArray($this->formArray));
				$tableArray['usertype_id'] = 1;
				$replace['speciality'] = $this->buildInputCheckBox($arrCheckBox,'speciality',$this->value('speciality'));			
				$replace['lrb'] = $this->buildInputRadioBox($arrRadioBox,'lrb',$this->value('lrb'));
				$replace['statusOption'] = $this->build_select_option($status,$replace['status']);
				$replace['helpText'] = 'Put the checkmark against the appropriate box to which this treatment is related to';
				$replace['header'] = $this->build_template($this->get_template("header"));
				$replace['footer'] = $this->build_template($this->get_template("footer"));		
                $replace['userId'] = $userId;
				if ($userInfo['usertype_id'] == 4)
				{
					$replace['manageCategoryIcon'] = '<td class="iconLabel">
														<a href="index.php?action=categoryManager">
															<img src="images/manageTreatmentCategories.gif" width="127" height="81" alt="Manage Treatment Categories">
														</a>
													  </td>';
                    $replace['tag'] = $this->value('tag');
                    $replace['tagForm1'] = $this->build_template($this->get_template('tagForm'),$replace);
				}
				else 
				{
					$replace['manageCategoryIcon'] = "";
                    $replace['tag'] = $this->value('tag');
                    $replace['tagForm'] = $this->build_template($this->get_template('tagForm'),$replace);
				}
				$replace['sidebar'] = $this->sidebar();
				$replace['body'] = $this->build_template($this->get_template("createTreatment"),$replace);
                
				$replace['browser_title'] = "Tx Xchange: Create Treatment";
				 
				$replace['get_satisfaction'] = $this->get_satisfaction();

                // Personalized GUI  
                // Labels
                $TreatmentName=$_SESSION['providerLabel']['Treatment Name']!=''?$_SESSION['providerLabel']['Treatment Name']:'Treatment Name'; 
                //$TreatmentName=$_SESSION['providerLabel']['Treatment Name']!=''?$_SESSION['providerLabel']['Treatment Name']:'Video Name'; 
                $DefaultInstructions=$_SESSION['providerLabel']['Default Instructions']!=''?$_SESSION['providerLabel']['Default Instructions']:'Default Instructions';
                $BenefitofTreatment=$_SESSION['providerLabel']['Benefit of Treatment']!=''?$_SESSION['providerLabel']['Benefit of Treatment']:'Benefit of Treatment';
                //$BenefitofTreatment=$_SESSION['providerLabel']['Benefit of Treatment']!=''?$_SESSION['providerLabel']['Benefit of Treatment']:'Benefit of Video';
                $TreatmentDetails=$_SESSION['providerLabel']['Treatment Details']!=''?$_SESSION['providerLabel']['Treatment Details']:'Treatment Details';
                //$TreatmentDetails=$_SESSION['providerLabel']['Treatment Details']!=''?$_SESSION['providerLabel']['Treatment Details']:'Video Details';
                $TreatmentMedia=$_SESSION['providerLabel']['Treatment Media']!=''?$_SESSION['providerLabel']['Treatment Media']:'Treatment Media';
                //$TreatmentMedia=$_SESSION['providerLabel']['Treatment Media']!=''?$_SESSION['providerLabel']['Treatment Media']:'Media';
                $TreatmentAvailability=$_SESSION['providerLabel']['Treatment Availability']!=''?$_SESSION['providerLabel']['Treatment Availability']:'Treatment Availability';
                //$TreatmentAvailability=$_SESSION['providerLabel']['Treatment Availability']!=''?$_SESSION['providerLabel']['Treatment Availability']:'Video Availability';
                $createTreatmentImage=$_SESSION['providerLabel']['images/createNewTreatment.gif']!=''?$_SESSION['providerLabel']['images/createNewTreatment.gif']:'images/createNewTreatment.gif';
               // $createnewTreatment=$_SESSION['providerLabel']['Create New Treatment']!=''?$_SESSION['providerLabel']['Create New Treatment']:'Create New Treatment';
               $createnewTreatment=$_SESSION['providerLabel']['Create New Treatment']!=''?$_SESSION['providerLabel']['Create New Treatment']:'Create Video';
                // Labels            
                $replace['labelDefaultInstructions']=$DefaultInstructions;
                $replace['labelBenefitofTreatment']=$BenefitofTreatment;
                $replace['labelTreatmentDetails']=$TreatmentDetails;
                $replace['labelTreatmentMedia']=$TreatmentMedia;
                $replace['labelTreatmentAvailability']=$TreatmentAvailability;
                $replace['labelTreatmentName']=$TreatmentName;                 
                $replace['imageCreateTreatment']=$createTreatmentImage;
                $replace['labelCreateNewTreatment']=$createnewTreatment;
        

                // Fields
                $displayFieldSets='';
                $displayFieldReps='';
                $displayFieldHold='';
                $displayFieldLRB='';
                
                $SetsDisplay=$_SESSION['providerField']['Sets']!=''?$_SESSION['providerField']['Sets']:'1';
                $RepsDisplay=$_SESSION['providerField']['Reps']!=''?$_SESSION['providerField']['Reps']:'1';
                $HoldDisplay=$_SESSION['providerField']['Hold']!=''?$_SESSION['providerField']['Hold']:'1';
                $LRBDisplay=$_SESSION['providerField']['LRB']!=''?$_SESSION['providerField']['LRB']:'1';
                
                if($SetsDisplay=='0'){
                    $replace['displayFieldSets']=$displayFieldSets='none';      
                }
                if($RepsDisplay=='0'){
                    $replace['displayFieldReps']=$displayFieldReps='none';      
                }
                if($HoldDisplay=='0'){
                    $replace['displayFieldHold']=$displayFieldHold='none';      
                }
                if($LRBDisplay=='0'){
                    $replace['displayFieldLRB']=$displayFieldLRB='none';      
                }
					$specialityDisplay=$_SESSION['providerField']['speciality']!=''?$_SESSION['providerField']['speciality']:'1';
					if($specialityDisplay=='1'){
    	              $replace['specialityform'] = $this->build_template($this->get_template("specialityform"),$replace);   
	                }
                
				$this->output = $this->build_template($this->get_template("main"),$replace);
			}

			if ($showList == 1)
			{
				header("location:index.php?action=treatmentManager&tid=$treatment_id");	
			}
		}

		/**
		 * Functionality for editing existing treatment
		 *
		 * @access public
		 */
		function editTreatment()
		{
			//print_r($_SESSION);
            header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
			$replace = array();		

			include_once("template/treatmentManager/createTreatmentArray.php");
			/**********************code starts here******************************************************/
			//The below code is to perform delete action if request is made to delete treatment photos or videos.
			/********************************************************************************************/
            
            $userInfo = $this->userInfo();
			$userId = $userInfo['user_id'];	
			if( $_GET['submit'] == "delete" && !empty($_GET['pic']) && !empty($_GET['id']))
			{				
				$Source  = 'asset/temporary/'.$userId;
                if(is_dir($Source)){
                $this->empty_folder($Source);
                }
                $treatment_id = $_GET['id'];
				$pic = $this->value('pic');
				$query="SELECT {$pic} as pics FROM treatment WHERE treatment_id={$treatment_id}";
				$res=@mysql_query($query);
				$obj=@mysql_fetch_object($res);
				$path=$_SERVER['DOCUMENT_ROOT'].'/asset/images/treatment/'.$treatment_id.'/';
				@unlink($path.$obj->pics);
                $ext=explode('.',$obj->pics);
				@unlink($path.'thumb.'.$ext[1]);
				$query = "update treatment set {$pic}='' where treatment_id ={$treatment_id}";
                @mysql_query($query);
			}
			/*******************************************************************************************/
			//The below code is to perform delete action if request is made to delete treatment photos or videos.
			/**********************code ends here*****************************************************/
			
			$showForm = 1;
            $displayError = false;

					

			$allowFlag = 1;
			$treatment_id = $this->value('id');
			$queryTreatment = "SELECT * FROM treatment WHERE treatment_id = '$treatment_id'";
			$resultTreatment = $this->execute_query($queryTreatment);
			$row = $this->fetch_array($resultTreatment);  
			$clinic_id = $this->clinicInfo('clinic_id');			

			if ($userInfo['usertype_id'] == 4)
			{
				if (!empty($row['clinic_id'])) {
					//$allowFlag = 0;
					$showForm = 0;
					//$showList = 1;
				}				
			}
			else if ($userInfo['usertype_id'] == 2)
			{
				if (empty($row['clinic_id']) || ($clinic_id != $row['clinic_id']))
				{
					$allowFlag = 0;
					$showForm = 0;
					$showList = 1;
				}
			}

			if( $this->value('submitted') == "Save Treatment" && $allowFlag == 1)
			{				
				$treatment_id = $this->value('id');
				$this->validateFormTreatment($treatment_id);											


//write edit code
			if($_REQUEST['video_field']=='1'){





			$destintnb  = $_SERVER['DOCUMENT_ROOT'].'/asset/images/treatment/'.$treatment_id.'/';

			chmod($destintnb, 0777);





		    
		      $b = $this->ReadFolderDirectory($destintnb);


		
			foreach($b as $kv=>$vv)
			{
				$fileNameParts = explode( ".", $vv ); // seperate the name from the ext
			   	$fileExtension = end( $fileNameParts ); // part behind last dot
			   	$fileExtension = strtolower( $fileExtension );
				if(substr($fileNameParts[0], 0, 5) == 'video')
				
				{
				
				echo  $destintnb."/".$vv;
                if(file_exists($destintnb."/".$vv))
				@unlink($destintnb."/".$vv);
				if(file_exists($destintnb."/".$vv))
				@unlink($destintnb."/videolarge.jpg");
				if(file_exists($destintnb."/videolarge.jpg"))
				@unlink($destintnb."/videolarge.jpg");

			
					
				}
				
		
			
			
			


			 }

 			


			$tid=$treatment_id;

			$querya = "update treatment set vconversion_status='0' WHERE treatment_id = $treatment_id ";
						
			$resulta = @mysql_query($querya) ;






			

			}



				if($this->error == "")
				{                       
					//check for fileupload
                    
                           $Source  = 'asset/temporary/'.$userId;
                           $destintn  = 'asset/images/treatment/'.$treatment_id;
             
                       $this->copydir($Source,$destintn,$treatment_id);
		//echo"hi";

                    
                    
                    
					$arrFileContent = $this->handleFileUpload($treatment_id);	

					if (!empty($this->invalid))
					{
						//error
						$searchArray = array('pic1','pic2','pic3');
						for ($i=0;$i<count($searchArray);++$i)
						{
							if (array_key_exists($searchArray[$i],$this->invalid))
							{
								$replace['error'] = 'Invalid image type for'.$searchArray[$i].', please make sure the image extension is of type jpeg/jpg/png/bmp/gif';								
								break;
							}
						}

						if ($replace['error'] == "")
						{
							if (array_key_exists('video',$this->invalid))
							{
								$replace['error'] = 'Invalid video type, please specify that the video file is of extension .flv';	
							}
						}					

						$showForm = 1;		
						$displayError = true;
					}						
					else 
					{
						//everything goes well
						//insert the rest of the record fields
						$condition = "treatment_id = ".$treatment_id;
						$updateFieldsArray = array(
													'treatment_name' => $this->value('treatment_name'),
													'benefit' => $this->value('benefit'),
													'instruction' => $this->value('instruction'),
													'sets' => $this->value('sets'),
													'reps' => $this->value('reps'),
													'hold' => $this->value('hold'),
													'lrb' => $this->value('lrb'),
													'status' => $this->value('status')
													);

						$tableName = 'treatment';						
						if ($arrFileContent != null)
						{
							foreach ($arrFileContent as $key => $value )
							{		
								if (!empty($value)) 
								{	
									$updateFieldsArray[$key] = 	$value;
								}
							}							
						}
                        
                        //$old_treatment_name = $this->get_field($treatment_id,'treatment','treatment_name');
                        //$new_treatment_name = $this->value('treatment_name');
                        
						$result = $this->update($tableName,$updateFieldsArray,$condition);		
                        
                        // update treatment tag coloumn in treatment table.
                        if( $result && is_numeric($treatment_id) ){
                            //$query = "update treatment set treatment_tag = replace(treatment_tag,'{$old_treatment_name}','{$new_treatment_name}') where treatment_id = '{$treatment_id}';";
                            $query = "update treatment as a left join (SELECT GROUP_CONCAT( `tag_name` SEPARATOR ' ' ) as c_tag, `treatment_id`
                                FROM `tag` GROUP BY `treatment_id` having  treatment_id = '{$treatment_id}' ) as b on b.treatment_id = a.treatment_id 
                                set a.treatment_tag = CONCAT_WS(' ',a.treatment_name,b.c_tag)
                                where a.treatment_id = '{$treatment_id}' ";

                            @mysql_query($query);
                        }
						  //update categories                        

                        /*if (!empty($_SESSION['catArr']) && $result)
                        {

                            // since its an update so

                            $sql = "DELETE FROM treatment_category WHERE treatment_id = ".$treatment_id;

                            $this->execute_query($sql);    

                            

                            $flagAllFields = true;                            

                            $tableName = "treatment_category";

                            for($i=0;$i<count($_SESSION['catArr']);++$i)

                            {

                                $fieldArray = array(

                                                "treatment_id" => $treatment_id,

                                                "category_id" => $_SESSION['catArr'][$i],

                                                "creation_date" => date('Y-m-d H:i:s',time())                            

                                                );

                                                

                                //$result = $this->executeInsertQuery($tableName,$fieldArray,false);    

                                $result = $this->insert($tableName,$fieldArray);



                                                            

                                if (!$result)

                                {

                                    break;

                                }

                            }                        

                            

                        }*/
                        
						if ($result)
						{
							$tableName = "treatment_speciality";
							$fieldArray = null;
							// since its an update so
							$sql = "DELETE FROM treatment_speciality WHERE treatment_id = ".$treatment_id;
							$this->execute_query($sql);	

							for($i=0;$i<count($_POST['speciality']);++$i)
							{
								$fieldArray = array(
												"treatment_id" => $treatment_id,
												"speciality" => $_POST['speciality'][$i],
												"creation_date" => date('Y-m-d H:i:s',time())							
												);

								$result = $this->insert($tableName,$fieldArray);
								if (!$result)
								{
									break;
								}
							}	
						}

						if ($result)
						{
							// updation successful
							if(isset($_SESSION['catArr']))
							{
								unset($_SESSION['catArr']);
							}
							$showList = 1;				
						}
						else 
						{
							$replace['error'] = 'Unable to update into the database.';
							$showForm = 1;
							$displayError = true;
						}
					}
				}	
				else 
				{
					$showForm = 1;	
					$replace['error'] = $this->error;
					$displayError = true;												
				}	
			}
			else if($this->value('id')!="")
			{
				//first time form
				$showForm = 1;
				$displayError = false;
			}
			/* The Form */
			if ($showForm == 1)
			{
				$treatment_id = $this->value('id');
				$where = " treatment_id = ".$treatment_id;
				$row = $this->table_record($this->config['table']['treatment'],$where);				
				if(is_array($row))
				{										
					$replace['id'] = $this->value('id');
					if (empty($row['clinic_id']))
					{
						$replace['clinic_id'] = 'null'; 
					}
					else 
					{
						$replace['clinic_id'] = $row['clinic_id']; 				
					}	                              
                     $replace['userId'] = $userId;    
					$replace['treatmentName'] = strtoupper($row['treatment_name']);	
					$replace['treatmentHeading'] = ucwords($row['treatment_name']);
					$replace['treatment_name'] = ($displayError) ? $this->value('treatment_name') : $row['treatment_name'];							
					$replace['instruction'] = ($displayError) ? $this->value('instruction') : $row['instruction'];
					$replace['benefit'] = ($displayError) ? $this->value('benefit') : $row['benefit'];
					$replace['sets'] = ($displayError) ? $this->value('sets') : $row['sets'];
					$replace['reps'] = ($displayError) ? $this->value('reps') : $row['reps'];
					$replace['hold'] = ($displayError) ? $this->value('hold') : $row['hold'];
					$replace['lrb'] = ($displayError) ? $this->buildInputRadioBox($arrRadioBox,'lrb',$this->value('lrb')) : $this->buildInputRadioBox($arrRadioBox,'lrb',$row['lrb']);
					$replace['statusOption'] = ($displayError) ? $this->build_select_option($status,$this->value('status')) : $this->build_select_option($status,$row['status']);
				    if (!empty($row['video'])) 
                    {
                        if(file_exists($_SERVER['DOCUMENT_ROOT'].$this->canonicalize($this->config['tx']['treatment']['media_url'] . $this->value('id') . '/')  . 'video.jpg'))
                    	$replace['thumbImage'] = '<img src="'.$this->canonicalize($this->config['tx']['treatment']['media_url'] . $this->value('id') . '/')  . 'video.jpg" border="0" />';
                    	              
                        $replace['mayBeShowMediaVideo'] = $this->maybeShowMedia('video',substr($row['original_video'], 9,strlen($row['original_video'])),$treatment_id);
                    }
					if (!empty($row['pic1'])) 
					{
						$ext=explode('.',$row['pic1']);
						//echo $this->canonicalize($this->config['tx']['treatment']['media_url'] . $this->value('id') . '/')  . 'thumb.'.strtolower($ext[1]);
						if(file_exists($_SERVER['DOCUMENT_ROOT'].$this->canonicalize($this->config['tx']['treatment']['media_url'] . $this->value('id') . '/')  . 'thumb.'.strtolower($ext[1])))
                        $replace['thumbImage'] = '<img src="'.$this->canonicalize($this->config['tx']['treatment']['media_url'] . $this->value('id') . '/')  . 'thumb.'.strtolower($ext[1]).'" border="0" />';				
						
                        $replace['mayBeShowMediaPic1'] = $this->maybeShowMedia('pic1',$row['pic1'],$treatment_id);
					}
					if (!empty($row['pic2'])) 
					{						
						$replace['mayBeShowMediaPic2'] = $this->maybeShowMedia('pic2',$row['pic2'],$treatment_id);
					}
					if (!empty($row['pic3'])) 
					{						
						$replace['mayBeShowMediaPic3'] = $this->maybeShowMedia('pic3',$row['pic3'],$treatment_id);
					}	
					
				}
				if ($displayError == true) 
				{
					$replace['speciality'] = $this->buildInputCheckBox($arrCheckBox,'speciality',$this->value('speciality'));										
				}
				else 
				{
					$sql = "SELECT speciality FROM treatment_speciality WHERE treatment_id = ".$treatment_id;
					$result = $this->execute_query($sql);
					while($row = $this->fetch_array($result))
					{
						$arraySpeciality[] = $row['speciality'];					
					}
					$replace['speciality'] = $this->buildInputCheckBox($arrCheckBox,'speciality',$arraySpeciality);	
				}					
				$replace['helpText'] = 'Put the checkmark against the appropriate box to which this treatment is related to';
				$replace['header'] = $this->build_template($this->get_template("header"));
				$replace['footer'] = $this->build_template($this->get_template("footer"));		
				//$replace['sidebar'] = $this->build_template($this->get_template("sidebar"));
				$replace['sidebar'] = $this->sidebar();
				if ($userInfo['usertype_id'] == 4)
				{
					$replace['manageCategoryIcon'] = '<td class="iconLabel">
														<a href="index.php?action=categoryManager">
															<img src="images/manageTreatmentCategories.gif" width="127" height="81" alt="Manage Treatment Categories">
														</a>
													  </td>';
                    //$replace['tag'] = $this->value('tag');
                    $replace['tagFormEdit1'] = $this->build_template($this->get_template('tagFormEdit'),$replace);
				}
				else 
				{
					$replace['manageCategoryIcon'] = "";
                    $replace['tagFormEdit'] = $this->build_template($this->get_template('tagFormEdit'),$replace);
				}
                
                 // Personalized GUI  
                // Labels
                $TreatmentName=$_SESSION['providerLabel']['Treatment Name']!=''?$_SESSION['providerLabel']['Treatment Name']:'Treatment Name'; 
                //$TreatmentName=$_SESSION['providerLabel']['Treatment Name']!=''?$_SESSION['providerLabel']['Treatment Name']:'Video Name'; 
                $DefaultInstructions=$_SESSION['providerLabel']['Default Instructions']!=''?$_SESSION['providerLabel']['Default Instructions']:'Default Instructions';
               $BenefitofTreatment=$_SESSION['providerLabel']['Benefit of Treatment']!=''?$_SESSION['providerLabel']['Benefit of Treatment']:'Benefit of Treatment';
                //$BenefitofTreatment=$_SESSION['providerLabel']['Benefit of Treatment']!=''?$_SESSION['providerLabel']['Benefit of Treatment']:'Benefit of Video';
                $TreatmentDetails=$_SESSION['providerLabel']['Treatment Details']!=''?$_SESSION['providerLabel']['Treatment Details']:'Treatment Details';
                //$TreatmentDetails=$_SESSION['providerLabel']['Treatment Details']!=''?$_SESSION['providerLabel']['Treatment Details']:'Video Details';
                $TreatmentMedia=$_SESSION['providerLabel']['Treatment Media']!=''?$_SESSION['providerLabel']['Treatment Media']:'Treatment Media';
                //$TreatmentMedia=$_SESSION['providerLabel']['Treatment Media']!=''?$_SESSION['providerLabel']['Treatment Media']:'Media';
                $TreatmentAvailability=$_SESSION['providerLabel']['Treatment Availability']!=''?$_SESSION['providerLabel']['Treatment Availability']:'Treatment Availability';
                //$TreatmentAvailability=$_SESSION['providerLabel']['Treatment Availability']!=''?$_SESSION['providerLabel']['Treatment Availability']:'Video Availability';
                $createTreatmentImage=$_SESSION['providerLabel']['images/createNewTreatment.gif']!=''?$_SESSION['providerLabel']['images/createNewTreatment.gif']:'images/createNewTreatment.gif';
                //$createnewTreatment=$_SESSION['providerLabel']['Create New Treatment']!=''?$_SESSION['providerLabel']['Create New Treatment']:'Create New Treatment';
                $createnewTreatment=$_SESSION['providerLabel']['Create New Treatment']!=''?$_SESSION['providerLabel']['Create New Treatment']:'Create New Video';
                // Labels            
                $replace['labelDefaultInstructions']=$DefaultInstructions;
                $replace['labelBenefitofTreatment']=$BenefitofTreatment;
                $replace['labelTreatmentDetails']=$TreatmentDetails;
                $replace['labelTreatmentMedia']=$TreatmentMedia;
                $replace['labelTreatmentAvailability']=$TreatmentAvailability;
                $replace['labelTreatmentName']=$TreatmentName;                 
                $replace['imageCreateTreatment']=$createTreatmentImage;
                $replace['labelCreateNewTreatment']=$createnewTreatment;

                // Fields
                $displayFieldSets='';
                $displayFieldReps='';
                $displayFieldHold='';
                $displayFieldLRB='';
                
                $SetsDisplay=$_SESSION['providerField']['Sets']!=''?$_SESSION['providerField']['Sets']:'1';
                $RepsDisplay=$_SESSION['providerField']['Reps']!=''?$_SESSION['providerField']['Reps']:'1';
                $HoldDisplay=$_SESSION['providerField']['Hold']!=''?$_SESSION['providerField']['Hold']:'1';
                $LRBDisplay=$_SESSION['providerField']['LRB']!=''?$_SESSION['providerField']['LRB']:'1';
                
                if($SetsDisplay=='0'){
                    $replace['displayFieldSets']=$displayFieldSets='none';      
                }
                if($RepsDisplay=='0'){
                    $replace['displayFieldReps']=$displayFieldReps='none';      
                }
                if($HoldDisplay=='0'){
                    $replace['displayFieldHold']=$displayFieldHold='none';      
                }
                if($LRBDisplay=='0'){
                    $replace['displayFieldLRB']=$displayFieldLRB='none';      
                }
                

                
				$replace['body'] = $this->build_template($this->get_template("editTreatment"),$replace);
				$replace['browser_title'] = "Tx Xchange: Edit Video";
				$specialityDisplay=$_SESSION['providerField']['speciality']!=''?$_SESSION['providerField']['speciality']:'1';
					if($specialityDisplay=='1'){
    	              $replace['specialityform'] = $this->build_template($this->get_template("specialityform"),$replace);   
	                }
				$this->output = $this->build_template($this->get_template("main"),$replace);			
			}		
			/* End of the form */

			/* The Treatment List */
			if ($showList == 1)
			{
				## Redirecting to list page
				$url = $this->redirectUrl('treatmentManager');
				header("location:".$url."&tid=".$tid);
			}
			/* End of The Treatment List */
		}
		/**
		 * Helper function used while creating treatment record, here it is the db insertion
		 *
		 * @access public
		 */

		function insertTreatmentRecord()
		{
			$treatment_id = 0;							
			$userInfo = $this->userInfo();
            $treatment_name = $this->value('treatment_name'); 
            
			/*if ($userInfo['usertype_id'] == 4)
			{   
				    $query = "SELECT treatment_name FROM treatment WHERE treatment_name = '{$treatment_name}' AND status <> '3' AND clinic_id IS NULL";
			}
			else 
			{
					$clinic_id = $this->clinicInfo('clinic_id');
					$query = "SELECT treatment_name FROM treatment WHERE treatment_name = '{$treatment_name}' AND status <> 3 AND  clinic_id = '$clinic_id' ";
			}
            
			$result = $this->execute_query($query);
			$noRows = $this->num_rows($result);									*/
			/*if ($noRows != 0)
			{
				//$error = 'Treatment name: exists in the system. Please choose another.';				
			}*/
			//else 
			{	
				$insertArr = array(
									'treatment_name' => $this->value('treatment_name'),
                                    'treatment_tag' => $this->value('treatment_name'),
									'creation_date' => date('Y-m-d H:i:s',time()),
									'status'=>	$this->value('status')			
									);
				$result = $this->insert('treatment',$insertArr);	
				$treatment_id = $this->insert_id();							
				if (!$result)
				{
					$treatment_id = 0;	
				}
			}	
			return $treatment_id;			
		}
		/**
		 * Helper function used for db record insertion execution.
		 * This function is the customized version exclusively for treatment records insertion
		 *
		 * @access public
		 */

		function executeInsertQuery($tableName,$fieldArray,$flagAllFields = true)
		{
			$sql = NULL;
			if ($flagAllFields == true)		
			{
				$sql = "INSERT INTO ".$tableName." VALUES (".$fieldArray[0];
				for ($i = 1; $i<count($fieldArray);++$i)
				{
					$sql = $sql.",".$fieldArray[$i];
				}			
				$sql = $sql.")";
			}
			else 
			{
				$keys	= array();
				$keys = array_keys($fieldArray);
				$sql = "INSERT INTO ".$tableName."(".$keys[0];
				for ($i = 1; $i < count($fieldArray); ++$i)
				{
					$sql = $sql.",".$keys[$i];
				}
				$sql = $sql.") VALUES (".addslashes($fieldArray[$keys[0]]);
				for ($i = 1; $i<count($fieldArray);++$i)
				{
					$sql = $sql.",".addslashes($fieldArray[$keys[$i]]);
				}
				$sql = $sql.")";
              }	
			$result = $this->execute_query($sql);	
			return $result;
		}
		/**
		 * Helper function used for db record updation execution.
		 * This function is the customized version exclusively for treatment records updation
		 *
		 * @access public
		 */

		function updateTreatmentRecord($tableName,$updateFieldsArray,$condition = "")
		{
			$sql = NULL;
			$keys	= array();		
			$keys = array_keys($updateFieldsArray);			
			$sql = "UPDATE ".$tableName." SET ".$keys[0]." = ".addslashes($updateFieldsArray[$keys[0]]);
			for ($i=1;$i<count($updateFieldsArray);++$i)
			{
				$sql = $sql.",".$keys[$i]." = ".addslashes($updateFieldsArray[$keys[$i]]);
			}
			// also the conditionif any				
			$sql = $sql.$condition;		
			$result = $this->execute_query($sql);
			return $result;
		}
		/**
		 * Helper function used for treatment deletion.
		 * This function is different from normal deletion functionality in the sense
		 * that its a conditional deletion and not the application functionality
		 * This is executed if the program fails in insert/edit treatment record, due to file upload error
		 *
		 * @access public
		 */
		function deleteBadTreatmentRecord($treatment_id)
		{		
			$sql = NULL;
			$sql = "DELETE FROM treatment WHERE treatment_id = ".$treatment_id;		
			$result = $this->execute_query($sql);		
		}
		/**
		 * Functionality for treatment deletion
		 *
		 * @access public
		 */
		function deleteTreatment()
		{
			/*
				Use to delete the treatment, treatment is not deleted from the database 
				only status field value is changed for the particular treatment
			*/
			$userInfo = $this->userInfo();
			if (!$userInfo)
			{
				header("location:index.php?sysAdmin");
			}
			else 
			{	
				$treatment_id = (int) $this->value('id');
				$allowFlag = 1;
				$queryTreatment = "SELECT * FROM treatment WHERE treatment_id = '$treatment_id'";
				$resultTreatment = $this->execute_query($queryTreatment);
				$row = $this->fetch_array($resultTreatment);
				$clinic_id = $this->clinicInfo('clinic_id');			
				if ($userInfo['usertype_id'] == 4)
				{
					if (!empty($row['clinic_id'])) {
						$allowFlag = 0;						
					}				
				}
				else if ($userInfo['usertype_id'] == 2)
				{
					if (empty($row['clinic_id']) || ($clinic_id != $row['clinic_id']))
					{
						$allowFlag = 0;						
					}
				}							
				if (1 == $allowFlag) 
				{					
					// only right user has access to delete the treatment				
					$queryUpdate = "UPDATE treatment SET status = 3 WHERE treatment_id = ". $treatment_id;
					$this->execute_query($queryUpdate);	
					$url = $this->redirectUrl('treatmentManager');
					header("location:".$url);					
				}	
				else
				{
					unset($_GET['id']);	
					header("location:index.php");
				}	
			}		
		}

		/**
		 * Functionality for treatment activation, that is to activate the treatment which was inactive earlier.
		 *
		 * @access public
		 */

		function activeTreatment()
		{
			$userInfo = $this->userInfo();
			if (!$userInfo)
			{
				header("location:index.php?sysAdmin");
			}
			else 
			{	
				$treatment_id = (int) $this->value('id');				
				$allowFlag = 1;
				$queryTreatment = "SELECT * FROM treatment WHERE treatment_id = '$treatment_id'";
				$resultTreatment = $this->execute_query($queryTreatment);
				$row = $this->fetch_array($resultTreatment);
				$clinic_id = $this->clinicInfo('clinic_id');			
				if ($userInfo['usertype_id'] == 4)
				{
					if (!empty($row['clinic_id'])) {
						$allowFlag = 0;						
					}				
				}
				else if ($userInfo['usertype_id'] == 2)
				{
					if (empty($row['clinic_id']) || ($clinic_id != $row['clinic_id']))
					{
						$allowFlag = 0;						
					}
				}									
				if (1 == $allowFlag) 
				{					
					// only right user has access to activate the treatment				
					$queryUpdate = "UPDATE treatment SET status = 1 WHERE treatment_id = ". $treatment_id;
					$this->execute_query($queryUpdate);	

					$url = $this->redirectUrl('treatmentManager');
					header("location:".$url);
				}	
				else
				{
					unset($_GET['id']);	
					header("location:index.php?action=treatmentManager");
				}	
			}		
		}
		/**
		 * Functionality for treatment inactivation, that is to inactivate the treatment which is active.
		 *
		 * @access public
		 */
		function inactiveTreatment()
		{
			/*
				Use to inactivate the treatment
			*/
			$userInfo = $this->userInfo();
			if (!$userInfo)
			{
				header("location:index.php?sysAdmin");
			}
			else 
			{	
				$treatment_id = (int) $this->value('id');
				//extra precaution check if user has access to inactive the treatment				
				$allowFlag = 1;
				$queryTreatment = "SELECT * FROM treatment WHERE treatment_id = '$treatment_id'";
				$resultTreatment = $this->execute_query($queryTreatment);
				$row = $this->fetch_array($resultTreatment);
				$clinic_id = $this->clinicInfo('clinic_id');			
				if ($userInfo['usertype_id'] == 4)
				{
					if (!empty($row['clinic_id'])) {
						$allowFlag = 0;						
					}				
				}
				else if ($userInfo['usertype_id'] == 2)
				{
					if (empty($row['clinic_id']) || ($clinic_id != $row['clinic_id']))
					{
						$allowFlag = 0;						
					}
				}			
				if (1 == $allowFlag) 
				{					
					// only the right user has access to inactive the treatment				
					$queryUpdate = "UPDATE treatment SET status = 2 WHERE treatment_id = ". $treatment_id;
					$this->execute_query($queryUpdate);	
					//header("location:index.php?action=treatmentManager");
					$url = $this->redirectUrl('treatmentManager');
					header("location:".$url);
				}	
				else
				{
					unset($_GET['id']);	
					header("location:index.php?action=treatmentManager");
				}	
			}		
		}

		/**
		 * Helper function for file upload (treatment images/videos files)
		 *
		 * @access public
		 */
		function handleFileUpload($treatment_id)
		{
			$ins2 = null;
			// check file upload
			$this->uploaded = false;
			$this->is_valid_file('pic1');
			$this->is_valid_file('pic2');
			$this->is_valid_file('pic3');
			$this->is_valid_file('video');
		
			if (!empty($this->invalid))
			{
				$msg = $this->invalid;
			}
			else 
			{
				if($this->uploaded)
				{	
					$pic1 = $this->do_file_upload('pic1', $treatment_id);
					$pic2 = $this->do_file_upload('pic2', $treatment_id);
					$pic3 = $this->do_file_upload('pic3', $treatment_id);	
					$video = $this->do_file_upload('video', $treatment_id);				
					$ins2 = array(
						//'treatment_id' => $treatment_id,
						'pic1' => ($pic1 == false)? "":$pic1,
						'pic2' =>  ($pic2 == false)? "":$pic2,
						'pic3' =>  ($pic3 == false)? "":$pic3,
						'video' => ($video == false)? "":$video
					);					
				}
				else 
				{
					//error redirect to create treatment form page
					$ins2 = null;
				}
			}
			return $ins2;
		}

		

		/**

		 * Displays the categories for treatment in a popup window

		 *

		 * @access public

		 */

		

		function PopupCategoryList()
        {
			// To save main category.
            $arrayCategories = null;
            // Check treatment id exist or not in the request.
			if ($this->value('id') != '')
			{
				$treatment_id = $this->value('id');
				$replace['queryStrTreatment'] = "&id=".$treatment_id;

				//get all the category ids for this treatment_id from treatment_category table
				$sql = "SELECT category_id FROM treatment_category WHERE treatment_id = '".$treatment_id."'";
				$result = $this->execute_query($sql);
				while($row = $this->fetch_array($result))
				{
					$arrayCategories[] = $row['category_id'];					
				}
			}
			else 
			{
				$replace['queryStrTreatment'] = "";		
				if (isset($_SESSION['catArr']))
				{
					$arrayCategories = $_SESSION['catArr'];	
				}
			}
			$where = " parent_category_id = 0 ";
			$result = $this->select($this->config['table']['category'],"","*",$where,"category_name");
			if(is_resource($result)){
				while($row = $this->fetch_array($result)){
					$replace['categoryList'] .=  $this->build_template($this->get_template("treatmentCategoryMainStart"),$row);
					$where = " parent_category_id = {$row['category_id']} AND status = 1";
					$result1 = $this->select($this->config['table']['category'],"","*",$where);	
                    $cnt = 1;
                    if(!(@mysql_num_rows($result1) > 0) ){
                        $replace['categoryList'] .= "<td colspan='8' align='left' >No child categroy</td>";
                        $replace['categoryList'] .=  "</tr><tr>";
                    }
					while($row1 = $this->fetch_array($result1))
					{
						if ($arrayCategories != null) 
						{
							if (in_array($row1['category_id'],$arrayCategories )) 
							{
								$replace['categoryList'] .=  $this->build_template($this->get_template("treatmentCategoryChildChecked"),$row1);
							}	
							else 
							{
								$replace['categoryList'] .=  $this->build_template($this->get_template("treatmentCategoryChild"),$row1);		
							}							
						}
						else 
						{
							$replace['categoryList'] .=  $this->build_template($this->get_template("treatmentCategoryChild"),$row1);
						}
                        if( $cnt % 4 == 0) {
                            $replace['categoryList'] .=  "</tr><tr>";
                        }
                        $cnt++;
					}
					$replace['categoryList'] .=  $this->build_template($this->get_template("treatmentCategoryMainEnd"));
				}
			}		

			//$replace['header'] = $this->build_template($this->get_template("header"));
			//$replace['footer'] = $this->build_template($this->get_template("footer"));		
			$replace['body'] = $this->build_template($this->get_template("treatmentCategoryListPopup"),$replace);
			$replace['browser_title'] = "Tx Xchange: Category List";
			$this->output = $this->build_template($this->get_template("main"),$replace);
		}

		

		/**

		 * Sets the category(s) selected for treatment in a popup window

		 *

		 * @access public

		 */

		

		function setCategories()

		{

			if ($this->value('id') != '')

			{

				//update categories

				 $treatment_id = $this->value('id');

				//delete from treatment_category table

				$sql = "DELETE FROM treatment_category WHERE treatment_id = ".$treatment_id;

				$this->execute_query($sql);				

				

				//insert categories						

				if (!empty($_POST['selected_cat']))

				{

					$tableName = "treatment_category";

					for($i=0;$i<count($_POST['selected_cat']);++$i)

					{

						$fieldArray = array(

										"treatment_id" => $treatment_id,

										"category_id" => $_POST['selected_cat'][$i],

										"creation_date" => date('Y-m-d H:i:s',time())						

										);

										

						//$result = $this->executeInsertQuery($tableName,$fieldArray,false);

						$result = $this->insert($tableName,$fieldArray);

						

					}						

					

				}

				

				$str = "<script language='JavaScript' type='text/javascript'>

						window.close();
                        
						//window.opener.location.reload(true);

					</script>";

			}

			else 

			{

				$_SESSION['catArr'] = $_POST['selected_cat'];	

				$str = "<script language='JavaScript' type='text/javascript'>

						window.close();

					</script>";	

								

			}

			

			echo $str;

			//unset($_SESSION['catArr']);

		}


		/**

		 * Helper function for validating treatment fields

		 *

		 * @access public

		 */

		

		function validateCreateTreatement(){

			$error = array();			

			if(trim($this->value('treatment_name')) == "" ){

				$error[] = "Please Enter Your Treatment Name.";

			}

			if(count($error) > 0 ){

				$error = $this->show_error($error);

			}

			else{

				$error = "";

			}

			return $error;	

		}		

		/**

		 * Customized function for creating HTML input checkbox in HTML form

		 *

		 * @access public

		 */

		function buildInputCheckBox($arrCheckBox,$inputName, $value)
		{

			$noElements = count($arrCheckBox); 

			

			$inputCheckBox = "";

			

			for($i=0;$i<$noElements;++$i)

			{

				$inputCheckBox.= "<input type='checkbox' name='".$inputName."[]' " .$arrCheckBox[$i]['extra']. " value='".$arrCheckBox[$i]['value']."'";

				if (is_array($value))

				{

					$inputCheckBox.= in_array($arrCheckBox[$i]['value'],$value) ? "checked='checked'":"";	

				}				

				

				$inputCheckBox.= " /> &nbsp;<label>". $arrCheckBox[$i]['lblName']."</label>&nbsp";		

			}

			

			return $inputCheckBox;

			

		}

		/**
		 * Customized function for creating HTML input radiobox in HTML form
		 *
		 * @access public
		 */
		function buildInputRadioBox($arrRadioBox,$inputName,$value)
		{
			$noElements = count($arrRadioBox); 
			$inputRadioBox = "";
			for($i=0;$i<$noElements;++$i)
			{
				$inputRadioBox.= "<input type='radio' name='".$inputName."' " .$arrRadioBox[$i]['extra']. " value='".$arrRadioBox[$i]['value']."'";
				if ($arrRadioBox[$i]['value'] == $value)
				{
					$inputRadioBox.= "checked='checked'";	
				}				
				$inputRadioBox.= " /> &nbsp;<label>". $arrRadioBox[$i]['lblName']."</label>&nbsp";		
			}
			return $inputRadioBox;
		}

		/**
		 * Validates the complete treatment form fields filled in by user while create/edit treatment
		 *
		 * @access public
		 */
		function validateFormTreatment($uniqueId = null)
		{
			$userInfo = $this->userInfo();
			$objValidationSet = new ValidationSet();
            $allowchar=array('0'=>'@','1'=>'.','2'=>'_','3'=>'-','4'=>'"');	
            $objValidationSet->addValidator(new  StringMinLengthValidator('treatment_name', 1, "Treatment Name cannot be empty",$_POST['treatment_name']));
            //$objValidationSet->addValidator(new  StringMinLengthValidator('treatment_name', 1, "Video Name cannot be empty",$_POST['treatment_name']));	
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('treatment_name',$allowchar,"Please enter valid characters in treatment name",$this->value('treatment_name')));
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('instruction',null,"Please enter valid characters in instruction",$this->value('instruction')));
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('benefit',null,"Please enter valid characters in benefit",$this->value('benefit')));
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('sets',null,"Please enter valid characters in sets",$this->value('sets')));
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('reps',null,"Please enter valid characters in reps",$this->value('reps')));
			$objValidationSet->addValidator(new AlphanumericOnlyValidator('hold',null,"Please enter valid characters in hold",$this->value('hold')));

			if ($uniqueId == null)
			{
				if ($userInfo['usertype_id'] == 4)
				{
					$treatment_name = $this->value('treatment_name'); 
                    //$queryTreatmentName = "SELECT treatment_name FROM treatment WHERE treatment_name = '{$treatment_name}' AND status <> '3' AND clinic_id IS NULL";
				}
				else 
				{
					$treatment_name = $this->value('treatment_name'); 
                    $clinic_id = $this->clinicInfo('clinic_id');
					//$queryTreatmentName = "SELECT treatment_name FROM treatment WHERE treatment_name = '{$treatment_name}' AND status <> 3 AND  clinic_id = '$clinic_id' ";
				}
				/*$result = $this->execute_query($queryTreatmentName);
				if ($this->num_rows($result) != 0)
				{
					$objValidationErr = new ValidationError('treatment_name',"Treatment Name : exists in the system. Please choose another.");
					$objValidationSet->addValidationError($objValidationErr);
				}*/
			}
			else 
			{
				if ($userInfo['usertype_id'] == 4)
				{
					$treatment_name = $this->value('treatment_name'); 
                    //$queryTreatmentName = "SELECT treatment_name FROM treatment WHERE treatment_name = '{$treatment_name}' AND treatment_id <> '$uniqueId' AND status <> '3' AND clinic_id IS NULL";
				}
				else 
				{
					$treatment_name = $this->value('treatment_name'); 
                    $clinic_id = $this->clinicInfo('clinic_id');
					//$queryTreatmentName = "SELECT treatment_name FROM treatment WHERE treatment_name = '{$treatment_name}' AND treatment_id <> '$uniqueId' AND status <> 3 AND  clinic_id = '$clinic_id' ";
				}
				//$result = $this->execute_query($queryTreatmentName);

				//if ($this->num_rows($result) != 0)
				//{
					//$objValidationErr = new ValidationError('treatment_name',"Treatment Name : exists in the system. Please choose another.");
					//$objValidationSet->addValidationError($objValidationErr);
				//}
			}	

			$objValidationSet->validate();			
			if ($objValidationSet->hasErrors())
			{
				$arrayFields = array("treatment_name","instruction","benefit","sets","reps","hold");
				for($i=0;$i<count($arrayFields);++$i)
				{
					$errorMsg = $objValidationSet->getErrorByFieldName($arrayFields[$i]);
					if ($errorMsg != "")
					{
						$this->error = $errorMsg."<br>";
						break;
					}
				}			
			}	
			else 
			{
				$this->error = "";	
			}
		}

		/* Extra Functions */
		/**
		 * Constructs a fragment of a "WHERE" clause that can be used to conduct a search.
		 * Note that this uses "LIKE" subclauses with wildcards, and will force a full table scan.
		 * 
		 *
		 * $searchMode must be ANY_WORD, ALL_WORDS, ALL_WORDS_IN_ORDER, or EXACT_PHRASE.
		 * $searchForWords and $inColumns can each be either an array or a comma-separated string.
		 */
		function makeSearch($searchMode, $searchForWords, $inColumns)
		{

			if (!is_array($searchForWords))

			{

				if ($searchMode == EXACT_PHRASE) $searchForWords = array($searchForWords);

				else $searchForWords = preg_split("/\s+/", $searchForWords, -1, PREG_SPLIT_NO_EMPTY);

			}

			elseif ($searchMode == EXACT_PHRASE && count($searchForWords) > 1)

				$searchForWords = array(implode(' ', $searchForWords));

	

			if (!is_array($inColumns))

				$inColumns = preg_split("/[\s,]+/", $inColumns, -1, PREG_SPLIT_NO_EMPTY);

	

			$where = '';

			foreach ($searchForWords as $searchForWord)

			{

				if (strlen($where)) $where .= ($searchMode == ALL_WORDS) ? ' AND ' : ' OR ';

	

				$sub = '';

				foreach ($inColumns as $inColumn)

				{

					if (strlen($sub)) $sub .= ' OR ';

					$sub .= "$inColumn LIKE '%" . $searchForWord . "%'"; //!! escaping?

				}

	

				$where .= "($sub)";

			}

	

			return $where;

		}	

		/**

		 * Displays confirm popup window for treatment

		 *

		 * @access public

		 */

		function confirmPopupTreatment(){

			$this->output = $this->build_template($this->get_template("confirmPopupTreatment"));

		}

		/**
		 * Displays confirm popup window for treatment photos deletion.
		 *
		 * @access public
		 */
		function confirmPopupTreatmentPhotoDelete(){

			$replace['pic']=$_GET['pic'];
			$replace['id']=$_GET['id'];
			$this->output = $this->build_template($this->get_template("confirmPopupTreatmentPhotoDelete"),$replace);
		}

		

		/**

		 * Displays confirm popup window for create treatment

		 *

		 * @access public

		 */

		

		function confirmPopupTreatmentCreate(){

			$this->output = $this->build_template($this->get_template("confirmPopupTreatmentCreate"));

		}		

				

		/**

		 * Helper function for file upload.

		 * This function checks if uploaded file is valid or invalid

		 *

		 * @access public

		 */

		

		function is_valid_file($fieldname)
		{

			

			$imageTypeAllowed = array(

									   "image/bmp",

		                               "image/gif",

		                               "image/jpeg",

		                               "image/pjpeg",

		                               "image/png",

		                               "image/x-png",

		                               "image/tiff",

		                               "image/x-tiff",

		                               "image/x-windows-bmp"			

										);

			

			if($this->have_uploaded_file($fieldname))

			{

				if($_FILES[$fieldname]['size'])

				{

					if($_FILES[$fieldname]['name'])

					{

						if ($fieldname != 'video') 

						{						

							if (in_array($_FILES[$fieldname]['type'],$imageTypeAllowed))

							{

								$this->uploaded = true;

							}

							else 

							{

								$this->invalid[$fieldname] = 'invalid image type';

							}

							

						}

						else

						{

							if(strripos($_FILES[$fieldname]['name'], '.flv') !== (strlen($_FILES[$fieldname]['name']) - 4)) $this->invalid[$fieldname] = 'invalid video type ('.$_FILES[$fieldname]['name'].') ('.strripos($_FILES[$fieldname]['name'], '.flv').')  ('.(strlen($_FILES[$fieldname]['name']) - 4).')';

							else $this->uploaded = true;

						}

					}

					else $this->invalid[$fieldname] = 'invalid file name';

				}

				else

				{

					$this->invalid[$fieldname] = 'invalid file size';

				}

			}

			

		}

		

		/**

		 * Helper function for file upload.

		 * This function checks if file has been uploaded at server or some error has occured

		 *

		 * @access public

		 */

		

		function have_uploaded_file($fieldName)
		{

			if (!isset($_FILES[$fieldName])) 

			{

				//echo 'no file called '.$fieldName."<br>";

				return false;

			}

			if (!is_uploaded_file($_FILES[$fieldName]['tmp_name']))

			{

				//echo 'no uploaded file '.$fieldName."<br>";

				return false;

			}

			return true;

		}

		

		/**

		 * Helper function for image file upload.

		 * This function resize the image file to the desired image dimensions specs

		 *

		 * @access public

		 */

		

		function resize_image($fileName, $imageName, $imagePath, $dimX, $dimY, $dimRestrict = NULL)
		{

			$imgInfo = getimagesize($imagePath.$imageName);

			$imgType = $imgInfo[2];

			$imageX = $imgInfo[0];

			$imageY = $imgInfo[1];

		

			// resize the image based on restrictions, otherwise we leave it alone

			if ($dimX != 0) $imageX = $dimX;

			if ($dimY != 0) $imageY = $dimY;

			if ($dimRestrict == RESTRICT_WIDTH){
				$imageY = $imgInfo[1] * ($imageX / $imgInfo[0]);
				 if( $imageY > 480 && ($fileName == "pic1.jpg" || $fileName == "pic2.jpg" || $fileName == "pic3.jpg") ){
                     $imageY = 480;
                 }
			}
			elseif ($dimRestrict == RESTRICT_HEIGHT){
				$imageX = $imgInfo[0] * ($imageY / $imgInfo[1]);
				if( $imageX > 640 && ($fileName == "pic1.jpg" || $fileName == "pic2.jpg" || $fileName == "pic3.jpg") ){
                     $imageX = 640;
                }
			}

		

			// create the destination image resource

			if (function_exists('imagecreatetruecolor'))

				$imageDest = imagecreatetruecolor($imageX, $imageY);

			else

				$imageDest = imagecreate($imageX, $imageY);

		

			// read the source image and use the correct function depending on format

			if ($imgType == IMAGETYPE_JPEG) $imageType = imagecreatefromjpeg($imagePath.$imageName);

			elseif ($imgType == IMAGETYPE_PNG) $imageType = imagecreatefrompng($imagePath.$imageName);

			elseif ($imgType == IMAGETYPE_GIF) $imageType = imagecreatefromgif($imagePath.$imageName);

		

			// gd 2.0 is needed for clean resampling, but sloppy resizing is always available

			if (function_exists('imagecopyresampled'))

				imagecopyresampled($imageDest, $imageType, 0,0,0,0, $imageX, $imageY, $imgInfo[0], $imgInfo[1]);

			else

				imagecopyresized($imageDest, $imageType, 0,0,0,0, $imageX, $imageY, $imgInfo[0], $imgInfo[1]);

		

			// make the image path and image name, strip file extension and change to .jpg

			$imageFileName = $imagePath.substr($fileName, 0, strlen($fileName) - 4).'.jpg';

		

			// check to see if the file is being overwritten

			$imgUpdated = false;

			if (file_exists($imageFileName)) $imgUpdated = true;

		

			// save the image to the image path

			if (!imageJPEG($imageDest, $imageFileName, 80)) return false;

		

			// return in an array: the filename, if the file was overwritten

			return array(substr($fileName, 0, strlen($fileName) - 4).'.jpg', $imgUpdated);

		}

		/**
		 * Helper function for file upload.
		 * This function actually uploads the file and stores it to the server dir
		 *
		 * @access public
		 */
		function do_file_upload($fieldname, $tid)
		{

			//file path

			            // echo"<pre>";
                     //  print_r($fieldname);
               
                       
			$fileDest = $_SERVER['DOCUMENT_ROOT'].$this->config['tx']['treatment']['media_url']. $tid . '/';			

			

			if(!file_exists($fileDest))

			{

				mkdir($fileDest);

				chmod($fileDest, 0777);

			}

			
                 
                                                // print_R($_FILES);
                                             
			$foo = new Upload($_FILES[$fieldname]); 

			

			$foo->file_overwrite = true;

			

			$newName = false;

			

			if ($foo->uploaded) 

			{

			  // save uploaded image with no changes			  

			  $foo->Process($fileDest);

			  

			  if ($foo->processed) 

			  { 			     

			  	$newName = $foo->file_dst_name;

			  	 

			  }

			  else 

			  {

				//error	

			  	$newName = false;

			  }

			}

			else 

			{

				//error

				$newName = false;				

			}			

			

			

			

			if(!$newName)

			{

			 return false;

			}

			

			$newFile = $fileDest . $newName;
                   //    print_r($fileDest);
                   //    echo"<br>";
                  //    print_r($newName);
                  // die;

			//thumbnail support

			if($fieldname != 'video')

			{

				$imgInfo = getimagesize($newFile);

				$imgType = $imgInfo[2];

				$imageX = $imgInfo[0];

				$imageY = $imgInfo[1];

			}

			

			// 640x480 or 360x480

			$restrictY = 80;

			$restrictX = 100;

			

			if($fieldname == 'pic1')

			{//Create thumbnails for pic1

				if($imageY > $imageX)

				{ // Portrait

					

					$aspect = RESTRICT_HEIGHT;

					//  		resize_image($fileName, $imageName, $imagePath, $dimX, $dimY, $dimRestrict = NULL)

					$imageDetails = $this->resize_image('thumb.jpg', $newName, $fileDest, 0, $restrictY, $aspect);

					

				}

				if($imageY < $imageX)

				{ // Landscape

					

					$aspect = RESTRICT_WIDTH;

					

					$imageDetails = $this->resize_image('thumb.jpg',  $newName, $fileDest, $restrictX, 0, $aspect);

				}

			}

			

			//Create resized images for the rest of the pics

			if($fieldname != 'video')

			{

				if($imageY > $imageX)

				{// Portrait

					$restrictY = 480;

					$aspect = RESTRICT_HEIGHT;

					//  		resize_image($fileName, $imageName, $imagePath, $dimX, $dimY, $dimRestrict = NULL)

					$imageDetails = $this->resize_image($fieldname.'.jpg', $newName, $fileDest,360, $restrictY, $aspect);

				}

				if($imageY < $imageX)

				{// Landscape

					$restrictX = 640;

					$aspect = RESTRICT_WIDTH;

					

					$imageDetails = $this->resize_image($fieldname.'.jpg',  $newName, $fileDest, $restrictX,480, $aspect);

				}

			}

			//end thumbnail support

		

			if($newName) return $newName;

			return false;

		}

		

		/**

		 * Helper function for file upload.

		 * This function verifies the image type (JPEG/PNG/GIF)

		 *

		 * @access public

		 */

		

		function verify_image($imageName)
		{

			$imgInfo = getImageSize($imageName);

			$imgType = $imgInfo[2];

		

			if ($imgType == IMAGETYPE_JPEG) return true;

			elseif ($imgType == IMAGETYPE_PNG) return true;

			elseif ($imgType == IMAGETYPE_GIF) return true;

		

			return false;

		}

		

		/**

		 * This is a click event function.

		 * A toggle link for show/hide files in edit treatment form		 

		 *

		 * @access public

		 */

		function maybeShowMedia($fieldName,$fieldValue,$id)
		{

			

			$fileName = false;

			

			if($id && !empty($fieldValue))

			{

				
                $ext=explode('.',$fieldValue);
                if ($fieldName != 'video') $fileName = $this->canonicalize($this->config['tx']['treatment']['media_url'] . $id . '/')  . "$fieldName.".strtolower($ext[1]);

				else $fileName = $this->canonicalize($this->config['tx']['treatment']['media_url'] . $id . '/') . $fieldValue;

				

				if($fileName)

				{

					return '&nbsp;<a id = "showhide" href="javascript:void(0)" onClick="toggleMediaDisplay(\'media_' . $fieldName . '\')">Show/Hide</a>&nbsp;&nbsp;<a id ="deletetreatment" href="index.php?action=confirmPopupTreatmentPhotoDelete&pic='.$fieldName.'&id='.$id.'" title="Delete Treatment Photo" rel="gb_page_center[600, 210]" >Delete</a>' .

						'<div id="media_' . $fieldName . '" style="display:none">' .

						(($fieldName != 'video') ? '<img src="' . $fileName . '" border="0" />' : '<b>Flash video:</b> '  . $fieldValue) . 

						'</div>';

				}

				else return '&nbsp;Missing File ('.$fileName.')';

			}

			else return '&nbsp;No File';

		}

		

		/**

		 * Helper function for file upload.

		 * This function return the server dir address where the uploaded file will be stored

		 *

		 * @access public

		 */

		function canonicalize($address)
		{

			$address = explode('/', $address);

			$keys = array_keys($address, '..');

		

			foreach($keys as $keypos => $key)

			{

				array_splice($address, $key - ($keypos * 2 + 1), 2);

			}

			$address = implode('/', $address);

			$address = str_replace('./', '', $address);

			return $address;

		}
        
        /**
        * @desc This function will show tags.
        */

        function showTag(){
            $replace['id'] = $this->value('id');
            $replace['reload'] = $this->reloadTag();
            $this->output =  $this->build_template($this->get_template('showTag'),$replace);
        }
        /**
        * @desc This function adds Tags in Tag table.
        * 
        */
         function insertTag($tag="",$id=""){
             
             if( $tag == ""){
                $tag = $this->value('tag');
             }
             if($id == ""){
                $id = $this->value('id');
             }
             $tag_array = explode(",",$tag);
             $user_id = $this->userInfo('user_id');
             
             if( strlen($tag) > 0 && ( is_numeric($id) && $id > 0) ){
                if( is_array($tag_array) && count($tag_array) > 0 ){
                 
                 foreach( $tag_array as $value ){
                     $query = "insert into tag (tag_name,treatment_id,user_id) values";
                     $value = trim($value);
                     if($value == ''){
                         continue;
                     }
                     if( $this->duplicate_tag($value,$id) === true ){
                         continue;
                     }
                     
                     $query .= "('{$value}','{$id}','{$user_id}');";
                    if(@mysql_query($query)){
                        echo "success";
                    }
                    else{
                        echo "failed";
                    }
                 }
                 if( is_numeric($id) && $id > 0 ){
                     $query = "update treatment as a inner join (SELECT GROUP_CONCAT( `tag_name` SEPARATOR ' ' ) as c_tag, `treatment_id`
                                FROM `tag` GROUP BY `treatment_id` having treatment_id = '{$id}' ) as b on b.treatment_id = a.treatment_id 
                                set a.treatment_tag = concat(a.treatment_name,' ',b.c_tag) where a.treatment_id = '{$id}'";
                     @mysql_query($query);
                 }
                }    
             }
             else{
                    echo "failed";
             }
             
         }
		 /**
         * @desc validate tags.
         */
         function duplicate_tag($tag,$id){
             $query = "select count(*) as tag_count from tag where tag_name = '{$tag}' and treatment_id = '{$id}' ";
             $result = @mysql_query($query);
             $row = @mysql_fetch_array($result);
             if($row['tag_count'] > 0){
                 return true;
             }
             else{
                 return false;
             }
         }
         /**
         * @desc Returns Tag list.
         * 
         */
         function reloadTag(){
             $id = $this->value('id');
             $user_type = $this->userInfo('usertype_id');
              if( (is_numeric($id) && $id > 0 && $user_type == '4') or  (is_numeric($id) && $id > 0 && $user_type == '2')){
             //if( is_numeric($id) && $id > 0 && $user_type == '4'  ){
                 $query = "select tag_id,tag_name from tag where treatment_id = '{$id}' order by tag_name ";
                 $result = @mysql_query($query);
                 $num = @mysql_num_rows($result);
                 
                 if( $num > 0 ){
                        //$height = $num <= 10?($num * 20):(11 * 20);
                        $overflow = $num <= 10?"":"height:200px;overflow-y:auto";
                        $this->output = "<div style='width:330px;margin:0px;' >
                                            <table border='0'>
                                            <tr>
                                                <td style='width:50px;' ><strong>#</strong></td>
                                                <td style='width:450px;' ><strong>Tag Name</strong></td>
                                                <td style='width:20px;' >&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                            </tr>
                                          </table>
                                          </div>
                                          <div style='width:330px;margin-left:0px;{$overflow}' >
                                          <table align='left' width='90%' border='0' >
                                          ";
                     $cnt = 1;
                     while( $row = @mysql_fetch_array($result) ){
                        $row['cnt'] = $cnt++;
                        $this->output .= $this->build_template($this->get_template('tagList'),$row); 
                     }
                     $this->output .= "</table></div>";
                     return $this->output;
                 }
             }
             
             return "";
             
         }
         /**
         * @desc Delete tag
         */
         function removeTag(){
             $id = $this->value('id');
             if( is_numeric($id) && $id > 0 ){
                $treatment_id = $this->get_field($id,'tag','treatment_id');
                $query = "DELETE FROM tag WHERE `tag_id` = '{$id}' LIMIT 1";
                @mysql_query($query);

                $query = "update treatment as a left join (SELECT GROUP_CONCAT( `tag_name` SEPARATOR ' ' ) as c_tag, `treatment_id`
                                FROM `tag` GROUP BY `treatment_id` having  treatment_id = '{$treatment_id}' ) as b on b.treatment_id = a.treatment_id 
                                set a.treatment_tag = CONCAT_WS(' ',a.treatment_name,b.c_tag)
                                where a.treatment_id = '{$treatment_id}' ";
                
                @mysql_query($query);
                 
             }  
         }
         /**
         * @desc Tag popup
         */
         function tagPopup(){
             $treatment_id = $this->value('id');
             $user_id = $this->userInfo('user_id');
             $replace['checked'] = '';
             if( is_numeric($treatment_id) && is_numeric($user_id) ){
                $query = "select count(*) as cnt from treatment_favorite where treatment_id = '{$treatment_id}' and user_id = '{$user_id}' ";
                $result = @mysql_query($query);
                if( $row = @mysql_fetch_array($result) ){
                    if($row['cnt'] > 0 ){
                        $replace['checked'] = 'checked';
                    }
                }
             }
             if($this->value('save') == 'save' ){
                 $favorite = $this->value('favorite');
                 $treatment_id = $this->value('id');
                 $tag = $this->value('tag');
                 if( strlen($tag) > 0 && is_numeric($treatment_id)){
                     $this->insertTag($tag,$treatment_id);
                 }
                 if( $this->userInfo('usertype_id') == 2 ){
                     if(is_numeric($favorite) && $favorite == '1' ){
                         $this->insertFavorite($treatment_id,$user_id);
                     }
                     else{
                         $this->deleteFavorite($treatment_id,$user_id);
                     }
                 }
             }
             $replace['id'] = $treatment_id;
             if( $this->userInfo('usertype_id') == 4 ){
                 $this->output = $this->build_template($this->get_template("tagPopupSystem"),$replace);
             }
             else{
                 $this->output = $this->build_template($this->get_template("tagPopup"),$replace);
             }
             
         }
         /**
         * @desc This function insert favorite treatment in treatment_favorite table.
         */
         function insertFavorite($treatment_id="",$user_id=""){
             if( is_numeric($treatment_id) && is_numeric($user_id) ){
                 $query = "select count(*) as cnt from treatment_favorite where treatment_id = '{$treatment_id}' and user_id = '{$user_id}' ";
                 $result = @mysql_query($query);
                 if($row = @mysql_fetch_array($result) ){
                     if( $row['cnt'] == 0 ){
                         $favorite_treatment_arr = array(
                                'treatment_id' => $treatment_id,
                                'user_id' => $user_id
                         );
                         $this->insert('treatment_favorite',$favorite_treatment_arr);
                     }
                 }
             }
             return "";
             
         }
         /**
         * @desc This function deletes favorite treatment from treatment_favorite table.
         */
         function deleteFavorite($treatment_id="",$user_id=""){
             if( is_numeric($treatment_id) && is_numeric($user_id) ){
                 $query = "delete from treatment_favorite where treatment_id = '{$treatment_id}' and user_id = '{$user_id}'  limit 1";
                 $result = @mysql_query($query);
             }
             return "";
             
         }
		/**
		 * Function to populate side bar
		 *
		 * @return side bar menu template
 		 * @access public			
		 */	
		function sidebar(){

			

			$userInfo = $this->userInfo();			

			

			if ($userInfo['usertype_id'] == 4) 

			{			

				$data = array(

					'name_first' => $this->userInfo('name_first'),

					'name_last' =>  $this->userInfo('name_last')

				);

				

				return $this->build_template($this->get_template("sidebar"),$data);

				

			}

			else if ($userInfo['usertype_id'] == 2) {

				
				//code for checking the trial period days left for Provider/AA
			    $freetrialstr=$this->getFreeTrialDaysLeft($this->userInfo('user_id'));
				$data = array(

					'name_first' => $this->userInfo('name_first'),

					'name_last' =>  $this->userInfo('name_last'),

					'sysadmin_link' => $this->sysadmin_link(),

					'therapist_link' => $this->therapist_link(),
					
					'freetrial_link' => $freetrialstr

				);



			return $this->build_template($this->get_template("sidebarThpst"),$data);

			}

			

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
        
        
         /*
         Function Name : checkFolderIsEmptyOrNot.
         Desc : used to check whether the folder is empty or not.
         Param : VARCHAR $folderName
         Return : void
         Access : public
         Author : Abhishek Sharma
         Created Date : 2 May 2011
         Organization : Hytech Professionals
         */
         
        function checkFolderIsEmptyOrNot ( $folderName ){
                           $files = array ();
                if ( $handle = opendir ( $folderName ) ) {
                    while ( false !== ( $file = readdir ( $handle ) ) ) {
                        if ( $file != "." && $file != ".." ) {
                            $files [] = $file;
                        }
                    }
                    closedir ( $handle );
                }
                return ( count ( $files ) > 0 ) ?  0: 1;
            }




         /*
         Function Name : empty_folder.
         Desc : used to make a folder empty.
         Param : VARCHAR $folder
         Return : void
         Access : public
         Author : Abhishek Sharma
         Created Date : 2 May 2011
         Organization : Hytech Professionals
         */

        function empty_folder($folder)
        { 
                          
           
           $d = dir($folder); 
            
            while (false !== ($entry = $d->read())) { 
            
                $isdir = is_dir($folder."/".$entry); 
                
                if (!$isdir and $entry!="." and $entry!="..") { 
                
                    unlink($folder."/".$entry); 
                    
                } elseif ($isdir  and $entry!="." and $entry!="..") { 
                
                    empty_folder($folder."/".$entry,$debug); 
                    
                    rmdir($folder."/".$entry); 
                    
                } 
            } 
            $d->close(); 
        } 
        
        
         /*
         Function Name : copydir.
         Desc : used to move directory contents in another directory.
         Param : VARCHAR $source
         Param : VARCHAR $destination
         Return : void
         Access : public
         Author : Abhishek Sharma
         Created Date : 3 May   2011
         Organization : Hytech Professionals
         */
         
        function copydir($source,$destination,$treatment_id)
        {
            
              //  print_r($source);
               // print_R($destination);
              
             if(!is_dir($source)){
             $oldumask = @umask(0); 
                @mkdir($source, 0777); // so you get the sticky bit set
                @chmod($source, 0777); 
                @umask($oldumask);
            }   
            if(!is_dir($destination)){
                
                
                
            $oldumask = @umask(0); 
            @mkdir($destination, 0777); // so you get the sticky bit set
            @chmod($destination, 0777); 
            @umask($oldumask);
            }
            $dir_handle = @opendir($source) or die("Unable to open");
            while ($file = readdir($dir_handle)) 
            {
            
            if($file!="." && $file!=".." && !is_dir("$source/$file"))
            copy("$source/$file","$destination/$file");
            }
            closedir($dir_handle);
            
           $a =  $this->ReadFolderDirectory($destination);
                  //  echo"<pre>";    
           //print_r($a); 
           
           
           
           foreach($a as $key=>$val){
           
               
               $exploded = explode('.',$val);
               
               //print_r( $exploded);
               if($exploded['0']=='pict1')
               {
                $mysql_query="update treatment set pic1='$val' where treatment_id='$treatment_id'";
                @mysql_query($mysql_query);
               }
                if($exploded['0']=='pict2')
               {
                 $mysql_query="update treatment set pic2='$val' where treatment_id='$treatment_id'";
                @mysql_query($mysql_query);
               }
               
               if($exploded['0']=='pict3')
               {
               $mysql_query="update treatment set pic3='$val' where treatment_id='$treatment_id'";
                 @mysql_query($mysql_query);
               }
               if(substr($exploded['0'], 0, 5) == 'video')
                {
                $fileNameParts = explode( ".", $val ); // seperate the name from the ext
                $fileExtension = end( $fileNameParts ); // part behind last dot
                $fileExtension = strtolower( $fileExtension );
                if($fileExtension=='flv')
                    $mysql_query="update treatment set video='$val',status='1' where treatment_id='$treatment_id'";
                @mysql_query($mysql_query);
                } 
                
	       if(substr($exploded['0'], 0, 7) == 'videosh')
                {
                      $mysql_querya="update treatment set original_video='$val' where treatment_id='$treatment_id'";        	
		              @mysql_query($mysql_querya);
                }            
               
           
           }

		$b =  $this->ReadFolderDirectory($source);
        foreach($b as $keya=>$vala)
		{
         if(substr($vala, 0, 5)=='video')
			{
		      unlink($_SERVER['DOCUMENT_ROOT'].'/'.$source."/".$vala);
             }
		}		
        }
        
         /*
         Function Name : ReadFolderDirectory.
         Desc : used to Read temporary Folder .
         Param : VARCHAR $dir
         Return : array $listDir
         Access : public
         Author : Abhishek Sharma
         Created Date : 6 May 2011
         Organization : Hytech Professionals
         */
         
         
         function ReadFolderDirectory($dir) 
         { 
                $listDir = array(); 
                if($handler = opendir($dir)) { 
                    while (($sub = readdir($handler)) !== FALSE) { 
                        if ($sub != "." && $sub != ".." && $sub != "Thumb.db") { 
                            if(is_file($dir."/".$sub)) { 
                                $listDir[] = $sub; 
                            }elseif(is_dir($dir."/".$sub)){ 
                                $listDir[$sub] = $this->ReadFolderDirectory($dir."/".$sub); 
                            } 
                        } 
                    }    
                    closedir($handler); 
                } 
                return $listDir;    
            } 

        
 /*
         Function Name : Convertvideo.
         Desc : used to convert video format in flv format by ffmpeg.
         Param : void
         Return : node
         Access : public
         Created Date : 14 Sep 2011      
         */
            
        function ConvertVideo()
        {
		$Priority = 1;
		echo $exec_string = "/usr/bin/php conversion.php " . " " . $_REQUEST['tid'] . " " . $_REQUEST['file'];
		$returnval = $this->run_in_background($exec_string, $Priority);
        }

	/*
         Function Name : run_in_background.
         Desc : used to process command execution in background.
         Param : $Command
	 Param : $Priority
         Access : public
         Created Date : 14 Sep 2011      
         */
	function run_in_background($Command, $Priority = 0)
	{
	    
	    if($Priority)
        	$PID = shell_exec("nohup nice -n $Priority $Command > /dev/null	& echo $!");
	    else
        	$PID = shell_exec("$Command > /dev/null & echo $!");
	    return($PID);
	}
  	
	function show_videomsg(){
            $this->output = $this->build_template($this->get_template('show_videomsg'),$replace);
    }    
        
        
}
            
            
    

	// creating object of this class

	$obj = new treatmentManager();

?>

