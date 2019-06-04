(function () {

	"use strict";

	Vue.component( 'cbw-license', {
		template: '#cbw_license',
		mixins: [ window.CBWRecursiveRequest ],
		data: function() {
			return {
				licenseKey: null,
				installationType: null,
				loading: false,
				log: {},
				error: false,
				errorMessage: '',
				buttonLabel: window.CBWPageConfig.button_label,
			};
		},
		methods: {
			clearErrors: function() {
				this.error        = false;
				this.errorMessage = '';
			},
			activateLicense: function() {

				var self = this;

				self.loading = true;
				self.log     = {};

				jQuery.ajax({
					url: ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: {
						action: window.CBWPageConfig.action_mask.replace( /%module%/, window.CBWPageConfig.module ),
						handler: 'verify_license',
						license_key: self.licenseKey,
						nonce: window.CBWPageConfig.nonce,
					},
				}).done( function( response ) {

					if ( ! response ) {
						self.error        = true;
						self.errorMessage = 'Empty response';
						self.loading = false;
						return;
					}

					if ( ! response.success ) {
						self.error        = true;
						self.errorMessage = response.data.message;
						self.loading      = false;
					} else {

						if ( response.data.doNext ) {
							self.recursiveRequest( {
								key: response.data.nextRequest.handler,
								status: 'in-progress',
								message: response.data.message,
							}, response.data.nextRequest );
						}

					}

				} ).fail( function( xhr, textStatus, error ) {

					self.loading      = false;
					self.error        = true;
					self.errorMessage = error;

				} );

			},
		}
	} );

})();