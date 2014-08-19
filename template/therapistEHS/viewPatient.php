<link rel="STYLESHEET" type="text/css" href="css/calendar.css">
<script language="JavaScript" type="text/javascript"
	src="js/prototype.min.js"></script>
        <!--
<script language='javascript' type='text/javascript' src='js/jquery.js'></script>
        -->

<div id="container">
<div id="header"><!header></div>

<div id="sidebar"><!sidebar></div>
<div id="mainContent">
<table style="vertical-align: middle; width: 100%;" border ="0">
	<tr>
		<td style="width: 600px;" > <!automaticscheduling>
                                    </td>
		
		<td style="width: 100px;">
		<table border="0" cellpadding="5" cellspacing="0"
			style="float: right;">
			<tr>
				<td class="iconLabel"></td>
				<td class="iconLabel"><!navigation_image_view_patient></td>
			</tr>

		</table>
		</td>
	</tr>
</table>

<script type="text/javascript">

function handlePatientArticleAction(s, id,pid, paid)
	{
		var a = s.options[s.options.selectedIndex].value;
		var c = false;
		switch (a)
		{
			case 'preview':
				showArticlePreview(id);
				break;
			case 'added_article_preview':
				showArticlePreview(id);
				break;
			default:
				c = true;
				break;
		}

		s.options.selectedIndex = 0;

		if (c) window.location.href = 'index.php?action=remove_ehs_article&article_id=' + id +'&patientArticleId='+ paid;
	}

function showArticlePreview(id)
	{
		if(!csw) var csw = window.open('index.php?action=articlePreview&aid='+id, 'catSelectWindow', 'width=750, height=480, status=no, toolbar=no, resizable=1,scrollbars=yes');
		csw.focus();
	}

function add_goal(){
   
         GB_showCenter('Add Goal', '/index.php?action=add_goal_ehs&therapistId=<!therpaistId>&ehsunsub=<!ehsunsub>', 125, 355 );
    
}


function handleAction(s, id)
	{
		var a = s.options[s.options.selectedIndex].value;
		var c = false;
		switch (a)
		{
			case 'view_patient_details':
				if(!patient_detail_win)
				{
					var patient_detail_win = window.open('patient_detail_popup.php?id='+patient_id, 'Patient Details', 'width=750,height=480,resizable=1,scrollbars=auto');
				}
				patient_detail_win.focus();
				c = false;
				break;
			default:
				c = true;
				break;
		}
		s.options.selectedIndex = 0;
		if (c) window.location.href = '/admin/patient_detail.php?id=11'+ '&act=' + a + '&id=' + _id;
	}
	function submitFilter()
	{
		if(document.forms['filter'].elements['search'].value != '') document.forms['filter'].submit();
	}
	
	function showCatSelect(patient_id)
	{
		if(!csw) var csw = window.open('patient2user_cat_select.php?patient_id='+patient_id, 'catSelectWindow', 'width=750, height=480, status=no, toolbar=no, resizable=1');
		csw.focus();
	}
function handlePlanAction(s, p2p_id)
{
	var a = s.options[s.options.selectedIndex].value;
	var c = false;
       
	switch (a)
	{
		case 'deletePlan':
			c = confirm('Deleting this plan will remove all record of it from the site. Are you sure you want to continue with deleting this plan?');
			break;
		case 'viewPlan':
			if(!plan_detail_win)
			{
				var plan_detail_win = window.open('index.php?action=planViewer&id='+p2p_id, 'PlanPreview', 'width=1024,height=800,resizable=1,scrollbars=yes');
			}
			plan_detail_win.focus();
			c = false;
			break;
		default:
			c = true;
			break;
	}
	s.options.selectedIndex = 0;
	if (c) window.location.href = 'index.php?action=therapistEhsPatient&' + 'act=' + a + '&plan_id=' + p2p_id;
}

function handle_action(s, id)
{
	var a = s.options[s.options.selectedIndex].value;
	var c = false;

	switch (a)
	{
		case 'delete':
			c = confirm('Discharging this patient will prevent them from logging-in and viewing treatment plans.  Are you sure you would like to continue with discharging this patient?');
			break;
		case 'undelete':
			c = confirm('Restoring this discharged patient will allow them to log-in and view treatment plans.  Are you sure you would like to continue with restoring this patient?');
			break;
		case 'realdelete':
			c = confirm('Deleting this patient will remove all record of them from the site. Are you sure you want to continue with deleting this patient?');
			break;
		
		default:
			c = true;
			break;
	}

	s.options.selectedIndex = 0;

	if (c) window.location.href = '/admin/patient_detail.php?' + 'act=' + a + '&id=' + id;
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
    $('#span_' + id).html("<img src='/images/horizontal-preloader.gif' align='bottom' />");
    $.post('index.php?action=update_goal_ehs',{patient_goal_id:patient_goal_id,status:3}, function(data,status){
                        if( status == "success" ){
                            if(/success/.test(data)){
                                $('#div_' + id).remove();
                                 // update percentage of goal
                                 goalPercentageCompleted();
                            }
                            else{
                                alert('Failed to delete goal.');
                                $('#span_' + id).html(content);
                            }
                        }        
                    }
            );
}

function stikeout(obj){
    if( obj != null ){
        var content = $('#span_' + obj.value).html();
        patient_goal_id = obj.value;
        $('#span_' + obj.value).html("<img src='/images/horizontal-preloader.gif' align='bottom' />");
        if( obj.checked ===  true ){
            $.post('index.php?action=update_goal_ehs',{patient_goal_id:patient_goal_id,status:2}, function(data,status){
                        if( status == "success" ){
                            if(/success/.test(data)){
                                $('#span_' + obj.value).html(content);
                                $('#span_' + obj.value).css('text-decoration', 'line-through');
                                // Update goal percentage
                               // goalPercentageCompleted();
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
            $.post('index.php?action=update_goal_ehs',{patient_goal_id:patient_goal_id,status:1}, function(data,status){
                        if( status == "success" ){
                            if(/success/.test(data)){
                                $('#span_' + obj.value).css('text-decoration', 'none');
                                $('#span_' + obj.value).html(content);
                                // Update goal percentage
                                goalPercentageCompleted();
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


function goalPercentageCompleted(){
    // get goal percentage completed
    var url =  'index.php?action=goal_completed_ehs';
    $('#goal_percentage').html('<img src="/images/ajax-loader.gif" />');
     $.post(url,{userId:<!therpaistId>}, function(data,status){
                        if( status == "success" ){
                        	//data=10;
                            $('#goal_percentage').html(data + '%');
                            //$(this).hide();
                        }        
                    }
            );
}

</script>
<script language="Javascript">
function mypopup(id)
 {
	var randomnumber=Math.floor(Math.random()*11)
    url = "index.php?action=video_conference&patient_id=" + id +"&rand="+randomnumber;
     mywindow = window.open (url,"mywindow","location=0,status=0,scrollbars=0,width=525,height=345");
     mywindow.moveTo(0,0);
} 
function gotosoapnote(id){
	window.location = "/index.php?action=provider_view_soap_note&id="+id;
}
</script>
<script type="text/javascript">

/*****************************************************************************
Copyright (C) 2006  Nick Baicoianu

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*****************************************************************************/
//constructor for the main Epoch class (ENGLISH VERSION)
function Epoch(name,mode,targetelement,multiselect)
{
	
	this.state = 0;
	this.name = name;
	this.curDate = new Date();
	this.mode = mode;
	this.selectMultiple = (multiselect == true); //'false' is not true or not set at all
	
	//the various calendar variables
	//this.selectedDate = this.curDate;
	//Wed May 21 2008 16:25:40 GMT+0530 (India Standard Time)
	this.selectedDates = new Array();
	//this.selectedDates[0] = new Date('21 May 2008 16:25:40 GMT+0530 (India Standard Time)');
	//this.selectedDates[1] = new Date('22 Jun 2008 16:25:40');
	//this.selectedDates[2] = new Date('24 May 2008 16:29:31');
	this.calendar;
	this.calHeading;
	this.calCells;
	this.rows;
	this.cols;
	this.cells = new Array();
	
	//The controls
	this.monthSelect;
	this.yearSelect;
	
	//standard initializations
	this.mousein = false;
	this.calConfig();
	this.setDays();
	this.displayYear = this.displayYearInitial;
	this.displayMonth = this.displayMonthInitial;
	
	this.createCalendar(); //create the calendar DOM element and its children, and their related objects
	
	if(this.mode == 'popup' && targetelement && targetelement.type == 'text') //if the target element has been set to be an input text box
	{
		this.tgt = targetelement;
		this.calendar.style.position = 'absolute';
		this.topOffset = this.tgt.offsetHeight; // the vertical distance (in pixels) to display the calendar from the Top of its input element
		this.leftOffset = 0; 					// the horizontal distance (in pixels) to display the calendar from the Left of its input element
		this.calendar.style.top = this.getTop(targetelement) + this.topOffset + 'px';
		this.calendar.style.left = this.getLeft(targetelement) + this.leftOffset + 'px';
		document.body.appendChild(this.calendar);
		this.tgt.calendar = this;
		this.tgt.onfocus = function () {this.calendar.show();}; //the calendar will popup when the input element is focused
		this.tgt.onblur = function () {if(!this.calendar.mousein){this.calendar.hide();}}; //the calendar will popup when the input element is focused
	}
	else
	{
		this.container = targetelement;
		this.container.appendChild(this.calendar);
	}
	
	this.state = 2; //0: initializing, 1: redrawing, 2: finished!
	this.visible ? this.show() : this.hide();
}
//-----------------------------------------------------------------------------
Epoch.prototype.calConfig = function () //PRIVATE: initialize calendar variables
{
	
	//this.mode = 'flat'; //can be 'flat' or 'popup'
	this.displayYearInitial = this.curDate.getFullYear(); //the initial year to display on load
	this.displayMonthInitial = this.curDate.getMonth(); //the initial month to display on load (0-11)
	this.rangeYearLower = 2005;
	this.rangeYearUpper = 2037;
	this.minDate = new Date(2005,0,1);
	this.maxDate = new Date(2037,0,1);
	this.startDay = 0; // the day the week will 'start' on: 0(Sun) to 6(Sat)
	this.showWeeks = false; //whether the week numbers will be shown
	this.selCurMonthOnly = true; //allow user to only select dates in the currently displayed month
	this.clearSelectedOnChange = true; //whether to clear all selected dates when changing months
	
	//flat mode-only settings:
	//this.selectMultiple = true; //whether the user can select multiple dates (flat mode only)

	switch(this.mode) //set the variables based on the calendar mode
	{
		case 'popup': //popup options
			this.visible = false;
			break;
		case 'flat':
			this.visible = true;
			
			break;
	}
	this.setLang();
};
//-----------------------------------------------------------------------------
Epoch.prototype.setLang = function()  //all language settings for Epoch are made here.  Check Date.dateFormat() for the Date object's language settings
{
	
	
	
	this.daylist = new Array('Su','Mo','Tu','We','Th','Fr','Sa','Su','Mo','Tu','We','Th','Fr','Sa'); /*<lang:en>*/
	this.months_sh = new Array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
	this.monthup_title = 'Go to the next month';
	this.monthdn_title = 'Go to the previous month';
	this.clearbtn_caption = 'Clear';
	this.clearbtn_title = 'Clears any dates selected on the calendar';
	this.maxrange_caption = 'This is the maximum range';
};
//-----------------------------------------------------------------------------
Epoch.prototype.getTop = function (element) //PRIVATE: returns the absolute Top value of element, in pixels
{
    
	
	var oNode = element;
    var iTop = 0;
    
    while(oNode.tagName != 'BODY') {
        iTop += oNode.offsetTop;
        oNode = oNode.offsetParent;
    }
    
    return iTop;
};
//-----------------------------------------------------------------------------
Epoch.prototype.getLeft = function (element) //PRIVATE: returns the absolute Left value of element, in pixels
{
    
	
	var oNode = element;
    var iLeft = 0;
    
    while(oNode.tagName != 'BODY') {
        iLeft += oNode.offsetLeft;
        oNode = oNode.offsetParent;        
    }
    
    return iLeft;
};
//-----------------------------------------------------------------------------
Epoch.prototype.show = function () //PUBLIC: displays the calendar
{
	
	
	this.calendar.style.display = 'block';
	this.visible = true;
};
//-----------------------------------------------------------------------------
Epoch.prototype.hide = function () //PUBLIC: Hides the calendar
{
	
	
	this.calendar.style.display = 'none';
	this.visible = false;
};
//-----------------------------------------------------------------------------
Epoch.prototype.toggle = function () //PUBLIC: Toggles (shows/hides) the calendar depending on its current state
{
	
	
	if(this.visible) {
		this.hide();
	}
	else {
		this.show();
	}
};
//-----------------------------------------------------------------------------
Epoch.prototype.setDays = function ()  //PRIVATE: initializes the standard Gregorian Calendar parameters
{
	
	
	
	this.daynames = new Array();
	var j=0;
	for(var i=this.startDay; i< this.startDay + 7;i++) {
		this.daynames[j++] = this.daylist[i];
	}
	
	this.monthDayCount = new Array(31,((this.curDate.getFullYear() - 2000) % 4 ? 28 : 29),31,30,31,30,31,31,30,31,30,31);
};
//-----------------------------------------------------------------------------
Epoch.prototype.setClass = function (element,className) //PRIVATE: sets the CSS class of the element, W3C & IE
{
	
	
	element.setAttribute('class',className);
	element.setAttribute('className',className); //<iehack>
};
//-----------------------------------------------------------------------------
Epoch.prototype.createCalendar = function ()  //PRIVATE: creates the full DOM implementation of the calendar
{
	
	
	var tbody, tr, td;
	this.calendar = document.createElement('table');
	this.calendar.setAttribute('id',this.name+'_calendar');
	this.setClass(this.calendar,'calendar');
	//to prevent IE from selecting text when clicking on the calendar
	this.calendar.onselectstart = function() {return false;};
	this.calendar.ondrag = function() {return false;};
	tbody = document.createElement('tbody');
	
	//create the Main Calendar Heading
	tr = document.createElement('tr');
	td = document.createElement('td');
	td.appendChild(this.createMainHeading());
	tr.appendChild(td);
	tbody.appendChild(tr);
	
	//create the calendar Day Heading
	tr = document.createElement('tr');
	td = document.createElement('td');
	td.appendChild(this.createDayHeading());
	tr.appendChild(td);
	tbody.appendChild(tr);

	//create the calendar Day Cells
	tr = document.createElement('tr');
	td = document.createElement('td');
	td.setAttribute('id',this.name+'_cell_td');
	this.calCellContainer = td;	//used as a handle for manipulating the calendar cells as a whole
	td.appendChild(this.createCalCells());
	tr.appendChild(td);
	tbody.appendChild(tr);
	
	//create the calendar footer
	tr = document.createElement('tr');
	td = document.createElement('td');
	td.appendChild(this.createFooter());
	tr.appendChild(td);
	tbody.appendChild(tr);
	
	//add the tbody element to the main calendar table
	this.calendar.appendChild(tbody);

	//and add the onmouseover events to the calendar table
	this.calendar.owner = this;
	this.calendar.onmouseover = function() {this.owner.mousein = true;};
	this.calendar.onmouseout = function() {this.owner.mousein = false;};
};
//-----------------------------------------------------------------------------
Epoch.prototype.createMainHeading = function () //PRIVATE: Creates the primary calendar heading, with months & years
{
	
	
	//create the containing <div> element
	var container = document.createElement('div');
	container.setAttribute('id',this.name+'_mainheading');
	this.setClass(container,'mainheading');
	//create the child elements and other variables
	this.monthSelect = document.createElement('select');
	this.yearSelect = document.createElement('select');
	
	
	var monthDn = document.createElement('input'), monthUp = document.createElement('input');
	var opt, i;
	//fill the month select box
	for(i=0;i<12;i++)
	{
		opt = document.createElement('option');
		opt.setAttribute('value',i);
		if(this.state == 0 && this.displayMonth == i) {
			opt.setAttribute('selected','selected');
		}
		opt.appendChild(document.createTextNode(this.months_sh[i]));
		this.monthSelect.appendChild(opt);
	}
	//and fill the year select box
	
	for(i=this.rangeYearLower;i<=this.rangeYearUpper;i++)
	{
		opt = document.createElement('option');
		opt.setAttribute('value',i);
		if(this.state == 0 && this.displayYear == i) {
			opt.setAttribute('selected','selected');
		}
		opt.appendChild(document.createTextNode(i));
		this.yearSelect.appendChild(opt);		
	}
	
	//add the appropriate children for the month buttons
	monthUp.setAttribute('type','button');
	monthUp.setAttribute('value','>');
	monthUp.setAttribute('title',this.monthup_title);
	monthDn.setAttribute('type','button');
	monthDn.setAttribute('value','<');
	monthDn.setAttribute('title',this.monthdn_title);
	this.monthSelect.owner = this.yearSelect.owner = monthUp.owner = monthDn.owner = this;  //hack to allow us to access this calendar in the events (<fix>??)
	
	//assign the event handlers for the controls
	monthUp.onmouseup = function () {this.owner.nextMonth();};
	monthDn.onmouseup = function () {this.owner.prevMonth();};
	this.monthSelect.onchange = function() {
		this.owner.displayMonth = this.value;
		this.owner.displayYear = this.owner.yearSelect.value; 
		this.owner.goToMonth(this.owner.displayYear,this.owner.displayMonth);
	};
	this.yearSelect.onchange = function() {
		this.owner.displayMonth = this.owner.monthSelect.value;
		this.owner.displayYear = this.value; 
		this.owner.goToMonth(this.owner.displayYear,this.owner.displayMonth);
	};
	
	//and finally add the elements to the containing div
	container.appendChild(monthDn);
	container.appendChild(this.monthSelect);
	//@ HTP commented by htp.
	//container.appendChild(this.yearSelect);
	//@ HTP customized to remove year drop down.
	container.appendChild(document.createTextNode(""));
	container.appendChild(monthUp);
	return container;
};
//-----------------------------------------------------------------------------
Epoch.prototype.createFooter = function () //PRIVATE: creates the footer of the calendar - goes under the calendar cells
{
	var container = document.createElement('div');
	var clearSelected = document.createElement('input');
	clearSelected.setAttribute('type','button');
	clearSelected.setAttribute('value',this.clearbtn_caption);
	clearSelected.setAttribute('title',this.clearbtn_title);
	clearSelected.owner = this;
	clearSelected.onclick = function() { this.owner.resetSelections(false);};
	container.appendChild(clearSelected);
	return container;
};
//-----------------------------------------------------------------------------
Epoch.prototype.resetSelections = function (returnToDefaultMonth)  //PRIVATE: reset the calendar's selection variables to defaults
{
	
	if(this.selectedDates.length > 0){
			for(i=0;i<this.selectedDates.length;i++){
				this.selectedDates[i] = this.selectedDates[i].dateFormat('Y-m-d H:i:s'); 
			}
			scheduled_dated = this.selectedDates.join(",");
			$.post('index.php?action=removeReminderScheduleEhs',{clinicId:<!clinicId>,scheduled_date:scheduled_dated,reminder_set:1}, function(data,status){
					
					//$("div#multi_container").fadeTo("slow",1);
					}
			)
		
	}
	
	this.selectedDates = new Array();
	this.rows = new Array(false,false,false,false,false,false,false);
	this.cols = new Array(false,false,false,false,false,false,false);
	if(this.tgt)  //if there is a target element, clear it too
	{
		this.tgt.value = '';
		if(this.mode == 'popup') {//hide the calendar if in popup mode
			this.hide();
		}
	}
		
	if(returnToDefaultMonth == true) {
		this.goToMonth(this.displayYearInitial,this.displayMonthInitial);
	}
	else {
		this.reDraw();
	}
};
//-----------------------------------------------------------------------------
Epoch.prototype.createDayHeading = function ()  //PRIVATE: creates the heading containing the day names
{
	//create the table element
	this.calHeading = document.createElement('table');
	this.calHeading.setAttribute('id',this.name+'_caldayheading');
	this.setClass(this.calHeading,'caldayheading');
	var tbody,tr,td;
	tbody = document.createElement('tbody');
	tr = document.createElement('tr');
	this.cols = new Array(false,false,false,false,false,false,false);
	
	//if we're showing the week headings, create an empty <td> for filler
	if(this.showWeeks)
	{
		td = document.createElement('td');
		td.setAttribute('class','wkhead');
		td.setAttribute('className','wkhead'); //<iehack>
		tr.appendChild(td);
	}
	//populate the day titles
	for(var dow=0;dow<7;dow++)
	{
		td = document.createElement('td');
		td.appendChild(document.createTextNode(this.daynames[dow]));
		if(this.selectMultiple) { //if selectMultiple is true, assign the cell a CalHeading Object to handle all events
			td.headObj = new CalHeading(this,td,(dow + this.startDay < 7 ? dow + this.startDay : dow + this.startDay - 7));
		}
		tr.appendChild(td);
	}
	tbody.appendChild(tr);
	this.calHeading.appendChild(tbody);
	return this.calHeading;	
};
//-----------------------------------------------------------------------------
Epoch.prototype.createCalCells = function ()  //PRIVATE: creates the table containing the calendar day cells
{
	this.rows = new Array(false,false,false,false,false,false);
	this.cells = new Array();
	var row = -1, totalCells = (this.showWeeks ? 48 : 42);
	var beginDate = new Date(this.displayYear,this.displayMonth,1);
	
	
	var endDate = new Date(this.displayYear,this.displayMonth + 1,this.monthDayCount[this.displayMonth]);
	
	var sdt = new Date(beginDate);
	
	sdt.setDate(sdt.getDate() + (this.startDay - beginDate.getDay()) - (this.startDay - beginDate.getDay() > 0 ? 7 : 0) );
	//create the table element
	this.calCells = document.createElement('table');
	this.calCells.setAttribute('id',this.name+'_calcells');
	this.setClass(this.calCells,'calcells');
	var tbody,tr,td;
	tbody = document.createElement('tbody');

	/* @HTP Start */
		var beginSdt = new Date(beginDate);
		var endSdt = new Date(endDate);
		//alert(endDate);
		var startDate = beginSdt.getDate();
		
	/* @HTP End */
	
	for(var i=0;i<totalCells;i++)
	{
		if(this.showWeeks) //if we are showing the week headings
		{
			if(i % 8 == 0)
			{
				row++;
				tr = document.createElement('tr');
				td = document.createElement('td');
				if(this.selectMultiple) { //if selectMultiple is enabled, create the associated weekObj objects
					td.weekObj = new WeekHeading(this,td,sdt.getWeek(),row)
				}
				else //otherwise just set the class of the td for consistent look
				{
					td.setAttribute('class','wkhead');
					td.setAttribute('className','wkhead'); //<iehack>
				}
				td.appendChild(document.createTextNode(sdt.getWeek()));			
				tr.appendChild(td);
				i++;
			}
		}
		else if(i % 7 == 0) //otherwise, new row every 7 cells
		{
			row++;
			tr = document.createElement('tr');
		}
		//create the day cells
		td = document.createElement('td');
		
		/* @HTP Start */
		
		//if( sdt.getDate() == startDate && sdt.getDate() <= endSdt.getDate() ){
		if( sdt.getDate() == startDate  ){
			td.appendChild(document.createTextNode(sdt.getDate()));// +' ' +sdt.getUeDay()));	
			startDate++;
		}
		else{
			td.appendChild(document.createTextNode(""));// +' ' +sdt.getUeDay()));	
		}
		
		/* @HTP End */
		
		// @HTP commented by HTP
		//td.appendChild(document.createTextNode(sdt.getDate()));// +' ' +sdt.getUeDay()));
		
		var cell = new CalCell(this,td,sdt,row);
		this.cells.push(cell);
		td.cellObj = cell;
		sdt.setDate(sdt.getDate() + 1); //increment the date
		
		tr.appendChild(td);
		tbody.appendChild(tr);
	}
	this.calCells.appendChild(tbody);
	this.reDraw();
	return this.calCells;
};
//-----------------------------------------------------------------------------
Epoch.prototype.reDraw = function () //PRIVATE: reapplies all the CSS classes for the calendar cells, usually called after chaning their state
{
	this.state = 1;
	var i,j;
	
	for(i=0;i<this.cells.length;i++) {
		this.cells[i].selected = false;
	}
	for(i=0;i<this.cells.length;i++)
	{
		
		for(j=0;j<this.selectedDates.length;j++) { //if the cell's date is in the selectedDates array, set its selected property to true
			
			if(this.cells[i].date.getUeDay() == this.selectedDates[j].getUeDay() ) {
				this.cells[i].selected = true;
			}
		}
		
		/* @HTP Start,to unselect the date which is not coming in ths selected month. */
		if(this.displayMonth != this.cells[i].date.getMonth()){
			this.cells[i].selected = false;
		}
		/* @HTP End,to unselect the date which is not coming in ths selected month. */
		
		this.cells[i].setClass();
	}

	this.state = 2;
};
//-----------------------------------------------------------------------------
Epoch.prototype.deleteCells = function () //PRIVATE: removes the calendar cells from the DOM (does not delete the cell objects associated with them
{
	
	this.calCellContainer.removeChild(this.calCellContainer.firstChild); //get a handle on the cell table (optional - for less indirection)
	this.cells = new Array(); //reset the cells array
};
//-----------------------------------------------------------------------------
Epoch.prototype.goToMonth = function (year,month) //PUBLIC: sets the calendar to display the requested month/year
{
	this.monthSelect.value = this.displayMonth = month;
	this.yearSelect.value = this.displayYear = year;
	this.deleteCells();
	this.calCellContainer.appendChild(this.createCalCells());
};
//-----------------------------------------------------------------------------
Epoch.prototype.nextMonth = function () //PUBLIC: go to the next month.  if the month is december, go to january of the next year
{
	
	//increment the month/year values, provided they're within the min/max ranges
	if(this.monthSelect.value < 11) {
		this.monthSelect.value++;
	}
	else
	{
		if(this.yearSelect.value < this.rangeYearUpper)
		{
			this.monthSelect.value = 0;
			this.yearSelect.value++;
		}
		else {
			alert(this.maxrange_caption);
		}
	}
	//assign the currently displaying month/year values
	this.displayMonth = this.monthSelect.value;
	this.displayYear = this.yearSelect.value;
	
	//and refresh the calendar for the new month/year
	this.deleteCells();
	this.calCellContainer.appendChild(this.createCalCells());
};
//-----------------------------------------------------------------------------
Epoch.prototype.prevMonth = function () //PUBLIC: go to the previous month.  if the month is january, go to december of the previous year
{
	
	//increment the month/year values, provided they're within the min/max ranges
	if(this.monthSelect.value > 0)
		this.monthSelect.value--;
	else
	{
		if(this.yearSelect.value > this.rangeYearLower)
		{
			this.monthSelect.value = 11;
			this.yearSelect.value--;
		}
		else {
			alert(this.maxrange_caption);
		}
	}
	
	//assign the currently displaying month/year values
	this.displayMonth = this.monthSelect.value;
	this.displayYear = this.yearSelect.value;
	
	//and refresh the calendar for the new month/year
	this.deleteCells();
	this.calCellContainer.appendChild(this.createCalCells());
};
//-----------------------------------------------------------------------------
Epoch.prototype.addZero = function (vNumber) //PRIVATE: pads a 2 digit number with a leading zero
{
	return ((vNumber < 10) ? '0' : '') + vNumber;
};
//-----------------------------------------------------------------------------
Epoch.prototype.addDates = function (dates,redraw)  //PUBLIC: adds the array "dates" to the calendars selectedDates array (no duplicate dates) and redraws the calendar
{
	
	var j,in_sd;
	for(var i=0;i<dates.length;i++)
	{	
		in_sd = false;
		for(j=0;j<this.selectedDates.length;j++)
		{
			if(dates[i].getUeDay() == this.selectedDates[j].getUeDay())
			{
				in_sd = true;
				break;
			}
		}
		if(!in_sd) { //if the date isn't already in the array, add it!
			this.selectedDates.push(dates[i]);
		}
	}
	if(redraw != false) {//redraw  the calendar if "redraw" is false or undefined
		this.reDraw();
	}
};
//-----------------------------------------------------------------------------
Epoch.prototype.removeDates = function (dates,redraw)  //PUBLIC: adds the dates to the calendars selectedDates array and redraws the calendar
{
	
	var j;
	for(var i=0;i<dates.length;i++)
	{
		for(j=0;j<this.selectedDates.length;j++)
		{
			if(dates[i].getUeDay() == this.selectedDates[j].getUeDay()) { //search for the dates in the selectedDates array, removing them if the dates match
				this.selectedDates.splice(j,1);
			}
		}
	}
	if(redraw != false) { //redraw  the calendar if "redraw" is false or undefined
		this.reDraw();
	}
};
//-----------------------------------------------------------------------------
Epoch.prototype.outputDate = function (vDate, vFormat) //PUBLIC: outputs a date in the appropriate format (DEPRECATED)
{
	var vDay			= this.addZero(vDate.getDate()); 
	var vMonth			= this.addZero(vDate.getMonth() + 1); 
	var vYearLong		= this.addZero(vDate.getFullYear()); 
	var vYearShort		= this.addZero(vDate.getFullYear().toString().substring(3,4)); 
	var vYear			= (vFormat.indexOf('yyyy') > -1 ? vYearLong : vYearShort);
	var vHour			= this.addZero(vDate.getHours()); 
	var vMinute			= this.addZero(vDate.getMinutes()); 
	var vSecond			= this.addZero(vDate.getSeconds()); 
	return vFormat.replace(/dd/g, vDay).replace(/mm/g, vMonth).replace(/y{1,4}/g, vYear).replace(/hh/g, vHour).replace(/nn/g, vMinute).replace(/ss/g, vSecond);
};
//-----------------------------------------------------------------------------
Epoch.prototype.updatePos = function (target) //PUBLIC: moves the calendar's position to target's location (popup mode only)
{
	this.calendar.style.top = this.getTop(target) + this.topOffset + 'px'
	this.calendar.style.left = this.getLeft(target) + this.leftOffset + 'px'
}
//-----------------------------------------------------------------------------

/*****************************************************************************/
function CalHeading(owner,tableCell,dow)
{
	this.owner = owner;
	this.tableCell = tableCell;
	this.dayOfWeek = dow;
	
	//the event handlers
	this.tableCell.onclick = this.onclick;
}
//-----------------------------------------------------------------------------
CalHeading.prototype.onclick = function ()
{
	//@HTP Start
	return false;
	//@HTP End
	
	//reduce indirection:
	var owner = this.headObj.owner;
	var sdates = owner.selectedDates;
	var cells = owner.cells;
	
	owner.cols[this.headObj.dayOfWeek] = !owner.cols[this.headObj.dayOfWeek];
	for(var i=0;i<cells.length;i++) //cycle through all the cells in the calendar, selecting all cells with the same dayOfWeek as this heading
	{
		if(cells[i].dayOfWeek == this.headObj.dayOfWeek && (!owner.selCurMonthOnly || cells[i].date.getMonth() == owner.displayMonth && cells[i].date.getFullYear() == owner.displayYear)) //if the cell's DoW matches, with other conditions
		{
			if(owner.cols[this.headObj.dayOfWeek]) 		//if selecting, add the cell's date to the selectedDates array
			{
				if(owner.selectedDates.arrayIndex(cells[i].date) == -1) { //if the date isn't already in the array
					
					sdates.push(cells[i].date);
				}
			}
			else										//otherwise, remove it
			{
				for(var j=0;j<sdates.length;j++) 
				{
					if(cells[i].dayOfWeek == sdates[j].getDay())
					{
						sdates.splice(j,1);	//remove dates that are within the displaying month/year that have the same day of week as the day cell
						break;
					}
				}
			}
			cells[i].selected = owner.cols[this.headObj.dayOfWeek];
		}
	}
	
	owner.reDraw();
};
/*****************************************************************************/
function WeekHeading(owner,tableCell,week,row)
{
	
	this.owner = owner;
	this.tableCell = tableCell;
	this.week = week;
	this.tableRow = row;
	this.tableCell.setAttribute('class','wkhead');
	this.tableCell.setAttribute('className','wkhead'); //<iehack>
	//the event handlers
	this.tableCell.onclick = this.onclick;
	
}
//-----------------------------------------------------------------------------
WeekHeading.prototype.onclick = function ()
{
	
	//reduce indirection:
	var owner = this.weekObj.owner;
	var cells = owner.cells;
	var sdates = owner.selectedDates;
	var i,j;
	owner.rows[this.weekObj.tableRow] = !owner.rows[this.weekObj.tableRow];
	for(i=0;i<cells.length;i++)
	{
		if(cells[i].tableRow == this.weekObj.tableRow)
		{
			if(owner.rows[this.weekObj.tableRow] && (!owner.selCurMonthOnly || cells[i].date.getMonth() == owner.displayMonth && cells[i].date.getFullYear() == owner.displayYear)) //match all cells in the current row, with option to restrict to current month only
			{
				if(owner.selectedDates.arrayIndex(cells[i].date) == -1) {//if the date isn't already in the array
					sdates.push(cells[i].date);
				}
			}
			else										//otherwise, remove it
			{
				for(j=0;j<sdates.length;j++)
				{
					if(sdates[j].getTime() == cells[i].date.getTime())  //this.weekObj.tableRow && sdates[j].getMonth() == owner.displayMonth && sdates[j].getFullYear() == owner.displayYear)
					{
						sdates.splice(j,1);	//remove dates that are within the displaying month/year that have the same day of week as the day cell
						break;
					}
				}
			}
		}
	}
	
	owner.reDraw();
};
/*****************************************************************************/
//-----------------------------------------------------------------------------
function CalCell(owner,tableCell,dateObj,row)
{
	this.owner = owner;		//used primarily for event handling
	this.tableCell = tableCell; 			//the link to this cell object's table cell in the DOM
	this.cellClass;			//the CSS class of the cell
	this.selected = false;	//whether the cell is selected (and is therefore stored in the owner's selectedDates array)
	this.date = new Date(dateObj);
	this.dayOfWeek = this.date.getDay();
	this.week = this.date.getWeek();
	this.tableRow = row;
	
	//assign the event handlers for the table cell element
	this.tableCell.onclick = this.onclick;
	this.tableCell.onmouseover = this.onmouseover;
	this.tableCell.onmouseout = this.onmouseout;
	
	//and set the CSS class of the table cell
	this.setClass();
}
//-----------------------------------------------------------------------------
CalCell.prototype.onmouseover = function () //replicate CSS :hover effect for non-supporting browsers <iehack>
{
	this.cellObj.setClass();
	/*
	if(this.selected) {
		this.cellClass = 'cell_selected';
	}
	else if(this.owner.displayMonth != this.date.getMonth() ) {
		this.cellClass = 'notmnth';	
		
	}
	else if(this.date.getDay() > 0 && this.date.getDay() < 6) {
		this.cellClass = 'wkday';
	}
	else {
		this.cellClass = 'wkend';
	}
	
	if(this.date.getFullYear() == this.owner.curDate.getFullYear() && this.date.getMonth() == this.owner.curDate.getMonth() && this.date.getDate() == this.owner.curDate.getDate()) {
		this.cellClass = this.cellClass + ' curdate';
	}

	this.tableCell.setAttribute('class',this.cellClass);
	this.tableCell.setAttribute('className',this.cellClass); //<iehack>
	*/
	/* Start commented by @HTP */
	
	//this.setAttribute('class',this.cellClass + ' hover');
	//this.setAttribute('className',this.cellClass + ' hover');
	
	/* End */
};
//-----------------------------------------------------------------------------
CalCell.prototype.onmouseout = function () //replicate CSS :hover effect for non-supporting browsers <iehack>
{
	this.cellObj.setClass();
};
//-----------------------------------------------------------------------------
CalCell.prototype.onclick = function () 
{
	
	
	
	//reduce indirection:
	var cell = this.cellObj;
	var curDate = new Date();
		
	if( cell.date.getUeDay() <  curDate.getUeDay() ){
		alert("Selected date already past.");
		return;
	}
	
	var owner = cell.owner;
	
	if(!owner.selCurMonthOnly || cell.date.getMonth() == owner.displayMonth && cell.date.getFullYear() == owner.displayYear)
	{
		if(owner.selectMultiple == true)  //if we can select multiple cells simultaneously, add the currently selected cell's date to the selectedDates array
		{
			if(!cell.selected) //if this cell has been selected
			{
				if(owner.selectedDates.arrayIndex(cell.date) == -1) {
					owner.selectedDates.push(cell.date);
					/* Start. Ajax will be fired from this location for Adding dates. */
						//$("#multi_container").css('background', 'yellow');
						var scheduled_dated = cell.date.getFullYear() + '-' + (cell.date.getMonth() + 1)+ "-" + cell.date.getDate();
						

						//$("div#multi_container").fadeTo("fast",0);
						//alert(owner.name);
						//if(owner.name=='epoch_multi'){
						$.post('index.php?action=addReminderScheduleEhs',{therpaistId:<!therpaistId>,scheduled_date:scheduled_dated,reminder_set:1}, function(data,status){//alert(data);
							}
						)
						//}else{
						//	$.post('index.php?action=addReminderSchedule',{patient_id:0,scheduled_date:scheduled_dated,reminder_set:2}, function(data,status){//alert(data);
						//	}
						//)		
						//}
					/* End. Ajax will be fired from this location for Adding dates. */
					
				}
			}
			else		
			{
				var tmp = owner.selectedDates; // to reduce indirection
				//if the cell has been deselected, remove it from the owner calendar's selectedDates array
				for(var i=0;i<tmp.length;i++)
				{
					if(tmp[i].getUeDay() == cell.date.getUeDay()) {
						tmp.splice(i,1);
						/* Start. Ajax will be fired from this location for removing dates. */
						
							var scheduled_dated = cell.date.getFullYear() + '-' + (cell.date.getMonth() + 1)+ "-" + cell.date.getDate();
							$.post('index.php?action=removeReminderScheduleEhs',{patient_id:0,scheduled_date:scheduled_dated,reminder_set:1,clinicId:<!clinicId>}, function(data,status){//alert(data)
								}
							)
					
							
						/* End. Ajax will be fired from this location for removing dates. */
					}
				}
			}
		}
		else //if we can only select one cell at a time
		{
			owner.selectedDates = new Array(cell.date);
			if(owner.tgt) //if there is a target element to place the value in, do so
			{
				owner.tgt.value = owner.selectedDates[0].dateFormat();
				if(owner.mode == 'popup') {
					owner.hide();
				}
			}
		}
		
		owner.reDraw(); //redraw the calendar cell styles to reflect the changes
	}
};
//-----------------------------------------------------------------------------
CalCell.prototype.setClass = function ()  //private: sets the CSS class of the cell based on the specified criteria
{
	if(this.selected) {
		this.cellClass = 'cell_selected';
	}
	else if(this.owner.displayMonth != this.date.getMonth() ) {
		this.cellClass = 'notmnth';	
		
	}
	else if(this.date.getDay() > 0 && this.date.getDay() < 6) {
		this.cellClass = 'wkday';
	}
	else {
		this.cellClass = 'wkend';
	}
	
	if(this.date.getFullYear() == this.owner.curDate.getFullYear() && this.date.getMonth() == this.owner.curDate.getMonth() && this.date.getDate() == this.owner.curDate.getDate()) {
		this.cellClass = this.cellClass + ' curdate';
	}

	this.tableCell.setAttribute('class',this.cellClass);
	this.tableCell.setAttribute('className',this.cellClass); //<iehack>
};
/*****************************************************************************/
Date.prototype.getDayOfYear = function () //returns the day of the year for this date
{
	return parseInt((this.getTime() - new Date(this.getFullYear(),0,1).getTime())/86400000 + 1);
};
//-----------------------------------------------------------------------------
Date.prototype.getWeek = function () //returns the day of the year for this date
{
	return parseInt((this.getTime() - new Date(this.getFullYear(),0,1).getTime())/604800000 + 1);
};

//-----------------------------------------------------------------------------
Date.prototype.getUeDay = function () //returns the number of DAYS since the UNIX Epoch - good for comparing the date portion
{
	return parseInt(Math.floor((this.getTime() - this.getTimezoneOffset() * 60000)/86400000)); //must take into account the local timezone
};
//-----------------------------------------------------------------------------
Date.prototype.dateFormat = function(format)
{
	if(!format) { // the default date format to use - can be customized to the current locale
		format = 'm/d/Y';
	}
	LZ = function(x) {return(x < 0 || x > 9 ? '' : '0') + x};
	var MONTH_NAMES = new Array('January','February','March','April','May','June','July','August','September','October','November','December','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
	var DAY_NAMES = new Array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sun','Mon','Tue','Wed','Thu','Fri','Sat');
	format = format + "";
	var result="";
	var i_format=0;
	var c="";
	var token="";
	var y=this.getFullYear().toString();
	var M=this.getMonth()+1;
	var d=this.getDate();
	var E=this.getDay();
	var H=this.getHours();
	var m=this.getMinutes();
	var s=this.getSeconds();
	var yyyy,yy,MMM,MM,dd,hh,h,mm,ss,ampm,HH,H,KK,K,kk,k;
	// Convert real this parts into formatted versions
	var value = new Object();
	//if (y.length < 4) {y=''+(y-0+1900);}
	value['Y'] = y.toString();
	value['y'] = y.substring(2);
	value['n'] = M;
	value['m'] = LZ(M);
	value['F'] = MONTH_NAMES[M-1];
	value['M'] = MONTH_NAMES[M+11];
	value['j'] = d;
	value['d'] = LZ(d);
	value['D'] = DAY_NAMES[E+7];
	value['l'] = DAY_NAMES[E];
	value['G'] = H;
	value['H'] = LZ(H);
	if (H==0) {value['g']=12;}
	else if (H>12){value['g']=H-12;}
	else {value['g']=H;}
	value['h']=LZ(value['g']);
	if (H > 11) {value['a']='pm'; value['A'] = 'PM';}
	else { value['a']='am'; value['A'] = 'AM';}
	value['i']=LZ(m);
	value['s']=LZ(s);
	//construct the result string
	while (i_format < format.length) {
		c=format.charAt(i_format);
		token="";
		while ((format.charAt(i_format)==c) && (i_format < format.length)) {
			token += format.charAt(i_format++);
			}
		if (value[token] != null) { result=result + value[token]; }
		else { result=result + token; }
		}
	return result;
};
/*****************************************************************************/
Array.prototype.arrayIndex = function(searchVal,startIndex) //similar to array.indexOf() - created to fix IE deficiencies
{
	startIndex = (startIndex != null ? startIndex : 0); //default startIndex to 0, if not set
	for(var i=startIndex;i<this.length;i++)
	{
		if(searchVal == this[i]) {
			return i;
		}
	}
	return -1;
};
/*****************************************************************************/
</script>
<script type="text/javascript">

function dumpProps(obj, parent) {
   // Go through all the properties of the passed-in object
   for (var i in obj) {
      // if a parent (2nd parameter) was passed in, then use that to
      // build the message. Message includes i (the object's property name)
      // then the object's property value on a new line
      if (parent) { var msg = parent + "." + i + "\n" + obj[i]; } else { var msg = i + "\n" + obj[i]; }
      // Display the message. If the user clicks "OK", then continue. If they
      // click "CANCEL" then quit this level of recursion
      if (!confirm(msg)) { return; }
      // If this property (i) is an object, then recursively process the object
      if (typeof obj[i] == "object") {
         if (parent) { dumpProps(obj[i], parent + "." + i); } else { dumpProps(obj[i], i); }
      }
   }
}

/*You can also place this code in a separate file and link to it like epoch_classes.js*/
	var bas_cal,dp_cal,ms_cal;      
	window.onload = function () {
	ms_cal=new Epoch('epoch_multi','flat',document.getElementById('multi_container'),true);
	addedDates = new Array(<!selected_dates>);
	ms_cal.addDates(addedDates,true);

	
};

</script>

<script type="text/javascript" src="js/paginate.js"></script>
<style type="text/css">
/* Demo style */
p
        {
        width:800px;
        margin:0 auto 1.6em auto;
        }
        
/* Pagination list styles */
ul.fdtablePaginater
        {
       /* display:table;*/
        list-style:none;
        padding:0;
        margin:0 auto;
        text-align:center;
        
        width:auto;
       
        }
ul.fdtablePaginater li
        {
        display:table-cell;
        padding-right:4px;
        color:#ff0000;
        list-style:none;
        
        -moz-user-select:none;
        -khtml-user-select:none;
        }
ul.fdtablePaginater li a.currentPage
        {
        border-color:#a84444 !important;
        color:#000;
        }
ul.fdtablePaginater li a:active
        {
        border-color:#222 !important;
        color:#222;
        }
ul.fdtablePaginater li a,
ul.fdtablePaginater li div
        {
        display:block;
      
        font-size:11px;
        color:#666;
        padding:0;
        margin:0;
        text-decoration:none;
        outline:none;
        font-family: Geneva,Verdana,san-serif;
        
       
        }
ul.fdtablePaginater li div
        {
        cursor:normal;
            color:#000;
       
        }
ul.fdtablePaginater li a span,
ul.fdtablePaginater li div span
        {
        display:block;
        
            color:#0069A0;
            margin-top:5px;
        
       
        }
ul.fdtablePaginater li a
        {
        cursor:pointer;
            color:#000;
            border-right:1px solid #000;
            padding:0 5px 0 2px;
        }
 ul.fdtablePaginater li a.currentPage
        {
        	border-color:#fff !important;
        	border-right:1px solid #000 !important;
}       

ul.fdtablePaginater li a:focus
        {
        color:#333;
        text-decoration:none;
        border-color:#aaa;
        }
.fdtablePaginaterWrap
        {
        text-align:center;
        clear:both;
        text-decoration:none;
        }
ul.fdtablePaginater li .next-page span,
ul.fdtablePaginater li .previous-page span,
ul.fdtablePaginater li .first-page span,
ul.fdtablePaginater li .last-page span
        {
        font-weight:normal !important;
            
        }
    ul.fdtablePaginater li div.previous-page span{color:#000 !important;} 
        ul.fdtablePaginater li div.next-page span{color:#000 !important;}     
        
/* Keep the table columns an equal size during pagination */
td.sized1
        {
        width:16em;
        text-align:left;
        }
td.sized2
        {
        width:10em;
        text-align:left;
        }
td.sized3
        {
        width:7em;
        text-align:left;
        }
tfoot td
        {
        text-align:right;
       /* font-weight:bold;*/
        text-transform:uppercase;
        letter-spacing:1px;
        }
#visibleTotal
        {
        text-align:center;
        letter-spacing:auto;
        }
* html ul.fdtablePaginater li div span,
* html ul.fdtablePaginater li div span
        {
        background:#eee;
        }
tr.invisibleRow
        {
        display:none;
        visibility:hidden;
        }
p.paginationText
        {
        font-style:oblique;
        }
        #paginate1-fdtablePaginaterWrapTop{ display:none;}
		#paginate-fdtablePaginaterWrapTop{display:none;}
        
   ul.fdtablePaginater li a.currentPage span{color:#000 !important;}
    
   
 ul.fdtablePaginater li a.next-page {border-right:none; font-weight:normal !important;}
 .patient_reminderehs{
  width: 260px;
  min-height:215px;
  float: left;
  background-color: #ebebeb; 
  margin-left: 10px;
  margin-top:15px;
}
        
</style>
<!--[if IE]>
<style type="text/css">
ul.fdtablePaginater {display:inline-block;}
ul.fdtablePaginater {display:inline;}
ul.fdtablePaginater li {float:left;}
ul.fdtablePaginater {text-align:center;}
.patient_reminderehs{
  width: 260px;
  min-height:240px;
  float: left;
  background-color: #ebebeb; 
  margin-left: 10px;
  margin-top:15px;
}
/*table { border-bottom:1px solid #C1DAD7 }*/
</style>
<![endif]-->

<table border="0" cellpadding="2" cellspacing="1" width="100%" class="form_container">
<tr><td colspan="2"> <div id = "messageDiv" style="display:block;"><span style="color:#54A113;font-weight:bold;padding:0px;margin:0px;"><!mass_message_message><!removePlanAricleMsg></div><div id="top7" style="color:#54A113;font-weight:bold;padding:0px;margin:0px;display:none;">Your goal has been queued for processing. This process could take a from a few minutes to a few hours.<tr><td>&nbsp;</td></tr></div></span></td></tr>

	<tr>
		<td colspan="2">
		<h3>
		<div
			style="width: 50%; float: left; margin-bottom: 0px; padding-top: 5px; padding-bottom: 0px; text-transform: uppercase;">Messages</div>
		<div
			style="width: 50%; float: right; padding-bottom: 0px; padding-top: 5px; text-align: right;"><a
			href="index.php?action=compose_message_ehs&from_record=1">Compose
		New Message</a></div>
		</h3>
		</td>
	</tr>
	<tr>

		<td colspan="2">
		<table border="0" cellpadding="2" cellspacing="1" width="100%"
			class="list">
			<thead>
				<!recent_message_head>
			</thead>
			<tr>
				<!recent_message_record>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="padding-bottom: 10px;">
				<div style="padding: 0px; margin: 0px;"><a
					href="index.php?action=message_listing_ehs&sort=sent_date&order=desc&sub=Search"><strong>View
				All Messages</strong></a></div>
				</td>
			</tr>


			<tr>
				<td colspan="2" style="padding-bottom: 5px; border-bottom:0px;"><span
					style="font-size: 110%; color: #ed1f24; font-weight: bold; text-transform: uppercase;"><!labelAssignedPlans></span>
				<table border="0" cellpadding="2" cellspacing="1" width="100%" 	 id="paginate" class="rowstyle-alt paginate-5 max-pages-5 list" style="margin-top: 5px;">

					<thead>
						<tr>
							<th width="35%" class="nosort" style="white-space: nowrap;">Treatment
							Plan Name</th>
							<th width="30%" class="nosort" style="white-space: nowrap;">Status</th>
							<th width="8%" class="nosort"
								style="white-space: nowrap; text-align: right">&nbsp;</th>
						</tr>
					</thead>
					<!viewPatientPlanRecord>
					</td>

					</tr>
					<tr>
		<td colspan="2">
		<h3>
		<div
			style="width: 50%; float: left; margin-bottom: 0px; padding-top: 5px; padding-bottom: 0px; text-transform: uppercase;">Assigned articles</div>
		<div
			style="width: 50%; float: right; padding-bottom: 0px; padding-top: 5px; text-align: right;"><a
			href="index.php?action=assign_articles_ehs">Assign New Article</a></div>
			<div style="clear:both;"></div>

		</h3>
		</td>
	</tr>

<tr>
	<td colspan="2">			<table border="0" cellpadding="2" cellspacing="1" width="100%"
					 style="margin-top: 5px;"  id="paginate1" class="rowstyle-alt paginate-5 max-pages-5 list">

					<thead>
						<tr>
							<th width="35%" class="nosort" style="white-space: nowrap;">Article name</th>
							<th width="30%" class="nosort" style="white-space: nowrap;">Headline</th>
							<th width="8%" class="nosort"
								style="white-space: nowrap; text-align: right">&nbsp;</th>
						</tr>
					</thead>
					<!viewPatientArticleRecord>
					</table>
					</td>

					</tr>

					<tr>
						
						<tr>
							<tr>
								<td colspan="2">
								<h3><div style="clear: both"></div>
								<div
									style="width: 50%; float: left; margin-bottom: 9px; padding-top: 5px; padding-bottom: 0px; text-transform: uppercase;">Goal
								and Reminders</div>

								<div
									style="width: 50%; float: right; padding-bottom: 0px; padding-top: 5px; text-align: right; color: #0069a0;">
								<a href="javascript:void(0);"
									onclick="GB_showCenter('Change Reminders', '/index.php?action=editReminderEhs&patient_id=<!therpaistId>&ehsunsub=<!ehsunsub>', 550, 800 );">Change
								Reminders</a></div>
								</h3>
								</td>
							</tr>
							<tr>
								<td colspan="2">
								<table border="0" cellpadding="2" cellspacing="1" width="100%">
									<thead>

										<tr>
											<th width="100%" colspan="2">&nbsp;</th>
										</tr>
									</thead>
								
								</table>
								</td>
							</tr>
							<!--<tr>
								<td style="float: left; padding-bottom: 0px; padding-top: 0px;">&nbsp;

								</td>
								<td width="70%"
									style="font-weight: bold; text-align: right; padding-right: 30px; padding-bottom: 0px; padding-top: 0px; color: #0069a0;">
								Notification Schedule</td>
							</tr>-->
							<tr>
								<td colspan="2" height="210px;" style="padding-bottom: 10px;">
								<div style="float: left; width: 240px;">
								<!--<div><span
									style="font-size: 16px; color: #6cb907; font-weight: bold;"
									id="goal_percentage"><!goal_completed>%</span> Completed</div>-->
								<div
									style="padding-top: 10px; padding-bottom: 5px; padding-right: 10px;">
								<div><a href="javascript:add_goal();">Add a Goal</a></div>
								</div>
								<div id="top6" 
									style="font-weight: normal; position: relative; margin-left: -15px;">
								<!member_goal></div>
								</div><div id="set1" style="display: block; width: 67%; float: left;padding-bottom: 10px;">
								<p style="width:30%;float: right; color: #0069A0;font-weight: bold;margin:10px 15px 10px 0px"">Notification Schedule</p>
								<div id="patient_reminder" class="patient_reminderehs"><!patient_reminder></div>
									
								<div style="width: 225px; float: right;">
								<div id="multi_container" align="right"></div>
								</div>
								</div>
							</td>

							</tr>
							
				
				</table>
				</div>
				</div>

				<div id="footer"></div>
				</div>
<!--
<script src="js/jquery.js"></script>  
-->
<script language="javascript" type="text/javascript">
      $(document).ready(function() {
        switchMenu('eMaintenance',document.getElementById('switch'));
      });
</script>
