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
					
					'fax' => 'fax'
					
					);
	
						
	$sortColTblArray = array(
	
							"full_name" => array("ASC" => " cast(name_first_sort as char) ASC, cast(name_last_sort as char) ASC ","DESC" =>" cast(name_first_sort as char) DESC, cast(name_last_sort  as char ) DESC "),
							"username" => array("ASC" => " username ASC ", "DESC" => " username DESC "),							
							"usertype_id" => array("ASC" => " usertype_id ASC, therapist_access ASC, admin_access ASC ", "DESC" => " usertype_id DESC, therapist_access DESC, admin_access DESC "),							
							"last_login" => array("ASC" => " last_login ASC", "DESC" => " last_login DESC")
			
						);		
	

?>