'use strict';
(function ($) {
	jQuery(window).on('elementor/frontend/init', function(){
		elementorFrontend.hooks.addAction('frontend/element_ready/pd-pcf.default', function ($scope, $) {
			var elem = $scope.find('.wbel_pd_pcf_wrapper');
			var load_more_text = $scope.find('.pd_pcf_loadmore_btn').data('load_more');
			var loading_more_text = $scope.find('.pd_pcf_loadmore_btn').data('loading_more');
			
			var istp_grid = elem.isotope({
			  // options
			  itemSelector: '.pd_pcf_item',
			  layoutMode: 'fitRows',
			});
			istp_grid.imagesLoaded().progress( function() {
			  istp_grid.isotope();
			});

			// filter items on button click
			jQuery('.pd-pcf-filter-button-group').on( 'click', '.pd-pcf-filter-btn', function(e) {
				e.preventDefault();
				jQuery(this).siblings('.pd-pcf-filter-btn').removeClass('pd-pcf-filter-btn-active');
			  	jQuery(this).addClass('pd-pcf-filter-btn-active');
			  	var filterValue = jQuery(this).attr('data-filter');
			  	istp_grid.isotope({ filter: filterValue });
			});

			jQuery('.pd_pcf_loadmore_btn').on('click', function(e){
				e.preventDefault();
				var _this = jQuery(this);
				var args = jQuery(this).parents('.pd-pcf-container').data('settings');
				var offset = jQuery(this).parents('.pd-pcf-container').find('.pd_pcf_item').length;
				args['offset'] = offset;
				jQuery.ajax({
					// fas fa-spinner fa-spin
					url: pd_pcf_ajax_object.ajax_url,
					type: 'post',
					data : {
						'action': 'load_posts',
						'args' : args,
					},
					beforeSend: function(){
						// _this.find('.fas').addClass('fa-spin');
						_this.find('.pd_pcf_load_icon').addClass('pd-pcf-d-none');
						_this.find('.pd_pcf_loading_icon').removeClass('pd-pcf-d-none');
						_this.find('.pd-pcf-load-more-text').text(loading_more_text);
					},
					complete: function(xhr,status){
						// console.log(xhr.responseText);
						if(_this.parents('.pd-pcf-container').find('.pd_pcf_reach_limit').length > 0){
							_this.parent('.pd-pcf-load-btn').remove();
						}
						_this.find('.pd-pcf-load-more-text').text(load_more_text);
						_this.find('.pd_pcf_load_icon').removeClass('pd-pcf-d-none');
						_this.find('.pd_pcf_loading_icon').addClass('pd-pcf-d-none');

						var $html = jQuery( xhr.responseText );
						istp_grid.append( $html ).isotope( 'appended', $html );
						istp_grid.imagesLoaded().progress( function() {
						  istp_grid.isotope();
						});

						if(_this.parents('.pd-pcf-container').find('.wbel_pd_pcf_wrapper').find('.pd_pcf_reach_limit').length > 0){
							_this.parent('.pd-pcf-load-btn').addClass('pd-pcf-d-none');
						}
					}
				})
			});


		});
	});
})(jQuery);