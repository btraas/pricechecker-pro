/*

common.js - Various utilities & setup used with each main page-load
Loaded by page_header_tpl.html (and NOT ajax_header_tpl.js)

*/

// Used for/from login dialog
var login_username = '';
var login_password = '';
var login_open = false;

$(document).ready(function() { // {{{

	$("#logoutButton").button() // {{{
		.css(
		{
			'margin-right'		: '5px',
			'width'				: '75px',
			'height'			: '26px',
			'padding'			: '0px' 
		})
		.click( function()
		{
			window.location = 'index.php?p=Logout';
		}); // }}}

	$("#loginButton").button() // {{{
		.css(
		{
			'margin-right'		: '5px',
			'width'				: '75px',
			'height'			: '26px',
			'padding'			: '0px' 
		}); // }}}
	$("#fancybox-login").fancybox( // {{{
	{
		'transitionIn'          : 'elastic',
		'transitionOut'         : 'elastic',
		'width'					: 325,
		'height'				: 185,
		'padding'				: 0,
		'type'					: 'iframe',
		'showCloseButton'		: false,
		'titleShow'				: false,
		'modal'					: false,
		'hideOnOverlayClick'	: false,
		'hideOnContentClick'	: false,
		'centerOnScroll'		: false,
		onStart					: function()
		{
			login_username = '';
			login_password = '';
			var url = "index.php?p=Login";
			if( authTimeout.length>0) url += '&authTimeout=' + authTimeout;
			this.href = url;
			login_open = true;
		},
		onComplete              : function()
		{
			// Insert custom title-bar & allow dialog to be dragged from it
			$('<h5 id="fancybox-titlebar" class="ui-widget-header">' 
			+ '<div class="fbtb-m">Intranet Login</div>'
			+ '<div class="fbtb-r" onClick="$.fancybox.close();" title="Close Dialog">&nbsp;X&nbsp;</div>'
			+ '<div style="display:inline-block;float:right;">|</div>'
			+ '</h5>').insertBefore( $('#fancybox-frame') );

			$('#fancybox-wrap').draggable( { handle: $('#fancybox-titlebar') } );
		},
		onCleanup				: function()
		{
			login_open = false;

			if( login_username.length > 0  &&  login_password.length > 0 )
			{
				// Use post() instead of load() so password doesn't show up in Apache logs (URL string)
				//$('#tmp').load( 'index.php?p=Login&username=' + login_username + '&password=' + login_password );

				$.post( 'index.php', 
				{ 
					'p'				: "Login", 
					'username'		: login_username, 
					'password'		: login_password,
					'orig_request'	: orig_request 
				}, 
				function( data )
				{
					$('#tmp').html( data );
				});
			}
		}
	}); // }}}

	// Set up window size & resize handler {{{
	var hAdj = 90;
	var wAdj = 20;
	if( kioskMode=='t' ) { hAdj = 0; wAdj = 10; }
	if( popupMode=='t' ) { hAdj = 5; wAdj = 5; }
	
	/* 
	$('#contentarea').css( 'height', ( $(window).height() - hAdj ) + 'px' );
	$('#contentarea').css( 'width', ( $(window).width() - wAdj ) + 'px' );

	// Create window-resize event-handler
	$(window).resize(function()
	{
		logger( "window.height=" + $(window).height() + ", window.width=" + $(window).width() );
		$('#contentarea').css( 'height', ( $(window).height() - hAdj ) + 'px' );
		$('#contentarea').css( 'width', ( $(window).width() - wAdj ) + 'px' );
	});

	*/

	var topbarHeight = 36;
	$('#topbar').children('div:visible').each( function()
	{
		var bottom = $(this).height() + parseInt( $(this).position().top );
		if( bottom > topbarHeight  &&  bottom < 150 ) topbarHeight = bottom;
		//logger( 'bottom = ' + bottom );
	});
	$('#topbar').height( topbarHeight );

	$('#middlebar').height( $(window).height() - $('#topbar').height() - $('#bottombar').height() );


	$(window).resize(function()
	{
		//logger( "window.height=" + $(window).height() + ", window.width=" + $(window).width() );
		var topbarHeight = 36;
		$('#topbar').children('div:visible').each( function()
		{
			var bottom = $(this).height() + parseInt( $(this).position().top );
			if( bottom > topbarHeight  &&  bottom < 150 ) topbarHeight = bottom;
			//logger( 'bottom = ' + bottom );
		});
		$('#topbar').height( topbarHeight );

		$('#middlebar').height( $(window).height() - $('#topbar').height() - $('#bottombar').height() );
	});
	// }}}

	// Initialize the menu & main page {{{
	$('#mainmenu_underlay').bind( 'click', function()
	{
		//$('#mainmenu_contents').css( 'display', 'none' );
		$('#mainmenu_div').css( 'display', 'none' );
		$(this).hide();
	});

	goMenu();
	// }}}

	// Set focus to the first visible form field
	//$(':input:enabled:visible:first').focus();

	setupSpecialLinkHandlers();

	// Validate user login every minute.
	setTimeout( authChecker, ( 1 * 60 * 1000 ) );

}); // }}} 

function setupSpecialLinkHandlers() // {{{
{
	// This needs to get (re-)run from .ready() functions of sub-page elements (loaded via Ajax, etc.)
	//console.log( "Setting up special link handlers...." );

	// Setup special clickable links (that go on top of clickable containers)
	// See example in templates/orders-details_html.tpl

	$('.link').unbind( 'mousedown' )
		.bind( 'mousedown', function( event )							// Left-click
		{
			//console.log( 'Button clicked: ' + event.which );

			if( event.which == 1 )			// Left-click
			{
				showSpinner();
				//$.post( $(this).data( 'href' ), function( data ) { $('body').html( data ); } );
				location.href = $(this).data( 'href' );
				event.stopPropagation();		// Prevent event 'bubbling'.  I.e. allow clickable items ontop of clickable items (containers).
				return false;
			}
			else if( event.which == 2 )		// Middle-click
			{
				window.open( $(this).data( 'href' ) );
				event.stopPropagation();		// Prevent event 'bubbling'.  I.e. allow clickable items ontop of clickable items (containers).
				return false;
			}

			// NOTE:  Right-click (event.which==3) is ignored...  I.e. allow default action

		});

} // }}}

function goMenu( id ) // {{{
{
	var label;
	var init = false;

	if( typeof(id) == 'undefined' )
	{
		id = current_page;
		init = true;

	}
	if( id == '' ) id = current_page;
	if( id == '' ) id = 'Tools';

	// Hide the drop-down menu
	$('#mainmenu_div').css( 'display', 'none' );

	// Show and remember what our current page is...
	//$('#mainmenu').html( $('#MM_'+id).html() );
	$('#mainmenu_button').html( $('#MM2_'+id).html() );
	current_page = id;

	// Load content into the main area....
	if( ! init ) loadContent( id );

} // }}}

function loadContent( id ) // {{{
{
	showSpinner();
	$.post( 'index.php?p=' + id, function( data ) { $('body').html( data ); } );
} // }}}

function submitForm( id ) // {{{
{
	// There must be an HTML form with id=id to use this function....
	// Kevin Traas :: 21 Oct 2010

	if( typeof $('#'+id).attr('id') == "undefined" )
	{
		alert( 
			"Oops!  No (form) object found having id = " + id + 
			"\n\nSee index_tpl.js :: submitForm(id) for more info."
		);
		return false;
	}
	showSpinner();
	setTimeout( function() { $('#'+id).submit(); }, 501 );

} // }}}

function authChecker() // {{{
{
	// This function polls the Server every 5 minutes to see if user is still authenticated.
	// If authenticated, nothing happens.  Otherwise, user is redirected to a "you've been logged out" page.

	if( userid.length == 0 ) return;
	if( login_open ) return;
	
	$('#authTmp').load( 'index.php?authCheck=' + current_page );

	// Wait 5 minutes and try again.
	setTimeout( authChecker, ( 5 * 60 * 1000 ) );

} // }}}

