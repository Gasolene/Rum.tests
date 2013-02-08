

	/**
	 * Initialize namespace
	 */
	var PickList = {};

	/**
	 * PickList.move
	 *
	 * add items to list
	 *
	 * @param	controlId1		name of control
	 * @param	controlId2		name of control
	 *
	 * @return	TRUE if successfull
	 */
	PickList.move = function( controlId1, controlId2 )
	{
		var select = document.getElementById(controlId1);
		var select2 = document.getElementById(controlId2);
		var valuesToRemove = new Array();

		for(var i = 0; i < select.options.length; i++)
		{
			if(select.options[i].selected)
			{
				var option = document.createElement('option');
				option.text = select.options[i].text;
				option.value = select.options[i].value;
				try
				{
					select2.add(option, null);
				}
				catch(e)
				{
					select2.add(option);
				}

				valuesToRemove.push(select.options[i].value);
			}
		}

		for(var x = 0; x < valuesToRemove.length; x++)
		{
			for(var y = 0; y < select.options.length; y++)
			{
				if(select.options[y].value == valuesToRemove[x])
				{
					select.remove(select.options[y].index);
					continue;
				}
			}
		}

		//fnSortDropDown(controlId1);
		//fnSortDropDown(controlId2);

		return true;
	}


	/**
	 * PickList.updateSelected
	 *
	 * update selected list items
	 *
	 * @param	controlId1		name of control
	 * @param	controlId2		name of control
	 *
	 * @return	TRUE if successfull
	 */
	PickList.updateSelected = function( controlId1, controlId2 )
	{
		var select = document.getElementById(controlId1);
		var select2 = document.getElementById(controlId2);

		select2.options.length = 0;

		for(var i = 0; i < select.options.length; i++)
		{
			var option = document.createElement('option');
			option.text = select.options[i].text;
			option.value = select.options[i].value;
			option.selected = true;

			try
			{
				select2.add(option, null);
			}
			catch(e)
			{
				select2.add(option);
			}
		}

		PickList.fnSortDropDown(controlId1);
		PickList.fnSortDropDown(controlId2);

		try
		{
			select.onchange();
		}
		catch(e){}
	}

	/**
	 * PickList.fnSortDropDown
	 *
	 * remove duplicates
	 *
	 * @param	controlId1		name of control
	 */
	PickList.fnSortDropDown = function(controlId1)
	{
		var oSelect = document.getElementById(controlId1)
		var i = 0;
		while(i<oSelect.options.length)
		{
			PickList.fnRemoveOptions(controlId1, oSelect.options[i].value)
			i++;
		}
	}

	/**
	 * PickList.fnRemoveOptions
	 *
	 * remove option
	 *
	 * @param	controlId1		name of control
	 * @param	sValue			values to remove
	 */
	PickList.fnRemoveOptions = function(controlId1, sValue)
	{
		var oSelect = document.getElementById(controlId1);
		var i = oSelect.options.length;
		var bFound = false;
		while(i>0)
		{
			i--;
			if (oSelect.options[i].value==sValue && bFound==false)
			{
				bFound = true
			}
			else if (oSelect.options[i].value==sValue)
			{
				oSelect.removeChild(oSelect.options[i]);
			}
		}
	}

