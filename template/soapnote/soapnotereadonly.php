<div id="container">
	<div id="header">
		<!header>
	</div>
	
	<div id="sidebar">
		<!sidebar>
	</div>

<script type="text/javascript">



function tabs(obj, el){
	//alert(obj);
	$(".mydiv").hide();
	$("#"+obj).show();
	$('.newtab li a span').removeClass('textblue');
	$(el).find('span').addClass('textblue');
	$('.newtab li').removeClass('current');
	$(el).parent().addClass('current');
	
	jQuery("textarea.expand").TextAreaExpander();
    jQuery("textarea.expand_desc").TextAreaExpander();
    jQuery("textarea.expand_desc").css({"width":123});
}

function treeView(col,e)
{
//alert(col);
	var colData = $("#"+col);

//alert($(colData).css("display"));

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
jQuery("textarea.expand_desc").TextAreaExpander();
jQuery("textarea.expand_desc").css({"width":123});
}

var a=0;
function addData(){
	
	if(a < 2){
		$('#addy').append("<table cellpadding='5' cellspacing='0' style='width: 680px;' class='soap-note-subj'><tr><td style='width:10px; font-size:16px; color:#00699d;' class='linkCol' valign='top' align='center'> <a href='javascript:;' style='text-decoration:none;' onclick=treeView('history"+a+"',this)>+</a> </td><td class='heading' >History of Present Illness</td></tr></table><div id='history"+a+"' style='display:none;'><table cellpadding='5'  cellspacing='0' style='width: 680px;' class='soap-note-subj' align='center'><tr><td>&nbsp;</td><td >Location<br/><textarea class='expand' name=location[] ></textarea></td></tr><tr><td>&nbsp;</td><td >Onset<br/><textarea class='expand' name=onset[] ></textarea></td></tr><tr><td>&nbsp;</td><td >Provocation<br/><textarea class='expand' name=provocation[]  ></textarea></td></tr><tr><td>&nbsp;</td><td >Palliation<br/><textarea class='expand' name=palliation[]  ></textarea></td></tr><tr><td>&nbsp;</td><td >Quality<br/><textarea class='expand' name=quality[]  ></textarea></td></tr><tr><td>&nbsp;</td><td >Radiation<br/><textarea class='expand' name=radiation[]  ></textarea></td></tr><tr><td>&nbsp;</td><td>Severity<br/><textarea class='expand' name=severity[] ></textarea></td></tr><tr><td>&nbsp;</td><td>Duration<br/><textarea class='expand' name=duration[] ></textarea></td></tr><tr><td>&nbsp;</td><td>Timing<br/><textarea class='expand' name=timing[] ></textarea></td></tr><tr><td>&nbsp;</td><td>Associated signs/symptoms<br/><textarea class='expand' name=associated_signs_symptoms[] ></textarea></td></tr></table></div><table cellpadding='5'cellspacing='0'style='width:680px;'class='soap-note-subj'><tr><td style='width:10px;font-size:16px;color:#00699d;'class='linkCol' align='center'><a href='javascript:;' style='text-decoration:none;' onclick=treeView('review"+a+"',this)>+</a></td><td class='heading'>Review of Systems</td></tr></table><div id='review"+a+"' style='display:none;'><table cellpadding='5' cellspacing='0' style='width:680px;' class='soap-note-subj' align='center'><tr><td>&nbsp;</td><td>Constitutional<br/><textarea class='expand' name=constitutional[] ></textarea></td></tr><tr><td>&nbsp;</td><td>Eyes<br/><textarea class='expand' name=eyes[] ></textarea></td></tr><tr><td>&nbsp;</td><td>Ears, nose, mouth, throat<br/><textarea class='expand' name=ears_nose_mouth_throat[] ></textarea></td></tr><tr><td>&nbsp;</td><td>Cardiovascular<br/><textarea class='expand' name=cardiovascular[] ></textarea></td></tr><tr><td>&nbsp;</td><td>Respiratory<br/><textarea class='expand' name=respiratory[] ></textarea></td></tr><tr><td>&nbsp;</td><td>Gastrointestinal<br/><textarea class='expand' name=gastrointestinal[] ></textarea></td></tr><tr><td>&nbsp;</td><td>Genital-urinary<br/><textarea class='expand' name=genital_urinary[] ></textarea></td></tr><tr><td>&nbsp;</td><td>Musculo-skeletal<br/><textarea class='expand' name=musculo_skeletal[] ></textarea></td></tr><tr><td>&nbsp;</td><td>Skin<br/><textarea class='expand' name=skin[] ></textarea></td></tr><tr><td>&nbsp;</td><td>Neurological<br/><textarea class='expand' name=neurological[] ></textarea></td></tr><tr><td>&nbsp;</td><td>Psychiatric<br/><textarea class='expand' name=psychiatric[] ></textarea></td></tr><tr><td>&nbsp;</td><td>Endocrine<br/><textarea class='expand' name=endocrine[] ></textarea></td></tr><tr><td>&nbsp;</td><td>Hematology/lymphatic<br/><textarea class='expand' name=hematology_lymphatic[] ></textarea></td></tr><tr><td>&nbsp;</td><td>Allergy/immunology<br/><textarea class='expand' name=allergy_immunology[] ></textarea></td></tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr></table></div>");
	a++;
	if(a >1)
		$('#Button1').hide();	
	}else{
		$('#Button1').hide();
	
	}
jQuery("textarea.expand").TextAreaExpander();
jQuery("textarea.expand_desc").TextAreaExpander();
jQuery("textarea.expand_desc").css({"width":123});
}
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




$(document).ready(function()
{
$(".linkCol a").css({textDecoration:"none"});
var product_count	=	<!prouct_count>;
if(product_count > 6){
	for(var i=6;i< product_count;i++){
		//$('#product').append("<tr><td><input name='product_description"+i+"' type='text' maxlength='100' title='product_description'/></td><td class='small'><input name='arising"+i+"' type='text' maxlength='10' title='arising"+i+"'/></td><td class='small'><input name='breakfast"+i+"' type='text'  maxlength='10' title='breakfast"+i+"'/></td><td class='small'><input name='mid_am"+i+"' type='text'  maxlength='10' title='mid_am"+i+"'/></td><td class='small'><input name='lunch"+i+"' type='text'  maxlength='10'  title='lunch"+i+"'/></td><td class='small'><input name='mid_pm"+i+"' type='text'  maxlength='10'  title='mid_pm"+i+"'/></td><td class='small'><input name='dinner"+i+"' type='text'  maxlength='10' title='dinner"+i+"'/></td><td class='small'><input name='before_bed"+i+"' type='text'  maxlength='10' title='before_bed"+i+"'/></td><td><input name='comments"+i+"' type='text'  maxlength='100' title='comments"+i+"'/></td></tr>");
        $('#product').append("<tr><td><textarea name='product_description"+i+"' class='expand_desc' title='product_description"+i+"' style='width: 123px; height: 18px;'></textarea></td><td class='small'><input name='arising"+i+"' id='arising"+i+"_id' type='text' maxlength='10' title='arising"+i+"'/><div id='div_arising"+i+"_id'></div></td><td class='small'><input name='breakfast"+i+"' id='breakfast"+i+"_id' type='text'  maxlength='10' title='breakfast"+i+"'/><div id='div_breakfast"+i+"_id'></div></td><td class='small'><input name='mid_am"+i+"' id='mid_am"+i+"_id' type='text'  maxlength='10' title='mid_am"+i+"'/><div id='div_mid_am"+i+"_id'></div></td><td class='small'><input name='lunch"+i+"' id='lunch"+i+"_id' type='text'  maxlength='10'  title='lunch"+i+"'/><div id='div_lunch"+i+"_id'></div></td><td class='small'><input name='mid_pm"+i+"' id='mid_pm"+i+"_id' type='text'  maxlength='10'  title='mid_pm"+i+"'/><div id='div_mid_pm"+i+"_id'></div></td><td class='small'><input name='dinner"+i+"' id='dinner"+i+"_id' type='text'  maxlength='10' title='dinner"+i+"'/><div id='div_dinner"+i+"_id'></div></td><td class='small'><input name='before_bed"+i+"' id='before_bed"+i+"_id' type='text'  maxlength='10' title='before_bed"+i+"'/><div id='div_before_bed"+i+"_id'></div></td><td><textarea name='comments"+i+"' class='expand_desc' maxlength='100' title='comments"+i+"' style='width: 123px; height: 18px;'></textarea><input name='comments"+i+"' type='text'  maxlength='100' title='comments"+i+"'/></td></tr>");
        }
}
var data1={<!stateJavascript>};
var cc1=0;
var cc2=0;
for(var prop in data1){
    var statevalue=data1[prop];
    //alert($("input[name="+prop+"]").attr("type"));
    // For Input
    var ObjElement=$("input[name="+prop+"]");

     if($(ObjElement).attr('type')=='text' ){
       divid=$(ObjElement).attr('id');
        divid='#div_' + divid;
        $(divid).css("width", "40px");
        $(divid).css("word-wrap", "break-word");
        $(divid).html(Encoder.htmlEncode(URLDecode(statevalue)));
        $(ObjElement).hide(); 
        
       //$(ObjElement).val(URLDecode(statevalue));
       //$(ObjElement).attr('readonly','readonly'); 
    }
    // For Textarea
     if(prop=='chief_complaint1')
         cc1=1;
     if(prop=='chief_complaint2')
         cc2=1;
         
    var ObjElement=$("textarea[name="+prop+"]");
        $(ObjElement).val(URLDecode(statevalue));
        $(ObjElement).attr('readonly','readonly');
		}
if(cc1==1)
{$("#cc1").show();} 
if(cc2==1)
{$("#cc2").show();}


	var cmn=$("table.soap-note-prog tr td input");
	$(cmn).each(function()
	{
    	$(this).attr('readonly','readonly');
	});
	var ObjElement=$("textarea");
	$(ObjElement).each(function()
		    {
		$(this).attr('readonly','readonly');
					
			 });

});
Encoder = {

	// When encoding do we convert characters into html or numerical entities
	EncodeType : "entity",  // entity OR numerical

	isEmpty : function(val){
		if(val){
			return ((val===null) || val.length==0 || /^\s+$/.test(val));
		}else{
			return true;
		}
	},
	
	// arrays for conversion from HTML Entities to Numerical values
	arr1: ['&nbsp;','&iexcl;','&cent;','&pound;','&curren;','&yen;','&brvbar;','&sect;','&uml;','&copy;','&ordf;','&laquo;','&not;','&shy;','&reg;','&macr;','&deg;','&plusmn;','&sup2;','&sup3;','&acute;','&micro;','&para;','&middot;','&cedil;','&sup1;','&ordm;','&raquo;','&frac14;','&frac12;','&frac34;','&iquest;','&Agrave;','&Aacute;','&Acirc;','&Atilde;','&Auml;','&Aring;','&AElig;','&Ccedil;','&Egrave;','&Eacute;','&Ecirc;','&Euml;','&Igrave;','&Iacute;','&Icirc;','&Iuml;','&ETH;','&Ntilde;','&Ograve;','&Oacute;','&Ocirc;','&Otilde;','&Ouml;','&times;','&Oslash;','&Ugrave;','&Uacute;','&Ucirc;','&Uuml;','&Yacute;','&THORN;','&szlig;','&agrave;','&aacute;','&acirc;','&atilde;','&auml;','&aring;','&aelig;','&ccedil;','&egrave;','&eacute;','&ecirc;','&euml;','&igrave;','&iacute;','&icirc;','&iuml;','&eth;','&ntilde;','&ograve;','&oacute;','&ocirc;','&otilde;','&ouml;','&divide;','&oslash;','&ugrave;','&uacute;','&ucirc;','&uuml;','&yacute;','&thorn;','&yuml;','&quot;','&amp;','&lt;','&gt;','&OElig;','&oelig;','&Scaron;','&scaron;','&Yuml;','&circ;','&tilde;','&ensp;','&emsp;','&thinsp;','&zwnj;','&zwj;','&lrm;','&rlm;','&ndash;','&mdash;','&lsquo;','&rsquo;','&sbquo;','&ldquo;','&rdquo;','&bdquo;','&dagger;','&Dagger;','&permil;','&lsaquo;','&rsaquo;','&euro;','&fnof;','&Alpha;','&Beta;','&Gamma;','&Delta;','&Epsilon;','&Zeta;','&Eta;','&Theta;','&Iota;','&Kappa;','&Lambda;','&Mu;','&Nu;','&Xi;','&Omicron;','&Pi;','&Rho;','&Sigma;','&Tau;','&Upsilon;','&Phi;','&Chi;','&Psi;','&Omega;','&alpha;','&beta;','&gamma;','&delta;','&epsilon;','&zeta;','&eta;','&theta;','&iota;','&kappa;','&lambda;','&mu;','&nu;','&xi;','&omicron;','&pi;','&rho;','&sigmaf;','&sigma;','&tau;','&upsilon;','&phi;','&chi;','&psi;','&omega;','&thetasym;','&upsih;','&piv;','&bull;','&hellip;','&prime;','&Prime;','&oline;','&frasl;','&weierp;','&image;','&real;','&trade;','&alefsym;','&larr;','&uarr;','&rarr;','&darr;','&harr;','&crarr;','&lArr;','&uArr;','&rArr;','&dArr;','&hArr;','&forall;','&part;','&exist;','&empty;','&nabla;','&isin;','&notin;','&ni;','&prod;','&sum;','&minus;','&lowast;','&radic;','&prop;','&infin;','&ang;','&and;','&or;','&cap;','&cup;','&int;','&there4;','&sim;','&cong;','&asymp;','&ne;','&equiv;','&le;','&ge;','&sub;','&sup;','&nsub;','&sube;','&supe;','&oplus;','&otimes;','&perp;','&sdot;','&lceil;','&rceil;','&lfloor;','&rfloor;','&lang;','&rang;','&loz;','&spades;','&clubs;','&hearts;','&diams;'],
	arr2: ['&#160;','&#161;','&#162;','&#163;','&#164;','&#165;','&#166;','&#167;','&#168;','&#169;','&#170;','&#171;','&#172;','&#173;','&#174;','&#175;','&#176;','&#177;','&#178;','&#179;','&#180;','&#181;','&#182;','&#183;','&#184;','&#185;','&#186;','&#187;','&#188;','&#189;','&#190;','&#191;','&#192;','&#193;','&#194;','&#195;','&#196;','&#197;','&#198;','&#199;','&#200;','&#201;','&#202;','&#203;','&#204;','&#205;','&#206;','&#207;','&#208;','&#209;','&#210;','&#211;','&#212;','&#213;','&#214;','&#215;','&#216;','&#217;','&#218;','&#219;','&#220;','&#221;','&#222;','&#223;','&#224;','&#225;','&#226;','&#227;','&#228;','&#229;','&#230;','&#231;','&#232;','&#233;','&#234;','&#235;','&#236;','&#237;','&#238;','&#239;','&#240;','&#241;','&#242;','&#243;','&#244;','&#245;','&#246;','&#247;','&#248;','&#249;','&#250;','&#251;','&#252;','&#253;','&#254;','&#255;','&#34;','&#38;','&#60;','&#62;','&#338;','&#339;','&#352;','&#353;','&#376;','&#710;','&#732;','&#8194;','&#8195;','&#8201;','&#8204;','&#8205;','&#8206;','&#8207;','&#8211;','&#8212;','&#8216;','&#8217;','&#8218;','&#8220;','&#8221;','&#8222;','&#8224;','&#8225;','&#8240;','&#8249;','&#8250;','&#8364;','&#402;','&#913;','&#914;','&#915;','&#916;','&#917;','&#918;','&#919;','&#920;','&#921;','&#922;','&#923;','&#924;','&#925;','&#926;','&#927;','&#928;','&#929;','&#931;','&#932;','&#933;','&#934;','&#935;','&#936;','&#937;','&#945;','&#946;','&#947;','&#948;','&#949;','&#950;','&#951;','&#952;','&#953;','&#954;','&#955;','&#956;','&#957;','&#958;','&#959;','&#960;','&#961;','&#962;','&#963;','&#964;','&#965;','&#966;','&#967;','&#968;','&#969;','&#977;','&#978;','&#982;','&#8226;','&#8230;','&#8242;','&#8243;','&#8254;','&#8260;','&#8472;','&#8465;','&#8476;','&#8482;','&#8501;','&#8592;','&#8593;','&#8594;','&#8595;','&#8596;','&#8629;','&#8656;','&#8657;','&#8658;','&#8659;','&#8660;','&#8704;','&#8706;','&#8707;','&#8709;','&#8711;','&#8712;','&#8713;','&#8715;','&#8719;','&#8721;','&#8722;','&#8727;','&#8730;','&#8733;','&#8734;','&#8736;','&#8743;','&#8744;','&#8745;','&#8746;','&#8747;','&#8756;','&#8764;','&#8773;','&#8776;','&#8800;','&#8801;','&#8804;','&#8805;','&#8834;','&#8835;','&#8836;','&#8838;','&#8839;','&#8853;','&#8855;','&#8869;','&#8901;','&#8968;','&#8969;','&#8970;','&#8971;','&#9001;','&#9002;','&#9674;','&#9824;','&#9827;','&#9829;','&#9830;'],
		
	// Convert HTML entities into numerical entities
	HTML2Numerical : function(s){
		return this.swapArrayVals(s,this.arr1,this.arr2);
	},	

	// Convert Numerical entities into HTML entities
	NumericalToHTML : function(s){
		return this.swapArrayVals(s,this.arr2,this.arr1);
	},


	// Numerically encodes all unicode characters
	numEncode : function(s){
		
		if(this.isEmpty(s)) return "";

		var e = "";
		for (var i = 0; i < s.length; i++)
		{
			var c = s.charAt(i);
			if (c < " " || c > "~")
			{
				c = "&#" + c.charCodeAt() + ";";
			}
			e += c;
		}
		return e;
	},
	
	// HTML Decode numerical and HTML entities back to original values
	htmlDecode : function(s){

		var c,m,d = s;
		
		if(this.isEmpty(d)) return "";

		// convert HTML entites back to numerical entites first
		d = this.HTML2Numerical(d);
		
		// look for numerical entities &#34;
		arr=d.match(/&#[0-9]{1,5};/g);
		
		// if no matches found in string then skip
		if(arr!=null){
			for(var x=0;x<arr.length;x++){
				m = arr[x];
				c = m.substring(2,m.length-1); //get numeric part which is refernce to unicode character
				// if its a valid number we can decode
				if(c >= -32768 && c <= 65535){
					// decode every single match within string
					d = d.replace(m, String.fromCharCode(c));
				}else{
					d = d.replace(m, ""); //invalid so replace with nada
				}
			}			
		}

		return d;
	},		

	// encode an input string into either numerical or HTML entities
	htmlEncode : function(s,dbl){
			
		if(this.isEmpty(s)) return "";

		// do we allow double encoding? E.g will &amp; be turned into &amp;amp;
		dbl = dbl || false; //default to prevent double encoding
		
		// if allowing double encoding we do ampersands first
		if(dbl){
			if(this.EncodeType=="numerical"){
				s = s.replace(/&/g, "&#38;");
			}else{
				s = s.replace(/&/g, "&amp;");
			}
		}

		// convert the xss chars to numerical entities ' " < >
		s = this.XSSEncode(s,false);
		
		if(this.EncodeType=="numerical" || !dbl){
			// Now call function that will convert any HTML entities to numerical codes
			s = this.HTML2Numerical(s);
		}

		// Now encode all chars above 127 e.g unicode
		s = this.numEncode(s);

		// now we know anything that needs to be encoded has been converted to numerical entities we
		// can encode any ampersands & that are not part of encoded entities
		// to handle the fact that I need to do a negative check and handle multiple ampersands &&&
		// I am going to use a placeholder

		// if we don't want double encoded entities we ignore the & in existing entities
		if(!dbl){
			s = s.replace(/&#/g,"##AMPHASH##");
		
			if(this.EncodeType=="numerical"){
				s = s.replace(/&/g, "&#38;");
			}else{
				s = s.replace(/&/g, "&amp;");
			}

			s = s.replace(/##AMPHASH##/g,"&#");
		}
		
		// replace any malformed entities
		s = s.replace(/&#\d*([^\d;]|$)/g, "$1");

		if(!dbl){
			// safety check to correct any double encoded &amp;
			s = this.correctEncoding(s);
		}

		// now do we need to convert our numerical encoded string into entities
		if(this.EncodeType=="entity"){
			s = this.NumericalToHTML(s);
		}

		return s;					
	},

	// Encodes the basic 4 characters used to malform HTML in XSS hacks
	XSSEncode : function(s,en){
		if(!this.isEmpty(s)){
			en = en || true;
			// do we convert to numerical or html entity?
			if(en){
				s = s.replace(/\'/g,"&#39;"); //no HTML equivalent as &apos is not cross browser supported
				s = s.replace(/\"/g,"&quot;");
				s = s.replace(/</g,"&lt;");
				s = s.replace(/>/g,"&gt;");
			}else{
				s = s.replace(/\'/g,"&#39;"); //no HTML equivalent as &apos is not cross browser supported
				s = s.replace(/\"/g,"&#34;");
				s = s.replace(/</g,"&#60;");
				s = s.replace(/>/g,"&#62;");
			}
			return s;
		}else{
			return "";
		}
	},

	// returns true if a string contains html or numerical encoded entities
	hasEncoded : function(s){
		if(/&#[0-9]{1,5};/g.test(s)){
			return true;
		}else if(/&[A-Z]{2,6};/gi.test(s)){
			return true;
		}else{
			return false;
		}
	},

	// will remove any unicode characters
	stripUnicode : function(s){
		return s.replace(/[^\x20-\x7E]/g,"");
		
	},

	// corrects any double encoded &amp; entities e.g &amp;amp;
	correctEncoding : function(s){
		return s.replace(/(&amp;)(amp;)+/,"$1");
	},


	// Function to loop through an array swaping each item with the value from another array e.g swap HTML entities with Numericals
	swapArrayVals : function(s,arr1,arr2){
		if(this.isEmpty(s)) return "";
		var re;
		if(arr1 && arr2){
			//ShowDebug("in swapArrayVals arr1.length = " + arr1.length + " arr2.length = " + arr2.length)
			// array lengths must match
			if(arr1.length == arr2.length){
				for(var x=0,i=arr1.length;x<i;x++){
					re = new RegExp(arr1[x], 'g');
					s = s.replace(re,arr2[x]); //swap arr1 item with matching item from arr2	
				}
			}
		}
		return s;
	},

	inArray : function( item, arr ) {
		for ( var i = 0, x = arr.length; i < x; i++ ){
			if ( arr[i] === item ){
				return i;
			}
		}
		return -1;
	}

}

</script>

	
<div id="mainContent">
		
<table style="vertical-align:middle;width:700px;"><tr><td style="width:400px;">
	<div id="breadcrumbNav"  style=" padding-bottom:40px; padding-top:40px;">
        <a href="index.php" >HOME</a> / <a href="index.php?action=therapistViewPatient&id=<!id>" >VIEW <!patientname></a> / <span class="highlight">VIEW SOAP NOTE</span></div>
	</td><td style="width:300px;"></td></tr>
</table>
	

<input type="hidden" name="id" value="<!id>" >	
<ul class="newtab">
	<div style="float: right;"><a target="_blank" href="/index.php?action=printsoapnote&shopnoteid=<!shopnoteid>&printform=yes"><img src="/images/print-button.jpg"></a></div> 
    <li class="current"><a href="javascript:;" onclick="javascript:tabs('subjective',this);"><span class="textblue">Subjective</span></a></li>
	<li><a href="javascript:;" onclick="javascript:tabs('objective',this);"><span>Objective</span></a></li>
    <li><a href="javascript:;" onclick="javascript:tabs('assessment',this);"><span>Assessment</span></a></li>
    <li><a href="javascript:;" onclick="javascript:tabs('program',this);"><span>Program</span></a></li>
</ul>
<div style="clear:both;"></div>
<div style="border:#ccc 1px solid;padding:0px; margin-top:0px;">
<div style="background:url(/images/img-topnav-repeat.gif) top left repeat-x; height:30px;"></div>


<div id="subjective" class="mydiv">
<table cellpadding="5" cellspacing="0" style="width: 680px;" class="soap-note-subj" align="center">
	<tr><td>&nbsp;</td>
		<td >Chief Complaint<br/><textarea class="expand" name="chief_complaint"  ></textarea></td>

	</tr></table>

<table cellpadding="5" cellspacing="0" style="width: 680px;" class="soap-note-subj">	
	<tr><td style="width:10px; font-size:16px; color:#00699d;" class="linkCol" valign="top" align="center"> <a href="javascript:;" onclick="treeView('history',this);">+</a> </td><td class="heading" >History of Present Illness</td></tr>
	</table>
<div id="history" style="display:none;">	
<table cellpadding="5"  cellspacing="0" style="width: 680px;" class="soap-note-subj" align="center">
	<tr><td>&nbsp;</td>
		<td >Location<br/><textarea class="expand" name="location" ></textarea></td>
	</tr>
	<tr><td>&nbsp;</td>
		<td >Onset<br/><textarea class="expand" name="onset" ></textarea></td>

	</tr>
	<tr><td>&nbsp;</td>
		<td >Provocation<br/><textarea class="expand" name="provocation" ></textarea></td>
	</tr>
	<tr><td>&nbsp;</td>
		<td >Palliation<br/><textarea class="expand" name="palliation" ></textarea></td>
	</tr>

	<tr><td>&nbsp;</td>
		<td >Quality<br/><textarea class="expand" name="quality" ></textarea></td>
	</tr>
	<tr><td>&nbsp;</td>
		<td >Radiation<br/><textarea class="expand" name="radiation" ></textarea></td>
	</tr>
	<tr><td>&nbsp;</td>

		<td >Severity<br/><textarea class="expand" name="severity" ></textarea></td>
	</tr>
	<tr><td>&nbsp;</td>
		<td >Duration<br/><textarea class="expand" name="duration" ></textarea></td>
	</tr>
	<tr><td>&nbsp;</td>
		<td >Timing<br/><textarea class="expand" name="timing" ></textarea></td>

	</tr>
	<tr><td>&nbsp;</td>
		<td >Associated signs/symptoms<br/><textarea class="expand" name="associated_signs_symptoms" ></textarea></td>
	</tr>
	</table>
</div>	
<table cellpadding="5" cellspacing="0" style="width: 680px;" class="soap-note-subj">
	<tr><td style="width:10px; font-size:16px; color:#00699d;" class="linkCol"> 
	<a href="javascript:;" onclick="treeView('review',this);">+</a>
	<!--<a href="javascript:;" onclick="treeView('review',this);">-</a>--> </td><td class="heading">Review of Systems</td></tr>
	</table>
<div id="review" style="display:none;">
<table cellpadding="5" cellspacing="0" style="width: 680px;" class="soap-note-subj" align="center">
<tr><td>&nbsp;</td>

		<td >Constitutional <br/><textarea class="expand" name="constitutional" ></textarea></td>
	</tr>

<tr><td>&nbsp;</td>
		<td >Eyes<br/><textarea class="expand" name="eyes" ></textarea></td>
	</tr>

<tr><td>&nbsp;</td>
		<td >Ears, nose, mouth, throat<br/><textarea class="expand" name="ears_nose_mouth_throat" ></textarea></td>

	</tr>

<tr><td>&nbsp;</td>
		<td >Cardiovascular<br/><textarea class="expand" name="cardiovascular" ></textarea></td>
	</tr>

<tr><td>&nbsp;</td>
		<td >Respiratory<br/><textarea class="expand" name="respiratory" ></textarea></td>
	</tr>

    <tr><td>&nbsp;</td>
		<td >Gastrointestinal<br/><textarea class="expand" name="gastrointestinal" ></textarea></td>
	</tr>
	<tr><td>&nbsp;</td>
		<td >Genital-urinary<br/><textarea class="expand" name="genital_urinary" ></textarea></td>
	</tr>

<tr><td>&nbsp;</td>
		<td >Musculo-skeletal<br/><textarea class="expand" name="musculo_skeletal" ></textarea></td>
	</tr>
<tr><td>&nbsp;</td>
		<td >Skin<br/><textarea class="expand" name="skin" ></textarea></td>
	</tr>
<tr><td>&nbsp;</td>
		<td >Neurological<br/><textarea class="expand" name="neurological" ></textarea></td>

	</tr>
<tr><td>&nbsp;</td>
		<td >Psychiatric<br/><textarea class="expand" name="psychiatric" ></textarea></td>
	</tr>
<tr><td>&nbsp;</td>
		<td >Endocrine<br/><textarea class="expand" name="endocrine" ></textarea></td>
	</tr>
<tr><td>&nbsp;</td>

		<td >Hematology/lymphatic<br/><textarea class="expand" name="hematology_lymphatic" ></textarea></td>
	</tr>
<tr><td>&nbsp;</td>
		<td >Allergy/immunology<br/><textarea class="expand" name="allergy_immunology" ></textarea></td>
	</tr>
<tr><td>&nbsp;</td>
		<td >&nbsp;</td>
	</tr>
</table>
</div>
<div id="addy"></div>
<div id="cc1" style="display: none;">
<table cellpadding="5" cellspacing="0" style="width: 680px;" class="soap-note-subj" align="center">
	<tr><td>&nbsp;</td>
		<td >Chief Complaint<br/><textarea class="expand" name="chief_complaint1"  ></textarea></td>

	</tr></table>
<table cellpadding='5' cellspacing='0' style='width: 680px;' class='soap-note-subj'><tr><td style='width:12px; font-size:16px; color:#00699d;' class='linkCol' valign='top' align='center'> <a href='javascript:;' style='text-decoration:none;' onclick=treeView('history1',this)>+</a> </td><td class='heading' >History of Present Illness</td></tr></table><div id='history1' style='display:none;'><table cellpadding='5'  cellspacing='0' style='width: 680px;' class='soap-note-subj' align='center'><tr><td>&nbsp;</td><td >Location<br/><textarea class='expand' name="location1" ></textarea></td></tr><tr><td>&nbsp;</td><td >Onset<br/><textarea class='expand' name="onset1" ></textarea></td></tr><tr><td>&nbsp;</td><td >Provocation<br/><textarea class='expand' name="provocation1"  ></textarea></td></tr><tr><td>&nbsp;</td><td >Palliation<br/><textarea class='expand' name="palliation1"  ></textarea></td></tr><tr><td>&nbsp;</td><td >Quality<br/><textarea class='expand' name="quality1"  ></textarea></td></tr><tr><td>&nbsp;</td><td >Radiation<br/><textarea class='expand' name="radiation1"  ></textarea></td></tr><tr><td>&nbsp;</td><td>Severity<br/><textarea class='expand' name="severity1" ></textarea></td></tr><tr><td>&nbsp;</td><td>Duration<br/><textarea class='expand' name="duration1" ></textarea></td></tr><tr><td>&nbsp;</td><td>Timing<br/><textarea class='expand' name="timing1" ></textarea></td></tr><tr><td>&nbsp;</td><td>Associated signs/symptoms<br/><textarea class='expand' name="associated_signs_symptoms1" ></textarea></td></tr></table></div><table cellpadding='5'cellspacing='0'style='width:680px;'class='soap-note-subj'><tr><td style='width:12px;font-size:16px;color:#00699d;'class='linkCol'><a href='javascript:;' style='text-decoration:none;' onclick=treeView('review1',this)>+</a></td><td class='heading'>Review of Systems</td></tr></table><div id='review1' style='display:none;'><table cellpadding='5' cellspacing='0' style='width:680px;' class='soap-note-subj' align='center'><tr><td>&nbsp;</td><td>Constitutional<br/><textarea class='expand' name="constitutional1" ></textarea></td></tr><tr><td>&nbsp;</td><td>Eyes<br/><textarea class='expand' name="eyes1" ></textarea></td></tr><tr><td>&nbsp;</td><td>Ears, nose, mouth, throat<br/><textarea class='expand' name="ears_nose_mouth_throat1" ></textarea></td></tr><tr><td>&nbsp;</td><td>Cardiovascular<br/><textarea class='expand' name="cardiovascular1" ></textarea></td></tr><tr><td>&nbsp;</td><td>Respiratory<br/><textarea class='expand' name="respiratory1" ></textarea></td></tr><tr><td>&nbsp;</td><td>Gastrointestinal<br/><textarea class='expand' name="gastrointestinal1" ></textarea></td></tr><tr><td>&nbsp;</td><td>Genital-urinary<br/><textarea class='expand' name="genital_urinary1" ></textarea></td></tr><tr><td>&nbsp;</td><td>Musculo-skeletal<br/><textarea class='expand' name="musculo_skeletal1" ></textarea></td></tr><tr><td>&nbsp;</td><td>Skin<br/><textarea class='expand' name="skin1" ></textarea></td></tr><tr><td>&nbsp;</td><td>Neurological<br/><textarea class='expand' name="neurological1" ></textarea></td></tr><tr><td>&nbsp;</td><td>Psychiatric<br/><textarea class='expand' name="psychiatric1" ></textarea></td></tr><tr><td>&nbsp;</td><td>Endocrine<br/><textarea class='expand' name="endocrine1" ></textarea></td></tr><tr><td>&nbsp;</td><td>Hematology/lymphatic<br/><textarea class='expand' name="hematology_lymphatic1" ></textarea></td></tr><tr><td>&nbsp;</td><td>Allergy/immunology<br/><textarea class='expand' name="allergy_immunology1" ></textarea></td></tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr></table></div>
</div>
<div id="cc2" style="display: none;">
<table cellpadding="5" cellspacing="0" style="width: 680px;" class="soap-note-subj" align="center">
	<tr><td>&nbsp;</td>
		<td >Chief Complaint<br/><textarea class="expand" name="chief_complaint2"  ></textarea></td>

	</tr></table>
<table cellpadding='5' cellspacing='0' style='width: 680px;' class='soap-note-subj'><tr><td style='width:12px; font-size:16px; color:#00699d;' class='linkCol' valign='top' align='center'> <a href='javascript:;' style='text-decoration:none;' onclick=treeView('history2',this)>+</a> </td><td class='heading' >History of Present Illness</td></tr></table><div id='history2' style='display:none;'><table cellpadding='5'  cellspacing='0' style='width: 680px;' class='soap-note-subj' align='center'><tr><td>&nbsp;</td><td >Location<br/><textarea class='expand' name="location2" ></textarea></td></tr><tr><td>&nbsp;</td><td >Onset<br/><textarea class='expand' name="onset2" ></textarea></td></tr><tr><td>&nbsp;</td><td >Provocation<br/><textarea class='expand' name="provocation2"  ></textarea></td></tr><tr><td>&nbsp;</td><td >Palliation<br/><textarea class='expand' name="palliation2"  ></textarea></td></tr><tr><td>&nbsp;</td><td >Quality<br/><textarea class='expand' name="quality2"  ></textarea></td></tr><tr><td>&nbsp;</td><td >Radiation<br/><textarea class='expand' name="radiation2"  ></textarea></td></tr><tr><td>&nbsp;</td><td>Severity<br/><textarea class='expand' name="severity2" ></textarea></td></tr><tr><td>&nbsp;</td><td>Duration<br/><textarea class='expand' name="duration2" ></textarea></td></tr><tr><td>&nbsp;</td><td>Timing<br/><textarea class='expand' name="timing2" ></textarea></td></tr><tr><td>&nbsp;</td><td>Associated signs/symptoms<br/><textarea class='expand' name="associated_signs_symptoms2" ></textarea></td></tr></table></div><table cellpadding='5'cellspacing='0'style='width:680px;'class='soap-note-subj'><tr><td style='width:12px;font-size:16px;color:#00699d;'class='linkCol' align='center'><a href='javascript:;' style='text-decoration:none;' onclick=treeView('review2',this)>+</a></td><td class='heading'>Review of Systems</td></tr></table><div id='review2' style='display:none;'><table cellpadding='5' cellspacing='0' style='width:680px;' class='soap-note-subj' align='center'><tr><td>&nbsp;</td><td>Constitutional<br/><textarea class='expand' name="constitutional2" ></textarea></td></tr><tr><td>&nbsp;</td><td>Eyes<br/><textarea class='expand' name="eyes2" ></textarea></td></tr><tr><td>&nbsp;</td><td>Ears, nose, mouth, throat<br/><textarea class='expand' name="ears_nose_mouth_throat2" ></textarea></td></tr><tr><td>&nbsp;</td><td>Cardiovascular<br/><textarea class='expand' name="cardiovascular2" ></textarea></td></tr><tr><td>&nbsp;</td><td>Respiratory<br/><textarea class='expand' name="respiratory2" ></textarea></td></tr><tr><td>&nbsp;</td><td>Gastrointestinal<br/><textarea class='expand' name="gastrointestinal2" ></textarea></td></tr><tr><td>&nbsp;</td><td>Genital-urinary<br/><textarea class='expand' name="genital_urinary2" ></textarea></td></tr><tr><td>&nbsp;</td><td>Musculo-skeletal<br/><textarea class='expand' name="musculo_skeletal2" ></textarea></td></tr><tr><td>&nbsp;</td><td>Skin<br/><textarea class='expand' name="skin2" ></textarea></td></tr><tr><td>&nbsp;</td><td>Neurological<br/><textarea class='expand' name="neurological2" ></textarea></td></tr><tr><td>&nbsp;</td><td>Psychiatric<br/><textarea class='expand' name="psychiatric2" ></textarea></td></tr><tr><td>&nbsp;</td><td>Endocrine<br/><textarea class='expand' name="endocrine2" ></textarea></td></tr><tr><td>&nbsp;</td><td>Hematology/lymphatic<br/><textarea class='expand' name="hematology_lymphatic2" ></textarea></td></tr><tr><td>&nbsp;</td><td>Allergy/immunology<br/><textarea class='expand' name="allergy_immunology2" ></textarea></td></tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr></table></div>
</div>
<!--<table cellpadding="5" cellspacing="0" style="width: 680px;" class="soap-note-subj" align="center">
<tr><td>&nbsp;</td>
		<td align="right" ><input name="Button1" id="Button1" type="button" onclick="addData();" value="Add Chief Complaint" /></td>
	</tr>
<tr><td>&nbsp;</td>
		<td >&nbsp;</td>
	</tr>
</table>
--></div>
<div id="objective" style="display:none;" class="mydiv">
<table cellpadding="5" cellspacing="0" style="width: 680px;" class="soap-note-obj" align="center">
	<tr>
		<td>Vitals<br/><textarea class="expand" name="ob_vitals" ></textarea></td>

	</tr>
	<tr>
		<td>Constitutional <br/><textarea class="expand" name="ob_constitutional" ></textarea></td>
	</tr>
	<tr>
		<td>Eyes<br/><textarea class="expand" name="ob_eyes" ></textarea></td>
	</tr>

	<tr>
		<td>Ears, nose, mouth, throat<br/><textarea class="expand" name="ob_ears_nose_mouth_throat" ></textarea></td>
	</tr>
	<tr>
		<td>Cardiovascular<br/><textarea class="expand" name="ob_cardiovascular" ></textarea></td>
	</tr>
	<tr>

		<td>Respiratory<br/><textarea class="expand" name="ob_respiratory" ></textarea></td>
	</tr>
	<tr>
		<td>Gastrointestinal<br/><textarea class="expand" name="ob_gastrointestinal" ></textarea></td>
	</tr>
	<tr>
		<td>Genital-urinary<br/><textarea class="expand" name="ob_genital_urinary" ></textarea></td>

	</tr>
	<tr>
		<td>Musculo-skeletal<br/><textarea class="expand" name="ob_musculo_skeletal" ></textarea></td>
	</tr>
	<tr>
		<td>Skin<br/><textarea class="expand" name="ob_skin" ></textarea></td>
	</tr>

	<tr>
		<td>Neurological<br/><textarea class="expand" name="ob_neurological" ></textarea></td>
	</tr>

<tr>
		<td>Psychiatric<br/><textarea class="expand" name="ob_psychiatric" ></textarea></td>
	</tr>

<tr>
		<td>Endocrine<br/><textarea class="expand" name="ob_endocrine" ></textarea></td>
	</tr>

<tr>
		<td>Hematology/ lymphatic<br/><textarea class="expand" name="ob_hematology_lymphatic" ></textarea></td>
	</tr>

<tr>

		<td>Allergy/ immunology<br/><textarea class="expand" name="ob_allergy_immunology" ></textarea></td>
	</tr>

<tr>
		<td>EAV<br/><textarea class="expand" name="ob_eav" ></textarea></td>
	</tr>

<tr>
		<td>Laboratory<br/><textarea class="expand" name="ob_laboratory" ></textarea></td>

	</tr>

<tr>
		<td>&nbsp;</td>
	</tr>


</table>
</div>
<div id="assessment" style="display:none;" class="mydiv">
<table cellpadding="5" cellspacing="0" style="width: 680px;" class="soap-note-ass" align="center">
	<tr><td valign="top" style="width:80px;">DX:</td>
		<td ><textarea class="expand" name="dx" ></textarea></td>

	</tr>

	
	<tr><td valign="top">DDX:</td>
		<td ><textarea class="expand" name="ddx" ></textarea></td>
	</tr>
	
<tr><td>&nbsp;</td>
		<td >&nbsp;</td>
	</tr>


</table>
</div>
<div id="program" style="display:none;" class="mydiv">
<table cellpadding="0" cellspacing="0" style="width: 680px;" align="center">
<tr><td>
<b class="soap-note-prog-b-h"><!clinicname></b>
<b class="soap-note-prog-g-h">RECOMMENDED PATIENT PROTOCOL / TREATMENT PROGRAM</b>


	<table cellpadding="5" cellspacing="0" style="width: 100%">
		<tr>
			<td style="width:35px;"><strong>Patient:</strong></td>
			<td style="width:200px;"><!patientname></td>
			
			<td align="right"><strong>Doctor:</strong></td>
			<td><!providername></td>

			<td align="right"><strong>Date:</strong></td>
			<td align="right" ><!currentdate></td>
		</tr>
		
	</table>
	<p style=" font-family:Verdana;">The following products and/or protocols have been specifically chosen for their effectiveness, quality and potency, so it is of tantamount importance that you follow the instructions exactly. Any deviation from the specific recommendations may influence the success of your treatment program. If you have any questions please let us know immediately.</p>
<b class="soap-note-prog-b-s">PLEASE BRING THIS SHEET IN WITH YOU WHEN PICKING UP REFILLS.</b>

<table cellpadding="0" cellspacing="0" style="width: 100%"
									class="soap-note-prog-bottom">

									<tr>
										<td>Dietary Instructions:<br /> <textarea class="expand"
												name="dietary_instructions" title="dietary_instructions"></textarea>
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
									</tr>
								</table>
	<table cellpadding="6" cellspacing="0" style="width: 100%" class="soap-note-prog" id="product"  >
		<tr>
			<td valign="bottom"><b>Product Description</b></td>
						<td><img alt="" title="" height="44" src="/images/img-arising.jpg" width="12" /></td>
			<td><img alt="" title="" src="/images/img-break-fast.jpg" /></td>
			<td><img alt="" title="" src="/images/img-mid-am.jpg" /></td>
			<td><img alt="" title="" src="/images/img-lunch.jpg" /></td>

			<td><img alt="" title="" src="/images/img-mid-pm.jpg" /></td>
			<td><img alt="" title="" src="/images/img-dinner.jpg" /></td>
			<td><img alt="" title="" src="/images/img-before-bed.jpg" /></td>
			<td valign="bottom"><b>Comments</b></td>
		</tr>
		<tr>
			<td><textarea name="product_description0" class="expand_desc" style="width: 123px; height: 18px;"></textarea></td><!--<input name="product_description0" type="text" /></td>-->
			<td class="small"><input name="arising0" id="arising0_id" type="text" /><div id="div_arising0_id"></div></td>
			<td class="small"><input name="breakfast0" id="breakfast0_id" type="text" /><div id="div_breakfast0_id"></div></td>
			<td class="small"><input name="mid_am0" id="mid_am0_id" type="text" /><div id="div_mid_am0_id"></div></td>
			<td class="small"><input name="lunch0" id="lunch0_id" type="text" /><div id="div_lunch0_id"></div></td>
			<td class="small"><input name="mid_pm0" id="mid_pm0_id" type="text" /><div id="div_mid_pm0_id"></div></td>
			<td class="small"><input name="dinner0" id="dinner0_id" type="text" /><div id="div_dinner0_id"></div></td>
			<td class="small"><input name="before_bed0" id="before_bed0" type="text" /><div id="div_before_bed0"></div></td>
			<td><textarea name="comments0" class="expand_desc" style="width: 123px; height: 18px;"></textarea></td><!--<input name="comments0" type="text" /></td>-->
		</tr>
		<tr>
			<td><textarea name="product_description1" class="expand_desc" style="width: 123px; height: 18px;"></textarea></td><!--<input name="product_description1" type="text" /></td>-->
			<td class="small"><input name="arising1" id="arising1_id" type="text" /><div id="div_arising1_id"></div></td>
			<td class="small"><input name="breakfast1" id="breakfast1_id" type="text" /><div id="div_breakfast1_id"></div></td>
			<td class="small"><input name="mid_am1" id="mid_am1_id" type="text" /><div id="div_mid_am1_id"></div></td>
			<td class="small"><input name="lunch1" id="lunch1_id" type="text" /><div id="div_lunch1_id"></div></td>
			<td class="small"><input name="mid_pm1" id="mid_pm1_id" type="text" /><div id="div_mid_pm1_id"></div></td>
			<td class="small"><input name="dinner1" id="dinner1_id" type="text" /><div id="div_dinner1_id"></div></td>
			<td class="small"><input name="before_bed1" id="before_bed1" type="text" /><div id="div_before_bed1"></div></td>
			<td><textarea name="comments1" class="expand_desc" style="width: 123px; height: 18px;"></textarea></td><!--<input name="comments1" type="text" /></td>-->
		</tr>
		<tr>
			<td><textarea name="product_description2" class="expand_desc" style="width: 123px; height: 18px;"></textarea></td><!--<input name="product_description2" type="text" /></td>-->
			<td class="small"><input name="arising2" id="arising2_id" type="text" /><div id="div_arising2_id"></div></td>
			<td class="small"><input name="breakfast2" id="breakfast2_id" type="text" /><div id="div_breakfast2_id"></div></td>
			<td class="small"><input name="mid_am2" id="mid_am2_id" type="text" /><div id="div_mid_am2_id"></div></td>
			<td class="small"><input name="lunch2" id="lunch2_id" type="text" /><div id="div_lunch2_id"></div></td>
			<td class="small"><input name="mid_pm2" id="mid_pm2_id" type="text" /><div id="div_mid_pm2_id"></div></td>
			<td class="small"><input name="dinner2" id="dinner2_id" type="text" /><div id="div_dinner2_id"></div></td>
			<td class="small"><input name="before_bed2" id="before_bed2" type="text" /><div id="div_before_bed2"></div></td>
			<td><textarea name="comments2" class="expand_desc" style="width: 123px; height: 18px;"></textarea></td><!--<input name="comments2" type="text" /></td>-->

		</tr>

        <tr>
			<td><textarea name="product_description3" class="expand_desc" style="width: 123px; height: 18px;"></textarea></td><!--<input name="product_description3" type="text" /></td>-->
			<td class="small"><input name="arising3" id="arising3_id" type="text" /><div id="div_arising3_id"></div></td>
			<td class="small"><input name="breakfast3" id="breakfast3_id" type="text" /><div id="div_breakfast3_id"></div></td>
			<td class="small"><input name="mid_am3" id="mid_am3_id" type="text" /><div id="div_mid_am3_id"></div></td>
			<td class="small"><input name="lunch3" id="lunch3_id" type="text" /><div id="div_lunch3_id"></div></td>
			<td class="small"><input name="mid_pm3" id="mid_pm3_id" type="text" /><div id="div_mid_pm3_id"></div></td>
			<td class="small"><input name="dinner3" id="dinner3_id" type="text" /><div id="div_dinner3_id"></div></td>
			<td class="small"><input name="before_bed3" id="before_bed3" type="text" /><div id="div_before_bed3"></div></td>
			<td><textarea name="comments3" class="expand_desc" style="width: 123px; height: 18px;"></textarea></td><!--<input name="comments3" type="text" /></td>-->
		</tr>

         <tr>
			<td><textarea name="product_description4" class="expand_desc" style="width: 123px; height: 18px;"></textarea></td><!--<input name="product_description4" type="text" /></td>-->
			<td class="small"><input name="arising4" id="arising4_id" type="text" /><div id="div_arising4_id"></div></td>
			<td class="small"><input name="breakfast4" id="breakfast4_id" type="text" /><div id="div_breakfast4_id"></div></td>
			<td class="small"><input name="mid_am4" id="mid_am4_id" type="text" /><div id="div_mid_am4_id"></div></td>
			<td class="small"><input name="lunch4" id="lunch4_id" type="text" /><div id="div_lunch4_id"></div></td>
			<td class="small"><input name="mid_pm4" id="mid_pm4_id" type="text" /><div id="div_mid_pm4_id"></div></td>
			<td class="small"><input name="dinner4" id="dinner4_id" type="text" /><div id="div_dinner4_id"></div></td>
			<td class="small"><input name="before_bed4" id="before_bed4" type="text" /><div id="div_before_bed4"></div></td>
			<td><textarea name="comments4" class="expand_desc" style="width: 123px; height: 18px;"></textarea></td><!--<input name="comments4" type="text" /></td>-->
		</tr>

         <tr>
			<td><textarea name="product_description5" class="expand_desc" style="width: 123px; height: 18px;"></textarea></td><!--<input name="product_description5" type="text" /></td>-->
			<td class="small"><input name="arising5" id="arising5_id" type="text" /><div id="div_arising5_id"></div></td>
			<td class="small"><input name="breakfast5" id="breakfast5_id" type="text" /><div id="div_breakfast5_id"></div></td>
			<td class="small"><input name="mid_am5" id="mid_am5_id" type="text" /><div id="div_mid_am5_id"></div></td>
			<td class="small"><input name="lunch5" id="lunch5_id" type="text" /><div id="div_lunch5_id"></div></td>
			<td class="small"><input name="mid_pm5" id="mid_pm5_id" type="text" /><div id="div_mid_pm5_id"></div></td>
			<td class="small"><input name="dinner5" id="dinner5_id" type="text" /><div id="div_dinner5_id"></div></td>
			<td class="small"><input name="before_bed5" id="before_bed5" type="text" /><div id="div_before_bed5"></div></td>
			<td><textarea name="comments5" class="expand_desc" style="width: 123px; height: 18px;"></textarea></td><!--<input name="comments5" type="text" /></td>-->
		</tr>

	</table>
	<div style="height:20px;"></div>
<div>


	<table cellpadding="0" cellspacing="0" style="width: 100%" class="soap-note-prog-bottom">

		
		<tr>
			<td>Notes and/or additional instructions:<br/><textarea class="expand" name="notes_instructions" ></textarea></td>
		</tr>
	</table>


</div></td></tr>
</table>

</div>







</div>	
	
</div></div>	

<script type="text/javascript" src="/js/jquery.textarea-expander.js"></script>	
	
	
