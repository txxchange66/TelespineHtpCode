<?php

	$formArray = array(

					'username' => '',

					'email_address' => '',

					'new_password' => '',

					'new_password2' => '',							
					
					'answer' => '',		

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
					
					'status' => '1'

					);



	$tableFieldArray = array(

					'username' => 'email_address',

					'password' => 'new_password',										
					
					'answer' => 'answer',

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

					);
	
						
	$arrUserType = array(
						"" => "Choose...",
						"2" => "Provider",
						"3" => "Account Admin",
						"4" => "System Admin"
	
						);	


	$arrStatus = array(
					 "1" => "Active",
					 "2" => "Inactive"
	
						);	
																			
						
	

?>