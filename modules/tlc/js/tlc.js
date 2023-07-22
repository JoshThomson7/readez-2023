// @codekit-prepend "../../../lib/select2/_select2.full.min.js";

(function ($, root, undefined) {

	$(document).on('click', '.toggle-attendees', function() {
		var container = $(this).closest('.meta');
		container.find('.attendees').toggleClass('active');
		
		var spanElement = $(this).children('span');
		var text = spanElement.text();
		spanElement.text(text === "Hide Attendees" ? "View Attendees" : "Hide Attendees");
	});
	
    
})(jQuery, this);