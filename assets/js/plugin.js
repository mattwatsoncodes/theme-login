( function( $ ) {
	'use strict';

	/**
	 * Remove alert when x clicked.
	 */
	$( '.mkdo_alert .close' ).click( function(){
		$(this).closest( '.mkdo_alert' ).fadeOut( function() {
			$(this).slideUp( function() {
				$(this).remove();
			} )
		} )
	} );

} )( jQuery );
