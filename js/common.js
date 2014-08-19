var expand = true;

function nav_over(id, type)
{
	var d = document;
	var a = d.getElementById('navhead' + id);
	a.className = 'nav_title' + type + '_over';
}

function nav_out(id, type)
{
	var d = document;
	var a = d.getElementById('navhead' + id);
	a.className = 'nav_title' + type;
}

function nav_toggle(id, type, skin)
{
	var d = document;
	var a = d.getElementById('navbody' + id);
	var b = d.getElementById('navimage' + id);
	if (a.className == 'nav_body_hidden')
	{
		a.className = 'nav_body';
		b.src = 'skin/' + skin + '/images/nav_close' + type + '.png'
	}
	else
	{
		a.className = 'nav_body_hidden';
		b.src = 'skin/' + skin + '/images/nav_open' + type + '.png'
	}
}

function show_hide_rows()
{
	var a = show_hide_rows.arguments;

	var skin = a[0];
	var prefix = a[1];
	var id = a[2];
	if (!skin || !prefix || !id) return;

	var d = document;
	var img = d.getElementById(prefix + '_img' + id);
	if (img.src.indexOf("group_show") > -1)
		img.src = 'skin/' + skin + '/images/group_hide.gif';
	else
		img.src = 'skin/' + skin + '/images/group_show.gif';

	for (var i = 3; i < a.length; i++)
	{
		var row = d.getElementById(prefix + '_' + a[i] + id);

		if (row.style.display == 'table-row' || row.style.display == '' || row.style.display == 'block')
		{
			row.style.display = 'none';
		}
		else
		{
			var display = 'table-row';
			if (document.all) display = 'block';

			row.style.display = display;
		}
	}
}

function help_text(el, str)
{
	return true;
	var d = document;
	var h = d.getElementById('rolloverhelp');
	if (h != null)
	{
		h.innerHTML = str + '.';
		h.style.background = '#c7d28a';
		el.onmouseout = function(e)
		{
			h.innerHTML = 'Rollover buttons or elements on the page to get help and tips.';
			h.style.background = '#ffffff';
			el.onmouseout = null;
		}
	}	
}
function popWindow(wName){
	features = 'width=400,height=400,toolbar=no,location=no,directories=no,menubar=no,scrollbars=no,copyhistory=no,resizable=no';
	pop = window.open('',wName,features);
	if(pop.focus){ pop.focus(); }
	return true;
}

function reset_help_text(now)
{
	if (now)
	{
		var h = document.getElementById('rolloverhelp');
		h.innerHTML = 'Rollover buttons or elements on the page to get help and tips.';
	}
	else setTimeout('reset_help_text(true)', 400);
}
function search_key(keyword){
   if(keyword.replace(/^\s*(\S*(\s+\S+)*)\s*$/, "$1") == ""){
   		alert('Please enter search key in search text box.')
   		return false;
   }
   return true;
}

function Trim(str)
{  while(str.charAt(0) == (" ") )
  {  str = str.substring(1);
  }
  while(str.charAt(str.length-1) == " " )
  {  str = str.substring(0,str.length-1);
  }
  return str;
}

function isAlphaNumeric(val)
{
	if (val.match(/^[a-zA-Z0-9 ]+$/))
	{
		return true;
	}
	else
	{
		return false;
	} 
}
function isNumeric(val)
{
    if (val.match(/^[0-9]+$/))
    {
        return true;
    }
    else
    {
        return false;
    } 
}
function switchMenu(obj,liobj,collapse) {
    if(expand === false){
        return;
    }
    if( /list_expand/.test(liobj.style.background) ){
        liobj.style.background = "url(/images/list_contract.gif) no-repeat 0px 6px";
    }
    else{
        liobj.style.background = "url(/images/list_expand.gif) no-repeat 0px 6px";
    }
    var el = document.getElementById(obj);
    if ( el.style.display != 'none' ) {
        el.style.display = 'none';
    }
    else {
        el.style.display = '';
    }
}

function assign(){
    expand = false;
}
var previous_line;
function highlight(mouse_move,row){
    if(mouse_move == 'in' ){
        previous_line = row.className
        row.className = "line4";
    }
    if(mouse_move == 'out' ){
        row.className = previous_line;
    }
}