<?php	
    require_once("module/application.class.php");
  	class popup extends application{
		private $action;
		private $formArray;
		private $error;
		private $output;
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
				$str = "patient"; //default if no action is specified
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
			$str = $str."()";
			eval("\$this->$str;"); 
			$this->display();			
		}
		
		function ptInfo()
		{
						
			$replace['browserTitle'] = "PT Info Popup";
			$replace['mainRegion'] = $this->therapistInfo();										
			
			$this->output = $this->build_template($this->get_template("popuptemplate"),$replace);		
				
			
		}		
		

		function articlePreview()
		{
						
			$this->articleContent();												
			
		}	
		
		
		function articleContent()
		{
			//Grab article Id
			$articleId = $this->value('aid');
			$articleInfo;
			$userInfo;
			
			if(!empty($articleId))			
			{
				//Article data
				$query = "SELECT * FROM article WHERE article_id = '".$articleId."'";
				$result = $this->execute_query($query);
				$articleInfo = $this->fetch_array($result);
				
                $privateKey = $this->config['private_key'];
				$query = "SELECT *,
                          AES_DECRYPT(UNHEX(name_title),'{$privateKey}') as name_title, 
                          AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as name_first,
                          AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as name_last
                          FROM user WHERE user_id = '{$articleInfo['user_id']}'";
				$result = $this->execute_query($query);
				$userInfo = $this->fetch_array($result);
				
				
			}
			else
			{
				exit();
			}
			
			
			//switch content type
			
			switch($articleInfo['article_type'])
			{
								
				//LINK
				case 1:
				//article name
				//headline
				//URL link
						echo '<script language="JavaScript">';
						echo 'window.location = "'.$articleInfo['link_url'].'";';	
						echo '</script>';			
						break;
				
				//FILE
				case 2:
				//article name
				//headline
				//File Link
				$fileName = '/' . $articleInfo['file_url'];
				$ext = explode(".",$fileName);
				$extension = strtolower($ext[(count($ext) - 1)]);
				switch($extension)
				{
					case 'jpg':
								$file = '<img src="'.$this->get_article_path_for_database($articleInfo['article_id'],'display'). '" border="0" />';
								echo $file;
								break;
				
					case 'pdf':
				    case 'html':
                    case 'htm':
                    case 'txt':
                    case 'bmp':
                   	case 'gif':
                   	case 'png':
                        $file = $this->get_article_path_for_database($articleInfo['article_id'],'display');
                        echo '<script language="JavaScript">';
                        echo 'window.location = "'.$file.'";';
                        echo '</script>'; 
                    break;			
								
				default:
				$file = $this->get_article_path_for_database($articleInfo['article_id'],'display');
				$file =ltrim($file,'/' );
				if (file_exists($file)) {
					
                 header('Content-Description: File Transfer');
                 header('Content-Type: application/octet-stream');
                 header('Content-Disposition: attachment; filename='.basename($file));
			     header('Content-Transfer-Encoding: binary');
			     header('Expires: 0');
			     header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			     header('Pragma: public');
			     header('Content-Length: ' . filesize($file));
			     ob_clean();
			     flush();
			     readfile($file);
			     exit;
                 }
				/*echo '<script language="JavaScript">';
				echo 'window.location = "'.$file.'";';
				echo '</script>';*/	
				break;
				
				}
				
				
				//UNKNOWN
				default:
				
				break;
			}
			
			
		}
		
		
		/**
		 * This method is called to display Treatment plans.
		 * 
		 * @access public
		 * 
		 */
	function planViewer()
	{						
	    $NO_RTA = true;
	    $id = $this->value('id');			
            $usertype = $this->userInfo('usertype_id');
            $replace['flag'] = 0;
            if( $usertype == '1' ){
                $replace['userId'] = $this->userInfo('user_id');
                $replace['flag'] = 1;
            }
            
			$replace['browserTitle'] = "Plan Viewer";
			$replace['planId'] = $id;												
			$this->output = $this->build_template($this->get_template("planviewertemplate"),$replace);			
			
	}
        /**
        * This method inserts record in plan view table.	
        */
        function insert_record_plan_view(){
            $plan_id = $this->value('plan_id');
            $user_id = $this->value('user_id');
            if( is_numeric($plan_id) && is_numeric($user_id) ){
                $arr_plan_view = array(
                    'plan_id' => $plan_id,
                    'user_id' => $user_id
                );
                $this->insert("plan_view", $arr_plan_view);
            }
        }
		/**
		 * This method is called to display Treatment.
		 * 
		 * @access public
		 * 
		 */
		function treatmentViewer()
		{						
			$NO_RTA = true;
			$id = $this->value('id');			

			$replace['browserTitle'] = "Treatment Viewer";
			$replace['planId'] = $id;												
			$replace['rand']=rand(5, 15);
			$_SESSION['planViewType'] = "treatment";
			
			$this->output = $this->build_template($this->get_template("planviewertemplate"),$replace);			
			
		}
		
		function planPrint()
		{			
			$NO_RTA = true;
			$id = $this->value('id');	
			
			if(!empty($id))
			{
				$id = $this->value('id');
				$arr = explode("_",$id);
				if(in_array("t",$arr)){
					$id = $arr[0];
					$replace['browserTitle'] = "Print Treatment Content";
					//$replace['planId'] = $id;												
					$replace['planPrintView'] = $this->treatmentPrintView($id);
				}
				else{
					$replace['browserTitle'] = "Print Treatment Plan";
					//$replace['planId'] = $id;												
					//$replace['planPrintView'] = $this->planPrintView();
                    
                    $replace['print_treatment'] = $this->planPrintView('treatments');
                    $this->output = $this->build_template($this->get_template("printTemplatePlan"),$replace);
                    return;    
				}
			}
			$this->output = $this->build_template($this->get_template("popuptemplate"),$replace);	
		}
		function treatmentPrintView($id)
		{	
			
			$planPrintView = "";
			
			if(!empty($id))
			{
				$id = trim($id);
				
				$treatmentSql = 'SELECT treatment_id, treatment_name, pic1, pic2, pic3, video, 
				sets, reps, hold, instruction, benefit, lrb FROM treatment where treatment_id  = ' . $id . ' '; 
				
			//$siteCfg['db']['debug_sql'] = true;
				$res = $this->execute_query($treatmentSql);
				
				if($res !== false && ($trmnt_total = $this->num_rows($res)) > 0)
				{
					$trmnt_current = 1;
					// loop throught treatments, one per page
					while($r = $this->fetch_array($res))
					{
						// do page header
						$planPrintView .= '<div class="head"><img src="images/print_head.gif"  alt="Tx Xchange" class="headLogo" /></div>';
						
						// plan info
						/* $planPrintView .= '<div class="plan_info"><p>'.
							'Plan:' . $r['plan_name'] . 
							'<br/>Assigned To:' . $r['p_name_first'] .'&nbsp;'.$r['p_name_last'] .
							'<br/>Assigned By:' . $r['t_name_first'] .'&nbsp;'.$r['t_name_last'] .
							'</p></div>';
						*/
						$planPrintView .= '<div class="treatments">'.
							'<h1>' . $r['treatment_name'] . ' <small>(Treatment ' . $trmnt_current . ' of ' . $trmnt_total . ')</small></h1>';
						
						$planPrintView .= '<table width="800" cellpadding="0" cellspacing="0" border="0">'.
							'<tr><td>' . ((!empty($r['pic1'])) ? $this->makeImageTag($this->canonicalize($this->config['tx']['treatment']['media_url'] . $r['treatment_id']) . '/pic1.jpg') : '&nbsp;') . '</td>'.
							'<td rowspan="3" valign="top">';
							
						// instructions
						if(!empty($r['instruction'])) {
							$planPrintView .= '<p>' . $r['instruction'] . '</p>';
						}
						
						// sets, reps, hold
						if(!empty($r['sets'])){
							$planPrintView .= '<p>Sets: ' . $r['sets'] . '</p>';
						}
						
						if(!empty($r['reps'])) {
							$planPrintView .= '<p>Reps: ' . $r['reps'] . '</p>';
						}
						
						if(!empty($r['hold'])) {
							$planPrintView .= '<p>Hold: ' . $r['hold'] . '</p>';
						}
                        
						if(!empty($r['lrb'])) {
                            $planPrintView .= '<p>Side: ' . $this->config['lrb'][$r['lrb']] . '</p>';
                        }
                        
						if(!empty($r['benefit'])) {
							$planPrintView .= '<p>Benefit: ' . $r['benefit'] . '</p>';
						}
						
						$planPrintView .= '</td></tr>'.
							'<tr><td>'.((!empty($r['pic2'])) ? $this->makeImageTag($this->canonicalize($this->config['tx']['treatment']['media_url'] . $r['treatment_id']) . '/pic2.jpg') : '&nbsp;').'</td></tr>'.
							'<tr><td>'.((!empty($r['pic3'])) ? $this->makeImageTag($this->canonicalize($this->config['tx']['treatment']['media_url'] . $r['treatment_id']) . '/pic3.jpg') : '&nbsp;').'</td></tr>'.
							'</table>';
						if( $trmnt_current  < $this->num_rows($res)  ){
							$planPrintView .= '</div><div class="foot">&copy; 2010 Tx Xchange</div>';
						}
						else{
							$planPrintView .= '</div><div >&copy; 2010 Tx Xchange</div>';
						}
							
						$trmnt_current++;
					}
				}
				else
				{
					echo 'Couldn\'t find plan with id ' . $id . '.';
				}
			}	
			
			return $planPrintView;
		
		}
        function planPrintView_latest($print_info){
            $id = $this->value('id');
            $planPrintView = "";
            if(!empty($id))
            {
                $id = trim($id);
                // Get plan name, Assigned by and Assigned to info.
                if( $print_info == "plan_info" ){
                    $plan_info = "";
                    $query = "select * from plan where plan_id = '{$this->escape($id)}' ";
                    $result = @mysql_query($query);
                    while( $row = @mysql_fetch_array($result) ){
                        //print_r($row);                    
                        if( $row['plan_name'] != "" ){
                              $plan_info = "<tr><td class='plan_top'>Plan : {$row['plan_name']}</td></tr>";
                        }
                        if( is_numeric($row['patient_id']) ){
                            $patient_name = $this->userInfo("name_title",$row['patient_id']);
                            $patient_name .= $this->userInfo("name_first",$row['patient_id']);
                            $patient_name .= $this->userInfo("name_last",$row['patient_id']);
                            $plan_info .= "<tr><td class='plan_top'>Assigned to :{$patient_name}</td></tr>";
                        }
                        if( is_numeric($row['user_id']) ){
                            $therapist_name = $this->userInfo("name_title",$row['user_id']);
                            $therapist_name .= $this->userInfo("name_first",$row['user_id']);
                            $therapist_name .= $this->userInfo("name_last",$row['user_id']);
                            $plan_info .= "<tr><td class='plan_top'>Assigned by : {$therapist_name}</td></tr>";
                        }
                    }
                    return $plan_info;
                }
                // Get plan info.
                if( $print_info == "treatments" ){
                    $query = " select pt.plan_id, pt.treatment_id, pt.instruction, pt.sets, pt.reps, pt.hold, pt.lrb, pt.benefit, t.treatment_name, t.pic1, t.pic2, t.pic3   from plan_treatment pt 
                                inner join treatment t on t.treatment_id = pt.treatment_id
                                where pt.plan_id = '{$this->escape($id)}' order by treatment_order ";
                    
                    $result = @mysql_query($query);
                    $total = @mysql_num_rows($result);
                    $print_page = "";
                    $pagebreak = 0;
                    $flag = 0;
                    while( $row = @mysql_fetch_array($result)){
                        $data = array(
                            'treatment_id' => $row['treatment_id'],
                            'treatment_name' => $row['treatment_name'],
                            'count' => ++$cnt,                                                                           
                            'total' => $total,
                            'instruction' => $row['instruction'],
                            'benefit' => $row['benefit'],
                            'sets' => $row['sets'] != "" ? "Sets: {$row['sets']}":"",
                            'reps' => $row['reps'] != "" ? "Reps: {$row['reps']}":"",
                            'hold' => $row['hold'] != "" ? "Hold: {$row['hold']}":"",
                            'lrb' => $row['lrb'] != "" ? "Side: {$this->config['lrb'][$row['lrb']]}":"",
                            'thumb' => $row['pic1'] != ""?'<img src="asset/images/treatment/'.$row['treatment_id'].'/thumb.jpg"  alt=""  />':"",
                            'thumb2' => $row['pic2'] != ""?'<img src="asset/images/treatment/'.$row['treatment_id'].'/thumb2.jpg"  alt=""  />':"",
                            'thumb3' => $row['pic3'] != ""?'<img src="asset/images/treatment/'.$row['treatment_id'].'/thumb3.jpg"  alt=""  />':"",
                        );
                        
                        // Create thumnail thumb, if do not exist.
                        if( !empty($row['pic1']) ){
                            $this->create_thumbnail_pic($row['treatment_id'],$row['pic1'],'pic1');
                        }
                        
                        
                        // Create thumnail thumb2, if do not exist.
                        if( !empty($row['pic2']) ){
                            $this->create_thumbnail_pic($row['treatment_id'],$row['pic2'],'pic2');
                        }
                          
                        // Create thumnail thumb3, if do not exist.
                        if( !empty($row['pic3']) ){
                            $this->create_thumbnail_pic($row['treatment_id'],$row['pic3'],'pic3');
                        }
                        
                        if(!(($cnt - 1) % 5)){
                            $data['plan_info'] = $this->planPrintView('plan_info');
                            $print_page .= $this->build_template($this->get_template("header"),$data);
                            $pagebreak = ($cnt -1) + 5;
                            $flag = 1;
                        }
                        
                        $print_page .= $this->build_template($this->get_template("treatmentRecord"),$data);
                        
                        if( $pagebreak == $cnt ){
                            if( $cnt < $total ){
                                $page_break['page_break'] = 'class="foot"';
                            }
                            else{
                                $page_break['page_break'] = "";
                            }
                            $print_page .= $this->build_template($this->get_template("footer"),$page_break);
                            $flag = 0;
                        }
                    }
                    if( $flag == 1){
                         if( $cnt < $total ){
                                $page_break['page_break'] = 'class="foot"';
                            }
                         else{
                                $page_break['page_break'] = "";                                
                         }
                         $print_page .= $this->build_template($this->get_template("footer"),$page_break);
                    }
                    return $print_page;
                }
            }
        }
        
        function create_thumbnail_pic($tid,$newName,$fieldname){
            //file path
            $fileDest = $_SERVER['DOCUMENT_ROOT'].$this->config['tx']['treatment']['media_url']. $tid . '/';
                        
            $newFile = $fileDest . $newName;
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

            if($fieldname == 'pic1' || $fieldname == 'pic2' || $fieldname == 'pic3')
            {//Create thumbnails for pic1
                
                if( $fieldname == 'pic1' ){
                    $thumbName = "thumb.jpg";
                }
                elseif($fieldname == 'pic2'){
                    $thumbName = "thumb2.jpg";
                }
                elseif($fieldname == 'pic3'){
                    $thumbName = "thumb3.jpg";
                }
                
                if($imageY > $imageX)
                { // Portrait
                    $aspect = RESTRICT_HEIGHT;
                   //          resize_image($fileName, $imageName, $imagePath, $dimX, $dimY, $dimRestrict = NULL)
                   $imageDetails = $this->resize_image($thumbName, $newName, $fileDest, 0, $restrictY, $aspect);
                }

                if($imageY < $imageX)
                { // Landscape
                    $aspect = RESTRICT_WIDTH;
                    $imageDetails = $this->resize_image($thumbName,  $newName, $fileDest, $restrictX, 0, $aspect);
                }
            }
        }
        function resize_image($fileName, $imageName, $imagePath, $dimX, $dimY, $dimRestrict = NULL){
            $imgInfo = getimagesize($imagePath.$imageName);
            $imgType = $imgInfo[2];
            $imageX = $imgInfo[0];
            $imageY = $imgInfo[1];

            // resize the image based on restrictions, otherwise we leave it alone
            if ($dimX != 0) $imageX = $dimX;
            if ($dimY != 0) $imageY = $dimY;
            if ($dimRestrict == RESTRICT_WIDTH) $imageY = $imgInfo[1] * ($imageX / $imgInfo[0]);
            elseif ($dimRestrict == RESTRICT_HEIGHT) $imageX = $imgInfo[0] * ($imageY / $imgInfo[1]);

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
        
        
		function planPrintView()
		{	
			$id = $this->value('id');
			$planPrintView = "";
			
			if(!empty($id))
			{
				$id = trim($id);
				$privateKey = $this->config['private_key'];
				$sqlField = " AES_DECRYPT(UNHEX(ut.name_first),'{$privateKey}') as t_name_first, AES_DECRYPT(UNHEX(ut.name_last),'{$privateKey}') as t_name_last, AES_DECRYPT(UNHEX(up.name_first),'{$privateKey}') as p_name_first,AES_DECRYPT(UNHEX(up.name_last),'{$privateKey}') as p_name_last ,".
							' pt.treatment_id, t.treatment_name, pt.treatment_order,t.pic1, t.pic2, t.pic3, t.video, '.
							' pt.sets, pt.reps, pt.hold, pt.lrb, pt.benefit, pt.instruction '; 
				
				$sqlTable = ' plan p '.
				' inner join plan_treatment pt on pt.plan_id = p.plan_id '.
				' inner join treatment t on t.treatment_id = pt.treatment_id '.
				' left join user up on p.patient_id = up.user_id '.
				' left join user ut on p.user_id = ut.user_id ' ;
				
				$sqlWhere =  ' p.plan_id  = ' . $this->escape($id) . ' ' . 	'ORDER BY pt.treatment_order';
				$sqlOrder = "";
				
			//$siteCfg['db']['debug_sql'] = true;
				$res = $this->execute_query("SELECT $sqlField FROM $sqlTable WHERE $sqlWhere $sqlOrder");
				
				if($res !== false && ($trmnt_total = $this->num_rows($res)) > 0)
				{
					$trmnt_current = 1;
					// loop throught treatments, one per page
					// do page header
						//$planPrintView .= '<div class="head"><img src="images/print_head.gif"  alt="Tx Xchange" class="headLogo" /></div>';
					while($r = $this->fetch_array($res))
					{
						
						// plan info
						/*$planPrintView .= '<div class="plan_info"><p>'.
							'Plan:' . $r['plan_name'] . 
							'<br/>Assigned To:' . $r['p_name_first'] .'&nbsp;'.$r['p_name_last'] .
							'<br/>Assigned By:' . $r['t_name_first'] .'&nbsp;'.$r['t_name_last'] .
							'</p></div>';*/
						
						$planPrintView .= '<div>'.
							'<h2>' . trim($r['treatment_name']) . ' <small>(Treatment ' . $trmnt_current . ' of ' . $trmnt_total . ')</small></h2>';
						
						$planPrintView .= '<table width="800" cellpadding="10" cellspacing="0" border="0" style="font-size:17px;" >'.
							'<tr><td valign="top" align="left" width="170">' . ((!empty($r['pic1'])) ? $this->makeImageTag($this->canonicalize($this->config['tx']['treatment']['media_url'] . $r['treatment_id']) . '/pic1.jpg') : '&nbsp;') . '</td>'.
							'<td rowspan="3" valign="top" align="left"><table width="100%" cellpadding="5" cellspacing="0" border="0" >';
							
						// instructions
						if(!empty($r['instruction'])) {
							$planPrintView .= '<tr><td valign="top" align="left">' . $r['instruction'] . '</td></tr>';
						}
						
						// sets, reps, hold
						$planPrintView .= '<tr><td valign="top" align="left">Sets: ' . $r['sets'] . '</td></tr>';
						$planPrintView .= '<tr><td valign="top" align="left">Reps: ' . $r['reps'] . '</td></tr>';
						$planPrintView .= '<tr><td valign="top" align="left">Hold: ' . $r['hold'] . '</td></tr>';
						$planPrintView .= '<tr><td valign="top" align="left">Benefit: ' . $r['benefit'] . '</td></tr>';
                        $this->config['lrb'][$r['lrb']] = ($r['lrb'] != 0?$this->config['lrb'][$r['lrb']]:'');
                        $planPrintView .= '<tr><td valign="top" align="left">Side: ' . $this->config['lrb'][$r['lrb']] . '</td></tr>';
						$planPrintView .= '</table></td></tr>'.
							'<tr><td>'.((!empty($r['pic2'])) ? $this->makeImageTag($this->canonicalize($this->config['tx']['treatment']['media_url'] . $r['treatment_id']) . '/pic2.jpg') : '&nbsp;').'</td></tr>'.
							'<tr><td>'.((!empty($r['pic3'])) ? $this->makeImageTag($this->canonicalize($this->config['tx']['treatment']['media_url'] . $r['treatment_id']) . '/pic3.jpg') : '&nbsp;').'</td></tr>'.
							'</table>';
						if( $trmnt_current  < $this->num_rows($res) && $trmnt_current%2 == 0 ){
							$planPrintView .= '</div><div class="foot" style="page-break-after:always;"></div>';
						}
						else{
							//$planPrintView .= '</div><div >&copy; 2007 Tx Xchange</div>';
						}
							
						$trmnt_current++;
					}
					//$planPrintView .= '<div class="foot">&copy; 2007 Tx Xchange</div>';
				}
				else
				{
					echo 'Couldn\'t find plan with id ' . $id . '.';
				}
			}	
			
			return $planPrintView;
		
		}
		
		/*
			Therapist Info 
		
		*/
		
		function therapistInfo()
		{
			$therapistId = $this->value('tid');			
			if(!empty($therapistId) && $therapistId > 0)
			{
				$query = "SELECT * FROM `user` WHERE user_id='{$therapistId}' ";
				
				$res = $this->execute_query($query);
				
				if($res !== false && $this->num_rows($res))
				{
					$r = $this->fetch_array($res);				
                    // Decrypt data
                            $encrypt_field = array('name_title','name_first','name_last','password','address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2','fax');
                            $r = $this->decrypt_field($r, $encrypt_field);
                    // End Decrypt
					
					$ptInfo = '<strong>';				
					
					// format subscriber details 
	
					if(!empty($r['name_title']))
					{
						$ptInfo .= $r['name_title'] . ' ';
					}
					
					$ptInfo .= $r['name_first'] . ' ' .$r['name_last'];
					
					if(!empty($r['name_suffix'])) 
					{
						$ptInfo .= ', ' . $r['name_suffix'];
					}
		
					$ptInfo .= '</strong><br />';	
					
					if(!empty($r['address'])) $ptInfo .= $r['address'] . '<br />';
					if(!empty($r['address2'])) $ptInfo .= $r['address2'] . '<br />';
					
					if (!empty($r['city']) && !empty($r['state']) && !empty($r['zip']))
					{
						$ptInfo .= $r['city'] . ', '. $r['state'] . ' ' .$r['zip'].'<br />';									
	
					}
					else if (!empty($r['city']) && !empty($r['state']))
					{
						$ptInfo .= $r['city'] .' ' .$r['state'].'<br />';
					}
					else if (!empty($r['city']) && !empty($r['zip']))
					{
						$ptInfo .= $r['city'] .' ' .$r['zip'].'<br />';
					}
					else if (!empty($r['state']) && !empty($r['zip'])) 
					{
						$ptInfo .= $r['state'] .' ' .$r['zip'].'<br />';	
					}
					else if (!empty($r['city']))
					{
						$ptInfo .= $r['city'].'<br />';
					}
					else if (!empty($r['state']))
					{
						$ptInfo .= $r['state'].'<br />';
					}
					else if (!empty($r['zip']))
					{
						$ptInfo .= $r['zip'].'<br />';
					}
					
					
					//$ret .= '<br />';
					if(!empty($r['username'])) $ptInfo .= '<a href="mailto:' . $r['username'] . '">' . $r['username'] . '</a><br />';
					if(!empty($r['phone1'])) $ptInfo .= 'Phone 1: ' . $r['phone1'] . '<br />';
					if(!empty($r['phone2'])) $ptInfo .= 'Phone 2: ' .$r['phone2'] . '<br />';
					if(!empty($r['fax'])) $ptInfo .= 'Fax: ' .$r['fax'] . '<br />';
					
				}
			}				
				
			return $ptInfo;		
			
		}	
		
		function reminderPopup()
		{
			$patient_id = $_GET['patient_id'];
			$query = "select * from patient_reminder where patient_id = '{$patient_id}' and status = 1 ORDER BY patient_reminder_id DESC";
			$result = $this->execute_query($query);
			$reminderContent = '';										
			
			while ($currentReminder = $this->fetch_array($result))
			{				
				$reminderContent .= '<li>'.$this->decrypt_data($currentReminder['reminder']).'</li>';			
			}
			
			//top5 or top reminders that fit in box depending on their length.			
			
			$reminderComponent = '<h3> Reminders</h3>'.'<div id="all" style="width:100%;  height:100%; vertical-align:top;"><ul style="margin-left:20px;">'.$reminderContent.'</ul></div>';
			//has needed ie fix
			//$reminderComponent .= '<div id="all" style="z-index:1;position:absolute;background-color: #ffffff;display:none;width:250px; height:92px; vertical-align:top;"><ul style="margin-left:20px;overflow:visible;">'.substr($reminderContent,0,strlen($reminderContent) - 185).$reminderContent2.'<br /><a href="#" onclick="getElementById(\'top5\').style.display=\'block\';getElementById(\'all\').style.display=\'none\';getElementById(\'allLink\').style.display=\'block\';">View Top '.$smartLimit.'</a></ul></div>';

			$replace['browserTitle'] = "Reminder Popup";
			$replace['mainRegion'] = $reminderComponent;									
			
			$this->output = $this->build_template($this->get_template("popuptemplate"),$replace);					
			
		}
		
		function therapistListPopup()
		{
			$patient_id = $_GET['patient_id'];
			$privateKey = $this->config['private_key'];
			$query = "select u.user_id, 
                      AES_DECRYPT(UNHEX(u.name_title),'{$privateKey}') as name_title, 
                      AES_DECRYPT(UNHEX(u.name_first),'{$privateKey}') as name_first,
                      AES_DECRYPT(UNHEX(u.name_last),'{$privateKey}') as name_last, 
                      u.name_suffix   from therapist_patient tp 
					inner join user u on u.user_id = tp.therapist_id
					where tp.patient_id = '{$patient_id}' AND u.status = 1 ";
			
			$result = $this->execute_query($query);	
			$totRecords = $this->num_rows($result);								

			$therapistList = "";
					
			if($result !== false && $totRecords)
			{
				while($row = $this->fetch_array($result) )
				{	
					$ptInfo ="";		
			
					{					
						$privateKey = $this->config['private_key'];
                        $query = "SELECT *,
                                  AES_DECRYPT(UNHEX(name_title),'{$privateKey}') as name_title, 
                                  AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as name_first,
                                  AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as name_last
                                  FROM `user` WHERE user_id='{$row['user_id']}' ";
						
						$res = $this->execute_query($query);
						
						if($res !== false && $this->num_rows($res))
						{
							$r = $this->fetch_array($res);				
							
							$ptInfo = '<strong>';				
							
							// format subscriber details 
			
							if(!empty($r['name_title']))
							{
								$ptInfo .= $r['name_title'] . ' ';
							}
							
							$ptInfo .= $r['name_first'] . ' ' .$r['name_last'];
							
							if(!empty($r['name_suffix'])) 
							{
								$ptInfo .= ', ' . $r['name_suffix'];
							}
				
							$ptInfo .= '</strong><br />';	
							
							if(!empty($r['address'])) $ptInfo .= $r['address'] . '<br />';
							if(!empty($r['address2'])) $ptInfo .= $r['address2'] . '<br />';
							
							if (!empty($r['city']) && !empty($r['state']) && !empty($r['zip']))
							{
								$ptInfo .= $r['city'] . ', '. $r['state'] . ' ' .$r['zip'].'<br />';									
			
							}
							else if (!empty($r['city']) && !empty($r['state']))
							{
								$ptInfo .= $r['city'] .' ' .$r['state'].'<br />';
							}
							else if (!empty($r['city']) && !empty($r['zip']))
							{
								$ptInfo .= $r['city'] .' ' .$r['zip'].'<br />';
							}
							else if (!empty($r['state']) && !empty($r['zip'])) 
							{
								$ptInfo .= $r['state'] .' ' .$r['zip'].'<br />';	
							}
							else if (!empty($r['city']))
							{
								$ptInfo .= $r['city'].'<br />';
							}
							else if (!empty($r['state']))
							{
								$ptInfo .= $r['state'].'<br />';
							}
							else if (!empty($r['zip']))
							{
								$ptInfo .= $r['zip'].'<br />';
							}
							
							
							//$ret .= '<br />';
							if(!empty($r['username'])) $ptInfo .= '<a href="mailto:' . $r['username'] . '">' . $r['username'] . '</a><br />';
							if(!empty($r['phone1'])) $ptInfo .= 'Phone 1: ' . $r['phone1'] . '<br />';
							if(!empty($r['phone2'])) $ptInfo .= 'Phone 2: ' .$r['phone2'] . '<br />';
							if(!empty($r['fax'])) $ptInfo .= 'Fax: ' .$r['fax'] . '<br />';
						}
					}
					
					$therapistList .= "<br />". $ptInfo;
				}				
			}		
			
			$replace['browserTitle'] = "Provider List Popup";
			$replace['mainRegion'] = $therapistList;									
			
			$this->output = $this->build_template($this->get_template("popuptemplate"),$replace);					
			
		}
				
		
		
		function get_template($template){
			$login_arr = $this->action_parser($this->action,'template') ;
			$pos =  array_search($template, $login_arr['template']['name']); 
			return $login_arr['template']['path'][$pos];
		}
		
		function display(){
			view::$output =  $this->output;
		}
		
		
		function canonicalize($address)
		{
			$address = explode('/', $address);
			$keys = array_keys($address, '..');
		
			foreach($keys AS $keypos => $key)
			{
				array_splice($address, $key - ($keypos * 2 + 1), 2);
			}
			$address = implode('/', $address);
			$address = str_replace('./', '', $address);
			return $address;
		}

		function makeImageTag($src)
		{
			$user_browser = $this->browser_detection('browser');
			if($user_browser == 'msie'){
				return '<image src="'.$src.'" width="160" height="140"  border="0" alt="Treatment Image" />';
			}elseif($user_browser == 'mozilla'){
				return '<image src="'.$src.'" width="160" height="135"  border="0" alt="Treatment Image" />';
			}elseif($user_browser == 'safari'){
				return '<image src="'.$src.'" width="160" height="125"  border="0" alt="Treatment Image" />';
			}else{
				return '<image src="'.$src.'" width="160" height="130"  border="0" alt="Treatment Image" />';
			}			
		}
		
		function escape($str)
		{
			if ($str === NULL) return NULL;
		
			$terminators = array("\r", "\n", "\t", ' ', ';', "--", "/*");
		
			for ($i = 0; $i < strlen($str); ++$i)
			{
				$ch = $str{$i};
				if (ctype_space($ch) || $ch == ';')
				{
					$str = substr($str, 0, $i);
					break;
				}
			}
		
			$commentStrings = array('--', '/*');
			foreach ($commentStrings as $commentString)
			{
				$pos = strpos($str, $commentString);
				if ($pos !== false) $str = substr($str, 0, $pos);
			}
		
			return $this->_doEscape($str);
		}

		function _doEscape($str)
		{
			if (function_exists("mysql_real_escape_string"))
				return mysql_real_escape_string($str);
			else
				return mysql_escape_string($str);
		}
    /**
    * Function to get Browser detection
    * @desc 
    */	
	function browser_detection( $which_test ) {
	
		// initialize the variables
		$browser = '';
		$dom_browser = '';
	
		// set to lower case to avoid errors, check to see if http_user_agent is set
		$navigator_user_agent = ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) ? strtolower( $_SERVER['HTTP_USER_AGENT'] ) : '';
	
		// run through the main browser possibilities, assign them to the main $browser variable
		if (stristr($navigator_user_agent, "opera")){
		
			$browser = 'opera';
			$dom_browser = true;
		
		}elseif (stristr($navigator_user_agent, "msie 4")){
		
			$browser = 'msie4'; 
			$dom_browser = false;
		
		}elseif (stristr($navigator_user_agent, "msie")){
		
			$browser = 'msie'; 
			$dom_browser = true;
			
		}elseif ((stristr($navigator_user_agent, "konqueror")) || (stristr($navigator_user_agent, "safari"))){
		
			$browser = 'safari'; 
			$dom_browser = true;
		
		}elseif (stristr($navigator_user_agent, "gecko")){
		
			$browser = 'mozilla';
			$dom_browser = true;
		
		}elseif (stristr($navigator_user_agent, "mozilla/4")) {
		
			$browser = 'ns4';
			$dom_browser = false;
		
		}else{
		
			$dom_browser = false;
			$browser = false;
		}
	
		// return the test result you want
		if ( $which_test == 'browser' )
		{
			return $browser;
		}
		elseif ( $which_test == 'dom' )
		{
			return $dom_browser;
			//  note: $dom_browser is a boolean value, true/false, so you can just test if
			// it's true or not.
		}
	 }	
	}
	
	$obj = new popup();
?>
