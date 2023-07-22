/**
 * Resources - JS
 * 
 * @version 1.0
 */

(function($, root, undefined) {

	var SpeakerFilters = $('#resources_filters').filterify({
		ajaxAction: 'tlc_resources_filters',
		responseEl: '#resources_response',
		paginationSelector: '.ajax-paginate',
		trigger: '.filter-trigger',
		skeleton: {
			count: 12,
			markup: '<article class="skeleton">'+
                '<div class="resource-pad">'+
                    '<figure></figure>'+
                    '<div class="resource-info">'+
                        '<h3></h3>'+
                        '<span class="woocommerce-Price-amount"></span>'+
                    '</div>'+
                '</div>'+
            '</article>'
		}
	});

	$(document).on('click', '.tlc-filters article h3', function() {
		$(this).parent('article').toggleClass('expand');
		$(this).find('i').toggleClass('fa-chevron-down fa-chevron-up');
	});

})(jQuery, this);
