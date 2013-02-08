

	/**
	 * Initialize namespace
	 */
	Mootools.FacebookCropper = {};

	Mootools.FacebookCropper.uploadImage = function( form, callback ) {
		PHPRum.submitForm( form, callback );
		form.submit();
	}


	/**
	 * Function to set the validation ready flag
	 */
	Mootools.FacebookCropper.handleResponse = function(form, response) {
		eval(response);
		form.removeChild(document.getElementById(form.getAttribute('id')+'__async'));
		form.setAttribute('target', '');
	}


	/**
	 * Function to create the image cropper tool
	 */
	Mootools.FacebookCropper.cropImage = function(target, container, coordinateContainer, topCoord, leftCoord, desired_ratio, width, height){
		var img = $(target);
		var imgSizeRatio = img.getSize().x / img.getSize().y;
		var desiredRatio = desired_ratio;
		if (imgSizeRatio > desiredRatio) {
			img.setStyle('height',height);
		}else{
			img.setStyle('width',width);
		}
		var myDragScroller = new Drag(container, {
			snap:0,
			style: false,
			invert: true,
			modifiers: {x: 'scrollLeft', y: 'scrollTop'},
			onComplete: function(el){
				var cropCutFromTop = img.getCoordinates($(coordinateContainer)).top;
				var cropCutFromLeft = img.getCoordinates($(coordinateContainer)).left;
					$(topCoord).set('value',cropCutFromTop);
					$(leftCoord).set('value',cropCutFromLeft);
			}
		});
	}

