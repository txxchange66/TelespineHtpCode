<html>
<head>
<script src="js/jquery.js"></script>  
<script language="Javascript" >
function close_window(){
    top.document.location.reload();
    parent.parent.GB_CURRENT.hide(); 
}
function stikeout(obj){
    if( obj != null ){
        var content = $('#span_' + obj.value).html();
        patient_goal_id = obj.value;
        $('#span_' + obj.value).html("<img src='/images/horizontal-preloader.gif' />");
        if( obj.checked ===  true ){
            $.post('index.php?action=update_goal',{patient_goal_id:patient_goal_id,status:2}, function(data,status){
                        if( status == "success" ){
                            if(/success/.test(data)){
                                $('#span_' + obj.value).html(content);
                                $('#span_' + obj.value).css('text-decoration', 'line-through');
                            }
                            else{
                                $('#span_' + obj.value).html(content);
                                alert('Failed to update goal.');
                            }
                        }        
                    }
            );
        }
        else{
            $.post('index.php?action=update_goal',{patient_goal_id:patient_goal_id,status:1}, function(data,status){
                        if( status == "success" ){
                            if(/success/.test(data)){
                                $('#span_' + obj.value).css('text-decoration', 'none');
                                $('#span_' + obj.value).html(content);
                            }
                            else{
                                alert('Failed to update goal.');
                                $('#span_' + obj.value).html(content);
                            }
                        }        
                    }
            );
        }
        
    }
}
function show_trash(obj,display){
    if(obj != null ){
        var id = obj.id;
        id = id.substr(4);
        if( display == 1 ){
            $('#trash_' + id).css('visibility', 'visible');
        }
        else{
            $('#trash_' + id).css('visibility', 'hidden');
        }
    }
}

function del_goal(obj){
    var id = obj.id;
    id = id.substr(6);
    var patient_goal_id = id;
    var content = $('#span_' + id).html();
    $('#span_' + id).html("<img src='/images/horizontal-preloader.gif' />");
    $.post('index.php?action=update_goal',{patient_goal_id:patient_goal_id,status:3}, function(data,status){
                        if( status == "success" ){
                            if(/success/.test(data)){
                                $('#div_' + id).remove();
                               
                            }
                            else{
                                alert('Failed to delete goal.');
                                $('#span_' + id).html(content);
                            }
                        }        
                    }
            );
}

</script>
</head>
<body>
<div style="font-size:16px;padding-left:18px;padding-bottom:10px;" ><strong>List of Goals</strong></div>
<div id="top6" style="vertical-align:top; ">
            <!list_goal>
</div>
<div style="font-size:14px;padding-left:18px;padding-top:10px;" ><input type="button" name="submit" value=" Close " onclick="close_window();" /></div>
</body>        
