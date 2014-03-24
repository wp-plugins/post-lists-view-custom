jQuery(document).ready(function($) {

	var $Form = $("#post_lists_view_custom_form");

	if( 0 < $("#use" , $Form).size() ) {

		// Show spec information
		$(document).on('click', '.widget .widget-title-action a.widget-action', function( ev ) {
			
			var Available_type = $(ev.target).parent().parent().parent().parent().prop('id');
			
			if( Available_type == 'use' ) {
				$(ev.target).parent().parent().parent().find('.widget-inside').slideToggle();
			}
			
			return false;
		});

		// sortable
		$("#use, #not_use").sortable({
			placeholder: "widget-placeholder",
			connectWith: ".widget-list",
			stop: function( ev , ui ) {
				var Before = $(ev.target).prop('id');
				var After = ui.item.parent().prop('id');
				
				if(Before != After) {
					ui.item.find('input').each(function( key , el ) {
						var ItemName = $(el).prop('name');
						ui.item.find('input:eq(' + key + ')').prop('name', ItemName.replace( Before , After ) );
					});
					ui.item.find('select').each(function( key , el ) {
						var ItemName = $(el).prop('name');
						ui.item.find('select:eq(' + key + ')').prop('name', ItemName.replace( Before , After ) );
					});
				}
				
				if( After == 'not_use' ) {
					ui.item.find('.widget-inside').hide()
				}
				
			}
		}).disableSelection();

	} else {

		$('#column_load').on('click', function( ev ) {
			var load_url = $(ev.target).prop('href');
			
			$.ajax({
				url: load_url,
				beforeSend: function( xhr ) {
					$(ev.target).parent().parent().find('.loading').show();
				}
			}).done(function( data ) {
				location.reload();
			});
	
			return false;
		});

	}

	function donation_toggle_set( s ) {
		if( s ) {
			$(".plvc").addClass('full-width');
		} else {
			$(".plvc").removeClass('full-width');
		}
	}

	$('.plvc .toggle-plugin .icon a').on('click', function() {
		var Action = 'plvc_donation_toggle';
		if( $(".plvc").hasClass('full-width') ) {
			donation_toggle_set( false );
			$.post(ajaxurl, {
				'action': Action,
				'f': 0,
			});

		} else {
			donation_toggle_set( true );
			$.post(ajaxurl, {
				'action': Action,
				'f': 1,
			});
		}

		return false;
	});

});
