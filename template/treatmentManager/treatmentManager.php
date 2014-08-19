<script type="text/javascript">  
var GB_ROOT_DIR = "js/greybox/";
</script>
<script type="text/javascript" src="js/greybox/AJS.js"></script>
<script type="text/javascript" src="js/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="js/greybox/gb_scripts.js"></script>
<script src="js/jquery.js"></script> 
<link href="js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
</script>
<!convertV>

<!--<script src="js/jquery-latest.js"></script>  
<script language="javascript" >
function addOrDesignateTag(id,tag,designate){
    if( (tag != null && tag != "") && ( id != null && id != "")  ){
            $.post('index.php?action=insertTag',{id:id,tag:tag}, function(data,status){
            if( status == "success" ){
                if( /success/.test(data)){
                    
                }     
                else if( /failed/.test(data) ){
                    alert('Failed to add tag.');
                }
            }
            else{
                alert("Ajax connection failed.");
            }
            
            //reloadTag(id);
            loadToggleDiv(id)
            //document.getElementById('tag').value = '';    
          }
        )        
        
    }
    else{
        alert("Please enter tag.");
    }
}
</script>-->

<div id="container">
	<div id="header">
		<!header>
	</div>
	
	<div id="sidebar">
		<!sidebar>
	</div>
	
	
	
<div id="mainContent">
    <table style="vertical-align:middle;width:700px;"><tr><td style="width:400px;"><!--<div id="breadcrumbNav"><a href="index.php?action=treatmentManager" >TREATMENT</a> / <span class="highlight">SELECT TREATMENT</span></div>--><div id="breadcrumbNav"><a href="index.php?action=treatmentManager" >VIDEO</a> / <span class="highlight">SELECT VIDEO</span></div></td><td style="width:300px;">	<table border="0" cellpadding="5" cellspacing="0" style="float:right;">
    <tr>
	    <td class="iconLabel">
		    <a href="index.php?action=createTreatment">
			<img src="<!imageCreateTreatment>" width="127" height="81" alt="Create New Treatment"></a>
        </td>
	        
    </tr>
</table>
	</td></tr></table>
<script language="JavaScript">
<!--

function handleAction(s, id)
{
	var a = s.options[s.options.selectedIndex].value;
	var c = false;

	switch (a)
	{
		case 'delete':
			c = confirm('Deleting this treatment will remove it completely from the system.  Are you sure you would like to continue with deleting this treatment?');
			break;	
		case 'inactive':
			c = true;
			break;
		case 'active':			
			c = true;	
			break;		
        case 'tag':            
            c = true;    
            break;        
		default:
			c = true;
			break;
	}

	s.options.selectedIndex = 0;

	if (c) 
	{
		if( 'tag' == a ){
                GB_showCenter('<!tag_title>', '/index.php?action=tagPopup&id=' + id, 150, 480 );
        }
        if ('edit' == a)
		{
			window.location.href = 'index.php?action=editTreatment&' + 'id=' + id;
		}	
		
		if ('inactive' == a)
		{
			window.location.href = 'index.php?action=inactiveTreatment&' + 'id=' + id;
		}
		
		if ('active' == a)
		{
			window.location.href = 'index.php?action=activeTreatment&' + 'id=' + id;
		}
		
		if ('delete' == a)
		{
			window.location.href = 'index.php?action=deleteTreatment&' +'id=' + id;
		}
		if ('view' == a)
		{
				var action_url = 'index.php?action=treatmentViewer&id=' + id + '_t';
			var plan_detail_win = window.open( action_url , 'PlanPreview', 'width=970,height=688,resizable=1,scrollbars=auto');
		}
	}
}



function submitFilter()
{
	var searchValue = document.forms['filter'].elements['search'].value;
	searchValue = Trim(searchValue);
	
	if(searchValue != '') 
	{
		if (isAlphaNumeric(searchValue))
		{
			document.forms['filter'].submit();			
		}
		else
		{
			//alert("Please enter valid search key in search text box");
			return true;
		}
		
	}
	else
	{
		alert("Please enter search key in search text box.");
		return false;
	}
}

function showCatSelect(id)
{
	if(!csw) var csw =  window.open('index.php?action=PopupCategoryList&id='+id, 'catSelectWindow', 'width=850, height=650, status=no, toolbar=no, resizable=yes, scrollbars=yes');
	csw.focus();
}
-->
</script>

<!-- [title and common actions] -->
<h1 class="largeH1" style="margin-top:-2px; height:26px;">Video Library</h1>

<!-- Filter -->
<!filter>
<!-- Filter -->

<!-- [/title and common actions] -->

<!-- [user list] -->

<!-- [items] -->
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="list" onMouseOver="help_text(this, 'Displays the Treatments, click on clickable link to edit it.  To sort this list click on the column header you would like to sort by')">
<thead>
<tr>
<!treatmentManagerHead>
</tr>
</thead>
<!treatmentRecord>
</table>
<!-- [/items] -->
<script language="javascript" >
function jump_to(page){
	window.location = "<!action_url>&page=" + page;
	
}
</script>

<div class="paging" style="padding-top:2px;">
		<div style="float:left"><!link></div>
		<!-- <div style="float:right;padding-right:3px;" valign="top" >Jump to page:<select class="action_select" style="width:47px;" name="page" onchange="jump_to(this.value);"><!pages></select></div> -->
</div>
<!-- [/user list] -->
</div>
</div>
<div id="footer">
    <!footer>
</div>
</div>

