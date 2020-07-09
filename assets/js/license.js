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
				hasTemplateAccess: window.CBWPageConfig.has_template_access,
				hasDesignTemplateAccess: window.CBWPageConfig.has_design_template_access,
				pageTitle: window.CBWPageConfig.page_title,
				pageTitleActive: window.CBWPageConfig.page_title_active,
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

			if ( this.isActivated && ! this.templatesAllowed() ) {
				this.installationType = 'plugins';
			}

		},
		computed: {
			startLocked: function() {
				if ( this.isActivated ) {
					return null === this.installationType;
				} else {
					return null === this.licenseKey;
				}
			},
			buttonLabel: function() {
				var label = window.CBWPageConfig.button_label;

				if ( this.isActivated ) {
					if ( ! this.startLocked ) {
						label = window.CBWPageConfig.ready_button_label;
					} else {
						label = window.CBWPageConfig.select_type_button_label;
					}

				}

				return label;
			},
			currentPageTitle: function() {

				var title = this.pageTitle;

				if ( this.isActivated ) {
					title = this.pageTitleActive;
				}

				return title;

			}
		},
		methods: {
			templatesAllowed: function() {
				if ( this.hasTemplateAccess || this.hasDesignTemplateAccess ) {
					return true;
				} else {
					return false;
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

						self.success = true;

						if ( response.data.has_template_access ) {
							self.loading = false;
							self.isActivated = true;
						} else {
							self.successMessage = response.data.message;
							window.location = window.CBWPageConfig['redirect_plugins'];
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