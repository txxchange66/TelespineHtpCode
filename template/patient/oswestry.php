<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>Oswestry</title>
    <link href="css/outcome.css" rel="stylesheet" type="text/css">
    <script>
function validate(form) {
    var elLength = form.elements.length;

    for (i=0; i<elLength; i++)
    {
        var type = form.elements[i].type;
        var name = form.elements[i].name;
        var target = name.substr(0,(name.length-2));
        
        var value = form.elements[i].value;
        if( type == 'radio' ){
             len = form.elements[name].length;
             flag = false;
             for( j = 0; j<len; j++ ){
                if( form.elements[i].checked ){
                    flag = true;
                }
                i++;
             }
             i--;
             if( flag == false ){
				 document.getElementById("atleast").style.display='inline';
		document.location.href="#showfocus";		
		return false;                
             }
            
        }
		 
    }

  return true;
}</script>

</head>
<body>
<form action="/index.php?action=show_outcome_form" method="post" onSubmit="return validate(this);">
<!--<div style="margin: 0pt auto; width: 830px; font-weight: bold; font-family: 'Times New Roman',Times,serif; font-size: 23px; padding-bottom: 15px;">MODIFIED OSWESTRY LOW BACK PAIN DISABILITY QUESTIONNAIRE</div>-->
<table class="second-tab" width="830" align="center" border="0" cellpadding="0" cellspacing="0">
<tbody>
 <tr>
<td colspan="2"><div style="width:830px; margin:0 auto;  font-weight: bold; font-family: 'Times New Roman',Times,serif; font-size: 23px; padding-bottom: 15px;">Low Back Questionnaire</div></td>
</tr>
<tr>
<td colspan="2" style="padding-top: 10px; padding-bottom: 15px;">This questionnaire has been designed to give your Provider information as to how your back pain has affected your ability to manage in everyday life. Please answer every question by placing a mark on the line that best describes your condition today .We realize that you may feel that two of the statements may describe your condition, but <strong><u>please mark only the line which most closely describes your current condition.</u></strong></td>
</tr>
<tr>
    <td colspan="2" class="" align="center"><span id="atleast" style="color:#ff0000;display:none;"><a name="showfocus"></a>Please answer all of the questions and submit.</td>
  </tr>
  <tr>
    <td colspan="2" class="space-heading2">PAIN INTENSITY  </td>
  </tr>
  
  <tr>
    <td  class="spacer" style="padding-right:8px;" >
        <input name="pain_intensity[]" value="1" type="radio"  ></td>
    <td >The pain is  mild and comes and goes.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="pain_intensity[]" value="2" type="radio"></td>
    <td >The pain is  mild and does not vary much.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="pain_intensity[]" value="3" type="radio"></td>
    <td >The pain is moderate and comes and goes. </td>
  </tr>
  <tr>

    <td class="spacer"><input name="pain_intensity[]" value="4" type="radio"></td>
    <td >The pain is moderate and does not vary much.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="pain_intensity[]" value="5" type="radio"></td>
    <td >The pain is  severe and come and goes.</td>
  </tr>
  <tr>

    <td class="spacer"><input name="pain_intensity[]" value="6" type="radio"></td>
    <td >The pain is  severe and and does not vary much.</td>
  </tr>
  <tr>
    <td colspan="2" class="space-heading2">PERSONAL CARE (Washing, Dressing, etc.) </td>
  </tr>
  <tr>
    <td class="spacer"><input name="personal_care[]" value="1" type="radio"></td>
    <td >I do not have to change the way I wash and dress myself to avoid the pain.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="personal_care[]" value="2" type="radio"></td>
    <td >I do not normally change the way I wash or dress myself even though it causes some pain.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="personal_care[]" value="3" type="radio"></td> 
    <td >Washing and dressing increases my pain, but I can do it without changing my way of doing it.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="personal_care[]" value="4" type="radio"></td>
    <td >Washing and dressing increases my pain, and I find if necessary to change the way I do it.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="personal_care[]" value="5" type="radio"></td>
    <td >Because of my pain I am partially unable to wash and dress without help. </td>
  </tr>
  <tr>
    <td class="spacer"><input name="personal_care[]" value="6" type="radio"></td>
    <td >Because of my pain I am completely unable to wash and dress without help.</td>
  </tr>
  <tr>
    <td colspan="2" class="space-heading2">LIFTING</td>
  </tr>

  <tr>
    <td class="spacer"><input name="lifting[]" value="1" type="radio"></td>
    <td >I can lift heavy weights without increased pain.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="lifting[]" value="2" type="radio"></td>
    <td >I can lift heavy weights but it causes increased pain.</td>
  </tr>

  <tr>
    <td class="spacer" valign="top" ><input name="lifting[]" value="3" type="radio" ></td>
    <td valign="top"  >Pain prevents me from lifiting heavy weights off of the floor, but i can manage if they are conveniently positioned (ex. on a table,etc.).</td>
  </tr>
  <tr>
    <td class="spacer" valign="top" ><input name="lifting[]" value="4" type="radio"></td>
    <td valign="top" >Pain prevents me from lifiting heavy weights off of the floor, but i can manage light to medium weights if they are conveniently positioned.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="lifting[]" value="5" type="radio"></td>
    <td >I can lift only very light weights.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="lifting[]" value="6" type="radio"></td>
    <td >I can not lift or carry anything at all.</td>
  </tr>
  <tr>
    <td colspan="2" class="space-heading2">WALKING</td>
  </tr>
  <tr>
    <td class="spacer"><input name="walking[]" value="1" type="radio"></td>
    <td >I have no pain when walking.</td>
  </tr>

  <tr>
    <td class="spacer"><input name="walking[]" value="2" type="radio"></td>
    <td >I have pain when walking, but I can still walk my required normal distances.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="walking[]" value="3" type="radio"></td>
    <td >Pain pevents me from walking long distance.</td>
  </tr>

  <tr>
    <td class="spacer"><input name="walking[]" value="4" type="radio"></td>
    <td >Pain pevents me from walking intermediate distance.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="walking[]" value="5" type="radio"></td>
    <td >Pain pevents me from walking even short distance.</td>
  </tr>

  <tr>
    <td class="spacer"><input name="walking[]" value="6" type="radio"></td>
    <td >Pain pevents me from walking at all.</td>
  </tr>
  <tr>
    <td colspan="2" class="space-heading2">SITTING</td>
  </tr>
  <tr>

    <td class="spacer"><input name="sitting[]" value="1" type="radio"></td>
    <td >Sitting does not cause me any pain.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="sitting[]" value="2" type="radio"></td>
    <td >I can only sit as long as I like providing that I have my choice of seating surface.</td>
  </tr>
  <tr>

    <td class="spacer"><input name="sitting[]" value="3" type="radio"></td>
    <td >Pain prevent me from sitting for more than 1 hour.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="sitting[]" value="4" type="radio"></td>
    <td >Pain prevent me from sitting for more than 1/2 hour.</td>
  </tr>
  <tr>

    <td class="spacer"><input name="sitting[]" value="5" type="radio"></td>
    <td >Pain prevent me from sitting for more than 10 minutes.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="sitting[]" value="6" type="radio"></td>
    <td >Pain prevent me from sitting at all.</td>
  </tr>
  <tr>

    <td colspan="2" class="space-heading2">STANDING</td>
  </tr>
  <tr>
    <td class="spacer"><input name="standing[]" value="1" type="radio"></td>
    <td >I can stand as long as I want without increased pain.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="standing[]" value="2" type="radio"></td>
    <td >I can stand as long as I want but my pain increases with time.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="standing[]" value="3" type="radio"></td>
    <td >Pain prevents me from standing more than 1 hour.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="standing[]" value="4" type="radio"></td>
    <td >Pain prevents me from standing more than 1/2 hour.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="standing[]" value="5" type="radio"></td>
    <td >Pain prevents me from standing more than 10 minutes. </td>
  </tr>
  <tr>
    <td class="spacer"><input name="standing[]" value="6" type="radio"></td>
    <td >I avoid standing because it increases my pain right away.</td>
  </tr>
  <tr>
    <td colspan="2" class="space-heading2">SLEEPING</td>
  </tr>
  <tr>
    <td class="spacer"><input name="sleeping[]" value="1" type="radio"></td>
    <td >I get no pain when I am in bed.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="sleeping[]" value="2" type="radio"></td>
    <td >I get pain in bed, but it does not prevent me from sleeping well.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="sleeping[]" value="3" type="radio"></td>
    <td >Because of my pain, my sleep is only 3/4 of my normal amount.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="sleeping[]" value="4" type="radio"></td>
    <td >Because of my pain, my sleep is only 1/2 of my normal amount.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="sleeping[]" value="5" type="radio"></td>
    <td >Because of my pain, my sleep is only 1/4 of my normal amount.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="sleeping[]" value="6" type="radio"></td>
    <td >Pain prevents me from sleeping at all.</td>
  </tr>
  <tr>
    <td colspan="2" class="space-heading2">SOCIAL LIFE</td>
  </tr>

  <tr>
    <td class="spacer"><input name="social_life[]" value="1" type="radio"></td>
    <td >My social life is normal and does not increase my pain.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="social_life[]" value="2" type="radio"></td>
    <td >My social life is normal, but it increases my level of pain.</td>
  </tr>

  <tr>
    <td class="spacer"><input name="social_life[]" value="3" type="radio"></td>
    <td >Pain prevents me from participating in more energetic activities (ex. sports, dancing, etc.)</td>
  </tr>
  <tr>
    <td class="spacer"><input name="social_life[]" value="4" type="radio"></td>
    <td >Pain prevents me from going out very often.</td>
  </tr>

  <tr>
    <td class="spacer"><input name="social_life[]" value="5" type="radio"></td>
    <td >Pain has restricted my social life to my home.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="social_life[]" value="6" type="radio"></td>
    <td >I have hardly any social life because of my pain.</td>
  </tr>

  <tr>
    <td colspan="2" class="space-heading2">TRAVELING </td>
  </tr>
  <tr>
    <td class="spacer"><input name="traveling[]" value="1" type="radio"></td>
    <td >I get no increased pain when traveling.</td>
  </tr>
  <tr>

    <td class="spacer"><input name="traveling[]" value="2" type="radio"></td>
    <td >I get some pain while traveling, but none of my usual forms of travel make it any worse.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="traveling[]" value="3" type="radio"></td>
    <td >I get increased pain while traveling, but it does not cause me to seek alternative forms of travel.</td>
  </tr>
  <tr>

    <td class="spacer"><input name="traveling[]" value="4" type="radio"></td>
    <td >I get increased pain while traveling which causes me to seek alternative forms of travel.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="traveling[]" value="5" type="radio"></td>
    <td >My pain restricts all forms of travel except that which is done while I am lying down.</td>
  </tr><tr>
    <td class="spacer"><input name="traveling[]" value="6" type="radio"></td>
    <td >My pain restricts all forms of travel.</td>
  </tr>
  <tr>
    <td colspan="2" class="space-heading2">EMPLOYMENT / HOMEMAKING  </td>
  </tr>
  <tr>
    <td class="spacer"><input name="employment_homemaking[]" value="1" type="radio"></td>
    <td >My normal job/homemaking activities do not cause pain.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="employment_homemaking[]" value="2" type="radio"></td>
    <td >My normal job/homemaking activities increase my pain, but I can still perform all that is required of me.</td>
  </tr>
  <tr>
    <td class="spacer" valign="top" ><input name="employment_homemaking[]" value="3" type="radio"></td>
    <td valign="top" >I can perform most of my job/homemaking duties, but pain prevents me from performing more physically stressful activities (ex. lifting, vacuuming)</td>
  </tr>
  <tr>
    <td class="spacer"><input name="employment_homemaking[]" value="4" type="radio"></td>
    <td >Pain prevents me from doing anything but light duties.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="employment_homemaking[]" value="5" type="radio"></td>
    <td >Pain prevents me from doing even light duties.</td>
  </tr>
  <tr>
    <td class="spacer"><input name="employment_homemaking[]" value="6" type="radio"></td>
    <td >Pain prevents me from performing any job or homemaking chores.</td>
  </tr>
  <tr>
    <td colspan="2" class="space-heading2">PAIN SCALE</td>
  </tr>

  <tr>
    <td  colspan="2">During the last 4 weeks, how intense have your symptoms been:</td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td >None<span style="padding-left:320px;">Unbearable</span></td>
  </tr>
  
  <tr>
    <td >&nbsp;</td>
    <td class="spacer"><input type="radio" name="painscale[]" value="1">&nbsp;&nbsp;&nbsp;&nbsp;
    	<input type="radio" name="painscale[]" value="2">&nbsp;&nbsp;&nbsp;&nbsp;
    	<input type="radio" name="painscale[]" value="3">&nbsp;&nbsp;&nbsp;&nbsp;
    	<input type="radio" name="painscale[]" value="4">&nbsp;&nbsp;&nbsp;&nbsp;
    	<input type="radio" name="painscale[]" value="5">&nbsp;&nbsp;&nbsp;&nbsp;
    	<input type="radio" name="painscale[]" value="6">&nbsp;&nbsp;&nbsp;&nbsp;
    	<input type="radio" name="painscale[]" value="7">&nbsp;&nbsp;&nbsp;&nbsp;
    	<input type="radio" name="painscale[]" value="8">&nbsp;&nbsp;&nbsp;&nbsp;
    	<input type="radio" name="painscale[]" value="9">&nbsp;&nbsp;&nbsp;&nbsp;
    	<input type="radio" name="painscale[]" value="10"> </td>
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