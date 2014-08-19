<style>
.popup-text
{
    font-family:Verdana;
    font-size:12px;
    color:#333333;
    margin:5px 6px 6px 6px;
    padding-bottom:10px;
    line-height:25px;
    border-bottom:1px #999999 solid;
}
.submit-button
{
    text-align:right;
    padding-right:13px;
     padding-top:5px;
     margin-bottom:6px;
}
</style>
<script>
function checkSelectBox(id)
{
if(document.form1.close.value=="close"){
	parent.parent.GB_hide();
}
	var namelist = "";
    var namelistvalu = "";
    
      with(document.form1) {
    	if(ans.checked){
   		 namelist += ans.value + ";";
       	}else{  
        for(var i = 0; i < ans.length; i++){
            if(ans[i].checked) {
                namelist += ans[i].value + ";";
            }
        }
       	}
        
    }
    //alert(namelist);
    namelistvalu =window.top.document.getElementById("<!id>").value +namelist; 
    window.top.document.getElementById("<!id>").value = namelistvalu;
    window.top.document.getElementById("<!id>").focus();
    //parent.parent.document.getElementById(q1).value = namelist;
    parent.parent.GB_hide();
}
function closeme()
{

	parent.parent.GB_hide();
}
</script>
<div class="popup">
<div class="popup-text">
    <form name="form1">
        <!rowasn>
    </form>
</div>
<div class="submit-button">
<!submit>
</div>
</div>
