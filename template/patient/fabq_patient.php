<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Fear-Avoidance Beliefs Questionnaire (FABQ)</title>
<link href="css/outcome.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/messages.css" />
<style>
.textQestion{
font-size:14px;
font-family:Times New Roman;
background:#F3F3F3;
}
.textOption{
font-size:14px;
font-family:Times New Roman;
padding-top:5px;
padding-left:5px;
}
</style>
<script type="text/javascript" src="js/fabq.js"></script>
<script src="js/jquery.js"></script>
</head>
<body>
<form name="form" id="form" action="index.php?action=show_outcome_form" method="post" onsubmit="return validatefabq(this);" >
  <div style="width:890px;margin:auto;">
    <table width="870" border="0" cellspacing="0" cellpadding="0" align="center" >
      <tr>
        <td><h2 align="center" style="font-family:Times New Roman;">Fear-Avoidance Beliefs Questionnaire (FABQ)</h2></td>
      </tr>
      <tr>
        <td style="padding-top:10px;text-align:left;font-size:18px; font-family:Times New Roman;">Here are some of the things which other patients have told us about their pain. For each statement please circle any number from 0 to 6 to say how much physical activities such as bending, lifting, walking or driving affect or would affect <i>your</i> back pain.</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><div style="border:solid 1px; color:#000000;"></div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="textQestion"><strong>1. My pain was caused by physical activity </strong><span id="q1" >&nbsp;</span></td>
      </tr>
      <tr>
        <td class="textOption"><table border="0" cellspacing="0" cellpadding="0" width="57%" >
            <tr>
              <td align="center" width="20%">Completely disagree</td>
              <td align="center" width="30%">Unsure</td>
              <td align="right" width="20%">Completely agree</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td style="padding-left:55px;"><table border="0" cellspacing="7" cellpadding="0" style="margin-bottom:8px;" width="50%">
            <tr>
              <td align="right"><input name="q1[]" id="q1id" type="radio" value="0" /></td>
                <td >0</td>
              <td align="right"><input name="q1[]" id="q1id" type="radio" value="1" /></td>
                <td >1</td>
              <td align="right"><input name="q1[]" id="q1id" type="radio" value="2" /></td>
                <td >2</td>
              <td align="right"><input name="q1[]" id="q1id" type="radio" value="3" /></td>
                <td >3</td>
              <td align="right"><input name="q1[]" id="q1id" type="radio" value="4" /></td>
                <td >4</td>
              <td align="right"><input name="q1[]" id="q1id" type="radio" value="5" /></td>
                <td >5</td>
              <td align="right"><input name="q1[]" id="q1id" type="radio" value="6" /></td>
                <td>6</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td  class="textQestion"><strong>2. Physical activity makes my pain worse </strong><span id="q2" >&nbsp;</span></td>
      </tr>
      <tr>
        <td class="textOption"><table border="0" cellspacing="0" cellpadding="0" width="57%" >
            <tr>
              <td align="center" width="20%">Completely disagree</td>
              <td align="center" width="30%">Unsure</td>
              <td align="right" width="20%">Completely agree</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td style="padding-left:55px;"><table border="0" cellspacing="7" cellpadding="0" style="margin-bottom:8px;" width="50%">
            <tr>
              <td align="right"><input name="q2[]" id="q2id" type="radio" value="0" /></td>
                <td >0</td>
              <td align="right"><input name="q2[]" id="q2id" type="radio" value="1" /></td>
                <td >1</td>
              <td align="right"><input name="q2[]" id="q2id" type="radio" value="2" /></td>
                <td >2</td>
              <td align="right"><input name="q2[]" id="q2id" type="radio" value="3" /></td>
                <td >3</td>
              <td align="right"><input name="q2[]" id="q2id" type="radio" value="4" /></td>
                <td >4</td>
              <td align="right"><input name="q2[]" id="q2id" type="radio" value="5" /></td>
                <td >5</td>
              <td align="right"><input name="q2[]" id="q2id" type="radio" value="6" /></td>
                <td>6</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td class="textQestion"><strong>3. Physical activity might harm my back </strong><span id="q3" >&nbsp;</span></td>
      </tr>
      <tr>
        <td class="textOption"><table border="0" cellspacing="0" cellpadding="0" width="57%" >
            <tr>
              <td align="center" width="20%">Completely disagree</td>
              <td align="center" width="30%">Unsure</td>
              <td align="right" width="20%">Completely agree</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td style="padding-left:55px;"><table border="0" cellspacing="7" cellpadding="0" style="margin-bottom:8px;" width="50%">
            <tr>
              <td align="right"><input name="q3[]" id="q3id" type="radio" value="0" /></td>
                <td >0</td>
              <td align="right"><input name="q3[]" id="q3id" type="radio" value="1" /></td>
                <td >1</td>
              <td align="right"><input name="q3[]" id="q3id" type="radio" value="2" /></td>
                <td >2</td>
              <td align="right"><input name="q3[]" id="q3id" type="radio" value="3" /></td>
                <td >3</td>
              <td align="right"><input name="q3[]" id="q3id" type="radio" value="4" /></td>
                <td >4</td>
              <td align="right"><input name="q3[]" id="q3id" type="radio" value="5" /></td>
                <td >5</td>
              <td align="right"><input name="q3[]" id="q3id" type="radio" value="6" /></td>
                <td>6</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td class="textQestion" ><strong>4. I should not do physical activities which (might) make my pain worse </strong><span id="q4" >&nbsp;</span></td>
      </tr>
     <tr>
        <td class="textOption"><table border="0" cellspacing="0" cellpadding="0" width="57%" >
            <tr>
              <td align="center" width="20%">Completely disagree</td>
              <td align="center" width="30%">Unsure</td>
              <td align="right" width="20%">Completely agree</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td style="padding-left:55px;"><table border="0" cellspacing="7" cellpadding="0" style="margin-bottom:8px;" width="50%">
            <tr>
              <td align="right"><input name="q4[]" id="q4id" type="radio" value="0" /></td>
                <td >0</td>
              <td align="right"><input name="q4[]" id="q4id" type="radio" value="1" /></td>
                <td >1</td>
              <td align="right"><input name="q4[]" id="q4id" type="radio" value="2" /></td>
                <td >2</td>
              <td align="right"><input name="q4[]" id="q4id" type="radio" value="3" /></td>
                <td >3</td>
              <td align="right"><input name="q4[]" id="q4id" type="radio" value="4" /></td>
                <td >4</td>
              <td align="right"><input name="q4[]" id="q4id" type="radio" value="5" /></td>
                <td >5</td>
              <td align="right"><input name="q4[]" id="q4id" type="radio" value="6" /></td>
                <td>6</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td class="textQestion"><strong>5. I cannot do physical activities which (might) make my pain worse </strong><span id="q5" >&nbsp;</span></td>
      </tr>
     <tr>
        <td class="textOption"><table border="0" cellspacing="0" cellpadding="0" width="57%" >
            <tr>
              <td align="center" width="20%">Completely disagree</td>
              <td align="center" width="30%">Unsure</td>
              <td align="right" width="20%">Completely agree</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td style="padding-left:55px;"><table border="0" cellspacing="7" cellpadding="0" style="margin-bottom:8px;" width="50%">
            <tr>
              <td align="right"><input name="q5[]" id="q5id" type="radio" value="0" /></td>
                <td >0</td>
              <td align="right"><input name="q5[]" id="q5id" type="radio" value="1" /></td>
                <td >1</td>
              <td align="right"><input name="q5[]" id="q5id" type="radio" value="2" /></td>
                <td >2</td>
              <td align="right"><input name="q5[]" id="q5id" type="radio" value="3" /></td>
                <td >3</td>
              <td align="right"><input name="q5[]" id="q5id" type="radio" value="4" /></td>
                <td >4</td>
              <td align="right"><input name="q5[]" id="q5id" type="radio" value="5" /></td>
                <td >5</td>
              <td align="right"><input name="q5[]" id="q5id" type="radio" value="6" /></td>
                <td>6</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td style="font-size:18px; font-family:Times New Roman;" bgcolor="#F3F3F3">The following statements are about how your normal work affects or would affect your back pain</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="textQestion"><strong>6. My pain was caused by my work or by an accident at work </strong><span id="q6" >&nbsp;</span></td>
      </tr>
     <tr>
        <td class="textOption"><table border="0" cellspacing="0" cellpadding="0" width="57%" >
            <tr>
              <td align="center" width="20%">Completely disagree</td>
              <td align="center" width="30%">Unsure</td>
              <td align="right" width="20%">Completely agree</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td style="padding-left:55px;"><table border="0" cellspacing="7" cellpadding="0" style="margin-bottom:8px;" width="50%">
            <tr>
              <td align="right"><input name="q6[]" id="q6id" type="radio" value="0" /></td>
                <td >0</td>
              <td align="right"><input name="q6[]" id="q6id" type="radio" value="1" /></td>
                <td >1</td>
              <td align="right"><input name="q6[]" id="q6id" type="radio" value="2" /></td>
                <td >2</td>
              <td align="right"><input name="q6[]" id="q6id" type="radio" value="3" /></td>
                <td >3</td>
              <td align="right"><input name="q6[]" id="q6id" type="radio" value="4" /></td>
                <td >4</td>
              <td align="right"><input name="q6[]" id="q6id" type="radio" value="5" /></td>
                <td >5</td>
              <td align="right"><input name="q6[]" id="q6id" type="radio" value="6" /></td>
                <td>6</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td class="textQestion"><strong>7. My work aggravated my pain </strong><span id="q7" >&nbsp;</span></td>
      </tr>
     <tr>
        <td class="textOption"><table border="0" cellspacing="0" cellpadding="0" width="57%" >
            <tr>
              <td align="center" width="20%">Completely disagree</td>
              <td align="center" width="30%">Unsure</td>
              <td align="right" width="20%">Completely agree</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td style="padding-left:55px;"><table border="0" cellspacing="7" cellpadding="0" style="margin-bottom:8px;" width="50%">
            <tr>
              <td align="right"><input name="q7[]" id="q7id" type="radio" value="0" /></td>
                <td >0</td>
              <td align="right"><input name="q7[]" id="q7id" type="radio" value="1" /></td>
                <td >1</td>
              <td align="right"><input name="q7[]" id="q7id" type="radio" value="2" /></td>
                <td >2</td>
              <td align="right"><input name="q7[]" id="q7id" type="radio" value="3" /></td>
                <td >3</td>
              <td align="right"><input name="q7[]" id="q7id" type="radio" value="4" /></td>
                <td >4</td>
              <td align="right"><input name="q7[]" id="q7id" type="radio" value="5" /></td>
                <td >5</td>
              <td align="right"><input name="q7[]" id="q7id" type="radio" value="6" /></td>
                <td>6</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td class="textQestion"><strong>8. I have a claim for compensation for my pain </strong><span id="q8" >&nbsp;</span></td>
      </tr>
     <tr>
        <td class="textOption"><table border="0" cellspacing="0" cellpadding="0" width="57%" >
            <tr>
              <td align="center" width="20%">Completely disagree</td>
              <td align="center" width="30%">Unsure</td>
              <td align="right" width="20%">Completely agree</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td style="padding-left:55px;"><table border="0" cellspacing="7" cellpadding="0" style="margin-bottom:8px;" width="50%">
            <tr>
              <td align="right"><input name="q8[]" id="q8id" type="radio" value="0" /></td>
                <td >0</td>
              <td align="right"><input name="q8[]" id="q8id" type="radio" value="1" /></td>
                <td >1</td>
              <td align="right"><input name="q8[]" id="q8id" type="radio" value="2" /></td>
                <td >2</td>
              <td align="right"><input name="q8[]" id="q8id" type="radio" value="3" /></td>
                <td >3</td>
              <td align="right"><input name="q8[]" id="q8id" type="radio" value="4" /></td>
                <td >4</td>
              <td align="right"><input name="q8[]" id="q8id" type="radio" value="5" /></td>
                <td >5</td>
              <td align="right"><input name="q8[]" id="q8id" type="radio" value="6" /></td>
                <td>6</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td class="textQestion"><strong>9. My work is too heavy for me </strong><span id="q9" >&nbsp;</span></td>
      </tr>
     <tr>
        <td class="textOption"><table border="0" cellspacing="0" cellpadding="0" width="57%" >
            <tr>
              <td align="center" width="20%">Completely disagree</td>
              <td align="center" width="30%">Unsure</td>
              <td align="right" width="20%">Completely agree</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td style="padding-left:55px;"><table border="0" cellspacing="7" cellpadding="0" style="margin-bottom:8px;" width="50%">
            <tr>
              <td align="right"><input name="q9[]" id="q9id" type="radio" value="0" /></td>
                <td >0</td>
              <td align="right"><input name="q9[]" id="q9id" type="radio" value="1" /></td>
                <td >1</td>
              <td align="right"><input name="q9[]" id="q9id" type="radio" value="2" /></td>
                <td >2</td>
              <td align="right"><input name="q9[]" id="q9id" type="radio" value="3" /></td>
                <td >3</td>
              <td align="right"><input name="q9[]" id="q9id" type="radio" value="4" /></td>
                <td >4</td>
              <td align="right"><input name="q9[]" id="q9id" type="radio" value="5" /></td>
                <td >5</td>
              <td align="right"><input name="q9[]" id="q9id" type="radio" value="6" /></td>
                <td>6</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td class="textQestion"><strong>10. My work makes or would make my pain worse </strong><span id="q10" >&nbsp;</span></td>
      </tr>
     <tr>
        <td class="textOption"><table border="0" cellspacing="0" cellpadding="0" width="57%" >
            <tr>
              <td align="center" width="20%">Completely disagree</td>
              <td align="center" width="30%">Unsure</td>
              <td align="right" width="20%">Completely agree</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td style="padding-left:55px;"><table border="0" cellspacing="7" cellpadding="0" style="margin-bottom:8px;" width="50%">
            <tr>
              <td align="right"><input name="q10[]" id="q10id" type="radio" value="0" /></td>
                <td >0</td>
              <td align="right"><input name="q10[]" id="q10id" type="radio" value="1" /></td>
                <td >1</td>
              <td align="right"><input name="q10[]" id="q10id" type="radio" value="2" /></td>
                <td >2</td>
              <td align="right"><input name="q10[]" id="q10id" type="radio" value="3" /></td>
                <td >3</td>
              <td align="right"><input name="q10[]" id="q10id" type="radio" value="4" /></td>
                <td >4</td>
              <td align="right"><input name="q10[]" id="q10id" type="radio" value="5" /></td>
                <td >5</td>
              <td align="right"><input name="q10[]" id="q10id" type="radio" value="6" /></td>
                <td>6</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td class="textQestion"><strong>11.    My work might harm my back </strong><span id="q11" >&nbsp;</span></td>
      </tr>
     <tr>
        <td class="textOption"><table border="0" cellspacing="0" cellpadding="0" width="57%" >
            <tr>
              <td align="center" width="20%">Completely disagree</td>
              <td align="center" width="30%">Unsure</td>
              <td align="right" width="20%">Completely agree</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td style="padding-left:55px;"><table border="0" cellspacing="7" cellpadding="0" style="margin-bottom:8px;" width="50%">
            <tr>
              <td align="right"><input name="q11[]" id="q11id" type="radio" value="0" /></td>
                <td >0</td>
              <td align="right"><input name="q11[]" id="q11id" type="radio" value="1" /></td>
                <td >1</td>
              <td align="right"><input name="q11[]" id="q11id" type="radio" value="2" /></td>
                <td >2</td>
              <td align="right"><input name="q11[]" id="q11id" type="radio" value="3" /></td>
                <td >3</td>
              <td align="right"><input name="q11[]" id="q11id" type="radio" value="4" /></td>
                <td >4</td>
              <td align="right"><input name="q11[]" id="q11id" type="radio" value="5" /></td>
                <td >5</td>
              <td align="right"><input name="q11[]" id="q11id" type="radio" value="6" /></td>
                <td>6</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td class="textQestion"><strong>12.    I should not do my normal work with my present pain </strong><span id="q12" >&nbsp;</span></td>
      </tr>
     <tr>
        <td class="textOption"><table border="0" cellspacing="0" cellpadding="0" width="57%" >
            <tr>
              <td align="center" width="20%">Completely disagree</td>
              <td align="center" width="30%">Unsure</td>
              <td align="right" width="20%">Completely agree</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td style="padding-left:55px;"><table border="0" cellspacing="7" cellpadding="0" style="margin-bottom:8px;" width="50%">
            <tr>
              <td align="right"><input name="q12[]" id="q12id" type="radio" value="0" /></td>
                <td >0</td>
              <td align="right"><input name="q12[]" id="q12id" type="radio" value="1" /></td>
                <td >1</td>
              <td align="right"><input name="q12[]" id="q12id" type="radio" value="2" /></td>
                <td >2</td>
              <td align="right"><input name="q12[]" id="q12id" type="radio" value="3" /></td>
                <td >3</td>
              <td align="right"><input name="q12[]" id="q12id" type="radio" value="4" /></td>
                <td >4</td>
              <td align="right"><input name="q12[]" id="q12id" type="radio" value="5" /></td>
                <td >5</td>
              <td align="right"><input name="q12[]" id="q12id" type="radio" value="6" /></td>
                <td>6</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td class="textQestion"><strong>13.    I cannot do my normal work with my present pain </strong><span id="q13" >&nbsp;</span></td>
      </tr>
     <tr>
        <td class="textOption"><table border="0" cellspacing="0" cellpadding="0" width="57%" >
            <tr>
              <td align="center" width="20%">Completely disagree</td>
              <td align="center" width="30%">Unsure</td>
              <td align="right" width="20%">Completely agree</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td style="padding-left:55px;"><table border="0" cellspacing="7" cellpadding="0" style="margin-bottom:8px;" width="50%">
            <tr>
              <td align="right"><input name="q13[]" id="q13id" type="radio" value="0" /></td>
                <td >0</td>
              <td align="right"><input name="q13[]" id="q13id" type="radio" value="1" /></td>
                <td >1</td>
              <td align="right"><input name="q13[]" id="q13id" type="radio" value="2" /></td>
                <td >2</td>
              <td align="right"><input name="q13[]" id="q13id" type="radio" value="3" /></td>
                <td >3</td>
              <td align="right"><input name="q13[]" id="q13id" type="radio" value="4" /></td>
                <td >4</td>
              <td align="right"><input name="q13[]" id="q13id" type="radio" value="5" /></td>
                <td >5</td>
              <td align="right"><input name="q13[]" id="q13id" type="radio" value="6" /></td>
                <td>6</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td class="textQestion"><strong>14.    I cannot do my normal work till my pain is treated </strong><span id="q14" >&nbsp;</span></td>
      </tr>
     <tr>
        <td class="textOption"><table border="0" cellspacing="0" cellpadding="0" width="57%" >
            <tr>
              <td align="center" width="20%">Completely disagree</td>
              <td align="center" width="30%">Unsure</td>
              <td align="right" width="20%">Completely agree</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td style="padding-left:55px;"><table border="0" cellspacing="7" cellpadding="0" style="margin-bottom:8px;" width="50%">
            <tr>
              <td align="right"><input name="q14[]" id="q14id" type="radio" value="0" /></td>
                <td >0</td>
              <td align="right"><input name="q14[]" id="q14id" type="radio" value="1" /></td>
                <td >1</td>
              <td align="right"><input name="q14[]" id="q14id" type="radio" value="2" /></td>
                <td >2</td>
              <td align="right"><input name="q14[]" id="q14id" type="radio" value="3" /></td>
                <td >3</td>
              <td align="right"><input name="q14[]" id="q14id" type="radio" value="4" /></td>
                <td >4</td>
              <td align="right"><input name="q14[]" id="q14id" type="radio" value="5" /></td>
                <td >5</td>
              <td align="right"><input name="q14[]" id="q14id" type="radio" value="6" /></td>
                <td>6</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td class="textQestion"><strong>15.    I do not think that I will be back to my normal work within 3 months </strong><span id="q15" >&nbsp;</span></td>
      </tr>
     <tr>
        <td class="textOption"><table border="0" cellspacing="0" cellpadding="0" width="57%" >
            <tr>
              <td align="center" width="20%">Completely disagree</td>
              <td align="center" width="30%">Unsure</td>
              <td align="right" width="20%">Completely agree</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td style="padding-left:55px;"><table border="0" cellspacing="7" cellpadding="0" style="margin-bottom:8px;" width="50%">
            <tr>
              <td align="right"><input name="q15[]" id="q15id" type="radio" value="0" /></td>
                <td >0</td>
              <td align="right"><input name="q15[]" id="q15id" type="radio" value="1" /></td>
                <td >1</td>
              <td align="right"><input name="q15[]" id="q15id" type="radio" value="2" /></td>
                <td >2</td>
              <td align="right"><input name="q15[]" id="q15id" type="radio" value="3" /></td>
                <td >3</td>
              <td align="right"><input name="q15[]" id="q15id" type="radio" value="4" /></td>
                <td >4</td>
              <td align="right"><input name="q15[]" id="q15id" type="radio" value="5" /></td>
                <td >5</td>
              <td align="right"><input name="q15[]" id="q15id" type="radio" value="6" /></td>
                <td>6</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td class="textQestion"><strong>16.    I do not think that I will ever be able to go back to that work</strong> <span id="q16" >&nbsp;</span></td>
      </tr>
     <tr>
        <td class="textOption"><table border="0" cellspacing="0" cellpadding="0" width="57%" >
            <tr>
              <td align="center" width="20%">Completely disagree</td>
              <td align="center" width="30%">Unsure</td>
              <td align="right" width="20%">Completely agree</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td style="padding-left:55px;"><table border="0" cellspacing="7" cellpadding="0" style="margin-bottom:8px;" width="50%">
            <tr>
              <td align="right"><input name="q16[]" id="q16id" type="radio" value="0" /></td>
                <td >0</td>
              <td align="right"><input name="q16[]" id="q16id" type="radio" value="1" /></td>
                <td >1</td>
              <td align="right"><input name="q16[]" id="q16id" type="radio" value="2" /></td>
                <td >2</td>
              <td align="right"><input name="q16[]" id="q16id" type="radio" value="3" /></td>
                <td >3</td>
              <td align="right"><input name="q16[]" id="q16id" type="radio" value="4" /></td>
                <td >4</td>
              <td align="right"><input name="q16[]" id="q16id" type="radio" value="5" /></td>
                <td >5</td>
              <td align="right"><input name="q16[]" id="q16id" type="radio" value="6" /></td>
                <td>6</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td align="center">
		  <input name="form_type" value="<!form_type>" type="hidden" >
          <input name="submitted" value="submit" type="hidden" >
          <input name="patient_om_id" value="<!patient_om_id>" type="hidden" >
          <input name="Submit" value="Submit" type="submit">
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
  </div>
</form>
</body>
</html>
