<!--//--><![CDATA[//><!--

/**
* Calendar Widget Version 1.0
* Copyright (c) 2004, Tribador Mediaworks,
*
* Brian Munroe <bmunroe@tribador.net
*
* calendar.js - Calendar Widget JavaScript Library
*
* Permission to use, copy, modify, distribute, and sell this software and its
* documentation for any purpose is hereby granted without fee, provided that
* the above copyright notice appear in all copies and that both that
* copyright notice and this permission notice appear in supporting
* documentation.  No representations are made about the suitability of this
* software for any purpose.  It is provided "as is" without express or
* implied warranty.
*/



function _isLeapYear(year) {
    return (((year % 4 == 0) && (year % 100 != 0)) || (year % 400 == 0)) ? 1 : 0;
}

function setCalendar(idtag,yyyy,mm,dd) {
	y = document.getElementById(idtag);
	//y.value = mm + "/" + dd + "/" + yyyy;
	y.value = yyyy + '-' + mm + '-' + dd;

if ("fireEvent" in y)
    y.fireEvent("onchange");
else
{
    var evt = document.createEvent("HTMLEvents");
    evt.initEvent("change", false, true);
    y.dispatchEvent(evt);
}
    y = document.getElementById(idtag + "__cal");
	y.style.display = "none";
//	dynamicTextBoxRegion( idtag );
//	staticTextBoxRegion( idtag );
}

function closeCal(idtag) {
    t = document.getElementById(idtag + "__cal");
    t.style.display = "none";
}

function closeCalNoDate(idtag) {
    y = document.getElementById(idtag);
    y.value = "";
    t = document.getElementById(idtag + "__cal");
    t.style.display = "none";
}

function closeCalSetToday(idtag) {
    var doDate = new Date();
    var mm = doDate.getMonth()+1;
    var dd = doDate.getDate();
    var yyyy = doDate.getYear();

    if (yyyy < 1000) {
        yyyy = yyyy + 1900;
    }

    setCalendar(idtag,yyyy,mm,dd);
}

function redrawCalendar(idtag) {

    var x = document.getElementById(idtag + "SelectMonth");
    for (i = 0; i < x.options.length;i++){
        if (x.options[i].selected) {
            var mm = x.options[i].value;
        }
    }

    var y = document.getElementById(idtag + "SelectYear");
    for (i = 0; i < y.options.length; i++) {
        if (y.options[i].selected) {
            var yyyy = y.options[i].value;
        }
    }

    // Who f-ing knows why you need this?
    // If you don't cast it to an int,
    // the browser goes into some kind of
    // infinite loop, atleast in IE6.0/Win32
    //
    mm = mm*1;
    yyyy = yyyy*1;

    drawCalendar(idtag,yyyy,mm);
}

function _buildCalendarControls() {

    var months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
    var nw = new Date();

    (arguments[0] ? idtag = arguments[0] : idtag = "");
    (arguments[1] ? yyyy = arguments[1] : yyyy = nw.getYear());
    (arguments[2] ? mm = arguments[2] : mm = nw.getMonth());
    (arguments[3] ? dd = arguments[3] : dd = nw.getDay());

    // Mozilla hack,  I am sure there is a more elegent way, but I did it
    // on a Friday to get a release out the door...
    //
    if (yyyy < 1000) {
        yyyy = yyyy + 1900;
    }

    var monthArray = '<select class ="DateControls" id="' + idtag + 'SelectMonth" onchange="redrawCalendar(\'' + idtag + '\');">';
    // First build the month selection box
    for (i = 0; i < months.length; i++){
        if (i == mm-1) {
            monthArray = monthArray + '<option value="' + eval(i + 1) + '" selected="selected">' + months[i] + '</option>';
        } else {
            monthArray = monthArray + '<option value="' + eval(i + 1) + '">' + months[i] + '</option>';
        }
    }
    monthArray = monthArray + "</select>";

    var yearArray = '<select class ="DateControls" id="' + idtag + 'SelectYear" onchange="redrawCalendar(\'' + idtag + '\');">';
    for (i=yyyy-4;i<= yyyy+4;i++){
        if (i == yyyy) {
            yearArray = yearArray + '<option value="' + i + '" selected="selected">' + i + '</option>';
        } else {
            yearArray = yearArray + '<option value="' + i + '">' + i + '</option>';
        }
    }
    yearArray = yearArray + "</select>";

		var monthPrev = "<a onclick='monthPrev()'>&lt;</a>";
		var monthNext = "<a onclick='monthNext()'>&gt;</a>";
    return( monthPrev + " " + monthArray + " " + yearArray + " " + monthNext );
}

function clickWidgetIcon(idtag) {
    (arguments[0] ? idtag = arguments[0] : idtag = "");

    t = document.getElementById(idtag + "__cal");
    if (t.style.display == "none") {
        drawCalendar(idtag);
    } else {
        closeCal(idtag);
    }
}

function drawCalendar() {

    (arguments[0] ? idtag = arguments[0] : idtag = "");
    (arguments[1] ? yyyy = arguments[1] : yyyy = void(0));
    (arguments[2] ? mm = arguments[2] : mm = void(0));
    (arguments[3] ? dd = arguments[3] : dd = void(0));

    if (!yyyy && !mm) {
        x = document.getElementById(idtag);
		var wholeValue, doDate, dateparts, yyyy, mm, dd;

        if (x.value != "") {
            wholeValue = x.value;
            dateparts = wholeValue.split("-");
            yyyy = dateparts[0]*1;
            mm = dateparts[1]*1;
            dd = dateparts[2]*1;
        } else {
            doDate = new Date();
            mm = doDate.getMonth()+1;
            dd = doDate.getDate();
            yyyy = doDate.getYear();
        }
    }

    // Mozilla hack,  I am sure there is a more elegent way, but I did it
    // on a Friday to get a release out the door...
    //
    if (yyyy < 1000) {
        yyyy = yyyy + 1900;
    }

    var newDate = new Date(yyyy,mm-1,1);
    var startDay = newDate.getDay();
    var dom = [31,28,31,30,31,30,31,31,30,31,30,31];
    var dateControls = '<tr><td class="DateControlFrame" colspan="7">' + _buildCalendarControls(idtag,yyyy,mm,dd) + '</td></tr>';
    var beginTable = '<table class="CalendarFrame">';
    var calHeader = '<tr><td class="CalHeader">Su</td><td class="CalHeader">Mo</td><td class="CalHeader">Tu</td><td class="CalHeader">We</td><td class="CalHeader">Th</td><td class="CalHeader">Fr</td><td class="CalHeader">Sa</td></tr>';
    var closeControls = '<tr><td class="CloseControls" colspan="7"> <a class="close" onclick="closeCal(\'' + idtag + '\');">Close</a> <a class="cancel" onclick="closeCalNoDate(\'' + idtag + '\');">No Date</a> <a class="today" onclick="closeCalSetToday(\'' + idtag + '\');">Today</a></td></tr></table>';
    var curHTML = "";
    var curDay = 1;
    var endDay = 0;
    var rowElement = 0;
    var startFlag = 1;
    var endFlag = 0;
    var elementClick = "";
    var celldata = "";

    ((_isLeapYear(yyyy) && mm == 2) ? endDay = 29 : endDay = dom[mm-1]);

    // calculate the lead gap
    if (startDay != 0) {
        curHTML = "<tr>";
        for (i = 0; i < startDay; i++) {
            curHTML = curHTML + '<td class="EmptyCell"></td>';
            rowElement++;
        }
    }

    for (i=1;i<=endDay;i++){
        (dd == i ? celldata = "CurrentCellElement" : celldata = "CellElement");

        if (rowElement == 0) {
            curHTML = curHTML + '<tr>' + '<td class="' + celldata + '" onclick="setCalendar(\'' + idtag + '\','+ yyyy +',' + mm + ',' + i +');">' + i + '</td>';
            rowElement++;
            continue;
        }

        if (rowElement > 0 && rowElement < 6) {
            curHTML = curHTML + '<td class="' + celldata + '" onclick="setCalendar(\'' + idtag + '\','+ yyyy +',' + mm + ',' + i +');">' + i + '</td>';
            rowElement++;
            continue;
        }

        if (rowElement == 6) {
            curHTML = curHTML + '<td class="' + celldata + '" onclick="setCalendar(\'' + idtag + '\','+ yyyy +',' + mm + ',' + i +');">' + i + '</td></tr>';
            rowElement = 0;
            continue;
        }
    }

    // calculate the end gap
    if (rowElement != 0) {
        for (i = rowElement; i <= 6; i++){
            curHTML = curHTML + '<td class="EmptyCell"></td>';
        }
	    curHTML = curHTML + "</tr>";
    }

    t = document.getElementById(idtag + "__cal");
    dateField = document.getElementById(idtag);
	t.innerHTML = beginTable + dateControls + calHeader + curHTML + closeControls;

    // need to write some better browser detection/positioning code here
    // Also, there is a perceived stability issue where the calendar goes offscreen
    // when the widget is right justified..Need some edge detection
    //

   	var kitName = "applewebkit/";
	var tempStr = navigator.userAgent.toLowerCase();
	var pos = tempStr.indexOf(kitName);
	var isAppleWebkit = (pos != -1);

    if (isAppleWebkit || document.all) {
        ieOffset = 10;
    } else {
        ieOffset = 0;
    }

    t.style.left = ieOffset + dateField.offsetLeft + "px";
    t.style.display = "";


}

function createCalendarWidget() {
    (arguments[0] ? idtag = arguments[0] : idtag = "");
    (arguments[1] ? isEditable = arguments[1] : isEditable = "EDIT");
    (arguments[2] ? hasIcon = arguments[2] : hasIcon = "NO_ICON");
    (arguments[3] ? iconPath = arguments[3] : hasIcon = "./OU812.gif");
		(arguments[4] ? date = arguments[4] : date = "" );

    (isEditable == "NO_EDIT" ? readOnly = 'readonly="readonly"' : readOnly = '');

    if (hasIcon == "ICON"&&0) {
        clicking = '';
        icon = ' <img src="' + iconPath + '" class="CalIcon" id="' + idtag + 'Icon" onmousedown="clickWidgetIcon(\'' + idtag + '\');" />';
    } else {
        clicking = 'onclick="drawCalendar(\'' + idtag + '\')"';
        icon = '';
    }
		
    document.write('<input name="' + idtag + '" id="' + idtag + '" type="text" class="DateField" size="10" value="' + date + '" ' + readOnly + ' ' + clicking + ' />' +  icon + '<div id="' + idtag + 'Div" style="background: #ffffff; position: absolute; display:none;"></div>');
}

function monthPrev()
{
	var m = document.getElementById( idtag + 'SelectMonth' ).value - 1;
	if( m >= 1 )
		document.getElementById( idtag + 'SelectMonth' ).value = m;
	else
	{
		document.getElementById( idtag + 'SelectMonth' ).value = 12;
		document.getElementById( idtag + 'SelectYear' ).value--;
	}
		
	redrawCalendar( idtag );
}

function monthNext()
{
	var m = Number( document.getElementById( idtag + 'SelectMonth' ).value ) + 1;
	if( m <= 12 )
		document.getElementById( idtag + 'SelectMonth' ).value = m;
	else
	{
		document.getElementById( idtag + 'SelectMonth' ).value = 1;
		document.getElementById( idtag + 'SelectYear' ).value++;
	}
		
	redrawCalendar( idtag );
}

//--><!]]>