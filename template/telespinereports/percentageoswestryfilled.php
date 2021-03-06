<link rel="stylesheet" href="js/autocomplete/themes/base/jquery.ui.core.css">
<link rel="stylesheet" href="js/autocomplete/themes/base/jquery.ui.datepicker.css">
<link rel="stylesheet" href="js/autocomplete/themes/base/jquery.ui.theme.css">
<script src="js/autocomplete/jquery-1.5.1.js"></script>
<script src="js/autocomplete/ui/jquery-ui-1.8.14.custom.js"></script>
<script src="js/autocomplete/ui/minified/jquery.ui.datepicker.min.js"></script>

<div class="subheading">Percentage of oswestrys filled</div>

<label for="from">From</label>
<input type="text" id="from" name="from">
<label for="to">to</label>
<input type="text" id="to" name="to">
<input type="submit" value="ok" />

<table cellpadding="2" width="100%" style="border:1px solid #CCCCCC;">
    <tr>
        <td valign="top" class="topnavnew">
            Total Users
        </td>
        <td valign="top" class="topnavnew">Oswestry 1</td>
        <td valign="top" class="topnavnew">Oswestry 2</td>
        <td valign="top" class="topnavnew">Oswestry 3</td>
        <td valign="top" class="topnavnew">Oswestry 4</td>
    </tr>
    <tr>
        <td>40</td>
        <td>80 %</td>
        <td>90 %</td>
        <td>70 %</td>
        <td>50 %</td>
    </tr>
</table>

<script language="JavaScript1.2">
    $(function() {
        $( "#from" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function( selectedDate ) {
                $( "#to" ).datepicker( "option", "minDate", selectedDate );
            }
        });
        $( "#to" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function( selectedDate ) {
                $( "#from" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
    });
</script>