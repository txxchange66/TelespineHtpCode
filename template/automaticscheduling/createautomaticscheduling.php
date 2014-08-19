<script language='javascript' type='text/javascript' src='js/jquery.js'></script>

<div id="container">
    <div id="header"><!header></div>

    <div id="sidebar"><!sidebar></div>
    <div id="mainContent">
        <form name="autoprogram" method="post" action="index.php?action=submitautoprogram">
        <table border="0" cellpadding="2" cellspacing="1" width="100%" class="form_container">
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2"><span style="color: #0069A0;font-size: 12px;" >Create new program</span></td>
            </tr>
            <tr>
                <td height="8"></td>
            </tr>
            <tr>
                <td>Program name:</td>
                <td><input type="text" name="programename"  style="background: #FFFFFF; border: 1px solid #CCCCCC;border-radius: 5px 5px 5px 5px; padding: 3px 5px; width: 200px;"   id="programename" value="<!programevalue>" readonly></td>
                
            </tr>
            <tr>
                <td height="8"></td>
            </tr>
            <tr>
                <td>Program Duration</td>
                <td> <select name="program_duration" id="program_duration" onMouseOver="help_text(this, 'Duration in week')" style="background: #FFFFFF; border: 1px solid #CCCCCC;border-radius: 5px 5px 5px 5px; padding: 3px 5px; width: 215px;">
                    <option value="">Select</option>
                    <!automaticduration>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" ><input type="hidden"name="act" value="<!act>"/><input type="Submit" name="submit" value="Next" style="background:#0069A0;border: 0 none; border-radius: 5px 5px 5px 5px; color: #FFFFFF; margin: 10px 0 0 402px;padding: 5px 8px;"/></td>
            </tr>
            
        </table>
        </form>
    </div>
</div>
<div id="footer"></div>
				
