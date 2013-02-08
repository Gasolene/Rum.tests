

	// -- 4umi additional functions

	// Array.forEach( function ) - Apply a function to each element
	Array.prototype.forEach = function( f ) {
	 var i = this.length, j, l = this.length;
	 for( i=0; i<l; i++ ) { if( ( j = this[i] ) ) { f( j ); } }
	};

	// Array.indexOf( value, begin, strict ) - Return index of the first element that matches value
	Array.prototype.indexOf = function( v, b, s ) {
	 for( var i = +b || 0, l = this.length; i < l; i++ ) {
	  if( this[i]===v || s && this[i]==v ) { return i; }
	 }
	 return -1;
	};

	// Array.insert( index, value ) - Insert value at index, without overwriting existing keys
	Array.prototype.insert = function( i, v ) {
	 if( i>=0 ) {
	  var a = this.slice(), b = a.splice( i );
	  a[i] = v;
	  return a.concat( b );
	 }
	 return false;
	};

	// Array.lastIndexOf( value, begin, strict ) - Return index of the last element that matches value
	Array.prototype.lastIndexOf = function( v, b, s ) {
	 b = +b || 0;
	 var i = this.length; while(i-->b) {
	  if( this[i]===v || s && this[i]==v ) { return i; }
	 }
	 return -1;
	};

	// Array.random( range ) - Return a random element, optionally up to or from range
	Array.prototype.random = function( r ) {
	 var i = 0, l = this.length;
	 if( !r ) { r = this.length; }
	 else if( r > 0 ) { r = r % l; }
	 else { i = r; r = l + r % l; }
	 return this[ Math.floor( r * Math.random() - i ) ];
	};

	// Array.shuffle( deep ) - Randomly interchange elements
	Array.prototype.shuffle = function( b ) {
	 var i = this.length, j, t;
	 while( i ) {
	  j = Math.floor( ( i-- ) * Math.random() );
	  t = b && typeof this[i].shuffle!=='undefined' ? this[i].shuffle() : this[i];
	  this[i] = this[j];
	  this[j] = t;
	 }
	 return this;
	};

	// Array.unique( strict ) - Remove duplicate values
	Array.prototype.unique = function( b ) {
	 var a = [], i, l = this.length;
	 for( i=0; i<l; i++ ) {
	  if( a.indexOf( this[i], 0, b ) < 0 ) { a.push( this[i] ); }
	 }
	 return a;
	};

	// Array.walk() - Change each value according to a callback function
	Array.prototype.walk = function( f ) {
	 var a = [], i = this.length;
	 while(i--) { a.push( f( this[i] ) ); }
	 return a.reverse();
	};

	/**
	* Function : get_class(obj)
	*/
	function get_class(obj){ // webreflection.blogspot.com
	 function get_class(obj){
	  return "".concat(obj).replace(/^.*function\s+([^\s]*|[^\(]*)\([^\x00]+$/, "$1") || "anonymous";
	 }
	 var result = "";
	 if(obj === null)
	  result = "null";
	 else if(obj === undefined)
	  result = "undefined";
	 else {
	  result = get_class(obj.constructor);
	  if(result === "Object" && obj.constructor.prototype) {
	   for(result in this) {
		if(typeof(this[result]) === "function" && obj instanceof this[result]) {
		 result = get_class(this[result]);
		 break;
		}
	   }
	  }
	 }
	 return result;
	}

