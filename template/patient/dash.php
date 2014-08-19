<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>DASH</title>
    <link href="css/outcome.css" rel="stylesheet" type="text/css">
<script>
function validateDash(Frm)
{ 	var j=0;
	for (var i=0; i < Frm.elements.length; i++)
	{
	   if (Frm.elements[i].checked)
		  {	j++;
		  }
	}
	if(j < 27)	
	{	document.getElementById("atleast").style.display='inline';
		document.location.href="#showfocus";		
		return false;
	}
	else
	{	return true;
	}
}
</script>
</head>
<body>
<form action="/index.php?action=show_outcome_form" method="post" onSubmit="return validateDash(this);">
<table class="second-tab" width="830" align="center" border="0" cellpadding="0" cellspacing="0">
<tbody>
 <tr>
<td colspan="2"><a name="showfocus"></a><div style="width:830px; margin:0 auto;  font-weight: bold; font-family: 'Times New Roman',Times,serif; font-size: 23px; padding-bottom: 15px;">Disability of the Arm, Shoulder and Hand  Questionnaire</div></td>
</tr>
<tr>
<td colspan="2" style="padding-top: 10px; padding-bottom: 15px;">Please rate your ability to do the following activities in the last week. Please answer every question by placing a mark on the line that best describes your condition today .We realize that you may feel that two of the statements may describe your condition, but <strong><u>please mark only the line which most closely describes your current condition.</u></strong></td>
</tr>
<tr>
    <td colspan="2" class="" align="center"><span id="atleast" style="color:#ff0000;display:none;">At least 27 of the 30 questions must be answered for scoring.</td>
  </tr>
  <tr>
    <td colspan="2" class="space-heading2">OPEN A TIGHT OR NEW JAR</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="jar[]" value="1" type="radio"  ></td>
    <td >No difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="jar[]" value="2" type="radio"></td>
    <td >Mild difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="jar[]" value="3" type="radio"></td>
    <td >Moderate difficulty. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="jar[]" value="4" type="radio"></td>
    <td >Severe difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="jar[]" value="5" type="radio"></td>
    <td >Unable.</td>
  </tr>
  
  
  <tr>
    <td colspan="2" class="space-heading2">WRITE</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="write[]" value="1" type="radio"  ></td>
    <td >No difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="write[]" value="2" type="radio"></td>
    <td >Mild difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="write[]" value="3" type="radio"></td>
    <td >Moderate difficulty. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="write[]" value="4" type="radio"></td>
    <td >Severe difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="write[]" value="5" type="radio"></td>
    <td >Unable.</td>
  </tr>
  
  <tr>
    <td colspan="2" class="space-heading2">TURN A KEY</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="key[]" value="1" type="radio"  ></td>
    <td >No difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="key[]" value="2" type="radio"></td>
    <td >Mild difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="key[]" value="3" type="radio"></td>
    <td >Moderate difficulty. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="key[]" value="4" type="radio"></td>
    <td >Severe difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="key[]" value="5" type="radio"></td>
    <td >Unable.</td>
  </tr>

  <tr>
    <td colspan="2" class="space-heading2">PREPARE A MEAL</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="meal[]" value="1" type="radio"  ></td>
    <td >No difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="meal[]" value="2" type="radio"></td>
    <td >Mild difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="meal[]" value="3" type="radio"></td>
    <td >Moderate difficulty. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="meal[]" value="4" type="radio"></td>
    <td >Severe difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="meal[]" value="5" type="radio"></td>
    <td >Unable.</td>
  </tr>

  <tr>
    <td colspan="2" class="space-heading2">PUSH OPEN A HEAVY DOOR</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="door[]" value="1" type="radio"  ></td>
    <td >No difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="door[]" value="2" type="radio"></td>
    <td >Mild difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="door[]" value="3" type="radio"></td>
    <td >Moderate difficulty. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="door[]" value="4" type="radio"></td>
    <td >Severe difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="door[]" value="5" type="radio"></td>
    <td >Unable.</td>
  </tr>

  <tr>
    <td colspan="2" class="space-heading2">PLACE AN OBJECT ON A SHELF ABOVE YOUR HEAD</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="object_shelf[]" value="1" type="radio"  ></td>
    <td >No difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="object_shelf[]" value="2" type="radio"></td>
    <td >Mild difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="object_shelf[]" value="3" type="radio"></td>
    <td >Moderate difficulty. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="object_shelf[]" value="4" type="radio"></td>
    <td >Severe difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="object_shelf[]" value="5" type="radio"></td>
    <td >Unable.</td>
  </tr>

  <tr>
    <td colspan="2" class="space-heading2">DO HEAVY HOUSEHOLD CHORES (E.G. WASH WALLS, WASH FLOORS)</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="household[]" value="1" type="radio"  ></td>
    <td >No difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="household[]" value="2" type="radio"></td>
    <td >Mild difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="household[]" value="3" type="radio"></td>
    <td >Moderate difficulty. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="household[]" value="4" type="radio"></td>
    <td >Severe difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="household[]" value="5" type="radio"></td>
    <td >Unable.</td>
  </tr>

  <tr>
    <td colspan="2" class="space-heading2">GARDEN OR DO YARD WORK</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="garden[]" value="1" type="radio"  ></td>
    <td >No difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="garden[]" value="2" type="radio"></td>
    <td >Mild difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="garden[]" value="3" type="radio"></td>
    <td >Moderate difficulty. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="garden[]" value="4" type="radio"></td>
    <td >Severe difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="garden[]" value="5" type="radio"></td>
    <td >Unable.</td>
  </tr>

  <tr>
    <td colspan="2" class="space-heading2">MAKE A BED</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="bed[]" value="1" type="radio"  ></td>
    <td >No difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="bed[]" value="2" type="radio"></td>
    <td >Mild difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="bed[]" value="3" type="radio"></td>
    <td >Moderate difficulty. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="bed[]" value="4" type="radio"></td>
    <td >Severe difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="bed[]" value="5" type="radio"></td>
    <td >Unable.</td>
  </tr>

  <tr>
    <td colspan="2" class="space-heading2">CARRY A SHOPPING BAG OR BRIEFCASE</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="shopping[]" value="1" type="radio"  ></td>
    <td >No difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="shopping[]" value="2" type="radio"></td>
    <td >Mild difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="shopping[]" value="3" type="radio"></td>
    <td >Moderate difficulty. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="shopping[]" value="4" type="radio"></td>
    <td >Severe difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="shopping[]" value="5" type="radio"></td>
    <td >Unable.</td>
  </tr>

  <tr>
    <td colspan="2" class="space-heading2">CARRY A HEAVY OBJECT (OVER 10 LBS)</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="heavy[]" value="1" type="radio"  ></td>
    <td >No difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="heavy[]" value="2" type="radio"></td>
    <td >Mild difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="heavy[]" value="3" type="radio"></td>
    <td >Moderate difficulty. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="heavy[]" value="4" type="radio"></td>
    <td >Severe difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="heavy[]" value="5" type="radio"></td>
    <td >Unable.</td>
  </tr>

  <tr>
    <td colspan="2" class="space-heading2">CHANGE A LIGHT BULB OVERHEAD</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="bulb[]" value="1" type="radio"  ></td>
    <td >No difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="bulb[]" value="2" type="radio"></td>
    <td >Mild difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="bulb[]" value="3" type="radio"></td>
    <td >Moderate difficulty. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="bulb[]" value="4" type="radio"></td>
    <td >Severe difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="bulb[]" value="5" type="radio"></td>
    <td >Unable.</td>
  </tr>

  <tr>
    <td colspan="2" class="space-heading2">WASH OR BLOW DRY YOUR HAIR</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="hair[]" value="1" type="radio"  ></td>
    <td >No difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="hair[]" value="2" type="radio"></td>
    <td >Mild difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="hair[]" value="3" type="radio"></td>
    <td >Moderate difficulty. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="hair[]" value="4" type="radio"></td>
    <td >Severe difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="hair[]" value="5" type="radio"></td>
    <td >Unable.</td>
  </tr>

  <tr>
    <td colspan="2" class="space-heading2">WASH YOUR BACK</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="back[]" value="1" type="radio"  ></td>
    <td >No difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="back[]" value="2" type="radio"></td>
    <td >Mild difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="back[]" value="3" type="radio"></td>
    <td >Moderate difficulty. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="back[]" value="4" type="radio"></td>
    <td >Severe difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="back[]" value="5" type="radio"></td>
    <td >Unable.</td>
  </tr>

  <tr>
    <td colspan="2" class="space-heading2">PUT ON A PULLOVER SWEATER</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="pullover[]" value="1" type="radio"  ></td>
    <td >No difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="pullover[]" value="2" type="radio"></td>
    <td >Mild difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="pullover[]" value="3" type="radio"></td>
    <td >Moderate difficulty. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="pullover[]" value="4" type="radio"></td>
    <td >Severe difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="pullover[]" value="5" type="radio"></td>
    <td >Unable.</td>
  </tr>

  <tr>
    <td colspan="2" class="space-heading2">USE A KNIFE TO CUT FOOD</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="knife[]" value="1" type="radio"  ></td>
    <td >No difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="knife[]" value="2" type="radio"></td>
    <td >Mild difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="knife[]" value="3" type="radio"></td>
    <td >Moderate difficulty. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="knife[]" value="4" type="radio"></td>
    <td >Severe difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="knife[]" value="5" type="radio"></td>
    <td >Unable.</td>
  </tr>

  <tr>
    <td colspan="2" class="space-heading2">RECREATIONAL ACTIVITIES WHICH REQUIRE LITTLE EFFORT (E.G., CARDPLAYING, KNITTING, ETC.)</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="recreational[]" value="1" type="radio"  ></td>
    <td >No difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="recreational[]" value="2" type="radio"></td>
    <td >Mild difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="recreational[]" value="3" type="radio"></td>
    <td >Moderate difficulty. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="recreational[]" value="4" type="radio"></td>
    <td >Severe difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="recreational[]" value="5" type="radio"></td>
    <td >Unable.</td>
  </tr>

  <tr>
    <td colspan="2" class="space-heading2">RECREATIONAL ACTIVITIES IN WHICH YOU TAKE SOME FORCE OR IMPACT THROUGH YOUR ARM, SHOULDER, OR HAND (E.G., GOLF, HAMMERING, TENNIS, ETC.)</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="recreational_force[]" value="1" type="radio"  ></td>
    <td >No difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="recreational_force[]" value="2" type="radio"></td>
    <td >Mild difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="recreational_force[]" value="3" type="radio"></td>
    <td >Moderate difficulty. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="recreational_force[]" value="4" type="radio"></td>
    <td >Severe difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="recreational_force[]" value="5" type="radio"></td>
    <td >Unable.</td>
  </tr>

  <tr>
    <td colspan="2" class="space-heading2">RECREATIONAL ACTIVITIES IN WHICH YOU MOVE YOUR ARM FREELY (E.G.,  PLAYING FRISBEE, BADMINTON, ETC.)</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="recreational_free[]" value="1" type="radio"  ></td>
    <td >No difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="recreational_free[]" value="2" type="radio"></td>
    <td >Mild difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="recreational_free[]" value="3" type="radio"></td>
    <td >Moderate difficulty. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="recreational_free[]" value="4" type="radio"></td>
    <td >Severe difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="recreational_free[]" value="5" type="radio"></td>
    <td >Unable.</td>
  </tr>

  <tr>
    <td colspan="2" class="space-heading2">MANAGE TRANSPORTATION NEEDS (GETTING FROM ONE PLACE TO ANOTHER)</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="transportation[]" value="1" type="radio"  ></td>
    <td >No difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="transportation[]" value="2" type="radio"></td>
    <td >Mild difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="transportation[]" value="3" type="radio"></td>
    <td >Moderate difficulty. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="transportation[]" value="4" type="radio"></td>
    <td >Severe difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="transportation[]" value="5" type="radio"></td>
    <td >Unable.</td>
  </tr>

  <tr>
    <td colspan="2" class="space-heading2">SEXUAL ACTIVITIES</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="sexual[]" value="1" type="radio"  ></td>
    <td >No difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="sexual[]" value="2" type="radio"></td>
    <td >Mild difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="sexual[]" value="3" type="radio"></td>
    <td >Moderate difficulty. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="sexual[]" value="4" type="radio"></td>
    <td >Severe difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="sexual[]" value="5" type="radio"></td>
    <td >Unable.</td>
  </tr>

  <tr>
    <td colspan="2" class="space-heading2">DURING THE PAST WEEK, <i>TO WHAT EXTENT</i> HAS YOUR ARM, SHOULDER OR HAND PROBLEM INTERFERED WITH YOUR NORMAL SOCIAL ACTIVITIES WITH FAMILY, FRIENDS, NEIGHBOURS OR GROUPS</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="week_extent[]" value="1" type="radio"  ></td>
    <td >Not at all.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="week_extent[]" value="2" type="radio"></td>
    <td >Slightly.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="week_extent[]" value="3" type="radio"></td>
    <td >Moderately. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="week_extent[]" value="4" type="radio"></td>
    <td >Quite a bit.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="week_extent[]" value="5" type="radio"></td>
    <td >Extremely.</td>
  </tr>

  <tr>
    <td colspan="2" class="space-heading2">DURING THE PAST WEEK, WERE YOU LIMITED IN YOUR WORK OR OTHER REGULAR DAILY ACTIVITIES AS A RESULT OF YOUR ARM, SHOULDER OR HAND PROBLEM</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="week_limited[]" value="1" type="radio"  ></td>
    <td >Not limited at all.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="week_limited[]" value="2" type="radio"></td>
    <td >Slightly limited.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="week_limited[]" value="3" type="radio"></td>
    <td >Moderately limited. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="week_limited[]" value="4" type="radio"></td>
    <td >Very limited.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="week_limited[]" value="5" type="radio"></td>
    <td >Unable.</td>
  </tr>

  <tr>
    <td colspan="2" class="space-heading2">ARM, SHOULDER OR HAND PAIN</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="arm_pain[]" value="1" type="radio"  ></td>
    <td >None.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="arm_pain[]" value="2" type="radio"></td>
    <td >Mild.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="arm_pain[]" value="3" type="radio"></td>
    <td >Moderate. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="arm_pain[]" value="4" type="radio"></td>
    <td >Severe.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="arm_pain[]" value="5" type="radio"></td>
    <td >Extreme.</td>
  </tr>

  <tr>
    <td colspan="2" class="space-heading2">ARM, SHOULDER OR HAND PAIN WHEN YOU PERFORMED ANY SPECIFIC ACTIVITY</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="arm_pain_specific[]" value="1" type="radio"  ></td>
    <td >None.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="arm_pain_specific[]" value="2" type="radio"></td>
    <td >Mild.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="arm_pain_specific[]" value="3" type="radio"></td>
    <td >Moderate. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="arm_pain_specific[]" value="4" type="radio"></td>
    <td >Severe.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="arm_pain_specific[]" value="5" type="radio"></td>
    <td >Extreme.</td>
  </tr>

  <tr>
    <td colspan="2" class="space-heading2">TINGLING (PINS AND NEEDLES) IN YOUR ARM, SHOULDER OR HAND</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="tingling[]" value="1" type="radio"  ></td>
    <td >None.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="tingling[]" value="2" type="radio"></td>
    <td >Mild.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="tingling[]" value="3" type="radio"></td>
    <td >Moderate. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="tingling[]" value="4" type="radio"></td>
    <td >Severe.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="tingling[]" value="5" type="radio"></td>
    <td >Extreme.</td>
  </tr>

  <tr>
    <td colspan="2" class="space-heading2">WEAKNESS IN YOUR ARM, SHOULDER OR HAND</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="weakness[]" value="1" type="radio"  ></td>
    <td >None.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="weakness[]" value="2" type="radio"></td>
    <td >Mild.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="weakness[]" value="3" type="radio"></td>
    <td >Moderate. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="weakness[]" value="4" type="radio"></td>
    <td >Severe.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="weakness[]" value="5" type="radio"></td>
    <td >Extreme.</td>
  </tr>
	
  <tr>
    <td colspan="2" class="space-heading2">STIFFNES IN YOUR ARM, SHOULDER OR HAND</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="stiffness[]" value="1" type="radio"  ></td>
    <td >None.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="stiffness[]" value="2" type="radio"></td>
    <td >Mild.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="stiffness[]" value="3" type="radio"></td>
    <td >Moderate. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="stiffness[]" value="4" type="radio"></td>
    <td >Severe.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="stiffness[]" value="5" type="radio"></td>
    <td >Extreme.</td>
  </tr>

  <tr>
    <td colspan="2" class="space-heading2">DURING THE PAST WEEK, HOW MUCH DIFFICULTY HAVE YOU HAD SLEEPING BECAUSE OF PAIN IN YOUR ARM, SHOULDER OR HAND</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="week_sleep[]" value="1" type="radio"  ></td>
    <td >No difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="week_sleep[]" value="2" type="radio"></td>
    <td >Mild difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="week_sleep[]" value="3" type="radio"></td>
    <td >Moderate difficulty. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="week_sleep[]" value="4" type="radio"></td>
    <td >Severe difficulty.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="week_sleep[]" value="5" type="radio"></td>
    <td >So much difficulty that i can't sleep.</td>
  </tr>

  <tr>
    <td colspan="2" class="space-heading2">I FEEL LESS CAPABLE, LESS CONFIDENT OR LESS USEFUL BECAUSE OF MY ARM, SHOULDER OR HAND PROBLEM</td>
  </tr>
  <tr>
    <td  class="spacer" WIDTH="1%" style="padding-right:8px;" >
        <input name="capable[]" value="1" type="radio"  ></td>
    <td >Strongly disagree.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="capable[]" value="2" type="radio"></td>
    <td >Disagree.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="capable[]" value="3" type="radio"></td>
    <td >Neither agree nor disagree. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="capable[]" value="4" type="radio"></td>
    <td >Agree.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="capable[]" value="5" type="radio"></td>
    <td >Strongly agree.</td>
  </tr>

  <tr>
    <td colspan="2" align="center">&nbsp;</td>
  </tr>

  <tr>
    <td align="center" colspan="2" >
    <input name="form_type" value="<!form_type>" type="hidden" >
    <input name="submitted" value="submit" type="hidden">
    <input name="patient_om_id" value="<!patient_om_id>" type="hidden"> 
    <input name="Submit" value="Submit" type="submit"> 
    </td>
  </tr>
</tbody></table>
</form>
</body></html>