<?php
/**
 *
 * Copyright (c) 2008 Tx Xchange
 *
 * This class contains the mothods for uploading lab results and retrieving lab results from database.the functions used are commom for AA,provider and patient.
 * 
 *
 * // necessary classes 
 * require_once("module/application.class.php");
 * //Server side form validation classes
 * require_once("include/validation/_includes/classes/validation/ValidationSet.php");
 * require_once("include/validation/_includes/classes/validation/ValidationError.php");
 * 
 */

// including files
require_once ("include/paging/my_pagina_class.php");
require_once ("include/fileupload/class.upload.php");
require_once ("module/application.class.php");
require_once ("include/validation/_includes/classes/validation/ValidationSet.php");
require_once ("include/validation/_includes/classes/validation/ValidationError.php");

// class declaration
class labreport extends application {
	
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
	 * constructor
	 * set action variable from url string, if not found action in url, call default action from config.php
	 * 
	 */
	function __construct() {
		parent::__construct ();
		if ($this->value ( 'action' )) {
			/*
                    This block of statement(s) are to handle all the actions supported by this Login class
                    that is it could be the case that more then one action are handled by login
                    for example at first the action is "login" then after submit say action is submit
                    so if login is explicitly called we have the login action set (which is also our default action)
                    else whatever action is it is set in $str.                
                */
			$str = $this->value ( 'action' );
		} else {
			$str = ""; //default if no action is specified
		}
		$this->action = $str;
		if ($this->get_checkLogin ( $this->action ) == "true") {
			
			if (isset ( $_SESSION ['username'] ) && isset ( $_SESSION ['password'] )) {
				
				if (! $this->chk_login ( $_SESSION ['username'], $_SESSION ['password'] )) {
					
					header ( "location:index.php" );
				
				}
			
			} 

			else {
				
				header ( "location:index.php" );
			
			}
		
		}
		
		if ($this->userAccess ( $str )) {
			$str = $str . "()";
			eval ( "\$this->$str;" );
		} else {
			$this->output = $this->config['error_message'];
		}
		
		$this->display ();
		/*
            $str = $str."()";
            eval("\$this->$str;"); 
            $this->display();            
            */
	}
	
	/**
	 * @desc This function make pop up for labreport
	 * @param void
	 * @param void
	 * @access public
	 */
	public function aa_upload_lab_result() {
		$replace ['javascript'] = '';
		$replace ['pid'] = $this->value ( 'pid' );
		$replace ['filename'] = '';
		$replace ['role'] = $_REQUEST ['role'];
                $user=  $this->userInfo();
                if($user['usertype_id']==2)
                $replace['makeprivatetemplate']='<span style="font-size:14px;" ><input type="checkbox" name="makeprivate" id="makeprivate" value="1"/>Make Private</span>';
                else
                $replace['makeprivatetemplate']='';
                $replace ['body'] = $this->build_template ( $this->get_template ( "aa_upload_lab_result" ), $replace );
		$this->output = $this->build_template ( $this->get_template ( "main" ), $replace );
	}
	
	/**
	 * @desc this function upload file on server and save details of labreport in database
	 * @param void
	 * @return void
	 * @access private
	 */
	public function aa_submit_lab_result() {
		
           // $this->printR($_REQUEST);die;	
            $replace ['filename'] = $_REQUEST['labname'];
		$replace ['pid'] = $this->value ( 'pid' );
		$replace ['javascript'] = '';
		if ($_FILES ['labfile'] ['name'] == '') {
			$error [] = "Please select the file";
		}
        else{
            $type = array ('doc', 'docx', 'pdf', 'rtf', 'DOC', 'DOCX', 'PDF','xls','xlsx','XLS','XLSX','zip','ZIP');
    		$ext = explode ( ".", $_FILES ['labfile'] ['name'] );
    		$count = count ( $ext ) - 1;
    		
    		
    
    		if (! in_array ( $ext [$count], $type )) {
			$error [] = "Please select only pdf, doc, xls, zip  and rtf files";
            }
        }
		
		if ($this->value ( 'labname' ) == '') {
			$error [] = "Please enter the file name";
		
		}
        else
        {
    		if (!preg_match('/^[a-zA-Z0-9\-\ _]*\Z/',$this->value ( 'labname' ))) {
    			$error [] = "Please enter valid file name";
    		
    		}
        }
		// check error occoured or not.
		if (is_array ( $error ) && count ( $error ) > 0) {
			$replace ['pid'] = $this->value ( 'pid' );
			$replace ['error'] = $this->show_error_notbold ( $error );
                        $user=  $this->userInfo();
                if($user['usertype_id']==2){
                    if($_REQUEST[makeprivate]==1)
                        $replace['makeprivatetemplate']='<span style="font-size:14px;" ><input type="checkbox" name="makeprivate" id="makeprivate" value="1" checked/>Make Private</span>';
                    else 
                        $replace['makeprivatetemplate']='<span style="font-size:14px;" ><input type="checkbox" name="makeprivate" id="makeprivate" value="1"/>Make Private</span>';
                    
                    
                }else
                $replace['makeprivatetemplate']='';
                        
		} else {
			
			// End
			

			if (isset ( $_REQUEST ['save'] ) and $_REQUEST ['save'] == 'Save') {
				if (is_array ( $_FILES ) && $_FILES ['labfile'] ['error'] == 0) {
					
					if (! is_dir ( 'asset/labreport' ))
						mkdir ( 'asset/labreport', 0777 );
					$year = date ( 'Y' );
					if (! is_dir ( 'asset/labreport/' . $year ))
						mkdir ( 'asset/labreport/' . $year, 0777 );
					$p_id = $this->value ( 'pid' );
					if (! is_dir ( 'asset/labreport/' . $year . '/' . $p_id ))
						mkdir ( 'asset/labreport/' . $year . '/' . $p_id, 0777 );
					$ext = explode ( ".", $_FILES ['labfile'] ['name'] );
					//print_r($ext);
					$count = count ( $ext ) - 1;
					$filename = $p_id . '_' . time () . '.' . $ext [$count];
					$filepath = 'asset/labreport/' . $year . '/' . $p_id . '/' . $filename;
					move_uploaded_file ( $_FILES ['labfile'] ['tmp_name'], $filepath );
					$userid = $this->userInfo ( 'user_id' );
					$clinic_id = $this->clinicInfo ( 'clinic_id', $userid );
					if($_REQUEST['role']=='pat')
					$uploadedby = '1';
					else 
					$uploadedby = '2';
                                       $makeprivate='0';
                                        if($_REQUEST['makeprivate']==1){
                                            $makeprivate='1';
                                        }else{
                                            $makeprivate='0';
                                        }
					$labrep = array ('clinic_id' => $clinic_id, 'pro_user_id' => $userid, 'pat_user_id' => $p_id, 'lab_path' => $filepath, 'lab_title' => $this->value ( 'labname' ), 'lab_datetime' => date ( 'Y-m-d H:i:s', time () ), 'lab_status' => 1 ,'uploaded_by' =>$uploadedby, 'original_filename' => $_FILES ['labfile'] ['name'],'makeprivate'=>$makeprivate);
					$this->insert ( 'patient_lab_reports', $labrep );
				}
			
			}
			$error = "Successfully saved  ";
			
			
			if (($_REQUEST ['role'] == "headAA" || $_REQUEST ['role'] == "aa") && $makeprivate=='0') {
				
				

				
			/*
				$query = "  select *
                            from therapist_patient tp
                            inner join user u on u.user_id = tp.therapist_id 
                            where tp.patient_id  = '{$p_id}' ";
				
				
				
				$result = @mysql_query ( $query );
				$ProviderList = $this->populate_array ( $result, 0 );
				
					foreach ( $ProviderList as $Prokey => $ProVal ) {
				*/		
						$subjectPro = "New Lab Results are In";
						$messagebodyPro = '';
						
						$sub = "From: " . $this->userInfo ( 'name_first', $p_id) . $this->userInfo ( 'name_last',$p_id ) . "<support@txxchange.com>" . "\n";
						$returnpath = '-fsupport@txxchange.com';
						
						//$this->sendTxMessage ( $p_id, $p_id, $subjectPro, $messagebodyPro );
						$this->sendTxMessageByPatToPro ($p_id, $p_id, $subjectPro, $messagebodyPro );
					
			/*}*/
					
				
				$subjectPat = "New document for you";
				$sub = "From: " . $this->userInfo ( 'name_first' ) . $this->userInfo ( 'name_last' ) . "<support@txxchange.com>" . "\n";
				$returnpath = '-fsupport@txxchange.com';
				$message = addslashes("We have uploaded a document to your health record. It can be viewed by clicking on the link in the Results and Documents section of your portal
				</br>Please message us with questions or to schedule a consult.
						<br/>Regards,<br/>
							" . $this->get_clinic_info($p_id,"clinic_name"));
					
					//$to = $this->userInfo("username",$p_id);	
					
				$this->sendTxMessageByAdmin (  $this->userInfo ( 'user_id' ),  $p_id, $subjectPat, $message );
			
			}
			
			
			
			if ($_REQUEST ['role'] == "pro" && $makeprivate == 0)
			 {
				
				$message = addslashes("We have uploaded a document to your health record. It can be viewed by clicking on the link in the Results and Documents section of your portal
				</br>Please message us with questions or to schedule a consult.
						<br/>Regards,<br/>
				" . $this->get_clinic_info($p_id,"clinic_name"));
				
				$this->userInfo ( 'username' );
				
			
				$subject = "New document for you";
				
				$sub = "From: " . $this->userInfo ( 'name_first' ) . $this->userInfo ( 'name_last' ) . "<support@txxchange.com>" . "\n";
				$returnpath = '-fsupport@txxchange.com';
				$this->sendTxMessage ( $this->userInfo ( 'user_id' ), $p_id, $subject, $message );
			}
			
			if ($_REQUEST ['role'] == "pat") 
			{
				
				$message = "";
				
				
			 $thid = "select * from therapist_patient where patient_id = {$this->userInfo ( 'user_id' )}";
			$queryTh = $this->execute_query ( $thid);	
			
					//while($rowTh = $this->fetch_array ( $queryTh))
					//{	
						$this->userInfo ( 'username' );
						
						
						$subject = "New Test or Lab Results are In";
						
						$sub = "From: " . $this->userInfo ( 'name_first' ) . $this->userInfo ( 'name_last' ) . "<support@txxchange.com>" . "\n";
						$returnpath = '-fsupport@txxchange.com';
						$this->sendTxMessageByPat ( $this->userInfo ( 'user_id' ) , $this->userInfo ( 'user_id' ), $subject, $message );
				
					//}
			
						
			}
			
			$replace ['error'] = $error = '<span style="color:green;padding-left:0px" ><b>' . $error . '</b></span>';
			$replace ['javascript'] = "parent.parent.GB_hide();parent.parent.reloadlab('{$p_id}');";
		}
		
		$replace ['body'] = $this->build_template ( $this->get_template ( "aa_upload_lab_result" ), $replace );
		$this->output = $this->build_template ( $this->get_template ( "main" ), $replace );
	}
	
	
	public function downloadfile(){
	$pid=$this->value('lab_report_id');
	$sql = "select lab_title,DATE_FORMAT(lab_datetime, '%m/%d/%Y') as labdate,lab_path,original_filename from patient_lab_reports where lab_report_id ={$pid} order by lab_report_id desc" ;
	$query = $this->execute_query ( $sql );
	$rowLab = $this->fetch_array($query);
	$file=	$rowLab['lab_path'];	
	 
                if (file_exists($file)) {
                	//echo 'fdfdsf';

                     $original_filename = $rowLab['original_filename'];

                     if($rowLab['original_filename']!= '') {
                        $original_filename = str_replace(' ','_',$rowLab['original_filename']);
                     } else {
                        $original_filename = $file;
                     }

                     header('Content-Description: File Transfer');
                     header('Content-Type: application/octet-stream',true);
                     header('Content-Disposition: attachment; filename='.basename($original_filename),true);
                     header('Content-Transfer-Encoding: binary');
                     header('Expires: 0');
                     header('Cache-Control: must-revalidate, post-check=0, pre-check=0',true);
                     header('Pragma: public',true);
                     header('Content-Length: ' . filesize($file),true);
                     ob_clean();
                     flush();
                     readfile($file);
                     exit;
	       }
	}
	
	/** delete download file**/
    public function deletedownloadfile()
    {
        $fileID=$this->value('file_id');
        $userId=$this->userInfo();
            if($fileID!=''){ 
                $selectPath = $this->execute_query("SELECT lab_path FROM patient_lab_reports WHERE lab_report_id ='".$fileID."'");  
                $row=$this->fetch_array($selectPath);
                $file = $this->config['application_path'].$row['lab_path'];
                //echo $file;exit;
                if (file_exists($file)) {
                    //echo file_exists($file);exit;
                    unlink($file);
                } 
                //else{echo "2";exit;}
                $deletedata=$this->execute_query("DELETE FROM patient_lab_reports WHERE lab_report_id ='".$fileID."'");
                if($deletedata)
                {
                    echo "success"; 
                }
                else
                {
                     echo "fail";   
                }    
            }
            else
            {
                echo "fail";
            }
    }
    
	
	/**
	 * @desc This function shows the lab report listing
	 * @param void
	 * @return void
	 * @access public
	 */
	public function labreportlist() {
		
                $pId = $this->value ( 'patient_id' );
		$user=  $this->userInfo();
                if($user['usertype_id']==2)
                $sql = "select lab_report_id,lab_title,DATE_FORMAT(lab_datetime, '%m/%d/%Y') as labdate,lab_path from patient_lab_reports where pat_user_id ={$pId} order by lab_report_id desc" ;
                else
                $sql = "select lab_report_id,lab_title,DATE_FORMAT(lab_datetime, '%m/%d/%Y') as labdate,lab_path from patient_lab_reports where pat_user_id ={$pId} and makeprivate='0' order by lab_report_id desc" ;
                
		
                $query = $this->execute_query ( $sql );
		//$row=$this->fetch_array($query);
		

		$rowLab = $this->populate_array ( $query, 0 );
		$labData.='<table cellpadding="5" cellspacing="0" style="width:100%" class="list">';
		foreach ( $rowLab as $klab => $kValue ) {
			
			//print_r($kValue);
                    
                     /**
                             * Change For Ticket No: TXM-36 By Rohit Mishra On Date 24/1/2013
                             */
                    
                     $usertype=$this->userInfo('usertype_id');
                     if($usertype==1){
                        $labData .="<tr><td><a href=index.php?action=downloadfile&lab_report_id=".$kValue['lab_report_id']." target=\"_blank\" style=\"color:#0069A0; display:block; padding:4px 13px;\">".$kValue['lab_title'].' '.$kValue['labdate']."</a></td><td>&nbsp;</td></tr>"; 
                     }else{
                       $labData .="<tr><td><a href=index.php?action=downloadfile&lab_report_id=".$kValue['lab_report_id']." target=\"_blank\" style=\"color:#0069A0; display:block; border-bottom:#bbb solid 1px;padding:4px 13px;\">".$kValue['lab_title'].' '.$kValue['labdate']."</a></td><td><input type='image' src='/images/dustbin_icon.png' onclick='deletedownloadfile(".$kValue['lab_report_id'].")' name='delete' id='delete' value='Delete' style='font-size:11px;' /></td></tr>";  
                     }
		 
		}
		$labData.='</table>';
		echo $labData;
	
	}
	
	/**
	 * This function sends TX message:from therapist.
	 * @param Integer $sender_id
	 * @param Integer $rec_id
	 * @param String  $sub
	 * @param String  $content
	 * @return void
	 * @access public
	 */
	
	public function sendTxMessage($sender_id, $rec_id, $sub, $content) {
		$data = array ('sender_id' => $sender_id,'patient_id' => $rec_id, 'subject' => $this->encrypt_data ( $sub ), 'content' => $this->encrypt_data ( $content ), 'parent_id' => '0', 'sent_date' => date ( 'Y-m-d H:i:s', time () ), 'recent_date' => date ( 'Y-m-d H:i:s', time () ), 'replies' => '0' );
		
		if ($this->insert ( "message", $data )) {
			$message_id = $this->insert_id ();
			// Entry for Account Admin
			$data = array ('message_id' => $message_id, 'user_id' => $sender_id, 'unread_message' => '2' );
			$this->insert ( "message_user", $data );
			
			// Entry for Patient
			

			$data = array ('message_id' => $message_id, 'user_id' => $rec_id, 'unread_message' => '1' );
			$this->insert ( "message_user", $data );
		
		}
	
	}
	
/**
	 * This function sends TX message:from patient.
	 * @param Integer $sender_id
	 * @param Integer $rec_id
	 * @param String  $sub
	 * @param String  $content
	 * @return void
	 * @access public
	 */
	
	public function sendTxMessageByPat($sender_id, $rec_id, $sub, $content) {
		$data = array ('sender_id' => $sender_id,'patient_id' => $rec_id, 'subject' => $this->encrypt_data ( $sub ), 'content' => $this->encrypt_data ( $content ), 'parent_id' => '0', 'sent_date' => date ( 'Y-m-d H:i:s', time () ), 'recent_date' => date ( 'Y-m-d H:i:s', time () ), 'replies' => '0' );
		
		if ($this->insert ( "message", $data )) {
			$message_id = $this->insert_id ();
			// Entry for Account Admin
			$data = array ('message_id' => $message_id, 'user_id' => $this->userInfo ( 'user_id' ), 'unread_message' => '2' );
			$this->insert ( "message_user", $data );
			
			
			 $thid = "select * from therapist_patient where patient_id = {$this->userInfo ( 'user_id' )}";
			$queryTh = $this->execute_query ( $thid);	
			
					while($rowTh = $this->fetch_array ( $queryTh))
					{	
			
			// Entry for therapist
			

			$data = array ('message_id' => $message_id, 'user_id' => $rowTh['therapist_id'], 'unread_message' => '1' );
			$this->insert ( "message_user", $data );
					}
		}
	
	}

		/**
	 * This function sends TX message from admin.
	 * @param Integer $sender_id
	 * @param Integer $rec_id
	 * @param String  $sub
	 * @param String  $content
	 * @return void
	 * @access public
	 */
	
	public function sendTxMessageByAdmin($sender_id, $rec_id, $sub, $content) {
		$data = array ('sender_id' => $sender_id,'patient_id' => $rec_id, 'subject' => $this->encrypt_data ( $sub ), 'content' => $this->encrypt_data ( $content ), 'parent_id' => '0', 'sent_date' => date ( 'Y-m-d H:i:s', time () ), 'recent_date' => date ( 'Y-m-d H:i:s', time () ), 'replies' => '0' ,'aa_message'=>'1');
		
		if ($this->insert ( "message", $data )) {
			$message_id = $this->insert_id ();
			// Entry for Account Admin
			$data = array ('message_id' => $message_id, 'user_id' => $sender_id, 'unread_message' => '2' );
			$this->insert ( "message_user", $data );
			
			// Entry for Patient
			

			$data = array ('message_id' => $message_id, 'user_id' => $rec_id, 'unread_message' => '1' );
			$this->insert ( "message_user", $data );
		
		}
	
	}
	
	
	/**
	 * This function sends TX message from patient to provider.
	 * @param Integer $sender_id
	 * @param Integer $rec_id
	 * @param String  $sub
	 * @param String  $content
	 * @return void
	 * @access public
	 */
	
	public function sendTxMessageByPatToPro($sender_id, $rec_id, $sub, $content) {
		$data = array ('sender_id' => $sender_id,'patient_id' => $rec_id, 'subject' => $this->encrypt_data ( $sub ), 'content' => $this->encrypt_data ( $content ), 'parent_id' => '0', 'sent_date' => date ( 'Y-m-d H:i:s', time () ), 'recent_date' => date ( 'Y-m-d H:i:s', time () ), 'replies' => '0' );
		
		if ($this->insert ( "message", $data )) {
			$message_id = $this->insert_id ();
			// Entry for displaying unread messages count in pat section
			$data = array ('message_id' => $message_id, 'user_id' => $sender_id, 'unread_message' => '2' );
			$this->insert ( "message_user", $data );
			
			
			 $thid = "select * from therapist_patient where patient_id = {$sender_id}";
			$queryTh = $this->execute_query ( $thid);	
			
					while($rowTh = $this->fetch_array ( $queryTh))
					{	
			
			// Entry for therapist
			

			$data = array ('message_id' => $message_id, 'user_id' => $rowTh['therapist_id'], 'unread_message' => '1' );
			$this->insert ( "message_user", $data );
					}
		}
	
	}
	
	
	/**
	 * This function gets the template path from xml file.
	 *
	 * @param string $template - pass template file name as defined in xml file for that template file.
	 * @return string - template file
	 * @access private
	 */
	function get_template($template) {
		$login_arr = $this->action_parser ( $this->action, 'template' );
		$pos = array_search ( $template, $login_arr ['template'] ['name'] );
		return $login_arr ['template'] ['path'] [$pos];
	}
	/**
	 * This function sends the output to browser.
	 * 
	 * @access public
	 */
	function display() {
		view::$output = $this->output;
	}
}
$obj = new labreport ();
?>
