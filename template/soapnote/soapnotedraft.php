<div id="container">
	<div id="header">
		<!header>
	</div>

	<div id="sidebar">
		<!sidebar>
	</div>
	<script language="JavaScript" type="text/javascript">
   	var con=false;
$("a").bind("click",function(){

	
	con=confirm("You are about to navigate away from this SOAP without saving it first. Are you sure?");

	if(!con)
    return false;
}); 
 

    window.onload = function() {

$("#breadcrumbNav a").bind("click",function(){

	con=confirm("You are about to navigate away from this SOAP without saving it first. Are you sure?");
	if(!con)
		return false;
});       
    	$("#mainContent form a").click(function(){
    			return false;
    			
    	}); 

    };
</script>

	<script type="text/javascript">

function setstatus(valu){
	$('#status').val(valu);
  return false;
}

function tabs(obj, el){
	//alert(obj);
	$(".mydiv").hide();
	$("#"+obj).show();
	$('.newtab li a span').removeClass('textblue');
	$(el).find('span').addClass('textblue');
	$('.newtab li').removeClass('current');
	$(el).parent().addClass('current');
	jQuery("textarea.expand").css({"width":630});
	jQuery("textarea.expand").TextAreaExpander();
    jQuery("textarea.expand_desc").TextAreaExpander();
	jQuery("textarea.expand_desc").css({"width":123});
}

function treeView(col,e)
{
		var colData = $("#"+col);
	if($(colData).css("display")=="block")
	{
		$(colData).hide();
		$(e).parent().css("font-size","16px");
		$(e).html("+");
	}
	else
	{
		$(colData).show();
		$(e).parent().css("font-size","20px");
		$(e).html("-");	
	}
jQuery("textarea.expand").TextAreaExpander();
jQuery("textarea.expand").css({"width":630});
jQuery("textarea.expand_desc").TextAreaExpander();
jQuery("textarea.expand_desc").css({"width":123});
}

var a=0;
function addData(){
	if(a < 2){
		//alert(a);
		var b=a+1;
		if(a==0){
		$('#addy').append("<table cellpadding='5' cellspacing='0' style='width: 680px;' class='soap-note-subj' align='center'><tr><td>&nbsp;</td><td >Chief Complaint<br/><span style='float:right;'><a onclick=\"GB_showCenter('Chief Complaint ', '/index.php?action=soapfillanswer&qid=1&id=26',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name='chief_complaint[]'  title='chief_complaint"+b+"' id='q26' ></textarea></td></tr></table><table cellpadding='5' cellspacing='0' style='width: 680px;' class='soap-note-subj'><tr><td style='width:10px; font-size:16px; color:#00699d;' class='linkCol' valign='top'><a href='javascript:;' style='text-decoration:none;' onclick=treeView('history"+b+"',this)>+</a> </td><td class='heading' >History of Present Illness</td></tr></table><div id='history"+b+"' style='display:none;'><table cellpadding='5'  cellspacing='0' style='width: 680px;' class='soap-note-subj' align='center'><tr><td>&nbsp;</td><td >Location<br/><span style='float:right;'><a onclick=\"GB_showCenter('History of Present Illness/Location ', '/index.php?action=soapfillanswer&qid=2&id=27',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=location[]  title='location"+b+"' id='q27'></textarea></td></tr><tr><td>&nbsp;</td><td >Onset<br/><span style='float:right;'><a onclick=\"GB_showCenter('History of Present Illness/Onset ', '/index.php?action=soapfillanswer&qid=3&id=28',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=onset[]  title='onset"+b+"' id='q28'></textarea></td></tr><tr><td>&nbsp;</td><td >Provocation<br/><span style='float:right;'><a onclick=\"GB_showCenter('History of Present Illness/Provocation ', '/index.php?action=soapfillanswer&qid=4&id=29',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=provocation[]  title='provocation"+b+"' id='q29' ></textarea></td></tr><tr><td>&nbsp;</td><td >Palliation<br/><span style='float:right;'><a onclick=\"GB_showCenter('History of Present Illness/Palliation', '/index.php?action=soapfillanswer&qid=5&id=30',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=palliation[]  title='palliation"+b+"' id='q30' ></textarea></td></tr><tr><td>&nbsp;</td><td >Quality<br/><span style='float:right;'><a onclick=\"GB_showCenter('History of Present Illness/Quality ', '/index.php?action=soapfillanswer&qid=6&id=31',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=quality[]  title='quality"+b+"' id='q31' ></textarea></td></tr><tr><td>&nbsp;</td><td >Radiation<br/><span style='float:right;'><a onclick=\"GB_showCenter('History of Present Illness/Radiation ', '/index.php?action=soapfillanswer&qid=7&id=32',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=radiation[]  title='radiation"+b+"' id='q32' ></textarea></td></tr><tr><td>&nbsp;</td><td>Severity<br/><span style='float:right;'><a onclick=\"GB_showCenter('History of Present Illness/Severity ', '/index.php?action=soapfillanswer&qid=8&id=33',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=severity[]  title='severity"+b+"' id='q33'></textarea></td></tr><tr><td>&nbsp;</td><td>Duration<br/><span style='float:right;'><a onclick=\"GB_showCenter('History of Present Illness/Duration ', '/index.php?action=soapfillanswer&qid=9&id=34',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=duration[]  title='duration"+b+"' id='q34' ></textarea></td></tr><tr><td>&nbsp;</td><td>Timing<br/><span style='float:right;'><a onclick=\"GB_showCenter('History of Present Illness/Timing ', '/index.php?action=soapfillanswer&qid=10&id=35',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=timing[]  title='timing"+b+"' id='q35'></textarea></td></tr><tr><td>&nbsp;</td><td>Associated signs/symptoms<br/><span style='float:right;'><a onclick=\"GB_showCenter('History of Present Illness/Associated signs/symptoms ', '/index.php?action=soapfillanswer&qid=11&id=36',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=associated_signs_symptoms[]  title='associated_signs_symptoms"+b+"' id='q36' ></textarea></td></tr></table></div><table cellpadding='5'cellspacing='0'style='width:680px;'class='soap-note-subj'><tr><td style='width:10px;font-size:16px;color:#00699d;'class='linkCol'><a href='javascript:;' style='text-decoration:none;' onclick=treeView('review"+b+"',this)>+</a></td><td class='heading'>Review of Systems</td></tr></table><div id='review"+b+"' style='display:none;'><table cellpadding='5' cellspacing='0' style='width:680px;' class='soap-note-subj' align='center'><tr><td>&nbsp;</td><td>Constitutional<br/><span style='float:right;'><a onclick=\"GB_showCenter('Review of Systems/Constitutional ', '/index.php?action=soapfillanswer&qid=12&id=37',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=constitutional[]  title='constitutional"+b+"' id='q37' ></textarea></td></tr><tr><td>&nbsp;</td><td>Eyes<br/><span style='float:right;'><a onclick=\"GB_showCenter('Review of Systems/Eyes ', '/index.php?action=soapfillanswer&qid=13&id=38',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=eyes[]  title='eyes"+b+"' id='q38' ></textarea></td></tr><tr><td>&nbsp;</td><td>Ears, nose, mouth, throat<br/><span style='float:right;'><a onclick=\"GB_showCenter('Review of Systems/Ears, nose, mouth, throat', '/index.php?action=soapfillanswer&qid=14&id=39',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=ears_nose_mouth_throat[]  title='ears_nose_mouth_throat"+b+"' id='q39'></textarea></td></tr><tr><td>&nbsp;</td><td>Cardiovascular<br/><span style='float:right;'><a onclick=\"GB_showCenter('Review of Systems/Cardiovascular ', '/index.php?action=soapfillanswer&qid=15&id=40',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=cardiovascular[]  title='cardiovascular"+b+"' id='q40' ></textarea></td></tr><tr><td>&nbsp;</td><td>Respiratory<br/><span style='float:right;'><a onclick=\"GB_showCenter('Review of Systems/Respiratory ', '/index.php?action=soapfillanswer&qid=16&id=41',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=respiratory[]  title='respiratory"+b+"' id='q41'></textarea></td></tr><tr><td>&nbsp;</td><td>Gastrointestinal<br/><span style='float:right;'><a onclick=\"GB_showCenter('Review of Systems/Gastrointestinal ', '/index.php?action=soapfillanswer&qid=17&id=42',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=gastrointestinal[]  title='gastrointestinal"+b+"' id='q42'></textarea></td></tr><tr><td>&nbsp;</td><td>Genital-urinary<br/><span style='float:right;'><a onclick=\"GB_showCenter('Review of Systems/Genital-urinary', '/index.php?action=soapfillanswer&qid=18&id=43',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=genital_urinary[]   title='genital_urinary"+b+"' id='q43'></textarea></td></tr><tr><td>&nbsp;</td><td>Musculo-skeletal<br/><span style='float:right;'><a onclick=\"GB_showCenter('Review of Systems/Musculo-skeletal ', '/index.php?action=soapfillanswer&qid=19&id=44',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=musculo_skeletal[]  title='musculo_skeletal"+b+"' id='q44'></textarea></td></tr><tr><td>&nbsp;</td><td>Skin<br/><span style='float:right;'><a onclick=\"GB_showCenter('Review of Systems/Skin', '/index.php?action=soapfillanswer&qid=20&id=45',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=skin[]   title='skin"+b+"' id='q45' ></textarea></td></tr><tr><td>&nbsp;</td><td>Neurological<br/><span style='float:right;'><a onclick=\"GB_showCenter('Review of Systems/Neurological ', '/index.php?action=soapfillanswer&qid=21&id=46',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=neurological[]  title='neurological"+b+"'  id='q46'></textarea></td></tr><tr><td>&nbsp;</td><td>Psychiatric<br/><span style='float:right;'><a onclick=\"GB_showCenter('Review of Systems/Psychiatric ', '/index.php?action=soapfillanswer&qid=22&id=47',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=psychiatric[]  title='psychiatric"+b+"' id='q47'></textarea></td></tr><tr><td>&nbsp;</td><td>Endocrine<br/><span style='float:right;'><a onclick=\"GB_showCenter('Review of Systems/Endocrine', '/index.php?action=soapfillanswer&qid=23&id=48',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=endocrine[]  title='endocrine"+b+"' id='q48' ></textarea></td></tr><tr><td>&nbsp;</td><td>Hematology/lymphatic<br/><span style='float:right;'><a onclick=\"GB_showCenter('Review of Systems/Hematology/lymphatic ', '/index.php?action=soapfillanswer&qid=24&id=49',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=hematology_lymphatic[]  title='hematology_lymphatic"+b+"' id='q49' ></textarea></td></tr><tr><td>&nbsp;</td><td>Allergy/immunology<br/><span style='float:right;'><a onclick=\"GB_showCenter('Review of Systems/Allergy/immunology', '/index.php?action=soapfillanswer&qid=25&id=50',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=allergy_immunology[]  title='allergy_immunology"+b+"' id='q50'></textarea></td></tr><tr><td>&nbsp;</td><td><input type='hidden' name='ccnumber[]' value='2'></td></tr></table></div>");
		}
		if(a==1){
		$('#addy').append("<table cellpadding='5' cellspacing='0' style='width: 680px;' class='soap-note-subj' align='center'><tr><td>&nbsp;</td><td >Chief Complaint<br/><span style='float:right;'><a onclick=\"GB_showCenter('Chief Complaint ', '/index.php?action=soapfillanswer&qid=1&id=51',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name='chief_complaint[]'  title='chief_complaint"+b+"' id='q51' ></textarea></td></tr></table><table cellpadding='5' cellspacing='0' style='width: 680px;' class='soap-note-subj'><tr><td style='width:10px; font-size:16px; color:#00699d;' class='linkCol' valign='top'><a href='javascript:;' style='text-decoration:none;' onclick=treeView('history"+b+"',this)>+</a> </td><td class='heading' >History of Present Illness</td></tr></table><div id='history"+b+"' style='display:none;'><table cellpadding='5'  cellspacing='0' style='width: 680px;' class='soap-note-subj' align='center'><tr><td>&nbsp;</td><td >Location<br/><span style='float:right;'><a onclick=\"GB_showCenter('History of Present Illness/Location ', '/index.php?action=soapfillanswer&qid=2&id=52',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=location[]  title='location"+b+"' id='q52'></textarea></td></tr><tr><td>&nbsp;</td><td >Onset<br/><span style='float:right;'><a onclick=\"GB_showCenter('History of Present Illness/Onset ', '/index.php?action=soapfillanswer&qid=3&id=53',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=onset[]  title='onset"+b+"' id='q53'></textarea></td></tr><tr><td>&nbsp;</td><td >Provocation<br/><span style='float:right;'><a onclick=\"GB_showCenter('History of Present Illness/Provocation ', '/index.php?action=soapfillanswer&qid=4&id=54',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=provocation[]  title='provocation"+b+"' id='q54' ></textarea></td></tr><tr><td>&nbsp;</td><td >Palliation<br/><span style='float:right;'><a onclick=\"GB_showCenter('History of Present Illness/Palliation', '/index.php?action=soapfillanswer&qid=5&id=55',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=palliation[]  title='palliation"+b+"' id='q55' ></textarea></td></tr><tr><td>&nbsp;</td><td >Quality<br/><span style='float:right;'><a onclick=\"GB_showCenter('History of Present Illness/Quality ', '/index.php?action=soapfillanswer&qid=6&id=56',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=quality[]  title='quality"+b+"' id='q56' ></textarea></td></tr><tr><td>&nbsp;</td><td >Radiation<br/><span style='float:right;'><a onclick=\"GB_showCenter('History of Present Illness/Radiation ', '/index.php?action=soapfillanswer&qid=7&id=57',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=radiation[]  title='radiation"+b+"' id='q57' ></textarea></td></tr><tr><td>&nbsp;</td><td>Severity<br/><span style='float:right;'><a onclick=\"GB_showCenter('History of Present Illness/Severity ', '/index.php?action=soapfillanswer&qid=8&id=58',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=severity[]  title='severity"+b+"' id='q58'></textarea></td></tr><tr><td>&nbsp;</td><td>Duration<br/><span style='float:right;'><a onclick=\"GB_showCenter('History of Present Illness/Duration ', '/index.php?action=soapfillanswer&qid=9&id=59',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=duration[]  title='duration"+b+"' id='q59' ></textarea></td></tr><tr><td>&nbsp;</td><td>Timing<br/><span style='float:right;'><a onclick=\"GB_showCenter('History of Present Illness/Timing ', '/index.php?action=soapfillanswer&qid=10&id=60',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=timing[]  title='timing"+b+"' id='q60'></textarea></td></tr><tr><td>&nbsp;</td><td>Associated signs/symptoms<br/><span style='float:right;'><a onclick=\"GB_showCenter('History of Present Illness/Associated signs/symptoms ', '/index.php?action=soapfillanswer&qid=11&id=61',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=associated_signs_symptoms[]  title='associated_signs_symptoms"+b+"' id='q61' ></textarea></td></tr></table></div><table cellpadding='5'cellspacing='0'style='width:680px;'class='soap-note-subj'><tr><td style='width:10px;font-size:16px;color:#00699d;'class='linkCol'><a href='javascript:;' style='text-decoration:none;' onclick=treeView('review"+b+"',this)>+</a></td><td class='heading'>Review of Systems</td></tr></table><div id='review"+b+"' style='display:none;'><table cellpadding='5' cellspacing='0' style='width:680px;' class='soap-note-subj' align='center'><tr><td>&nbsp;</td><td>Constitutional<br/><span style='float:right;'><a onclick=\"GB_showCenter('Review of Systems/Constitutional ', '/index.php?action=soapfillanswer&qid=12&id=62',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=constitutional[]  title='constitutional"+b+"' id='q62' ></textarea></td></tr><tr><td>&nbsp;</td><td>Eyes<br/><span style='float:right;'><a onclick=\"GB_showCenter('Review of Systems/Eyes ', '/index.php?action=soapfillanswer&qid=13&id=63',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=eyes[]  title='eyes"+b+"' id='q63' ></textarea></td></tr><tr><td>&nbsp;</td><td>Ears, nose, mouth, throat<br/><span style='float:right;'><a onclick=\"GB_showCenter('Review of Systems/Ears, nose, mouth, throat', '/index.php?action=soapfillanswer&qid=14&id=64',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=ears_nose_mouth_throat[]  title='ears_nose_mouth_throat"+b+"' id='q64'></textarea></td></tr><tr><td>&nbsp;</td><td>Cardiovascular<br/><span style='float:right;'><a onclick=\"GB_showCenter('Review of Systems/Cardiovascular ', '/index.php?action=soapfillanswer&qid=15&id=65',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=cardiovascular[]  title='cardiovascular"+b+"' id='q65' ></textarea></td></tr><tr><td>&nbsp;</td><td>Respiratory<br/><span style='float:right;'><a onclick=\"GB_showCenter('Review of Systems/Respiratory ', '/index.php?action=soapfillanswer&qid=16&id=66',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=respiratory[]  title='respiratory"+b+"' id='q66'></textarea></td></tr><tr><td>&nbsp;</td><td>Gastrointestinal<br/><span style='float:right;'><a onclick=\"GB_showCenter('Review of Systems/Gastrointestinal ', '/index.php?action=soapfillanswer&qid=17&id=67',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=gastrointestinal[]  title='gastrointestinal"+b+"' id='q67'></textarea></td></tr><tr><td>&nbsp;</td><td>Genital-urinary<br/><span style='float:right;'><a onclick=\"GB_showCenter('Review of Systems/Genital-urinary', '/index.php?action=soapfillanswer&qid=18&id=68',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=genital_urinary[]   title='genital_urinary"+b+"' id='q68'></textarea></td></tr><tr><td>&nbsp;</td><td>Musculo-skeletal<br/><span style='float:right;'><a onclick=\"GB_showCenter('Review of Systems/Musculo-skeletal ', '/index.php?action=soapfillanswer&qid=19&id=69',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=musculo_skeletal[]  title='musculo_skeletal"+b+"' id='q69'></textarea></td></tr><tr><td>&nbsp;</td><td>Skin<br/><span style='float:right;'><a onclick=\"GB_showCenter('Review of Systems/Skin', '/index.php?action=soapfillanswer&qid=20&id=70',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=skin[]   title='skin"+b+"' id='q70' ></textarea></td></tr><tr><td>&nbsp;</td><td>Neurological<br/><span style='float:right;'><a onclick=\"GB_showCenter('Review of Systems/Neurological ', '/index.php?action=soapfillanswer&qid=21&id=71',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=neurological[]  title='neurological"+b+"'  id='q71'></textarea></td></tr><tr><td>&nbsp;</td><td>Psychiatric<br/><span style='float:right;'><a onclick=\"GB_showCenter('Review of Systems/Psychiatric ', '/index.php?action=soapfillanswer&qid=22&id=72',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=psychiatric[]  title='psychiatric"+b+"' id='q72'></textarea></td></tr><tr><td>&nbsp;</td><td>Endocrine<br/><span style='float:right;'><a onclick=\"GB_showCenter('Review of Systems/Endocrine', '/index.php?action=soapfillanswer&qid=23&id=73',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=endocrine[]  title='endocrine"+b+"' id='q73' ></textarea></td></tr><tr><td>&nbsp;</td><td>Hematology/lymphatic<br/><span style='float:right;'><a onclick=\"GB_showCenter('Review of Systems/Hematology/lymphatic ', '/index.php?action=soapfillanswer&qid=7&id=74',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=hematology_lymphatic[]  title='hematology_lymphatic"+b+"' id='q74' ></textarea></td></tr><tr><td>&nbsp;</td><td>Allergy/immunology<br/><span style='float:right;'><a onclick=\"GB_showCenter('Review of Systems/Allergy/immunology', '/index.php?action=soapfillanswer&qid=25&id=75',500,900);\" href='javascript:void(0);' style=\"display:<!display>;\"><img src='/images/soap_answer_btn.jpg'></a></span><textarea class='expand' name=allergy_immunology[]  title='allergy_immunology"+b+"' id='q75'></textarea></td></tr><tr><td>&nbsp;</td><td><input type='hidden' name='ccnumber[]' value='3'></td></tr></table></div>");
		}
	a++;
	if(a >1)
		$('#Button1').hide();
	if(a >2)
		$('#Button1').hide();
		
	}else{
		$('#Button1').hide();
		
	}
jQuery("textarea.expand").TextAreaExpander();
jQuery("textarea.expand").css({"width":630});
jQuery("textarea.expand_desc").TextAreaExpander();
jQuery("textarea.expand_desc").css({"width":123});
}

$(document).ready(function()
{
$(".linkCol a").css({textDecoration:"none"});
jQuery("textarea.expand").css({"width":630});
jQuery("textarea.expand_desc").TextAreaExpander();
jQuery("textarea.expand_desc").css({"width":123});
var product_count=<!prouct_count>;
if(product_count > 6){
	for(var i=6;i< product_count;i++){
		//$('#product').append("<tr><td><input name='product_description[]' type='text' maxlength='100' title='product_description"+i+"'/></td><td class='small'><input name='arising[]' type='text' maxlength='10' title='arising"+i+"'/></td><td class='small'><input name='breakfast[]' type='text'  maxlength='10' title='breakfast"+i+"'/></td><td class='small'><input name='mid_am[]' type='text'  maxlength='10' title='mid_am"+i+"'/></td><td class='small'><input name='lunch[]' type='text'  maxlength='10'  title='lunch"+i+"'/></td><td class='small'><input name='mid_pm[]' type='text'  maxlength='10'  title='mid_pm"+i+"'/></td><td class='small'><input name='dinner[]' type='text'  maxlength='10' title='dinner"+i+"'/></td><td class='small'><input name='before_bed[]' type='text'  maxlength='10' title='before_bed"+i+"'/></td><td><input name='comments[]' type='text'  maxlength='100' title='comments"+i+"'/></td></tr>");
		$('#product').append("<tr><td><textarea name='product_description[]' class='expand_desc' title='product_description"+i+"' style='width: 123px; height: 18px;'></textarea></td><td class='small'><input name='arising[]' type='text' maxlength='10' title='arising"+i+"'/></td><td class='small'><input name='breakfast[]' type='text'  maxlength='10' title='breakfast"+i+"'/></td><td class='small'><input name='mid_am[]' type='text'  maxlength='10' title='mid_am"+i+"'/></td><td class='small'><input name='lunch[]' type='text'  maxlength='10'  title='lunch"+i+"'/></td><td class='small'><input name='mid_pm[]' type='text'  maxlength='10'  title='mid_pm"+i+"'/></td><td class='small'><input name='dinner[]' type='text'  maxlength='10' title='dinner"+i+"'/></td><td class='small'><input name='before_bed[]' type='text'  maxlength='10' title='before_bed"+i+"'/></td><td><textarea name='comments[]' class='expand_desc' maxlength='100' title='comments"+i+"' style='width: 123px; height: 18px;'></textarea></td></tr>");
        }
}



var data1={<!stateJavascript>};
//alert(data1);
if((data1['chief_complaint1']) || (data1['location1']) || (data1['onset1']) || (data1['provocation1']) || (data1['palliation1']) || (data1['quality1']) || (data1['radiation1']) || (data1['severity1']) || (data1['duration1']) || (data1['timing1']) || (data1['associated_signs_symptoms1']) || (data1['constitutional1']) || (data1['eyes1']) || (data1['ears_nose_mouth_throat1']) || (data1['cardiovascular1']) || (data1['respiratory1']) || (data1['gastrointestinal1']) || (data1['genital_urinary1']) || (data1['musculo_skeletal1']) || (data1['skin1']) || (data1['neurological1']) || (data1['psychiatric1']) || (data1['endocrine1']) || (data1['hematology_lymphatic1']) || (data1['allergy_immunology1']))
	addData();
if((data1['chief_complaint2']) || (data1['location2']) || (data1['onset2']) || (data1['provocation2']) || (data1['palliation2']) || (data1['quality2']) || (data1['radiation2']) || (data1['severity2']) || (data1['duration2']) || (data1['timing2']) || (data1['associated_signs_symptoms2']) || (data1['constitutional2']) || (data1['eyes2']) || (data1['ears_nose_mouth_throat2']) || (data1['cardiovascular2']) || (data1['respiratory2']) || (data1['gastrointestinal2']) || (data1['genital_urinary2']) || (data1['musculo_skeletal2']) || (data1['skin2']) || (data1['neurological2']) || (data1['psychiatric2']) || (data1['endocrine2']) || (data1['hematology_lymphatic2']) || (data1['allergy_immunology2']))
	addData();

var ObjElement=$("textarea");
var cmn=$("table.soap-note-prog tr td input");
		$(cmn).each(function()
	    {
	    	//alert(data1[objem]);
	      var objem=$(this).attr("title");
	      if(data1[objem]!=''){
	    	  if(data1[objem]!=undefined){
	      		$(this).val(URLDecode(data1[objem]));
	    	  }
	      }
	      $(this).attr('title','');
			 });

		$(ObjElement).each(function()
	    {
	      var objem=$(this).attr("title");
	     // alert(data1[objem]);
	      if(data1[objem]!=''){
	    	  if(data1[objem]!=undefined){
	      		$(this).val(URLDecode(data1[objem]));
	    	  }
	      }
	      $(this).attr('title','');
//alert(objem);
				
		 });
});
function saveme(){
	var ObjElement=$("textarea");
	var flag='blank';
	 $(ObjElement).each(function()
			    {
			      var objem=$(this).val();
					if(objem!=''){
						flag='data';
						
					}	
			    });
	 var cmn=$("table.soap-note-prog tr td input");
		$(cmn).each(function()
	    {
			var objem=$(this).val();
			if(objem!=''){
				flag='data';
			}
	    });
	    
	 if(flag=='blank'){
		 	alert("You are about to save this SOAP without fill any section. Please fill any section");
			return false;
		}
}

function addproduct()
{
    coun=$("#count").val();
    if(coun < 6 )
    coun = 6;
    coun=eval(Number(coun)+1);
    $("#count").val(coun);
    if(coun>20){
        return false;
    }
   
	//$('#product').append("<tr><td><input name='product_description[]' type='text' maxlength='100' /></td><td class='small'><input name='arising[]' type='text' maxlength='10'/></td><td class='small'><input name='breakfast[]' type='text'  maxlength='10'/></td><td class='small'><input name='mid_am[]' type='text'  maxlength='10'/></td><td class='small'><input name='lunch[]' type='text'  maxlength='10'/></td><td class='small'><input name='mid_pm[]' type='text'  maxlength='10'/></td><td class='small'><input name='dinner[]' type='text'  maxlength='10'/></td>	<td class='small'><input name='before_bed[]' type='text'  maxlength='10'/></td>	<td><input name='comments[]' type='text'  maxlength='100'/></td></tr>");
    $('#product').append("<tr><td><textarea name='product_description[]' class='expand_desc' style='width: 123px; height: 18px;'></textarea></td><td class='small'><input name='arising[]' type='text' maxlength='10'/></td><td class='small'><input name='breakfast[]' type='text'  maxlength='10'/></td><td class='small'><input name='mid_am[]' type='text'  maxlength='10'/></td><td class='small'><input name='lunch[]' type='text'  maxlength='10'/></td><td class='small'><input name='mid_pm[]' type='text'  maxlength='10'/></td><td class='small'><input name='dinner[]' type='text'  maxlength='10'/></td>	<td class='small'><input name='before_bed[]' type='text'  maxlength='10'/></td>	<td><textarea name='comments[]' class='expand_desc' maxlength='100' style='width: 123px; height: 18px;'></textarea></td></tr>");
    jQuery("textarea.expand_desc").TextAreaExpander();
    jQuery("textarea.expand_desc").css({"width":123});
    
}
/********This code modified by pawan khandelwal on 28-jun-2013**********/
function copyForward(patient_id){
     var allElements = new Array();
     $("table.soap-note-prog tr td input").each(function()
     {
          var objem=$(this).attr('id');
          allElements.push($(this).val());
     });
     var count = 0;
     
      if(allElements.length != 0)
      {
          r=confirm("If you click on ok, you will lost all previous data?");
          if(r==false)
          {
            $('#copyprogram').attr("disabled", true);
            return false;
          }
          else
          {
            $("table.soap-note-prog tr").each(function()
             {
                  count=count+1;
                  if(count>7)
                  $(this).remove();
             });
          }
          
      }
shopnoteid=jQuery('#shopnoteid').val();	 
$.post('index.php?action=copyforward_soap',{patient_id:patient_id,status:1,soapnotedraft:1,shopnoteid:shopnoteid}, function(data,status){
	   if(data != 'fail')
       {
           // alert(data); 
            var json = JSON.stringify(eval("(" + data + ")"));
           	 var data1 = JSON.parse(json);
                 
             if( status == "success" ){
                    var product_count=data1['prouct_count'];
                    if(product_count > 6){
           	            for(var i=6;i< product_count;i++){
                		  //$('#product').append("<tr><td><input name='product_description[]' type='text' maxlength='100' title='product_description"+i+"'/></td><td class='small'><input name='arising[]' type='text' maxlength='10' title='arising"+i+"'/></td><td class='small'><input name='breakfast[]' type='text'  maxlength='10' title='breakfast"+i+"'/></td><td class='small'><input name='mid_am[]' type='text'  maxlength='10' title='mid_am"+i+"'/></td><td class='small'><input name='lunch[]' type='text'  maxlength='10'  title='lunch"+i+"'/></td><td class='small'><input name='mid_pm[]' type='text'  maxlength='10'  title='mid_pm"+i+"'/></td><td class='small'><input name='dinner[]' type='text'  maxlength='10' title='dinner"+i+"'/></td><td class='small'><input name='before_bed[]' type='text'  maxlength='10' title='before_bed"+i+"'/></td><td><input name='comments[]' type='text'  maxlength='100' title='comments"+i+"'/></td></tr>");
                            $('#product').append("<tr><td><textarea name='product_description[]' class='expand_desc' title='product_description"+i+"' id='product_description"+i+"' style='width: 123px; height: 18px;'></textarea></td><td class='small'><input name='arising[]' type='text' maxlength='10' title='arising"+i+"' id='arising"+i+"' /></td><td class='small'><input name='breakfast[]' type='text'  maxlength='10' title='breakfast"+i+"' id='breakfast"+i+"' /></td><td class='small'><input name='mid_am[]' type='text'  maxlength='10' title='mid_am"+i+"' id='mid_am"+i+"' /></td><td class='small'><input name='lunch[]' type='text'  maxlength='10'  title='lunch"+i+"' id='lunch"+i+"' /></td><td class='small'><input name='mid_pm[]' type='text'  maxlength='10'  title='mid_pm"+i+"' id='mid_pm"+i+"' /></td><td class='small'><input name='dinner[]' type='text'  maxlength='10' title='dinner"+i+"' id='dinner"+i+"' /></td><td class='small'><input name='before_bed[]' type='text'  maxlength='10' title='before_bed"+i+"' id='before_bed"+i+"' /></td><td><textarea name='comments[]' class='expand_desc' maxlength='100' title='comments"+i+"' id='comments"+i+"' style='width: 123px; height: 18px;'></textarea></td></tr>");
                            $("#count").val(product_count);
                        }
        		    }else{
 $("#count").val(6);
}
            	  $(".linkCol a").css({textDecoration:"none"});
            	 jQuery("textarea.expand").css({"width":630});
                 jQuery("textarea.expand_desc").TextAreaExpander();
    	           jQuery("textarea.expand_desc").css({"width":123});
    				var ObjElement=$("textarea");
                    $("#product tr td textarea").each(function()
    			      {
		                  $(this).val('');
    			      }); 
    		    	//	var cmn=$("table.soap-note-prog tr td input");
    				$("#product tr td input").each(function()
    			    {
    				      var objem=$(this).attr('id');
                         $(this).val('');
    			      if(data1[objem]!=''){
    			    	  if(data1[objem]!=undefined){
    			      		$(this).val(URLDecode(data1[objem]));
    			    	  }
    			      }else{
                                   $(this).val('');
                              }
    					 });
    
    				$(ObjElement).each(function()
    			    {
    			      var objem=$(this).attr("id");
                            
    			      if(data1[objem]!=''){
    			    	  if(data1[objem]!=undefined){
    			      		$(this).val(URLDecode(data1[objem]));
    			    	  }
    			      }else{
                                  $(this).val('');
                              }
    			      $(this).attr('title','');		
    				 });
    			
             }
        }
        else{
            
        }
        $('#copyprogram').attr("disabled", true);
	 }
     );
}
/****This code modified by pawan khandelwal on 28-jun-2013 ends here****/ 
</script>


	<div id="mainContent">

		<table style="vertical-align: middle; width: 700px;">
			<tr>
				<td style="width: 400px;">
					<div id="breadcrumbNav"
						style="padding-bottom: 40px; padding-top: 40px;">
						<a href="index.php">HOME</a> / <a
							href="index.php?action=therapistViewPatient&id=<!id>">VIEW
							<!patientname></a> / <span class="highlight"> EDIT SOAP NOTE</span>
					</div>
				</td>
				<td style="width: 300px;"></td>
			</tr>
		</table>

		<form action="/index.php" name="new_shopnote" method="post"
			onsubmit="javascript:return saveme();">
			<input type="hidden" name="id" value="<!id>"> <input type="hidden"
				name="shopnoteid" id ="shopnoteid" value="<!shopnoteid>"> <input type="hidden"
				name="action" value="provider_submit_soap_note"> <input
				type="hidden" name="status" id="status" value="">
			<ul class="newtab">

				<li style="float: right; background: none;"><input type="image"
					src="/images/save-finish.gif" onclick="javascript:setstatus('1');" />
				</li>
				<li style="float: right; background: none;"><input type="image"
					src="/images/btn_saveAsDraft.gif"
					onclick="javascript:setstatus('0');" /></li>
				<li class="current"><a href="javascript:;"
					onclick="javascript:tabs('subjective',this);"><span
						class="textblue">Subjective</span> </a></li>
				<li><a href="javascript:void(0);"
					onclick="javascript:tabs('objective',this);"><span>Objective</span>
				</a></li>
				<li><a href="javascript:void(0);"
					onclick="javascript:tabs('assessment',this);"><span>Assessment</span>
				</a></li>
				<li><a href="javascript:void(0);"
					onclick="javascript:tabs('program',this);"><span>Program</span> </a>
				</li>
			</ul>
			<div style="clear: both;"></div>
			<div style="border: #ccc 1px solid; padding: 0px;">
				<div
					style="background: url(/images/img-topnav-repeat.gif) top left repeat-x; height: 30px;"></div>
				<div id="subjective" class="mydiv">
					<table cellpadding="5" cellspacing="0" style="width: 680px;"
						class="soap-note-subj" align="center">
						<tr>
							<td>&nbsp;</td>
							<td>Chief Complaint<br /> <span style="float: right;"><a
									onclick="GB_showCenter('Chief Complaint ', '/index.php?action=soapfillanswer&qid=1&id=1',500,900);"
									href="javascript:void(0);" style="display:<!display>;"><img
										src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
									class="expand" id="q1" name="chief_complaint[]"
									title="chief_complaint"></textarea>
							</td>
						</tr>
					</table>

					<table cellpadding="5" cellspacing="0" style="width: 680px;"
						class="soap-note-subj">
						<tr>
							<td style="width: 12px; font-size: 16px; color: #00699d;"
								class="linkCol" valign="top" align="right"><a
								href="javascript:;" onclick="treeView('history',this);">+</a>
							</td>
							<td class="heading">History of Present Illness</td>
						</tr>
					</table>
					<div id="history" style="display: none;">
						<table cellpadding="5" cellspacing="0" style="width: 680px;"
							class="soap-note-subj" align="center">
							<tr>
								<td>&nbsp;</td>
								<td>Location<br /> <span style="float: right;"><a
										onclick="GB_showCenter('History of Present Illness/Location', '/index.php?action=soapfillanswer&qid=2&id=2',500,900);"
										href="javascript:void(0);" style="display:<!display>;"><img
											src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
										class="expand" name="location[]" title="location" id="q2"></textarea>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>Onset<br /> <span style="float: right;"><a
										onclick="GB_showCenter('History of Present Illness/Onset', '/index.php?action=soapfillanswer&qid=3&id=3',500,900);"
										href="javascript:void(0);" style="display:<!display>;"><img
											src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
										class="expand" name="onset[]" id="q3" title="onset"></textarea>
								</td>

							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>Provocation<br /> <span style="float: right;"><a
										onclick="GB_showCenter('History of Present Illness/Provocation ', '/index.php?action=soapfillanswer&qid=4&id=4',500,900);"
										href="javascript:void(0);" style="display:<!display>;"><img
											src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
										class="expand" name="provocation[]" id="q4"
										title="provocation"></textarea>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>Palliation<br /> <span style="float: right;"><a
										onclick="GB_showCenter('History of Present Illness/Palliation', '/index.php?action=soapfillanswer&qid=5&id=5',500,900);"
										href="javascript:void(0);" style="display:<!display>;"><img
											src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
										class="expand" name="palliation[]" id="q5" title="palliation"></textarea>
								</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
								<td>Quality<br /> <span style="float: right;"><a
										onclick="GB_showCenter('History of Present Illness/Quality', '/index.php?action=soapfillanswer&qid=6&id=6',500,900);"
										href="javascript:void(0);" style="display:<!display>;"><img
											src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
										class="expand" name="quality[]" id="q6" title="quality"></textarea>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>Radiation<br /> <span style="float: right;"><a
										onclick="GB_showCenter('History of Present Illness/Radiation', '/index.php?action=soapfillanswer&qid=7&id=7',500,900);"
										href="javascript:void(0);" style="display:<!display>;"><img
											src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
										class="expand" name="radiation[]" id="q7" title="radiation"></textarea>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>

								<td>Severity<br /> <span style="float: right;"><a
										onclick="GB_showCenter('History of Present Illness/Severity', '/index.php?action=soapfillanswer&qid=8&id=8',500,900);"
										href="javascript:void(0);" style="display:<!display>;"><img
											src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
										class="expand" name="severity[]" id="q8" title="severity"></textarea>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>Duration<br /> <span style="float: right;"><a
										onclick="GB_showCenter('History of Present Illness/Duration', '/index.php?action=soapfillanswer&qid=9&id=9',500,900);"
										href="javascript:void(0);" style="display:<!display>;"><img
											src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
										class="expand" name="duration[]" id="q9" title="duration"></textarea>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>Timing<br /> <span style="float: right;"><a
										onclick="GB_showCenter('History of Present Illness/Timing', '/index.php?action=soapfillanswer&qid=10&id=10',500,900);"
										href="javascript:void(0);" style="display:<!display>;"><img
											src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
										class="expand" name="timing[]" id="q10" title="timing"></textarea>
								</td>

							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>Associated signs/symptoms<br /> <span style="float: right;"><a
										onclick="GB_showCenter('History of Present Illness/Associated signs/symptoms', '/index.php?action=soapfillanswer&qid=11&id=11',500,900);"
										href="javascript:void(0);" style="display:<!display>;"><img
											src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
										class="expand" name="associated_signs_symptoms[]" id="q11"
										title="associated_signs_symptoms"></textarea><input
									type="hidden" name="ccnumber[]" value="1">
								</td>
							</tr>
						</table>
					</div>
					<table cellpadding="5" cellspacing="0" style="width: 680px;"
						class="soap-note-subj">
						<tr>
							<td style="width: 12px; font-size: 16px; color: #00699d;"
								class="linkCol" align="right"><a href="javascript:;"
								onclick="treeView('review',this);">+</a>
							</td>
							<td class="heading">Review of Systems</td>
						</tr>
					</table>
					<div id="review" style="display: none;">
						<table cellpadding="5" cellspacing="0" style="width: 680px;"
							class="soap-note-subj" align="center">
							<tr>
								<td>&nbsp;</td>

								<td>Constitutional <br /> <span style="float: right;"><a
										onclick="GB_showCenter('Review of Systems/Constitutional ', '/index.php?action=soapfillanswer&qid=12&id=12',500,900);"
										href="javascript:void(0);" style="display:<!display>;"><img
											src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
										class="expand" name="constitutional[]" id="q12"
										title="constitutional"></textarea>
								</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
								<td>Eyes<br /> <span style="float: right;"><a
										onclick="GB_showCenter('Review of Systems/Eyes ', '/index.php?action=soapfillanswer&qid=13&id=13',500,900);"
										href="javascript:void(0);" style="display:<!display>;"><img
											src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
										class="expand" name="eyes[]" id="q13" title="eyes"></textarea>
								</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
								<td>Ears, nose, mouth, throat<br /> <span style="float: right;"><a
										onclick="GB_showCenter('Review of Systems/Ears, nose, mouth, throat ', '/index.php?action=soapfillanswer&qid=14&id=14',500,900);"
										href="javascript:void(0);" style="display:<!display>;"><img
											src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
										class="expand" name="ears_nose_mouth_throat[]" id="q14"
										title="ears_nose_mouth_throat"></textarea>
								</td>

							</tr>

							<tr>
								<td>&nbsp;</td>
								<td>Cardiovascular<br /> <span style="float: right;"><a
										onclick="GB_showCenter('Review of Systems/Cardiovascular ', '/index.php?action=soapfillanswer&qid=15&id=15',500,900);"
										href="javascript:void(0);" style="display:<!display>;"><img
											src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
										class="expand" name="cardiovascular[]" id="q15"
										title="cardiovascular"></textarea>
								</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
								<td>Respiratory<br /> <span style="float: right;"><a
										onclick="GB_showCenter('Review of Systems/Respiratory ', '/index.php?action=soapfillanswer&qid=16&id=16',500,900);"
										href="javascript:void(0);" style="display:<!display>;"><img
											src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
										class="expand" name="respiratory[]" id="q16"
										title="respiratory"></textarea>
								</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
								<td>Gastrointestinal<br /> <span style="float: right;"><a
										onclick="GB_showCenter('Review of Systems/Gastrointestinal ', '/index.php?action=soapfillanswer&qid=17&id=17',500,900);"
										href="javascript:void(0);" style="display:<!display>;"><img
											src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
										class="expand" name="gastrointestinal[]" id="q17"
										title="gastrointestinal"></textarea>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>Genital-urinary<br /> <span style="float: right;"><a
										onclick="GB_showCenter('Review of Systems/Genital-urinary ', '/index.php?action=soapfillanswer&qid=18&id=18',500,900);"
										href="javascript:void(0);" style="display:<!display>;"><img
											src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
										class="expand" name="genital_urinary[]" id="q18"
										title="genital_urinary"></textarea>
								</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
								<td>Musculo-skeletal<br /> <span style="float: right;"><a
										onclick="GB_showCenter('Review of Systems/Musculo-skeletal ', '/index.php?action=soapfillanswer&qid=19&id=19',500,900);"
										href="javascript:void(0);" style="display:<!display>;"><img
											src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
										class="expand" name="musculo_skeletal[]" id="q19"
										title="musculo_skeletal"></textarea>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>Skin<br /> <span style="float: right;"><a
										onclick="GB_showCenter('Review of Systems/Skin ', '/index.php?action=soapfillanswer&qid=20&id=20',500,900);"
										href="javascript:void(0);" style="display:<!display>;"><img
											src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
										class="expand" name="skin[]" id="q20" title="skin"></textarea>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>Neurological<br /> <span style="float: right;"><a
										onclick="GB_showCenter('Review of Systems/Neurological ', '/index.php?action=soapfillanswer&qid=21&id=21',500,900);"
										href="javascript:void(0);" style="display:<!display>;"><img
											src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
										class="expand" name="neurological[]" id="q21"
										title="neurological"></textarea>
								</td>

							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>Psychiatric<br /> <span style="float: right;"><a
										onclick="GB_showCenter('Review of Systems/Psychiatric ', '/index.php?action=soapfillanswer&qid=22&id=22',500,900);"
										href="javascript:void(0);" style="display:<!display>;"><img
											src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
										class="expand" name="psychiatric[]" id="q22"
										title="psychiatric"></textarea>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>Endocrine<br /> <span style="float: right;"><a
										onclick="GB_showCenter('Review of Systems/Endocrine ', '/index.php?action=soapfillanswer&qid=23&id=23',500,900);"
										href="javascript:void(0);" style="display:<!display>;"><img
											src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
										class="expand" name="endocrine[]" id="q23" title="endocrine"></textarea>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>

								<td>Hematology/lymphatic<br /> <span style="float: right;"><a
										onclick="GB_showCenter('Review of Systems/Hematology/lymphatic ', '/index.php?action=soapfillanswer&qid=24&id=24',500,900);"
										href="javascript:void(0);" style="display:<!display>;"><img
											src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
										class="expand" name="hematology_lymphatic[]" id="q24"
										title="hematology_lymphatic"></textarea>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>Allergy/immunology<br /> <span style="float: right;"><a
										onclick="GB_showCenter('Review of Systems/Allergy/immunology ', '/index.php?action=soapfillanswer&qid=25&id=25',500,900);"
										href="javascript:void(0);" style="display:<!display>;"><img
											src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
										class="expand" name="allergy_immunology[]" id="q25"
										title="allergy_immunology"></textarea>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
						</table>
					</div>
					<div id="addy"></div>
					<table cellpadding="5" cellspacing="0" style="width: 680px;"
						class="soap-note-subj" align="center">
						<tr>
							<td>&nbsp;</td>
							<td align="right"><input name="Button1" id="Button1"
								type="button" onclick="addData();" value="Add Chief Complaint" />
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					</table>
				</div>
				<div id="objective" style="display: none;" class="mydiv">
					<table cellpadding="5" cellspacing="0" style="width: 666px;"
						class="soap-note-obj" align="center">
						<tr>
							<td>Vitals<br /> <span style="float: right;"><a
									onclick="GB_showCenter('Vitals ', '/index.php?action=soapfillanswer&qid=26&id=76',500,900);"
									href="javascript:void(0);" style="display:<!display>;"><img
										src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
									class="expand" name="ob_vitals" id="q76" title="ob_vitals"></textarea>
							</td>

						</tr>
						<tr>
							<td>Constitutional <br /> <span style="float: right;"><a
									onclick="GB_showCenter('Constitutional ', '/index.php?action=soapfillanswer&qid=27&id=77',500,900);"
									href="javascript:void(0);" style="display:<!display>;"><img
										src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
									class="expand" name="ob_constitutional" id="q77"
									title="ob_constitutional"></textarea>
							</td>
						</tr>
						<tr>
							<td>Eyes<br /> <span style="float: right;"><a
									onclick="GB_showCenter('Eyes ', '/index.php?action=soapfillanswer&qid=28&id=78',500,900);"
									href="javascript:void(0);" style="display:<!display>;"><img
										src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
									class="expand" name="ob_eyes" id="q78" title="ob_eyes"></textarea>
							</td>
						</tr>

						<tr>
							<td>Ears, nose, mouth, throat<br /> <span style="float: right;"><a
									onclick="GB_showCenter('Ears, nose, mouth, throat ', '/index.php?action=soapfillanswer&qid=29&id=79',500,900);"
									href="javascript:void(0);" style="display:<!display>;"><img
										src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
									class="expand" name="ob_ears_nose_mouth_throat" id="q79"
									title="ob_ears_nose_mouth_throat"></textarea>
							</td>
						</tr>
						<tr>
							<td>Cardiovascular<br /> <span style="float: right;"><a
									onclick="GB_showCenter('Cardiovascular ', '/index.php?action=soapfillanswer&qid=30&id=80',500,900);"
									href="javascript:void(0);" style="display:<!display>;"><img
										src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
									class="expand" name="ob_cardiovascular" id="q80"
									title="ob_cardiovascular"></textarea>
							</td>
						</tr>
						<tr>

							<td>Respiratory<br /> <span style="float: right;"><a
									onclick="GB_showCenter('Respiratory ', '/index.php?action=soapfillanswer&qid=31&id=81',500,900);"
									href="javascript:void(0);" style="display:<!display>;"><img
										src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
									class="expand" name="ob_respiratory" id="q81"
									title="ob_respiratory"></textarea>
							</td>
						</tr>
						<tr>
							<td>Gastrointestinal<br /> <span style="float: right;"><a
									onclick="GB_showCenter('Gastrointestinal ', '/index.php?action=soapfillanswer&qid=32&id=82',500,900);"
									href="javascript:void(0);" style="display:<!display>;"><img
										src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
									class="expand" name="ob_gastrointestinal" id="q82"
									title="ob_gastrointestinal"></textarea>
							</td>
						</tr>
						<tr>
							<td>Genital-urinary<br /> <span style="float: right;"><a
									onclick="GB_showCenter('Genital-urinary ', '/index.php?action=soapfillanswer&qid=33&id=83',500,900);"
									href="javascript:void(0);" style="display:<!display>;"><img
										src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
									class="expand" name="ob_genital_urinary" id="q83"
									title="ob_genital_urinary"></textarea>
							</td>

						</tr>
						<tr>
							<td>Musculo-skeletal<br /> <span style="float: right;"><a
									onclick="GB_showCenter('Musculo-skeletal ', '/index.php?action=soapfillanswer&qid=34&id=84',500,900);"
									href="javascript:void(0);" style="display:<!display>;"><img
										src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
									class="expand" name="ob_musculo_skeletal" id="q84"
									title="ob_musculo_skeletal"></textarea>
							</td>
						</tr>
						<tr>
							<td>Skin<br /> <span style="float: right;"><a
									onclick="GB_showCenter('Skin ', '/index.php?action=soapfillanswer&qid=35&id=85',500,900);"
									href="javascript:void(0);" style="display:<!display>;"><img
										src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
									class="expand" name="ob_skin" id="q85" title="ob_skin"></textarea>
							</td>
						</tr>

						<tr>
							<td>Neurological<br /> <span style="float: right;"><a
									onclick="GB_showCenter('Neurological ', '/index.php?action=soapfillanswer&qid=36&id=86',500,900);"
									href="javascript:void(0);" style="display:<!display>;"><img
										src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
									class="expand" name="ob_neurological" id="q86"
									title="ob_neurological"></textarea>
							</td>
						</tr>

						<tr>
							<td>Psychiatric<br /> <span style="float: right;"><a
									onclick="GB_showCenter('Psychiatric ', '/index.php?action=soapfillanswer&qid=37&id=87',500,900);"
									href="javascript:void(0);" style="display:<!display>;"><img
										src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
									class="expand" name="ob_psychiatric" id="q87"
									title="ob_psychiatric"></textarea>
							</td>
						</tr>

						<tr>
							<td>Endocrine<br /> <span style="float: right;"><a
									onclick="GB_showCenter('Endocrine ', '/index.php?action=soapfillanswer&qid=38&id=88',500,900);"
									href="javascript:void(0);" style="display:<!display>;"><img
										src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
									class="expand" name="ob_endocrine" id="q88"
									title="ob_endocrine"></textarea>
							</td>
						</tr>

						<tr>
							<td>Hematology/ lymphatic<br /> <span style="float: right;"><a
									onclick="GB_showCenter('Hematology/ lymphatic ', '/index.php?action=soapfillanswer&qid=39&id=89',500,900);"
									href="javascript:void(0);" style="display:<!display>;"><img
										src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
									class="expand" name="ob_hematology_lymphatic" id="q89"
									title="ob_hematology_lymphatic"></textarea>
							</td>
						</tr>

						<tr>

							<td>Allergy/ immunology<br /> <span style="float: right;"><a
									onclick="GB_showCenter('Allergy/ immunology ', '/index.php?action=soapfillanswer&qid=40&id=90',500,900);"
									href="javascript:void(0);" style="display:<!display>;"><img
										src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
									class="expand" name="ob_allergy_immunology" id="q90"
									title="ob_allergy_immunology"></textarea>
							</td>
						</tr>

						<tr>
							<td>EAV<br /> <span style="float: right;"><a
									onclick="GB_showCenter('EAV ', '/index.php?action=soapfillanswer&qid=41&id=91',500,900);"
									href="javascript:void(0);" style="display:<!display>;"><img
										src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
									class="expand" name="ob_eav" id="q91" title="ob_eav"></textarea>
							</td>
						</tr>

						<tr>
							<td>Laboratory<br /> <span style="float: right;"><a
									onclick="GB_showCenter('Laboratory ', '/index.php?action=soapfillanswer&qid=42&id=92',500,900);"
									href="javascript:void(0);" style="display:<!display>;"><img
										src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
									class="expand" name="ob_laboratory" id="q92"
									title="ob_laboratory"></textarea>
							</td>

						</tr>

						<tr>
							<td>&nbsp;</td>
						</tr>


					</table>
				</div>
				<div id="assessment" style="display: none;" class="mydiv">
					<table cellpadding="5" cellspacing="0" style="width: 680px;"
						class="soap-note-ass" align="center">
						<tr>
							<td>DX:</br> <span style="float: right;"><a
									onclick="GB_showCenter('DX ', '/index.php?action=soapfillanswer&qid=43&id=93',500,900);"
									href="javascript:void(0);" style="display:<!display>;"><img
										src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
									class="expand" name="dx" id="q93" title="dx"></textarea>
							</td>

						</tr>


						<tr>
							<td>DDX:</br> <span style="float: right;"><a
									onclick="GB_showCenter('DDX ', '/index.php?action=soapfillanswer&qid=44&id=94',500,900);"
									href="javascript:void(0);" style="display:<!display>;"><img
										src="/images/soap_answer_btn.jpg"> </a> </span> <textarea
									class="expand" name="ddx" id="q94" title="ddx"></textarea>
							</td>
						</tr>


						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>


					</table>
				</div>
				<div id="program" style="display: none;" class="mydiv">
					<table cellpadding="0" cellspacing="0" style="width: 680px;"
						align="center">
						<tr>
							<td><b class="soap-note-prog-b-h"><!clinicname>
							
							</b> <b class="soap-note-prog-g-h">RECOMMENDED PATIENT PROTOCOL /
									TREATMENT PROGRAM</b>


								<table cellpadding="5" cellspacing="0" style="width: 100%">
									<tr>
										<td style="width: 35px;"><strong>Patient:</strong></td>
										<td style="width: 200px;"><!patientname>
										
										</td>

										<td align="right"><strong>Doctor:</strong></td>
										<td><!providername>
										
										</td>

										<td align="right"><strong>Date:</strong></td>
										<td><!currentdate>
										
										</td>
									</tr>

								</table>
								<p style="font-family: Verdana;">The following products and/or
									protocols have been specifically chosen for their
									effectiveness, quality and potency, so it is of tantamount
									importance that you follow the instructions exactly. Any
									deviation from the specific recommendations may influence the
									success of your treatment program. If you have any questions
									please let us know immediately.</p> <b
								class="soap-note-prog-b-s">PLEASE BRING THIS SHEET IN WITH YOU
									WHEN PICKING UP REFILLS.</b>
								<table cellpadding="0" cellspacing="0" style="width: 100%;margin-bottom: 5px;"
									class="soap-note-prog-bottom">
                                     <tr>
                            			<td align="right"><input type="checkbox" value="1" onclick="javascipt: copyForward(<!id>);" id="copyprogram" />Copy Forward</td>
                            		</tr>
									<tr>
										<td>Dietary Instructions:<br /> <textarea class="expand"
												name="dietary_instructions" title="dietary_instructions" id="dietary_instructions"></textarea>
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
									</tr>
								</table>

								<table cellpadding="6" cellspacing="0" style="width: 100%"
									class="soap-note-prog" id="product">
									<tr>
										<td valign="bottom"><b>Product Description</b></td>
										<td><img height="44" src="/images/img-arising.jpg" width="12" />
										</td>
										<td><img src="/images/img-break-fast.jpg" /></td>
										<td><img src="/images/img-mid-am.jpg" /></td>
										<td><img src="/images/img-lunch.jpg" /></td>

										<td><img src="/images/img-mid-pm.jpg" /></td>
										<td><img src="/images/img-dinner.jpg" /></td>
										<td><img src="/images/img-before-bed.jpg" /></td>
										<td valign="bottom"><b>Comments</b></td>
									</tr>
									<tr>
										<td><textarea name="product_description[]" class="expand_desc" title="product_description0" id="product_description0" style="width: 123px; height: 18px;"></textarea></td>
                                            <!--<input name="product_description[]" type="text"
											maxlength="100" title="product_description0" /></td>-->
										<td class="small"><input name="arising[]" type="text" maxlength="10" title="arising0" id="arising0" /></td>
										<td class="small"><input name="breakfast[]" type="text" maxlength="10" title="breakfast0" id="breakfast0" /></td>
										<td class="small"><input name="mid_am[]" type="text" maxlength="10" title="mid_am0" id="mid_am0" /></td>
										<td class="small"><input name="lunch[]" type="text" maxlength="10" title="lunch0" id="lunch0" /></td>
										<td class="small"><input name="mid_pm[]" type="text" maxlength="10" title="mid_pm0" id="mid_pm0"/></td>
										<td class="small"><input name="dinner[]" type="text" maxlength="10" title="dinner0" id="dinner0" /></td>
										<td class="small"><input name="before_bed[]" type="text" maxlength="10" title="before_bed0" id="before_bed0" /></td>
										<td><textarea name="comments[]" class="expand_desc" maxlength="100" title="comments0" id="comments0" style="width: 123px; height: 18px;"></textarea></td>
                                            <!--<input name="comments[]" type="text" maxlength="100"
											title="comments0" /></td>-->
									</tr>
									<tr>
										<td><textarea name="product_description[]" class="expand_desc" title="product_description1" id="product_description1" style="width: 123px; height: 18px;"></textarea></td>
                                        <!--<input name="product_description[]" type="text"
											maxlength="100" title="product_description1" /></td>-->
										
										<td class="small"><input name="arising[]" type="text" maxlength="10" title="arising1" id="arising1" /></td>
										<td class="small"><input name="breakfast[]" type="text" maxlength="10" title="breakfast1" id="breakfast1" /></td>
										<td class="small"><input name="mid_am[]" type="text" maxlength="10" title="mid_am1" id="mid_am1" /></td>
										<td class="small"><input name="lunch[]" type="text" maxlength="10" title="lunch1" id="lunch1" /></td>
										<td class="small"><input name="mid_pm[]" type="text" maxlength="10" title="mid_pm1" id="mid_pm1" /></td>
										<td class="small"><input name="dinner[]" type="text" maxlength="10" title="dinner1" id="dinner1" /></td>
										<td class="small"><input name="before_bed[]" type="text" maxlength="10" title="before_bed1" id="before_bed1" /></td>
										<td><textarea name="comments[]" class="expand_desc" maxlength="100" title="comments1" id="comments1" style="width: 123px; height: 18px;"></textarea></td>
                                            <!--<input name="comments[]" type="text" maxlength="100"
											title="comments1" /></td>-->
									</tr>
									<tr>
										<td><textarea name="product_description[]" class="expand_desc" title="product_description2" id="product_description2" style="width: 123px; height: 18px;"></textarea></td>
                                        <!--<input name="product_description[]" type="text"
											maxlength="100" title="product_description2" /></td>-->
										<td class="small"><input name="arising[]" type="text" maxlength="10" title="arising2" id="arising2"/></td>
										<td class="small"><input name="breakfast[]" type="text" maxlength="10" title="breakfast2" id="breakfast2" /></td>
										<td class="small"><input name="mid_am[]" type="text" maxlength="10" title="mid_am2" id="mid_am2" /></td>
										<td class="small"><input name="lunch[]" type="text" maxlength="10" title="lunch2"  id="lunch2" /></td>
										<td class="small"><input name="mid_pm[]" type="text" maxlength="10" title="mid_pm2" id="mid_pm2" /></td>
										<td class="small"><input name="dinner[]" type="text" maxlength="10" title="dinner2" id="dinner2" /></td>
										<td class="small"><input name="before_bed[]" type="text" maxlength="10" title="before_bed2" id="before_bed2" /></td>
										<td><textarea name="comments[]" class="expand_desc" maxlength="100" title="comments2" id="comments2" style="width: 123px; height: 18px;"></textarea></td>
                                            <!--<input name="comments[]" type="text" maxlength="100"
											title="comments2" /></td>-->

									</tr>

									<tr>
										<td><textarea name="product_description[]" class="expand_desc" title="product_description3" id="product_description3" style="width: 123px; height: 18px;"></textarea></td>
                                        <!--<input name="product_description[]" type="text"
											maxlength="100" title="product_description3" /></td>-->
										
										<td class="small"><input name="arising[]" type="text" maxlength="10" title="arising3" id="arising3" /></td>
										<td class="small"><input name="breakfast[]" type="text" maxlength="10" title="breakfast3" id="breakfast3" /></td>
										<td class="small"><input name="mid_am[]" type="text" maxlength="10" title="mid_am3" id="mid_am3" /></td>
										<td class="small"><input name="lunch[]" type="text" maxlength="10" title="lunch3"  id="lunch3" /></td>
										<td class="small"><input name="mid_pm[]" type="text" maxlength="10" title="mid_pm3" id="mid_pm3" /></td>
										<td class="small"><input name="dinner[]" type="text" maxlength="10" title="dinner3" id="dinner3" /></td>
										<td class="small"><input name="before_bed[]" type="text" maxlength="10" title="before_bed3" id="before_bed3" /></td>
										<td><textarea name="comments[]" class="expand_desc" title="comments3" id="comments3" maxlength="100" style="width: 123px; height: 18px;"></textarea></td>
                                            <!--<input name="comments[]" type="text" maxlength="100"
											title="comments3" /></td>-->
									</tr>

									<tr>
										<td><textarea name="product_description[]" class="expand_desc" title="product_description4" id="product_description4" style="width: 123px; height: 18px;"></textarea></td>
                                        <!--<input name="product_description[]" type="text"
											maxlength="100" title="product_description4" /></td>-->
										<td class="small"><input name="arising[]" type="text" maxlength="10" title="arising4" id="arising4" /></td>
										<td class="small"><input name="breakfast[]" type="text" maxlength="10" title="breakfast4" id="breakfast4" /></td>
										<td class="small"><input name="mid_am[]" type="text" maxlength="10" title="mid_am4" id="mid_am4" /></td>
										<td class="small"><input name="lunch[]" type="text" maxlength="10" title="lunch4" id="lunch4" /></td>
										<td class="small"><input name="mid_pm[]" type="text" maxlength="10" title="mid_pm4" id="mid_pm4" /></td>
										<td class="small"><input name="dinner[]" type="text" maxlength="10" title="dinner4" id="dinner4" /></td>
										<td class="small"><input name="before_bed[]" type="text" maxlength="10" title="before_bed4" id="before_bed4" /></td>
										<td><textarea name="comments[]" class="expand_desc" title="comments4" id="comments4" maxlength="100" style="width: 123px; height: 18px;"></textarea></td>
                                            <!--<input name="comments[]" type="text" maxlength="100"
											title="comments4" /></td>-->
									</tr>

									<tr>
										<td><textarea name="product_description[]" class="expand_desc" title="product_description5" id="product_description5" style="width: 123px; height: 18px;"></textarea></td>
                                        <!--<input name="product_description[]" type="text"
											maxlength="100" title="product_description5" /></td>-->
										<td class="small"><input name="arising[]" type="text" maxlength="10" title="arising5" id="arising5" /></td>
										<td class="small"><input name="breakfast[]" type="text" maxlength="10" title="breakfast5" id="breakfast5" /></td>
										<td class="small"><input name="mid_am[]" type="text" maxlength="10" title="mid_am5" id="mid_am5" /></td>
										<td class="small"><input name="lunch[]" type="text" maxlength="10" title="lunch5" id="lunch5" /></td>
										<td class="small"><input name="mid_pm[]" type="text" maxlength="10" title="mid_pm5" id="mid_pm5" /></td>
										<td class="small"><input name="dinner[]" type="text" maxlength="10" title="dinner5" id="dinner5" /></td>
										<td class="small"><input name="before_bed[]" type="text" maxlength="10" title="before_bed5" id="before_bed5" /></td>
										<td><textarea name="comments[]" class="expand_desc" title="comments5" id="comments5" maxlength="100" style="width: 123px; height: 18px;"></textarea></td>
                                            <!--<input name="comments[]" type="text" maxlength="100"
											title="comments5" /></td>-->
									</tr>

								</table>
								<div style="height: 20px; float: right;">
									<input type="button" name="add_more"
										value="Add Another Product" onclick="javascript:addproduct()" />
                                        <input type="hidden" id="count" value="<!prouct_count>"/>
								</div>
								<div>
									<div style="clear: both;"></div>


									<table cellpadding="0" cellspacing="0" style="width: 100%"
										class="soap-note-prog-bottom">

										<tr>
											<td>Notes and/or additional instructions:<br /> <textarea
													class="expand" name="notes_instructions"
													title="notes_instructions" id="notes_instructions"></textarea>
											</td>
										</tr>
									</table>


								</div></td>
						</tr>
					</table>

				</div>







			</div>
		</form>
	</div>
</div>

<script
	type="text/javascript" src="/js/jquery.textarea-expander.js"></script>
<script type="text/javascript">
var str='';
var slash=/\\\\/gi;

function URLDecode(psEncodeString)
{
  // Create a regular expression to search all +s in the string
//alert(psEncodeString);
	   str=psEncodeString;
	   str = str.replace(/\+/g, " ");	
	   str= str.replace(/&#039;/gi,"'");
	   str= str.replace(/&quot;/gi,'"');
	   str= str.replace(/&lt;/gi,"<");
	   str= str.replace(/&gt;/gi,">");
       str= str.replace(/&amp;/gi,"&");
       str= str.replace(slash,"");
       str= str.replace(/%2F/,"/");
       str= str.replace(/%20/gi," ");
       str= str.replace(/%21/gi,"!");
       str= str.replace(/%22/gi,'"');
       str= str.replace(/%23/gi,"#");
       str= str.replace(/%24/gi,"$");
       str= str.replace(/%25/gi,"%");
       str= str.replace(/%26/gi,"&");
       str= str.replace(/%27/gi,"'");
       str= str.replace(/%28/gi,"(");
       str= str.replace(/%29/gi,")");
       str= str.replace(/%2A/gi,"*");
       str= str.replace(/%2B/gi,"+");
       str= str.replace(/%2C/gi,",");
       str= str.replace(/%2D/gi,"-");
       str= str.replace(/%2E/gi,".");
       str= str.replace(/%2F/gi,"/");
       str= str.replace(/%30/gi,"0");
       str= str.replace(/%31/gi,"1");
       str= str.replace(/%32/gi,"2");
       str= str.replace(/%33/gi,"3");
       str= str.replace(/%34/gi,"4");
       str= str.replace(/%35/gi,"5");
       str= str.replace(/%36/gi,"6");
       str= str.replace(/%37/gi,"7");
       str= str.replace(/%38/gi,"8");
       str= str.replace(/%39/gi,"9");
       str= str.replace(/%3A/gi,":");
       str= str.replace(/%3B/gi,";");
       str= str.replace(/%3C/gi,"<");
       str= str.replace(/%3D/gi,"=");
       str= str.replace(/%3E/gi,">");
       str= str.replace(/%3F/gi,"?");
       str= str.replace(/%40/gi,"@");
       str= str.replace(/%41/gi,"A");
       str= str.replace(/%42/gi,"B");
       str= str.replace(/%43/gi,"C");
       str= str.replace(/%44/gi,"D");
       str= str.replace(/%45/gi,"E");
       str= str.replace(/%46/gi,"F");
       str= str.replace(/%47/gi,"G");
       str= str.replace(/%48/gi,"H");
       str= str.replace(/%49/gi,"I");
       str= str.replace(/%4A/gi,"J");
       str= str.replace(/%4B/gi,"K");
       str= str.replace(/%4C/gi,"L");
       str= str.replace(/%4D/gi,"M");
       str= str.replace(/%4E/gi,"N");
       str= str.replace(/%4F/gi,"O");
       str= str.replace(/%50/gi,"P");
       str= str.replace(/%51/gi,"Q");
       str= str.replace(/%52/gi,"R");
       str= str.replace(/%53/gi,"S");
       str= str.replace(/%54/gi,"T");
       str= str.replace(/%55/gi,"U");
       str= str.replace(/%56/gi,"V");
       str= str.replace(/%57/gi,"W");
       str= str.replace(/%58/gi,"X");
       str= str.replace(/%59/gi,"Y");
       str= str.replace(/%5A/gi,"Z");
       str= str.replace(/%5B/gi,"[");
       str= str.replace(/%5D/gi,"]");
       str= str.replace(/%5E/gi,"^");
       str= str.replace(/%5F/gi,"_");
       str= str.replace(/%60/gi,"`");
       str= str.replace(/%61/gi,"a");
       str= str.replace(/%62/gi,"b");
       str= str.replace(/%63/gi,"c");
       str= str.replace(/%64/gi,"d");
       str= str.replace(/%65/gi,"e");
       str= str.replace(/%66/gi,"f");
       str= str.replace(/%67/gi,"g");
       str= str.replace(/%68/gi,"h");
       str= str.replace(/%69/gi,"i");
       str= str.replace(/%6A/gi,"j");
       str= str.replace(/%6B/gi,"k");
       str= str.replace(/%6C/gi,"l");
       str= str.replace(/%6D/gi,"m");
       str= str.replace(/%6E/gi,"n");
       str= str.replace(/%6F/gi,"o");
       str= str.replace(/%70/gi,"p");
       str= str.replace(/%71/gi,"q");
       str= str.replace(/%72/gi,"r");
       str= str.replace(/%73/gi,"s");
       str= str.replace(/%74/gi,"t");
       str= str.replace(/%75/gi,"u");
       str= str.replace(/%76/gi,"v");
       str= str.replace(/%77/gi,"w");
       str= str.replace(/%78/gi,"x");
       str= str.replace(/%79/gi,"y");
       str= str.replace(/%7A/gi,"z");
       str= str.replace(/%7B/gi,"{");
       str= str.replace(/%7C/gi,"|");
       str= str.replace(/%7D/gi,"|");
       str= str.replace(/%7E/gi,"~");
       str= str.replace(/%7F/gi,"");
       str= str.replace(/%80/gi,"€");
       str= str.replace(/%81/gi,"");
       str= str.replace(/%82/gi,"‚");
       str= str.replace(/%83/gi,"ƒ");
       str= str.replace(/%84/gi,"„");
       str= str.replace(/%85/gi,"…");
       str= str.replace(/%86/gi,"†");
       str= str.replace(/%87/gi,"‡");
       str= str.replace(/%88/gi,"ˆ");
       str= str.replace(/%89/gi,"‰");
       str= str.replace(/%8A/gi,"Š");
       str= str.replace(/%8B/gi,"‹");
       str= str.replace(/%8C/gi,"Œ");
       str= str.replace(/%8D/gi,"");
       str= str.replace(/%8E/gi,"Ž");
       str= str.replace(/%8F/gi,"");
       str= str.replace(/%90/gi,"");
       str= str.replace(/%91/gi,"‘");
       str= str.replace(/%92/gi,"’");
       str= str.replace(/%93/gi,'“');
       str= str.replace(/%94/gi,'”');
       str= str.replace(/%95/gi,"•");
       str= str.replace(/%96/gi,"–");
       str= str.replace(/%97/gi,"—");
       str= str.replace(/%98/gi,"˜");
       str= str.replace(/%99/gi,"");
       str= str.replace(/%9A/gi,"š");
       str= str.replace(/%9B/gi,"›");
       str= str.replace(/%9C/gi,"œ");
       str= str.replace(/%9D/gi,"");
       str= str.replace(/%9E/gi,"ž");
       str= str.replace(/%9F/gi,"Ÿ");
       str= str.replace(/%A0/gi," ");
       str= str.replace(/%A1/gi,"¡");
       str= str.replace(/%A2/gi,"¢");
       str= str.replace(/%A3/gi,"£");
       str= str.replace(/%A4/gi,"");
       str= str.replace(/%A5/gi,"¥");
       str= str.replace(/%A6/gi,"|");
       str= str.replace(/%A7/gi,"§");
       str= str.replace(/%A8/gi,"¨");
       str= str.replace(/%A9/gi,"©");
       str= str.replace(/%AA/gi,"ª");
       str= str.replace(/%AB/gi,"«");
       str= str.replace(/%AC/gi,"¬");
       str= str.replace(/%AD/gi,"¯");
       str= str.replace(/%AE/gi,"®");
       str= str.replace(/%AF/gi,"¯");
       str= str.replace(/%B0/gi,"°");
       str= str.replace(/%B1/gi,"±");
       str= str.replace(/%B2/gi,"²");
       str= str.replace(/%B3/gi,"³");
       str= str.replace(/%B4/gi,"´");
       str= str.replace(/%B5/gi,"µ");
       str= str.replace(/%B6/gi,"¶");
       str= str.replace(/%B7/gi,"·");
       str= str.replace(/%B8/gi,"¸");
       str= str.replace(/%B9/gi,"¹");
       str= str.replace(/%BA/gi,"º");
       str= str.replace(/%BB/gi,"»");
       str= str.replace(/%BC/gi,"¼");
       str= str.replace(/%BD/gi,"½");
       str= str.replace(/%BE/gi,"¾");
       str= str.replace(/%BF/gi,"¿");
       str= str.replace(/%C0/gi,"À");
       str= str.replace(/%C1/gi,"Á");
       str= str.replace(/%C2/gi,"Â");
       str= str.replace(/%C3/gi,"Ã");
       str= str.replace(/%C4/gi,"Ä");
       str= str.replace(/%C5/gi,"Å");
       str= str.replace(/%C6/gi,"Æ");
       str= str.replace(/%C7/gi,"Ç");
       str= str.replace(/%C8/gi,"È");
       str= str.replace(/%C9/gi,"É");
       str= str.replace(/%CA/gi,"Ê");
       str= str.replace(/%CB/gi,"Ë");
       str= str.replace(/%CC/gi,"Ì");
       str= str.replace(/%CD/gi,"Í");
       str= str.replace(/%CE/gi,"Î");
       str= str.replace(/%CF/gi,"Ï");
       str= str.replace(/%D0/gi,"Ð");
       str= str.replace(/%D1/gi,"Ñ");
       str= str.replace(/%D2/gi,"Ò");
       str= str.replace(/%D3/gi,"Ó");
       str= str.replace(/%D4/gi,"Ô");
       str= str.replace(/%D5/gi,"Õ");
       str= str.replace(/%D6/gi,"Ö");
       str= str.replace(/%D7/gi," ");
       str= str.replace(/%D8/gi,"Ø");
       str= str.replace(/%D9/gi,"Ù");
       str= str.replace(/%DA/gi,"Ú");
       str= str.replace(/%DB/gi,"Û");
       str= str.replace(/%DC/gi,"Ü");
       str= str.replace(/%DD/gi,"Ý");
       str= str.replace(/%DE/gi,"Þ");
       str= str.replace(/%DF/gi,"ß");
       str= str.replace(/%E0/gi,"à");
       str= str.replace(/%E1/gi,"á");
       str= str.replace(/%E2/gi,"â");
       str= str.replace(/%E3/gi,"ã");
       str= str.replace(/%E4/gi,"ä");
       str= str.replace(/%E5/gi,"å");
       str= str.replace(/%E6/gi,"æ");
       str= str.replace(/%E7/gi,"ç");
       str= str.replace(/%E8/gi,"è");
       str= str.replace(/%E9/gi,"é");
       str= str.replace(/%EA/gi,"ê");
       str= str.replace(/%EB/gi,"ë");
       str= str.replace(/%EC/gi,"ì");
       str= str.replace(/%ED/gi,"í");
       str= str.replace(/%EE/gi,"î");
       str= str.replace(/%EF/gi,"ï");
       str= str.replace(/%F0/gi,"ð");
       str= str.replace(/%F1/gi,"ñ");
       str= str.replace(/%F2/gi,"ò");
       str= str.replace(/%F3/gi,"ó");
       str= str.replace(/%F4/gi,"ô");
       str= str.replace(/%F5/gi,"õ");
       str= str.replace(/%F6/gi,"ö");
       str= str.replace(/%F7/gi,"÷");
       str= str.replace(/%F8/gi,"ø");
       str= str.replace(/%F9/gi,"ù");
       str= str.replace(/%FA/gi,"ú");
       str= str.replace(/%FB/gi,"û");
       str= str.replace(/%FC/gi,"ü");
       str= str.replace(/%FD/gi,"ý");
       str= str.replace(/%FE/gi,"þ");
	   
	  if (psEncodeString.match(/\+/gi))
		     {	 
		  	   var lsRegExp = /\+/gi;
		 	   var str=unescape(String(psEncodeString).replace(lsRegExp, " "));
		       str= str.replace(/&#039;/gi,"'");
	 		   str= str.replace(/&quot;/gi,'"');
	 		   str= str.replace(/&lt;/gi,"<");
	 		   str= str.replace(/&gt;/gi,">");
	 		   str= str.replace(/&amp;/gi,"&");
	 		   str= str.replace(slash,"");
		 		  str= str.replace(/%2F/,"/");
			 		 str= str.replace(/%20/gi," ");
				 	    str= str.replace(/%21/gi,"!");
				 	    str= str.replace(/%22/gi,'"');
				 	    str= str.replace(/%23/gi,"#");
				 	    str= str.replace(/%24/gi,"$");
				 	    str= str.replace(/%25/gi,"%");
				 	    str= str.replace(/%26/gi,"&");
				 	    str= str.replace(/%27/gi,"'");
				 	    str= str.replace(/%28/gi,"(");
				 	    str= str.replace(/%29/gi,")");
				 	    str= str.replace(/%2A/gi,"*");
				 	    str= str.replace(/%2B/gi,"+");
				 	    str= str.replace(/%2C/gi,",");
				 	    str= str.replace(/%2D/gi,"-");
				 	    str= str.replace(/%2E/gi,".");
				 	    str= str.replace(/%2F/gi,"/");
				 	    str= str.replace(/%30/gi,"0");
				 	    str= str.replace(/%31/gi,"1");
				 	    str= str.replace(/%32/gi,"2");
				 	    str= str.replace(/%33/gi,"3");
				 	    str= str.replace(/%34/gi,"4");
				 	    str= str.replace(/%35/gi,"5");
				 	    str= str.replace(/%36/gi,"6");
				 	    str= str.replace(/%37/gi,"7");
				 	    str= str.replace(/%38/gi,"8");
				 	    str= str.replace(/%39/gi,"9");
				 	    str= str.replace(/%3A/gi,":");
				 	    str= str.replace(/%3B/gi,";");
				 	    str= str.replace(/%3C/gi,"<");
				 	    str= str.replace(/%3D/gi,"=");
				 	    str= str.replace(/%3E/gi,">");
				 	    str= str.replace(/%3F/gi,"?");
				 	    str= str.replace(/%40/gi,"@");
				 	    str= str.replace(/%41/gi,"A");
				 	    str= str.replace(/%42/gi,"B");
				 	    str= str.replace(/%43/gi,"C");
				 	    str= str.replace(/%44/gi,"D");
				 	    str= str.replace(/%45/gi,"E");
				 	    str= str.replace(/%46/gi,"F");
				 	    str= str.replace(/%47/gi,"G");
				 	    str= str.replace(/%48/gi,"H");
				 	    str= str.replace(/%49/gi,"I");
				 	    str= str.replace(/%4A/gi,"J");
				 	    str= str.replace(/%4B/gi,"K");
				 	    str= str.replace(/%4C/gi,"L");
				 	    str= str.replace(/%4D/gi,"M");
				 	    str= str.replace(/%4E/gi,"N");
				 	    str= str.replace(/%4F/gi,"O");
				 	    str= str.replace(/%50/gi,"P");
				 	    str= str.replace(/%51/gi,"Q");
				 	    str= str.replace(/%52/gi,"R");
				 	    str= str.replace(/%53/gi,"S");
				 	    str= str.replace(/%54/gi,"T");
				 	    str= str.replace(/%55/gi,"U");
				 	    str= str.replace(/%56/gi,"V");
				 	    str= str.replace(/%57/gi,"W");
				 	    str= str.replace(/%58/gi,"X");
				 	    str= str.replace(/%59/gi,"Y");
				 	    str= str.replace(/%5A/gi,"Z");
				 	    str= str.replace(/%5B/gi,"[");
				 	    
				 	    str= str.replace(/%5D/gi,"]");
				 	    str= str.replace(/%5E/gi,"^");
				 	    str= str.replace(/%5F/gi,"_");
				 	    str= str.replace(/%60/gi,"`");
				 	    str= str.replace(/%61/gi,"a");
				 	    str= str.replace(/%62/gi,"b");
				 	    str= str.replace(/%63/gi,"c");
				 	    str= str.replace(/%64/gi,"d");
				 	    str= str.replace(/%65/gi,"e");
				 	    str= str.replace(/%66/gi,"f");
				 	    str= str.replace(/%67/gi,"g");
				 	    str= str.replace(/%68/gi,"h");
				 	    str= str.replace(/%69/gi,"i");
				 	    str= str.replace(/%6A/gi,"j");
				 	    str= str.replace(/%6B/gi,"k");
				 	    str= str.replace(/%6C/gi,"l");
				 	    str= str.replace(/%6D/gi,"m");
				 	    str= str.replace(/%6E/gi,"n");
				 	    str= str.replace(/%6F/gi,"o");
				 	    str= str.replace(/%70/gi,"p");
				 	    str= str.replace(/%71/gi,"q");
				 	    str= str.replace(/%72/gi,"r");
				 	    str= str.replace(/%73/gi,"s");
				 	    str= str.replace(/%74/gi,"t");
				 	    str= str.replace(/%75/gi,"u");
				 	    str= str.replace(/%76/gi,"v");
				 	    str= str.replace(/%77/gi,"w");
				 	    str= str.replace(/%78/gi,"x");
				 	    str= str.replace(/%79/gi,"y");
				 	    str= str.replace(/%7A/gi,"z");
				 	    str= str.replace(/%7B/gi,"{");
				 	    str= str.replace(/%7C/gi,"|");
				 	    str= str.replace(/%7D/gi,"|");
				 	    str= str.replace(/%7E/gi,"~");
				 	    str= str.replace(/%7F/gi,"");
				 	    str= str.replace(/%80/gi,"€");
				 	    str= str.replace(/%81/gi,"");
				 	    str= str.replace(/%82/gi,"‚");
				 	    str= str.replace(/%83/gi,"ƒ");
				 	    str= str.replace(/%84/gi,"„");
				 	    str= str.replace(/%85/gi,"…");
				 	    str= str.replace(/%86/gi,"†");
				 	    str= str.replace(/%87/gi,"‡");
				 	    str= str.replace(/%88/gi,"ˆ");
				 	    str= str.replace(/%89/gi,"‰");
				 	    str= str.replace(/%8A/gi,"Š");
				 	    str= str.replace(/%8B/gi,"‹");
				 	    str= str.replace(/%8C/gi,"Œ");
				 	    str= str.replace(/%8D/gi,"");
				 	    str= str.replace(/%8E/gi,"Ž");
				 	    str= str.replace(/%8F/gi,"");
				 	    str= str.replace(/%90/gi,"");
				 	    str= str.replace(/%91/gi,"‘");
				 	    str= str.replace(/%92/gi,"’");
				 	    str= str.replace(/%93/gi,'“');
				 	    str= str.replace(/%94/gi,'”');
				 	    str= str.replace(/%95/gi,"•");
				 	    str= str.replace(/%96/gi,"–");
				 	    str= str.replace(/%97/gi,"—");
				 	    str= str.replace(/%98/gi,"˜");
				 	    str= str.replace(/%99/gi,"™");
				 	    str= str.replace(/%9A/gi,"š");
				 	    str= str.replace(/%9B/gi,"›");
				 	    str= str.replace(/%9C/gi,"œ");
				 	    str= str.replace(/%9D/gi,"");
				 	    str= str.replace(/%9E/gi,"ž");
				 	    str= str.replace(/%9F/gi,"Ÿ");
				 	    str= str.replace(/%A0/gi," ");
				 	    str= str.replace(/%A1/gi,"¡");
				 	    str= str.replace(/%A2/gi,"¢");
				 	    str= str.replace(/%A3/gi,"£");
				 	    str= str.replace(/%A4/gi,"");
				 	    str= str.replace(/%A5/gi,"¥");
				 	    str= str.replace(/%A6/gi,"|");
				 	    str= str.replace(/%A7/gi,"§");
				 	    str= str.replace(/%A8/gi,"¨");
				 	    str= str.replace(/%A9/gi,"©");
				 	    str= str.replace(/%AA/gi,"ª");
				 	    str= str.replace(/%AB/gi,"«");
				 	    str= str.replace(/%AC/gi,"¬");
				 	    str= str.replace(/%AD/gi,"¯");
				 	    str= str.replace(/%AE/gi,"®");
				 	    str= str.replace(/%AF/gi,"¯");
				 	    str= str.replace(/%B0/gi,"°");
				 	    str= str.replace(/%B1/gi,"±");
				 	    str= str.replace(/%B2/gi,"²");
				 	    str= str.replace(/%B3/gi,"³");
				 	    str= str.replace(/%B4/gi,"´");
				 	    str= str.replace(/%B5/gi,"µ");
				 	    str= str.replace(/%B6/gi,"¶");
				 	    str= str.replace(/%B7/gi,"·");
				 	    str= str.replace(/%B8/gi,"¸");
				 	    str= str.replace(/%B9/gi,"¹");
				 	    str= str.replace(/%BA/gi,"º");
				 	    str= str.replace(/%BB/gi,"»");
				 	    str= str.replace(/%BC/gi,"¼");
				 	    str= str.replace(/%BD/gi,"½");
				 	    str= str.replace(/%BE/gi,"¾");
				 	    str= str.replace(/%BF/gi,"¿");
				 	    str= str.replace(/%C0/gi,"À");
				 	    str= str.replace(/%C1/gi,"Á");
				 	    str= str.replace(/%C2/gi,"Â");
				 	    str= str.replace(/%C3/gi,"Ã");
				 	    str= str.replace(/%C4/gi,"Ä");
				 	    str= str.replace(/%C5/gi,"Å");
				 	    str= str.replace(/%C6/gi,"Æ");
				 	    str= str.replace(/%C7/gi,"Ç");
				 	    str= str.replace(/%C8/gi,"È");
				 	    str= str.replace(/%C9/gi,"É");
				 	    str= str.replace(/%CA/gi,"Ê");
				 	    str= str.replace(/%CB/gi,"Ë");
				 	    str= str.replace(/%CC/gi,"Ì");
				 	    str= str.replace(/%CD/gi,"Í");
				 	    str= str.replace(/%CE/gi,"Î");
				 	    str= str.replace(/%CF/gi,"Ï");
				 	    str= str.replace(/%D0/gi,"Ð");
				 	    str= str.replace(/%D1/gi,"Ñ");
				 	    str= str.replace(/%D2/gi,"Ò");
				 	    str= str.replace(/%D3/gi,"Ó");
				 	    str= str.replace(/%D4/gi,"Ô");
				 	    str= str.replace(/%D5/gi,"Õ");
				 	    str= str.replace(/%D6/gi,"Ö");
				 	    str= str.replace(/%D7/gi," ");
				 	    str= str.replace(/%D8/gi,"Ø");
				 	    str= str.replace(/%D9/gi,"Ù");
				 	    str= str.replace(/%DA/gi,"Ú");
				 	    str= str.replace(/%DB/gi,"Û");
				 	    str= str.replace(/%DC/gi,"Ü");
				 	    str= str.replace(/%DD/gi,"Ý");
				 	    str= str.replace(/%DE/gi,"Þ");
				 	    str= str.replace(/%DF/gi,"ß");
				 	    str= str.replace(/%E0/gi,"à");
				 	    str= str.replace(/%E1/gi,"á");
				 	    str= str.replace(/%E2/gi,"â");
				 	    str= str.replace(/%E3/gi,"ã");
				 	    str= str.replace(/%E4/gi,"ä");
				 	    str= str.replace(/%E5/gi,"å");
				 	    str= str.replace(/%E6/gi,"æ");
				 	    str= str.replace(/%E7/gi,"ç");
				 	    str= str.replace(/%E8/gi,"è");
				 	    str= str.replace(/%E9/gi,"é");
				 	    str= str.replace(/%EA/gi,"ê");
				 	    str= str.replace(/%EB/gi,"ë");
				 	    str= str.replace(/%EC/gi,"ì");
				 	    str= str.replace(/%ED/gi,"í");
				 	    str= str.replace(/%EE/gi,"î");
				 	    str= str.replace(/%EF/gi,"ï");
				 	    str= str.replace(/%F0/gi,"ð");
				 	    str= str.replace(/%F1/gi,"ñ");
				 	    str= str.replace(/%F2/gi,"ò");
				 	    str= str.replace(/%F3/gi,"ó");
				 	    str= str.replace(/%F4/gi,"ô");
				 	    str= str.replace(/%F5/gi,"õ");
				 	    str= str.replace(/%F6/gi,"ö");
				 	    str= str.replace(/%F7/gi,"÷");
				 	    str= str.replace(/%F8/gi,"ø");
				 	    str= str.replace(/%F9/gi,"ù");
				 	    str= str.replace(/%FA/gi,"ú");
				 	    str= str.replace(/%FB/gi,"û");
				 	    str= str.replace(/%FC/gi,"ü");
				 	    str= str.replace(/%FD/gi,"ý");
				 	    str= str.replace(/%FE/gi,"þ");
			 }
		 if (psEncodeString.match(/\%5Cn/gi))  
		   {
				
			   var lsRegExp = /\%5Cn/gi;
			   var str=unescape(String(psEncodeString).replace(lsRegExp, "\n"));
			   str = str.replace(/\+/g, " ");	
			   str= str.replace(/&#039;/gi,"'");
			   str= str.replace(/&quot;/gi,'"');
			   str= str.replace(/&lt;/gi,"<");
			   str= str.replace(/&gt;/gi,">");
			   str= str.replace(/&amp;/gi,"&");
			   str= str.replace(slash,"");
			   str= str.replace(/%2F/,"/");	
			   str= str.replace(/%20/gi," ");
			    str= str.replace(/%21/gi,"!");
			    str= str.replace(/%22/gi,'"');
			    str= str.replace(/%23/gi,"#");
			    str= str.replace(/%24/gi,"$");
			    str= str.replace(/%25/gi,"%");
			    str= str.replace(/%26/gi,"&");
			    str= str.replace(/%27/gi,"'");
			    str= str.replace(/%28/gi,"(");
			    str= str.replace(/%29/gi,")");
			    str= str.replace(/%2A/gi,"*");
			    str= str.replace(/%2B/gi,"+");
			    str= str.replace(/%2C/gi,",");
			    str= str.replace(/%2D/gi,"-");
			    str= str.replace(/%2E/gi,".");
			    str= str.replace(/%2F/gi,"/");
			    str= str.replace(/%30/gi,"0");
			    str= str.replace(/%31/gi,"1");
			    str= str.replace(/%32/gi,"2");
			    str= str.replace(/%33/gi,"3");
			    str= str.replace(/%34/gi,"4");
			    str= str.replace(/%35/gi,"5");
			    str= str.replace(/%36/gi,"6");
			    str= str.replace(/%37/gi,"7");
			    str= str.replace(/%38/gi,"8");
			    str= str.replace(/%39/gi,"9");
			    str= str.replace(/%3A/gi,":");
			    str= str.replace(/%3B/gi,";");
			    str= str.replace(/%3C/gi,"<");
			    str= str.replace(/%3D/gi,"=");
			    str= str.replace(/%3E/gi,">");
			    str= str.replace(/%3F/gi,"?");
			    str= str.replace(/%40/gi,"@");
			    str= str.replace(/%41/gi,"A");
			    str= str.replace(/%42/gi,"B");
			    str= str.replace(/%43/gi,"C");
			    str= str.replace(/%44/gi,"D");
			    str= str.replace(/%45/gi,"E");
			    str= str.replace(/%46/gi,"F");
			    str= str.replace(/%47/gi,"G");
			    str= str.replace(/%48/gi,"H");
			    str= str.replace(/%49/gi,"I");
			    str= str.replace(/%4A/gi,"J");
			    str= str.replace(/%4B/gi,"K");
			    str= str.replace(/%4C/gi,"L");
			    str= str.replace(/%4D/gi,"M");
			    str= str.replace(/%4E/gi,"N");
			    str= str.replace(/%4F/gi,"O");
			    str= str.replace(/%50/gi,"P");
			    str= str.replace(/%51/gi,"Q");
			    str= str.replace(/%52/gi,"R");
			    str= str.replace(/%53/gi,"S");
			    str= str.replace(/%54/gi,"T");
			    str= str.replace(/%55/gi,"U");
			    str= str.replace(/%56/gi,"V");
			    str= str.replace(/%57/gi,"W");
			    str= str.replace(/%58/gi,"X");
			    str= str.replace(/%59/gi,"Y");
			    str= str.replace(/%5A/gi,"Z");
			    str= str.replace(/%5B/gi,"[");
			       str= str.replace(/%5D/gi,"]");
			    str= str.replace(/%5E/gi,"^");
			    str= str.replace(/%5F/gi,"_");
			    str= str.replace(/%60/gi,"`");
			    str= str.replace(/%61/gi,"a");
			    str= str.replace(/%62/gi,"b");
			    str= str.replace(/%63/gi,"c");
			    str= str.replace(/%64/gi,"d");
			    str= str.replace(/%65/gi,"e");
			    str= str.replace(/%66/gi,"f");
			    str= str.replace(/%67/gi,"g");
			    str= str.replace(/%68/gi,"h");
			    str= str.replace(/%69/gi,"i");
			    str= str.replace(/%6A/gi,"j");
			    str= str.replace(/%6B/gi,"k");
			    str= str.replace(/%6C/gi,"l");
			    str= str.replace(/%6D/gi,"m");
			    str= str.replace(/%6E/gi,"n");
			    str= str.replace(/%6F/gi,"o");
			    str= str.replace(/%70/gi,"p");
			    str= str.replace(/%71/gi,"q");
			    str= str.replace(/%72/gi,"r");
			    str= str.replace(/%73/gi,"s");
			    str= str.replace(/%74/gi,"t");
			    str= str.replace(/%75/gi,"u");
			    str= str.replace(/%76/gi,"v");
			    str= str.replace(/%77/gi,"w");
			    str= str.replace(/%78/gi,"x");
			    str= str.replace(/%79/gi,"y");
			    str= str.replace(/%7A/gi,"z");
			    str= str.replace(/%7B/gi,"{");
			    str= str.replace(/%7C/gi,"|");
			    str= str.replace(/%7D/gi,"|");
			    str= str.replace(/%7E/gi,"~");
			    str= str.replace(/%7F/gi,"");
			    str= str.replace(/%80/gi,"€");
			    str= str.replace(/%81/gi,"");
			    str= str.replace(/%82/gi,"‚");
			    str= str.replace(/%83/gi,"ƒ");
			    str= str.replace(/%84/gi,"„");
			    str= str.replace(/%85/gi,"…");
			    str= str.replace(/%86/gi,"†");
			    str= str.replace(/%87/gi,"‡");
			    str= str.replace(/%88/gi,"ˆ");
			    str= str.replace(/%89/gi,"‰");
			    str= str.replace(/%8A/gi,"Š");
			    str= str.replace(/%8B/gi,"‹");
			    str= str.replace(/%8C/gi,"Œ");
			    str= str.replace(/%8D/gi,"");
			    str= str.replace(/%8E/gi,"Ž");
			    str= str.replace(/%8F/gi,"");
			    str= str.replace(/%90/gi,"");
			    str= str.replace(/%91/gi,"‘");
			    str= str.replace(/%92/gi,"’");
			    str= str.replace(/%93/gi,'“');
			    str= str.replace(/%94/gi,'”');
			    str= str.replace(/%95/gi,"•");
			    str= str.replace(/%96/gi,"–");
			    str= str.replace(/%97/gi,"—");
			    str= str.replace(/%98/gi,"˜");
			    str= str.replace(/%99/gi,"™");
			    str= str.replace(/%9A/gi,"š");
			    str= str.replace(/%9B/gi,"›");
			    str= str.replace(/%9C/gi,"œ");
			    str= str.replace(/%9D/gi,"");
			    str= str.replace(/%9E/gi,"ž");
			    str= str.replace(/%9F/gi,"Ÿ");
			    str= str.replace(/%A0/gi," ");
			    str= str.replace(/%A1/gi,"¡");
			    str= str.replace(/%A2/gi,"¢");
			    str= str.replace(/%A3/gi,"£");
			    str= str.replace(/%A4/gi,"");
			    str= str.replace(/%A5/gi,"¥");
			    str= str.replace(/%A6/gi,"|");
			    str= str.replace(/%A7/gi,"§");
			    str= str.replace(/%A8/gi,"¨");
			    str= str.replace(/%A9/gi,"©");
			    str= str.replace(/%AA/gi,"ª");
			    str= str.replace(/%AB/gi,"«");
			    str= str.replace(/%AC/gi,"¬");
			    str= str.replace(/%AD/gi,"¯");
			    str= str.replace(/%AE/gi,"®");
			    str= str.replace(/%AF/gi,"¯");
			    str= str.replace(/%B0/gi,"°");
			    str= str.replace(/%B1/gi,"±");
			    str= str.replace(/%B2/gi,"²");
			    str= str.replace(/%B3/gi,"³");
			    str= str.replace(/%B4/gi,"´");
			    str= str.replace(/%B5/gi,"µ");
			    str= str.replace(/%B6/gi,"¶");
			    str= str.replace(/%B7/gi,"·");
			    str= str.replace(/%B8/gi,"¸");
			    str= str.replace(/%B9/gi,"¹");
			    str= str.replace(/%BA/gi,"º");
			    str= str.replace(/%BB/gi,"»");
			    str= str.replace(/%BC/gi,"¼");
			    str= str.replace(/%BD/gi,"½");
			    str= str.replace(/%BE/gi,"¾");
			    str= str.replace(/%BF/gi,"¿");
			    str= str.replace(/%C0/gi,"À");
			    str= str.replace(/%C1/gi,"Á");
			    str= str.replace(/%C2/gi,"Â");
			    str= str.replace(/%C3/gi,"Ã");
			    str= str.replace(/%C4/gi,"Ä");
			    str= str.replace(/%C5/gi,"Å");
			    str= str.replace(/%C6/gi,"Æ");
			    str= str.replace(/%C7/gi,"Ç");
			    str= str.replace(/%C8/gi,"È");
			    str= str.replace(/%C9/gi,"É");
			    str= str.replace(/%CA/gi,"Ê");
			    str= str.replace(/%CB/gi,"Ë");
			    str= str.replace(/%CC/gi,"Ì");
			    str= str.replace(/%CD/gi,"Í");
			    str= str.replace(/%CE/gi,"Î");
			    str= str.replace(/%CF/gi,"Ï");
			    str= str.replace(/%D0/gi,"Ð");
			    str= str.replace(/%D1/gi,"Ñ");
			    str= str.replace(/%D2/gi,"Ò");
			    str= str.replace(/%D3/gi,"Ó");
			    str= str.replace(/%D4/gi,"Ô");
			    str= str.replace(/%D5/gi,"Õ");
			    str= str.replace(/%D6/gi,"Ö");
			    str= str.replace(/%D7/gi," ");
			    str= str.replace(/%D8/gi,"Ø");
			    str= str.replace(/%D9/gi,"Ù");
			    str= str.replace(/%DA/gi,"Ú");
			    str= str.replace(/%DB/gi,"Û");
			    str= str.replace(/%DC/gi,"Ü");
			    str= str.replace(/%DD/gi,"Ý");
			    str= str.replace(/%DE/gi,"Þ");
			    str= str.replace(/%DF/gi,"ß");
			    str= str.replace(/%E0/gi,"à");
			    str= str.replace(/%E1/gi,"á");
			    str= str.replace(/%E2/gi,"â");
			    str= str.replace(/%E3/gi,"ã");
			    str= str.replace(/%E4/gi,"ä");
			    str= str.replace(/%E5/gi,"å");
			    str= str.replace(/%E6/gi,"æ");
			    str= str.replace(/%E7/gi,"ç");
			    str= str.replace(/%E8/gi,"è");
			    str= str.replace(/%E9/gi,"é");
			    str= str.replace(/%EA/gi,"ê");
			    str= str.replace(/%EB/gi,"ë");
			    str= str.replace(/%EC/gi,"ì");
			    str= str.replace(/%ED/gi,"í");
			    str= str.replace(/%EE/gi,"î");
			    str= str.replace(/%EF/gi,"ï");
			    str= str.replace(/%F0/gi,"ð");
			    str= str.replace(/%F1/gi,"ñ");
			    str= str.replace(/%F2/gi,"ò");
			    str= str.replace(/%F3/gi,"ó");
			    str= str.replace(/%F4/gi,"ô");
			    str= str.replace(/%F5/gi,"õ");
			    str= str.replace(/%F6/gi,"ö");
			    str= str.replace(/%F7/gi,"÷");
			    str= str.replace(/%F8/gi,"ø");
			    str= str.replace(/%F9/gi,"ù");
			    str= str.replace(/%FA/gi,"ú");
			    str= str.replace(/%FB/gi,"û");
			    str= str.replace(/%FC/gi,"ü");
			    str= str.replace(/%FD/gi,"ý");
			    str= str.replace(/%FE/gi,"þ");	  
		 }
		   
		  
		  // Return the decoded string
     return str;
}

</script>


