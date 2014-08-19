<?php
	$formArray = array(
					'username' => '',
					'email_address' => '',
					'new_password' => $this->generateCode(9),
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
			'name_last' => 'Patient Name',
			'username' => 'Email',
			'status' => 'Status',
			'last_login' => 'Last Login'
		);
	$planListHead = array(
			'p.plan_name' => 'Template Plan Name',
			'no_treatments' => '# T',
			'no_articles' => '# A',
		);
	
	$planListAction = array(
			'plan_view' => "View Plan",
			'plan_edit' => "Edit Plan",
			'deletePlan' => "Delete Plan",
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
			"preview"  => "Preview Article"
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
	
?>