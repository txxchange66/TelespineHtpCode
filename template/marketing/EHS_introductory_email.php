<input type="hidden"  name="showsoap" id="showsoap" value="<!shopshow>">
<script language="JavaScript" src="js/show_menu_head.js"></script>
<script language="JavaScript" src="js/mm_menu.js"></script>
<!-- TinyMCE -->
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
//Creates a new plugin class
tinymce.create('tinymce.plugins.ExamplePlugin', {
    createControl: function(n, cm) {
        switch (n) {
            case 'mylistbox':
                var mlb = cm.createListBox('mylistbox', {
                     title : 'Select Field',
                     onselect : function(v) {
                         //alert(tinyMCE.activeEditor.windowManager);
                        // tinyMCE.activeEditor.windowManager.selection.setContent(v);
                    	 tinyMCE.activeEditor.focus();
                         tinyMCE.activeEditor.selection.setContent(v);
                         
                     }
                });

                // Add some values to the list box
                mlb.add('First Name', '{First Name}');
                mlb.add('Clinic Logo', '{Clinic Logo}');
                mlb.add('EHS Name', '{EHS Name}');
                                // Return the new listbox instance
                return mlb;
        }

        return null;
    }
});

// Register plugin
tinymce.PluginManager.add('example', tinymce.plugins.ExamplePlugin);

	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist,autosave,visualblocks,-example",
		elements : "elm1,elm2",
		
		// Theme options
		theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,|,justifyleft,justifycenter,justifyright,fontselect,fontsizeselect,formatselect,mylistbox",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",      
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : false,
		
		// Example content CSS (should be your site CSS)
		content_css : "css/content.css",
		setup : function (ed) {
			ed.onKeyUp.add(
			function (ed, evt) {
			//document.addon.deslen.value = tinyMCE.activeEditor.getContent().replace(/<[^>]+>/g, '').length;
			}
			);
			},
		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		],

				
		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	
	
	});
	
		
	
</script>
<!-- /TinyMCE -->
<div class="center-align" >
  <div id="container"><!--style="padding-top:14px;"-->
    <div id="header">
      <!header>
    </div>
    
    <div id="sidebar">
          <!sidebar>
    </div>
    <!-- body part starts-->
    <div id="mainContent">
      <table border="0" cellpadding="0" cellspacing="0" style="vertical-align:middle; width:100%; height:81px;">
        <tr>
          <td colspan="3" style=" width:400px; height:9px;"></td>
        </tr>
         <tr>
          <td width="151" style=" width:400px;"><div id="breadcrumbNav"  style="padding-left:6px;margin-top:12px;"> <a href="index.php">HOME</a> /  CUSTOMIZE /<span class="highlight"  > EHS INTRO EMAIL</span></div></td>
          <td width="138"></td>
          <td width="173" align="right" style="padding-right:75px; margin-top:11px;height:70px;">&nbsp;</td>
        </tr> 
        <tr>
          <td colspan="3" valign="top" style=" width:400px; font-size: large;
    font-weight: bold;
    padding-bottom: 7px; margin:0px; vertical-align:top; 
    ">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" valign="top"  ><div>
            <!navigationTab>
          </div></td>
        </tr>
        <tr>      
          <td colspan="3" valign="top" class="topnavnew" >&nbsp;</td>
        </tr>
      </table>
      
      
 <!-- best practices table starts from here-->  
<form action="index.php" method="post" name="frm" enctype="multipart/form-data"  >
    <table width="100%" border="0" cellpadding="0" cellspacing="0"  style="border:1px solid #CCCCCC;"> 
        <tr>
            <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
         <tr>
        <td colspan="2" style="padding-left: 20px;padding-top: 5px; color: <!color>"><!message></td>
        </tr>
        <tr>
          <td class="marketingtitle" width="185px">EHS Introductory Email</td>
          <td align="left" style="padding-top: 20px;"><!error></td>
        </tr>
        <tr>
        <td >&nbsp;</td> <td >&nbsp;</td>
        </tr>
            <tr>
        <td colspan="2" style="padding-left: 20px;padding-bottom: 5px;"><strong>*Subject</strong></td>
        </tr>
        <tr>
        <td colspan="2" style="padding-left: 20px;" ><input type="text" name="subject" id="subject" value="<!subject>" style="width: 500px;"></td>
        </tr>
        <tr>
        <td >&nbsp;</td> <td >&nbsp;</td>
        </tr>
              
         <tr><td colspan="2" style="padding-left: 20px;padding-bottom: 5px;"><strong>Message</strong></td> </tr>
         
         <tr><td colspan="2" align="center"><textarea name="subs_description"  id="subs_description"    rows="20" style="width: 700px;" ><!subs_description></textarea></td></tr>
                <tr>
                    <td align="center" colspan="2" >
                        <input type="hidden" name="action" value="EHS_introductory_email" />
                        <input type="hidden" name="save_introductory_email" value="submit" />
                        
                    </td>
                </tr>
              
                
            </table>

            </td>
        </tr>
        <tr>
          <td colspan="2" align="right">&nbsp;</td>
        </tr>
      </table>
      
     <table cellpadding="0" cellspacing="0" width="100%">
         <tr>
          <td colspan="2" align="right" ><input type="submit" name="save" value=" Save " /></td>
        </tr>
    </table>
                </form>
 <!-- best practices table ends here--> 
      <div class="paging" align="right"></div>
      <!-- body part ends-->
      <!-- [/items] -->
      <div align="center" style="padding:5px;"> </div>
      <!-- [/list] -->
    </div>
  </div>
  
</div>
<script type="text/javascript">
    mmLoadMenus("<!showmenu>");
</script>