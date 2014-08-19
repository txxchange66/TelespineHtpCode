<html>
<head>
<script language="JavaScript" type="text/javascript" src="js/common.js"></script>
<script src="js/jquery-latest.js"></script>     
<script language="javascript" >
<!--
function tagPopup(id){
        tag = document.frm.tag.value;
        save = 'save';
        if( id != null && id != "" ){
                $.post('index.php?action=tagPopup',{id:id,tag:tag,save:save}, function(data,status){
                    if( status == 'success'){
                        closePopup(); 
                    }
              }
            )        
        }
}
function closePopup(){
     parent.parent.GB_CURRENT.hide();
}
-->
</script>
</head>
<body>
<br>
<div  align="center"  >
        <form name="frm" action="index.php" method="post" />
        <table>
            <tr >
                <td style="padding-bottom:14px;" >
                    <strong>Tags:</strong>
                 </td>
                 <td >  
                    <input type="text" name="tag" value="" size="60" />
                    <div align='left' style='font-size:11px;'>(Please separate tags with comma)</div>
                </td>
            </tr>
            <tr >
                <td  colspan="2" align="center">
                    <input type="button" onclick="tagPopup('<!id>');"  name="save" value=" Save & close " />
                </td>
            </tr>
        </table>
        </form>
</div>
</body>
</html>
