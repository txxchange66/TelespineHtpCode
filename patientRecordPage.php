<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"

    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">



<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <title>Tx Xchange: View Patient</title>

    <link rel="STYLESHEET" type="text/css" href="css/styles.css">

    <!style>

    <script language="JavaScript" type="text/javascript" src="js/common.js"></script>

    

</head>

<body>

    <!-- starting template -->

    <div class="center-align" >

        <link rel="STYLESHEET" type="text/css" href="css/calendar.css">

<!--<script language="JavaScript" type="text/javascript" src="js/prototype.js"></script>-->
<script language='javascript' type='text/javascript' src='js/jquery.js'></script>
<!-- Grey box -->
<script type="text/javascript">  
var GB_ROOT_DIR = "js/greybox/";
</script>
<script type="text/javascript" src="js/greybox/AJS.js"></script>
<script type="text/javascript" src="js/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="js/greybox/gb_scripts.js"></script>
<link href="js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
<!-- End -->
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
        this.leftOffset = 0;                     // the horizontal distance (in pixels) to display the calendar from the Left of its input element
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
    this.calCellContainer = td;    //used as a handle for manipulating the calendar cells as a whole
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
            $.post('index.php?action=removeReminderSchedule',{patient_id:470,scheduled_date:scheduled_dated}, function(data,status){
                    
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
    var vDay            = this.addZero(vDate.getDate()); 
    var vMonth            = this.addZero(vDate.getMonth() + 1); 
    var vYearLong        = this.addZero(vDate.getFullYear()); 
    var vYearShort        = this.addZero(vDate.getFullYear().toString().substring(3,4)); 
    var vYear            = (vFormat.indexOf('yyyy') > -1 ? vYearLong : vYearShort);
    var vHour            = this.addZero(vDate.getHours()); 
    var vMinute            = this.addZero(vDate.getMinutes()); 
    var vSecond            = this.addZero(vDate.getSeconds()); 
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
            if(owner.cols[this.headObj.dayOfWeek])         //if selecting, add the cell's date to the selectedDates array
            {
                if(owner.selectedDates.arrayIndex(cells[i].date) == -1) { //if the date isn't already in the array
                    
                    sdates.push(cells[i].date);
                }
            }
            else                                        //otherwise, remove it
            {
                for(var j=0;j<sdates.length;j++) 
                {
                    if(cells[i].dayOfWeek == sdates[j].getDay())
                    {
                        sdates.splice(j,1);    //remove dates that are within the displaying month/year that have the same day of week as the day cell
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
            else                                        //otherwise, remove it
            {
                for(j=0;j<sdates.length;j++)
                {
                    if(sdates[j].getTime() == cells[i].date.getTime())  //this.weekObj.tableRow && sdates[j].getMonth() == owner.displayMonth && sdates[j].getFullYear() == owner.displayYear)
                    {
                        sdates.splice(j,1);    //remove dates that are within the displaying month/year that have the same day of week as the day cell
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
    this.owner = owner;        //used primarily for event handling
    this.tableCell = tableCell;             //the link to this cell object's table cell in the DOM
    this.cellClass;            //the CSS class of the cell
    this.selected = false;    //whether the cell is selected (and is therefore stored in the owner's selectedDates array)
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
                        $.post('index.php?action=addReminderSchedule',{patient_id:470,scheduled_date:scheduled_dated}, function(data,status){
                                //$("div#multi_container").fadeIn('300');
                                //$("div#multi_container").fadeTo("fast",1);
                            }
                        )
                        
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
                            //$("div#multi_container").fadeOut("slow");
                            //$("div#multi_container").fadeTo("slow",0);
                            $.post('index.php?action=removeReminderSchedule',{patient_id:470,scheduled_date:scheduled_dated}, function(data,status){
                            //$("div#multi_container").fadeIn('300');
                            //$("div#multi_container").fadeTo("slow",1);
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
    ms_cal  = new Epoch('epoch_multi','flat',document.getElementById('multi_container'),true);
    addedDates = new Array();
    ms_cal.addDates(addedDates,true);
    //dumpProps(ms_cal);
    
};
</script>
<div id="container">
    <div id="header">
            <div id='line'>
                                <div id='txlogo' style='float:left; padding-left:55px;' ></div>
                             <div id='subheader' style='float:right; padding-right:55px;' >
                                        <div id='cliniclogo'  ></div>
                             </div>
                             <div style='clear:both;'></div>

                             </div>
                                     
    </div>
    
    <div id="sidebar"><h2>Welcome <br>Jonathan Epstein</h2><a href="mailto:support@txxchange.com"><img src="images/feedbackIcon.gif" /></a>
        <ul class="sideNav">
            <li class="loginBtn"><a href="index.php?action=logout">Logout</a></li>

            <li class="seperator"><img src="images/spacer.gif" width="148" height="1"></li>

            
                                    <li ><a href="index.php?action=therapist"  class="selected">Home</a></li><li>
                                                <a href="index.php?action=message_listing&sort=sent_date&order=desc" >Messages(2)</a>
                                    </li>
                                         <li style="background:url(/images/list_expand.gif) no-repeat 0px;  6px;padding-left:15px;" onclick="switchMenu('eMaintenance',this);" >
                                        <a href="index.php?action=myPatients" onclick="assign();" >My Patients</a>
                                        <div id="eMaintenance" style="padding-left:10px;display:none;" ><a href="index.php?action=eMaintenance" onclick="assign();" >e-Rehab Patients</a></div>
                                    </li>

                                    <li >
                                        <a href="index.php?action=therapistPlan" >My Template Plans</a>
                                    </li>
                                    <li>
                                        <a href="index.php?action=therapistLibrary" >Article Manager</a>
                                    </li>                                            
                                            <li ><a href="index.php?action=treatmentManager" >Tx Manager</a></li><li ><a  href='index.php?action=accountAdminClinicList' >Account Admin</a></li>

            <li ><a href="index.php?action=viewMyAccount" onMouseOver="help_text(this, 'Manage your account and contact inforamtion')">My Account</a></li>
            
            <li class="seperator"><img src="images/spacer.gif" width="148" height="1"></li>
            <!-- <li class="homeBtn"><a href="../" target="_blank">Site Home Page</a></li> -->
        </ul>
        
        <ul class="sideNav">
            <!-- <li class="helpBtn"><a href="help.php">Help</a></li> -->
            <li ><a  href='index.php?action=switch_back' >Switch to System Admin</a></li><li><h2 style="text-align:left;"><a href="index.php?action=help_therapist" target="_blank" style="color:#0a558f;"  ><strong>Help</strong></a></h2></li>

            <!--
            <li class="rolloverhelp">
            <div id="rolloverhelp" style="font-size: 85%; text-align:left;padding: 5px 1em;">Rollover buttons or elements on the page to get help and tips.</div>
            <li>
            -->
        </ul>

        
    </div>
    <div id="mainContent">
<table style="vertical-align:middle;width:700px;">
<tr>
<td style="width:400px;"><div id="breadcrumbNav"><a href="index.php?action=myPatients" ><span style="font-size:10px;">PATIENT</span></a> / <span ><SPAN CLASS="CURRENT_ACTION">BRIAN&nbsp;DAVID</SPAN></span> / <span class="highlight"> VIEW PATIENT</span></div></td>

<td style="width:300px;">
    <table border="0" cellpadding="5" cellspacing="0" style="float:right;">
        <tr>
            <td class="iconLabel"></td>
            <td class="iconLabel">
                
                        <a href="index.php?action=createNewPlan&patient_id=470&type=finish"><img src="images/createNewTreatmentPlan.gif" width="127" height="81" alt="Create New Treatment Plan"></a></td><td>
                        <a href="index.php?action=therapistPlan&patient_id=470&path=my_patient"><img src="images/assignTemplatePlan.gif" width="127" height="81" alt="Assign Plan"></a>
                        
            </td>
        </tr>

    </table>
</td>
</tr>
</table>
<!--    
<script language="JavaScript" src="js/validateform.js"></script>
<script language="JavaScript">


window.formRules = new Array(
    new Rule("name_title", "title", false, "string|0,5"),
    new Rule("name_first", "first name", true, "string|0,50"),
    new Rule("name_last", "last name", true, "string|0,50"),
    new Rule("name_suffix", "name suffix", false, "string|0,5"),
    new Rule("address", "address", false, "string|0,50"),
    new Rule("address2", "address line 2", false, "string|0,50"),
    new Rule("city", "city", false, "string|0,50"),
    new Rule("state", "state", false, "string|0,2"),
    new Rule("zip", "zip code", false, "zipcode"),
    new Rule("phone1", "1st phone number", false, "usphone"),
    new Rule("phone2", "2nd phone number", false, "usphone"),
    new Rule("status", "status", false, "integer"),
    //new Rule("diagnosis", "current diagnosis", true, "string|1,50"),
    new Rule("email_address", "email address", true, "email"),
    new Rule("reminder", "reminder", false, "string|0,250"),
    new Rule("choose_therapists", "reminder", false, "string|0,250"),
    new Rule("new_password", "new password", false, "string|4,20"),
    new Rule("new_password2", "confirm password", false, "string|4,20"));

</script>
-->
<script type="text/javascript">
<!--
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
                var plan_detail_win = window.open('index.php?action=planViewer&id='+p2p_id, 'PlanPreview', 'width=1024,height=768,resizable=1,scrollbars=auto');
            }
            plan_detail_win.focus();
            c = false;
            break;
        default:
            c = true;
            break;
    }
    s.options.selectedIndex = 0;
    if (c) window.location.href = 'index.php?action=therapistViewPatient&' + 'act=' + a + '&plan_id=' + p2p_id + '&id=' + 470;
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
function maybeShowBillingAddress(cb)
{
    b = document.getElementById('billing_address');
    (cb.checked) ? b.style.display = 'block' : b.style.display = 'none';
}
function login_detail(patient_id){
    if(patient_id != null && patient_id != "" ){
        
        $.post('index.php?action=mail_login_detail_patient',{patient_id:patient_id}, function(data,status){
                $content = document.getElementById("login_detail").innerHTML;    
                document.getElementById("login_detail").innerHTML = "<img src='images/ajax-loader.gif' />";
                if( status == "success" ){
                    if(/success/.test(data)){
                        //alert("Login info email successfully sent to Patient.");    
                        //showme("Login info email successfully sent to Patient.");
                        GB_showCenter('Resend Patient\'s Login Info', '/template/alert.php?action=success', 100, 350 );
                    }
                    else if( /failed/.test(data) ){
                        //alert("E-mail delivery failed.");
                        //showme("E-mail delivery failed.");
                        GB_showCenter('Resend Patient\'s Login Info', '/template/alert.php?action=fail', 100, 350 );
                    }    
                    else{
                        alert(data);
                    }
                }
                else{
                    alert("Ajax connection failed.");
                }    
                
                document.getElementById("login_detail").innerHTML = $content;   
            }
        )        
        
    }
    else{
        alert("Patient Id not Found.");
    }
    
}
//-->
</script>
<table border="0" cellpadding="2" cellspacing="1" width="100%" class="form_container">
<tr>
    <td colspan="2" style="padding-bottom:15px;">
        
            <span style="font-size: 110%;color: #ed1f24;font-weight: bold;" >Assigned Plans</span>
            <table border="0" cellpadding="2" cellspacing="1" width="100%"  class="list" style="margin-top:5px;" >

            <thead>
            <tr>
            <th width="35%" class="nosort" style="white-space:nowrap;">Treatment Plan Name</th>
            <th width="20%" class="nosort" style="white-space:nowrap;">Status</th>
            <th width="8%" class="nosort" style="white-space:nowrap;text-align:right">&nbsp;</th>
            </tr>
            </thead>
            
        <tr class="<!sytle>"><td style="white-space:nowrap;"><nobr>Gary Gray Shoulder - 1</nobr></td>

<td style="white-space:nowrap;"><nobr>Current</nobr></td>
<td style="white-space:nowrap;text-align:right">
<select  class="action_select" style="width:100px;" onChange="handlePlanAction(this, 7677)">
<option value="">Actions...</option>
<option value='viewPlan'>View Plan</option><option value='current'>Current</option><option value='archive'>Archive Plan</option><option value='editPlan'>Edit Plan</option>
</select>
</td>
</tr><tr class="<!sytle>"><td style="white-space:nowrap;"><nobr>Acute Low Back Pain</nobr></td>
<td style="white-space:nowrap;"><nobr>Archived</nobr></td>

<td style="white-space:nowrap;text-align:right">
<select  class="action_select" style="width:100px;" onChange="handlePlanAction(this, 7773)">
<option value="">Actions...</option>
<option value='viewPlan'>View Plan</option><option value='current'>Activate Plan</option><option value='deletePlan'>Delete Plan</option><option value='editPlan'>Edit Plan</option>
</select>
</td>
</tr><tr class="<!sytle>"><td style="white-space:nowrap;"><nobr>Advanced Yoga for Back Pain</nobr></td>
<td style="white-space:nowrap;"><nobr>Current</nobr></td>
<td style="white-space:nowrap;text-align:right">
<select  class="action_select" style="width:100px;" onChange="handlePlanAction(this, 7866)">

<option value="">Actions...</option>
<option value='viewPlan'>View Plan</option><option value='current'>Current</option><option value='archive'>Archive Plan</option><option value='editPlan'>Edit Plan</option>
</select>
</td>
</tr></table>
    </td>
</tr>
<tr>
    <td colspan="2">    
        <h3>

            <div style="width:50%;float:left;margin-bottom:0px;padding-top:5px;padding-bottom:0px;">Recent Messages</div>
            <div style="width:50%;float:right;padding-bottom:0px;padding-top:5px;text-align:right;"><a href="index.php?action=compose_message&patient_name_id=470&patient_name=Brian David&from_record=1">Compose New Message</a></div>
        </h3>
    </td>
</tr>
<tr>
    <td colspan="2">
        <table border="0" cellpadding="2" cellspacing="1" width="100%"  class="list" >
            <thead>

                <tr>
    <th width="35%" >Subject</th>
    <th width="20%"  >Last Post(11 replies)</th>
</tr>

            </thead>
        <tr  onMouseOver="help_text(this, 'Displays the recent message list for this patient. Click on the subject to view the full message');" >


<td width="50%"0%" >

    <a href='index.php?action=set_unread_message&message_id=2523'><b>Got a little problem</b></a>
</td>
<td width="30%"0%">
    <a href='index.php?action=set_unread_message&message_id=2523'><b>04/23/2009 01:38 PM</b></a>&nbsp;( 5 replies)
</td>
</tr><tr  onMouseOver="help_text(this, 'Displays the recent message list for this patient. Click on the subject to view the full message');" >


<td width="50%"0%" >
    <a href='index.php?action=set_unread_message&message_id=2433'>Got a question</a>
</td>

<td width="30%"0%">
    <a href='index.php?action=set_unread_message&message_id=2433'>03/26/2009 12:28 AM</a>&nbsp;( 1 replies)
</td>
</tr><tr  onMouseOver="help_text(this, 'Displays the recent message list for this patient. Click on the subject to view the full message');" >


<td width="50%"0%" >
    <a href='index.php?action=set_unread_message&message_id=3189'>Run this past weekend</a>
</td>
<td width="30%"0%">
    <a href='index.php?action=set_unread_message&message_id=3189'>03/24/2009 01:01 AM</a>&nbsp;( 1 replies)

</td>
</tr><tr  onMouseOver="help_text(this, 'Displays the recent message list for this patient. Click on the subject to view the full message');" >


<td width="50%"0%" >
    <a href='index.php?action=set_unread_message&message_id=3080'>How&#039;s it going?</a>
</td>
<td width="30%"0%">
    <a href='index.php?action=set_unread_message&message_id=3080'>03/21/2009 01:03 AM</a>&nbsp;( 2 replies)
</td>
</tr><tr  onMouseOver="help_text(this, 'Displays the recent message list for this patient. Click on the subject to view the full message');" >

<td width="50%"0%" >
    <a href='index.php?action=set_unread_message&message_id=1895'>New Plan</a>
</td>
<td width="30%"0%">
    <a href='index.php?action=set_unread_message&message_id=1895'>11/25/2008 12:43 AM</a>&nbsp;( 2 replies)
</td>
</tr></table>    
    </td>
</tr>
<tr>
    <td colspan="2" style="padding-bottom:10px;" >

        <div style="padding:0px;margin:0px;"><a href="index.php?action=message_listing&sort=sent_date&order=desc&patient_name_id=470&patient_name=Brian David&sub=Search"><strong>View All Messages</strong></a></div>
    </td>
</tr>
</tr>
<tr>
    <td colspan="2" width="100%" >
        <h3>
        <div style="width:50%;float:left;margin-bottom:0px;padding-top:5px;padding-bottom:0px;">Reminders</div>
        <div style="width:50%;float:right;padding-bottom:0px;padding-top:5px;text-align:right;color: #0069a0;">                     
            <a href="javascript:void(0);" onclick="GB_showCenter('Edit Reminder', '/index.php?action=editReminder&patient_id=470', 550, 800 );"  >Edit Reminder</a> 
            
        </div>

        </h3>
    </td>
</tr>
<tr>
    <td colspan="2" width="100%" >
        <table border="0" cellpadding="2" cellspacing="1" width="100%"  class="list"  >
            <thead>
                <tr>
                    <th width="100%" colspan="2" >&nbsp;</th>
                </tr>

            </thead>
        </table>
    </td>    
</tr>
<tr>
    <td colspan="2" width="100%">
        <div style="width:100%;font-weight:bold;padding-left:525px;padding-bottom:0px;padding-top:0px;color: #0069a0;">Notification Schedule</div>
    </td>
</tr>
<tr>

    <td colspan="2" width="100%" height="210px;" style="padding-bottom:10px;"  >
        <div id="patient_reminder" style="width:440px;height:100%;float:left;background-color: #ebebeb;"><ol><li>Drink lots of water</li><li>Do your exercises today</li></ol></div>
        <div style="width:280px;float:right;"><div id="multi_container" align="center"></div></div>
    </td>                     
</tr>
<tr>
    <td colspan="2" width="100%" >
        <h3>
        <div style="width:450px;float:left;padding-top:8px;padding-bottom:0px;">SOAP Notes</div>

        <div style="width:280px;float:right;padding-bottom:0px;padding-top:8px;text-align:right;color: #0069a0;">
            <a href='index.php?action=therapistEditPatient&id=470' >New SOAP Notes</a>
        </div>
        </h3>
    </td>
</tr>
<tr>
    <td colspan="2" width="100%" >
        <table border="0" cellpadding="2" cellspacing="1" width="100%"  class="list" >

            <thead>
                <tr>
                    <th width="100%" colspan="2" >&nbsp;</th>
                </tr>
            </thead>
        </table>
    </td>    
</tr>
<tr>
    <td colspan="2" width="100%" >
        <table border="0" cellpadding="2" cellspacing="1" width="100%"  class="list" >
                <tr >
                    <td >
                        <a href=''><b>04/23/2009 01:38 PM</b></a>
                    </td>
                </tr>
                <tr >
                    <td >
                        <a href=''><b>04/23/2009 01:38 PM</b></a>
                    </td>
                </tr>
                <tr >
                    <td >
                        <a href=''><b>04/23/2009 01:38 PM</b></a>
                    </td>
                </tr>
        </table>
    </td>    
</tr>
<tr>
    <td colspan="2" width="100%" >
        <h3>
        <div style="width:450px;float:left;padding-top:8px;padding-bottom:0px;">Outcome Measure</div>

        <div style="width:280px;float:right;padding-bottom:0px;padding-top:8px;text-align:right;color: #0069a0;">
            <a href="">Assign Outcome Measure</a>
        </div>
        </h3>
    </td>
</tr>
<tr>
    <td colspan="2" width="100%" >
        <table border="0" cellpadding="2" cellspacing="1" width="100%"  class="list" >

            <thead>
                <tr>
                    <th width="100%" colspan="2" >&nbsp;</th>
                </tr>
            </thead>
        </table>
    </td>    
</tr>
<tr>
    <td colspan="2" width="100%" >
        <h3>
        <div style="width:280px;float:right;padding-bottom:0px;padding-top:1px;text-align:right;color: #0069a0;">
            <a href="">View Patient Questionnaire</a>
			<div align="left" style="color:#000000; padding-top:12px; padding-bottom:5px;" >Functional Status :
			<div style="font-weight:normal; padding-bottom:3px; padding-top:2px;">0%&nbsp;&nbsp; to 20% &nbsp;&nbsp;- minimal disability </div>
			<div style="font-weight:normal; padding-bottom:3px;"> 20% to 40% &nbsp;&nbsp;- moderate disability</div>
			<div style="font-weight:normal; padding-bottom:3px;"> 40% to 60% &nbsp;&nbsp;- severe disability</div>
			<div style="font-weight:normal; padding-bottom:3px;"> 60% to 80% &nbsp;&nbsp;- crippled</div>
			<div style="font-weight:normal; padding-bottom:3px;"> 80% to 100% - bed bound </div>
			</div>
        </div>
        </h3>
       
        <img src="images/bargraph.png" />
    </td>    
</tr>
<tr>
    <td colspan="2" width="100%" >
        <h3>
        <div style="width:450px;float:left;padding-top:8px;padding-bottom:0px;">General Patient Information</div>

        <div style="width:280px;float:right;padding-bottom:0px;padding-top:8px;text-align:right;color: #0069a0;">
            <a href='index.php?action=therapistEditPatient&id=470' >Edit Information</a>
        </div>
        </h3>
    </td>
</tr>
<tr>
    <td colspan="2" width="100%" >
        <table border="0" cellpadding="2" cellspacing="1" width="100%"  class="list" >

            <thead>
                <tr>
                    <th width="100%" colspan="2" >&nbsp;</th>
                </tr>
            </thead>
        </table>
    </td>    
</tr>
<tr>
    <td colspan="2" width="100%" >

        <div style="width:450px;float:left">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" >
                <tr>
                    <td style='font-weight:bold;color: #0069a0;' >Contact Information</td>
                </tr>
                <tr>
                    <td>Brian&nbsp;David<br>6666 Gunpark Drive.<br>Suite 1111<br>Gunbarrel,&nbsp;CO&nbsp;80301<br>Phone 1:&nbsp;3035300105<br>Phone 2:&nbsp;123456789<br><a href='mailto:jguard682000-txxchange@yahoo.com'>jguard682000-txxchange@yahoo.com</a></td>

                </tr>

            </table>
        </div>
        <div style="width:280px;float:right">
             <table border="0" cellpadding="0" cellspacing="0" width="100%" >
                <tr>
                    <td style='font-weight:bold;color: #0069a0;' >Refrerring Dr:</td>
                    <td >John Jachimiak</td>

                </tr>
                <tr>
                    <td style='font-weight:bold;color: #0069a0;' >Created On:</td>
                    <td >04/19/2008 08:50 AM</td>
                </tr>
                <tr>
                    <td style='font-weight:bold;color: #0069a0;' >Status:</td>

                    <td >Current</td>
                </tr>
                <tr>
                    <td style='font-weight:bold;color: #0069a0;' >Programs:</td>
                    <td >No current programs</td>
                </tr>
                <tr>

                    <td colspan="2" style="padding-top:5px;" ><span id='login_detail'><a href="javascript:void(0);" onclick="login_detail('470');" ><strong>Resend Login Info</strong></a></span></td>
                </tr>
                 <tr>
                    <td >
                    <a href="javascript:void(0);" onclick="GB_showCenter('Login History', '/index.php?action=loginHistory&patient_id=470', 400, 490 );"  ><strong>View Login History</strong></a> 
                    </td>
                </tr>
             </table>

        </div>
    </td>
</tr>
<tr>
    <td colspan="2" width="100%" >
        <div style="width:450px;float:left;padding-top:5px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" >
                <tr>
                    <td  >
                    <span style='font-weight:bold;color: #0069a0;' >Associated Therapists:</span>

                    <span style='padding-left:40px;' >
                    <a href="javascript:void(0);" onclick="GB_showCenter('Associate Therapists', '/index.php?action=associateTherapist&patient_id=470', 550, 800 );"  >[Edit]</a> 
                    </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div id='therapistName' >
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" ><tr><td>Jonathan&nbsp;Epstein</td></tr><tr><td>Sheri&nbsp;Mascorro</td></tr></table>  
                        </div>

                    </td>
                </tr>

            </table>
        </div>
    </td>
</tr>
<tr>
    <td colspan="2">&nbsp;</td>
</tr>
</table>

    </div>
    </div>
    
    <div id="footer">
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"

    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">



<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <title>View Patient</title>

    <link rel="STYLESHEET" type="text/css" href="css/styles.css">

    <!style> 

    <script language="JavaScript" type="text/javascript" src="js/common.js"></script>

    

</head>

<body>

    <!-- starting template -->

    <div class="center-align" >

        <!body>

    </div>

</body>

</html>


    </div>

</div>


    </div>

</body>

</html>

