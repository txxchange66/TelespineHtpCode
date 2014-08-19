<?php
error_reporting(E_ERROR|E_WARNING);
ini_set('max_execution_time', -1);
ini_set('session.gc_maxlifetime', 18000);
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 1);
ini_set("display_errors",0);
ini_set("session.cache_expire", -1);
DEFINE('_ISO', 'charset=iso-8859-1');

/*
 * first set the default timezone to America/Chicago.
 * this timezone was in effect before implementation of TXM-31
 * default timezone shown to user will be CST (GMT -6) 
 */
date_default_timezone_set("America/Chicago");
$txxchange_config = array(
    /**
     * Defined path for aritcle,treatment,richTextArea.
     */
    'tx' => array(
        'article' => array('media_url' => '/asset/images/article/'),
        'treatment' => array('media_url' => '/asset/images/treatment/'),
        'richTextArea' => array('dirPath' => '/richtextarea/')
    ),
    /**
     * List of table used.
     */
    'table' => array(
        'user' => 'user',
        'treatment' => 'treatment',
        'category' => 'category',
        'therapist_patient' => 'therapist_patient',
        'plan' => 'plan',
        'treatment_speciality' => 'treatment_speciality',
        'treatment_category' => 'treatment_category',
        'plan_treatment' => 'plan_treatment',
        'plan_article' => 'plan_article',
        'article' => 'article',
        'clinic' => 'clinic',
        'clinic_user' => 'clinic_user',
        'mass_uploaded_files' => 'mass_uploaded_files',
    ),
    /**
     * Database connection information.
     */
    'dbconfig' => array(
        'db_host_name' => 'localhost',
        'db_user_name' => 'root',
        'db_password' => 'htp@123',
        'db_name' => 'mytxplandev',
        //'db_name' => 'mytxplantest',
        'db_type' => 'mysql',
    ),
    /**
     * Defult pages for different users.
     */
    'default_action' => array('login', 'agreement_patient', 'agreement', 'accountAdmin', 'agreement', 'patientlogin'),
    /**
     * Listing of modules in the application.
     */
    'module' => array(
        'therapist' => array(
            'therapist',
            'therapistPlan',
            'therapistEhsPlan',
            'reminder',
            'reminderEhs',
            'login',
            'associateTherapist',
            'popup',
            'therapistLibrary',
            'myAccount',
            'agreement',
            'messageTherapist',
            'treatmentManager',
            'alert',
            'bargraph',
            'goal',
            'intakePaper',
            'labreport',
            'soapnote',
            'superBill',
            'notepad',
            'automaticscheduling'
        ),
        'accountAdmin' => array(
            'accountAdmin_patient',
            'accountAdminClinic',
            'accountAdminUser',
            'myAccount',
            'reminder',
            'associateTherapist',
            'treatmentManager',
            'therapistLibrary',
            'marketing',
            'download',
            'alert',
            'website',
            'maintenance',
            'headAccountAdmin',
            'messageTherapist',
            'healthServiceReport',
            'intakePaper',
            'labreport',
            'superBill',
            'soapnote',
            'notepad',
            'automaticscheduling'
        ),
        'systemAdmin' => array(
            'sysAdmin',
            'systemPlan',
            'treatmentManager',
            'categoryManager',
            'article',
            'login',
            'popup',
            'subscriberManager',
            'clinic',
            'clinicDetail',
            'reminder',
            'associateTherapist',
            'agreement',
            'alert',
            'patientManager',
            'associateTherapist',
            'systemReport',
            'superBill'
        ),
        'patient' => array(
            'agreement_patient',
            'patient',
            'patientdashboard',
            'patientvideos',
            'patientarticles',
            'patientaccount',
            'popup',
            'login',
            'patientlogin',
            'messagePatient',
            'bargraph',
            'goal',
            'linegraph',
            'intakePaper',
            'labreport',
            'soapnote',
            'superBill',
            'getDayMessage',
            'changepassword',
            'checksignupcode',
            'checkdiscountcode'
        )
    ),
    /**
     * Type of users in application.
     */
    'user_type' => array('1' => "Patient",
        '2' => "Therapist",
        '3' => "Account Admin",
        '4' => "System Admin"
    ),
    /**
     * Numeric valude for Type of users in application.
     */
    'user_type_num' => array('patient' => "1",
        'therapist' => "2",
        'accountAdmin' => "3",
        'systemAdmin' => "4"
    ),
    /**
     * Type of title for users.
     */
    'title' => array('Mr.' => "Mr.",
        'Ms.' => "Ms.",
        'Mrs.' => "Mrs.",
        'Dr.' => "Dr."
    ),
    /**
     * Type of suffix for users.
     */
    'suffix' => array('Sr.' => "Sr.",
        'Jr.' => "Jr.",
        'III' => "III",
        'IV' => "IV",
        'V' => "V"
    ),
    /**
     * Type of status for patient.
     */
    'patientStatus' => array('1' => "Current",
        '2' => "Discharge",
        '3' => "Archive"
    ),
    /**
     * Type of status for user.
     */
    'userStatus' => array('1' => "Active",
        '2' => "Inactive",
        '3' => "Archive",
        '4' => "cradit card payment fail"   
    ),
    /**
     * Type of status for category.
     */
    'categoryStatus' => array('1' => "Active",
        '2' => "Archive"
    ),
    /**
     * Type of status for treatment.
     */
    'treatmentStatus' => array(
        '1' => "Active",
        '2' => "Inactive",
        '3' => "Archive"
    ),
    /**
     * Type of status for reminders.
     */
    'reminderStatus' => array(
        '1' => "Active",
        '2' => "Archive"
    ),
    /**
     * Type of status for articles.
     */
    'articleStatus' => array(
        '1' => "Active",
        '2' => "Inactive",
        '3' => "Archive"
    ),
    'articleType' => array(
        '1' => "Link",
        '2' => "File"
    ),
    /**
     * Type of speciality available.
     */
    'speciality' => array(
        '1' => 'Ortho',
        '2' => 'PED',
        '3' => 'Cardio',
        '4' => 'Neuro'
    ),
    'lrb' => array(
        '1' => 'Left',
        '2' => 'Right',
        '3' => 'Bilateral'
    ),
    'gender' => array('Male' => 'Male',
        'Female' => 'Female'),
    /**
     * Default image parameter setting.
     */
    'image' => array(
        'RESTRICT_WIDTH' => 1,
        'RESTRICT_HEIGHT' => 2,
        'IMAGETYPE_GIF' => 1,
        'IMAGETYPE_JPEG' => 2,
        'IMAGETYPE_PNG' => 3
    ),
    /**
     * Used to persist the state of any listing page.
     */
    'persistpaging' => array(
        'page' => '',
        'search' => '',
        'sort' => '',
        'order' => '',
    ),
    /**
     * Dimension for clinic logo.
     */
    'clinic_logo_image' => array(
        'width' => '210',
        'height' => '120',
    ),
    /**
     * For "from address" and system admin email address.
     */
    'from_email_address' => "sanjay gairola <sanjay.gairola@hytechpro.com>",
    'email_tx' => "support@txxchange.com",
    'email_wx' => "support@wholemedx.com",
    'business_tx' => "http://www.txxchange.com ",
    'business_wx' => "http://www.wholemedx.com ",
    'email_telespine' => "support@telespine.com", 
    'business_telespine' => "http://www.telespine.com/",
    'telespine_login'=>"http://telespine.txxchange.com",
    'telespine_admin'=>'sanjay.gairola@hytechpro.com',
    /**
     * For "Release version" displayed in left panel of login screen.
     */
    'release_version' => "2.4.5.0",
    /**
     * Url of business application.
     */
    'url' => "https://myhealthyback.txxchange.local:8016/",
    /**
     * This image url centralizes url path for all  images in the application.
     */
    'images_url' => "http://telespine.txxchange.local:8016/",
    /**
     * This application path centralizes path for application.
     * 'application_path' =>	"C:/wamp/www/txxchange/development/code/txxchange/"
     * 
     */
    'application_path' => "/var/www/html/txxchange/branches/telespine-release-1.0/",
    /**
     * Key for encryption and decryption
     */
    'private_key' => 'a2dc2a99e649f147bcabc5a99bea7d96',
    /**
     * List of US states.
     */
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
    ),
    'canada_state' => array(
        'AB' => "Alberta",
        'BC' => "British Columbia",
        'MB' => "Manitoba",
        'NB' => "New Brunswick",
        'NL' => "New Foundland",
        'NT' => "Northwest Territories",
        'NS' => "Nova Scotia",
        'NU' => "Nunavut",
        'ON' => "Ontario",
        'PE' => "Prince Edward Island",
        'QC' => "Quebec",
        'SK' => "Saskatchewan",
        'YT' => "Yukon Territories"
    ),
    'country' => array('US' => "United States",
        'CAN' => "Canada"),
    'countrypaypal' => array('US' => "United States",
        'CA' => "Canada"),
    'freetrial' => 30,
    'paypalprodetails' => array('API_UserName' => 'localsanjay_api1.txxchange.com',
        'API_Password' => '1393335309',
        'API_Signature' => 'AYg6mSoyMaya-Fm29xmgl6Gy5-zRAJxKcAgOhx4qdaiOdyCUE8HloTlP',
        'currencyID' => 'USD',
        'startDate' => date('Y-m-d') . 'T0:0:0',
        'billingPeriod' => 'Month',
        'billingFreq' => '2',
        'environment' => 'sandbox'),

    'clinicpaypalprodetails' => array('API_UserName' => 'rohit_api1.hytechpro.com',
        'API_Password' => 'CG4Y82F732YKVTK7',
        'API_Signature' => 'ADFBKlb0l9ke3uTSj7XsKD1OnUgfAWccoNXtm9ZG6J8-72-DoU9cdeT3',
        'currencyID' => 'USD',
        'startDate' => date('Y-m-d') . 'T0:0:0',
        'billingPeriod' => 'Day',
        'billingFreq' => '1',
        'environment' => 'sandbox'),
        'wholemedxurl' => 'http://localtxxchange.com:8013/wmdx/index.php',
        'error_message' => 'We have experienced an error and apologize for any inconvenience. Please login and try again. If the problem persists, please contact <a href="mailto:support@txxchange.com">support@txxchange.com</a>',
        'paymentDuration' => array('' => "Select Duration",
        '1' => "1 Month",
        '2' => "2 Month",   
        '3' => "3 Months",
        '6' => "6 Months",
        '9' => "9 Months",
        '12' => "12 Months"),
    'monthsArray' => array('' => "Exp. Month",
        '01' => "Jan 01",
        '02' => "Feb 02",
        '03' => "Mar 03",
        '04' => "Apr 04",
        '05' => "May 05",
        '06' => "Jun 06",
        '07' => "Jul 07",
        '08' => "Aug 08",
        '09' => "Sep 09",
        '10' => "Oct 10",
        '11' => "Nov 11",
        '12' => "Dec 12"),
    'yearArray' => array('' => "Exp. Year",
        '2014' => "2014",
        '2015' => "2015",
        '2016' => "2016",
        '2017' => "2017",
        '2018' => "2018",
        '2019' => "2019",
        '2020' => "2020",
        '2021' => "2021",
        '2022' => "2022",
        '2023' => "2023",
        '2024' => "2024",
        '2025' => "2025"),
    'clinic_price'=>89,
    'timezone' => array(
        // this is the default one that is there in the database currently, it is being deliberately set in index.php as well. 
        'default' => array(
            'region' => 'America/Chicago'
        ),
        //These will impact the dates shown in frontend, if no region is specified in database then CST will be used
        'frontend' => array(
            'region' => 'America/New_York',
            'dateformat' => 'm/d/Y h:i A'
        )
    ),
    'automaticduration'=>   array('1'=>'1 week',
                                  '2'=>'2 week',
                                  '3'=>'3 week',
                                  '4'=>'4 week',
                                  '5'=>'5 week',
                                  '6'=>'6 week',
                                  '7'=>'7 week',
                                  '8'=>'8 week'
                                  ),
    'telespineid'=>381,
    'configdate'=>  date('Y-m-d'), //'2014-03-21' //
    'domain'=>'myhealthyback',
    'channelname'=>array('bj'=>'BackJoy','h2'=>'H2U','TS'=>'TeleSpine')
  );

define('EHSCLINICID', '');
?>
