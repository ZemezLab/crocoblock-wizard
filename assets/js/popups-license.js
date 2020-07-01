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

	Vue.component( 'cbw-popups-license', {
		template: '#cbw_popups_license',
		data: function() {
			return {
				licenseKey: null,
				installationType: 'full',
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
				hasTemplateAccess: window.CBWPageConfig.has_template_access,
				tutorials: window.CBWPageConfig.tutorials,
			};
		},
		mounted: function() {
			this.maybeChangeBtnLabel();
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
					window.location = window.CBWPageConfig.next_step;
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

						self.loading = false;

						if ( response.data.access_error ) {
							self.success           = true;
							self.isActivated       = true;
							self.hasTemplateAccess = false;
						} else {
							self.error        = true;
							self.errorMessage = response.data.message;
						}

					} else {

						self.success        = true;
						self.successMessage = response.data.message;
						window.location     = window.CBWPageConfig.next_step;

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