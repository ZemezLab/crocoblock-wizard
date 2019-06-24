(function () {

	"use strict";

	Vue.component( 'cbw-connection-error', {
		template: '#cbw_connection_error',
		methods: {
			downloadReport: function() {
				window.location = window.CBWPageConfig.report_url;
			}
		}
	} );

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
				pageTitle: window.CBWPageConfig.page_title,
				buttonLabel: window.CBWPageConfig.button_label,
				isActivated: window.CBWPageConfig.license_is_active,
				deactivateLink: window.CBWPageConfig.deactivate_link,
				videoURL: '',
				showVideo: false,
				tutorials: window.CBWPageConfig.tutorials,
			};
		},
		mounted: function() {
			var storage = window.sessionStorage;
			storage.removeItem( 'cbw-theme-to-install' );
		},
		computed: {
			startLocked: function() {
				if ( this.isActivated ) {
					return null === this.installationType;
				} else {
					return null === this.installationType || null === this.licenseKey;
				}
			},
		},
		methods: {
			maybeChangeBtnLabel: function() {
				if ( ! this.startLocked ) {
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

				if ( self.isActivated ) {
					window.location = window.CBWPageConfig[ 'redirect_' + self.installationType ];
					return;
				}

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
						window.location     = window.CBWPageConfig[ 'redirect_' + self.installationType ];

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