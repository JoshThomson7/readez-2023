/**
 * Courses Online - JS
 * 
 * @version 1.0
 */

(function($, root, undefined) {

	var CourseFilters = $('#tlc_form_filters').filterify({
		ajaxAction: 'tlc_courses_online_filters',
		responseEl: '#courses_response',
		paginationSelector: '.ajax-paginate',
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

})(jQuery, this);
