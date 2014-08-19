<html>
<head>
<script src="js/jquery.js"></script>  
<script language="javascript" >
function emptyString(str){
    str = $.trim(str);
    if( str.length == 0 ){
        return true;
    }
    else{
        return false;
    }
}
function close_window(){
       // window.location.href='index.php?action=therapistEhsPatient&msg=goal'; return true;
    //top.document.location.reload();
    parent.parent.GB_CURRENT.hide();
         
}

function validatefrm(frm){
    var str = frm.goal.value;
    if( emptyString(str) ){
        alert('Please enter your goal.');
    }
    else{
        $.post('index.php?action=add_goal_ehs',{goal:str,therapistId:'<!therapistId>'}, function(data,status){
                    
                    if( status == "success" ){//alert(data);return false;

                       
                        if(!/failed/.test(data)){
                             top.document.getElementById('top7').style.display = "block"; 
                             top.document.getElementById('messageDiv').style.display = "none";  
                                
                           // top.document.getElementById('top6').innerHTML = data + top.document.getElementById('top6').innerHTML;
                            // Update percentage of goal
                           	//parent.parent.goalPercentageCompleted();
                           	//parent.parent.GB_hide(); 
                            close_window();
                               
                            return true;
                        }
                        else{
                            alert('Failed to add goal.');
                        }
                    }        
            }
       );
    }
    return false;
}
</script>
</head>
<body>
<form name="frm" action="" onsubmit="return validatefrm(this);">
<table>
    <tr>        
        <td>Enter your Goal</td>
    </tr>
    <tr>
        <td>
            <input type="text" name="goal"  size="45" />
        </td>
    </tr>    
    <tr>
        <td>
            <input type="submit" name="submit" value=" Add Goal ">
       </td>
    </tr>
</table>
</form>    
</body>
</html>
