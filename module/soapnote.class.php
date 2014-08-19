<?php	
/**
 *
 * Copyright (c) 2008 Tx Xchange
 *
 * This class includes the functionality add sopa note and plan
 *
 * // necessary classes
 * require_once("module/application.class.php");
 * require_once("include/netsuite/netsuite_functions.php");
 *
 * // file upload class
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
require_once("include/paging/my_pagina_class.php");
require_once("module/application.class.php");
require_once("include/validation/_includes/classes/validation/ValidationSet.php");
require_once("include/validation/_includes/classes/validation/ValidationError.php");

// class declaration
class soapnote extends application{

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
			$str = ""; //default if no action is specified
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
		/*
		 $str = $str."()";
		eval("\$this->$str;");
		$this->display();
		*/
	}
		
	 
	/**
	 * This function view spap note
	 *
	 * @access private
	 */
	function provider_view_soap_note(){

		$userInfo = $this->userInfo();
		$cli_type=$this->getClinicDetails($this->userInfo('user_id'));
		//if($cli_type['clinic_type']==4){
		$replace['display']='inline';
		//}else{
		//  $replace['display']='none';
		//}
		$replace['header'] = $this->build_template($this->get_template("header"));
		$replace['footer'] = $this->build_template($this->get_template("footer"));
		$replace['sidebar'] = $this->sidebar();
		$replace['id'] = $this->value('id');
		$userInfo = $this->userInfo();
		$userId = $userInfo['user_id'];
		$clinicname=$this->get_clinic_info($userId,'clinic_name');
	  
		$providername=$this->userInfo('name_title',$userId).' '.$this->userInfo('name_first',$userId).' '.$this->userInfo('name_last',$userId);
		$replace['clinicname']=$clinicname;
		$replace['patientname']=  strtoupper($this->userInfo('name_first',$this->value('id')).' '.$this->userInfo('name_last',$this->value('id')));
		$replace['providername']=$providername;
		$replace['currentdate']=date('m/d/Y',time());
		$replace['body'] = $this->build_template($this->get_template("provider_view_soap_note"),$replace);
		$replace['browser_title'] = "Provider add soap note";
		$this->output = $this->build_template($this->get_template("main"),$replace);

	}


	function provider_view_soap_note_readonly(){
		$replace['header'] = $this->build_template($this->get_template("header"));
		$replace['footer'] = $this->build_template($this->get_template("footer"));
		$replace['sidebar'] = $this->sidebar();
		$replace['id'] = $this->value('id');
		$replace['shopnoteid'] = $this->value('shopnoteid');
		$userInfo = $this->userInfo();
		$result=$this->execute_query("select * ,DATE_FORMAT(create_datetime, '%m/%d/%Y') as date1 from new_soap_note where soap_note_id =".$this->value('shopnoteid')." order by soap_note_id ASC");
		if($this->num_rows($result)>0){
			while($row=mysql_fetch_assoc($result)){
				foreach($row as $key=>$value){
					$value=urlencode(html_entity_decode(($this->jsesc($value))));
					$stateJavascript.="\"{$key}\":\"{$value}\",";
				}
				$provider_id=$row['provider_id'];
				$patient_id=$row['patient_id'];
				$date=$row['date1'];
			}
			// $stateJavascript=trim($stateJavascript,',');
		}
		 
		$resultcc=$this->execute_query("select * from cc_tbl where soap_note_id =".$this->value('shopnoteid')." ORDER BY `ccnumber` ASC" );
		if($this->num_rows($resultcc)>0){
			$i=0;
			while($row1=mysql_fetch_assoc($resultcc)){
				foreach($row1 as $key=>$value){
					$value=urlencode(html_entity_decode(($this->jsesc($value))));
					if($i!=0)
						$key=$key.$i;
					$stateJavascript.="\"{$key}\":\"{$value}\",";
				}
				$i++;
				 
			}
		}
		$resultsoap_plan=$this->execute_query("select * from soap_plan where soap_note_id =".$this->value('shopnoteid'));
		if($this->num_rows($resultsoap_plan)>0){
			while($row2=mysql_fetch_assoc($resultsoap_plan)){
				$soap_plan_id=$row2['soap_plan_id'];
				foreach($row2 as $key=>$value){
					$value=urlencode(html_entity_decode(($this->jsesc($value))));
					$stateJavascript.="\"{$key}\":\"{$value}\",";
				}
			}
		}
		if($soap_plan_id!='' or $soap_plan_id!=0){
			$resultsoap_plan_detail=$this->execute_query("select * from soap_plan_description where soap_plan_id =".$soap_plan_id." order by soap_plan_description_id ASC");
			if($this->num_rows($resultsoap_plan_detail)>0){
				$i=0;
				while($row3=mysql_fetch_assoc($resultsoap_plan_detail)){
			   
					foreach($row3 as $key=>$value){
						$value=urlencode(html_entity_decode(($this->jsesc($value))));
						$key=$key.$i;
						$stateJavascript.="\"{$key}\":\"{$value}\",";
					}
					$i++;
				}
			}
		}
		//$stateJavascript=str_replace('\n\r','',nl2br($stateJavascript));
		$stateJavascript=trim($stateJavascript,',');
		$replace['prouct_count']	=	0;
		if($this->num_rows($resultsoap_plan_detail)!='')
		$replace['prouct_count']	=	$this->num_rows($resultsoap_plan_detail);
		$providername=$this->userInfo('name_title',$provider_id).' '.$this->userInfo('name_first',$provider_id).' '.$this->userInfo('name_last',$provider_id);
		$replace['patientname']=strtoupper($this->userInfo('name_first',$patient_id).' '.$this->userInfo('name_last',$patient_id));
		$replace['providername']=$providername;
		$replace['currentdate']=$date;
		$clinicname=$this->get_clinic_info($patient_id,'clinic_name');
		$replace['clinicname']=$clinicname;
		$replace['stateJavascript']=$stateJavascript;
		$replace['body'] = $this->build_template($this->get_template("provider_view_soap_note_readonly"),$replace);
		$replace['browser_title'] = "Provider view soap note";
		$this->output = $this->build_template($this->get_template("main"),$replace);

	}
	 
	function provider_view_soap_note_draft(){
		$userInfo = $this->userInfo();
		$cli_type=$this->getClinicDetails($this->userInfo('user_id'));
		//if($cli_type['clinic_type']==4){
		$replace['display']='inline';
		//}else{
		//  $replace['display']='none';
		// }
		$replace['header'] = $this->build_template($this->get_template("header"));
		$replace['footer'] = $this->build_template($this->get_template("footer"));
		$replace['sidebar'] = $this->sidebar();
		$replace['id'] = $this->value('id');
		$replace['shopnoteid'] = $this->value('shopnoteid');
		$userInfo = $this->userInfo();
		$result=$this->execute_query("select * ,DATE_FORMAT(create_datetime, '%m/%d/%Y') as date1 from new_soap_note where soap_note_id =".$this->value('shopnoteid')." order by soap_note_id ASC");
		if($this->num_rows($result)>0){
			while($row=mysql_fetch_assoc($result)){
				foreach($row as $key=>$value){
					$value=urlencode(html_entity_decode(($this->jsesc($value))));
					$stateJavascript.="\"{$key}\":\"{$value}\",";
				}
				$provider_id=$row['provider_id'];
				$patient_id=$row['patient_id'];
				$date=$row['date1'];
			}
			// $stateJavascript=trim($stateJavascript,',');
		}
		 
		$resultcc=$this->execute_query("select * from cc_tbl where soap_note_id =".$this->value('shopnoteid') ." ORDER BY `ccnumber` ASC");
		if($this->num_rows($resultcc)>0){
			$i=0;
			while($row1=mysql_fetch_assoc($resultcc)){
				foreach($row1 as $key=>$value){
					$value=urlencode(html_entity_decode(($this->jsesc($value))));
					if($i!=0)
						$key=$key.$i;
					$stateJavascript.="\"{$key}\":\"{$value}\",";
				}
				$i++;
				 
			}
		}
		$resultsoap_plan=$this->execute_query("select * from soap_plan where soap_note_id =".$this->value('shopnoteid'));
		if($this->num_rows($resultsoap_plan)>0){
			while($row2=mysql_fetch_assoc($resultsoap_plan)){
				$soap_plan_id=$row2['soap_plan_id'];
				foreach($row2 as $key=>$value){
					$value=urlencode(html_entity_decode(($this->jsesc($value))));
					$stateJavascript.="\"{$key}\":\"{$value}\",";
				}
			}
		}
		if($soap_plan_id!='' or $soap_plan_id!=0){
			$resultsoap_plan_detail=$this->execute_query("select * from soap_plan_description where soap_plan_id =".$soap_plan_id." order by soap_plan_description_id ASC");
			if($this->num_rows($resultsoap_plan_detail)>0){
				$i=0;
				while($row3=mysql_fetch_assoc($resultsoap_plan_detail)){
			   
					foreach($row3 as $key=>$value){
						$value=urlencode(html_entity_decode(($this->jsesc($value))));
						$key=$key.$i;
						$stateJavascript.="\"{$key}\":\"{$value}\",";
					}
					$i++;
				}
			}
		}
		//$stateJavascript=str_replace('\n\r','',nl2br($stateJavascript));
		$replace['prouct_count']	=	0;
		if($this->num_rows($resultsoap_plan_detail)!='')
		$replace['prouct_count']	=	$this->num_rows($resultsoap_plan_detail);
		$stateJavascript			=	trim($stateJavascript,',');
		$providername				=	$this->userInfo('name_title',$provider_id).' '.$this->userInfo('name_first',$provider_id).' '.$this->userInfo('name_last',$provider_id);
		$replace['patientname']		=	strtoupper($this->userInfo('name_first',$patient_id).' '.$this->userInfo('name_last',$patient_id));
		$replace['providername']	=	$providername;
		$replace['currentdate']		=	$date;
		$clinicname					=	$this->get_clinic_info($patient_id,'clinic_name');
		$replace['clinicname']		=	$clinicname;
		$replace['stateJavascript']	=	$stateJavascript;
		$replace['body'] 			=	$this->build_template($this->get_template("provider_view_soap_note_draft"),$replace);
		$replace['browser_title']	=	"Provider view soap note";
		$this->output				=	$this->build_template($this->get_template("main"),$replace);

	}

	function jsesc($escString){
		$find = array( "'", '’', "\n", "\r", chr(226).chr(128).chr(168),
				chr(226).chr(128).chr(169), chr(194).chr(133));
		$replace = array( "\\'", "\\'", "\\n ", "", "\\n", "\\n", "\\n");
		return str_replace($find, $replace, stripslashes($escString));
	}
	/**
	 * This function provider fill soap note
	 *
	 * @access private
	 */
	function provider_submit_soap_note(){
		$userInfo = $this->userInfo();
		$userId = $userInfo['user_id'];
		unset($_REQUEST['os_user']);
		unset($_REQUEST['PHPSESSID']);
		unset($_REQUEST['x']);
		unset($_REQUEST['y']);
		unset($_REQUEST['action']);
		 
		/*
		 echo "<pre>";
		print_r($_REQUEST);
		die;
		*/
		$shopnoteid=$this->value('shopnoteid');

		$empty=1;
		foreach($_REQUEST as $key=>$value){
			if(is_array($value)){
				//	echo "array";
				//	echo '<br>';
				foreach($value as $keysub=>$valsub){
					if($valsub != ''){
						$empty=2;
					}
				}
			}elseif($value != ''){
				//	echo "value";
				//	echo '<br>';
				$empty=2;
			}
			if($key=='x')
				$empty=1;
			if($key=='y')
				$empty=1;
			if($key=='action')
				$empty=1;
			if($key=='id')
				$empty=1;
		}
		//echo $empty;die;
		if($empty == 2)
		{
			$newsoapnote=array(
					'provider_id'				=>$userId,
					'patient_id'        		=>$this->value('id'),
					'create_datetime'        	=> date('Y-m-d H:i:s',time()),
					'ob_vitals'        			=>$this->value('ob_vitals'),
					'ob_constitutional'         =>$this->value('ob_constitutional'),
					'ob_eyes'         			=>$this->value('ob_eyes'),
					'ob_ears_nose_mouth_throat' =>$this->value('ob_ears_nose_mouth_throat'),
					'ob_cardiovascular'			=>$this->value('ob_cardiovascular'),
					'ob_respiratory'			=>$this->value('ob_respiratory'),
					'ob_gastrointestinal'		=>$this->value('ob_gastrointestinal'),
					'ob_genital_urinary'		=>$this->value('ob_genital_urinary'),
					'ob_musculo_skeletal'		=>$this->value('ob_musculo_skeletal'),
					'ob_skin'					=>$this->value('ob_skin'),
					'ob_neurological'			=>$this->value('ob_neurological'),
					'ob_psychiatric'			=>$this->value('ob_psychiatric'),
					'ob_endocrine'				=>$this->value('ob_endocrine'),
					'ob_hematology_lymphatic'	=>$this->value('ob_hematology_lymphatic'),
					'ob_allergy_immunology'		=>$this->value('ob_allergy_immunology'),
					'ob_eav'					=>$this->value('ob_eav'),
					'ob_laboratory'				=>$this->value('ob_laboratory'),
					'dx'						=>$this->value('dx'),
					'ddx'						=>$this->value('ddx'),
					'status'					=>$this->value('status'),
			);
			if($shopnoteid==''){
				$this->insert('new_soap_note',$newsoapnote);
				$shopnodeid=$this->insert_id();
			}else{
                                // BWU-15
                                unset($newsoapnote['create_datetime']);
				$where=" soap_note_id ={$shopnoteid}";
				$this->update('new_soap_note',$newsoapnote,$where);
				$shopnodeid=$shopnoteid;
			}
			$cctbl=array('chief_complaint','location','onset','provocation','palliation','quality','radiation','severity',
					'duration','timing','associated_signs_symptoms','constitutional','eyes','ears_nose_mouth_throat',
					'cardiovascular','respiratory','gastrointestinal','genital_urinary','musculo_skeletal','skin','neurological',
					'psychiatric','endocrine','hematology_lymphatic','allergy_immunology','ccnumber');
			$noblank1 = 0;
            $noblank2 = 0;
			foreach($cctbl as $cc){
				$i=0;
				foreach ($_REQUEST[$cc] as $key=>$val){
					if($key==$i){
						if($val!='')
						{
							$temp[$i]= 2;
						}
					}
					if($cc != 'ccnumber' && $key == '1')
                    {
                        if($val != '')
                        $noblank1 = 1;
                    }
                    if($cc != 'ccnumber' && $key == '2')
                    {
                        if($val != '')
                        $noblank2 = 1;
                    }	
					$i++;
				}
			}
            $unset1 = 0;
            $unset2 = 0;
            foreach($cctbl as $cc){
				foreach ($_REQUEST[$cc] as $key=>$val){
					if($key=='1'){
						if($noblank1 == 0)
						{
							unset($_REQUEST[$cc]['1']);
                            $unset1 = 1;
						}
					}
                    if($key=='2'){
						if($noblank2 == 0)
						{
							unset($_REQUEST[$cc]['2']);
                            $unset2 = 1;
						}
					}
				}
			}
			$sql="delete from cc_tbl where soap_note_id={$shopnodeid}";
			$this->execute_query($sql);
			if(count($temp)>0){
				foreach($temp as $k=>$v){
					//echo $k;
					$cc=array();
					foreach ($cctbl as $kcc){
						//echo $kcc;
						$cc[$kcc]=$_REQUEST["$kcc"]["$k"];
					}
					$cc['soap_note_id']=$shopnodeid;
					$cc['create_datetime']=date('Y-m-d H:i:s',time());
					$cc['status']=$this->value('status');
					//$cc['ccnumber']=$k+1;
					if($k == '1')
                    {
                        if($unset1 != 1)
                        {
                            $this->insert('cc_tbl',$cc);
                        }
                    }
                    else if($k == '2')
                    {
                        if($unset2 != 1)
                        {
                            $this->insert('cc_tbl',$cc);
                        }
                    }
                    else
                    {
                        $this->insert('cc_tbl',$cc);
                    }	
					
						
				}
			}
			$shopplan=array('product_description','arising','breakfast','mid_am','lunch','mid_pm','dinner','before_bed','comments');
			foreach($shopplan as $shopplan_value){
				$y=0;
				foreach($_REQUEST[$shopplan_value] as $key_plan=>$val_plan){
					if($key_plan==$y){
						if($val_plan!='')
						{
							$temp_plan[$y]= 2;
						}
					}
						
					$y++;
				}
			}
			//print_r($temp_plan);
  	         ksort($temp_plan);
			if(count($temp_plan)>0 or $this->value('dietary_instructions')!='' or $this->value('notes_instructions')!=''){
				$plan=	array(
						'soap_note_id'	=>	$shopnodeid,
						'patient_id'	=>	$this->value('id'),
						'provider_id'	=>	$userId,
						'create_datetime'=>	date('Y-m-d H:i:s',time()),
						'dietary_instructions'=>$this->value('dietary_instructions'),
						'notes_instructions'=>$this->value('notes_instructions'),
						'status'=>$this->value('status')
				);
				//
				$selectplan="select soap_plan_id from soap_plan where soap_note_id={$shopnodeid}";
				$queryplan=$this->execute_query($selectplan);
				if($this->num_rows($queryplan)==0){
					$this->insert('soap_plan',$plan);
					$shop_plan_id=$this->insert_id();
				}else{
					$res_plan=$this->fetch_object($queryplan);
					$shop_plan_id=$res_plan->soap_plan_id;
					$where="soap_plan_id={$shop_plan_id}";
					$this->update('soap_plan',$plan,$where);
				}
				 
			}
			if(count($temp_plan)>0){
				$sql="delete from soap_plan_description where soap_plan_id={$shop_plan_id}";
				$this->execute_query($sql);
				foreach($temp_plan as $k_plan=>$v_plan){
					$plan_desc=array();
					foreach ($shopplan as $shopplan_key){
						//echo $kcc;
						$plan_desc["$shopplan_key"]=$_REQUEST["$shopplan_key"]["$k_plan"];
					}
					$plan_desc['soap_plan_id']=$shop_plan_id;
					$plan_desc['create_datetime']=date('Y-m-d H:i:s',time());
					$plan_desc['status']=$this->value('status');
					//print_r($plan_desc);
					$this->insert('soap_plan_description',$plan_desc);
					//
				}
				 
			}
				
				
		}

		header("location:index.php?action=therapistViewPatient&id=".$this->value('id'));
	}

	/**
	 *This function provider list soap note
	 *
	 *
	 ***/
	function provider_show_soap_note_list(){
		 

	}
	/**
	 *This function will print soap note
	 *
	 *
	 ***/
    /************Code modified by pawan khandelwal for TXM-17 issue in jira updated on 08 august 2013 starts here*******************************************/  
	function printsoapnote(){
		$userInfo = $this->userInfo();
		$dos='';
        $printsoapdata = array();
        $printsoapdatacctbl = array();
		$result=$this->execute_query("select * ,DATE_FORMAT(create_datetime, '%m/%d/%Y') as date1 from new_soap_note where soap_note_id =".$this->value('shopnoteid')." order by soap_note_id ASC");
		if($this->num_rows($result)>0){
			while($row=mysql_fetch_assoc($result)){
				foreach($row as $key=>$value){
					//$value=urlencode(html_entity_decode(($this->jsesc($value))));
                    $value=stripcslashes($this->jsesc($value));
                    if(!empty($value))
                    $printsoapdataobjass[$key] = $value;
					$stateJavascript.="\"{$key}\":\"{$value}\",";
				}
				$provider_id=$row['provider_id'];
				$patient_id=$row['patient_id'];
				$date=$row['date1'];
				
			}
			// $stateJavascript=trim($stateJavascript,',');
		}
		 
		$resultcc=$this->execute_query("select * from cc_tbl where soap_note_id =".$this->value('shopnoteid')." ORDER BY `ccnumber` ASC" );
		if($this->num_rows($resultcc)>0){
			$i=0;
			while($row1=mysql_fetch_assoc($resultcc)){
				foreach($row1 as $key=>$value){
					//$value=urlencode(html_entity_decode(($this->jsesc($value))));
                    $value=stripcslashes($this->jsesc($value));
					if($i!=0)
						$key=$key.$i;
                    if(!empty($value))    
                    $printsoapdatacctbl[$i][$key] = $value;    
					$stateJavascript.="\"{$key}\":\"{$value}\",";
				}
				$i++;
				 
			}
		}
		$resultsoap_plan=$this->execute_query("select * from soap_plan where soap_note_id =".$this->value('shopnoteid'));
		if($this->num_rows($resultsoap_plan)>0){
			while($row2=mysql_fetch_assoc($resultsoap_plan)){
				$soap_plan_id=$row2['soap_plan_id'];
				foreach($row2 as $key=>$value){
					//$value=urlencode(html_entity_decode(($this->jsesc($value))));
                    $value=stripcslashes($this->jsesc($value));
                    if(!empty($value))
                    $printsoapdata[$key] = $value;
					$stateJavascript.="\"{$key}\":\"{$value}\",";
				}
			}
		}
		if($soap_plan_id!='' or $soap_plan_id!=0){
			$resultsoap_plan_detail=$this->execute_query("select * from soap_plan_description where soap_plan_id =".$soap_plan_id." order by soap_plan_description_id ASC");
			if($this->num_rows($resultsoap_plan_detail)>0){
				$i=0;
				while($row3=mysql_fetch_assoc($resultsoap_plan_detail)){
			   
					foreach($row3 as $key=>$value){
						//$value=urlencode(html_entity_decode(($this->jsesc($value))));
                        $value=stripcslashes($this->jsesc($value));
						$key=$key.$i;
                        if(!empty($value))
                        $printsoapdata[$key] = $value;
						$stateJavascript.="\"{$key}\":\"{$value}\",";
					}
					$i++;
				}
			}
		}
		$stateJavascript=trim($stateJavascript,',');
		//$stateJavascript='{'.$stateJavascript.'}';
		//echo "<pre>";print_r($printsoapdata);exit;
		$sql="select * from clinic where clinic_id=".$this->get_clinic_info($patient_id,'clinic_id');
		$result=$this->execute_query($sql);
		$row = $this->fetch_array($result);
		$providername=ucfirst($this->userInfo('name_title',$provider_id)).' '.ucfirst($this->userInfo('name_first',$provider_id)).' '.ucfirst($this->userInfo('name_last',$provider_id));
		$replace['prouct_count']	=	0;
		if($this->num_rows($resultsoap_plan_detail)!='')
		$replace['prouct_count']	=	$this->num_rows($resultsoap_plan_detail);
		$replace['patientname']=stripcslashes(html_entity_decode(ucfirst($this->userInfo('name_first',$patient_id)).' '.ucfirst($this->userInfo('name_last',$patient_id)),ENT_QUOTES, "UTF-8"));
		$replace['dateofbirth']=$this->userInfo('dob',$patient_id);
		$replace['soap_date']=$date;
		$replace['phone']=$row['phone'];
		$state='';
		if($row['country']=='US')
		$state=$this->config['state'][$row['state']];
		else
		$state=$this->config['canada_state'][$row['state']];
		if(isset($row['address2']) && !empty($row['address2']))
        $address2 = $row['address2'].", ";
        else
        $address2 = '';
        if(isset($row['address']) && !empty($row['address']))
        $address = $row['address'].", ";
        else
        $address = '';
        if(isset($row['city']) && !empty($row['city']))
        $city = $row['city'].", ";
        else
        $city = '';
		$replace['clinicaddress']= $address . $address2 . $city .$state.', '.$this->config['country'][$row['country']].', '.$row['zip'];
		$replace['providername']=stripcslashes(html_entity_decode(trim($providername),ENT_QUOTES, "UTF-8"));
		$replace['currentdate']=$date;
		$clinicname=stripcslashes(html_entity_decode($this->get_clinic_info($patient_id,'clinic_name'),ENT_QUOTES, "UTF-8"));
		$replace['clinicname']=$clinicname;
		$replace['stateJavascript']=$stateJavascript;
		$replace['body'] = $this->build_template($this->get_template("soapnotprint"),$replace);
		$replace['browser_title'] = "Provider view soap note";
		$this->output = $this->build_template($this->get_template("main"),$replace);
        
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              
        
        $resultsoap_plan=$this->execute_query("select *,DATE_FORMAT(create_datetime, '%m/%d/%Y') as date1 from soap_plan where soap_note_id =".$this->value('shopnoteid'));
		$row=mysql_fetch_assoc($resultsoap_plan);        
		//$paitentname=html_entity_decode($this->userInfo('name_first',$row['patient_id']).' '.$this->userInfo('name_last',$row['patient_id']),ENT_QUOTES, "UTF-8");
		//$providername=html_entity_decode($this->userInfo('name_title',$row['provider_id']).' '.$this->userInfo('name_first',$row['provider_id']).' '.$this->userInfo('name_last',$row['provider_id']),ENT_QUOTES, "UTF-8");
		//$date=$row['date1'];
		//$clinicname=html_entity_decode($this->get_clinic_info($row['patient_id'],'clinic_name'),ENT_QUOTES, "UTF-8");
		$patientname = stripcslashes(html_entity_decode(ucfirst($this->userInfo('name_first',$patient_id)).' '.ucfirst($this->userInfo('name_last',$patient_id)),ENT_QUOTES, "UTF-8")); 
		$soap_plan_id=$row['soap_plan_id'];
		 
		 
		require('include/fpdf/soapnotepdf.php');
		 
		$pdf = new PDF();
        $ra=array('pname'=>$replace['patientname'],'pdob'=>$replace['dateofbirth'],'dname'=>$replace['providername'],'dos'=>$replace['soap_date'],'cname'=>$replace['clinicname'],'ctel'=>$replace['phone'],'caddress'=>$replace['clinicaddress']);
        $_SESSION['header_info']=$ra;        
        //Subjective                
        if($this->num_rows($resultcc)>0)
        {
            $flag = 0;
            for($i=0;$i<$this->num_rows($resultcc);$i++)
            {
                if($i==0)
                {
                    $cc_id = 'cc_id';
                    $soap_note_id = 'soap_note_id';
                    $ccnumber = 'ccnumber';
                    $create_datetime = 'create_datetime';
                    $status = 'status';                    
                }
                else if($i==1)
                {
                    $cc_id = 'cc_id1';
                    $soap_note_id = 'soap_note_id1';
                    $ccnumber = 'ccnumber1';
                    $create_datetime = 'create_datetime1';
                    $status = 'status1'; 
                }
                else
                {
                    $cc_id = 'cc_id2';
                    $soap_note_id = 'soap_note_id2';
                    $ccnumber = 'ccnumber2';
                    $create_datetime = 'create_datetime2';
                    $status = 'status2'; 
                }
                unset($printsoapdatacctbl[$i][$cc_id]);
                unset($printsoapdatacctbl[$i][$soap_note_id]);
                unset($printsoapdatacctbl[$i][$ccnumber]);
                unset($printsoapdatacctbl[$i][$create_datetime]);
                unset($printsoapdatacctbl[$i][$status]);
                if(!empty($printsoapdatacctbl[$i]))
                $flag = 1;
                
            }
            if($flag)
            {
                $pdf->AddPage();        
                $pdf->SetFont('Arial','B',11);
                $pdf->Cell(0,10,'Subjective',0,1);                          
    		
                //echo "<pre>";print_r($printsoapdatacctbl);exit;
                for($i=0;$i<$this->num_rows($resultcc);$i++)
                {
                    
                    if($i==0)
                    {
                        $cc = 'chief_complaint';
                        $location = 'location';
                        $onset = 'onset';
                        $provocation = 'provocation';
                        $palliation = 'palliation';
                        $quality = 'quality';
                        $radiation = 'radiation';
                        $severity = 'severity';
                        $duration = 'duration';
                        $timing = 'timing';
                        $associated_signs_symptoms = 'associated_signs_symptoms';
                        $constitutional = 'constitutional';
                        $eyes = 'eyes';
                        $ears_nose_mouth_throat = 'ears_nose_mouth_throat';
                        $cardiovascular = 'cardiovascular';
                        $respiratory = 'respiratory';
                        $gastrointestinal = 'gastrointestinal';
                        $genital_urinary = 'genital_urinary';
                        $musculo_skeletal = 'musculo_skeletal';
                        $skin = 'skin';
                        $neurological = 'neurological';
                        $psychiatric = 'psychiatric';
                        $endocrine = 'endocrine';
                        $hematology_lymphatic = 'hematology_lymphatic';
                        $allergy_immunology = 'allergy_immunology';
                    }            
                    else if($i == 1)
                    {
                        $cc = 'chief_complaint1';
                        $location = 'location1';
                        $onset = 'onset1';
                        $provocation = 'provocation1';
                        $palliation = 'palliation1';
                        $quality = 'quality1';
                        $radiation = 'radiation1';
                        $severity = 'severity1';
                        $duration = 'duration1';
                        $timing = 'timing1';
                        $associated_signs_symptoms = 'associated_signs_symptoms1';
                        $constitutional = 'constitutional1';
                        $eyes = 'eyes1';
                        $ears_nose_mouth_throat = 'ears_nose_mouth_throat1';
                        $cardiovascular = 'cardiovascular1';
                        $respiratory = 'respiratory1';
                        $gastrointestinal = 'gastrointestinal1';
                        $genital_urinary = 'genital_urinary1';
                        $musculo_skeletal = 'musculo_skeletal1';
                        $skin = 'skin1';
                        $neurological = 'neurological1';
                        $psychiatric = 'psychiatric1';
                        $endocrine = 'endocrine1';
                        $hematology_lymphatic = 'hematology_lymphatic1';
                        $allergy_immunology = 'allergy_immunology1';
                    }            
                    else
                    {
                        $cc = 'chief_complaint2';
                        $location = 'location2';
                        $onset = 'onset2';
                        $provocation = 'provocation2';  
                        $palliation = 'palliation2';
                        $quality = 'quality2';  
                        $radiation = 'radiation2';
                        $severity = 'severity2';
                        $duration = 'duration2';
                        $timing = 'timing2';            
                        $associated_signs_symptoms = 'associated_signs_symptoms2';
                        $constitutional = 'constitutional2';
                        $eyes = 'eyes2';
                        $ears_nose_mouth_throat = 'ears_nose_mouth_throat2';
                        $cardiovascular = 'cardiovascular2';
                        $respiratory = 'respiratory2';
                        $gastrointestinal = 'gastrointestinal2';
                        $genital_urinary = 'genital_urinary2';
                        $musculo_skeletal = 'musculo_skeletal2';
                        $skin = 'skin2';
                        $neurological = 'neurological2';
                        $psychiatric = 'psychiatric2';
                        $endocrine = 'endocrine2';
                        $hematology_lymphatic = 'hematology_lymphatic2';
                        $allergy_immunology = 'allergy_immunology2';
                    }             
                    
                    if(isset($printsoapdatacctbl[$i][$cc]))
                    {
                        $pdf->SetFont('Arial','B',9);
                        $pdf->Cell(0,5,'Chief Complaint',0,1);
                        $pdf->SetFont('Arial','',9);                                    
                        $pdf->MultiCell(180,5,html_entity_decode($printsoapdatacctbl[$i][$cc],ENT_QUOTES, "UTF-8"),'1','L');
                        $pdf->Cell(0,3,'',0,1);
                    }                        
                    $pdf->SetFont('Arial','B',9);
                    if(isset($printsoapdatacctbl[$i][$location]) || isset($printsoapdatacctbl[$i][$onset]) || isset($printsoapdatacctbl[$i][$provocation]) || isset($printsoapdatacctbl[$i][$palliation]) || isset($printsoapdatacctbl[$i][$quality]) || isset($printsoapdatacctbl[$i][$radiation]) || isset($printsoapdatacctbl[$i][$severity]) || isset($printsoapdatacctbl[$i][$duration]) || isset($printsoapdatacctbl[$i][$timing]) || isset($printsoapdatacctbl[$i][$associated_signs_symptoms]))
                    $pdf->Cell(0,8,'History of Present Illness',0,1);
                    if(isset($printsoapdatacctbl[$i][$location]))
                    {
                        $pdf->Cell(0,5,'Location',0,1);
                        $pdf->SetFont('Arial','',9);                                    
                        $pdf->MultiCell(180,5,html_entity_decode($printsoapdatacctbl[$i][$location],ENT_QUOTES, "UTF-8"),'1','L');
                        $pdf->Cell(0,3,'',0,1);
                    }            
                    if(isset($printsoapdatacctbl[$i][$onset]))
                    {
                        $pdf->SetFont('Arial','B',9);
                        $pdf->Cell(0,5,'Onset',0,1);
                        $pdf->SetFont('Arial','',9);            
                        $pdf->MultiCell(180,5,html_entity_decode($printsoapdatacctbl[$i][$onset],ENT_QUOTES, "UTF-8"),'1','L');
                        $pdf->Cell(0,3,'',0,1);
                    }
                    
                    if(isset($printsoapdatacctbl[$i][$provocation]))
                    {
                        $pdf->SetFont('Arial','B',9);
                        $pdf->Cell(0,5,'Provocation',0,1);
                        $pdf->SetFont('Arial','',9);            
                        $pdf->MultiCell(180,5,html_entity_decode($printsoapdatacctbl[$i][$provocation],ENT_QUOTES, "UTF-8"),'1','L');
                        $pdf->Cell(0,3,'',0,1);
                    }
                    
                    if(isset($printsoapdatacctbl[$i][$palliation]))
                    {
                        $pdf->SetFont('Arial','B',9);
                        $pdf->Cell(0,5,'Palliation',0,1);
                        $pdf->SetFont('Arial','',9);            
                        $pdf->MultiCell(180,5,html_entity_decode($printsoapdatacctbl[$i][$palliation],ENT_QUOTES, "UTF-8"),'1','L');
                        $pdf->Cell(0,3,'',0,1);
                    }
                    
                    if(isset($printsoapdatacctbl[$i][$quality]))
                    {
                        $pdf->SetFont('Arial','B',9);
                        $pdf->Cell(0,5,'Quality',0,1);
                        $pdf->SetFont('Arial','',9);            
                        $pdf->MultiCell(180,5,html_entity_decode($printsoapdatacctbl[$i][$quality],ENT_QUOTES, "UTF-8"),'1','L');
                        $pdf->Cell(0,3,'',0,1);
                    }
                    
                    if(isset($printsoapdatacctbl[$i][$radiation]))
                    {
                        $pdf->SetFont('Arial','B',9);
                        $pdf->Cell(0,5,'Radiation',0,1);
                        $pdf->SetFont('Arial','',9);            
                        $pdf->MultiCell(180,5,html_entity_decode($printsoapdatacctbl[$i][$radiation],ENT_QUOTES, "UTF-8"),'1','L');
                        $pdf->Cell(0,3,'',0,1);
                    }
                    
                    if(isset($printsoapdatacctbl[$i][$severity]))
                    {
                        $pdf->SetFont('Arial','B',9);
                        $pdf->Cell(0,5,'Severity',0,1);
                        $pdf->SetFont('Arial','',9);            
                        $pdf->MultiCell(180,5,html_entity_decode($printsoapdatacctbl[$i][$severity],ENT_QUOTES, "UTF-8"),'1','L');
                        $pdf->Cell(0,3,'',0,1);
                    }
                    
                    if(isset($printsoapdatacctbl[$i][$duration]))
                    {
                        $pdf->SetFont('Arial','B',9);
                        $pdf->Cell(0,5,'Duration',0,1);
                        $pdf->SetFont('Arial','',9);            
                        $pdf->MultiCell(180,5,html_entity_decode($printsoapdatacctbl[$i][$duration],ENT_QUOTES, "UTF-8"),'1','L');
                        $pdf->Cell(0,3,'',0,1);
                    }
                    
                    if(isset($printsoapdatacctbl[$i][$timing]))
                    {
                        $pdf->SetFont('Arial','B',9);
                        $pdf->Cell(0,5,'Timing',0,1);
                        $pdf->SetFont('Arial','',9);            
                        $pdf->MultiCell(180,5,html_entity_decode($printsoapdatacctbl[$i][$timing],ENT_QUOTES, "UTF-8"),'1','L');
                        $pdf->Cell(0,3,'',0,1);
                    }
                    
                    if(isset($printsoapdatacctbl[$i][$associated_signs_symptoms]))
                    {
                        $pdf->SetFont('Arial','B',9);
                        $pdf->Cell(0,5,'Associated signs/symptoms',0,1);
                        $pdf->SetFont('Arial','',9);            
                        $pdf->MultiCell(180,5,html_entity_decode($printsoapdatacctbl[$i][$associated_signs_symptoms],ENT_QUOTES, "UTF-8"),'1','L');
                        $pdf->Cell(0,3,'',0,1);
                    }            
                    
                    
                    $pdf->SetFont('Arial','B',9);
                    if(isset($printsoapdatacctbl[$i][$constitutional]) || isset($printsoapdatacctbl[$i][$eyes]) || isset($printsoapdatacctbl[$i][$ears_nose_mouth_throat]) || isset($printsoapdatacctbl[$i][$cardiovascular]) || isset($printsoapdatacctbl[$i][$respiratory]) || isset($printsoapdatacctbl[$i][$gastrointestinal]) || isset($printsoapdatacctbl[$i][$genital_urinary]) || isset($printsoapdatacctbl[$i][$musculo_skeletal]) || isset($printsoapdatacctbl[$i][$skin]) || isset($printsoapdatacctbl[$i][$neurological]) || isset($printsoapdatacctbl[$i][$psychiatric]) || isset($printsoapdatacctbl[$i][$endocrine]) || isset($printsoapdatacctbl[$i][$hematology_lymphatic]) || isset($printsoapdatacctbl[$i][$allergy_immunology]))
                    $pdf->Cell(0,8,'Review of Systems',0,1);
                    
                    if(isset($printsoapdatacctbl[$i][$constitutional]))
                    {
                        $pdf->Cell(0,5,'Constitutional',0,1);
                        $pdf->SetFont('Arial','',9);            
                        $pdf->MultiCell(180,5,html_entity_decode($printsoapdatacctbl[$i][$constitutional],ENT_QUOTES, "UTF-8"),'1','L');
                        $pdf->Cell(0,3,'',0,1);
                    }
                    
                    if(isset($printsoapdatacctbl[$i][$eyes]))
                    {
                        $pdf->SetFont('Arial','B',9);
                        $pdf->Cell(0,5,'Eyes',0,1);
                        $pdf->SetFont('Arial','',9);            
                        $pdf->MultiCell(180,5,html_entity_decode($printsoapdatacctbl[$i][$eyes],ENT_QUOTES, "UTF-8"),'1','L');
                        $pdf->Cell(0,3,'',0,1);
                    }
                    
                    if(isset($printsoapdatacctbl[$i][$ears_nose_mouth_throat]))
                    {
                        $pdf->SetFont('Arial','B',9);
                        $pdf->Cell(0,5,'Ears, nose, mouth, throat',0,1);
                        $pdf->SetFont('Arial','',9);            
                        $pdf->MultiCell(180,5,html_entity_decode($printsoapdatacctbl[$i][$ears_nose_mouth_throat],ENT_QUOTES, "UTF-8"),'1','L');
                        $pdf->Cell(0,3,'',0,1);            
                    }
                    
                    if(isset($printsoapdatacctbl[$i][$cardiovascular]))
                    {
                        $pdf->SetFont('Arial','B',9);
                        $pdf->Cell(0,5,'Cardiovascular',0,1);
                        $pdf->SetFont('Arial','',9);            
                        $pdf->MultiCell(180,5,html_entity_decode($printsoapdatacctbl[$i][$cardiovascular],ENT_QUOTES, "UTF-8"),'1','L');
                        $pdf->Cell(0,3,'',0,1);
                    }
                    
                    if(isset($printsoapdatacctbl[$i][$respiratory]))
                    {
                        $pdf->SetFont('Arial','B',9);
                        $pdf->Cell(0,5,'Respiratory',0,1);
                        $pdf->SetFont('Arial','',9);            
                        $pdf->MultiCell(180,5,html_entity_decode($printsoapdatacctbl[$i][$respiratory],ENT_QUOTES, "UTF-8"),'1','L');
                        $pdf->Cell(0,3,'',0,1);
                    }
                    
                    if(isset($printsoapdatacctbl[$i][$gastrointestinal]))
                    {
                        $pdf->SetFont('Arial','B',9);
                        $pdf->Cell(0,5,'Gastrointestinal',0,1);
                        $pdf->SetFont('Arial','',9);            
                        $pdf->MultiCell(180,5,html_entity_decode($printsoapdatacctbl[$i][$gastrointestinal],ENT_QUOTES, "UTF-8"),'1','L');
                        $pdf->Cell(0,3,'',0,1);
                    }
                    
                    if(isset($printsoapdatacctbl[$i][$genital_urinary]))
                    {
                        $pdf->SetFont('Arial','B',9);
                        $pdf->Cell(0,5,'Genital-urinary',0,1);
                        $pdf->SetFont('Arial','',9);            
                        $pdf->MultiCell(180,5,html_entity_decode($printsoapdatacctbl[$i][$genital_urinary],ENT_QUOTES, "UTF-8"),'1','L');
                        $pdf->Cell(0,3,'',0,1);
                    }
                    
                    if(isset($printsoapdatacctbl[$i][$musculo_skeletal]))
                    {
                        $pdf->SetFont('Arial','B',9);
                        $pdf->Cell(0,5,'Musculo-skeletal',0,1);
                        $pdf->SetFont('Arial','',9);            
                        $pdf->MultiCell(180,5,html_entity_decode($printsoapdatacctbl[$i][$musculo_skeletal],ENT_QUOTES, "UTF-8"),'1','L');
                        $pdf->Cell(0,3,'',0,1);
                    }
                    
                    if(isset($printsoapdatacctbl[$i][$skin]))
                    {
                        $pdf->SetFont('Arial','B',9);
                        $pdf->Cell(0,5,'Skin',0,1);
                        $pdf->SetFont('Arial','',9);            
                        $pdf->MultiCell(180,5,html_entity_decode($printsoapdatacctbl[$i][$skin],ENT_QUOTES, "UTF-8"),'1','L');
                        $pdf->Cell(0,3,'',0,1);
                    }
                    
                    if(isset($printsoapdatacctbl[$i][$neurological]))
                    {
                        $pdf->SetFont('Arial','B',9);
                        $pdf->Cell(0,5,'Neurological',0,1);
                        $pdf->SetFont('Arial','',9);            
                        $pdf->MultiCell(180,5,html_entity_decode($printsoapdatacctbl[$i][$neurological],ENT_QUOTES, "UTF-8"),'1','L');
                        $pdf->Cell(0,3,'',0,1);
                    }
                    
                    if(isset($printsoapdatacctbl[$i][$psychiatric]))
                    {
                        $pdf->SetFont('Arial','B',9);
                        $pdf->Cell(0,5,'Psychiatric',0,1);
                        $pdf->SetFont('Arial','',9);            
                        $pdf->MultiCell(180,5,html_entity_decode($printsoapdatacctbl[$i][$psychiatric],ENT_QUOTES, "UTF-8"),'1','L');
                        $pdf->Cell(0,3,'',0,1);
                    }
                    
                    if(isset($printsoapdatacctbl[$i][$endocrine]))
                    {
                        $pdf->SetFont('Arial','B',9);
                        $pdf->Cell(0,5,'Endocrine',0,1);
                        $pdf->SetFont('Arial','',9);            
                        $pdf->MultiCell(180,5,html_entity_decode($printsoapdatacctbl[$i][$endocrine],ENT_QUOTES, "UTF-8"),'1','L');
                        $pdf->Cell(0,3,'',0,1);
                    }
                    
                    if(isset($printsoapdatacctbl[$i][$hematology_lymphatic]))
                    {
                        $pdf->SetFont('Arial','B',9);
                        $pdf->Cell(0,5,'Hematology/lymphatic',0,1);
                        $pdf->SetFont('Arial','',9);            
                        $pdf->MultiCell(180,5,html_entity_decode($printsoapdatacctbl[$i][$hematology_lymphatic],ENT_QUOTES, "UTF-8"),'1','L');
                        $pdf->Cell(0,3,'',0,1);
                    }
                    
                    if(isset($printsoapdatacctbl[$i][$allergy_immunology]))
                    {
                        $pdf->SetFont('Arial','B',9);
                        $pdf->Cell(0,5,'Allergy/immunology',0,1);
                        $pdf->SetFont('Arial','',9);            
                        $pdf->MultiCell(180,5,html_entity_decode($printsoapdatacctbl[$i][$allergy_immunology],ENT_QUOTES, "UTF-8"),'1','L');
                        $pdf->Cell(0,3,'',0,1);
                    }            
                    
                }
            }
            
        } 
        //Objective and Assesment         
        if($this->num_rows($result)>0)  
        {
            //Objective
            $printsoapnoteobj = $printsoapdataobjass;
            unset($printsoapnoteobj['dx']);
            unset($printsoapnoteobj['ddx']);
            unset($printsoapnoteobj['soap_note_id']);
            unset($printsoapnoteobj['provider_id']);
            unset($printsoapnoteobj['patient_id']);
            unset($printsoapnoteobj['create_datetime']);
            unset($printsoapnoteobj['status']);
            unset($printsoapnoteobj['date1']);
            if(!empty($printsoapnoteobj))
            {
                $pdf->Ln();                        
                $pdf->AddPage(); // page break.    
                $pdf->SetFont('Arial','B',11);            
                $pdf->Cell(0,10,'Objective',0,1); 
                if(isset($printsoapnoteobj['ob_vitals']))
                {
                    $pdf->SetFont('Arial','B',9);
                    $pdf->Cell(0,5,'Vitals',0,1);
                    $pdf->SetFont('Arial','',9);
                    $pdf->MultiCell(180,5,html_entity_decode($printsoapnoteobj['ob_vitals'],ENT_QUOTES, "UTF-8"),'1','L');
                    $pdf->Cell(0,3,'',0,1);
                }
                
                if(isset($printsoapnoteobj['ob_constitutional']))
                {
                    $pdf->SetFont('Arial','B',9);
                    $pdf->Cell(0,5,'Constitutional',0,1);
                    $pdf->SetFont('Arial','',9);
                    $pdf->MultiCell(180,5,html_entity_decode($printsoapnoteobj['ob_constitutional'],ENT_QUOTES, "UTF-8"),'1','L');
                    $pdf->Cell(0,3,'',0,1); 
                }    
                 
                if(isset($printsoapnoteobj['ob_eyes']))
                {
                    $pdf->SetFont('Arial','B',9);
                    $pdf->Cell(0,5,'Eyes',0,1);
                    $pdf->SetFont('Arial','',9);
                    $pdf->MultiCell(180,5,html_entity_decode($printsoapnoteobj['ob_eyes'],ENT_QUOTES, "UTF-8"),'1','L');
                    $pdf->Cell(0,3,'',0,1); 
                }
                
                if(isset($printsoapnoteobj['ob_ears_nose_mouth_throat']))
                {
                    $pdf->SetFont('Arial','B',9);
                    $pdf->Cell(0,5,'Ears, nose, mouth, throat',0,1);
                    $pdf->SetFont('Arial','',9);
                    $pdf->MultiCell(180,5,html_entity_decode($printsoapnoteobj['ob_ears_nose_mouth_throat'],ENT_QUOTES, "UTF-8"),'1','L');
                    $pdf->Cell(0,3,'',0,1); 
                }
                
                if(isset($printsoapnoteobj['ob_cardiovascular']))
                {
                    $pdf->SetFont('Arial','B',9);
                    $pdf->Cell(0,5,'Cardiovascular',0,1);
                    $pdf->SetFont('Arial','',9);
                    $pdf->MultiCell(180,5,html_entity_decode($printsoapnoteobj['ob_cardiovascular'],ENT_QUOTES, "UTF-8"),'1','L');
                    $pdf->Cell(0,3,'',0,1);
                }
                
                if(isset($printsoapnoteobj['ob_respiratory']))
                {
                    $pdf->SetFont('Arial','B',9);
                    $pdf->Cell(0,5,'Respiratory',0,1);
                    $pdf->SetFont('Arial','',9);
                    $pdf->MultiCell(180,5,html_entity_decode($printsoapnoteobj['ob_respiratory'],ENT_QUOTES, "UTF-8"),'1','L');
                    $pdf->Cell(0,3,'',0,1);
                }
                
                if(isset($printsoapnoteobj['ob_gastrointestinal']))
                {
                    $pdf->SetFont('Arial','B',9);
                    $pdf->Cell(0,5,'Gastrointestinal',0,1);
                    $pdf->SetFont('Arial','',9);
                    $pdf->MultiCell(180,5,html_entity_decode($printsoapnoteobj['ob_gastrointestinal'],ENT_QUOTES, "UTF-8"),'1','L');
                    $pdf->Cell(0,3,'',0,1);
                }
                
                if(isset($printsoapnoteobj['ob_genital_urinary']))
                {
                    $pdf->SetFont('Arial','B',9);
                    $pdf->Cell(0,5,'Genital-urinary',0,1);
                    $pdf->SetFont('Arial','',9);
                    $pdf->MultiCell(180,5,html_entity_decode($printsoapnoteobj['ob_genital_urinary'],ENT_QUOTES, "UTF-8"),'1','L');
                    $pdf->Cell(0,3,'',0,1);
                }
                
                if(isset($printsoapnoteobj['ob_musculo_skeletal']))
                {
                    $pdf->SetFont('Arial','B',9);
                    $pdf->Cell(0,5,'Musculo-skeletal',0,1);
                    $pdf->SetFont('Arial','',9);
                    $pdf->MultiCell(180,5,html_entity_decode($printsoapnoteobj['ob_musculo_skeletal'],ENT_QUOTES, "UTF-8"),'1','L');
                    $pdf->Cell(0,3,'',0,1);
                }
                
                if(isset($printsoapnoteobj['ob_skin']))
                {
                    $pdf->SetFont('Arial','B',9);
                    $pdf->Cell(0,5,'Skin',0,1);
                    $pdf->SetFont('Arial','',9);
                    $pdf->MultiCell(180,5,html_entity_decode($printsoapnoteobj['ob_skin'],ENT_QUOTES, "UTF-8"),'1','L');
                    $pdf->Cell(0,3,'',0,1);
                }
                
                if(isset($printsoapnoteobj['ob_neurological']))
                {
                    $pdf->SetFont('Arial','B',9);
                    $pdf->Cell(0,5,'Neurological',0,1);
                    $pdf->SetFont('Arial','',9);
                    $pdf->MultiCell(180,5,html_entity_decode($printsoapnoteobj['ob_neurological'],ENT_QUOTES, "UTF-8"),'1','L');
                    $pdf->Cell(0,3,'',0,1);
                }
                
                if(isset($printsoapnoteobj['ob_psychiatric']))
                {
                   $pdf->SetFont('Arial','B',9);
                    $pdf->Cell(0,5,'Psychiatric',0,1);
                    $pdf->SetFont('Arial','',9);
                    $pdf->MultiCell(180,5,html_entity_decode($printsoapnoteobj['ob_psychiatric'],ENT_QUOTES, "UTF-8"),'1','L');
                    $pdf->Cell(0,3,'',0,1);
                }
                
                if(isset($printsoapnoteobj['ob_endocrine']))
                {
                    $pdf->SetFont('Arial','B',9);
                    $pdf->Cell(0,5,'Endocrine',0,1);
                    $pdf->SetFont('Arial','',9);
                    $pdf->MultiCell(180,5,html_entity_decode($printsoapnoteobj['ob_endocrine'],ENT_QUOTES, "UTF-8"),'1','L');
                    $pdf->Cell(0,3,'',0,1);
                }
                
                if(isset($printsoapnoteobj['ob_hematology_lymphatic']))
                {
                    $pdf->SetFont('Arial','B',9);
                    $pdf->Cell(0,5,'Hematology/lymphatic',0,1);
                    $pdf->SetFont('Arial','',9);
                    $pdf->MultiCell(180,5,html_entity_decode($printsoapnoteobj['ob_hematology_lymphatic'],ENT_QUOTES, "UTF-8"),'1','L');
                    $pdf->Cell(0,3,'',0,1);
                }
                
                if(isset($printsoapnoteobj['ob_allergy_immunology']))
                {
                    $pdf->SetFont('Arial','B',9);
                    $pdf->Cell(0,5,'Allergy/immunology',0,1);
                    $pdf->SetFont('Arial','',9);
                    $pdf->MultiCell(180,5,html_entity_decode($printsoapnoteobj['ob_allergy_immunology'],ENT_QUOTES, "UTF-8"),'1','L');
                    $pdf->Cell(0,3,'',0,1);
                }
                
                if(isset($printsoapnoteobj['ob_eav']))
                {
                    $pdf->SetFont('Arial','B',9);
                    $pdf->Cell(0,5,'EAV',0,1);
                    $pdf->SetFont('Arial','',9);
                    $pdf->MultiCell(180,5,html_entity_decode($printsoapnoteobj['ob_eav'],ENT_QUOTES, "UTF-8"),'1','L');
                    $pdf->Cell(0,3,'',0,1);
                }
                
                if(isset($printsoapnoteobj['ob_laboratory']))
                {
                    $pdf->SetFont('Arial','B',9);
                    $pdf->Cell(0,5,'Laboratory',0,1);
                    $pdf->SetFont('Arial','',9);
                    $pdf->MultiCell(180,5,html_entity_decode($printsoapnoteobj['ob_laboratory'],ENT_QUOTES, "UTF-8"),'1','L');
                    $pdf->Cell(0,3,'',0,1);
                }
                
            }
             //Assessment   
             if(isset($printsoapdataobjass['dx']) || isset($printsoapdataobjass['ddx'])) 
             {
                $pdf->Ln();                        
                $pdf->AddPage(); // page break.    
                $pdf->SetFont('Arial','B',11);
                $pdf->Cell(0,10,'Assessment',0,1); 
                if(isset($printsoapdataobjass['dx']))
                {
                    $pdf->SetFont('Arial','B',9);
                    $pdf->Cell(0,5,'DX',0,1);
                    $pdf->SetFont('Arial','',9);
                    $pdf->MultiCell(180,5,html_entity_decode($printsoapdataobjass['dx'],ENT_QUOTES, "UTF-8"),'1','L');
                    $pdf->Cell(0,3,'',0,1);
                }
                
                if(isset($printsoapdataobjass['ddx']))  
                {
                    $pdf->SetFont('Arial','B',9);
                    $pdf->Cell(0,5,'DDX',0,1);
                    $pdf->SetFont('Arial','',9);
                    $pdf->MultiCell(180,5,html_entity_decode($printsoapdataobjass['ddx'],ENT_QUOTES, "UTF-8"),'1','L');
                    $pdf->Cell(0,3,'',0,1); 
                }   
                
             }   
                
        }
         
        //Programme
        $pdf->Ln();                        
        $pdf->AddPage(); // page break.
        $pdf->SetFont('Arial','',18);
		$pdf->SetTextColor(0,125,210);
		//$pdf->SetXY(0, 60);
		$pdf->Cell(0,0,$clinicname,0,0,'C');
		$pdf->SetFont('Arial','',12);
		$pdf->SetTextColor(134,179,0);
		$pdf->SetXY(35, 60);
		$pdf->Cell(10,30,'RECOMMENDED PATIENT PROTOCOL / TREATMENT PROGRAM');
		$pdf->SetTextColor(0,0,0);
		$pdf->SetXY(10, 70);
		$pdf->SetFont('Arial','B',11);;
		$pdf->Cell(10,30,'Patient:');
		$pdf->SetX(25);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(10,30,$patientname);
		$pdf->SetX(80);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(10,30,'Doctor:');
		$pdf->SetX(95);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(10,30,$replace['providername']);
		$pdf->SetX(170);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(10,30,'Date:');
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(2,30,$date);
		$pdf->SetXY(10,90);
		$pdf->MultiCell(180,5,'The following products and/or protocols have been specifically chosen for their effectiveness, quality and potency, so it is of tantamount importance that you follow the instructions exactly. Any deviation from the specific recommendations may influence the success of your treatment program. If you have any questions please let us know immediately.',0,1);
		$pdf->SetXY(40,115);
		$pdf->SetTextColor(2,117,155);
		$pdf->Cell(0,0,'PLEASE BRING THIS SHEET IN WITH YOU WHEN PICKING UP REFILLS.');
		$pdf->SetTextColor(0,0,0);
		$pdf->SetXY(10,110);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(10,40,'Dietary Instructions:');
		$x=$pdf->GetX();
		$y=$pdf->GetY();
		$pdf->SetFont('Arial','',10);
		$pdf->SetXY(10,$y+23);
		$pdf->MultiCell(180,5,html_entity_decode($row['dietary_instructions'],ENT_QUOTES, "UTF-8"),'1','L');
		// Header
		$pdf->MultiCell(0,30,'','0','L',0); 
		$x=$pdf->GetX();
		$y=$pdf->GetY();
		$pdf->SetXY($x,$y);
		
		$header=array('Product Description','Arising','Breakfast','Mid AM','Lunch','Mid PM','Dinner','Before Bed','Comments');
		//$pdf->SetXY(0,80);
		
		$x=$x+50;
		//$y=99;
		$x1=10;
		$pdf->SetFont('Arial','B',11);
		
		foreach($header as $col){
			if($col!='Product Description' and $col!='Comments'){
				$pdf->TextWithDirection($x,$y,$col,'U');
				$x=$x+16;
			}else{
		//$pdf->SetXY($x,$y);				
				//$pdf->Cell($x,50,'',1,0,'L',1);
				$pdf->Text($x1+2,$y,$col);
				$x1=$x1+152;
			}

		}
		$pdf->Ln();
		$pdf->Line(10,$y-25,202,$y-25);
		$pdf->Line(10,$y-25,10,$y+8);
		$pdf->Line(50,$y-25,50,$y+8);
		$pdf->Line(66,$y-25,66,$y+8);
		$pdf->Line(82,$y-25,82,$y+8);
		$pdf->Line(98,$y-25,98,$y+8);
		$pdf->Line(114,$y-25,114,$y+8);
		$pdf->Line(130,$y-25,130,$y+8);
		$pdf->Line(146,$y-25,146,$y+8);
		$pdf->Line(162,$y-25,162,$y+8);
		$pdf->Line(202,$y-25,202,$y+8);
		// Data
		$x=$pdf->GetX();
		$y=$pdf->GetY();
		$pdf->SetXY($x,$y-22);
		$pdf->SetFont('Arial','',10);
		if($soap_plan_id!='' or $soap_plan_id!=0){
			$resultsoap_plan_detail=$this->execute_query("select * from soap_plan_description where soap_plan_id =".$soap_plan_id." order by soap_plan_description_id ASC");
			if($this->num_rows($resultsoap_plan_detail)>0){
				$i=0;
				while($row3=mysql_fetch_assoc($resultsoap_plan_detail)){
					$x=40;
					$val=array();
					foreach($row3 as $key=>$value){
						if($key!='soap_plan_description_id' and $key!='soap_plan_id' and $key!='create_datetime' and $key!='status' and $key!='total_per_day' and $key!='no_of_bottles'){
							/*if($key=='comments')
							 $x=40;
							$pdf->Cell($x,10,$value,1);
							$x=11;*/
							$val[]=html_entity_decode($value,ENT_QUOTES, "UTF-8");
						}
					}
					$pdf->SetWidths(array(40,16,16,16,16,16,16,16,40));
					$pdf->Row($val);
				}
			}
		}
			
		//dietary_instructions
		//
		$x=$pdf->GetX();
		$y=$pdf->GetY();
		$pdf->SetXY($x,$y);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(40,10,'Notes and/or additional instructions:');

		$pdf->SetFont('Arial','',10);
		$pdf->SetXY(10,$y+8);
		$pdf->MultiCell(180,5,html_entity_decode($row['notes_instructions'],ENT_QUOTES, "UTF-8"),'1','L');
            		
		$pdf->Output('SOAP Note.pdf','I');
	}
    /************Code modified by pawan khandelwal for TXM-17 issue in jira updated on 08 august 2013 ends here*******************************************/  
	/**
	 *This function create pdf program
	 *
	 *
	 ***/
	function creat_pdf_plan_program(){
		$resultsoap_plan=$this->execute_query("select *,DATE_FORMAT(create_datetime, '%m/%d/%Y') as date1 from soap_plan where soap_plan_id =".$this->value('soap_plan_id'));
		$row=mysql_fetch_assoc($resultsoap_plan);
		$paitentname=html_entity_decode(ucfirst($this->userInfo('name_first',$row['patient_id'])).' '.ucfirst($this->userInfo('name_last',$row['patient_id'])),ENT_QUOTES, "UTF-8");
		$providername=html_entity_decode(ucfirst($this->userInfo('name_title',$row['provider_id'])).' '.ucfirst($this->userInfo('name_first',$row['provider_id'])).' '.ucfirst($this->userInfo('name_last',$row['provider_id'])),ENT_QUOTES, "UTF-8");
		$date=$row['date1'];
		$clinicname=html_entity_decode($this->get_clinic_info($row['patient_id'],'clinic_name'),ENT_QUOTES, "UTF-8");
		 
		$soap_plan_id=$this->value('soap_plan_id');
		 
		 
		require('include/fpdf/fpdf.php');
		 
		$pdf = new FPDF();
		$pdf->AddPage();
		$pdf->SetFont('Arial','',18);
		$pdf->SetTextColor(0,125,210);
		$pdf->SetXY(0, 10);
		$pdf->Cell(0,0,$clinicname,0,0,'C');
		$pdf->SetFont('Arial','',12);
		$pdf->SetTextColor(134,179,0);
		$pdf->SetXY(50, 10);
		$pdf->Cell(10,30,'RECOMMENDED PATIENT PROTOCOL / TREATMENT PROGRAM');
		$pdf->SetTextColor(0,0,0);
		$pdf->SetXY(10, 20);
		$pdf->SetFont('Arial','B',11);;
		$pdf->Cell(10,30,'Patient:');
		$pdf->SetX(25);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(10,30,$paitentname);
		$pdf->SetX(80);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(10,30,'Doctor:');
		$pdf->SetX(95);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(10,30,$providername);
		$pdf->SetX(170);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(10,30,'Date:');
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(2,30,$date);
		$pdf->SetXY(10,40);
		$pdf->MultiCell(180,5,'The following products and/or protocols have been specifically chosen for their effectiveness, quality and potency, so it is of tantamount importance that you follow the instructions exactly. Any deviation from the specific recommendations may influence the success of your treatment program. If you have any questions please let us know immediately.');
		$pdf->SetXY(40,65);
		$pdf->SetTextColor(2,117,155);
		$pdf->Cell(0,0,'PLEASE BRING THIS SHEET IN WITH YOU WHEN PICKING UP REFILLS.');
		$pdf->SetTextColor(0,0,0);
		$pdf->SetXY(10,60);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(10,40,'Dietary Instructions:');
		$x=$pdf->GetX();
		$y=$pdf->GetY();
		$pdf->SetFont('Arial','',10);
		$pdf->SetXY(10,$y+23);
		$pdf->MultiCell(180,5,html_entity_decode($row['dietary_instructions'],ENT_QUOTES, "UTF-8"),'1','L');
		// Header
		$pdf->MultiCell(0,30,'','0','L',0); 
		$x=$pdf->GetX();
		$y=$pdf->GetY();
		$pdf->SetXY($x,$y);
		
		$header=array('Product Description','Arising','Breakfast','Mid AM','Lunch','Mid PM','Dinner','Before Bed','Comments');
		//$pdf->SetXY(0,80);
		
		$x=$x+50;
		//$y=99;
		$x1=10;
		$pdf->SetFont('Arial','B',11);
		
		foreach($header as $col){
			if($col!='Product Description' and $col!='Comments'){
				$pdf->TextWithDirection($x,$y,$col,'U');
				$x=$x+16;
			}else{
		//$pdf->SetXY($x,$y);				
				//$pdf->Cell($x,50,'',1,0,'L',1);
				$pdf->Text($x1+2,$y,$col);
				$x1=$x1+152;
			}

		}
		$pdf->Ln();
		$pdf->Line(10,$y-25,202,$y-25);
		$pdf->Line(10,$y-25,10,$y+8);
		$pdf->Line(50,$y-25,50,$y+8);
		$pdf->Line(66,$y-25,66,$y+8);
		$pdf->Line(82,$y-25,82,$y+8);
		$pdf->Line(98,$y-25,98,$y+8);
		$pdf->Line(114,$y-25,114,$y+8);
		$pdf->Line(130,$y-25,130,$y+8);
		$pdf->Line(146,$y-25,146,$y+8);
		$pdf->Line(162,$y-25,162,$y+8);
		$pdf->Line(202,$y-25,202,$y+8);
		// Data
		$x=$pdf->GetX();
		$y=$pdf->GetY();
		$pdf->SetXY($x,$y-22);
		$pdf->SetFont('Arial','',10);
		if($soap_plan_id!='' or $soap_plan_id!=0){
			$resultsoap_plan_detail=$this->execute_query("select * from soap_plan_description where soap_plan_id =".$soap_plan_id." order by soap_plan_description_id ASC");
			if($this->num_rows($resultsoap_plan_detail)>0){
				$i=0;
				while($row3=mysql_fetch_assoc($resultsoap_plan_detail)){
					$x=40;
					$val=array();
					foreach($row3 as $key=>$value){
						if($key!='soap_plan_description_id' and $key!='soap_plan_id' and $key!='create_datetime' and $key!='status' and $key!='total_per_day' and $key!='no_of_bottles'){
							/*if($key=='comments')
							 $x=40;
							$pdf->Cell($x,10,$value,1);
							$x=11;*/
							$val[]=html_entity_decode($value,ENT_QUOTES, "UTF-8");
						}
					}
					$pdf->SetWidths(array(40,16,16,16,16,16,16,16,40));
					$pdf->Row($val);
				}
			}
		}
			
		//dietary_instructions
		//
		$x=$pdf->GetX();
		$y=$pdf->GetY();
		$pdf->SetXY($x,$y);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(40,10,'Notes and/or additional instructions:');

		$pdf->SetFont('Arial','',10);
		$pdf->SetXY(10,$y+8);
		$pdf->MultiCell(180,5,html_entity_decode($row['notes_instructions'],ENT_QUOTES, "UTF-8"),'1','L');
		$pdf->Output();
	}

	/**
	 * populate side panel in page.
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

	function treatementprogramlist(){
		$pat_id=$this->value('id');
		$sql="SELECT * ,DATE_FORMAT(create_datetime, '%m/%d/%Y') as date1 FROM soap_plan WHERE patient_id=".$pat_id." order by soap_plan_id desc";
		$result=$this->execute_query($sql);
		$numrow=$this->num_rows($result);
		if($numrow>0){
			//$var.="<div class=\"list\" id=\"soapnote\"><table cellpadding=\"2\" cellspacing=\"2\" border=\"0\" width=\"100%\" style=\"font-size:12px;\" >";
			while($row = $this->fetch_array($result)){
				//$style = ($c++%2)?"line1":"line2";
				$providerId=$row['provider_id'];
				$providername=$this->userInfo('name_title',$providerId).' '.$this->userInfo('name_first',$providerId).' '.$this->userInfo('name_last',$providerId);
				$date=$row['date1'];
				$var.="<a href=\"index.php?action=creat_pdf_plan_program&soap_plan_id=$row[soap_plan_id]\" target=\"_blank\" style=\"color:#0069A0; display:block;  border-bottom:#bbb solid 1px;padding:4px 15px;\">".$providername." "."$date </a>";
				 
			}
			//$var.='</table></div>';

			$varhead="<div style=\"width:280px; float:left;height:22px; color:#0069A0; background:url(/images/bg-gray-heading.gif) top left repeat-x; border-top:#bbb solid 1px;border-bottom:#bbb solid 1px; padding:2px 0px 0px 10px;margin:6px 5px;\"><b>Treatment Program</b></div><div style=\"clear:both\"></div>";
			// $varhead="<div style=\"width:280px; float:left; color:#0069A0; background:url(/images/bg-gray-heading.gif) top left repeat-x;border-top:#bbb solid 1px;border-bottom:#bbb solid 1px;padding:2px 0px;margin:6px 5px;\"><b>SOAP Notes</b></div><div style=\"clear:both\"></div>";
		}

		echo  $varhead.$var;
		 
		 
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
	function account_soap_services(){
		$clinicId = $this->clinicInfo("clinic_id");

		// Code to save the details
		if(isset($_POST) && $_POST['submit']=='Save'){

			$unfilledFlag='0';
			/*echo '<pre>'; print_r($_POST); echo '</pre>';
			 die;*/
			unset($_POST['submit']);
			unset($_POST['action']);
			unset($_POST['totalRows']);
			unset($_POST['PHPSESSID']);
			unset($_POST['os_user']);
			extract($_POST);
			$arraySave=array();
			$counterValid=0;
			foreach($_POST as $keyP=>$valueP)
			{

				if(trim($valueP[0])==''){

				}else if(trim($valueP[0])!=''){
					$arraySave[]=$valueP;
				}else{
					$unfilledFlag='1';
					$unfilledRows[]=$counterValid;
				}

				$counterValid++;
			}
			/* echo '<pre>';
			 print_r($arraySave);*/
			 
			if($unfilledFlag=='1'){
				//print_r($unfilledRows);
			}elseif(count($arraySave)>0){
				$this->saveSoap($arraySave);
				 
			}

		}
		 

		 
		$replace = array();
                /* code for show telespine menu on list */
        $replace['showmenu']="no";
        if($this->clinicInfo('clinic_id')==$this->config['telespineid']){
            $replace['showmenu']="yes";
        }
        /* code end */
		$userInfo = $this->userInfo();
		$cli_type=$this->getClinicDetails($this->userInfo('user_id'));
		//if($cli_type['clinic_type']==4){
		$replace['shopshow']=1;
		/*}else{
		 $replace['shopshow']='0';
		}*/
		$replace['header'] = $this->build_template($this->get_template("header"));
		$replace['sidebar'] = $this->sidebar();
		$replace['footer'] = $this->build_template($this->get_template("footer"));
		$userInfo=$this->userInfo("user_id");
		$userRole=$this->getUserRole($userInfo);
		$url_array = $this->tab_url();
		$replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"),$url_array);
		$replace['location'] = $url_array['location'];
		if($userRole=='HeadAccountAdmin'){
			$replace['menuhead']="<script language=\"JavaScript\" src=\"js/show_menu_head.js\"></script>";
			$replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigationHead"),$url_array);
		}else{
			$replace['menuhead']="<script language=\"JavaScript\" src=\"js/show_menu.js\"></script>";
			$replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"));
		}
	
		$replace['browser_title'] = "Tx Xchange: Customize Soap";

		// Code Start here
		$replace['servicesListHead'] = $this->build_template($this->get_template("soapListHead"),$replace);

		//Select Data from Account Billing Services
		 
		// Count the totla number of rows to be displayed [Database + Default]
		$counterSn=0;
		// echo "select * from soap_question where status='1'";
		$selectsoapQuestion=$this->execute_query("select * from soap_question where status='1' ");
		if($this->num_rows($selectsoapQuestion)>0){
			 
			while($resultSoapQuestion=$this->fetch_array($selectsoapQuestion)){
				$counterSn=0;
				$tempReplace='';
				//echo "select * from soap_answer where soap_question_id = {$resultSoapQuestion['soap_question_id']} and clinic_id ={$clinicId} and status='1' ";
				$selectsoapQuestionAnswer=$this->execute_query("select * from soap_answer where soap_question_id = {$resultSoapQuestion['soap_question_id']} and clinic_id ={$clinicId} and status='1' ");
				$tempclass='';
				if($this->num_rows($selectsoapQuestionAnswer)>0){
					while($resultSoapQuestionAnswer=$this->fetch_array($selectsoapQuestionAnswer)){
						$replaceSoapListBody['sno']=$counterSn++;
						$replaceSoapListBody['soap_answer_id']=$resultSoapQuestionAnswer['soap_answer_id'];
						$replaceSoapListBody['soap_question_id']=$resultSoapQuestionAnswer['soap_question_id'];
						$replaceSoapListBody['answer']=$resultSoapQuestionAnswer['answer'];
						$replaceSoapListBody['TRclass']="line".(($counterSn%2)+1);
						$replaceSoapListBody['bgclass0']='';
						$replaceSoapListBody['bgclass1']='';
						$replaceSoapListBody['bgclass2']='';
						$replaceSoapListBody['read']='readonly';
						$tempReplace.=$this->build_template($this->get_template("soapListBody"),$replaceSoapListBody);

					}
				}
				 
				$temp=$counterSn+1;
				for($iArray=$counterSn;$iArray<5;$iArray++){

					$replaceSoapListBody['sno']=$iArray;
					$replaceSoapListBody['bgclass0']='';
					$replaceSoapListBody['bgclass1']='';
					$replaceSoapListBody['bgclass2']='';
					$replaceSoapListBody['soap_answer_id']='';
					$replaceSoapListBody['TRclass']="line".(($temp%2)+1);
					$temp++;

					$replaceSoapListBody['soap_question_id']=$resultSoapQuestion['soap_question_id'];
					$replaceSoapListBody['answer']='';
					$replaceSoapListBody['read']='';
					$tempReplace.=$this->build_template($this->get_template("soapListBody"),$replaceSoapListBody);
				}
				$totalRows=$counterSn>5?$counterSn:5;
				$qid=$resultSoapQuestion['soap_question_id'];
				$replace['questionid'.$qid]= $qid;
				$replace['servicesListBody'.$qid] = $tempReplace."<input type='hidden' name='totalRows' id='totalRows' value='".$totalRows."'>";
				$replace['servicesListCount']=$totalRows;
			}
		}
		// Codse Ends here

		if($_REQUEST['saved']=='y')
		{
			$error   = "Your answers have been saved";
			$replace['error'] = $error = '<span style="color:green" ><b>'.$error.'</b></span>';
		}
		if($_REQUEST['soap_question_id']!='')
		{
			if($_REQUEST['soap_question_id']> 0 && $_REQUEST['soap_question_id']<= 25)
				$replace['submenushow'] = '#subjective';
			if($_REQUEST['soap_question_id']> 25 && $_REQUEST['soap_question_id']<= 42)
				$replace['submenushow'] = '#sellers';
			if($_REQUEST['soap_question_id']> 42)
				$replace['submenushow'] = '#arrivals';

			$replace['showhideid'] =$_REQUEST['soap_question_id'];
			 
		}else{
			$replace['submenushow'] = '#subjective';
			$replace['showhideid'] =0;
		}

		$replace['body'] = $this->build_template($this->get_template("soap_services"),$replace);


		$this->output = $this->build_template($this->get_template("main"),$replace);

	}
	 
	/**
	 * @desc This function saves the data into the account billing table
	 * @param array $arraySave
	 * @access public
	 * @return void
	 */

	public function saveSoap($arraySave){
		$userInfo=$this->userInfo("user_id");
		$clinicId = $this->clinicInfo("clinic_id");
		foreach($arraySave as $valueSave){
			if($valueSave[1]==''){
				//Save the data
				$valueinsert=array('soap_question_id'=>$valueSave[2],
						'answer'=>htmlspecialchars($valueSave[0],ENT_QUOTES),
						'user_id'=>$userInfo,
						'status'=>1,
						'clinic_id'=>$clinicId,
						'createdate'=>date('Y-m-d H:i:s',time()),
						'modifydate'=>date('Y-m-d H:i:s',time())
				);
				$insertQuery=$this->insert('soap_answer',$valueinsert);

			}else{
				//Edit the data
				$valueupdate=array('soap_question_id'=>$valueSave[2],
						'answer'=>htmlspecialchars($valueSave[0],ENT_QUOTES),
						'user_id'=>$userInfo,
						'status'=>1,
						'clinic_id'=>$clinicId,
						'modifydate'=>date('Y-m-d H:i:s',time())
				);
				$where = " soap_answer_id = '".$valueSave[1]."'";
				$UpdateQuery=$this->update('soap_answer',$valueupdate,$where);
				 
			}
		}
		// Redirect the page to the same action
		header("location:index.php?action=account_soap_services&saved=y&soap_question_id=".$valueSave[2]);
	}

	public function soapfillanswer(){
		$qid=$this->value('qid');
		$id=$this->value('id');
		$clinicId = $this->clinicInfo("clinic_id");
		$query="select * from soap_answer where soap_question_id = {$qid} and clinic_id ={$clinicId} and status='1' ";
		$res=$this->execute_query($query);
		$numans=$this->num_rows($res);
		if($numans>0)
		{
			while($rowans=mysql_fetch_array($res)){
				$rows.="<input type=\"checkbox\" name=\"ans\" id=ans[] value=\"{$rowans['answer']}\"/>".$rowans['answer']."<br/><input type=\"hidden\" value=\"\" name=\"close\" id=\"close\">";
			}
			$replace['submit']='<input type="button" value="Submit" onclick="javascript:checkSelectBox()"/>';
		}else{
			$rows ="There are currently no pre-defined chart notes. To create them, please go to Account Admin link > Customize tab > SOAP. <br/><input type=\"hidden\" value=\"close\" name=\"close\" id=\"close\">";
			$replace['submit']='<input type="button" value="Close" onclick="javascript:closeme()"/>';
		}
		 
		$replace['rowasn']=$rows;
		$replace['id']="q".$id;
		$replace['body'] = $this->build_template($this->get_template("soapfillanswer"),$replace);
		$this->output = $this->build_template($this->get_template("main"),$replace);
		 
	}

	public function delete_soap(){
		$soap_answer_id=$_REQUEST['soap_answer_id'];
		$deleteBill=$this->execute_query("update soap_answer set status='0' where soap_answer_id='{$soap_answer_id}' ");
		//echo "update account_bill_services set bill_services_status='0' where bill_services_id='{$account_bill_services_id}' ";
	}

	public function copyforward_soap(){
		$userInfo = $this->userInfo();
                $id=$this->value('patient_id');        
                $soapnotedraft = $this->value('soapnotedraft');
		$cli_type=$this->getClinicDetails($this->userInfo('user_id'));
		$user_id=$this->userInfo('user_id');
		$replace['display']='inline';
		$replace['shopnoteid'] = $this->value('shopnoteid');
		$sql=$this->execute_query("select soap_note_id from new_soap_note where patient_id='".$id."' order by soap_note_id desc limit 0,1");
        if($this->num_rows($sql)>0){
			$row1=mysql_fetch_assoc($sql);
			$shopnoteid=$row1['soap_note_id'];
		}   

        /****This code modified by pawan khandelwal on 28-jun-2013****/     
        if(isset($soapnotedraft) && !empty($soapnotedraft))
        {            
           // $sql=$this->execute_query("select soap_note_id from soap_plan where patient_id='".$id."' and soap_note_id='".$shopnoteid."'");
           // if($this->num_rows($sql)==0){
    		//echo "select soap_note_id from new_soap_note where patient_id='".$id."' and soap_note_id!= order by soap_note_id desc limit 0,1";
                $sql=$this->execute_query("select soap_note_id from new_soap_note where patient_id='".$id."' and soap_note_id!='".$this->value('shopnoteid')."' order by soap_note_id desc limit 0,1");
                if($this->num_rows($sql)>0){
        			$row1=mysql_fetch_assoc($sql);
        			$shopnoteid=$row1['soap_note_id'];
				
        		}                
            //}
	}
;
        if($shopnoteid > 0)
        {
    		$result=$this->execute_query("select * ,DATE_FORMAT(create_datetime, '%m/%d/%Y') as date1 from new_soap_note where soap_note_id =".$shopnoteid." order by soap_note_id ASC");
    		if($this->num_rows($result)>0){
    			/* while($row=mysql_fetch_assoc($result)){
    				foreach($row as $key=>$value){
    					$value=urlencode(html_entity_decode(($this->jsesc($value))));
    					$stateJavascript.="\"{$key}\":\"{$value}\",";
    				}
    				$provider_id=$row['provider_id'];
    				$patient_id=$row['patient_id'];
    				$date=$row['date1'];
    			} */
    			// $stateJavascript=trim($stateJavascript,',');
    		}
    		
    		$resultcc=$this->execute_query("select * from cc_tbl where soap_note_id =".$shopnoteid ." ORDER BY `ccnumber` ASC");
    		if($this->num_rows($resultcc)>0){
    			$i=0;
    			/* while($row1=mysql_fetch_assoc($resultcc)){
    				foreach($row1 as $key=>$value){
    					$value=urlencode(html_entity_decode(($this->jsesc($value))));
    					if($i!=0)
    						$key=$key.$i;
    					$stateJavascript.="\"{$key}\":\"{$value}\",";
    				}
    				$i++;
    				 
    			} */
    		}
            
    		$resultsoap_plan=$this->execute_query("select * from soap_plan where soap_note_id =".$shopnoteid);
    		if($this->num_rows($resultsoap_plan)>0){
    			while($row2=mysql_fetch_assoc($resultsoap_plan)){
    				$soap_plan_id=$row2['soap_plan_id'];
    				foreach($row2 as $key=>$value){
    					$value=urlencode(html_entity_decode(($this->jsesc($value))));
    					$stateJavascript.="\"{$key}\":\"{$value}\",";
    				}
    			}
    		}
    		if($soap_plan_id!='' or $soap_plan_id!=0){
    			$resultsoap_plan_detail=$this->execute_query("select * from soap_plan_description where soap_plan_id =".$soap_plan_id." order by soap_plan_description_id ASC");
    			if($this->num_rows($resultsoap_plan_detail)>0){
    				$i=0;
    				while($row3=mysql_fetch_assoc($resultsoap_plan_detail)){
    			   
    					foreach($row3 as $key=>$value){
    						$value=urlencode(html_entity_decode(($this->jsesc($value))));
    						$key=$key.$i;
    						$stateJavascript.="\"{$key}\":\"{$value}\",";
    					}
    					$i++;
    				}
    			}
    		}
    		//$stateJavascript=str_replace('\n\r','',nl2br($stateJavascript));
    		$replace['prouct_count']	=	$this->num_rows($resultsoap_plan_detail);
    		$stateJavascript			.=	"\"prouct_count\":\"{$replace['prouct_count']}\"";
    		echo $stateJavascript			=	'{'.trim($stateJavascript,',').'}';
    		//$replace['stateJavascript']	=	$stateJavascript;
        }
        else
        {
            echo "fail";
        }
        /****This code modified by pawan khandelwal on 28-jun-2013 ends here****/ 
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
$obj = new soapnote();
?>
