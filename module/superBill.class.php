<?php	
	/**
	 *
	 * Copyright (c) 2008 Tx Xchange
	 *
	 * This class includes the Super Bill Service for Clinic and Doctors functionality.
	 * 
	 * // necessary classes 
	 * require_once("module/application.class.php");
	 * 
	 * 
	 */

    
	require_once("include/paging/my_pagina_class.php");	
	require_once("module/application.class.php");
    

	// class declaration
  	class superBill extends application{
  		
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
        * @desc  This function open the web page to add/edit the Billing services available to Providers
        * @param void
        * @return void
        * 
        */
        public function account_bill_services(){
            //echo 'xxx'; die();
            $replace = array();
            $clinicId = $this->clinicInfo("clinic_id");
              /* code for show telespine menu on list */
        $replace['showmenu']="no";
        if($this->clinicInfo('clinic_id')==$this->config['telespineid']){
            $replace['showmenu']="yes";
        }
        /* code end */
            // Code to save the details 
            if(isset($_POST) && $_POST['submit']=='Save'){

                $unfilledFlag='0';
                //echo '<pre>'; print_r($_POST); echo '</pre>';
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
                    
                    if(trim($valueP[0])=='' && trim($valueP[1])=='' && trim($valueP[2])=='' ){
                        
                    }else if(trim($valueP[0])!='' && trim($valueP[1])!='' && trim($valueP[2])!='' ){
                        $arraySave[]=$valueP;
                    }else{
                          $unfilledFlag='1';
                          $unfilledRows[]=$counterValid;
                    }
                
                    $counterValid++;
               }
               
               if($unfilledFlag=='1'){
                  //print_r($unfilledRows);    
               }elseif(count($arraySave)>0){
                  $this->saveServicesBilling($arraySave);   
                 
               }
           					
            }
                           
            
            $url_array = $this->tab_url();
            
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
            if($userRole=='HeadAccountAdmin'){ 
            	$replace['menuhead']="<script language=\"JavaScript\" src=\"js/show_menu_head.js\"></script>";             
            	$replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigationHead"),$url_array);
            }else{
            	$replace['menuhead']="<script language=\"JavaScript\" src=\"js/show_menu.js\"></script>";
            	$replace['tabNavigation'] = $this->build_template($this->get_template("tabNavigation"));
           }
            $replace['browser_title'] = "Tx Xchange: Billing";

            // Code Start here
            $replace['servicesListHead'] = $this->build_template($this->get_template("servicesListHead"),$replace);            
            
            //Select Data from Account Billing Services
           
            // Count the totla number of rows to be displayed [Database + Default] 
            $counterSn=0;
            $selectBilling=$this->execute_query("select * from account_bill_services where bill_services_clinic_id='{$clinicId}' AND bill_services_status='1' ");
            if($this->num_rows($selectBilling)>0){
                   while($resultBilling=$this->fetch_array($selectBilling)){
                        $replaceservicesListBody['sno']=$counterSn++;
                        $replaceservicesListBody['billingID']=$resultBilling['bill_services_id'];
                        $replaceservicesListBody['bill_services_code']=$resultBilling['bill_services_code'];
                        $replaceservicesListBody['bill_services_descr']=$resultBilling['bill_services_descr'];
                        $replaceservicesListBody['bill_services_price']=$resultBilling['bill_services_price'];
                        $replaceservicesListBody['TRclass']="line".(($counterSn%2)+1);
                        $replaceservicesListBody['bgclass0']='';
                        $replaceservicesListBody['bgclass1']='';
                        $replaceservicesListBody['bgclass2']='';
                        $replaceservicesListBody['read']='readonly';
                        
                        // Apply Only in case of PHP Validation
                        /*
                        if(in_array(($counterSn-1),$unfilledRows))
                           {
                            $fieldName="row".($counterSn-1);
                            $arrayValue=$$fieldName;
                            
                            if($arrayValue[0]=='') {$replaceservicesListBody['bill_services_code']=$arrayValue[0];$replaceservicesListBody['bgclass0']=' style="bgcolor:#FF0000;" ';}
                            if($arrayValue[1]==''){$replaceservicesListBody['bill_services_descr']=$arrayValue[1];$replaceservicesListBody['bgclass1']=' style="bgcolor:#FF0000;"';}
                            if($arrayValue[2]==''){$replaceservicesListBody['bill_services_price']=$arrayValue[2];$replaceservicesListBody['bgclass2']='style="bgcolor:#FF0000;"';}
                           }
                          */
                        $tempReplace.=$this->build_template($this->get_template("servicesListBody"),$replaceservicesListBody);                        
                   }
            }
            
            

            
            for($iArray=$counterSn;$iArray<10;$iArray++){
                    $replaceservicesListBody['sno']=$iArray;
                    $replaceservicesListBody['bgclass0']='';
                    $replaceservicesListBody['bgclass1']='';
                    $replaceservicesListBody['bgclass2']='';                    
                    $replaceservicesListBody['billingID']='';
                    $replaceservicesListBody['TRclass']="line".(($iArray%2)+1);
                    $replaceservicesListBody['bill_services_code']='';
                    $replaceservicesListBody['bill_services_descr']='';
                    $replaceservicesListBody['bill_services_price']='';
                    $replaceservicesListBody['read']=''; 
                    //Apply Only in caes of PHP validation
                    /*
                        if(in_array(($iArray),$unfilledRows))
                           {
                            $fieldName="row".($iArray);
                            $arrayValue=$$fieldName;
                            //echo $arrayValue[0].$arrayValue[1].$arrayValue[2]." ASFD <br />";
                            if($arrayValue[0]!='') {$replaceservicesListBody['bill_services_code']=$arrayValue[0];$replaceservicesListBody['bgclass0']=' style="bgcolor:#FF0000;" ';}
                            if($arrayValue[1]!=''){$replaceservicesListBody['bill_services_descr']=$arrayValue[1];$replaceservicesListBody['bgclass1']=' style="bgcolor:#FF0000;"';}
                            if($arrayValue[2]!=''){$replaceservicesListBody['bill_services_price']=$arrayValue[2];$replaceservicesListBody['bgclass2']='style="bgcolor:#FF0000;"';}
                           }                                        
                           
                     */      
                    $tempReplace.=$this->build_template($this->get_template("servicesListBody"),$replaceservicesListBody);            
            }
            
            $totalRows=$counterSn>10?$counterSn:10;
            
            $replace['servicesListBody'] = $tempReplace."<input type='hidden' name='totalRows' id='totalRows' value='".$totalRows."'>";            
            $replace['servicesListCount']=$totalRows;
            
            // Codse Ends here            
                        
            if($_REQUEST['saved']=='y')
            {
            	 $error   = "Your changes have been saved";
                  $replace['error'] = $error = '<span style="color:green" ><b>'.$error.'</b></span>';
            }
            $replace['body'] = $this->build_template($this->get_template("account_bill_services"),$replace);            
            
            
            $this->output = $this->build_template($this->get_template("main"),$replace);            
            
        }
         
        /**
        * @desc This function saves the data into the account billing table
        * @param array $arraySave
        * @access public
        * @return void
        */
        
        public function saveServicesBilling($arraySave){
               $userInfo=$this->userInfo("user_id");
               $clinicId = $this->clinicInfo("clinic_id");
               foreach($arraySave as $valueSave){
                       if($valueSave[3]==''){                             
                            //Save the data
                           $valueinsert=array('bill_services_code'=>$valueSave[0],
                           			'bill_services_descr'=>htmlspecialchars($valueSave[1],ENT_QUOTES),
                           			'bill_services_price'=>$valueSave[2],
                           			'bill_services_user_id'=>$userInfo,
                           			'bill_services_status'=>1,
                           			'bill_services_clinic_id'=>$clinicId,	
                           			'bill_services_modified_by'=>$userInfo,
                          			'bill_services_modified_date'=>date('Y-m-d H:i:s',time())
                           		); 
                       	$insertQuery=$this->insert('account_bill_services',$valueinsert);
                       }else{
                            //Edit the data
                            $valueupdate=array('bill_services_code'=>$valueSave[0],
                           			'bill_services_descr'=>htmlspecialchars($valueSave[1],ENT_QUOTES),
                           			'bill_services_price'=>$valueSave[2],
                           			'bill_services_user_id'=>$userInfo,
                           			'bill_services_status'=>1,
                           			'bill_services_clinic_id'=>$clinicId,	
                           			'bill_services_modified_by'=>$userInfo,
                          			'bill_services_modified_date'=>date('Y-m-d H:i:s',time())
                           		);
                           	$where = " bill_services_id = '".$valueSave[3]."'";
                            $UpdateQuery=$this->update('account_bill_services',$valueupdate,$where); 
                                               
                       }
               }
            // Redirect the page to the same action
            header("location:index.php?action=account_bill_services&saved=y");
        }
        
        public function delete_account_bill_services(){
                $account_bill_services_id=$_REQUEST['account_bill_services_id'];
                $deleteBill=$this->execute_query("update account_bill_services set bill_services_status='0' where bill_services_id='{$account_bill_services_id}' ");
                //echo "update account_bill_services set bill_services_status='0' where bill_services_id='{$account_bill_services_id}' ";
        }
        
        public function providerServiceslist(){
            // Services Listing
            $id=$this->value('id');
            $selectQueryServices=$this->execute_query("select pro_bill_services_patient.*,DATE_FORMAT(pro_bill_datetime, '%m/%d/%Y') as format_pro_bill_datetime from pro_bill_services_patient where pro_bill_patient_id = '{$id}' AND pro_status = '1' order by pro_bill_services_patient_id desc");
            $services_display='';
            if($this->num_rows($selectQueryServices)>0)            {
                while($resultServices=$this->fetch_array($selectQueryServices)){
                      $services_display.="<a href='index.php?action=createServicesBillingPdf&id={$resultServices[pro_bill_services_patient_id]}' style=\"color:#0069A0; display:block; border-bottom:#bbb solid 1px;padding:4px 6px;\" target=\"_blank\">".$resultServices['format_pro_bill_datetime']."</a>";    
                }
            }
            echo $services_display;            
        }
        
        public function add_provider_account_bill(){

            if(isset($_POST['submit']) && $_POST['submit'] == 'Save' ){
                $patient_id=$this->value('id');
                unset($_POST['submit']);
                unset($_POST['action']);
                unset($_POST['totalRows']);
                unset($_POST['id']);
                unset($_POST['PHPSESSID']);
                unset($_POST['os_user']); 
                $dataArray=array();
               foreach($_POST as $keyP=>$valueP)
               {
                   if(trim($valueP[0])!='' && trim($valueP[1])!='' && trim($valueP[2])!='' )     
                        $dataArray[]= $valueP;       
               }
               
               if(count($dataArray)>0) {
                   // Insert into pro_bill_services_patient table
                   $userInfo=$this->userInfo("user_id");
                   $clinicId = $this->clinicInfo("clinic_id");                   
                   $insertvalue=array('pro_bill_provider_id'=>$userInfo,
                   					  'pro_bill_patient_id'=>$patient_id,
                   					  'pro_bill_datetime'=>date('Y-m-d H:i:s',time()),
                   					  'pro_status'=>1,
                   					  'pro_bill_clinic_id'=>$clinicId);
                   $insertProBillServicesPatient=$this->insert("pro_bill_services_patient",$insertvalue);
                   $pro_bill_services_patient_id=$this->insert_id();
                   foreach($dataArray as $valueData){
                            $this->savePrividerBilling($valueData,$patient_id,$pro_bill_services_patient_id,$clinicId,$userInfo);
                   }                        
               }          
               echo '<script>parent.parent.GB_hide();parent.parent.reloadServices('.$patient_id.');</script>';  
            }
            $replace['providerListHead']=$this->build_template($this->get_template("addproviderListHead"),$replace);
            
            $counterSn=0;
            $countLimit=5;
            for($iArray=$counterSn;$iArray<$countLimit;$iArray++){
                    $replaceservicesListBody['sno']=$iArray;
                    $replaceservicesListBody['bgclass1']='';
                    $replaceservicesListBody['bgclass2']='';                    
                    $replaceservicesListBody['billingID']='';
                    $replaceservicesListBody['TRclass']="line".(($iArray%2)+1);

                    
                    //Apply Only in caes of PHP validation
                    /*
                        if(in_array(($iArray),$unfilledRows))
                           {
                            $fieldName="row".($iArray);
                            $arrayValue=$$fieldName;
                            //echo $arrayValue[0].$arrayValue[1].$arrayValue[2]." ASFD <br />";
                            if($arrayValue[0]!='') {$replaceservicesListBody['bill_services_code']=$arrayValue[0];$replaceservicesListBody['bgclass0']=' style="bgcolor:#FF0000;" ';}
                            if($arrayValue[1]!=''){$replaceservicesListBody['bill_services_descr']=$arrayValue[1];$replaceservicesListBody['bgclass1']=' style="bgcolor:#FF0000;"';}
                            if($arrayValue[2]!=''){$replaceservicesListBody['bill_services_price']=$arrayValue[2];$replaceservicesListBody['bgclass2']='style="bgcolor:#FF0000;"';}
                           }                                        
                           
                     */      
                    $tempReplace.=$this->build_template($this->get_template("addproviderListBody"),$replaceservicesListBody);            
            }
            
            $totalRows=$counterSn>$countLimit?$counterSn:$countLimit;
            
            $replace['providerListBody'] = $tempReplace."<input type='hidden' name='totalRows' id='totalRows' value='".$totalRows."'>";            
            $replace['servicesListCount']=$totalRows;            
            $replace['id']=$this->value('patient_id');
            //print_r($replace);
            $this->output=$this->build_template($this->get_template("add_provider_bill_services"),$replace);
        }
        
        function savePrividerBilling($dataSave,$patient_id,$pro_bill_services_patient_id,$clinicId,$userInfo){

               $insertvalues=array('pat_user_id'=>$patient_id,
               						'pro_user_id'=>$userInfo,
               						'pro_bill_services_code'=>$dataSave[0],
               						'pro_bill_services_descr'=>$dataSave[1],
               						'pro_bill_services_price'=>$dataSave[2],
               						'pro_bill_services_status'=>1,
               						'bill_services_id'=>$dataSave[3],
               						'pro_bill_services_clinic_id'=>$clinicId,
               						'pro_bill_services_date_time'=>date('Y-m-d H:i:s',time()),
               						'pro_bill_services_patient_id'=>$pro_bill_services_patient_id
               						);
               $insertQuery=$this->insert("provider_bill_services",$insertvalues);
            
            
        }
        /**
        * @desc Get data for Auto Fill Provider add billing summary
        * @param void
        * @return void
        */
        
        function getBillingServicesData(){
                 $data=$this->value('term');
                 $clinicId = $this->clinicInfo("clinic_id");
                 //Get the required data from database
                 $queryData=$this->execute_query("select * from account_bill_services where bill_services_clinic_id= '{$clinicId}' AND bill_services_code LIKE '{$data}%' AND bill_services_status= '1' ");    
                 //echo "select * from account_bill_services where bill_services_clinic_id= '{$clinicId}' AND bill_services_code LIKE '{$data}%' AND bill_services_status= '1' ";
                 if($this->num_rows($queryData)>0)        {
                    $items=array(); 
                    while($resultData=$this->fetch_array($queryData)){
                            $ie9Code=$resultData['bill_services_code'];
                            $arrayPush=array("id"=>$resultData["bill_services_id"],"descr"=>$resultData["bill_services_descr"],"price"=>$resultData["bill_services_price"]);
                            $items[$ie9Code]=($arrayPush); 
                    }   
                 }
                //print_r($items);  
                //print_r($items);
                $resultArray = array();
                foreach ($items as $key=>$value) {
                    //if (strpos(strtolower($key), $q) !== false) 
                    {
                        array_push($resultArray, array("id"=>$value['id'], "label"=>$key, "descr" => strip_tags($value['descr']),"price"=>$value['price']));
                    }

                }                 
               
                echo $this->array_to_json($resultArray);
                
        }
        
     /**
     * @desc  This function Converts data into JSON Format 
     * @param array  $array
     * @return JSON
     * @access public   
     */
     
      public function array_to_json( $array ){

            if( !is_array( $array ) ){
                return false;
            }

            $associative = count( array_diff( array_keys($array), array_keys( array_keys( $array )) ));
            if( $associative ){

                $construct = array();
                foreach( $array as $key => $value ){

                    // We first copy each key/value pair into a staging array,
                    // formatting each key and value properly as we go.
                                                           
                    // Format the key:
                    if( is_numeric($key) ){
                        $key = "key_$key";
                    }
                    $key = "\"".addslashes($key)."\"";

                    // Format the value:
                    if( is_array( $value )){
                        $value = $this->array_to_json( $value );
                    } else if( !is_numeric( $value ) || is_string( $value ) ){
                        //$value = "\"".addslashes($value)."\"";
                        $value = "\"".$value."\"";
                    }

                    // Add to staging array:
                    $construct[] = "$key: $value";
                }

                // Then we collapse the staging array into the JSON form:
                $result = "{ " . implode( ", ", $construct ) . " }";

            } else { // If the array is a vector (not associative):

                $construct = array();
                foreach( $array as $value ){

                    // Format the value:
                    if( is_array( $value )){
                        $value = $this->array_to_json( $value );
                    } else if( !is_numeric( $value ) || is_string( $value ) ){
                        $value = "'".addslashes($value)."'";
                    }

                    // Add to staging array:
                    $construct[] = $value;
                }

                // Then we collapse the staging array into the JSON form:
                $result = "[ " . implode( ", ", $construct ) . " ]";
            }

            return $result;
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
        
        
        /**    
		 *This function create pdf program
		 *
		 *
		 ***/
     	function createServicesBillingPdf(){
        $result=$this->execute_query("select *,DATE_FORMAT(pro_bill_datetime, '%m/%d/%Y') as date1 from pro_bill_services_patient where pro_bill_services_patient_id =".$this->value('id'));
        $row=mysql_fetch_assoc($result);
        $paitentname=html_entity_decode($this->userInfo('name_first',$row['pro_bill_patient_id']).' '.$this->userInfo('name_last',$row['pro_bill_patient_id']),ENT_QUOTES, "UTF-8");
	    $providername=html_entity_decode($this->userInfo('name_title',$row['pro_bill_provider_id']).' '.$this->userInfo('name_first',$row['pro_bill_provider_id']).' '.$this->userInfo('name_last',$row['pro_bill_provider_id']),ENT_QUOTES, "UTF-8");
        $date=$row['date1'];
        
       $clinicname=html_entity_decode($this->get_clinic_info($row['pro_bill_patient_id'],'clinic_name'),ENT_QUOTES, "UTF-8");
       $clinicid=html_entity_decode($this->clinicInfo('clinic_id',$row['pro_bill_provider_id']),ENT_QUOTES, "UTF-8");
        $clinicsql="select address,address2,city,country,state,zip,phone from clinic where clinic_id=".$clinicid;
        $clinicres=$this->execute_query($clinicsql);
        $clinicrow=mysql_fetch_assoc($clinicres);
        $address=html_entity_decode($clinicrow['address'],ENT_QUOTES, "UTF-8");
       $address2=html_entity_decode($clinicrow['address2'],ENT_QUOTES, "UTF-8");
       $city=html_entity_decode($clinicrow['city'],ENT_QUOTES, "UTF-8");
       $country=html_entity_decode($clinicrow['country'],ENT_QUOTES, "UTF-8");
       $state=html_entity_decode($clinicrow['state'],ENT_QUOTES, "UTF-8");
      $zip=html_entity_decode($clinicrow['zip'],ENT_QUOTES, "UTF-8");
      $phone=html_entity_decode($clinicrow['phone'],ENT_QUOTES, "UTF-8");
      $print_address=$address.' '.$address2.' '.$city.' '.$state.' '.$country.' '.$zip;
       $billserviceid=$this->value('id');	
     	require('include/fpdf/fpdf.php');
     		$pdf = new FPDF('P', 'mm','letter');
			$pdf->AddPage();
			$pdf->SetFont('Arial','',18);
			$pdf->SetTextColor(0,125,210);
			$pdf->SetXY(0, 10);
			$pdf->Cell(0,0,$clinicname,0,0,'C');
			$pdf->SetFont('Arial','',12);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetXY(0, 20);
			$pdf->Cell(0,0,$print_address,0,0,'C');
			$pdf->SetTextColor(0,0,0);
			$pdf->SetXY(0, 30);
			if($phone!=''){
			$pdf->SetFont('Arial','B',12);
			$pdf->SetX(80);
			$pdf->Cell(0,0,'Phone:');
			$pdf->SetX(95);
			$pdf->SetFont('Arial','',12);
			$pdf->Cell(0,0,$phone);
			}
            $pdf->Ln();
            $pdf->Line(5,35,200,35);
			$pdf->SetXY(20,50);
			$pdf->SetFont('Arial','B',15);
			$pdf->Cell(0,0,'Provider Name: ');
			$pdf->SetX(78);
			$pdf->SetFont('Arial','',15);
			$pdf->Cell(0,0,$providername);
			$pdf->SetXY(20,60);
			$pdf->SetFont('Arial','B',15);
			$pdf->Cell(0,0,'Patient Name: ');
			$pdf->SetX(80);
			$pdf->SetFont('Arial','',15);
			$pdf->Cell(0,0,$paitentname);
            $pdf->SetXY(20,70);
			$pdf->SetFont('Arial','B',15);
			$pdf->Cell(0,0,'Date of Service: ');
			$pdf->SetX(80);
			$pdf->SetFont('Arial','',15);
			$pdf->Cell(0,0,$date);
        
        $header=array(' Code',' Description ',' Price');
     	$pdf->SetXY(5,80);
   		$pdf->SetFont('Arial','B',14);
     	$pdf->SetWidths(array(44,145,30));
		$pdf->Rowcenter($header);
        $pdf->SetX(5);
        $pdf->SetFont('Arial','',14);
        $y=80;
	        $resultsoap_plan_detail=$this->execute_query("select * from provider_bill_services where pro_bill_services_patient_id=".$billserviceid);
	     		if($this->num_rows($resultsoap_plan_detail)>0){
			     	$i=0;
                    $price=0;
	     			while($row3=mysql_fetch_assoc($resultsoap_plan_detail)){
                        $pdf->SetX(5);
			     			$x=40;
			     			$val=array();
			     			foreach($row3 as $key=>$value){
			     				if($key!='provider_bill_services_id' and $key!='pat_user_id' and $key!='pro_user_id' and $key!='pro_bill_services_status'  and $key!='pro_bill_services_date_time' and $key!='pro_bill_services_clinic_id' and $key!='provider_bill_services_id' and $key!='bill_services_id' and $key!='pro_bill_services_patient_id'){
                                   if($key=='pro_bill_services_price')     
                                        $val[]=html_entity_decode(" $".$value,ENT_QUOTES, "UTF-8");
                                       else
                                        $val[]=html_entity_decode(' '.$value,ENT_QUOTES, "UTF-8");
			     				}
			     			}
                            $price=$price+$row3['pro_bill_services_price']; 
		            		$pdf->SetWidths(array(44,145,30));
			     			$pdf->Rowcenter($val);
                       }
	     		}
  			$lastcol=array('','                                                               Total',' $'.$price);
  			$pdf->SetX(5);
            $pdf->SetFont('Arial','B',14);
            $pdf->SetWidths(array(44,145,30));
            $pdf->Rowcenter($lastcol);
            
			$pdf->Output();
        }
  				
  		/**
        * @desc Get data for Auto Fill Provider add billing summary
        * @param void
        * @return void
        */
        
        function checkBillingServicesData(){
                 $serviceId=$this->value('serviceId');
                 $queryData="select * from account_bill_services where bill_services_id= '{$serviceId}'";
                $queryDatares=$this->execute_query($queryData);    
                 if($this->num_rows($queryDatares)>0)        {
                    $resultData=$this->fetch_array($queryDatares);
                    $bill_services_code=$resultData['bill_services_code'];   
                 }
                echo $bill_services_code;
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
        
        
        public function drawIntakeGraph(){
               $this->getdrawIntakeGraph();
        }
      
	}
	$obj = new superBill();
?>