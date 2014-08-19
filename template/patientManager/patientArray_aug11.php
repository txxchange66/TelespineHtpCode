<?php
	$formArray = array(
					'username' => '',
					'email_address' => '',
					'new_password' => '',
					'diagnosis' => '',
					'name_title' => '',
					'name_first' => '',
					'name_last' => '',
					'name_suffix' => '',
					'address' => '',
					'address2' => '',
					'city' => '',
					'state' => '',
					'zip' => '',
					'phone1' => '',
					'phone2' => '',
					'status' => '1',
					);

	$tableFieldArray = array(
					'username' => 'email_address',
					'password' => 'new_password',
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
					'status' => 'status',
					);
	$patientListAction = Array(
							"act_view" => "View Patient",
							"act_edit" => "Edit Patient",
							"act_assign" => "Assign Plan",
							"act_delete" => "Discharge Patient",
							
						);
    $patientHeading = array(
                        'u.name_last' => 'Patient Name',
                        'u.username' => 'Email',
                        'u.status' => 'Status',
                        'u.last_login' => 'Last Login',
                        'clinic_name' => 'Clinic Name',
                    );
?>