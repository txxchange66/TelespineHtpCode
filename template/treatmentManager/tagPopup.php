<html>
<head>
<script language="JavaScript" type="text/javascript" src="js/common.js"></script>
<script src="js/jquery-latest.js"></script>     
<script language="javascript" >
<!--
function tagPopup(id){
        tag = document.frm.tag.value;
        save = 'save';
        favoriteChecked = document.frm.favorite.checked;
        favoriteValue = '';  
        if( favoriteChecked === true ){
            favoriteValue = document.frm.favorite.value;
        }
        if( id != null && id != "" ){
                $.post('index.php?action=tagPopup',{id:id,tag:tag,favorite:favoriteValue,save:save}, function(data,status){
                    if( status == 'success'){
                        favorite(id);
                        closePopup(); 
                    }
              }
            )        
        }
}
function closePopup(){
     parent.parent.GB_CURRENT.hide();
}
function favorite(id){
    treatment_id = id;
    favoriteChecked = document.frm.favorite.checked;
    
    if( isNumeric(treatment_id) ){
        favorite = 'favorite_' + treatment_id;
        if(top.document.getElementById(favorite)){
            if(favoriteChecked === true  ){
                top.document.getElementById(favorite).innerHTML = "<img src='images/favorite.gif' />";
            }
            else{
                top.document.getElementById(favorite).innerHTML = "&nbsp;";
            }
        }
    }
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
                <td style="padding-bottom:14px;"  >
                    <strong>Tags:</strong>
                 </td>
                 <td>  
                    <input type="text" name="tag" value="" size="60" />
                    <div align='left' style='font-size:11px;'>(Please separate tags with comma)</div>
                </td>
            </tr>
            <tr >
                <td><strong>Favorite:</strong></td>
                <td align="left" ><input type="checkbox" name="favorite" <!checked> value="1" /></td>
            </tr>
            <tr >
                <td  colspan="2" align="center">
                    <input type="button" onclick="javascript:tagPopup('<!id>');"  name="save" value=" Save & Close " />
                </td>
            </tr>
        </table>
        </form>
</div>
</body>
</html>
