/**
 * Courses Online - JS
 * 
 * @version 1.0
 */

(function($, root, undefined) {

	var CourseFilters = $('#tlc_form_filters').filterify({
		ajaxAction: 'tlc_events_filters',
		responseEl: '#events_response',
		paginationSelector: '.ajax-paginate',
		skeleton: {
			count: 12,
			markup: '<article class="skeleton">'+
				'<div class="padder">'+
					'<div class="dates">'+
						'<div class="date">'+
							'<span></span>'+
							'<strong></strong>'+
						'</div>'+
						'<span class="separator"></span>'+
						'<div class="date">'+
							'<span></span>'+
							'<strong></strong>'+
						'</div>'+
					'</div>'+
					'<div class="meta">'+
						'<h4></h4>'+
						'<span></span>'+
					'</div>'+
				'</div>'+
			'</article>'
		}
	});

})(jQuery, this);
