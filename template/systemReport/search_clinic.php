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
                <td style=" width:400px;">
                    <div id="breadcrumbNav">
                        <!breadcrumb>
                    </div>
                </td>
            </tr>
        </table>
	<br />
	<h1 class="largeH1" style="margin-top:30px;"><!heading></h1>
	    <table border="0" cellpadding="0" cellspacing="0" style="margin-top:10px;"width="100%"><tr><td>
	    <div>				
            <!-- [Search form] -->
		    <form method="POST" action="index.php" name="filter" >
			    <input type="text" id="search" size="40" maxlength="70" name="search" value="<!search>" >
                <input name="action" type="hidden" value="search_clinic" />
			    <input type="submit" size="20" name="sub" value=" Search Clinic ">
		    </form>
            <!-- [Search form] -->
	    </div>
	    </td>
	    <td align="right"></td>
	    </tr>
	    </table>
	</div>
    <!-- [footer] -->
	<div id="footer">
		<!footer>
	</div>
    <!-- [footer] -->
</div>
 <script src="js/jquery-latest.js"></script>  
 <script language="javascript" type="text/javascript">
          $(document).ready(function() {
            $("#search").focus();
          });
 </script>