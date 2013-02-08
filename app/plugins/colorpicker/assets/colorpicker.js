

	/**
	 * Initialize namespace
	 */
	var ColorPicker = {};

	ColorPicker.getColor = function( color, id ) {
		document.getElementById( id ).value = color;
		document.getElementById( 'c_' + id ).style.display = 'none';
	}

