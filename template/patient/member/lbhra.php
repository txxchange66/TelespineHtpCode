<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Patient History Questionnaire</title>
<link href="css/outcome.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/messages.css" />
<script type="text/javascript" src="js/messages.js"></script>      
<script src="js/jquery.js"></script>
<script language="javascript" >
$(document).ready(function(){
    /*$("input[@type=radio]").click(function() {
        var target = this.id.substr(0,4);
        if( !(target == 'q11s' || target == 'q11f') ){
            target = this.id.substr(0,3);
        }
        if( target == 'q11s' || target == 'q11f' || target == 'q12' || target == 'q13' ){
            $("input[@id='" + target + "']").attr("checked","");    
        }
    });*/
});    
</script>
</head>
<body>
<form name="form" id="form" action="index.php?action=employee_thanks_page" method="post" onsubmit="return validate(this);" >
<div style="width:890px;margin:auto;">
<table width="870" border="0" cellspacing="0" cellpadding="0" align="center" >
  <tr>
    <td style="padding-top:10px;text-align:left">This brief questionnaire is designed to assess your current low back health status and your risk for developing low back pain in the future. Tx Xchange will use this information to ensure you receive the most effective and appropriate program for your low back health. Completing the assessment takes about 5 minutes and your answers will be kept private.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td  bgcolor="#F3F3F3"><strong>1. What is your height? </strong><span id="q1" >&nbsp;</span></td>
  </tr>
  <tr>
    <td style="padding-top:5px; padding-left:20px;"><input name="q1[]" id="q1id" type="text" style="width:25%;"   /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td  bgcolor="#F3F3F3"><strong>2. What is your weight ? </strong><span id="q2" >&nbsp;</span></td>
  </tr>
  <tr>
    <td style="padding-top:5px; padding-left:20px;"><input name="q2[]" id="q2id" type="text" style="width:25%;"   /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#F3F3F3"><strong>3. Do you smoke? </strong><span id="q3" >&nbsp;</span></td>
  </tr>
  <tr>
    <td style="padding-top:5px;padding-left:17px;" > <input name="q3[]" id="q3id" type="radio" value="no" />
        No</td>
  </tr>
  <tr>
    <td style="padding-left:17px;"> <input name="q3[]" id="q3id" type="radio" value="socially" />
        Socially</td>
  </tr>
  <tr>
    <td style="padding-left:17px;" ><input name="q3[]" id="q3id" type="radio" value="daily" />
        Daily</td>
  </tr>
  <tr>
    <td style="padding-left:17px;" ><input name="q3[]" id="q3id" type="radio" value="pack" />
        1+ pack/day</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#F3F3F3" ><strong>4. Do you have scoliosis? </strong><span id="q4" >&nbsp;</span></td>
  </tr>
  <tr>
    <td style="padding-top:5px;padding-left:17px;" > <input name="q4[]" id="q4id" type="radio" value="yes" />
        Yes</td>
  </tr>
  <tr>
    <td style="padding-left:17px;" > <input name="q4[]" id="q4id" type="radio" value="no" />
        No</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#F3F3F3"><strong>5. Are you currently experiencing low back pain during any of your daily/work activities?  </strong><span id="q5" >&nbsp;</span></td>
  </tr>
  <tr>
    <td style="padding-top:5px;padding-left:17px;"  > <input name="q5[]" id="q5id" type="radio" value="yes" />
        Yes</td>
  </tr>
  <tr>
    <td style="padding-left:17px;" > <input name="q5[]" id="q5id" type="radio" value="no" />
        No</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#F3F3F3"><strong>6. Have you ever had an episode of low back pain in the past?  </strong><span id="q6" >&nbsp;</span></td>
  </tr>
  <tr>
    <td style="padding-top:5px;padding-left:17px;" > <input name="q6[]" id="q6id" type="radio" value="yes" />
        Yes</td>
  </tr>
  <tr>
    <td style="padding-left:17px;" > <input name="q6[]" id="q6id" type="radio" value="no" />
        No
<!--        </td>
  </tr>-->
  <div id="extra1" ></div>
    </td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#F3F3F3"><strong>7. How is your posture? </strong><span id="q7" >&nbsp;</span></td>
  </tr>
  <tr>
    <td style="padding-top:5px;padding-left:17px;" ><input name="q7[]" id="q7id" type="radio" value="I am aware of my posture and sit|stand upright most of the time" />    I am aware of my posture and sit/stand upright most of the time</td>
  </tr>
  <tr>
    <td style="padding-left:17px;" ><input name="q7[]" id="q7id" type="radio" value="I am sometimes aware of my posture" />    I am sometimes aware of my posture</td>
  </tr>
  <tr>
    <td style="padding-left:17px;" ><input name="q7[]" id="q7id" type="radio" value="I am not aware of my posture" />    I am not aware of my posture</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#F3F3F3"><strong>8. Do you know about the relationship between &#8220;core muscles&#8221; and back pain? </strong><span id="q8" >&nbsp;</span></td>  </tr>
  <tr>
    <td style="padding-top:5px;padding-left:17px;" ><input name="q8[]" id="q8id" type="radio" value="yes" /> Yes</td>
  </tr>
  <tr>
    <td style="padding-left:17px;" >
        <input name="q8[]" id="q8id" type="radio" value="no" /> No
        <div id="extra2" ></div>
    </td>
    
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#F3F3F3"><strong>9. How many hours a week are you active (i.e. playing sports, walking, active hobbies)  </strong><span id="q9" >&nbsp;</span></td>
  </tr>
  <tr>
    <td style="padding-top:5px;padding-left:17px;" ><input name="q9[]" id="q9id" type="radio" value="none" /> None</td>
  </tr>
  <tr>
    <td style="padding-left:17px;" ><input name="q9[]" id="q9id" type="radio" value="lessthen3" /> Less than 3</td>
  </tr>
  <tr>
    <td style="padding-left:17px;" ><input name="q9[]" id="q9id" type="radio" value="morethan3" /> More than 3</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#F3F3F3"><strong>10. Do you drive a vehicle for a living?   </strong><span id="q10" >&nbsp;</span></td>
  </tr>
  <tr>
    <td style="padding-top:5px;padding-left:25px;" ><input name="q10[]" id="q10id" type="radio" value="yes" /> 
    Yes </td>
  </tr>
  <tr>
    <td style="padding-left:25px;" ><input name="q10[]" id="q10id" type="radio" value="no" /> No</td>
  </tr>
  <tr><td>&nbsp;</td></tr>
  
  <tr>
    <td bgcolor="#F3F3F3"><strong>11.    Do you have occupational exposure to vibration?   </strong><span id="q11" >&nbsp;</span></td>
  </tr>
  <tr>
    <td style="padding-top:5px;padding-left:25px;" ><input name="q11[]" id="q11id" type="radio" value="yes" /> 
    Yes </td>
  </tr>
  <tr>
    <td style="padding-left:25px;" ><input name="q11[]" id="q11id" type="radio" value="no" /> No</td>
  </tr>
  <tr><td>&nbsp;</td></tr>
  <tr>
    <td bgcolor="#F3F3F3"><strong>12.    Do you perform backward or forward bending motions during work?  </strong><span id="q12" >&nbsp;</span></td>
  </tr>
  <tr>
    <td style="padding-top:5px;padding-left:25px;"  ><input name="q12[]" id="q12id" type="radio" value="" /> 
    Yes </td>
  </tr>
  <tr>
    <td style="padding-left:25px;" ><input name="q12[]" id="q12id" type="radio" value="" /> No</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#F3F3F3"><strong>13.    Do you have heavy physical demands at work?  </strong><span id="q13" >&nbsp;</span></td>
  </tr>
  <tr>
    <td style="padding-top:5px;padding-left:25px;" ><input name="q13[]" id="q13id" type="radio" value="no" /> No</td>
  </tr>
  <tr>
    <td style="padding-left:25px;" ><input name="q13[]" id="q13id" type="radio" value="sometimes" /> 
      Sometimes </td>
  </tr>
  <tr>
    <td style="padding-left:25px;" ><input name="q13[]" id="q13id" type="radio" value="mostofthetime" /> Most of the time</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#F3F3F3"><strong>14.    What is your stress level at work?  </strong><span id="q14" >&nbsp;</span></td>
  </tr>
  <tr>
    <td style="padding-top:5px;padding-left:25px;"  >
    <input name="q14[]" id="q14id" type="radio" value="1" /> 1&nbsp;&nbsp;&nbsp;&nbsp; 
    <input name="q14[]" id="q14id" type="radio" value="2" /> 2&nbsp;&nbsp;&nbsp;&nbsp; 
    <input name="q14[]" id="q14id" type="radio" value="3" /> 3&nbsp;&nbsp;&nbsp;&nbsp; 
    <input name="q14[]" id="q14id" type="radio" value="4" /> 4&nbsp;&nbsp;&nbsp;&nbsp; 
    <input name="q14[]" id="q14id" type="radio" value="5" /> 5&nbsp;&nbsp;&nbsp;&nbsp; 
    <input name="q14[]" id="q14id" type="radio" value="6" /> 6&nbsp;&nbsp;&nbsp;&nbsp; 
    <input name="q14[]" id="q14id" type="radio" value="7" /> 7&nbsp;&nbsp;&nbsp;&nbsp; 
    <input name="q14[]" id="q14id" type="radio" value="8" /> 8&nbsp;&nbsp;&nbsp;&nbsp; 
    <input name="q14[]" id="q14id" type="radio" value="9" /> 9&nbsp;&nbsp;&nbsp;&nbsp; 
    <input name="q14[]" id="q14id" type="radio" value="10" />10
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#F3F3F3"><strong>15.    Rate your stress level outside the workplace?  </strong><span id="q15" >&nbsp;</span></td>
  </tr>
  <tr>
    <td style="padding-top:5px;padding-left:25px;" >
        <input name="q15[]" id="q15id" type="radio" value="1" /> 1&nbsp;&nbsp;&nbsp;&nbsp; 
        <input name="q15[]" id="q15id" type="radio" value="2" /> 2&nbsp;&nbsp;&nbsp;&nbsp; 
        <input name="q15[]" id="q15id" type="radio" value="3" /> 3&nbsp;&nbsp;&nbsp;&nbsp; 
        <input name="q15[]" id="q15id" type="radio" value="4" /> 4&nbsp;&nbsp;&nbsp;&nbsp; 
        <input name="q15[]" id="q15id" type="radio" value="5" /> 5&nbsp;&nbsp;&nbsp;&nbsp; 
        <input name="q15[]" id="q15id" type="radio" value="6" /> 6&nbsp;&nbsp;&nbsp;&nbsp; 
        <input name="q15[]" id="q15id" type="radio" value="7" /> 7&nbsp;&nbsp;&nbsp;&nbsp; 
        <input name="q15[]" id="q15id" type="radio" value="8" /> 8&nbsp;&nbsp;&nbsp;&nbsp; 
        <input name="q15[]" id="q15id" type="radio" value="9" /> 9&nbsp;&nbsp;&nbsp;&nbsp; 
        <input name="q15[]" id="q15id" type="radio" value="10" />10
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#F3F3F3"><strong>16.    Do you have the ability to modify your work environment or positioning?</strong> <span id="q16" >&nbsp;</span></td>
  </tr>
  <tr>
    <td style="padding-top:5px;padding-left:25px;" ><input name="q16[]" id="q16id" type="radio" value="yes" /> Yes</td>
  </tr>
  <tr>
    <td style="padding-left:25px;"><input name="q16[]" id="q16id" type="radio" value="tosomedegree" /> To some degree</td>
  </tr>
  <tr>
    <td style="padding-left:25px;" ><input name="q16[]" id="q16id" type="radio" value="notatall" /> Not at all</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#F3F3F3"><strong>17.    Do you have workplace social support? </strong> <span id="q17" >&nbsp;</span></td>
  </tr>
  <tr>
    <td style="padding-top:5px;padding-left:25px;" ><input name="q17[]" id="q17id" type="radio" value="yes" /> Yes</td>
  </tr>
  <tr>
    <td style="padding-left:25px;" ><input name="q17[]" id="q17id" type="radio" value="some" /> Some</td>
  </tr>
  <tr>
    <td style="padding-left:25px;" ><input name="q17[]" id="q17id" type="radio" value="no" /> No</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#F3F3F3"><strong>18.    Rate your job satisfaction?</strong> <span id="q18" >&nbsp;</span></td>
  </tr>
  <tr>
    <td style="padding-top:5px;padding-left:25px;" ><input name="q18[]" id="q18id" type="radio" value="iamnotsatisfiedatwork" /> I am not satisfied at work</td>
  </tr>
  <tr>
    <td style="padding-left:25px;" ><input name="q18[]" id="q18id" type="radio" value="igetsomesatisfactionfromwork" /> I get some satisfaction from work</td>
  </tr>
  <tr>
    <td style="padding-left:25px;" ><input name="q18[]" id="q18id" type="radio" value="ihavealotofjobstatisfaction" /> I have a lot of job satisfaction</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#F3F3F3"><strong>19.    Do you have discomfort while working? </strong><span id="q19" >&nbsp;</span></td>
  </tr>
  <tr>
    <td style="padding-top:5px;padding-left:25px;" ><input name="q19[]" id="q19id" type="radio" value="none" /> None</td>
  </tr>
  <tr>
    <td style="padding-left:25px;" ><input name="q19[]" id="q19id" type="radio" value="occasional" /> Occasional </td>
  </tr>
  <tr>
    <td style="padding-left:25px;" ><input name="q19[]" id="q19id" type="radio" value="halfofthetime" /> Half of the time</td>
  </tr>
  <tr>
    <td style="padding-left:25px;" ><input name="q19[]" id="q19id" type="radio" value="allofthetime" /> All of the time </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td  bgcolor="#F3F3F3"><strong>20.    Do you have financial worries? </strong><span id="q20" >&nbsp;</span></td>
  </tr>
  <tr>
    <td style="padding-top:5px;padding-left:25px;"  ><input name="q20[]" id="q20id" type="radio" value="ihavenofinancialworries" /> I have no financial worries</td>
  </tr>
  <tr>
    <td style="padding-left:25px;" ><input name="q20[]" id="q20id" type="radio" value="sometimesiwrryaboutfinances" /> Sometimes I worry about finances</td>
  </tr>
  <tr>
    <td style="padding-left:25px;" ><input name="q20[]" id="q20id" type="radio" value="iworryagreatdealaboutfinances" />    I worry a great deal about finances</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#F3F3F3"><strong>21.    Do you have job insecurity? </strong><span id="q21" >&nbsp;</span></td>
  </tr>
  <tr>
    <td style="padding-top:5px;padding-left:25px;"  ><input name="q21[]" id="q21id" type="radio" value="none" /> None </td>
  </tr>
  <tr>
    <td style="padding-left:25px;" ><input name="q21[]" id="q21id" type="radio" value="some" /> Some </td>
  </tr>
  <tr>
    <td style="padding-left:25px;" ><input name="q21[]" id="q21id" type="radio" value="alot" /> A lot</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><input name="submit" type="submit" value="Submit" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</div>
</form>
<div id="extrahide1" style="display:none">
<table>
            
  <tr>
    <td>a. If yes, how long ago? <span id="q6a" >&nbsp;</span></td>
  </tr>
  <tr>
    <td style="padding-top:5px; padding-left:17px;">
        <input name="q6a[]" id="q6aid" type="text" style="width:58%;"   />
    </td>
  </tr>
  <tr>
    <td>b. If yes, how frequently do you have pain <span id="q6b" >&nbsp;</span></td>
  </tr>
  <tr>
    <td style="padding-top:5px;padding-left:17px;"><input name="q6b[]" id="q6bid" type="radio" value="1x" /> 1x per year</td>
  </tr>
  <tr>
    <td style="padding-left:17px;"><input name="q6b[]" id="q6bid" type="radio" value="2x" /> 2x per year</td>
  </tr>
  <tr>
    <td style="padding-left:17px;"><input name="q6b[]" id="q6bid" type="radio" value="3x" /> 3x per year</td>
  </tr>
  <tr>
    <td style="padding-left:17px;"><input name="q6b[]" id="q6bid" type="radio" value="4x" /> More than 3x per year</td>
  </tr>
  <tr>
    <td>c. If yes, how long do these episodes last (days) <span id="q6c" >&nbsp;</span></td>
  </tr>
  <tr>
    <td style="padding-top:5px; padding-left:17px;"><input name="q6c[]" id="q6cid" type="text" style="width:58%;"   /></td>
  </tr>
  <tr>
    <td>d. If yes, did you receive physical therapy treatment? <span id="q6d" >&nbsp;</span></td>
  </tr>
  <tr>
    <td style="padding-top:5px;padding-left:17px;"><input name="q6d[]" id="q6did" type="radio" value="yes" /> Yes</td>
  </tr>
  <tr>
    <td style="padding-left:17px;" ><input name="q6d[]" id="q6did" type="radio" value="no" /> No</td>
  </tr>
        </table>
</div>
<div id="extrahide2" style="display:none">
    <table>
                <tr>
                    <td>a. If yes, do you contract your &#8220;core muscles&#8221; during the day or activities? <span id="q8a" >&nbsp;</span></td>
                </tr>
                <tr>
                    <td style="padding-top:5px;padding-left:17px;"  ><input name="q8a[]" id="q8aid" type="radio" value="yes" /> Yes</td>
                </tr>
                <tr>
                    <td style="padding-left:17px;" ><input name="q8a[]" id="q8aid" type="radio" value="no" /> No</td>
                </tr>
            </table>
</div>
</body>
</html>
<script type="text/javascript">
    $(document).ready(function(){
        // Add onclick handler to checkbox w/id checkme
       $('[name="q6[]"]').click(function(){
           //alert($("#q6id").val());
           if ($("#q6id").is(":checked") && $("#q6id").val() == 'yes'){
                $("#extra1").html($("#extrahide1").html());
                $("#extra1").show('slow');
           }
           else{
               $("#extra1").hide('slow'); 
               $("#extra1").html('');
           }
      });

       $('[name="q8[]"]').click(function(){
           if ($("#q8id").is(":checked") && $("#q8id").val() == 'yes'){
                $("#extra2").html($("#extrahide2").html());
                $("#extra2").show('slow');
           }
           else{
               $("#extra2").hide('slow'); 
               $("#extra2").html('');
           }
        });
        
    })
     </script>
