//This prototype is provided by the Mozilla foundation and
//is distributed under the MIT license.
//http://www.ibiblio.org/pub/Linux/LICENSES/mit.license

if (!Array.prototype.indexOf)
{
  Array.prototype.indexOf = function(elt /*, from*/)
  {
    var len = this.length;

    var from = Number(arguments[1]) || 0;
    from = (from < 0)
         ? Math.ceil(from)
         : Math.floor(from);
    if (from < 0)
      from += len;

    for (; from < len; from++)
    {
      if (from in this &&
          this[from] === elt)
        return from;
    }
    return -1;
  };
}

// form validation function //
function validate(form) {
   
    var elLength = form.elements.length;
    var fieldArray = [];
    var cnt = 0;
    for (i=0; i<elLength; i++)
    {
        var type = form.elements[i].type;
        var name = form.elements[i].name;
        var target = name.substr(0,(name.length-2));
        
        if( fieldArray.indexOf(name) == -1 ){
            fieldArray[cnt] = target;
            cnt++;
        }
    }
    
    for (i=0; i<fieldArray.length; i++)
    {
        var fieldName = fieldArray[i];
        var target = fieldName;
        fieldName = '#' + fieldName + 'id';
        var targetId = target + 'id';
        var type = $(fieldName).attr('type');
         if( type == 'textarea' ) {
            value = $(fieldName).val();
            if( value == "" ) {
                inlineMsg(target,'Please answer this question.',2);
                return false;
            }
        }
        else if( type == 'text' ){
            value = $(fieldName).val();
            var flagText = false;
            if( value == "" ) {
                if( target == 'q8bt1' || target == 'q8bt2' || target == 'q8bt3' || target == 'q8bt4' ){
                    var checkboxName =   target.substr(0,(target.length-2));
                    var checkboxNameId = checkboxName + 'id';
                    $("input[@id='" + checkboxNameId + "']:checked").each(
                        function(){
                            if(this.checked == true){
                                if( this.value == 'xrays_date'){
                                    if( target == 'q8bt1' ){
                                        flagText = true;
                                    }
                                }
                                if(this.value == 'ct_scan_date'){
                                    if( target == 'q8bt2' ){
                                        flagText = true;
                                    }
                                }
                                if(this.value == 'none'){
                                    
                                }
                                if(this.value == 'mri_date'){
                                    if( target == 'q8bt3' ){
                                        flagText = true;
                                    }
                                }
                                if(this.value == 'other_date'){
                                    if( target == 'q8bt4' ){
                                        flagText = true;
                                    }
                                }
                            }
                            
                        }    
                    );
                    if( flagText == true){
                        inlineMsg(target,'Please answer this question.',2);
                        return false;
                    }
                }
                else{
                        inlineMsg(target,'Please answer this question.',2);
                        return false;
                }
                
            }
        }
        else if( type == 'radio' ){
             flag = $("input[@id='" + targetId + "']:checked").val();
             if( flag  == undefined){
                inlineMsg(target,'Please answer this question.',2);
                return false;
             }
        }
        else if( type == 'checkbox' ){
             flag = false;
			 if($('#q9id').val()=="no" && $('#q9id').is(":checked")){
			 
			 alert($('#q9id').val());
			 return true;
			 }
			 $("input[@id='" + targetId + "']:checked").each(
                    function(){
                        if(this.checked == true){
                            flag = true;
                        }
                    }    
             );
             if( flag  == false ){
                inlineMsg(target,'Please answer this question.',2);
                return false;
             }
            
        }
    }
    
  return true;
    
}


function test()
{
$("#extrahide1").append("<div id='abc'><table width='100%'><tr><td colspan='4' width='100%' class='pad-lft' style='padding-bottom:8px;' >a. If you have received treatment in the past for the same or similar symptoms, who did you see? <span id='q9a' >&nbsp;</span></td></tr><tr><td style='padding-right:8px;' ><input name='q9a[]' id='q9aid' type='checkbox' value='medical_doctor' /> Medical Doctor</td><td style='padding-right:8px;' ><input name='q9a[]' id='q9aid' type='checkbox' value='physical_therapist' /> Physical Therapist</td><td style='padding-right:8px;' ><input name='q9a[]' id='q9aid' type='checkbox' value='chiropractor' /> Chiropractor</td><td ><input name='q9a[]' id='q9aid' type='checkbox' value='other' /> Other</td></tr></table></div>");
$("#extrahide1").show();
}






// START OF MESSAGE SCRIPT //

var MSGTIMER = 20;
var MSGSPEED = 5;
var MSGOFFSET = 30;
var MSGHIDE = 3;

// build out the divs, set attributes and call the fade function //
function inlineMsg(target,string,autohide,top, left) {
  var msg;
  var msgcontent;
  if(!document.getElementById('msg')) {
    msg = document.createElement('div');
    msg.id = 'msg';
    msgcontent = document.createElement('div');
    msgcontent.id = 'msgcontent';
    document.body.appendChild(msg);
    msg.appendChild(msgcontent);
    msg.style.filter = 'alpha(opacity=0)';
    msg.style.opacity = 0;
    msg.alpha = 0;
  } else {
    msg = document.getElementById('msg');
    msgcontent = document.getElementById('msgcontent');
  }
  msgcontent.innerHTML = string;
  msg.style.display = 'block';
  var msgheight = msg.offsetHeight;
  var targetdiv = document.getElementById(target);
  var targetdivNew = $("#"+target);
   
  //targetdiv.focus();
  var targetheight = targetdiv.offsetHeight;
  var targetwidth = targetdiv.offsetWidth;
  var topposition = topPosition(targetdiv) - ((msgheight - targetheight) / 2);
  var leftposition = leftPosition(targetdiv) + targetwidth + MSGOFFSET;
  msg.style.top = topposition + 'px';
  msg.style.left = leftposition + 'px'; 
  window.scrollTo(0,$(targetdivNew).offset().top);  
  //alert(topposition +  ',' + leftposition)

  clearInterval(msg.timer);
  msg.timer = setInterval("fadeMsg(1)", MSGTIMER);
  if(!autohide) {
    autohide = MSGHIDE;  
  }
  window.setTimeout("hideMsg()", (autohide * 1000000));
}

// hide the form alert //
function hideMsg(msg) {
  var msg = document.getElementById('msg');
  if(!msg.timer) {
    msg.timer = setInterval("fadeMsg(0)", MSGTIMER);
  }
}

// face the message box //
function fadeMsg(flag) {
  if(flag == null) {
    flag = 1;
  }
  var msg = document.getElementById('msg');
  var value;
  if(flag == 1) {
    value = msg.alpha + MSGSPEED;
  } else {
    value = msg.alpha - MSGSPEED;
  }
  msg.alpha = value;
  msg.style.opacity = (value / 100);
  msg.style.filter = 'alpha(opacity=' + value + ')';
  if(value >= 99) {
    clearInterval(msg.timer);
    msg.timer = null;
  } else if(value <= 1) {
    msg.style.display = "none";
    clearInterval(msg.timer);
  }
}

// calculate the position of the element in relation to the left of the browser //
function leftPosition(target) {
  var left = 0;
  if(target.offsetParent) {
    while(1) {
      left += target.offsetLeft;
      if(!target.offsetParent) {
        break;
      }
      target = target.offsetParent;
    }
  } else if(target.x) {
    left += target.x;
  }
  return left;
}

// calculate the position of the element in relation to the top of the browser window //
function topPosition(target) {
  var top = 0;
  if(target.offsetParent) {
    while(1) {
      top += target.offsetTop;
      if(!target.offsetParent) {
        break;
      }
      target = target.offsetParent;
    }
  } else if(target.y) {
    top += target.y;
  }
  return top;
}

// preload the arrow //
if(document.images) {
  arrow = new Image(7,80); 
  arrow.src = "images/msg_arrow.gif"; 
}