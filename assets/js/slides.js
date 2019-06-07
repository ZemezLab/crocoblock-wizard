(function () {

	"use strict";

	Vue.component( 'cbw-slides', {
		template: '#cbw_slides',
		data: function() {
			return {
				slides: [],
			};
		},
		mounted: function() {

			var self = this;

			jQuery.ajax({
				url: window.CBWPageConfig.slides_url,
				type: 'GET',
				dataType: 'json',
			}).done( function( response ) {
				self.slides = response;
				self.$nextTick( function() {
					new Siema();
				} );
			});

		},
	} );

})();