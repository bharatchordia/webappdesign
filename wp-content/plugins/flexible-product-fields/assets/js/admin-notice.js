jQuery(document).ready(function() {

	var notice         = jQuery( '[data-notice="fpf-admin-notice"]' );
	var is_permanently = false;
	if ( ! notice ) {
		return;
	}

	function close_notice() {

		jQuery.ajax(
			notice.attr( 'data-notice-url' ),
			{
				type: 'POST',
				data: {
					action: notice.attr( 'data-notice-action' ),
					is_permanently: ( is_permanently ) ? 1 : 0,
				},
			}
		);
	}

	var notice_button_close = notice.find( '.notice-dismiss' );
	var notice_button_hide  = notice.find( '[data-notice-button]' );
	if ( ! notice_button_close || ! notice_button_hide ) {
		return;
	}

	notice_button_close.click( close_notice );
	notice_button_hide.click( function( e ) {
		e.preventDefault();
		is_permanently = true;
		notice_button_close.click();
	} );

});
