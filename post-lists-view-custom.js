jQuery(document).ready(function($) {

	var $Form = $("#post_lists_view_custom_form");

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

});
