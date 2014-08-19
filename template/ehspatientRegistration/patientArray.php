<?php
	$formArray = array(
					'username' => '',
					'email_address' => '',
					'refering_physician' => '',
					//'new_password' => $this->generateCode(9),
					'diagnosis' => '',
                                        'company' => '',
					'name_title' => '',
					'firstname' => '',
					'lastname' => '',
					'name_suffix' => '',
					'address1' => '',
					'address2' => '',
					'city' => '',
					'state' => '',
					'country' => '',
					'zipcode' => '',
					'phone1' => '',
					'phone2' => '',
					'status' => '1',
					
					);

	$tableFieldArray = array(
					'username' => 'email_address',
					'password' => 'new_password',
					'refering_physician' => 'refering_physician',
					'diagnosis' => 'diagnosis',
                                        'company' => 'company',
					'name_title' => 'name_title',
					'name_first' => 'firstname',
					'name_last' => 'lastname',
					'name_suffix' => 'name_suffix',
					'address' => 'address1',
					'address2' => 'address2',
					'city' => 'city',
					'state' => 'state',
					'country' => 'country',
					'zip' => 'zipcode',
					'phone1' => 'phone1',
					'phone2' => 'phone2',
					'status' => 'status',
					);
	
    $encrypt_field = array('password','address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2');
?>
