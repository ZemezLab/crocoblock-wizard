(function () {

	"use strict";

	Vue.component( 'cbw-license', {
		template: '#cbw_license',
		data: function() {
			return {
				licenseKey: null,
				installationType: null,
				loading: false,
				log: {},
				error: false,
				errorMessage: '',
				success: false,
				successMessage: '',
				buttonLabel: window.CBWPageConfig.button_label,
				videoURL: '',
				showVideo: false,
				tutorials: window.CBWPageConfig.tutorials,
			};
		},
		methods: {
			maybeChangeBtnLabel: function() {
				if ( this.licenseKey && this.installationType ) {
					this.buttonLabel = window.CBWPageConfig.ready_button_label;
				} else {
					this.buttonLabel = window.CBWPageConfig.button_label;
				}
			},
			clearErrors: function() {
				this.error        = false;
				this.errorMessage = '';
			},
			openVideoPopup: function( url ) {
				this.videoURL  = url;
				this.showVideo = true;
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

						self.success        = true;
						self.successMessage = response.data.message;
						self.loading        = false;

						window.location = window.CBWPageConfig[ 'redirect_' + self.installationType ];

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