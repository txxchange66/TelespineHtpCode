<script language='JavaScript' src='js/show_menu_head.js'></script>
<script language="JavaScript" src="js/mm_menu.js"></script>

<style type="text/css">
/* Demo style */
p
        {
        width:800px;
        margin:0 auto 1.6em auto;
        }
        
/* Pagination list styles */
ul.fdtablePaginater
        {
       /* display:table;*/
        list-style:none;
        padding:0;
        margin:0 auto;
        text-align:center;
        
        width:auto;
       
        }
ul.fdtablePaginater li
        {
        display:table-cell;
        padding-right:4px;
        color:#ff0000;
        list-style:none;
        
        -moz-user-select:none;
        -khtml-user-select:none;
        }
ul.fdtablePaginater li a.currentPage
        {
        border-color:#a84444 !important;
        color:#000;
        }
ul.fdtablePaginater li a:active
        {
        border-color:#222 !important;
        color:#222;
        }
ul.fdtablePaginater li a,
ul.fdtablePaginater li div
        {
        display:block;
      
        font-size:11px;
        color:#666;
        padding:0;
        margin:0;
        text-decoration:none;
        outline:none;
        font-family: Geneva,Verdana,san-serif;
        
       
        }
ul.fdtablePaginater li div
        {
        cursor:normal;
            color:#000;
       
        }
ul.fdtablePaginater li a span,
ul.fdtablePaginater li div span
        {
        display:block;
        
            color:#0069A0;
            margin-top:5px;
        
       
        }
ul.fdtablePaginater li a
        {
        cursor:pointer;
            color:#000;
            border-right:1px solid #000;
            padding:0 5px 0 2px;
        }
 ul.fdtablePaginater li a.currentPage
        {
        	border-color:#fff !important;
        	border-right:1px solid #000 !important;
}       

ul.fdtablePaginater li a:focus
        {
        color:#333;
        text-decoration:none;
        border-color:#aaa;
        }
.fdtablePaginaterWrap
        {
        text-align:center;
        clear:both;
        text-decoration:none;
        }
ul.fdtablePaginater li .next-page span,
ul.fdtablePaginater li .previous-page span,
ul.fdtablePaginater li .first-page span,
ul.fdtablePaginater li .last-page span
        {
        font-weight:normal !important;
            
        }
    ul.fdtablePaginater li div.previous-page span{color:#000 !important;} 
        ul.fdtablePaginater li div.next-page span{color:#000 !important;}     
        
/* Keep the table columns an equal size during pagination */
td.sized1
        {
        width:16em;
        text-align:left;
        }
td.sized2
        {
        width:10em;
        text-align:left;
        }
td.sized3
        {
        width:7em;
        text-align:left;
        }
tfoot td
        {
        text-align:right;
       /* font-weight:bold;*/
        text-transform:uppercase;
        letter-spacing:1px;
        }
#visibleTotal
        {
        text-align:center;
        letter-spacing:auto;
        }
* html ul.fdtablePaginater li div span,
* html ul.fdtablePaginater li div span
        {
        background:#eee;
        }
tr.invisibleRow
        {
        display:none;
        visibility:hidden;
        }
p.paginationText
        {
        font-style:oblique;
        }
 #paginate1-fdtablePaginaterWrapTop{display:none;}
 #paginate2-fdtablePaginaterWrapTop{display:none;}	

      
        
     
        
   ul.fdtablePaginater li a.currentPage span{color:#000 !important;}
    
   
 ul.fdtablePaginater li a.next-page {border-right:none; font-weight:normal !important;}
 

        
</style>

<style>
table.serReport{border:1px solid #ccc; border-collapse:collapse;}
table.serReport tr td{border:1px solid #ccc;}
</style>


<div id="container">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="2">
                <div id="header">
                    <!header>
                </div>
            </td>
        </tr>
        <tr>
            <td width="16%" align="left" valign="top">
                <div id="sidebar">
                    <!sidebar>
                </div>
            </td>
            <td width="84%"  align="left" valign="top">
                <div id="mainContent-sec">
                    <!--  MAIN TABLE STARTS -->
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td valign="top" align="left">
                                <!--  INNER MAIN TABLE STARTS (5) ROWS STARTS  -->
                                <table width="100%" border="0" style="vertical-align:middle; width:700px; ">
                                    <tr>
                                        <td>
                                            <table style="vertical-align:middle;width:700px;" >
                                                <tr>
                                                    <td style="width:400px;">
                                                        <div id="breadcrumbNav" style="padding-left:0px;margin-top:18px;">
                                                            <a href="index.php" >HOME </a> / <span >REPORT</span> / <span class="highlight">TELESPINE REPORTS</span>
                                                        </div>
                                                    </td>
                                                    <td style="width:300px;height: 70px;">
                                                        &nbsp;
                                                    </td>
                                                </tr>

                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <!error>
                                <div align="right" style="padding-top:4px;">
                                    <!tabNavigation>
                                </div>
                        </td>
                        </tr>
                        <tr>
                            <td>
                                <div>
                                    <!report>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div>
                        <div class=\"paging\">
                        <span>
                            <!link>
                        </span>
                    </div>
                          </div>
                            </td>
                        </tr>
                        <!--  4th ROW ENDS  -->
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <!--  5th ROW ENDS  -->
                    </table>
                </div>
                <!--  INNER MAIN TABLE STARTS (5) ROWS ENDS -->
            </td>
        </tr>
        <tr>
            <td colspan="2"><div id="footer">
                    <!footer>
                </div></td>
        </tr>
    </table>
    <!--  MAIN TABLE ENDS  -->

</div><!-- div ( container ) Ends -->
<script type="text/javascript">
    mmLoadMenus("<!showmenu>");
</script>
