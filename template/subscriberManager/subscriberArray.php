<?php

	$formArray = array(

					'username' => '',

					'email_address' => '',

					'new_password' => '',

					'new_password2' => '',							
					
			//		'answer' => '',		

					'name_first' => '',

					'name_last' => '',					

					'address' => '',

					'address2' => '',

					'city' => '',

					'state' => '',

					'zip' => '',

					'phone1' => '',

					'phone2' => '',
					
					'fax' => '',

					'usertype_id' => '1',
					
					'status' => '1',
					'practitioner_type'=>''

					);



	$tableFieldArray = array(

					'username' => 'email_address',

					'password' => 'new_password',										
					
				//	'answer' => 'answer',

					'diagnosis' => 'diagnosis',

					'name_title' => 'name_title',

					'name_first' => 'name_first',

					'name_last' => 'name_last',

					'name_suffix' => 'name_suffix',

					'address' => 'address',

					'address2' => 'address2',

					'city' => 'city',

					'state' => 'state',

					'zip' => 'zip',

					'phone1' => 'phone1',

					'phone2' => 'phone2',
					
					'fax' => 'fax',
					
					'usertype_id' => 'usertype_id',

					'status' => 'status',
					'practitioner_type'=>''

					);

	$patientListAction = Array(

							"act_view" => "View Patient",

							"act_edit" => "Edit Patient",

							"act_assign" => "Assign Plan",

							"act_delete" => "Discharge Patient",

							

						);
						
	$sortColTblArray = array(
							"username" => array("ASC" => " username ASC ", "DESC" => " username DESC "),
							"full_name" => array("ASC" => " name_first ASC, name_last ASC ","DESC" =>" name_first DESC, name_last DESC "),
							"usertype_id" => array("ASC" => " usertype_id ASC, therapist_access ASC, admin_access ASC ", "DESC" => " usertype_id DESC, therapist_access DESC, admin_access DESC "),
							"status" => array("ASC" => " status ASC ", "DESC" => " status DESC "),							
							"last_login" => array("ASC" => " last_login ASC", "DESC" => " last_login DESC"),
							"creation_date" => array("ASC" => " creation_date ASC", "DESC" => " creation_date DESC")
			
						);		
						
	$arrUserType = array(
						"" => "Choose...",
						"2" => "Provider",
						"3" => "Account Admin",
						"4" => "System Admin"
	
						);	


	$arrStatus = array(
					 "1" => "Active",
					 "2" => "Inactive",
                     "3" => "Trial"
	
						);	
																			
						
	

?>