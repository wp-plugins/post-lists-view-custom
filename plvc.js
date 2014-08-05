jQuery(document).ready(function($) {

	$('.plvc #postbox-container-1 .toggle-width').on('click', function( ev ) {
		
		var Action = 'plvc_donation_toggle';
		$('.plvc').toggleClass('full-width');

		if( $('.plvc').hasClass('full-width') ) {
			$.post(ajaxurl, {
				'action': Action,
				'f': 1,
			});
		} else {
			$.post(ajaxurl, {
				'action': Action,
				'f': 0,
			});
		}
		
		return false;

	});

});
