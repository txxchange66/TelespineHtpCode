<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Special Character</title>
	<link rel="stylesheet" type="text/css" href="../../richtextarea.css">
	<script language="JavaScript">
	<!--

	// get the opener information from the query string
	window.rta = window.opener.RichTextarea.getInstance('<?= $_GET['rta_id'] ?>');
	window.region = '<?= $_GET['rta_region'] ?>';

	// define the entities
	var entities = new Array(
		new Array('quot',     '#34;',   'quotation mark (APL quote)'),
		new Array('amp',      '#38;',   'ampersand'),
		new Array('lt',       '#60;',   'less-than sign'),
		new Array('gt',       '#62;',   'greater-than sign'),
		new Array('circ',     '#710;',  'modifier letter circumflex accent'),
		new Array('tilde',    '#732;',  'small tilde'),
		new Array('nbsp',     '#160;',  'non-breaking space'),
		new Array('ensp',     '#8194;', 'en space'),
		new Array('emsp',     '#8195;', 'em space'),
		new Array('thinsp',   '#8201;', 'thin space'),
		new Array('ndash',    '#8211;', 'en dash'),
		new Array('mdash',    '#8212;', 'em dash'),
		new Array('lsquo',    '#8216;', 'left single quotation mark'),
		new Array('rsquo',    '#8217;', 'right single quotation mark'),
		new Array('sbquo',    '#8218;', 'single low-9 quotation mark'),
		new Array('ldquo',    '#8220;', 'left double quotation mark'),
		new Array('rdquo',    '#8221;', 'right double quotation mark'),
		new Array('bdquo',    '#8222;', 'double low-9 quotation mark'),
		new Array('dagger',   '#8224;', 'dagger'),
		new Array('Dagger',   '#8225;', 'double dagger'),
		new Array('permil',   '#8240;', 'per mille sign'),
		new Array('lsaquo',   '#8249;', 'single left-pointing angle quotation mark'),
		new Array('rsaquo',   '#8250;', 'single right-pointing angle quotation mark'),
		new Array('iexcl',    '#161;',  'inverted exclamation mark'),
		new Array('cent',     '#162;',  'cent sign'),
		new Array('pound',    '#163;',  'pound sign'),
		new Array('curren',   '#164;',  'currency sign'),
		new Array('yen',      '#165;',  'yen sign'),
		new Array('brvbar',   '#166;',  'broken bar'),
		new Array('sect',     '#167;',  'section sign'),
		new Array('uml',      '#168;',  'spacing diaeresis'),
		new Array('copy',     '#169;',  'copyright sign'),
		new Array('ordf',     '#170;',  'feminine ordinal indicator'),
		new Array('ordm',     '#186;',  'masculine ordinal indicator'),
		new Array('laquo',    '#171;',  'left pointing guillemet'),
		new Array('not',      '#172;',  'not sign'),
		new Array('reg',      '#174;',  'registered trade mark sign'),
		new Array('macr',     '#175;',  'spacing macron'),
		new Array('deg',      '#176;',  'degree sign'),
		new Array('plusmn',   '#177;',  'plus-or-minus sign'),
		new Array('sup1',     '#185;',  'superscript one'),
		new Array('sup2',     '#178;',  'superscript digit two'),
		new Array('sup3',     '#179;',  'superscript digit three'),
		new Array('acute',    '#180;',  'acute accent'),
		new Array('micro',    '#181;',  'micro sign'),
		new Array('para',     '#182;',  'pilcrow sign'),
		new Array('middot',   '#183;',  'middle dot'),
		new Array('cedil',    '#184;',  'cedilla'),
		new Array('raquo',    '#187;',  'right-pointing double angle quotation mark,'),
		new Array('frac14',   '#188;',  'fraction one quarter'),
		new Array('frac12',   '#189;',  'fraction one half'),
		new Array('frac34',   '#190;',  'fraction three quarters'),
		new Array('iquest',   '#191;',  'inverted question mark'),
		new Array('times',    '#215;',  'multiplication sign'),
		new Array('divide',   '#247;',  'division sign'),

		new Array('Agrave',   '#192;',  'latin capital letter A with grave'),
		new Array('agrave',   '#224;',  'latin small letter a with grave'),
		new Array('Aacute',   '#193;',  'latin capital letter A with acute'),
		new Array('aacute',   '#225;',  'latin small letter a with acute'),
		new Array('Acirc',    '#194;',  'latin capital letter A with circumflex'),
		new Array('acirc',    '#226;',  'latin small letter a with circumflex'),
		new Array('Atilde',   '#195;',  'latin capital letter A with tilde'),
		new Array('atilde',   '#227;',  'latin small letter a with tilde'),
		new Array('Auml',     '#196;',  'latin capital letter A with diaeresis'),
		new Array('auml',     '#228;',  'latin small letter a with diaeresis'),
		new Array('Aring',    '#197;',  'latin capital letter A with ring above'),
		new Array('aring',    '#229;',  'latin small letter a with ring above'),

		new Array('Egrave',   '#200;',  'latin capital letter E with grave'),
		new Array('egrave',   '#232;',  'latin small letter e with grave'),
		new Array('Eacute',   '#201;',  'latin capital letter E with acute'),
		new Array('eacute',   '#233;',  'latin small letter e with acute'),
		new Array('Ecirc',    '#202;',  'latin capital letter E with circumflex'),
		new Array('ecirc',    '#234;',  'latin small letter e with circumflex'),
		new Array('Euml',     '#203;',  'latin capital letter E with diaeresis'),
		new Array('euml',     '#235;',  'latin small letter e with diaeresis'),

		new Array('Igrave',   '#204;',  'latin capital letter I with grave'),
		new Array('igrave',   '#236;',  'latin small letter i with grave'),
		new Array('Iacute',   '#205;',  'latin capital letter I with acute'),
		new Array('iacute',   '#237;',  'latin small letter i with acute'),
		new Array('Icirc',    '#206;',  'latin capital letter I with circumflex'),
		new Array('icirc',    '#238;',  'latin small letter i with circumflex'),
		new Array('Iuml',     '#207;',  'latin capital letter I with diaeresis'),
		new Array('iuml',     '#239;',  'latin small letter i with diaeresis'),

		new Array('Ograve',   '#210;',  'latin capital letter O with grave'),
		new Array('ograve',   '#242;',  'latin small letter o with grave'),
		new Array('Oacute',   '#211;',  'latin capital letter O with acute'),
		new Array('oacute',   '#243;',  'latin small letter o with acute'),
		new Array('Ocirc',    '#212;',  'latin capital letter O with circumflex'),
		new Array('ocirc',    '#244;',  'latin small letter o with circumflex'),
		new Array('Otilde',   '#213;',  'latin capital letter O with tilde'),
		new Array('otilde',   '#245;',  'latin small letter o with tilde'),
		new Array('Ouml',     '#214;',  'latin capital letter O with diaeresis'),
		new Array('ouml',     '#246;',  'latin small letter o with diaeresis'),
		new Array('Oslash',   '#216;',  'latin capital letter O with stroke'),
		new Array('oslash',   '#248;',  'latin small letter o with stroke'),

		new Array('Ugrave',   '#217;',  'latin capital letter U with grave'),
		new Array('ugrave',   '#249;',  'latin small letter u with grave'),
		new Array('Uacute',   '#218;',  'latin capital letter U with acute'),
		new Array('uacute',   '#250;',  'latin small letter u with acute'),
		new Array('Ucirc',    '#219;',  'latin capital letter U with circumflex'),
		new Array('ucirc',    '#251;',  'latin small letter u with circumflex'),
		new Array('Uuml',     '#220;',  'latin capital letter U with diaeresis'),
		new Array('uuml',     '#252;',  'latin small letter u with diaeresis'),

		new Array('Yacute',   '#221;',  'latin capital letter Y with acute'),
		new Array('yacute',   '#253;',  'latin small letter y with acute'),
		new Array('Yuml',     '#376;',  'latin capital letter Y with diaeresis'),
		new Array('yuml',     '#255;',  'latin small letter y with diaeresis'),

		new Array('AElig',    '#198;',  'latin capital ligature AE'),
		new Array('aelig',    '#230;',  'latin small ligature ae'),

		new Array('OElig',    '#338;',  'latin capital ligature OE'),
		new Array('oelig',    '#339;',  'latin small ligature oe'),

		new Array('Scaron',   '#352;',  'latin capital letter S with caron'),
		new Array('scaron',   '#353;',  'latin small letter s with caron'),

		new Array('Ccedil',   '#199;',  'latin capital letter C with cedilla'),
		new Array('ccedil',   '#231;',  'latin small letter c with cedilla'),

		new Array('ETH',      '#208;',  'latin capital letter ETH'),
		new Array('eth',      '#240;',  'latin small letter eth'),

		new Array('Ntilde',   '#209;',  'latin capital letter N with tilde'),
		new Array('ntilde',   '#241;',  'latin small letter n with tilde'),

		new Array('THORN',    '#222;',  'latin capital letter THORN'),
		new Array('thorn',    '#254;',  'latin small letter thorn'),

		new Array('szlig',    '#223;',  'latin small letter sharp s (ess-zed)'),
		new Array('fnof',     '#402;',  'latin small f with hook (function)'),

		new Array('Alpha',    '#913;',  'greek capital letter alpha'),
		new Array('Beta',     '#914;',  'greek capital letter beta'),
		new Array('Gamma',    '#915;',  'greek capital letter gamma'),
		new Array('Delta',    '#916;',  'greek capital letter delta'),
		new Array('Epsilon',  '#917;',  'greek capital letter epsilon'),
		new Array('Zeta',     '#918;',  'greek capital letter zeta'),
		new Array('Eta',      '#919;',  'greek capital letter eta'),
		new Array('Theta',    '#920;',  'greek capital letter theta'),
		new Array('Iota',     '#921;',  'greek capital letter iota'),
		new Array('Kappa',    '#922;',  'greek capital letter kappa'),
		new Array('Lambda',   '#923;',  'greek capital letter lambda'),
		new Array('Mu',       '#924;',  'greek capital letter mu'),
		new Array('Nu',       '#925;',  'greek capital letter nu'),
		new Array('Xi',       '#926;',  'greek capital letter xi'),
		new Array('Omicron',  '#927;',  'greek capital letter omicron'),
		new Array('Pi',       '#928;',  'greek capital letter pi'),
		new Array('Rho',      '#929;',  'greek capital letter rho'),
		new Array('Sigma',    '#931;',  'greek capital letter sigma'),
		new Array('Tau',      '#932;',  'greek capital letter tau'),
		new Array('Upsilon',  '#933;',  'greek capital letter upsilon'),
		new Array('Phi',      '#934;',  'greek capital letter phi'),
		new Array('Chi',      '#935;',  'greek capital letter chi'),
		new Array('Psi',      '#936;',  'greek capital letter psi'),
		new Array('Omega',    '#937;',  'greek capital letter omega'),
		new Array('alpha',    '#945;',  'greek small letter alpha'),
		new Array('beta',     '#946;',  'greek small letter beta'),
		new Array('gamma',    '#947;',  'greek small letter gamma'),
		new Array('delta',    '#948;',  'greek small letter delta'),
		new Array('epsilon',  '#949;',  'greek small letter epsilon'),
		new Array('zeta',     '#950;',  'greek small letter zeta'),
		new Array('eta',      '#951;',  'greek small letter eta'),
		new Array('theta',    '#952;',  'greek small letter theta'),
		new Array('iota',     '#953;',  'greek small letter iota'),
		new Array('kappa',    '#954;',  'greek small letter kappa'),
		new Array('lambda',   '#955;',  'greek small letter lambda'),
		new Array('mu',       '#956;',  'greek small letter mu'),
		new Array('nu',       '#957;',  'greek small letter nu'),
		new Array('xi',       '#958;',  'greek small letter xi'),
		new Array('omicron',  '#959;',  'greek small letter omicron'),
		new Array('pi',       '#960;',  'greek small letter pi'),
		new Array('rho',      '#961;',  'greek small letter rho'),
		new Array('sigmaf',   '#962;',  'greek small letter final sigma'),
		new Array('sigma',    '#963;',  'greek small letter sigma'),
		new Array('tau',      '#964;',  'greek small letter tau'),
		new Array('upsilon',  '#965;',  'greek small letter upsilon'),
		new Array('phi',      '#966;',  'greek small letter phi'),
		new Array('chi',      '#967;',  'greek small letter chi'),
		new Array('psi',      '#968;',  'greek small letter psi'),
		new Array('omega',    '#969;',  'greek small letter omega'),
		new Array('thetasym', '#977;',  'greek small letter theta symbol'),
		new Array('upsih',    '#978;',  'greek upsilon with hook symbol'),
		new Array('piv',      '#982;',  'greek pi symbol'),

		new Array('bull',     '#8226;', 'bullet (black small circle)'),
		new Array('hellip',   '#8230;', 'horizontal ellipsis'),
		new Array('prime',    '#8242;', 'prime (minutes/feet)'),
		new Array('Prime',    '#8243;', 'double prime (seconds/inches)'),
		new Array('oline',    '#8254;', 'overline'),
		new Array('frasl',    '#8260;', 'fraction slash'),
		new Array('weierp',   '#8472;', 'Weierstrass p'),
		new Array('image',    '#8465;', 'imaginary part'),
		new Array('real',     '#8476;', 'real part symbol'),
		new Array('trade',    '#8482;', 'trade mark sign'),
		new Array('alefsym',  '#8501;', 'first transfinite cardinal'),

		new Array('larr',     '#8592;', 'leftwards arrow'),
		new Array('uarr',     '#8593;', 'upwards arrow'),
		new Array('rarr',     '#8594;', 'rightwards arrow'),
		new Array('darr',     '#8595;', 'downwards arrow'),
		new Array('harr',     '#8596;', 'left right arrow'),
		new Array('crarr',    '#8629;', 'downwards arrow with corner leftwards'),
		new Array('lArr',     '#8656;', 'leftwards double arrow'),
		new Array('uArr',     '#8657;', 'upwards double arrow'),
		new Array('rArr',     '#8658;', 'rightwards double arrow'),
		new Array('dArr',     '#8659;', 'downwards double arrow'),
		new Array('hArr',     '#8660;', 'left right double arrow'),

		new Array('forall',   '#8704;', 'for all'),
		new Array('part',     '#8706;', 'partial differential'),
		new Array('exist',    '#8707;', 'there exists'),
		new Array('empty',    '#8709;', 'empty set (null set/diameter)'),
		new Array('nabla',    '#8711;', 'nabla (backward difference)'),
		new Array('isin',     '#8712;', 'element of'),
		new Array('notin',    '#8713;', 'not an element of'),
		new Array('ni',       '#8715;', 'contains as member'),
		new Array('prod',     '#8719;', 'n-ary product (product sign)'),
		new Array('sum',      '#8721;', 'n-ary sumation'),
		new Array('minus',    '#8722;', 'minus sign'),
		new Array('lowast',   '#8727;', 'asterisk operator'),
		new Array('radic',    '#8730;', 'square root (radical sign)'),
		new Array('prop',     '#8733;', 'proportional to'),
		new Array('infin',    '#8734;', 'infinity'),
		new Array('ang',      '#8736;', 'angle'),
		new Array('and',      '#8743;', 'logical and (wedge)'),
		new Array('or',       '#8744;', 'logical or (vee)'),
		new Array('cap',      '#8745;', 'intersection (cap)'),
		new Array('cup',      '#8746;', 'union (cup)'),
		new Array('int',      '#8747;', 'integral'),
		new Array('there4',   '#8756;', 'therefore'),
		new Array('sim',      '#8764;', 'tilde operator (varies with/similar to)'),
		new Array('cong',     '#8773;', 'approximately equal to'),
		new Array('asymp',    '#8776;', 'almost equal to (asymptotic to)'),
		new Array('ne',       '#8800;', 'not equal to'),
		new Array('equiv',    '#8801;', 'identical to'),
		new Array('le',       '#8804;', 'less-than or equal to'),
		new Array('ge',       '#8805;', 'greater-than or equal to'),
		new Array('sub',      '#8834;', 'subset of'),
		new Array('sup',      '#8835;', 'superset of'),
		new Array('nsub',     '#8836;', 'not a subset of'),
		new Array('sube',     '#8838;', 'subset of or equal to'),
		new Array('supe',     '#8839;', 'superset of or equal to'),
		new Array('oplus',    '#8853;', 'circled plus (direct sum)'),
		new Array('otimes',   '#8855;', 'circled times (vector product)'),
		new Array('perp',     '#8869;', 'up tack (orthogonal to/perpendicular)'),
		new Array('sdot',     '#8901;', 'dot operator'),

		new Array('lceil',    '#8968;', 'left ceiling (apl upstile)'),
		new Array('rceil',    '#8969;', 'right ceiling'),
		new Array('lfloor',   '#8970;', 'left floor (apl downstile)'),
		new Array('rfloor',   '#8971;', 'right floor'),
		new Array('lang',     '#9001;', 'left-pointing angle bracket (bra)'),
		new Array('rang',     '#9002;', 'right-pointing angle bracket (ket)'),
		new Array('loz',      '#9674;', 'lozenge'),

		new Array('spades',   '#9824;', 'black spade suit'),
		new Array('clubs',    '#9827;', 'black club suit (shamrock)'),
		new Array('hearts',   '#9829;', 'black heart suit (valentine)'),
		new Array('diams',    '#9830;', 'black diamond suit')
		);

	function setup_form()
	{
	}

	function update_display(entity, description)
	{
		document.getElementById('entity_preview').innerHTML = '&' + entity;
		document.getElementById('entity_description').innerHTML = description;
		document.getElementById('entity_hidden').value = entity;
	}

	function insertentity()
	{
		var f = document.rta_form;

		var html = '&' + f.entity_hidden.value;

		window.rta.handleCommand('insertentity', html);

		window.close();
		return true;
	}

	//-->
	</script>
</head>
<body id="rta_dialog">

<form name="rta_form" id="rta_form" onSubmit="return insertentity();">
<table border="0" cellpadding="4" cellspacing="0" width="100%" height="100%">
<tr>
	<td valign="top" id="rta_panel">

		<table border="0" cellpadding="0" cellspacing="2">
		<tr>
			<script language="JavaScript">
			<!--

			var c = 0;
			for (var i = 0; i < entities.length; i++)
			{
				c++;
				document.writeln('<td class="entity" onClick="update_display(\'' + entities[i][1] + '\', \'' + entities[i][2] + '\')" onMouseOver="window.status=\'' + entities[i][2] + '\'">&' + entities[i][1] + '</td>');
				if (c >= 20)
				{
					c = 0;
					document.writeln('</tr><tr>');
				}
			}

			//-->
			</script>
		</tr>
		<tr>
			<td colspan="20" id="rta_buttons">

				<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td id="entity_description">Select a character to preview</td>
					<td id="entity_preview">&nbsp;</td>
				</tr>
				</table>

			</td>
		</tr>
		</table>

	</td>
</tr>
<tr>
	<td height="1" id="rta_buttons">

		<hr>
		<input type="hidden" name="entity_hidden" id="entity_hidden" value="">
		<input type="button" value="Cancel" onClick="window.close();" />
		<input type="submit" value="Insert Character" />

	</td>
</tr>
</table>

<script language="javaScript">
<!--
setup_form();
//-->
</script>

</body>
</html>
