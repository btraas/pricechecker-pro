/*

functions.js - Very common functions used throughout the website....
Loaded by page_header_tpl.html AND ajax_header_tpl.js

*/

$(document).ready( function() // {{{
{
	/*		-- Brayden -> moved to ui-functions.js (jQuery UI dependant)
	$(document).tooltip(
	{
		tooltipClass		: 'ui-tooltip-smoke',
		show				: { effect: '', delay: 1000, duration: 'fast' },
		hide				: { effect: '', delay:  250, duration: 'fast' },
	});
	*/

	setMouseHoverAction( 0 );
	enableSelectBoxIt( 1 );

	//removeObsoleteTooltips();

	$(document)
		.on( 'click', '.disabled', function( event ) 
		{
			logger( "Click event on DOM element ignored because element has 'disabled' class assigned" );
			event.stopPropagation();
			return false;
		})
		.on( 'contextmenu', '.disabled', function( event ) 
		{
			logger( "Click event on DOM element ignored because element has 'disabled' class assigned" );
			event.stopPropagation();
			return false;
		});

}); // }}} 

/*	Brayden - moved to ui-functions.js
function removeObsoleteTooltips() // {{{
{
	// Called from document.ready().   No need to call from elsewhere....

	// This is necessary when a tooltip is shown, but the underlying content (which generated the tooltip)
	// is removed (empty(), load(), etc.) before the tooltip is removed.  The event to remove the tooltip
	// is now gone, so the tooltip hangs around forever.   This function gets rid of them after a long timeout.

	var waitFor = 20;			// How many seconds to wait before removing a tooltip.

	// Tag all existing tooltips...
	$('.ui-tooltip').data( 'kevinQueueForCleanup', 't' );

	setTimeout( function()
	{
		// Look for tagged tooltips (that weren't autoremoved already) and remove them...

		$('.ui-tooltip').each( function()
		{
			if( $(this).data( 'kevinQueueForCleanup' ) == 't' ) 
			{
				logger( "Removing old (orphaned?) Tooltip..." );
				$(this).fadeOut().remove();
			}
		});

		removeObsoleteTooltips();		// Restart....
	}, (waitFor*1000) );

} // }}}
function removeToolTips() // {{{
{
	$('.ui-tooltip').each( function() { $(this).fadeOut().remove(); });
}  // }}}
*/

var selectBoxIt_running = false;
var selectBoxIt_collection = new Array();
var selectBoxIt_manualrun = false;
function enableSelectBoxIt( timeout, callback ) // {{{
{
	// Need to run this continually to ensure Ajax-loaded content gets <select> elements updated too
	// Increase timeout by 0.5s each iteration until max of 10s is reached. 
	// Run with timeout <= 0 or null to have it run once (i.e. via ready() of ajax-loaded content using enableSelectBoxIt(); )
	// Note that any items that need to be set up are simply added to a collection that gets processed via a scheduled task.
	// This method ensures a huge collection (i.e. hundreds of selectboxit's on a page) don't "freeze" the browser.

	if( jQuery.type( timeout ) == 'undefined' ) timeout = 0;
	if( timeout > 0  &&  timeout < 10000 ) timeout += 500;
	if( timeout == 0 ) selectBoxIt_manualrun = true;		// This allows many more to be processed at once.  See activateSelectBoxIt()


//	logger( "enableSelectBoxIt( " + timeout + " ):  Starting..." );

	// Find all un-configured selectBoxIt elements and append them to the original collection
	selectBoxIt_collection.push.apply( selectBoxIt_collection, $('.selectBoxIt:not(.selectBoxIt-enabled)') );


	// If we have unconfigured SelectBoxIt items to be processed....
	if( selectBoxIt_collection.length > 0 )	
	{
		if( selectBoxIt_running )		// If it's already running, then re-queue to ensure a final attempt
		{
			// Wait a minimum of 1s before trying again...
			setTimeout( function() { enableSelectBoxIt( timeout, callback ); }, ( timeout < 1000 ? 1000 : timeout ) );
			return;
		}
		else
		{
			selectBoxIt_running = true;
//			logger( "enableSelectBoxIt( " + timeout + " ):  Found " + selectBoxIt_collection.length + " items to process..." );
			setTimeout( function() { activateSelectBoxIt( callback ); }, 100 );
			return;
		}
	}
	else if( callback ) callback();		// If no items to process, then be sure to run callback()


	// Run this function again (after timeout) to check for any new selectBoxIt items that get added to the DOM
	if( timeout > 0 ) setTimeout( function() { enableSelectBoxIt( timeout, callback ); }, timeout );

} // }}}
function activateSelectBoxIt( callback ) // {{{
{
	// This function sets up the selectBoxIt.  It's run as a scheduled task, and will process up to 6 queued
	// DOM elements before re-scheduling itself for future exection and relinquishing control back to browser.
	// Scheduling the tasks causes the overall process to take longer, however, the browser is responsive to
	// the user while the collection is being processed.   A good trade-off.

	var item;
	var max = 10;
	if( selectBoxIt_manualrun ) { selectBoxIt_manualrun = false; max = 60; }

	for( var x=0; x<=max; x++ )
	{
		if( selectBoxIt_collection.length == 0 ) break;					// We're done!

		item = selectBoxIt_collection.shift();

		if( $(item).hasClass( 'selectBoxIt-enabled' ) ) continue;		// This item is already done?!?!?

		$(item) // {{{
			.addClass( 'selectBoxIt-enabled' )		// Flag to ensure we don't try re-enabling....
			.removeClass( 'ui-state-default' )		// This overrides various selectBoxIt CSS settings, so remove it, if exists....
			.selectBoxIt(
			{
				showEffect			: 'slideDown',
				showEffectSpeed		: 'fast',
				hideEffect			: 'slideUp',
				hideEffectSpeed		: 'fast',
				viewport			: $('#middlebar'),
				autoWidth			: true						// If true, selects with dynamically-loaded content inside hidden tabs won't have width set right.  I.e. planting_counter-jobmgr
																// autoWidth == true is very convenient, but is a HUGE performance hit.
			})
			.bind( 'open', function()
			{
				// Work-around to fix problem where dropdown contents in a jQuery-UI Accordion or Dialog are "clipped".
				$(this).parents().each( function()
				{
					if( $(this).hasClass('ui-accordion-content')  ||  $(this).hasClass('ui-dialog-content') ) $(this).css( 'overflow', 'visible' );
				});
			})
			.bind( 'close', function()
			{
				// Undo work-around...
				$(this).parents().each( function()
				{
					if( $(this).hasClass('ui-accordion-content')  ||  $(this).hasClass('ui-dialog-content') ) $(this).css( 'overflow', 'hidden' );
				});
			})
			; // }}}

	}
	
	// If still more in the queue, then schedule myself to run again
	if( selectBoxIt_collection.length > 0 ) setTimeout( function() { activateSelectBoxIt( callback ); }, 10 );

	// Else we're done!
	else 
	{
		selectBoxIt_running = false;
// 		logger( "enableSelectBoxIt() is done" );

		// If no more selectBoxIt's to set up, then run the callback!
		if( callback ) callback();
	}

} // }}}
function refreshSelectBoxIt( obj ) // {{{
{
	// Refresh every (or specific obj, if defined) SelectBoxIt object on the page.

	if( jQuery.type( obj ) != 'undefined' )
	{
		var sb = obj.data('selectBox-selectBoxIt');
		if( jQuery.type( sb ) == 'undefined' ) return;
		sb.refresh();
		return;			// If just refreshing the one obj, we're done!
	}

	// Refresh all in a timer event to "background" it, so user is never "held-up" waiting for this to complete.
	setTimeout( function() 
	{
		//logger( "refreshSelectBoxIt() starting..." );

		$('.selectBoxIt-enabled').each( function()
		{
			var sb = $(this).data('selectBox-selectBoxIt');
			if( jQuery.type( sb ) == 'undefined' ) return;
			sb.refresh();
		});
	}, 10 );

} // }}}

function setMouseHoverAction( timeout ) // {{{
{
	// Hover transition is now handled by .hover:hover in css/screen.css
	return false;

	// Need to run this continually to ensure Ajax-loaded content gets EventListeners added to 'hover'-class items.
	// Increase timeout by 0.5s each iteration until max of 10s is reached. 
	// 
	if( timeout < 10000 ) timeout += 500;

	$('.hover').each( function()
	{
		if( $(this).hasClass( 'hover-added' ) ) return;

		$(this)
			.addClass( 'hover-added' )		// Flag to ensure we don't try re-adding event-listeners....
			.bind( 'mouseover', function() { $(this).addClass('ui-state-highlight'); } )
			.bind( 'mouseout', function() { $(this).removeClass('ui-state-highlight'); } );

	});

	setTimeout( function() { setMouseHoverAction( timeout ); }, timeout );
} // }}}

function url_encode( str ) // {{{
{
	// Turn str into a url-safe string
	return encodeURIComponent( str );
} // }}}
function url_decode( str )  // {{{
{
	return decodeURIComponent( ( str + '' ).replace( /\+/g, '%20' ) );
} // }}}

function addCommas( nStr ) // {{{
{
	// This public domain function was provided by http://www.mredkj.com/javascript/nfbasic.html
	// It takes a long number (i.e. 100000) and turns it into 100,000

	nStr += '';
	x = nStr.split( '.' );
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while( rgx.test( x1 ) ) 
	{
		x1 = x1.replace( rgx, '$1' + ',' + '$2' );
	}
	return x1 + x2;

} // }}}
function removeCommas( nStr ) // {{{
{
	// parseFloat() can't handle commas, so remove them first.... then return numeric datatype
	// i.e. convert 100,000 to 100000

	if( !nStr ) return null;
	return parseFloat( nStr.replace( /,/g, '' ) );

} // }}}
function getNumber( nStr ) // {{{
{
	//logger( 'nStr=' + nStr + ', typeof=' + typeof nStr );
	if( empty( nStr ) ) return 0;
	if( typeof nStr == 'number' ) return nStr;
	nStr = +( parseFloat( nStr.replace( /[^\d.-]/g, '' ) ) );       // First, remove anything other than 0-9, dot, or dash
	if( isNaN( nStr ) ) return 0;									// Stupid Javascript
	return nStr;
} // }}}




// Emulate PHP Functions {{{

function number_format( number, decimals, dec_point, thousands_sep ) // {{{
{
	// This function emulates PHP's number_format() function.
	// Sourced from:  http://phpjs.org/functions/number_format/

	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');

	var n = !isFinite(+number) ? 0 : +number,
	prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
	sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
	dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
	s = '',

	toFixedFix = function(n, prec) 
	{
		var k = Math.pow(10, prec);
		return '' + (Math.round(n * k) / k).toFixed(prec);
	};

	// Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	if (s[0].length > 3) 
	{
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || '').length < prec) 
	{
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}

	//logger( 'number_format( ' + number + ', ' + decimals + ' ) = ' + s.join(dec) );
	return s.join(dec);

}
// Testing...
//number_format( 5 );
//number_format( 5, 0 );
//number_format( 5, 1 );
//number_format( 5.1, 0 );
//number_format( 4.9, 0 );
//number_format( 5.1, 1 );
//number_format( 5.1, 3 );
//number_format( 5.13, 1 );
//number_format( 5.18, 1 );
//number_format( 5.25, 1 );
//number_format( 499.999, 1 );
//number_format( 500.15, 1 );
//number_format( 500.17, 1 );
//number_format( 500.17, 2 );
//number_format( 500.17353, 2 );
//number_format( 500.17753, 2 );
//number_format( 1500.17753, 2 );
//number_format( '1,499.97', 1 );
//number_format( 15000.17753, 2 );
//number_format( 150000.17753, 2 );
//number_format( 1500000.17753, 2 );
//number_format( 15000000.17753, 2 );
//number_format( 150000000.17753, 2 );
//number_format( 1500000000.17753, 2 );
//number_format( 15000000000.17753, 2 );
//number_format( 150000000000.17753, 2 );
//number_format( 1500000000000.17753, 2 );
// }}}

function function_exists( str ) // {{{
{
	if( typeof str === 'string' ) str = window[str];

	//logger( 'typeof=' + typeof str + ', function? ' + ( typeof str == 'function' ? 'Yes' : 'No' ) );
	return ( typeof str == 'function' );
} // }}}

empty = function( v ) { var t = typeof v; return t === 'undefined' || ( t === 'object' ? ( v === null || Object.keys( v ).length === 0 ) : [false, 0, "", "0"].indexOf( v ) >= 0 ); };

getBoolean = function( v ) { return ( ( v === true || v == 't' || v== 'true' ) ? true : false ); }

function in_array (needle, haystack, argStrict) // {{{
{
	// From: http://phpjs.org/functions
	// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +   improved by: vlado houba
	// +   input by: Billy
	// +   bugfixed by: Brett Zamir (http://brett-zamir.me)
	// *     example 1: in_array('van', ['Kevin', 'van', 'Zonneveld']);
	// *     returns 1: true
	// *     example 2: in_array('vlado', {0: 'Kevin', vlado: 'van', 1: 'Zonneveld'});
	// *     returns 2: false
	// *     example 3: in_array(1, ['1', '2', '3']);
	// *     returns 3: true
	// *     example 3: in_array(1, ['1', '2', '3'], false);
	// *     returns 3: true
	// *     example 4: in_array(1, ['1', '2', '3'], true);
	// *     returns 4: false
	var key = '',
	strict = !! argStrict;

	if( strict ) 
	{
		for( key in haystack ) if( haystack[key] === needle ) return true;
	} 
	else 
	{
		for( key in haystack ) if( haystack[key] == needle ) return true;
	}

	return false;
} // }}}

function array_key_exists( needle, haystack ) // {{{
{
	// discuss at: http://phpjs.org/functions/array_key_exists/
	// original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// improved by: Felix Geisendoerfer (http://www.debuggable.com/felix)
	// example 1: array_key_exists('kevin', {'kevin': 'van Zonneveld'});
	// returns 1: true

	if( !haystack || ( haystack.constructor !== Array && haystack.constructor !== Object ) ) return false;
	return needle in haystack;
} // }}}

function ucWords( str ) // {{{
{
	// NOTE:  This function considers space, underscore, & dash as word-delimiters....

	return str.toLowerCase().replace( /^([a-z])|[\s_-]+([a-z])/g, function( $1 )
	{
		return $1.toUpperCase();
	});
}
function ucwords( str ) {
	return ucWords( str );
} // }}}

var uniqid_Counter = 0;
function uniqid( prefix ) // {{{
{
	if( typeof prefix == 'undefined' ) prefix = 'kst';
	return prefix + '-' + ++uniqid_Counter;
} // }}}

nl2br = function(str, is_xhtml) { // {{{
	// Brayden 2016-08-24
	// http://stackoverflow.com/questions/7467840/nl2br-equivalent-in-javascript
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
} // }}}

// Brayden 2016 - get url parameters like PHP. 
//
// ##### PHP = $_GET['param'] :: JS = $_GET('param')

function $_GET(param) { // {{{
    var vars = {};
    window.location.href.replace( location.hash, '' ).replace(
        /[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
        function( m, key, value ) { // callback
            vars[key] = value !== undefined ? value : '';
        }
    );

    if ( param ) {
        return vars[param] ? vars[param] : null;
    }
    return vars;
} // }}}

// }}}

// Brayden 2016 - format phone number
getPhone = function( input, style ) // {{{
{
    // This only handles 10-digit numbers.  Returns original input (stripped of all non-numerics) otherwise...
    // style        : 1     --> (604) 858-5678  (default, unless cleansed input is not 10 chars)
    //              : 2     --> 604-858-5678
    //              : 3     --> 6048585678

	if(empty(input)) return input;

    val = input.replace(/\D/g,'');
    if( val.length == 11   &&  val.substr( 0, 1 ) == "1" ) val = val.substr( 1, 10 );      // If starts with 1 (LD prefix), then strip it.

    if( val.length != 10 ) return val;

    switch( style )
    {
        case 3:     return val;
        case 2:     return val.substr( 0, 3 ) + "-" . val.substr( 3, 3 ) + "-" . val.substr( 6, 4 );
        case 1:
        default:    return "(" + val.substr( 0, 3 ) + ") " + val.substr( 3, 3 ) + "-" + val.substr( 6, 4 );
    }

    // should never get here
    return val;

} // }}}



/* The shortest jQuery function ever.
 * Courtesy of http://www.mail-archive.com/discuss@jquery.com/msg04261.html
 * Reverse the order of a collection of jQuery dom objects
 * I.e. iterate through a collection in reverse order via:
 *		$('.selector').reverse().each( function() {} );
 */
jQuery.fn.reverse = [].reverse;

function fancybox_FormatTitle( title, currentArray, currentIndex, currentOpts ) // {{{
{
	//return '<div id="fb-title">' + title + '</div>';
	return '<h4>' + title + '</h4>';
} // }}}


/*	function insertAtCaret( txt ) // {{{
	Insert text into a text field (or textarea) at the current cursor/caret position.
	Usage:  $(obj).insertAtCaret( 'some text' );
*/
jQuery.fn.extend(
{
	insertAtCaret: function( myValue ) 
	{
		return this.each( function( i ) 
		{
			if( document.selection ) 
			{
				//For browsers like Internet Explorer
				this.focus();
				sel = document.selection.createRange();
				sel.text = myValue;
				this.focus();
			}
			else if( this.selectionStart || this.selectionStart == '0' ) 
			{
				//For browsers like Firefox and Webkit based
				var startPos = this.selectionStart;
				var endPos = this.selectionEnd;
				var scrollTop = this.scrollTop;
				this.value = this.value.substring( 0, startPos ) + myValue + this.value.substring( endPos, this.value.length );
				this.focus();
				this.selectionStart = startPos + myValue.length;
				this.selectionEnd = startPos + myValue.length;
				this.scrollTop = scrollTop;
			} 
			else 
			{
				this.value += myValue;
				this.focus();
			}
		})
	}, 
	insertAtCursor: function( myValue )			// Alias for the funtion above....
	{
		return this.insertAtCaret( myValue );
	}
}); // }}}

function getCaretPosition( ctrl )  // {{{
{
	/*
		Returns the current position of the caret/cursor within a text field (text, textarea)
		Usage:  var pos = getCaretPosition( document.getElementById( 'myElement' ) );
	*/

	var CaretPos = 0;  

	// IE Support
	if( document.selection ) 
	{
		ctrl.focus();
		var Sel = document.selection.createRange();
		Sel.moveStart( 'character', -ctrl.value.length );
		CaretPos = Sel.text.length;
	}

	// Firefox/Webkit support
	else if( ctrl.selectionStart || ctrl.selectionStart == '0' ) 
		CaretPos = ctrl.selectionStart;

	return CaretPos;
} // }}}
function setCaretPosition( ctrl, pos ) // {{{
{
	/*
		Repositions the caret/cursor within a text field (text, textarea)
		Usage:  setCaretPosition( document.getElementById( 'myElement' ), 42 );
	*/

	if( ctrl.setSelectionRange )
	{
		ctrl.focus();
		ctrl.setSelectionRange( pos, pos );
	}
	else if( ctrl.createTextRange ) 
	{
		var range = ctrl.createTextRange();
		range.collapse( true );
		range.moveEnd( 'character', pos );
		range.moveStart( 'character', pos );
		range.select();
	}
} // }}}

function isTouchDevice() // {{{
{
	// Return true|false if page is running on a touchscreen device (iPhone, iPad, etc.)
	return !!( 'ontouchstart' in window );
} // }}}

// define console.log if it doesn't exist. We need console.log for the zebradog kiosk app
if (!window.console) window.console = {};
if (!window.console.log) window.console.log = function () { };

function logger( msg ) // {{{
{
	// console.log() doesn't exist on IE unless Developer Tools is open.  And... if it doesn't exist, calls to this function
	// cause all JS on page to blow up.  I.e. won't run.  Thanks Microsoft.   This function checks that....
	// For an even better implementation, consider....  http://benalman.com/projects/javascript-debug-console-log/

	// Might want to dump to a log DIV that can be displayed?  (Popup Dialog or something?)

	try
	{
		if( window.console && console.log && arguments && arguments.callee && arguments.callee.caller )
		{
			var caller = arguments.callee.caller;

			var callingFunction = '';
			if( jQuery.type( caller ) == 'null' ) callingFunction = '';
			else if( jQuery.type( caller.name ) == 'undefined' ) callingFunction = 'Anonymous(): ';
			else callingFunction = caller.name + '(): ';

			if( typeof msg == 'object' )
			{
				console.log( timeStamp() + ": " + callingFunction + ": Object Properties:" );
				jQuery.each( msg, function( name, value ) { console.log( '   ' + name + '=' + value ) });
			}
			else console.log( timeStamp() + ": " + callingFunction + msg );
			return true;
		}

		return false
	}
	catch( err ) {}

} // }}}

function timeStamp( now ) // Return a timestamp with the format "yy-m-d h:MM:ss TT".  I.e. '2016-08-14 01:11:17.04 pm' {{{
{
	// Courtesy of: https://gist.github.com/hurjas/2660489

	if( now === undefined || !( now instanceof Date ) )
	{
		// Create a date object with the current time
		now = new Date();
	}

	// Create an array with the current month, day and time
	var date = [ now.getFullYear(), now.getMonth() + 1, now.getDate() ];

	// Create an array with the current hour, minute and second
	var time = [ now.getHours(), now.getMinutes(), now.getSeconds() ];

	// Determine AM or PM suffix based on the hour
	var suffix = ( time[0] < 12 ) ? "am" : "pm";

	// Convert hour from military time
	time[0] = ( time[0] < 12 ) ? time[0] : time[0] - 12;

	// If hour is 0, set it to 12
	time[0] = time[0] || 12;

	// If seconds and minutes are less than 10, add a zero
	for ( var i = 1; i < 3; i++ ) 
	{
		if ( time[i] < 10 ) 
		{
			time[i] = "0" + time[i];
		}
	}

	// Return the formatted string
	return date.join("/") + " " + time.join(":") + "." + now.getMilliseconds() + " " + suffix;
} // }}}

function jGrowl( Gmsg, Gtitle, Glife, Gtheme, Gsticky, Gposition ) // {{{
{
	if(typeof $.jGrowl != 'function') { alert('$.jGrowl undefined!'); return false; } 

	// msg			: message body
	// title		: message title
	// life			: ms to show message (0-20000)
	// theme		: [ info | good | error | warn ]
	// position		: [ top-right | top-left | bottom-right | bottom-left | center ]
	// sticky		: [ true | false ] - require user to manually close message....

	if( jQuery.type( Gmsg		) == 'undefined' ) Gmsg			= "<i>No message set</i>";
	if( jQuery.type( Gtitle		) == 'undefined' ) Gtitle		= "<i>No title set</i>";
	if( jQuery.type( Glife		) == 'undefined' ) Glife		= 3000;
	if( jQuery.type( Gtheme		) == 'undefined' ) Gtheme		= "info";
	if( jQuery.type( Gsticky	) == 'undefined' ) Gsticky		= false;
	if( jQuery.type( Gposition	) == 'undefined' ) Gposition	= "top-right";

	if( Glife == 0 ) Gsticky = true;
	if( Glife < 1000 ) Glife = 1000;
	if( Glife > 20000 ) Glife = 20000;
	if( Gtheme == "success" ) Gtheme = "good";
	if( Gtheme == "fail" ) Gtheme = "error";

	// Log to console too
	logger( Gtitle + ": " + Gmsg );
	logger( 'life=' + Glife + ', theme=' + Gtheme + ', position=' + Gposition );

	$.jGrowl( Gmsg, 
	{
		theme			: Gtheme,
		header			: Gtitle,
		life			: Glife,
		position		: Gposition,
		sticky			: Gsticky,
		openDuration	: 200,
		closeDuration	: 600
	});

} // }}}

// The following is thanks to http://www.somacon.com/p355.php {{{

// Add as method to all Strings  - i.e. mystring.ltrim();
String.prototype.trim = function() { return this.replace(/^\s+|\s+$/g,""); }
String.prototype.ltrim = function() { return this.replace(/^\s+/,""); }
String.prototype.rtrim = function() { return this.replace(/\s+$/,""); }

// Stand-alone functions - i.e. ltrim( mystring );
function trim(stringToTrim) { return stringToTrim.replace(/^\s+|\s+$/g,""); }
function ltrim(stringToTrim) { return stringToTrim.replace(/^\s+/,""); }
function rtrim(stringToTrim) { return stringToTrim.replace(/\s+$/,""); }
// }}}

// Documented/Provided by https://developer.mozilla.org/en-US/docs/JavaScript/Reference/Global_Objects/Array/forEach {{{
// .forEach is native in Webkit, FF1.5+, IE9+, & Opera so this code is probably not needed, but just in case...
if( !Array.prototype.forEach ) 
{
	Array.prototype.forEach = function( fn, scope ) 
	{
		for( var i=0, len=this.length; i<len; ++i ) 
		{
			fn.call( scope, this[i], i, this );
		}
	}
}
// }}}


function confirmDialog(m, o, c1, c2, c3, c4) // {{{ Fallback (fancybox) if ui-functions isn't loaded
{
	mobileConfirm(m, o, c1, c2, c3, c4, null);
} // }}}

function mobileConfirm( message, options, callback1, callback2, callback3, callback4, orig ) // {{{ confirmDialog for mobile
{

	var defaults = {
	
		'preset'				: '',

		'button1Label'			: 'OK',
		'button2Label'			: 'Cancel',
		'button3Label'			: '',
		'button4Label'			: '',

		'button1Class'			: '',
		'button2Class'          : '',
		'button3Class'          : '',
		'button4Class'          : '',

		'button1Theme'			: 'f',		// a -> lite theme
		'button2Theme'			: 'a',		// b -> dark theme
		'button3Theme'			: 'a',		// c -> blue theme
		'button4Theme'			: 'a',		// d -> grey/red theme
											// e -> yellow theme
		'headerTheme'			: 'f',		// f -> red theme
											// g -> green theme
											// h -> lite blue theme

		'autoClose'				: true,

		'buttonWidth'     		: '100px',
		'title'					: 'Confirm action',
		'message'				: 'Are you sure?',
		

		/*  Fancybox options */
		'transitionIn'          : 'elastic',
        'transitionOut'         : 'elastic',
		'titleShow'             : false,
        'modal'                 : false,
        'showCloseButton'       : false,
		'origin'				: orig,
        'width'                 : 515,
        'height'                : 315,
        'padding'               : 0,
		'hideOnOverlayClick'    : false,
        'hideOnContentClick'    : false,
        'centerOnScroll'        : false,
		onStart                 : function()
        {
        },
		onComplete              : function()
        {
			$('input[type=button]').button();
            hideSpinner();
        }
	};
	
	//	{{{ Apply defaults if options not set
	if(empty(callback1)) callback1 = function() {};
	if(empty(callback2)) callback2 = function() {};
	if(empty(callback3)) callback3 = function() {};
	if(empty(callback4)) callback4 = function() {};

	window.func1 = callback1;
	window.func2 = callback2;
	window.func3 = callback3;
	window.func4 = callback4;
	

	if(!empty(options.preset))
	{
		if(options.preset == 'delete')
		{
			defaults.button1Label = 'Delete';
			defaults.button2Label = 'Cancel';
			defaults.button1Theme = 'f';
			defaults.button2Theme = 'a';
			defaults.headerTheme = 'f';
			defaults.title = 'Delete';
			defaults.message = 'Are you sure you want to delete this item?';
		}

		else if(options.preset == 'save')
        {
            defaults.button1Label = 'Save';
            defaults.button2Label = 'Discard';
			defaults.button3Label = 'Cancel';
            defaults.button1Theme = 'c';
            defaults.button2Theme = 'e';
			defaults.button3Theme = 'a'
            defaults.headerTheme = 'e';
            defaults.title = 'Save changes';
            defaults.message = 'Do you want to save your changes?';
        }
	}

	if(empty(message)) message = 'Are you sure?';

	if(empty(options.button1Label)) options.button1Label = defaults.button1Label;
	if(empty(options.button2Label)) options.button2Label = defaults.button2Label;
	if(empty(options.button3Label)) options.button3Label = '';
	if(empty(options.button4Label)) options.button4Label = '';

	if(empty(options.button1Class)) options.button1Class = defaults.button1Class;
    if(empty(options.button2Class)) options.button2Class = defaults.button2Class;
    if(empty(options.button3Class)) options.button3Class = defaults.button3Class;
    if(empty(options.button4Class)) options.button4Class = defaults.button4Class;

	if(empty(options.button1Theme)) options.button1Theme = defaults.button1Theme;
    if(empty(options.button2Theme)) options.button2Theme = defaults.button2Theme;
    if(empty(options.button3Theme)) options.button3Theme = defaults.button3Theme;
    if(empty(options.button4Theme)) options.button4Theme = defaults.button4Theme;

	if(empty(options.headerTheme)) options.headerTheme = defaults.headerTheme;

	if(options.autoClose !== true && options.autoClose !== false) options.autoClose = defaults.autoClose;

	if(empty(options.buttonWidth)) options.buttonWidth = defaults.buttonWidth;

	if(empty(options.transitionIn)) options.transitionIn = defaults.transitionIn;
	if(empty(options.transitionOut)) options.transitionOut = defaults.transitionOut;

	//options.titleShow = false;
	if(empty(options.title) && !empty(options.titleFormat)) options.title = options.titleFormat;
	if(empty(options.title)) options.title = defaults.title;
	var title = options.title;
	
	options.titleFormat = null;
	options.title = null;
	//if(!empty(options.titleFormat)) options.titleShow = true;
	
	if(empty(options.modal)) options.modal = defaults.modal;	// If the user passes FALSE, it will replace with default (FALSE)
	if(empty(options.showCloseButton)) options.showCloseButton = defaults.showCloseButton;
	if(empty(options.width)) options.width = defaults.width;
	if(empty(options.height)) options.height = defaults.height;
	if(empty(options.padding)) options.padding = defaults.padding;	// again, 0 defaults to 0 so not an issue
	if(empty(options.hideOnOverlayClick)) options.hideOnOverlayClick = defaults.hideOnOverlayClick;
	if(empty(options.hideOnContentClick)) options.hideOnContentClick = defaults.hideOnContentClick;
	if(empty(options.centerOnScroll)) options.centerOnScroll = defaults.centerOnScroll;
	if(empty(options.onStart)) options.onStart = defaults.onStart;
	if(empty(options.onComplete)) options.onComplete = defaults.onComplete;

	var closeScript = '';
	if(options.autoClose) closeScript = '$.fancybox.close(); ';

	var callb1 = closeScript+'window.func1();';
    var callb2 = closeScript+'window.func2();';
    var callb3 = closeScript+'window.func3();';
    var callb4 = closeScript+'window.func4();';


  	// }}}

	var head = "<div class='ui-bar-"+options.headerTheme+"' style='height: 30px; padding-top: 8px; font-size: 260%; text-shadow: none; text-align: center;'>"+title+'</div>';

	var html = head+"<div style='padding: 10px; text-align: center; font-size: 140%;'>"+message + "<br /><br /><table style='width: 100%;'><tr>";
		
		html += "<td class='"+options.button1Class+"' style='margin: 0 auto; padding-left: 10px; padding-right: 10px; width: "+options.buttonWidth+";'><input type='button' data-theme='"+options.button1Theme+"' value='"+options.button1Label+"' onClick='"+callb1+"'></td>";
		html += "<td class='"+options.button2Class+"' style='margin: 0 auto; padding-left: 10px; padding-right: 10px; width: "+options.buttonWidth+";'><input type='button' data-theme='"+options.button2Theme+"' value='"+options.button2Label+"' onClick='"+callb2+"'></td>";
		
	if(!empty(options.button3Label)) html+= "<td class='"+options.button3Class+"' style='margin: 0 auto; padding-left: 10px; padding-right: 10px; width: "+options.buttonWidth+";'><input type='button' data-theme='"+options.button3Theme+"' value='"+options.button3Label+"' onClick='"+callb3+"'></td>";
	if(!empty(options.button4Label)) html+= "<td class='"+options.button4Class+"' style='margin: 0 auto; padding-left: 10px; padding-right: 10px; width: "+options.buttonWidth+";'><input type='button' data-theme='"+options.button4Theme+"' value='"+options.button4Label+"' onClick='"+callb4+"'></td>";

		html += "</td></tr></table></div>";

	options.content = html;
	

	logger(JSON.stringify(options));

	parent.$.fancybox(options);

} // }}}


function JSONUserInfo_toKeyValuePairs( input, field, excludeDisabled ) // {{{
{
	// This is used by jEditable plugin (see t1_list2.html) to convert the "complex" UserInfo structure
	// to a simple key/value pair required as data input for jEditable.
	// Note that string input has already been sanitized by the backend PHP
	// input == Array
	// output == JSON data

	//logger( JSON.stringify( input, null, 2 ) );

	if( empty( field ) ) field = 'user_id';
	excludeDisabled = getBoolean( excludeDisabled );

	var str = null;

	for( var key in input )
	{
		if( excludeDisabled  && input[key].enabled == 'f' ) continue;

		if( str === null ) str = '{ ';
		else str += ', ';

		if( field == 'user_id' )	str += '"uid_' + input[key].user_id + '":"' + input[key].shortname + '"';
		else						str += '"cpaid_' + input[key].cpa_id + '":"' + input[key].shortname + '"';
	}

	str += ' }';

	var json = jQuery.parseJSON( str );
//	logger( JSON.stringify( json, null, 2 ) );

	return json;
} // }}}
function JSON_Months_toKeyValuePairs( input, field ) // {{{
{
	// This is used by jEditable plugin (see llt_clients-client-list.html) to convert the "complex" Month structure
	// to a simple key/value pair required as data input for jEditable.
	// Note that string input has already been sanitized by the backend PHP
	// input == Array
	// output == JSON data

	if( empty( field ) ) field = 'short';

	var str = null;

	for( var key in input )
	{
		if( str === null ) str = '{ ';
		else str += ', ';

		if( field == 'short' )		str += '"month_' + input[key].month + '":"' + input[key].short + '"';
		else						str += '"month_' + input[key].month + '":"' + input[key].description + '"';
	}

	str += ' }';

	return jQuery.parseJSON( str );
} // }}}
function JSON_lobs_toKeyValuePairs( input ) // {{{
{
	// This is used by jEditable plugin (see llt_clients-client_list.html) to convert the "complex" UserInfo structure
	// to a simple key/value pair required as data input for jEditable.
	// Note that string input has already been sanitized by the backend PHP
	// input == Array
	// output == JSON data

	var str = null;

	for( var key in input )
	{
		if( str === null ) str = '{ ';
		else str += ', ';

		str += '"lobid_' + input[key].lob_id + '":"' + input[key].description + '"';
	}

	str += ' }';

	return jQuery.parseJSON( str );
} // }}}
function JSON_clientTypes_toKeyValuePairs( input ) // {{{
{
	// This is used by jEditable plugin (see llt_clients-client_list.html) to convert the "complex" UserInfo structure
	// to a simple key/value pair required as data input for jEditable.
	// Note that string input has already been sanitized by the backend PHP
	// input == Array
	// output == JSON data

	var str = null;

	for( var key in input )
	{
		if( str === null ) str = '{ ';
		else str += ', ';

		str += '"ct_' + input[key].client_type_id + '":"' + input[key].client_type_description + '"';
	}

	str += ' }';

	return jQuery.parseJSON( str );
} // }}}



// Calculate an opacity value - see css/screen.css
//var opacity = 0.2;
//console.log( "Opacity " + opacity + " = " + Math.floor( opacity * 255 ).toString( 16 ) );



/* jQuery - Post with redirect {{{
This posts the data to the server, but via a redirect - i.e. reload entire page
Used when you want to send data to the server, but don't want it in a URL string.

Usage:
$.redirect( url, { var1: 'value1', var2: 'value2' } );

*/
$.extend(
{
	redirect: function( location, args )
	{
		var form = '';
		$.each( args, function( key, value )
		{
			form += '<input type="hidden" name="' + key + '" value="' + url_encode( value ) + '">';
		});

		$('<form action="' + location + '" method="POST">' + form + '</form>').appendTo('body').submit();
	}
}); // }}}

function validEmail( email ) // {{{
{
	// This does a basic check to see if an entered email address is valid.
	// Do NOT depend on this exclusively.  This is just for client-side checking.
	// You MUST also validate an email from server-side (preferably with actually
	// querying the server to ensure the recipient exists).
	// Or by using a validation response from an 'account activation' email.
	// Or by using the server-side common function validEmail()
	//
	// See http://stackoverflow.com/questions/46155/validate-email-address-in-javascript

	// This matches 99.99%, but allows dummy emails (i.e. asdf@asdf.asdf)
	//var regex = /^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/i;

	// This helps filter dummy emails by allowing 2-letter country codes and other specified TLD's
	// However, the accepted TLD's must be ammended when new TLD's are added.
	var regex = /^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[A-Z]{2}|com|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum)\b$/i;

	return regex.test( email );

} // }}}

// Browser detection {{{

/* This was sourced from:
	http://pupunzi.open-lab.com/2013/01/16/jquery-1-9-is-out-and-browser-has-been-removed-a-fast-workaround/
	http://jsfiddle.net/pupunzi/dnJNS/

   Usage:
	if( jQuery.browser.msie ) logger( "Internet Explorer detected." );
*/


jQuery.browser = {};
jQuery.browser.chrome = false;
jQuery.browser.msie = false;
jQuery.browser.mozilla = false;
jQuery.browser.opera = false;
jQuery.browser.safari = false;
jQuery.browser.webkit = false;

jQuery.browser.mozilla = /mozilla/.test(navigator.userAgent.toLowerCase()) && !/webkit/.test(navigator.userAgent.toLowerCase());
jQuery.browser.webkit = /webkit/.test(navigator.userAgent.toLowerCase());
jQuery.browser.opera = /opera/.test(navigator.userAgent.toLowerCase());
jQuery.browser.msie = /msie/.test(navigator.userAgent.toLowerCase()) || /trident/.test(navigator.userAgent.toLowerCase());


jQuery.browser.chrome = jQuery.browser.webkit;
jQuery.browser.safari = jQuery.browser.webkit;

// }}}

/**
	jQuery plugin for cleaning iframe content + memory 
	http://stackoverflow.com/questions/8407946/is-it-possible-to-use-iframes-in-ie-without-memory-leaks

	USAGE: $(frame).purgeFrame() instead of $(frame).remove();
*/

(function($) { // {{{
    $.fn.purgeFrame = function() {
        var deferred;

        if ($.browser.msie && parseFloat($.browser.version, 10) < 9) {
            deferred = purge(this);
        } else {
            this.remove();
            deferred = $.Deferred();
            deferred.resolve();
        }

        return deferred;
    };

	/* Brayden Traas 2016-05-31
		$(frame).resetFrame() 
		to clear data, purge memory, delete & re-add element
	*/
	$.fn.resetFrame = function() {
		var content = this.clone().empty();
		var _parent = this.parent();
		var $frame  = this.purgeFrame();
		_parent.append(content);
		return _parent.find('iframe').last();
	};

	 /* Brayden Traas 2016-05-31
        $(frame).updateFrame()
        to clear data, purge memory, 
		delete & re-add element, then change src
    */
	$.fn.updateFrame = function(src, src2) {
		if(empty(src)) src = this.attr('src');
		var self = this.resetFrame();
		self.load(function()
		{
			if(!empty(src2)) self.attr('src', src2);
			self.off('load');
		}).attr('src', src);
		return self;
	};

    function purge($frame) {
        var sem = $frame.length
          , deferred = $.Deferred();

        $frame.load(function() {
            var frame = this;
            frame.contentWindow.document.innerHTML = '';

            sem -= 1;
            if (sem <= 0) {
                $frame.remove();
                deferred.resolve();
            }
        });
        $frame.attr('src', 'about:blank');

        if ($frame.length === 0) {
            deferred.resolve();
        }

        return deferred.promise();
    }
})(jQuery); // }}}

