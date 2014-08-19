<?php
$configArray = array (

	'tx' => array(				
				'article' => array('media_url'=>'/asset/images/article/'),
				'treatment' =>array('media_url'=>'/asset/images/treatment/'),
				'richTextArea'=>array('dirPath' =>'/richtextarea/')
					),

  'table' =>  array (
						'user' => 'user',
						'treatment' => 'treatment',
						'category'	=> 'category',
						'therapist_patient' => 'therapist_patient',
						'plan' => 'plan',
						'treatment_speciality' => 'treatment_speciality',
						'treatment_category' => 'treatment_category',
						'plan_treatment' => 'plan_treatment',
						'plan_article' => 'plan_article',
						'article' => 'article',
						'clinic'	=> 'clinic',
						'clinic_user' => 'clinic_user',
			 ),

  'dbconfig' => array (

						'db_host_name' => 'localhost',

					    'db_user_name' => 'txxcha5_htpuser1',

					    'db_password' => 'htpuser1',

					    'db_name' => 'txxcha5_htpdevnew',

					    'db_type' => 'mysql',

			),

	'default_action' =>  array ('signUpPage'),

	'module' => array(
						'trialUser' => array('signUpPage',),
					),
	
	'user_type' => array( 

						'1' => "trialUser",

					 ),	

	'user_type_num' => array( 

								'trialUser' => "1",

					 ),						 

	'title' => array( 'Mr.' => "Mr.",

						  'Ms.' => "Ms.",

						  'Mrs.' => "Mrs.",

						  'Dr.' => "Dr."

						 ),	

	'suffix' => array( 'Sr.' => "Sr.",

						  'Jr.' => "Jr.",

						  'III' => "III",

						  'IV' => "IV",

						  'V' => "V"

						 ),

	'patientStatus' => array( '1' => "Current",
						      '2' => "Discharge",
						      '3' => "Archive"
						 ),
	'userStatus'	=> array( 	'1' => "Active",
								'2' => "Inactive",
								'3' => "Archive"
						),	
	'categoryStatus' => array( 	'1' => "Active",
								'2' => "Archive"
							),	
	'treatmentStatus' => array(
								'1' => "Active",
								'2' => "Inactive",
								'3' => "Archive"
	
							),	
							
	'reminderStatus' => array(
								'1' => "Active",								
								'2' => "Archive"
	
							),							
	'articleStatus' => array(
								'1' => "Active",
								'2' => "Inactive",
								'3' => "Archive"
	
							),								
	'articleType' => array(
								'1' => "Link",
								'2' => "File"	
							),							
							
	'speciality'     => array(
								'1' => 'Ortho',
								'2' => 'PED',
								'3' => 'Cardio',
								'4' => 'Neuro'
	
							),		
	'lrb'     =>         array(
								'1' => 'Left',
								'2' => 'Right',
								'3' => 'Bilateral'	
							),							
							
	'image' =>	array(
						'RESTRICT_WIDTH' => 1,
						'RESTRICT_HEIGHT' => 2,
						'IMAGETYPE_GIF' => 1,
						'IMAGETYPE_JPEG' => 2,
						'IMAGETYPE_PNG' => 3	
						),
						
	'persistpaging'		=>	array(
							'page'		=> '',
							'search'	=> '',
							'sort'		=> '',
							'order'		=> '',
							),
						
	'from_email_address'=> 'Jonathan Epstein <jepstein@txxchange.com>',
	'url' 				=> 'http://www.txxchange.com',
	'images_url' 		=> 'http://stage.txxchange.com',
	'application_url' 	=> 'http://www.beta.txxchange.com',
	'business_url' 		=> 'http://www.txxchange.com',
	'netsuite_url' 		=> 'http://www.txxchange.com',
	
	'cardtype'	=>	array(
	
						'3' => 'Discover',
						'4' => 'Master Card',
						'5' => 'VISA',
						'6' => 'American Express',
					),
	
	'state' => array( 

						'AK' => "Alaska",

						'AL' => "Alabama",

						'AR' => "Arkansas",

						'AZ' => "Arizona",

						'CA' => "California",

						'CO' => "Colorado",

						'CT' => "Connecticut",

						'DC' => "District of Columbia",

						'DE' => "Delaware",

						'FL' => "Florida",

						'GA' => "Georgia",

						'HI' => "Hawaii",

						'IA' => "Iowa",

						'ID' => "Idaho",

						'IL' => "Illinois",

						'IN' => "Indiana",

						'KS' => "Kansas",

						'KY' => "Kentucky",

						'LA' => "Louisiana",

						'MA' => "Massachusetts",

						'MD' => "Maryland",

						'ME' => "Maine",

						'MI' => "Michigan",

						'MN' => "Minnesota",

						'MO' => "Missouri",

						'MS' => "Mississippi",

						'MT' => "Montana",

						'NC' => "North Carolina",

						'ND' => "North Dakota",

						'NE' => "Nebraska",

						'NH' => "New Hampshire",

						'NJ' => "New Jersey",

						'NM' => "New Mexico",

						'NV' => "Nevada",

						'NY' => "New York",

						'OH' => "Ohio",

						'OK' => "Oklahoma",

						'OR' => "Oregon",

						'PA' => "Pennsylvania",

						'RI' => "Rhode Island",

						'SC' => "South Carolina",

						'SD' => "South Dakota",

						'TN' => "Tennessee",

						'TX' => "Texas",

						'UT' => "Utah",

						'VA' => "Virginia",

						'VT' => "Vermont",

						'WA' => "Washington",

						'WI' => "Wisconsin",

						'WV' => "West Virginia",

						'WY' => "Wyoming"
					 )
);

?>