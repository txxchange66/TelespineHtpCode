<?php
 
require_once("config.php");
$host = $txxchange_config['dbconfig']['db_host_name'];
$user = $txxchange_config['dbconfig']['db_user_name'];
$pass = $txxchange_config['dbconfig']['db_password'];
$db   = $txxchange_config['dbconfig']['db_name'];
$application_path   = $txxchange_config['application_path'];
$telespine_id=$txxchange_config['telespineid'];

$imagePath = $txxchange_config['images_url'];
$from_email_address = $txxchange_config['email_telespine'];
$loginurl=$txxchange_config['telespine_login'];
// Make connection with server.
$link = @mysql_connect($host,$user,$pass);
// select database.
@mysql_select_db($db,$link);        

		/* Function for sneding Reminder mail to patients who had not logged in for 1 days after their account creation. */
		function reminderMail(){
            global $from_email_address,$templatePath,$imagePath,$txxchange_config,$telespine_id,$loginurl,$application_path;
			 $query = " SELECT usr.user_id,usr.username,AES_DECRYPT(UNHEX(usr.name_first),'{$txxchange_config['private_key']}') as name_first,AES_DECRYPT(UNHEX(usr.name_last),'{$txxchange_config['private_key']}') as name_last,usr.username,DATEDIFF( current_date, DATE( usr.creation_date ) )+1 as cuurday,cu.clinic_id  FROM user usr,clinic_user cu  WHERE usr.usertype_id =1 AND usr.STATUS !=3 AND usr.agreement = 1 AND  usr.user_id=cu.user_id AND cu.clinic_id='".$telespine_id."'";
						
			$result = @mysql_query($query) ; 
			// taking every user's date data line by line
			if(@mysql_num_rows($result)){
				while ($row = @mysql_fetch_array($result)) {
						//fill in data for mail content.
						
						$current_day = $row['cuurday'];
						//$current_day = $current_day%50;
						$data = array() ;
                                                $data['images_url'] = $imagePath;
						//code section for sending of mail.
						$user_id = $row['user_id'];
						$fullname = $row['name_first'].'  '.$row['name_last'];
						$data['fullname'] = $fullname;
						$data['loginurl']=$loginurl;
						
						
                                                $clinicName	=  get_clinic_info($user_id,"clinic_name");
						$data['clinicName'] = $clinicName;
						$data['username'] = $row['username'];
						$clinic_type = getUserClinicType($user_id);
						$clinic_channel=getchannel(get_clinic_info($user_id,'clinic_id'));
                                                $business_url=$txxchange_config['business_telespine']; 
                                                $support_email=$txxchange_config['email_telespine'];
						$images_url=$txxchange_config['images_url'];
                                                $data['images_url'] = $images_url;
						
                                                $data['support_email'] = $support_email;
						
						
						$to = $fullname.'<'.$row['username'].'>';	
						$subject ="Day ".$current_day." : Telespine Focus";
						// To send HTML mail, the Content-type header must be set
						$headers  = 'MIME-Version: 1.0' . "\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
						
					
				
						  $headers .= "From: Telespine Support<".$txxchange_config['email_telespine'].">" . "\n";
						 $returnpath = "-f".$txxchange_config['email_telespine'];
						 //$headers .= 'Bcc: ' . $from_email_address . "\n"; // will be deleted later.
						
						echo "<br>".$row['username']."==>".$current_day;
						
						switch ($current_day) {
						
						case "1":
								$data_base_message='Welcome to your first week of Telespine! This week healing is the number one priority. To heal as quickly as possible, we`ll want you to maintain good posture and keep your spine in a neutral position. This will be the your first priority and help to alleviate the pain. You can also apply ice or heat and practice breathing techniques we`ll provide to speed up the healing process. You will notice that you have a lot of videos to watch this week. Do not be overwhelmed. Many of them are simply informational. During the first two weeks of your program, you will be given more exercises than later in the program. Starting in week two, you will see that you have two rest days each week.  For this first week however, be sure to make time for your recovery exercises each day. Every exercise and stretch will help you build a solid foundation of spinal strength, flexibility and mobility.';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								
								break;
								
								
						case "2":
								$data_base_message='Hi there. You`ll be given exercises, stretches, and activities in sets so that your muscles can get stronger before we progress you to the next set of exercises. There are two things we want you to learn and keep doing every day: 1) Maintain your neutral spine through good posture and 2) Contract your inner core. We hope that over time both will become a daily habit of yours. In the meantime, keep watching the videos and follow what they show.';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								
								break;
								
								
						case "3":
								$templatePath = $application_path."mail_content/telespine/".$current_day."_day_notification.php";
								
								$message = build_template($templatePath,$data);
								//echo $message;
								// Mail it
								//////echo $to;
								//@mail($to, $subject, $message, $headers, $returnpath); 
								
								
								$data_base_message='We hope that you`re starting to see some improvement. Keep up the ice or heat and maintain good posture. We`re going to continue to focus on getting your back healed and starting your recovery exercises. Recovery exercises will help to stimulate blood flow to your injured muscles, speeding up the healing process. They will also help you to become more mobile for everyday activities. All exercises shown in the Telespine program are good for you, no matter whether you are currently experiencing low back pain or are looking to prevent it.';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								
								
								
								
								
								break;
								
								case "4":
								$data_base_message='Welcome to day 4 of your program. How are you sleeping at night? To further promote healing we strongly recommend trying to use pillow or cushion support at night. If you are a side lying sleeper, place the pillow between your knees. If you are a back sleeper, try placing the pillow under your knees for relief. For the tummy sleepers out there, try placing a pillow under your hips. You may need to sleep without a pillow under your head to reduce pressure on your lower back. More important than which position is best to sleep in, is that you get a good night of sleep. You may want to try each position to see which relieves the greatest amount of pain and allows for a good night`s rest. ';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								
								break;
								
								case "5":
								$data_base_message='Today would be a good day to start adding goals if you haven`t already. You will see the goal section on the lower left of this page. We recommended picking 1 to 3 goals per week. The goals should be 1) simple 2) measurable and 3) related to healing your back. Here are some examples. Goal #1: Do my 3 exercises from Telespine today before work and before going to bed. Goal #2: Walk 30 minutes 2 times this week on Wednesday at 5:30pm and Saturday morning at 9:30am. Goal #3: Bring a water bottle to work and fill up 4 times each day.';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								
								break;
								
								case "6":
								$data_base_message='Hopefully you are beginning to feel better. This week`s reading materials contain extremely useful information on how to reduce stress. The way you breath plays a much larger role in the pain you experience than you may realize. Today, if you feel stressed or pain, watch the breathing video and read about the ways to reduce stress. Breathing and relaxing will also help you with your posture by relaxing tight muscles in your shoulders and back.';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								
								break;
								
								case "7":
								$data_base_message='Did you find that writing down your goals was helpful? If it wasn`t, perhaps try making them simpler and start with just one goal. Your #1 goal should be do to the assigned activities 3-5 times a week. Your program is designed for 5 days a week, but if you are only able to do them 3 times a week you`ll still benefit.  By doing the exercises 3-5 times a week your back will get stronger and stay stronger - which will prevent painful episodes in the future.';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								
								break;
								
								
								
								
						
						
							case "8":
								$templatePath = $application_path."mail_content/telespine/".$current_day."_day_notification.php";
								
								$message = build_template($templatePath,$data);
								//echo $message;
								// Mail it
								//////echo $to;
								//@mail($to, $subject, $message, $headers, $returnpath); 
								////@mail('rohit.mishra@hytechpro.com', $subject, $message, $headers, $returnpath); 
								$data_base_message='Congratulations on completing the first week of Telespine! This upcoming week you will continue recovery activities to promote healing in your lower back. You`ll see that there are a lot of inner core strengthening exercises. While we`re still giving you some recovery exercises, you will also start corrective exercises. Corrective exercises will help strengthen and align weak and injured areas of your back. These exercises are safe for everyone of all abilities. As always though, if you find that you are experiencing too much pain doing the exercises, stop the activity, and go back to the earlier exercises until you feel ready for the next set. Make sure you take the time to write down your goals for the week. Writing them down will help you stay accountable and ultimately, help you achieve them. Keep up the good work!';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								
												
								
								break;
								
								
								
								case "9":
								$data_base_message='You may have noticed in the videos that we talk a lot about your `inner core` or we talk about contracting your `natural corset`. When talking about your inner core we are mainly talking about your transversus abdominis (TrA). This is your deepest core muscle and the only core muscle that wraps around to your spine. This amazing muscle supports and protects your back during nearly every movement you make. It has been found that by strengthening your TrA, you will heal significantly faster and prevent future injury of your lower back.  Keep up your recovery exercises so that your back will be ready for a new set of exercises coming up soon. ';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								
								break;
								
								
								case "10":
								$data_base_message='Today is a REST DAY! This is a day for you to take a break from exercising. Even on rest days however, you still need to work on 1) maintaining good posture 2) contracting your natural corset (TrA) throughout the day and 3) not sitting for prolonged periods of time and 4) and keeping active. Please keep in mind that if this is not a good rest day for you, you can switch days. Just make sure that you are giving yourself two rest days a week.  If your lower back is still bothering you and you feel stiff, you can do backward extensions throughout the day. Have a good rest day!';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								
								break;
								
								case "11":
								$data_base_message='Hopefully you had a nice rest day and you are ready to get back to your daily exercises. While you are doing your exercises picture your TrA getting stronger, your hips becoming more flexible, and your joints less stiff. Did you know that when you do exercises or stretches that your body releases a fluid into your joints that lubricates them? This results in less pain and more ease of movement. Whether you are super fit or haven`t exercised in years, the exercises that you are given in this program are proven to be the best way to stabilize and protect your spine from all of life`s activities.';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								case "12":
								$data_base_message='What you do for a living can have a major impact on your lower back. Whatever you do for work, even if you are retired or a stay at home parent, make sure you do not sit for prolonged periods of time. If you sit often, it’s essential to stand every 20-60 minutes. Get up to fill a water bottle, use the restroom, or just take a 1 minute walk around. Maybe you can walk during your lunch break. On the other hand, if you stand for work, make sure you shift your weight between your feet and put one foot at a time up on an elevated surface to relieve pressure in your spine. Take time to read the WorkPlace and Low Back Pain articles. They will show you ways to positively impact on your overall health. If you are retired or work at home, there are many important principles in these articles that apply to you too.';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								case "13":
								$data_base_message='Your body has had nearly two weeks of recovery exercises. We gave you exercises to help decrease pain and inflammation and we gave you exercises to increase mobility, flexibility, and strength. Your body is now ready for slightly more challenging exercises. You may notice that the exercises involve more moving around. It may be challenging to keep your TrA engaged while, for example, moving your legs in the Up Up Down Down exercises and that is exactly what we want. We want to start to challenge your body. This is necessary in order to make your muscles stronger. Please remember, however, that while you should feel a natural burning sensation while challenging your muscles, you should be no means feel sharp or sudden pains. If you feel that your pain is not normal for the exercise, stop the exercise, and go back to the first exercises in your plan until your body is ready for the more challenging ones.';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								
								case "14":
								$data_base_message='Today is a REST DAY! This is a day for you to take a break from exercising. Even on rest days however, you still need to work on 1) maintaining good posture 2) contracting your natural corset (TrA) throughout the day and 3) not sitting for prolonged periods of time and 4) and keeping active. Please keep in mind that if this is not a good rest day for you, you can switch days. Just make sure that you are giving yourself two rest days a week.  If your lower back is still bothering you and you feel stiff, you can do backward extensions throughout the day. Have a good rest day!';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								

								
							case "15":
								$templatePath = $application_path."mail_content/telespine/".$current_day."_day_notification.php";
								$message = build_template($templatePath,$data);
								// Mail it
								//echo $to;
								//@mail($to, $subject, $message, $headers, $returnpath); 
								$data_base_message='';
								$data_base_message='Congratulations on completing week 2 of Telespine. Hopefully you are continuing to feel better. In week 3 you will continue recovery activities, as well as exercises that improve mobility. Please note that for several types of exercises we offer different levels. Please select the easiest level first before attempting the next level up. Make sure you take the time to write your goals for the week. Writing the when, where and which exercises and activities you will do this week will help you achieve them. Keep up the good work! ';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 15',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);	
								
								break;
								
							case "16":
								$data_base_message='Welcome to Day 16. During this phase you will see an exercise called "Bridge". This is a fantastic exercise to strengthen your core, back, gluteus maximus (buttock) and thighs. Not only will "Bridge" make your body look good, it will also help to make you stronger while you are lifting objects, walking, and participating in athletic activities. You have also already tried out the plank exercise. Please keep in mind that you can keep doing the modified plank as long as you need to. If you feel that the modified plank is too easy, try the next level up. Plank strengthens every core muscle, your back muscles, legs and arms! Be sure not to drop your head when you get too tired and don’t forget to contract your TrA. ';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								case "17":
								$data_base_message='Today is a REST DAY! This is a day for you to take a break from exercising. Even on rest days however, you still need to work on 1) maintaining good posture 2) contracting your natural corset (TrA) throughout the day and 3) not sitting for prolonged periods of time and 4) and keeping active. Please keep in mind that if this is not a good rest day for you, you can switch days. Just make sure that you are giving yourself two rest days a week.  If your lower back is still bothering you and you feel stiff, you can do backward extensions throughout the day. Have a good rest day!';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								case "18":
								$data_base_message='How are you doing keeping up on your exercises? By doing your exercises, you will continue to get stronger, more flexible and mobile. To maximize the healing process you will notice that we have provided you an article on nutrition and low back pain. We are sure you have heard the old adage "you are what you eat". There is a lot of truth to this statement. Pain has been directly correlated with chronic inflammation. Chronic inflammation can be the result of a poor diet and being overweight. Fight back by eating foods that decrease inflammation and you will help decrease your back pain. You will also get the added benefit of absorbing vitamins that help with memory, vision, weight loss, and even fight heart disease and cancer.';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								
								case "19":
								$data_base_message='Now that you have been participating in the program for a little while, you find yourself forgetting about doing your exercises and stretches because you`re starting to feel better. However, if you start feeling better and you stop exercising and stretching your muscles will begin to lose the strength and flexibility you worked for. Don`t think of doing these exercises and stretches as temporary. They need to be thought of as a beneficial everyday habit - like brushing your teeth.  If you find yourself falling off your routine, don’t give in. Establish a long term routine and you`ll have lasting low back pain relief!';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								
								case "20":
								$data_base_message='Hello there. Are you keeping up with your Telespine program? If you are, great job! If not, don`t worry. It`s not too late to get back into a routine. Keep in mind though, that you can lose up to 80% of what you have gained in muscle strength in as little as 1.5 weeks (of course, this depends on your base fitness level. Top athletes can take up to 12 weeks to lose 80% of their muscle mass). Document your goals in the software, be accountable, and you`ll achieve them!';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								
								case "21":
								$data_base_message='Today is a REST DAY! This is a day for you to take a break from exercising. Even on rest days however, you still need to work on 1) maintaining good posture 2) contracting your natural corset (TrA) throughout the day and 3) not sitting for prolonged periods of time and 4) and keeping active. Please keep in mind that if this is not a good rest day for you, you can switch days. Just make sure that you are giving yourself two rest days a week.  If your lower back is still bothering you and you feel stiff, you can do backward extensions throughout the day. Have a good rest day!';
								
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								
							case "22":
								$templatePath = $application_path."mail_content/telespine/".$current_day."_day_notification.php";
								$message = build_template($templatePath,$data);
								// Mail it
								//echo $to;
								//@mail($to, $subject, $message, $headers, $returnpath); 
								
								$data_base_message='Nice job getting through the first 3 weeks of Telespine. Your lower back is now well on its way to being recovered from acute low back and beginning to get stronger and more flexible. This week you will start corrective exercises that will strengthen and stretch your back muscles, ligaments, and joints. Make sure you take the time to write down your goals for the week. Writing them down will help you stay accountable and ultimately, help you achieve them. Keep up the good work!';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 22',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								
								case "23":
								$data_base_message='You may have noticed that you are starting to do more full body movements and that the exercises are requiring more major muscle groups. We added these exercises because we know that low back pain can be due to problems with other areas of the body. By doing these exercises, we are making your entire body stronger and better able to support and protect your back.';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								
								case "24":
								$data_base_message='Today is a REST DAY! This is a day for you to take a break from exercising. Even on rest days however, you still need to work on 1) maintaining good posture 2) contracting your natural corset (TrA) throughout the day and 3) not sitting for prolonged periods of time and 4) and keeping active. Please keep in mind that if this is not a good rest day for you, you can switch days. Just make sure that you are giving yourself two rest days a week.  If your lower back is still bothering you and you feel stiff, you can do backward extensions throughout the day. Have a good rest day!';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
							
								case "25":
								$data_base_message='Every time you exercise, your body creates an enzyme that builds muscle, increases oxygen in your bloodstream and uses up sugars, thus preventing it from becoming fat. By completing your exercise and stretch activities, your spine will become more flexible which will allow you to participate in more athletic endeavors and gain the benefits listed above.';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								
								case "26":
								$data_base_message='Welcome to Day 26. We hope by now that you are starting to feel much better and that you`ve found a good routine. If you do have a solid routine down, now is the time to add more goals related to cardiovascular exercise (if you are not already doing so). Examples of cardiovascular exercise include walking, running or swimming. If you are having a hard time finding a good routine you may want to try keeping a daily journal or find a workout partner to do the exercises with.';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								
								case "27":
								$data_base_message='How are you today? This is a friendly reminder that when you are watching a new exercise or stretch we recommended that you watch the video first at least one time before trying the exercise. When you are doing the exercise listen for cues such as "contract" your core or "relax" your shoulders. Please make sure you are not twisting in an awkward position to watch the video while doing the exercise.';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
							
								case "28":
								$data_base_message='Today is a REST DAY! This is a day for you to take a break from exercising. Even on rest days however, you still need to work on 1) maintaining good posture 2) contracting your natural corset (TrA) throughout the day and 3) not sitting for prolonged periods of time and 4) and keeping active. Please keep in mind that if this is not a good rest day for you, you can switch days. Just make sure that you are giving yourself two rest days a week.  If your lower back is still bothering you and you feel stiff, you can do backward extensions throughout the day. Have a good rest day!';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								

								
								
								
								
							case "29":
								$templatePath = $application_path."mail_content/telespine/".$current_day."_day_notification.php";
								$message = build_template($templatePath,$data);
								// Mail it
								//echo $to;
								//@mail($to, $subject, $message, $headers, $returnpath); 
								$data_base_message='Way to go, you`re past the half-way mark of the Telespine program! You`re entering week 5 and will continue to work on the corrective exercises that promote core strength, mobility and better posture. Make sure you take the time to write down your goals for the week. Writing them down will help you stay accountable and ultimately, help you achieve them. Don`t forget - keep participating in the program even if you are feeling better. You have to stick with it for lasting pain relief. ';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 29',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								
								
								break;
								
								
								case "30":
								$data_base_message='As far as your low back is concerned, this is an exciting week. Your activities will now start to include range of motion exercises. These exercises will increase your flexibility and movement in your joints, and improve your balance. They’ll feel good and are great to use as a warm-up to your everyday exercise routine.';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								
								
								case "31":
								$data_base_message='Today is a REST DAY! This is a day for you to take a break from exercising. Even on rest days however, you still need to work on 1) maintaining good posture 2) contracting your natural corset (TrA) throughout the day and 3) not sitting for prolonged periods of time and 4) and keeping active. Please keep in mind that if this is not a good rest day for you, you can switch days. Just make sure that you are giving yourself two rest days a week.  If your lower back is still bothering you and you feel stiff, you can do backward extensions throughout the day. Have a good rest day!';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								
								case "32":
								$data_base_message='Hi. We hope you are enjoying the exercises so far. Please remember to keep contracting your TrA throughout day, during exercises, and while lifting any objects. If your job requires lots of sitting, one way to remember to stand up often is to set a timer. Set your timer for every 20-60 minutes. Also, don`t forget those workplace stretches.';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								case "33":
								$data_base_message='Welcome to Day 33. Let`s take a couple moments to review work out safety. 1) Long gone are the days of holding static stretches at the beginning of workout. Instead, do a warmup for 5-10 minutes such as walking, jogging or going through light movements. 2) Always start slowly and be aware that exercising too hard too fast can cause injury. 3) Mix up your workout if possible to work different muscle groups and avoid overuse injuries. 4) Drink plenty of water and back off your effort level if you experience pain you believe to be beyond what you should be feeling during exercise. 5) Make sure you’re wearing good shoes for the activity you`re participating in. 6) If you are learning a new exercise, focus on your form and stop when you feel fatigued. 7) Prepare for the weather and make sure you are not too hot or cold. And lastly,  if you experience persistent pain as a result from working out, pain that does not go away within a week or two, you should consider calling your healthcare provider.';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								case "34":
								$data_base_message='Welcome to Day 34. Now that we`re coming to the end of week 5, we expect that your back is feeling much better. Do you have a regular cardiovascular exercise routine already? You may already be aware of all the benefits of exercises, but did you know that it helps significantly with the reduction of low back pain? A recent study found that people who started a walking routine dramatically reduced their low back pain levels. If you are already an avid exerciser, there are 3 main factors to consider before you jump back into your favorite sport or exercise routine after an episode of low back pain: 1) Are you no longer experiencing pain or do you only have very mild pain? 2) Is your range of motion back to normal without pain or close to normal without pain? 3) Have you regained enough strength and endurance needed for your sport? If you can answer "Yes" to all 3 of those questions, you are ready to return to your normal level of activity. If not, keep rebuilding your lower back foundation and you should be ready in a couple of weeks. ';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								case "35":
								$data_base_message='Today is a REST DAY! This is a day for you to take a break from exercising. Even on rest days however, you still need to work on 1) maintaining good posture 2) contracting your natural corset (TrA) throughout the day and 3) not sitting for prolonged periods of time and 4) and keeping active. Please keep in mind that if this is not a good rest day for you, you can switch days. Just make sure that you are giving yourself two rest days a week.  If your lower back is still bothering you and you feel stiff, you can do backward extensions throughout the day. Have a good rest day!';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								
								
								
								
								
							case "36":
								$templatePath = $application_path."mail_content/telespine/".$current_day."_day_notification.php";
								 $message = build_template($templatePath,$data);
								// Mail it
								//echo $to;
								//@mail($to, $subject, $message, $headers, $returnpath); 
								$data_base_message='Congratulations on completing the first 5 weeks of Telespine. In week 6, you will finish the corrective exercise phase and begin to get ready for the dynamic phase of this program. Remember it is the combination of strength, flexibility, healthy eating, good posture and using stress reduction techniques that will keep your back happy. Make sure you take the time to write down your goals for the week. Writing them down will help you stay accountable and ultimately, help you achieve them. Don`t forget - keep participating in the program even if you are feeling better. Keep up the good work!';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 36',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								
								
								
								
								break;
								
								case "37":
								$data_base_message='Welcome to Day 37 of your program! The Dynamic Phase exercises you`ve begun are training your back to stay in neutral spine. When you sit with poor posture your lower spine is being pulled by the weight of your upper body. By sitting in neutral spine, you immediately take pressure off of your lower back. By doing your exercises on a regular basis you will both train your back to find the right position and have the strength to maintain the right position.';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								case "38":
								$data_base_message='Today is a REST DAY! This is a day for you to take a break from exercising. Even on rest days however, you still need to work on 1) maintaining good posture 2) contracting your natural corset (TrA) throughout the day and 3) not sitting for prolonged periods of time and 4) and keeping active. Please keep in mind that if this is not a good rest day for you, you can switch days. Just make sure that you are giving yourself two rest days a week.  If your lower back is still bothering you and you feel stiff, you can do backward extensions throughout the day. Have a good rest day!';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								case "39":
								$data_base_message='Hello. Let`s talk about yoga. Some people enjoy yoga, some people want nothing to do with it, and others have had their lives changed by it. No matter how you feel about it, the fact is, if done right, yoga is an incredible combination of strengthening and stretching for your low back. Study after study has found that yoga has a very positive impact on low back pain. Doing yoga safely is the key to low back pain relief. Start with a beginner class and only do positions that your body feels comfortable doing. Do not worry about what the rest of the class is doing. Don`t be afraid to ask the teacher for modifications and be sure to let him or her know that you have had lower back pain issue';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								
								case "40":
								$data_base_message='Welcome to Day 40! Today, let’s talk about shoes. Having the right shoes can aid in healing your lower back, especially if you are on your feet often. You need to find shoes that have good support and absorb shock. Flip flops, flats, and heels need to be reserved for times that require very little walking or standing. One solution is to wear sneakers while you walk to and from your workplace or event and then slip on your nicer shoes when you arrive.';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								case "41":
								$data_base_message='Yesterday we told you a little about the importance of wearing the right shoes. Today, we`d like to talk about the importance of stress relief. In the beginning of the program we gave you an article on the importance of reducing stress. The article included some stress relieving techniques. Many people are surprised by how much of a role stress can play in their back pain. Even more people are surprised by how breathing can reduce their stress. Take a moment today and take in 5 slow, deep breathes.  As you exhale, picture breathing out your stress.  At the same time, focus on relaxing your shoulders and jaw. By doing this, your muscles will relax, your circulation will improve, and you`re lower back will be less prone to strain and pain.';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								case "42":
								$data_base_message='Today is a REST DAY! This is a day for you to take a break from exercising. Even on rest days however, you still need to work on 1) maintaining good posture 2) contracting your natural corset (TrA) throughout the day and 3) not sitting for prolonged periods of time and 4) and keeping active. Please keep in mind that if this is not a good rest day for you, you can switch days. Just make sure that you are giving yourself two rest days a week.  If your lower back is still bothering you and you feel stiff, you can do backward extensions throughout the day. Have a good rest day!';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
							case "43":
								$templatePath = $application_path."mail_content/telespine/".$current_day."_day_notification.php";
								$message = build_template($templatePath,$data);
								// Mail it
								//echo $to;
								//@mail($to, $subject, $message, $headers, $returnpath); 
								$data_base_message='Congratulations on completing 7 weeks of Telespine.  By now, your back should be more stable, strong, and mobile. The activities for this week will continue to build your dynamic strength and mobility, which will protect you from future low back pain episodes. Make sure you take the time to write down your goals for the week. Writing them down will help you stay accountable and ultimately, help you achieve them. We strongly recommend you continue doing all of the activities you`ve learned, even after completion of the program, to keep your back healthy longer term. ';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 43',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								
								break;
								
								
								case "44":
								$data_base_message='Welcome to Day 44 of your program. Today we will review the importance of staying involved in the program. Did you know that without participation in a program like Telespine, acute low back pain returns 84% of the time? Worse yet, when people don’t do anything about their low back pain over time, there`s a good chance that acute low back pain can become a chronic problem. The good news is that by keeping up with your exercise routine and maintaining good posture and other healthy habits, you can significantly reduce the chances of your low back pain returning. The exercises you`ll be provided over the last two weeks of the program will help get your back ready for sudden movements, lifting of objects, and improve your balance.';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								case "45":
								$data_base_message='Today is a REST DAY! This is a day for you to take a break from exercising. Even on rest days however, you still need to work on 1) maintaining good posture 2) contracting your natural corset (TrA) throughout the day and 3) not sitting for prolonged periods of time and 4) and keeping active. Please keep in mind that if this is not a good rest day for you, you can switch days. Just make sure that you are giving yourself two rest days a week.  If your lower back is still bothering you and you feel stiff, you can do backward extensions throughout the day. Have a good rest day!';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								case "46":
								$data_base_message='Do you know of the relationship between squats and your lower back? By doing squats, you will strengthen your hips, thighs and buttocks. Contract your TrA during the squat and you will also strengthen your core and low back muscles. With your lower body stronger, you will decrease the load placed on your lower back, which will decrease the chances of injuring or re-injuring your lower back. To do a squat safely, place more of your weight in the back of your heels and don`t over arch your lower back too much. ';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								case "47":
								$data_base_message='Did you know that both sitting and standing for extended periods can have a major impact on your back health? A lot of sitting will increase the tightness in your hips which will result in muscles and joints tugging at your back. It also places extra stress on your discs, reducing their ability to absorb shock. Combat this by standing, exercising, or even just a walking around for a few minutes to get a break from sitting. Don`t forget if you do have to sit for long periods, maintain a good neutral spine and posture (no slouching). If you feel like your back is tight or you have back pain, avoid sitting on soft couches that round your back (i.e. get you out of your neutral spine). If you`ve had a long day of standing, shifting your weight between feet or getting off of your feet for a few minutes will relieve pressure on your spine.';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								case "48":
								$data_base_message='Welcome to Day 48 of your program. If you haven`t already added a strength training program to your exercise routine here is why you should. First, strength training helps with weight loss and maintaining weight loss. By building your muscles, your body will also learn to burn calories more efficiently even when you aren`t working out. Second, strength training will help make your bones stronger, making it easier to manage conditions such as, you guessed it, back pain, and even arthritis.';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								
								case "49":
								$data_base_message='Today is a REST DAY! This is a day for you to take a break from exercising. Even on rest days however, you still need to work on 1) maintaining good posture 2) contracting your natural corset (TrA) throughout the day and 3) not sitting for prolonged periods of time and 4) and keeping active. Please keep in mind that if this is not a good rest day for you, you can switch days. Just make sure that you are giving yourself two rest days a week.  If your lower back is still bothering you and you feel stiff, you can do backward extensions throughout the day. Have a good rest day!';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								case "50":
								$data_base_message='Congratulations on completing 7 weeks of Telespine. By now your back should more mobile, stable and strong. The activities for this week will continue to build your dynamic strength and mobility, which will protect you from future low back pain episodes. We recommend that you continue doing all of the activities you`ve learned even after completion of the program This will ensure you maintain a healthy back. This is your last week to write down and achieve your goals. If writing them down has been helpful for you, we recommend you keep writing down your goals even after the completion of the program.';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								case "51":
								$data_base_message='Welcome to Day 51. Since you may be increasing your exercise routine, here is a reminder on workout safety: 1) Long gone are the days of holding static stretches at the beginning of workout. Instead, do a warmup for 5-10 minutes such as walking, jogging or going through light movements. 2) Always start slowly and be aware that exercising too hard too fast can cause injury. 3) Mix up your workout if possible to work different muscle groups and avoid overuse injuries. 4) Drink plenty of water and back off your effort level if you experience pain you believe to be beyond what you should be feeling during exercise. 5) Make sure you’re wearing good shoes for the activity you`re participating in. 6) If you are learning a new exercise, focus on your form and stop when you feel fatigued. 7) Prepare for the weather and make sure you are not too hot or cold. And lastly,  if you experience persistent pain as a result from working out, pain that does not go away within a week or two, you should consider calling your healthcare provider. ';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								case "52":
								$data_base_message='Today is a REST DAY! This is a day for you to take a break from exercising. Even on rest days however, you still need to work on 1) maintaining good posture 2) contracting your natural corset (TrA) throughout the day and 3) not sitting for prolonged periods of time and 4) and keeping active. Please keep in mind that if this is not a good rest day for you, you can switch days. Just make sure that you are giving yourself two rest days a week.  If your lower back is still bothering you and you feel stiff, you can do backward extensions throughout the day. Have a good rest day!';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								case "53":
								$data_base_message='Hi there. We would like to take the time to remind you of proper lifting techniques. Please remember that when you go to pick up an object off the floor to either kneel on one knee or squat down. Do not bend at the waist. Instead, crouch down and use your legs to lift the object up. Don`t forget to contract your TrA! Keep the object as close to your body as possible. When putting the object back down, still keep the object close to your body and go back into a kneeling or squatting position without bending at the waist. We hope you enjoy the remaining exercises left in your program and keep up the good work! ';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								case "54":
								$data_base_message='Now that you are coming to the end of the program, here the 5 top low back pain prevention tips you should always try and keep in mind. 1) Good posture can help to instantly relieve lower back pressure and strain. 2) Core strengthening builds a strong core foundation to protect your lower back. 3) Movement prevents negative effects of prolonged sitting. 4)  Practice stress reduction techniques to relax your muscles and reduce low back pain and strain. 5) Healthy habits such as eating a diet full of vegetables and fruit decreases your risk of chronic inflammation, which will help relieve low back pain.';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
								case "55":
								$data_base_message='Hi there. Since you are nearly done with your program we would like to remind you one last time to please remember to keep contracting your TrA throughout day, during exercises, and while lifting any objects. If your job requires lots of sitting, one way to remember to stand up often is to set a timer. Set your timer for every 20-60 minutes. Also, do not forget those workplace stretches.';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 8',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);
								break;
								
							case "56":
								$templatePath = $application_path."mail_content/telespine/".$current_day."_day_notification.php";
								$message = build_template($templatePath,$data);
								// Mail it
								//echo $to;
								//@mail($to, $subject, $message, $headers, $returnpath); 
								$data_base_message='You did it! Today is your very last day of Telespine. Our expert team has designed this program so that on this very day your lower back is ready to take on the challenges and activities of everyday life. Phase one (Recovery) included exercises to first heal your lower back and stabilize your spine. Phase two (Corrective) worked on correcting issues that develop from things such as poor posture, lack of core strength, and muscle imbalances. Phase three (Dynamic) worked to reinforce the first 6 weeks of exercises by continuing to strengthen your muscles while working more on balance, movement, and flexibility. Going forward, what`s most important is for you to continue your exercises and stretches with consistency. If you have not already, check out the article on guidelines to help keep the program going on your own. We wish you a happy and healthy low back for life! ';
								$providerId=getClinicTherapistId($data['clinic_id']);
								$sqlinsert="INSERT INTO patient_message_center set 
													patient_id='".$user_id."',
													sender_id='".$providerId."',
													subject='Day 50',
													message='".$data_base_message."',
													creation_date=now(),
													read_date=now(),
													is_read=0";
								$resultMessage=@mysql_query($sqlinsert);	
								break;
						}
						
							
						
						
                        
						
						
						
						
	                    
						
                        

						
						

				}
			}
		}
		/**
         * This function used to get mail form or subject encoding 
         * @param string $message
         * return string $message
         */
     function setmailheader($str){
         $str="=?UTF-8?B?".base64_encode($str)."?=";
         return $str;
    }
		function getUserClinicType($user_id){
        	if( is_numeric($user_id) ){
	            $query = " select clinic_service.service_name from clinic_service JOIN clinic_user ON clinic_service.clinic_id=clinic_user.clinic_id where user_id = '{$user_id}'";
	            $result = @mysql_query($query);
	            if( $row = @mysql_fetch_array($result) ){
	            	$serviceName = $row['service_name'];
	            }
	            return $serviceName;
	        }
        	return ""; 
        }
        /**
         * This function get the channel type.
         * @param numeric $user_id
         * @return channel type
         * @access public
         */
        function getchannel($clinic_id){
           $sql="SELECT clinic_channel FROM clinic where clinic_id=".$clinic_id;
           $res=mysql_query($sql);
           $row=mysql_fetch_array($res); 
           return $row['clinic_channel'];   
        }
        
		/**
	 		* Template parsing function.
	 	*/
		function build_template($template_path, $replace="") {
			$content = file_get_contents($template_path);
			while( is_array($replace) && list($key,$value) = each($replace) ){
				$patterns = '/<!' . $key . '>/';
				$value = (string)$value;
				if (empty($value) === false) {
					$content = preg_replace($patterns, $value, $content);
				}else{
					$content = preg_replace($patterns, $value, $content);
				}
			}
			return $content;
		}

		/**
		 * This functio get clinic Id or clinic name of a user from clinic_user table.
		 *
		 * @param string $user_id:: user id of which details to be fetched.
		 * @param integer $field:: which field value to get.
		 * @return mixed
		 * @access public
		 */
        function get_clinic_info($user_id,$field = "clinic_id" ){
            if( is_numeric($user_id) && $user_id >0 ){
                $sql = "select clinic_id from clinic_user where user_id = '".$user_id."'";
                $result = @mysql_query($sql);
                while( $row = @mysql_fetch_array($result) ){
                    $clinic_id = $row["clinic_id"];
                    if(is_numeric($clinic_id) && $clinic_id > 0  &&  $field == "clinic_id" ) {
                        return $row[$field];
                    }
                    if( is_numeric($clinic_id) && $clinic_id > 0 &&  $field == "clinic_name" ){
                        $clinic_name = get_clinic_name($clinic_id,"clinic_name");                      return $clinic_name;
                    }
                }
            }
            return "";
        }
		/**
        * Get clinic name of a user from the clinic table.
		* @param integer $clinic_id
		* @return string $clinic_name
		* @access public
        */
        function get_clinic_name($clinic_id,$field = "clinic_name" ){
            if( is_numeric($clinic_id) && $clinic_id >0 ){
                $sql = "select clinic_name from clinic where clinic_id = '{$clinic_id}'";
                $result = @mysql_query($sql);
                $row = @mysql_fetch_array($result); 
                $clinic_name = $row["clinic_name"];
                return $clinic_name;
           }
                
        
            return "";
        }
       
	   
	   /**
        * Get clinic therapist Id name of a user from the clinic table.
		* @param integer $clinic_id
		* @return string $clinic_name
		* @access public
        */
	   function getClinicTherapistId($clinicId=''){
                        		
            $query = "select clinic_user.user_id from clinic_user,user where clinic_user.user_id=user.user_id and user.usertype_id=2 and user.status=1  and user.therapist_access=1 and clinic_id = '{$clinicId}'";
            $result = @mysql_query($query);
            $tempArray = array();
            if( @mysql_num_rows($result) > 0 ){
                while( $row = @mysql_fetch_array($result)){
                    $tempArray[] =$row[user_id]; 
                         
                }
                //print_r($tempArray);
               
                return $tempArray[0];
                
            }
            return "0";
            
            
        }
	   
	   
	   
        

		reminderMail($templatePath,$imagePath);






?>