<?php

	$formArray = array(

					'username' => '',

					'confirmUsername' => '',
					
					'new_password' => '',	
					
					'new_password2' => '',			

					'name_first' => '',

					'name_last' => '',					

					'address' => '',

					'address2' => '',

					'city' => '',

					'state' => '',

					'zip' => '',
					
					'phone1' => '',					
					
					'fax' => '',
					
					'practitioner_type'=>''

					);



	$tableFieldArray = array(

					'username' => 'username',	
					
					'password' => 'new_password',			

					'name_first' => 'name_first',

					'name_last' => 'name_last',				

					'address' => 'address',

					'address2' => 'address2',

					'city' => 'city',

					'state' => 'state',

					'zip' => 'zip',
					
					'phone1' => 'phone1',
					
					'fax' => 'fax',

					'practitioner_type'=>'practitioner_type'
					
					);
	
						
	$sortColTblArray = array(
	
							"full_name" => array("ASC" => " name_first ASC, name_last ASC ","DESC" =>" name_first DESC, name_last DESC "),
							"username" => array("ASC" => " username ASC ", "DESC" => " username DESC "),							
							"usertype_id" => array("ASC" => " usertype_id ASC, therapist_access ASC, admin_access ASC ", "DESC" => " usertype_id DESC, therapist_access DESC, admin_access DESC "),							
							"last_login" => array("ASC" => " last_login ASC", "DESC" => " last_login DESC")
			
						);		
	

?>