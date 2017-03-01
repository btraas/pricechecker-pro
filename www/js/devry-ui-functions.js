/*

ui-functions.js -> for functions dependant on jquery UI
Loaded by page_header_tpl ONLY IF NOT MOBILE MODE (jQuery Mobile & jQuery UI conflict.) AFTER functions.js

*/

$(document).ready( function() // {{{
{
	$(document).tooltip(
	{
		tooltipClass		: 'ui-tooltip-smoke',
		show				: { effect: '', delay: 1000, duration: 'fast' },
		hide				: { effect: '', delay:  250, duration: 'fast' },
	});

	setMouseHoverAction( 0 );
	enableSelectBoxIt( 1 );

	removeObsoleteTooltips();

}); // }}} 

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
} // }}}

/** Improved alert() using a jQuery-UI Dialog box {{{
*
*	Usage:
*		alertDialog( "This is some alert message", { 'title': 'Did you know?' } );
*
*	Note:
*		Unlike confirm(), this dialog is Asynchronous.  It will NOT halt JS execution.
*
* @depends $.getOrCreateDialog
*
* @param { String } the alert message
* @param { Object } jQuery Dialog box options 
*/
function alertDialog( message, options, callback ) 
{
	// NOTE:  	These are the default options.  Many more can be set (i.e. height, width, title, position, Class, etc.)
	//			For more info, see http://api.jqueryui.com/dialog/
	var defaults = {
		title			: "Alert Message",
		modal			: true,
		resizable		: false,
		buttonWidth		: '70px',
		buttonLabel		: 'OK',
		buttons			: 
		[
			{
				text    : !empty( options.buttonLabel ) ? options.buttonLabel : 'OK',
				click   : function()
				{
					$(this).dialog('close');
					if( !empty( callback ) ) return (typeof callback == 'string') ?  window.location.href = callback : callback();
				}
			}
		],
		focus			: function()
		{
			$('#alert').next().find('button:eq(0)').addClass('ui-state-default').focus();
		},
		show			: 'fade',
		hide			: 'fade',
		minHeight		: 50,
		width			: 300,
		dialogClass		: 'ui-state-highlight',
		closeOnEscape	: true
	}

	$alert = $.getOrCreateDialog( 'alert' );	

	// set message
	message = message.replace( /\n/g, '<br />' );
	$("p", $alert).html( '<div style="text-align:left;">' + message + '</div>' );

	// init dialog
	$alert.dialog( $.extend( {}, defaults, options ) );

	var buttonWidth = ( typeof options.buttonWidth != 'undefined' ) ? options.buttonWidth : defaults.buttonWidth;

	$('#alert').next()
		.removeClass( 'ui-widget-content' ).addClass( options.dialogClass ? options.dialogClass : defaults.dialogClass ).css( 'border', '0px' )
		.find('.ui-button').addClass('small_button').css( { 'width':buttonWidth, 'margin-left':'10px' } )
		.find('.ui-button-text').css( { 'padding-top':'2px' } );

	// Make sure dialog appears above other dialogs
	$('#alert').closest('.ui-dialog').css( 'z-index', 10001 );

} // }}}
/** Improved confirm() using a jQuery-UI Dialog box {{{
*
*	Usage:
*		confirmDialog( "Are you sure?", { 'title': 'Please confirm' }, function() { console.log( "User clicked OK!" ); }, function() { console.log( "User clicked cancel" ); } );
*		confirmDialog( 'The message', { 'title': 'The Title', 'button1Label': 'Yes', 'button2Label': 'No' }, function() { alert( 'Yes' ); }, function() { alert( 'No' ); } );
*
*	Note:
*		Unlike confirm(), this dialog is Asynchronous.  It will NOT halt JS execution, which is why the callbacks are required.
*
*		See js/jquery.dataTables.FilterMgr.js for an example of a using confirmDialog() as a quick/easy   ---->TODO INPUT DIALOG TODO <----.
*
* @depends $.getOrCreateDialog
*
* @param { String } the alert message
* @param { String/Object } the confirm callback	
* @param { Object } jQuery Dialog box options 
*/
function confirmDialog( message, options, callback1, callback2, callback3, callback4 ) 
{
	// NOTE:  	These are the default options.  Many more can be set (i.e. height, width, title, position, Class, etc.)
	//			For more info, see http://api.jqueryui.com/dialog/

	var defaults = {
		title			: "Are you sure?",
		modal			: true,
		resizable		: false,
		messageFormat	: 'text',			// [text|html]		If text, \n will be translated to <br />.  If html, content is left untouched (literal)
		buttonWidth		: '70px',
		button1Label	: 'OK',
		button2Label	: 'Cancel',
		button3Label	: null,
		button4Label	: null,
		buttons			: 
		[
			{
				text	: !empty( options.button1Label ) ? options.button1Label : 'OK',
				click	: function() 
				{
					$(this).dialog('close');
					return (typeof callback1 == 'string') ?  window.location.href = callback1 : callback1();
				}
			},
			{
				text	: !empty( options.button2Label ) ? options.button2Label : 'Cancel',
				click	: function() 
				{
					$(this).dialog('close');
					if( !empty( callback2 ) ) 
						return (typeof callback2 == 'string') ?  window.location.href = callback2 : callback2();
					return false;
				}
			},
			{
				text	: !empty( options.button3Label ) ? options.button3Label : null,
				click	: function() 
				{
					$(this).dialog('close');
					if( !empty( callback3 ) ) 
						return (typeof callback3 == 'string') ?  window.location.href = callback3 : callback3();
					return false;
				}
			},
			{
				text	: !empty( options.button4Label ) ? options.button4Label : null,
				click	: function() 
				{
					$(this).dialog('close');
					if( !empty( callback4 ) ) 
						return (typeof callback4 == 'string') ?  window.location.href = callback4 : callback4();
					return false;
				}
			}
		],
		focus			: function()
		{
			// Make the last button the default
			$('#confirm').next().find('button:eq(0)').blur(); 
			$('#confirm').next().find('button').last().addClass('ui-state-default').focus();
		},
		show			: 'fade',
		hide			: 'fade',
		minHeight		: 50,
		width			: 300,
		dialogClass		: 'ui-state-highlight',
		closeOnEscape	: true,
		open			: null					// An optional function to call after the popup has been opened
	}

	$confirm = $.getOrCreateDialog( 'confirm' );	

	// set message
	if( options.messageFormat != 'html' ) message = message.replace( /\n/g, '<br />' );

	$("p", $confirm).html( '<div style="text-align:left;">' + message + '</div>' );

	// If user doesn't want a 2nd button, then remove it (and the 3rd and 4th) from the default collection
	if( empty( options.button2Label ) ) defaults.buttons.splice( 1, 3 );

	// If user doesn't want a 3rd button, then remove it (and the 4th) from the default collection
	if( empty( options.button3Label ) ) defaults.buttons.splice( 2, 2 );

	// If user doesn't want a 4th button, then remove it from the default collection
	else if( empty( options.button4Label ) ) defaults.buttons.splice( 3, 1 );


	// init dialog
	$confirm.dialog( $.extend( {}, defaults, options ) );


	var buttonWidth = ( typeof options.buttonWidth != 'undefined' ) ? options.buttonWidth : defaults.buttonWidth;

	$('#confirm').next()
		.removeClass( 'ui-widget-content' ).addClass( options.dialogClass ? options.dialogClass : defaults.dialogClass ).css( 'border', '0px' )
		.find('.ui-button').addClass('small_button').css( { 'width':buttonWidth, 'margin-left':'10px' } )
		.find('.ui-button-text').css( { 'padding-top':'2px' } );

	// Make sure dialog appears above other dialogs
	$('#confirm').closest('.ui-dialog').css( 'z-index', 10001 );

} // }}}
$.extend( // getOrCreateDialog() {{{
{
	/** Create DialogBox by ID
	* 
	* @param { String } elementID
	*/
	getOrCreateDialog: function( id ) 
	{
		$box = $('#' + id);
		if( !$box.length ) 
		{
			$box = $('<div id="' + id + '"><p></p></div>').hide().appendTo('body');
		}
		return $box;
	}		
}); // }}}


// load dialog with options, inc. loading HTML and callbacks
$.fn.extend(
{
	loadDialog: function(opts)
	{
		if(opts.html) this.html(opts.html);
		if(opts.htmlCallback) opts.htmlCallback();
		if(opts.url) this.load(opts.url);
		if(opts.loadCallback) opts.loadCallback();

		return this;
	}
});

// Extend jQuery UI Autocomplete Widget to better handle default selections {{{
// By default, widget does not search/select a value so various events are fired with no selected value
// even if the text input field has a value that matches a value in the list.  I.e. the user must 
// actually click (or scroll and hit Enter) on an item to select it.
// This live() function gets around this by auto-searching the select list, selecting the matching entry,
// and firing necessary events during a blur() event on the field with the autocomplete widget attached.
// The Weekly Commitments Edit Panel fields use this.
// Function is courtesy of: https://github.com/scottgonzalez/jquery-ui-extensions/blob/master/autocomplete/jquery.ui.autocomplete.autoSelect.js
//		- with various changes I've made to support jQuery >= 1.9 and jQuery UI >= 1.10
( function( $ ) 
{
	$.ui.autocomplete.prototype.options.autoSelect = true;

	$(document).on( 'blur', '.ui-autocomplete-input', function( event )
	{
		//logger( "autocomplete.blur(): Searching for a matching value...." );

		var jQUIver = jQuery.ui.version.split('.');

		var autocomplete = ( jQUIver[0] >=2 || ( jQUIver[0] == 1 && jQUIver[1] >= 10 ) ? $(this).data("uiAutocomplete") : $(this).data("autocomplete") );
		if( !autocomplete || !autocomplete.options.autoSelect || autocomplete.selectedItem ) return;

		var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( $(this).val().trim() ) + "$", "i" );
		autocomplete.widget().children( ".ui-menu-item" ).each( function() 
		{
			var item = ( jQUIver[0] >=2 || ( jQUIver[0] == 1 && jQUIver[1] >= 10 ) ? $(this).data("uiAutocompleteItem") : $(this).data("item.autocomplete") );
			if ( matcher.test( item.label || item.value || item ) ) 
			{
				autocomplete.selectedItem = item;
				return false;
			}
		});
		if( autocomplete.selectedItem ) 
		{
			logger( "autocomplete.blur(): Found a match!  Triggering select() event with value=" + autocomplete.selectedItem.value + ", label=" + autocomplete.selectedItem.label );
			autocomplete._trigger( "select", event, { item: autocomplete.selectedItem } );
		}
	});

}( jQuery ) );
// }}}



