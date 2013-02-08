<!--//--><![CDATA

	/* add trim() function to string */
	String.prototype.trim = function() {
		return this.replace(/^\s+|\s+$/g,"");
	}

	/**
	 * Initialize namespace
	 */
	var SuggestBox = {};

	/**
	 * array to store the list items
	 */
	SuggestBox.itemData = Array();

	/**
	 * array to store the list items
	 */
	SuggestBox.textValues = Array();

	/**
	 * var to store the http request object
	 */
	SuggestBox.HTTPRequest = Array();


	/**
	 * storeData
	 *
	 * store listbox data
	 *
	 */
	SuggestBox.storeData = function( listName, data ) {
		SuggestBox.itemData[listName] = data.unique();
	}


	/**
	 * onResponse
	 *
	 * get listbox data
	 *
	 */
	SuggestBox.onResponse = function( listName, textField, textInput )
	{
		if (SuggestBox.HTTPRequest[listName])
		{
			// if xmlhttp shows "loaded"
			if (SuggestBox.HTTPRequest[listName].readyState==4)
			{
				// if status "OK"
				if (SuggestBox.HTTPRequest[listName].status==200)
				{
					eval('var options = ' + SuggestBox.HTTPRequest[listName].responseText + ';');
					SuggestBox.storeData( listName, options );

					document.getElementById(textInput).disabled=false;
					document.getElementById(textInput).value=SuggestBox.textValues[listName];
				}
				else
				{
					// Problem retrieving XML data
					// alert( 'Problem retrieving XML data' );
				}
			}
		}
	}


	/**
	 * lookupHandleKeyUp
	 *
	 * update listbox based on textbox string
	 * on arrow keys set focus to listbox
	 * on <enter> update textbox with selected item
	 *
	 */
	SuggestBox.handleKeyUp = function(keyCode,maxNumToShow,autoComplete,textObj,selectObj,listName,autoSelect,delimiter)
	{
		var itemListLength;
		var i, searchPattern, numShown;

		// handle key events
    	if( keyCode == 40 && selectObj.style.display != 'none' ) {
			selectObj.focus();
			selectObj.selectedIndex = 0;
			return;
    	}
    	else if( keyCode == 38 && selectObj.style.display != 'none' ) {
			selectObj.focus();
			selectObj.selectedIndex = selectObj.length - 1;
			return;
    	}
		else if( keyCode == 13 && selectObj.style.display != 'none' ) {
			if( selectObj.style.display=='block' ) {
				SuggestBox.update(textObj,selectObj,delimiter);
			}
			return;
    	}

		if( !SuggestBox.itemData[listName] ) return;

 	   	// Remember the function list length for loop speedup
		itemListLength = SuggestBox.itemData[listName].length;

  	  	// Set the search pattern depending
		if( delimiter.length > 0 )
		{
  	    	// searchPattern = "^"+textObj.value.substr(textObj.value.lastIndexOf(delimiter)+1).trim();
			searchPattern = textObj.value.substr(textObj.value.lastIndexOf(delimiter)+1).toString().trim();

			// if(searchPattern.length < 2) {
			if(searchPattern.length < 1) {
				selectObj.style.display = 'none';
				return;
			}
		}
		else // containing
		{
   	 		searchPattern = textObj.value.trim();

			if(searchPattern.length < 1) {
				selectObj.style.display = 'none';
				return;
			}
		}

    	// Create a regulare expression
		var re = new RegExp(searchPattern,"gi");

		// Clear the options list
		selectObj.length = 0;

 	 	// Loop through the array and re-add matching options
  		numShown = 0;
		for(i = 0; i < itemListLength; i++ )
		{
 			if(SuggestBox.itemData[listName][i].search(re) != -1)
			{
   	     		selectObj[numShown] = new Option(SuggestBox.itemData[listName][i],"");
            	numShown++;
        	}
        	// Stop when the number to show is reached
        	if(numShown == maxNumToShow)
        	{
            	break;
        	}
   		}

	    //allow backspace to work in IE
		// if (typeof textObj.selectionStart == 'undefined' && keyCode == 8) { textObj.value = textObj.value.substr(0,textObj.value.length-1); }


    	// When options list whittled to one, select that entry
    	if( autoSelect ) {
    		len = 1;
    	}
    	else {
    		len = 0;
    	}

		if( selectObj.length <= len || ( selectObj.options[0].text == searchPattern && selectObj.length == 1 ))
		{
			if( selectObj.length > 0 ) {
    			selectObj.options[0].selected = true;
    		}

			switch (keyCode) {
				case 37: //left arrow
				case 39: //right arrow
				case 33: //page up
				case 34: //page down
				case 36: //home
				case 35: //end
				case 13: //enter
				case 9: //tab
				case 27: //esc
				case 16: //shift
				case 17: //ctrl
				case 18: //alt
				case 20: //caps lock
				case 8: //backspace
				case 46: //delete
				case 38: //up arrow
				case 40: //down arrow
				return;
				break;
		    }

			var len = textObj.value.length;
    		SuggestBox.update(textObj,selectObj,delimiter);
			//textObj.setSelectionRange(len, textObj.value.length); // bug fix oct 18 2008
			selectObj.style.display = 'none';
			return;
		}

		// make visible
		if(searchPattern.length > 0) {
			selectObj.style.display = 'block';
		}
	}

	/**
	 * lookupUpdate
	 *
	 * update textbox with value of listbox
	 *
	 */
	SuggestBox.update = function(textObj,selectObj,delimiter) {
		if( selectObj.options && selectObj.selectedIndex >= 0 ) {
			if( delimiter ) {
				if( textObj.value.lastIndexOf(delimiter) > 0) {
					textObj.value = textObj.value.substr(0, textObj.value.lastIndexOf(delimiter)) + delimiter + ' ' + selectObj.options[selectObj.selectedIndex].text + delimiter + ' ';
				}
				else {
					textObj.value = selectObj.options[selectObj.selectedIndex].text + delimiter + ' ';
				}
			}
			else {
				textObj.value = selectObj.options[selectObj.selectedIndex].text;
			}
		}

    	selectObj.style.display = 'none';
    	textObj.focus();
	}

	/**
	 * lookupSelectHandleKeyUp
	 *
	 * update textbox if <enter> presses
	 *
	 */
	SuggestBox.selectHandleKeyUp = function(keyCode,textObj,selectObj,delimiter) {
		if( keyCode == 13 ) {
			SuggestBox.update(textObj,selectObj,delimiter);
		}
	}

//--><!]]>