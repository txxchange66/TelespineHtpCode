<?php
	$formArray = array(
					'treatment_name' => '',					
					'instruction' => '',
					'benefit' => '',
					'sets' => '',
					'reps' => '',
					'hold' => '',
					'pic1' => '',
					'pic2' => '',
					'pic3' => '',
					'video' => '',
					'status' => ''
					);

	$tableFieldArray = array(
					'treatment_name' => 'treatment_name',					
					'instruction' => 'instruction',
					'benefit'=>'benefit',
					'sets' => 'sets',
					'reps' => 'reps',
					'hold' => 'hold',
					'status' => 'status',
					'pic1' => 'pic1',
					'pic2' => 'pic2',
					'pic3' => 'pic3',
					'video' => 'video',
					
					);
	
	$status = Array(
							"1" => "Active",
							"2" => "Inactive",
							
						);	
						
	$arrCheckBox = array(
							array("name" => "speciality1", "lblName" => "Ortho", "value" => 1, "checked" => false, "extra" => "onMouseOver=\"help_text(this, '<!helpText>')\""),
							array("name" => "speciality2", "lblName" => "PED", "value" => 2, "checked" => false, "extra" =>"onMouseOver=\"help_text(this, '<!helpText>')\""),
							array("name" => "speciality3", "lblName" => "Cardio", "value" => 3, "checked" => false, "extra" =>"onMouseOver=\"help_text(this, '<!helpText>')\""),
							array("name" => "speciality4", "lblName" => "Neuro", "value" => 4, "checked" => false, "extra" =>"onMouseOver=\"help_text(this, '<!helpText>')\"")
							
						);	

	$arrRadioBox = array(
							array("name" => "lrb", "lblName" => "Left", "value" => 1, "checked" => false, "extra" => "onMouseOver=\"help_text(this, '<!helpText>')\""),
							array("name" => "lrb", "lblName" => "Right", "value" => 2, "checked" => false, "extra" =>"onMouseOver=\"help_text(this, '<!helpText>')\""),
							array("name" => "lrb", "lblName" => "Bilateral", "value" => 3, "checked" => false, "extra" =>"onMouseOver=\"help_text(this, '<!helpText>')\""),							
							array("name" => "lrb", "lblName" => "None", "value" => 0, "checked" => false, "extra" =>"onMouseOver=\"help_text(this, '<!helpText>')\"")							
							
						);							
?>