(function () {

	"use strict";

	Vue.component( 'cbw-slides', {
		template: '#cbw_slides',
		data: function() {
			return {
				slides: [],
				slider: false,
				autoplay: 5000,
				autoplayInterval: false,
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
					self.slider = new Siema({
						loop: true,
						duration: 500,
					});
				} );

				self.setAutoplay();
			});

		},
		methods: {
			setAutoplay: function() {

				var self = this;

				self.autoplayInterval = setInterval( function() {
					if ( self.slider ) {
						self.slider.next();
					}
				}, self.autoplay );

			},
			resetAutoplay: function() {
				clearInterval( this.autoplayInterval );
				this.setAutoplay();
			}
		}
	} );

})();