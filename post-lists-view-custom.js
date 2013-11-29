jQuery(document).ready(function($) {

	var $Form = $("#post_lists_view_custom_form");

	if( 0 < $("#use" , $Form).size() ) {

		// sortable
		$("div#use, div#not_use").sortable({
			placeholder: "widget-placeholder",
			connectWith: ".widget-list",
			stop: function(event, ui) {
				var Before = $(this).attr("id");
				var After = ui.item.parent().attr("id");
				
				if(Before != After) {
					ui.item.children(".widget-inside").children("input").each(function() {
						var ItemName = $(this).attr("name");
						$(this).attr("name", ItemName.replace(Before, After));
					});
				}
			}
		}).disableSelection();

	}

	function donation_toggle_set( s ) {
		if( s ) {
			$(".plvc").addClass('full-width');
		} else {
			$(".plvc").removeClass('full-width');
		}
	}

	$(".toggle-plugin .icon a" , $(".plvc") ).click(function() {

		if( $(".plvc").hasClass('full-width') ) {
			donation_toggle_set( false );
			$.post(ajaxurl, {
				'action': 'plvc_set_donation_toggle',
				'f': 0,
			});

		} else {
			donation_toggle_set( true );
			$.post(ajaxurl, {
				'action': 'plvc_set_donation_toggle',
				'f': 1,
			});
		}

		return false;
	});


});
