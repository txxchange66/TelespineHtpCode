<?php
	$formArray = array(
					'username' => '',
					'email_address' => '',
					'refering_physician' => '',
					//'new_password' => $this->generateCode(9),
					'diagnosis' => '',
                    'company' => '',
					'name_title' => '',
					'name_first' => '',
					'name_last' => '',
					'name_suffix' => '',
					'address' => '',
					'address2' => '',
					'city' => '',
					'state' => '',
					'country' => '',
					'zip' => '',
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
					'name_first' => 'name_first',
					'name_last' => 'name_last',
					'name_suffix' => 'name_suffix',
					'address' => 'address',
					'address2' => 'address2',
					'city' => 'city',
					'state' => 'state',
					'country' => 'clinic_country',
					'zip' => 'zip',
					'phone1' => 'phone1',
					'phone2' => 'phone2',
					'status' => 'status',
					
					);
	$patientListAction = Array(
							"act_view" => "View Patient",
							"act_edit" => "Edit Patient",
							"act_assign" => "Assign Plan",
							//"act_delete" => "Discharge Patient",
							
						);
	$patientHeading = array(
			'name_first' => 'Patient Name',
			'username' => 'Email',
			'status' => 'Status',
			'last_login' => 'Last Login',
			'subscription_title'=>'E-Health Service'
		);
    $eMaintenanceHeading = array(
            'name_last' => 'Full Name',
            'username' => 'User Name',
            'start_date' => 'Start Date',
            'last_login' => 'Last Login'
        );
	
	
	$planListAction = array(
			'plan_view' => "View Plan",
			'plan_assign' => "Assign Plan",
			'plan_customize' => "Assign & Edit Plan",
			'plan_edit' => "Edit Plan",
            'copy_plan' => "Copy Plan",
			'deletePlan' => "Delete Plan",
	);
	$PatientPlanListAction = array(
			'plan_view' => "View Plan",
			'choose_patient' => "Select Plan",
            'plan_customize' => "Select & Edit Plan",
	);
	$customizeInstructionAction = array(
			'edit_instruction' => "Edit Instructions",
	);
	$instructionFormArray = array(
			'instruction' => '',
			'benefit' => '',
			'sets' => '',
			'reps' => '',
			'hold' => '',
			'lrb' => '',
	);
	$articleAddOption = array(
			"remove_article" => "Remove Article",
			"added_article_preview"  => "Preview Article"
	);
	
	$articleLibOption = array(
			"add_article" => "Add Article",
			"preview"  => "Preview Article"
	);
	$assignPatientOption = array(
			"view_patient_details" => "View Details",
			"choose_patient"  => "Select Patient"
	);
	$assignPatientHead = array(
			'u.name_last' => 'Patient Name',
		);
	$patientStatusCurrent = array(
		'viewPlan' => 'View Plan',	
		'current' => 'Current',
		'archive' => 'Archive Plan',
		'editPlan' => 'Edit Plan',
		'copyPlan' => 'Copy Plan',
		
	);
	$patientStatusArchive = array(
		'viewPlan' => 'View Plan',		
		'current' => 'Activate Plan',
		'deletePlan' => 'Delete Plan',
		'editPlan' => 'Edit Plan',
	);
	$step4ArticleLib = array(
		'a.article_name' => 'Article Name',
		'a.headline' => 'Headline',
		'a.modified' => 'Last Update'
		
		
	);
    $program = array(
        'cash-based-program' => '1'
    );
    $encrypt_field = array('password','address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2');
?>
