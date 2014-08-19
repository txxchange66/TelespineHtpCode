<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        <title>Notepad</title>
        <meta name="description" content="<!metaDesc>" />	
        <link rel="stylesheet" type="text/css" href="css/styles_popup.css" />
        <script language="JavaScript" type="text/javascript" src="js/common.js"></script>
    </head>
    <body>
        <div id="container" style="margin: 0;position:inherit;width:100%;">
            <div id="mainContent" style="width:100%;">
                <script language="JavaScript" src="js/validateform.js"></script>
                <script language="JavaScript">
                    window.formRules = new Array(
                            new Rule("note", "a note", true, "string|1,2500")
                            );
                </script>
                <script language="JavaScript">
                    function closeWindow() {
                        //top.location.reload(true);
                        parent.parent.GB_hide();
                    }
                </script>
                <br/>
                <span style="padding: 5px;color: #0069a0;font-weight: bold;">Add a new note</span>
                <!statusMessage>

                <form name="addnoteform" id="addnoteform" method="POST" action="index.php?action=addNote&patient_id=<!patient_id>" onSubmit="return validate_form(this);">
                    <table border="0" cellpadding="2" cellspacing="1" width="100%" class="form_container">
                        <tr class="input">
                            <td width="100%"><textarea name="note" id="note" cols="50" rows="7" style="width: 99%;"></textarea></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="right"><input type="submit" name="submitted" value="Save" />&nbsp;
                                <input type="button" name="close" value="Cancel"  onClick="closeWindow();" />
                            </td>

                        </tr>
                    </table>
                </form>

                <span style="color: #0069a0;font-weight: bold;">Notes for <!patientName> </span>

                <table border="0" cellpadding="2" cellspacing="1" width="100%" class="list">
                    <!noteTblRecord>
                </table>
                </body>
                </html>