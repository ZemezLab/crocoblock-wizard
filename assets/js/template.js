(function () {

	"use strict";

	Vue.component( 'cbw-free-templates', {
		template: '#cbw_free_templates',
		data: function() {
			return {
				activeTab: 'home-pages',
				items: window.CBWPageConfig.templates,
				tabs: window.CBWPageConfig.tabs,
			};
		},
		methods: {
			goBack: function() {
				window.location = window.CBWPageConfig.main_page;
			},
			onStartInstall: function() {
				this.$emit( 'change-body', 'cbw-import-template' );
			},
			templatesByTabs: function( tab ) {

				var result = {};

				for ( var slug in this.items ) {
					if ( this.items[ slug ].tab === tab ) {
						result[ slug ] = this.items[ slug ];
					}
				}

				return result;
			}
		}
	} );

	Vue.component( 'cbw-import-template', {
		template: '#cbw_import_template',
		data: function() {
			return {
				result: {
					type: '',
					message: 'default',
				},
				pageTitle: '',
				template: {},
				importType: 'jet',
				loading: {
					template: false,
					page: false,
				},
				resultURL: {
					template: null,
					page: null,
				},
				imported: {
					template: false,
					page: false,
				},
				buttons: {
					template: window.CBWPageConfig.template_button,
					page: window.CBWPageConfig.page_button,
				}
			};
		},
		mounted: function() {
			this.template   = window.CBWPageConfig.templateToImport.template;
			this.importType = window.CBWPageConfig.templateToImport.type;
		},
		methods: {
			importTemplate: function() {
				this.importItem( 'template' );
			},
			createPage: function() {
				this.importItem( 'page' );
			},
			importItem: function( type ) {

				var self = this,
					templateUrl,
					title;

				templateUrl          = self.template.urls[ self.importType ];
				self.loading[ type ] = true;

				if ( self.imported[ type ] && self.resultURL[ type ] ) {
					window.location = self.resultURL[ type ];
					return;
				}

				if ( 'page' === type ) {
					title = self.pageTitle;
				} else {
					title = false;
				}

				jQuery.ajax({
					url: ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: {
						action: window.CBWPageConfig.action_mask.replace( /%module%/, window.CBWPageConfig.module ),
						handler: 'import_' + type,
						url: templateUrl,
						title: title,
						nonce: window.CBWPageConfig.nonce,
					},
				}).done( function( response ) {

					if ( ! response ) {
						self.result.type     = 'error';
						self.result.message  = 'Empty response';
						self.$set( self.loading, type, false );
						return;
					}

					if ( ! response.success ) {
						self.result.type     = 'error';
						self.result.message  = response.data.message;
						self.$set( self.loading, type, false );
					} else {

						self.result.type       = 'success';
						self.result.message    = response.data.message;
						self.$set( self.loading, type, false );
						self.$set( self.buttons, type, response.data.button_label );
						self.$set( self.imported, type, true );
						self.$set( self.resultURL, type, response.data.url );

					}

				} ).fail( function( xhr, textStatus, error ) {

					self.result.type     = 'error';
					self.result.message  = error;
					self.$set( self.loading, type, false );

				} );

			},
			goBack: function() {
				window.CBWPageConfig.templateToImport = {};
				this.$emit( 'change-body', 'cbw-free-templates' );
			}
		},
	} );

	Vue.component( 'cbw-template', {
		template: '#cbw_template',
		props: {
			template: {
				type: Object,
				default: function() {
					return {};
				}
			},
			slug: {
				type: String,
				default: '',
			},
		},
		data: function() {
			return {
				loading: false,
				isPreview: false,
				previewTimeout: null,
				importType: false,
			};
		},
		methods: {
			clearPreview: function() {
				if ( this.previewTimeout ) {
					clearTimeout( this.previewTimeout );
					this.isPreview = false;
				}
			},
			showPreview: function() {

				var self = this;

				if ( self.previewTimeout ) {
					clearTimeout( self.previewTimeout );
				}

				self.previewTimeout = setTimeout( function() {
					self.isPreview = true;
				}, 100 );

			},
			startInstall: function() {

				window.CBWPageConfig.templateToImport = {
					template: this.template,
					type: this.importType,
				};

				this.$emit( 'start-install' );
			}
		}
	} );

})();