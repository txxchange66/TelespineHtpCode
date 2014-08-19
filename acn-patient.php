<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Patient History Questionnaire</title>
<link href="css/outcome.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/messages.css" />
<script type="text/javascript" src="js/messages.js"></script>      
<script src="js/jquery.js"></script>
</head>
<body>
<form name="form" id="form" action="index.php?action=acn_patient" method="post" onsubmit="return validate(this);" >
<!--<form name="form" id="form" action="index.php?action=acn_patient" method="post" >-->
<table width="890" border="0" cellspacing="0" cellpadding="0" align="center" class="top-tab">
  <tr>
    <td colspan="11" style="vertical-align:bottom;"><strong>1. Describe your symptoms  (e.g. When did they start? How did they begin? Where do you feel them?) </strong></td>
  </tr>
  <tr>
    <td colspan="11"  style="vertical-align:bottom; padding-top:5px; padding-left:25px;"><textarea name="qtext1" id="qtext1" cols="" rows="" style="width:400px; height:100px;"></textarea><span id="q1" >&nbsp;</span></td>
  </tr>
  <tr>
    <td width="448" colspan="10" >&nbsp;</td>
    <td width="442"  >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="11" ><strong>2. How often do you experience your symptoms?</strong><span id="q2" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="11" class="pad-lft"  ><input name="q2[]" id="q2[]" type="radio" value="constantly"  />
      Constantly (76-100% of the day) </td>
  </tr>
  <tr>
    <td colspan="11" class="pad-lft" ><input name="q2[]" id="q2[]" type="radio" value="occasionally" />
      Occasionally (26-50% of the day) </td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft"  > <input name="q2[]" id="q2[]" type="radio" value="frequently" />
      Frequently (51-75% of the day) </td>
    <td  >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="10"  class="pad-lft" ><input name="q2[]" id="q2[]" type="radio" value="intermittently" />
     Intermittently (0-25% of the day) </td>
    <td  >&nbsp;</td>
  </tr>
  <tr>
    <td width="448" colspan="10" >&nbsp;</td>
    <td  >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="10" ><strong>3. What describes the nature of your symptoms? </strong><span id="q3" >&nbsp;</span></td>
    <td  >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft" ><Span style="padding-right:39px;"><input name="q3[]" id="q3[]" type="checkbox" value="sharp" />
      Sharp</Span><input name="q3[]" id="q3[]" type="checkbox" value="shooting" />
      Shooting  </td>
    <td  ></td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft" ><span style="padding-right:15px;"><input name="q3[]" id="q3[]" type="checkbox" value="dull_ache" />
      Dull ache</span> <input name="q3[]" id="q3[]" type="checkbox" value="burning" />
     Burning </td>
    <td  ></td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft" ><span style="padding-right:33px"><input name="q3[]" id="q3[]" type="checkbox" value="numb" />
     Numb</span> <input name="q3[]" id="q3[]" type="checkbox" value="tingling" />
      Tingling </td>
    <td  ></td>
  </tr>
  <tr>
    <td colspan="10" >&nbsp;</td>
    <td  >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="11" ><strong>4. How are your symptoms changing?</strong><span id="q4" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft" ><input name="q4[]" id="q4[]" type="radio" value="getting_better" />
      Getting Better </td>
    <td  > </td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft" >
      <input name="q4[]" type="radio" value="not_changing" id="q4[]" />
      Not Changing </td>
    <td  >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft"><span >
      <input name="q4[]" id="q4[]" type="radio" value="getting_worse" />
      Getting Worse</span></td>
    <td  >&nbsp;</td>
  </tr>
  <tr>
    <td width="448" colspan="10" >&nbsp;</td>
    <td  >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="10" ><strong>5. During the past 4 weeks: </strong> </td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft" >a. Indicate the average intensity of your symptoms<span id="q5a" >&nbsp;</span></td>
    <td  >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="11" class="pad-lft" ><Span style=" float:right;padding-right:370px;">Unbearable</Span>None</td>
  </tr>
  
  <tr>
    <td class="pad-lft" ><input name="q5a[]" id="q5a[]" type="radio" value="none" /></td>
    <td class="pad-lft" ><input name="q5a[]" id="q5a[]" type="radio" value="1" /></td>
    <td class="pad-lft" ><input name="q5a[]" id="q5a[]" type="radio" value="2" /></td>
    <td class="pad-lft" ><input name="q5a[]" id="q5a[]" type="radio" value="3" /></td>
    <td class="pad-lft" ><input name="q5a[]" id="q5a[]" type="radio" value="4" /></td>
    <td class="pad-lft" ><input name="q5a[]" id="q5a[]" type="radio" value="5" /></td>
    <td class="pad-lft" ><input name="q5a[]" id="q5a[]" type="radio" value="6" /></td>
    <td class="pad-lft" ><input name="q5a[]" id="q5a[]" type="radio" value="7" /></td>
    <td class="pad-lft" ><input name="q5a[]" id="q5a[]" type="radio" value="8" /></td>
    <td class="pad-lft" ><input name="q5a[]" id="q5a[]" type="radio" value="9" /></td>
    <td class="pad-lft" ><input name="q5a[]" id="q5a[]" type="radio" value="unbearable" /></td>
  </tr>
  <tr>
    <td colspan="11" class="pad-lft" >b. How much has pain interfered with your normal work (including both work outside the home, and housework)<span id="q5b" >&nbsp;</span> </td>
  </tr>
  <tr>
    <td colspan="11" class="pad-lft-new" style="padding-top:5px;"><Span style="margin-right:15px;" >
      <input name="q5b[]" id="q5b[]" type="radio" value="not_at_all" />
      Not at all</Span> <Span style="margin-right:15px;">
      <input name="q5b[]" id="q5b[]" type="radio" value="a_little_bit" />
      A little bit </Span> <Span style="margin-right:15px;">
      <input name="q5b[]" id="q5b[]" type="radio" value="moderately" />
      Moderately</Span> <Span style="margin-right:15px;">
      <input name="q5b[]" id="q5b[]" type="radio" value="quite_a_bit" />
      Quite a bit </Span> <Span>
      <input name="q5b[]" id="q5b[]" type="radio" value="extremely" />
      Extremely</Span></td>
  </tr>
  <tr>
    <td colspan="10" >&nbsp;</td>
    <td  >&nbsp;</td>
  </tr>
  
  <tr>
    <td colspan="11" ><strong>6. During the past 4 weeks how much of the time has your condition interfered with your social activities? </strong>( e.g. Visiting with friends, family, etc)<span id="q6" >&nbsp;</span> </td>
  </tr>
  <tr>
    <td colspan="11" height="25px;" class="pad-lft-new" ><Span style="margin-right:15px;" >
      <input name="q6[]" id="q6[]" type="radio" value="all_of_the_time" />
      All of the time </Span> <Span style="margin-right:15px;">
      <input name="q6[]" id="q6[]" type="radio" value="most_of_the_time" />
      Most of the time </Span> <Span style="margin-right:15px;">
      <input name="q6[]" id="q6[]" type="radio" value="some_of_the_time" />
Some of the time </Span> <Span style="margin-right:15px;">
      <input name="q6[]" id="q6[]" type="radio" value="a_little_of_the_time" />
      A little of the time </Span> <Span>
      <input name="q6[]" id="q6[]" type="radio" value="none_of_the_time" />
      None of the time </Span></td>
  </tr>
  <tr>
    <td width="448" colspan="10" >&nbsp;</td>
    <td  >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="11" ><strong>7. In general would you say your overall health right now is...</strong><span id="q7" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="11" height="25px;" class="pad-lft-new" ><Span style="margin-right:15px;" >
      <input name="q7[]" id="q7[]" type="radio" value="excellent" />
      Excellent </Span> <Span style="margin-right:15px;">
      <input name="q7[]" id="q7[]" type="radio" value="very_good" />
      Very Good </Span> <Span style="margin-right:15px;">
      <input name="q7[]" id="q7[]" type="radio" value="good" />
     Good </Span> <Span style="margin-right:15px;">
      <input name="q7[]" id="q7[]" type="radio" value="fair" />
     Fair </Span> <Span>
      <input name="q7[]" id="q7[]" type="radio" value="poor" />
     Poor </Span></td>
  </tr>
  <tr>
    <td colspan="11"  >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="10" ><strong>8. Who have you seen for your symptoms? </strong><span id="q8" >&nbsp;</span></td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="10" style="padding-left:20px;" ><span style="margin-right:20px;">
      <input name="q8[]" id="q8[]" type="checkbox" value="no_one" />
       No One</span> <span style="margin-right:23px;">
      <input name="q8[]" id="q8[]" type="checkbox" value="medical_doctor" />
       Medical Doctor </span> <span style="padding-right:15px;">
      <input name="q8[]" id="q8[]" type="checkbox" value="other" />
      Other</span><span style="padding-right:13px;">
      <input name="q8[]" id="q8[]" type="checkbox" value="chiropractor" />
      Chiropractor </span></td>
    <td > <span>
      <input name="q8[]" id="q8[]" type="checkbox" value="physical_therapist" />
      Physical Therapist </span></td>
  </tr>
  <tr>
    <td colspan="11" style="padding-left:20px; padding-top:5px; " >a. What treatment did you receive and when? (write "none" if no treatment prescribed)<span id="q8a" >&nbsp;</span></td>
  </tr>
  <td colspan="11"  style="padding-top:5px; padding-bottom:5px; padding-left:25px;" ><input name="q8a[]" id="q8a[]" type="text" style="width:58%;"   /></td>
  
  <tr>
    <td colspan="11" class="pad-lft">b. What tests have you had for your symptoms and when were they performed<span id="q8b" >&nbsp;</span> </td>
  </tr>
  <tr>
    <td colspan="10" style="padding-left:20px; padding-top:5px; " ><span style="padding-right:20px;">
      <input name="q8b[]" id="q8b[]" type="checkbox" value="xrays_date" />
      Xrays date:&nbsp;&nbsp;
      <input name="q8bt1[]" id="q8bt1[]" type="text" style="width:75px;" value="" />
      </span> <span >
      <input name="q8b[]" id="q8b[]" type="checkbox" value="ct_scan_date" />
      CT Scan date:&nbsp;
      <input name="q8bt2[]" id="q8bt2[]" type="text" style="width:75px;"  />
      </span> </td>
    <td style="padding-top:5px;"  > <input  name="q8b[]" id="q8b[]" type="checkbox" value="none" /> None    </td>
  </tr>
  <tr>
    <td colspan="10" style="padding-left:20px; padding-top:5px; " ><span style="padding-right:20px;"><input name="q8b[]" id="q8b[]" type="checkbox" value="mri_date" />
      MRI date: &nbsp;&nbsp;&nbsp;
      <input name="q8bt3[]" id="q8bt3[]" type="text" style="width:75px;"  /></span><span >
      <input name="q8b[]" id="q8b[]" type="checkbox" value="other_date" />
      Other date:<Span style="padding-left:21px;">
      <input name="q8bt4[]" id="q8bt4[]" type="text" style="width:75px;"  /></Span>
      </span>      </td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td width="448" colspan="10" >&nbsp;</td>
    <td  >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="10" ><strong>9. Have you had similar symptoms in the past?</strong><span id="q9" >&nbsp;</span></td>
    <td ></td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft" ><span style="margin-right:15px;">
      <input name="q9[]" id="q9[]" type="radio" value="yes" />
      &nbsp;Yes</span> <span style="margin-right:25px;">
      <input name="q9[]" id="q9[]" type="radio" value="no" />
      &nbsp;No</span></td>
    <td valign="bottom" >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="11" class="pad-lft" style="padding-bottom:3px;" >a. If you have received treatment in the past for the 
      same or similar symptoms, who did you see? <span id="q9a" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10"  style="padding-left:20px;"><span style="margin-right:15px;">
      <input name="q9a[]" id="q9a[]" type="checkbox" value="this_office" />
      This Office </span><span style="margin-right:15px;">
      <input name="q9a[]" id="q9a[]" type="checkbox" value="medical_doctor" />
     Medical Doctor </span><span style="margin-right:10px;">
      <input name="q9a[]" id="q9a[]" type="checkbox" value="others" />
     Others</span>
      <input name="q9a[]" id="q9a[]" type="checkbox" value="chiropractor" />
       Chiropractor </td>
    <td ><span>
      <input name="q9a[]" id="q9a[]" type="checkbox" value="physical_therapist" />
       Physical Therapist </span></td>
  </tr>
  <tr>
    <td colspan="10" >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="10" ><strong>10. What is your occupation?</strong><span id="q10" >&nbsp;</span> </td>
    <td style="padding-left:3px; padding-bottom:3px;" >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="11" style="padding-left:25px; padding-top:5px; padding-bottom:5px;" ><input name="q10[]" id="q10[]" type="text" style="width:58%;"  /> </td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft" style="padding-bottom:3px;" >a. what is your current work status?<span id="q10a" >&nbsp;</span> </td>
    <td ></td>
  </tr>
  <tr>
    <td colspan="10" style="padding-left:20px;" ><span style="margin-right:20px;">
      <input name="q10a[]" id="q10a[]" type="radio" value="full-time" />
      Full-time </span><span style="margin-right:20px;">
      <input name="q10a[]" id="q10a[]" type="radio" value="self-employed" />
      Self-employed </span><span style="margin-right:20px;">
      <input name="q10a[]" id="q10a[]" type="radio" value="off_work" />
      Off work </span><span >
      <input name="q10a[]" id="q10a[]" type="radio" value="part-time" />
     Part-time </span></td>
    <td ><span style="margin-right:41px;">
      <input name="q10a[]" id="q10a[]" type="radio" value="unemployed" />
      Unemployed </span><span style="margin-right:58px;">
      <input name="q10a[]" id="q10a[]" type="radio" value="others" />
      Others</span></td>
  </tr>
  <tr>
    <td colspan="11"  >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="11"  ><strong>11. Have you or any immediate family member ever been 
      told you have:<Span style="padding-left:50px; padding-right:135px;">Self</Span> Family</strong> </td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft" > Cancer ?</td>
    <td  ><input name="q11sa[]" id="q11sa[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q11sa[]" id="q11sa[]" type="radio" value="no" />
      No <span style="padding-left:55px;">
      <input name="q11fa[]" id="q11fa[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q11fa[]" id="q11fa[]" type="radio" value="no" />
      No</span><span id="q11sa" >&nbsp;</span><span id="q11fa" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft" >Diabetes ?</td>
    <td  ><input name="q11sb[]" id="q11sb[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q11sb[]" id="q11sb[]" type="radio" value="no" />
      No <span style="padding-left:55px;">
      <input name="q11fb[]" id="q11fb[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q11fb[]" id="q11fb[]" type="radio" value="no" />
      No</span><span id="q11sb" >&nbsp;</span><span id="q11fb" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft"  > High blood pressure ?</td>
    <td  ><input name="q11sc[]" id="q11sc[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q11sc[]" id="q11sc[]" type="radio" value="no" />
      No <span style="padding-left:55px;">
      <input name="q11fc[]" id="q11fc[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q11fc[]" id="q11fc[]" type="radio" value="no" />
      No</span><span id="q11sc" >&nbsp;</span><span id="q11fc" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft"  >Heart disease ?</td>
    <td  ><input name="q11sd[]" id="q11sd[]" type="radio" value="yes" />      
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q11sd[]" id="q11sd[]" type="radio" value="no" />
      No <span style="padding-left:55px;">
      <input name="q11fd[]" id="q11fd[]" type="radio" value="yes"  />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q11fd[]" id="q11fd[]" type="radio" value="no" />
      No</span><span id="q11sd" >&nbsp;</span><span id="q11fd" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10"  class="pad-lft" > Angina/chest pain ?</td>
    <td  ><input name="q11se[]" id="q11se[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q11se[]" id="q11se[]" type="radio" value="no" />
      No <span style="padding-left:55px;">
      <input name="q11fe[]" id="q11fe[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q11fe[]" id="q11fe[]" type="radio" value="no" />
      No</span><span id="q11se" >&nbsp;</span><span id="q11fe" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10"  class="pad-lft" >Stroke ?</td>
    <td  ><input name="q11sf[]" id="q11sf[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q11sf[]" id="q11sf[]" type="radio" value="no" />
      No <span style="padding-left:55px;">
      <input name="q11ff[]" id="q11ff[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q11ff[]" id="q11ff[]" type="radio" value="no" />
      No</span><span id="q11sf" >&nbsp;</span><span id="q11ff" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10"  class="pad-lft" >Osteoporosis ?</td>
    <td  ><input name="q11sg[]" id="q11sg[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q11sg[]" id="q11sg[]" type="radio" value="no" />
      No <span style="padding-left:55px;">
      <input name="q11fg[]" id="q11fg[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q11fg[]" id="q11fg[]" type="radio" value="no" />
      No</span><span id="q11sg" >&nbsp;</span><span id="q11fg" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft"  >Osteoarthritis ?</td>
    <td ><input name="q11sh[]" id="q11sh[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q11sh[]" id="q11sh[]" type="radio" value="no" />
      No <span style="padding-left:55px;">
      <input name="q11fh[]" id="q11fh[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q11fh[]" id="q11fh[]" type="radio" value="no" />
      No</span><span id="q11sh" >&nbsp;</span><span id="q11fh" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10"  class="pad-lft" >Rheumatoid arthritis ?</td>
    <td  ><input name="q11si[]" id="q11si[]"  type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q11si[]" id="q11si[]" type="radio" value="no" />
      No <span style="padding-left:55px;">
      <input name="q11fi[]" id="q11fi[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q11fi[]" id="q11fi[]" type="radio" value="no" />
      No</span><span id="q11si" >&nbsp;</span><span id="q11fi" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10" >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="11" ><strong>12. In the past 3 months have you had or do you 
      experience:</strong> </td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft" >A change in your health ?</td>
    <td  ><input name="q12a[]" id="q12a[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q12a[]" id="q12a[]" type="radio" value="no" />
      No <span id="q12a" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft">Nausea/Vomiting ?</td>
    <td ><input name="q12b[]" id="q12b[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q12b[]" id="q12b[]" type="radio" value="no" />
      No <span id="q12b" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft"> Fever/chills/sweats ?</td>
    <td ><input name="q12c[]" id="q12c[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q12c[]" id="q12c[]" type="radio" value="no" />
      No <span id="q12c" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10"  class="pad-lft">Unexplained weight change ?</td>
    <td ><input name="q12d[]" id="q12d[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q12d[]" id="q12d[]" type="radio" value="no" />
      No <span id="q12d" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft">Numbness or tingling ?</td>
    <td  ><input name="q12e[]" id="q12e[]" type="radio" value="yes" />    
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q12e[]" id="q12e[]" type="radio" value="no" />
    No <span id="q12e" >&nbsp;</span></td></tr>
  <tr>
    <td colspan="10" class="pad-lft">Changes in appetite ?</td>
    <td  ><input name="q12f[]" id="q12f[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q12f[]" id="q12f[]" type="radio" value="no" />
      No <span id="q12f" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft"> Difficulty swallowing ?</td>
    <td  ><input name="q12g[]" id="q12g[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q12g[]" id="q12g[]" type="radio" value="no" />
      No <span id="q12g" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft">Changes in bowel or bladder function ?</td>
    <td  ><input name="q12h[]" id="q12h[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q12h[]" id="q12h[]" type="radio" value="no" />
      No <span id="q12h" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft"> Shortness of breath ?</td>
    <td ><input name="q12i[]" id="q12i[]" type="radio" value="yes" />    
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q12i[]" id="q12i[]" type="radio" value="no" />
    No <span id="q12i" >&nbsp;</span></td></tr>
  <tr>
    <td colspan="10" class="pad-lft">Dizziness ?</td>
    <td  ><input name="q12j[]" id="q12j[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q12j[]" id="q12j[]" type="radio" value="no" />
      No <span id="q12j" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft">Upper respiratory infection ?</td>
    <td  ><input name="q12k[]" id="q12k[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q12k[]" id="q12k[]" type="radio" value="no" />
      No <span id="q12k" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft">Urinary tract infection ?</td>
    <td  ><input name="q12l[]" id="q12l[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q12l[]" id="q12l[]" type="radio" value="no" />
      No <span id="q12l" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10" >&nbsp; </td>
    <td style="padding-left:78px;" ></td>
  </tr>
  <tr>
    <td colspan="10" ><strong>13. Do you have a history of:</strong> </td>
    <td style="padding-left:78px;" ></td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft">Allergies/Asthma ?</td>
    <td ><input name="q13a[]" id="q13a[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q13a[]" id="q13a[]" type="radio" value="no" />
      No <span id="q13a" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft">Headaches ?</td>
    <td  ><input name="q13b[]" id="q13a[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q13b[]" id="q13a[]" type="radio" value="no" />
      No <span id="q13b" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft">Bronchitis ?</td>
    <td ><input name="q13c[]" id="q13c[]" type="radio" value="yes" />    
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q13c[]" id="q13c[]" type="radio" value="no" />
    No <span id="q13c" >&nbsp;</span></td></tr>
  <tr>
    <td colspan="10" class="pad-lft">Kidney disease ?</td>
    <td ><input name="q13d[]" id="q13d[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q13d[]" id="q13d[]" type="radio" value="no" />
      No <span id="q13d" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft">Rheumatic fever ?</td>
    <td  ><input name="q13e[]" id="q13e[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q13e[]" id="q13e[]" type="radio" value="no" />
      No <span id="q13e" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft">Ulcers ?</td>
    <td  ><input name="q13f[]" id="q13f[]"  type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q13f[]" id="q13f[]" type="radio" value="no" />
      No <span id="q13f" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft">Seizures ?</td>
    <td  ><input name="q13g[]" id="q13g[]" type="radio" value="yes" />      
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q13g[]" id="q13g[]" type="radio" value="no" />
      No <span id="q13g" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10" >&nbsp; </td>
    <td style="padding-left:78px;" ></td>
  </tr>
  <tr>
    <td colspan="10" ><strong>14. Are you currently:</strong> </td>
    <td style="padding-left:78px;" ></td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft">Pregnant ?</td>
    <td ><input name="q14a[]" id="q14a[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q14a[]" id="q14a[]" type="radio" value="no" />
      No <span id="q14a" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft">Depressed ?</td>
    <td  ><input name="q14b[]" id="q14b[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q14b[]" id="q14b[]" type="radio" value="no" />
      No <span id="q14b" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10" class="pad-lft">Under Stress ?</td>
    <td  ><input name="q14c[]" id="q14c[]" type="radio" value="yes" />
      Yes &nbsp;&nbsp;&nbsp;&nbsp;
      <input name="q14c[]" id="q14c[]" type="radio" value="no" />
      No <span id="q14c" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10" >&nbsp;</td>
    <td ></td>
  </tr>
  <tr>
    <td colspan="10" ><strong>15. How are you able to sleep at night? (check one) </strong><span id="q15" >&nbsp;</span></td>
    <td style="padding-left:78px;" ></td>
  </tr>
  <tr>
    <td colspan="11" class="pad-lft-new" ><span style="padding-right:15px;">
      <input name="q15[]" id="q15[]" type="radio" value="fine" />
      Fine</span><span style="padding-right:15px;">
      <input name="q15[]" id="q15[]" type="radio" value="moderate_difficulty" />
      Moderate difficulty</span>
      <input name="q15[]" id="q15[]" type="radio" value="only_with_medication" />
      Only with 
      medication </td>
  </tr>
  <tr>
    <td colspan="10" >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="11" ><strong>16. Date of last physical examination </strong><span id="q16" >&nbsp;</span></td>
  </tr>
  <tr>
    <td colspan="10" style="padding-top:5px; padding-bottom:5px; padding-left:25px;" ><input name="q16[]" id="q16[]" type="text" style="width:75px;"  /></td>
    <td  >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="10" ><strong>17. List medications currently using: </strong><span id="q17" >&nbsp;</span></td>
    <td  >    </td>
  </tr>
  <tr>
    <td colspan="11" style="padding-top:5px; padding-bottom:5px; padding-left:25px;"  ><input name="q17[]" id="q17[]" type="text" style="width:58%;"  /></td>
  </tr>
  <tr>
    <td colspan="10" ><strong>18. Your Goals?</strong><span id="q18" >&nbsp;</span></td>
    <td  ></td>
  </tr>
  <tr>
    <td colspan="11"  style="padding-top:5px; padding-bottom:5px; padding-left:25px;" ><input name="q18[]" id="q18[]" type="text" style="width:58%;"  /></td>
  </tr>
  <tr>
    <td colspan="10" ><strong>19. Your Activities/Sports/Hobbies?</strong><span id="q19" >&nbsp;</span></td>
    <td  ></td>
  </tr>
  <tr>
    <td colspan="11" style="padding-top:5px; padding-bottom:5px; padding-left:25px;"  ><input name="q19[]" id="q19[]" type="text" style="width:58%;"  /></td>
  </tr>
  <tr>
    <td colspan="10" align="right" style="padding-top:5px; padding-bottom:10px;" ><input name="submit" type="submit" value="Submit" /></td>
    <td  ></td>
  </tr>
</table>
<input type="hidden" name="patient_id" value="20" />
</form>
</body>
<script language="javascript" type="text/javascript" >
      $(document).ready(function() {
        <!javascript>
      });
      
</script>
</html>
