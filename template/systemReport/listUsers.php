<div id="container">
	<div id="header">
		<!header>
	</div>
	
	<div id="sidebar">
		<!sidebar>
	</div>
	<div id="mainContent">
<table style="vertical-align:middle; width:700px; margin-top:35px;"  border="0">
    <tr>
        <td style="width:700px;">
            <div id="breadcrumbNav">
            <!breadcrumb>
            </div>
        </td>
    <td>
</table>
	
	<!-- [list] -->
	<br />
	<h1 class="largeH1" style="margin-top:30px;"><!heading></h1>
	<table border="0" cellpadding="0" cellspacing="0" style="margin-top:10px;"width="100%"><tr><td>
	<div>				
		<form method="POST" action="index.php" name="filter" onsubmit="return search_key(document.filter.search.value);" >
			<input type="text" size="40" maxlength="250" name="search" value="<!search>">
            <input type="hidden" name="clinic_id" value="<!clinic_id>" >
            <input type="hidden" name="action" value="viewTherapistOfClinic" >
			<input type="submit" size="20" name="sub" value=" Search User ">
            <input type="button" size="20" name="all_user" value=" All Users " onclick="javascript:window.location.href='?index.php&action=viewTherapistOfClinic&clinic_id=<!clinic_id>'" >
		</form>
	</div>
	</td>
	<td align="right"></td>
	</tr>
	</table>

<script language="JavaScript">
    <!--
    function handleAction(s, clinic_id, user_id )
    {
        var a = s.options[s.options.selectedIndex].value;
        s.options.selectedIndex = 0; 
        var c = true;
        var action = 'select_period';
        var url = "";
        if(a == 'one'){
            act = 'one';
            url = 'index.php?action=' + action +  '&report_type=' + act ;
        }
        if(a == 'two' ){
            act = 'two';
            url = 'index.php?action=' + action + '&report_type=' + act ;
        }
        if(a == 'three' ){
            act = 'three';
            url = 'index.php?action=' + action + '&report_type=' + act ;
        }
        if(a == 'four' ){
            act = 'four';
            url = 'index.php?action=' + action + '&report_type=' + act ;
        }
        if(a == 'five' ){
            act = 'five';
            url = 'index.php?action=' + action + '&report_type=' + act ;
        }
        if(a == 'six' ){
            act = 'six';
            url = 'index.php?action=' + action + '&report_type=' + act;
        }
        if ( c && url != '' ){
             window.location.href = url + '&clinic_id=' + clinic_id + '&user_id=' + user_id;
        }
        
    }
    
    function submitFilter()
	{
		if(document.forms['filter'].elements['search'].value != '')
		{
			document.forms['filter'].submit();
			return true;
		}
		else return false;
	}
	-->
	</script>
	
	
	<table border="0" cellpadding="2" cellspacing="1" width="100%" class="list" style="margin-top:10px; onMouseOver="help_text(this, 'To sort this list click on the column header you would like to sort by. Use action menus to perform action for the template plan')">
	 <!listUsersHead>
	 <!listUsersRecord>
	</table>
	<div class="paging">
				<span ><!link></div>
    <div align="center" style="padding:5px;">
		</div> 
	
		</div>
	
	<div id="footer">
		<!footer>
	</div>
</div>